<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_matkul extends CI_Model {

    var $id = 'id_matkul';
    var $table = 'tmp_matkul';
    
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
        $this->db->from('tmp_matkul m');
        $this->db->join('m_prodi p', 'm.prodi_id = p.id_prodi', 'inner');
        if(!is_null($where)){
            $this->db->where($where);
        }
        $this->db->order_by('m.kode_matkul', 'asc');
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
        $data['id_matkul'] = NULL;
        $data['prodi_id'] = NULL;
        $data['nama_matkul'] = NULL;
        $data['kode_matkul'] = NULL;
        $data['sks_matkul'] = NULL;
        return $data;
   }
}
