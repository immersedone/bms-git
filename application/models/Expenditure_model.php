<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    require_once("Extended_generic_model.php");
	
    class Expenditure_model extends Extended_generic_model {
     
        private  $query_str = ''; 
        function __construct() {
            parent::__construct();
        }
     /*
        function delete_reimb($expid, $perid)
        {

            $resp = array();

            if($this->db->simple_query("DELETE FROM Reimbursement WHERE ExpID='$expid' OR PerID='$perid' LIMIT 1")) {
                $resp['success'] = TRUE;
                $resp['success_message'] = "Successfully removed Reimbursement";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = "Failed to remove Item from Reimbursement\n Try again later or contact system administrator.";
            }

            echo json_encode($resp);
        }
		
	*/

        function insert_expenditure($ExpName, $Reason, $CompanyName, $amount, $gst, $SpentBy, $ProjectID)  
		{

            $resp = array();

            if($this->db->simple_query("INSERT INTO Expenditure (ExpName, Reason, CompanyName, Amount, GST, SpentBy, ProjID) VALUES('$ExpName', '$Reason', '$CompanyName', '$amount', '$gst', '$SpentBy', '$ProjectID')")) 
			{
                $resp['success'] = TRUE;
                $resp['success_list_url'] = base_url() . "user/expenditures";
                $resp['success_message'] = "Successfully added Expenditure to Project";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = "Failed to added Expenditure to Project";
                $resp['error_fields'] = $this->db->error();
            }

            echo json_encode($resp);

        }

    }
