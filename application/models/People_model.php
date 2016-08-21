<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class People_model extends Grocery_crud_model {
    
    function __construct() {
        parent::construct();
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
            ->where('Expenditure.SpentBy'=$chosen_person);
    }

    //View reimbursements for a person
    function view_person_reimbursements()
    {
        $this->db
            ->select('*');
            ->from('Reimbursement');
            ->where('Reimbursement.PerID', $chosen_person);
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
    
    //Remove epxenditure from a person
    function remove_person_expenditure()
    {

    }
}
*/
?>