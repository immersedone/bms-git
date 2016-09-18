<?php

class Employee extends CI_Controller {

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
		$this->employee();
	}

	public function render($output = null) {
		$this->load->view('employee', $output);
	}


	public function employee() {

		$crud = new grocery_CRUD();
		$crud->set_model('Employee_GC');
		$crud->set_table('Employee');
		$crud->set_subject('Employee');
		$crud->basic_model->set_query_str('SELECT CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as FullName, Per.WorkEmail as WEmail, Per.Mobile, Opt1.data as Pos1, Opt2.data as Pos2, Emp.* from Employee Emp
		LEFT OUTER JOIN Person Per ON Per.PerID = Emp.PerID
		LEFT OUTER JOIN OptionType Opt1 ON Opt1.OptID = Emp.EmpPosition
		LEFT OUTER JOIN OptionType Opt2 ON Opt2.OptID = Emp.EmpSecPosition');
		$crud->columns('FullName', 'WEmail', 'Mobile', 'Pos1','Pos2');
		$crud->display_as("FullName", "Full Name");
		$crud->display_as("Pos1", "Position");
		$crud->display_as("Pos2", "Secondary Position");
		$crud->fields('FullName', 'Pos1', 'Pos2');
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

		$positions = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType
		where type = 'Position'");
		$posArr = array();
		foreach($positions as $pos) {
			$posArr += [$pos->OptID => $pos->data];
		}
		$crud->field_type("Pos1", "dropdown", $posArr);
		$crud->field_type("Pos2", "dropdown", $posArr);
		
		
		
		$output = $crud->render();

		$this->render($output);
	}

	public function empproj($id) {

		$crud = new grocery_CRUD();
		$crud->set_model('Extended_generic_model');
		$crud->set_table('Employee');
		$crud->set_subject('Employee');
		$crud->basic_model->set_query_str(
		'SELECT Proj.Name, Proj.ProjID, `PersonProject`.Role as ProjRole, CONCAT(FirstName, " ", MiddleName, " ", LastName) as FullName, Sub.SuburbName as SubName, 
		Sub.Postcode as Postcode, Per.* FROM `Person` Per 
		LEFT OUTER JOIN `PersonProject` ON Per.PerID=`PersonProject`.PerID 
		LEFT OUTER JOIN `Project` Proj ON `PersonProject`.ProjID=Proj.ProjID 
		LEFT OUTER JOIN `Suburb` Sub ON Sub.SuburbID=Per.SuburbID 
		WHERE `PersonProject`.Role="Employee"', ' GROUP BY FullName, Name, ProjRole');
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

		$output = $crud->render();

		$this->render($output);
	}


	public function pp_delete($uID, $pID) {

		$crud = new grocery_CRUD();
		$crud->set_model('Employee_GC');
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
		$crud->set_model('Employee_GC');
		$resp = $crud->basic_model->insert_pp($personID, $projectID, $role);
		echo $resp;
	}
}