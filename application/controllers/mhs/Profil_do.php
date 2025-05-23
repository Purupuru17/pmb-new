<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Profil_do extends KZ_Controller {
    
    private $module = 'mhs/profil';
    private $module_do = 'mhs/profil_do';
    private $path = 'upload/mhs/';
            
    function __construct() {
        parent::__construct();
        
        $this->load->model(array('m_mhs'));
        $this->_getMhs();
    }
    function edit() {
        if(!$this->_validation($this->rules)){
            redirect($this->module.'/edit');
        }
        $data['nisn'] = $this->input->post('nisn');
        $data['sekolah'] = strtoupper($this->input->post('sekolah'));
        $data['npsn'] = $this->input->post('npsn');
        
        $data['tempat_lahir'] = ucwords(strtolower($this->input->post('tempat')));
        $data['tgl_lahir'] = $this->input->post('lahir');
        $data['kelamin_mhs'] = $this->input->post('kelamin');
        $data['agama'] = $this->input->post('agama');
        $data['email_mhs'] = strtolower($this->input->post('email'));
        $data['alamat_mhs'] = ucwords(strtolower($this->input->post('alamat')));
        
        $data['jalan'] = ucwords(strtolower($this->input->post('jalan')));
        $data['rt'] = $this->input->post('rt');
        $data['rw'] = $this->input->post('rw');
        $data['kelurahan'] = ucwords(strtolower($this->input->post('lurah')));
        $data['kecamatan'] = $this->input->post('camat');
        $data['kabupaten'] = $this->input->post('bupati');
        
        if($this->input->post('status') == 'PENDING'){
            $data['status_mhs'] = 'PENDAFTARAN';
        }
        $data['update_mhs'] = date('Y-m-d H:i:s');
        $data['log_mhs'] = $this->sessionname.' mengubah profil';
        
        $cek = $this->m_mhs->getId(array('nisn' => $data['nisn']));
        if(!is_null($cek) && ($this->mid != $cek['id_mhs'])){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data NISN sudah tersimpan atas nama : ' . $cek['nama_mhs']));
            redirect($this->module.'/edit');
        }
        $this->load->library(array('storage'));
        $filename = url_title($this->input->post('nama').' '.random_string('alnum',3), 'dash', TRUE);
        //upload
        $path = array('local' => $this->path, 's3' => $this->sessionusr);
        if(!empty($_FILES['foto']['name'])){
            $upload =  $this->storage->putImg('foto', $filename, $path, 300);
            if(empty($upload->fullPath)){
                redirect($this->module);
            }
            $data['foto_mhs'] = $upload->customName;
            
            $old_img = $this->input->post('exfoto');
            delete_file($old_img); 
        }
        $result = $this->m_mhs->update($this->mid, $data);
        if ($result) {
            //update user
            $this->load->model(array('m_user'));
            $this->m_user->update($this->sessionid, array('email' => $data['email_mhs']));
        
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data mahasiswa berhasil diubah'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data mahasiswa gagal diubah'));
            redirect($this->module.'/edit');
        }
    }
    
    //function
    function _valid_date($tgl) {
        list($yyyy,$mm,$dd) = explode('-',$tgl);
        $now = intval(date('Y'));
        $min = $now - intval($yyyy);
        
        if(!checkdate($mm,$dd,$yyyy)) {
            $this->form_validation->set_message("_valid_date", "Kolom {field} tidak sesuai format.");
            return FALSE;
        }else if($min < 15 || $min > 60) {
            $this->form_validation->set_message("_valid_date", "Kolom {field} tidak sesuai usia anda. Min : 15 Tahun, Maks : 60 Tahun");
            return FALSE;
        }else {
            return TRUE;
        }
    }
    function _valid_email($address){
        if(!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) {
            $this->form_validation->set_message("_valid_email", "Kolom {field} anda tidak sesuai format.");
            return FALSE;
        }else{
            return TRUE;
        }
    }
    function _valid_zero($str) {
        if(strpos($str, '00000') !== false) {
            $this->form_validation->set_message("_valid_zero", "Format {field} tidak sesuai. Mohon input data sebenarnya");
            return FALSE;
        }else{
            return TRUE;
        }
    }
    private $rules = array(
        array(
            'field' => 'nisn',
            'label' => 'NISN',
            'rules' => 'required|trim|xss_clean|is_natural|min_length[10]|max_length[10]|callback__valid_zero'
        ),array(
            'field' => 'sekolah',
            'label' => 'Asal Sekolah    ',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'npsn',
            'label' => 'NPSN Sekolah',
            'rules' => 'required|trim|xss_clean|is_natural|min_length[8]|max_length[10]|callback__valid_zero'
        ),
        
        array(
            'field' => 'tempat',
            'label' => 'Tempat Lahir',
            'rules' => 'required|trim|xss_clean|min_length[3]'
        ),array(
            'field' => 'lahir',
            'label' => 'Tanggal Lahir',
            'rules' => 'required|trim|xss_clean|callback__valid_date'
        ),array(
            'field' => 'kelamin',
            'label' => 'Jenis Kelamin',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'agama',
            'label' => 'Agama',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|trim|xss_clean|callback__valid_email'
        ),array(
            'field' => 'alamat',
            'label' => 'Alamat',
            'rules' => 'required|trim|xss_clean|min_length[30]'
        ),
        
        array(
            'field' => 'jalan',
            'label' => 'Nama Jalan',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'rt',
            'label' => 'RT',
            'rules' => 'required|trim|xss_clean|is_natural'
        ),array(
            'field' => 'rw',
            'label' => 'RW',
            'rules' => 'required|trim|xss_clean|is_natural'
        ),array(
            'field' => 'lurah',
            'label' => 'Kelurahan',
            'rules' => 'required|trim|xss_clean|min_length[3]'
        ),array(
            'field' => 'camat',
            'label' => 'Kecamatan',
            'rules' => 'required|trim|xss_clean|min_length[3]'
        ),array(
            'field' => 'bupati',
            'label' => 'Kabupaten',
            'rules' => 'required|trim|xss_clean|min_length[3]'
        )
    );
}
