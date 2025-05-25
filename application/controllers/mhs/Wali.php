<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Wali extends KZ_Controller {
    
    private $module = 'mhs/wali';
    private $module_do = 'mhs/wali_do';
    
    function __construct() {
        parent::__construct();
        
        $this->load->model(array('m_ortu'));
        $this->_getMhs();
        if(empty($this->mid)){
            redirect('beranda');
        }
    }
    function index() {
        $this->data['list'] = $this->m_ortu->getAll(array('mhs_id' => $this->mid));

        $this->data['module'] = $this->module;
        $this->data['title'] = array('Orang Tua/Wali', 'List Data');
        $this->data['breadcrumb'] = array( 
            array('title'=> 'Mahasiswa', 'url'=>'#'),
            array('title'=> 'Orang Tua/Wali', 'url'=> '')
        );
        $this->load_view('mhs/wali/v_index', $this->data);
    }
    function add() {
        $this->data['edit'] = $this->m_ortu->getEmpty();
        
        $this->data['didik'] = load_array('didik');
        $this->data['kerja'] = load_array('kerja');
        $this->data['golongan'] = load_array('golongan');
        
        $this->data['action'] = $this->module_do.'/add/';
        $this->data['title'] = array('Orang Tua/Wali','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>'Mahasiswa', 'url'=>'#'),
            array('title'=>'Orang Tua/Wali', 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('mhs/wali/v_form', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['edit'] = $this->m_ortu->getId(decode($id));
        
        $this->data['didik'] = load_array('didik');
        $this->data['kerja'] = load_array('kerja');
        $this->data['golongan'] = load_array('golongan');
        
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('Orang Tua/Wali','Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>'Mahasiswa', 'url'=>'#'),
            array('title'=>'Orang Tua/Wali', 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('mhs/wali/v_form', $this->data);
    }
    function delete($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $result = $this->m_ortu->delete(decode($id));
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil dihapus'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal dihapus'));
            redirect($this->module);
        }
    }
}
