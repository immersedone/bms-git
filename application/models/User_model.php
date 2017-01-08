<?php

class User_model extends CI_Model
{
    function login($username, $password)
    {
        //$this->db->select(')
    }
	
	function countAProj()
	{
    	$countProj = $this->db->where("Status", "Active")
						->count_all_results("Project");
    	return $countProj;
	}
	
}
?>