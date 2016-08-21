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

	//Deprecated, converted over to Custom Model
	/*public function employee() {

		$crud = new grocery_CRUD();
		$crud->set_theme('flexigrid');
		$crud->set_table('Person');
		$crud->set_subject('Employee');
		$crud->where("Role", "EMPLOYEE");
		$crud->columns('Role', 'FirstName', 'LastName', 'Address', 'SuburbID', 'WorkEmail', 'PersonalEmail', 'Mobile', 'HomePhone');
		$crud->add_fields('FirstName', 'MiddleName', 'LastName', 'Address', 'SuburbID', 'WorkEmail', 'PersonalEmail', 'Mobile', 'HomePhone', 'Status', 'DateStarted', 'WWC', 'WWCFiled', 'Username');
		$crud->edit_fields('FirstName', 'MiddleName', 'LastName', 'Address', 'SuburbID', 'WorkEmail', 'PersonalEmail', 'Mobile', 'HomePhone', 'Status', 'DateStarted', 'DateFinished', 'WWC', 'WWCFiled', 'Username');
		$crud->display_as('FirstName', 'First Name');
		$crud->display_as('MiddleName', 'Middle Name');
		$crud->display_as('LastName', 'Last Name');
		$crud->display_as('WorkEmail', 'Work Email');
		$crud->display_as('PersonalEmail', 'Personal Email');
		$crud->display_as('HomePhone', 'Home Phone');
		$crud->display_as('DateStarted', 'Date Started');
		$crud->unset_add();

		$output = $crud->render();

		$this->render($output);
	}*/


	public function employee() {

		$crud = new grocery_CRUD();
		$crud->set_model('Employee_GC');
		$crud->set_table('Person');
		$crud->set_subject('Employee');
		$crud->basic_model->set_query_str('SELECT `Project`.Name, `Project`.ProjID, `PersonProject`.Role as ProjRole, CONCAT(FirstName, " ", MiddleName, " ", LastName) as FullName, `Person`.* FROM `Person` LEFT OUTER JOIN `PersonProject` ON `Person`.PerID=`PersonProject`.PerID LEFT OUTER JOIN `Project` ON `PersonProject`.ProjID=`Project`.ProjID WHERE `PersonProject`.Role="Employee" ORDER BY `Project`.Name ASC');
		$crud->columns("Name", "FullName", "Address", "SuburbID", "WorkEmail", "PersonalEmail", "Mobile", "HomePhone");
		$crud->display_as("Name", "Project Name");
		$crud->display_as("ProjRole", "Project Role");
		$crud->display_as("FullName", "Full Name");

		
		
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
		$crud->callback_before_insert(array($this,'employee_add'));

		$crud->unset_edit();
		$crud->unset_delete();
		$crud->add_action('Delete', '', '', 'delete-icon', array($this, 'employee_delete'));
		//$crud->callback_delete(array($this, 'volunteer_delete'));

		$output = $crud->render();

		$this->render($output);
	}

	function employee_delete($primarykey, $row) {
		return base_url().'user/employee/index/pp_delete/'.$primarykey.'/'.$row->ProjID;
	}

	function employee_add($post_array) {
		
		$this->pp_insert($post_array);
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