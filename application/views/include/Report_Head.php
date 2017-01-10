 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <link rel="stylesheet" type="text/css" href="/assets/css/style.pdf.css">
 <style>
 	html, body {
 		background-color: white;
 		border: 1px solid black;
 	}

 	table {
 	}

 	.head {
 		width: 100%;
 		height: 125px;
 	}

 	.details {
 		float: left;
 		text-align: left;
 	}

 	.logo {
 		float: right;
 		height: 75px;
 		text-align: right;
 	}

 	.head .logo > img {
 		float: right;
 		position: absolute;
 		right: 0;
 		top: 0;
 	}

 	.wrapper {
 		width: 100%;
 		height: 100%;
 		/*border: 1px solid black;
 		padding: 20px;*/
 	}

 	.wrapper .mTitle {
 		font-weight: bold;
 		margin: 10px 0px 10px 5px;
 	}

 	table.details td.first {
 		text-align: left;
 		width: 185px;
 	}


 	table.main {
 		width: 100%;
 	}

 	table.main td, table.main th {
 		border: 1px solid black;
 		padding: 3px 5px;
 	}

 	table.main th {
 		background: #43bc14;
 		color: white;
 	}

 	table.foot td {
 		padding: 20px 3px 5px;
 	}


 </style>
</head>

<body>
	<table class="head">
		<tr>
		<td class="details" width="33%">
			<h4 class="title"><b><?php echo $titleLine_One; ?></b></h4>
			<br/>
			<?php echo $detailsHTML; ?>
		</td>
		<td class="signedBy"  width="33%">
			<table class="foot">
				<tr><td width="30%" style="text-align: right;">Signed By:</td><td width="70%" style="text-align: left;">_______________________________________________</td></tr>
				<tr><td width="30%" style="text-align: right;">Date:</td><td width="70%" style="text-align: left;">_______________________________________________</td></tr>
				<tr><td width="30%" style="text-align: right;">Signature:</td><td width="70%" style="text-align: left;">_______________________________________________</td></tr>
			</table>
		</td>
		<td class="logo" width="33%">
			<img src="<?php echo base_url('/assets/img/Logo_banner.jpg'); ?>" width="305" height="85" />
		</td>
		</tr>
	</table>
	<hr>
	<div class="wrapper">
