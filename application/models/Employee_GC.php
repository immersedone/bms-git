<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


    require_once("Extended_generic_model.php");

    class Employee_GC extends Extended_generic_model {
     
        function __construct() {
            parent::__construct();
        }


        function delete_pp($userid, $projectid)
        {

            $resp = array();

            if($this->db->simple_query("DELETE FROM PersonProject WHERE ProjID='$projectid' AND PerID='$userid' AND Role='EMPLOYEE' LIMIT 1")) {
                $resp['success'] = TRUE;
                $resp['success_message'] = "Successfully removed Person from Project.";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = "Failed to remove Person from Project.\n Try again later or contact system administrator.";
            }

            echo json_encode($resp);
        }

        function insert_pp($PerID, $EmpPosition, $EmpSecPosition, $BGCSDepartment, $Supervisor, $WorkMob, $WorkEmail, $EmpDate, $Contract, $ContStatus, $ContStartDate, $ContEndDate, $HrlyRate, $SecHrlyRate, $HrsPerFrtnt, $DaysWork, $NHACEClass, $NHACEDate, $AnnualLeave, $PersonalLeave, $FundUSI , $MmbershpNo , $SuperFund, $TerminationDate) {

            $resp = array();
            $days = "";
            for($i = 0; $i < count($DaysWork); $i++) {
                if($i == count($DaysWork) - 1) {
                    $days .= $DaysWork[$i];
                } else {
                    $days .= $DaysWork[$i].',';
                }
            }

            if($this->db->simple_query("INSERT INTO Employee (PerID, EmpPosition, EmpSecPosition, BGCSDepartment, Supervisor, WorkMob, WorkEmail, EmpDate, Contract, ContStatus, ContStartDate, ContEndDate, HrlyRate, SecHrlyRate, HrsPerFrtnt, DaysWork, NHACEClass, NHACEDate, AnnualLeave, PersonalLeave, SuperFund, FundUSI, MmbershpNo, TerminationDate) VALUES('$PerID', '$EmpPosition', '$EmpSecPosition', '$BGCSDepartment', '$Supervisor', '$WorkMob', '$WorkEmail', '$EmpDate', '$Contract', '$ContStatus', '$ContStartDate', '$ContEndDate', '$HrlyRate', '$SecHrlyRate', '$HrsPerFrtnt','$days', '$NHACEClass', '$NHACEDate', '$AnnualLeave', '$PersonalLeave', '$SuperFund', '$FundUSI', '$MmbershpNo',  '$TerminationDate')")) {
                $resp['success'] = TRUE;
                $resp['success_list_url'] = base_url() . "user/employee";
                $resp['success_message'] = "Successfully added Person to Project.";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = '(Error): '. print_r($this->db->error());
                $resp['error_fields'] = "";
            }

            echo json_encode($resp);

        }

}
