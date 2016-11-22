<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    require_once("Extended_generic_model.php");
	
    class Expenditure_model extends Extended_generic_model {
     
        private  $query_str = ''; 
        function __construct() {
            parent::__construct();
        }

        function insert_expenditure($ExpName, $Reason, $CompanyName, $amount, $gst, $SpentBy, $FilePath, $newDate, $ProjectID)  
		{

            $resp = array();

            if($this->db->simple_query("INSERT INTO Expenditure (ExpName, CompanyName, Reason, Amount, GST, SpentBy, ExpDate, FilePath, ProjID) VALUES('$ExpName', '$CompanyName', '$Reason', '$amount', '$gst', '$SpentBy', '$newDate', '$FilePath', '$ProjectID')")) 
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
