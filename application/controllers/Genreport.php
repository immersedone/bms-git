<?php

class Genreport extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('url');

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

		$pdfFilePath = "/home/immersed/www/Report.pdf";

		$html = $this->load->view('/include/Report_CoverPage', $data, TRUE);

		echo $html;
		$this->pdf = $this->m_pdf->load('utf-8', 'A4');
		$this->pdf->WriteHTML($html);
		$this->pdf->Output($pdfFilePath, "F");

	}
}