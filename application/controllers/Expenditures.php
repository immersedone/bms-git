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
		$crud->set_theme('flexigrid');
		$crud->set_table('Expenditure');
		$crud->set_subject('Expenditure');

		$output = $crud->render();

		$this->render($output);
	}
	
	public function add_person_expenditure(){
		
	}
}