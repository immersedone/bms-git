<?php

class Expenditures extends CI_Controller {

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
		$this->expenditures();
	}

	public function render($output = null) {
		$this->load->view('expenditures', $output);
	}

	public function expenditures() {

		$crud = new grocery_CRUD();
		$crud->set_model('Extended_generic_model'); 
		$crud->set_table('Expenditure');
		$crud->set_subject('Expenditure');
		$crud->basic_model->set_query_str('SELECT Proj.Name, CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as FullName, Exp.* from `Expenditure` Exp
		LEFT OUTER JOIN `Project` Proj ON Proj.ProjID=Exp.ProjID
		LEFT OUTER JOIN `Person` Per ON Per.PerID=Exp.ApprovedBy');
		$crud->columns('Name', 'ExpName', 'Reason', 'Amount', 'FullName', 'SpentBy', 'Type');
		$crud->add_fields('Name', 'ExpName', 'Reason', 'Amount', 'FullName', 'SpentBy', 'Type');
		$crud->display_as('ExpName', 'Expenditure Name');
		$crud->display_as('FullName', 'Full Name');
		$crud->display_as('SpentBy', 'Spent By');
		$crud->display_as('Name', 'Project Name');

		$projects = $crud->basic_model->return_query("SELECT ProjID, Name FROM Project");

		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->Name];
		}

		$crud->field_type("Name", "dropdown", $prjArr);

		//Call Model to get the User's Full Names
		$users = $crud->basic_model->return_query("SELECT PerID, CONCAT(FirstName, ' ', MiddleName, ' ', LastName) as FullName FROM Person");

		//Convert Return Object into Associative Array
		$usrArr = array();
		foreach($users as $usr) {
			$usrArr += [$usr->PerID => $usr->FullName];
		}
		
		//Change the field type to a dropdown with values
		//to add to the relational table
		$crud->field_type("SpentBy", "dropdown", $usrArr);
		$crud->field_type("FullName", "dropdown", $usrArr);
		
		
		$output = $crud->render();

		$this->render($output);
	}
	
	public function add_person_expenditure(){
		
	}
}