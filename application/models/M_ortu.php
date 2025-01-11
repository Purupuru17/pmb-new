<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_ortu extends CI_Model {
    var $id = 'id_ortu';
    var $table = 'm_ortu';
    
    function __construct() {
        parent::__construct();
    }
    //INSERT
    function insert($data) {
        $this->db->set($this->id, 'UUID()', FALSE);
        
        $this->db->insert($this->table, $data);
        return $this->db->affected_rows() > 0 ? true : false;
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
    function getAll($where = NULL, $order = 'asc') {
        $this->db->from($this->table);
        if(!is_null($where)){
            $this->db->where($where);
        }
        if(!is_null($order)){
            $this->db->order_by('update_ortu', $order);
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
    function getEmpty() {
        $data['id_ortu'] = NULL;
        $data['mhs_id'] = NULL;

        $data['nik_ayah'] = NULL;
        $data['nama_ayah'] = NULL;
        $data['lahir_ayah'] = NULL;
        $data['didik_ayah'] = NULL;
        $data['kerja_ayah'] = NULL;
        $data['hasil_ayah'] = NULL;
        
        $data['nik_ibu'] = NULL;
        $data['nama_ibu'] = NULL;
        $data['lahir_ibu'] = NULL;
        $data['kerja_ibu'] = NULL;
        
        $data['telepon_ortu'] = NULL;
        $data['alamat_ortu'] = NULL;
        
        $data['update_ortu'] = NULL;
        $data['log_ortu'] = NULL;
        return $data;
   }
}
