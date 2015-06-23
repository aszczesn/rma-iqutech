<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Diy_customer extends MX_Controller
{
    function __construct() {
        parent::__construct();
        $this->load->model('diy_customer_type/mdl_diy_customer_type');
        $this->load->library(array('auth/ion_auth', 'form_validation'));
	$this->load->helper(array('url', 'language', 'form'));
    }
    
    function index(){
        if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
        }
        elseif (!$this->ion_auth->is_admin() || !$this->ion_auth->in_group('operator', TRUE)) 
        {
            $this->session->set_flashdata('message', 'You must be an admin to view this page');
            redirect('/');
        }
        else
        {
            //list the customers
            $data['customers'] = $this->get('id');

            foreach ($data['customers'] as $k => $customer)
            {
                $data['customers'][$k]->groups = 
                        $this->mdl_diy_customer_type->get_where($customer->categoryid)->result();
            }            

            $data['module'] = 'diy_customer';
            $data['view_file'] = 'index.php';
            echo Modules::run('template/admin', $data);
        }
    }
    
    function create_customer(){
        $data['title'] = 'Create customer';
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('auth', 'refresh');
        }
        
        //validate form input
        $this->form_validation->set_rules('shortname', 'Shortname', 'required');
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'required');
        $this->form_validation->set_rules('catgoryid', 'Category', 'required');

        if ($this->form_validation->run() == true)
        {
            $email    = strtolower($this->input->post('email'));

            $additional_data = array(
                    'shortname' => $this->input->post('shortname'),
                    'name'  => $this->input->post('name'),
                    'categoryid'    => $this->input->post('categoryid'),
                    'phone'      => $this->input->post('phone'),
            );
        }
        if ($this->form_validation->run() == true 
                && $this->ion_auth->register(
                        $username, 
                        $password, 
                        $email, 
                        $additional_data))
        {
            //check to see if we are creating the user
            //redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth", 'refresh');
        }
        else
        {
            //display the create user form
            //set the flash data error message if there is one
            $data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $data['shortname'] = array(
                    'name'  => 'shortname',
                    'id'    => 'shortname',
                    'type'  => 'text',
                    'value' => $this->form_validation->set_value('shortname'),
            );
            $data['name'] = array(
                    'name'  => 'name',
                    'id'    => 'name',
                    'type'  => 'text',
                    'value' => $this->form_validation->set_value('name'),
            );
            $data['email'] = array(
                    'name'  => 'email',
                    'id'    => 'email',
                    'type'  => 'text',
                    'value' => $this->form_validation->set_value('email'),
            );
            $data['categoryid'] = array(
                    'name'  => 'categoryid',
                    'id'    => 'categoryid',
                    'type'  => 'text',
                    'value' => $this->form_validation->set_value('categoryid'),
            );
            $data['phone'] = array(
                    'name'  => 'phone',
                    'id'    => 'phone',
                    'type'  => 'text',
                    'value' => $this->form_validation->set_value('phone'),
            );
            $data['address'] = array(
                    'name'  => 'address',
                    'id'    => 'address',
                    'type'  => 'text',
                    'value' => $this->form_validation->set_value('address'),
            );
            $data['city'] = array(
                    'name'  => 'city',
                    'id'    => 'city',
                    'type'  => 'text',
                    'value' => $this->form_validation->set_value('city'),
            );
            $data['zip'] = array(
                    'name'  => 'zip',
                    'id'    => 'zip',
                    'type'  => 'text',
                    'value' => $this->form_validation->set_value('zip'),
            );
            $data['country'] = array(
                    'name'  => 'country',
                    'id'    => 'country',
                    'type'  => 'text',
                    'value' => $this->form_validation->set_value('country'),
            );
            
            $this->_render_page('diy_customer/create_customer', $data);
        }
               
    }
    
    function get($order_by) {
        $this->load->model('mdl_diy_customer');
        $query = $this->mdl_diy_customer->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
        $this->load->model('mdl_diy_customer');
        $query = $this->mdl_diy_customer->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id) {
        $this->load->model('mdl_diy_customer');
        $query = $this->mdl_diy_customer->get_where($id);
        var_dump($query);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model('mdl_diy_customer');
        $query = $this->mdl_diy_customer->get_where_custom($col, $value);
        return $query;
    }

    function _insert($data) {
        $this->load->model('mdl_diy_customer');
        $this->mdl_diy_customer->_insert($data);
    }

    function _update($id, $data) {
        $this->load->model('mdl_diy_customer');
        $this->mdl_diy_customer->_update($id, $data);
    }

    function _delete($id) {
        $this->load->model('mdl_diy_customer');
        $this->mdl_diy_customer->_delete($id);
    }

    function count_where($column, $value) {
        $this->load->model('mdl_diy_customer');
        $count = $this->mdl_diy_customer->count_where($column, $value);
        return $count;
    }

    function get_max() {
        $this->load->model('mdl_diy_customer');
        $max_id = $this->mdl_diy_customer->get_max();
        return $max_id;
    }

    function _custom_query($mysql_query) {
        $this->load->model('mdl_diy_customer');
        $query = $this->mdl_diy_customer->_custom_query($mysql_query);
        return $query;
    }
    
    function _render_page($view, $data=null, $render=false)
    {
        $temp = explode('/', $view);
        
        $data['module'] = $temp[0];
        $data['view_file'] = $temp[1].'.php';

        if (!$render) 
        {
            echo Modules::run('template/admin', $data);
        }
    }
}
