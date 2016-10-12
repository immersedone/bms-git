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
		$users = $crud->basic_model->return_query("SELECT P.PerID, CONCAT(FirstName, ' ', MiddleName, ' ', LastName) as FullName FROM Person P
		LEFT OUTER JOIN Employee E on E.PerID = P.PerID
		WHERE E.PerID IS NULL");

		//Convert Return Object into Associative Array
		$usrArr = array();
		$usrArrIDOnly = "";

		for($i = 0; $i < count($users); $i++) {
			if($i == count($users) - 1) {
				$usrArrIDOnly .= $users[$i]->PerID;
			} else {
				$usrArrIDOnly .= $users[$i]->PerID.',';
			}
		}


		foreach($users as $usr) {
			$usrArr += [$usr->PerID => $usr->FullName];
			
		}
		
		//Change the field type to a dropdown with values
		//to add to the relational table
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
        $funds = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='SPR_FND'");
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
		
		$state = $crud->getState();
		$stateInfo = $crud->getStateInfo();

        //Validation for Employee
        $crud->set_rules('HrlyRate', 'Hourly Rate', 'numeric');
        $crud->set_rules('SecHrlyRate', 'Second Hourly Rate', 'numeric');
        $crud->set_rules('HrsPerFrtnt', 'Hours Per Fortnight', 'numeric');
        //$crud->set_rules("DaysWork", "Days Available", "required");
        


		
		if ($state === "edit" || $state === "update" || $state === "update_validation") {
			$crud->field_type("PerID", "readonly");
			$crud->required_fields(
            'EmpPosition',
            'WorkMob',
            'WorkEmail',
            'EmpDate',
            'HrlyRate',
            'HrsPerFrtnt',
            'AnnualLeave',
            'PersonalLeave',
            'NHACEClass',
            'BGCSDepartment');
            //$crud->set_rules("PerID", "Employee Name", "required");
		} else if ($state === "insert" || $state == "insert_validation" || $state === "add") {
			//$crud->required_fields( 'PerID');
			//$crud->set_rules("PerID", "Employee Name", "in_list[" . $usrArrIDOnly . "]|required");
            //echo $usrArrIDOnly;
		}

		$employeeOP = $crud->render();


		if($state === "read") {
			$pkID = $stateInfo->primary_key;
			//Instantiate Second CRUD for Full Details
			$crudTwo = new grocery_CRUD();
			$crudTwo->set_model('Extended_generic_model');
			$crudTwo->set_table('Person');
			$crudTwo->set_subject('Person Details');

			//Get the Person ID
			$perID = $crudTwo->basic_model->return_query("SELECT PerID FROM Employee WHERE EmpID='$pkID'");
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
			//$output["perID"] = $perID;
			$crudTwo->set_read_fields("Address", "SuburbID", "PersonalEmail", "Mobile", "HomePhone", "Status", "DateStarted", "DateFinished", "ContractSigned", "PaperworkCompleted", "WWC", "WWCFiled", "PoliceCheck", "TeacherRegCheck", "FAQual", "DateofBirth", "LanguagesSpoken", "EmergContName", "EmergContMob", "EmergContHPhone", "EmergContWPhone", "EmergContRelToPer");
			$crudTwo->setNewState($perID);
            $fullDetailsOP = $crudTwo->render();
			
			$crudThree = new grocery_CRUD();
			$crudThree->set_model('Extended_generic_model');
			$crudThree->set_table('PersonProject');
			$crudThree->set_subject('Employee History');
			$crudThree->basic_model->set_query_str("SELECT Proj.Name as ProjName, O1.Data as Role, O2.Data as Dept,  CONCAT(Per.FirstName, ' ', Per.MiddleName, ' ', Per.LastName) as SupName, PP.StartDate, PP.FinishDate FROM PersonProject PP
			LEFT OUTER JOIN Person Per ON Per.PerID = PP.Supervisor
			LEFT OUTER JOIN Project Proj ON Proj.ProjID = PP.ProjID
			LEFT OUTER JOIN OptionType O1 on O1.OptID = PP.Role
			LEFT OUTER JOIN OptionType O2 on O2.OptID = PP.BGCSDepartment
			WHERE PP.PerID = '$perID'");
			$crudThree->columns("ProjName", "Role", "Dept", "SupName", "StartDate", "FinishDate");
			$crudThree->display_as('ProjName', 'Project Name');
			$crudThree->display_as('Dept', 'Department');
			$crudThree->display_as('SupName', "Supervisor's Name");
			$crudThree->display_as('StartDate', 'Date Started');
			$crudThree->display_as('FinishDate', 'Date Finished');
			
			$crudThree->setStateCode(1);
			$crudThree->unset_add();
			$crudThree->unset_edit();
			$crudThree->unset_delete();
			$empHistoryOP = $crudThree->render();

            $crudFour = new grocery_CRUD();
            $crudFour->set_model('Extended_generic_model');
            $crudFour->set_table('PersonProject');
            $crudFour->set_subject('Employee History');
            $crudFour->basic_model->set_query_str("SELECT Proj.Name as ProjName, O1.Data as Role, O2.Data as Dept,  CONCAT(Per.FirstName, ' ', Per.MiddleName, ' ', Per.LastName) as SupName, PP.StartDate FROM PersonProject PP
			LEFT OUTER JOIN Person Per ON Per.PerID = PP.Supervisor
			LEFT OUTER JOIN Project Proj ON Proj.ProjID = PP.ProjID
			LEFT OUTER JOIN OptionType O1 on O1.OptID = PP.Role
			LEFT OUTER JOIN OptionType O2 on O2.OptID = PP.BGCSDepartment
			WHERE PP.PerID = '$perID'");
            $crudFour->columns("ProjName", "Role", "Dept", "SupName", "StartDate");
			$crudFour->display_as('ProjName', 'Project Name');
			$crudFour->display_as('Dept', 'Department');
			$crudFour->display_as('SupName', "Supervisor's Name");
			$crudFour->display_as('StartDate', 'Date Started');
			$crudFour->display_as('FinishDate', 'Date Finished');

            $crudFour->setStateCode(1);
            $crudFour->unset_add();
            $crudFour->unset_edit();
            $crudFour->unset_delete();
            $empCurrentOP = $crudFour->render();
			
			$output["empHistory"] = $empHistoryOP;
			$output["fullDetails"] = $fullDetailsOP;
            $output["empCurrent"] = $empCurrentOP;
			$output["multiView"] = "YES";
			
		} else {
            $output["empCurrent"] = "";
			$output["fullDetails"] = "";
			$output["multiView"] = "NO";

		}
		$output["employee"] = $employeeOP;

		$this->render($output);
	}

	public function empproj($id) {

		$crud = new grocery_CRUD();
		$crud->set_model('Employee_GC');
		$crud->set_table('PersonProject');
		$crud->set_subject('Employee');
		$crud->basic_model->set_query_str("SELECT CONCAT(Emp.FirstName, ' ', Emp.MiddleName, ' ', Emp.LastName) as EmpName, O1.Data as Role, O2.Data as Dept, O3.Data as Position,  CONCAT(Sup.FirstName, ' ', Sup.MiddleName, ' ', Sup.LastName) as SupName, PP.StartDate, PP.FinishDate FROM PersonProject PP
			LEFT OUTER JOIN Person Emp ON Emp.PerID = PP.Supervisor
			LEFT OUTER JOIN Person Sup ON Sup.PerID = PP.Supervisor
			LEFT OUTER JOIN Project Proj ON Proj.ProjID = PP.ProjID
			LEFT OUTER JOIN OptionType O1 on O1.OptID = PP.Role
			LEFT OUTER JOIN OptionType O2 on O2.OptID = PP.BGCSDepartment
			LEFT OUTER JOIN OptionType O3 on O3.OptID = PP.Position
			WHERE O3.Data != 'Volunteer' 
			AND PP.ProjID=".$id);
		$crud->columns("EmpName", "Role", "Position", "Dept", "SupName", "StartDate", "FinishDate");
		$crud->display_as("Role", "Project Role");
		$crud->display_as("EmpName", "Full Name");
		$crud->display_as("SupName", "Supervisor Name");

		//Change the Add Volunteer Fields
		$crud->add_fields("EmpName", "Role", "Position", "Dept", "SupName", "IsActive", "StartDate", "FinishDate", "projectID");
		$crud->field_type("projectID", 'hidden', $id);
		
		//Call Model to get the User's Full Names
		$users = $crud->basic_model->return_query("SELECT PerID, CONCAT(FirstName, ' ', MiddleName, ' ', LastName) as FullName FROM Person");

		//Convert Return Object into Associative Array
		$usrArr = array();
		foreach($users as $usr) {
			$usrArr += [$usr->PerID => $usr->FullName];
		}
		//BCGS Departments
		$bcgs = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='BGCS_DEP'");
		$bcgsArr = array();
		foreach($bcgs as $bc) {
			$bcgsArr += [$bc->OptID => $bc->data];
		}
		//Position
		$pos = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='Position'");
		$posArr = array();
		foreach($pos as $p) {
			$posArr += [$p->OptID => $p->data];
		}
		//Roles in a Project
		$roles = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='Role'");
		$roleArr = array();
		foreach($roles as $role) {
			$roleArr += [$role->OptID => $role->data];
		}
		
		//Change the field type to a dropdown with values
		//to add to the relational table
		$crud->field_type("Position", "dropdown", $posArr);
		$crud->field_type("Dept", "dropdown", $bcgsArr);
		$crud->field_type("Role", "dropdown", $roleArr);
		$crud->field_type("EmpName", "dropdown", $usrArr);
		$crud->field_type("SupName", "dropdown", $usrArr);

		$output["multiView"] = "NO";	
		
		$output["employee"] = $crud->render();

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
	
		$personID = $_POST['EmpName'];
		$projectID = $_POST['projectID'];
		$role = $_POST['Role'];
		$position = $_POST['Position'];
		$BGCSDept = $_POST['Dept'];
		$isActive = $_POST['IsActive'];
		$supervisor = $_POST['SupName'];
		$startdate = $_POST['StartDate'];
		$finishdate = $_POST['FinishDate'];

		$crud = new grocery_CRUD();
		$crud->set_model('Employee_GC');
		$resp = $crud->basic_model->insert_pp($personID, $projectID, $role, $position, $BGCSDept, $isActive, $supervisor, $startdate, $finishdate);
		echo $resp;
	}
}