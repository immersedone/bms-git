<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


    require_once("Extended_generic_model.php");

    class PerEmp_GC extends Extended_generic_model {
     
        function __construct() {
            parent::__construct();
        }

		public function insert_per($FirstName, $MiddleName, $LastName, $DateofBirth, $Address, $SuburbID, $PersonalEmail, $Mobile, $HomePhone, $Status, $DateStarted, $ContractSigned, $PaperworkCompleted, $WWC, $WWCFiled, $WWCExpiry, $PoliceCheck, $PoliceCheckDate, $TeacherRegCheck, $TeacherExipry, $FAQual, $FAQLev, $FAQaulExpiry, $Username, $Password, $LanguagesSpoken, $EmergContName, $EmergContMob, $EmergContHPhone, $EmergContWPhone, $EmergContRelToPer, $Is_Sup){
		           
			$resp = array();
            if($this->db->simple_query("INSERT INTO Person(FirstName, MiddleName, LastName, DateofBirth, Address, SuburbID, PersonalEmail, Mobile, HomePhone, Status, DateStarted, ContractSigned, PaperworkCompleted, WWC, WWCFiled, WWCExpiry, PoliceCheck, PoliceCheckDate, TeacherRegCheck, TeacherExipry, FAQual, FAQLev, FAQaulExpiry, Username, Password, LanguagesSpoken, EmergContName, EmergContMob, EmergContHPhone, EmergContWPhone, EmergContRelToPer, Is_Sup) VALUES ('$FirstName', '$MiddleName', '$LastName', '$DateofBirth', '$Address', '$SuburbID', '$PersonalEmail', '$Mobile', '$HomePhone', '$Status', '$DateStarted', '$ContractSigned', '$PaperworkCompleted', '$WWC', '$WWCFiled', '$WWCExpiry', '$PoliceCheck', '$PoliceCheckDate', '$TeacherRegCheck', '$TeacherExipry', '$FAQual', '$FAQLev', '$FAQaulExpiry', '$Username', '$Password', '$LanguagesSpoken', '$EmergContName', '$EmergContMob', '$EmergContHPhone', '$EmergContWPhone', '$EmergContRelToPer', '$Is_Sup')")){
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
                $resp['success_message'] = "Successfully inserted Employee.";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = "Failed to insert Employee into database.\n Try again later or contact system administrator.";
            }
            echo json_encode($resp);
        }
		
		
}