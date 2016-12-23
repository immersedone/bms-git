<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fundingbody extends MY_Controller {
    public function __construct()
    {
        parent::__construct();


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
        $crud->set_subject('Funding Body');
        $crud->basic_model->set_query_str('SELECT * FROM (SELECT CONCAT(S.Postcode, " - ", S.SuburbName) as SubName, F.* FROM FundingBody F
		LEFT OUTER JOIN Suburb S on S.SuburbID = F.Location) x');
        $crud->display_as('Location', 'Suburb');
        $crud->display_as('BodyName', 'Funding Body Name');
        $crud->display_as('ContactPhone', 'Contact Phone Number');
        $crud->display_as('ContactMob', 'Contact Mobile Number');
        $crud->display_as('ContactEmail', 'Contact Email');
        $crud->display_as('ContactName', 'Contact Name');
	
		$crud->columns('BodyName', 'Location', 'Address', 'ContactPhone', 'URL', 'Comments');
		$crud->add_fields('BodyName', 'Location', 'Address', 'ContactPhone', 'ContactMob', 'ContactEmail', 'ContactName', 'URL', 'Comments');
		$crud->edit_fields('BodyName', 'Location', 'Address', 'ContactPhone', 'ContactMob', 'ContactEmail', 'ContactName', 'URL', 'Comments');
		$crud->field_type('Comments', 'text');
		
		$crud->required_fields(
		'BodyName',
		'SubName',
		'Address', 
		'ContactPhone' 
		);
		
		
		
		
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