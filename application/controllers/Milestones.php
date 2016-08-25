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
			
		$crud->columns('ProjName', 'Title', 'Description', 'StartDate', 'FinishDate');
		$crud->display_as('StartDate', 'Start Date');
		$crud->display_as('FinishDate', 'Finish Date');
		$crud->display_as('ProjID', 'Project');
		$crud->add_fields('ProjID', 'Title', 'Description', 'StartDate', 'FinishDate');

		$projects = $crud->basic_model->return_query("SELECT ProjID, Name FROM Project");

		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->Name];
		}

		$crud->field_type("ProjID", "dropdown", $prjArr);
		
		$output = $crud->render();

		$this->render($output);
	}
}