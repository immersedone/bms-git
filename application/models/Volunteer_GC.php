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
                $resp['result'] = "success";
            } else {
                $resp['result'] = "failed";
            }

            echo json_encode($resp);
        }
}
