<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {
	
	public function __construct() {

		parent::__construct();

		//Load Libraries
		$this->load->library('grocery_CRUD');
		$this->load->library('bcrypt');
		$this->load->helper('form');

	}


	public function index() {

		//Pass Session Vars
		$perID = $_SESSION["session_user"]["bms_psnid"];


		//Get the current details and pass the data to the view
		$data = array();
		$data['fRC'] = $this->returnCurrent($perID);
		$data['sbLT'] = $this->returnSuburbList();

		//Load the View
		$this->load->view('profile', $data);

	}

	public function returnSuburbList() {

		$list = $this->db->query('SELECT SuburbID, SuburbName, Postcode FROM Suburb')->result();

		$listArr = array();

		foreach($list as $k => $v) {
			$listArr[$v->SuburbID] = $v->SuburbName . ' (' . $v->Postcode . ')';
			//$listArr[$v->SuburbID] = $v->SuburbName;
			//echo $k . ' || ' . $v;
		}

		return $listArr;

	}


	public function returnCurrent($perID) {

		//Get the current details
		$cPD = $this->db->query('SELECT * FROM Person WHERE PerID="' . $perID . '" LIMIT 1');

		//Declare storage array
		$cPD_Arr = $cPD->row();

		return $cPD_Arr;


	}

	public function procdet() {

		//Get the POST array and convert them to variables
		$email = $this->input->post('email');
		$phone = $this->input->post('phone');
		$mobile = $this->input->post('mobile');
		$address = $this->input->post('address');
		$suburb = $this->input->post('suburb');


		//Person ID
		$perID = $_SESSION["session_user"]["bms_psnid"];

		//Get the current details and pass the data to the view
		$data = array();
		$data['fRC'] = $this->returnCurrent($perID);
		$data['sbLT'] = $this->returnSuburbList();

		//Array for ERROR/SUCCESS Messages
		$data['error'] = array();

		//Get Details
		$det = $this->returnCurrent($perID);


		//Run Empty Checks
		if($email === "" || empty($email)) {
			$data['error']['field'] = "email";
			$data['error']['message'] = "Error - Email is required.";
		} elseif($phone === "" || empty($phone)) {
			$data['error']['field'] = "phone";
			$data['error']['message'] = "Error - Phone Number is required.";
		} elseif($mobile === "" || empty($mobile)) {
			$data['error']['field'] = "mobile";
			$data['error']['message'] = "Error - Mobile Number is required.";
		} elseif($address === "" || empty($address)) {
			$data['error']['field'] = "address";
			$data['error']['message'] = "Error - Address is required.";
		} elseif($suburb === "" || empty($suburb)) {
			$data['error']['field'] = "suburb";
			$data['error']['message'] = "Error - Suburb is required.";
		} else {

			//No Empty Fields, proceed to deeper validation

			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$data['error']['field'] = "email";
				$data['error']['message'] = "Error - Invalid Email Address.";
			} elseif(!preg_match('/[0-9+\-() ]{8,16}/', $phone)) {
				$data['error']['field'] = "phone";
				$data['error']['message'] = "Error - Phone Number is invalid.";
			} elseif(!preg_match('/[0-9+\-() ]{8,16}/', $mobile)) {
				$data['error']['field'] = "mobile";
				$data['error']['message'] = "Error - Mobile Number is invalid.";
			} elseif(!preg_match('/[A-Za-z0-9\\/ ,]{10,80}/', $address)) {
				$data['error']['field'] = "address";
				$data['error']['message'] = "Error - Invalid Address. Only use alphanumeric characters and the following symbols: '\', '/', ','.";
			} elseif(!preg_match('/[0-9]/', $suburb)) {
				$data['error']['field'] = "suburb";
				$data['error']['message'] = "Error - Suburb is invalid.";
			} else {

				//Deeper validation OK, proceed to process form

				$upRes = $this->db->query("UPDATE Person SET PersonalEmail='" . $email ."', HomePhone='" . $phone . "', Mobile='" . $mobile . "', Address='" . $address . "', SuburbID='" . $suburb . "' WHERE PerID='" . $perID . "'" );

				if($upRes === 0 || $upRes === false) {
					//Error updating new password
					$data['error']['field'] = "updateProf";
					$data['error']['message'] = "Error while updating your profile. Please try again later.";

				} else {

					//[Redundant] - Just using redirect instead
					//All is ok, return success message
					/*$data['success']['status'] = true;
					$data['success']['form'] = 'profile';
					$data['success']['message'] = "Successfully updated your profile.";*/
					redirect('user/profile', 'refresh');

				}

			}


		} 


		//Return View with Messages
		$this->load->view('profile', $data);

	}

	public function procpas() {

		//Get the POST array and convert them to variables
		$cuPass = $this->input->post('current');
		$nePass = $this->input->post('new');
		$rpPass = $this->input->post('newrepeat');


		//Person ID
		$perID = $_SESSION["session_user"]["bms_psnid"];

		//Get the current details and pass the data to the view
		$data = array();
		$data['fRC'] = $this->returnCurrent($perID);
		$data['sbLT'] = $this->returnSuburbList();

		//Array for ERROR/SUCCESS Messages
		$data['error'] = array();

		//Get Details
		$det = $this->returnCurrent($perID);

		//Run Checks
		if($this->bcrypt->check_password($cuPass, $det->Password)) {


			//If current and new pass word the same 
			if($cuPass === $nePass) {
				//Current & New Passwords Match
				//Add error message

				$data['error']['field'] = "new";
				$data['error']['message'] = "Error - New Password must be different to current password.";

			} else {

				if($nePass !== $rpPass) {

					$data['error']['field'] = "newrepeat";
					$data['error']['message'] = "Error - Passwords do not match.";

				} else {

					//All fields OK
					//Get New Password Hash
					$nwHsh = $this->bcrypt->hash_password($rpPass);

					//Update Table && Remove Temp Pass status if applicable
					$upRes = $this->db->query("UPDATE Person SET Password='" . $nwHsh . "', isTempPass='0' WHERE PerID='" . $perID . "'");

					if($upRes === 0 || $upRes === false) {
						
						//Error updating new password
						$data['error']['field'] = "updatePass";
						$data['error']['message'] = "Error while changing password. Please try again later.";

					} else {

						//[Redundant] - Just using redirect instead
						//All is ok, return success
						/*$data['success']['status'] = true;
						$data['success']['form'] = 'password';
						$data['success']['message'] = "Successfully changed password. New Password will be in effect after you sign in next.";*/

						redirect('user/profile', 'refresh');

					}

				}

			}
			

		} else { 

			//Current Password doesn't match
			
			$data['error']['field'] = "current";
			$data['error']['message'] = "Error - Current Password is incorrect.";

			

		}


		//Return View with Messages
		$this->load->view('profile', $data);

	}

}


?>