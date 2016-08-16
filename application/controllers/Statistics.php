<?php

class Statistics extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
	}

	public function index()
	{
		$this->render((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
		//$this->projects();
		//$this->load->view('statistics');
	}

	public function render($output = null) {
		$this->load->view('statistics', $output);
	}

	public function statistics() {

		/*$crud = new grocery_CRUD();
		$crud->set_theme('flexigrid');
		$crud->set_table('Project');
		$crud->set_subject('Project');
		$crud->columns("Name", "Description", "StartDate", "FinishDate", "Status", "TotalFunding");
		$crud->display_as("Name", "Project Name");
		$crud->display_as("Description", "Project Description");
		$crud->display_as("StartDate", "Start Date");
		$crud->display_as("FinishDate", "Finish Date");
		$crud->display_as("Status", "Project Status");
		$crud->display_as("TotalFunding", "Total Funding");

		$output = $crud->render();

		$this->render($output);*/
	}
}