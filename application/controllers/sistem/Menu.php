<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends KZ_Controller {
    
    private $module = 'sistem/menu';
    private $module_do = 'sistem/menu_do'; 
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_menu'));
    }
    function index() {
        $data = array();
        foreach ($this->m_menu->getAll()['data'] as $val) {
            $row = array();
            $row['a'] = $val;
            $row['b'] = $this->m_menu->getId($val['parent_menu']);
            
            $data[] = $row;
        }
        $this->data['module'] = $this->module;
        $this->data['menu'] = $data;
        
        $this->data['title'] = array('Menu','List Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('sistem/menu/v_index', $this->data);
    }
    function add() {
        $this->data['menu'] = $this->m_menu->getEmpty();
        $this->data['aksi'] = $this->m_menu->getAksiEmpty();
        $this->data['parent'] = $this->m_menu->getParent();
        
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Menu','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('sistem/menu/v_form', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['menu'] = $this->m_menu->getId(decode($id));
        $this->data['aksi'] = $this->m_menu->getMenuAksi(decode($id));
        $this->data['parent'] = $this->m_menu->getParent();
        
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('Menu','Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('sistem/menu/v_form', $this->data);
    }
    function delete($id) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $result = $this->m_menu->deleteSub(decode($id));
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil dihapus'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal dihapus'));
            redirect($this->module);
        }
    }
}
