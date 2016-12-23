<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

	public function __construct() {
		
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->library('bcrypt');

	}

	public function index()
	{

		

		if(!isset($_SESSION["session_user"])) {
			$this->load->view('login');
		} else {
			redirect('user', 'refresh');
		}
		
	}

	public function auth() {

		//Declare Vars from Post Array
		$username = $this->input->post('username');
		$password = $this->input->post('password');

		//Determine whether username is email or not
		if(filter_var($username, FILTER_VALIDATE_EMAIL)) {
			$isEmail = true;
			$SQLvar = 'PersonalEmail';
		} else {
			$isEmail = false;
			$SQLvar = 'Username';
		}


		//Check if Username Exists
		$checkExists = $this->db->query("SELECT * FROM Person WHERE $SQLvar='$username'");

		if($checkExists->num_rows() == 0) {

			//Username doesn't exist
			redirect("login?error=Username doesn't exist", "refresh");

		} else {
			//Username exists, extract records
			$row = $checkExists->row();

			//Check Password against BCrypt Library
			if($this->bcrypt->check_password($password, $row->Password)) {
				
				//Password Correct, now set the session and
				//redirect to backend

				$sessArr = array("session_user" => array(
					"bms_psnid" => $row->PerID,
					"bms_psnfullName" => $row->FirstName . ' ' . $row->MiddleName .  ' ' . $row->LastName,
					"bms_psnusrname" => $row->Username,
					"bms_isAdmin" => $row->isAdmin,
					"bms_dialog" => $row->DialogForms
				));

				$this->session->set_userdata($sessArr);

				redirect('user', 'refresh');


			} else {

				//Password Incorrect, redirect
				redirect("login?error=Password incorrect.", "refresh");

			}
		}




	}
}