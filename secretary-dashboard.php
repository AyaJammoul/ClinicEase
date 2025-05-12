<?php
include("connect.php"); 

session_start();

if (!isset($_SESSION['secretary_logged_in']) || $_SESSION['secretary_logged_in'] !== true) {
    header("Location: secretary-login.php");
    exit();
}

$clinicname = $_SESSION['clinicname'];

$sql = "SELECT DISTINCT p.*
        FROM Patientreg p
        INNER JOIN Appointment a ON p.patientid = a.patientid
        WHERE a.clinicname = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $clinicname);
$stmt->execute();
$result = $stmt->get_result();

$patients = [];

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
      $patients[] = $row;
  }
} else {
  echo "0 results";
}

$stmt->close();


$today = date('Y-m-d');

$sql0 = "SELECT a.appid, a.patientid, p.patientname, a.appdate, a.procedurename, a.apptime, d.doctorname
        FROM Appointment a
        INNER JOIN Patientreg p ON a.patientid = p.patientid
        INNER JOIN Doctor d ON a.doctorid = d.doctorid
        WHERE a.clinicname = ? AND DATE(a.appdate) = ? AND a.status='S'";

$stmt0 = $conn->prepare($sql0);
$stmt0->bind_param("ss", $clinicname, $today);
$stmt0->execute();
$result0 = $stmt0->get_result();

$appointments = [];

if ($result0->num_rows > 0) {
    while ($row0 = $result0->fetch_assoc()) {
        $appointments[] = $row0;
    }
} else {
    echo "0 results";
}

$stmt0->close();


$sqlDoctor = "SELECT COUNT(*) AS total FROM Doctor WHERE clinicname = ?";
$stmtDoctor = $conn->prepare($sqlDoctor);
$stmtDoctor->bind_param("s", $clinicname);
$stmtDoctor->execute();
$resultDoctor = $stmtDoctor->get_result();
$rowDoctor = $resultDoctor->fetch_assoc();
$totalDoctors = $rowDoctor['total'];


$sqlNurse = "SELECT COUNT(*) AS total FROM Nurse WHERE clinicname = ?";
$stmtNurse = $conn->prepare($sqlNurse);
$stmtNurse->bind_param("s", $clinicname);
$stmtNurse->execute();
$resultNurse = $stmtNurse->get_result();
$rowNurse = $resultNurse->fetch_assoc();
$totalNurses = $rowNurse['total'];


$sqlPatientreg = "SELECT COUNT(DISTINCT p.patientid) AS total 
                  FROM Patientreg p
                  INNER JOIN Appointment a ON p.patientid = a.patientid
                  WHERE a.clinicname = ?";
$stmtPatientreg = $conn->prepare($sqlPatientreg);
$stmtPatientreg->bind_param("s", $clinicname);
$stmtPatientreg->execute();
$resultPatientreg = $stmtPatientreg->get_result();
$rowPatientreg = $resultPatientreg->fetch_assoc();
$totalPatients = $rowPatientreg['total'];

$stmtDoctor->close();
$stmtNurse->close();
$stmtPatientreg->close();


$sql1 = "SELECT * FROM Schedule";
$result1 = $conn->query($sql1);

$schedules = []; 

if ($result1->num_rows > 0) {
  while($row1= $result1->fetch_assoc()) {
      $schedules[] = $row1; 
  }
} else {
  echo "0 results";
}


$sql2 = "SELECT * FROM Holidays";
$result2 = $conn->query($sql2);

$holidays = []; 

if ($result2->num_rows > 0) {
  while($row2= $result2->fetch_assoc()) {
      $holidays[] = $row2; 
  }
} else {
  echo "0 results";
}


$secretary_username = $_SESSION['secretary_username'];

$queryprofile = "SELECT username, password FROM Staff WHERE username = ? AND typeid = '4' AND clinicname = ?";
$stmtprofile = $conn->prepare($queryprofile);
$stmtprofile->bind_param("ss", $secretary_username, $clinicname);
$stmtprofile->execute();
$resultprofile = $stmtprofile->get_result();

