<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_file extends CI_Model {

    var $id = 'id_file';
    var $table = 'wb_file';

    function __construct() {
        parent::__construct();
    }
    //INSERT
    function insert($data) {
        $row = $this->db->insert($this->table, $data);
        return $row;
    }
    //UPDATE
    function update($id, $data) {
        $this->db->where($this->id, $id);
        $row = $this->db->update($this->table, $data);
        return $row;
    }
    //DELETE
    function delete($id) {
        $this->db->where($this->id, $id);
        $row = $this->db->delete($this->table);
        return $row;
    }
    
    //GET
    function getAll($where = NULL, $order = 'asc', $limit = 0, $offset = 0, $search = NULL) {
        $this->db->from($this->table);
        if(!is_null($where)){
            $this->db->where($where);
        }
        if(!is_null($search)){
            $this->db->like('nama_file', trim($search), 'both');
        }
        if(!is_null($limit)){
            $this->db->limit($limit, $offset);
        }
        if(!is_null($order)){
            $this->db->order_by('update_file', $order);
        }
        $get = $this->db->get();
        return [
            'rows' => $get->num_rows(),
            'data' => $get->result_array()
        ];
    }
    function getId($id) {
        $this->db->from($this->table)->where($this->id, $id);
        return $this->db->get()->row_array();
    }

    function getEmpty() {
        $data['id_file'] = NULL;
        $data['nama_file'] = NULL;
        $data['type_file'] = NULL;
        $data['size_file'] = NULL;
        $data['url_file'] = NULL;

        $data['update_file'] = NULL;
        $data['log_file'] = NULL;
        return $data;
   }
}
