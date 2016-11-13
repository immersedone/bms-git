<?php

class People extends CI_Controller {

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
		$this->all_people();
	}

	public function render($output = null) {
		$this->load->view('people', $output);
	}

	public function all_people() {

		$crud = new grocery_CRUD();
		$crud->set_model('Extended_generic_model');
		$crud->set_table('Person');
		$crud->set_subject('Person');
		$crud->basic_model->set_query_str('SELECT * FROM (SELECT Sub.SuburbName as SubName, Sub.Postcode as Postcode, Per.* from `Person` Per
		LEFT OUTER JOIN `Suburb` Sub ON Per.SuburbID=Sub.SuburbID) x');
		$crud->columns('FirstName', 'LastName', 'Address', 'Postcode', 'SubName', 'PersonalEmail', 'Mobile', 'HomePhone');
		$crud->add_fields('FirstName', 'MiddleName', 'LastName', 'Address', 'SuburbID', 'PersonalEmail', 'Mobile', 'HomePhone', 'Status', 'DateStarted', 'WWC', 'WWCFiled', 'LanguagesSpoken', 'EmergContName', 'EmergContMob', 'EmergContHPhone', 'EmergContWPhone', 'EmergContRelToPer');
		$crud->edit_fields('FirstName', 'MiddleName', 'LastName','Address', 'SuburbID', 'PersonalEmail', 'Mobile', 'HomePhone', 'Status', 'DateStarted', 'DateFinished', 'ContractSigned', 'PaperworkCompleted', 'WWC', 'WWCFiled', 'PoliceCheck', 'TeacherRegCheck', 'FAQual', 'LanguagesSpoken', 'EmergContName', 'EmergContMob', 'EmergContHPhone', 'EmergContWPhone', 'EmergContRelToPer');	
		$crud->display_as('FirstName', 'First Name');
		$crud->display_as('MiddleName', 'Middle Name');
		$crud->display_as('LastName', 'Last Name');
		$crud->display_as('SuburbID', 'Suburb');
		//$crud->display_as('WorkEmail', 'Work Email');
		$crud->display_as('PersonalEmail', 'Personal Email');
		$crud->display_as('HomePhone', 'Home Phone');
		$crud->display_as('DateStarted', 'Date Started');
		$crud->display_as('DateFinished', 'Date Finished');
		$crud->display_as('ContractSigned', 'Contract Signed');
		$crud->display_as('PaperworkCompleted', 'Paperwork is Completed');
		$crud->display_as('SubName', 'Suburb');
		$crud->display_as('PoliceCheck', 'Valid Police Check');
		$crud->display_as('TeacherRegCheck', 'Valid Teacher Registration');
		$crud->display_as('WWC', 'Working With Children Check (WWC)');
		$crud->display_as('WWCFiled', 'Working With Children Check (WWC) is Filed');
		$crud->display_as('DateofBirth', 'Date of Birth');
		$crud->display_as('FAQual', 'First Aid Qualification Level');
		$crud->display_as('LanguagesSpoken', 'Languages Spoken');
		$crud->display_as('EmergContName', 'Emergency Contact Name');
		$crud->display_as('EmergContMob', 'Emergency Contact Mobile');
		$crud->display_as('EmergContHPhone', 'Emergency Contact Home Phone');
		$crud->display_as('EmergContWPhone', 'Emergency Contact Work Phone');
		$crud->display_as('EmergContRelToPer', 'Emergency Contact Relation');
		$crud->field_type('Username', 'hidden');
		$crud->field_type('Password', 'hidden');
		$crud->field_type('Hash', 'hidden');
		$crud->field_type('Timeout', 'hidden');

		//$crud->required_fields('Firstname', 'Lastname', 'Address', 'Suburb');
		//Call model to get languages
		$languages = $crud->basic_model->return_query("SELECT `LangID`, `LangName`, `LangISO_639_1` FROM Language");
		$langArr = array();
		foreach($languages as $lang) {
			$langArr += [$lang->LangID => '(' . $lang->LangISO_639_1 . ') - ' . $lang->LangName];
		}


		$crud->field_type('LanguagesSpoken', 'multiselect', $langArr);
		

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
	
		//Call Model to get the Project Names
		$suburbs = $crud->basic_model->return_query("SELECT SuburbID, CONCAT(Postcode, ' - ', SuburbName) as FullSub FROM Suburb");
		
		//Convert Return Object into Associative Array
		$subArr = array();
		foreach($suburbs as $sub) {
			$subArr += [$sub->SuburbID => $sub->FullSub];
		}

		//Change the field type to a dropdown with values
		//to add to the relational table
		$crud->field_type("SuburbID", "dropdown", $subArr);
		
		$output = $crud->render();

		$this->render($output);
	}

	public function getPerName($id) {

		$crud = new grocery_CRUD();
		$crud->set_model('Project_GC');
		$res = $crud->basic_model->return_query("SELECT CONCAT(FirstName, ' ', MiddleName, ' ', LastName) as PerName FROM Person WHERE PerID='$id' LIMIT 1");

		$resp = array();
		$resp["PerName"] = $res[0]->PerName;
		echo json_encode($resp);
	}

	public function getRole($id) {

		$crud = new grocery_CRUD();
		$crud->set_model("Project_GC");
		$res = $crud->basic_model->return_query("SELECT data FROM OptionType WHERE OptID='" . $id ."' LIMIT 1");
		$resp = array();
		$resp["RoleName"] = $res[0]->data;
		echo json_encode($resp);
	}

	public function getSBName($id) {

		$crud = new grocery_CRUD();
		$crud->set_model('Project_GC');
		$res = $crud->basic_model->return_query("SELECT CONCAT(SuburbName, ' - ', Postcode, ', ', SuburbState_Postal,'.') as SBName FROM Suburb WHERE SuburbID='$id' LIMIT 1");


		$resp = array();
		$resp["SBName"] = $res[0]->SBName;
		echo json_encode($resp);
	}

	public function getLangName() {
		$langArr = explode(',', $_POST['languages']);

		$crud = new grocery_CRUD();
		$crud->set_model('Project_GC');

		$resp = array();
		$resp["Languages"] = "";
		for($i = 0; $i < count($langArr); $i++) {
			$id = $langArr[$i];
			$res = $crud->basic_model->return_query("SELECT CONCAT('(', `LangISO_639_1`, ') - ', `LangName`) as Lang FROM Language WHERE LangID='$id' LIMIT 1");

			$count = $i + 1;

			$resp["Languages"] .= $count . ". ";
			$resp["Languages"] .= $res[0]->Lang;
			$resp["Languages"] .= "<br/>";
		}
		
		echo json_encode($resp);
	}

	public function getDays() {
		$daysArr = explode(',', $_POST['days']);

		$crud = new grocery_CRUD();
		$crud->set_model('Project_GC');

		$resp = array();
		$resp["Days"] = "";
		for($i = 0; $i < count($daysArr); $i++) {
			$id = $daysArr[$i];
			$res = $crud->basic_model->return_query("SELECT data FROM OptionType WHERE OptID='$id' AND type='Availability' LIMIT 1");

			$resp["Days"] .= $res[0]->data;
			if($i !== (count($daysArr) - 1)) {
				$resp["Days"] .= ", ";
			}
		}
		
		echo json_encode($resp);
	}

	public function getPosition($id) {
		$crud = new grocery_CRUD();
		$crud->set_model('Project_GC');
		$res = $crud->basic_model->return_query("SELECT data FROM OptionType WHERE OptID='$id' LIMIT 1");


		$resp = array();
		$resp["Position"] = $res[0]->data;
		echo json_encode($resp);
	}

	public function getBGCS() {
		$bgcsArr = explode(',', $_POST['BGCS']);

		$crud = new grocery_CRUD();
		$crud->set_model('Project_GC');

		$resp = array();
		$resp["BGCS"] = "";
		for($i = 0; $i < count($bgcsArr); $i++) {
			$id = $bgcsArr[$i];
			$res = $crud->basic_model->return_query("SELECT data FROM OptionType WHERE OptID='$id' AND type='BGCS_DEP' LIMIT 1");

			$resp["BGCS"] .= $res[0]->data;
			if($i !== (count($bgcsArr) - 1)) {
				$resp["BGCS"] .= ",";
			}
		}
		
		echo json_encode($resp);
	}

	public function getNHACE() {
		$nhaceArr = explode(',', $_POST['NHACE']);

		$crud = new grocery_CRUD();
		$crud->set_model('Project_GC');

		$resp = array();
		$resp["NHACE"] = "";
		for($i = 0; $i < count($nhaceArr); $i++) {
			$id = $nhaceArr[$i];
			$res = $crud->basic_model->return_query("SELECT data FROM OptionType WHERE OptID='$id' AND type='NHACE_CLASS' LIMIT 1");

			$resp["NHACE"] .= $res[0]->data;
			if($i !== (count($nhaceArr) - 1)) {
				$resp["NHACE"] .= ",";
			}
		}
		
		echo json_encode($resp);
	}
	
	public function output_people() {

		$crud = new grocery_CRUD();
		$crud->set_model('Extended_generic_model');
		$crud->set_table('Person');
		$crud->set_subject('Person');
		$crud->basic_model->set_query_str('SELECT Sub.SuburbName as SubName, Sub.Postcode as Postcode, Per.* from `Person` Per
		LEFT OUTER JOIN `Suburb` Sub ON Per.SuburbID=Sub.SuburbID');
		$crud->columns('FirstName', 'LastName', 'Address', 'Postcode', 'SubName', 'PersonalEmail', 'Mobile', 'HomePhone');
		$crud->add_fields('FirstName', 'MiddleName', 'LastName', 'Address', 'SuburbID', 'PersonalEmail', 'Mobile', 'HomePhone', 'Status', 'DateStarted', 'WWC', 'WWCFiled', 'LanguagesSpoken', 'EmergContName', 'EmergContMob', 'EmergContHPhone', 'EmergContWPhone', 'EmergContRelToPer');
		$crud->edit_fields('FirstName', 'MiddleName', 'LastName','Address', 'SuburbID', 'PersonalEmail', 'Mobile', 'HomePhone', 'Status', 'DateStarted', 'DateFinished', 'ContractSigned', 'PaperworkCompleted', 'WWC', 'WWCFiled', 'PoliceCheck', 'TeacherRegCheck', 'FAQual', 'LanguagesSpoken', 'EmergContName', 'EmergContMob', 'EmergContHPhone', 'EmergContWPhone', 'EmergContRelToPer');	
		$crud->display_as('FirstName', 'First Name');
		$crud->display_as('MiddleName', 'Middle Name');
		$crud->display_as('LastName', 'Last Name');
		$crud->display_as('SuburbID', 'Suburb');
		//$crud->display_as('WorkEmail', 'Work Email');
		$crud->display_as('PersonalEmail', 'Personal Email');
		$crud->display_as('HomePhone', 'Home Phone');
		$crud->display_as('DateStarted', 'Date Started');
		$crud->display_as('DateFinished', 'Date Finished');
		$crud->display_as('ContractSigned', 'Contract Signed');
		$crud->display_as('PaperworkCompleted', 'Paperwork is Completed');
		$crud->display_as('SubName', 'Suburb');
		$crud->display_as('PoliceCheck', 'Valid Police Check');
		$crud->display_as('TeacherRegCheck', 'Valid Teacher Registration');
		$crud->display_as('WWC', 'Working With Children Check (WWC)');
		$crud->display_as('WWCFiled', 'Working With Children Check (WWC) is Filed');
		$crud->display_as('DateofBirth', 'Date of Birth');
		$crud->display_as('FAQual', 'First Aid Qualification Level');
		$crud->display_as('LanguagesSpoken', 'Languages Spoken');
		$crud->display_as('EmergContName', 'Emergency Contact Name');
		$crud->display_as('EmergContMob', 'Emergency Contact Mobile');
		$crud->display_as('EmergContHPhone', 'Emergency Contact Home Phone');
		$crud->display_as('EmergContWPhone', 'Emergency Contact Work Phone');
		$crud->display_as('EmergContRelToPer', 'Emergency Contact Relation');
		$crud->field_type('Username', 'hidden');
		$crud->field_type('Password', 'hidden');
		$crud->field_type('Hash', 'hidden');
		$crud->field_type('Timeout', 'hidden');


		//Call model to get languages
		$languages = $crud->basic_model->return_query("SELECT `LangID`, `LangName`, `LangISO_639_1` FROM Language");
		$langArr = array();
		foreach($languages as $lang) {
			$langArr += [$lang->LangID => '(' . $lang->LangISO_639_1 . ') - ' . $lang->LangName];
		}


		$crud->field_type('LanguagesSpoken', 'multiselect', $langArr);
		

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
	
		//Call Model to get the Project Names
		$suburbs = $crud->basic_model->return_query("SELECT SuburbID, CONCAT(Postcode, ' - ', SuburbName) as FullSub FROM Suburb");
		
		//Convert Return Object into Associative Array
		$subArr = array();
		foreach($suburbs as $sub) {
			$subArr += [$sub->SuburbID => $sub->FullSub];
		}

		//Change the field type to a dropdown with values
		//to add to the relational table
		$crud->field_type("SuburbID", "dropdown", $subArr);
		
		$output = $crud->render();

		return $output->output;
	}

}
