<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Site_security extends MX_Controller
{
    function __construct() {
        parent::__construct();
    }
    
    function check_is_admin($username){
        $query = Modules::run('user/get_where_custom', 'username', $username);
        $row = $query->row();
        
        if ($row->role == 'admin') {
            return TRUE;
        }else {
            return FALSE;
        }
    }
    
    function make_hash($password){
        $safepassword = $this->encrypt->encode($password);
        echo $safepassword;
        return $safepassword;
    }
    function unmake_hash($password){
        $de_password = $this->encrypt->decode($password);
        return $de_password;
    }
}
