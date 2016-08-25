<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    require_once("Extended_generic_model.php");
	
    class Funding_GC extends Extended_generic_model {
     
        private  $query_str = ''; 
        function __construct() {
            parent::__construct();
        }
     
        function delete_fund($fundbodyid, $projectid)
        {
			//$amount = $this->db->simple_query("SELECT Amount FROM Funding WHERE ProjID='$projectid' AND FundBodyID='$fundbodyid' LIMIT 1");
            $resp = array();
			
            if($this->db->simple_query("DELETE FROM Funding WHERE ProjID='$projectid' AND FundBodyID='$fundbodyid' LIMIT 1")) {
				//$this->db->simple_query("UPDATE Project SET `TotalFunding`=`TotalFunding`-$amount	WHERE `ProjID` = $projectID ");
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

            if($this->db->simple_query("INSERT INTO Funding (ProjID, FundBodyID, Amount, PaymentType, ApprovedBy, ApprovedOn) 
			VALUES('$projectID', '$fundbodyid', '$amount', '$PaymentType', '$Approvedby', '$ApprovedOn')")) 
			{
				$this->db->simple_query("UPDATE Project SET `TotalFunding`=`TotalFunding`+$amount
				WHERE `ProjID` = $projectID ");
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

}
