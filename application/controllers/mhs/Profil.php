<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends KZ_Controller {

    private $module = 'mhs/profil';
    private $module_do = 'mhs/profil_do';
    private $url_route = array('id', 'source', 'type');

    function __construct() {
        parent::__construct();
        
        $this->load->model(array('m_mhs'));
        $this->_getMhs();
        if(empty($this->mid)){
            redirect('beranda');
        }
    }
    function index() {
        $detail = $this->m_mhs->getId($this->mid);
        if(is_null($detail)) {
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect('beranda');
        }
        if(empty($detail['agama']) || empty($detail['kecamatan'])){
            redirect($this->module.'/edit');
        }
        $this->data['valid_test'] = (in_array($detail['status_mhs'], ['PENDING'])) ? FALSE : TRUE;
        $this->data['detail'] = $detail;
        $this->data['user'] = $this->m_mhs->getTMP(array('mhs_id' => $this->mid));
        
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Pendaftaran', $detail['nama_mhs']);
        $this->data['breadcrumb'] = array(
            array('title' => 'Mahasiswa', 'url' => '#'),
            array('title' => $this->uri->segment(2), 'url' => '')
        );
        $this->load_view('mhs/profil/v_index', $this->data);
    }
    function add($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->load->model(array('m_jawab'));
        
        $result = $this->m_jawab->getId(decode($id));
        if(is_null($result)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Sesi tidak ditemukan'));
            redirect($this->module);
        }
        if($result['status_jawab'] == '0'){
            $this->session->set_flashdata('notif', notif('warning', 'Informasi', 'Sesi terkunci atau sedang berjalan di perangkat lain'));
            redirect($this->module);
        }
        if ($result['status_jawab'] == '2') {
            $this->m_jawab->update(decode($id), array('status_jawab' => '0'));
        }
        $this->data['detail'] = $result;
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Profil','Seleksi Mandiri');
        $this->data['breadcrumb'] = array( 
            array('title' => 'Mahasiswa', 'url' => '#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('mhs/profil/v_add', $this->data);
    }
    function edit() {
        $this->data['edit'] = $this->m_mhs->getId($this->mid);
        
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do . '/edit/';
        $this->data['title'] = array('Profil', 'Ubah Data');
        $this->data['breadcrumb'] = array(
            array('title' => 'Mahasiswa', 'url' => '#'),
            array('title'=> $this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title' => $this->data['title'][1], 'url' => '')
        );
        $this->load_view('mhs/profil/v_form', $this->data);
    }
    function cetak() {
        $this->load->model(array('m_prodi'));
        $this->load->library(array('fungsi'));
        
        $detail = $this->m_mhs->getId($this->mid);
        if(is_null($detail)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect($this->module);
        }
        if(!in_array($detail['status_mhs'], ['LULUS','VALID','AKTIF'])){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan'
                , 'Mohon maaf anda belum dinyatakan LULUS. KTM hanya terbit ketika Status Mahasiswa : LULUS & AKTIF'));
            redirect($this->module);
        }
        $this->data['detail'] = $detail;
        $this->data['prodi'] = $this->m_prodi->getId($detail['prodi_id']);
        
        $title = 'KARTU MAHASISWA SEMENTARA';
        $this->data['judul'] = array($title, null);
        $this->fungsi->PdfGenerate($this->load->view('mhs/profil/v_kartu', $this->data, true), 
            url_title($title.' '.$detail['nim'].' '.$detail['nama_mhs'], '-', true));
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if($routing_module['type'] == 'table') {
            //LIST
            if($routing_module['source'] == 'wilayah') {
                $this->_get_wilayah();
            }
        }else if($routing_module['type'] == 'list') {
            //TABLE
            if($routing_module['source'] == 'index') {
                $this->_list_index();
            }else if($routing_module['source'] == 'soal') {
                $this->_list_soal();
            }
        }
    }
    //function
    function _list_index() {
        $no = 1;
        $activ_html = '';
        $activity = $this->db->order_by('update_module','asc')
            ->get_where('lm_module', array('status_module' => '1', 'jenis_module' => 'QUIZ'));
        foreach ($activity->result_array() as $val) {
            
            $icon = ($val['is_quiz'] == 'ESSAI') ? 'fa-pencil-square-o btn-success' : 'fa-list-ol btn-primary';
            $is_file_mod = empty($val['file_module']) ? 'hide':'';
            $is_enable_mod = (range_date(date('Y-m-d H:i:s'), $val['buka_module'], $val['tutup_module'])['st']) ? '' : 'disabled';

            $is_jawab = $this->db->get_where('lm_jawab', array('module_id' => $val['id_module'], 
                'peserta_id' => $this->mid, 'valid_jawab' => '1', 'status_jawab' => '1'))->row_array();
            $color_jawab = 'red2'; 
            $st_jawab = '<button '.$is_enable_mod.' id="submit-btn" itemid="'.encode($val['id_module']).'" itemname="'.$val['nama_module'].'"
                class="btn btn-danger btn-white btn-bold btn-lg"><i class="fa fa-pencil"></i> Mulai Pengerjaan </button>';
            
            if(!is_null($is_jawab) && in_array($val['jenis_module'], array('QUIZ'))){
                $json_skor = json_decode($is_jawab['skor_jawab'], true);
                $skor = (int) element('nilai', $json_skor);
                $jumlah_soal = (int) element('jumlah', $json_skor);
                
                $nilai = ($val['is_quiz'] == 'PILIHAN-GANDA' && $jumlah_soal > 0) ? round($skor/$jumlah_soal*100) : $skor;
                $color_jawab = 'blue2';
                $st_jawab = '<span class="btn btn-app btn-primary">
                    <span class="line-height-1 bigger-300 bolder"> '.$nilai.' </span><br>
                        <span class="line-height-1 smaller-70"> Nilai Anda </span>
                    </span>';
            }
            $json_soal = json_decode($val['soal_module'], true);
            $enrol_soal = !empty($val['soal_module']) && is_array($json_soal) ? 
                array_map(function($item) {  return $item['id']; }, $json_soal) : array();
                
            $activ_html .= '<div class="timeline-container">
                <div class="timeline-label">
                    <span class="label label-warning arrowed-in-right label-xlg">
                        <strong class="bigger-120">'.count($enrol_soal).'</strong> '.$val['is_quiz'].'
                    </span>
                    <span class="label label-danger arrowed arrowed-right label-lg">
                        <strong class="bigger-120">'.$val['durasi_module'].'</strong> MENIT
                    </span>
                </div>
                <div class="timeline-items">
                    <div class="timeline-item clearfix">
                        <div class="timeline-info">
                            <i class="timeline-indicator ace-icon fa btn no-hover '.$icon.' bigger-130"></i>
                        </div>
                        <div class="widget-box widget-color-'.$color_jawab.'">
                            <div class="widget-header">
                                <h4 class="widget-title bolder">'.$no.'. '.$val['nama_module'].'</h4>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main">
                                    '.$val['note_module'].'
                                    <div class="pull-right">
                                        <a target="_blank" href="'.load_file($val['file_module']).'"
                                            class="btn btn-white btn-grey btn-bold btn-mini '.$is_file_mod.'">Berkas File&nbsp;
                                            <i class="fa fa-download"></i>
                                        </a>
                                        <div class="space-6"></div>
                                    </div>
                                    <div class="space-6"></div>
                                    '.$st_jawab.'
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
            $no++;
        }
        $data['course'] = $activ_html;
                
        jsonResponse(array('data' => $data, 'status' => true, 'msg' => 'Data ditemukan'));
    }
    function _list_soal() {
        $this->load->model(array('m_soal'));
        
        $id = decode($this->input->post('id'));
        $nomor = $this->input->post('no');
        //check jawab
        $result = $this->db->join('lm_module m','m.id_module = j.module_id','inner')
            ->get_where('lm_jawab j', array('j.id_jawab' => $id))->row_array();
        if(is_null($result)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Sesi tidak ditemukan'));
        }
        $sisa_module = range_date(date('Y-m-d H:i:s'), $result['buka_module'], $result['tutup_module']);
        //$sisa_quiz = range_date(date('Y-m-d H:i:s'), $result['mulai_jawab'], $result['selesai_jawab']);
        $st_jawab = ($result['status_jawab'] == '0') ? false : true;
        //is done
        $data['is_done'] = ($st_jawab || !$sisa_module['st']);
        $data['limit_time'] = $result['selesai_jawab'];
        //list nomor
        $list_nomor = $this->db->get_where('lmrf_quiz', array('jawab_id' => $id));
        if($list_nomor->num_rows() < 1){
            jsonResponse(array('status' => FALSE, 'msg' => 'Daftar Pertanyaan tidak ditemukan'));
        }
        $data['content_nomor'] = '';
        foreach($list_nomor->result_array() as $val) {
            $now = ($nomor == $val['order_quiz']) ? 'btn-white disabled btn-xlg' : '';
            $data['content_nomor'] .= '<button id="nomor-btn" itemid="'.($val['order_quiz']).'"
                    class="btn btn-round '.st_soal($val). ' '.$now.'" style="margin:2px">
                <strong class="bigger-120">' . $val['order_quiz'] . '</strong></button>';
        }
        //check soal
        $soal = $this->db->join('lm_soal s','s.id_soal = q.soal_id','inner')
            ->get_where('lmrf_quiz q', array('q.jawab_id' => $id, 'q.order_quiz' => abs($nomor), 's.status_soal' => '1'))->row_array();
        if(is_null($soal)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Pertanyaan Nomor '.abs($nomor).' tidak ditemukan'));
        }
        $data['soal'] = $soal;
        $data['id'] = encode($soal['id_soal']);
        $data['waktu'] = selisih_wkt($soal['buat_quiz']);
        $data['content_opsi'] = '';
        $data['prev'] = (($nomor - 1) < 1) ? '' : $nomor - 1;
        $data['nomor'] = $nomor;
        $data['next'] = (($nomor + 1) > $list_nomor->num_rows()) ? '' : $nomor + 1;
        
        if(in_array($result['is_quiz'], array('PILIHAN-GANDA','KUESIONER'))){
            //opsi soal
            $opsi_arr = array();
            if (!empty($soal['opsi_a'])) { $opsi_arr[] = json_decode($soal['opsi_a'], true); }
            if (!empty($soal['opsi_b'])) { $opsi_arr[] = json_decode($soal['opsi_b'], true); }
            if (!empty($soal['opsi_c'])) { $opsi_arr[] = json_decode($soal['opsi_c'], true); }
            if (!empty($soal['opsi_d'])) { $opsi_arr[] = json_decode($soal['opsi_d'], true); }
            if (!empty($soal['opsi_e'])) { $opsi_arr[] = json_decode($soal['opsi_e'], true); }
            
            $string = "ABCDEFG";
            $index = 0;
            foreach ($opsi_arr as $item) {
                $checked = '';
                if (!empty(element('key', $item)) && $soal['opsi_key'] == element('key', $item)) {
                    $checked = 'checked';
                } 
                $data['content_opsi'] .= '<div class="profile-info-row">
                    <div class="profile-info-name"><label class="control-label">
                        <input '.$checked.' name="opsi" id="opsi-check"
                            value="'. element('key', $item) .'" type="radio" class="is-done ace" disabled /><span class="lbl bolder"> </span>
                        </label><label class="bolder bigger-130">'. $string[$index] .'</label>
                    </div><div class="profile-info-value"><span class="bigger-130">'.element('isi', $item).
                        '</span><br>'.$this->_is_file(element('file', $item)).'</div></div>';
                $index++;
            }
        }else{
            $is_respon = ($data['is_done']) ? '':'hide';
            $data['content_opsi'] = '<div class="profile-info-row">
                <div class="profile-info-name">Jawaban</div><div class="profile-info-value">
                    <textarea name="essay" id="essay" rows="5" cols="1" class="is-done width-100 bolder" disabled>'. $soal['essay_quiz'] .'</textarea>
                    <div class="space-4"></div><button id="btn-simpan" type="button" disabled class="is-done btn btn-success btn-sm btn-bold btn-white">
                    <i class="ace-icon fa fa-save"></i>Simpan Jawaban</button>
                </div>
            </div>
            <div class="profile-info-row '.$is_respon.'">
                <div class="profile-info-name">
                    <strong>Catatan</strong>
                </div>
                <div class="profile-info-value">
                    <textarea name="catatan" id="catatan" rows="3" cols="1" placeholder="Catatan Penilaian" class="width-100" disabled>'. $soal['note_quiz'] .'</textarea>
                </div>
            </div>';
        }
        $is_file = empty($soal['file_soal']) ? 'hide':'';
        $data['content_soal'] = '<div class="bigger-150">'. ($soal['isi_soal']) .'</div>
            <div class="social-or-login center '.$is_file.'"><button id="btn-zoom" class="btn btn-white btn-info btn-round btn-mini">
            <i class="fa fa-search-plus"></i>Zoom</button></div><div class="space-4"></div>
        <div align="center">'.$this->_is_file($soal['file_soal']).'</div>';
        
        jsonResponse(array('data' => $data, 'status' => true, 'msg' => 'Data ditemukan'));
    }
    function _is_file($file) {
        if(empty($file)){ return ''; }
        return '<embed toolbar="true" src="'.load_file($file).'" width="30%" class="blur-up img-thumbnail lazyload is-embed">';
    }
    function _get_wilayah(){
        $id = $this->input->get('id');
        $keyword = $this->input->post('key');
        $opsi = $this->input->post('opsi');
            
        if(!empty($id)){
            $this->db->where('id_wilayah', $id);
        }else{
            if($opsi == 'Kab'){
                $this->db->where('RIGHT(id_wilayah, 2) =', '00');
                $this->db->where('RIGHT(id_wilayah, 4) !=', '0000');
            }else{
                $this->db->where('LEFT(id_wilayah, 4) =', substr($opsi, 0, 4));
                $this->db->where('RIGHT(id_wilayah, 2) !=', '00');
            }
            $this->db->like('nama_wilayah', $keyword);
        }
        $result = $this->db->get('m_wilayah');
        
        $data = array();
        foreach ($result->result_array() as $val) {
            $data[] = array("id" => ($val['id_wilayah']), "text" => $val['nama_wilayah']);
        }
        jsonResponse($data);
    }
}
