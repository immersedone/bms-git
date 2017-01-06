<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Options extends MY_Controller {

	public function __construct()
	{
		parent::__construct();


		$this->load->library('grocery_CRUD');
	}

	public function index()
	{


		//$this->render((object)array('output' => '' , 'js_files' => array() , 'css_files' => array(), 'options' => ''));
		$this->options();

	}

	public function render($output = null) {
		$this->load->view('options', $output);
	}

	public function options() {
		$crud = new grocery_CRUD();
		$crud->set_model('Extended_generic_model');
		$crud->set_table('OptionType');
		$crud->set_subject('Options');
		$crud->basic_model->set_query_str("SELECT * FROM (SELECT * FROM OptionType WHERE type!='Availability' AND type!='Role') x");
		$crud->columns('type', 'data');
		$crud->set_read_fields('type', 'data');
		$crud->edit_fields('type', 'data');
		$crud->add_fields('type', 'data');
		$crud->display_as('type', 'Option Type');
		$crud->display_as('data', 'Option Value');

		$state = $crud->getState();
		$state_info = $crud->getStateInfo();

		if($state == 'edit') {
			$crud->field_type('type', 'readonly');
		} else if($state === "ajax_list" || $state === "ajax_list_info") {

			$identifiers = array(
				"NHACE_CLASS" => "NHACE Classification",
				"FA_QUAL" => "First Aid Qualification",
				"Position" => "Position",
				"BGCS_DEP" => "BGCS Department",
				"SKILLS_EXP" => "Skills & Experience",
				"QUAL_STUD" => "Qualifications & Current Studies",
				"EXP_TYPE" => "Expenditure Type",
				"SPR_FND" => "Superannuation Fund",
				"COMP_NAME" => "Company Name",
			);

			if($_POST["search_text"] !== "" && $_POST["search_field"] === "type") {
				//When searching Options, convert strings to identifiers

				//Calculate similarity percentages for each identifier stored
				//in DB against search_text
				


				$percentages = array();
				foreach($identifiers as $key => $value) {
					similar_text($_POST["search_text"], $value, $perc);

					$percentages[$key] = $perc;
				}

				asort($percentages);

				if(end($percentages) > 60) {

					$_POST["search_text"] = array_search(end($percentages), $percentages);

				}


				
			}

			$crud->field_type("type", "dropdown", $identifiers);

		} else {

			//Allow only specific options
			$optArr = array(
				"NHACE_CLASS" => "NHACE Classification",
				"FA_QUAL" => "First Aid Qualification",
				"Position" => "Position",
				"BGCS_DEP" => "BGCS Department",
				"SKILLS_EXP" => "Skills & Experience",
				"QUAL_STUD" => "Qualifications & Current Studies",
				"EXP_TYPE" => "Expenditure Type",
				"SPR_FND" => "Superannuation Fund",
				"COMP_NAME" => "Company Name",
			);

			$crud->field_type("type", "dropdown", $optArr);

		}
		$crud->required_fields(
		'type',
		'data'
		);

		$output = $crud->render();	

		$this->render($output);

	}


}