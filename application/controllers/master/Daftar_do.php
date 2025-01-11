<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Daftar_do extends KZ_Controller {
    
    private $module = 'master/daftar';
    private $module_do = 'master/daftar_do';
    private $path = 'app/upload/mhs/';
            
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_mhs'));
    }
    function add() {
        if(!$this->_validation($this->rules)){
            redirect($this->module.'/add');
        }
        $data['prodi_id'] = decode($this->input->post('opsi1'));
        $prodi[] = $this->input->post('opsi2');
        $prodi[] = $this->input->post('opsi3');
        $data['angkatan'] = $this->input->post('tahun');
        $data['jalur_mhs'] = $this->input->post('jalur');
        $data['atribut_mhs'] = strtoupper($this->input->post('atribut'));
        
        $data['nisn'] = $this->input->post('nisn');
        $data['sekolah'] = strtoupper($this->input->post('sekolah'));
        $data['npsn'] = $this->input->post('npsn');
        
        $data['nik'] = $this->input->post('nik');
        $data['nama_mhs'] = strtoupper($this->input->post('nama'));
        $data['ibu_kandung'] = strtoupper($this->input->post('ibu'));
        $data['tempat_lahir'] = ucwords(strtolower($this->input->post('tempat')));
        $data['tgl_lahir'] = $this->input->post('lahir');
        $data['kelamin_mhs'] = $this->input->post('kelamin');
        $data['agama'] = $this->input->post('agama');
        $data['telepon_mhs'] = $this->input->post('telepon');
        $data['email_mhs'] = strtolower($this->input->post('email'));
        $data['alamat_mhs'] = ucwords(strtolower($this->input->post('alamat')));
        
        $data['jalan'] = ucwords(strtolower($this->input->post('jalan')));
        $data['rt'] = $this->input->post('rt');
        $data['rw'] = $this->input->post('rw');
        $data['kelurahan'] = ucwords(strtolower($this->input->post('lurah')));
        $data['kecamatan'] = $this->input->post('camat');
        $data['kabupaten'] = $this->input->post('bupati');

        $data['opsi_prodi'] = implode("|", $prodi); 
        $data['id_mhs'] = random_string('lownum',8).'-'.random_string('lownum',4).'-'.random_string('lownum',6);
        $data['kode_reg'] = $this->m_mhs->getNomor('UNMD');
        $data['status_mhs'] = 'PENDAFTARAN';
        $data['kip_mhs'] = 'PENDING';
        $data['tgl_daftar'] = date('Y-m-d H:i:s');
        $data['log_mhs'] = $this->sessionname.' menambahkan data';
        $data['set_by'] = '0';
        
        $user['id_group'] = 4;
        $user['fullname'] = $data['nama_mhs'];
        $user['username'] = $data['kode_reg'];
        $user['email'] = $data['email_mhs'];
        $user['password'] = password_hash($data['kode_reg'], PASSWORD_DEFAULT);
        $user['status_user'] = '1';
        $user['log_user'] = $data['log_mhs'];
        $user['ip_user'] = ip_agent();
        $user['buat_user'] = $data['tgl_daftar'];

        $cek = $this->m_mhs->isExist(array('nik' => $data['nik']));
        if($cek > 0){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data NIK sudah tersimpan sebelumnya'));
            redirect($this->module.'/add');
        }
        $result = $this->m_mhs->insertAll($data, $user);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil disimpan'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal disimpan'));
            redirect($this->module.'/add');
        }
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        if(!$this->_validation($this->rules)){
            redirect($this->module.'/edit/'.$id);
        }
        if(!$this->_validation($this->rules_edit)){
            redirect($this->module.'/edit/'.$id);
        }
        
        $data['prodi_id'] = decode($this->input->post('opsi1'));
        $prodi[] = $this->input->post('opsi2');
        $prodi[] = $this->input->post('opsi3');
        
        $data['opsi_prodi'] = implode("|", $prodi); 
        $data['status_mhs'] = $this->input->post('status');
        $data['jalur_mhs'] = $this->input->post('jalur');
        $data['angkatan'] = $this->input->post('tahun');
        $data['atribut_mhs'] = strtoupper($this->input->post('atribut'));
        $data['kip_mhs'] = $this->input->post('kip');
        
        $data['nisn'] = $this->input->post('nisn');
        $data['sekolah'] = strtoupper($this->input->post('sekolah'));
        $data['npsn'] = $this->input->post('npsn');
        
        $data['nik'] = $this->input->post('nik');
        $data['nama_mhs'] = strtoupper($this->input->post('nama'));
        $data['ibu_kandung'] = strtoupper($this->input->post('ibu'));
        $data['tempat_lahir'] = ucwords(strtolower($this->input->post('tempat')));
        $data['tgl_lahir'] = $this->input->post('lahir');
        $data['kelamin_mhs'] = $this->input->post('kelamin');
        $data['agama'] = $this->input->post('agama');
        $data['telepon_mhs'] = $this->input->post('telepon');
        $data['email_mhs'] = strtolower($this->input->post('email'));
        $data['alamat_mhs'] = ucwords(strtolower($this->input->post('alamat')));
        
        $data['jalan'] = ucwords(strtolower($this->input->post('jalan')));
        $data['rt'] = $this->input->post('rt');
        $data['rw'] = $this->input->post('rw');
        $data['kelurahan'] = ucwords(strtolower($this->input->post('lurah')));
        $data['kecamatan'] = $this->input->post('camat');
        $data['kabupaten'] = $this->input->post('bupati');
        
        $data['update_mhs'] = date('Y-m-d H:i:s');
        $data['log_mhs'] = $this->sessionname.' mengubah data';
        
        if($this->sessiongroup !== '1' && $data['status_mhs'] == 'AKTIF'){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Anda tidak memiliki akses untuk meng-AKTIF-kan mahasiswa'));
            redirect($this->module.'/edit/'.$id);
        }
        $cek = $this->m_mhs->getId(array('nisn' => $data['nisn']));
        if(!is_null($cek) && ($id != encode($cek['id_mhs']))){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data NISN sudah tersimpan atas nama : ' . $cek['nama_mhs']));
            redirect($this->module.'/edit/'.$id);
        }
        $result = $this->m_mhs->update(decode($id), $data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil diubah'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal diubah'));
            redirect($this->module.'/edit/'.$id);
        }
    }
    function detail($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        if(!$this->_validation($this->rules_berkas)){
            redirect($this->module.'/detail/'.$id);
        }
        $this->load->model(array('m_berkas'));
        $berkas_id = decode($this->input->post('berkas'));
        
        $data['status_berkas'] = $this->input->post('status');
        $data['update_berkas'] = date('Y-m-d H:i:s');
        $data['log_berkas'] = $this->sessionname.' mengubah data';
            
        $result = $this->m_berkas->update($berkas_id, $data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil diubah'));
            redirect($this->module.'/detail/'.$id);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal diubah'));
            redirect($this->module.'/detail/'.$id);
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
    private $rules_berkas = array(
        array(
            'field' => 'berkas',
            'label' => 'ID Berkas',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'status',
            'label' => 'Status Berkas',
            'rules' => 'required|trim|xss_clean'
        )
    );
    private $rules_edit = array(
        array(
            'field' => 'nisn',
            'label' => 'NISN',
            'rules' => 'required|callback__valid_zero'
        ),array(
            'field' => 'sekolah',
            'label' => 'Asal Sekolah',
            'rules' => 'required'
        ),array(
            'field' => 'npsn',
            'label' => 'NPSN Sekolah',
            'rules' => 'required|callback__valid_zero'
        )
    );
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
            'field' => 'status',
            'label' => 'Status Mahasiswa',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'jalur',
            'label' => 'Jalur Pendaftaran',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'tahun',
            'label' => 'Periode Masuk',
            'rules' => 'required|trim|xss_clean'
        ),
        // Data Pendidikan
        array(
            'field' => 'nisn',
            'label' => 'NISN',
            'rules' => 'trim|xss_clean|is_natural|min_length[10]|max_length[10]|callback__valid_zero'
        ),array(
            'field' => 'sekolah',
            'label' => 'Asal Sekolah',
            'rules' => 'trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'npsn',
            'label' => 'NPSN Sekolah',
            'rules' => 'trim|xss_clean|is_natural|min_length[8]|max_length[10]|callback__valid_zero'
        ),
        // Data Diri
        array(
            'field' => 'nik',
            'label' => 'NIK',
            'rules' => 'required|trim|xss_clean|is_natural|min_length[16]|max_length[16]'
        ),array(
            'field' => 'nama',
            'label' => 'Nama Mahasiswa',
            'rules' => 'required|trim|xss_clean|min_length[4]'
        ),array(
            'field' => 'ibu',
            'label' => 'Ibu Kandung',
            'rules' => 'required|trim|xss_clean|min_length[3]'
        ),array(
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
            'field' => 'telepon',
            'label' => 'Telepon',
            'rules' => 'required|trim|xss_clean|is_natural|min_length[11]|max_length[12]'
        ),array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|trim|xss_clean|callback__valid_email'
        ),array(
            'field' => 'alamat',
            'label' => 'Alamat',
            'rules' => 'required|trim|xss_clean|min_length[10]'
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
