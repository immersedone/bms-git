<?php

class Volunteer extends CI_Controller {

	//public $multiView;

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
		$this->volunteer();
	}

	public function render($output = null) {
		$this->load->view('volunteer', $output);
	}

	public function volunteer() {

		$crud = new grocery_CRUD();
		$crud->set_model('Volunteer_GC');
		$crud->set_table('Volunteer');
		$crud->set_subject('Volunteer');
		$crud->basic_model->set_query_str('SELECT CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as FullName, Vol.* FROM `Volunteer` Vol 
		LEFT OUTER JOIN Person Per on Per.PerID = Vol.PerID');
		$crud->columns("FullName",  "isActive", "DateStarted", "DateFinished", "RefFullName", "RefMobile", "RefHPhone", "RefRelToVol", "DaysAvailable", "ContSkills", "ContQual");
		$crud->display_as("BGCSDepartment", "Department Assigned To");
		$crud->display_as("RefFullName", "Referee Full Name");
		$crud->display_as("RefMobile", "Referee Mobile");
		$crud->display_as("RefRelToVol", "Referee Relation to Volunteer");
		$crud->display_as("RefHPhone", "Referee Home Phone");
		$crud->display_as("DaysAvailable", "Days Available");
		$crud->display_as("ContSkills", "Skills and Experience");
		$crud->display_as("ContQual", "Qualifications and Current Studies");
		$crud->display_as("FullName", "Full Name");
		$crud->display_as("PerID", "Full Name");
		$crud->display_as("ProjID", "Project");
		$crud->display_as("Supervisor", "Supervisor");
		$crud->display_as("BGCSDepartment", "BGCS Department");
		$crud->display_as("DateStarted", "Date Started as Volunteer");
		$crud->display_as("DateFinished", "Date Finished as Volunteer");
		$crud->display_as("isActive", "Is Active");

		$crud->add_fields("PerID", "isActive", "DateStarted", "DateFinished", "RefFullName", "RefMobile", "RefHPhone", "RefRelToVol", "DaysAvailable", "ContSkills", "ContQual");
		$crud->edit_fields("PerID", "isActive", "DateStarted", "DateFinished", "RefFullName", "RefMobile", "RefHPhone", "RefRelToVol", "DaysAvailable", "ContSkills", "ContQual");
		$crud->set_read_fields("PerID", "isActive", "DateStarted", "DateFinished", "RefFullName", "RefMobile", "RefHPhone", "RefRelToVol", "DaysAvailable", "ContSkills", "ContQual");


		//List of Projects
		$projects = $crud->basic_model->return_query("SELECT ProjID, Name FROM Project");
		$projArr = array();

		foreach($projects as $proj) {
			$projArr += [$proj->ProjID => $proj->Name];
		}

		$crud->field_type("ProjID", "dropdown", $projArr);


		//BCGS Departments
		$bcgs = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='BGCS_DEP'");
		$bcgsArr = array();
		foreach($bcgs as $bc) {
			$bcgsArr += [$bc->OptID => $bc->data];
		}
		$crud->field_type("BGCSDepartment", "dropdown", $bcgsArr);

		//Days Array
		$availability = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='Availability'");
		
		$daysArr = array();
		foreach($availability as $av) {
			$daysArr += [$av->OptID => $av->data];
		}
		$crud->field_type("DaysAvailable", "multiselect", $daysArr);
		
		
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
		$crud->field_type("PerID", "dropdown", $usrArr);
		$crud->field_type("Supervisor", "dropdown", $usrArr);

		$state = $crud->getState();
		$stateInfo = $crud->getStateInfo();
		
		

		$volunteerOP = $crud->render();
		if($state === 'read') {
			$pkID = $stateInfo->primary_key;
			//Instantiate Second CRUD for Full Details
			$crudTwo = new grocery_CRUD();
			$crudTwo->set_model('Extended_generic_model');
			$crudTwo->set_table('Person');
			$crudTwo->set_subject('Person Details');

			//Get the Person ID
			$perID = $crudTwo->basic_model->return_query("SELECT PerID FROM Volunteer WHERE VolID='$pkID'");
			$perID = $perID[0]->PerID;

			$crudTwo->basic_model->set_query_str('SELECT Sub.SuburbName as SubName, Sub.Postcode as Postcode, Per.* from `Person` Per
			LEFT OUTER JOIN `Suburb` Sub ON Per.SuburbID=Sub.SuburbID WHERE PerID="$perID"');
			$crudTwo->columns('FirstName', 'LastName', 'Address', 'Postcode', 'SubName', 'PersonalEmail', 'Mobile', 'HomePhone');
			$crudTwo->display_as('FirstName', 'First Name');
			$crudTwo->display_as('MiddleName', 'Middle Name');
			$crudTwo->display_as('LastName', 'Last Name');
			$crudTwo->display_as('SuburbID', 'Suburb');
			//$crud->display_as('WorkEmail', 'Work Email');
			$crudTwo->display_as('PersonalEmail', 'Personal Email');
			$crudTwo->display_as('HomePhone', 'Home Phone');
			$crudTwo->display_as('DateStarted', 'Date Started with BGCS');
			$crudTwo->display_as('DateFinished', 'Date Finished with BGCS');
			$crudTwo->display_as('ContractSigned', 'Contract Signed');
			$crudTwo->display_as('PaperworkCompleted', 'Paperwork is Completed');
			$crudTwo->display_as('SubName', 'Suburb');
			$crudTwo->display_as('PoliceCheck', 'Valid Police Check');
			$crudTwo->display_as('TeacherRegCheck', 'Valid Teacher Registration');
			$crudTwo->display_as('WWC', 'Working With Children Check (WWC)');
			$crudTwo->display_as('WWCFiled', 'Working With Children Check (WWC) is Filed');
			$crudTwo->display_as('DateofBirth', 'Date of Birth');
			$crudTwo->display_as('FAQual', 'First Aid Qualification Level');
			$crudTwo->display_as('LanguagesSpoken', 'Languages Spoken');
			$crudTwo->display_as('EmergContName', 'Emergency Contact Name');
			$crudTwo->display_as('EmergContMob', 'Emergency Contact Mobile');
			$crudTwo->display_as('EmergContHPhone', 'Emergency Contact Home Phone');
			$crudTwo->display_as('EmergContWPhone', 'Emergency Contact Work Phone');
			$crudTwo->display_as('EmergContRelToPer', 'Emergency Contact Relation');
			$crudTwo->field_type('Username', 'hidden');
			$crudTwo->field_type('Password', 'hidden');
			$crudTwo->field_type('Hash', 'hidden');
			$crudTwo->field_type('Timeout', 'hidden');
			$crudTwo->set_read_fields("Address", "SuburbID", "PersonalEmail", "Mobile", "HomePhone", "Status", "DateStarted", "DateFinished", "ContractSigned", "PaperworkCompleted", "WWC", "WWCFiled", "PoliceCheck", "TeacherRegCheck", "FAQual", "DateofBirth", "LanguagesSpoken", "EmergContName", "EmergContMob", "EmergContHPhone", "EmergContWPhone", "EmergContRelToPer");
			$output["perID"] = $perID;
			$crudTwo->setNewState($perID);

			$crudThree = new grocery_CRUD();
			$crudThree->set_model('Extended_generic_model');
			$crudThree->set_table('PersonProject');
			$crudThree->set_subject('Volunteer History');
			$crudThree->basic_model->set_query_str("SELECT Proj.Name as ProjName, O1.Data as Role, O2.Data as Dept,  CONCAT(Per.FirstName, ' ', Per.MiddleName, ' ', Per.LastName) as SupName, PP.StartDate, PP.FinishDate, PP.PerID, PP.PersonProjectID FROM PersonProject PP
			LEFT OUTER JOIN Person Per ON Per.PerID = PP.Supervisor
			LEFT OUTER JOIN Project Proj ON Proj.ProjID = PP.ProjID
			LEFT OUTER JOIN OptionType O1 on O1.OptID = PP.Role
			LEFT OUTER JOIN OptionType O2 on O2.OptID = PP.BGCSDepartment
			WHERE PP.PerID = '$perID' AND PP.IsActive='0'");
			$crudThree->columns("ProjName", "Role", "Dept", "SupName", "StartDate", "FinishDate");
			
			$crudThree->setStateCode(1);
			$crudThree->unset_add();
			$crudThree->unset_edit();
			$crudThree->unset_delete();
			$crudThree->unset_export();
			$crudThree->unset_print();
			$volHistoryOP = $crudThree->render();

			$crudFour = new grocery_CRUD();
			$crudFour->set_model('Extended_generic_model');
			$crudFour->set_table('PersonProject');
			$crudFour->set_subject('Volunteer History');
			$crudFour->basic_model->set_query_str("SELECT Proj.Name as ProjName, O1.Data as Role, O2.Data as Dept,  CONCAT(Per.FirstName, ' ', Per.MiddleName, ' ', Per.LastName) as SupName, PP.StartDate, PP.FinishDate FROM PersonProject PP
			LEFT OUTER JOIN Person Per ON Per.PerID = PP.Supervisor
			LEFT OUTER JOIN Project Proj ON Proj.ProjID = PP.ProjID
			LEFT OUTER JOIN OptionType O1 on O1.OptID = PP.Role
			LEFT OUTER JOIN OptionType O2 on O2.OptID = PP.BGCSDepartment
			WHERE PP.PerID = '$perID' AND PP.IsActive='1'");
			$crudFour->columns("ProjName", "Role", "Dept", "SupName", "StartDate", "FinishDate");
			
			$crudFour->setStateCode(1);
			$crudFour->unset_add();
			$crudFour->unset_edit();
			$crudFour->unset_delete();
			$crudFour->unset_export();
			$crudFour->unset_print();
			$volCurrentOP = $crudFour->render();

			//echo $perID;
			$sInf = $crudTwo->getStateInfo();

			//print_r($sInf);

			$fullDetailsOP = $crudTwo->render();
			

			//print_r($fullDetailsOP);

			$output["fullDetails"] = $fullDetailsOP;
			$output["volHistory"] = $volHistoryOP;
			$output["volCurrent"] = $volCurrentOP;
			$output["multiView"] = "YES";
			
		} else {
			$output["fullDetails"] = "";
			$output["volHistory"] = "";
			$output["volCurrent"] = "";
			$output["multiView"] = "NO";
		}
		$output["volunteer"] = $volunteerOP;


		$this->render($output);
	}

	public function volproj($id) {

		$crud = new grocery_CRUD();
		$crud->set_model('Volunteer_GC');
		$crud->set_table('PersonProject');
		$crud->set_subject('Volunteer');
		$crud->basic_model->set_query_str("SELECT CONCAT(Vol.FirstName, ' ', Vol.MiddleName, ' ', Vol.LastName) as VolName, O1.Data as Role, O2.Data as Dept,  CONCAT(Sup.FirstName, ' ', Sup.MiddleName, ' ', Sup.LastName) as SupName, PP.StartDate, PP.FinishDate FROM PersonProject PP
			LEFT OUTER JOIN Person Vol ON Vol.PerID = PP.Supervisor
			LEFT OUTER JOIN Person Sup ON Sup.PerID = PP.Supervisor
			LEFT OUTER JOIN Project Proj ON Proj.ProjID = PP.ProjID
			LEFT OUTER JOIN OptionType O1 on O1.OptID = PP.Role
			LEFT OUTER JOIN OptionType O2 on O2.OptID = PP.BGCSDepartment
			LEFT OUTER JOIN OptionType O3 on O3.OptID = PP.Position
			WHERE O3.Data = 'Volunteer' 
			AND PP.ProjID=".$id);
		$crud->columns("VolName", "Role", "Dept", "SupName", "StartDate", "FinishDate");
		$crud->display_as("VolName", "Volunteer Name");
		$crud->display_as("Role", "Project Role");
		$crud->display_as("Dept", "Banksia Deparment");
		$crud->display_as("SupName", "Supervisor Name");	

		$crud->add_fields("VolName", "Role", "position", "Dept", "SupName", "IsActive", "StartDate", "FinishDate", "projectID");
		$crud->field_type("projectID", 'hidden', $id);
		
		$volID = $crud->basic_model->return_query("SELECT OptID FROM OptionType
		WHERE data = 'Volunteer' and type = 'position'");
		$crud->field_type("position", 'hidden', $volID[0]->OptID);

		//BCGS Departments
		$bcgs = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='BGCS_DEP'");
		$bcgsArr = array();
		foreach($bcgs as $bc) {
			$bcgsArr += [$bc->OptID => $bc->data];
		}
		//Roles in a Project
		$roles = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='Role'");
		$roleArr = array();
		foreach($roles as $role) {
			$roleArr += [$role->OptID => $role->data];
		}
		
		//Full Names
		$users = $crud->basic_model->return_query("SELECT PerID, CONCAT(FirstName, ' ', MiddleName, ' ', LastName) as FullName FROM Person");
		$usrArr = array();
		foreach($users as $usr) {
			$usrArr += [$usr->PerID => $usr->FullName];
		}		
		
		$crud->field_type("Dept", "dropdown", $bcgsArr);
		$crud->field_type("Role", "dropdown", $roleArr);
		$crud->field_type("VolName", "dropdown", $usrArr);
		$crud->field_type("SupName", "dropdown", $usrArr);
		//Change the default method to fire when adding
		//a new person to a project
		$crud->callback_before_insert(array($this,'volunteer_add'));

		$crud->unset_edit();
		$crud->unset_delete();
		$crud->add_action('Delete', '', '', 'delete-icon', array($this, 'volunteer_delete'));
		//$crud->callback_delete(array($this, 'volunteer_delete'));

		$output["multiView"] = "NO";	
		
		$output["volunteer"] = $crud->render();
	
		$this->render($output);
	}

	function volunteer_delete($primarykey, $row) {
		return base_url().'user/volunteer/index/pp_delete/'.$primarykey.'/'.$row->ProjID;
	}

	function volunteer_add($post_array) {
		
		$this->pp_insert($post_array);
	}

	public function pp_delete($uID, $pID) {

		$crud = new grocery_CRUD();
		$crud->set_model('Volunteer_GC');
		$resp = $crud->basic_model->delete_pp($uID, $pID);
		echo $resp;
		/*if($resp['result'] === "success") {
			redirect(base_url().'/user/volunteer/');
		} else {
			redirect(base_url().'user/volunteer/index/error/e=Error%20Deleting%20Volunteer.%20Please%20try%20again%20or%20contact%20administrator.');
		}*/
	}

	public function pp_insert() {

		//Initialise and assign variables
		$personID = $_POST['VolName'];
		$projectID = $_POST['projectID'];
		$role = $_POST['Role'];
		$position = $_POST['position'];
		$BGSCDept = $_POST['Dept'];
		$isActive = $_POST['IsActive'];
		$supervisor = $_POST['SupName'];
		$startdate = $_POST['StartDate'];
		$finishdate = $_POST['FinishDate'];

		$crud = new grocery_CRUD();
		$crud->set_model('Volunteer_GC');
		$resp = $crud->basic_model->insert_pp($personID, $projectID, $role, $position, $BGSCDept, $isActive, $supervisor, $startdate, $finishdate);
		echo $resp;
	}

}