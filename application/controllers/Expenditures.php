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
		$crud->basic_model->set_query_str("SELECT Proj.Name, CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as FullName, Exp.* from `Expenditure` Exp
		LEFT OUTER JOIN `Project` Proj ON Proj.ProjID=Exp.Proj.ID
		LEFT OUTER JOIN `Person` Per ON Per.PerID=Exp.ApprovedBy");
		$crud->columns('Name', 'ExpName', 'Reason', 'Amount', 'FullName', 'SpentBy', 'Type');
		
		
		$output = $crud->render();

		$this->render($output);
	}
	
	public function add_person_expenditure(){
		
	}
}