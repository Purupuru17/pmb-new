<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_artikel extends CI_Model {
    var $id = 'id_artikel';
    var $table = 'wb_artikel';
    var $column_order = array(null,'judul_artikel','judul_jenis','status_artikel','is_popular','is_breaking','update_artikel',null); //set column field database for datatable orderable
    var $column_search = array('judul_artikel','judul_jenis','status_artikel','is_popular','is_breaking','update_artikel'); //set column field database for datatable searchable 
    var $order = array('update_artikel' => 'desc'); // default order 

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
        $this->db->from('wb_artikel a');
        $this->db->join('wb_jenis_artikel j', 'a.jenis_id = j.id_jenis', 'inner');
        if(!is_null($where)){
            $this->db->where($where);
        }
        if(!is_null($search)){
            $this->db->like('a.judul_artikel', trim($search), 'both');
        }
        if(!is_null($limit)){
            $this->db->limit($limit, $offset);
        }
        if(!is_null($order)){
            $this->db->order_by('a.update_artikel', $order);
        }
        $get = $this->db->get();
        return [
            'rows' => $get->num_rows(),
            'data' => $get->result_array()
        ];
    }
    function countAll($where = NULL, $search = NULL) {
        $this->db->select('COUNT(a.id_artikel) AS total');
        $this->db->from('wb_artikel a');
        $this->db->join('wb_jenis_artikel j', 'a.jenis_id = j.id_jenis', 'inner');
        if(!is_null($where)){
            $this->db->where($where);
        }
        if(!is_null($search)){
            $this->db->like('a.judul_artikel', trim($search), 'both');
        }
        return $this->db->get()->row_array()['total'];
        
    }
    function getId($id) {
        $this->db->from($this->table)->where($this->id, $id);
        return $this->db->get()->row_array();
    }
    function getSlug($slug) {
        $this->db->from('wb_artikel a');
        $this->db->join('wb_jenis_artikel j', 'a.jenis_id = j.id_jenis', 'inner');
        $this->db->where('a.slug_artikel', $slug)->where('a.status_artikel', '1');
        
        return $this->db->get()->row_array();
    }
    function getEmpty() {
        $data['id_artikel'] = NULL;
        $data['judul_artikel'] = NULL;
        $data['isi_artikel'] = NULL;
        $data['jenis_id'] = NULL;
        $data['status_artikel'] = NULL;
        $data['is_popular'] = NULL;
        $data['is_breaking'] = NULL;

        $data['update_artikel'] = NULL;
        $data['log_artikel'] = NULL;
        $data['foto_artikel'] = NULL;
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
        $this->db->from('wb_artikel a');
        $this->db->join('wb_jenis_artikel j', 'a.jenis_id = j.id_jenis', 'inner');
        
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
