<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Akses extends KZ_Controller {
    
    private $module = 'sistem/akses';
    private $module_do = 'sistem/akses_do';
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_menu', 'm_group'));
    }
    function index() {
        $this->data['module'] = $this->module;
        $this->data['group'] = $this->m_group->getAll();
        
        $this->data['title'] = array('Hak Akses','Daftar Group');
        $this->data['breadcrumb'] = array( 
            array('title'=> $this->uri->segment(1), 'url'=>'#'),
            array('title'=>'Hak Akses', 'url'=> '')
        );
        $this->load_view('sistem/akses/v_index', $this->data);
    }
    function add($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['group'] = $this->m_group->getId(decode($id));
        $this->data['role'] = $this->m_group->getRole(array('r.group_id' => decode($id)));
        
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Hak Akses','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>'Hak Akses', 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('sistem/akses/v_add', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['group'] = $this->m_group->getId(decode($id));
        $this->data['akses'] = $this->m_menu->getAuthenticationMenu(decode($id));
        
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('Hak Akses','Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>'Hak Akses', 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('sistem/akses/v_ubah', $this->data);
    }
    function delete($id, $user) {
        if(empty(decode($id)) || is_null($user)){
            redirect($this->module);
        }
        $result = $this->m_group->deleteRole(decode($id),decode($user));
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil dihapus'));
            redirect($this->module.'/add/'.$id);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal dihapus'));
            redirect($this->module.'/add/'.$id);
        }
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if($routing_module['type'] == 'list') {
            //TABLE
            if($routing_module['source'] == 'user') {
                $this->_get_user();
            }
        }
    }
    //function
    function _get_user(){
        $key = $this->input->post('key');
        
        $result = $this->db->like('fullname', $key, 'both')
            ->get_where('yk_user')->result_array();
        $data = array();
        foreach ($result as $val) {
            $text = ctk($val['fullname']).' ['.ctk($val['username']).']';
            $data[] = array("id" => encode($val['id_user']), "text" => $text);
        }
        jsonResponse($data);
    }
}
