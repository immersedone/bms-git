<?php

class Expenditures extends CI_Controller {

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
		$this->expenditures();
	}

	public function render($output = null) {
		$this->load->view('expenditures', $output);
	}

	public function expenditures() {

		$crud = new grocery_CRUD();
		$crud->set_model('Extended_generic_model'); 
		$crud->set_table('Expenditure');
		$crud->set_subject('Expenditure');
		$crud->basic_model->set_query_str('SELECT Proj.Name, CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as FullName, Opt.Data as EType, Exp.* from `Expenditure` Exp
		LEFT OUTER JOIN `Project` Proj ON Proj.ProjID=Exp.ProjID
		LEFT OUTER JOIN `Person` Per ON Per.PerID=Exp.SpentBy
		LEFT OUTER JOIN OptionType Opt ON Opt.OptID = Exp.ExpType');
		$crud->display_as('ExpName', 'Expenditure Name');
		$crud->display_as('EType', 'Expenditure Type');
		$crud->display_as('ProjID', 'Project Name');
		$crud->display_as('FullName', 'Spent By');
		$crud->display_as('SpentBy', 'Spent By');
		$crud->display_as('Name', 'Project Name');

		$projects = $crud->basic_model->return_query("SELECT ProjID, Name FROM Project");

		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->Name];
		}

		$crud->field_type("Name", "dropdown", $prjArr);
				
		//Expenditure Types
		$exptypes = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType
		where type = 'Expenditure'");
		$expArr = array();
		foreach($exptypes as $exp) {
			$expArr += [$exp->OptID => $exp->data];
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
		$crud->field_type("EType", "dropdown", $expArr);
		$crud->field_type("SpentBy", "dropdown", $usrArr);
		$crud->field_type("FullName", "dropdown", $usrArr);
		$crud->field_type("ProjID", "dropdown", $prjArr);
		$crud->callback_before_insert(array($this,'expenditure_add'));
		
		
		$output = $crud->render();

		$this->render($output);
	}

	public function expendproj($id) {
		echo $id;
		$crud = new grocery_CRUD();
		$crud->set_model('Expenditure_model'); 
		$crud->set_table('Expenditure');
		$crud->set_subject('Expenditure');
		$crud->basic_model->set_query_str('SELECT Proj.Name, CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as FullName, Exp.* from `Expenditure` Exp
		LEFT OUTER JOIN `Project` Proj ON Proj.ProjID=Exp.ProjID
		LEFT OUTER JOIN `Person` Per ON Per.PerID=Exp.SpentBy');
		$crud->columns('Name', 'ExpName', 'Reason', 'Amount', 'ApprovedBy', 'FullName'); 
		$crud->add_fields('ProjID', 'ExpName', 'Reason', 'Amount', 'ApprovedBy', 'FullName');
		$crud->display_as('ExpName', 'Expenditure Name');
		$crud->display_as('ApprovedBy', 'Approved By'); 
		$crud->display_as('FullName', 'Spent By');
		$crud->display_as('ProjID', 'Project Name');
		$crud->display_as('Name', 'Project Name');

		$projects = $crud->basic_model->return_query("SELECT ProjID, Name FROM Project WHERE ProjID=".$id);

		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->Name];
		}

		$crud->field_type("ProjID", "dropdown", $prjArr);

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
		$crud->callback_before_insert(array($this,'expenditure_add'));
		

		$output = $crud->render();

		$this->render($output);
	}
	
	function expenditure_add($post_array) {
		
		$this->exp_insert($post_array);
	}
	
	public function exp_insert() {

		//Initialise and assign variables 
		
		$ExpName = $_POST['ExpName'];
		$Reason = $_POST['Reason'];
		$amount = $_POST['Amount'];
		$Approvedby = $_POST['ApprovedBy'];
		$SpentBy = $_POST['FullName'];
		$ProjectID = $_POST['Name'];
		
		$crud = new grocery_CRUD();
		$crud->set_model('Expenditure_model');
		$resp = $crud->basic_model->insert_expenditure($ExpName, $Reason, $amount, $Approvedby, $SpentBy, $ProjectID);
		echo $resp;
	}

}
