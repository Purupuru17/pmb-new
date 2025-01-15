<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_group extends CI_Model {

    private $id = 'id_group';
    private $table = 'yk_group';

    function __construct() {
        parent::__construct();
    }
    //INSERT
    function insert($data) {
        $row = $this->db->insert($this->table, $data);
        return $row;
    }
    function insertRole($data) {
        $row = $this->db->insert('yk_group_role', $data);
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
    function deleteRole($group, $user) {
        $this->db->where('group_id', $group);
        $this->db->where('user_id', $user);
        
        $row = $this->db->delete('yk_group_role');
        return $row;
    }
    
    //GET
    function getAll($where = NULL) {
        $this->db->from($this->table);
        if(!is_null($where)){
            $this->db->where($where);
        }
        $this->db->order_by('nama_group','asc');
        
        $get = $this->db->get();
        return [
            'rows' => $get->num_rows(),
            'data' => $get->result_array()
        ];
    }
    function getRole($where = NULL) {
        $this->db->select('r.*,u.fullname,u.username,g.nama_group,g.level');
        $this->db->from('yk_group_role r');
        $this->db->join('yk_group g', 'r.group_id = g.id_group', 'inner');
        $this->db->join('yk_user u', 'r.user_id = u.id_user', 'inner');
        if(!is_null($where)){
            $this->db->where($where);
        }
        $this->db->order_by('r.group_id','asc');
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
        $data['id_group'] = NULL;
        $data['nama_group'] = NULL;
        $data['level'] = NULL;
        $data['keterangan_group'] = NULL;
       
        return $data;
   }
}
