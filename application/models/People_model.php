<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class People_model extends grocery_CRUD_Model {

    private $query_str = '';
    
    function __construct() {
        parent::construct();
    }
    
    public function set_query_str($query_str){
        $this->query_str = $query_str;
    }
    
    function list_all_people()
    {
        $query = $this->db->query($this->query_str);
        
        $results_arr=$query->result();
        return $results_arr;       
    }

    /*
    function list_all_employees()
    {
    }
    */

    /*
    function list_all_volunteers()
    {

    }
    */
    //Add people to a project
    function add_people_to_proj()
    {  
        /*
        $sql = "IF NOT EXISTS 
                INSERT INTO PersonProject(PersonProjectID, PersonID, ProjID, Role)
                VALUES(PersonProjectID,
                      (SELECT PersonID
                       FROM Person
                       WHERE FirstName =
                       OR MiddleName =
                       OR LastName = ),
                      (SELECT ProjID
                       FROM Project
                       WHERE Name = 
                       OR ProjID = 
                       OR Status = ),
                       )";
                     /*
                      (SELECT RoleName
                       FROM Roles
                       WHERE RoleName = $chosen_role)
                     */
        $query = $this->db->query($sql);
    }

    //Remove people from project
    function remove_people_from_proj()
    {
        $sql = "IF EXISTS
                INSERT INTO bms_archive.Person
                SELECT Person
                ";
        $query = $this->db->query($sql);
               
    }

    //View expenditures for a person
    function view_person_expenditures()
    {
        $sql = "SELECT FROM Expenditure
                ";
        $query = $this->db->query($sql);
    }

    //View reimbursements for a person
    function view_person_reimbursements()
    {
        $sql = "SELECT FROM Reimbursement
                ";
        $query = $this->db->query($sql);
    }

    //Add an expenditure for a person
    function add_person_expenditure()
    {
        $chosen_person;
        $sql = "IF NOT EXISTS
                INSERT INTO
                ";
        $query = $this->db->query($sql);
    }

    //Remove epxenditure from a person
    function remove_person_expenditure()
    {
        $sql = "IF EXISTS
                ALTER TABLE
                ";
        $query = $this->db->query($sql);
    }
}
?>