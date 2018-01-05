<?php

class ValidateAuthorize extends CI_Controller {

    protected $table;
    public $current_user;
    public $get_fnc;
    public $current_url;
    protected $APILOGIN;
    protected $TransactionKey;
    protected $Endpoint;

    public function __construct() {
        parent::__construct();
        parent::__construct();
        $this->load->helper(array('form', 'url', 'cookie'));
        $this->load->model(array('admin_manager'));
        $this->table = array('admin', 'users', 'api', 'disputes', 'transaction', 'alert', 'merchant', 'charge_back', 'csv_template');
        $this->load->library(array('form_validation', 'email'));
        $this->current_user = (!empty($this->session->userdata('admin_Id'))) ? $this->session->userdata('admin_Id') : "";
        $this->get_fnc = get_instance();
        $this->current_url = $this->uri->segment(2);
        $this->APILOGIN = '5W3Wd9kL';
        $this->TransactionKey = '7w4L56qarV65RpYW';
        $this->Endpoint = 'https://apitest.authorize.net/xml/v1/request.api';
    }
    
    

}
