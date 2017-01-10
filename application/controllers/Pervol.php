<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pervol extends MY_Controller {

	public function __construct()
	{
		parent::__construct();


		$this->load->library('grocery_CRUD');
		$this->load->library('bcrypt');
	}

	public function index()
	{
		$this->person_volunteer();
	}

	public function render($output = null) {
		$this->load->view('pervol', $output);
	}

	public function person_volunteer() {

		$tempPass = "d3F!_P4s$)";
		$tempHash = $this->bcrypt->hash_password($tempPass);

		$crud = new grocery_CRUD();
		$crud->set_model('Extended_generic_model');
		$crud->set_table('Person');
		$crud->set_subject('Person');
		$crud->basic_model->set_query_str('SELECT Vol.isActive, Vol.Supervisor, Vol.BGCSDepartment, Vol.DateStarted, Vol.DateFinished, Vol.RefFullName, Vol.RefMobile, Vol.RefHPhone, Vol.RefRelToVol, Vol.DaysWork, Vol.ContSkills, Vol.ContQual, Per.* FROM Person Per 
		LEFT OUTER JOIN Volunteer Vol on Vol.PerID = Per.PerID');

		$crud->add_fields('FirstName', 'MiddleName', 'LastName', 'Address', 'DateofBirth', 'SuburbID', 'PersonalEmail', 'Mobile', 'HomePhone', 'Status', 'DateStarted', 'ContractSigned', 'PaperworkCompleted', 'WWC', 'WWCFiled', 'WWCExpiry', 'PoliceCheck', 'PoliceCheckDate', 'TeacherRegCheck', 'TeacherExipry', 'FAQual', 'FAQLev', 'FAQaulExpiry', 'LanguagesSpoken', 'EmergContName', 'EmergContMob', 'EmergContHPhone', 'EmergContWPhone', 'EmergContRelToPer', 'Username', 'Password', 'Is_Sup', 'isActive', 'Supervisor', 'BGCSDepartment', 'DateStarted', 'DateFinished', 'RefFullName', 'RefMobile', 'RefHPhone', 'RefRelToVol', 'DaysWork', 'ContSkills', 'ContQual');

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
		$crud->display_as("BGCSDepartment", "Department Assigned To");
		$crud->display_as("RefFullName", "Referee Full Name");
		$crud->display_as("RefMobile", "Referee Mobile");
		$crud->display_as("RefRelToVol", "Referee Relation to Volunteer");
		$crud->display_as("RefHPhone", "Referee Home Phone");
		$crud->display_as("DaysWork", "Days Available");
		$crud->display_as("ContSkills", "Skills and Experience");
		$crud->display_as("ContQual", "Qualifications and Current Studies");
		$crud->display_as("FullName", "Full Name");
		$crud->display_as("Supervisor", "Supervisor");
		$crud->display_as("BGCSDepartment", "BGCS Department");
		$crud->display_as("DateStarted", "Date Started as Volunteer");
		$crud->display_as("DateFinished", "Date Finished as Volunteer");
		$crud->display_as("isActive", "Is Active");
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
		
		//BCGS Departments
		$bcgs = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='BGCS_DEP'");
		$bcgsArr = array();
		foreach($bcgs as $bc) {
			$bcgsArr += [$bc->OptID => $bc->data];
		}
		$crud->field_type("BGCSDepartment", "dropdown", $bcgsArr);

		//Days Array
		$availability = $crud->basic_model->return_query("SELECT OptID, data FROM OptionType WHERE type='Availability'");
		
		$daysArr = array();
		foreach($availability as $av) {
			$daysArr += [$av->OptID => $av->data];
		}
		$crud->field_type("DaysWork", "multiselect", $daysArr);

		$crud->set_rules("DaysWork", "Days Available", "trim|numeric|callback_multi_LS");
        
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
		
			
		
		$output = $crud->render();

		
		$this->render($output);

		//print_r($output);
	}
	
	public function per_vol_insert($post_array){
		$this->insert_per_vol($post_array);
	}
	
	public function insert_per_vol(){
			
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
	
		//Volunteer Add
				
		$isActive = $_POST["isActive"]; 
		$Supervisor = $_POST["Supervisor"]; 
		$BGCSDepartment = $_POST["BGCSDepartment"]; 
		$DateStarted = $_POST["DateStarted"]; 
		$DateFinished = $_POST["DateFinished"]; 
		$RefFullName = $_POST["RefFullName"]; 
		$RefMobile = $_POST["RefMobile"]; 
		$RefHPhone = $_POST["RefHPhone"]; 
		$RefRelToVol = $_POST["RefRelToVol"]; 
		$DaysWork = $_POST["DaysWork"]; 
		$ContSkills = $_POST["ContSkills"]; 
		$ContQual = $_POST["ContQual"];
		
		$daysList = "";	
		for($i = 0; $i < count($DaysWork); $i++) {
			if($i === count($DaysWork) - 1) {
				$daysList .= $DaysWork[$i];
			} else {
				$daysList .= $DaysWork[$i] .',';
			}
		}
		$resp = $crud->basic_model->insert_vol($PerID, $isActive, $Supervisor,  $BGCSDepartment,$DateStarted, $DateFinished,$RefFullName, $RefMobile, $RefHPhone, $RefRelToVol, $DaysWork, $ContSkills,  $ContQual);
		
		
		echo $resp;
	}

	
}