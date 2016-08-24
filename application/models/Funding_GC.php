<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    class Funding_GC extends grocery_CRUD_model {
     
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

        function delete_fund($fundbodyid, $projectid)
        {

            $resp = array();

            if($this->db->simple_query("DELETE FROM Funding WHERE ProjID='$projectid' AND FundBodyID='$fundbodyid' AND Role='EMPLOYEE' LIMIT 1")) {
                $resp['success'] = TRUE;
                $resp['success_message'] = "Successfully removed Item from Funding.";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = "Failed to remove Item from Funding.\n Try again later or contact system administrator.";
            }

            echo json_encode($resp);
        }

        function insert_fund($projectID, $fundbodyid, $amount, $PaymentType, $Approvedby, $ApprovedOn) {

            $resp = array();

            if($this->db->simple_query("INSERT INTO Funding (ProjID, FundBodyID, Amount, PaymentType, ApprovedBy, Approvedon) 
			VALUES('$projectID', $fundbodyid, $amount, $PaymentType, $Approvedby, $ApprovedOn')")) {
                $resp['success'] = TRUE;
                $resp['success_list_url'] = base_url() . "user/funding";
                $resp['success_message'] = "Successfully added Funding to Project.";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = "Successfully added Funding to Project.";
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
