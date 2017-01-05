<?php

	$this->set_css($this->default_theme_path.'/flexigrid/css/flexigrid.css');

    $this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.form.min.js');
	$this->set_js_config($this->default_theme_path.'/flexigrid/js/flexigrid-edit.js');

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
?>
<div class="flexigrid crud-form" style='width: 100%;' data-unique-hash="<?php echo $unique_hash; ?>">
	<div class="mDiv">
		<div class="ftitle">
			<div class='ftitle-left'>
				<?php echo $this->l('form_edit'); ?> <?php echo $subject?>
			</div>
			<div class='clear'></div>
		</div>
		<div title="<?php echo $this->l('minimize_maximize');?>" class="ptogtitle">
			<span></span>
		</div>
	</div>
<div id='main-table-box'>
	<?php echo form_open( $update_url, 'method="post" id="crudForm"  enctype="multipart/form-data"'); ?>
	<div class='form-div'>
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
						    var Per = $("#field-PerID").html();
						    var csrf_token = Cookies.get('csrf_cookie');
						    //alert(Per);
						    
						    if(value === "") {
						    	return true;
						    }

						    $.ajax({
						    	url: "<?php echo base_url(); ?>user/expenditures/index/getExpBy/" + value,
						    	type: "POST",
						    	data: { "csrf_token": csrf_token},
						    	dataType: "json",
						    	success: function(data) {
						    		$("#field-ExpList option[value='"+value+"']").attr("data-expby", data.ExpBy);
						    		if(data.ExpBy !== Per) {
						    			$("#field-ExpList option[value='"+value+"']").hide();
						    		}
						    		$("#field-ExpList").trigger("chosen:updated");
						    	}
						    });

						});
						
					});
				</script>
			<?php } elseif($field->field_name === "PerID") {
			//Get Project ID to convert to a name
			$perID = $input_fields["PerID"]->input;
			$perID = str_replace("</div>", "", $perID);
			$perID = str_replace('<div id="field-PerID" class="readonly_label">', "", $perID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				var csrf_token = Cookies.get("csrf_cookie");
				$.ajax({
					url: "'. base_url() .'user/people/index/getPerName/' . $perID .'",
					type: "POST",
					data: { "csrf_token": csrf_token},
					dataType: "json",
					success: function(data) {
						$("div#field-PerID.readonly_label").attr("data-expby", "' . $perID .'");
						$("div#field-PerID.readonly_label").text(data.PerName);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		}?>
			<div class='form-field-box <?php echo $even_odd?>' id="<?php echo $field->field_name; ?>_field_box">
				<div class='form-display-as-box' id="<?php echo $field->field_name; ?>_display_as_box">
					<?php echo $input_fields[$field->field_name]->display_as?><?php echo ($input_fields[$field->field_name]->required)? "<span class='required'>*</span> " : ""?> :
				</div>
				<div class='form-input-box' id="<?php echo $field->field_name; ?>_input_box">
					<?php echo $input_fields[$field->field_name]->input?>
				</div>
				<div class='clear'></div>
			</div>
		<?php }?>
		<?php if(!empty($hidden_fields)){?>
		<!-- Start of hidden inputs -->
			<?php
				foreach($hidden_fields as $hidden_field){
					echo $hidden_field->input;
				}
			?>
		<!-- End of hidden inputs -->
		<?php }?>
		<?php if ($is_ajax) { ?><input type="hidden" name="is_ajax" value="true" /><?php }?>
		<div id='report-error' class='report-div error'></div>
		<div id='report-success' class='report-div success'></div>
	</div>
	<div class="pDiv">
		<div class='form-button-box'>
			<input  id="form-button-save" type='submit' value='<?php echo $this->l('form_update_changes'); ?>' class="btn btn-large"/>
		</div>
<?php 	if(!$this->unset_back_to_list) { ?>
		<div class='form-button-box'>
			<input type='button' value='<?php echo $this->l('form_update_and_go_back'); ?>' id="save-and-go-back-button" class="btn btn-large"/>
		</div>
		<div class='form-button-box'>
			<input type='button' value='<?php echo $this->l('form_cancel'); ?>' class="btn btn-large" id="cancel-button" />
		</div>
<?php 	} ?>
		<div class='form-button-box'>
			<div class='small-loading' id='FormLoading'><?php echo $this->l('form_update_loading'); ?></div>
		</div>
		<div class='clear'></div>
	</div>
	<?php echo form_close(); ?>
</div>
</div>
<?php
//echo $subject;
if($subject === "Reimbursement") { ?>
	<script type="text/javascript">
	$(window).on("load", function() {
		var selVal = $("#field-PerID.readonly_label").html();
		//alert(selVal);
		$("#field-ApprovedBy option[value='" + selVal + "']").attr("disabled", "disabled");
		$("#field-ApprovedBy").trigger("chosen:updated");
	});
	</script>
<?php } ?>
<script>

	<?php
		//Check to see if it is coming from project View
		$fullURL = explode('/', $_SERVER['REQUEST_URI']);
		if(end($fullURL) === "") {
	    	array_pop($fullURL);
		} 

		$fullURL = array_map('strtolower', $fullURL);

		if(in_array("mileproj", $fullURL) || in_array("expendproj", $fullURL) || in_array("fundproj", $fullURL) || in_array("empproj", $fullURL)
			|| in_array("volproj", $fullURL)) {
			$list_url = base_url() . 'user/projects/index/projread/list';
			echo 'var success_list_url = "'.base_url().'user/projects/index/projread/list";';
			$page = "PROJECT_VIEW";
		}

		if(in_array("people", $fullURL) && in_array("index", $fullURL) && in_array("edit", $fullURL)) {

			$page = "PEOPLE_EDIT";
		}

	?>
	var validation_url = '<?php echo $validation_url?>';
	var list_url = '<?php echo $list_url?>';

	var isProjectView = <?php if (isset($page) && $page === "PROJECT_VIEW") { echo "true";} else { echo "false"; } ?>;

	var message_alert_edit_form = "<?php echo $this->l('alert_edit_form')?>";
	var message_update_error = "<?php echo $this->l('update_error')?>";
</script>
<?php if(isset($page) && $page == "PEOPLE_EDIT"): ?>
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