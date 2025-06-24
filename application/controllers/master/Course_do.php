<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Course_do extends KZ_Controller {
    
    private $module = 'master/course';
    private $module_do = 'master/course_do';
    private $url_route = array('id', 'source', 'type');
    private $path = 'upload/activity';
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_module','m_jawab'));
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        if(!$this->_validation($this->rules)){
            redirect($this->module.'/edit/'.$id);
        }
        $data['nama_module'] = strtoupper($this->input->post('nama'));
        $data['jenis_module'] = $this->input->post('jenis');
        $data['buka_module'] = empty($this->input->post('buka')) ? null : $this->input->post('buka');
        $data['tutup_module'] = empty($this->input->post('tutup')) ? null : $this->input->post('tutup');
        $data['durasi_module'] = empty($this->input->post('durasi')) ? null : $this->input->post('durasi');
        $data['status_module'] = $this->input->post('status');
        $data['is_quiz'] = $this->input->post('tipe');
        $data['is_random'] = $this->input->post('random');
        $data['note_module'] = $this->input->post('note');
        
        $data['update_module'] = date('Y-m-d H:i:s');
        $data['log_module'] = $this->sessionname.' mengubah data aktivitas';
        
        if(!empty($_FILES['file']['name'])){
            $this->load->library(array('storage'));
            //upload
            $filename = url_title($data['nama_module'].' '.random_string('alnum',3), 'dash', TRUE);
            $path = array('local' => $this->path, 's3' => $this->sessionusr.'/activity');
            $upload =  $this->storage->putFile('file', $filename, $path);
            
            if(empty($upload->fullPath)){
                redirect($this->module.'/edit/'.$id);
            }
            $data['file_module'] = $upload->customName;
            //delete
            $old_file = $this->input->post('exfile');
            delete_file($old_file);
        }
        $result = $this->m_module->update(decode($id), $data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil diubah'));
            redirect($this->module.'/edit/'.$id);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal diubah'));
            redirect($this->module.'/edit/'.$id);
        }
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if($routing_module['type'] == 'action') {
            //ACTION
            if($routing_module['source'] == 'activity') {
                $this->_add_activity();
            }else if($routing_module['source'] == 'enrol') {
                $this->_add_enrol();
            }else if($routing_module['source'] == 'start') {
                $this->_start_jawab();
            }else if($routing_module['source'] == 'update') {
                $this->_update_jawab();
            }else if($routing_module['source'] == 'done') {
                $this->_done_jawab();
            }else if($routing_module['source'] == 'skor') {
                $this->_update_skor();
            }else if($routing_module['source'] == 'respon') {
                $this->_update_respon();
            }
        }
    }
    //function
    function _add_activity() {
        if(!$this->_validation($this->rules_activ,'ajax')){
            jsonResponse(array('status' => FALSE, 'msg' => validation_errors()));
        }
        $data['id_module'] = random_string('unique');
        $data['nama_module'] = strtoupper($this->input->post('nama_activ'));
        $data['jenis_module'] = $this->input->post('jenis_activ');
        $data['is_quiz'] = ($data['jenis_module'] == 'QUIZ') ? $this->input->post('tipe_quiz') : null;
        
        $data['update_module'] = date('Y-m-d H:i:s');
        $data['log_module'] = $this->sessionname. ' menambahkan aktivitas';
        
        $result = $this->m_module->insert($data);
        if($result) {
            jsonResponse(array('data' => encode($data['id_module']),'status' => true, 'msg' => 'Data berhasil tersimpan'));
        }else {
            jsonResponse(array('status' => false, 'msg' => 'Data gagal tersimpan'));
        }
    }
    function _add_enrol() {
        $id = decode($this->input->post('id'));
        $status = $this->input->post('status');
        $arr_soal = array_filter(explode(",", $this->input->post('soal')));
        
        $module = $this->m_module->getId($id);
        if(is_null($module)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Data tidak temukan'));
        }
        if(!is_array($arr_soal) || empty($arr_soal)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Tidak ada data yang dipilih'));
        }
        $dataArray = json_decode($module['soal_module'], true);
        if (empty($dataArray)) {
            foreach ($arr_soal as $newId) {
                $dataArray[] = ["id" => decode($newId)];
            }
        } else {
            $ids = array_column($dataArray, 'id');
            foreach ($arr_soal as $newId) {
                if (!in_array(decode($newId), $ids)) {
                    $dataArray[] = ["id" => decode($newId)];
                }else {
                    if(!empty($status)){
                        $idToRemove = decode($newId);
                        $dataArray = array_filter($dataArray, function($item) use ($idToRemove) {
                            return $item['id'] !== $idToRemove;
                        });
                        $dataArray = array_values($dataArray);
                    }
                }
            }
        }
        $data['soal_module'] = json_encode($dataArray);
        $data['update_module'] = date('Y-m-d H:i:s');
        $data['log_module'] = $this->sessionname. ' enrol soal';
        
        $result = $this->m_module->update($id, $data);
        if($result){
            jsonResponse(array('status' => TRUE, 'msg' => 'Data berhasil disimpan'));
        }else{
            jsonResponse(array('status' => FALSE, 'msg' => 'Data gagal disimpan'));
        }
    }
    function _start_jawab() {
        $peserta_id = $this->sessionid;
        $id = decode($this->input->post('id'));
        //check module
        $module = $this->m_module->getId($id);
        if(empty($module)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Tidak ada sesi yang dipilih'));
        }
        $durasi = intval($module['durasi_module']);
        //check jawab
        $jawab = $this->m_jawab->getId(array('module_id' => $id, 'peserta_id' => $peserta_id, 'valid_jawab' => '1'));
        if(!is_null($jawab)){
            $msg_jwb = ($jawab['status_jawab'] == '1') ? 'Terimakasih telah mengerjakan sesi ini' 
                : 'Selamat datang kembali. Mohon segera mengerjakan sebelum waktu habis';
            jsonResponse(array('status' => TRUE, 'msg' => $msg_jwb, 
                'link' => site_url($this->module.'/add/'.encode($jawab['id_jawab']))));
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
    function _update_skor() {
        $id = decode($this->input->post('id'));
        $tipe = $this->input->post('tipe');
        $skor = intval($this->input->post('skor'));
        
        $check = $this->m_jawab->getId($id);
        if(is_null($check)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Data Penilaian tidak ditemukan'));
        }
        switch ($tipe) {
            case 'hitung':
                $rs_skor = $this->db->select('SUM(nilai_quiz) AS nilai, COUNT(soal_id) AS jumlah')
                    ->get_where('lmrf_quiz', array('jawab_id' => $id))->row_array();
                $data['skor_jawab'] = json_encode($rs_skor);
                break;
            case 'simpan':
                if($skor < 0 || $skor > 100){
                    jsonResponse(array('status' => FALSE, 'msg' => 'Rentang skor dari 0 - 100'));
                }
                $data['skor_jawab'] = json_encode(['nilai' => $skor, 'jumlah' => 1]);
                break;
            case 'ubah':
                $durasi = intval($this->input->post('waktu'));
                if(!empty($durasi) && $durasi >= 5){
                    $now = strtotime(date('Y-m-d H:i:s'));
                    $done = strtotime($check['selesai_jawab']);
                    if ($done >= $now) {
                        // Jika waktu selesai sama/lebih besar dari waktu sekarang, tambahkan 10 menit ke waktu selesai
                        $data['selesai_jawab'] = date('Y-m-d H:i:s', strtotime('+'.$durasi.' minutes', $done));
                    } else {
                        // Jika waktu selesai kurang dari waktu sekarang, waktu selesai adalah waktu sekarang tambah 10 menit
                        $data['selesai_jawab']  = date('Y-m-d H:i:s', strtotime('+'.$durasi.' minutes', $now));
                    }
                    $data['status_jawab'] = '0';
                    $data['valid_jawab'] = '1';
                    $data['session_jawab'] = NULL;
                }else{
                    $data['status_jawab'] = $this->input->post('status');
                    $data['valid_jawab'] = $this->input->post('valid');
                }
                break;
            case 'hapus':
                $rs_del = $this->m_jawab->delete($id);
                if ($rs_del) {
                    delete_file($check['file_jawab']);
                    jsonResponse(array('status' => TRUE, 'msg' => 'Data berhasil dihapus'));
                } else {
                    jsonResponse(array('status' => TRUE, 'msg' => 'Data gagal dihapus'));
                }
                break;
            default:
                jsonResponse(array('status' => FALSE, 'msg' => 'Data tidak ditemukan'));
                break;
        }
        $data['update_jawab'] = date('Y-m-d H:i:s');
        $data['log_jawab'] = $this->sessionname.' memperbarui data';
        
        $result = $this->m_jawab->update($id, $data);
        if ($result) {
            jsonResponse(array('status' => TRUE, 'msg' => 'Data berhasil diperbarui'));
        } else {
            jsonResponse(array('status' => TRUE, 'msg' => 'Data gagal diperbarui'));
        }
    }
    function _update_respon() {
        if(!$this->_validation($this->rules_respon,'ajax')){
            jsonResponse(array('status' => FALSE, 'msg' => strval(validation_errors())));
        }
        $id = decode($this->input->post('id'));
        $soal = decode($this->input->post('soal'));
        
        $data['valid_quiz'] = $this->input->post('valid');
        $data['nilai_quiz'] = $this->input->post('nilai');
        $data['note_quiz'] = $this->input->post('catatan');
        //cek data
        $check = $this->db->join('lm_module m','m.id_module = j.module_id','inner')
            ->get_where('lm_jawab j', array('j.id_jawab' => $id))->row_array();
        if(is_null($check)){   
            jsonResponse(array('status' => FALSE, 'msg' => 'Sesi tidak ditemukan'));
        }
        if($check['status_jawab'] != '1' || $check['valid_jawab'] == '0'){
            jsonResponse(array('status' => FALSE, 'msg' => 'Penilaian belum dapat dilakukan hingga sesi selesai'));
        }
        $rs_skor = $this->db->select('SUM(nilai_quiz) AS nilai, COUNT(soal_id) AS jumlah')->from('lmrf_quiz')
            ->where(array('jawab_id' => $id, 'soal_id <>' => $soal))->get()->row_array();
        $total = (int) $rs_skor['nilai'] + (int) $data['nilai_quiz'];
        $max_skor = round(100/((int) $rs_skor['jumlah'] + 1));
        if($total > 100){
            jsonResponse(array('status' => FALSE, 
                'msg' => 'Total Nilai : '.$total.' (melebihi skala 100). Nilai Maksimal untuk setiap soal adalah : '.$max_skor));
        }
        $result = $this->m_jawab->updateAll(array('jawab_id' => $id, 'soal_id' => $soal), null, $data);
        if($result){
            jsonResponse(array('status' => TRUE, 'msg' => 'Respon berhasil disimpan'));
        }else{
            jsonResponse(array('status' => FALSE, 'msg' => 'Respon gagal disimpan. Mohon ulangi kembali'));
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
    private $rules_respon = array(
        array(
            'field' => 'id',
            'label' => 'Sesi',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'soal',
            'label' => 'Soal',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'valid',
            'label' => 'Validasi Jawaban',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'nilai',
            'label' => 'Skor Nilai',
            'rules' => 'required|trim|xss_clean|is_natural|greater_than_equal_to[0]|less_than_equal_to[100]'
        ),array(
            'field' => 'catatan',
            'label' => 'Catatan',
            'rules' => 'trim|xss_clean'
        )
    );
    private $rules_activ = array(
        array(
            'field' => 'activid',
            'label' => 'Pertemuan',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'nama_activ',
            'label' => 'Nama Aktivitas',
            'rules' => 'required|trim|xss_clean|min_length[3]'
        ),array(
            'field' => 'jenis_activ',
            'label' => 'Jenis Aktivitas',
            'rules' => 'required|trim|xss_clean'
        )
    );
    private $rules = array(
        array(
            'field' => 'nama',
            'label' => 'Nama Aktivitas',
            'rules' => 'required|trim|xss_clean|min_length[3]'
        ),array(
            'field' => 'jenis',
            'label' => 'Jenis Aktivitas',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'buka',
            'label' => 'Tanggal Buka',
            //'rules' => 'required|trim|xss_clean|min_length[15]'
        ),array(
            'field' => 'tutup',
            'label' => 'Tanggal Tutup',
            //'rules' => 'required|trim|xss_clean|min_length[15]'
        ),array(
            'field' => 'durasi',
            'label' => 'Durasi Pengerjaan',
            //'rules' => 'required|trim|xss_clean|is_natural|less_than_equal_to[360]'
        ),array(
            'field' => 'status',
            'label' => 'Status Aktivitas',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'random',
            'label' => 'Random Soal',
            //'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'tipe',
            'label' => 'Tipe Soal',
            //'rules' => 'required|trim|xss_clean'
        ), array(
            'field' => 'note',
            'label' => 'Catatan Aktivitas',
            'rules' => 'min_length[5]'
        )
    );
}