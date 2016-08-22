<?php

class Funding extends CI_Controller {

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
		$this->funding();
	}

	public function render($output = null) {
		$this->load->view('funding', $output);
	}

	public function funding() {

		$crud = new grocery_CRUD();
		$crud->set_model('Funding_GC');
		$crud->set_table('Funding');
		$crud->set_subject('Funding');
		$crud->basic_model->set_query_str('SELECT Proj.Name as ProjName, FB.BodyName as FBName, `Funding`.* from `Funding`
		LEFT OUTER JOIN `FundingBody` FB on FB.FundBodyID=`Funding`.FundBodyID
		LEFT OUTER JOIN `Project` Proj on Proj.ProjID=`Funding`.ProjID
		ORDER BY Proj.Name
		');
		$crud->columns('ProjName', 'FBName', 'Amount', 'PaymentType', 'ApprovedBy', 'ApprovedOn');
		$crud->display_as('ProjName', 'Project');
		$crud->display_as('FBName', 'Funding Body');
		$crud->display_as('PaymentType', 'Payment Type');
		$crud->display_as('ApprovedBy', 'Approved By');
		$crud->display_as('ApprovedOn', 'Approved On');

		$output = $crud->render();

		$this->render($output);
	}
}