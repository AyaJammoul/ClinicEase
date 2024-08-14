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
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.6);
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
                </ul>
                <div class="form-content">
                    <form id="check-form" method="post" class="check-form active">
                        <div class="existing-account">
                            <p>Enter your ID:</p>
                        </div>
                        <input type="number" name="patientid" placeholder="ID" required>
                        <button type="submit" class="btn btn-primary">Check</button>
                        <div id="check-message"></div>
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
                        showPopup("This ID was not found. Please call the clinic to register a file for you and get your ID.");
                    } else {
                        window.location.href = 'appointment.php';
                    }
                })
                .catch(error => console.error('Error:', error));
            });

            function showPopup(message) {
                const popup = document.getElementById('myPopup');
                const popupMessage = document.getElementById('popupMessage');
                popupMessage.textContent = message;
                popup.style.display = 'block';

                document.querySelector('.close').onclick = function() {
                    popup.style.display = 'none';
                };
                document.getElementById('popupButton').onclick = function() {
                    popup.style.display = 'none';
                };
            }
        });
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
