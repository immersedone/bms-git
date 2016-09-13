<?php

class Milestones extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('cookie');
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
		$crud->set_table('Milestone_new');
		$crud->set_subject('Milestone_new');
		$crud->basic_model->set_query_str('SELECT P.Name as ProjName, M.* from `Milestone_new` M
		LEFT OUTER JOIN `Project` P on M.ProjID=P.ProjID');
		$crud->columns('ProjName', 'ShortDesc', 'DueDate', 'RptType', 'Amount', 'Comment', 'FilePath');

		$crud->display_as('ProjID', 'Project Name');
		$crud->display_as('ProjName', 'Project Name');
		$crud->display_as('ShortDesc', 'Description');
		$crud->display_as('DueDate', 'Due Date');
		$crud->display_as('RptType', 'Type');
		$crud->display_as('FilePath', 'File Attached');
		$crud->display_as('MSComplete', 'Complete');

		$crud->add_fields('ProjID', 'ShortDesc', 'DueDate', 'RptType', 'Amount', 'Comment');

		$crud->callback_column('MSComplete', array($this, 'check_complete'));
		//$crud->callback_column('MSComplete', array($this, 'field_width'));

		$projects = $crud->basic_model->return_query("SELECT ProjID, Name FROM Project");

		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->Name];
		}
		$rptArr = array("Report", "Payment", "Report & Payment", "Final Payment");
				
		$crud->field_type("DueDate", 'datetime');
		$crud->field_type("Comment", 'text');
		$crud->field_type("RptType", "enum", $rptArr);
		$crud->field_type("ProjID", "dropdown", $prjArr);
		$crud->field_type("ProjName", "dropdown", $prjArr);
		
		$output = $crud->render();

		$this->render($output);
	}

	public function mileproj($id) {

		$crud = new grocery_CRUD();
		$crud->set_model('Extended_generic_model'); 
		$crud->set_table('Milestone_new');
		$crud->set_subject('Milestone_new');
		$crud->basic_model->set_query_str('SELECT P.Name as ProjName, M.* from `Milestone_new` M
		LEFT OUTER JOIN `Project` P on M.ProjID=P.ProjID
		WHERE M.ProjID = $id');
			
		$crud->columns('ShortDesc', 'DueDate', 'RptType', 'Amount');
		$crud->display_as('ProjID', 'Project Name');
		$crud->display_as('ProjName', 'Project Name');
		$crud->display_as('ShortDesc', 'Description');
		$crud->display_as('DueDate', 'Due Date');
		$crud->display_as('RptType', 'Type');
		$crud->display_as('MSComplete', 'Complete');
		$crud->add_fields('ProjID', 'ShortDesc', 'DueDate', 'RptType', 'Amount', 'Comment');

		$projects = $crud->basic_model->return_query("SELECT ProjID, Name FROM Project WHERE ProjID=".$id);

		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->Name];
		}

		
		$rptArr = array("Report", "Payment", "Report & Payment", "Final Payment");
		$crud->field_type("DueDate", 'datetime');
		$crud->field_type("Comment", 'text');
		$crud->field_type("RptType", "enum", $rptArr);

		$crud->set_field_upload('FilePath', 'assets/uploads/files/milestones');
		
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

	function projectName($value, $row) {

		$crud = new grocery_CRUD();
		$crud->set_model('Extended_generic_model');

		$projects = $crud->basic_model->return_query("SELECT ProjID, Name FROM Project WHERE ProjID=".$id);

		$prjArr = array();
		foreach($projects as $prj) {
			if($prj->ProjID === $value) {
				$projectName = $prj->Name;
			}
		}
		return $projectName;
	}
/*
	function field_width($value, $row) {
		return "wordwrap($row->MSComplete, 50, "", true)";
	}
*/
	public function mile_insert() {

		//Initialise and assign variables
		$ProjID = $_POST['ProjID'];
		$RptType = $_POST['RptType'];
		$Amount = $_POST['Amount'];
		$Comment = $_POST['Comment'];
		$DueDate = $_POST['DueDate'];
		$ShortDesc = $_POST['ShortDesc'];

		//Redundant (Old DB Table)
		/*$Title = $_POST['Title'];
		$Description = $_POST['Description'];
		$StartDate = $_POST['StartDate'];
		$FinishDate = $_POST['FinishDate'];*/

		$crud = new grocery_CRUD();
		$crud->set_model('Milestone_GC');
		$resp = $crud->basic_model->insert_mile($ProjID, $DueDate, $RptType, $ShortDesc, $Amount, $Comment);
		echo $resp;
	}
}