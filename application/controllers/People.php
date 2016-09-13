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
		$crud->set_model('Extended_generic_model');
		$crud->set_table('Person');
		$crud->set_subject('Person');
		$crud->basic_model->set_query_str('SELECT Sub.SuburbName as SubName, Sub.Postcode as Postcode, Per.* from `Person` Per
		LEFT OUTER JOIN `Suburb` Sub ON Per.SuburbID=Sub.SuburbID', " GROUP BY FirstName, LastName, Address");
		$crud->columns('FirstName', 'LastName', 'Address', 'Postcode', 'SubName', 'WorkEmail', 'PersonalEmail', 'Mobile', 'HomePhone');
		$crud->add_fields('FirstName', 'MiddleName', 'LastName', 'Address', 'SuburbID', 'WorkEmail', 'PersonalEmail', 'Mobile', 'HomePhone', 'Status', 'DateStarted', 'WWC', 'WWCFiled', 'Username', 'Role');
		$crud->edit_fields('FirstName', 'MiddleName', 'LastName', 'Address', 'SuburbID', 'WorkEmail', 'PersonalEmail', 'Mobile', 'HomePhone', 'Status', 'DateStarted', 'DateFinished', 'WWC', 'WWCFiled', 'Username', 'Role');	
		$crud->display_as('FirstName', 'First Name');
		$crud->display_as('MiddleName', 'Middle Name');
		$crud->display_as('LastName', 'Last Name');
		$crud->display_as('SuburbID', 'Suburb');
		$crud->display_as('WorkEmail', 'Work Email');
		$crud->display_as('PersonalEmail', 'Personal Email');
		$crud->display_as('HomePhone', 'Home Phone');
		$crud->display_as('DateStarted', 'Date Started');
		$crud->display_as('SubName', 'Suburb');
	
		//Call Model to get the Project Names
		$suburbs = $crud->basic_model->return_query("SELECT SuburbID, CONCAT(Postcode, ' - ', SuburbName) as FullSub FROM Suburb");
		
		//Convert Return Object into Associative Array
		$subArr = array();
		foreach($suburbs as $sub) {
			$subArr += [$sub->SuburbID => $sub->FullSub];
		}

		//Change the field type to a dropdown with values
		//to add to the relational table
		$crud->field_type("SuburbID", "dropdown", $subArr);
				
		
		
		
		$output = $crud->render();

		$this->render($output);
	}

	public function getPerName($id) {

		$crud = new grocery_CRUD();
		$crud->set_model('Project_GC');
		$res = $crud->basic_model->return_query("SELECT CONCAT(FirstName, ' ', MiddleName, ' ', LastName) as PerName FROM Person WHERE PerID='$id' LIMIT 1");

		$resp = array();
		$resp["PerName"] = $res[0]->PerName;
		echo json_encode($resp);
	}

}