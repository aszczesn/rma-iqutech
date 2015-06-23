<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Partnumbers extends MX_Controller
{
    function __construct() {
        parent::__construct();
    }

    function get($order_by) {
        $this->load->model('mdl_partnumbers');
        $query = $this->mdl_partnumbers->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
        $this->load->model('mdl_partnumbers');
        $query = $this->mdl_partnumbers->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id) {
        $this->load->model('mdl_partnumbers');
        $query = $this->mdl_partnumbers->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model('mdl_partnumbers');
        $query = $this->mdl_partnumbers->get_where_custom($col, $value);
        return $query;
    }

    function _insert($data) {
        $this->load->model('mdl_partnumbers');
        $this->mdl_partnumbers->_insert($data);
    }

    function _update($id, $data) {
        $this->load->model('mdl_partnumbers');
        $this->mdl_partnumbers->_update($id, $data);
    }

    function _delete($id) {
        $this->load->model('mdl_partnumbers');
        $this->mdl_partnumbers->_delete($id);
    }

    function count_where($column, $value) {
        $this->load->model('mdl_partnumbers');
        $count = $this->mdl_partnumbers->count_where($column, $value);
        return $count;
    }

    function get_max() {
        $this->load->model('mdl_partnumbers');
        $max_id = $this->mdl_partnumbers->get_max();
        return $max_id;
    }

    function _custom_query($mysql_query) {
        $this->load->model('mdl_partnumbers');
        $query = $this->mdl_partnumbers->_custom_query($mysql_query);
        return $query;
    }
}
