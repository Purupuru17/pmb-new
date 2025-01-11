<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Meeting extends KZ_Controller {
    
    private $module = 'mahasiswa/meeting';
    private $module_do = 'mahasiswa/meeting_do';
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_module','m_jawab'));
        
        $this->_mhs_id();
        if(empty($this->mid)){
            redirect('beranda');
        }
    }
    function index() {
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Meeting','List Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('mahasiswa/meeting/v_index', $this->data);
    }
    function add($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $result = $this->m_jawab->getId(decode($id));
        if(is_null($result)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Sesi tidak ditemukan'));
            redirect($this->module);
        }
        if($result['status_jawab'] == '0'){
            $this->session->set_flashdata('notif', notif('warning', 'Informasi', 'Sesi terkunci atau sedang berjalan di perangkat lain. Hubungi Dosen Pengampu'));
            redirect($this->module);
        }
        if ($result['status_jawab'] == '2') {
            $this->m_jawab->update(decode($id), array('status_jawab' => '0'));
        }
        $this->data['detail'] = $result;
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Aktivitas','Quiz');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('mahasiswa/meeting/v_add', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $result = $this->db->join('lm_module m','m.id_module = j.module_id','inner')
            ->get_where('lm_jawab j', array('j.id_jawab' => decode($id)))->row_array();
        if(is_null($result)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Sesi tidak ditemukan'));
            redirect($this->module);
        }
        $sisa_module = range_date(date('Y-m-d H:i:s'), $result['buka_module'], $result['tutup_module']);
        $st_jawab = ($result['status_jawab'] == '1') ? true : false;
        //is done
        $this->data['is_done'] = ($st_jawab || !$sisa_module['st']);
        
        $this->data['detail'] = $result;
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('Aktivitas','Penugasan');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('mahasiswa/meeting/v_edit', $this->data);
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if($routing_module['type'] == 'table') {
            //TABLE
            if($routing_module['source'] == 'mahasiswa') {
                $this->_table_mhs();
            }
        }else if($routing_module['type'] == 'list') {
            //TABLE
            if($routing_module['source'] == 'index') {
                $this->_list_index();
            }else if($routing_module['source'] == 'soal') {
                $this->_list_soal();
            }else if($routing_module['source'] == 'kelas') {
                $this->_get_kelas();
            }
        }
    }
    //function
    function _list_index() {
        $id = decode($this->input->post('id'));
        $nomor = $this->input->post('no');
        
        $where['kelas_id'] = $id;
        if(!empty($nomor)){
            $where['init_jurnal'] = $nomor;
        }
        $list = $this->db->order_by('init_jurnal', 'asc')->get_where('m_jurnal', $where);
        if($list->num_rows() < 1){
            jsonResponse(array('status' => false, 'msg' => 'Jurnal tidak ditemukan'));
        }
        $course_html = '';
        $no = 1;
        //list jurnal
        foreach ($list->result_array() as $items) {
            $activ_html = '';
            $is_active = ($items['status_jurnal'] == '1') ? 'widget-color-blue2':'widget-color-red disabled'; 
            //check presensi
            $json_presensi = empty($items['presensi_jurnal']) ? array() : json_decode($items['presensi_jurnal'], true);
            $search = array_filter($json_presensi, function($val) {
                return (element('id',$val) === $this->mid);
            });
            $is_presensi = '';
            if(!empty($search)){
                $found = reset($search);
                $is_presensi = st_mhs(element('status', $found)).
                    '<span class="label label-sm arrowed arrowed-right">'.format_date(element('buat',$found),0).'</span>';
            }
            //list module
            $activity = $this->db->order_by('jenis_module', 'asc')->order_by('update_module', 'asc')
                ->get_where('lm_module',array('jurnal_id' => $items['id_jurnal']))->result_array();
            foreach ($activity as $val) {
                $is_active_mod = ($items['status_jurnal'] == '1' && $val['status_module'] == '1') ? '':'hide'; 
                $is_file_mod = empty($val['file_module']) ? 'hide':'';
                $is_enable_mod = (range_date(date('Y-m-d H:i:s'), $val['buka_module'], $val['tutup_module'])['st']) ? '' : 'disabled';
                
                $is_jawab = $this->db->where(array('module_id' => $val['id_module'], 'peserta_id' => $this->mid, 'valid_jawab' => '1'))
                    ->count_all_results('lm_jawab');
                $st_jawab = ($is_jawab < 1 && in_array($val['jenis_module'], array('QUIZ','TUGAS'))) ? 'alert alert-danger':''; 
                        
                switch ($val['jenis_module']) {
                    case 'QUIZ':
                        $json_soal = json_decode($val['soal_module'], true);
                        $enrol_soal = !empty($val['soal_module']) && is_array($json_soal) ? 
                            array_map(function($item) {  return $item['id']; }, $json_soal) : array();
                            
                        $icon = ($val['is_quiz'] == 'ESSAI') ? 'fa-pencil-square-o btn-purple' : 'fa-list-ol btn-danger';
                        $is_action = '<div class="space-4"></div>
                            <ul class="list-unstyled '.$is_active_mod.'">
                                <li class="">
                                    <i class="ace-icon fa fa-list-ol blue"></i>
                                    Tipe Soal : <strong class="blue bigger-110">'.count($enrol_soal).'</strong> <strong>'.$val['is_quiz'].'</strong> 
                                </li>
                                <li class="">
                                    <i class="ace-icon fa fa-clock-o bigger-120 red"></i>
                                    Durasi Pengerjaan : <strong class="red bigger-110">'.$val['durasi_module'].'</strong> Menit
                                </li>
                                <li class="">
                                    <i class="ace-icon fa fa-calendar green"></i>
                                    Jadwal Pelaksanaan : <strong class="blue">'.format_date($val['buka_module'],2).
                                    '</strong> s/d <strong class="orange">'.format_date($val['tutup_module'],2).'</strong>
                                </li>
                            </ul>
                            <button '.$is_enable_mod.' id="submit-btn" itemid="'.encode($val['id_module']).'" itemname="'.$val['nama_module'].'"
                                class="btn btn-danger btn-white btn-bold btn-sm '.$is_active_mod.'"><i class="fa fa-pencil"></i> Mulai Pengerjaan </button>';
                        break;
                    case 'TUGAS': 
                        $icon = 'fa-cloud-upload btn-warning';
                        $is_action = '<div class="space-4"></div>
                            <ul class="list-unstyled '.$is_active_mod.'">
                                <li class="">
                                    <i class="ace-icon fa fa-calendar green"></i>
                                    Jadwal Submit : <strong class="blue">'.format_date($val['buka_module'],2).
                                    '</strong> s/d <strong class="orange">'.format_date($val['tutup_module'],2).'</strong>
                                </li>
                            </ul>
                            <button '.$is_enable_mod.' id="assign-btn" itemid="'.encode($val['id_module']).'" itemname="'.$val['nama_module'].'"
                                class="btn btn-warning btn-white btn-bold btn-sm '.$is_active_mod.'"><i class="fa fa-paper-plane"></i> Submit & Upload </button>';
                        break;
                    case 'MATERI': 
                        $icon = 'fa-book btn-success';
                        $is_action = '';
                        break;
                    case 'FILE': 
                        $icon = 'fa-file-pdf-o btn-primary';
                        $is_action = '';
                        break;
                    case 'LINK': 
                        $icon = 'fa-link btn-info';
                        $is_action = '';
                        break;
                    default: 
                        $icon = 'fa-star btn-default';
                        $is_action = '';
                        break;
                }
                $activ_html .= '<div class="row"><div class="timeline-item col-sm-offset-2 col-sm-8">
                    <div class="timeline-info bigger-150">
                        <i class="timeline-indicator ace-icon fa btn no-hover '.$icon.'"></i>
                    </div>
                    <div class="widget-box '.$is_active.'">
                        <div class="widget-body">
                            <div class="widget-main '.$st_jawab.'">
                                <div class="pull-right">
                                    <a target="_blank" href="'.load_file($val['file_module']).'"
                                        class="btn btn-white btn-grey btn-round btn-mini 
                                        '.$is_file_mod.' '.$is_active_mod.'">Berkas File&nbsp;
                                        <i class="fa fa-download"></i>
                                    </a>
                                </div>
                                <strong>'.$val['nama_module'].'</strong><br/>'.$val['note_module'].$is_action.'
                            </div>
                        </div>
                    </div>
                </div></div>';
            }
            $course_html .= '<div class="timeline-container">
                <div class="timeline-label">
                    <span class="label label-danger arrowed-in-right label-xlg">
                        <b class="bigger-110">Ke - ' .$items['init_jurnal']. '</b>
                    </span>'.st_mhs($items['mode_jurnal']).$is_presensi.'
                </div>
                <div class="timeline-items">
                    <div class="timeline-item clearfix">
                        <div class="timeline-info">
                            <img class="" src="'.load_file('app/img/logo.png').'" />
                        </div>
                        <div class="widget-box '.$is_active.'">
                            <div class="widget-header widget-header-small">
                                <h5 class="widget-title bolder black">'.format_date($items['tgl_jurnal']).'</h5>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main">
                                    Waktu : <strong>'.ctk($items['waktu_jurnal']).'</strong><br/>
                                    Ruangan : <strong>'.ctk($items['ruang_jurnal']).'</strong><br/>
                                    <span class="red">***</span> '.ctk($items['note_jurnal']).'
                                </div>
                            </div>
                        </div>
                    </div>
                    '.$activ_html.'
                </div>
            </div>';
            $no++;
        }
        $data['course'] = $course_html;
                
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
    function _table_mhs() {
        $id = decode($this->input->post('id'));
        
        $all_quiz = $this->db->join('m_jurnal j', 'j.id_jurnal = m.jurnal_id', 'inner')
            ->order_by('j.init_jurnal', 'ASC')->order_by('m.update_module', 'ASC')->where_in('jenis_module', array('QUIZ','TUGAS'))
            ->get_where('lm_module m', array('kelas_id' => $id))->result_array();
        
        $where['kelas_id'] = $id;
        $where['mhs_id'] = $this->mid;
        
        $list = $this->db->join('m_mhs m', 'n.mhs_id = m.id_mhs', 'left')->order_by('m.nim', 'ASC')->get_where('rf_nilai n', $where);
        if($list->num_rows() < 1){
            jsonResponse(array('status' => false, 'msg' => 'Data tidak ditemukan'));
        }
        $data = array('table' => array(), 'column' => array());
        $no = 1;
        foreach ($list->result_array() as $items) {
            $row = array();
            $total = 0;
            
            $row[] = $no;
            $row[] = '<strong>'.$items['nama_mhs'].'</strong> <br> '.$items['nim'];
            foreach ($all_quiz as $val) {
                $nilai = 0;
                $is_preview = '';
                $query = $this->db->get_where('lm_jawab', 
                    array('module_id' => $val['id_module'], 'peserta_id' => $items['id_mhs'], 'valid_jawab' => '1'))->row_array();
                
                if(!is_null($query)){
                    $json_skor = json_decode($query['skor_jawab'], true);
                    $skor = (int) element('nilai', $json_skor);
                    $jumlah_soal = (int) element('jumlah', $json_skor);
                    $total += $nilai = ($val['is_quiz'] == 'PILIHAN-GANDA' && $jumlah_soal > 0) ? round($skor/$jumlah_soal*100) : $skor;
                    
                    $is_preview = (empty($val['is_quiz'])) ? '':' --> <a href="'.site_url($this->module.'/add/'.encode($query['id_jawab'])).'" 
                        target="_blank" class="btn btn-white btn-primary btn-round btn-mini"><i class="fa fa-search-plus"></i></a>';
                }
                $row[] = $nilai > 0 ? '<strong class="orange bigger-110">'.$nilai.'</strong>'.$is_preview : null;
            }
            $row[] = $total > 0 ? '<strong class="bigger-120">'.$total.'</strong>':null;
            
            $data['table'][] = $row;
            $no++;
        }
        $data['column'] = $all_quiz;
        
        jsonResponse(array('data' => $data, 'status' => true, 'msg' => 'Data ditemukan'));
    }
    function _get_kelas(){
        //krs-mhs
        $result = $this->db->join('m_prodi p', 'p.id_prodi = k.prodi_id', 'inner')
            ->join('rf_nilai n','n.kelas_id = k.id_kelas','inner')
            ->get_where('m_kelas k', array('id_semester' => $this->smtid, 'mhs_id' => $this->mid))->result_array();
        $data = array();
        foreach ($result as $val) {
            $text = ctk($val['kode_matkul']).' - '.ctk($val['nama_matkul']).' ['.ctk($val['nama_kelas']).'] - '.ctk($val['nama_prodi']);
            $data[] = array("id" => encode($val['id_kelas']), "text" => $text);
        }
        jsonResponse($data);
    }
    function _is_file($file) {
        if(empty($file)){ return ''; }
        return '<embed toolbar="true" src="'.load_file($file).'" width="30%" class="blur-up img-thumbnail lazyload is-embed">';
    }
}