if ($resultprofile->num_rows > 0) {
    $secretaryinfo = $resultprofile->fetch_assoc();
} else {
    echo "No results found.";
    $conn->close();
    exit();
}


$sql3 = "SELECT DISTINCT speciality FROM Availableprocedures WHERE clinicname='$clinicname'";
$result3 = $conn->query($sql3);

$appointmentspeciality = []; 

if ($result3->num_rows > 0) {
  while($row3 = $result3->fetch_assoc()) {
      $appointmentspeciality[] = $row3['speciality']; 
  }
} else {
  echo "0 results";
}



$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secretary Dashboard</title>
	<link rel="icon" type="image/x-icon" href="uploads/anotherlogopng.png">
	<link rel="stylesheet" href="dashboardcss.css">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
</head>
<style>
 <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        td, th {
            padding: 10px;
            text-align: left;
            vertical-align: middle;
        }

        td img {
            vertical-align: middle;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>

</style>
<body>
	<section id="sidebar">
		<a href="#" class="brand">
			<img src="uploads/anotherlogopng.png" style="margin-top: 20px;height: 70px;width: 70px;">
			<span id="dynamic-title"><?php echo "$clinicname"?></span>
		</a>
		<ul class="side-menu top">
			<li class="active" id="dashboard">
				<a href="#">
					<img src="uploads/dashboard.png" class="icon">
					<span class="text">Dashboard</span>
				</a>
			</li>
			<li id="patients">
				<a href="#" onclick="showSection('patients-content')">
					<img src="uploads/patient.png" class="icon">
					<span class="text">Patients</span>
				</a>
			</li>
            <li id="appointments">
				<a href="#" onclick="showSection('appointments-content')">
					<img src="uploads/appointment.png" class="icon">
					<span class="text">Appointments</span>
				</a>
			</li>
		</ul>
		<ul class="side-menu top">
			<li id="settings">
				<a href="#" onclick="showSection('settings-content')">
					<img src="uploads/gear.png" class="icon">
					<span class="text">Set Up</span>
				</a>
			</li>
			<li id="logout">
				<a href="#" onclick="showSection('logout-content')">
					<img src="uploads/shutdown.png" class="icon">
					<span class="text">Logout</span>
				</a>
			</li>
		</ul>
	</section>
	<section id="content">
		<nav>
			<img src="uploads/list.png" class="icon bx bx-menu">
			<form action="#">
				<div class="form-input">
				   
				</div>
			</form>
			<input type="checkbox" id="switch-mode" hidden>
			<label for="switch-mode" class="switch-mode"></label>
			<a href="#" class="profile">
				<img src="uploads/profile.png" style="height: 45px; width: 45px;" id="profileBtn">
			</a>
			<div id="profileModal" class="modal">
              <div class="modal-content">
               <span class="close" id="profileclose">&times;</span>
                <img src="uploads/profile.png" style="height: 150px;">
                <p>Username: <?php echo htmlspecialchars($secretaryinfo['username']); ?></p>
                <p>Password: <?php echo htmlspecialchars($secretaryinfo['password']); ?></p>
                <button type="submit" style="font-size: 10px;padding: 6px 16px;color: var(--light);border-radius: 20px;font-weight: 700;background: var(--blue);cursor: pointer;" id="profileeditBtn">Edit</button>
              </div>
            </div>
            <script src="profileview.js"></script>
            <div id="profileeditModal" class="modal">
    <div class="modal-content">
        <span class="close" id="profileditclose">&times;</span>
        <form id="profileeditForm" method="post">
            <label for="updatedusername">New Username:</label>
            <input type="text" id="updatedusername" value="<?php echo $secretaryinfo['username']; ?>" name="username" required><br><br>
            <label for="updatedpassword">New Password:</label>
            <input type="password" id="updatedpassword" value="<?php echo $secretaryinfo['password']; ?>" name="password" required><br><br>
            <button type="submit" style="font-size: 10px; padding: 6px 16px; color: var(--light); border-radius: 20px; font-weight: 700; background: var(--blue); cursor: pointer;">Submit</button>
        </form>
    </div>
</div>
<script src="editprofilesecretarypopup.js"></script>
		</nav>
		<main>
			<section id="dashboard-content">
			<div class="head-title">
				<div class="left">
					<h1>Dashboard</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Dashboard</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Home</a>
						</li>
					</ul>
				</div>
			</div>
			<ul class="box-info">
				<li>
					<img src="uploads/infodoctor.png" class="infoicon">
					<span class="text">
						<h3><?php echo $totalDoctors; ?></h3>
						<p>Doctors</p>
					</span>
				</li>
				<li>
					<img src="uploads/infonurse.png" class="infoicon">
					<span class="text" >
						<h3><?php echo $totalNurses; ?></h3>
						<p>Nurses</p>
					</span>
				</li>
				<li>
					<img src="uploads/infopatient.png" class="infoicon">
					<span class="text">
						<h3><?php echo $totalPatients; ?></h3>
						<p>Patients</p>
					</span>
				</li>
			</ul>
			<div class="table-data">
                <div class="order">
					<div class="head">
						<h3>Appointments For Today</h3>
					</div>
					<table>
    <thead>
        <tr>
            <th>Appointment ID</th>
            <th>Patient ID</th>
            <th>Patient Name</th>
            <th>Doctor Name</th>
            <th>Appointment Date</th>
            <th>Procedure</th>
            <th>Appointment Time</th>
            <th> </th>
            <th> </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($appointments as $appointment) { ?>
            <tr>
                <td style="vertical-align: middle;"><?php echo $appointment['appid']; ?></td>
                <td style="vertical-align: middle;"><?php echo $appointment['patientid']; ?></td>
                <td style="vertical-align: middle;"><?php echo $appointment['patientname']; ?></td>
                <td style="vertical-align: middle;"><?php echo $appointment['doctorname']; ?></td>
                <td style="vertical-align: middle;"><?php echo $appointment['appdate']; ?></td>
                <td style="vertical-align: middle;"><?php echo $appointment['procedurename']; ?></td>
                <td style="vertical-align: middle;"><?php echo $appointment['apptime']; ?></td>
                <td><span class='status edit endappointmentdashboard' data-appid="<?php echo $appointment['appid']; ?>" style='margin-left:30px;cursor: pointer;'>End</span></td>
                <td><span class='status absent absentappointmentdashboard' data-appid="<?php echo $appointment['appid']; ?>" style='margin-left:30px;cursor: pointer;'>Absent</span></td>
                <td><span class='status delete cancelappointmentdashboard' data-appid="<?php echo $appointment['appid']; ?>" style='margin-left:30px;cursor: pointer;'>Cancel</span></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<script src="endappointmentdashboardpopup.js"></script>
<script src="absentappointmentdashboardpopup.js"></script>
<script src="cancelappointmentdashboardpopup.js"></script>
				</div>  
                <div class="order">
					<div class="head">
						<h3>Patients</h3>
					</div>
					<table>
						<thead>
							<tr>
                                <th>Patient ID</th>
								<th>Name</th>
								<th>Birthdate</th>
								<th>Sex</th>
								<th>Marital Status</th>
							</tr>
						</thead>
						<tbody>
        <?php foreach ($patients as $patient) { ?>
            <tr>
                <td style="vertical-align: middle;"><?php echo $patient['patientid']; ?></td>
                <td style="vertical-align: middle;"><?php echo $patient['patientname']; ?></td>
                <td style="vertical-align: middle;"><?php echo $patient['birthdate']; ?></td>
                <td style="vertical-align: middle;"><?php echo $patient['sex']; ?></td>
                <td style="vertical-align: middle;"><?php echo $patient['maritalstatus']; ?></td>
            </tr>
        <?php } ?>

    </tbody>
					</table>
				</div>
			</div>
		 </section>
		 <section id="patients-content" style="display:none;">
			<div class="head-title">
				<div class="left">
					<h1>Patients</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Patients</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Home</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Patients</h3>
            <i class='bx bx-plus' id="addPatientBtn"></i>
            <div id="patientModal" class="modal">
              <div class="modal-content">
               <span class="close" id="patclose">&times;</span>
               <form id="patientForm" action="insertPatient.php" method="POST">
                <label for="patientname">Patient Name:</label>
                <input type="text" id="patientname" name="patientname" required><br><br>
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required><br><br>
                <label for="homenumber">Home number:</label>
                <input type="text" id="homenumber" name="homenumber" required><br><br>
                <label for="mobilenumber">Mobile number:</label>
                <input type="text" id="mobilenumber" name="mobilenumber" required><br><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br><br>
                <label for="birthdate">Birth date:</label>
                <input type="date" id="birthdate" name="birthdate" required><br><br>
                <label for="sex">Sex:</label>
                <select id="sex" name="sex">
                  <option value="Female">Female</option>
                  <option value="Male">Male</option>
                </select><br><br>
                <label for="socialsecurity">Social security:</label>
                <input type="text" id="socialsecurity" name="socialsecurity" required><br><br>
                <label for="maritalstatus">Marital Status:</label>
                <select id="maritalstatus" name="maritalstatus">
                  <option value="Single">Single</option>
                  <option value="Married">Married</option>
                </select><br><br>
                <input type="submit" value="Submit" style="font-size: 10px;padding: 6px 16px;color: var(--light);border-radius: 20px;font-weight: 700;background: var(--blue);cursor: pointer;">
               </form>
              </div>
            </div>
            <script src="insertpatientpopup.js"></script>
            <input style="border-radius: 30px 30px 30px 30px;" type="text" class="form-control" id="live_search_patients" placeholder="Search Patients..." autocomplete="off">
            <i id="sortalphabetpatient" class='bx bx-filter'></i>
            <script src="sortalphabetpatient.js"></script>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Patient ID</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Home Number</th>
                    <th>Mobile Number</th>
                    <th>Email</th>
                    <th>Social Security</th>
                    <th>Register Date</th>
                    <th> </th>
                    <th> </th>
                    <th> </th>
                </tr>
            </thead>
            <tbody id="searchresult_patients">
            </tbody>
        </table>
        <div id="editPatientModal" class="modal">
              <div class="modal-content">
            <span id="editpatientclose" class="close">&times;</span>
            <form id="editPatientForm">
                <input type="hidden" id="editPatientId">
                <label for="editPatientName">Name:</label>
                <input type="text" id="editPatientName" name="editPatientName"><br>
                <label for="editPatientAddress">Address:</label>
                <input type="text" id="editPatientAddress" name="editPatientAddress"><br>
                <label for="editPatientHomenumber">Home Number:</label>
                <input type="text" id="editPatientHomenumber" name="editPatientHomenumber"><br>
                <label for="editPatientMobilenumber">Mobile Number:</label>
                <input type="text" id="editPatientMobilenumber" name="editPatientMobilenumber"><br>
                <label for="editPatientEmail">Email:</label>
                <input type="text" id="editPatientEmail" name="editPatientEmail"><br>
                <label for="editPatientBirthdate">Birthdate:</label>
                <input type="date" id="editPatientBirthdate" name="editPatientBirthdate"><br>
                <label for="editPatientSex">Sex:</label>
                 <select id="editPatientSex" name="editPatientSex">
                  <option value="Female">Female</option>
                  <option value="Male">Male</option>
                 </select><br>
                <label for="editPatientSocialsecurity">Social Security:</label>
                <input type="text" id="editPatientSocialsecurity" name="editPatientSocialsecurity"><br>
                <label for="editPatientMaritalstatus">Marital Status:</label>
                <select id="editPatientMaritalstatus" name="editPatientMaritalstatus">
                  <option value="Single">Single</option>
                  <option value="Married">Married</option>
                </select><br><br>
                <input type="submit" style="font-size: 10px;padding: 6px 16px;color: var(--light);border-radius: 20px;font-weight: 700;background: var(--blue);cursor: pointer;" value="Save Changes">
            </form>
        </div>
       </div>
    </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="editpatientpopup.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $("#live_search_patients").keyup(function(){
        var input = $(this).val();
        if(input != ""){
            $.ajax({
                url: "livesearchpatients.php",
                method: "POST",
                data: {input:input},
                success:function(data){
                    $("#searchresult_patients").html(data);
                }
            });
        } else {
            $("#searchresult_patients").empty();
        }
    });
});
</script>
<script src="editpatientsortpopup.js"></script>
		 </section>
		 <section id="appointments-content" style="display:none;">
			<div class="head-title">
				<div class="left">
					<h1>Appointments</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Appointments</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Home</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="table-data">
    <div class="order">
        <div class="head">
            <h3>Appointments</h3>
            <i class='bx bx-plus' id="addAppointmentBtn"></i>
            <img src="clock.png" id="checktime" style="width:2%"></img>
