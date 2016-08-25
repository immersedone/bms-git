<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


    require_once("Extended_generic_model.php");

    class Milestone_GC extends Extended_generic_model {
     
        function __construct() {
            parent::__construct();
        }


        function insert_mile($ProjID, $Title, $Description, $StartDate, $FinishDate) {

            $resp = array();

            if($this->db->simple_query("INSERT INTO Milestone (ProjID, Title, Description, StartDate, FinishDate) VALUES('$ProjID', '$Title', '$Description', '$StartDate', '$FinishDate')")) {
                $resp['success'] = TRUE;
                $resp['success_list_url'] = base_url() . "user/milestones";
                $resp['success_message'] = "Successfully added Person to Project.";
            } else {
                $resp['success'] = FALSE;
                $resp['error_message'] = "Successfully added Person to Project.";
                $resp['error_fields'] = "";
            }

            echo json_encode($resp);

        }

}
