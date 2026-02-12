<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Berkas_do extends KZ_Controller {
    
    private $module = 'mhs/berkas';
    private $module_do = 'mhs/berkas_do';
    private $path = 'upload/berkas/';
    
    function __construct() {
        parent::__construct();
        
        $this->load->model(array('m_berkas'));
        $this->_getMhs();
    }
    function add() {
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module.'/add');
        }
        $where['mhs_id'] = $data['mhs_id'] = $this->mid;
        $where['upload_id'] = $data['upload_id'] = decode($this->input->post('jenis'));
        
        $cek = $this->m_berkas->getAll($where);
        if ($cek['rows'] > 0) {
            $this->session->set_flashdata('notif', notif('warning', 'Informasi', 'Data jenis berkas sudah ada sebelumnya'));
            redirect($this->module.'/add');
        }
        if(empty($_FILES['file']['name'])){
            $this->session->set_flashdata('notif', notif('warning', 'Informasi', 'Tidak ada berkas yang dapat di unggah'));
            redirect($this->module.'/add');
        }
        $this->load->library(array('storage'));
        //filename
        $berkas = $this->m_berkas->getUpId($where['upload_id']);
        $filename = url_title($this->sessionusr.' '.$berkas['kode_upload'].' '.random_string('numeric', 3), 'dash', TRUE);
        //upload
        $path = array('local' => $this->path, 's3' => $this->sessionusr);
        $upload = $this->storage->putFile('file', $filename, $path);
        if(empty($upload->fullPath)){
            redirect($this->module.'/add');
        }
        $data['file_berkas'] = $upload->customName;
        $data['tipe_berkas'] = $upload->mime;
        $data['size_berkas'] = $upload->size.' KB';
        $data['status_berkas'] = '0';
        $data['update_berkas'] = date('Y-m-d H:i:s');
        $data['log_berkas'] = $this->sessionname.' menambahkan data';
        
        $result = $this->m_berkas->insert($data);
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
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module.'/edit/'.$id);
        }
        if(empty($_FILES['file']['name'])){
            $this->session->set_flashdata('notif', notif('warning', 'Informasi', 'Tidak ada berkas yang dapat di unggah'));
            redirect($this->module.'/edit/'.$id);
        }
        $upload_id = decode($this->input->post('jenis'));
        
        $this->load->library(array('storage'));
        //filename
        $berkas = $this->m_berkas->getUpId($upload_id);
        $filename = url_title($this->sessionusr.' '.$berkas['kode_upload'].' '.random_string('numeric', 3), 'dash', TRUE);
        //upload
        $path = array('local' => $this->path, 's3' => $this->sessionusr);
        $upload = $this->storage->putFile('file', $filename, $path);
        if(empty($upload->fullPath)){
            redirect($this->module.'/edit/'.$id);
        }
        $data['file_berkas'] = $upload->customName;
        $data['status_berkas'] = '0';
        $data['tipe_berkas'] = $upload->mime;
        $data['size_berkas'] = $upload->size.' KB';
        $data['update_berkas'] = date('Y-m-d H:i:s');
        $data['log_berkas'] = $this->sessionname.' mengubah data';

        $old_file = $this->input->post('exfile');
        delete_file($old_file); 
        
        $result = $this->m_berkas->update(decode($id), $data);
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
            'field' => 'jenis',
            'label' => 'Jenis Berkas',
            'rules' => 'required|trim|xss_clean'
        )
    );
}
