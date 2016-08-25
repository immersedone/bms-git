<?php

class Projects extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('cookie');
		$this->load->helper('url');

		
		$this->load->library('grocery_CRUD');

	}

	public function index()
	{
		//$this->render((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
		$this->projects();
	}

	public function render($output = null) {
		$this->load->view('projects', $output);
	}

	public function projects() {

		$crud = new grocery_CRUD();

		$crud->set_theme('flexigrid');
		$crud->set_table('Project');
		$crud->set_subject('Project');
		$crud->columns("Name", "Description", "StartDate", "FinishDate", "Status", "TotalFunding");
		//$crud->set_js('People_function.js');
		$crud->display_as("Name", "Project Name");
		$crud->display_as("Description", "Project Description");
		$crud->display_as("StartDate", "Start Date");
		$crud->display_as("FinishDate", "Finish Date");
		$crud->display_as("Status", "Project Status");
		$crud->display_as("TotalFunding", "Total Funding");

		//$crud->form_buttons('View Milestones', 'showMilestones', '');
		//$crud->form_buttons('View Expenditures', 'showReimbursements', '');
		//$crud->form_buttons('View Reimbursements', 'showReimbursements', '');
		//$crud->form_buttons('View Funding', 'showFunding', '');

		$crud->unset_operations();
		$crud->add_action('View', '', '', 'read-icon', array($this, 'employee_read'));

		$output = $crud->render();
		//print_r($output);
		$this->render($output);
	}

	public function projread() {

		if(!get_cookie('projID')) {
			redirect(base_url().'user/projects');
		}

		error_reporting(E_ALL ^ E_NOTICE);

		$id = get_cookie('projID');
		$this->load->library('grocery_CRUD');
		$this->load->library('Gmulti');
		$GCM = new Gmulti();

	  	$GCM->grid_add(1);

	   	$GCM->grids[1]->set_model('Extended_generic_model'); 
		$GCM->grids[1]->set_table('Milestone');
		$GCM->grids[1]->set_subject('Milestone');
		$GCM->grids[1]->basic_model->set_query_str('SELECT P.Name as ProjName, M.* from `Milestone` M
		LEFT OUTER JOIN `Project` P on M.ProjID=P.ProjID WHERE M.ProjID='.$id);
			
		$GCM->grids[1]->columns('ProjName', 'Title', 'Description', 'StartDate', 'FinishDate');
		$GCM->grids[1]->display_as('StartDate', 'Start Date');
		$GCM->grids[1]->display_as('FinishDate', 'Finish Date');
		$GCM->grids[1]->display_as('ProjName', 'Project');
		$GCM->grids[1]->add_fields('ProjID', 'Title', 'Description', 'StartDate', 'FinishDate');

		$GCM->grid_add(2);

		$GCM->grids[2]->set_model('Extended_generic_model'); 
		$GCM->grids[2]->set_table('Expenditure');
		$GCM->grids[2]->set_subject('Expenditure');
		$GCM->grids[2]->basic_model->set_query_str('SELECT Proj.Name, CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as FullName, Exp.* from `Expenditure` Exp
		LEFT OUTER JOIN `Project` Proj ON Proj.ProjID=Exp.ProjID
		LEFT OUTER JOIN `Person` Per ON Per.PerID=Exp.ApprovedBy  WHERE Exp.ProjID='.$id);
		$GCM->grids[2]->columns('Name', 'ExpName', 'Reason', 'Amount', 'FullName', 'SpentBy', 'Type');

		$GCM->grid_add(3);

		$GCM->grids[3]->set_model('Funding_GC');
		$GCM->grids[3]->set_table('Funding');
		$GCM->grids[3]->set_subject('Funding');
		$GCM->grids[3]->basic_model->set_query_str('SELECT Proj.Name as ProjName, FB.BodyName as FBName, CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as FullName, Fund.* from `Funding` Fund
		LEFT OUTER JOIN `FundingBody` FB on FB.FundBodyID=Fund.FundBodyID
		LEFT OUTER JOIN `Project` Proj on Proj.ProjID=Fund.ProjID
		LEFT OUTER JOIN `Person` Per on Per.PerID=Fund.ApprovedBy WHERE Fund.ProjID='.$id, ' GROUP BY FundID');
		$GCM->grids[3]->columns('ProjName', 'FBName', 'Amount', 'PaymentType', 'FullName', 'ApprovedOn');
		$GCM->grids[3]->display_as('ProjName', 'Project');
		$GCM->grids[3]->display_as('FBName', 'Funding Body');
		$GCM->grids[3]->display_as('PaymentType', 'Payment Type');
		$GCM->grids[3]->display_as('FullName', 'Approved By');
		$GCM->grids[3]->display_as('ApprovedOn', 'Approved On');
		
		//Change the Insert Funding fields
		$GCM->grids[3]->add_fields("ProjName", "FBName", "Amount", "PaymentType", "FullName", "ApprovedOn");
	
		//Call Model to get the Project Names
		$projects = $GCM->grids[3]->basic_model->return_query("SELECT ProjID, Name as ProjName FROM Project");
		
		//Convert Return Object into Associative Array
		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->ProjName];
		}

		//Change the field type to a dropdown with values
		//to add to the relational table
		$GCM->grids[3]->field_type("ProjName", "dropdown", $prjArr);
		
		//Call Model to get the names of the funding bodies
		$fundingbodies = $GCM->grids[3]->basic_model->return_query("SELECT FundBodyID, BodyName FROM FundingBody");
		
		//Convert Return Object into Associative Array
		$FBArr = array();
		foreach($fundingbodies as $fb) {
			$FBArr += [$fb->FundBodyID => $fb->BodyName];
		}

		//Change the field type to a dropdown with values
		//to add to the relational table
		$GCM->grids[3]->field_type("FBName", "dropdown", $FBArr);		
				
		//Call Model to get the User's Full Names
		$users = $GCM->grids[3]->basic_model->return_query("SELECT PerID, CONCAT(FirstName, ' ', MiddleName, ' ', LastName) as FullName FROM Person");

		//Convert Return Object into Associative Array
		$usrArr = array();
		foreach($users as $usr) {
			$usrArr += [$usr->PerID => $usr->FullName];
		}
		
		//Change the field type to a dropdown with values
		//to add to the relational table
		$GCM->grids[3]->field_type("FullName", "dropdown", $usrArr);

		//Change the default method to fire when organizing funding for a project
		$GCM->grids[3]->callback_before_insert(array($this,'volunteer_add'));

		$GCM->grids[3]->unset_edit();
		$GCM->grids[3]->unset_delete();
		//$GCM->grids[3]->add_action('Delete', '', '', 'delete-icon', array($this, 'delete_fund'));

		$GCM->grid_add(4);

		$GCM->grids[4]->set_model('Volunteer_GC');
		$GCM->grids[4]->set_table('Person');
		$GCM->grids[4]->set_subject('Volunteer');
		$GCM->grids[4]->basic_model->set_query_str('SELECT Proj.Name, Proj.ProjID, `PersonProject`.Role as ProjRole, CONCAT(FirstName, " ", MiddleName, " ", LastName) as FullName, Sub.SuburbName as SubName, Sub.Postcode as Postcode, Per.* FROM `Person` Per 
		LEFT OUTER JOIN `PersonProject` ON Per.PerID=`PersonProject`.PerID 
		LEFT OUTER JOIN `Project` Proj ON `PersonProject`.ProjID=Proj.ProjID 
		LEFT OUTER JOIN `Suburb` Sub ON Sub.SuburbID=Per.SuburbID 
		WHERE `PersonProject`.Role="VOLUNTEER" AND PersonProject.ProjID='.$id, ' GROUP BY FullName, Name, ProjRole');
		$GCM->grids[4]->columns("Name", "FullName", "Address", "Postcode", "SubName", "WorkEmail", "PersonalEmail", "Mobile", "HomePhone");
		$GCM->grids[4]->display_as("Name", "Project Name");
		$GCM->grids[4]->display_as("ProjRole", "Project Role");
		$GCM->grids[4]->display_as("FullName", "Full Name");
		$GCM->grids[4]->display_as("SubName", "Suburb");
		
		//Change the Add Volunteer Fields
		$GCM->grids[4]->add_fields("FullName", "Name", "Role");

		
		//Call Model to get the Project Names
		$projects = $GCM->grids[4]->basic_model->return_query("SELECT ProjID, Name FROM Project");
		
		//Convert Return Object into Associative Array
		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->Name];
		}

		//Change the field type to a dropdown with values
		//to add to the relational table
		$GCM->grids[4]->field_type("Name", "dropdown", $prjArr);
		
		//Call Model to get the User's Full Names
		$users = $GCM->grids[4]->basic_model->return_query("SELECT PerID, CONCAT(FirstName, ' ', MiddleName, ' ', LastName) as FullName FROM Person");

		//Convert Return Object into Associative Array
		$usrArr = array();
		foreach($users as $usr) {
			$usrArr += [$usr->PerID => $usr->FullName];
		}
		
		//Change the field type to a dropdown with values
		//to add to the relational table
		$GCM->grids[4]->field_type("FullName", "dropdown", $usrArr);

		//Change the default method to fire when adding
		//a new person to a project
		$GCM->grids[4]->callback_before_insert(array($this,'volunteer_add'));

		$GCM->grids[4]->unset_edit();
		$GCM->grids[4]->unset_delete();
		//$GCM->grids[4]->add_action('Delete', '', '', 'delete-icon', array($this, 'volunteer_delete'));

		$GCM->grid_add(5);
		$GCM->grids[5]->set_model("Employee_GC");
		$GCM->grids[5]->set_table('Person');
		$GCM->grids[5]->set_subject('Employee');
		$GCM->grids[5]->basic_model->set_query_str(
		'SELECT Proj.Name, Proj.ProjID, `PersonProject`.Role as ProjRole, CONCAT(FirstName, " ", MiddleName, " ", LastName) as FullName, Sub.SuburbName as SubName, 
		Sub.Postcode as Postcode, Per.* FROM `Person` Per 
		LEFT OUTER JOIN `PersonProject` ON Per.PerID=`PersonProject`.PerID 
		LEFT OUTER JOIN `Project` Proj ON `PersonProject`.ProjID=Proj.ProjID 
		LEFT OUTER JOIN `Suburb` Sub ON Sub.SuburbID=Per.SuburbID 
		WHERE `PersonProject`.Role="Employee" AND PersonProject.ProjID='.$id, ' GROUP BY FullName, Name, ProjRole');
		$GCM->grids[5]->columns("Name", "FullName", "Address", "Postcode", "SubName", "WorkEmail", "PersonalEmail", "Mobile", "HomePhone");
		$GCM->grids[5]->display_as("Name", "Project Name");
		$GCM->grids[5]->display_as("ProjRole", "Project Role");
		$GCM->grids[5]->display_as("FullName", "Full Name");
		$GCM->grids[5]->display_as("SubName", "Suburb");
		
		//Change the Add Volunteer Fields
		$GCM->grids[5]->add_fields("FullName", "Name", "Role");

		
		//Call Model to get the Project Names
		$projects = $GCM->grids[5]->basic_model->return_query("SELECT ProjID, Name FROM Project");
		
		//Convert Return Object into Associative Array
		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->Name];
		}

		//Change the field type to a dropdown with values
		//to add to the relational table
		$GCM->grids[5]->field_type("Name", "dropdown", $prjArr);
		
		//Call Model to get the User's Full Names
		$users = $GCM->grids[5]->basic_model->return_query("SELECT PerID, CONCAT(FirstName, ' ', MiddleName, ' ', LastName) as FullName FROM Person");

		//Convert Return Object into Associative Array
		$usrArr = array();
		foreach($users as $usr) {
			$usrArr += [$usr->PerID => $usr->FullName];
		}
		
		//Change the field type to a dropdown with values
		//to add to the relational table
		$GCM->grids[5]->field_type("FullName", "dropdown", $usrArr);

		//Change the default method to fire when adding
		//a new person to a project
		$GCM->grids[5]->callback_before_insert(array($this,'employee_add'));

		$GCM->grids[5]->unset_edit();
		$GCM->grids[5]->unset_delete();
		$GCM->grids[5]->add_action('Delete', '', '', 'delete-icon', array($this, 'employee_delete'));
		//$crud->callback_delete(array($this, 'volunteer_delete'));

		$output = $GCM->render();

	   $this->_output_multi($output);
	}

	function employee_read($primarykey, $row) {
		return base_url().'user/projects/index/setID/'.$primarykey;
	}

	function employee_delete($primarykey, $row) {
		return base_url().'user/employee/index/pp_delete/'.$primarykey.'/'.$row->ProjID;
	}

	function setID($id) {
		if(!get_cookie("projID")) {
			set_cookie("projID", $id, time()+86400);
		} else {
			delete_cookie("projID");
			set_cookie("projID", $id, time()+86400);
		}
		redirect(base_url().'user/projects/index/projread/list');
	}

	function _output_multi($output = null)
	{
	   if(is_array($output['output']))
		$output['output'] = implode(' ',$output['output']);
	  
	   $this->load->view('projects',$output);
	}  
}