<?php

class Reimbursements extends CI_Controller {

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
		$this->reimbursement();
	}

	public function render($output = null) {
		$this->load->view('reimbursements', $output);
	}

	public function reimbursement() {

		$crud = new grocery_CRUD();
		$crud->set_theme('flexigrid');
		$crud->set_table('Reimbursement');
		$crud->set_subject('Reimbursement')
		$crud->columns('PerID','Date', 'Reason', 'Type', 'ApprovedBy', 'IsPaid');
		$crud->add_fields('Date', 'Reason', 'Type', 'ApprovedBy', 'PerID', 'IsPaid');
		$crud->edit_fields('Date', 'Reason', 'Type', 'ApprovedBy', 'PerID', 'IsPaid')
		$crud->display_as('ApprovedBy', 'Approved By');
		$crud->display_as('PerID', 'Person ID');
		$crud->display_as('IsPaid', 'Is Paid');
		

		$output = $crud->render();

		$this->render($output);
	}
}