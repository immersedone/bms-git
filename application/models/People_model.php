<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class People_model extends Grocery_crud_model {
    
    function __construct() {
        parent::construct();
    }

    function list_all_people()
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
    
    //Add people to a project
    public function add_people_to_proj($data)
    {  
        $this->db->insert('PersonProject', $data);
    }

    //Remove people from project
    function remove_people_from_proj()
    {
        
    }

    //View expenditures for a person
    function view_person_expenditures()
    {

    }

    //View reimbursements for a person
    function view_person_reimbursements()
    {

    }

    //Add an expenditure for a person
    function add_person_expenditure()
    {

    }

    //Remove epxenditure from a person
    function remove_person_expenditure()
    {

    }
}
*/
?>