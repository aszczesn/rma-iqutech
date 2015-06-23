<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Template extends MX_Controller {
    function __construct(){
        parent::__construct();
        $this->load->library('auth/ion_auth');
    }
    
    public function admin($data = ""){
        if (!$this->ion_auth->is_admin())
        {
            $this->session->set_flashdata('message', 'You must be an admin to view this page');
            redirect('auth/login');
        }
        $this->load->view('layouts/admin_dashboard', $data);
    }
    
    public function one_col($data = ""){
        $this->load->view('header');
        $this->load->view('navigation');
        $this->load->view('layouts/one_col', $data);
        $this->load->view('footer');
    }
    
    public function operator_two_col($data = ""){
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth/login');
        }
        $this->load->view('header');
        $this->load->view('navigation');
        $this->load->view('layouts/operator_two_col', $data);
        $this->load->view('footer');
    }

    public function login($data = ""){
   	$this->load->view('layouts/login', $data);
    }
}
