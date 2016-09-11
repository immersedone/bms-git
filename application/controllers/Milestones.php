<?php

class Milestones extends CI_Controller {

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
		$this->milestones();
	}

	public function render($output = null) {
		$this->load->view('milestones', $output);
	}

	public function milestones() {

		$crud = new grocery_CRUD();
		$crud->set_model('Extended_generic_model'); 
		$crud->set_table('Milestone');
		$crud->set_subject('Milestone');
		$crud->basic_model->set_query_str('SELECT P.Name as ProjName, M.* from `Milestone` M
		LEFT OUTER JOIN `Project` P on M.ProjID=P.ProjID');
			
		$crud->columns('ProjName', 'Title', 'Description', 'StartDate', 'FinishDate','MSComplete');
		$crud->display_as('StartDate', 'Start Date');
		$crud->display_as('FinishDate', 'Finish Date');
		$crud->display_as('ProjID', 'Project Name');
		$crud->display_as('ProjName', 'Project Name');
		$crud->display_as('MSComplete', 'Complete');
		$crud->add_fields('ProjID', 'Title', 'Description', 'StartDate', 'FinishDate');
		$crud->callback_column('MSComplete', array($this, 'check_complete'));
		$crud->callback_column('MSComplete', array($this, 'field_width'));

		$projects = $crud->basic_model->return_query("SELECT ProjID, Name FROM Project");

		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->Name];
		}

		$crud->field_type("ProjID", "dropdown", $prjArr);
		
		$output = $crud->render();

		$this->render($output);
	}

	public function mileproj($id) {

		$crud = new grocery_CRUD();
		$crud->set_model('Extended_generic_model'); 
		$crud->set_table('Milestone');
		$crud->set_subject('Milestone');
		$crud->basic_model->set_query_str('SELECT P.Name as ProjName, M.* from `Milestone` M
		LEFT OUTER JOIN `Project` P on M.ProjID=P.ProjID');
			
		$crud->columns('ProjName', 'Title', 'Description', 'StartDate', 'FinishDate');
		$crud->display_as('StartDate', 'Start Date');
		$crud->display_as('FinishDate', 'Finish Date');
		$crud->display_as('ProjID', 'Project Name');
		$crud->display_as('ProjName', 'Project Name');

		$crud->add_fields('ProjID', 'Title', 'Description', 'StartDate', 'FinishDate');

		$projects = $crud->basic_model->return_query("SELECT ProjID, Name FROM Project WHERE ProjID=".$id);

		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->Name];
		}

		$crud->field_type("ProjID", "dropdown", $prjArr);
		
		$output = $crud->render();

		$this->render($output);
	}

	function milestone_add($post_array) {
		
		$this->mile_insert($post_array);
	}

	function check_complete($value, $row) {
		return "<input type='checkbox' name='MSComplete'>";
	}
/*
	function field_width($value, $row) {
		return "wordwrap($row->MSComplete, 50, "", true)";
	}
*/
	public function mile_insert() {

		//Initialise and assign variables
		$ProjID = $_POST['ProjID'];
		$Title = $_POST['Title'];
		$Description = $_POST['Description'];
		$StartDate = $_POST['StartDate'];
		$FinishDate = $_POST['FinishDate'];

		$crud = new grocery_CRUD();
		$crud->set_model('Milestone_GC');
		$resp = $crud->basic_model->insert_mile($ProjID, $Title, $Description, $StartDate, $FinishDate);
		echo $resp;
	}
}