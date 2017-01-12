<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reimbursements extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

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
		$crud->basic_model->set_query_str("SELECT * FROM (SELECT CONCAT(P.FirstName, ' ', P.MiddleName, ' ', P.LastName) as FullName, R.* FROM Reimbursement R
		LEFT OUTER JOIN Person P on R.PerID=P.PerID) x");
		$crud->set_table('Reimbursement');
		$crud->set_subject('Reimbursement');
		$crud->set_read_fields("ReimID", "ReimbDate", "ApprovedBy", "PerID", "ExpList", "IsPaid", "ReimbStatus", "Comments");
		$crud->columns('ReimID', 'FullName','ReimbDate', 'ApprovedBy', "ExpList",  'IsPaid', "ReimbStatus"); //'Type', removed due to lack of implementation
		$crud->add_fields('FullName', 'ReimbDate',  'ApprovedBy', "ExpList",  'IsPaid', "ReimbStatus", "Comments");
		$crud->edit_fields('ReimID', 'PerID', 'ReimbDate', 'ApprovedBy', "ExpList", 'IsPaid', "ReimbStatus", "Comments");
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
		
		//Get Financial Controller users for ApprovedBy
		$fcusers = $crud->basic_model->return_query("SELECT PerID, CONCAT(FirstName, ' ', MiddleName, ' ', LastName) as FullName FROM Person
		WHERE Is_FinanceController = 1");
		$fcArr = array();
		if (empty($fcusers) == false){
			foreach($fcusers as $fc){
				$fcArr += [$fc->PerID => $fc->FullName];
			}
			$crud->field_type("ApprovedBy", "dropdown", $fcArr);
		}
		else{
			$crud->field_type("ApprovedBy", 'hidden');
		}
		
		$unpaidExp = $crud->basic_model->return_query("SELECT ExpID, CONCAT(ExpName, ' - ', Concept, ' - ', Amount) as ExpData FROM Expenditure");
		
		$expArr = array();
		foreach($unpaidExp as $exp) {
            $expArr += [$exp->ExpID => $exp->ExpData];
        }
		$crud->field_type("ExpList", "multiselect", $expArr);
		
		$state = $crud->getState();

		
		//Change the field type to a dropdown with values
		//to add to the relational table
		if ($state == "edit" || $state == "update") {
			$crud->callback_edit_field("PerID", array($this, 'callback_ReFor_edit'));
			$crud->field_type("ReimID", "hidden");
			$crud->field_type("PerID", "readonly");
		} 

		
		$crud->field_type("FullName", "dropdown", $usrArr);
		$crud->field_type("PerID", "dropdown", $usrArr);
		
		$crud->callback_before_insert(array($this,'reimbursement_add'));
		$crud->callback_before_update(array($this, 'update_expenditures'));
		

		$crud->add_action('Print w/ Cover Page', base_url().'/assets/grocery_crud/themes/flexigrid/css/images/print.png', '', '', array($this, 'print_reimb'));

		$crud->add_action('Print w/o Cover Page', base_url().'/assets/grocery_crud/themes/flexigrid/css/images/printno.png', '', '', array($this, 'print_reimb_ncp'));
		

		if ($state === "add") {
			$crud->field_type("PerID", "readonly");
			$unpaidExp = $crud->basic_model->return_query("SELECT ExpID, CONCAT(ExpName, ' - ', Concept, ' - ', Amount) as ExpData FROM Expenditure WHERE IsPaid = 0");

			$expArr = array();
			foreach($unpaidExp as $exp) {
				$expArr += [$exp->ExpID => $exp->ExpData];
			}
			$crud->field_type("ExpList", "multiselect", $expArr);
		} elseif ($state === "edit" || $state == "update") {
			//$crud->field_type("PerID", "readonly");
			//Get Primary Key (Row) that is being edited and retrieve the Expenditures List
			$reimbID = $crud->getStateInfo()->primary_key;
			$reimbExp = $crud->basic_model->return_query("SELECT ExpList FROM Reimbursement WHERE ReimID='$reimbID' LIMIT 1");

			//Convert List to an array
			$expArr = explode(',', $reimbExp[0]->ExpList); 

			//Declare Array for Storage
			$expListArr = array();

			//Different Methods of Retrieving Expenditures based on count
			if(count($expArr) == 1) {

				//If only one in Expenditure List

				//Retrieve Expenditure
				$editExp = $crud->basic_model->return_query("SELECT ExpID, CONCAT(ExpName, ' - ', Concept, ' - ', Amount) as ExpData FROM Expenditure WHERE ExpID='$expArr[0]'");	

				//Add to Storage Array
				$expListArr += [$editExp[0]->ExpID => $editExp[0]->ExpData];

			} else {

				//If more than one Expenditure in list then loop

				foreach($expArr as $exp) {

					//Retrieve Expenditure
					$editExp = $crud->basic_model->return_query("SELECT ExpID, CONCAT(ExpName, ' - ', Concept, ' - ', Amount) as ExpData FROM Expenditure WHERE ExpID='$exp' LIMIT 1");

					//Add to Storage Array
					$expListArr += [$editExp[0]->ExpID => $editExp[0]->ExpData];

				}

			}

			//Declare Field type for Expenditure List
			$crud->field_type("ExpList", "multiselect", $expListArr);

		}
		
		$crud->set_rules("ExpList", "Expenditures", "trim|numeric|callback_multi_LS");
		
		
		$crud->required_fields(
		'FullName',
		'ExpList',
		'IsPaid',
		'ReimbStatus'
		);
		

		
		$crud->unset_print();
		$crud->unset_export();

		$output = $crud->render();

		$this->render($output);
	}
