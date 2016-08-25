<?php

class Volunteer extends CI_Controller {

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
		$projects = $crud->basic_model->return_query("SELECT ProjID, Name FROM Project");
		
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