<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Profil_do extends KZ_Controller {
    
    private $module = 'mhs/profil';
    private $module_do = 'mhs/profil_do';
    private $url_route = array('id', 'source', 'type');
    private $path = 'upload/mhs/';
            
    function __construct() {
        parent::__construct();
        
        $this->load->model(array('m_mhs','m_jawab'));
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
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if($routing_module['type'] == 'action') {
            //ACTION
            if($routing_module['source'] == 'start') {
                $this->_start_jawab();
            }else if($routing_module['source'] == 'update') {
                $this->_update_jawab();
            }else if($routing_module['source'] == 'done') {
                $this->_done_jawab();
            }
        }
    }
    //function
    function _start_jawab() {
        if(empty($this->mid)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Tidak memiliki akses pada menu ini'));
        }
        $this->load->model(array('m_module'));
        
        $peserta_id = $this->mid;
        $id = decode($this->input->post('id'));
        //check module
        $module = $this->m_module->getId($id);
        if(empty($module)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Tidak ada sesi yang dipilih'));
        }
        $durasi = intval($module['durasi_module']);
        //check avalaible
        $is_range = range_date(date('Y-m-d H:i:s'), $module['buka_module'], $module['tutup_module']);
        if(!$is_range['st']){
            jsonResponse(array('status' => FALSE, 'msg' => $is_range['rs']));
        }
        //check jawab
        $jawab = $this->m_jawab->getId(array('module_id' => $id, 'peserta_id' => $peserta_id, 'valid_jawab' => '1'));
        if(!is_null($jawab)){
            switch ($jawab['status_jawab']) {
                case '0':
                    //lock 1 sesi    
                    if (!empty($jawab['session_jawab']) && ($jawab['session_jawab'] !== $this->session->userdata('session_jawab'))){
                        jsonResponse(array('status' => FALSE, 'msg' => 'Sesi terkunci atau sedang berjalan di perangkat lain'));
                    }
                    //reset sesi
                    if(empty($jawab['session_jawab'])){
                        $session = encode(random_string('unique'));
                        $this->m_jawab->update($jawab['id_jawab'], ['session_jawab' => $session]);
                        $this->session->set_userdata(array('session_jawab' => $session));
                    }
                    //start sesi
                    jsonResponse(array('status' => TRUE, 'msg' => 'Selamat datang kembali. Mohon segera mengerjakan sebelum waktu habis', 
                        'link' => site_url($this->module.'/add/'.encode($jawab['id_jawab']))));
                    break;
                case '1':
                    jsonResponse(array('status' => FALSE, 'msg' => 'Terimakasih telah mengerjakan sesi ini'));
                    break;
                default:
                    jsonResponse(array('status' => FALSE, 'msg' => 'Sesi tidak ditemukan'));
                    break;
            }
        }
        //init soal
        $arr_soal = json_decode($module['soal_module'], true);
        if(empty($arr_soal) || !is_array($arr_soal)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Tidak ada soal dalam sesi ini'));
        }
        $enrol_soal = array_map(function($item) {  return $item['id']; }, $arr_soal);
        $is_random = ($module['is_random'] == '1') ? 'RANDOM' : 'ASC';
        //get soal
        $soal = $this->db->order_by('order_soal', $is_random)->where_in('id_soal', $enrol_soal)->get_where('lm_soal');
        if($soal->num_rows() < 1){
            jsonResponse(array('status' => FALSE, 'msg' => 'Tidak ada soal dalam sesi ini'));
        }
        //set quiz
        $data['id_jawab'] = random_string('unique');
        $quiz = array(); $nomor = 1;
        foreach ($soal->result_array() as $item) {
            $row = array();
            $row['jawab_id'] = $data['id_jawab'];
            $row['soal_id'] = $item['id_soal'];
            $row['order_quiz'] = $nomor;
            $row['status_quiz'] = '0';

            $quiz[] = $row;
            $nomor++;
        }
        //set jawab
        $data['module_id'] = $id;
        $data['peserta_id'] = $peserta_id;
        $data['mulai_jawab'] = date('Y-m-d H:i:s');
        $data['selesai_jawab'] = date('Y-m-d H:i:s', strtotime($data['mulai_jawab'] . ' +'.$durasi.' minutes'));
        $data['status_jawab'] = '0';
        $data['valid_jawab'] = '1';
        $data['session_jawab'] = encode(random_string('unique'));
        $data['update_jawab'] = date('Y-m-d H:i:s');
        $data['log_jawab'] = $this->sessionname.' memulai sesi ini';
        
        $result = $this->m_jawab->insertBatch($data, $quiz);
        if($result){
            $this->session->set_userdata(array('session_jawab' => $data['session_jawab']));
            
            jsonResponse(array('status' => TRUE, 'link' => site_url($this->module.'/add/'.encode($data['id_jawab'])), 
                'msg' => 'Waktu pengerjaan sudah di mulai. Harap kerjakan dengan teliti'));
        }else{
            jsonResponse(array('status' => FALSE, 'msg' => 'Sesi ini gagal disimpan'));
        }
    }
    function _update_jawab() {
        if(empty($this->mid)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Tidak memiliki akses pada menu ini'));
        }
        if(!$this->_validation($this->rules_jawab,'ajax')){
            jsonResponse(array('status' => FALSE, 'msg' => strval(validation_errors())));
        }
        $this->load->model(array('m_soal'));
            
        $id = decode($this->input->post('id'));
        $soal_id = decode($this->input->post('soal'));
        $status = $this->input->post('status');
        $opsi = $this->input->post('opsi');
        $now_ts = date('Y-m-d H:i:s');
        //cek data
        $check = $this->db->join('lm_module m','m.id_module = j.module_id','inner')
            ->get_where('lm_jawab j', array('j.id_jawab' => $id))->row_array();
        if(is_null($check)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Sesi tidak ditemukan'));
        }
        if($check['status_jawab'] != '0' || $check['valid_jawab'] == '0'){
            jsonResponse(array('status' => FALSE, 'msg' => 'Sesi ini sudah anda kerjakan sebelumnya'));
        }
        //cek module
        $cek_module = range_date($now_ts, $check['buka_module'], $check['tutup_module']);
        if(!$cek_module['st']){
            jsonResponse(array('status' => FALSE, 'msg' => $cek_module['rs']));
        }
        //cek jawab
        $cek_jawab = range_date($now_ts, $check['mulai_jawab'], $check['selesai_jawab']);
        if(!$cek_jawab['st']){
            jsonResponse(array('status' => FALSE, 'msg' => $cek_jawab['rs']));
        }
        //cek soal
        $soal = $this->m_soal->getId($soal_id);
        if(is_null($soal)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Pertanyaan tidak ditemukan'));
        }
        //cek opsi-essay
        if(in_array($check['is_quiz'], array('PILIHAN-GANDA','KUESIONER'))){
            if(empty($opsi)){
                jsonResponse(array('status' => FALSE, 'msg' => 'Pilih Opsi Jawaban terlebih dahulu'));
            }
            $opsi_arr = array();
            if (!empty($soal['opsi_a'])) { $opsi_arr[] = json_decode($soal['opsi_a']); }
            if (!empty($soal['opsi_b'])) { $opsi_arr[] = json_decode($soal['opsi_b']); }
            if (!empty($soal['opsi_c'])) { $opsi_arr[] = json_decode($soal['opsi_c']); }
            if (!empty($soal['opsi_d'])) { $opsi_arr[] = json_decode($soal['opsi_d']); }
            if (!empty($soal['opsi_e'])) { $opsi_arr[] = json_decode($soal['opsi_e']); }
            foreach ($opsi_arr as $item) {
                if ($opsi == $item->key) {
                    $data['opsi_key'] = $opsi;
                    $data['nilai_quiz'] = intval($item->nilai);
                    $data['valid_quiz'] = ($data['nilai_quiz'] == 0)  ? '0' : '1';
                }
            }
        }else{
            $essay = $this->input->post('essay');
            if(empty($essay)){
                jsonResponse(array('status' => FALSE, 'msg' => 'Jawaban Essai masih kosong'));
            }
            $data['essay_quiz'] = $essay;
        }
        $data['status_quiz'] = ($status == 'valid') ? '1' : '2';
        $data['buat_quiz'] = date('Y-m-d H:i:s');
        
        $result = $this->m_jawab->updateAll(array('jawab_id' => $id, 'soal_id' => $soal_id), null, $data);
        if($result){
            jsonResponse(array('status' => TRUE, 'msg' => 'Jawaban berhasil disimpan', 'waktu' => selisih_wkt($data['buat_quiz'])));
        }else{
            jsonResponse(array('status' => FALSE, 'msg' => 'Jawaban gagal disimpan. Mohon ulangi kembali'));
        }
    }
    function _done_jawab() {
        if(empty($this->mid)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Tidak memiliki akses pada menu ini'));
        }
        $id = decode($this->input->post('id'));
        
        $check = $this->m_jawab->getId($id);
        if(is_null($check)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Sesi tidak ditemukan'));
        }
        if($check['status_jawab'] != '0' || $check['valid_jawab'] == '0'){
            jsonResponse(array('status' => FALSE, 'msg' => 'Sesi ini sudah anda kerjakan sebelumnya'));
        }
        $rs_skor = $this->db->select('SUM(nilai_quiz) AS nilai, COUNT(soal_id) AS jumlah')
            ->get_where('lmrf_quiz', array('jawab_id' => $id))->row_array();
        
        $data['skor_jawab'] = json_encode($rs_skor);
        $data['status_jawab'] = '1';
        $data['selesai_jawab'] = date('Y-m-d H:i:s');
        $data['update_jawab'] = date('Y-m-d H:i:s');
        $data['log_jawab'] = $this->sessionname.' menyelesaikan sesi ini';
        
        $result = $this->m_jawab->updateAll(array('jawab_id' => $id, 'status_quiz' => '0', 'valid_quiz' => null), 
            $data, array('valid_quiz' => '0', 'nilai_quiz' => 0));
        if($result){
            jsonResponse(array('status' => TRUE, 'msg' => 'TERIMA KASIH'));
        }else{
            jsonResponse(array('status' => FALSE, 'msg' => 'Sesi ini gagal diakhiri. Silahkan ulangi kembali'));
        }
    }
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
    private $rules_jawab = array(
        array(
            'field' => 'id',
            'label' => 'Sesi',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'soal',
            'label' => 'Soal',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'opsi',
            'label' => 'Jawaban Opsi',
            'rules' => 'trim|xss_clean'
        ),array(
            'field' => 'essay',
            'label' => 'Jawaban Essay',
            'rules' => 'trim|xss_clean'
        ),array(
            'field' => 'status',
            'label' => 'Status Jawaban',
            'rules' => 'required|trim|xss_clean'
        )
    );
}