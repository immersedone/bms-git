<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peremp extends MY_Controller {

	public function __construct()
	{
		parent::__construct();


		$this->load->library('grocery_CRUD');
		$this->load->library('bcrypt');
	}

	public function index()
	{
		//$this->render((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
		$this->person_employee();
	}

	public function render($output = null) {
		$this->load->view('peremp', $output);
	}

	public function person_employee() {

		$tempPass = "d3F!_P4s$)";
		$tempHash = $this->bcrypt->hash_password($tempPass);

		$crud = new grocery_CRUD();
		$crud->set_model('Extended_generic_model');
		$crud->set_table('Person');
		$crud->set_subject('Person');
		$crud->basic_model->set_query_str('SELECT Emp.EmpPosition, Emp.EmpSecPosition, Emp.BGCSDepartment, Emp.Supervisor, Emp.WorkMob, Emp.WorkEmail, Emp.EmpDate, Emp.Contract, Emp.ContStatus, Emp.ContStartDate, Emp.ContEndDate, Emp.HrlyRate, Emp.SecHrlyRate, Emp.HrsPerFrtnt, Emp.DaysWork,NHACEClass, Emp.NHACEDate, Emp.AnnualLeave, Emp.PersonalLeave, Emp.FundUSI, Emp.MmbershpNo, Emp.SuperFund, Emp.TerminationDate, Per.* FROM Person Per LEFT OUTER JOIN Employee Emp on Emp.PerID = Per.PerID');

		$crud->add_fields('FirstName', 'MiddleName', 'LastName', 'Address', 'DateofBirth', 'SuburbID', 'PersonalEmail', 'Mobile', 'HomePhone', 'Status', 'DateStarted', 'ContractSigned', 'PaperworkCompleted', 'WWC', 'WWCFiled', 'WWCExpiry', 'PoliceCheck', 'PoliceCheckDate', 'TeacherRegCheck', 'TeacherExipry', 'FAQual', 'FAQLev', 'FAQaulExpiry', 'LanguagesSpoken', 'EmergContName', 'EmergContMob', 'EmergContHPhone', 'EmergContWPhone', 'EmergContRelToPer', 'Username', 'Password', 'Is_Sup', 'EmpPosition', 'EmpSecPosition', 'BGCSDepartment', 'Supervisor', 'WorkMob', 'WorkEmail', 'EmpDate', 'Contract', 'ContStatus', 'ContStartDate', 'ContEndDate', 'HrlyRate', 'SecHrlyRate', 'HrsPerFrtnt', 'DaysWork','NHACEClass', 'NHACEDate', 'AnnualLeave', 'PersonalLeave', 'FundUSI', 'MmbershpNo', 'SuperFund' , 'TerminationDate');

		$crud->display_as('FirstName', 'First Name');
		$crud->display_as('MiddleName', 'Middle Name');
		$crud->display_as('LastName', 'Last Name');
		$crud->display_as('SuburbID', 'Suburb');
		$crud->display_as('PersonalEmail', 'Personal Email');
		$crud->display_as('HomePhone', 'Home Phone');
		$crud->display_as('DateStarted', 'Date Started');
		$crud->display_as('DateFinished', 'Date Finished');
		$crud->display_as('ContractSigned', 'Contract Signed');
		$crud->display_as('PaperworkCompleted', 'Paperwork is Completed');
		$crud->display_as('PoliceCheck', 'Valid Police Check');
		$crud->display_as('PoliceCheckDate', 'Date of Police Check');
		$crud->display_as('TeacherRegCheck', 'Valid Teacher Registration');
		$crud->display_as('TeacherExipry', 'Valid Teacher Registration Expiry Date');
		$crud->display_as('WWC', 'Working With Children Check (WWC)');
		$crud->display_as('WWCFiled', 'Working With Children Check (WWC) is Filed');
		$crud->display_as('WWCExpiry', 'Working With Children Check (WWC) Expiry Date');
		$crud->display_as('DateofBirth', 'Date of Birth');
		$crud->display_as('FAQual', 'First Aid Qualification');
		$crud->display_as('FAQLev', 'First Aid Qualification Level');
		$crud->display_as('FAQaulExpiry', 'First Aid Qualification Expiry');
		$crud->display_as('LanguagesSpoken', 'Languages Spoken');
		$crud->display_as('EmergContName', 'Emergency Contact Name');
		$crud->display_as('EmergContMob', 'Emergency Contact Mobile');
		$crud->display_as('EmergContHPhone', 'Emergency Contact Home Phone');
		$crud->display_as('EmergContWPhone', 'Emergency Contact Work Phone');
		$crud->display_as('EmergContRelToPer', 'Emergency Contact Relation');
		$crud->field_type('Hash', 'hidden');
		$crud->field_type('Timeout', 'hidden');
		$crud->display_as('Is_Sup', 'Is Supervisor');
		$crud->display_as("EmpPosition", "Position");
		$crud->display_as("EmpSecPosition", "Secondary Position");
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
		$crud->field_type('Username', 'hidden');
		$crud->field_type('Password', 'hidden', $tempHash);
		
		$crud->required_fields('FirstName', 
			'LastName',
			'Address',
			'DateofBirth',
			'Mobile',
			'SuburbID',
			'Status');
		
		//$crud->callback_before_insert(array($this, 'create_user'));
		
		$languages = $crud->basic_model->return_query("SELECT `LangID`, `LangName`, `LangISO_639_1` FROM Language");
		$langArr = array();
		foreach($languages as $lang) {
			$langArr += [$lang->LangID => '(' . $lang->LangISO_639_1 . ') - ' . $lang->LangName];
		}
		$crud->field_type('LanguagesSpoken', 'multiselect', $langArr);

		//FAQ Levels
		$faql = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='FAQ_LEV'");
		$faqlArr = array();
		foreach($faql as $fl) {
			$faqlArr += [$fl->OptID => $fl->data];
		}
		$crud->field_type("FAQLev", "dropdown", $faqlArr);

		//Prettify Status Field
		$status = $crud->basic_model->return_query("SHOW COLUMNS FROM Person WHERE Field='Status'");

		preg_match("/^enum\(\'(.*)\'\)$/", $status[0]->Type, $matches);
		$statArr = explode("','", $matches[1]);
		$newstatArr = array();
		foreach($statArr as $contStat) {
			if($contStat==="EMPLOYED") {
				$newstatArr += [$contStat => "Employed"];
			} else if($contStat==="ON_LEAVE") {
				$newstatArr += [$contStat => "On Leave"];
			} else if($contStat==="RESIGNED") {
				$newstatArr += [$contStat => "Resigned"];
			} else if($contStat==="RETIRED") {
				$newstatArr += [$contStat => "Retired"];
			}
		}

		$crud->field_type('Status', 'dropdown', $newstatArr);
	
		//Enumerated Values for Suburbs
		$suburbs = $crud->basic_model->return_query("SELECT SuburbID, CONCAT(Postcode, ' - ', SuburbName) as FullSub FROM Suburb
		Order By SuburbName");
		$subArr = array();
		foreach($suburbs as $sub) {
			$subArr += [$sub->SuburbID => $sub->FullSub];
		}
		$crud->field_type("SuburbID", "dropdown", $subArr);
		
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
		$crud->field_type("EmpPosition", "dropdown", $posArr);
		$crud->field_type("EmpSecPosition", "dropdown", $posArr);		
		
		$output = $crud->render();

		
		$this->render($output);

		//print_r($output);
	}
	
	public function per_emp_insert($post_array){
		$this->insert_per_emp($post_array);
	}
	
	public function insert_per_emp(){
			
		//Build Person Insert
		$FirstName = $_POST['FirstName'];
		$MiddleName = $_POST['MiddleName'];
		$LastName = $_POST['LastName'];
		$Address = $_POST['Address'];
		$DateofBirth = $_POST['DateofBirth'];
		$SuburbID = $_POST['SuburbID'];
		$PersonalEmail = $_POST['PersonalEmail'];
		$Mobile = $_POST['Mobile'];
		$HomePhone = $_POST['HomePhone'];
		$Status = $_POST['Status'];
		$DateStarted = $_POST['DateStarted'];
		$ContractSigned = $_POST['ContractSigned'];
		$PaperworkCompleted = $_POST['PaperworkCompleted'];
		$WWC = $_POST['WWC'];
		$WWCFiled = $_POST['WWCFiled'];
		$WWCExpiry = $_POST['WWCExpiry'];
		$PoliceCheck = $_POST['PoliceCheck'];
		$PoliceCheckDate = $_POST['PoliceCheckDate'];
		$TeacherRegCheck = $_POST['TeacherRegCheck'];
		$TeacherExipry = $_POST['TeacherExipry'];
		$FAQual = $_POST['FAQual'];
		$FAQLev = $_POST['FAQLev'];
		$FAQaulExpiry = $_POST['FAQaulExpiry'];
		$LanguagesSpoken = $_POST['LanguagesSpoken'];
		$EmergContName = $_POST['EmergContName'];
		$EmergContMob = $_POST['EmergContMob'];
		$EmergContHPhone = $_POST['EmergContHPhone'];
		$EmergContWPhone = $_POST['EmergContWPhone'];
		$EmergContRelToPer = $_POST['EmergContRelToPer'];
		$Is_Sup = $_POST['Is_Sup'];
		$Password = $_POST['Password'];
		
		$LSList = "";	
		for($i = 0; $i < count($LanguagesSpoken); $i++) {
			if($i === count($LanguagesSpoken) - 1) {
				$LSList .= $LanguagesSpoken[$i];
			} else {
				$LSList .= $LanguagesSpoken[$i] .',';
			}
		}
		//create_user($post_array);
	
		$crud = new grocery_CRUD();
		$crud->set_model('PerEmp_GC');
		$Username = $this->PerEmp_GC->create_user($FirstName, $MiddleName, $LastName);
		$PerID = $crud->basic_model->insert_per($FirstName, $MiddleName, $LastName, $DateofBirth, $Address, $SuburbID, $PersonalEmail, $Mobile, $HomePhone, $Status, $DateStarted, $ContractSigned, $PaperworkCompleted, $WWC, $WWCFiled, $WWCExpiry, $PoliceCheck, $PoliceCheckDate, $TeacherRegCheck, $TeacherExipry, $FAQual, $FAQLev, $FAQaulExpiry, $Username, $Password, $LSList, $EmergContName, $EmergContMob, $EmergContHPhone, $EmergContWPhone, $EmergContRelToPer, $Is_Sup);
	
		//Employee Add
		
		$EmpPosition = $_POST['EmpPosition'];
		$EmpSecPosition = $_POST['EmpSecPosition'];
		$BGCSDepartment = $_POST['BGCSDepartment'];
		$Supervisor = $_POST['Supervisor'];
		$WorkMob = $_POST['WorkMob'];
		$WorkEmail = $_POST['WorkEmail'];
		$EmpDate = $_POST['EmpDate'];
		$Contract = $_POST['Contract'];
		$ContStatus = $_POST['ContStatus'];
		$ContStartDate = $_POST['ContStartDate'];
		$ContEndDate = $_POST['ContEndDate'];
		$HrlyRate = $_POST['HrlyRate'];
		$SecHrlyRate = $_POST['SecHrlyRate'];
		$HrsPerFrtnt = $_POST['HrsPerFrtnt'];
		$DaysWork = $_POST['DaysWork'];
		$NHACEClass = $_POST['NHACEClass'];
		$NHACEDate = $_POST['NHACEDate'];
		$AnnualLeave = $_POST['AnnualLeave'];
		$PersonalLeave = $_POST['PersonalLeave'];
		$FundUSI = $_POST['FundUSI'];
		$MmbershpNo = $_POST['MmbershpNo'];
		$SuperFund = $_POST['SuperFund'];
		$TerminationDate = $_POST['TerminationDate'];
		
		$daysList = "";	
		for($i = 0; $i < count($DaysWork); $i++) {
			if($i === count($DaysWork) - 1) {
				$daysList .= $DaysWork[$i];
			} else {
				$daysList .= $DaysWork[$i] .',';
			}
		}
		$resp = $crud->basic_model->insert_emp($PerID, $EmpPosition, $EmpSecPosition, $BGCSDepartment, $Supervisor, $WorkMob, $WorkEmail, $EmpDate, $Contract, $ContStatus, $ContStartDate, $ContEndDate, $HrlyRate, $SecHrlyRate, $HrsPerFrtnt, $daysList, $NHACEClass, $NHACEDate, $AnnualLeave,$PersonalLeave, $SuperFund, $FundUSI, $MmbershpNo, $TerminationDate);
		
		
		echo $resp;
	}

	
}