<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>View Report | Banksia Gardens</title>


    <!-- Bootstrap -->
    <link href="/assets/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/assets/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="/assets/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="/assets/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="/assets/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="/assets/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet"/>

    <!-- Theme Style -->
    <link href="/assets/css/style.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="/assets/css/custom.css" rel="stylesheet">


    <script src="/assets/js/js.cookie.js"></script>

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <a href="/user" class="site_title"><img src="/assets/img/logo.png" class="favicon" /> <span>Banksia Gardens</span></a>
            </div>

            <div class="clearfix"></div>

            <?php include_once("include/menu_profile.php"); ?>

            <br />

            <?php include_once("include/sidebar_menu.php"); ?>

            <?php include_once("include/menu_footer.php"); ?>            
          </div>
        </div>

        <?php include_once("include/top_navigation.php"); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="row first">

            <div class="col-md-12 col-sm-12 col-xs-12 bg_norm">  
                <div class="dashboard_generate"> 
                    <div class="row x_custom x_title">
                        <div class="col-md-12">
                            <h3>Viewing Custom Report</h3>
                        </div>
                    </div>
                    <div class="row x_custom">
                        <div class="col-md-12">
                            <p>To open the Report in a new tab, click <a href="<?php echo $url; ?>" target="_blank">here.</a></p>
                            <!-- Insert Report -->
                            <iframe class="viewReport" type="application/pdf" src="<?php echo $url;?>"> </iframe>
                        </div>
                    </div>

                </div>
            </div>

          </div>

          
        </div>
        <!-- /page content -->

        <?php include_once('include/footer_jquery.php'); ?>