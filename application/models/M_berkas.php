<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_berkas extends CI_Model {

    var $id = 'id_berkas';
    var $table = 'm_berkas';
    var $tableupload = 'tmp_upload';
    var $column_order = array(null,'kode_reg','prodi_id','update_mhs','upload_id','status_berkas',null); //set column field database for datatable orderable
    var $column_search = array('kode_reg','nama_mhs','nama_prodi','update_mhs','status_mhs','upload_id','nama_upload','status_berkas'); //set column field database for datatable searchable 
    var $order = array('update_berkas' => 'desc'); // default order
    
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
    function getAll($where = NULL, $order = 'desc') {
        $this->db->from($this->table.' b');
        $this->db->join('tmp_upload t', 'b.upload_id = t.id_upload', 'inner');
        if(!is_null($where)){
            $this->db->where($where);
        }
        if(!is_null($order)){
            $this->db->order_by('b.update_berkas', $order);
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
    function getExcel($where = NULL, $order = 'asc') {
        $this->db->from('m_mhs m');
        $this->db->join('m_prodi p', 'm.prodi_id = p.id_prodi', 'inner');
        $this->db->join('m_berkas b', 'b.mhs_id = m.id_mhs', 'inner');
        if(!is_null($where)){
            $this->db->where($where);
        }
        if(!is_null($order)){
            $this->db->order_by('m.tgl_daftar', $order);
        }
        $get = $this->db->get();
        return [
            'rows' => $get->num_rows(),
            'data' => $get->result_array()
        ];
    }
    function getUpload($where = NULL, $order = 'asc') {
        $this->db->from($this->tableupload);
        if(!is_null($where)){
            $this->db->where($where);
        }
        if(!is_null($order)){
            $this->db->order_by('tipe_upload', $order);
        }
        $get = $this->db->get();
        return [
            'rows' => $get->num_rows(),
            'data' => $get->result_array()
        ];
    }
    function getUpId($id) {
        $this->db->from($this->tableupload);
        if(is_array($id)){
            $this->db->where($id);
        }else{
            $this->db->where('id_upload', $id);
        }
        return $this->db->get()->row_array();
    }
    function getEmpty() {
        $data['id_berkas'] = NULL;
        $data['mhs_id'] = NULL;
        $data['upload_id'] = NULL;
        
        $data['status_berkas'] = NULL;
        $data['tipe_berkas'] = NULL;
        $data['size_berkas'] = NULL;
        $data['file_berkas'] = NULL;

        $data['update_berkas'] = NULL;
        $data['log_berkas'] = NULL;
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
        $this->db->from('m_mhs m');
        $this->db->join('m_prodi p', 'm.prodi_id = p.id_prodi', 'inner');
        $this->db->join('m_berkas b', 'b.mhs_id = m.id_mhs', 'inner');
        $this->db->join('tmp_upload t', 'b.upload_id = t.id_upload', 'inner');
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
