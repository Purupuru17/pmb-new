<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_prodi extends CI_Model {

    var $id = 'id_prodi';
    var $table = 'm_prodi';
    
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
        $this->db->order_by('nama_prodi', 'asc');
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
        $data['id_prodi'] = NULL;
        $data['nama_prodi'] = NULL;
        $data['fakultas'] = NULL;
        $data['sk_prodi'] = NULL;
        $data['tgl_prodi'] = NULL;
        return $data;
   }
}
