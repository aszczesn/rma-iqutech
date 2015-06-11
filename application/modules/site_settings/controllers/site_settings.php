<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Site_settings extends MX_Controller
{
    function __construct() {
        parent::__construct();
    }

    function get_max_category_depth(){
        $max_depth = 3;
        return $max_depth;
    }
    
    function get_site_name(){
        $site_name = "Albert Szczesny";
        return $site_name;
    }
    
    function get_owner_name(){
        $name = "Albert";
        return $name;
    }
    
    function get_owner_email(){
        $email = "albert.szczesny@gmail.com";
        return $email;
    }
    function get_currency(){
        $currency = "&euro;";
        return $currency;
    }
}
