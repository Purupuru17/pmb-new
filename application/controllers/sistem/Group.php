<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends KZ_Controller {

    private $module = 'sistem/group';
    private $module_do = 'sistem/group_do';    
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_group'));
    }
    function index() {
        $this->data['module'] = $this->module;
        $this->data['group'] = $this->m_group->getAll();

        $this->data['title'] = array('Group','List Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('sistem/group/v_index', $this->data);
    }
    function add() {
        $this->data['group'] = $this->m_group->getEmpty();
        
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Group','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('sistem/group/v_form', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['group'] = $this->m_group->getId(decode($id));
        
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('Group','Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('sistem/group/v_form', $this->data);
    }
    function delete($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $result = $this->m_group->delete(decode($id));
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil dihapus'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal dihapus'));
            redirect($this->module);
        }
    }
}
