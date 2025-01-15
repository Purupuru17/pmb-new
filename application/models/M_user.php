<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_user extends CI_Model {

    var $id = 'id_user';
    var $table = 'yk_user';
    var $column_order = array(null,'fullname','username','nama_group','status_user','buat_user','last_login',null); //set column field database for datatable orderable
    var $column_search = array('fullname','username','nama_group','status_user','buat_user','last_login'); //set column field database for datatable searchable 
    var $order = array('last_login' => 'desc'); // default order 

    function __construct() {
        parent::__construct();
    }
    //INSERT
    function insert($data) {
        $this->db->set($this->id, 'UUID()', FALSE);
        
        $this->db->insert($this->table, $data);
        return $this->db->affected_rows() > 0 ? true : false;
    }
    function insertLog($id, $data) {
        $log = array(
            'user_id' => $id,
            'ip_log' => $data['ip_user'],
            'msg_log' => $data['log_user'],
            'buat_log' => date('Y-m-d H:i:s')
        );
        $this->db->insert('yk_user_log', $log);
        return $this->db->affected_rows() > 0 ? true : false;
    }
    //UPDATE
    function update($id, $data, $log = NULL) {
        $this->db->where($this->id, $id);
        $row = $this->db->update($this->table, $data);
        if(!is_null($log)){
            $this->insertLog($id, $data);
        }
        return $row;
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
    function resetLog($day) {
        $this->db->where('DATE(buat_log) <= DATE(CURRENT_DATE - INTERVAL '.$day.' DAY)');
        $this->db->delete('yk_user_log');
        return $this->db->affected_rows();
    }
    //GET
    function getAll($where = NULL) {
        $this->db->from('yk_user u');
        $this->db->join('yk_group g','u.id_group = g.id_group','inner');
        if(!is_null($where)){
            $this->db->where($where);
        }
        $this->db->order_by('u.last_login','desc');
        
        $get = $this->db->get();
        return [
            'rows' => $get->num_rows(),
            'data' => $get->result_array()
        ];
    }
    function getBy($where = NULL) {
        $this->db->from('yk_user u');
        $this->db->join('yk_group g','u.id_group = g.id_group','inner');
        if(!is_null($where)){
            $this->db->where($where);
        }
        return $this->db->get()->row_array();
    }
    function getLog($where) {
        $this->db->from('yk_user_log');
        if(!is_null($where)){
            $this->db->where($where);
        }
        $this->db->order_by('buat_log','desc');
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
        $data['id_user'] = NULL;
        $data['id_group'] = NULL;
        $data['fullname'] = NULL;
        $data['username'] = NULL;
        $data['password'] = NULL;
        $data['status_user'] = NULL;
        $data['foto_user'] = NULL;
        
        return $data;
    }
    function get_datatables($where = NULL) {
        $this->get_datatables_query($where);
        if ($_POST['length'] != -1){
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        return $query->result_array();
    }
    function count_filtered($where = NULL) {
        $this->get_datatables_query($where);
        $query = $this->db->get();
        return $query->num_rows();
    }
    function count_all() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
    function get_datatables_query($where = NULL) {
        $this->db->from('yk_user u');
        $this->db->join('yk_group g','u.id_group = g.id_group','inner');
        
        if(!is_null($where)){
            $this->db->where($where);
        }
        $i = 0;
        foreach ($this->column_search as $item) { // looping awal
            if ($_POST['search']['value']) { // jika datatable mengirimkan pencarian dengan metode POST
                if ($i === 0) { // looping awal
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }
        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
}
