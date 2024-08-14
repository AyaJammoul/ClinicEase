<?php
session_start();
?>
<?php require 'header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
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
<section class="page-title bg-1">
  <div class="overlay"></div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="block text-center">
          <span class="text-white">Our department</span>
          <h1 class="text-capitalize mb-5 text-lg">Specializations and Services</h1>
        </div>
      </div>
    </div>
  </div>
</section>


<section class="section service-2">
	<div class="container">
		<div class="row">
			<div class="col-lg-4 col-md-6 col-sm-6">
				<div class="service-block mb-5">
					<img src="image/Dermatology.png" alt="" class="img-fluid">
					<div class="content">
						<h4 class="mt-4 mb-2 title-color">Dermatology</h4>
						<p class="mb-4">A dermatologist can treat skin issues that affect your appearance. This may include hair loss, etc...
                             Many dermatologists are trained to administer cosmetic treatments, too. 
                            They also help manage chronic skin conditions like eczema and psoriasis.</p>
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-6 col-sm-6">
				<div class="service-block mb-5">
					<img src="image/Plastic surgery.png" alt="" class="img-fluid">
					<div class="content">
						<h4 class="mt-4 mb-2  title-color">Plastic surgery</h4>
						<p class="mb-4">Plastic surgery focuses on repairing and enhancing the body. This includes correcting defects from injury or illness and improving appearance through cosmetic procedures.
                             Plastic surgeons aim to improve function and aesthetics.</p>
					</div>
				</div>
			</div>
			
			<div class="col-lg-4 col-md-6 col-sm-6">
				<div class="service-block mb-5">
					<img src="image/Pediatrics.png" alt="" class="img-fluid">
					<div class="content">
						<h4 class="mt-4 mb-2 title-color">Pediatrics</h4>
						<p class="mb-4">Pediatrics is a medical field focusing on the health and medical care of infants, children, and adolescents.
                             It covers preventive care, diagnosis, and treatment of illnesses and developmental issues, ensuring healthy growth and development.</p>
					</div>
				</div>
			</div>


			<div class="col-lg-4 col-md-6 col-sm-6">
				<div class="service-block mb-5 mb-lg-0">
					<img src="image/Psychiatry.png" alt="" class="img-fluid">
					<div class="content">
						<h4 class="mt-4 mb-2 title-color">Psychiatry</h4>
						<p class="mb-4">A psychiatrist diagnoses and treats mental health issues. This includes conditions like depression, anxiety, and schizophrenia.
							 Psychiatrists use therapies such as medication and counseling.
						</p>
					</div>
				</div>
			</div>

			<div class="col-lg-4 col-md-6 col-sm-6">
				<div class="service-block mb-5 mb-lg-0">
					<img src="image/dentistry.jpeg" alt="" class="img-fluid">
					<div class="content">
						<h4 class="mt-4 mb-2 title-color">Dentistry</h4>
						<p class="mb-4">A dentist cares for your teeth and gums. They fix cavities, treat gum disease, and clean teeth. Dentists also help you learn how to brush and floss to keep your mouth healthy.
						</p>
					</div>
				</div>
			</div>
			
			<div class="col-lg-4 col-md-6 col-sm-6">
				<div class="service-block mb-5 mb-lg-0">
					<img src="image/Neurology.png" alt="" class="img-fluid">
					<div class="content">
						<h4 class="mt-4 mb-2 title-color">Neurology</h4>
						<p class="mb-4"> A neurologist treats disorders of the nervous system. This includes the brain, spinal cord, and nerves.
							 Neurologists manage conditions like stroke, epilepsy, and Parkinson's disease.</p>
					</div>
				</div>
			</div>
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
					<a href="appointment.php" class="btn btn-main-2 btn-round-full">Get appoinment<i class="fa-solid fa-chevron-right"></i></a>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
         require 'footer.php';
        ?>
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