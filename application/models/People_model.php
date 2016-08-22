<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class People_model extends Grocery_crud_model {
    
	
	private  $query_str = ''; 
	
    function __construct() {
        parent::construct();
    }
	
	function get_list() {
		$query=$this->db->query($this->query_str);
 
		$results_array=$query->result();
		return $results_array;      
	}
 
	public function set_query_str($query_str) {
		$this->query_str = $query_str;
	}

	function get_total_results() {
		return count($this->get_list());
	}



    function view_all_people()
    {
        $this->db->select('*');
        $this->db->from('People');
          
    }
    
        /*
    function list_all_employees()
    {

    }

    function list_all_volunteers()
    {

    }
    */
        
    //View expenditures for a person
    function view_person_expenditures()
    {
        $this->db
            ->select('*');
            ->from('Expenditure');
            ->where('SpentBy', $chosen_person);
    }

    //View reimbursements for a person
    function view_person_reimbursements()
    {
        $this->db
            ->select('*');
            ->from('Reimbursement');
            ->where('PerID', $chosen_person);
    }
   
    
    //Add people to a project
    public function add_people_to_proj($data)
    {  
        $this->db
            ->insert('PersonProject', $data);
    }
    
    
    //Add an expenditure for a person
    function add_person_expenditure()
    {
        $this->db
            ->insert('Expenditure', $data);
    }


    //Remove people from project
    function remove_people_from_proj()
    {
        
    }
    
    //Remove expenditure from a person
    function remove_person_expenditure()
    {

    }
}
*/
?>