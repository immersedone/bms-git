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
		$crud->set_read_fields("ReimID", "ReimbDate", "ApprovedBy", "PerID", "ExpList", "IsPaid", "ReimbStatus", "Comments");
		$crud->columns('ReimID', 'FullName','ReimbDate', 'ApprovedBy', "ExpList",  'IsPaid', "ReimbStatus"); //'Type', removed due to lack of implementation
		$crud->add_fields('FullName', 'ReimbDate',  'ApprovedBy', "ExpList",  'IsPaid', "ReimbStatus", "Comments");
		$crud->edit_fields('PerID', 'ReimbDate', 'ApprovedBy', "ExpList", 'IsPaid', "ReimbStatus", "Comments");
		$crud->display_as('ReimbDate', 'Date');
		$crud->display_as('ExpList', 'Expenditures');
		$crud->display_as('ApprovedBy', 'Approved By');
		$crud->display_as('FullName', 'Reimbursement For');
		$crud->display_as("ReimID", "Reimbursement #");
		$crud->display_as('IsPaid', 'Is Paid');
		$crud->display_as('PerID', 'Reimbursement For');
		$crud->display_as('ReimbStatus', 'Reimbursement Status');


		//Call Model to get the User's Full Names
		$users = $crud->basic_model->return_query("SELECT PerID, CONCAT(FirstName, ' ', MiddleName, ' ', LastName) as FullName FROM Person");

		//Convert Return Object into Associative Array
		$usrArr = array();
		foreach($users as $usr) {
			$usrArr += [$usr->PerID => $usr->FullName];
		}
		
		$unpaidExp = $crud->basic_model->return_query("SELECT ExpID, CONCAT(ExpName, ' - ', Reason, ' - ', Amount) as ExpData FROM Expenditure");
		
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

		$crud->add_action('Print', base_url().'/assets/grocery_crud/themes/flexigrid/css/images/print.png', '', '', array($this, 'print_reimb'));
		
		$state = $crud->getState();

		if ($state === "add" or $state === "edit") {
			$crud->field_type("PerID", "readonly");
			$unpaidExp = $crud->basic_model->return_query("SELECT ExpID, CONCAT(ExpName, ' - ', Reason, ' - ', Amount) as ExpData FROM Expenditure WHERE IsPaid = 0");

			$expArr = array();
			foreach($unpaidExp as $exp) {
				$expArr += [$exp->ExpID => $exp->ExpData];
			}
			$crud->field_type("ExpList", "multiselect", $expArr);
		}
		

		$crud->callback_before_update(array($this, 'update_expenditures'));
		$crud->unset_print();
		$crud->unset_export();

		$output = $crud->render();

		$this->render($output);
	}


	function print_reimb($primary_key, $row) {
		return base_url().'user/genreport/printreimb/'.$primary_key;
	}

	function reimbursement_add($post_array) {
		
		$this->reimb_insert($post_array);
	}

	function update_expenditures($post_array, $primary_key) {
		if(!empty($post_array['ExpList'])) {
			$exp = $post_array['ExpList'];
			$ispaid = $post_array['IsPaid'];

			for($i = 0; $i < count($exp); $i++) {

				if($ispaid === "YES") {
					$this->db->where('ExpID', $exp[$i]);
					$this->db->update('Expenditure', array('IsPaid'=>1));
				}
			}
		}

		return $post_array;
	}

	public function reimb_insert() {

		//Initialise and assign variables 
		
		$perid = $_POST['FullName'];
		$date = $_POST['ReimbDate'];
		$ispaid = $_POST['IsPaid'];
		$exp = $_POST['ExpList'];
		$Approvedby = $_POST['ApprovedBy'];

		$expStr = "";

		for($i = 0; $i < count($exp); $i++) {
			if($i === count($exp) - 1) {
				$expStr .= $exp[$i];
			} else {
				$expStr .= $exp[$i] .',';
			}

			if($ispaid === "YES") {
				$this->db->where('ExpID', $exp[$i]);
				$this->db->update('Expenditure', array('IsPaid'=>1));
			}
		}

		$newDateRep = preg_replace('/\//', '-',$date);
		$newDate = date("Y-m-d H:i:s", strtotime($newDateRep));
		$crud = new grocery_CRUD();
		$crud->set_model('Reimbursement_GC');
		$resp = $crud->basic_model->insert_reimb($newDate, $expStr, $Approvedby, $ispaid, $perid);
		echo $resp;
	}
	
	
}