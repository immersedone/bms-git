<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    require_once("Extended_generic_model.php");
	
    class Funding_GC extends Extended_generic_model {
     
        private  $query_str = ''; 
        function __construct() {
            parent::__construct();
        }
     
        function delete_fund($id, $amt, $projID)
        {
            $resp = array();
			
            if($this->db->simple_query("DELETE FROM Funding WHERE FundID='$id' LIMIT 1")) {
                $currentAmt = $this->db->query('SELECT TotalFunding FROM Project');
                $row = $currentAmt->row($projID-1);
                $newAmt = $row->TotalFunding - $amt;
				$this->db->simple_query("UPDATE Project SET TotalFunding='$newAmt' WHERE ProjID='$projID'");
                $resp['success'] = TRUE;
                $resp['success_message'] = "Successfully removed Item from Funding.";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = "Failed to remove Item from Funding.\n Try again later or contact system administrator.";
            }

            echo json_encode($resp);
        }

        function insert_fund($projectID, $fundbodyid, $amount, $PaymentType, $Approvedby, $ApprovedOn, $status) {

            $resp = array();

            if($this->db->simple_query("INSERT INTO Funding (ProjID, FundBodyID, Amount, PaymentType, ApprovedBy, ApprovedOn, Status) 
			VALUES('$projectID', '$fundbodyid', '$amount', '$PaymentType', '$Approvedby', '$ApprovedOn', '$status')")) 
			{

                $currentAmt = $this->db->query('SELECT TotalFunding FROM Project');
                $row = $currentAmt->row($projectID-1);
                $newAmt = $row->TotalFunding + $amount;
				$this->db->simple_query("UPDATE Project SET `TotalFunding`='$newAmt'
				WHERE `ProjID` = $projectID ");
                $resp['success'] = TRUE;
                $resp['success_list_url'] = base_url() . "user/funding";
                $resp['success_message'] = "Successfully added Funding to Project.";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = "Error adding Funding to Project.";
                $resp['error_fields'] = "";
            }

            echo json_encode($resp);

        }

        function update_fund($id, $newamount, $PaymentType, $Approvedby, $ApprovedOn, $status, $projectID, $oldamount) {

			$resp = array();

            if($this->db->simple_query("UPDATE Funding SET Amount = '$newamount', PaymentType = '$PaymentType', Status = '$status', ApprovedBy = '$Approvedby', ApprovedOn = '$ApprovedOn' WHERE FundID = '$id'"))
			{
				if($oldamount != $newamount){
                $currentAmt = $this->db->query('SELECT TotalFunding FROM Project');
                $row = $currentAmt->row($projectID-1);
                $newTotal = $row->TotalFunding + $newamount - $oldamount;
				$this->db->simple_query("UPDATE Project SET `TotalFunding`='$newTotal'
				WHERE `ProjID` = $projectID ");
				}
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
