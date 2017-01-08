<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('grocery_CRUD');
		$this->load->model('User_model');
	}

	public function index()
	{

		$data = array();
		$data["ProjCount"] = $this->User_model->countAProj();
		$this->load->view('user', $data);
		
	}

}