<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Volunteer_GC extends grocery_CRUD_model {
     
        private  $query_str = ''; 
        function __construct() {
            parent::__construct();
        }
     
        function get_list() {
            $query=$this->db->query($this->query_str);
     
            $results_array=$query->result();
            return $results_array;      
        }
     
        public function set_query_str($query_str) {
            $this->query_str = $query_str;
        }
    
        function get_total_results() {
            return count($this->get_list());
        }

        function delete_pp($userid, $projectid)
        {

            $resp = array();

            if($this->db->simple_query("DELETE FROM PersonProject WHERE ProjID='$projectid' AND PerID='$userid' LIMIT 1")) {
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
                $resp['success_list_url'] = base_url() . "user/volunteer";
                $resp['success_message'] = "Successfully added Person to Project.";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = "Successfully added Person to Project.";
                $resp['error_fields'] = "";
            }

            echo json_encode($resp);

        }

        function return_query($query_string) {
            $query=$this->db->query($query_string);
            $results_array=$query->result();
            return $results_array;
        }
}
