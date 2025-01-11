<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Meeting_do extends KZ_Controller {
    
    private $module = 'mahasiswa/meeting';
    private $module_do = 'mahasiswa/meeting_do';
    private $url_route = array('id', 'source', 'type');
    private $path = 'app/upload/activity';
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_module','m_jawab'));
        
        $this->_mhs_id();
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $data['note_jawab'] = $this->input->post('note');
        $data['selesai_jawab'] = date('Y-m-d H:i:s');
        $data['status_jawab'] = '1';
        $data['update_jawab'] = date('Y-m-d H:i:s');
        $data['log_jawab'] = $this->sessionname.' mengubah jawaban';
        
        if(!empty($_FILES['file']['name'])){
            $this->load->library(array('storage'));
            //upload
            $filename = url_title($this->sessionusr.' '.random_string('alnum',5), 'dash', TRUE);
            $path = array('local' => $this->path, 's3' => $this->sessionusr.'/activity');
            $upload =  $this->storage->putFile('file', $filename, $path);
            if(empty($upload->fullPath)){
                redirect($this->module);
            }
            $data['file_jawab'] = $upload->customName;
            //delete
            $old_file = $this->input->post('exfile');
            delete_file($old_file);
        }
        $result = $this->m_jawab->update(decode($id), $data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil ditambahkan'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal ditambahkan'));
            redirect($this->module);
        }
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if($routing_module['type'] == 'action') {
            //ACTION
            if($routing_module['source'] == 'assign') {
                $this->_start_assign();
            }else if($routing_module['source'] == 'start') {
                $this->_start_jawab();
            }else if($routing_module['source'] == 'update') {
                $this->_update_jawab();
            }else if($routing_module['source'] == 'done') {
                $this->_done_jawab();
            }else if($routing_module['source'] == 'qrcode') {
                $this->_auth_qrcode();
            }
        }
    }
    //function
    function _start_assign() {
        if(empty($this->mid)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Tidak memiliki akses pada menu ini'));
        }
        $peserta_id = $this->mid;
        $id = decode($this->input->post('id'));
        //check module
        $module = $this->m_module->getId($id);
        if(empty($module)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Tidak ada sesi yang dipilih'));
        }
        //check avalaible
        $is_range = range_date(date('Y-m-d H:i:s'), $module['buka_module'], $module['tutup_module']);
        if(!$is_range['st']){
            jsonResponse(array('status' => FALSE, 'msg' => $is_range['rs']));
        }
        //check khs
        $khs = $this->db->join('rf_nilai n','j.kelas_id = n.kelas_id','inner')
            ->get_where('m_jurnal j', array('id_jurnal' => $module['jurnal_id'], 'mhs_id' => $peserta_id));
        if($khs->num_rows() < 1){
            jsonResponse(array('status' => FALSE, 'msg' => 'Kelas Perkuliahan ini tidak ada dalam KRS, hubungi Dosen Pengampu'));
        }
        //check jawab
        $jawab = $this->m_jawab->getId(array('module_id' => $id, 'peserta_id' => $peserta_id, 'valid_jawab' => '1'));
        if(!is_null($jawab)){
            jsonResponse(array('status' => TRUE, 'link' => site_url($this->module.'/edit/'.encode($jawab['id_jawab'])), 
                'msg' => 'Selamat datang kembali. Mohon segera upload penugasan sebelum waktu habis'));
        }
        //set jawab
        $data['id_jawab'] = random_string('unique');
        $data['module_id'] = $id;
        $data['peserta_id'] = $peserta_id;
        $data['mulai_jawab'] = date('Y-m-d H:i:s');
        $data['status_jawab'] = '0';
        $data['valid_jawab'] = '1';
        $data['update_jawab'] = date('Y-m-d H:i:s');
        $data['log_jawab'] = $this->sessionname.' memulai sesi ini';
        
        $result = $this->m_jawab->insert($data);
        if($result){
            jsonResponse(array('status' => TRUE, 'link' => site_url($this->module.'/edit/'.encode($data['id_jawab'])),
                'msg' => 'Silahkan mengupload jawaban anda. Harap kerjakan dengan teliti'));
        }else{
            jsonResponse(array('status' => FALSE, 'msg' => 'Sesi ini gagal disimpan'));
        }
    }
    function _start_jawab() {
        if(empty($this->mid)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Tidak memiliki akses pada menu ini'));
        }
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
        //check khs
        $khs = $this->db->join('rf_nilai n','j.kelas_id = n.kelas_id','inner')
            ->get_where('m_jurnal j', array('id_jurnal' => $module['jurnal_id'], 'mhs_id' => $peserta_id));
        if($khs->num_rows() < 1){
            jsonResponse(array('status' => FALSE, 'msg' => 'Kelas Perkuliahan ini tidak ada dalam KRS, hubungi Dosen Pengampu'));
        }
        //check jawab
        $jawab = $this->m_jawab->getId(array('module_id' => $id, 'peserta_id' => $peserta_id, 'valid_jawab' => '1'));
        if(!is_null($jawab)){
            if($jawab['status_jawab'] == '0'){
                jsonResponse(array('status' => FALSE, 'msg' => 'Sesi terkunci atau sedang berjalan di perangkat lain. Hubungi Dosen Pengampu'));
            }
            $msg_jwb = ($jawab['status_jawab'] == '1') ? 'Terimakasih telah mengerjakan sesi ini' 
                : 'Selamat datang kembali. Mohon segera mengerjakan sebelum waktu habis';
            jsonResponse(array('status' => TRUE, 'link' => site_url($this->module.'/add/'.encode($jawab['id_jawab'])), 'msg' => $msg_jwb));
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
        $data['status_jawab'] = '2';
        $data['valid_jawab'] = '1';
        $data['update_jawab'] = date('Y-m-d H:i:s');
        $data['log_jawab'] = $this->sessionname.' memulai sesi ini';
        
        $result = $this->m_jawab->insertBatch($data, $quiz);
        if($result){
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
    function _auth_qrcode() {
        $this->load->model(array('m_jurnal'));
        
        if(empty($this->mid)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Data Mahasiswa tidak ditemukan'));
        }
        $token = $this->input->post('token');
        try {
            $decode = JWT::decode($token, new Key($this->config->item('encryption_key'), 'HS256'));
            //jurnal
            $result = $this->db->join('m_kelas k','k.id_kelas = j.kelas_id','inner')
                ->get_where('m_jurnal j',array('id_jurnal' => $decode->data))->row_array();
            if(empty($result)){
                jsonResponse(array('status' => FALSE, 'msg' => 'Kelas tidak ditemukan'));
            }
            $message = 'Kelas '.$result['nama_matkul'].' Pertemuan Ke - '.$result['init_jurnal'];
            //cek krs-mhs
            $is_krs = $this->db->join('rf_nilai n','j.kelas_id = n.kelas_id','inner')
                ->get_where('m_jurnal j', array('id_jurnal' => $result['id_jurnal'], 'mhs_id' => $this->mid));
            if($is_krs->num_rows() < 1){
                jsonResponse(array('status' => FALSE, 'msg' => 'Kelas ini tidak ada dalam KRS, hubungi Dosen Pengampu'));
            }
            $expired = (strtotime(date('Y-m-d')) - strtotime($result['tgl_jurnal']))/86400;
            if($expired > 100) {
                jsonResponse(array('status' => FALSE, 
                    'msg' => 'Waktu Presensi melebihi <strong>100 HARI</strong> sejak tanggal kuliah : '. format_date($result['tgl_jurnal'])));
            }
            $akm = $this->db->get_where('rf_akm', array('mhs_id' => $this->mid, 'semester_id' => $this->smtid))->row_array();
            $status = (!empty($akm) && $akm['valid_akm'] == '0') ? 'PENDING':'HADIR';
            
            $json_presensi = json_decode($result['presensi_jurnal'], true);
            if(empty($json_presensi)){
                $json_presensi[] = array('id' => $this->mid, 'status' => $status, 'buat' => date('Y-m-d H:i:s'));
            }else{
                $id_mhs = array_column($json_presensi, 'id');
                if (in_array($this->mid, $id_mhs)) {
                    jsonResponse(array('status' => FALSE, 'msg' => 'Anda sudah melakukan Presensi sebelumnya pada '.$message));
                }
                $json_presensi[] = array('id' => $this->mid, 'status' => $status, 'buat' => date('Y-m-d H:i:s'));
            }
            $data['presensi_jurnal'] = json_encode($json_presensi);
            $data['update_jurnal'] = date('Y-m-d H:i:s');
            $data['log_jurnal'] = $this->sessionname.' mengubah presensi';

            $update = $this->m_jurnal->update($result['id_jurnal'], $data);
            if ($update) {
                jsonResponse(array('status' => TRUE, 'msg' => 'Anda berhasil Presensi pada '.$message));
            } else {
                jsonResponse(array('status' => FALSE, 'msg' => 'Presensi Gagal'));
            }
        } catch (Exception $e) {
            jsonResponse(array('status' => FALSE, 'msg' => 'Silahkan scan ulang. QR Code tidak berlaku : '.$e->getMessage()));
        }
    }
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