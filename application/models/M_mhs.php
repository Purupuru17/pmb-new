<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_mhs extends CI_Model {
    
    var $id = 'id_mhs';
    var $table = 'm_mhs';
    var $column_order = array(null,'tgl_daftar','nama_mhs','nim','npsn','kelamin_mhs','status_mhs',null); //set column field database for datatable orderable
    var $column_search = array('kode_reg','jalur_mhs','nama_mhs','nim','nik','nama_prodi','nisn','npsn','kelamin_mhs','telepon_mhs','status_mhs'); //set column field database for datatable searchable 
    var $order = array('update_mhs' => 'desc'); // default order
    
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
        $this->db->from('m_mhs m');
        $this->db->join('m_prodi p', 'm.prodi_id = p.id_prodi', 'left');
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
    function getId($id) {
        $this->db->from('m_mhs m');
        $this->db->join('m_prodi p', 'm.prodi_id = p.id_prodi', 'left');
        if(is_array($id)){
            $this->db->where($id);
        }else{
            $this->db->where($this->id, $id);
        }
        return $this->db->get()->row_array();
    }
    function getTMP($where) {
        $this->db->from('tmp_mhs t');
        $this->db->join('yk_user u', 't.user_id = u.id_user', 'inner');
        $this->db->where($where);
        
        return $this->db->get()->row_array();
    }
    function isExist($where) {
        $this->db->from($this->table);
        $this->db->where($where);
        
        return $this->db->get()->num_rows();
    }
    function getNomor($code){
        $sql = "SELECT MAX(MID(kode_reg,9,3)) AS kode
            FROM m_mhs
            WHERE MID(kode_reg,1,4) = DATE_FORMAT(CURDATE(), '%m%y')
            AND MID(kode_reg,5,4) = '$code'";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0){
            $row = $query->row();
            $n = ((int)$row->kode) + 1;
            $no = sprintf("%'.03d", $n);
        }else{
            $no = "001";
        }
        return date('my').$code.$no;
    }
    function getNIM($prodi, $tahun){
        $angkatan = str_replace('20', '', $tahun);
        $sql = "SELECT MAX(MID(nim,10,3)) AS nim FROM m_mhs WHERE MID(nim,3,5)='{$prodi}' AND MID(nim,8,2)='{$angkatan}' AND angkatan='{$tahun}' ";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0){
            $row = $query->row();
            $n = ((int)$row->nim) + 1;
            $no = sprintf("%'.03d", $n);
        }else{
            $no = "001";
        }
        //14-84205-19-001
        return '14' . $prodi . $angkatan . $no;
    }
    function getEmpty() {
        $data['id_mhs'] = NULL;
        $data['nim'] = NULL;
        $data['kode_reg'] = NULL;
        $data['prodi_id'] = NULL;
        $data['opsi_prodi'] = NULL;
        $data['status_mhs'] = NULL;
        $data['jalur_mhs'] = NULL;
        $data['angkatan'] = (date('m') == '12') ? date('Y') + 1 : date('Y');
        
        $data['nisn'] = NULL;
        $data['sekolah'] = NULL;
        $data['npsn'] = NULL;
        
        $data['nama_mhs'] = NULL;
        $data['ibu_kandung'] = NULL;
        $data['nik'] = NULL;
        $data['tempat_lahir'] = NULL;
        $data['tgl_lahir'] = NULL;
        $data['kelamin_mhs'] = NULL;
        $data['agama'] = NULL;
        $data['telepon_mhs'] = NULL;
        $data['email_mhs'] = NULL;
        $data['alamat_mhs'] = NULL;
        
        $data['jalan'] = NULL;
        $data['rt'] = NULL;
        $data['rw'] = NULL;
        $data['kelurahan'] = NULL;
        $data['kecamatan'] = NULL;
        $data['kabupaten'] = NULL;
        $data['note_mhs'] = NULL;
        $data['foto_mhs'] = NULL;
        $data['atribut_mhs'] = NULL;
        
        $data['tgl_daftar'] = NULL;
        $data['update_mhs'] = NULL;
        $data['log_mhs'] = NULL;
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
        $this->db->join('m_prodi p', 'm.prodi_id = p.id_prodi', 'left');
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
    function get_chart_range($where = null, $group = null, $awal = '', $akhir = '') {
        $this->db->select('COUNT(m.id_mhs) AS maba,
            p.nama_prodi AS prodi, npsn, sekolah,
            DATE_FORMAT(m.tgl_daftar,"%d-%m-%Y") AS day');
        $this->db->from('m_mhs m');
        $this->db->join('m_prodi p', 'm.prodi_id = p.id_prodi', 'inner');
        if(!is_null($where)){
            $this->db->where($where);
        }
        if(!empty($awal) && !empty($akhir)){
            $this->db->where('DATE(m.tgl_daftar) >=', $awal);
            $this->db->where('DATE(m.tgl_daftar) <=', $akhir);
        }
        if(!is_null($group)){
            if($group == 'prodi'){
                $this->db->group_by('m.prodi_id');
            }else if($group == 'sekolah'){
                $this->db->group_by('m.npsn');
            }
        }else{
            $this->db->group_by('DATE(m.tgl_daftar)');
        }
        $this->db->order_by('maba', 'asc');
        
        $get = $this->db->get();
        return [
            'rows' => $get->num_rows(),
            'data' => $get->result_array()
        ];
    }
}