<div id="appointmentModal" class="modal">
    <div class="modal-content">
        <span class="close" id="appointmentclose">&times;</span>
        <form id="appointmentForm">
            <label for="patient-name">Patient Name:</label>
            <input type="text" id="patient-name" name="patientname" required><br>
            <label for="mobile-number">Mobile Number:</label>
            <input type="number" id="mobile-number" name="mobilenumber" required><br>
            <label for="clinic-name">Clinic's Name:</label>
            <input type="text" id="clinic-name" name="clinicname" value="<?php echo $clinicname; ?>" readonly><br>
            <label for="speciality">Speciality:</label>
            <select id="speciality" name="speciality" onchange="updateDoctors();" required>
               <?php foreach ($appointmentspeciality as $index => $speciality) { ?>
                 <option value="<?php echo $speciality; ?>"><?php echo $speciality; ?></option>
               <?php } ?>
            </select><br>
            <label for="docto-namer">Doctor:</label>
            <select id="doctor-name" name="doctorname" onchange="updateProcedures();" required>
                <option value="">Select Doctor</option>
            </select><br>
            <label for="procedure">Procedure:</label>
            <select id="procedure" name="procedurename" onchange="updateDuration();" required>
                <option value="">Select Procedure</option>
            </select><br>
            <label for="duration">Duration:</label>
            <div id="duration">Select a procedure...</div>
            <label for="appointmentDate">Appointment Date:</label>
            <input type="date" id="appointmentDate" name="appointmentdate" required><br>
            <label for="appointmentTime">Appointment Time:</label>
            <input type="time" id="appointmentTime" name="appointmenttime" required><br>
            <label>Vital Fill:</label>
            <label><input type="radio" name="vitalfill" value="Yes" required> Yes</label>
            <label><input type="radio" name="vitalfill" value="No" required> No</label><br>
            <input type="submit" value="Submit">
        </form>
    </div>
