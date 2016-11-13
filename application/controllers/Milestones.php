<?php

class Milestones extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->helper('cookie');
		$this->load->helper('url');

		$this->load->library('grocery_CRUD');
	}

	public function index()
	{
		//$this->render((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
		$this->milestones();
	}

	public function render($output = null) {
		$this->load->view('milestones', $output);
	}

	public function milestones() {

		$crud = new grocery_CRUD();
		$crud->set_model('Extended_generic_model'); 
		$crud->set_table('Milestone_new');
		$crud->set_subject('Milestone');
		$crud->basic_model->set_query_str('SELECT * FROM (SELECT P.Name as ProjName, M.* from `Milestone_new` M
		LEFT OUTER JOIN `Project` P on M.ProjID=P.ProjID) x');
		$crud->columns('ProjName', 'ShortDesc', 'DueDate', 'RptType', 'ReportIsDue', 'PaymentMode', 'Status', 'Amount', 'Comment', 'FilePath');

		$crud->display_as('ProjID', 'Project Name');
		$crud->display_as('ProjName', 'Project Name');
		$crud->display_as('ShortDesc', 'Description');
		$crud->display_as('DueDate', 'Due Date');
		$crud->display_as('RptType', ' Report Type');
		$crud->display_as('FilePath', 'File Attached');
		$crud->display_as('MSComplete', 'Complete');
		$crud->display_as('ReportIsDue', 'Report Is Due');
		$crud->display_as('PaymentMode', 'Payment Mode');

		$crud->add_fields('ProjID', 'ShortDesc', 'DueDate', 'RptType', 'ReportIsDue', 'PaymentMode', 'Status', 'Amount', 'Comment', 'FilePath');

		$crud->callback_column('MSComplete', array($this, 'check_complete'));
		//$crud->callback_column('MSComplete', array($this, 'field_width'));

		//Prettify Report Type
		$rptType = $crud->basic_model->return_query("SHOW COLUMNS FROM Milestone_new WHERE Field='RptType'");
		preg_match("/^enum\(\'(.*)\'\)$/", $rptType[0]->Type, $matchesRpt);
		$rptTypeArr = explode("','", $matchesRpt[1]);
		$newRptTypeArr = array();
		foreach($rptTypeArr as $rpt) {
			if($rpt==="REPORT") {
				$newRptTypeArr += [$rpt => "Report"];
			} else if($rpt==="PAYMENT") {
				$newRptTypeArr += [$rpt => "Payment"];
			} else if($rpt==="REP_PAY") {
				$newRptTypeArr += [$rpt => "Report &amp; Payment"];
			} else if($rpt==="FINAL_REP") {
				$newRptTypeArr += [$rpt => "Final Report"];
			}
		}


		$crud->field_type('RptType', 'dropdown', $newRptTypeArr);

		//Prettify Payment Mode
		$payM = $crud->basic_model->return_query("SHOW COLUMNS FROM Milestone_new WHERE Field='PaymentMode'");

		preg_match("/^enum\(\'(.*)\'\)$/", $payM[0]->Type, $matches);
		$payMArr = explode("','", $matches[1]);
		$newpayMArr = array();
		foreach($payMArr as $contPayM) {
			if($contPayM==="AUTOMATIC") {
				$newpayMArr += [$contPayM => "Automatic Payment"];
			} else if($contPayM==="MANUAL") {
				$newpayMArr += [$contPayM => "Manual Payment"];
			}
		}


		$crud->field_type('PaymentMode', 'dropdown', $newpayMArr);

		

		$crud->set_field_upload('FilePath', 'assets/uploads/files/');

		$projects = $crud->basic_model->return_query("SELECT ProjID, Name FROM Project");

		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->Name];
		}
		//$rptArr = array("Report", "Payment", "Report & Payment", "Final Payment");
				
		$crud->field_type("DueDate", 'datetime');
		$crud->field_type("Comment", 'text');
		//$crud->field_type("RptType", "enum", $rptArr);
		$crud->field_type("ProjID", "dropdown", $prjArr);
		$crud->field_type("ProjName", "dropdown", $prjArr);
		
		$output = $crud->render();

		$this->render($output);
	}

	public function mileproj($id) {

		$crud = new grocery_CRUD();
		$crud->set_model('Extended_generic_model'); 
		$crud->set_table('Milestone_new');
		$crud->set_subject('Milestone');
		$crud->basic_model->set_query_str('SELECT * FROM (SELECT P.Name as ProjName, M.* from `Milestone_new` M
		LEFT OUTER JOIN `Project` P on M.ProjID=P.ProjID WHERE M.ProjID="'. $id .'") x');
			
		$crud->columns('ShortDesc', 'DueDate', 'RptType', 'Amount');
		$crud->display_as('ProjID', 'Project Name');
		$crud->display_as('ProjName', 'Project Name');
		$crud->display_as('ShortDesc', 'Description');
		$crud->display_as('DueDate', 'Due Date');
		$crud->display_as('RptType', 'Type');
		$crud->display_as('MSComplete', 'Complete');
		$crud->add_fields('ProjID', 'ShortDesc', 'DueDate', 'RptType', 'Amount', 'Comment', 'FilePath');

		$state = $crud->getState();

		$projects = $crud->basic_model->return_query("SELECT Pr.ProjID, Pr.Name FROM Project Pr LEFT OUTER JOIN Milestone_new Ms ON Ms.ProjID=Pr.ProjID WHERE Ms.milestoneID=".$id);

		$prjArr = array();
		foreach($projects as $prj) {
			$prjArr += [$prj->ProjID => $prj->Name];
		}


		if($state === "edit" || $state === "update") {
			$crud->callback_edit_field("ProjID", array($this, 'callback_projID_edit'));
		} else if ($state === "add" || $state === "insert") {
			$crud->callback_add_field("ProjID", function() {
				$id = get_cookie("projID");
				//echo $id;
				$q = $this->db->query('SELECT Name FROM Project WHERE ProjID="' . $id .'" LIMIT 1')->row();
				$readOnly = '<div id="field-ProjID" class="readonly_label">' . $q->Name .'</div>';
				return $readOnly. '<input id="field-ProjID" name="ProjID" type="text" value="' . $id . '" class="numeric form-control" maxlength="255" style="display:none;">';
			});
		} else {
			$crud->field_type("ProjID", "dropdown", $prjArr);
		}

		//print_r($projects);
		//echo $id;


		//Prettify Report Type
		$rptType = $crud->basic_model->return_query("SHOW COLUMNS FROM Milestone_new WHERE Field='RptType'");
		preg_match("/^enum\(\'(.*)\'\)$/", $rptType[0]->Type, $matchesRpt);
		$rptTypeArr = explode("','", $matchesRpt[1]);
		$newRptTypeArr = array();
		foreach($rptTypeArr as $rpt) {
			if($rpt==="REPORT") {
				$newRptTypeArr += [$rpt => "Report"];
			} else if($rpt==="PAYMENT") {
				$newRptTypeArr += [$rpt => "Payment"];
			} else if($rpt==="REP_PAY") {
				$newRptTypeArr += [$rpt => "Report &amp; Payment"];
			} else if($rpt==="FINAL_REP") {
				$newRptTypeArr += [$rpt => "Final Report"];
			}
		}


		$crud->field_type('RptType', 'dropdown', $newRptTypeArr);
		
		//$rptArr = array("Report", "Payment", "Report & Payment", "Final Payment");
		$crud->field_type("DueDate", 'datetime');
		$crud->field_type("Comment", 'text');
		//$crud->field_type("RptType", "enum", $rptArr);

		$crud->set_field_upload('FilePath', 'assets/uploads/files/');
		
		
		
		$output = $crud->render();

		$this->render($output);
	}

	public function callback_projID_edit($value, $primary_key) {
		$q = $this->db->query('SELECT Name FROM Project WHERE ProjID="' . $value .'" LIMIT 1')->row();
		//$projName = array_shift($q->result_array());
		$readOnly = '<div id="field-ProjID" class="readonly_label">' . $q->Name .'</div>';
		return $readOnly . '<input id="field-ProjID" name="ProjID" type="text" value="' . $value . '" class="numeric form-control" maxlength="255" style="display:none;">';
	}

	function milestone_add($post_array) {
		
		$this->mile_insert($post_array);
	}

	function check_complete($value, $row) {
		return "<input type='checkbox' name='MSComplete'>";
	}

	function projectName($value, $row) {

		$crud = new grocery_CRUD();
		$crud->set_model('Extended_generic_model');

		$projects = $crud->basic_model->return_query("SELECT ProjID, Name FROM Project WHERE ProjID=".$id);

		$prjArr = array();
		foreach($projects as $prj) {
			if($prj->ProjID === $value) {
				$projectName = $prj->Name;
			}
		}
		return $projectName;
	}
/*
	function field_width($value, $row) {
		return "wordwrap($row->MSComplete, 50, "", true)";
	}
*/
	public function mile_insert() {

		//Initialise and assign variables
		$ProjID = $_POST['ProjID'];
		$RptType = $_POST['RptType'];
		$Amount = $_POST['Amount'];
		$Comment = $_POST['Comment'];
		$DueDate = $_POST['DueDate'];
		$ShortDesc = $_POST['ShortDesc'];

		//Redundant (Old DB Table)
		/*$Title = $_POST['Title'];
		$Description = $_POST['Description'];
		$StartDate = $_POST['StartDate'];
		$FinishDate = $_POST['FinishDate'];*/

		$crud = new grocery_CRUD();
		$crud->set_model('Milestone_GC');
		$resp = $crud->basic_model->insert_mile($ProjID, $DueDate, $RptType, $ShortDesc, $Amount, $Comment);
		echo $resp;
	}

	public function crud_delete_file($primary_key)
	{
    	$row = $this->db->where('id',$primary_key)->get('Milestones')->row();
    	unlink('assets/uploads/files/'.$row->file_url);
   
    	return true;
	}
}