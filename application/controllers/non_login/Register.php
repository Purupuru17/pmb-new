<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends KZ_Controller {   
    
    private $module = 'non_login/register';
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_mhs','m_prodi'));
    }
    function index() {
        !empty($this->sessionid) ? redirect('beranda') : null;
        
        $this->load->library(array('recaptcha'));
        $this->load->helper(array('form'));
        
        $this->data['prodi'] = $this->m_prodi->getAll();
        $this->data['captcha'] = $this->recaptcha->getWidget();
        $this->data['script_captcha'] = $this->recaptcha->getScriptTag();
        
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Mahasiswa Baru','Pendaftaran');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->data['title'][0], 'url'=>'#'),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('non_login/v_register', $this->data);
        //$this->load_home('home/h_count', $this->data);
    }
    function add() {
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module);
        }
        $user['id_group'] = 4;
        $user['id_user'] = random_string('unique');
        $user['fullname'] = strtoupper($this->input->post('nama'));
        $user['username'] = $this->input->post('kode');
        $user['password'] = password_hash(preg_replace('/\s/', '', $this->input->post('confirm')), PASSWORD_DEFAULT);
        $user['status_user'] = '1';
        $user['log_user'] = 'Registrasi Akun';
        $user['ip_user'] = ip_agent();
        $user['buat_user'] = date('Y-m-d H:i:s');
        
        $prodi[] = $this->input->post('opsi2');
        $prodi[] = $this->input->post('opsi3');
        
        $data['opsi_prodi'] = implode("|", $prodi); 
        $data['prodi_id'] = decode($this->input->post('opsi1'));
        $data['jalur_mhs'] = $this->input->post('jalur');
        $data['id_mhs'] = $user['id_user'];
        $data['nama_mhs'] = $user['fullname'];
        $data['ibu_kandung'] = strtoupper($this->input->post('ibu'));
        $data['nik'] = $this->input->post('nik');
        $data['kode_reg'] = $user['username'];
        $data['telepon_mhs'] = $this->input->post('telepon');
        $data['status_mhs'] = 'PENDING';
        $data['kip_mhs'] = 'PENDING';
        $data['angkatan'] = (date('m') == '12') ? date('Y') + 1 : date('Y');
        $data['tgl_daftar'] = date('Y-m-d H:i:s');
        $data['set_by'] = '1';
        
        $this->db->trans_start();
        //Insert User
        $this->db->insert('yk_user', $user);
        //Insert Mahasiswa
        $this->db->insert('m_mhs', $data);
        //Insert TMP
        $this->db->insert('tmp_mhs', array('user_id' => $user['id_user'], 'mhs_id' => $data['id_mhs']));
        $this->db->trans_complete();
        
        if ($this->db->trans_status()) {
            $this->_auto_login($user['username']);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Pendaftaran gagal. Mohon lengkapi data anda dengan baik!'));
            redirect($this->module);
        }
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if ($routing_module['type'] == 'action') {
            //ACTION
            if ($routing_module['source'] == 'kode') {
                $this->_generate_kode();
            }
        }
    }
    //function
    function _generate_kode() {
        if(!$this->fungsi->Validation($this->rules_ajax,'ajax')) {
            jsonResponse(array('status' => false, 'msg' => validation_errors()));
        }
        $rs = $this->m_mhs->getNomor();
        if(!empty($rs)) {
            jsonResponse(array('status' => true, 'data' => strval($rs), 'msg' => 'Kode Registrasi berhasil dibuat'));
        }else{
            jsonResponse(array('status' => false, 'msg' => 'Kode Registrasi gagal dibuat. Silahkan ulangi kembali'));
        }
    }
    function _auto_login($str) {
        $this->load->model(array('m_authentication','m_user'));
        $this->load->library(array('session'));
        
        $data = $this->m_authentication->getAuth($str);
        $this->session->set_userdata(array(
            'logged' => true,
            'id' => $data['id_user'],
            'name' => $data['fullname'],
            'usr' => $data['username'],
            'groupid' => $data['id_group'],
            'foto' => $data['foto_user']
        ));
        $usr['last_login'] = date('Y-m-d H:i:s');
        $usr['ip_user'] = ip_agent();
        $usr['log_user'] = $data['fullname'] . ' baru saja membuat akun';
        
        $rs = $this->m_user->update($data['id_user'], $usr, 1);
        if($rs){
           $this->session->set_flashdata('notif', notif('success', 'Pendaftaran Berhasil', 'Selamat Datang, '.$data['fullname']));
           redirect('beranda'); 
        }   
    }
    function _valid_kode($str) {
        $this->load->model(array('m_authentication'));
        
        $check = $this->m_mhs->isExist(array('kode_reg' => $str));
        $user = $this->m_authentication->getAuth($str);
        
        if($check > 0 || sizeof($user) > 0) {
            $this->form_validation->set_message("_valid_kode", "Kolom {field} yang anda input sudah terdaftar di sistem kami. 
                Mohon ulangi Pendaftaran kembali");
            return FALSE;
        }else{
            return TRUE;
        }
    }
    function _valid_nik($str) {
        $check = $this->m_mhs->isExist(array('nik' => $str));
        if($check > 0) {
            $this->form_validation->set_message("_valid_nik", "Nomor Induk Kependudukan (NIK) : ".$str." ini sudah terdaftar sebelumnya. 
                Apabila tidak dapat mengakses, silahkan Reset Password akun");
            return FALSE;
        }else {
            return TRUE;
        }
    }
    function _valid_phone($str) {
        $check = $this->m_mhs->isExist(array('telepon_mhs' => $str));
        if($check > 0) {
            $this->form_validation->set_message("_valid_phone", "Nomor Telepon : ".$str." ini sudah terdaftar sebelumnya");
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
            $this->form_validation->set_message('_captcha_google', 'Berikan tanda centang apabila anda bukan Robot');
            return FALSE;
        }
    }
    private $rules = array(
        array(
            'field' => 'opsi1',
            'label' => 'Pilihan Pertama',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'opsi2',
            'label' => 'Pilihan Kedua',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'opsi3',
            'label' => 'Pilihan Ketiga',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'jalur',
            'label' => 'Jalur Pendaftaran',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'nik',
            'label' => 'Nomor Induk Kependudukan',
            'rules' => 'required|trim|xss_clean|is_natural|min_length[16]|max_length[16]|callback__valid_nik'
        ),array(
            'field' => 'nama',
            'label' => 'Nama Lengkap',
            'rules' => 'required|trim|xss_clean|min_length[3]'
        ),array(
            'field' => 'ibu',
            'label' => 'Nama Ibu Kandung',
            'rules' => 'required|trim|xss_clean|min_length[3]'
        ),array(
            'field' => 'telepon',
            'label' => 'Nomor Telepon',
            'rules' => 'required|trim|xss_clean|is_natural|min_length[11]|max_length[12]|callback__valid_phone'
        ),array(
            'field' => 'kode',
            'label' => 'Kode Registrasi',
            'rules' => 'required|trim|xss_clean|min_length[5]|callback__valid_kode'
        ),array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'confirm',
            'label' => 'Konfirmasi Password',
            'rules' => 'required|trim|xss_clean|min_length[5]|matches[password]'
        ),array(
            'field' => 'g-recaptcha-response',
            'label' => 'Pengecekan Keamanan',
            'rules' => 'trim|required|xss_clean|callback__captcha_google' 
        )
    );
    private $rules_ajax = array(
        array(
            'field' => 'opsi1',
            'label' => 'Pilihan Pertama',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'opsi2',
            'label' => 'Pilihan Kedua',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'opsi3',
            'label' => 'Pilihan Ketiga',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'jalur',
            'label' => 'Jalur Pendaftaran',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'nik',
            'label' => 'Nomor Induk Kependudukan',
            'rules' => 'required|trim|xss_clean|is_natural|min_length[16]|max_length[16]|callback__valid_nik'
        ),array(
            'field' => 'nama',
            'label' => 'Nama Lengkap',
            'rules' => 'required|trim|xss_clean|min_length[3]'
        ),array(
            'field' => 'ibu',
            'label' => 'Nama Ibu Kandung',
            'rules' => 'required|trim|xss_clean|min_length[3]'
        ),array(
            'field' => 'telepon',
            'label' => 'Nomor Telepon',
            'rules' => 'required|trim|xss_clean|is_natural|min_length[11]|max_length[12]|callback__valid_phone'
        )
    );
}
