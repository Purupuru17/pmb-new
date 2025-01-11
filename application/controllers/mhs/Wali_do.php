<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Wali_do extends KZ_Controller {
    
    private $module = 'mhs/wali';
    private $module_do = 'mhs/wali_do';
    private $mid = NULL;
    
    function __construct() {
        parent::__construct();
        
        $this->load->model(array('m_ortu'));
        $this->_getMhs();
    }
    function add() {
        if(!$this->_validation($this->rules)){
            redirect($this->module.'/add');
        }
        $data['mhs_id'] = $this->mid;
        $data['nik_ayah'] = $this->input->post('nik');
        $data['nama_ayah'] = strtoupper($this->input->post('nama'));
        $data['lahir_ayah'] = $this->input->post('lahir');
        $data['didik_ayah'] = $this->input->post('didik');
        $data['kerja_ayah'] = $this->input->post('kerja');
        $data['hasil_ayah'] = $this->input->post('hasil');
        
        $data['nik_ibu'] = $this->input->post('nikB');
        $data['nama_ibu'] = strtoupper($this->input->post('namaB'));
        $data['lahir_ibu'] = $this->input->post('lahirB');
        $data['kerja_ibu'] = $this->input->post('kerjaB');
        
        $data['telepon_ortu'] = $this->input->post('telepon');
        $data['alamat_ortu'] = ucwords(strtolower($this->input->post('alamat')));

        $data['update_ortu'] = date('Y-m-d H:i:s');
        $data['log_ortu'] = $this->sessionname.' menambahkan data';
        
        $cek = $this->m_ortu->getAll(array('mhs_id' => $this->mid));
        if ($cek['rows'] > 0) {
            $this->session->set_flashdata('notif', notif('warning', 'Informasi', 'Data Orang Tua sudah ada sebelumnya'));
            redirect($this->module);
        }
        $result = $this->m_ortu->insert($data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil disimpan'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal disimpan'));
            redirect($this->module.'/add');
        }
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        if(!$this->_validation($this->rules)){
            redirect($this->module.'/edit/'.$id);
        }
        $data['nik_ayah'] = $this->input->post('nik');
        $data['nama_ayah'] = strtoupper($this->input->post('nama'));
        $data['lahir_ayah'] = $this->input->post('lahir');
        $data['didik_ayah'] = $this->input->post('didik');
        $data['kerja_ayah'] = $this->input->post('kerja');
        $data['hasil_ayah'] = $this->input->post('hasil');
        
        $data['nik_ibu'] = $this->input->post('nikB');
        $data['nama_ibu'] = strtoupper($this->input->post('namaB'));
        $data['lahir_ibu'] = $this->input->post('lahirB');
        $data['kerja_ibu'] = $this->input->post('kerjaB');
        
        $data['telepon_ortu'] = $this->input->post('telepon');
        $data['alamat_ortu'] = ucwords(strtolower($this->input->post('alamat')));

        $data['update_ortu'] = date('Y-m-d H:i:s');
        $data['log_ortu'] = $this->sessionname.' mengubah data';
        
        $result = $this->m_ortu->update(decode($id),$data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil diubah'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal diubah'));
            redirect($this->module.'/edit/'.$id);
        }
    }
    //function
    function _getMhs(){
        $this->load->model(array('m_mhs'));
        $this->mid = $this->m_mhs->getTMP(array('user_id' => $this->sessionid))['mhs_id'];
    }
    private $rules = array(
        array(
            'field' => 'nik',
            'label' => 'NIK',
            'rules' => 'required|trim|xss_clean|is_natural|min_length[16]|max_length[16]'
        ),array(
            'field' => 'nikB',
            'label' => 'NIK',
            'rules' => 'trim|xss_clean|is_natural|min_length[16]|max_length[16]'
        ),array(
            'field' => 'nama',
            'label' => 'Nama',
            'rules' => 'required|trim|xss_clean|min_length[3]'
        ),array(
            'field' => 'namaB',
            'label' => 'Nama',
            'rules' => 'trim|xss_clean|min_length[3]'
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
            'rules' => 'required|trim|xss_clean|min_length[30]'
        )
    );    
}
