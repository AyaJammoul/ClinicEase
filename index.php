<?php
session_start();
?>
<?php require 'header.php'; ?>
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
</head>
<body id="top">
<section class="banner">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-12 col-xl-7">
                <div class="block">
                    <div class="divider mb-3" style="color:#10a1da"></div>
                    <span class="text-uppercase text-sm letter-spacing ">Total Health care solution</span>
                    <h1 class="mb-3 mt-3">Your most trusted health partner</h1>
                    <p class="mb-4 pr-5">Providing comprehensive and compassionate care, we are dedicated to your health and well-being, ensuring you receive the highest quality medical services.</p>
                    <div class="btn-container">
                        <a href="appointment.php" class="btn btn-main-2 btn-icon btn-round-full">Make appointment <i class="fa-solid fa-chevron-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="features">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="feature-block d-lg-flex">
                    <div class="feature-item mb-5 mb-lg-0">
                        <div class="feature-icon mb-4">
                            <i class="fa-solid fa-user-nurse"></i>
                        </div>
                        <span>24 Hours Service</span>
                        <h4 class="mb-3">Online Appointment</h4>
                        <p class="mb-4">Get real-time support for all your medical needs. Schedule an online appointment with our experienced healthcare professionals and receive timely consultations from the comfort of your home.</p>
                        <a href="appointment.php" class="btn btn-main btn-round-full">Make an appointment</a>
                    </div>
                
                    <div class="feature-item mb-5 mb-lg-0">
                        <div class="feature-icon mb-4">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                        <span>Timing schedule</span>
                        <h4 class="mb-3">Working Hours</h4>
                        <ul class="w-hours list-unstyled">
                        <?php
                        include("connect.php");
                        $sql = "SELECT days, openingtime, closingtime FROM Schedule WHERE status = 'Open'";
                        $result = $conn->query($sql);
                        $hours = [];
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $day = $row['days'];
                                $openingtime = date("g:i A", strtotime($row['openingtime']));
                                $closingtime = date("g:i A", strtotime($row['closingtime'])); 
                                $hours[$day] = $openingtime . ' - ' . $closingtime;
                            }
                        } else {
                            echo 'No opening hours available';
                            exit;
                        }
                        $conn->close();
                        echo '<ul class="list-unstyled lh-35">';
                        foreach ($hours as $day => $time) {
                            echo '<li class="d-flex justify-content-between align-items-center">';
                            echo '<span>' . $day . '</span>';
                            echo '<span>' . $time . '</span>';
                            echo '</li>';
                        }
                        echo '</ul>';
                        ?>
                    </div>
                
                    <div class="feature-item mb-5 mb-lg-0">
                        <div class="feature-icon mb-4">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <span>Emergency Cases</span>
                        <h4 class="mb-3">05-807 711</h4>
                        <?php
                        include("connect.php");
                        $today = date("l"); 
                        $sql = "SELECT openingtime, closingtime FROM Schedule WHERE status = 'Open' AND days = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("s", $today);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $hours = [];
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $openingtime = date("H:i", strtotime($row['openingtime'])); 
                                $closingtime = date("H:i", strtotime($row['closingtime'])); 
                                $hours[] = ['openingtime' => $openingtime, 'closingtime' => $closingtime];
                            }
                        }else echo "Closed";
                        $stmt->close();
                        $conn->close();
                        ?>
                        <?php foreach ($hours as $hour): ?>
                        <p>In case of emergency, please call our <?php echo $hour['openingtime']; ?> am till <?php echo $hour['closingtime']; ?> pm hotline for immediate assistance. Our dedicated team is here to provide you with urgent medical care whenever you need it.</p>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section about">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4 col-sm-6">
                <div class="about-img">
                    <img src="image/img-1.jpg" alt="" class="img-fluid">
                    <img src="image/img-2.jpg" alt="" class="img-fluid mt-4">
                </div>
            </div>
            <div class="col-lg-4 col-sm-6">
                <div class="about-img mt-4 mt-lg-0">
                    <img src="image/img-3.jpg" alt="" class="img-fluid">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="about-content pl-4 mt-4 mt-lg-0">
                    <h2 class="title-color">Holistic Health & Wellbeing</h2>
                    <p class="mt-4 mb-5">Explore our diverse range of medical specialties, including cardiology, neurology, orthopedics, pediatrics, and more, ensuring comprehensive care for all your health needs.</p>
                    <a href="department.php" class="btn btn-main-2 btn-round-full btn-icon">Department<i class="fa-solid fa-chevron-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<h2 class="title-color" style="text-align: center;">Our Medical Team and Facilities</h2>
<br>
<?php
include("connect.php");

