<?php
session_start();
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['patientname'];
    $phone = $_POST['mobilenumber'];

    $sql = "SELECT * FROM patientreg WHERE patientname = ? AND mobilenumber = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $fullname, $phone);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $_SESSION['user'] = $result->fetch_assoc();
        header("Location: index.php?status=found");
        exit();
    } else {
        header("Location: login.php?status=notfound");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Ease - Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="stylecheck.css">
    <script src="https://kit.fontawesome.com/882757363d.js" crossorigin="anonymous"></script>
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
            text-decoration: none;
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
    <div class="container">
        <div class="left">
            <div class="logo">
                <h1><i class="fa-solid fa-house-chimney-medical"></i> Clinic Ease</h1>
            </div>
            <div class="image">
                <img src="image/file.png" alt="Doctor">
            </div>
        </div>
        <div class="right">
            <div class="form-container">
                <ul class="tabs">
                    <li class="active">Check</li>
                    <li>Registration</li>
                </ul>
                <div class="form-content">
                    <form id="check-form" method="post" class="check-form active">
                        <input type="text" name="fullname" placeholder="Full Name" required>
                        <input type="number" name="phone" placeholder="Phone" required>
                        <button type="submit">Check</button>
                        <div class="existing-account">
                            <p>You don't have an account? <a href="#" id="register-link">Register</a></p>
                        </div>
                        <div id="check-message"></div>
                    </form>
                    <form id="registration-form" action="registration.php" method="post" class="registration-form">
                        <div class="form-group">
                            <label for="regname">Name:</label>
                            <input type="text" id="regname" name="fullname" placeholder="Name" required>
                        </div>
                        <div class="form-group">
                            <label for="regaddress">Address:</label>
                            <input type="text" id="regaddress" name="address" placeholder="Address" required>
                        </div>
                        <div class="form-group">
                            <label for="reghomenumber">Home Number:</label>
                            <input type="number" id="reghomenumber" name="homenumber" placeholder="Home Number">
                        </div>
                        <div class="form-group">
                            <label for="regphone">Mobile Number:</label>
                            <input type="number" id="regphone" name="mobilenumber" placeholder="Mobile Number" required>
                        </div>
                        <div class="form-group">
                            <label for="regemail">Email:</label>
                            <input type="email" id="regemail" name="email" placeholder="Email" required>
                        </div>
                        <div class="form-group">
                            <label for="regbirthdate">Birthdate:</label>
                            <input type="date" id="regbirthdate" name="birthdate" required>
                        </div>
                        <div class="form-group">
                            <label for="regsex">Sex:</label>
                            <select id="regsex" name="sex" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="regssn">Social Security Number:</label>
                            <input type="number" id="regssn" name="socialsecurity" placeholder="ضمان إجتماعي">
                        </div>
                        <div class="form-group">
                            <label for="regmaritalstatus">Marital Status:</label>
                            <select id="regmaritalstatus" name="maritalstatus" required>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Divorced">Divorced</option>
                                <option value="Widowed">Widowed</option>
                            </select>
                        </div>
                        <button type="submit">Register</button>
                        <div class="existing-account">
                            <p>Do you already have an account? <a href="#" id="check-link">Check</a></p>
                        </div>
                        <div id="registration-message"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="myPopup" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <p id="popupMessage"></p>
            <button class="popup-button" id="popupButton">OK</button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tabs = document.querySelectorAll('.tabs li');
            const checkForm = document.querySelector('.check-form');
            const registrationForm = document.querySelector('.registration-form');
            const registerLink = document.getElementById('register-link');
            const checkLink = document.getElementById('check-link');

            tabs.forEach((tab, index) => {
                tab.addEventListener('click', () => {
                    document.querySelector('.tabs .active').classList.remove('active');
                    tab.classList.add('active');

                    if (index === 0) {
                        checkForm.classList.add('active');
                        registrationForm.classList.remove('active');
                    } else {
                        checkForm.classList.remove('active');
                        registrationForm.classList.add('active');
                    }
                });
            });

            registerLink.addEventListener('click', (event) => {
                event.preventDefault();
                tabs[1].click();
            });

            checkLink.addEventListener('click', (event) => {
                event.preventDefault();
                tabs[0].click();
            });

            document.getElementById('check-form').addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                fetch('check.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "not found") {
                        showPopup("You do not have a patient file. Please Register");
                    } else {
                        window.location.href = 'appointment.php';
                    }
                })
                .catch(error => console.error('Error:', error)); 
            });

            document.getElementById('registration-form').addEventListener('submit', function(event) {
                event.preventDefault();
                const formData = new FormData(this);
                fetch('registration.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.text())
                .then(data => {
                    if (data.trim() === "User already exists. Redirecting to check.") {
                        showPopup(data);
                        setTimeout(() => {
                            tabs[0].click();
                        }, 2000);
                    } else if (data.trim() === "registered") {
                        window.location.href = 'appointment.php';
                    } else {
                        showPopup(data);
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        });

        function showPopup(message) {
            const popup = document.getElementById('myPopup');
            const popupMessage = document.getElementById('popupMessage');
            popupMessage.innerHTML = message;
            popup.style.display = 'block';

            document.querySelector('.close').onclick = function() {
                popup.style.display = 'none';
            };
            document.getElementById('popupButton').onclick = function() {
                popup.style.display = 'none';
            };
        }
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
