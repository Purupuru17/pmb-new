<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ortu extends KZ_Controller {
    
    private $module = 'mhs/daftar';
            
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_ortu','m_mhs'));
    }
    function edit($id = NULL, $mid = NULL) {
        if(empty(decode($id)) || is_null($mid)){
            redirect($this->module);
        }
        $this->data['edit'] = $this->m_ortu->getId(decode($id));
        
        $this->data['keterangan'] = load_array('keterangan');
        $this->data['didik'] = load_array('didik');
        $this->data['kerja'] = load_array('kerja');
        $this->data['golongan'] = load_array('golongan');
        
        $this->data['action'] = 'mhs/ortu_do/edit/'.$id;
        $this->data['title'] = array('Pendaftaran','Ubah Data Orang Tua/Wali');
        $this->data['breadcrumb'] = array( 
            array('title'=>'Mahasiswa', 'url'=>'#'),
            array('title'=>'Pendaftaran', 'url'=> site_url($this->module.'/detail/'.$mid)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('mhs/ortu/v_edit', $this->data);
    }
    function delete($id = NULL, $mid = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $result = $this->m_ortu->delete(decode($id));
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data Orang Tua/Wali berhasil dihapus'));
            redirect($this->module.'/detail/'.$mid);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data Orang Tua/Wali gagal dihapus'));
            redirect($this->module.'/detail/'.$mid);
        }
    }  
}