$doctorsCount = $conn->query("SELECT COUNT(*) as count FROM Doctor d JOIN Staff s ON d.staffid = s.staffid WHERE s.status='active'")->fetch_assoc()['count'];
$nursesCount = $conn->query("SELECT COUNT(*) as count FROM Nurse n JOIN Staff s ON n.staffid = s.staffid WHERE s.status='active'")->fetch_assoc()['count'];
$staffCount = $conn->query("SELECT COUNT(*) as count FROM Staff WHERE status='active' AND typeid='4'")->fetch_assoc()['count'];

$clinicsCount = $conn->query("SELECT COUNT(*) as count FROM Clinic WHERE status='Active'")->fetch_assoc()['count'];
?>

<section class="cta-section">
    <div class="container">
        <div class="cta position-relative">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="counter-stat">
                        <i class="fa-solid fa-user-doctor"></i>
                        <span class="h3 counter" data-count="<?php echo $doctorsCount; ?>"><?php echo $doctorsCount; ?></span>
                        <p>Doctors</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="counter-stat">
                        <i class="fa-solid fa-user-nurse"></i>
                        <span class="h3 counter" data-count="<?php echo $nursesCount; ?>"><?php echo $nursesCount; ?></span>
                        <p>Nurses</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="counter-stat">
                        <i class="fa-solid fa-laptop-medical"></i>
                        <span class="h3 counter" data-count="<?php echo $staffCount; ?>"><?php echo $staffCount; ?></span>
                        <p>Staff</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="counter-stat">
                        <i class="fa-solid fa-house-chimney-medical"></i>
                        <span class="h3 counter" data-count="<?php echo $clinicsCount; ?>"><?php echo $clinicsCount; ?></span>
                        <p>Clinics</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section service gray-bg">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 text-center">
                <div class="section-title">
                    <h2>Comprehensive Medical Services</h2>
                    <div class="divider mx-auto my-4"></div>
                    <p>Our clinic offers a wide range of specialized medical services to meet all your healthcare needs. From advanced diagnostics to expert treatments, we are dedicated to providing exceptional care across various specialties.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="service-item mb-4">
                    <div class="icon d-flex align-items-center">
                        <i class="fa-solid fa-hand-dots"></i>
                        <h4 class="mt-3 mb-3">Dermatology</h4>
                    </div>
                    <div class="content">
                        <p class="mb-4">Advanced skin care and treatment for optimal health.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="service-item mb-4">
                    <div class="icon d-flex align-items-center">
                        <i class="fa-solid fa-hands-holding-child"></i>
                        <h4 class="mt-3 mb-3">Pediatrics</h4>
                    </div>
                    <div class="content">
                        <p class="mb-4">Comprehensive care for children's health and development.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="service-item mb-4">
                    <div class="icon d-flex align-items-center">
                        <i class="fa-solid fa-tooth"></i>
                        <h4 class="mt-3 mb-3">Dentistry</h4>
                    </div>
                    <div class="content">
                        <p class="mb-4">Expert dental services for a healthy and beautiful smile.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="service-item mb-4">
                    <div class="icon d-flex align-items-center">
                        <i class="fa-solid fa-syringe"></i>
                        <h4 class="mt-3 mb-3">Plastic Surgery</h4>
                    </div>
                    <div class="content">
                        <p class="mb-4">Advanced surgical procedures for optimal outcomes.</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="service-item mb-4">
                    <div class="icon d-flex align-items-center">
                        <i class="fa-solid fa-brain"></i>
                        <h4 class="mt-3 mb-3">Neurology</h4>
                    </div>
                    <div class="content">
                        <p class="mb-4">Specialized neurological care and surgeries.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6 col-sm-6">
                <div class="service-item mb-4">
                    <div class="icon d-flex align-items-center">
                        <i><img src="image/patient.png" style="width:50px; float: left; margin-bottom: 10px; color: #10a1da"></i>
                        <h4 class="mt-3 mb-3">Psychiatry</h4>
                    </div>
                    <div class="content">
                        <p class="mb-4">Comprehensive mental health services and support</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="section clients">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="section-title text-center">
                    <h2>Our Specialized Clinics</h2>
                    <div class="divider mx-auto my-4"></div>
                    <p>Explore our specialized clinics: SafeClinic for comprehensive family health, Beauty for You for aesthetic excellence, and Brain Wellness for advanced neurological care. We are committed to delivering the highest standards of medical expertise and patient care.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row clients-logo">
            <div class="col-lg-4">
                <div class="client-thumb">
                    <img src="image/safeclinic.png" alt="" class="img-fluid">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="client-thumb">
                    <img src="image/beautyforyou.png" alt="" class="img-fluid">
                </div>
            </div>
            <div class="col-lg-4">
                <div class="client-thumb">
                    <img src="image/brainwellness.png" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>
<?php require 'footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
