<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    require_once("Extended_generic_model.php");
	
    class Reimbursement_GC extends Extended_generic_model {
     
        private  $query_str = ''; 
        function __construct() {
            parent::__construct();
        }
     
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

        function insert_reimb($reason, $date, $Approvedby, $ispaid, $perid)  //Removed type/ReimbID Temporarily
		{

            $resp = array();

            if($this->db->simple_query("INSERT INTO Reimbursement ('Reason', 'Date', 'ApprovedBy', 'IsPaid', 'PerID') 
			VALUES('$reason', '$date', '$Approvedby', '$ispaid', '$perid')")) 
			{
				//$this->db->simple_query("UPDATE Reimbursement SET `Amount`=`Amount`+$amount WHERE `PerID` = $perid ");
                $resp['success'] = TRUE;
                $resp['success_list_url'] = base_url() . "user/reimbursement";
                $resp['success_message'] = "Successfully added Reimbursement to Person";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = "Failed to added Reimbursement to Person";
                $resp['error_fields'] = "";
            }

            echo json_encode($resp);

        }

}