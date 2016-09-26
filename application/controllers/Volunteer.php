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
		$crud->columns("FullName",  "ProjID", "Supervisor", "BGCSDepartment", "isActive", "DateStarted", "DateFinished", "RefFullName", "RefMobile", "RefHPhone", "RefRelToVol", "DaysAvailable", "ContSkills", "ContQual");
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
		$crud->display_as("DateStarted", "Date Started");
		$crud->display_as("DateFinished", "Date Finished");
		$crud->display_as("isActive", "Is Active");

		$crud->add_fields("FullName", "ProjID", "Supervisor", "BGCSDepartment", "isActive", "DateStarted", "DateFinished", "RefFullName", "RefMobile", "RefHPhone", "RefRelToVol", "DaysAvailable", "ContSkills", "ContQual");
		$crud->edit_fields("PerID",  "ProjID", "Supervisor", "BGCSDepartment", "isActive", "DateStarted", "DateFinished", "RefFullName", "RefMobile", "RefHPhone", "RefRelToVol", "DaysAvailable", "ContSkills", "ContQual");
		$crud->set_read_fields("PerID",  "ProjID", "Supervisor", "BGCSDepartment", "isActive", "DateStarted", "DateFinished", "RefFullName", "RefMobile", "RefHPhone", "RefRelToVol", "DaysAvailable", "ContSkills", "ContQual");


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
			$crudTwo->display_as('DateStarted', 'Date Started');
			$crudTwo->display_as('DateFinished', 'Date Finished');
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
			$output["perID"] = $perID;
			$crudTwo->setNewState($perID);

			$crudThree = new grocery_CRUD();
			$crudThree->set_model('Extended_generic_model');
			$crudThree->set_table('Volunteer');
			$crudThree->set_subject('Volunteer History');
			$crudThree->basic_model->set_query_str("SELECT * FROM Volunteer WHERE PerID='$perID' AND VolID!='$pkID'");
			$crudThree->columns("ProjID", "Supervisor", "BGCSDepartment", "isActive", "DateStarted", "DateFinished", "RefFullName", "RefMobile", "RefHPhone", "RefRelToVol", "DaysAvailable", "ContSkills", "ContQual");
			$crudThree->display_as("ProjID", "Project Name");
			$crudThree->display_as("BGCSDepartment", "BGCS Department");
			$crudThree->display_as("DateStarted", "Date Started");
			$crudThree->display_as("DateFinished", "Date Finished");
			$crudThree->display_as("RefFullName", "Referee Full Name");
			$crudThree->display_as("RefMobile", "Referee Mobile");
			$crudThree->display_as("RefHPhone", "Referee Home Phone");
			$crudThree->display_as("RefRelToVol", "Referee Relation to Volunteer");
			$crudThree->display_as("DaysAvailable", "Days Available");
			$crudThree->display_as("ContSkills", "Skills and Experience");
			$crudThree->display_as("isActive", "Is Active");
			$crudThree->display_as("ContQual", "Qualifications and Current Studies");
			$crudThree->field_type("ProjID", "dropdown", $projArr);
			$crudThree->field_type("BGCSDepartment", "dropdown", $bcgsArr);
			$crudThree->field_type("DaysAvailable", "multiselect", $daysArr);
			$crudThree->field_type("Supervisor", "dropdown", $usrArr);
			
			$crudThree->setStateCode(1);
			$crudThree->unset_add();
			$crudThree->unset_edit();
			$crudThree->unset_delete();
			$volHistoryOP = $crudThree->render();


			//echo $perID;
			$sInf = $crudTwo->getStateInfo();

			//print_r($sInf);

			$fullDetailsOP = $crudTwo->render();
			

			//print_r($fullDetailsOP);

			$output["fullDetails"] = $fullDetailsOP;
			$output["volHistory"] = $volHistoryOP;
			$output["multiView"] = "YES";
			
		} else {
			$output["fullDetails"] = "";
			$output["volHistory"] = "";
			$output["multiView"] = "NO";
		}
		$output["volunteer"] = $volunteerOP;


		$this->render($output);
	}

	public function volproj($id) {

		$crud = new grocery_CRUD();
		$crud->set_model('Volunteer_GC');
		$crud->set_table('Person');
		$crud->set_subject('Volunteer');
		$crud->basic_model->set_query_str('SELECT Proj.Name, Proj.ProjID, `PersonProject`.Role as ProjRole, CONCAT(FirstName, " ", MiddleName, " ", LastName) as FullName, Sub.SuburbName as SubName, Sub.Postcode as Postcode, Per.* FROM `Person` Per 
		LEFT OUTER JOIN `PersonProject` ON Per.PerID=`PersonProject`.PerID 
		LEFT OUTER JOIN `Project` Proj ON `PersonProject`.ProjID=Proj.ProjID 
		LEFT OUTER JOIN `Suburb` Sub ON Sub.SuburbID=Per.SuburbID 
		WHERE `PersonProject`.Role="VOLUNTEER"', ' GROUP BY FullName, Name, ProjRole');
		$crud->columns("Name", "FullName", "Address", "Postcode", "SubName", "WorkEmail", "PersonalEmail", "Mobile", "HomePhone");
		$crud->display_as("Name", "Project Name");
		$crud->display_as("ProjRole", "Project Role");
		$crud->display_as("FullName", "Full Name");
		$crud->display_as("SubName", "Suburb");
		
		//Change the Add Volunteer Fields
		$crud->add_fields("FullName", "Name", "Role");

		
		//Call Model to get the Project Names
		$projects = $crud->basic_model->return_query("SELECT ProjID, Name FROM Project WHERE ProjID=".$id);
		
		//Convert Return Object into Associative Array
		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->Name];
		}

		//Change the field type to a dropdown with values
		//to add to the relational table
		$crud->field_type("Name", "dropdown", $prjArr);
		
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

		//Change the default method to fire when adding
		//a new person to a project
		$crud->callback_before_insert(array($this,'volunteer_add'));

		$crud->unset_edit();
		$crud->unset_delete();
		$crud->add_action('Delete', '', '', 'delete-icon', array($this, 'volunteer_delete'));
		//$crud->callback_delete(array($this, 'volunteer_delete'));

		$output = $crud->render();

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
		$personID = $_POST['FullName'];
		$projectID = $_POST['Name'];
		$role = $_POST['Role'];

		$crud = new grocery_CRUD();
		$crud->set_model('Volunteer_GC');
		$resp = $crud->basic_model->insert_pp($personID, $projectID, $role);
		echo $resp;
	}

}