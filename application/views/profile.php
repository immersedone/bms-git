<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Update Profile | Banksia Gardens</title>

    <?php if(isset($css_files)): ?>
    <?php foreach($css_files as $file): ?>
      <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach; ?>
    <?php endif; ?>
    <?php if(isset($js_files)): ?>
    <?php foreach($js_files as $file): ?>
      <script src="<?php echo $file; ?>"></script>
    <?php endforeach; ?>
    <?php endif; ?>

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
                            <h3>Update Profile</h3>
                        </div>
                    </div>
                    <div class="row x_custom">
                        <div class="col-md-12">
                            <p>Please use the form below if you wish to update your current profile details.</p>
                            <p><i>If the input is grayed out, that means that you are unable to change this and is for viewing purposes only.</i></p>
                            <hr/>

                            <?php if(isset($success) && isset($success["status"]) && isset($success["form"]) && $success["status"] === true && $success["form"] === "profile"): ?>
                                <!-- Success Message -->
                                <p class="info" style="color: green; font-style:italic;"><?php echo $success["message"]; ?></p>
                            <?php endif; ?>

                            <?php if(isset($error) && isset($error["field"]) && $error["field"] === "updateProf"): ?>
                                <!-- Error Message -->
                                <p class="info" style="color: red; font-style:italic;"><?php echo $error["message"]; ?></p>
                            <?php endif; ?>

                            <!-- Read Only Section --> 
                            <div class="form-group">
                                <label for='fname' class='form-label'>First Name:</label>
                                <input type="text" disabled="disabled" readonly="readonly" name="fname" class="form-control" value="<?php echo $fRC->FirstName ?>" />
                            </div>
                            <div class="form-group">
                                <label for='mname' class='form-label'>Middle Name:</label>
                                <input type="text" disabled="disabled" readonly="readonly" name="mname" class="form-control" value="<?php echo $fRC->MiddleName ?>" />
                            </div>
                            <div class="form-group">
                                <label for='lname' class='form-label'>Last Name:</label>
                                <input type="text" disabled="disabled" readonly="readonly" name="lname" class="form-control" value="<?php echo $fRC->LastName ?>" />
                            </div>
                            <!-- End Read Only Section --> 
                            <!--Begin Form -->
                            <?php echo form_open('user/profile/procdet'); ?>
                            <div class="form-group">
                                <label for='email' class='form-label'>Personal Email Address:</label>

                                <?php if(isset($error) && isset($error["field"]) && $error["field"] === "email"): ?>
                                    <!-- Error Message -->
                                    <p class="info" style="color: red; font-style:italic;"><?php echo $error["message"]; ?></p>
                                <?php endif; ?>

                                <input type="text" name="email" class="form-control" value="<?php echo $fRC->PersonalEmail ?>" />
                            </div>
                            <div class="form-group">
                                <label for='phone' class='form-label'>Phone Number:</label>

                                <?php if(isset($error) && isset($error["field"]) && $error["field"] === "phone"): ?>
                                    <!-- Error Message -->
                                    <p class="info" style="color: red; font-style:italic;"><?php echo $error["message"]; ?></p>
                                <?php endif; ?>

                                <input type="text" name="phone" class="form-control" value="<?php echo $fRC->HomePhone ?>" />
                            </div>
                            <div class="form-group">
                                <label for='mobile' class='form-label'>Mobile Number:</label>


                                <?php if(isset($error) && isset($error["field"]) && $error["field"] === "mobile"): ?>
                                    <!-- Error Message -->
                                    <p class="info" style="color: red; font-style:italic;"><?php echo $error["message"]; ?></p>
                                <?php endif; ?>

                                <input type="text" name="mobile" class="form-control" value="<?php echo $fRC->Mobile ?>" />
                            </div>
                            <div class="form-group">
                                <label for='address' class='form-label'>Address:</label>

                                <?php if(isset($error) && isset($error["field"]) && $error["field"] === "address"): ?>
                                    <!-- Error Message -->
                                    <p class="info" style="color: red; font-style:italic;"><?php echo $error["message"]; ?></p>
                                <?php endif; ?>

                                <input type="text" name="address" class="form-control" value="<?php echo $fRC->Address ?>" />
                            </div>
                            <div class="form-group">
                                <label for='suburb' class='form-label'>Suburb:</label>
                                
                                <?php if(isset($error) && isset($error["field"]) && $error["field"] === "suburb"): ?>
                                    <!-- Error Message -->
                                    <p class="info" style="color: red; font-style:italic;"><?php echo $error["message"]; ?></p>
                                <?php endif; ?>

                                <select name="suburb" class="form-control">
                                    <?php foreach($sbLT as $k => $v): ?>
                                        <option value="<?php echo $k; ?>" 
                                        <?php echo ($fRC->SuburbID == $k)? "selected='selected'" : '' ;?>>
                                            <?php echo $v; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 15px;">Update Profile</button>
                            </div>

                            </form>
                            <!-- End Form -->
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
                            <h3>Change Password</h3>
                        </div>
                    </div>


                    <div class="row x_custom">
                        <div class="col-md-12">
                            <p class="info">If you wish to change your password, please use the form below: </p>
                            <hr/>


                            <?php if(isset($success) && isset($success["status"]) && isset($success["form"]) && $success["status"] === true && $success["form"] === "password"): ?>
                                <!-- Success Message -->
                                <p class="info" style="color: green; font-style:italic;"><?php echo $success["message"]; ?></p>
                            <?php endif; ?>

                            <?php if(isset($error) && isset($error["field"]) && $error["field"] === "updatePass"): ?>
                                <!-- Error Message -->
                                <p class="info" style="color: red; font-style:italic;"><?php echo $error["message"]; ?></p>
                            <?php endif; ?>

                            <!--Begin Form -->
                            <?php echo form_open('user/profile/procpas'); ?>
                            <div class="form-group">
                                <label for='current' class='form-label'>Current Password:</label>
                                <p class="info"><i>This is the password you are currently using to sign in.</i></p>
                                <?php if(isset($error) && isset($error["field"]) && $error["field"] === "current"): ?>
                                    <!-- Error Message -->
                                    <p class="info" style="color: red; font-style:italic;"><?php echo $error["message"]; ?></p>
                                <?php endif; ?>
                                <input type="password" name="current" class="form-control" value="" />
                            </div>
                            <div class="form-group">
                                <label for='new' class='form-label'>New Password:</label>
                                <p class="info"><i>Please type in a password that is different to your current password.</i></p>
                                <?php if(isset($error) && isset($error["field"]) && $error["field"] === "new"): ?>
                                    <!-- Error Message -->
                                    <p class="info" style="color: red; font-style:italic;"><?php echo $error["message"]; ?></p>
                                <?php endif; ?>
                                <input type="password" name="new" class="form-control" value="" />
                            </div>
                            <div class="form-group">
                                <label for='newrepeat' class='form-label'>Repeat Password:</label>
                                <p class="info"><i>Please repeat your new password.</i></p>
                                <?php if(isset($error) && isset($error["field"]) && $error["field"] === "newrepeat"): ?>
                                    <!-- Error Message -->
                                    <p class="info" style="color: red; font-style:italic;"><?php echo $error["message"]; ?></p>
                                <?php endif; ?>
                                <input type="password" name="newrepeat" class="form-control" value="" />
                                <div class="repeatMG" style="display:none; margin-top: 15px; line-height: 20px;"></div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary chP" style="width: 100%; margin-top: 15px;" disabled="disabled">Change Password</button>
                            </div>

                            </form>
                            <!-- End Form -->

                        </div>
                    </div>

                </div>
            </div>
          </div>
        </div>
        <!-- /page content -->
        <?php include_once('include/footer_jquery.php'); ?>
        <script type="text/javascript" src="<?php echo base_url() . '/assets/js/jquery.pwstrength/dist/pwstrength-bootstrap.min.js'; ?>"></script>
        <script type="text/javascript">

            //Password Strength
            $('input[name="new"]:password').pwstrength();


            //Clear Messages
            $('input[name="new"]').keyup(function() {

                $('div.repeatMG').slideUp();
                $('input[name="newrepeat"]:password').val("");
            
            });


            //Matching Passwords
            $('input[name="newrepeat"]').keyup(function() {

                //Hide Message Panel
                $('div.repeatMG').slideUp();

                //Delay
                delay(function() {


                //If current password not empty
                if($('input[name="current"]:password').val() !== "") {

                    //If new password not empty
                    if($('input[name="new"]:password').val() !== "") {

                        //Not Matching
                        if($('input[name="new"]:password').val() !== $('input[name="newrepeat"]:password').val()) {
                            
                            $('div.repeatMG').html('<i class="fa fa-times-circle-o" style="color: red; font-size: 20px; position: relative; top: 2px;"></i> Passwords do not match.');
                        }
                        //Matching
                        else {

                            $('div.repeatMG').html('<i class="fa fa-check-circle" style="color: green; font-size: 20px; position: relative; top: 2px;"></i> Passwords match.');
                            $('button.chP').removeAttr('disabled');

                        }

                        $('div.repeatMG').slideDown();

                    } else {

                        $('input[name="new"]:password').focus();

                        $('div.repeatMG').html('<i class="fa fa-times-circle-o" style="color: red; font-size: 20px; position: relative; top: 2px;"></i> Please type new password first.');
                        $('div.repeatMG').slideDown();

                    }

                } else {

                    $('input[name="new"]:password').val("");
                    $('input[name="newrepeat"]:password').val("");

                    $('input[name="current"]:password').focus();

                    $('div.repeatMG').html('<i class="fa fa-times-circle-o" style="color: red; font-size: 20px; position: relative; top: 2px;"></i> Please type current password first.');
                    $('div.repeatMG').slideDown();

                }

                }, 1000);
            });


            //Delay Function
            var delay = (function(){
              var timer = 0;
              return function(callback, ms){
                clearTimeout (timer);
                timer = setTimeout(callback, ms);
              };
            })();
        </script>
