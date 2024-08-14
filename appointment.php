<?php
session_start();

$userLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'];

if (!$userLoggedIn) {
    echo '<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Clinic Ease</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<link rel="stylesheet" href="style.css">
<link rel="icon" type="image/x-icon" href="image/iconlogo.png">
<script src="https://kit.fontawesome.com/882757363d.js" crossorigin="anonymous"></script>
<style>
    .popup {
        display: flex;
        position: fixed;
        z-index: 9;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        padding: 10px;
        background-color: white;
        box-shadow: 0px 0px 10px rgba(0,0,0,0.6);
        border-radius: 5px;
        flex-direction: column;
        align-items: center;
    }
    .popup-content {
        padding: 20px;
        border-radius: 5px;
    }
    .popup-button {
        background-color: #10a1da;
        color: white;
        padding: 6px 15px;
        text-align: center;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        font-size: 0.8125rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        font-family: "Exo", sans-serif;
        text-transform: uppercase;
        border-radius: 40px;
        border: 2px solid transparent;
    }
    @media (max-width: 768px) {
        .popup {
            width: 95%;
            padding: 5px;
        }
        .popup-content {
            padding: 10px;
        }
        .popup-button {
            padding: 5px 10px;
            font-size: 14px;
        }
    }
</style>
</head>
<body>
<div class="popup">
    <div class="popup-content">
        <p>You are not signed in yet.</p>
        <button class="popup-button" onclick="window.location.href=\'login.php\'">Sign in</button>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var ad = document.querySelector(\'a[href="https://www.000webhost.com/?utm_source=000webhostapp&utm_campaign=000_logo&utm_medium=website&utm_content=footer_img"]\');
    if (ad) {
        ad.parentNode.removeChild(ad);
    }
});
</script>
</body>
</html>';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require('header.php'); ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Ease</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/882757363d.js" crossorigin="anonymous"></script>
        <link rel="icon" type="image/x-icon" href="image/iconlogo.png">
    <style>
        .popup {
            display: none;
            position: fixed;
            z-index: 9;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            padding: 10px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.6);
            border-radius: 5px;
        }
        .popup-content {
            padding: 20px;
            border-radius: 5px;
        }
        .close {
            color: #7a7b7e;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
        .popup-button {
            background-color: #10a1da;
            color: white;
            padding: 6px 15px;
            text-align: center;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
            font-size: 0.8125rem;
            font-weight: 700;
            letter-spacing: 0.5px;
            font-family: "Exo", sans-serif;
            text-transform: uppercase;
            border-radius: 40px;
            border: 2px solid transparent;
        }
        @media (max-width: 768px) {
            .popup {
                width: 95%;
                padding: 5px;
            }
            .popup-content {
                padding: 10px;
            }
            .close {
                font-size: 24px;
            }
            .popup-button {
                padding: 5px 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">Book your Seat</span>
          <h1 class="text-capitalize mb-5 text-lg">Appointment</h1>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="appoinment section">
  <div class="container">
    <div class="row">
      <div class="col-lg-4">
          <div class="mt-3">
            <div class="feature-icon mb-3">
            <i class="fa-solid fa-headset"></i>
            </div>
             <span class="h3">Call for an Emergency Service!</span>
              <h2 class="text-color mt-3">05-807 711</h2>
          </div>
          <div class="col-lg-15">
                <div class="sidebar-widget  gray-bg p-4">
                    <h5 class="mb-4">Make Appointment</h5>
                        <?php
                            include("connect.php");
                            $sql = "SELECT days, openingtime, closingtime, status FROM Schedule";
                            $result = $conn->query($sql);
                            $hours = [];
                            $closed_days = [];
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $day = $row['days'];
                                    if ($row['status'] == 'Open') {
                                        $openingtime = date("g:i A", strtotime($row['openingtime'])); 
                                        $closingtime = date("g:i A", strtotime($row['closingtime'])); 
                                        $hours[$openingtime . ' - ' . $closingtime][] = $day;
                                    } else {
                                        $closed_days[] = $day;
                                    }
                                }
                            } else {
                                echo 'No schedule data available';
                                exit;
                            }
                            $conn->close();
                            echo '<ul class="list-unstyled lh-35">';
                            foreach ($hours as $time => $days) {
                                echo '<li class="d-flex justify-content-between align-items-center">';
                                if (count($days) > 1) {
                                    echo '<span>' . implode(' to ', [reset($days), end($days)]) . ' </span>';
                                } else {
                                    echo '<span>' . reset($days) . '  </span>';
                                }
                                echo '<span>' . $time . '</span>';
                                echo '</li>';
                            }
                            foreach ($closed_days as $day) {
                                echo '<li class="d-flex justify-content-between align-items-center">';
                                echo '<span>' . $day . '</span>';
                                echo '<span>Closed</span>';
                                echo '</li>';
                            }
                            echo '</ul>';
                        ?>
                </div>
            </div>
      </div>
      <div class="col-lg-8">
           <div class="appoinment-wrap mt-5 mt-lg-0 pl-lg-5">
            <h2 class="mb-2 title-color">Book an Appointment</h2>
            <form id="appointment">
    <div class="form-group">
        <label for="patientid">Patient Id:</label>
        <input type="text" id="patient-id" name="patientid" class="form-control" value="<?php echo htmlspecialchars($_SESSION['patientid']); ?>" readonly required>
    </div>

    <div class="form-group">
        <label for="clinic-name">Clinic's Name:</label>
        <select id="clinic-name" name="clinicname" class="form-control" required>
            <option value="">Select Clinic</option>
        </select>
    </div>

    <div class="form-group">
        <label for="specialization">Specialization:</label>
        <select id="specialization" name="specialization" class="form-control" required>
            <option value="">Select Specialization</option>
        </select>
    </div>

    <div class="form-group">
        <label for="doctor-name">Doctor's Name:</label>
        <select id="doctor-name" name="doctorname" class="form-control" required>
            <option value="">Select Doctor</option>
        </select>
    </div>

    <div class="form-group">
        <label for="procedure-name">Procedure:</label>
        <select id="procedure-name" name="procedurename" class="form-control" required>
            <option value="">Select Procedure</option>
        </select>
    </div>

    <div class="form-group">
        <label for="appointment-date">Appointment Date:</label>
        <input type="date" id="appointment-date" name="appointmentdate" class="form-control" required>
    </div>
    <div id="available-times">
        <ul id="times-list">
        </ul>
    </div> 
    <div class="form-group">
        <label for="appointment-time">Appointment Time:</label>
        <input type="time" id="appointment-time" name="appointmenttime" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-main btn-round-full">Submit</button>
</form>

<div id="myPopup" class="popup">
    <div class="popup-content">
        <span class="close">&times;</span>
        <p id="popupMessage"></p>
        <button class="popup-button" id="popupButton">OK</button>
    </div>
</div>

        </div>
        <div id="time"></div>
    </div>
</section>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    const today = new Date().toISOString().split('T')[0];
    $('#appointment-date').attr('min', today);

    $('#appointment-time').attr('step', 60); 

    $.get('your_appointment_handler.php', function(data) {
        $('#time').text(data);
    });

    $.getJSON('view.php', { type: 'clinics' }, function(data) {
        if (data.clinics) {
            var clinicSelect = $('#clinic-name');
            clinicSelect.empty().append('<option>Select Clinic</option>');
            $.each(data.clinics, function(index, clinic) {
                clinicSelect.append('<option value="' + clinic + '">' + clinic + '</option>');
            });
        }
    });

    $('#clinic-name').change(function() {
        var clinic = $(this).val();
        $.getJSON('view.php', { type: 'specializations', clinic: clinic }, function(data) {
            if (data.specializations) {
                var specializationSelect = $('#specialization');
                specializationSelect.empty().append('<option>Select Specialization</option>');
                $.each(data.specializations, function(index, specialization) {
                    specializationSelect.append('<option value="' + specialization + '">' + specialization + '</option>');
                });
            }
        });
    });

    $('#specialization').change(function() {
        var specialization = $(this).val();
        $.getJSON('view.php', { type: 'doctors', speciality: specialization }, function(data) {
            if (data.doctors) {
                var doctorSelect = $('#doctor-name');
                doctorSelect.empty().append('<option>Select Doctor</option>');
                $.each(data.doctors, function(index, doctor) {
                    doctorSelect.append('<option value="' + doctor + '">' + doctor + '</option>');
                });
            }
        });

        $.getJSON('view.php', { type: 'procedures', speciality: specialization }, function(data) {
            if (data.procedures) {
                var procedureSelect = $('#procedure-name');
                procedureSelect.empty().append('<option value="">Select Procedure</option>');
                $.each(data.procedures, function(index, procedure) {
                    procedureSelect.append('<option value="' + procedure.name + '" data-duration="' + procedure.duration + '">' + procedure.name + ' (' + procedure.duration + ' mins)</option>');
                });
            }
        });
    });

    document.getElementById('appointment').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch('your_appointment_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            showPopup(data);
        })
        .catch(error => {
            console.error('Error:', error);
            showPopup('An error occurred. Please try again.');
        });
    });

    function showPopup(message) {
        var popup = document.getElementById("myPopup");
        var popupMessage = document.getElementById("popupMessage");
        popupMessage.innerText = message;
        popup.style.display = "block";
    }

    document.querySelector(".close").addEventListener("click", function() {
        document.getElementById("myPopup").style.display = "none";
    });

    document.getElementById("popupButton").addEventListener("click", function() {
        document.getElementById("myPopup").style.display = "none";
    });
});
$(document).ready(function() {
    function fetchAvailableTimes() {
        var doctorName = $('#doctor-name').val();
        var appointmentDate = $('#appointment-date').val();

        if (doctorName && appointmentDate) {
            console.log("Doctor Name:", doctorName); 
            console.log("Appointment Date:", appointmentDate); 

            $.post('get_available_times.php', { doctorname: doctorName, appointmentdate: appointmentDate }, function(data) {
                console.log("Response:", data);
                $('#times-list').html(''); 
                var times;
                try {
                    times = JSON.parse(data);
                } catch (e) {
                    console.error("Error parsing JSON:", e);
                    $('#times-list').append('<li>There was an error retrieving available times</li>');
                    return;
                }
                if (times.length === 0) {
                    $('#times-list').append('<li>No available times</li>');
                } else {
                    times.forEach(function(time) {
                        $('#times-list').append('<li>Available Time: ' + time + '</li>');
                    });
                }
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error("Error:", textStatus, errorThrown); 
                $('#times-list').append('<li>There was an error retrieving available times</li>');
            });
        }
    }

    $('#doctor-name').change(fetchAvailableTimes);
    $('#appointment-date').change(fetchAvailableTimes);
});


</script>
<script src="script.js"></script>
<?php require('footer.php'); ?>
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
