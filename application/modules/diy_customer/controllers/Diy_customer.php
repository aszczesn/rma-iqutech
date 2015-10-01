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
    
    //edit a customer
    function edit_customer($id)
    {
        $data['title'] = "Edit Customer";

        if(!$this->ion_auth->logged_in())
        {
            redirect('auth', 'refresh');
        }

        $customer = $this->get_where($id)->row();

        $groups = $this->ion_auth->groups()->result_array();
        $currentGroups = $this->ion_auth->get_users_groups($id)->result();

        //validate form input
        $this->form_validation->set_rules(
            'first_name', 
            $this->lang->line(
                'edit_user_validation_fname_label'
            ), 
            'required');
        $this->form_validation->set_rules(
            'last_name', 
            $this->lang->line(
                'edit_user_validation_lname_label'
            ),
            'required');
        $this->form_validation->set_rules(
            'phone', 
            $this->lang->line(
                'edit_user_validation_phone_label'
            ), 
            'required');
        $this->form_validation->set_rules(
            'company', 
            $this->lang->line(
                'edit_user_validation_company_label'
                ), 
            'required');

        if (isset($_POST) && !empty($_POST))
        {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE ||
                    $id != $this->input->post('id'))
            {
                show_error($this->lang->line('error_csrf'));
            }
            
            //update the password if it was posted
            if (!empty($this->input->post('password')))
            {                
                $this->form_validation->set_rules(
                    'password', 
                    $this->lang->line(
                        'edit_user_validation_password_label'
                    ),
                    'required|min_length[' . $this->config->item(
                        'min_password_length',
                        'ion_auth'
                    ) . ']|max_length[' . $this->config->item(
                        'max_password_length',
                        'ion_auth'
                    ) . ']|matches[password_confirm]');
                $this->form_validation->set_rules(
                    'password_confirm', 
                    $this->lang->line(
                        'edit_user_validation_password_confirm_label'
                    ), 
                    'required');
            }

            if($this->form_validation->run() === TRUE)
            {
                $data = array(
                        'first_name' => $this->input->post('first_name'),
                        'last_name'  => $this->input->post('last_name'),
                        'company'    => $this->input->post('company'),
                        'phone'      => $this->input->post('phone'),
                );

                //update the password if it was posted
                if($this->input->post('password'))
                {
                    $data['password'] = $this->input->post('password');
                }

                // Only allow updating groups if user is admin
                if ($this->ion_auth->is_admin())
                {
                    //Update the groups user belongs to
                    $groupData = $this->input->post('groups');

                    if (isset($groupData) && !empty($groupData)) {

                        $this->ion_auth->remove_from_group('', $id);

                        foreach ($groupData as $grp) {
                            $this->ion_auth->add_to_group($grp, $id);
                        }
                    }
                }

                //check to see if we are updating the user
                if($this->ion_auth->update($user->id, $data))
                {
                    //redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->ion_auth->messages() );
                    if($this->ion_auth->is_admin())
                    {
                        redirect('auth', 'refresh');
                    }
                    else
                    {
                        redirect('/', 'refresh');
                    }

                }
                else
                {
                    //redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->ion_auth->errors() );
                    if ($this->ion_auth->is_admin())
                    {
                        redirect('auth', 'refresh');
                    }
                    else
                    {
                        redirect('/', 'refresh');
                    }

                }

            }
        }

        //display the edit user form
        $data['csrf'] = $this->_get_csrf_nonce();

        //set the flash data error message if there is one
        $data['message'] = (validation_errors() 
                ? validation_errors() 
                : ($this->ion_auth->errors() 
                        ? $this->ion_auth->errors() 
                        : $this->session->flashdata('message')));

        //pass the user to the view
        $data['user'] = $user;
        $data['groups'] = $groups;
        $data['currentGroups'] = $currentGroups;

        $data['first_name'] = array(
                'name'  => 'first_name',
                'id'    => 'first_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('first_name', $user->first_name),
        );
        $data['last_name'] = array(
                'name'  => 'last_name',
                'id'    => 'last_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('last_name', $user->last_name),
        );
        $data['company'] = array(
                'name'  => 'company',
                'id'    => 'company',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('company', $user->company),
        );
        $data['phone'] = array(
                'name'  => 'phone',
                'id'    => 'phone',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('phone', $user->phone),
        );
        $data['password'] = array(
                'name' => 'password',
                'id'   => 'password',
                'type' => 'password'
        );
        $data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id'   => 'password_confirm',
                'type' => 'password'
        );
        #$this->_render_page('auth/edit_user', $data);
        $data['module'] = 'auth';
        $data['view_file'] = 'edit_user.php';
        echo Modules::run('template/admin', $data);
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
