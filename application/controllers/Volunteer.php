<?php

class Volunteer extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
	}

	public function index()
	{
		//$this->render((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
		$this->volunteer();
	}

	public function render($output = null) {
		$this->load->view('volunteer', $output);
	}

	public function volunteer() {

		$crud = new grocery_CRUD();
		$crud->set_model('Volunteer_GC');
		$crud->set_table('Person');
		$crud->set_subject('Volunteer');
		$crud->basic_model->set_query_str('SELECT `Project`.Name, `PersonProject`.Role as ProjRole, `Person`.* FROM `Person` LEFT OUTER JOIN `PersonProject` ON `Person`.PerID=`PersonProject`.PerID LEFT OUTER JOIN `Project` ON `PersonProject`.ProjID=`Project`.ProjID WHERE `PersonProject`.Role="VOLUNTEER"');
		$crud->columns("Name", "ProjRole", "FirstName", "LastName", "Address", "SuburbID", "WorkEmail", "PersonalEmail", "Mobile", "HomePhone");
		$crud->display_as("Name", "Project Name");
		$crud->display_as("ProjRole", "Project Role");
		$crud->unset_add();
		$crud->unset_edit();

		$output = $crud->render();

		$this->render($output);
	}
}