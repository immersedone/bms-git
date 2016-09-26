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
		$crud->columns("FullName", "ProjOne", "ProjOne_Sup", "ProjOne_Dep", "ProjTwo", "ProjTwo_Sup", "ProjTwo_Dep", "ProjThree", "ProjThree_Sup", "ProjThree_Dep", "RefFullName", "RefMobile", "RefHPhone", "RefRelToVol", "DaysAvailable", "ContSkills", "ContQual");
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
		$crud->display_as("ProjOne", "Project #1");
		$crud->display_as("ProjTwo", "Project #2");
		$crud->display_as("ProjThree", "Project #3");
		$crud->display_as("ProjOne_Sup", "Supervisor #1");
		$crud->display_as("ProjTwo_Sup", "Supervisor #2");
		$crud->display_as("ProjThree_Sup", "Supervisor #3");
		$crud->display_as("ProjOne_Dep", "BGCS Department #1");
		$crud->display_as("ProjTwo_Dep", "BGCS Department #2");
		$crud->display_as("ProjThree_Dep", "BGCS Department #3");

		$crud->add_fields("FullName", "ProjOne", "ProjOne_Sup", "ProjOne_Dep", "ProjTwo", "ProjTwo_Sup", "ProjTwo_Dep", "ProjThree", "ProjThree_Sup", "ProjThree_Dep", "RefFullName", "RefMobile", "RefHPhone", "RefRelToVol", "DaysAvailable", "ContSkills", "ContQual");
		$crud->edit_fields("PerID", "ProjOne", "ProjOne_Sup", "ProjOne_Dep", "ProjTwo", "ProjTwo_Sup", "ProjTwo_Dep", "ProjThree", "ProjThree_Sup", "ProjThree_Dep", "RefFullName", "RefMobile", "RefHPhone", "RefRelToVol", "DaysAvailable", "ContSkills", "ContQual");
		$crud->set_read_fields("PerID", "ProjOne", "ProjOne_Sup", "ProjOne_Dep", "ProjTwo", "ProjTwo_Sup", "ProjTwo_Dep", "ProjThree", "ProjThree_Sup", "ProjThree_Dep", "RefFullName", "RefMobile", "RefHPhone", "RefRelToVol", "DaysAvailable", "ContSkills", "ContQual");


		//List of Projects
		$projects = $crud->basic_model->return_query("SELECT ProjID, Name FROM Project");
		$projArr = array();

		foreach($projects as $proj) {
			$projArr += [$proj->ProjID => $proj->Name];
		}

		$crud->field_type("ProjOne", "dropdown", $projArr);
		$crud->field_type("ProjTwo", "dropdown", $projArr);
		$crud->field_type("ProjThree", "dropdown", $projArr);


		//BCGS Departments
		$bcgs = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='BGCS_DEP'");
		$bcgsArr = array();
		foreach($bcgs as $bc) {
			$bcgsArr += [$bc->OptID => $bc->data];
		}
		$crud->field_type("ProjOne_Dep", "dropdown", $bcgsArr);
		$crud->field_type("ProjTwo_Dep", "dropdown", $bcgsArr);
		$crud->field_type("ProjThree_Dep", "dropdown", $bcgsArr);

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
		$crud->field_type("ProjOne_Sup", "dropdown", $usrArr);
		$crud->field_type("ProjTwo_Sup", "dropdown", $usrArr);
		$crud->field_type("ProjThree_Sup", "dropdown", $usrArr);

		$state = $crud->getState();

		$volunteerOP = $crud->render();
		if($state === 'read') {
			
			$output["fullDetails"] = $volunteerOP;
			$output["volHistory"] = $volunteerOP;
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