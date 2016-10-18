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

		$data = array();
		$projectID = $_POST['project'];
		$reportType = $_POST['reportType'];
		if(isset($_POST['optionalCoverPage'])) {
			$optCP = $_POST['optionalCoverPage'];
		}
		$data["titleLine_One"] = $_POST['title_one'];
		$data["titleLine_Two"] = $_POST['title_two'];
		$data["today_date"] = date('d F Y');
		$data["today_year"] = date('Y');
		$data["name"] = "Jaime de Loma-Osorio Ricon";
		if($reportType === "reimb") {
			$data["prjName"] = "N/A";
		} else { 
			$data["prjName"] = $this->Genreport_model->getProjectName($projectID);
		}
		$data["ReimbursementID"] = $_POST['reimbursement'];

		$pdfFilePath = "assets/public/Reports/Report.pdf";
		$viewPath = base_url() . $pdfFilePath;

		if(isset($optCP) && $optCP == "false") {
			$html = "";
		} else {
			$html = $this->load->view('/include/Report_CoverPage', $data, TRUE);
		}

		$this->load->library("grocery_CRUD");
		$this->load->model("Grocery_crud_model");
		$this->load->model("Extended_generic_model");

		if($reportType === "empprj") {

			$people = $this->Extended_generic_model->return_query("SELECT Proj.Name as ProjName, O1.Data as Role, CONCAT(Per.FirstName, ' ', Per.MiddleName, ' ', Per.LastName) as FullName, Per.*, Sub.Postcode as Postcode, Sub.SuburbName as SubName, Proj.ProjID as ProjID, Emp.*  FROM PersonProject PP
			LEFT OUTER JOIN Person Per ON Per.PerID = PP.PerID
			LEFT OUTER JOIN Project Proj ON Proj.ProjID = PP.ProjID
			LEFT OUTER JOIN OptionType O1 on O1.OptID = PP.Role
			LEFT OUTER JOIN OptionType O2 on O2.OptID = PP.BGCSDepartment
			LEFT OUTER JOIN Suburb Sub on Per.SuburbID = Sub.SuburbID
			LEFT OUTER JOIN Employee Emp on Per.PerID = Emp.PerID
			WHERE PP.ProjID=".$projectID);
			
			$html .= "<table><tbody>";
			$html .= "<tr><th>Full Name</th><th>Address</th><th>Postcode</th><th>Suburb</th><th>Work Email</th><th>Personal Email</th><th>Mobile</th><th>Home Phone</th></tr>";
			foreach($people as $ppl) {
				$html .= "<tr><td> " . $ppl->FullName . "</td><td> " . $ppl->Address . "</td><td> " . $ppl->Postcode . "</td><td> " . $ppl->SubName . "</td><td> " . $ppl->WorkEmail . "</td><td> " . $ppl->PersonalEmail . "</td><td> " . $ppl->Mobile . "</td><td> " . $ppl->HomePhone . "</td></tr>";
			}

			$html .= "</tbody></table>";

		} elseif ($reportType === "reimb") {

			$reimb = $this->Extended_generic_model->return_query('SELECT Re.*, CONCAT(Per.FirstName, " ", Per.MiddleName, " ", Per.LastName) as ApprovedBy, CONCAT(Pe.FirstName, " ", Pe.MiddleName, " ", Pe.LastName) as ApprovedFor FROM Reimbursement Re LEFT OUTER JOIN Person Per ON Re.ApprovedBy=Per.PerID LEFT OUTER JOIN Person Pe ON Re.PerID=Pe.PerID WHERE Re.ReimID='.$_POST['reimbursement']);
			

			$html .= "Reimbursement #: " .$_POST['reimbursement'];
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