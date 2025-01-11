<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_jawab extends CI_Model {

    var $id = 'id_jawab';
    var $table = 'lm_jawab';
    var $column_order = array(null,'nama_peserta','kelas_peserta','mulai_jawab','selesai_jawab','status_jawab','nama_module',null); //set column field database for datatable orderable
    var $column_search = array('no_peserta','nama_peserta','kelas_peserta','mulai_jawab','selesai_jawab','status_jawab','nama_module'); //set column field database for datatable searchable 
    var $order = array('selesai_jawab' => 'desc'); // default order 

    function __construct() {
        parent::__construct();
    }
    //INSERT
    function insert($data) {
        //$this->db->set($this->id, 'UUID()', FALSE);
        
        $this->db->insert($this->table, $data);
        return $this->db->affected_rows() > 0 ? true : false;
    }
    function insertBatch($data, $soal) {
        $this->db->trans_start();
        
        $insert = $this->db->insert($this->table, $data);
        if($insert){
            $this->db->insert_batch('lmrf_quiz', $soal);
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
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
    function updateAll($where, $jawab = NULL, $quiz = NULL) {
        $this->db->trans_start();
        if(!is_null($jawab)){
            $this->update($where['jawab_id'], $jawab);
        }
        if(!is_null($quiz)){
            $this->db->where($where);
            $this->db->update('lmrf_quiz', $quiz);
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
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
        $this->db->from($this->table.' j');
        $this->db->join('m_module m','m.id_module = j.module_id','inner');
        if(!is_null($where)){
            $this->db->where($where);
        }
        if(!is_null($order)){
            $this->db->order_by('j.mulai_jawab', $order);
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
        $data[$this->id] = NULL;
        $data['module_id'] = NULL;
        $data['peserta_id'] = NULL;
        $data['mulai_jawab'] = NULL;
        $data['selesai_jawab'] = NULL;
        $data['status_jawab'] = NULL;
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
        $this->db->from($this->table.' j');
        $this->db->join('m_module m','m.id_module = j.module_id','inner');
        $this->db->join('m_peserta p','p.id_peserta = j.peserta_id','inner');
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
