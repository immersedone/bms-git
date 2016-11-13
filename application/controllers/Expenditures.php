<?php

class Expenditures extends CI_Controller {

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
		$crud->basic_model->set_query_str('SELECT * FROM(SELECT Proj.Name, CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as FullName, Opt.Data as EType, Exp.* from `Expenditure` Exp
		LEFT OUTER JOIN `Project` Proj ON Proj.ProjID=Exp.ProjID
		LEFT OUTER JOIN `Person` Per ON Per.PerID=Exp.SpentBy
		LEFT OUTER JOIN OptionType Opt ON Opt.OptID = Exp.ExpType) x');
		$crud->display_as('ExpName', 'Expenditure Name');
		$crud->display_as('companyname', 'Company Name');
		$crud->display_as('ExpType', 'Expenditure Type');
		$crud->display_as('EType', 'Expenditure Type');
		$crud->display_as('SpentBy', 'Spent By');
		$crud->display_as('Name', 'Project Name');
		$crud->display_as('ExpDate', 'Date of Expenditure');
		$crud->columns('ProjID','ExpName', 'companyname', 'Reason', 'Amount', 'GST', 'SpentBy', 'ExpType', 'ExpDate', 'FilePath');
		$crud->set_read_fields('ProjID','ExpName', 'companyname', 'Reason', 'Amount', 'GST', 'SpentBy', 'ExpType', 'ExpDate', 'FilePath');
		$crud->add_fields('ProjID','ExpName', 'companyname', 'Reason', 'Amount', 'GST', 'SpentBy', 'ExpType', 'ExpDate', 'FilePath');
		$crud->edit_fields('ProjID','ExpName', 'companyname', 'Reason', 'Amount', 'GST', 'SpentBy', 'ExpType', 'ExpDate', 'FilePath');

		$crud->callback_before_delete(array($this,'crud_delete_file'));

