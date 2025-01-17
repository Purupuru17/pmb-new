<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Ifsnop\Mysqldump as IMysqldump;

class Login extends KZ_Controller {
    
    private $module = 'non_login/login';
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_authentication', 'm_user'));
    }
    function index() {
        !empty($this->sessionid) ? redirect('beranda') : null;
        
        $this->load->library(array('recaptcha'));
        
        $data['captcha'] = $this->recaptcha->getWidget();
        $data['script_captcha'] = $this->recaptcha->getScriptTag();
        $data['app'] = $this->session->userdata('app');
        $data['theme'] = explode(",",$data['app']['tema']);
        $data['module'] = $this->module;

        $this->data['content'] = $this->load->view('non_login/v_login', $data, TRUE);
        $this->load->view('non_login/v_template', $this->data);
    }
    function changed($id = NULL, $level = NULL) {
        if(empty(decode($id)) || empty($level)){
            redirect('beranda');
        }
        $this->load->model(array('m_group'));
        
        $role = $this->m_group->getRole(array('r.user_id' => $this->sessionid,'r.group_id' => decode($id)));
        if($role['rows'] > 0){
            
            $this->session->set_userdata(array(
                'logged' => true, 'id' => $this->sessionid,
                'name' => $this->sessionname, 'usr' => $this->sessionusr,
                'groupid' => decode($id), 'level' => decode($level), 'foto' => $this->sessionfoto
            ));
            $usr['last_login'] = date('Y-m-d H:i:s');
            $usr['ip_user'] = ip_agent();
            $usr['log_user'] = $this->sessionname . ' Login Sistem with Switch Account';
            
            $this->m_user->update($this->sessionid, $usr, 1);

            $this->session->set_flashdata('notif', notif('info', 'Selamat datang kembali', $this->sessionname));
            redirect('beranda');
        }else{
            redirect('home/err_module');
        }        
    }
    function logout() {
        session_destroy();
        redirect();
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if ($routing_module['type'] == 'list') {
            //LIST
            if ($routing_module['source'] == 'autoload') {
                $this->_autoload_module();
            }
        } else if ($routing_module['type'] == 'action') {
            //ACTION
            if ($routing_module['source'] == 'auth') {
                $this->_auth_login();
            }else if ($routing_module['source'] == 'cron') {
                $this->_backup_db();
            }
        }
    }
    //function
    function _auth_login() {
        $this->load->library(array('form_validation'));
        
        $this->form_validation->set_rules($this->rules)->set_error_delimiters('', '');
        if ($this->form_validation->run() == FALSE) {
            jsonResponse(array('status' => FALSE, 'msg' => validation_errors()));
        }
        $data = $this->m_authentication->getAuth($this->input->post('username'));
        $this->session->set_userdata(array(
            'logged' => true, 'id' => $data['id_user'], 'name' => $data['fullname'],
            'usr' => $data['username'], 'groupid' => $data['id_group'],'level' => $data['level'], 'foto' => $data['foto_user']
        ));
        $usr['last_login'] = date('Y-m-d H:i:s');
        $usr['ip_user'] = ip_agent();
        $usr['log_user'] = $data['fullname'] . ' Login Sistem';

        $this->m_user->update($data['id_user'], $usr, 1);

        $this->session->set_flashdata('notif', notif('info', 'Selamat datang kembali', $data['fullname']));
        jsonResponse(array('data' => site_url('beranda'), 'status' => TRUE, 'msg' => 'Selamat datang kembali, '.$data['fullname']));
    }
    function _autoload_module() {
        if(!$this->loggedin){
            jsonResponse(array('item' => 0 ,'status' => false));
        }
        $this->load->model(array('m_notif'));
        //klik notifikasi
        $id = $this->input->post('id');
        if(!empty(decode($id))){
            $this->m_notif->update(decode($id), array('status_notif' => '1'));
            jsonResponse(array('data' => null, 'item' => 0 ,'status' => false, 'msg' => 'Klik Notification'));
        }
        //update login
        $this->m_user->update($this->sessionid, array('last_login' => date('Y-m-d H:i:s')));
        //update notifikasi
        $result = $this->m_notif->getAll(array('status_notif' => '0'), 10);
        if($result['rows'] < 1){
            jsonResponse(array('data' => null, 'item' => 0 ,'status' => false, 'msg' => 'Empty Notification'));
        }
        $data = array();
        $html = '';
        foreach ($result['data'] as $item) {
            $status = ($item['status_notif'] == '0') ? 'unread' : '';
            $html .= '<li id="'.encode($item['id_notif']).'" class="'.$status.'">
                <a href="'.site_url($item['link_notif']).'" class="clearfix"><span class="msg-body" style="margin-left:5px">
                    <span class="msg-title"><span class="blue bigger-110 bolder">'.$item['subject_notif'].'</span><br/>
                    <span class="grey">'.$item['msg_notif'].'</span></span><span class="msg-time">
                        <i class="smaller-90 ace-icon fa fa-clock-o"></i>
                    <span class="">'.selisih_wkt($item['buat_notif']).'</span></span></span>
                </a></li>';
            $data[] = $item;
        }
        jsonResponse(array('data' => $data, 'html' => $html, 'item' => $result['rows'] ,'status' => true));
    }
    function _backup_db() {
        $this->load->helper(array('download'));
        $is_cli = $this->input->is_cli_request();
        if(!$is_cli){
            //exit('404 not found');
        }
        $title = url_title(APP_NAME.'db '. format_date(date('Y-m-d H:i:s'),2),'-',true);
        $file = "app/log/{$title}.sql";
        $table = array('m_mhs','yk_user_log','yk_site_log');
        
        $dumpSettings = array('exclude-tables' => $table);
        try {
            $dump = new IMysqldump\Mysqldump($this->db->dsn, $this->db->username, $this->db->password, $dumpSettings);
            $dump->start($file);
            force_download($file, NULL);
        } catch (\Exception $e) {
            echo $e->getMessage();
            exit();
        }
    }
    function _validate() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        
        $data = $this->m_authentication->getAuth($username);
        if (sizeof($data) < 1) {
            $this->form_validation->set_message("_validate", "Username anda belum terdaftar di sistem kami");
            return FALSE;
        }   
        if($data['status_user'] == '0') {
            $this->form_validation->set_message("_validate", "Mohon maaf untuk sementara Akun tidak aktif. Hubungi Administrator");
            return FALSE;
        }
        if(!password_verify($password, $data['password'])){
            $this->form_validation->set_message("_validate", "Password yang anda masukkan salah");
            return FALSE;
        }
        return TRUE;           
    }
    function _valid_nik($str) {
        $check = $this->db->get_where('m_pmm',array('nik_pmm' => $str));
        if($check->num_rows() > 0) {
            $this->form_validation->set_message("_valid_nik", "Nomor Induk Kependudukan (NIK) ini sudah tersimpan sebelumnya. 
                Apabila tidak dapat mengakses, segera hubungi pihak MBKM UNIMUDA");
            return FALSE;
        }else {
            return TRUE;
        }
    }
    function _valid_phone($str) {
        $check = $this->db->get_where('m_pmm',array('telepon_pmm' => $str));
        if($check->num_rows() > 0) {
            $this->form_validation->set_message("_valid_phone", "Nomor Telepon ini sudah tersimpan sebelumnya");
            return FALSE;
        } else {
            return TRUE;
        }
    }
    function _captcha_google($str){
        $this->load->library('recaptcha');
        $rs = $this->recaptcha->verifyResponse($str);
        if($rs['success']){
            return TRUE;
        }else{
            $this->form_validation->set_message('_captcha_google', 'Berikan tanda centang terlebih dahulu');
            return FALSE;
        }
    }
    private $rules = array(
        array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'required|trim|xss_clean|min_length[5]|callback__validate'
        ),array(
            'field' => 'g-recaptcha-response',
            'label' => 'Pengecekan Keamanan',
            'rules' => 'required|trim|xss_clean|callback__captcha_google' 
        )
    );
}
