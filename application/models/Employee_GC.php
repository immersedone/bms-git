<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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

        function insert_pp($personID, $projectID, $role) {

            $resp = array();

            if($this->db->simple_query("INSERT INTO PersonProject (ProjID, PerID, Role) VALUES('$projectID', '$personID', '$role')")) {
                $resp['success'] = TRUE;
                $resp['success_list_url'] = base_url() . "user/employee";
                $resp['success_message'] = "Successfully added Person to Project.";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = "Successfully added Person to Project.";
                $resp['error_fields'] = "";
            }

            echo json_encode($resp);

        }

}
