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
		$crud->basic_model->set_query_str('SELECT CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as FullName, Emp.WorkEmail as WEmail, Per.Mobile, Opt1.data as Pos1, Opt2.data as Pos2, Emp.* from Employee Emp
		LEFT OUTER JOIN Person Per ON Per.PerID = Emp.PerID
		LEFT OUTER JOIN OptionType Opt1 ON Opt1.OptID = Emp.EmpPosition
		LEFT OUTER JOIN OptionType Opt2 ON Opt2.OptID = Emp.EmpSecPosition');
		$crud->columns('FullName', 'WEmail', 'Mobile', 'Pos1','Pos2');
		$crud->display_as("FullName", "Full Name");
		$crud->display_as("Pos1", "Position");
		$crud->display_as("Pos1", "Position");
		$crud->display_as("EmpPosition", "Position");
		$crud->display_as("EmpSecPosition", "Secondary Position");
		$crud->display_as("PerID", "Name");
		$crud->display_as("WorkMob", "Work Mobile");
		$crud->display_as("WorkEmail", "Work Email");
		$crud->display_as("EmpDate", "Employee Start Date");
		$crud->display_as("ContStatus", "Contract Status");
		$crud->display_as("ContStartDate", "Contract Start Date");
		$crud->display_as("ContEndDate", "Contract End Date");
		$crud->display_as("HrlyRate", "Hourly Rate");
		$crud->display_as("SecHrlyRate", "Second Hourly Rate");
		$crud->display_as("HrsPerFrtnt", "Hours Per Fortnight");
		$crud->display_as("DaysWork", "Days Available");
		$crud->display_as("NHACEDate", "Date Due for NHACE year Progression");
		$crud->display_as("AnnualLeave", "Annual Leave Type");
		$crud->display_as("PersonalLeave", "Personal Leave Type");
		$crud->display_as("FundUSI", "Fund USI #");
		$crud->display_as("MmbershpNo", "Membership No. #");
		$crud->display_as("TerminationDate", "Termination Date");
		$crud->display_as("NHACEClass", "NHACE Classification");
		$crud->display_as("BGCSDepartment", "BGCS Department");
        $crud->display_as("SuperFund", "Superannuation Fund");

		$crud->display_as("Pos2", "Secondary Position");
		$crud->display_as("WEmail", "Work Email");

		$crud->add_fields('PerID', 'EmpPosition', 'EmpSecPosition', 'BGCSDepartment', 'Supervisor', 'WorkMob', 'WorkEmail', 'EmpDate', 'Contract', 'ContStatus', 'ContStartDate', 'ContEndDate', 'HrlyRate', 'SecHrlyRate', 'HrsPerFrtnt', 'DaysWork','NHACEClass', 'NHACEDate', 'AnnualLeave', 'PersonalLeave', 'FundUSI', 'MmbershpNo', 'SuperFund' , 'TerminationDate');
		$crud->edit_fields('PerID', 'EmpPosition', 'EmpSecPosition', 'BGCSDepartment', 'Supervisor', 'WorkMob', 'WorkEmail', 'EmpDate', 'Contract', 'ContStatus', 'ContStartDate', 'ContEndDate', 'HrlyRate', 'SecHrlyRate', 'HrsPerFrtnt', 'DaysWork','NHACEClass', 'NHACEDate', 'AnnualLeave', 'PersonalLeave', 'FundUSI', 'MmbershpNo', 'SuperFund', 'TerminationDate');
		$crud->set_read_fields('PerID', 'EmpPosition', 'EmpSecPosition', 'BGCSDepartment', 'Supervisor', 'WorkMob', 'WorkEmail', 'EmpDate', 'Contract', 'ContStatus', 'ContStartDate', 'ContEndDate', 'HrlyRate', 'SecHrlyRate', 'HrsPerFrtnt', 'DaysWork','NHACEClass', 'NHACEDate', 'AnnualLeave', 'PersonalLeave', 'FundUSI', 'MmbershpNo',  'SuperFund', 'TerminationDate');
		

		//Enumerated Values for Contract
		$contract = $crud->basic_model->return_query("SHOW COLUMNS FROM Employee WHERE Field='Contract'");

		preg_match("/^enum\(\'(.*)\'\)$/", $contract[0]->Type, $matches);
		$contractArr = explode("','", $matches[1]);
		$newContArr = array();
		foreach($contractArr as $cont) {
			if($cont==="FULL_TIME") {
				$newContArr += [$cont => "Full Time"];
			} else if($cont==="PART_TIME") {
				$newContArr += [$cont => "Part Time"];
			} else if($cont==="CASUAL") {
				$newContArr += [$cont => "Casual"];
			} else if($cont==="INDE_CONT") {
				$newContArr += [$cont => "Independant Contractor"];
			}
		}

		$crud->field_type('Contract', 'dropdown', $newContArr);

		//Enumerated Values for Contract Status
		$contractStat = $crud->basic_model->return_query("SHOW COLUMNS FROM Employee WHERE Field='ContStatus'");

		preg_match("/^enum\(\'(.*)\'\)$/", $contractStat[0]->Type, $matches);
		$contractStatArr = explode("','", $matches[1]);
		$newContStatArr = array();
		foreach($contractStatArr as $contStat) {
			if($contStat==="PERMANENT") {
				$newContStatArr += [$contStat => "Permanent"];
			} else if($contStat==="FIXED_TERM") {
				$newContStatArr += [$contStat => "Fixed Term"];
			}
		}

		$crud->field_type('ContStatus', 'dropdown', $newContStatArr);

		//Enumerated Values for Annual Leave
		$annualLeave = $crud->basic_model->return_query("SHOW COLUMNS FROM Employee WHERE Field='AnnualLeave'");

		preg_match("/^enum\(\'(.*)\'\)$/", $annualLeave[0]->Type, $matches);
		$annualLeaveArr = explode("','", $matches[1]);
		$newAnnualLeaveArr = array();
		foreach($annualLeaveArr as $ALStat) {
			if($ALStat==="AL_4") {
				$newAnnualLeaveArr += [$ALStat => "Annual Leave - 4 Weeks"];
			} else if($ALStat==="AL_5") {
				$newAnnualLeaveArr += [$ALStat => "Annual Leave - 5 Weeks"];
			} else if($ALStat==="AL_6") {
				$newAnnualLeaveArr += [$ALStat => "Annual Leave - 6 Weeks"];
			}
		}

		$crud->field_type('AnnualLeave', 'dropdown', $newAnnualLeaveArr);

		//Enumerated Values for Personal Leave
		$personalLeave = $crud->basic_model->return_query("SHOW COLUMNS FROM Employee WHERE Field='PersonalLeave'");

		preg_match("/^enum\(\'(.*)\'\)$/", $personalLeave[0]->Type, $matches);
		$personalLeaveArr = explode("','", $matches[1]);
		$newPersonalLeaveArr = array();
		foreach($personalLeaveArr as $PLStat) {
			if($PLStat==="PL_1") {
				$newPersonalLeaveArr += [$PLStat => "Personal Leave - Stage 1"];
			} else if($PLStat==="PL_2") {
				$newPersonalLeaveArr += [$PLStat => "Personal Leave - Stage 2"];
			} else if($PLStat==="PL_3") {
				$newPersonalLeaveArr += [$PLStat => "Personal Leave - Stage 3"];
			}
		}

		$crud->field_type('PersonalLeave', 'dropdown', $newPersonalLeaveArr);

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

		//Available Days
		$availability = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='Availability'");
		
		$daysArr = array();
		foreach($availability as $av) {
            $daysArr += [$av->OptID => $av->data];
        }
		$crud->field_type("DaysWork", "multiselect", $daysArr);

        //Supervisors
        $supervisors = $crud->basic_model->return_query("SELECT PerID, CONCAT(FirstName, ' ', MiddleName, ' ', LastName) as FullName FROM Person");
        $visorArr = array();
        foreach($supervisors as $spv){
            $visorArr += [$spv->PerID => $spv->FullName];
        }
        $crud->field_type("Supervisor", "dropdown", $visorArr);

        //Superannuation Funds
        $funds = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='Fund'");
        $fundArr = array();
        foreach($funds as $fnd) {
            $fundArr += [$fnd->OptID => $fnd->data];
        }
        $crud->field_type("SuperFund", "dropdown", $fundArr);

		//NHACE Classifications
		$nhace = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='NHACE_CLASS'");
		$nhaceArr = array();
		foreach($nhace as $nh) {
			$nhaceArr += [$nh->OptID => $nh->data];
		}
		$crud->field_type("NHACEClass", "dropdown", $nhaceArr);

		//BCGS Departments
		$bcgs = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='BGCS_DEP'");
		$bcgsArr = array();
		foreach($bcgs as $bc) {
			$bcgsArr += [$bc->OptID => $bc->data];
		}
		$crud->field_type("BGCSDepartment", "dropdown", $bcgsArr);


		//Position Names
		$positions = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType
		where type = 'Position'");
		$posArr = array();
		foreach($positions as $pos) {
			$posArr += [$pos->OptID => $pos->data];
		}

		$crud->field_type("Pos1", "dropdown", $posArr);
		$crud->field_type("Pos2", "dropdown", $posArr);
		$crud->field_type("EmpPosition", "dropdown", $posArr);
		$crud->field_type("EmpSecPosition", "dropdown", $posArr);
		
		
		
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
		$crud->display_as("PerID", "Name");
		
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