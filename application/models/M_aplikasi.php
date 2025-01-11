<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_aplikasi extends CI_Model {
    
    private $table = 'yk_aplikasi';
    private $id = 'id_aplikasi';
    
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
    function getAll($where = NULL) {
        $this->db->from($this->table);
        if(!is_null($where)){
            $this->db->where($where);
        }
        $this->db->limit(1);
        return $this->db->get()->row_array();
    }
    function getId($id) {
        $this->db->from($this->table)->where($this->id, $id);
        return $this->db->get()->row_array();
    }
}
