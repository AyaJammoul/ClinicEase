<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$userDetails = [
    'patientname' => 'Guest',
    'mobilenumber' => 'N/A'
];

if ($userLoggedIn = isset($_SESSION['patientname']) && isset($_SESSION['mobilenumber'])) {
    $userDetails = [
        'patientname' => $_SESSION['patientname'],
        'mobilenumber' => $_SESSION['mobilenumber']
    ];

    include("connect.php");

    $sql = "SELECT * FROM Patientreg WHERE patientname = ? AND mobilenumber = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $userDetails['patientname'], $userDetails['mobilenumber']);
    $stmt->execute();
    $result = $stmt->get_result();
    $fullUserDetails = $result->fetch_assoc();
    $stmt->close();
    $conn->close();
} else {
    $fullUserDetails = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Ease</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/882757363d.js" crossorigin="anonymous"></script>
    <style>
        .account-info {
            display: none;
            position: absolute;
            background: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            z-index: 1000;
        }
        .popup-button {
            background-color: #10a1da; 
            color: white;
            padding: 6px 15px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 15px;
            cursor: pointer;
            font-size: 0.8125rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            font-family: "Exo", sans-serif;
            text-transform: uppercase;
            border-radius: 40px;
            border: 2px solid transparent;
        }
        .account:hover .account-info {
            display: block;
        }
        .spinner {
            display: none;
            margin-left: 10px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #3498db;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        #account-popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            z-index: 1001;
            max-height: 80%;
            width: 80%;
            max-width: 400px;
            overflow-y: auto;
        }
        .account-popup-content {
            max-height: 60vh;
            overflow-y: auto;
        }
    </style>
</head>
<body id="top">
<header>
    <div class="header-top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <ul class="top-bar-info list-inline-item pl-0 mb-0">
                        <li class="list-inline-item"><a href="mailto:final2024project2024@gmail.com"><i class="fa-regular fa-envelope"></i> ClinicEase@gmail.com</a></li>
                        <li class="list-inline-item"><i class="fa-solid fa-location-dot"></i> Islamic University of Lebanon, Wardaniyeh</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <div class="text-lg-right top-right-bar mt-2 mt-lg-0">
                        <a href="tel:+23-345-67890">
                            <span>Call Now : </span>
                            <a href="tel:05807711"><span class="h4">05-807 711</span></a>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navigation" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="image/logo1.png" alt="" class="img-fluid" style="width: 14%;padding:0">
                <img src="image/logo2.png" alt="" class="img-fluid" style="width: 24%;padding:0">
            </a>

            <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarmain"
                aria-controls="navbarmain" aria-expanded="false" aria-label="Toggle navigation">
                <span class="fa-solid fa-bars"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarmain">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="department.php">Department</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="doctor.php" id="dropdown03" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">Doctors<i class="fa-solid fa-chevron-down"></i></a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown03">
                            <li><a class="dropdown-item" href="doctor.php">Doctors</a></li>
                            <li><a class="dropdown-item" href="appointment.php">Appointment</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <li class="nav-item">
                        <div class="account" onmouseover="showPopup()" onmouseout="hidePopup()" onclick="clickPopup()">
                            <a class="nav-link"><i class="fa-solid fa-user"></i>
                                <div class="account-info">
                                    <p>Name: <?php echo htmlspecialchars($userDetails['patientname']); ?></p>
                                    <p>Phone: <?php echo htmlspecialchars($userDetails['mobilenumber']); ?></p>
                                </div>
                            </a>
                        </div>
                    </li>
                    <?php if ($userLoggedIn) : ?>
                    <li class="nav-item"> <a class="nav-link" onclick="logout()"><i class="fa-solid fa-right-from-bracket"></i></a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div id="account-popup">
    <div class="account-popup-content">
        <?php if (!empty($fullUserDetails)) : ?>
            <form id="edit-form" method="POST">
                <p>Name: <input type="text" name="patientname" value="<?php echo htmlspecialchars($fullUserDetails['patientname']); ?>" class="editable" readonly></p>
                <p>Home Number: <input type="text" name="homenumber" value="<?php echo htmlspecialchars($fullUserDetails['homenumber']); ?>" class="editable" readonly></p>
                <p>Phone: <input type="text" name="mobilenumber" value="<?php echo htmlspecialchars($fullUserDetails['mobilenumber']); ?>" class="editable" readonly></p>
                <p>Address: <input type="text" name="address" value="<?php echo htmlspecialchars($fullUserDetails['address']); ?>" class="editable" readonly></p>
                <p>Email: <input type="text" name="email" value="<?php echo htmlspecialchars($fullUserDetails['email']); ?>" class="editable" readonly></p>
                <p>Birthdate: <input type="text" name="birthdate" value="<?php echo htmlspecialchars($fullUserDetails['birthdate']); ?>" class="editable" readonly></p>
                <p>Sex: 
                    <select name="sex" class="editable" readonly disabled>
                        <option value="Male" <?php echo ($fullUserDetails['sex'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($fullUserDetails['sex'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                    </select>
                </p>
                <p>Social Security: <input type="text" name="socialsecurity" value="<?php echo htmlspecialchars($fullUserDetails['socialsecurity']); ?>" class="editable" readonly></p>
                <p>Marital Status: 
                    <select name="maritalstatus" class="editable" readonly disabled>
                        <option value="Single" <?php echo ($fullUserDetails['maritalstatus'] == 'Single') ? 'selected' : ''; ?>>Single</option>
                        <option value="Married" <?php echo ($fullUserDetails['maritalstatus'] == 'Married') ? 'selected' : ''; ?>>Married</option>
                    </select>
                </p>
                <button class="popup-button" type="button" id="edit-btn" onclick="enableEditing()">Edit</button>
                <button class="popup-button" type="button" id="save-btn" onclick="saveDetails()" style="display:none;">Save</button>
                <div class="spinner" id="spinner"></div>
            </form>
        <?php else : ?>
            <p>No user details found.</p>
            <a href="login.php"><button class="popup-button">Sign In</button></a>
        <?php endif; ?>
        <button class="popup-button" onclick="clickPopup()">Close</button>
    </div>
</div>

<script>
    function clickPopup() {
        var popup = document.getElementById('account-popup');
        if (popup.style.display === 'block') {
            popup.style.display = 'none';
            location.reload();
        } else {
            popup.style.display = 'block';
        }
    }

    function showPopup() {
        document.querySelector('.account-info').style.display = 'block';
    }

    function hidePopup() {
        document.querySelector('.account-info').style.display = 'none';
    }

    function enableEditing() {
        document.querySelectorAll('.editable').forEach(el => {
            el.removeAttribute('readonly');
            el.removeAttribute('disabled');
        });
        document.getElementById('edit-btn').style.display = 'none';
        document.getElementById('save-btn').style.display = 'inline-block';
    }

    function saveDetails() {
        document.getElementById('spinner').style.display = 'inline-block';
        const formData = new FormData(document.getElementById('edit-form'));
        fetch('update_details.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            document.getElementById('spinner').style.display = 'none';
            if (data.trim() === 'success') {
                alert('Details updated successfully.');
                updateUI(formData);
                document.getElementById('account-popup').style.display = 'none';
                location.reload();
            } else {
                console.error('Failed to update details:', data);
                alert('Failed to update details: ' + data);
            }
        })
        .catch(error => {
            document.getElementById('spinner').style.display = 'none';
            console.error('Error:', error);
            alert('Failed to update details. Please try again.');
        });
    }

    function updateUI(formData) {
        document.querySelector('.account-info p:nth-child(1)').textContent = 'Name: ' + formData.get('patientname');
        document.querySelector('.account-info p:nth-child(2)').textContent = 'Phone: ' + formData.get('mobilenumber');
    }

    function logout() {
        window.location.href = 'logout.php';
    }

    document.addEventListener("DOMContentLoaded", function() {
        var ad = document.querySelector('a[href="https://www.000webhost.com/?utm_source=000webhostapp&utm_campaign=000_logo&utm_medium=website&utm_content=footer_img"]');
        if (ad) {
            ad.parentNode.removeChild(ad);
        }
    });
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
