<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Options | Banksia Gardens</title>

    <?php foreach($css_files as $file): ?>
      <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach; ?>
    <?php foreach($js_files as $file): ?>
      <script src="<?php echo $file; ?>"></script>
    <?php endforeach; ?>

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
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">  
              
              <?php echo $output; ?>
              <!--<h4 class="projTitle">Add New Option</h4>
              <div class="panelOption">
                <p>Please select an option to add: </p>
                <hr/>
                <form class="addOption" id="addOption" method="POST">
                    <div class="form-group">
                        <label class="form-label" for="optionType">Option Type:</label>
                        <select name="optionType" id="optionType" class="form-control">
                            <option value="0"> --- Please Select an Option --- </option>
                            <option value="BGCS_DEP">BGCS Departments</option>
                            <option value="SKILLS_EXP">Skills &amp; Experience</option>
                            <option value="NHACE_CLASS">NHACE Classifications</option>
                            <option value="Position">Positions</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="data">Option Name:</label>
                        <input type="text" class="form-control" name="data" id="data" placeholder="Please type Option Name here..." />
                    </div>
                    <hr/>
                    <div class="form-group">
                        <input type="submit" class="btn submit" value="Add New Option" />
                    </div>
                </form>
              </div>

              <h4 class="projTitle">All Options</h4>
              <div class="panelOption">
                    <?php echo $options; ?>
              </div>-->

            </div>
          </div>
        </div>
        <!-- /page content -->

        <?php include_once('include/footer.php'); ?>
