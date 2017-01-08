<?php

	$this->set_css($this->default_theme_path.'/flexigrid/css/flexigrid.css');
	$this->set_js_lib($this->default_theme_path.'/flexigrid/js/jquery.form.js');
    $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.form.min.js');
	$this->set_js_config($this->default_theme_path.'/flexigrid/js/flexigrid-add.js');

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');

	if(isset($subject)) {
		switch($subject) {
		    case "Project Details":
		        $gridNo = 1;
		        break;
		    case "Milestone":
		        $gridNo = 2;
		        break;
		    case "Expenditure":
		        $gridNo = 3;
		        break;
		    case "Funding":
		        $gridNo = 4;
		        break;
		    case "Volunteers":
		        $gridNo = 5;
		        break;
		    case "Volunteer";
		        $gridNo = 5;
		        break;
		    case "Employee":
		        $gridNo = 6;
		        break;
		}
	}
?>

<?php 
    $fullURL = explode('/', $_SERVER['REQUEST_URI']);
        if(end($fullURL) === "") {
            array_pop($fullURL);
        } 

        if($fullURL[0] === "") {
            array_shift($fullURL);
        }
        if(strtolower($fullURL[0]) === "user" && strtolower($fullURL[1]) === "projects" && strtolower($fullURL[2]) === "index" && strtolower($fullURL[3]) === "projread") {
            $page = "PROJECT_VIEW";
        }

        if(strtolower($fullURL[0]) === "user" && strtolower($fullURL[1]) === "people" && strtolower($fullURL[2]) === "index" && strtolower($fullURL[3]) === "add") {
        	$page = "PEOPLE_ADD";
        }

        if(strtolower($fullURL[0]) === "user" && strtolower($fullURL[1]) === "reimbursements" && strtolower($fullURL[2]) === "index" && strtolower($fullURL[3]) === "add") {
        	$page = "REIMB_ADD";
        }
    ?>
<div class="flexigrid crud-form" style='width: 100%;' data-unique-hash="<?php echo $unique_hash; ?>">
	<div class="mDiv">
		<div class="ftitle">
			<div class='ftitle-left'>
				<?php echo $this->l('form_add'); ?> <?php echo $subject?>
			</div>
			<div class='clear'></div>
		</div>
		<div title="<?php echo $this->l('minimize_maximize');?>" class="ptogtitle">
			<span></span>
		</div>
	</div>
<div id='main-table-box'>
	<?php echo form_open( $insert_url, 'method="post" id="crudForm"  enctype="multipart/form-data"'); ?>
		<div class='form-div'>
			<?php if($subject === "Reimbursement") { ?>
				<script>
					$(function() {
						$("#field-FullName").on('change', function() {
							var value = $(this).val();

							//Enable All Fields (to clear previous disables)
							$("#field-ApprovedBy option").prop("disabled", false);
							$("#field-ExpList option").each(function(i) {
								$(this).show();
							});

							//Disable Fields
							$("#field-ApprovedBy option[value='"+value+"']").attr("disabled", "disabled");
							$("#field-ExpList option").each(function(i) {
								if($(this).data("expby") != value) {
									$(this).hide();
								}
							});

							//Change Approved By Selection to Nothing
							$("#field-ApprovedBy").val($("#field-ApprovedBy option:first").val());
							$("#field-ApprovedBy").trigger("chosen:updated");
							$("#field-ExpList").val('').trigger("chosen:updated");
							$(".chosen-multiple-select").trigger("chosen:updated");
						});
					});
				</script>
			<?php } ?>
			<?php
			$counter = 0;
				foreach($fields as $field)
				{
					$even_odd = $counter % 2 == 0 ? 'odd' : 'even';
					$counter++;
			?>

			<?php if($field->field_name === "ExpList") { ?>
				<script>
					$(function() {
						$("#field-ExpList > option").each(function(i) {
						    var value = $(this).val();
						    var csrf_token = Cookies.get('csrf_cookie');
						    if(value === "") {
						    	return true;
						    }

						    $.ajax({
						    	url: "<?php echo base_url(); ?>user/expenditures/index/getExpBy/" + value,
						    	type: "POST",
						    	data: {"csrf_token": csrf_token},
						    	dataType: "json",
						    	success: function(data) {
						    		$("#field-ExpList option[value='"+value+"']").attr("data-expby", data.ExpBy);
						    	}
						    });
						});
					});
				</script>
			<?php } ?>
			<div class='form-field-box <?php echo $even_odd?>' id="<?php echo $field->field_name; ?>_field_box">
				<div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box">
					<?php echo $input_fields[$field->field_name]->display_as; ?><?php echo ($input_fields[$field->field_name]->required)? "<span class='required'>*</span> " : ""; ?> :
				</div>
				<div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
					<?php echo $input_fields[$field->field_name]->input?>
				</div>
				<div class='clear'></div>
			</div>
			<?php }?>
			<!-- Start of hidden inputs -->
				<?php
					foreach($hidden_fields as $hidden_field){
						echo $hidden_field->input;
					}
				?>
			<!-- End of hidden inputs -->
			<?php if ($is_ajax) { ?><input type="hidden" name="is_ajax" value="true" /><?php }?>

			<div id='report-error' class='report-div error'></div>
			<div id='report-success' class='report-div success'></div>
		</div>
		<div class="pDiv">
			<div class='form-button-box'>
				<input id="form-button-save" type='submit' value='<?php echo $this->l('form_save'); ?>'  class="btn btn-large"/>
			</div>
