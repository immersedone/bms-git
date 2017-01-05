<?php

class Genreport extends MY_Controller {

	public function __construct()
	{
		parent::__construct();


		$this->load->library('grocery_CRUD');
		$this->load->model('Genreport_model');
		$this->load->library('m_pdf');
	}

	public function index()
	{

		$data = array();
		$data["Projects"] = $this->Genreport_model->getProjects();
		$data["Reimbursements"] = $this->Genreport_model->getReimbursements();
		$data["Supervisors"] = $this->Genreport_model->getSupervisorsAllPrj();
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
		$data["name"] = $_SESSION["session_user"]["bms_psnfullName"];

		
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


		if(isset($_POST['supervisor'])) {
			$supervisor = $_POST['supervisor'];
		}

		

		//PDF Path & Declarations
		//$pdfFilePath = "assets/public/Reports/Report.pdf";
		$pdfFilePath = "assets/public/Reports/";
		$FileStorageName = uniqid(md5(rand())) . '-' . time() . '.pdf';
		$viewPath = base_url() . $pdfFilePath . $FileStorageName;


		//If Custom Title is not set
		if(isset($_POST['customTitleCheck']) && $_POST['customTitleCheck'] === "NO") {

			//Array for Pre-Defined Titles - Line #1
			//Index is the Name for Report Type
			//Value is the Title for Line #1
			$lineOneArr = array(
				"empprj" => "Employee Listing",
				"volprj" => "Volunteer Listing",
				"reimb" => "Reimbursement Details",
				"exp_prj" => "Expenditures by Project",
				"exp_spv" => "Expenditures by Supervisors",
				"pend_mst" => "Pending Milestones",
				"future_mst" => "Future Milestones",
				"cdet_emp" => "Contact Details - Employees",
				"cdet_vol" => "Contact Details - Volunteers",
				"cont_srv" => "Contract & WWC"
			);

			$data["titleLine_One"] = $lineOneArr[$reportType];

			//Make 2nd Line "Report" and Current Year
			$data["titleLine_Two"] = "Report " . date('Y');
		}

		//Determine whether or not to display Cover Page
		//YES == Remove Cover Page
		//NO == Add in Optional Cover Page
		if(isset($optCP) && $optCP == "YES") {
			$coverPage = "";
		} else {

			$coverPage = $this->load->view('/include/Report_CoverPage', $data, TRUE);
		}

		$html = "";
		$title = $data["titleLine_One"] . ' ' . $data["titleLine_Two"];
		$fileName = $title . '.pdf';

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
		else//Report for Employees in a Project
		if($reportType === "volprj") {

			$people = $this->Extended_generic_model->return_query("SELECT DISTINCT Proj.Name as ProjName, O1.Data as Role, CONCAT(Per.FirstName, ' ', Per.MiddleName, ' ', Per.LastName) as FullName, Per.*, Sub.Postcode as Postcode, Sub.SuburbName as SubName, Proj.ProjID as ProjID, Emp.*  FROM PersonProject PP
			LEFT OUTER JOIN Person Per ON Per.PerID = PP.PerID
			LEFT OUTER JOIN Project Proj ON Proj.ProjID = PP.ProjID
			LEFT OUTER JOIN OptionType O1 on O1.OptID = PP.Role
			LEFT OUTER JOIN Suburb Sub on Per.SuburbID = Sub.SuburbID
			LEFT OUTER JOIN Employee Emp on Per.PerID = Emp.PerID
			WHERE PP.ProjID=".$projectID." AND PP.EmpVol='Vol'");
			
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
			$html .= "<tr><th>Name</th><th>Company Name</th><th>Reason</th><th>Total Amount</th><th>GST</th><th>Type</th><th>Project</th></tr>";
			
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
				$html .= "<tr><td></td><td></td><td></td><td><b>Grand Total:</b></td><td>$" . number_format($totalAm, 2) . "</td><td></td><td></td></tr>";
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
				$html .= "<tr><td></td><td></td><td></td><td><b>Grand Total:</b></td><td>$" . number_format($totalAm, 2) . "</td><td></td><td></td></tr>";
				$html .= "<tr><td></td><td></td><td></td><td><b>Total GST:</b></td><td>$" . number_format($totalGST, 2) . "</td><td></td><td></td></tr>";
			}

			$html .= "</tbody></table>";


		}
		//Report for Expenditure by Supervisor & Date
		elseif ($reportType == "exp_spv") {
			
			//Get Supervisor Name
			$supName = $this->Extended_generic_model->return_query("SELECT
				CONCAT(Per.FirstName, ' ', Per.MiddleName, ' ', Per.LastName) as FullName
				FROM Person Per
				WHERE Per.PerID='" . $supervisor . "'
			");
			
			//Get Count of Total Projects (used for Loop)
			$countProj = $this->Extended_generic_model->return_query("SELECT
				COUNT(*) as Count,
				Prj.ProjID as ProjID
				FROM PersonProject PP 
				LEFT OUTER JOIN Person Per ON Per.PerID=PP.PerID
				LEFT OUTER JOIN Employee Emp ON Emp.PerID=PP.PerID
				LEFT OUTER JOIN OptionType Opt ON PP.Role=Opt.OptID
				LEFT OUTER JOIN Project Prj ON PP.ProjID=Prj.ProjID
				WHERE Opt.type='Role'
				AND Opt.data='Supervisor'
				AND PP.EmpVol='Emp'
				AND PP.PerID='" . $supervisor . "'
			");

			//Get Array of Project ID's (used for Loop)
			$getProjID = $this->Extended_generic_model->return_query("SELECT
				Prj.ProjID as ProjID,
				Prj.Name as ProjName
				FROM PersonProject PP 
				LEFT OUTER JOIN Person Per ON Per.PerID=PP.PerID
				LEFT OUTER JOIN Employee Emp ON Emp.PerID=PP.PerID
				LEFT OUTER JOIN OptionType Opt ON PP.Role=Opt.OptID
				LEFT OUTER JOIN Project Prj ON PP.ProjID=Prj.ProjID
				WHERE Opt.type='Role'
				AND Opt.data='Supervisor'
				AND PP.EmpVol='Emp'
				AND PP.PerID='" . $supervisor . "'
				ORDER BY Prj.ProjID
			");

			//Bind Variables
			$spvName = $supName[0]->FullName;
			$projCount = $countProj[0]->Count;

			$html .= "Supervisor Name: " . $spvName . "<br/>";
			$html .= "Number of Projects: " . $projCount;

			$projIDArr = array();
			$projNameArr = array();

			//Loop through and add ProjectID to array
			foreach($getProjID as $gPr) {
				array_push($projIDArr, $gPr->ProjID);
				array_push($projNameArr, $gPr->ProjName);
			}


			for($i = 0; $i < $projCount; $i++) {

				//Get Expenditures for a Specific Project in Array
				$spv = $this->Extended_generic_model->return_query("SELECT
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
				LEFT OUTER JOIN Person Per ON Per.PerID=Exp.SpentBy
				LEFT OUTER JOIN Project Prj ON Exp.ProjID=Prj.ProjID
				LEFT OUTER JOIN OptionType Opt ON Exp.ExpType=Opt.OptID
				WHERE Prj.ProjID='" . $projIDArr[$i] . "'
				AND Exp.ExpDate >= '" . $fromDate . "'
				AND Exp.ExpDate <= '" . $toDate . "'
				");

				//Check to see if List is empty
				if(empty($spv)) {
					$isEmpty = true;
				} else {
					$isEmpty = false;
				}

				//Variables for Counting
				$totalAm = 0;
				$totalGST = 0;

				//Spit out Table Header for Project
				$html .= "<h4>Expenditures for \"" . $projNameArr[$i] . "\"</h4>";
				$html .= "<table><tbody>";
				$html .= "<tr><th>Name</th><th>Company Name</th><th>Reason</th><th>Total Amount</th><th>GST</th><th>Type</th><th>Spent By</th></tr>";

				if($isEmpty == true) {
					$html .= "<tr><td colspan='7' style='text-align:center;'>No Expenditures Listed</td></tr>";
				} else {
					//Run Loop 
					foreach($spv as $row) {
						$totalAm += $row->ExpAmount;
						$totalGST += $row->ExpGST;
						$html .= "<tr><td>" . $row->ExpName ."</td><td>" . $row->ExpCPName ."</td><td>" . $row->ExpReason ."</td><td>$" . $row->ExpAmount ."</td><td>$" . $row->ExpGST ."</td><td>" . $row->ExpType ."</td><td>" . $row->ExpSpentBy . "</td></tr>";
					}

					$html .= "<tr><td></td><td></td><td></td><td><b>Grand Total:</b></td><td>$" . number_format($totalAm, 2) . "</td><td></td><td></td></tr>";
					$html .= "<tr><td></td><td></td><td></td><td><b>Total GST:</b></td><td>$" . number_format($totalGST, 2) . "</td><td></td><td></td></tr>";
				}

				//Spit out Table Footer for Project

				$html .= "</tbody></table>";
			}

		}
		//Report for Pending Milestones
		elseif ($reportType == "pend_mst") {
			
			$mst = $this->Extended_generic_model->return_query('SELECT 
				P.Name as ProjName, 
				M.* 
				FROM `Milestone_new` M
				LEFT OUTER JOIN `Project` P ON M.ProjID=P.ProjID 
				WHERE M.Status!="Completed"
				AND M.DueDate <= "' . $toDate . '"
				ORDER BY M.DueDate ASC
			');

			//Header 
			$html .= "Report Created: " . date('d F Y');

			//Content
			$html .= "<h4>Pending Milestones</h4>";
			$html .= "<table><tbody>";
			$html .= "<tr><th>Project Name</th><th>Description</th><th>Due Date</th><th>Report Type</th><th>Report Is Due</th><th>Payment Mode</th><th>Status</th><th>Total Amount</th><th>Comment</th></tr>";

			//Loop
			foreach($mst as $em) { 


				//Prettify Fields

				if($em->ReportIsDue == 0) {
					$RptID = "No";
				} else {
					$RptID = "Yes";
				}

				if($em->RptType === "REPORT") {
					$RptType = "Report";
				} elseif($em->RptType === "PAYMENT") {
					$RptType = "Payment";
				} elseif($em->RptType === "REP_PAY") {
					$RptType = "Report &amp; Payment";
				} elseif($em->RptType === "FINAL_REP") {
					$RptType = "Final Report";
				}


				$html .= "<tr><td>" . $em->ProjName . "</td><td>" . $em->ShortDesc . "</td><td>" . date('d F Y', strtotime($em->DueDate)) . "</td><td>" . $RptType . "</td><td>" . $RptID . "</td><td>" . $em->PaymentMode . "</td><td>" . $em->Status . "</td><td>$" . number_format($em->Amount, 2) . "</td><td>" . $em->Comment . "</td></tr>";
			}

			$html .= "</tbody></table>";

		}
		//Report for Pending Milestones
		elseif ($reportType == "future_mst") {
			
			$mst = $this->Extended_generic_model->return_query('SELECT 
				P.Name as ProjName, 
				M.* 
				FROM `Milestone_new` M
				LEFT OUTER JOIN `Project` P ON M.ProjID=P.ProjID 
				WHERE M.Status!="Completed"
				AND M.DueDate <= "' . $toDate . '"
				AND M.DueDate >= now()
				ORDER BY M.DueDate ASC
			');

			//Header 
			$html .= "Report Created: " . date('d F Y');

			//Content
			$html .= "<h4>Future Milestones</h4>";
			$html .= "<table><tbody>";
			$html .= "<tr><th>Project Name</th><th>Description</th><th>Due Date</th><th>Report Type</th><th>Report Is Due</th><th>Payment Mode</th><th>Status</th><th>Total Amount</th><th>Comment</th></tr>";

			//Loop
			foreach($mst as $em) { 


				//Prettify Fields

				if($em->ReportIsDue == 0) {
					$RptID = "No";
				} else {
					$RptID = "Yes";
				}

				if($em->RptType === "REPORT") {
					$RptType = "Report";
				} elseif($em->RptType === "PAYMENT") {
					$RptType = "Payment";
				} elseif($em->RptType === "REP_PAY") {
					$RptType = "Report &amp; Payment";
				} elseif($em->RptType === "FINAL_REP") {
					$RptType = "Final Report";
				}


				$html .= "<tr><td>" . $em->ProjName . "</td><td>" . $em->ShortDesc . "</td><td>" . date('d F Y', strtotime($em->DueDate)) . "</td><td>" . $RptType . "</td><td>" . $RptID . "</td><td>" . $em->PaymentMode . "</td><td>" . $em->Status . "</td><td>$" . number_format($em->Amount, 2) . "</td><td>" . $em->Comment . "</td></tr>";
			}

			$html .= "</tbody></table>";

		}
		//Report for Contact Details - All Employees
		elseif ($reportType == "cdet_emp") {
			

			$emp = $this->Extended_generic_model->return_query(" SELECT
				Emp.WorkEmail,
				Emp.WorkMob,
				Per.PersonalEmail,
				Per.Mobile,
				Per.HomePhone,
				Per.EmergContName,
				Per.EmergContMob,
				Per.EmergContHPhone,
				Per.EmergContWPhone,
				Per.EmergContRelToPer,
				CONCAT(Per.FirstName, ' ', Per.MiddleName, ' ', Per.LastName) AS FullName
				FROM Employee Emp
				LEFT OUTER JOIN Person Per ON Per.PerID=Emp.PerID 
				ORDER BY Per.FirstName ASC
			");

			//Header 
			$html .= "Report Created: " . date('d F Y');

			//Content
			$html .= "<h4>Employee Contact Details</h4>";
			$html .= "<table><tbody>";
			$html .= "<tr><th>Employee Name</th><th>Telephone</th><th>Mobile</th><th>Work Mob.</th><th>Email</th><th>Work Email</th><th>Emergency Contact</th><th>EC - Mobile</th><th>EC - Telephone</th><th>EC - Work Phone</th><th>EC - Relationship</th></tr>";

			
			for($i = 0; $i < 25; $i++){
			//Loop
			foreach($emp as $em) { 
				$html .= "<tr><td>" . $em->FullName . "</td><td>" . $em->HomePhone . "</td><td>" . $em->Mobile . "</td><td>" . $em->WorkMob . "</td><td>" . $em->PersonalEmail . "</td><td>" . $em->WorkEmail . "</td><td>" . $em->EmergContName . "</td><td>" . $em->EmergContMob . "</td><td>" . $em->EmergContHPhone . "</td><td>" . $em->EmergContWPhone . "</td><td>" . $em->EmergContRelToPer . "</td></tr>";
			}
			}
			$html .= "</tbody></table>";
			
		}
		//Report for Contact Details - All Volunteers
		elseif ($reportType == "cdet_vol") {
			$vol = $this->Extended_generic_model->return_query(" SELECT
				Per.PersonalEmail,
				Per.Mobile,
				Per.HomePhone,
				Per.EmergContName,
				Per.EmergContMob,
				Per.EmergContHPhone,
				Per.EmergContWPhone,
				Per.EmergContRelToPer,
				CONCAT(Per.FirstName, ' ', Per.MiddleName, ' ', Per.LastName) AS FullName
				FROM Volunteer Vol
				LEFT OUTER JOIN Person Per ON Per.PerID=Vol.PerID 
				ORDER BY Per.FirstName ASC
			");

			//Header 
			$html .= "Report Created: " . date('d F Y');

			//Content
			$html .= "<h4>Volunteer Contact Details</h4>";
			$html .= "<table><tbody>";
			$html .= "<tr><th>Volunteer Name</th><th>Telephone</th><th>Mobile</th><th>Email</th><th>Emergency Contact</th><th>EC - Mobile</th><th>EC - Telephone</th><th>EC - Work Phone</th><th>EC - Relationship</th></tr>";

			//Loop
			foreach($vol as $vo) { 
				$html .= "<tr><td>" . $vo->FullName . "</td><td>" . $vo->HomePhone . "</td><td>" . $vo->Mobile . "</td><td>" . $vo->PersonalEmail . "</td><td>" . $vo->EmergContName . "</td><td>" . $vo->EmergContMob . "</td><td>" . $vo->EmergContHPhone . "</td><td>" . $vo->EmergContWPhone . "</td><td>" . $vo->EmergContRelToPer . "</td></tr>";
			}

			$html .= "</tbody></table>";
		}
		//Report for Contract and WWC - Status Review
		elseif ($reportType == "cont_srv") {
			$ppl = $this->Extended_generic_model->return_query("SELECT
				CONCAT(FirstName, ' ', MiddleName, ' ', LastName) AS FullName,
				ContractSigned,
				PaperworkCompleted,
				WWC,
				WWCFiled,
				PoliceCheck,
				TeacherRegCheck,
				PerID
				FROM Person
				ORDER BY FirstName ASC
			");


			//Header 
			$html .= "Report Created: " . date('d F Y');

			//Content
			$html .= "<h4>Contract & WWC - Status Review</h4>";
			$html .= "<table><tbody>";
			$html .= "<tr><th>Full Name</th><th>Employee/Volunteer</th><th>Contract Signed</th><th>Paperwork Completed</th><th>WWC</th><th>WWC Filed</th><th>Police Check</th><th>Teacher Registration Check</th></tr>";

			//Loop
			foreach($ppl as $pp) { 

				//Determine whether Person is Volunteer, Employee, Both or Neither
				$checkEmp = $this->db->query("SELECT COUNT(*) as Count FROM Employee WHERE PerID='".$pp->PerID."'")->row_array();

				if($checkEmp['Count'] > 0) {
					$isEmp = true;
				} else {
					$isEmp = false;
				}

				$checkVol = $this->db->query("SELECT COUNT(*) as Count FROM Volunteer WHERE PerID='".$pp->PerID."'")->row_array();

				if($checkVol['Count'] > 0) {
					$isVol = true;
				} else {
					$isVol = false;
				}

				if($isEmp == true && $isVol == false) {
					$empVol = "Employee";
				} elseif($isEmp == false && $isVol == true) {
					$empVol = "Volunteer";
				} elseif ($isEmp == false && $isVol == false) {
					$empVol = "N/A";
				} else {
					$empVol = "Both";
				}

				//Prettify Report
				if($pp->ContractSigned == 0) {
					$cs = "No";
				} else {
					$cs = "Yes";
				}

				if($pp->PaperworkCompleted == 0) {
					$pc = "No";
				} else {
					$pc = "Yes";
				}	

				if($pp->WWC == 0) {
					$wwc = "No";
				} else {
					$wwc = "Yes";
				}	

				if($pp->WWCFiled == 0) {
					$wwcf = "No";
				} else {
					$wwcf = "Yes";
				}	

				if($pp->PoliceCheck == 0) {
					$polc = "No";
				} else {
					$polc = "Yes";
				}	

				if($pp->TeacherRegCheck == 0) {
					$trc = "No";
				} else {
					$trc = "Yes";
				}	


				$html .= "<tr><td>" . $pp->FullName . "</td><td>" . $empVol . "</td><td>" . $cs . "</td><td>" .  $pc . "</td><td>" .  $wwc . "</td><td>" .  $wwcf . "</td><td>" .  $polc . "</td><td>" .  $trc . "</td></tr>";
			}

			$html .= "</tbody></table>";
		}

		$html .= $this->load->view('/include/Report_EndHTML', $data, TRUE);

		//echo $html;
		$this->pdf = $this->m_pdf->load("'utf-8', 'A4-L', '', '',10,10,10,10,6,3");
		if(isset($coverPage) && $coverPage !== "") {
			$this->pdf->AddPage('P', // L - landscape, P - portrait
	        '', '', '', '',
	        0, // margin_left
	        0, // margin right
	        0, // margin top
	        0, // margin bottom
	        6, // margin header
	        3); // margin footer
			$this->pdf->WriteHTML($coverPage);
		}
		$this->pdf->AddPage('L', // L - landscape, P - portrait
        '', '', '', '',
        15, // margin_left
        15, // margin right
        15, // margin top
        15, // margin bottom
        6, // margin header
        3); // margin footer
		$this->pdf->WriteHTML($html);
		$this->pdf->Output(FCPATH . $pdfFilePath . $FileStorageName, "F");


		//Save File to Database
		$this->db->query("INSERT INTO Files(Title, Name, Directory, FileName, TempName, CreatedOn, Extension, CreatedBy, Type) VALUES ('".$title."', '".$title."', '$pdfFilePath', '".$fileName."', '$FileStorageName', now(), 'pdf', '" . $_SESSION['session_user']['bms_psnid'] ."', 'REPORT')");

		$this->viewReport($viewPath);

	}

	function printReimbursement($id) {
		$data = array();
		$data["titleLine_One"] = 'Reimbursement Report';
		$data["titleLine_Two"] = date('Y');
		$data["today_date"] = date('d F Y');
		$data["today_year"] = date('Y');
		$data["name"] = $_SESSION["session_user"]["bms_psnfullName"];
		$data["prjName"] = "N/A";		
		$data["ReimbursementID"] = $id;

		$cp = $this->uri->segment(5);


		$pdfFilePath = "assets/public/Reports/";
		$FileStorageName = uniqid(md5(rand())) . '-' . time() . '.pdf';
		$viewPath = base_url() . $pdfFilePath . $FileStorageName;


		if($cp == 0) {
			$html = "";
		} else {
			$html = $this->load->view('/include/Report_CoverPage', $data, TRUE);	
		}

		

		$title = $data["titleLine_One"] . ' ' . $data["titleLine_Two"] . ' - #' . $id;
		$fileName = $title . '.pdf';

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
		$html .= "<tr><th>Name</th><th>Company Name</th><th>Reason</th><th>Total Amount</th><th>GST</th><th>Type</th><th>Project</th></tr>";
		
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
		$this->pdf->Output(FCPATH . $pdfFilePath . $FileStorageName, "F");


		//Save File to Database
		$this->db->query("INSERT INTO Files(Title, Name, Directory, FileName, TempName, CreatedOn, Extension, CreatedBy, Type) VALUES ('".$title."', '".$title."', '$pdfFilePath', '".$fileName."', '$FileStorageName', now(), 'pdf', '" . $_SESSION['session_user']['bms_psnid'] ."', 'REPORT')");


		$this->viewReport($viewPath);
	}
}