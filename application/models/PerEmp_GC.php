<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


    require_once("Extended_generic_model.php");

    class PerEmp_GC extends Extended_generic_model {
     
        function __construct() {
            parent::__construct();
        }

		public function insert_per($FirstName, $MiddleName, $LastName, $DateofBirth, $Address, $SuburbID, $PersonalEmail, $Mobile, $HomePhone, $Status, $DateStarted, $ContractSigned, $PaperworkCompleted, $WWC, $WWCFiled, $WWCExpiry, $PoliceCheck, $PoliceCheckDate, $TeacherRegCheck, $TeacherExipry, $FAQual, $FAQLev, $FAQaulExpiry, $Username, $Password, $LanguagesSpoken, $EmergContName, $EmergContMob, $EmergContHPhone, $EmergContWPhone, $EmergContRelToPer, $Is_Sup, $WWCReq, $PCReq, $TRCReq, $Is_FinanceController){
		           
			$resp = array();
            if($this->db->simple_query("INSERT INTO Person(FirstName, MiddleName, LastName, DateofBirth, Address, SuburbID, PersonalEmail, Mobile, HomePhone, Status, DateStarted, ContractSigned, PaperworkCompleted, WWC, WWCFiled, WWCExpiry, PoliceCheck, PoliceCheckDate, TeacherRegCheck, TeacherExipry, FAQual, FAQLev, FAQaulExpiry, Username, Password, LanguagesSpoken, EmergContName, EmergContMob, EmergContHPhone, EmergContWPhone, EmergContRelToPer, Is_Sup, WWCReq, PCReq, TRCReq, Is_FinanceController) VALUES ('$FirstName', '$MiddleName', '$LastName', '$DateofBirth', '$Address', '$SuburbID', '$PersonalEmail', '$Mobile', '$HomePhone', '$Status', '$DateStarted', '$ContractSigned', '$PaperworkCompleted', '$WWC', '$WWCFiled', '$WWCExpiry', '$PoliceCheck', '$PoliceCheckDate', '$TeacherRegCheck', '$TeacherExipry', '$FAQual', '$FAQLev', '$FAQaulExpiry', '$Username', '$Password', '$LanguagesSpoken', '$EmergContName', '$EmergContMob', '$EmergContHPhone', '$EmergContWPhone', '$EmergContRelToPer', '$Is_Sup',  '$WWCReq', '$PCReq', '$TRCReq', '$Is_FinanceController')")){
               // $resp['success'] = TRUE;
               // $resp['success_message'] = "Successfully inserted Person from Project.";
				$per_ID = $this->db->insert_id();
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = "Failed to insert Person into Project.\n Try again later or contact system administrator.";
				echo json_encode($resp);
            }
            return $per_ID;
        }
		
		public function insert_emp($PerID, $EmpPosition, $EmpSecPosition, $BGCSDepartment, $Supervisor, $WorkMob, $WorkEmail, $EmpDate, $Contract, $ContStatus, $ContStartDate, $ContEndDate, $HrlyRate, $SecHrlyRate, $HrsPerFrtnt, $DaysWork, $NHACEClass, $NHACEDate, $AnnualLeave, $PersonalLeave, $SuperFund, $FundUSI, $MmbershpNo, $TerminationDate){
		           
			$resp = array();
            if($this->db->simple_query("INSERT INTO Employee(PerID, EmpPosition, EmpSecPosition, BGCSDepartment, Supervisor, WorkMob, WorkEmail, EmpDate, Contract, ContStatus, ContStartDate, ContEndDate, HrlyRate, SecHrlyRate, HrsPerFrtnt, DaysWork, NHACEClass, NHACEDate, AnnualLeave, PersonalLeave, SuperFund, FundUSI, MmbershpNo, TerminationDate) VALUES('$PerID', '$EmpPosition', '$EmpSecPosition', '$BGCSDepartment', '$Supervisor', '$WorkMob', '$WorkEmail', '$EmpDate', '$Contract', '$ContStatus', '$ContStartDate', '$ContEndDate', '$HrlyRate', '$SecHrlyRate', '$HrsPerFrtnt', '$DaysWork', '$NHACEClass', '$NHACEDate', '$AnnualLeave', '$PersonalLeave', '$SuperFund', '$FundUSI', '$MmbershpNo', '$TerminationDate')")) {
                $resp['success'] = TRUE;
                $resp['success_list_url'] = base_url() .'/user/employee';
                $resp['success_message'] = "Successfully added Employee.";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = "Failed to insert Employee into database.\n Try again later or contact system administrator.";
            }
            echo json_encode($resp);
        }
		
		public function insert_vol($PerID, $isActive, $Supervisor,  $BGCSDepartment,$DateStarted, $DateFinished,$RefFullName, $RefMobile, $RefHPhone, $RefRelToVol, $DaysWork, $ContSkills,  $ContQual){
		           
			$resp = array();
            if($this->db->simple_query("INSERT INTO 
			Employee(PerID, Supervisor, BGCSDepartment, DateStarted, DateFinished, isActive, RefFullName, RefMobile, RefHPhone, RefRelToVol, DaysWork, ContSkills, ContQual) 
			VALUES('$PerID', '$Supervisor', '$BGCSDepartment', '$DateStarted', '$DateFinished', '$isActive', '$RefFullName', '$RefMobile', '$RefHPhone', '$RefRelToVol', '$DaysWork', '$ContSkills', '$ContQual')")) {
                $resp['success'] = TRUE;
            	$resp['success_list_url'] = base_url() .'/user/volunteer';
                $resp['success_message'] = "Successfully added Volunteer.";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = "Failed to insert Volunteer into database.\n Try again later or contact system administrator.";
            }
            echo json_encode($resp);
        }		
			
	
		public function create_user($FirstName, $MiddleName, $LastName) {

		$FName = strtolower($FirstName);
		$MName = strtolower($MiddleName);
		$LName = strtolower($LastName);

		$newUN = $FName . '.' . substr($MName, 0, 1) . $LName;

		$check = $this->db->query("SELECT * FROM Person WHERE Username='$newUN'");

		$findUN = "";
		$x = 1;

		while($check->result_id->num_rows > 0) {
			$findUN = $newUN . $x;
			$check = $this->db->query("SELECT * FROM Person WHERE Username='$findUN'");
			$x++;
		}

		if($findUN === "") {
			$findUN = $newUN;
		}
		return $findUN;
	}
}