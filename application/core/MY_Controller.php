<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

	public function __construct() {

		parent::__construct();

		$this->check_login();

	}

	//Function to Check Login
	public function check_login() {


		//$perID = $_SESSION["session_user"]["bms_psnid"];

		//Check to see if they are a temporary password
		//$checkSetting = $this->db->query('SELECT * FROM Person WHERE PerID="'.$perID.'" LIMIT 1')->row();

		if(!$this->is_logged_in()) {
			redirect('login', 'refresh');
		}// elseif($this->is_logged_in() && $checkSetting->isTempPass) {
		//	redirect('user/profile', 'refresh');
		//}
	}

	public function is_logged_in()
    {
        $user = $this->session->userdata('session_user');
        return isset($user);
    }

    public function update_DialogSetting() {

    	$perID = $_SESSION["session_user"]["bms_psnid"];

    	$currentSetting = $this->db->query('SELECT * FROM Person WHERE PerID="'.$perID.'" LIMIT 1')->row();

    	if($currentSetting->DialogForms == true) {
    		$newSetting = false;
    	} else {
    		$newSetting = true;
    	}

    	$this->db->where('PerID', $perID);
    	$this->db->update('Person', array('DialogForms'=>$newSetting));

    	$sessArr = array("session_user" => array(
			"bms_psnid" => $_SESSION["session_user"]["bms_psnid"],
			"bms_psnfullName" => $_SESSION["session_user"]["bms_psnfullName"],
			"bms_psnusrname" => $_SESSION["session_user"]["bms_psnusrname"],
			"bms_isAdmin" => $_SESSION["session_user"]["bms_isAdmin"],
			"bms_dialog" => $newSetting,
			"bms_sUsrPriv" => $_SESSION["session_user"]["bms_sUsrPriv"]
		));

		$this->session->set_userdata($sessArr);

		$checkSetting = $this->db->query('SELECT * FROM Person WHERE PerID="'.$perID.'" LIMIT 1')->row();

		$resp = array();
		if($checkSetting->DialogForms == $newSetting) {
			$resp["message"] = "SUCCESS";
		} else {
			$resp["message"] = "ERROR";
		}

		echo json_encode($resp);
    }

}


?>