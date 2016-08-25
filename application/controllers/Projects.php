<?php

class Projects extends CI_Controller {

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
		$this->projects();
	}

	public function render($output = null) {
		$this->load->view('projects', $output);
	}

	public function projects() {

		$crud = new grocery_CRUD();

		$crud->set_theme('flexigrid');
		$crud->set_table('Project');
		$crud->set_subject('Project');
		$crud->columns("Name", "Description", "StartDate", "FinishDate", "TotalFunding"); //"Status", 
		$crud->set_js('People_function.js');
		$crud->display_as("Name", "Project Name");
		$crud->display_as("Description", "Project Description");
		$crud->display_as("StartDate", "Start Date");
		$crud->display_as("FinishDate", "Finish Date");
		//$crud->display_as("Status", "Project Status");
		$crud->display_as("TotalFunding", "Total Funding");

		$crud->form_buttons('View Milestones', 'showMilestones', '');
		$crud->form_buttons('View Expenditures', 'showReimbursements', '');
		$crud->form_buttons('View Reimbursements', 'showReimbursements', '');
		$crud->form_buttons('View Funding', 'showFunding', '');
		$output = $crud->render();
		//print_r($output);
		$this->render($output);
	}
}
