<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forgot extends CI_Controller {

	public function __construct() {
		
		parent::__construct();

		$this->load->library('form_validation');
		$this->load->library('bcrypt');
		$this->load->library('email');

	}

	public function index()
	{
		$this->load->view('forgot');
	}

	public function email() {

		//Declare Vars from Post Array
		$username = $this->input->post('username');
		$emailAdd = $this->input->post('emailadd');

		

		//Check Database for matching username and email
		$checkUser = $this->db->query("SELECT PerID, PersonalEmail, concat(FirstName, ' ', MiddleName, ' ', LastName) as FullName FROM Person WHERE Username='" . $username . "' LIMIT 1 ");
		$checkUserNumRow = $checkUser->num_rows();

		//Testing array print for SQL
		//print_r($checkUser);

		//Assign SQL to Variables
		$perID = $checkUser->row()->PerID;
		$fullName = $checkUser->row()->FullName;

		//Test Vars
		//echo $perID . ' || Name: ' . $fullName;

		if($checkUserNumRow == 0) {
			//Username doesn't exist
			redirect("forgot?er=udne", "refresh");

		} else {


			//Create temporary 6 alphanumeric password that is
			//case-sensitive
			$stack = str_split('abcdefghijklmnopqrstuvwxyz' . 
				'ABCDEFGHIJKLMNOPQRSTUVWXYZ' .
				'0123456789');

			//Sort through the stack
			shuffle($stack);

			//Declare the variables
			$key = '';

			//Loop through the randomly assorted array
			//and add to the key. 
			foreach (array_rand($stack, 6) as $k) {
				$key .= $stack[$k];	
			}

			//Pass Key to TempPass variable
			$tempPass = $this->bcrypt->hash_password($key);	

			//Update SQL Database with new Password
			$upRes = $this->db->query("UPDATE Person SET Password='" . $tempPass . "', isTempPass='1' WHERE PerID='" . $perID . "'");

			if($upRes === 0 || $upRes === false) { 
				redirect("login?er=ftup", "refresh");
			} else { 

				//

				//Send the Email
				$emSub = "[BGCS] Password Reset";

				$emMsg = "<html><head><style>";
				$emMsg .= "";
				$emMsg .= "</style></head><body>";
				$emMsg .= "<br/><div class='header'>Hi " . $fullName . ",</div><br/><br/>";
				$emMsg .= "<p>The password for your account with us at Banksia Gardens Community Services has been reset. The temporary password for your account is as shown below: <br/></p>";
				$emMsg .= "<p style='font-size: 24px;'><b>" . $key . "</b></p><br/>";
				$emMsg .= "Please make sure you change your password after signing in for security purposes.";
				$emMsg .= "<br/><br/><div class='footer'>Kind Regards,<br/><br/>Banksia Gardens Community Services Team.</div>";
				$emMsg .= "</body></html>";


				$emRes = $this->email
						->from('INSERT_FROM_EMAIL_HERE', 'Banksia Gardens Community Services')
						->reply_to('admin@banksiagardens.org.au','Banksia Gardens Community Services')
						->to($emailAdd)
						->subject($emSub)
						->message($emMsg)
						->send();

				if($emRes) {
					redirect("login?sc=1&rdt=frg", "refresh");
				} else {
					redirect("forgot?er=ftsm", "refresh");
				}

			}

		}

	}
}