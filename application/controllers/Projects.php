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
		$crud->set_model('Extended_generic_model');
		$crud->basic_model->set_query_str("SELECT * FROM Project"); 
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
		$crud->field_type("TotalFunding", "hidden");

		//$crud->form_buttons('View Milestones', 'showMilestones', '');
		//$crud->form_buttons('View Expenditures', 'showReimbursements', '');
		//$crud->form_buttons('View Reimbursements', 'showReimbursements', '');
		//$crud->form_buttons('View Funding', 'showFunding', '');

		//Prettify Report Type
		$stat = $crud->basic_model->return_query("SHOW COLUMNS FROM Project WHERE Field='Status'");
		preg_match("/^enum\(\'(.*)\'\)$/", $stat[0]->Type, $matches);
		$statArr = explode("','", $matches[1]);
		$newstatArr = array();
		foreach($statArr as $st) {
			if($st==="ACTIVE") {
				$newstatArr += [$st => "Active"];
			} else if($st==="COMPLETED") {
				$newstatArr += [$st => "Completed"];
			} else if($st==="TO_BEGIN") {
				$newstatArr += [$st => "To Begin"];
			} else if($st==="DELAYED") {
				$newstatArr += [$st => "Delayed"];
			} else if($st==="ON_HOLD") {
				$newstatArr += [$st => "On Hold"];
			} else if($st==="AWAITING_FUNDS") {
				$newstatArr += [$st => "Awaiting Funds"];
			}
		}

		$crud->field_type('Description', 'text');
		$crud->field_type('Status', 'dropdown', $newstatArr);

		$crud->unset_delete();
		$crud->unset_read();
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
		$GCM->grids[1]->set_model('Project_GC');
		$GCM->grids[1]->set_table('Project');
		$GCM->grids[1]->set_theme('fleximulti');
		$GCM->grids[1]->set_subject('Project Details');
		$GCM->grids[1]->basic_model->set_query_str('SELECT Name, Description, StartDate, FinishDate, TotalFunding FROM Project WHERE ProjID="'.$id.'"');
		$GCM->grids[1]->columns("Name", "Description", "StartDate", "FinishDate", "Status", "TotalFunding");
		//$crud->set_js('People_function.js');
		$GCM->grids[1]->display_as("Name", "Project Name");
		$GCM->grids[1]->display_as("Description", "Project Description");
		$GCM->grids[1]->display_as("StartDate", "Start Date");
		$GCM->grids[1]->display_as("FinishDate", "Finish Date");
		$GCM->grids[1]->display_as("Status", "Project Status");
		$GCM->grids[1]->display_as("TotalFunding", "Total Funding");
		$GCM->grids[1]->unset_edit();
		$GCM->grids[1]->unset_delete();
		$GCM->grids[1]->unset_add();
		$GCM->grids[1]->setStateCode(18);
		$GCM->grids[1]->setNewState($id);

		$GCM->grid_add(2);

	   	$GCM->grids[2]->set_model('Milestone_GC'); 
		$GCM->grids[2]->set_table('Milestone_new');
		$GCM->grids[2]->set_theme('fleximulti');
		$GCM->grids[2]->set_subject('Milestone');
		$GCM->grids[2]->basic_model->set_query_str('SELECT P.Name as ProjName, M.* FROM Milestone_new M
		LEFT OUTER JOIN Project P ON M.ProjID=P.ProjID WHERE M.ProjID='.$id);
			
		$GCM->grids[2]->columns('ShortDesc', 'DueDate', 'RptType', 'Amount', 'Comment', 'FilePath');
		$GCM->grids[2]->display_as('ProjID', 'Project Name');
		$GCM->grids[2]->display_as('ProjName', 'Project Name');
		$GCM->grids[2]->display_as('ShortDesc', 'Description');
		$GCM->grids[2]->display_as('DueDate', 'Due Date');
		$GCM->grids[2]->display_as('RptType', 'Type');
		$GCM->grids[2]->display_as('FilePath', 'Files Attached');
		$GCM->grids[2]->display_as('MSComplete', 'Complete');
		$GCM->grids[2]->field_type('ProjIDhidden', 'invisible');
		$GCM->grids[2]->add_fields('ShortDesc', 'DueDate', 'RptType', 'Amount', 'Comment', 'FilePath', 'ProjID');
		$GCM->grids[2]->callback_before_insert(array($this, 'addProjID'));
		

		$projects = $GCM->grids[2]->basic_model->return_query("SELECT ProjID, Name FROM Project WHERE ProjID='".$id."'");

		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->Name];
		}

		$GCM->grids[2]->field_type("ProjID", "dropdown", $prjArr);
		$GCM->grids[2]->unset_edit();
		$GCM->grids[2]->unset_delete();

		$GCM->grid_add(3);

		$GCM->grids[3]->set_model('Extended_project_model'); 
		$GCM->grids[3]->set_table('Expenditure');
		$GCM->grids[3]->set_theme('fleximulti');
		$GCM->grids[3]->set_subject('Expenditure');
		$GCM->grids[3]->basic_model->set_query_str('SELECT * FROM (SELECT Proj.Name, CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as FullName, Opt.Data as EType, Exp.* from `Expenditure` Exp
		LEFT OUTER JOIN `Project` Proj ON Proj.ProjID=Exp.ProjID
		LEFT OUTER JOIN `Person` Per ON Per.PerID=Exp.SpentBy
		LEFT OUTER JOIN OptionType Opt ON Opt.OptID = Exp.ExpType WHERE Exp.ProjID='.$id.') x');
		$GCM->grids[3]->columns('ExpName', 'CompanyName', 'Reason', 'Amount','SpentByPer', 'Type');
		$GCM->grids[3]->display_as('FullName', 'Spent By');

		$GCM->grids[3]->unset_edit();
		//$GCM->grids[3]->unset_delete();

		$GCM->grid_add(4);

		$GCM->grids[4]->set_model('Funding_GC');
		$GCM->grids[4]->set_table('Funding');
		$GCM->grids[4]->set_theme('fleximulti');
		$GCM->grids[4]->set_subject('Funding');
		$GCM->grids[4]->basic_model->set_query_str('SELECT * FROM (SELECT Proj.Name as ProjName, FB.BodyName as FBName, CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as SpentBy, Fund.* from `Funding` Fund
		LEFT OUTER JOIN `FundingBody` FB on FB.FundBodyID=Fund.FundBodyID
		LEFT OUTER JOIN `Project` Proj on Proj.ProjID=Fund.ProjID
		LEFT OUTER JOIN `Person` Per on Per.PerID=Fund.ApprovedBy WHERE Fund.ProjID='.$id.') x', ' GROUP BY FundID');
		$GCM->grids[4]->columns('FBName', 'Amount', 'PaymentType', 'SpentBy', 'ApprovedOn');
		$GCM->grids[4]->display_as('ProjName', 'Project Name');
		$GCM->grids[4]->display_as('FBName', 'Funding Body');
		$GCM->grids[4]->display_as('PaymentType', 'Payment Type');
		$GCM->grids[4]->display_as('SpentBy', 'Approved By');
		$GCM->grids[4]->display_as('ApprovedOn', 'Approved On');
		//$GCM->grids[4]->callback_before_delete(array($this, 'fund_delete'));
		$GCM->grids[4]->add_action('Delete', '', '', 'delete-icon fund_delete', array($this, 'fund_delete'));
		$GCM->grids[4]->unset_delete();

		//Change the Insert Funding fields
		$GCM->grids[4]->add_fields("ProjName", "FBName", "Amount", "PaymentType", "SpentBy", "ApprovedOn");
	
		//Call Model to get the Project Names
		$projects = $GCM->grids[4]->basic_model->return_query("SELECT ProjID, Name as ProjName FROM Project");
		
		//Convert Return Object into Associative Array
		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->ProjName];
		}

		//Change the field type to a dropdown with values
		//to add to the relational table
		$GCM->grids[4]->field_type("ProjName", "dropdown", $prjArr);
		
		//Call Model to get the names of the funding bodies
		$fundingbodies = $GCM->grids[4]->basic_model->return_query("SELECT FundBodyID, BodyName FROM FundingBody");
		
		//Convert Return Object into Associative Array
		$FBArr = array();
		foreach($fundingbodies as $fb) {
			$FBArr += [$fb->FundBodyID => $fb->BodyName];
		}

		//Change the field type to a dropdown with values
		//to add to the relational table
		$GCM->grids[4]->field_type("FBName", "dropdown", $FBArr);		
				
		//Call Model to get the User's Full Names
		$users = $GCM->grids[4]->basic_model->return_query("SELECT PerID, CONCAT(FirstName, ' ', MiddleName, ' ', LastName) as FullName FROM Person");

		//Convert Return Object into Associative Array
		$usrArr = array();
		foreach($users as $usr) {
			$usrArr += [$usr->PerID => $usr->FullName];
		}
		
		//Change the field type to a dropdown with values
		//to add to the relational table
		$GCM->grids[4]->field_type("SpentBy", "dropdown", $usrArr);

		//Change the default method to fire when organizing funding for a project
		//$GCM->grids[4]->callback_before_insert(array($this,'volunteer_add'));
		//$GCM->grids[4]->callback_before_delete(array($this,'delete_fund'));

		$GCM->grids[4]->unset_edit();

		$GCM->grid_add(5);

		$GCM->grids[5]->set_model('Volunteer_GC');
		$GCM->grids[5]->set_table('PersonProject');
		$GCM->grids[5]->set_theme('fleximulti');
		$GCM->grids[5]->set_subject('Volunteers');
		$GCM->grids[5]->basic_model->set_query_str("SELECT * FROM (SELECT CONCAT(Vol.FirstName, ' ', Vol.MiddleName, ' ', Vol.LastName) as VolName, O.Data as Role, PP.StartDate, PP.FinishDate FROM PersonProject PP
			LEFT OUTER JOIN Person Vol ON Vol.PerID = PP.PerID
			LEFT OUTER JOIN Project Proj ON Proj.ProjID = PP.ProjID
			LEFT OUTER JOIN OptionType O on O.OptID = PP.Role
			WHERE PP.EmpVol = 'Vol'
			AND PP.ProjID=".$id.") x");
		$GCM->grids[5]->columns("VolName", "Role", "StartDate", "FinishDate");
		$GCM->grids[5]->display_as("VolName", "Volunteer Name");
		$GCM->grids[5]->display_as("Role", "Project Role");	
		$GCM->grids[5]->display_as("StartDate", "Start Date");
		$GCM->grids[5]->display_as("FinishDate", "Finish Date");
		
		/*
		$GCM->grids[5]->add_fields("VolName", "Role", "IsActive", "StartDate", "FinishDate", "projectID", "EmpVol");
		$GCM->grids[5]->field_type('EmpVol', 'hidden', 'Vol');
		$GCM->grids[5]->field_type("projectID", 'hidden', $id);

		//Roles in a Project
		$roles = $GCM->grids[5]->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='Role'");
		$roleArr = array();
		foreach($roles as $role) {
			$roleArr += [$role->OptID => $role->data];
		}
		
		//Full Names
		$users = $GCM->grids[5]->basic_model->return_query("SELECT PerID, CONCAT(FirstName, ' ', MiddleName, ' ', LastName) FROM Person WHERE EXISTS(SELECT PerID FROM Volunteer)");
		$usrArr = array();
		foreach($users as $usr) {
			$usrArr += [$usr->PerID => $usr->FullName];
		}		
		
		$GCM->grids[5]->field_type("Role", "dropdown", $roleArr);
		$GCM->grids[5]->field_type("VolName", "dropdown", $usrArr);
		*/
		//Change the default method to fire when adding
		//a new person to a project
		$GCM->grids[5]->callback_before_insert(array($this,'volunteer_add'));

		//$GCM->grids[5]->callback_before_delete(array($this,'vol_delete'));
		$GCM->grids[5]->unset_edit();
		$GCM->grids[5]->unset_delete();
		
		$GCM->grid_add(6);
		$GCM->grids[6]->set_model("Employee_GC");
		$GCM->grids[6]->set_table('Person');
		$GCM->grids[6]->set_theme('fleximulti');
		$GCM->grids[6]->set_subject('Employee');
		$GCM->grids[6]->basic_model->set_query_str('SELECT * FROM (SELECT CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as EmpName, PP.*, Opt.Data as EmpRole FROM Person Per
		LEFT OUTER JOIN PersonProject PP ON PP.PerID = Per.PerID
		LEFT OUTER JOIN OptionType Opt ON Opt.OptID = PP.Role
		WHERE PP.EmpVol="Emp" AND PP.ProjID="'. $id . '") x');
		$GCM->grids[6]->columns("EmpName", "EmpRole", "StartDate", "FinishDate");
		$GCM->grids[6]->display_as("EmpName", "Project Name");
		$GCM->grids[6]->display_as("EmpRole", "Project Role");
		$GCM->grids[6]->display_as("StartDate", "Start Date");
		$GCM->grids[6]->display_as("FinishDate", "Finish Date");
		/*
		$GCM->grids[6]->add_fields("EmpName", "Role", "IsActive", "StartDate", "FinishDate", "projectID");
		$GCM->grids[6]->field_type("projectID", 'hidden', $id);

		//BCGS Departments
		$bcgs = $GCM->grids[6]->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='BGCS_DEP'");
		$bcgsArr = array();
		foreach($bcgs as $bc) {
			$bcgsArr += [$bc->OptID => $bc->data];
		}
		//Roles in a Project
		$roles = $GCM->grids[6]->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='Role'");
		$roleArr = array();
		foreach($roles as $role) {
			$roleArr += [$role->OptID => $role->data];
		}
		
		//Full Names
		$users = $GCM->grids[6]->basic_model->return_query("SELECT PerID, CONCAT(FirstName, ' ', MiddleName, ' ', LastName) FROM Person WHERE EXISTS(SELECT PerID FROM Employee)");
		$usrArr = array();
		foreach($users as $usr) {
			$usrArr += [$usr->PerID => $usr->FullName];
		}	
		//Position
		$pos = $GCM->grids[6]->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='Position'");
		$posArr = array();
		foreach($pos as $p) {
			$posArr += [$p->OptID => $p->data];
		}	
		
		$GCM->grids[6]->field_type("Position", "dropdown", $posArr);
		$GCM->grids[6]->field_type("Dept", "dropdown", $bcgsArr);
		$GCM->grids[6]->field_type("Role", "dropdown", $roleArr);
		$GCM->grids[6]->field_type("VolName", "dropdown", $usrArr);
		$GCM->grids[6]->field_type("SupName", "dropdown", $usrArr);
		*/
		//$GCM->grids[6]->unset_add();
		$GCM->grids[6]->unset_edit();
		$GCM->grids[6]->unset_delete();
		//$GCM->grids[6]->add_action('Delete', '', '', 'delete-icon', array($this, 'employee_delete'));
		//$crud->callback_delete(array($this, 'volunteer_delete'));

		$output = $GCM->render();

		$pos = array_search(base_url() . 'assets/grocery_crud/themes/flexigrid/js/flexigrid-edit.js', $output['js_files']);
		unset($output['js_files'][$pos]);
	  	
		//print_r($GCM->grids[1]);

	   $this->_output_multi($output);
	}

	function vol_delete($primarykey, $row) {
		return base_url().'user/volunteer/index/pp_delete/'.$primarykey.'/';
	}

	function fund_delete($primarykey, $row) {
		return base_url().'user/funding/index/delete/'.$primarykey.'/';
	}

	function employee_read($primarykey, $row) {
		return base_url().'user/projects/index/setID/'.$primarykey;
	}

	function employee_delete($primarykey, $row) {
		return base_url().'user/employee/index/pp_delete/'.$primarykey.'/'.$row->ProjID;
	}

	function volunteer_add($primarykey, $row) {
		print_r($row);
		return base_url().'user/employee/index/pp_insert/'.$primarykey.'/'.$row->ProjID;
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

	public function addProjID($post_array) {

		$id = get_cookie('projID');

		$post_array['ProjIDhidden'] = $id;

		return $post_array;
	}

	function delete_fund($primarykey) {
		$crud = new grocery_CRUD();
		$crud->set_model('Funding_GC');
		$resp = $crud->basic_model->delete_fund($primarykey);
	}
	
	public function getProjName($id) {

		$crud = new grocery_CRUD();
		$crud->set_model('Project_GC');
		$res = $crud->basic_model->return_query("SELECT Name FROM Project WHERE ProjID='$id' LIMIT 1");

		$resp = array();
		$resp["ProjName"] = $res[0]->Name;
		echo json_encode($resp);
	}
}