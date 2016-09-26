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
        $crud->basic_model->set_query_str('SELECT * FROM FundingBody');
        $crud->display_as('BodyName', 'Body Name');

        $output = $crud->render();
        $this->render($output);
        }


}
?>