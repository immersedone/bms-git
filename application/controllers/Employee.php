<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends MY_Controller {

	public function __construct()
	{
		parent::__construct();


		$this->load->library('grocery_CRUD');
		$this->load->library('Gmulti');
	}

	public function index()
	{
		//$this->render((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
		$this->employee();
	}

	public function render($output = null) {
		$this->load->view('employee', $output);
	}

	public function renderBaseTemplate($output = null) {
		$this->load->view('basetemplate', $output);
	}


	public function employee() {

		$crud = new grocery_CRUD();
		$crud->set_model('Employee_GC');
		$crud->set_table('Employee');
		$crud->set_subject('Employee');
		$crud->basic_model->set_query_str('SELECT * FROM (SELECT CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as FullName, Emp.WorkEmail as WEmail, Per.Mobile, Opt1.data as Pos1, Opt2.data as Pos2, Emp.* from Employee Emp
		LEFT OUTER JOIN Person Per ON Per.PerID = Emp.PerID
		LEFT OUTER JOIN OptionType Opt1 ON Opt1.OptID = Emp.EmpPosition
		LEFT OUTER JOIN OptionType Opt2 ON Opt2.OptID = Emp.EmpSecPosition) x');
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
        $supervisors = $crud->basic_model->return_query("SELECT PerID, CONCAT(FirstName, ' ', MiddleName, ' ', LastName) as FullName FROM Person WHERE Is_Sup = 1");
        $visorArr = array();
        if (empty($supervisors) == false){
			foreach($supervisors as $spv){
				$visorArr += [$spv->PerID => $spv->FullName];
			}
			$crud->field_type("Supervisor", "dropdown", $visorArr);
		}
		else{
			$crud->field_type("Supervisor", 'hidden');
		}

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
		$crud->set_rules("DaysWork", "Days Available", "trim|numeric|callback_multi_LS");
        //$crud->set_rules("DaysWork", "Days Available", "required");
        
		$crud->callback_before_insert(array($this,'work_details_check'));


		
		if ($state === "edit" || $state === "update" || $state === "update_validation") {
			$crud->field_type("PerID", "readonly");
			$crud->required_fields(
            'EmpPosition',
            'EmpDate',
            'HrlyRate',
            'HrsPerFrtnt',
            'AnnualLeave',
            'PersonalLeave',
            'NHACEClass',
            'BGCSDepartment',
			'DaysWork');
            //$crud->set_rules("PerID", "Employee Name", "required");
		} else if ($state === "insert" || $state == "insert_validation" || $state === "add") {
			$crud->required_fields(
            'PerID',
            'EmpPosition',
            'EmpDate',
            'HrlyRate',
            'HrsPerFrtnt',
            'AnnualLeave',
            'PersonalLeave',
            'NHACEClass',
            'BGCSDepartment',
			'DaysWork');
			//$crud->set_rules("PerID", "Employee Name", "in_list[" . $usrArrIDOnly . "]|required");
            //echo $usrArrIDOnly;
		} else if ($state === "read") {
			//$crud->set_theme("fleximulti");
		}

		$employeeOP = $crud->render();


		if($state === "read") {
			$pkID = $stateInfo->primary_key;
			//Instantiate Second CRUD for Full Details
			$crudTwo = new grocery_CRUD();
			$crudTwo->set_model('Extended_generic_model');
			$crudTwo->set_table('Person');
			//$crudTwo->set_theme('fleximulti');
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
			$crudTwo->display_as('WWCExpiry', 'Working With Children Check (WWC) Expiry Date');
			$crudTwo->display_as('TeacherExipry', 'Valid Teacher Registration Expiry Date');
			$crudTwo->display_as('FAQaulExpiry', 'First Aid Qualification Expiry');
			$crudTwo->display_as('PoliceCheckDate', 'Date of Police Check');
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
			$crudTwo->set_read_fields("Address", "SuburbID", "PersonalEmail", "Mobile", "HomePhone", 'Status', 'DateStarted', 'DateFinished', 'ContractSigned', 'PaperworkCompleted', 'WWC', 'WWCFiled', 'WWCExpiry', 'PoliceCheck', 'PoliceCheckDate', 'TeacherRegCheck', 'TeacherExipry', 'FAQual', 'FAQaulExpiry', 'LanguagesSpoken', 'EmergContName', 'EmergContMob', 'EmergContHPhone', 'EmergContWPhone', 'EmergContRelToPer');
			$crudTwo->setNewState($perID);
            $fullDetailsOP = $crudTwo->render();
			
			$crudThree = new grocery_CRUD();
			$crudThree->set_model('Extended_generic_model');
			$crudThree->set_table('PersonProject');
			//$crudThree->set_theme('fleximulti');
			$crudThree->set_subject('Employee History');
			$crudThree->basic_model->set_query_str("SELECT * FROM (SELECT Proj.Name as ProjName, O1.Data as Role, PP.StartDate, PP.FinishDate, PP.PersonProjectID as PersonProjectID, PP.PerID FROM PersonProject PP
			LEFT OUTER JOIN Project Proj ON Proj.ProjID = PP.ProjID
			LEFT OUTER JOIN OptionType O1 on O1.OptID = PP.Role
			WHERE PP.PerID = '$perID' AND PP.IsActive='0' ) x");
			$crudThree->columns("ProjName", "Role", "StartDate", "FinishDate");
			$crudThree->display_as('ProjName', 'Project Name');
			$crudThree->display_as('StartDate', 'Date Started');
			$crudThree->display_as('FinishDate', 'Date Finished');
			
			$crudThree->setStateCode(1);
			$crudThree->unset_operations();
			$crudThree->add_action('View', '', '', 'read-icon', array($this, 'link_prjvw_eh'));

			$empHistoryOP = $crudThree->render();

            $crudFour = new grocery_CRUD();
            $crudFour->set_model('Extended_generic_model');
            $crudFour->set_table('PersonProject');
            //$crudFour->set_theme('fleximulti');
            $crudFour->set_subject('Current Projects');
            $crudFour->basic_model->set_query_str("SELECT * FROM (SELECT Proj.Name as ProjName, O1.Data as Role, PP.StartDate, PP.FinishDate, PP.PersonProjectID as PersonProjectID, PP.PerID FROM PersonProject PP
			LEFT OUTER JOIN Project Proj ON Proj.ProjID = PP.ProjID
			LEFT OUTER JOIN OptionType O1 on O1.OptID = PP.Role
			WHERE PP.PerID = '$perID' AND PP.IsActive='1') x");
            $crudFour->columns("ProjName", "Role", "StartDate");
			$crudFour->display_as('ProjName', 'Project Name');
			$crudFour->display_as('StartDate', 'Date Started');
			$crudFour->display_as('FinishDate', 'Date Finished');

            $crudFour->setStateCode(1);
            $crudFour->unset_operations();
            $crudFour->add_action('View', '', '', 'read-icon', array($this, 'link_prjvw_cp'));
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

		if($_SESSION["session_user"]["bms_sUsrPriv"] !== "FullAdmin") {

			$htmlOutput = '<div class="col-md-12 col-sm-12 col-xs-12 bg_norm">  
                <div class="dashboard_generate"> 
                    <div class="row x_custom x_title">
                        <div class="col-md-12">
                            <h3>Error - Access Is Denied</h3>
                        </div>
                    </div>
                    <div class="row x_custom">
                        <div class="col-md-12">
                            <p style="margin:5px; font-size: 14px;">You do not have the permissions to view this page. Please contact your Manager/Supervisor.</p>
                        </div>
                    </div>

                </div>
            </div>';
			$htmlOutput .= "<script> var dialog_forms='". $_SESSION["session_user"]["bms_dialog"] . "';
			var js_date_format='dd/mm/yyyy';</script>";
			$output["employee"]->output = $htmlOutput;
			
		}

		$extraScript = '<script type="text/javascript">
		$(document).ready(function(){
		  $(".tDiv2").append("<a href=\'' . base_url('/user/') . 'peremp/index/add\' title=\'Add Person (Employee)\' class=\'add-anchor add_button\'><div class=\'fbutton\'><div><span class=\'add\'>Add Person (Employee)</span></div></div></a>");
		});
		</script>';

		$output["employee"]->output .= $extraScript;
		
		//print_r($output);
		$this->render($output);
	}

	public function empproj($id) {

		$crud = new grocery_CRUD();
		$crud->set_model('Employee_GC');

		$state = $crud->getState();

		if($state === "ajax_list") {
			$crud->set_table("PersonProject");
		} else {
			$crud->set_table("PersonProject");
		}


		$crud->set_subject('Employee');
		$crud->basic_model->set_query_str('SELECT * FROM (SELECT CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as EmpName, PP.ProjID, PP.StartDate as StartDate, PP.FinishDate as FinishDate, Opt.Data as EmpRole, Per.PerID, PP.PersonProjectID, PP.Role FROM PersonProject PP 
		LEFT OUTER JOIN Person Per ON PP.PerID = Per.PerID
		LEFT OUTER JOIN Employee Emp ON Emp.PerID = Per.PerID
		LEFT OUTER JOIN OptionType Opt ON Opt.OptID = PP.Role
		WHERE PP.EmpVol="Emp" AND PP.ProjID="'. $id . '") x');

		$crud->columns("EmpName", "EmpRole", "StartDate", "FinishDate");
		$crud->display_as("EmpName", "Employee Name");
		$crud->display_as("EmpRole", "Project Role");
		$crud->display_as("StartDate", "Start Date");
		$crud->display_as("FinishDate", "Finish Date");	
		$crud->display_as("IsActive", "Is Active");
		$crud->display_as("PerID", "Employee Name");
		$crud->display_as("ProjID", "Project ID");

		$crud->required_fields(
		'EmpName',
		'EmpRole',
		'IsActive',
		'StartDate');


		if ($state === "ajax_list") {
			$crud->setStateCode(7);
			
		} else if ($state === "ajax_list_info") { 
			$crud->setStateCode(8);
		} else if ($state === "edit" || $state === "update") {
			$crud->callback_edit_field("ProjID", array($this, 'callback_projID_edit'));
			$crud->callback_edit_field("PerID", array($this, 'callback_PerID_edit'));
		}
		

		$crud->add_fields("EmpName", "EmpRole", "IsActive", "StartDate", "FinishDate", "projectID", "EmpVol");
		$crud->edit_fields("ProjID", "PerID", "Role", "IsActive", "StartDate", "FinishDate", "EmpVol");
		$crud->field_type("EmpVol", 'hidden', 'Emp');
		$crud->field_type("projectID", 'hidden', $id);
		$crud->field_type("IsActive", "true_false");
		/*$crud->callback_add_field('IsActive', function() {

		});*/
		
		//Roles in a Project
		$roles = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='Role'");
		$roleArr = array();
		foreach($roles as $role) {
			$roleArr += [$role->OptID => $role->data];
		}
		
		//Full Names
		$users = $crud->basic_model->return_query("SELECT PerID, CONCAT(FirstName, ' ', MiddleName, ' ', LastName) as FullName FROM Person
		WHERE PerID in (SELECT PerID FROM Employee)");
		$usrArr = array();
		foreach($users as $usr) {
			$usrArr += [$usr->PerID => $usr->FullName];
		}		
		
		//Change the field type to a dropdown with values
		//to add to the relational table
		$crud->field_type("EmpRole", "dropdown", $roleArr);
		$crud->field_type("Role", "dropdown", $roleArr);
		$crud->field_type("IsActive", "true_false");
		$crud->field_type("EmpName", "dropdown", $usrArr);
		$crud->field_type("StartDate", "date");
		$crud->field_type("FinishDate", "date");

		//$crud->unset_delete();
		//$crud->add_action('Delete', '', '', 'delete-icon delete-row', array($this, 'employee_delete'));

		//$output["multiView"] = "NO";	
		
		//$output["employee"] = $crud->render();*/

		$output = $crud->render();


		$this->renderBaseTemplate($output);
	}


	public function callback_projID_edit($value, $primary_key) {
		$q = $this->db->query('SELECT Name FROM Project WHERE ProjID="' . $value .'" LIMIT 1')->row();
		//$projName = array_shift($q->result_array());
		$readOnly = '<div id="field-ProjID" class="readonly_label">' . $q->Name .'</div>';
		return $readOnly . '<input id="field-ProjID" name="ProjID" type="text" value="' . $value . '" class="numeric form-control" maxlength="255" style="display:none;">';
	}

	public function callback_PerID_edit($value, $primary_key) {
		$q = $this->db->query('SELECT CONCAT(FirstName, " ", MiddleName, " ", LastName) as FullName FROM Person WHERE PerID="'.$value.'" LIMIT 1')->row();
		$readOnly = '<div id="field-PerID" class="readonly_label">' . $q->FullName .'</div>';
		return $readOnly . '<input id="field-PerID" name="PerID" type="text" value="' . $value . '" class="numeric form-control" maxlength="255" style="display:none;">';
	}	
	
	public function multi_LS() {
		
		$dayArr = $this->input->post('DaysWork[]');
		if(empty($dayArr)) {
			$this->form_validation->set_message('multi_LS', 'Days Available is required and must not be empty.');

			return false;
		} else {
			return true;
		}

	}

	function employee_delete($primarykey, $row) {
		return base_url().'user/employee/index/pp_delete/'.$primarykey.'/'.$row->ProjID;
	}

	function link_prjvw_cp($primarykey, $row) {
		return base_url().'user/employee/index/prjvw/'.$primarykey.'/'.$row->PerID.'/Current%20Projects';
	}

	function link_prjvw_eh($primarykey, $row) {
		return base_url().'user/employee/index/prjvw/'.$primarykey.'/'.$row->PerID.'/Employee%20History';
	}

	public function prjvw($row, $uid, $subject) {
		
		//Start New Crud
		$crud = new grocery_CRUD();
		$crud->set_model('Employee_GC');
		$crud->set_subject(urldecode($subject));
		$crud->set_table('PersonProject');

		//Get Project ID
		$prID = $this->db->query("SELECT ProjID FROM PersonProject WHERE PersonProjectID='".$row."' LIMIT 1")->row();
		$pID = $prID->ProjID;

		$crud->basic_model->set_query_str('SELECT * FROM (SELECT  CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as VolName, PP.ProjID, PP.StartDate as StartDate, PP.FinishDate as FinishDate, Opt.Data as EmpRole, Per.PerID, PP.IsActive as IsActive, PP.PersonProjectID FROM PersonProject PP 
		LEFT OUTER JOIN Person Per ON PP.PerID = Per.PerID
		LEFT OUTER JOIN Employee Emp ON Emp.PerID = Per.PerID
		LEFT OUTER JOIN OptionType Opt ON Opt.OptID = PP.Role
		WHERE PP.EmpVol="Emp" AND PP.ProjID="'. $pID . '" AND PP.PerID="' . $uid . '") x');
		$crud->display_as("PerID", "Employee Name");
		$crud->display_as("ProjID", "Project Name");
		$crud->display_as("Role", "Project Role");
		$crud->display_as("IsActive", "Is Active");
		$crud->display_as("StartDate", "Start Date");
		$crud->display_as("FinishDate", "Finish Date");
		$crud->set_read_fields("PerID", "ProjID", "Role", "IsActive", "StartDate", "FinishDate");

		//$crud->field_type("EmpVol", "dropdown", array("Emp" => "Employee", "Vol" => "Volunteer"));

		$crud->setStateCode(18);
		$crud->setNewState($row);

		$output = $crud->render();
		$this->renderBaseTemplate($output);

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
		if(isset($_POST['Role'])) {
			$role = $_POST['Role'];
		} else if (isset($_POST['EmpRole'])) {
			$role = $_POST['EmpRole'];
		}
		$isActive = $_POST['IsActive'];
		$EmpVol = $_POST['EmpVol'];
		$startdate = $_POST['StartDate'];
		$finishdate = $_POST['FinishDate'];
		

		$newDateRep = preg_replace('/\//', '-',$startdate);
		$newStart = date("Y-m-d H:i:s", strtotime($newDateRep));

		$newDateRep = preg_replace('/\//', '-',$finishdate);
		$newFinish = date("Y-m-d H:i:s", strtotime($newDateRep));

		$crud = new grocery_CRUD();
		$crud->set_model('Employee_GC');
		$resp = $crud->basic_model->insert_pp($personID, $projectID, $role, $EmpVol, $isActive, $newStart, $newFinish);
		echo $resp;
	}
	
	public function work_details_check($post_array){
	
	if ($post_array['WorkMob'] == ''){
		$workMob = $this->db->query("SELECT Mobile FROM Person WHERE PerID='".$post_array['PerID']."' LIMIT 1")->row();
		$post_array['WorkMob'] = $workMob->Mobile;
	}	
	if ($post_array['WorkEmail'] == ''){
		$workEmail = $this->db->query("SELECT Email FROM Person WHERE PerID='".$post_array['PerID']."' LIMIT 1")->row();
		$post_array['WorkEmail'] = $workEmail->Mobile;
	}
	
	return $post_array;
	}
		
}