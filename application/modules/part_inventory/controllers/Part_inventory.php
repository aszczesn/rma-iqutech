<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Part_inventory extends MX_Controller
{
    function __construct() {
        parent::__construct();
    }

    function get($order_by) {
        $this->load->model('mdl_part_inventory');
        $query = $this->mdl_part_inventory->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
        $this->load->model('mdl_part_inventory');
        $query = $this->mdl_part_inventory->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id) {
        $this->load->model('mdl_part_inventory');
        $query = $this->mdl_part_inventory->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model('mdl_part_inventory');
        $query = $this->mdl_part_inventory->get_where_custom($col, $value);
        return $query;
    }

    function _insert($data) {
        $this->load->model('mdl_part_inventory');
        $this->mdl_part_inventory->_insert($data);
    }

    function _update($id, $data) {
        $this->load->model('mdl_part_inventory');
        $this->mdl_part_inventory->_update($id, $data);
    }

    function _delete($id) {
        $this->load->model('mdl_part_inventory');
        $this->mdl_part_inventory->_delete($id);
    }

    function count_where($column, $value) {
        $this->load->model('mdl_part_inventory');
        $count = $this->mdl_part_inventory->count_where($column, $value);
        return $count;
    }

    function get_max() {
        $this->load->model('mdl_part_inventory');
        $max_id = $this->mdl_part_inventory->get_max();
        return $max_id;
    }

    function _custom_query($mysql_query) {
        $this->load->model('mdl_part_inventory');
        $query = $this->mdl_part_inventory->_custom_query($mysql_query);
        return $query;
    }
}
