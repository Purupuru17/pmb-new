<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ortu_do extends KZ_Controller {
    
    private $module = 'mhs/daftar';
            
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_ortu'));
    }
    function add() {
        $mid = $this->input->post('mid');
        
        if(!$this->_validation($this->rules)){
            redirect($this->module.'/detail/'.$mid);
        }
        $where['mhs_id'] = $data['mhs_id'] = decode($mid);
        $where['jenis_ortu'] = $data['jenis_ortu'] = $this->input->post('keterangan');
        $data['nik_ortu'] = $this->input->post('nik');
        $data['nama_ortu'] = $this->input->post('nama');
        $data['lahir_ortu'] = $this->input->post('lahir');
        $data['didik_ortu'] = $this->input->post('didik');
        $data['kerja_ortu'] = $this->input->post('kerja');
        $data['hasil_ortu'] = $this->input->post('hasil');
        $data['telepon_ortu'] = $this->input->post('telepon');
        $data['alamat_ortu'] = $this->input->post('alamat');

        $data['update_ortu'] = date('Y-m-d H:i:s');
        $data['log_ortu'] = $this->sessionname;
        
        $cek = $this->m_ortu->getAll($where);
        if ($cek['rows'] > 0) {
            $this->session->set_flashdata('notif', notif('warning', 'Informasi', 'Data Orang Tua/Wali sudah ada sebelumnya'));
            redirect($this->module.'/detail/'.$mid);
        }
        $result = $this->m_ortu->insert($data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil disimpan'));
            redirect($this->module.'/detail/'.$mid);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data  gagal disimpan'));
            redirect($this->module.'/detail/'.$mid);
        }
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        if(!$this->_validation($this->rules)){
            redirect($this->module.'/detail/'.$mid);
        }
        $mid = $this->input->post('mid');
        
        $data['jenis_ortu'] = $this->input->post('keterangan');
        $data['nik_ortu'] = $this->input->post('nik');
        $data['nama_ortu'] = $this->input->post('nama');
        $data['lahir_ortu'] = $this->input->post('lahir');
        $data['didik_ortu'] = $this->input->post('didik');
        $data['kerja_ortu'] = $this->input->post('kerja');
        $data['hasil_ortu'] = $this->input->post('hasil');
        $data['telepon_ortu'] = $this->input->post('telepon');
        $data['alamat_ortu'] = $this->input->post('alamat');

        $data['update_ortu'] = date('Y-m-d H:i:s');
        $data['log_ortu'] = $this->sessionname;
        
        $result = $this->m_ortu->update(decode($id),$data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil diubah'));
            redirect($this->module.'/detail/'.$mid);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal diubah'));
            redirect($this->module.'/detail/'.$mid);
        }
    }
    private $rules = array(
        array(
            'field' => 'keterangan',
            'label' => 'Keterangan',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'nik',
            'label' => 'NIK',
            'rules' => 'required|trim|xss_clean|is_natural|min_length[16]|max_length[16]'
        ),array(
            'field' => 'nama',
            'label' => 'Nama',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'lahir',
            'label' => 'Tanggal Lahir',
            'rules' => 'required|trim|xss_clean|date'
        ),array(
            'field' => 'didik',
            'label' => 'Pendidikan',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'kerja',
            'label' => 'Pekerjaan',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'hasil',
            'label' => 'Penghasilan',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'telepon',
            'label' => 'Nomor Telepon',
            'rules' => 'required|trim|xss_clean|is_natural|min_length[11]|max_length[12]'
        ),array(
            'field' => 'alamat',
            'label' => 'Alamat',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        )
    );    
}