</div>            
<div id="checkTimeModal" class="modal">
        <div class="modal-content">
            <span class="close" id="checkTimeClose">&times;</span>
            <form id="checkTimeForm">
                <label for="checkSpeciality">Speciality:</label>
                <select id="checkSpeciality" name="speciality" onchange="updateDoctor();" required>
                    <?php foreach ($appointmentspeciality as $speciality) { ?>
                        <option value="<?php echo htmlspecialchars($speciality); ?>"><?php echo htmlspecialchars($speciality); ?></option>
                    <?php } ?>
                </select><br>
                <label for="checkDoctorName">Doctor Name:</label>
                <select id="checkDoctorName" name="doctorname" required>
                    <option value="">Select Doctor</option>
                </select><br>
                <label for="checkAppointmentDate">Appointment Date:</label>
                <input type="date" id="checkAppointmentDate" name="appointmentdate" required><br>
                <button type="button" id="getAvailableTimesBtn">Get Available Times</button><br>
                <div id="availableTimes"></div>
            </form>
        </div>
    </div>


    <script src="insertappointmentpopup.js"></script>
            <input style="border-radius: 30px 30px 30px 30px;" type="text" class="form-control" id="live_search_appointments" placeholder="Search by Patients' Name..." autocomplete="off">
            <i id="sortappointment" class='bx bx-filter'></i>
            <script src="sortappointment.js"></script>
        </div>
        <table>
            <thead>
                <tr>
                    <th>App ID</th>
                    <th>Patient ID</th>
                    <th>Patient Name</th>
                    <th>Doctor ID</th>
                    <th>Doctor Name</th>
                    <th>App Date</th>
                    <th>Procedure</th>
                    <th>Duration</th>
                    <th>App Time</th>
                    <th>Date Taken</th>
                    <th>Status</th>
                    <th>Vital Fill</th>
                    <th> </th>
                    <th> </th>
                    <th> </th>
                </tr>
            </thead>
            <tbody id="searchresult_appointments">
            </tbody>
            </table>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="endappointmentpopup.js"></script>
    <script src="cancelappointmentpopup.js"></script>
