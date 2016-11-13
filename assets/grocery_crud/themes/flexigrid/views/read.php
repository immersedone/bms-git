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
		<?php 
			if(isset($_SERVER["REQUEST_URI"])) {
				$rQ = $_SERVER["REQUEST_URI"];

				$exp = explode('/', $rQ);
				$exp = array_map('strtolower', $exp);

				if(in_array('reimbursements', $exp) && in_array('read', $exp) && in_array('index', $exp)) {
					if(end($exp) == "" || is_nan(end($exp))) {
						array_pop($exp);
					} else {
						$id = end($exp);
					}
				} elseif(in_array('projects', $exp) && in_array('projread', $exp) && in_array('index', $exp) && in_array('list', $exp)) {
					$view = "projectList";
					$rQ = "";
				} else {
					$rQ = "";
				}
			}

		?>
		<?php if( $rQ !== ""): ?>
			<div class="printReimb" style="text-align:right;">
				<a href="/user/genreport/printreimb/<?php echo $id; ?>/1">Print Reimbursement</a>
				<a href="/user/genreport/printreimb/<?php echo $id; ?>/0">Print Reimbursement without Cover Page</a>
			</div>
		<?php endif;?>
		<div title="<?php echo $this->l('minimize_maximize');?>" class="ptogtitle<?php if($view=='projectList'){ echo '-0'; } ?>">
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

		} elseif($field->field_name === "ProjOne") {
			//Get Project ID to convert to a name
			$projID = $input_fields["ProjOne"]->input;
			$projID = str_replace("</div>", "", $projID);
			$projID = str_replace('<div id="field-ProjOne" class="readonly_label">', "", $projID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/projects/index/getProjName/' . $projID .'",
					type: "POST",
					dataType: "json",
					success: function(data) {
						$("div#field-ProjOne.readonly_label").text(data.ProjName);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		} elseif($field->field_name === "ProjTwo") {
			//Get Project ID to convert to a name
			$projID = $input_fields["ProjTwo"]->input;
			$projID = str_replace("</div>", "", $projID);
			$projID = str_replace('<div id="field-ProjTwo" class="readonly_label">', "", $projID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/projects/index/getProjName/' . $projID .'",
					type: "POST",
					dataType: "json",
					success: function(data) {
						$("div#field-ProjTwo.readonly_label").text(data.ProjName);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		} elseif($field->field_name === "ProjThree") {
			//Get Project ID to convert to a name
			$projID = $input_fields["ProjThree"]->input;
			$projID = str_replace("</div>", "", $projID);
			$projID = str_replace('<div id="field-ProjThree" class="readonly_label">', "", $projID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/projects/index/getProjName/' . $projID .'",
					type: "POST",
					dataType: "json",
					success: function(data) {
						$("div#field-ProjThree.readonly_label").text(data.ProjName);
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

		} elseif($field->field_name === "Supervisor") {
			//Get Project ID to convert to a name
			$perID = $input_fields["Supervisor"]->input;
			$perID = str_replace("</div>", "", $perID);
			$perID = str_replace('<div id="field-Supervisor" class="readonly_label">', "", $perID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/people/index/getPerName/' . $perID .'",
					type: "POST",
					dataType: "json",
					success: function(data) {
						$("div#field-Supervisor.readonly_label").text(data.PerName);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		} elseif($field->field_name === "ProjOne_Sup") {
			//Get Project ID to convert to a name
			$perID = $input_fields["ProjOne_Sup"]->input;
			$perID = str_replace("</div>", "", $perID);
			$perID = str_replace('<div id="field-ProjOne_Sup" class="readonly_label">', "", $perID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/people/index/getPerName/' . $perID .'",
					type: "POST",
					dataType: "json",
					success: function(data) {
						$("div#field-ProjOne_Sup.readonly_label").text(data.PerName);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		} elseif($field->field_name === "ProjTwo_Sup") {
			//Get Project ID to convert to a name
			$perID = $input_fields["ProjTwo_Sup"]->input;
			$perID = str_replace("</div>", "", $perID);
			$perID = str_replace('<div id="field-ProjTwo_Sup" class="readonly_label">', "", $perID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/people/index/getPerName/' . $perID .'",
					type: "POST",
					dataType: "json",
					success: function(data) {
						$("div#field-ProjTwo_Sup.readonly_label").text(data.PerName);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		} elseif($field->field_name === "ProjThree_Sup") {
			//Get Project ID to convert to a name
			$perID = $input_fields["ProjThree_Sup"]->input;
			$perID = str_replace("</div>", "", $perID);
			$perID = str_replace('<div id="field-ProjThree_Sup" class="readonly_label">', "", $perID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/people/index/getPerName/' . $perID .'",
					type: "POST",
					dataType: "json",
					success: function(data) {
						$("div#field-ProjThree_Sup.readonly_label").text(data.PerName);
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

		}   elseif($field->field_name === "SuburbID") {
			//Get Project ID to convert to a name
			$fbID = $input_fields["SuburbID"]->input;
			$fbID = str_replace("</div>", "", $fbID);
			$fbID = str_replace('<div id="field-SuburbID" class="readonly_label">', "", $fbID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/people/index/getSBName/' . $fbID .'",
					type: "POST",
					dataType: "json",
					success: function(data) {
						$("div#field-SuburbID.readonly_label").text(data.SBName);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		}   elseif($field->field_name === "EmpPosition") {
			//Get Project ID to convert to a name
			$fbID = $input_fields["EmpPosition"]->input;
			$fbID = str_replace("</div>", "", $fbID);
			$fbID = str_replace('<div id="field-EmpPosition" class="readonly_label">', "", $fbID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/people/index/getPosition/' . $fbID .'",
					type: "POST",
					dataType: "json",
					success: function(data) {
						$("div#field-EmpPosition.readonly_label").text(data.Position);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		}   elseif($field->field_name === "EmpSecPosition") {
			//Get Project ID to convert to a name
			$fbID = $input_fields["EmpSecPosition"]->input;
			$fbID = str_replace("</div>", "", $fbID);
			$fbID = str_replace('<div id="field-EmpSecPosition" class="readonly_label">', "", $fbID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/people/index/getPosition/' . $fbID .'",
					type: "POST",
					dataType: "json",
					success: function(data) {
						$("div#field-EmpSecPosition.readonly_label").text(data.Position);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		}   elseif($field->field_name === "LanguagesSpoken") {
			//Get Project ID to convert to a name
			$fbID = $input_fields["LanguagesSpoken"]->input;
			$fbID = str_replace("</div>", "", $fbID);
			$fbID = str_replace('<div id="field-LanguagesSpoken" class="readonly_label">', "", $fbID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/people/index/getLangName/",
					type: "POST",
					data: {"languages": "' . $fbID .'"},
					dataType: "json",
					success: function(data) {
						$("div#field-LanguagesSpoken.readonly_label").html(data.Languages);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		}   elseif($field->field_name === "DaysWork") {
			//Get Project ID to convert to a name
			$fbID = $input_fields["DaysWork"]->input;
			$fbID = str_replace("</div>", "", $fbID);
			$fbID = str_replace('<div id="field-DaysWork" class="readonly_label">', "", $fbID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/people/index/getDays/",
					type: "POST",
					data: {"days": "' . $fbID .'"},
					dataType: "json",
					success: function(data) {
						$("div#field-DaysWork.readonly_label").html(data.Days);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		}   elseif($field->field_name === "DaysAvailable") {
			//Get Project ID to convert to a name
			$fbID = $input_fields["DaysAvailable"]->input;
			$fbID = str_replace("</div>", "", $fbID);
			$fbID = str_replace('<div id="field-DaysAvailable" class="readonly_label">', "", $fbID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/people/index/getDays/",
					type: "POST",
					data: {"days": "' . $fbID .'"},
					dataType: "json",
					success: function(data) {
						$("div#field-DaysAvailable.readonly_label").html(data.Days);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		}   elseif($field->field_name === "NHACEClass") {
			//Get Project ID to convert to a name
			$fbID = $input_fields["NHACEClass"]->input;
			$fbID = str_replace("</div>", "", $fbID);
			$fbID = str_replace('<div id="field-NHACEClass" class="readonly_label">', "", $fbID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/people/index/getNHACE/",
					type: "POST",
					data: {"NHACE": "' . $fbID .'"},
					dataType: "json",
					success: function(data) {
						$("div#field-NHACEClass.readonly_label").html(data.NHACE);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		}   elseif($field->field_name === "BGCSDepartment") {
			//Get Project ID to convert to a name
			$fbID = $input_fields["BGCSDepartment"]->input;
			$fbID = str_replace("</div>", "", $fbID);
			$fbID = str_replace('<div id="field-BGCSDepartment" class="readonly_label">', "", $fbID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/people/index/getBGCS/",
					type: "POST",
					data: {"BGCS": "' . $fbID .'"},
					dataType: "json",
					success: function(data) {
						$("div#field-BGCSDepartment.readonly_label").html(data.BGCS);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		}   elseif($field->field_name === "ProjOne_Dep") {
			//Get Project ID to convert to a name
			$fbID = $input_fields["ProjOne_Dep"]->input;
			$fbID = str_replace("</div>", "", $fbID);
			$fbID = str_replace('<div id="field-ProjOne_Dep" class="readonly_label">', "", $fbID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/people/index/getBGCS/",
					type: "POST",
					data: {"BGCS": "' . $fbID .'"},
					dataType: "json",
					success: function(data) {
						$("div#field-ProjOne_Dep.readonly_label").html(data.BGCS);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		}   elseif($field->field_name === "ProjTwo_Dep") {
			//Get Project ID to convert to a name
			$fbID = $input_fields["ProjTwo_Dep"]->input;
			$fbID = str_replace("</div>", "", $fbID);
			$fbID = str_replace('<div id="field-ProjTwo_Dep" class="readonly_label">', "", $fbID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/people/index/getBGCS/",
					type: "POST",
					data: {"BGCS": "' . $fbID .'"},
					dataType: "json",
					success: function(data) {
						$("div#field-ProjTwo_Dep.readonly_label").html(data.BGCS);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		}   elseif($field->field_name === "ProjThree_Dep") {
			//Get Project ID to convert to a name
			$fbID = $input_fields["ProjThree_Dep"]->input;
			$fbID = str_replace("</div>", "", $fbID);
			$fbID = str_replace('<div id="field-ProjThree_Dep" class="readonly_label">', "", $fbID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/people/index/getBGCS/",
					type: "POST",
					data: {"BGCS": "' . $fbID .'"},
					dataType: "json",
					success: function(data) {
						$("div#field-ProjThree_Dep.readonly_label").html(data.BGCS);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		}   elseif($field->field_name === "AnnualLeave") {
			//Get Project ID to convert to a name
			$fbID = $input_fields["AnnualLeave"]->input;
			$fbID = str_replace("</div>", "", $fbID);
			$fbID = str_replace('<div id="field-AnnualLeave" class="readonly_label">', "", $fbID);
			//Echo out HTML AJAX for name conversion
			switch($fbID) {
				case "AL_4":
					$data = "Annual Leave - 4 Weeks";
					break;
				case "AL_5":
					$data = "Annual Leave - 5 Weeks";
					break;
				case "AL_6":
					$data = "Annual Leave - 6 Weeks";
					break;
			}
			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				var data = "' . $data .'";
				$("div#field-AnnualLeave.readonly_label").text(data);
			});
			</script>';

			echo $ajaxHTML;

		}   elseif($field->field_name === "PersonalLeave") {
			//Get Project ID to convert to a name
			$fbID = $input_fields["PersonalLeave"]->input;
			$fbID = str_replace("</div>", "", $fbID);
			$fbID = str_replace('<div id="field-PersonalLeave" class="readonly_label">', "", $fbID);
			//Echo out HTML AJAX for name conversion
			switch($fbID) {
				case "PL_1":
					$data = "Personal Leave - Stage 1";
					break;
				case "PL_2":
					$data = "Personal Leave - Stage 2";
					break;
				case "PL_3":
					$data = "Personal Leave - Stage 3";
					break;
			}
			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				var data = "' . $data .'";
				$("div#field-PersonalLeave.readonly_label").text(data);
			});
			</script>';

			echo $ajaxHTML;

		}   elseif($field->field_name === "Contract") {
			//Get Project ID to convert to a name
			$fbID = $input_fields["Contract"]->input;
			$fbID = str_replace("</div>", "", $fbID);
			$fbID = str_replace('<div id="field-Contract" class="readonly_label">', "", $fbID);
			//Echo out HTML AJAX for name conversion
			switch($fbID) {
				case "FULL_TIME":
					$data = "Full Time";
					break;
				case "PART_TIME":
					$data = "Part Time";
					break;
				case "CASUAL":
					$data = "Casual";
					break;
				case "INDE_CONT":
					$data = "Independant Contractor";
					break;
			}
			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				var data = "' . $data .'";
				$("div#field-Contract.readonly_label").text(data);
			});
			</script>';

			echo $ajaxHTML;

		}   elseif($field->field_name === "ContStatus") {
			//Get Project ID to convert to a name
			$fbID = $input_fields["ContStatus"]->input;
			$fbID = str_replace("</div>", "", $fbID);
			$fbID = str_replace('<div id="field-ContStatus" class="readonly_label">', "", $fbID);
			//Echo out HTML AJAX for name conversion
			switch($fbID) {
				case "PERMANENT":
					$data = "Permanent";
					break;
				case "FIXED_TERM":
					$data = "Fixed Term";
					break;
			}
			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				var data = "' . $data .'";
				$("div#field-ContStatus.readonly_label").text(data);
			});
			</script>';

			echo $ajaxHTML;

		} elseif($field->field_name === "ExpType") {
			//Get Project ID to convert to a name
			$perID = $input_fields["ExpType"]->input;
			$perID = str_replace("</div>", "", $perID);
			$perID = str_replace('<div id="field-ExpType" class="readonly_label">', "", $perID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/expenditures/index/getExpName/' . $perID .'",
					type: "POST",
					dataType: "json",
					success: function(data) {
						$("div#field-ExpType.readonly_label").text(data.ExpName);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		} elseif($field->field_name === "Role") {
			//Get Project ID to convert to a name
			$roleID = $input_fields["Role"]->input;
			$roleID = str_replace("</div>", "", $roleID);
			$roleID = str_replace('<div id="field-Role" class="readonly_label">', "", $roleID);
			//Echo out HTML AJAX for name conversion

			$ajaxHTML = '<script type="text/javascript">
			$(function() {
				$.ajax({
					url: "'. base_url() .'user/people/index/getRole/' . $roleID .'",
					type: "POST",
					dataType: "json",
					success: function(data) {
						$("div#field-Role.readonly_label").text(data.RoleName);
					}

				});
			});
			</script>';

			echo $ajaxHTML;

		}    ?>
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
	<?php if($view !== "projectList"): ?>
	<div class="pDiv">
		<div class='form-button-box'>
			<input type='button' value='<?php echo $this->l('form_back_to_list'); ?>' class="btn btn-large back-to-list" id="cancel-button" />
		</div>
		<div class='form-button-box'>
			<div class='small-loading' id='FormLoading'><?php echo $this->l('form_update_loading'); ?></div>
		</div>
		<div class='clear'></div>
	</div>
	<?php endif; ?>
	<?php echo form_close(); ?>
</div>
</div>
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
		} else if (in_array("employee", $fullURL) && in_array("prjvw", $fullURL) || in_array("volunteer", $fullURL) && in_array("prjvw", $fullURL)) {
			$list_url = $_SERVER['HTTP_REFERER'];
		}

	?>
	var validation_url = '<?php echo $validation_url?>';
	var list_url = '<?php echo $list_url?>';

	var message_alert_edit_form = "<?php echo $this->l('alert_edit_form')?>";
	var message_update_error = "<?php echo $this->l('update_error')?>";
	
	$('.readonly_label').each(function() {
		if($.trim($(this).text()) === "" || $(this).text() === "&nbsp;") {
			$(this).text("N/A");
		}
	});

</script>
