<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


    require_once("Extended_generic_model.php");

    class Employee_GC extends Extended_generic_model {
     
        function __construct() {
            parent::__construct();
        }


        function delete_pp($userid, $projectid)
        {

            $resp = array();

            if($this->db->simple_query("DELETE FROM PersonProject WHERE ProjID='".$projectid."' AND PerID='".$userid."' AND EmpVol='Emp' LIMIT 1")) {
                $resp['success'] = TRUE;
                $resp['success_message'] = "Successfully removed Person from Project.";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = "Failed to remove Person from Project.\n Try again later or contact system administrator.";
            }

            echo json_encode($resp);
        }

        function insert_pp($personID, $projectID, $role, $EmpVol, $isActive, $startdate, $finishdate) {

            $resp = array();
			//Check if entry Person already exists in project with role
			$test = $this->db->query("SELECT * FROM PersonProject WHERE PerID = '$personID' AND ProjID = '$projectID' AND Role = '$role' AND EmpVol = '$EmpVol'");
			if($test->num_rows() > 0){
					$resp['exists'] = True;
					$resp['error_message'] = "User already exists in project with role";
					$resp['error_fields'] = "";
			}
			
			else{
				if($this->db->simple_query("INSERT INTO PersonProject (PerID, ProjID, Role, EmpVol, IsActive, StartDate, FinishDate) VALUES('$personID', '$projectID', '$role', '$EmpVol', '$isActive', '$startdate', '$finishdate')")) {
					$resp['success'] = TRUE;
					$resp['success_list_url'] = base_url() . "user/employee";
					$resp['success_message'] = "Successfully added Employee to Project.";
				} else {
					$resp['success'] = FALSE;
					$resp['error_message'] = $this->db->error();
					$resp['error_fields'] = "";
				}
			}
            echo json_encode($resp);
		
        }

}
