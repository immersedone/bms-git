<!--

	Project Name: 	Banksia Management System
	Course:			Programming Project 1

	Theme Name:		Login Page
	Revision: 		v1.0

	Authors: 		Adam Salek		-	S
					Ryan Bargholz	-	S
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
			<div class="col-md-12 header">
				<img src="assets/img/logo.png" class="rsp_img" />
				<h4>Banksia Gardens</h4>
			</div>
			<div class="col-md-12 content">
				<p class="justify">Locked out? No worries. Just fill in your Email Address and PIN below and we'll send out your temporary password.</p>
				<hr/>
				<form action="#" method="POST">
					<label for="username">Email Address:</label>
					<input type="text" name="username" required="required" />
					<label for="password">PIN:</label>
					<input type="number" name="password" required="required" />
					<input type="submit" value="Reset Password" class="btn" />
				</form>
				<hr/>
				<a href="http://banksiagardens.org.au" class="__flRight">Back to Banksia Gardens</a>
			</div>
		</div>
	</div>
</body>
</html>