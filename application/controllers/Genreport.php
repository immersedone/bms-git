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
		$data["titleLine_One"] = $_POST['title_one'];
		$data["titleLine_Two"] = $_POST['title_two'];
		$data["today_date"] = date('d F Y');
		$data["today_year"] = date('Y');
		$data["name"] = "Jaime de Loma-Osorio Ricon";
		$data["prjName"] = $this->Genreport_model->getProjectName($projectID);

		$pdfFilePath = "assets/public/Reports/Report.pdf";
		$viewPath = base_url() . $pdfFilePath;

		$html = $this->load->view('/include/Report_CoverPage', $data, TRUE);

		$this->load->library("grocery_CRUD");
		$this->load->model("Grocery_crud_model");
		$this->load->model("Extended_generic_model");

		$people = $this->Extended_generic_model->return_query('SELECT Proj.Name, Proj.ProjID, `PersonProject`.Role as ProjRole, CONCAT(FirstName, " ", MiddleName, " ", LastName) as FullName, Sub.SuburbName as SubName, 
		Sub.Postcode as Postcode, Per.* FROM `Person` Per 
		LEFT OUTER JOIN `PersonProject` ON Per.PerID=`PersonProject`.PerID 
		LEFT OUTER JOIN `Project` Proj ON `PersonProject`.ProjID=Proj.ProjID 
		LEFT OUTER JOIN `Suburb` Sub ON Sub.SuburbID=Per.SuburbID 
		WHERE `PersonProject`.Role="Employee" AND `PersonProject`.ProjID='.$projectID);
		
		$html .= "<table><tbody>";
		$html .= "<tr><th>Full Name</th><th>Address</th><th>Postcode</th><th>Suburb</th><th>Work Email</th><th>Personal Email</th><th>Mobile</th><th>Home Phone</th></tr>";
		foreach($people as $ppl) {
			$html .= "<tr><td> " . $ppl->FullName . "</td><td> " . $ppl->Address . "</td><td> " . $ppl->Postcode . "</td><td> " . $ppl->SubName . "</td><td> " . $ppl->WorkEmail . "</td><td> " . $ppl->PersonalEmail . "</td><td> " . $ppl->Mobile . "</td><td> " . $ppl->HomePhone . "</td></tr>";
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