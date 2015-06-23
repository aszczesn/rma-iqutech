<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Reciving extends MX_Controller
{
    function __construct() {
        parent::__construct();
    }

    function get($order_by) {
        $this->load->model('mdl_reciving');
        $query = $this->mdl_reciving->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
        $this->load->model('mdl_reciving');
        $query = $this->mdl_reciving->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id) {
        $this->load->model('mdl_reciving');
        $query = $this->mdl_reciving->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model('mdl_reciving');
        $query = $this->mdl_reciving->get_where_custom($col, $value);
        return $query;
    }

    function _insert($data) {
        $this->load->model('mdl_reciving');
        $this->mdl_reciving->_insert($data);
    }

    function _update($id, $data) {
        $this->load->model('mdl_reciving');
        $this->mdl_reciving->_update($id, $data);
    }

    function _delete($id) {
        $this->load->model('mdl_reciving');
        $this->mdl_reciving->_delete($id);
    }

    function count_where($column, $value) {
        $this->load->model('mdl_reciving');
        $count = $this->mdl_reciving->count_where($column, $value);
        return $count;
    }

    function get_max() {
        $this->load->model('mdl_reciving');
        $max_id = $this->mdl_reciving->get_max();
        return $max_id;
    }

    function _custom_query($mysql_query) {
        $this->load->model('mdl_reciving');
        $query = $this->mdl_reciving->_custom_query($mysql_query);
        return $query;
    }
}
