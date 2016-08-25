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
		$this->reimbursements();
	}

	public function render($output = null) {
		$this->load->view('reimbursements', $output);
	}

	public function reimbursements() {

		$crud = new grocery_CRUD();
		$crud->set_theme('flexigrid');
		$crud->set_model('Reimbursement_GC');
		$crud->basic_model->set_query_str("SELECT CONCAT(P.FirstName, ' ', P.MiddleName, ' ', P.LastName) as FullName, R.* FROM Reimbursement R
		LEFT OUTER JOIN Person P on R.PerID=P.PerID");
		$crud->set_table('Reimbursement');
		$crud->set_subject('Reimbursement');
		$crud->columns('FullName','Date', 'Reason', 'ApprovedBy', 'IsPaid'); //'Type', removed due to lack of implementation
		$crud->add_fields('FullName', 'Date', 'Reason', 'ApprovedBy', 'IsPaid');
		$crud->edit_fields('FullName', 'Date', 'Reason', 'ApprovedBy', 'IsPaid');
		$crud->display_as('ApprovedBy', 'Approved By');
		$crud->display_as('FullName', 'Reimbursement For');
		$crud->display_as('IsPaid', 'Is Paid');


		//Call Model to get the User's Full Names
		$users = $crud->basic_model->return_query("SELECT PerID, CONCAT(FirstName, ' ', MiddleName, ' ', LastName) as FullName FROM Person");

		//Convert Return Object into Associative Array
		$usrArr = array();
		foreach($users as $usr) {
			$usrArr += [$usr->PerID => $usr->FullName];
		}
		
		//Change the field type to a dropdown with values
		//to add to the relational table
		$crud->field_type("ApprovedBy", "dropdown", $usrArr);
		$crud->field_type("FullName", "dropdown", $usrArr);
		
		$crud->callback_before_insert(array($this,'reimb_insert'));
		

		$output = $crud->render();

		$this->render($output);
	}
	
	public function reimb_insert() {

		//Initialise and assign variables 
		
		$perid = $_POST['FullName'];
		$date = $_POST['ApprovedOn'];
		$reason = $_POST['Reason'];
		$ispaid = $_POST['PaymentType'];
		$Approvedby = $_POST['FullName'];

		$crud = new grocery_CRUD();
		$crud->set_model('Reimbursement_GC');
		$resp = $crud->basic_model->insert_reimb($perid, $reason, $Approvedby, $date, $ispaid);
		echo $resp;
	}
	
	
}