/*
	public function callback_AppBy_edit($value, $primary_key) {
		$q = $this->db->query('SELECT CONCAT( FirstName, " ", MiddleName, " ", LastName) as FullName FROM Person WHERE PerID="'.$value.'" LIMIT 1')->row();

		$readOnly = '<div id="field-ApprovedBy" class="readonly_label">' . $q->FullName .'</div>';
		return $readOnly . '<input id="field-ApprovedBy" name="ApprovedBy" type="text" value="' . $value . '" class="numeric form-control" maxlength="255" style="display:none;">';

	}
*/
	public function callback_ReFor_edit($value, $primary_key) {
		$q = $this->db->query('SELECT CONCAT( FirstName, " ", MiddleName, " ", LastName) as FullName FROM Person WHERE PerID="'.$value.'" LIMIT 1')->row();

		$readOnly = '<div id="field-PerID" class="readonly_label">' . $q->FullName .'</div>';
		return $readOnly . '<input id="field-PerID" name="PerID" type="text" value="' . $value . '" class="numeric form-control" maxlength="255" style="display:none;">';

	}


	function print_reimb($primary_key, $row) {
		return base_url().'user/genreport/printreimb/'.$primary_key.'/1';
	}

	function print_reimb_ncp($primary_key, $row) {
		return base_url().'user/genreport/printreimb/'.$primary_key.'/0';
	}

	function reimbursement_add($post_array) {
		
		$this->reimb_insert($post_array);
	}
	
	public function multi_LS() {
		
		$expListArr = $this->input->post('ExpList[]');
		if(empty($expListArr)) {
			$this->form_validation->set_message('multi_LS', 'At least one expenditure must be selected to reimburse.');

			return false;
		} else {
			return true;
		}

	}
	
	

	function update_expenditures($post_array) {
		/*if(!empty($post_array['ExpList'])) {
			$exp = $post_array['ExpList'];
			$ispaid = $post_array['IsPaid'];

			for($i = 0; $i < count($exp); $i++) {

				if($ispaid === "YES") {
					$this->db->where('ExpID', $exp[$i]);
					$this->db->update('Expenditure', array('IsPaid'=>1));
				}
			}
		}*/

		//Redundant - Use Custom Update Model
		//For Update & Print functionality
		//return $post_array;
		print_r($post_array);
		$this->reimb_update($post_array);
		//return $post_array;
	}

	public function reimb_update() {
		
		$perid = $_POST['PerID'];
		$date = $_POST['ReimbDate'];
		$ispaid = $_POST['IsPaid'];
		$exp = $_POST['ExpList'];
		$Approvedby = $_POST['ApprovedBy'];
		$Comments = $_POST['Comments'];
		$ReID = $_POST["ReimID"];

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
			} else {
				$this->db->where('ExpID', $exp[$i]);
				$this->db->update('Expenditure', array('IsPaid'=>0));
			}
		}

		$newDateRep = preg_replace('/\//', '-',$date);
		$newDate = date("Y-m-d H:i:s", strtotime($newDateRep));
		$crud = new grocery_CRUD();
		$crud->set_model('Reimbursement_GC');
		$resp = $crud->basic_model->update_reimb($ReID, $newDate, $expStr, $Approvedby, $ispaid, $perid, $Comments);
		echo $resp;

	}

	public function reimb_insert() {

		//Initialise and assign variables 
		
		$perid = $_POST['FullName'];
		$date = $_POST['ReimbDate'];
		$ispaid = $_POST['IsPaid'];
		$exp = $_POST['ExpList'];
		$Approvedby = $_POST['ApprovedBy'];
		$Comments = $_POST['Comments'];

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
			} else {
				$this->db->where('ExpID', $exp[$i]);
				$this->db->update('Expenditure', array('IsPaid'=>0));
			}
		}

		$newDateRep = preg_replace('/\//', '-',$date);
		$newDate = date("Y-m-d H:i:s", strtotime($newDateRep));
		$crud = new grocery_CRUD();
		$crud->set_model('Reimbursement_GC');
		$resp = $crud->basic_model->insert_reimb($newDate, $expStr, $Approvedby, $ispaid, $perid, $Comments);
		echo $resp;
	}
	
	public function getExpList(){

		$expArr = explode(',', $_POST['expList']);

		$crud = new grocery_CRUD();
		$crud->set_model('Extended_generic_model');

		$resp = array();
		$resp["ExpList"] = "";
		for($i = 0; $i < count($expArr); $i++) {
			$id = $expArr[$i];
			$res = $crud->basic_model->return_query("SELECT CONCAT('$', Amount, ' - (Name) ', ExpName, '; (Concept) ', Concept) as Exp FROM Expenditure WHERE ExpID='$id' LIMIT 1");

			$count = $i + 1;

			$resp["ExpList"] .= $count . ". ";
			$resp["ExpList"] .= $res[0]->Exp;
			$resp["ExpList"] .= "<br/>";
		}
		
		echo json_encode($resp);

	}
	
	
	
}