		$projects = $crud->basic_model->return_query("SELECT ProjID, Name FROM Project");

		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->Name];
		}

		$crud->field_type("Name", "dropdown", $prjArr);
				
		//Expenditure Types
		$exptypes = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type = 'EXP_TYPE'");
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
		$crud->set_field_upload('FilePath', 'assets/uploads/files/expenditures');
		$crud->field_type("ExpType", "dropdown", $expArr);
		$crud->field_type("EType", "dropdown", $expArr);
		$crud->field_type("SpentBy", "dropdown", $usrArr);
		$crud->field_type("ProjID", "dropdown", $prjArr);
		$crud->callback_before_insert(array($this,'expenditure_add'));

		
		
		$output = $crud->render();

		$this->render($output);
	}

	public function expendproj($id) {
		$crud = new grocery_CRUD();
		$crud->set_model('Expenditure_model'); 
		$crud->set_table('Expenditure');
		$crud->set_subject('Expenditure');
		$crud->basic_model->set_query_str('SELECT * FROM(SELECT Proj.Name, CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as FullName, Opt.Data as EType, Exp.* from `Expenditure` Exp
		LEFT OUTER JOIN `Project` Proj ON Proj.ProjID=Exp.ProjID
		LEFT OUTER JOIN `Person` Per ON Per.PerID=Exp.SpentBy
		LEFT OUTER JOIN OptionType Opt ON Opt.OptID = Exp.ExpType WHERE Exp.ProjID='.$id.') x');
		$crud->columns('Name', 'ExpName', 'Reason', 'Amount', 'GST', 'FullName'); 
		$crud->add_fields('ProjID', 'ExpName', 'Reason', 'Amount', 'GST', 'SpentBy');
		$crud->edit_fields('ProjID','ExpName', 'companyname', 'Reason', 'Amount', 'GST', 'SpentBy', 'ExpType', 'ExpDate', 'FilePath');
		$crud->display_as('ExpName', 'Expenditure Name');
		$crud->display_as('companyname', 'Company Name');
		$crud->display_as('FullName', 'Spent By');
		$crud->display_as('ProjID', 'Project Name');
		$crud->display_as('Name', 'Project Name');

		$state = $crud->getState();

		if($state === "edit" || $state === "update") {
			$projects = $crud->basic_model->return_query("SELECT Pr.ProjID, Pr.Name FROM Project Pr LEFT OUTER JOIN Expenditure Exp ON Exp.ProjID=Pr.ProjID WHERE Exp.ExpID=".$id);
		} else {
			$projects = $crud->basic_model->return_query("SELECT ProjID, Name FROM Project WHERE ProjID=".$id);
		}

		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->Name];
		}

		if($state === "edit" || $state === "update") {
			//$crud->field_type("ProjID", "readonly");
			$crud->callback_edit_field("ProjID", array($this, 'callback_projID_edit'));
		} else if ($state === "add" || $state === "insert") {
			$crud->callback_add_field("ProjID", function() {
				$id = get_cookie("projID");
				//echo $id;
				$q = $this->db->query('SELECT Name FROM Project WHERE ProjID="' . $id .'" LIMIT 1')->row();
				$readOnly = '<div id="field-ProjID" class="readonly_label">' . $q->Name .'</div>';
				return $readOnly. '<input id="field-ProjID" name="ProjID" type="text" value="' . $id . '" class="numeric form-control" maxlength="255" style="display:none;">';
			});
		}  else {
			$crud->field_type("ProjID", "dropdown", $prjArr);
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
		$crud->field_type("FullName", "dropdown", $usrArr);
		$crud->field_type("SpentBy", "dropdown", $usrArr);
		$crud->set_field_upload('FilePath', 'assets/uploads/files/expenditures');
		$crud->callback_before_insert(array($this,'expenditure_add'));
		

		$output = $crud->render();

		$this->render($output);
	}

	public function callback_projID_edit($value, $primary_key) {
		$q = $this->db->query('SELECT Name FROM Project WHERE ProjID="' . $value .'" LIMIT 1')->row();
		//$projName = array_shift($q->result_array());
		$readOnly = '<div id="field-ProjID" class="readonly_label">' . $q->Name .'</div>';
		return $readOnly . '<input id="field-ProjID" name="ProjID" type="text" value="' . $value . '" class="numeric form-control" maxlength="255" style="display:none;">';
	}
	
	function expenditure_add($post_array) {
		
		$this->exp_insert($post_array);
	}
	
	public function exp_insert() {

		//Initialise and assign variables 
		
		$ExpName = $_POST['ExpName'];
		$Reason = $_POST['Reason'];
		$amount = $_POST['Amount'];
		$gst = $_POST['GST'];
		$SpentBy = $_POST['SpentBy'];
		$ProjectID = $_POST['ProjID'];
		
		$crud = new grocery_CRUD();
		$crud->set_model('Expenditure_model');
		$resp = $crud->basic_model->insert_expenditure($ExpName, $Reason, $amount, $gst, $SpentBy, $ProjectID);
		echo $resp;
	}

public function crud_delete_file($primary_key)
{
    $row = $this->db->where('id',$primary_key)->get('Expenditures')->row();

    unlink('assets/uploads/files/expenditures'.$row->file_url);
   
    return true;
}

	public function getExpBy($id) {

		$crud = new grocery_CRUD();
		$crud->set_model('Project_GC');
		$res = $crud->basic_model->return_query("SELECT SpentBy FROM Expenditure WHERE ExpID='$id' LIMIT 1");

		$resp = array();
		$resp["ExpBy"] = $res[0]->SpentBy;
		echo json_encode($resp);
	}

	public function getExpName($id) {
		$crud = new grocery_CRUD();
		$crud->set_model('Project_GC');
		$res = $crud->basic_model->return_query("SELECT data FROM OptionType WHERE OptID='$id' LIMIT 1");

		$resp = array();
		$resp["ExpName"] = $res[0]->data;
		echo json_encode($resp); 
	}
}
