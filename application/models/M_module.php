<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_module extends CI_Model {

    var $id = 'id_module';
    var $table = 'lm_module';

    function __construct() {
        parent::__construct();
    }
    //INSERT
    function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->affected_rows() > 0 ? true : false;
    }
    function insertBatch($data) {
        $this->db->trans_start();
        $this->db->insert_batch($this->table, $data);
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    //UPDATE
    function update($id, $data) {
        if(is_array($id)){
            $this->db->where($id);
        }else{
            $this->db->where($this->id, $id);
        }
        $this->db->update($this->table, $data);
        return $this->db->affected_rows() > 0 ? true : false;
    }
    //DELETE
    function delete($id) {
        if(is_array($id)){
            $this->db->where($id);
        }else{
            $this->db->where($this->id, $id);
        }
        $this->db->delete($this->table);
        return $this->db->affected_rows() > 0 ? true : false;
    }
    //GET
    function getAll($where = NULL, $order = 'desc') {
        $this->db->from($this->table);
        if(!is_null($where)){
            $this->db->where($where);
        }
        if(!is_null($order)){
            $this->db->order_by('update_module', $order);
        }
        $get = $this->db->get();
        return [
            'rows' => $get->num_rows(),
            'data' => $get->result_array()
        ];
    }
    function getId($id) {
        $this->db->from($this->table);
        if(is_array($id)){
            $this->db->where($id);
        }else{
            $this->db->where($this->id, $id);
        }
        return $this->db->get()->row_array();
    }
}
