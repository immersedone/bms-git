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
		$crud->set_read_fields("ReimbDate", "ApprovedBy", "PerID", "ExpList", "IsPaid");
		$crud->columns('FullName','ReimbDate', 'ApprovedBy', "ExpList",  'IsPaid'); //'Type', removed due to lack of implementation
		$crud->add_fields('FullName', 'ReimbDate',  'ApprovedBy', "ExpList",  'IsPaid');
		$crud->edit_fields('PerID', 'ReimbDate', 'ApprovedBy', "ExpList", 'IsPaid');
		$crud->display_as('ReimbDate', 'Date');
		$crud->display_as('ExpList', 'Expenditures');
		$crud->display_as('ApprovedBy', 'Approved By');
		$crud->display_as('FullName', 'Reimbursement For');
		$crud->display_as('IsPaid', 'Is Paid');
		$crud->display_as('PerID', 'Reimbursement For');


		//Call Model to get the User's Full Names
		$users = $crud->basic_model->return_query("SELECT PerID, CONCAT(FirstName, ' ', MiddleName, ' ', LastName) as FullName FROM Person");

		//Convert Return Object into Associative Array
		$usrArr = array();
		foreach($users as $usr) {
			$usrArr += [$usr->PerID => $usr->FullName];
		}
		
		//Available Days
		$unpaidExp = $crud->basic_model->return_query("SELECT ExpID, CONCAT(ExpName, ' - ', Reason, ' - ', Amount) as ExpData FROM Expenditure WHERE IsPaid = 0");
		
		$expArr = array();
		foreach($unpaidExp as $exp) {
            $expArr += [$exp->ExpID => $exp->ExpData];
        }
		$crud->field_type("ExpList", "multiselect", $expArr);
		
		
		
		//Change the field type to a dropdown with values
		//to add to the relational table
		$crud->field_type("ApprovedBy", "dropdown", $usrArr);
		$crud->field_type("FullName", "dropdown", $usrArr);
		$crud->field_type("PerID", "dropdown", $usrArr);
		
		$crud->callback_before_insert(array($this,'reimbursement_add'));
		
		$state = $crud->getState();

		if($state === "edit") {
			$crud->field_type("PerID", "readonly");
		}

		$output = $crud->render();

		$this->render($output);
	}
	function reimbursement_add($post_array) {
		
		$this->reimb_insert($post_array);
	}
	public function reimb_insert() {

		//Initialise and assign variables 
		
		$perid = $_POST['FullName'];
		$date = $_POST['ReimbDate'];
		$reason = $_POST['Reason'];
		$ispaid = $_POST['IsPaid'];
		$Approvedby = $_POST['ApprovedBy'];

		$crud = new grocery_CRUD();
		$crud->set_model('Reimbursement_GC');
		$resp = $crud->basic_model->insert_reimb($reason, $date, $Approvedby, $ispaid, $perid);
		echo $resp;
	}
	
	
}