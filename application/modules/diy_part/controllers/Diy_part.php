<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Diy_part extends MX_Controller
{
    function __construct() {
        parent::__construct();
    }

    function get($order_by) {
        $this->load->model('mdl_diy_part');
        $query = $this->mdl_diy_part->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
        $this->load->model('mdl_diy_part');
        $query = $this->mdl_diy_part->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id) {
        $this->load->model('mdl_diy_part');
        $query = $this->mdl_diy_part->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model('mdl_diy_part');
        $query = $this->mdl_diy_part->get_where_custom($col, $value);
        return $query;
    }

    function _insert($data) {
        $this->load->model('mdl_diy_part');
        $this->mdl_diy_part->_insert($data);
    }

    function _update($id, $data) {
        $this->load->model('mdl_diy_part');
        $this->mdl_diy_part->_update($id, $data);
    }

    function _delete($id) {
        $this->load->model('mdl_diy_part');
        $this->mdl_diy_part->_delete($id);
    }

    function count_where($column, $value) {
        $this->load->model('mdl_diy_part');
        $count = $this->mdl_diy_part->count_where($column, $value);
        return $count;
    }

    function get_max() {
        $this->load->model('mdl_diy_part');
        $max_id = $this->mdl_diy_part->get_max();
        return $max_id;
    }

    function _custom_query($mysql_query) {
        $this->load->model('mdl_diy_part');
        $query = $this->mdl_diy_part->_custom_query($mysql_query);
        return $query;
    }
}
