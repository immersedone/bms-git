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
		$crud->basic_model->set_query_str('SELECT Proj.ProjID, Proj.Name as ProjName, FB.BodyName as FBName, CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as FullName, Fund.* from `Funding` Fund
		LEFT OUTER JOIN `FundingBody` FB on FB.FundBodyID=Fund.FundBodyID
		LEFT OUTER JOIN `Project` Proj on Proj.ProjID=Fund.ProjID
		LEFT OUTER JOIN `Person` Per on Per.PerID=Fund.ApprovedBy', ' GROUP BY FundID');
		$crud->set_read_fields('ProjID', 'FundBodyID', 'Amount', 'PaymentType', 'status', 'ApprovedBy', 'ApprovedOn');
		$crud->columns('ProjName', 'FBName', 'Amount', 'PaymentType', 'FullName', 'status', 'ApprovedOn');
		$crud->display_as('ProjName', 'Project');
		$crud->display_as('FBName', 'Funding Body');
		$crud->display_as('PaymentType', 'Payment Type');
		$crud->display_as('FullName', 'Approved By');
		$crud->display_as('ApprovedBy', 'Approved By');
		$crud->display_as('ApprovedOn', 'Approved On');
		$crud->display_as('ProjID', 'Project Name');
		$crud->display_as('FundBodyID', 'Funding Body');
		
		//Change the Insert Funding fields
		$crud->add_fields("ProjName", "FBName", "Amount", "PaymentType", 'status', "FullName", "ApprovedOn");
	
		//Call Model to get the Project Names
		$projects = $crud->basic_model->return_query("SELECT ProjID, Name as ProjName FROM Project");
		
		//Convert Return Object into Associative Array
		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->ProjName];
		}

		//Change the field type to a dropdown with values
		//to add to the relational table
		$crud->field_type("ProjName", "dropdown", $prjArr);
		
		//Call Model to get the names of the funding bodies
		$fundingbodies = $crud->basic_model->return_query("SELECT FundBodyID, BodyName FROM FundingBody");
		
		//Convert Return Object into Associative Array
		$FBArr = array();
		foreach($fundingbodies as $fb) {
			$FBArr += [$fb->FundBodyID => $fb->BodyName];
		}

		//Change the field type to a dropdown with values
		//to add to the relational table
		$crud->field_type("FBName", "dropdown", $FBArr);		
				
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

		//Change the default method to fire when organizing funding for a project
		$crud->callback_before_insert(array($this,'volunteer_add'));

		$crud->unset_edit();
		//$crud->unset_delete();
		//$crud->add_action('Delete', '', '', 'delete-icon', array($this, 'delete_fund'));
		//$crud->callback_delete(array($this, 'delete_fund'));
		
		$output = $crud->render();

		$this->render($output);
	}

	public function fundproj($id) {

		$crud = new grocery_CRUD();
		$crud->set_model('Funding_GC');
		$crud->set_table('Funding');
		$crud->set_subject('Funding');
		$crud->basic_model->set_query_str('SELECT Proj.ProjID, Proj.Name as ProjName, FB.BodyName as FBName, CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as FullName, Fund.* from `Funding` Fund
		LEFT OUTER JOIN `FundingBody` FB on FB.FundBodyID=Fund.FundBodyID
		LEFT OUTER JOIN `Project` Proj on Proj.ProjID=Fund.ProjID
		LEFT OUTER JOIN `Person` Per on Per.PerID=Fund.ApprovedBy', ' GROUP BY FundID');
		$crud->columns('ProjName', 'FBName', 'Amount', 'PaymentType', 'status', 'FullName', 'ApprovedOn');
		$crud->display_as('ProjName', 'Project');
		$crud->display_as('FBName', 'Funding Body');
		$crud->display_as('PaymentType', 'Payment Type');
		$crud->display_as('FullName', 'Approved By');
		$crud->display_as('ApprovedOn', 'Approved On');
		
		//Change the Insert Funding fields
		$crud->add_fields("ProjName", "FBName", "Amount", "PaymentType", 'status', "FullName", "ApprovedOn");
	
		//Call Model to get the Project Names
		$projects = $crud->basic_model->return_query("SELECT ProjID, Name as ProjName FROM Project WHERE ProjID=".$id);
		
		//Convert Return Object into Associative Array
		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->ProjName];
		}

		//Change the field type to a dropdown with values
		//to add to the relational table
		$crud->field_type("ProjName", "dropdown", $prjArr);
		
		//Call Model to get the names of the funding bodies
		$fundingbodies = $crud->basic_model->return_query("SELECT FundBodyID, BodyName FROM FundingBody");
		
		//Convert Return Object into Associative Array
		$FBArr = array();
		foreach($fundingbodies as $fb) {
			$FBArr += [$fb->FundBodyID => $fb->BodyName];
		}

		//Change the field type to a dropdown with values
		//to add to the relational table
		$crud->field_type("FBName", "dropdown", $FBArr);		
				
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

		//Change the default method to fire when organizing funding for a project
		$crud->callback_before_insert(array($this,'funding_add'));

		$crud->unset_edit();
		$crud->unset_delete();
		$crud->add_action('Delete', '', '', 'delete-icon', array($this, 'delete_fund'));
		//$crud->callback_delete(array($this, 'delete_fund'));
		
		$output = $crud->render();

		$this->render($output);
	}

	function delete_fund($primarykey, $row) {
		return base_url().'user/funding/index/fd_delete/'.$primarykey.'/'.$row->ProjID;
	}

	function funding_add($post_array) {
		
		$this->fd_insert($post_array);
	}

	public function fd_delete($uID, $pID) {

		$crud = new grocery_CRUD();
		$crud->set_model('Funding_GC');
		$resp = $crud->basic_model->delete_fund($uID, $pID);
		echo $resp;
		/*if($resp['result'] === "success") {
			redirect(base_url().'/user/volunteer/');
		} else {
			redirect(base_url().'user/volunteer/index/error/e=Error%20Deleting%20Volunteer.%20Please%20try%20again%20or%20contact%20administrator.');
		}*/
	}

	public function fd_insert() {

		//Initialise and assign variables 
		
		$projectID = $_POST['ProjName'];
		$fundbodyid = $_POST['FBName'];
		$amount = $_POST['Amount'];
		$PaymentType = $_POST['PaymentType'];
		$Approvedby = $_POST['FullName'];
		$ApprovedOn = $_POST['ApprovedOn'];

		$crud = new grocery_CRUD();
		$crud->set_model('Funding_GC');
		$resp = $crud->basic_model->insert_fund($projectID, $fundbodyid, $amount, $PaymentType, $Approvedby, $ApprovedOn);
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