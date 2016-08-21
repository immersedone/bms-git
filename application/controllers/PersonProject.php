<?php

class PersonProject extends CI_Controller {

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
		//$this->personproject();
		$this->load->view('personproject');
	
		/*
		Corresponds to People_model.php's function to add Person to project
		
		$data is a variable that exists in People_model - $data is the fields being inserted when the
		person_to_project function is called
		*/
		$data = array(
			'PerID' => $this->input->post('personid'),
			'ProjID' => $this->input->post('projectid'),
			'Role' => $this->input->post('role')
		);
		
		//Data transfer to Model
		$this->People_model->add_person_to_proj($data);
		$data['message'] = 'Person successfully added to Project';
	}

	public function render($output = null) {
		$this->load->view('personproject', $output);
	}

	public function personproject() {

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