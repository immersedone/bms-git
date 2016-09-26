<?php

class Options extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

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
		$crud->basic_model->set_query_str("SELECT * FROM OptionType WHERE type!='Availability' AND type!='Role'");
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
				"SPR_FND" => "Superannuation Fund"
			);

			$crud->field_type("type", "dropdown", $optArr);

		}


		$output = $crud->render();	

		$this->render($output);

	}


}