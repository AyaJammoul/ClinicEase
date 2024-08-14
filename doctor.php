<?php
session_start();
require 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Clinic Ease</title>
<link rel="icon" type="image/x-icon" href="image/iconlogo.png">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
<script src="https://kit.fontawesome.com/882757363d.js" crossorigin="anonymous"></script>
</head>
<body id="top">
<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">All Doctors</span>
          <h1 class="text-capitalize mb-5 text-lg">Specialized doctors</h1>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="section doctors">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6 text-center">
        <div class="section-title">
          <h2>Doctors</h2>
          <div class="divider mx-auto my-4"></div>
          <p>We offer a comprehensive range of medical services across various specialties.
          Our team of dedicated professionals ensures personalized care to meet your health needs.
          Trust us to provide the highest standard of medical expertise and patient care.</p>
        </div>
      </div>
    </div>
    <?php
    include("connect.php");

    $sql = "SELECT DISTINCT speciality FROM Availableprocedures";
    $result = $conn->query($sql);
    $specialities = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $specialities[] = $row['speciality'];
        }
    }

    $sql = "SELECT d.doctorname AS name, d.speciality AS specialization, s.clinicname FROM Doctor d JOIN Staff s ON d.staffid = s.staffid WHERE s.status = 'active'";
    $doctorResult = $conn->query($sql);
    $doctors = [];
    if ($doctorResult->num_rows > 0) {
        while ($row = $doctorResult->fetch_assoc()) {
            $doctors[] = $row;
        }
    }
    $conn->close();
    ?>

    <div class="col-12 text-center mb-5">
      <div class="btn-group btn-group-toggle" data-toggle="buttons">
        <label class="btn active">
          <input type="radio" name="shuffle-filter" value="all" checked="checked" />All Doctors
        </label>
        <?php foreach ($specialities as $index => $speciality): ?>
          <label class="btn">
            <input type="radio" name="shuffle-filter" value="cat<?php echo $index + 1; ?>" /><?php echo htmlspecialchars($speciality); ?>
          </label>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="row shuffle-wrapper portfolio-gallery">
      <?php foreach ($doctors as $doctor): ?>
        <div class="col-lg-3 col-sm-6 col-md-6 mb-4 shuffle-item" data-speciality="<?php echo htmlspecialchars($doctor['specialization']); ?>">
          <div class="position-relative doctor-inner-box">
            <div class="doctor-profile">
              <div class="doctor-img">
                <img src="image/doctor.png" alt="doctor-image" class="img-fluid w-100">
              </div>
            </div>
            <div class="content mt-3">
              <h4 class="mb-0"><?php echo htmlspecialchars($doctor['name']); ?></h4>
              <p><?php echo htmlspecialchars($doctor['specialization']); ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<section class="section cta-page">
  <div class="container">
    <div class="row">
      <div class="col-lg-7">
        <div class="cta-content">
          <div class="divider mb-4"></div>
          <h2 class="mb-5 text-lg">We are pleased to offer you the <span class="title-color">chance to have the healthy live</span></h2>
          <a href="appointment.php" class="btn btn-main-2 btn-round-full">Get appointment<i class="fa-solid fa-chevron-right"></i></a>
        </div>
      </div>
    </div>
  </div>
</section>
<?php require 'footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    $('input[name="shuffle-filter"]').on('change', function() {
        var selectedCategory = $(this).val();
        if (selectedCategory === 'all') {
            $('.shuffle-item').show();
        } else {
            var selectedSpeciality = $(this).parent().text().trim();
            $('.shuffle-item').each(function() {
                var itemSpeciality = $(this).data('speciality');
                if (itemSpeciality === selectedSpeciality) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    });
});
</script>
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
