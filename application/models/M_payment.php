<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_payment extends CI_Model {

    var $id = 'id_payment';
    var $table = 'm_payment';
    var $column_order = array(null,'nama_mhs','nama_prodi','va_payment','buat_payment','status_payment','saldo_payment', null); //set column field database for datatable orderable
    var $column_search = array('nim','nama_mhs','nama_prodi','angkatan','va_payment','total_payment','saldo_payment'); //set column field database for datatable searchable 
    var $order = array('update_payment' => 'desc'); // default order 

    function __construct() {
        parent::__construct();
    }
    //INSERT
    function insert($data) {
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
    function getAll($where = NULL, $order = 'desc') {
        $this->db->from($this->table);
        if(!is_null($where)){
            $this->db->where($where);
        }
        if(!is_null($order)){
            $this->db->order_by('buat_payment', $order);
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
    function getBill($id) {
        $this->db->from($this->table.' p');
        $this->db->join('m_mhs m', 'p.mhs_id = m.id_mhs', 'left');
        if(is_array($id)){
            $this->db->where($id);
        }else{
            $this->db->where($this->id, $id);
        }
        return $this->db->get()->row_array();
    }
    function getSum($id) {
        $this->db->select('SUM(total_payment) as total');
        $this->db->from($this->table);
        if(is_array($id)){
            $this->db->where($id);
        }else{
            $this->db->where($this->id, $id);
        }
        return (int) $this->db->get()->row_array()['total'];
    }
    function getEmpty() {
        $data = array($this->id, 'mhs_id' ,'total_payment', 'saldo_payment', 'email_payment',
            'note_payment', 'buat_payment', 'update_payment', 'log_payment'
        );
        return array_fill_keys($data, NULL);
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
        return $this->db->count_all_results();
    }
    function count_all() {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
    function get_datatables_query($where = NULL) {
        $this->db->from($this->table.' p');
        $this->db->join('m_mhs m', 'p.mhs_id = m.id_mhs', 'left');
        $this->db->join('m_prodi d', 'm.prodi_id = d.id_prodi', 'left');
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
