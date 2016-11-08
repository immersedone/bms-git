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
        $query = $this->db->query("SELECT Reimbursement.ReimID, CONCAT(Per.FirstName, ' ', Per.MiddleName, ' ', Per.LastName) as FullName, Reimbursement.ExpList as ExpList FROM Reimbursement LEFT OUTER JOIN Person ON Reimbursement.ApprovedBy=Person.PerID LEFT OUTER JOIN Person as Per ON Reimbursement.PerID=Per.PerID");
        $reimb = array();
        foreach($query->result_array() as $row) {

            if($row["ExpList"] !== "") {
                $list = explode(',', $row["ExpList"]);

                $amt = 0;

                foreach($list as $l) {
                    $qL = $this->db->query("SELECT Amount FROM Expenditure WHERE ExpID='" . $l . "' LIMIT 1");

                    $r = $qL->row();
                    $amt +=  $r->Amount;
                }
            } else {
                $amt = 0;
            }

            $reimb[$row["ReimID"]] = '(' . $row["ReimID"] . ')  ' . $row["FullName"] . ' - $' . $amt;
        }

        return $reimb;
    }

    public function getSupervisors()
    {
        $query = $this->db->query("SELECT 
            Per.PerID as PerID, 
            CONCAT(Per.FirstName, ' ', Per.MiddleName, ' ', Per.LastName) as FullName,
            Proj.Name as ProjName 
            FROM Person Per 
            LEFT OUTER JOIN PersonProject PP ON Per.PerID=PP.PerID 
            LEFT OUTER JOIN OptionType Opt ON PP.Role=Opt.OptID 
            LEFT OUTER JOIN Project Proj ON PP.ProjID=Proj.ProjID
            WHERE Opt.type='Role' AND Opt.data='Supervisor'");
        $supervisors = array();

        foreach($query->result_array() as $row) {
            $supervisors[$row["PerID"]] = $row["FullName"] . ' [' . $row["ProjName"] . ']';
        }

        return $supervisors;
    }

}

?>