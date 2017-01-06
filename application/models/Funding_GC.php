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
                $currentAmt = $this->db->query('SELECT TotalFunding FROM Project WHERE ProjID="' . $projID . '"');
                $row = $currentAmt->row();
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

                //Get Project ID
                /*$getProjID = $this->db->query("SELECT P.ProjID FROM `Project` P
                    WHERE P.ProjID < 
                    (
                        SELECT Pr.ProjID FROM `Project` Pr
                        WHERE Pr.ProjID <= '$projectID' ORDER BY Pr.ProjID DESC LIMIT 0,1
                    )
                    ORDER BY P.ProjID DESC
                    LIMIT 0,1
                ")->row();*/

                //echo $getProjID->ProjID;

                $currentAmt = $this->db->query('SELECT TotalFunding FROM Project WHERE ProjID="'. $projectID . '"');
                $row = $currentAmt->row();
                
                //echo $projectID;

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
                $currentAmt = $this->db->query('SELECT TotalFunding FROM Project WHERE ProjID="' . $projectID . '"');
                $row = $currentAmt->row();
                $newTotal = $row->TotalFunding + $newamount - $oldamount;
				$this->db->simple_query("UPDATE Project SET `TotalFunding`='$newTotal'
				WHERE `ProjID` = $projectID ");
				}
                $resp['success'] = TRUE;
                $resp['success_list_url'] = base_url() . "user/funding";
                $resp['success_message'] = "Successfully updated Funding";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = "Failed to update Funding.";
                $resp['error_fields'] = "";
            }

            echo json_encode($resp);

        }

}
