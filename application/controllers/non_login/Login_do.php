<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login_do extends KZ_Controller {
    private $module = 'non_login/login';
    private $beranda = 'non_login/beranda';
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_authentication', 'm_user'));
    }
    function auth() {
        if(!$this->_validation($this->rules)){
            redirect($this->module);
        } else {
            redirect($this->beranda);
        }
    }
    function forgot() {
        $this->load->model(array('m_notif'));
        
        if(!$this->_validation($this->forgot)){
            redirect($this->module);
        }
        $user = $this->input->post('fuser');
        $rs = $this->m_authentication->getAuth($user);
        
        $random = random_string('numeric',6);
        $send = array('from_id' => $rs['id_user'], 'send_id' => 1,
            'subject' => 'Reset Password', 'msg' => $rs['fullname'].' melakukan Reset password akun','link' => null
        );
        $result = $this->m_user->update($rs['id_user'], array('password' => password_hash($random, PASSWORD_DEFAULT)));
        if($result){
            //notifikasi
            $this->m_notif->insertAll($send, 1);
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Password berhasil di reset. <br/>
                Mohon catat informasi Akun berikut agar tidak lupa.<br/>
                <strong class="">Username : '.$user.'
                <br/>Password : '.$random.'</strong>'));
        }else{
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Maaf Password anda gagal di reset, ulangi kembali.'));
        }
        redirect($this->module);
    }
    function changed($id = NULL) {
        if(empty(decode($id))){
            redirect($this->beranda);
        }
        $this->load->model(array('m_group'));
        
        $role = $this->m_group->getRole(array('r.user_id' => $this->sessionid,'r.group_id' => decode($id)));
        if($role['rows'] > 0){
            
            $this->load->library(array('session'));
                
            $this->session->set_userdata(array(
                'logged' => true,
                'id' => $this->sessionid,
                'name' => $this->sessionname,
                'usr' => $this->sessionusr,
                'groupid' => decode($id),
                'foto' => $this->sessionfoto
            ));
            $usr['last_login'] = date('Y-m-d H:i:s');
            $usr['ip_user'] = ip_agent();
            $usr['log_user'] = $this->sessionname . ' Login Sistem with Switch Account';
            $this->m_user->update($this->sessionid, $usr, 1);

            $this->session->set_flashdata('notif', notif('info', 'Selamat datang kembali', $this->sessionname));
            redirect($this->beranda);
        }else{
            redirect('non_login/beranda/err_module');
        }        
    }
    //Callback Function
    function _validate() {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        
        $data = $this->m_authentication->getAuth($username);
        if (sizeof($data) > 0) {
            
            if($data['status_user'] == '0') {
                $this->form_validation->set_message("_validate", "Mohon maaf, untuk sementara Akun anda tidak aktif. Hubungi Administrator");
                return FALSE;
            }
            if(password_verify($password, $data['password'])){
                
                $this->load->library(array('session'));
                
                $this->session->set_userdata(array(
                    'logged' => true,
                    'id' => $data['id_user'],
                    'name' => $data['fullname'],
                    'usr' => $data['username'],
                    'groupid' => $data['id_group'],
                    'foto' => $data['foto_user'],
                    'periode' => date('Y')
                ));
                $usr['last_login'] = date('Y-m-d H:i:s');
                $usr['ip_user'] = ip_agent();
                $usr['log_user'] = $data['fullname'] . ' Login Sistem';
                $this->m_user->update($data['id_user'], $usr, 1);

                $this->session->set_flashdata('notif', notif('info', 'Selamat datang kembali', $data['fullname']));
                return TRUE;
            }else{
                $this->form_validation->set_message("_validate", "Password yang anda masukkan salah");
                return FALSE;
            }
        } else {
            $this->form_validation->set_message("_validate", "Email atau Username anda belum terdaftar di sistem kami");
            return FALSE;
        }
    }
    function _unique() {
        $this->load->model(array('m_mhs'));
        
        $user = $this->m_mhs->isExist(array('kode_reg' => $this->input->post('fuser')));
        if ($user < 1) {
            $this->form_validation->set_message("_unique", "Username atau Kode Registrasi belum terdaftar di sistem kami");
            return FALSE;
        }
        $nik = $this->m_mhs->isExist(array('nik' => $this->input->post('fnik')));
        if ($nik < 1) {
            $this->form_validation->set_message("_unique", "NIK anda belum terdaftar di sistem kami");
            return FALSE;
        }
        $phone = $this->m_mhs->isExist(array('telepon_mhs' => $this->input->post('fphone')));
        if ($phone < 1) {
            $this->form_validation->set_message("_unique", "Nomor HP anda belum terdaftar di sistem kami");
            return FALSE;
        }
        return TRUE;
    }
    private $rules = array(
        array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'required|trim|xss_clean|min_length[5]|callback__validate'
        )
    );     
    private $forgot = array(
        array(
            'field' => 'fuser',
            'label' => 'Username atau Kode Registrasi',
            'rules' => 'required|trim|xss_clean|min_length[5]|callback__unique'
        ),array(
            'field' => 'fnik',
            'label' => 'NIK Terdaftar',
            'rules' => 'required|trim|xss_clean|is_natural|min_length[16]|max_length[16]'
        ),array(
            'field' => 'fphone',
            'label' => 'Nomor HP Terdaftar',
            'rules' => 'required|trim|xss_clean|is_natural|min_length[11]|max_length[12]'
        )
    );
}
