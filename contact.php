<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Ease</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://kit.fontawesome.com/882757363d.js" crossorigin="anonymous"></script>
        <link rel="icon" type="image/x-icon" href="image/iconlogo.png">

    <style>
        .map-container {
            position: relative;
            padding-bottom: 35%;
            height: 0;
            overflow: hidden;
            background: #f0f0f0;
            border: 2px solid #10a1da;
            border-radius: 8px;
            margin: 10%;
        }

        .map-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }
    </style>
</head>

<body id="top">
    <?php require 'header.php'; ?>

    <section class="page-title bg-1">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="block text-center">
                        <span class="text-white">Contact Us</span>
                        <h1 class="text-capitalize mb-5 text-lg">Get in Touch</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section contact-info pb-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="contact-block mb-4 mb-lg-0">
                        <i class="fas fa-phone"></i>
                        <h5>Call Us</h5>
                        <a href="tel:05807711">05-807 711</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="contact-block mb-4 mb-lg-0">
                        <i class="far fa-envelope"></i>
                        <h5>Email Us</h5>
                        <a href="mailto:final2024project2024gmail.com">ClinicEase@gmail.com</a>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12" style="padding:0">
                    <div class="contact-block mb-4 mb-lg-0">
                        <i class="fas fa-map-marker-alt"></i>
                        <h5>Location</h5>
                        Islamic University of Lebanon, Wardaniyeh
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="contact-form-wrap section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="section-title text-center">
                        <h2 class="text-md mb-2">Contact us</h2>
                        <div class="divider mx-auto my-4"></div>
                        <p class="mb-5">Have questions or need assistance? Reach out to us, and our team will get
                            back to you promptly.
                            We're here to help with all your healthcare needs.</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <form id="contact-form" class="contact__form" method="POST">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input name="name" id="name" type="text" class="form-control"
                                        placeholder="Your Full Name" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input name="email" id="email" type="email" class="form-control"
                                        placeholder="Your Email Address" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input name="subject" id="subject" type="text" class="form-control"
                                        placeholder="Your Query Topic" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <input name="phone" id="phone" type="text" class="form-control"
                                        placeholder="Your Phone Number" required>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group-2 mb-4">
                                    <textarea name="message" id="message" class="form-control" rows="8"
                                        placeholder="Your Message" required></textarea>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-main btn-round-full">Send Message</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <div class="map-container">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1661.2938744757682!2d35.40948513209128!3d33.61599938297792!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x151ee4dd7766e28f%3A0x3d5dedfb4fddb1df!2sIslamic%20University%20of%20Lebanon%20%2CWardaniyeh!5e0!3m2!1sen!2slb!4v1719354283442!5m2!1sen!2slb"
            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <?php require 'footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#contact-form').on('submit', function (event) {
                event.preventDefault(); 

                $.ajax({
                    url: 'https://formsubmit.co/ajax/final2024project2024@gmail.com',
                    method: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function (response) {
                        alert('Your message has been sent successfully.');
                        $('#contact-form')[0].reset();
                    },
                    error: function (response) {
                        alert('Error submitting the form. Please try again later.');
                    }
                });
            });
        });
    </script>
</body>

</html>
