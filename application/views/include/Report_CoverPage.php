 
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <link rel="stylesheet" type="text/css" href="/assets/css/style.pdf.css">
</head>

<body>
<div id="wrapper-main">
<div class="center">
<div id="container">  
<div class="container">  
<header class="mainheading">

<ul>
<li style="font-size:45px; margin:0px; padding:0px; margin-left:22px; "><?php echo $titleLine_One; ?></li>
<li style=" font-size:45px; margin:0px; padding:0px; margin-left:22px; "><?php echo $titleLine_Two; ?></li>
</ul>
</header>

<section>

<div class="middlepart">
</div>

<div class="report" style="text-align: center">
<img class="logo" src="<?php echo base_url(); ?>assets/img/Logo_banner.jpg" style="" />
<!--<h4 style="padding-left: 27px; margin-right: -100px;"><span >Report Prepared For</span></h4>-->
<table width="350" class="reportinfo" style="float: right; padding-left: 20px;">
  <tr>
  	<td style="text-align:right; padding-right: 15px; vertical-align: top;"><b>Project: </b></td>
    <td class="name"><?php echo $prjName; ?></td>
  </tr>
  <tr>
  	<td style="text-align:right; padding-right: 15px;"><b>Date: </b></td>
    <td><?php echo $today_date; ?></td>
  </tr>
</table>

</div>

<div class="report-generate" style="width: 812px;">
<table>
<tbody>
<tr><td style="color:#7E7E7E; font-size: 12px;">Report Generated By:</td>
<td style="font-size:12px;"><?php echo $name; ?></td></tr>
</tbody>
</table>
<p class="copyright" style="">&copy; Copyright <?php echo $today_year; ?>, Banksia Gardens Community Centre.</p>
</div>
</div>

</section>


</div>
</div>
</div>
</div>

</body>
</html>