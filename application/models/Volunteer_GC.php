<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    require_once("Extended_generic_model.php");
	
    class Volunteer_GC extends Extended_generic_model {
     
        private  $query_str = ''; 
        function __construct() {
            parent::__construct();
        }

        function delete_pp($userid, $projectid)
        {

            echo $userid . ' - '. $projectid;
            $resp = array();

            if($this->db->simple_query("DELETE FROM PersonProject WHERE ProjID='$projectid' AND PerID='$userid' AND EmpVol='Vol' LIMIT 1")) {
                $resp['success'] = TRUE;
                $resp['success_list_url'] = base_url() . "user/projects/index/projread/list";
                $resp['success_message'] = "Successfully removed Person from Project.";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = "Failed to remove Person from Project.\n Try again later or contact system administrator.";
            }

            echo json_encode($resp);
        }

        function insert_pp($personID, $projectID, $role, $EmpVol, $isActive, $startdate, $finishdate) {

            $resp = array();

            if($this->db->simple_query("INSERT INTO PersonProject (PerID, ProjID, Role, EmpVol, IsActive, StartDate, FinishDate) VALUES('$personID', '$projectID', '$role', '$EmpVol', '$isActive', '$startdate', '$finishdate')")) {
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
