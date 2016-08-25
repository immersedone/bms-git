<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Projects | Banksia Gardens</title>

    <?php foreach($css_files as $file): ?>
      <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach; ?>
    <?php foreach($js_files as $file): ?>
      <script src="<?php echo $file; ?>"></script>
    <?php endforeach; ?>

    <!--Button scripts -->
    <script type="text/javascript">
    function showMilestones(){

    }

    function showExpenditures(){

    }

    function showReimbursements(){

    }

    function showFunding(){

    }
    </script>

    <style type="text/css">
    .form-button-box
    {
      display:inline;
      margin:0px;
      padding:0px;
      padding-right:7px;
    }
    </style>

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

            <?php include_once("include/sidebar_menu.php"); ?>

            <?php include_once("include/menu_footer.php"); ?>
          </div>
        </div>

        <?php include_once("include/top_navigation.php"); ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <?php
              if(isset($buttons) && is_array($buttons) && count($buttons) > 0)
              {
                $n=1;
                foreach ($buttons as $formName => $frmConfig)
                {
                  if(array_key_exists('class', $frmConfig))
                  {
                  $class = $frmConfig['class'];
                  }
                  else
                  {
                $class = 'btn btn-large';
                  }
                ?>
            <div class='form-button-box'>
              <input class='<?php echo $class?>' type='button' onclick="<?php echo 'javascript:' . $frmConfig['js_call'] . '()'?>"
                     value='<?php echo $formName; ?>' id="user-button_<?php echo $n?>" />
            </div>
            <?php
              }
            }?>
              <?php echo $output; ?>
            </div>
          </div>
        </div>
        <!-- /page content -->

        <?php include_once("include/footer.php"); ?>
      </div>
    </div>
    <!-- jQuery -->
    <!--<script src="/assets/vendors/jquery/dist/jquery.min.js"></script>-->
    <!-- Bootstrap -->
    <script src="/assets/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="/assets/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="/assets/vendors/nprogress/nprogress.js"></script>
    <!-- Chart.js -->
    <script src="/assets/vendors/Chart.js/dist/Chart.min.js"></script>
    <!-- gauge.js -->
    <script src="/assets/vendors/gauge.js/dist/gauge.min.js"></script>
    <!-- bootstrap-progressbar -->
    <script src="/assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="/assets/vendors/iCheck/icheck.min.js"></script>
    <!-- Skycons -->
    <script src="/assets/vendors/skycons/skycons.js"></script>
    <!-- Flot -->
    <script src="/assets/vendors/Flot/jquery.flot.js"></script>
    <script src="/assets/vendors/Flot/jquery.flot.pie.js"></script>
    <script src="/assets/vendors/Flot/jquery.flot.time.js"></script>
    <script src="/assets/vendors/Flot/jquery.flot.stack.js"></script>
    <script src="/assets/vendors/Flot/jquery.flot.resize.js"></script>
    <!-- Flot plugins -->
    <script src="/assets/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
    <script src="/assets/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
    <script src="/assets/vendors/flot.curvedlines/curvedLines.js"></script>
    <!-- DateJS -->
    <script src="/assets/vendors/DateJS/build/date.js"></script>
    <!-- JQVMap -->
    <script src="/assets/vendors/jqvmap/dist/jquery.vmap.js"></script>
    <script src="/assets/vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
    <script src="/assets/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="/assets/js/moment/moment.min.js"></script>
    <script src="/assets/js/datepicker/daterangepicker.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="/assets/js/custom.min.js"></script>


  </body>
</html>
