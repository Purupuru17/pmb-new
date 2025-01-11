<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Notif extends KZ_Controller {

    private $module = 'sistem/notif';
    private $module_do = 'sistem/notif_do';  
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_notif'));
    }
    function index() {
        $this->load->model(array('m_group'));
        
        $group = $this->m_group->getId($this->sessiongroup);
        $notif = $this->m_notif->getAll();
        if($group['level'] != '1'){
            $notif = $this->m_notif->getAll(array('n.send_id' => $this->sessionid));
        }
        $this->data['notif'] = $notif;
        $this->data['admin'] = $group['level'] ;
        
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Notifikasi','List Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('sistem/notif/v_index', $this->data);
    }
    function add() {
        $this->load->model(array('m_user'));
        
        $this->data['notif'] = $this->m_notif->getEmpty();
        $this->data['user'] = $this->m_user->getAll();
        
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Notifikasi','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('sistem/notif/v_form', $this->data);
    }
    function delete($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $result = $this->m_notif->delete(decode($id));
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil dihapus'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal dihapus'));
            redirect($this->module);
        }
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if ($routing_module['type'] == 'action') {
            //AKSI
            if ($routing_module['source'] == 'delete') {
                $this->_delete_all();
            }
        }
    }
    function _delete_all() {
        $id = $this->input->post('id');

        if($id === '' || empty($id)){
            jsonResponse(array('data' => NULL, 'msg' => 'Tidak ada data yang dipilih' ,'status' => FALSE));
        }   
        $data = array();
        $post = explode(",", $id);
        foreach ($post as $val) {
            $data[] = decode($val);
        }
        $result = $this->m_notif->delete($data);
        if($result) {
            jsonResponse(array('data' => 1, 'msg' => 'Notifikasi berhasil dihapus' ,'status' => TRUE));
        }else{
            jsonResponse(array('data' => NULL, 'msg' => 'Notifikasi gagal dihapus' ,'status' => FALSE));
        }
    }
}
