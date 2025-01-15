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
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Notifikasi','List Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('sistem/notif/v_index', $this->data);
    }
    function add() {
        $this->data['notif'] = $this->m_notif->getEmpty();
        
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
        if($routing_module['type'] == 'table') {
            //TABLE
            if($routing_module['source'] == 'index') {
                $this->_table_index();
            }
        }else if ($routing_module['type'] == 'action') {
            //AKSI
            if ($routing_module['source'] == 'delete') {
                $this->_delete_all();
            }
        }
    }
    function _table_index() {
        $where = null;
        if($this->sessionlevel != '1'){
            $where['n.send_id'] = $this->sessionid;
        }
        $list = $this->m_notif->getAll($where);
        if($list['rows'] < 1){
            jsonResponse(array('status' => false, 'msg' => 'Data tidak ditemukan'));
        }
        $data = array('table' => array());
        $no = 1;
        foreach ($list['data'] as $items) {
            $row = array();
            
            $aksi = ($this->sessionlevel != '1') ? '' : '<a href="'. site_url($items['link_notif']) .'" name="'. encode($items['id_notif']).'"
                    class="tooltip-warning btn btn-white btn-warning btn-sm btn-round" id="link-btn" data-rel="tooltip" title="Link">
                    <span class="orange"><i class="ace-icon fa fa-external-link bigger-120"></i></span>
                </a>'; 
            $aksi .= '<a href="#" name="'.encode($items['id_notif']).'" itemprop="'.$items['fullname'].' - '.$items['subject_notif'].'" id="delete-btn" 
                class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Hapus Data">
                    <span class="red"><i class="ace-icon fa fa-trash-o"></i></span>
                </a>';
            $box = '<label class="pos-rel">
                <input value="'.encode($items['id_notif']).'" type="checkbox" class="ace" id="checkboxData" name="dataCheckbox[]" />
                <span class="lbl"></span></label>';
            $status = ($items['status_notif'] == '1') ? '<i class="bigger-120 fa fa-check green"></i>' : '';
            $message = !in_array($items['subject_notif'], array('BNI','BMI','BRI','BTN')) ? $items['msg_notif']
                : '<span id="log-msg" itemid="'.$items['subject_notif'].'" itemname="'. base64_encode($items['msg_notif']).'">'.limit_text($items['msg_notif'],100).'</span>';
            $row[] = $status.' '.$no.' '.$box;
            $row[] = '<strong>'.$items['fullname'].'</strong>';
            $row[] = '<strong>'.$items['subject_notif'].'</strong>';
            $row[] = $message;
            $row[] = '<small>'.format_date($items['buat_notif'],0).'</small>';
            $row[] = '<div class="action-buttons">'.$aksi.'</div>';
            
            $data['table'][] = $row;
            $no++;
        }
        jsonResponse(array('data' => $data, 'status' => true, 'msg' => 'Data ditemukan'));
    }
    function _delete_all() {
        $id = $this->input->post('id');

        if(empty($id)){
            jsonResponse(array('msg' => 'Tidak ada data yang dipilih' ,'status' => FALSE));
        }
        $result = $this->m_notif->deleteAll($id);
        if($result) {
            jsonResponse(array('msg' => 'Notifikasi berhasil dihapus' ,'status' => TRUE));
        }else{
            jsonResponse(array('msg' => 'Notifikasi gagal dihapus' ,'status' => FALSE));
        }
    }
}
