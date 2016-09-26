<?php
class Fundingbody extends CI_Controller {
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');

        $this->load->library('grocery_CRUD');
    }

    public function index()
    {
        //$this->render((object)array('output' => '' , 'js_files' => array() , 'css_files' => array()));
        $this->fundingbody();
    }

    public function render($output = null) {
        $this->load->view('fundingbody', $output);
    }

    public function fundingbody() {

        $crud = new grocery_CRUD();
        $crud->set_model('Extended_generic_model');
        $crud->set_table('FundingBody');
        $crud->set_subject('FundingBody');
        $crud->basic_model->set_query_str('SELECT CONCAT(S.Postcode, " - ", S.SuburbName) as SubName, F.* FROM FundingBody F
		LEFT OUTER JOIN Suburb S on S.SuburbID = F.Location');
        $crud->display_as('SubName', 'Suburb');
        $crud->display_as('BodyName', 'Body Name');
	
		$crud->columns('BodyName','SubName','Address','URL','Comments');
		$crud->field_type('Comments', 'text');
		
		$suburbs = $crud->basic_model->return_query("SELECT SuburbID, CONCAT(Postcode, ' - ', SuburbName) as FullSub FROM Suburb");
		
		$subArr = array();
		foreach($suburbs as $sub) {
			$subArr += [$sub->SuburbID => $sub->FullSub];
		}
		$crud->field_type("Location", "dropdown", $subArr);
		
			
        $output = $crud->render();
        $this->render($output);
        }


}
?>