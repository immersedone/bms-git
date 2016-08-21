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
		$crud->set_theme('flexigrid');
		$crud->set_relation("PerID", "PersonProject", "PerID"); //, array("Role"=>"VOLUNTEER")
		$crud->where("Role", "VOLUNTEER");
		$crud->set_table('Person');
		$crud->set_subject('Volunteer');
		
		$crud->columns('ProjID', 'FirstName', 'LastName', 'Address', 'SuburbID', 'WorkEmail', 'PersonalEmail', 'Mobile', 'HomePhone', 'PerID');
		$crud->add_fields('FirstName', 'MiddleName', 'LastName', 'Address', 'SuburbID', 'WorkEmail', 'PersonalEmail', 'Mobile', 'HomePhone', 'Status', 'DateStarted', 'WWC', 'WWCFiled', 'Username');
		$crud->edit_fields('FirstName', 'MiddleName', 'LastName', 'Address', 'SuburbID', 'WorkEmail', 'PersonalEmail', 'Mobile', 'HomePhone', 'Status', 'DateStarted', 'DateFinished', 'WWC', 'WWCFiled', 'Username');
		$crud->display_as('FirstName', 'First Name');
		$crud->display_as('MiddleName', 'Middle Name');
		$crud->display_as('LastName', 'Last Name');
		$crud->display_as('WorkEmail', 'Work Email');
		$crud->display_as('PersonalEmail', 'Personal Email');
		$crud->display_as('HomePhone', 'Home Phone');
		$crud->display_as('DateStarted', 'Date Started');
		$crud->unset_add();
		
		$output = $crud->render();

		$this->render($output);
	}
}