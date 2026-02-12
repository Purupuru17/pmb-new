<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_galeri extends CI_Model {
    var $id = 'id_galeri';
    var $table = 'wb_galeri';
    
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
    function getAll($where = NULL, $order = 'desc', $limit = 0, $offset = 0, $search = NULL) {
        $this->db->from($this->table);
        if(!is_null($where)){
            $this->db->where($where);
        }
        if(!is_null($search)){
            $this->db->like('judul_galeri', trim($search), 'both');
        }
        if(!is_null($limit)){
            $this->db->limit($limit, $offset);
        }
        if(!is_null($order)){
            $this->db->order_by('update_galeri', $order);
        }
        $get = $this->db->get();
        return [
            'rows' => $get->num_rows(),
            'data' => $get->result_array()
        ];
    }
    function countAll($where = NULL, $search = NULL) {
        $this->db->select('COUNT(id_galeri) AS total');
        $this->db->from($this->table);
        if(!is_null($where)){
            $this->db->where($where);
        }
        if(!is_null($search)){
            $this->db->like('judul_galeri', trim($search), 'both');
        }
        return $this->db->get()->row_array()['total'];
        
    }
    function getId($id) {
        $this->db->from($this->table)->where($this->id, $id);
        return $this->db->get()->row_array();
    }
    function getSlug($slug) {
        $this->db->from($this->table)->where('slug_galeri', $slug)->where('status_galeri', '1');
        return $this->db->get()->row_array();
    }
    function getEmpty() {
        $data['id_galeri'] = NULL;
        $data['judul_galeri'] = NULL;
        $data['jenis_galeri'] = NULL;
        $data['isi_galeri'] = NULL;
        $data['status_galeri'] = NULL;
        $data['is_header'] = NULL;
        $data['foto_galeri'] = NULL;
        
        $data['update_galeri'] = NULL;
        $data['log_galeri'] = NULL;
        return $data;
   }
}
