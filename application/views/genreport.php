<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Generate a Report | Banksia Gardens</title>


    <!-- jQuery UI -->
    <link href="http://code.jquery.com/ui/1.12.0/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
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
                    <div class="row x_custom x_title">
                        <div class="col-md-12">
                            <h3>Form</h3>
                        </div>
                    </div>


                    <div class="row x_custom">
                        <div class="col-md-12">
                            <form onsubmit="validate()" class="genRep" method="POST" action="/user/genreport/createreport">
                                <input type="hidden" name="csrf_token" value="<?php echo $_COOKIE['csrf_cookie']  ?>" />
                                <!-- First Row in Form -->
                                <div class="col-md-12 control-wrap">

                                    <!-- Select Type of Report -->
                                    <div class="col-md-6">
	                                    <div class="control-form reportType">
	                                        <h4>Report Type:</h4>
	                                        <hr/>
	                                        <select name="reportType" id="reportType" class="form-control">
	                                            <option value="0"> --- Please select a Report Type --- </option>
	                                            <option value="empprj">Employees in a Project</option>
	                                            <option value="volprj">Volunteers in a Project</option>
	                                            <option value="reimb">Reimbursement Report</option>
	                                            <option value="exp_prj">Expenditure by Project and Date</option>
	                                            <option value="exp_spv">Expenditure by Supervisor and Date</option>
	                                            <option value="pend_mst">Pending Milestones</option>
	                                            <option value="future_mst">Future Milestones</option>
	                                            <option value="cdet_emp">Contact Details - All Employees</option>
	                                            <option value="cdet_vol">Contact Details - All Volunteers</option>
	                                            <option value="cont_srv">Contract and WWC - Status Review</option>
	                                        </select>
	                                    </div>
                                    </div>

                                    <div class="col-md-6">
                                    	<div class="control-form optionCoverPage">
                                    		<h4>Cover Page (optional):</h4>
                                    		<hr/>
                                    		<div class="form-group">
		                                        <label for="optionalCoverPage" class="control-label">Remove Cover Page for Report?</label>
		                                        <input type="radio" value="YES" name="optionalCoverPage" required="required" checked="checked"/> Yes
		                                        <input type="radio" value="NO" name="optionalCoverPage" required="required"/> No
	                                        </div>
	                                        <div class="form-group" id="customTitleCheck" style="display:none;">
		                                        <label for="customTitleCheck" class="control-label">Add Custom Title to Cover Page? </label>
		                                        <input type="radio" value="YES" name="customTitleCheck" required="required" /> Yes
		                                        <input type="radio" value="NO" name="customTitleCheck" required="required" checked="checked"/> No
	                                        </div>
	                                    </div>
                                    </div>
                                </div>


                                <!-- Custom Report Title -->
                                <div class="col-md-12 control-wrap" id="customTitle" style="display:none;">

                                    <!-- File Names -->
                                    <div class="col-md-12">
                                    <div class="control-form reportName">
                                    <h4>Custom Report Title</h4>
                                    <label for="title_one" class="control-label">Line 1:</label>
                                    <input type="text" name="title_one" placeholder="Title Line 1" class="form-control"/>
                                    <br/>
                                    <label for="title_two" class="control-label">Line 2:</label>
                                    <input type="text" name="title_two" placeholder="Title Line 2" class="form-control"/>
                                    </div>
                                    </div>

                                </div>



                                <!-- List Projects Form -->
                                <div class="col-md-12 control-wrap hideForm" id="listProj"  style="display: none">

                                    <!-- Select Type of Report -->
                                    <div class="col-md-12">
                                    <div class="control-form listProjects">
                                        <h4>Select Project</h4>
                                        <hr/>
                                        <select name="project" class="form-control">
                                            <option value="0"> --- Please select a project --- </option>
                                            <?php foreach($Projects as $key => $value) {
                                                echo '<option value="' . $key . '">' . $value . '</option>';
                                            }

                                            ?>

                                            
                                        </select>
                                    </div>
                                    </div>

                                </div>

                                <!-- Reimbursements -->
                                <div class="col-md-12 control-wrap hideForm" id="listReimb" style="display: none">

                                    <div class="col-md-12">
                                    <div class="control-form listReimbursements">
                                        <h4>Select Reimbursement</h4>
                                        <hr/>
                                        <select name="reimbursement" class="form-control">
                                            <option value="0"> --- Please select a reimbursement --- </option>
                                            <?php foreach($Reimbursements as $key => $value) {
                                                echo '<option value="' . $key . '">' . $value . '</option>';
                                            }

                                            ?>

                                            
                                        </select>
                                    </div>
                                    
                                    </div>

                                </div>

                                <!-- Supervisors -->
                                <div class="col-md-12 control-wrap hideForm" id="listSupervisor" style="display: none">

                                    <div class="col-md-12">
                                    <div class="control-form listSupervisors">
                                        <h4>Select Supervisor</h4>
                                        <hr/>
                                        <p><small><i>The list are of active Supervisors (Employee) across all projects.</i></small></p>
                                        <select name="supervisor" class="form-control">
                                            <option value="0"> --- Please select a supervisor --- </option>
                                            <?php foreach($Supervisors as $key => $value) {
                                                echo '<option value="' . $key . '">' . $value . '</option>';
                                            }

                                            ?>

                                            
                                        </select>
                                    </div>
                                    
                                    </div>

                                </div>

                               
                               	<!-- Date Range --> 
                               	<div class="col-md-12 control-wrap hideForm" id="dateRange" style="display:none;">
                               		<div class="col-md-12">
                               		<div class="control-form">
                               			<h4>Select Date Range</h4>
                               			<hr/>
                               			<label for="fromDate" class="control-label">From:</label>
                               			<input type="date" name="fromDate" class="input-date form-control datepicker" placeholder="DD/MM/YYYY" required="required" />
                               			<label for="toDate" class="control-label">To:</label>
                               			<input type="date" name="toDate" class="input-date form-control datepicker" placeholder="DD/MM/YYYY" required="required" />
                               		</div>
                               		</div>
                               	</div>

                               	<!-- Date Range --> 
                               	<div class="col-md-12 control-wrap hideForm" id="dateFuture" style="display:none;">
                               		<div class="col-md-12">
                               		<div class="control-form">
                               			<h4>Select Future Date</h4>
                               			<hr/>
                               			<label for="toDate" class="control-label">To:</label>
                               			<input type="date" name="toDate" class="input-date form-control datepicker" placeholder="DD/MM/YYYY" required="required" />
                               		</div>
                               		</div>
                               	</div>



                               	
                                <!-- Form Submit Button-->
                                <div class="col-md-12 control-wrap hideForm formSubmitCont"  style="display: none">
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

        <!-- footer content -->
        <footer>
          <div class="pull-right">
            Created By BMS Project Team</a>
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="/assets/vendors/jquery/dist/jquery.min.js"></script>
    <script   src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"   integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E="   crossorigin="anonymous"></script>
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


    <!-- jQuery UI-daterangepicker -->
    <script>
      $(document).ready(function() {

      		var dateInput = $('.datepicker');
      		dateInput.datepicker({
      			yearRange: "-20:+5",
      			dateFormat: "yy-mm-dd",
      			changeMonth: true,
      			changeYear: true
      		});
        });
    </script>


    <!-- Javascript for Forms -->
   	<script type="text/javascript">

   		


   		//Associative Array of Containers + Inputs
   		var indexInput = {
   			listProj:"#listProj", 
   			listReimb:"#listReimb",
   			listSupervisor:"#listSupervisor",
   			showDate:"#dateRange",
   			showFutureDate:"#dateFuture"
		};

   		//Individual Arrays for required elements
   		var empprjArray = ["listProj"];
   		var volprjArray = ["listProj"];
   		var reimbArray = ["listReimb"];
   		var exp_prjArray = ["listProj", "showDate"];
   		var exp_spvArray = ["listSupervisor", "showDate"];
   		var pend_mstArray = ["showFutureDate"];
   		var future_mstArray = ["showFutureDate"];
   		var cdet_empArray = [];
   		var cdet_volArray = [];
   		var cont_srvArray = [];

   		//Bind Report Types to an Array
   		var allArray = {
   			empprj:empprjArray, 
   			volprj:volprjArray, 
   			reimb:reimbArray, 
   			exp_prj:exp_prjArray, 
   			exp_spv:exp_spvArray, 
   			pend_mst:pend_mstArray,
   			future_mst:future_mstArray, 
   			cdet_emp:cdet_empArray, 
   			cdet_vol:cdet_volArray, 
   			cont_srv:cont_srvArray
   		};


   		//Show/Hide Custom Title
   		//Also disable form inputs when not checked
   		$('input[type=radio][name=optionalCoverPage]').change(function() {
   			if(this.value == 'YES') {
   				$('#customTitleCheck').slideUp();
   				$('#customTitle').slideUp();
   				$('input[type=radio][name=customTitleCheck][value="NO"]').prop("checked", "checked");
   				$('#customTitle input').prop("disabled", true);
   			} else {
   				$('#customTitleCheck').slideDown();
   				$('#customTitle').slideDown();
   			}
   		});

   		//Show/Hide Custom Title
   		//Also disable form inputs when not checked
   		$('input[type=radio][name=customTitleCheck]').change(function() {
   			if(this.value == 'YES') {
   				$('#customTitle').slideDown();
				$('#customTitle input').prop("disabled", false);
   			} else {
   				$('#customTitle').slideUp();
   				$('#customTitle input').prop("disabled", true);
   			}
   		});



   		//Show/Hide Form Elements onchange of Report Type
   		$('#reportType').change(function() {
   			if(this.value == '0') {
   				
   				//Hide all Form Inputs
   				$('.hideForm').slideUp();

   			} else {

   				//Hide all Form Inputs
   				$('.hideForm').slideUp();

   				if($('input[type=radio][name=customTitleCheck]').val() == "YES") {
   					$('#customTitle input').prop("disabled", false);
   				}

   				//Disable all inputs
   				$('.genRep input').prop("disabled", true);
   				$('.genRep select').prop("disabled", true);

   				//Enable CoverPage + Report Type Options
   				$('.genRep #reportType').prop("disabled", false);
   				$('.genRep input[name=optionalCoverPage]').prop("disabled", false);
   				$('.genRep input[name=customTitleCheck]').prop("disabled", false);
                $('.genRep input[name=csrf_token]').prop("disabled", false);
   				$('.reportName input').prop("disabled", false);
   				$('.formSubmitCont input').prop("disabled", false);

   				//Enable + Display required elements
   				var arrayList = allArray[this.value];

   				

   				for(var i=0; i < arrayList.length; i++) {
   					//Enable all associated
   					$(indexInput[arrayList[i]] + ' input').prop("disabled", false);
   					$(indexInput[arrayList[i]] + ' select').prop("disabled", false);
   					$(indexInput[arrayList[i]] + ' textarea').prop("disabled", false);
   					$(indexInput[arrayList[i]]).slideDown();
   				}
   				


   				//Show the Generate Button
   				$('.formSubmitCont').slideDown();
   			}
   		});

   	</script>
  </body>
</html>
