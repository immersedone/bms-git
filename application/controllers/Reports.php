<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller {

	public function __construct()
	{
		parent::__construct();


		$this->load->library('grocery_CRUD');
	}

	public function index()
	{

		//$this->render((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
		$this->reports();
	}

	public function render($output = null) {
		$this->load->view('reports', $output);		

	}

	public function reports() {

		$crud = new grocery_CRUD();
		$crud->set_model('Extended_generic_model');
		$crud->set_table('Files');
		$crud->set_subject('Reports');
		$crud->basic_model->set_query_str("SELECT * FROM Files WHERE Type='REPORT'");
		$crud->columns('Title', 'CreatedOn', 'CreatedBy');



		//Prettify Fields
		$crud->display_as('TempName', 'TemporaryName');
		$crud->display_as('CreatedOn', 'Created On');
		$crud->display_as('CreatedBy', 'Created By');

		//User Array
		//Call Model to get the User's Full Names
		$users = $crud->basic_model->return_query("SELECT PerID, CONCAT(FirstName, ' ', MiddleName, ' ', LastName) as FullName FROM Person");

		//Convert Return Object into Associative Array
		$usrArr = array();
		foreach($users as $usr) {
			$usrArr += [$usr->PerID => $usr->FullName];
		}

		//Field Types
		$crud->field_type('CreatedBy', 'dropdown', $usrArr);


		//Unset All Actions Actions Except for Delete
		$crud->unset_edit();
		$crud->unset_read();
		$crud->unset_add();
		$crud->unset_print();
		$crud->unset_export();

		//Add View Action
		$crud->add_action('View', '', '', 'read-icon view-report', array($this, 'report_view'));

		//Callback before delete to remove File from directory
		$crud->callback_before_delete(array($this, 'delete_file_before_delete'));



		$output = $crud->render();
		$this->render($output);
		
	}

	public function delete_file_before_delete($primary_key) {

		$q = $this->db->query("SELECT * FROM Files WHERE FileID='".$primary_key."' LIMIT 1")->row();

		if(!unlink(FCPATH . $q->Directory . $q->TempName)) {
			return false;
		} else {
			return true;
		}
	}

	public function report_view($primarykey, $row) {
		$q = $this->db->query("SELECT * FROM Files WHERE FileID='".$primarykey."' LIMIT 1")->row();

		return base_url().$q->Directory.$q->TempName;
	}

}