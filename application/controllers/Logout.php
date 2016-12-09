<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends CI_Controller {

	public function __construct() {

		parent::__construct();

	}

	public function index() {

		$array_items = array('bms_psnid', 'bms_psnfullName', 'bms_psnusrname', 'bms_isAdmin', 'bms_csrftoken');

		$this->session->unset_userdata($array_items);

		redirect('login', 'refresh');

	}

}

?>