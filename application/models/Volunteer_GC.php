<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    require_once("Extended_generic_model.php");
	
    class Volunteer_GC extends Extended_generic_model {
     
        private  $query_str = ''; 
        function __construct() {
            parent::__construct();
        }

        function delete_pp($userid, $projectid)
        {

            $resp = array();

            if($this->db->simple_query("DELETE FROM PersonProject WHERE ProjID='$projectid' AND PerID='$userid' AND Role='VOLUNTEER' LIMIT 1")) {
                $resp['success'] = TRUE;
                $resp['success_message'] = "Successfully removed Person from Project.";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = "Failed to remove Person from Project.\n Try again later or contact system administrator.";
            }

            echo json_encode($resp);
        }

        function insert_pp($personID, $projectID, $role, $position, $BGSCDept, $isActive, $supervisor, $startdate, $finishdate) {

            $resp = array();

            if($this->db->simple_query("INSERT INTO PersonProject (PerID, ProjID, Role, Position, BGCSDepartment, IsActive, Supervisor, StartDate, FinishDate) VALUES('$personID', '$projectID', '$role', '$position', '$BGSCDept', '$isActive', '$supervisor', '$startdate', '$finishdate')")) {
                $resp['success'] = TRUE;
                $resp['success_list_url'] = base_url() . "user/volunteer";
                $resp['success_message'] = "Successfully added Volunteer to Project.";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = $this->db->error();
                $resp['error_fields'] = "";
            }

            echo json_encode($resp);

        }

}
