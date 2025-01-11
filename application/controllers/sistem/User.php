<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends KZ_Controller {

    private $module = 'sistem/user';
    private $module_do = 'sistem/user_do'; 
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_user','m_group'));
    }
    function index() {
        $this->data['group'] = $this->m_group->getAll();
        
        $this->data['module'] = $this->module;
        $this->data['title'] = array('User','List Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('sistem/user/v_index', $this->data);
    }
    function add() {
        $this->data['group'] = $this->m_group->getAll();
        
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('User','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('sistem/user/v_tambah', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['group'] = $this->m_group->getAll();
        $this->data['user'] = $this->m_user->getId(decode($id));
        
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('User','Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('sistem/user/v_ubah', $this->data);
    }
    function detail($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['user'] = $this->m_user->getAll(array('u.id_user' => decode($id)))['data'][0];
        $this->data['log'] = $this->m_user->getLog(array('user_id' => decode($id)));
        
        $this->data['title'] = array('User','Detail Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('sistem/user/v_detail', $this->data);
    }
    function delete($id = NULL) {
        if($id == NULL){
            redirect($this->module);
        }
        $result = $this->m_user->delete(decode($id));
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
        if($routing_module['type'] == 'list') {
            //LIST
            if($routing_module['source'] == 'user') {
                $this->_list_user();
            }
        }else if ($routing_module['type'] == 'reset') {
            //AKSI
            if ($routing_module['source'] == 'log') {
                $this->_reset_log();
            }
        }
    }
    function _reset_log() {
        $hari = $this->input->post('hari', TRUE);
        
        $result = $this->m_user->resetLog(intval($hari));
        if($result > 0){
            jsonResponse(array('data' => $result, 'status' => TRUE));
        }else{
            jsonResponse(array('data' => $result, 'status' => FALSE));
        }
    }
    function _list_user() {
        $where = null;
        $group = $this->input->post('group');
        
        if ($group != '') {
            $where['u.id_group'] = $group;
        }
        $list = $this->m_user->get_datatables($where);
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $items) {
            $no++;
            $row = array();
            
            $aksi = '<div class="action-buttons">
                        <a href="'. site_url($this->module .'/detail/'. encode($items['id_user'])) .'" class="tooltip-info btn btn-white btn-info btn-sm btn-round" data-rel="tooltip" title="Lihat Data">
                            <span class="blue"><i class="ace-icon fa fa-search-plus bigger-130"></i></span>
                        </a>
                        <a href="'. site_url($this->module .'/edit/'. encode($items['id_user'])) .'" class="tooltip-warning btn btn-white btn-warning btn-sm btn-round" data-rel="tooltip" title="Ubah Data">
                            <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-130"></i></span>
                        </a>
                        <a href="#" name="'. encode($items['id_user']) .'" itemprop="'. ctk($items['fullname']) .'" id="delete-btn" class="tooltip-error btn btn-white btn-danger btn-sm btn-round" data-rel="tooltip" title="Hapus Data">
                            <span class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i></span>
                        </a>
                    </div>';
            
            $row[] = ctk($no);
            $row[] = ctk($items['fullname']);
            $row[] = ctk($items['username']);
            $row[] = ctk($items['nama_group']);
            $row[] = st_aktif($items['status_user']);
            $row[] = format_date($items['buat_user'], 2);
            $row[] = is_online($items['last_login']).
                    '<br/><span>'.$items['ip_user'].'</span>';
            $row[] = $aksi;

            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->m_user->count_all(),
            "recordsFiltered" => $this->m_user->count_filtered($where),
            "data" => $data,
        );
        jsonResponse($output);
    }
}
