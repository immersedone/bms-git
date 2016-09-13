<?php

	$this->set_css($this->default_theme_path.'/flexigrid/css/flexigrid.css');
	$this->set_js_lib($this->default_theme_path.'/flexigrid/js/jquery.form.js');
	$this->set_js_config($this->default_theme_path.'/flexigrid/js/flexigrid-edit.js');

	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
	$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
?>
<div class="flexigrid crud-form" style='width: 100%;' data-unique-hash="<?php echo $unique_hash; ?>">
	<div class="mDiv">
		<div class="ftitle">
			<div class='ftitle-left'>
				<?php echo $this->l('list_record'); ?> <?php echo $subject?>
			</div>
			<div class='clear'></div>
		</div>
		<div title="<?php echo $this->l('minimize_maximize');?>" class="ptogtitle">
			<span></span>
		</div>
	</div>
<div id='main-table-box'>
	<?php echo form_open( $read_url, 'method="post" id="crudForm"  enctype="multipart/form-data"'); ?>
	<div class='form-div'>
		<?php
		$counter = 0;
			foreach($fields as $field)
			{
				$even_odd = $counter % 2 == 0 ? 'odd' : 'even';
				$counter++;
		?>

		<?php 

		//Hack Read Template files to get the name of User/Project
		if($field->field_name === "ProjID") {
			//Get Project ID to convert to a name
			$projID = $input_fields["ProjID"]->input;
			$projID = str_replace("</div>", "", $projID);
			$projID = str_replace('<div id="field-ProjID" class="readonly_label">', "", $projID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/projects/index/getProjName/' . $projID .'",
					type: "POST",
					dataType: "json",
					success: function(data) {
						$("div#field-ProjID.readonly_label").text(data.ProjName);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		} elseif($field->field_name === "ApprovedBy") {
			//Get Project ID to convert to a name
			$perID = $input_fields["ApprovedBy"]->input;
			$perID = str_replace("</div>", "", $perID);
			$perID = str_replace('<div id="field-ApprovedBy" class="readonly_label">', "", $perID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/people/index/getPerName/' . $perID .'",
					type: "POST",
					dataType: "json",
					success: function(data) {
						$("div#field-ApprovedBy.readonly_label").text(data.PerName);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		} elseif($field->field_name === "SpentBy") {
			//Get Project ID to convert to a name
			$perID = $input_fields["SpentBy"]->input;
			$perID = str_replace("</div>", "", $perID);
			$perID = str_replace('<div id="field-SpentBy" class="readonly_label">', "", $perID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/people/index/getPerName/' . $perID .'",
					type: "POST",
					dataType: "json",
					success: function(data) {
						$("div#field-SpentBy.readonly_label").text(data.PerName);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		} elseif($field->field_name === "PerID") {
			//Get Project ID to convert to a name
			$perID = $input_fields["PerID"]->input;
			$perID = str_replace("</div>", "", $perID);
			$perID = str_replace('<div id="field-PerID" class="readonly_label">', "", $perID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/people/index/getPerName/' . $perID .'",
					type: "POST",
					dataType: "json",
					success: function(data) {
						$("div#field-PerID.readonly_label").text(data.PerName);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		}  elseif($field->field_name === "FundBodyID") {
			//Get Project ID to convert to a name
			$fbID = $input_fields["FundBodyID"]->input;
			$fbID = str_replace("</div>", "", $fbID);
			$fbID = str_replace('<div id="field-FundBodyID" class="readonly_label">', "", $fbID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/funding/index/getFBName/' . $fbID .'",
					type: "POST",
					dataType: "json",
					success: function(data) {
						$("div#field-FundBodyID.readonly_label").text(data.FBName);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		}  ?>
			
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
			<input type='button' value='<?php echo $this->l('form_back_to_list'); ?>' class="btn btn-large back-to-list" id="cancel-button" />
		</div>
		<div class='form-button-box'>
			<div class='small-loading' id='FormLoading'><?php echo $this->l('form_update_loading'); ?></div>
		</div>
		<div class='clear'></div>
	</div>
	<?php echo form_close(); ?>
</div>
</div>
<script>
	var validation_url = '<?php echo $validation_url?>';
	var list_url = '<?php echo $list_url?>';

	var message_alert_edit_form = "<?php echo $this->l('alert_edit_form')?>";
	var message_update_error = "<?php echo $this->l('update_error')?>";
</script>