<?php 	if(!$this->unset_back_to_list) { ?>
			<div class='form-button-box'>
				<input type='button' value='<?php echo $this->l('form_save_and_go_back'); ?>' id="save-and-go-back-button"  class="btn btn-large"/>
			</div>
			<?php if(isset($page) && $page === "REIMB_ADD"): ?>
			<div class='form-button-box'>
				<input type='button' value='<?php echo $this->l('form_save_and_print'); ?>' id="save-and-print-button"  class="btn btn-large"/>
			</div>
			<div class='form-button-box'>
				<input type='button' value='<?php echo $this->l('form_save_and_print_no'); ?>' id="save-and-print-no-button"  class="btn btn-large"/>
			</div>
			<?php endif; ?>
			<div class='form-button-box'>
				<input type='button' value='<?php echo $this->l('form_cancel'); ?>' class="btn btn-large" id="cancel-button" />
			</div>
<?php 	} ?>
			<div class='form-button-box'>
				<div class='small-loading' id='FormLoading'><?php echo $this->l('form_insert_loading'); ?></div>
			</div>
			<div class='clear'></div>
		</div>
	<?php echo form_close(); ?>
</div>
</div>
<script>
	
	
		<?php if(isset($page) && $page !== "" && $page === "PROJECT_VIEW"): ?>
			var list_url = '<?php echo base_url(). 'user/projects/index/projread/list';?>';
			var success_list_url = "<?php echo base_url().'user/projects/index/projread/list' ?>";
			var validation_url = '<?php echo base_url() . "/user/projects/index/projread/" . $gridNo . "/" . $_COOKIE['projID'] . "/insert_validation";?>';
		<?php else: ?>
			var list_url = '<?php echo $list_url; ?>';
			var validation_url = '<?php echo $validation_url?>';
		<?php endif; ?>
	
	var isProjectView = <?php if (isset($page) && $page === "PROJECT_VIEW") { echo "true";} else { echo "false"; } ?>;

	var message_alert_add_form = "<?php echo $this->l('alert_add_form')?>";
	var message_insert_error = "<?php echo $this->l('insert_error')?>";
</script>


<?php if(isset($page) && $page == "PEOPLE_ADD"): ?>
<script>
$(document).ready(function() {
	//Custom DOB Date Range
	var dateInput = $('input[name="DateofBirth"]');
	dateInput.datepicker("destroy");
	dateInput.datepicker({
		yearRange: "-100:+0",
		dateFormat: "yy-mm-dd",
		changeMonth: true,
		changeYear: true
	});
});
</script>
<?php endif; ?>