<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


    require_once("Extended_generic_model.php");

    class Milestone_GC extends Extended_generic_model {
     
        function __construct() {
            parent::__construct();
        }


        function insert_mile($ProjID, $ShortDesc, $DueDate, $RptType, $ReportIsDue, $PaymentMode, $Status, $Amount, $Comment, $FilePath) {

            $resp = array();

            if($this->db->simple_query("INSERT INTO Milestone_new (ProjID, ShortDesc, DueDate, RptType, ReportIsDue, PaymentMode, Status, Amount, Comment, FilePath) VALUES('$ProjID', '$ShortDesc', '$DueDate', '$RptType', '$ReportIsDue', '$PaymentMode', '$Status', '$Amount', '$Comment', '$FilePath')")) {
                $resp['success'] = TRUE;
                $resp['success_list_url'] = base_url() . "user/milestones";
                $resp['success_message'] = "Successfully added Milestone to Project.";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = "Error adding Milestone to Project.";
                $resp['error_fields'] = "";
            }

            echo json_encode($resp);

        }

}
