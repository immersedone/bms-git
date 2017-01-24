<!--

	Project Name: 	Banksia Management System
	Course:			Programming Project 1

	Theme Name:		Login Page
	Revision: 		v1.0

	Authors: 		Adam Salek		-	S
					Ryan Bargholz	-	S3485754
					Truc Minh Phan	- 	S3545017


	Description: 	Login Page using CodeIgniter & Bootstrap Frameworks.



-->

<?php 

	//Display Messages accordingly (Success/Failed)
	if(isset($_GET)) {

		if(isset($_GET['er'])) {

			if($_GET['er'] == 'udne') {

				$m = 'Username/Email doesn\'t exist.';

			} elseif($_GET['er'] == 'ftup') {
				
				$m = 'Failed to reset password.';

			} elseif($_GET['er'] == 'ftsm') {
				
				$m = 'Failed to send email instructions.';

			}

			$shEM = 'display:block';
			$emBG = 'maroon';
		}

	} else {

		$shEM = 'display:none';
		$emBG = 'limegreen';

	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	
	<!-- Meta Data -->
	<meta charset="UTF-8" />
	<meta name="description" content="Banksia Management System Login Page" />
	<meta name="keywords" content="Banksia Gardens Management System Community Services " />
	<meta name="author" content="Adam Salek, Ryan Bargholz, Truc Minh Phan" />

	<!-- Viewport -->
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<!-- Title -->
	<title>Forgot Password | Banksia Gardens</title>

	<!-- Favicon -->
	<link rel="icon" href="img/favicon.jpg" type="image/jpeg" />


	<!-- Stylesheets -->
	<link rel="stylesheet" type="text/css" href="assets/css/login.css" />
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" />

	<!-- Scripts -->
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<body>
	<div class="container">
		<div class="loginPnl forgot">
			<!-- Display Messages -->
			<div class="col-md-12 dpmg" style="position:absolute; top: -40px; background: <?php echo (isset($emBG))? $emBG : ''; ?>; padding: 5px; text-align: center; font-weight: bold; font-size: 17px; width: 100%; color: white; border-radius: 5px; <?php  echo (isset($shSM))? $shSM : ''; ?>">
				<?php echo (isset($m))? $m : ''; ?>
			</div>
			<div class="col-md-12 header">
				<img src="assets/img/logo.png" class="rsp_img" />
				<h4>Banksia Gardens</h4>
			</div>
			<div class="col-md-12 content">
				<p class="justify" style="font-size: 13px;">Locked out? No worries. Just fill in your Email Address and Username below and we'll send out your temporary password.</p>
				<hr/>
				<?php echo form_open('forgot/email'); ?>
					<label for="username">Current Personal Email Address:</label>
					<input type="email" name="emailadd" required="required"  placeholder="e.g. john.msmith@gmail.com" />
					<label for="password">Username:</label>
					<input type="text" name="username" required="required" placeholder="e.g. john.msmith" />
					<input type="submit" value="Reset Password" class="btn" />
				</form>
				<hr/>
				<a href="/" class="__flRight">Back to Login</a>
			</div>
		</div>
	</div>
</body>
</html>