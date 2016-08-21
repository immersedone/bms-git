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
		$crud->set_theme('flexigrid');
		$crud->set_table('Milestone');
		$crud->set_subject('Milestone');
		$crud->set_relation('ProjID', 'Project', '{ProjID} - {Name}');
		$crud->columns('Title', 'Description', 'StartDate', 'FinishDate');
		$crud->display_as('StartDate', 'Start Date');
		$crud->display_as('FinishDate', 'Finish Date');
		
		$output = $crud->render();

		$this->render($output);
	}
}