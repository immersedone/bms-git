<?php 

class Genreport_model extends CI_Model {

	public function __construct()
    {
            // Call the CI_Model constructor
            parent::__construct();
    }

    public function getProjects()
    {
    	$query = $this->db->query("SELECT ProjID, Name FROM Project");

    	$projects = array();
    	foreach ($query->result_array() as $row) {
    		$projects[$row["ProjID"]] = $row["Name"];
    	}

    	return $projects;
    }

    public function getProjectName($id) 
    {

        
        $query = $this->db->select("Name")
                        ->from("Project")
                        ->where("ProjID", $id)
                        ->get();
        $row = $query->row();

        return $row->Name;

    }

    public function getReimbursements()
    {
        $query = $this->db->query("SELECT Reimbursement.ReimID FROM Reimbursement LEFT OUTER JOIN Person ON Reimbursement.ApprovedBy=Person.PerID LEFT OUTER JOIN Person as Per ON Reimbursement.PerID=Per.PerID");
        $reimb = array();
        foreach($query->result_array() as $row) {
            $reimb[$row["ReimID"]] = $row["ReimID"];
        }

        return $reimb;
    }

}

?>