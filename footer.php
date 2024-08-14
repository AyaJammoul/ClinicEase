<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="style.css">
  <script src="https://kit.fontawesome.com/882757363d.js" crossorigin="anonymous"></script>
</head>

<body id="top">
<footer class="footer section gray-bg">
	<div class="container">
		<div class="row">
			<div class="col-lg-4 mr-auto col-sm-6">
				<div class="widget mb-5 mb-lg-0">
					<div class="logo mb-4">
						<img src="image/logo1.png" alt="" class="img-fluid" style="width: 30%">
						<img src="image/logo2.png" alt="" class="img-fluid" style="width: 50%">
					</div>
					<p>ClinicEase provides seamless and efficient healthcare management solutions. Our platform simplifies administrative tasks, allowing healthcare providers to focus on what matters most: patient care.
						 Connect with us on social media for the latest updates and insights.</p>

					<ul class="list-inline footer-socials mt-4">
						<li class="list-inline-item">
							<a href="https://www.facebook.com/clinicease"><i class="fa-brands fa-facebook"></i></a>
						</li>
						<li class="list-inline-item">
							<a href="https://www.twitter.com/clinicease"><i class="fa-brands fa-twitter"></i></a>
						</li>
						<li class="list-inline-item">
							<a href="https://www.linkedin.com/clinicease"><i class="fa-brands fa-linkedin"></i></a>
						</li>
						<li class="list-inline-item">
							<a href="https://www.instagram.com/clinicease"><i class="fa-brands fa-instagram"></i></a>
						</li>
					</ul>
				</div>
			</div>

			<div class="col-lg-2 col-md-6 col-sm-6">
				<div class="widget mb-5 mb-lg-0">
					<h4 class="text-capitalize mb-3">Department</h4>
					<div class="divider mb-4"></div>

					<ul class="list-unstyled footer-menu lh-35">
						<li><a href="department.php">Dentistry </a></li>
						<li><a href="department.php">Plastic surgery</a></li>
						<li><a href="department.php">Dermatology</a></li>
						<li><a href="department.php">Psychiatry</a></li>
						<li><a href="department.php">Neurology</a></li>
						<li><a href="department.php">Pediatrics</a></li>
						<li><a href="department.php">Public health</a></li>
					</ul>
				</div>
			</div>

			<div class="col-lg-3 col-md-6 col-sm-6">
				<div class="widget widget-contact mb-5 mb-lg-0">
					<h4 class="text-capitalize mb-3">Get in Touch</h4>
					<div class="divider mb-4"></div>

					<div class="footer-contact-block mb-4">
						<div class="icon d-flex align-items-center">
						<i class="fa-regular fa-envelope"></i>
							<span class="h6 mb-0" style="margin-left:4%">Support Available for 24/7</span>
						</div>
						<h4 class="mt-2"><a href="mailto:final2024project2024@gmail.com">ClinicEase@email.com</a></h4>
					</div>

					<div class="footer-contact-block"> 
						<div class="icon d-flex align-items-center">
						  <i class="fa-solid fa-headset"></i>
							<span class="h6 mb-0" style="margin-left:4%">
							
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
								echo '<li>';
								if (count($days) > 1) {
									echo '<span>' . implode(' to ', [reset($days), end($days)]) . '       </span>';
								} else {
									echo '<span>' . reset($days) . '  </span>';
								}
								echo '<span>' . $time . '</span>';
								echo '</li>';
							}
							echo '</ul>';
							?>
						    </span>
						</div>
						<h4 class="mt-2"><a href="tel:05807711 ">05-807 711</a></h4>
					</div>
				</div>
			</div>
		</div>

		<div class="footer-btm py-4 mt-5">
				<div class="col-lg-6">
					<div class="subscribe-form text-lg-right mt-5 mt-lg-0">
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-lg-4">
					<a class="backtop scroll-top-to" href="#top">
					   <i class="fa-solid fa-arrow-up"></i>
					</a>
				</div>
			</div>
		</div>
	</div>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="script.js"></script>
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