<?php

class People extends CI_Controller {

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
		$this->all_people();
	}

	public function render($output = null) {
		$this->load->view('people', $output);
	}

	public function all_people() {

		$crud = new grocery_CRUD();
		$crud->set_model('Person_GC');
		$crud->set_table('Person');
		$crud->set_subject('Person');
		$crud->basic_model->set_query_str('SELECT Sub.SuburbName as SubName, Per.* from `Person` Per
		OUTER LEFT JOIN `Suburb` Sub ON Per.SuburbID = Sub.Postcode
		ORDER BY LastName');
		$crud->columns('FirstName', 'LastName', 'Address', 'SuburbID', 'SubName', 'WorkEmail', 'PersonalEmail', 'Mobile', 'HomePhone');
		$crud->add_fields('FirstName', 'MiddleName', 'LastName', 'Address', 'SuburbID', 'WorkEmail', 'PersonalEmail', 'Mobile', 'HomePhone', 'Status', 'DateStarted', 'WWC', 'WWCFiled', 'Username', 'Role');
		$crud->edit_fields('FirstName', 'MiddleName', 'LastName', 'Address', 'SuburbID', 'WorkEmail', 'PersonalEmail', 'Mobile', 'HomePhone', 'Status', 'DateStarted', 'DateFinished', 'WWC', 'WWCFiled', 'Username', 'Role');
		$crud->display_as('FirstName', 'First Name');
		$crud->display_as('MiddleName', 'Middle Name');
		$crud->display_as('LastName', 'Last Name');
		$crud->display_as('WorkEmail', 'Work Email');
		$crud->display_as('PersonalEmail', 'Personal Email');
		$crud->display_as('HomePhone', 'Home Phone');
		$crud->display_as('DateStarted', 'Date Started');
		$crud->display_as('SubName', 'Suburb');
		$crud->display_as('SuburbID', 'Postcode');

		$output = $crud->render();

		$this->render($output);
	}

}