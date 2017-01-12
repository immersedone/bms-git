<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Funding extends MY_Controller {

	public function __construct()
	{
		parent::__construct();

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
		$crud->basic_model->set_query_str('SELECT * FROM (SELECT Proj.Name as ProjName, FB.BodyName as FBName, CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as FullName, Fund.* from `Funding` Fund
		LEFT OUTER JOIN `FundingBody` FB on FB.FundBodyID=Fund.FundBodyID
		LEFT OUTER JOIN `Project` Proj on Proj.ProjID=Fund.ProjID
		LEFT OUTER JOIN `Person` Per on Per.PerID=Fund.ApprovedBy) x');
		$crud->set_read_fields('ProjID', 'FundBodyID', 'Amount', 'PaymentType', 'status', 'ApprovedBy', 'ApprovedOn');
		$crud->columns('ProjName', 'FBName', 'Amount', 'PaymentType', 'ApprovedBy', 'status', 'ApprovedOn');
		$crud->display_as('ProjID', 'Project');
		$crud->display_as('ProjName', 'Project');
		$crud->display_as('FBName', 'Funding Body');
		$crud->display_as('PaymentType', 'Payment Type');
		$crud->display_as('FullName', 'Approved By');
		$crud->display_as('ApprovedBy', 'Approved By');
		$crud->display_as('ApprovedOn', 'Approved On');
		$crud->display_as('FundBodyID', 'Funding Body');
		
		//Change the Insert Funding fields
		$crud->edit_fields("ProjID", "FundBodyID", "Amount", "PaymentType", 'status', "ApprovedBy", "ApprovedOn");
		$crud->add_fields("ProjID", "FundBodyID", "Amount", "PaymentType", 'status', "ApprovedBy", "ApprovedOn");
		
		$state = $crud->getState();

		$crud->required_fields(
		'ProjID',
		'FundBodyID', 
		'Amount', 
		'PaymentType', 
		'status');
		
		//Call Model to get the Project Names
		$projects = $crud->basic_model->return_query("SELECT ProjID, Name as ProjName FROM Project");
		
		//Convert Return Object into Associative Array
		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->ProjName];
		}

		//Change the field type to a dropdown with values
		//to add to the relational table
		$crud->field_type("ProjID", "dropdown", $prjArr);
		
		//Call Model to get the names of the funding bodies
		$fundingbodies = $crud->basic_model->return_query("SELECT FundBodyID, BodyName FROM FundingBody");
		
		//Convert Return Object into Associative Array
		$FBArr = array();
		foreach($fundingbodies as $fb) {
			$FBArr += [$fb->FundBodyID => $fb->BodyName];
		}

		//Change the field type to a dropdown with values
		//to add to the relational table
		$crud->field_type("FundBodyID", "dropdown", $FBArr);		
				
		//Call Model to get the User's Full Names
		$users = $crud->basic_model->return_query("SELECT PerID, CONCAT(FirstName, ' ', MiddleName, ' ', LastName) as FullName FROM Person");

		//Convert Return Object into Associative Array
		$usrArr = array();
		foreach($users as $usr) {
			$usrArr += [$usr->PerID => $usr->FullName];
		}
		

		//Change the field type to a dropdown with values
		//to add to the relational table
		if($state == "edit" || $state == "update") {
			$crud->callback_edit_field("ApprovedBy", array($this, 'callback_AppBy_edit'));
			$crud->callback_edit_field("ProjID", array($this, 'callback_projID_edit'));
			$crud->callback_edit_field("FundBodyID", array($this, 'callback_FBID_edit'));
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
		
		
		
		//Change the default method to fire when organizing funding for a project
		$crud->callback_before_insert(array($this,'funding_add'));
		//$crud->callback_before_update(array($this,'update_fund'));
		//$crud->unset_delete();
		//$crud->add_action('Delete', '', '', 'delete-icon', array($this, 'delete_fund'));
		$crud->callback_delete(array($this, 'delete_fund'));
		
		$output = $crud->render();

		$this->render($output);
	}

	public function fundproj($id) {

		$crud = new grocery_CRUD();
		$crud->set_model('Funding_GC');
		$crud->set_table('Funding');
		$crud->set_subject('Funding');
		$crud->basic_model->set_query_str('SELECT * FROM (SELECT Proj.Name as ProjName, FB.BodyName as FBName, CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as FullName, Fund.* from `Funding` Fund
		LEFT OUTER JOIN `FundingBody` FB on FB.FundBodyID=Fund.FundBodyID
		LEFT OUTER JOIN `Project` Proj on Proj.ProjID=Fund.ProjID
		LEFT OUTER JOIN `Person` Per on Per.PerID=Fund.ApprovedBy WHERE Fund.ProjID="'.$id.'") x', ' GROUP BY FundID');
		$crud->columns('FBName', 'Amount', 'PaymentType', 'status', 'FullName', 'ApprovedOn');
		$crud->display_as('ProjName', 'Project');
		$crud->display_as('FBName', 'Funding Body');
		$crud->display_as('FundBodyID', 'Funding Body');
		$crud->display_as('PaymentType', 'Payment Type');
		$crud->display_as('FullName', 'Approved By');
		$crud->display_as('ApprovedBy', 'Approved By');
		$crud->display_as('ApprovedOn', 'Approved On');
		$crud->display_as("ProjID", "Project Name");
		
		//Change the Insert Funding fields
		$crud->add_fields("ProjID", "FundBodyID", "Amount", "PaymentType", 'status', "ApprovedBy", "ApprovedOn");
		$crud->edit_fields("ProjID", "FundBodyID", "Amount", "PaymentType", 'status', "ApprovedBy", "ApprovedOn", "row");
		$crud->field_type("row", "hidden", $id);
	
		$crud->required_fields(
		'FBName', 
		'Amount', 
		'PaymentType', 
		'status');	
	
		//Call Model to get the Project Names
		$projects = $crud->basic_model->return_query("SELECT ProjID, Name as ProjName FROM Project WHERE ProjID=".$id);
		
		//Convert Return Object into Associative Array
		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->ProjName];
		}

		//Call Model to get the names of the funding bodies
		$fundingbodies = $crud->basic_model->return_query("SELECT FundBodyID, BodyName FROM FundingBody");
		
		//Convert Return Object into Associative Array
		$FBArr = array();
		foreach($fundingbodies as $fb) {
			$FBArr += [$fb->FundBodyID => $fb->BodyName];
		}

		$state = $crud->getState();

		//Change the field type to a dropdown with values
		//to add to the relational table
		//$crud->field_type("ProjName", "dropdown", $prjArr);

		if($state === "edit" || $state === "update") {
			$crud->callback_edit_field("ProjID", array($this, 'callback_projID_edit'));
			$crud->callback_edit_field("FundBodyID", array($this, 'callback_FBID_edit'));
		} else if ($state === "add" || $state === "insert") {
			$crud->callback_add_field("ProjID", function() {
				$id = get_cookie("projID");
				//echo $id;
				$q = $this->db->query('SELECT Name FROM Project WHERE ProjID="' . $id .'" LIMIT 1')->row();
				$readOnly = '<div id="field-ProjID" class="readonly_label">' . $q->Name .'</div>';
				return $readOnly. '<input id="field-ProjID" name="ProjID" type="text" value="' . $id . '" class="numeric form-control" maxlength="255" style="display:none;">';
			});
			$crud->field_type("FBName", "dropdown", $FBArr);
			$crud->field_type("FundBodyID", "dropdown", $FBArr);
		} else {
			$crud->field_type("ProjID", "dropdown", $prjArr);
			$crud->field_type("FBName", "dropdown", $FBArr);
			$crud->field_type("FundBodyID", "dropdown", $FBArr);	
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
						
		//Call Model to get the User's Full Names
		$users = $crud->basic_model->return_query("SELECT PerID, CONCAT(FirstName, ' ', MiddleName, ' ', LastName) as FullName FROM Person");

		//Convert Return Object into Associative Array
		$usrArr = array();
		foreach($users as $usr) {
			$usrArr += [$usr->PerID => $usr->FullName];
		}
		
		//Change the field type to a dropdown with values
		//to add to the relational table
		$crud->field_type("FullName", "dropdown", $usrArr);
		
		//$crud->unset_edit();
		//$crud->add_action('Edit', '', '', 'edit_button edit-icon', array($this, 'upd_fund_proj'));
		//Change the default method to fire when organizing funding for a project
		$crud->callback_before_insert(array($this,'funding_add'));
		//$crud->unset_delete();
		$crud->add_action('Delete', '', '', 'delete-icon delete-row', array($this, 'del_fund_proj'));
		$crud->unset_delete();
		$crud->callback_before_update(array($this, 'update_fund'));
		//$crud->callback_before_delete(array($this, 'delete_fund'));

		
		$output = $crud->render();

		$this->render($output);
	}

	public function callback_AppBy_edit($value, $primary_key) {
		$q = $this->db->query('SELECT CONCAT( FirstName, " ", MiddleName, " ", LastName) as FullName FROM Person WHERE PerID="'.$value.'" LIMIT 1')->row();

		$readOnly = '<div id="field-ApprovedBy" class="readonly_label">' . $q->FullName .'</div>';
		return $readOnly . '<input id="field-ApprovedBy" name="ApprovedBy" type="text" value="' . $value . '" class="numeric form-control" maxlength="255" style="display:none;">';

	}

	public function callback_projID_edit($value, $primary_key) {
		$q = $this->db->query('SELECT Name FROM Project WHERE ProjID="' . $value .'" LIMIT 1')->row();
		//$projName = array_shift($q->result_array());
		$readOnly = '<div id="field-ProjID" class="readonly_label">' . $q->Name .'</div>';
		return $readOnly . '<input id="field-ProjID" name="ProjID" type="text" value="' . $value . '" class="numeric form-control" maxlength="255" style="display:none;">';
	}

	public function callback_FBID_edit($value, $primary_key) {
		$q = $this->db->query('SELECT BodyName FROM FundingBody WHERE FundBodyID="'.$value.'" LIMIT 1')->row();
		$readOnly = '<div id="field-FundBodyID" class="readonly_label">' . $q->BodyName .'</div>';
		return $readOnly . '<input id="field-FundBodyID" name="FundBodyID" type="text" value="' . $value . '" class="numeric form-control" maxlength="255" style="display:none;">';
	}

	function delete_fund($post_array) {
		$this->fd_delete($post_array);
		//return base_url().'user/funding/index/fd_delete/'.$primarykey.'/'.$row->ProjID;
	}

	function del_fund_proj($primarykey, $row) {
		//return base_url().'user/funding/index/fd_delete/'.$primarykey.'/'.$row->ProjID;	
		return base_url().'user/funding/index/fd_delete/'.$primarykey;	
	}

	function upd_fund_proj($primarykey, $row) {
		return base_url().'user/funding/index/fd_update/'.$primarykey;		
	}

	function update_fund($post_array) {
		$this->fd_update($post_array);
		//return base_url().'user/funding/index/fd_delete/'.$primarykey.'/'.$row->ProjID;
	}
	
	function funding_add($post_array) {
		
		$this->fd_insert($post_array);
	}

	public function fd_delete($id) {

		$crud = new grocery_CRUD();
		$crud->set_model('Funding_GC');
		$rmAmt = $crud->basic_model->return_query("SELECT Amount, ProjID FROM Funding
		WHERE FundID = '$id'");
		$amt = $rmAmt[0]->Amount;
		$resp = $crud->basic_model->delete_fund($id, $amt, $rmAmt[0]->ProjID);
		echo $resp;
		/*if($resp['result'] === "success") {
			redirect(base_url().'/user/volunteer/');
		} else {
			redirect(base_url().'user/volunteer/index/error/e=Error%20Deleting%20Volunteer.%20Please%20try%20again%20or%20contact%20administrator.');
		}*/
	}

	public function fd_proj_del($id, $pID) {

		$crud = new grocery_CRUD();
		$crud->set_model("Funding_GC");

	}

	public function fd_insert() {

		//Initialise and assign variables 
		
		$projectID = $_POST['ProjID'];
		$fundbodyid = $_POST['FundBodyID'];
		$amount = $_POST['Amount'];
		$PaymentType = $_POST['PaymentType'];
		$Approvedby = $_POST['ApprovedBy'];
		$ApprovedOn = $_POST['ApprovedOn'];
		$status = $_POST['status'];

		$newDateRep = preg_replace('/\//', '-',$ApprovedOn);
		$newDate = date("Y-m-d H:i:s", strtotime($newDateRep));
		$crud = new grocery_CRUD();
		$crud->set_model('Funding_GC');
		$resp = $crud->basic_model->insert_fund($projectID, $fundbodyid, $amount, $PaymentType, $Approvedby, $newDate, $status);
		echo $resp;
	}

	public function fd_update($id) {

		//Initialise and assign variables 
		$projectID = $_POST['ProjID'];
		$newamount = $_POST['Amount'];
		$PaymentType = $_POST['PaymentType'];
		$Approvedby = $_POST['ApprovedBy'];
		$ApprovedOn = $_POST['ApprovedOn'];
		$status = $_POST['status'];
		
					
		$crud = new grocery_CRUD();
		$crud->set_model('Funding_GC');

		if(isset($_POST['row'])) {
			$id = $_POST['row'];
			$oAmt = $this->db->query("SELECT Amount FROM Funding WHERE FundID='".$id."'")->row();
			$oldamount = $oAmt->Amount;
		} else {
			$rmAmt = $crud->basic_model->return_query("SELECT Amount FROM Funding WHERE FundID='". $id . "'");
			$oldamount = $rmAmt[0]->Amount;
		}
				
		$newDateRep = preg_replace('/\//', '-',$ApprovedOn);
		$newDate = date("Y-m-d H:i:s", strtotime($newDateRep));
		
		$resp = $crud->basic_model->update_fund($id, $newamount, $PaymentType, $Approvedby, $newDate, $status, $projectID, $oldamount);

		echo $resp;
	}

	public function getFBName($id) {

		$crud = new grocery_CRUD();
		$crud->set_model('Funding_GC');
		$res = $crud->basic_model->return_query("SELECT BodyName FROM FundingBody WHERE FundBodyID='$id' LIMIT 1");


		$resp = array();
		$resp["FBName"] = $res[0]->BodyName;
		echo json_encode($resp);
	}

}