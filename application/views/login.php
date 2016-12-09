<!--

	Project Name: 	Banksia Management System
	Course:			Programming Project 1

	Theme Name:		Login Page
	Revision: 		v1.0

	Authors: 		Adam Salek		-	S3462148
					Ryan Bargholz	-	S3485754
					Truc Minh Phan	- 	S3545017


	Description: 	Login Page using CodeIgniter & Bootstrap Frameworks.



-->

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
	<title>Login | Banksia Gardens</title>

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
		<div class="loginPnl">
			<div class="col-md-12 header">
				<img src="assets/img/logo.png" class="rsp_img" />
				<h4>Banksia Gardens</h4>
			</div>
			<div class="col-md-12 content">
				
				<?php echo form_open('login/auth'); ?>
					<label for="username">Username or Email:</label>
					<input type="text" name="username" required="required"/>
					<label for="password">Password:</label>
					<input type="password" name="password" required="required" />
					<a href="/forgot" class="__flRight">Forgot Password?</a>
					<input type="submit" value="Log In" class="btn" />
				</form>
				<hr/>
				<a href="http://banksiagardens.org.au" class="__flRight">Back to Banksia Gardens</a>
			</div>
		</div>
	</div>
</body>
</html>