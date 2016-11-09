<?php

class Genreport extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
		$this->load->model('Genreport_model');
		$this->load->library('m_pdf');
	}

	public function index()
	{
		$data = array();
		$data["Projects"] = $this->Genreport_model->getProjects();
		$data["Reimbursements"] = $this->Genreport_model->getReimbursements();
		$data["Supervisors"] = $this->Genreport_model->getSupervisors();
		$this->load->view('genreport', $data);
		
	}

	public function render($output = null) {
		$this->load->view('genreport', $output);
	}

	public function reports() {

		
	}

	public function viewReport($url) {
		$output["url"] = $url;
		$this->load->view('viewreport', $output);
	}

	function createReport() {

		//Declare Variables
		$data = array();
		$reportType = $_POST['reportType'];
		$optCP = $_POST['optionalCoverPage'];
		$data["today_date"] = date('d F Y');
		$data["today_year"] = date('Y');
		$data["name"] = "Jaime de Loma-Osorio Ricon"; //To Change upon Login/Register Functions

		
		//Check if Variable Exist & get extra data
		if(isset($_POST['project'])) {
			$projectID = $_POST['project'];
			$data["prjName"] = $this->Genreport_model->getProjectName($projectID);
		} else {
			$data['prjName'] = "N/A";
		}

		if(isset($_POST['title_one'])) {
			$data["titleLine_One"] = $_POST['title_one'];	
		}
		
		if(isset($_POST['title_two'])) {
			$data["titleLine_Two"] = $_POST['title_two'];
		}
		
		if(isset($_POST['reimbursement'])) {
			$data["ReimbursementID"] = $_POST['reimbursement'];	
			$postReimb = $_POST['reimbursement'];
		}

		if(isset($_POST['fromDate'])) {
			$fromDate = $_POST['fromDate'];
		}

		if(isset($_POST['toDate'])) {
			$toDate = $_POST['toDate'];
		}

		

		//PDF Path & Declarations
		$pdfFilePath = "assets/public/Reports/Report.pdf";
		$viewPath = base_url() . $pdfFilePath;


		//Determine whether or not to display Cover Page
		//YES == Remove Cover Page
		//NO == Add in Optional Cover Page
		if(isset($optCP) && $optCP == "YES") {
			$html = "";
		} else {
			$html = $this->load->view('/include/Report_CoverPage', $data, TRUE);
		}


		//Load GroceryCRUD to generate data
		$this->load->library("grocery_CRUD");
		$this->load->model("Grocery_crud_model");
		$this->load->model("Extended_generic_model");

		//Report for Employees in a Project
		if($reportType === "empprj") {

			$people = $this->Extended_generic_model->return_query("SELECT DISTINCT Proj.Name as ProjName, O1.Data as Role, CONCAT(Per.FirstName, ' ', Per.MiddleName, ' ', Per.LastName) as FullName, Per.*, Sub.Postcode as Postcode, Sub.SuburbName as SubName, Proj.ProjID as ProjID, Emp.*  FROM PersonProject PP
			LEFT OUTER JOIN Person Per ON Per.PerID = PP.PerID
			LEFT OUTER JOIN Project Proj ON Proj.ProjID = PP.ProjID
			LEFT OUTER JOIN OptionType O1 on O1.OptID = PP.Role
			LEFT OUTER JOIN Suburb Sub on Per.SuburbID = Sub.SuburbID
			LEFT OUTER JOIN Employee Emp on Per.PerID = Emp.PerID
			WHERE PP.ProjID=".$projectID." AND PP.EmpVol='Emp'");
			
			$html .= "<table><tbody>";
			$html .= "<tr><th>Full Name</th><th>Address</th><th>Postcode</th><th>Suburb</th><th>Work Email</th><th>Personal Email</th><th>Mobile</th><th>Home Phone</th></tr>";
			foreach($people as $ppl) {
				$html .= "<tr><td> " . $ppl->FullName . "</td><td> " . $ppl->Address . "</td><td> " . $ppl->Postcode . "</td><td> " . $ppl->SubName . "</td><td> " . $ppl->WorkEmail . "</td><td> " . $ppl->PersonalEmail . "</td><td> " . $ppl->Mobile . "</td><td> " . $ppl->HomePhone . "</td></tr>";
			}

			$html .= "</tbody></table>";

		} 
		//Report for Reimbursements
		elseif ($reportType === "reimb") {

			$reimb = $this->Extended_generic_model->return_query('SELECT Re.*, CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as ApprovedBy, CONCAT(Pe.FirstName, " ", Pe.MiddleName, " ", Pe.LastName) as ApprovedFor FROM Reimbursement Re LEFT OUTER JOIN Person Per ON Re.ApprovedBy=Per.PerID LEFT OUTER JOIN Person Pe ON Re.PerID=Pe.PerID WHERE Re.ReimID='.$postReimb);
			

			$html .= "Reimbursement #: " .$postReimb;
			$html .= "<br/>Date of Reimbursement: " . $reimb[0]->ReimbDate;
			$html .= "<br/>Approved By: " . $reimb[0]->ApprovedBy;
			$html .= "<br/>Reimbursement For: " . $reimb[0]->ApprovedFor;
			$html .= "<br/>Reimbursement Is Paid: " . $reimb[0]->IsPaid;
			$html .= "<br/>Reimbursement Status: " . $reimb[0]->ReimbStatus;

			$html .= "<h4>Expenditures</h4>";
			$html .= "<table><tbody>";
			$html .= "<tr><th>Name</th><th>Company Name</th><th>Reason</th><th>Amount</th><th>GST</th><th>Type</th><th>Project</th></tr>";
			
			if($reimb[0]->ExpList == "") {
				$isEmpty = true;
			} else {
				$exp = explode(",", $reimb[0]->ExpList);
				$isEmpty = false;
			}
			
			$totalAm = 0;
			$totalGST = 0;

			if($isEmpty == true) {
				$html .= "<tr><td colspan='7' style='text-align:center;'>No Expenditures Listed</td></tr>";
			} else {
				foreach($exp as $row) {
					$expDet = $this->Extended_generic_model->return_query("SELECT Ex.ExpName, Ex.CompanyName, Ex.Reason, Ex.Amount, Ex.GST, Opt.Data as Type, Prj.Name as PrjName FROM Expenditure Ex LEFT OUTER JOIN OptionType Opt ON Ex.ExpType=Opt.OptID LEFT OUTER JOIN Project Prj ON Ex.ProjID=Prj.ProjID WHERE Ex.ExpID='" . $row ."'");
					$totalAm += $expDet[0]->Amount;
					$totalGST += $expDet[0]->GST;
					$html .= "<tr><td>" . $expDet[0]->ExpName ."</td><td>" . $expDet[0]->CompanyName ."</td><td>" . $expDet[0]->Reason ."</td><td>$" . $expDet[0]->Amount ."</td><td>$" . $expDet[0]->GST ."</td><td>" . $expDet[0]->Type ."</td><td>" . $expDet[0]->PrjName ."</td></tr>";
				}
				$html .= "<tr><td></td><td></td><td></td><td><b>Total Amount:</b></td><td>$" . number_format($totalAm, 2) . "</td><td></td><td></td></tr>";
				$html .= "<tr><td></td><td></td><td></td><td><b>Total GST:</b></td><td>$" . number_format($totalGST, 2) . "</td><td></td><td></td></tr>";
			}

			$html .= "</tbody></table>";
		} 
		//Report for Expenditure by Project & Date
		elseif ($reportType == "exp_prj") {

			$exp = $this->Extended_generic_model->return_query("SELECT 
				Exp.ExpID as ExpID,
				Exp.ExpName as ExpName,
				Exp.CompanyName as ExpCPName,
				Exp.Reason as ExpReason,
				Exp.Amount as ExpAmount,
				Exp.GST as ExpGST,
				Opt.data as ExpType,
				CONCAT(Per.FirstName, ' ', Per.MiddleName, ' ', Per.LastName) as ExpSpentBy,
				Exp.IsPaid as ExpIsPaid,
				Exp.IsRejected as ExpIsRejected,
				Exp.ExpDate as ExpDate,
				Prj.Name as ProjName
				FROM Expenditure Exp
				LEFT OUTER JOIN Person Per ON Exp.SpentBy=Per.PerID
				LEFT OUTER JOIN OptionType Opt ON Exp.ExpType=Opt.OptID
				LEFT OUTER JOIN Project Prj ON Exp.ProjID=Prj.ProjID
				WHERE Exp.ProjID='" . $projectID. "' 
				AND Exp.ExpDate >= '" . $fromDate . "'
				AND Exp.ExpDate <= '" . $toDate . "'
			");


			//Get Project Name
			$projName = $this->Extended_generic_model->return_query("SELECT Name FROM Project WHERE ProjID='" . $projectID. "' LIMIT 1");

			$html .= "Project Name: " . $projName[0]->Name;
			
			$html .= "<br/>Date Range: " . date('d F Y', strtotime($fromDate)) . ' - ' . date('d F Y', strtotime($toDate));

			$html .= "<h4>Expenditures</h4>";
			$html .= "<table><tbody>";
			$html .= "<tr><th>Name</th><th>Company Name</th><th>Reason</th><th>Amount</th><th>GST</th><th>Type</th><th>Spent By</th></tr>";

			//Check to see if List is empty
			if(empty($exp)) {
				$isEmpty = true;
			} else {
				$isEmpty = false;
			}
			
			//Variables to increment with each Expenditure
			$totalAm = 0;
			$totalGST = 0;


			if($isEmpty == true) {
				$html .= "<tr><td colspan='7' style='text-align:center;'>No Expenditures Listed</td></tr>";
			} else {
				foreach($exp as $row) {
					$totalAm += $row->ExpAmount;
					$totalGST += $row->ExpGST;
					$html .= "<tr><td>" . $row->ExpName ."</td><td>" . $row->ExpCPName ."</td><td>" . $row->ExpReason ."</td><td>$" . $row->ExpAmount ."</td><td>$" . $row->ExpGST ."</td><td>" . $row->ExpType ."</td><td>" . $row->ExpSpentBy . "</td></tr>";
				}
				$html .= "<tr><td></td><td></td><td></td><td><b>Total Amount:</b></td><td>$" . number_format($totalAm, 2) . "</td><td></td><td></td></tr>";
				$html .= "<tr><td></td><td></td><td></td><td><b>Total GST:</b></td><td>$" . number_format($totalGST, 2) . "</td><td></td><td></td></tr>";
			}

			$html .= "</tbody></table>";


		}
		//Report for Expenditure by Supervisor & Date
		elseif ($reportType == "exp_spv") {
			
		}
		//Report for Pending Milestones
		elseif ($reportType == "pend_mst") {
			
		}
		//Report for Contact Details - All Employees
		elseif ($reportType == "cdet_emp") {
			
		}
		//Report for Contact Details - All Volunteers
		elseif ($reportType == "cdet_vol") {
			
		}
		//Report for Contract and WWC - Status Review
		elseif ($reportType == "cont_srv") {
			
		}

		$html .= $this->load->view('/include/Report_EndHTML', $data, TRUE);

		//echo $html;
		$this->pdf = $this->m_pdf->load('utf-8', 'A4');
		$this->pdf->WriteHTML($html);
		$this->pdf->Output(FCPATH . $pdfFilePath, "F");

		$this->viewReport($viewPath);

	}

	function printReimbursement($id) {
		$data = array();
		$data["titleLine_One"] = 'Reimbursement Report';
		$data["titleLine_Two"] = date('Y');
		$data["today_date"] = date('d F Y');
		$data["today_year"] = date('Y');
		$data["name"] = "Jaime de Loma-Osorio Ricon";
		$data["prjName"] = "N/A";		
		$data["ReimbursementID"] = $id;

		$cp = $this->uri->segment(5);


		$pdfFilePath = "assets/public/Reports/Report.pdf";
		$viewPath = base_url() . $pdfFilePath;

		if($cp == 0) {
			$html = "";
		} else {
			$html = $this->load->view('/include/Report_CoverPage', $data, TRUE);	
		}

		

		$this->load->library("grocery_CRUD");
		$this->load->model("Grocery_crud_model");
		$this->load->model("Extended_generic_model");

		

		$reimb = $this->Extended_generic_model->return_query('SELECT Re.*, CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as ApprovedBy, CONCAT(Pe.FirstName, " ", Pe.MiddleName, " ", Pe.LastName) as ApprovedFor FROM Reimbursement Re LEFT OUTER JOIN Person Per ON Re.ApprovedBy=Per.PerID LEFT OUTER JOIN Person Pe ON Re.PerID=Pe.PerID WHERE Re.ReimID='.$id);
		

		$html .= "Reimbursement #: " .$id;
		$html .= "<br/>Date of Reimbursement: " . $reimb[0]->ReimbDate;
		$html .= "<br/>Approved By: " . $reimb[0]->ApprovedBy;
		$html .= "<br/>Reimbursement For: " . $reimb[0]->ApprovedFor;
		$html .= "<br/>Reimbursement Is Paid: " . $reimb[0]->IsPaid;
		$html .= "<br/>Reimbursement Status: " . $reimb[0]->ReimbStatus;

		$html .= "<h4>Expenditures</h4>";
		$html .= "<table><tbody>";
		$html .= "<tr><th>Name</th><th>Company Name</th><th>Reason</th><th>Amount</th><th>GST</th><th>Type</th><th>Project</th></tr>";
		
		if($reimb[0]->ExpList == "") {
			$isEmpty = true;
		} else {
			$exp = explode(",", $reimb[0]->ExpList);
			$isEmpty = false;
		}
		
		$totalAm = 0;
		$totalGST = 0;

		if($isEmpty == true) {
			$html .= "<tr><td colspan='7' style='text-align:center;'>No Expenditures Listed</td></tr>";
		} else {
			foreach($exp as $row) {
				$expDet = $this->Extended_generic_model->return_query("SELECT Ex.ExpName, Ex.CompanyName, Ex.Reason, Ex.Amount, Ex.GST, Opt.Data as Type, Prj.Name as PrjName FROM Expenditure Ex LEFT OUTER JOIN OptionType Opt ON Ex.ExpType=Opt.OptID LEFT OUTER JOIN Project Prj ON Ex.ProjID=Prj.ProjID WHERE Ex.ExpID='" . $row ."'");
				$totalAm += $expDet[0]->Amount;
				$totalGST += $expDet[0]->GST;
				$html .= "<tr><td>" . $expDet[0]->ExpName ."</td><td>" . $expDet[0]->CompanyName ."</td><td>" . $expDet[0]->Reason ."</td><td>" . $expDet[0]->Amount ."</td><td>" . $expDet[0]->GST ."</td><td>" . $expDet[0]->Type ."</td><td>" . $expDet[0]->PrjName ."</td></tr>";
			}
			$html .= "<tr><td></td><td></td><td></td><td><b>Total Amount:</b></td><td>$" . $totalAm . "</td><td></td><td></td></tr>";
			$html .= "<tr><td></td><td></td><td></td><td><b>Total GST:</b></td><td>$" . $totalGST . "</td><td></td><td></td></tr>";
		}

		$html .= "</tbody></table>";
		

		$html .= $this->load->view('/include/Report_EndHTML', $data, TRUE);

		//echo $html;
		$this->pdf = $this->m_pdf->load('utf-8', 'A4');
		$this->pdf->WriteHTML($html);
		$this->pdf->Output(FCPATH . $pdfFilePath, "F");

		$this->viewReport($viewPath);
	}
}