<script type="text/javascript">
$(document).ready(function(){
    $("#live_search_appointments").keyup(function(){
        var input = $(this).val();
        if(input != ""){
            $.ajax({
                url: "livesearchappointments.php",
                method: "POST",
                data: {input:input},
                success:function(data){
                    $("#searchresult_appointments").html(data);
                }
            });
        } else {
            $("#searchresult_appointments").empty();
        }
    });
});
</script>
<script src="endappointmentsortpopup.js"></script>
<script src="cancelappointmentsortpopup.js"></script>
		 </section>
		 <section id="settings-content" style="display:none;">
			<div class="head-title">
				<div class="left">
					<h1>Set Up</h1>
					<ul class="breadcrumb">
						<li>
							<a href="#">Set Up</a>
						</li>
						<li><i class='bx bx-chevron-right' ></i></li>
						<li>
							<a class="active" href="#">Home</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="table-data">
			<div class="order">
					<div class="head">
						<h3>Schedule</h3>
					</div>
					<table>
						<thead>
							<tr>
								
								<th>Days</th>
								<th>Opening Time</th>
                                <th>Closing Time</th>
                                <th>Status</th>
                                <th> </th>
                                <th> </th>
							</tr>
						</thead>
						<tbody>
						    <?php foreach ($schedules as $schedule) { ?>
                                <tr>
                                    
                                    <td style="vertical-align: middle;margin-top:85px;"><?php echo $schedule['days']; ?></td>
                                    <td style="vertical-align: middle;"><?php echo $schedule['openingtime']; ?></td>
                                    <td style="vertical-align: middle;"><?php echo $schedule['closingtime']; ?></td>
                                    <td style="vertical-align: middle;"><?php echo $schedule['status']; ?></td>
                                </tr>
                            <?php } ?>
						</tbody>
					</table>
					</div>
                   <div class="order">
					<div class="head">
						<h3>Holidays</h3>
					</div>
					<table>
						<thead>
							<tr>
								
								<th>Holiday Date</th>
								<th>Holiday Name</th>
                                <th>Status</th>
                                <th> </th>
                                <th> </th>
							</tr>
						</thead>
						<tbody>
						    <?php foreach ($holidays as $holiday) { ?>
                                <tr>
                                
                                    <td style="vertical-align: middle;"><?php echo $holiday['holidaydate']; ?></td>
                                    <td style="vertical-align: middle;"><?php echo $holiday['holidayname']; ?></td>
                                    <td style="vertical-align: middle;"><?php echo $holiday['status']; ?></td>
                               </tr>
                            <?php } ?>
						</tbody>
					</table>
				</div>
		 </section>
		 <section id="logout-content" style="display:none;">
            <div class="head-title">
                <div class="left">
                    <h1>Logout</h1>
                </div>
            </div>
            <table>		
                <thead>
                    <div id="dynamic-title" style="text-align: center; margin-bottom: 100px;">Are You Sure You Want To Logout?</div>
                </thead>
                <tbody>
                    <tr>
                       <form method="post" action="logoutsecretary.php"> 
                           <button class="btn logout" style="display:block;margin-left:auto;margin-right:auto;width:50%;"><p style="color:white"><b>Logout</b></p></button>
                       </form>
                    </tr>
                </tbody>
            </table>
         </section>
		</main>
	</section>
	<script src="script1.js"></script>
	<script>
  document.addEventListener("DOMContentLoaded", function() {
    var ad = document.querySelector('a[href="https://www.000webhost.com/?utm_source=000webhostapp&utm_campaign=000_logo&utm_medium=website&utm_content=footer_img"]');
    if (ad) {
      ad.parentNode.removeChild(ad);
    }
  });
</script>

</body>
</html>