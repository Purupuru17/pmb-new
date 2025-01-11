<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Berkas extends KZ_Controller {
    
    private $module = 'mhs/berkas';
    private $module_do = 'mhs/berkas_do';  
    private $mid = NULL;
    
    function __construct() {
        parent::__construct();
        
        $this->load->model(array('m_berkas'));
        $this->_getMhs();
    }
    function index() {
        $this->data['list'] = $this->m_berkas->getAll(array('mhs_id' => $this->mid));
        
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Upload Berkas','List Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>'Mahasiswa', 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('mhs/berkas/v_index', $this->data);
    }
    function add() {
        $this->data['upload'] = $this->m_berkas->getUpload();
        $this->data['edit'] = $this->m_berkas->getEmpty();
        
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Upload Berkas','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>'Mahasiswa', 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('mhs/berkas/v_form', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['upload'] = $this->m_berkas->getUpload();
        $this->data['edit'] = $this->m_berkas->getId(decode($id));
        
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('Upload Berkas','Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>'Mahasiswa', 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('mhs/berkas/v_form', $this->data);
    }
    function delete($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $rs = $this->m_berkas->getId(decode($id));
        $result = $this->m_berkas->delete(decode($id));
        if ($result) {
            //delete file
            delete_file($rs['file_berkas']); 
            
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil dihapus'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal dihapus'));
            redirect($this->module);
        }
    }
    
    //function
    function _getMhs(){
        $this->load->model(array('m_mhs'));
        $this->mid = $this->m_mhs->getTMP(array('user_id' => $this->sessionid))['mhs_id'];
    }
}