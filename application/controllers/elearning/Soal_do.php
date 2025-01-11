<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Soal_do extends KZ_Controller {
    
    private $module = 'elearning/soal';
    private $module_do = 'elearning/soal_do';
    private $path = 'app/upload/soal/';
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_soal'));
    }
    function add() {
        if(!$this->_validation($this->rules)){
            redirect($this->module.'/add');
        }
        $data['bank_id'] = decode($this->input->post('bank'));
        $data['materi_soal'] = ucwords($this->input->post('materi'));
        $data['isi_soal'] = $this->input->post('isi');
        $data['status_soal'] = $this->input->post('status');
        $data['order_soal'] = $this->input->post('order');
        $data['update_soal'] = date('Y-m-d H:i:s');
        $data['log_soal'] = $this->sessionname.' menambahkan data';
        
        $jumlah = intval($this->input->post('jumlah'));
        $value1 = $this->input->post('opsiA'); $nilai1 = (int) $this->input->post('nilaiA'); 
        $value2 = $this->input->post('opsiB'); $nilai2 = (int) $this->input->post('nilaiB'); 
        $value3 = $this->input->post('opsiC'); $nilai3 = (int) $this->input->post('nilaiC'); 
        $value4 = $this->input->post('opsiD'); $nilai4 = (int) $this->input->post('nilaiD'); 
        $value5 = $this->input->post('opsiE'); $nilai5 = (int) $this->input->post('nilaiE'); 
        
        $data['opsi_a'] = ($jumlah >= 1) ? json_encode(array('key' => '3-A', 'isi' => $value1, 'nilai' => $nilai1, 'file' => '')) : null;
        $data['opsi_b'] = ($jumlah >= 2) ? json_encode(array('key' => '3-B', 'isi' => $value2, 'nilai' => $nilai2, 'file' => '')) : null;
        $data['opsi_c'] = ($jumlah >= 3) ? json_encode(array('key' => '3-C', 'isi' => $value3, 'nilai' => $nilai3, 'file' => '')) : null;
        $data['opsi_d'] = ($jumlah >= 4) ? json_encode(array('key' => '3-D', 'isi' => $value4, 'nilai' => $nilai4, 'file' => '')) : null;
        $data['opsi_e'] = ($jumlah >= 5) ? json_encode(array('key' => '3-E', 'isi' => $value5, 'nilai' => $nilai5, 'file' => '')) : null;
        
        $check = $this->m_soal->getAll(array('bank_id' => $data['bank_id'], 'order_soal' => $data['order_soal']));
        if($check['rows'] > 0){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Soal Nomor Urut '.$data['order_soal'].' sudah tersimpan sebelumnya'));
            redirect($this->module.'/add');
        }
        if(!empty($_FILES['foto']['name'])){
            $this->load->library(array('storage'));
            //upload
            $filename = url_title($this->sessionusr.' '.random_string('alnum',5), 'dash', TRUE);
            $path = array('local' => $this->path, 's3' => $this->sessionusr.'/soal');
            $upload =  $this->storage->putFile('foto', $filename, $path);
            if(empty($upload->fullPath)){
                redirect($this->module.'/add');
            }
            $data['file_soal'] = $upload->customName;
        }
        $result = $this->m_soal->insert($data);
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
        $data['bank_id'] = decode($this->input->post('bank'));
        $data['materi_soal'] = ucwords($this->input->post('materi'));
        $data['isi_soal'] = $this->input->post('isi');
        $data['status_soal'] = $this->input->post('status');
        $data['order_soal'] = $this->input->post('order');
        $data['update_soal'] = date('Y-m-d H:i:s');
        $data['log_soal'] = $this->sessionname.' mengubah data';
        
        $jumlah = intval($this->input->post('jumlah'));
        $value1 = $this->input->post('opsiA'); $nilai1 = (int) $this->input->post('nilaiA'); 
        $value2 = $this->input->post('opsiB'); $nilai2 = (int) $this->input->post('nilaiB'); 
        $value3 = $this->input->post('opsiC'); $nilai3 = (int) $this->input->post('nilaiC'); 
        $value4 = $this->input->post('opsiD'); $nilai4 = (int) $this->input->post('nilaiD'); 
        $value5 = $this->input->post('opsiE'); $nilai5 = (int) $this->input->post('nilaiE'); 
        
        $data['opsi_a'] = ($jumlah >= 1) ? json_encode(array('key' => '3-A', 'isi' => $value1, 'nilai' => $nilai1, 'file' => '')) : null;
        $data['opsi_b'] = ($jumlah >= 2) ? json_encode(array('key' => '3-B', 'isi' => $value2, 'nilai' => $nilai2, 'file' => '')) : null;
        $data['opsi_c'] = ($jumlah >= 3) ? json_encode(array('key' => '3-C', 'isi' => $value3, 'nilai' => $nilai3, 'file' => '')) : null;
        $data['opsi_d'] = ($jumlah >= 4) ? json_encode(array('key' => '3-D', 'isi' => $value4, 'nilai' => $nilai4, 'file' => '')) : null;
        $data['opsi_e'] = ($jumlah >= 5) ? json_encode(array('key' => '3-E', 'isi' => $value5, 'nilai' => $nilai5, 'file' => '')) : null;
        
        if(!empty($_FILES['foto']['name'])){
            $this->load->library(array('storage'));
            //upload
            $filename = url_title($this->sessionusr.' '.random_string('alnum',5), 'dash', TRUE);
            $path = array('local' => $this->path, 's3' => $this->sessionusr.'/soal');
            $upload =  $this->storage->putFile('foto', $filename, $path);
            if(empty($upload->fullPath)){
                redirect($this->module.'/edit/'.$id);
            }
            $data['file_soal'] = $upload->customName;
            delete_file($this->input->post('exfoto'));
        }
        $result = $this->m_soal->update(decode($id), $data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil diubah'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal diubah'));
            redirect($this->module.'/edit/'.$id);
        }
    }
    private $rules = array(
        array(
            'field' => 'bank',
            'label' => 'Bank Soal',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'materi',
            'label' => 'Materi Soal',
            'rules' => 'trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'isi',
            'label' => 'Isi Soal',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'order',
            'label' => 'Nomor Urut',
            'rules' => 'required|trim|xss_clean|is_natural|greater_than[0]|less_than_equal_to[100]'
        ),array(
            'field' => 'status',
            'label' => 'Status Soal',
            'rules' => 'required|trim|xss_clean'
        )
    );
}
