<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Generate a Report | Banksia Gardens</title>


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
                            <h3>Generate Custom Report</h3>
                        </div>
                    </div>
                    <div class="row x_custom">
                        <div class="col-md-12">
                            <p>To create a custom report, please use the form below: </p>
                        </div>
                    </div>

                </div>
            </div>

          </div>

          <div class="row mTop">
            <div class="col-md-12 col-sm-12 col-xs-12 bg_norm">  
                <div class="dashboard_generate"> 
                    
                    <div class="row x_custom">
                        <div class="col-md-12">
                            <form onsubmit="validate()" method="POST" action="/user/genreport/createreport">
                                
                                <div class="col-md-12 control-wrap">

                                    <!-- Select Type of Report -->
                                    <div class="col-md-6">
                                    <div class="control-form reportType">
                                        <h4>Select Report Type</h4>
                                        <select name="reportType">
                                            <option value="0"> --- Please select a Report Type --- </option>
                                            <!--<option value="allemp">All Employees</option>
                                            <option value="allvol">All Volunteers</option>
                                            <option value="allprj">All Projects</option>
                                            <option value="allfun">All Funding</option>
                                            <option value="prjovr">Project Overview</option>-->
                                            <option value="empprj">Employees in a Project</option>
                                            <option value="reimb">Reimbursements</option>
                                            <!--<option value="volprj">Volunteers in a Project</option>
                                            <option value="expprj">Expenditures in a Project</option>
                                            <option value="reiprj">Reimbursements in a Project</option>
                                            <option value="mstprj">Milestones in a Project</option>
                                            <option value="funprj">Funding in a Project</option>-->
                                        </select>
                                    </div>
                                    </div>

                                    <!-- Select Type of Report -->
                                    <div class="col-md-6">
                                    <div class="control-form listProjects">
                                        <h4>Select Project</h4>
                                        <select name="project">
                                            <option value="0"> --- Please select a project --- </option>
                                            <?php foreach($Projects as $key => $value) {
                                                echo '<option value="' . $key . '">' . $value . '</option>';
                                            }

                                            ?>

                                            
                                        </select>
                                    </div>
                                    </div>

                                    <!-- Select Type of Report -->
                                    <div class="col-md-6">
                                    <div class="control-form listProjects">
                                        <h4>Select Reimbursement</h4>
                                        <select name="reimbursement">
                                            <option value="0"> --- Please select a project --- </option>
                                            <?php foreach($Reimbursements as $key => $value) {
                                                echo '<option value="' . $key . '">' . $value . '</option>';
                                            }

                                            ?>

                                            
                                        </select>
                                    </div>
                                    </div>

                                </div>

                               
                                <div class="col-md-12 control-wrap">

                                    <!-- File Names -->
                                    <div class="col-md-6">
                                    <div class="control-form reportName">
                                    <h4>Report Name</h4>
                                    <label for="title_one">Line 1:</label>
                                    <input type="text" name="title_one" placeholder="Title Line 1"/>
                                    <br/>
                                    <label for="title_two">Line 2:</label>
                                    <input type="text" name="title_two" placeholder="Title Line 2"/>
                                    </div>
                                    </div>

                                </div>

                                <div class="col-md-12 control-wrap">
                                    <div class="col-md-12">
                                        <input type="submit" class="btn" value="Generate Report" style="float:right"/>
                                    </div>
                                </div> 

                            </form>
                        </div>
                    </div>

                </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

        <?php include_once("include/footer.php"); ?>
      </div>
    </div>

    <!-- jQuery -->
    <script src="/assets/vendors/jquery/dist/jquery.min.js"></script>
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
