<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Course extends KZ_Controller {
    
    private $module = 'master/course';
    private $module_do = 'master/course_do';
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_module','m_jawab'));
    }
    function index() {
        $this->load->model(array('m_prodi'));
        
        $is_smt = ($this->sessionlevel != '1') ? array('periode_aktif !=' => '2') : null;
        $this->data['semester'] = $this->m_semester->getAll($is_smt);
        $this->data['prodi'] = $this->m_prodi->getAll();
        
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Course','List Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('master/course/v_index', $this->data);
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
        $this->load_view('master/course/v_add', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $result = $this->m_module->getId(decode($id));
        
        $this->data['is_materi'] = in_array($result['jenis_module'], array('MATERI','FILE','LINK')) ? 'hide':'';
        $this->data['is_tugas'] = ($result['jenis_module'] == 'TUGAS') ? 'hide':'';
        
        $this->data['bank'] = $this->db->get_where('lm_bank', 
            array('jenis_bank' => $result['is_quiz'], 'dosen_id' => $this->did))->result_array();
        
        $this->data['edit'] = $result;
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('Aktivitas', 'Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('master/course/v_edit', $this->data);
    }
    function detail($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['detail'] = $this->m_module->getId(decode($id));
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Aktivitas', 'Detail Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('master/course/v_detail', $this->data);
    }
    function delete($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $module = $this->m_module->getId(decode($id));
        //cek jawab
        $check = $this->m_jawab->getId(array('module_id' => decode($id)));
        if(!is_null($check)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak dapat dihapus, terhubung dengan data lainnya'));
            redirect($this->module.'/detail/'.$id);
        }
        $result = $this->m_module->delete(decode($id));
        if ($result) {
            delete_file($module['file_module']);
            
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil dihapus'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal dihapus'));
            redirect($this->module.'/detail/'.$id);
        }
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if($routing_module['type'] == 'table') {
            //TABLE
            if($routing_module['source'] == 'soal') {
                $this->_table_soal();
            }else if($routing_module['source'] == 'rekap') {
                $this->_table_rekap();
            }else if($routing_module['source'] == 'mahasiswa') {
                $this->_table_mhs();
            }
        }else if($routing_module['type'] == 'list') {
            //TABLE
            if($routing_module['source'] == 'index') {
                $this->_list_index();
            }else if($routing_module['source'] == 'soal') {
                $this->_list_soal();
            }else if($routing_module['source'] == 'dosen') {
                $this->_get_dosen();
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
        $is_dosen = (empty($this->did) && $this->sessionlevel != '1') ? 'hide':'';
        $course_html = '';
        $no = 1;
        //list jurnal
        foreach ($list->result_array() as $items) {
            $is_active = ($items['status_jurnal'] == '1') ? 'widget-color-blue2':'widget-color-red disabled'; 
            $activ_html = '';
            
            $activity = $this->db->order_by('jenis_module', 'asc')->order_by('update_module', 'asc')
                ->get_where('lm_module',array('jurnal_id' => $items['id_jurnal']))->result_array();
            //list module
            foreach ($activity as $val) {
                $is_active_mod = ($val['status_module'] == '1') ? '':'alert alert-danger'; 
                $is_file_mod = empty($val['file_module']) ? 'hide':'';
                     
                switch ($val['jenis_module']) {
                    case 'QUIZ':
                        $json_soal = json_decode($val['soal_module'], true);
                        $enrol_soal = !empty($val['soal_module']) && is_array($json_soal) ? 
                            array_map(function($item) {  return $item['id']; }, $json_soal) : array();
                            
                        $icon = ($val['is_quiz'] == 'ESSAI') ? 'fa-pencil-square-o btn-purple' : 'fa-list-ol btn-danger';
                        $is_action = '<div class="space-4"></div>
                            <ul class="list-unstyled">
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
                            <button id="submit-btn" itemid="'.encode($val['id_module']).'" itemname="'.$val['nama_module'].'"
                                class="btn btn-danger btn-white btn-bold btn-sm '.$is_dosen.'"><i class="fa fa-pencil"></i> Mulai Pengerjaan </button>';
                        break;
                    case 'TUGAS': 
                        $icon = 'fa-cloud-upload btn-warning';
                        $is_action = '<div class="space-4"></div>
                            <ul class="list-unstyled">
                                <li class="">
                                    <i class="ace-icon fa fa-calendar green"></i>
                                    Jadwal Submit : <strong class="blue">'.format_date($val['buka_module'],2).
                                    '</strong> s/d <strong class="orange">'.format_date($val['tutup_module'],2).'</strong>
                                </li>
                            </ul>';
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
                            <div class="widget-main '.$is_active_mod.'">
                                <div class="pull-right">
                                    <a target="_blank" href="'.load_file($val['file_module']).'"
                                        class="btn btn-white btn-grey btn-round btn-mini '.$is_file_mod.'">Berkas File&nbsp;
                                        <i class="fa fa-download"></i>
                                    </a>
                                    <a href="'.site_url($this->module.'/detail/'.encode($val['id_module'])).'" target="_blank" 
                                        class="btn btn-white btn-primary btn-round btn-mini">
                                        <i class="fa fa-search-plus"></i>
                                    </a>
                                    <a href="'.site_url($this->module.'/edit/'.encode($val['id_module'])).'" target="_blank" 
                                        class="btn btn-white btn-warning btn-round btn-mini '.$is_dosen.'">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </a>
                                    <button id="delete-activ-btn" itemid="'.site_url($this->module.'/delete/'.encode($val['id_module'])).'"'
                        . '             itemname="'.ctk($val['nama_module']).'" class="btn btn-white btn-danger btn-round btn-mini '.$is_dosen.'">
                                        <i class="fa fa-trash-o"></i>
                                    </button>
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
                    </span>'.st_mhs($items['mode_jurnal']).'
                </div>
                <div class="timeline-items">
                    <div class="timeline-item clearfix">
                        <div class="timeline-info">
                            <img class="" src="'.load_file('theme/img/logo.png').'" />
                        </div>
                        <div class="widget-box '.$is_active.'">
                            <div class="widget-header widget-header-small">
                                <h5 class="widget-title bolder black">'.format_date($items['tgl_jurnal']).'</h5>
                                <span class="widget-toolbar no-border">
                                    <div class="btn-group btn-overlap '.$is_dosen.'">
                                        <a target="_blank" href="'.site_url('master/jurnal/edit/'.encode($items['id_jurnal'])).'" 
                                            class="btn btn-white btn-warning btn-bold btn-mini">
                                            <i class="fa fa-pencil-square-o bigger-110"></i> Ubah
                                        </a>
                                        <a href="'.site_url('master/jurnal/detail/'.encode($items['id_jurnal'])).'" target="_blank" 
                                            class="btn btn-white btn-info btn-bold btn-mini" '.$is_active.'>
                                            <i class="fa fa-user-plus bigger-110"></i> Presensi
                                        </a>
                                        <button id="add-btn" itemid="'.encode($items['id_jurnal']).'" 
                                            class="btn btn-white btn-success btn-bold btn-mini">
                                            <i class="fa fa-plus-square bigger-110"></i> Aktivitas
                                        </button>
                                        <button id="delete-btn" itemid="'.site_url('master/jurnal/delete/'.encode($items['id_jurnal'])).'" itemname="Pertemuan Ke - '.ctk($items['init_jurnal']).'"
                                            class="btn btn-white btn-danger btn-bold btn-mini">
                                            <i class="fa fa-trash bigger-110"></i>
                                        </button>
                                    </div>
                                </span>
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
    function _table_soal() {
        $this->load->model(array('m_soal'));
        
        $id = decode($this->input->post('id'));
        $bank = decode($this->input->post('bank'));
        
        $result = $this->m_module->getId($id);
        if(is_null($result)){
            jsonResponse(array('status' => false, 'msg' => 'Data tidak ditemukan'));
        }
        $json_enrol = json_decode($result['soal_module']);
        
        $where['jenis_bank'] = $result['is_quiz'];
        $where['bank_id'] = $bank;
        
        $list = $this->m_soal->getAll($where);
        if($list['rows'] < 1){
            jsonResponse(array('status' => false, 'msg' => 'Data tidak ditemukan'));
        }
        $data = array('table' => array());
        $no = 1;
        foreach ($list['data'] as $items) {
            $row = array();
            
            $search = null;
            if(!empty($json_enrol) && is_array($json_enrol)){
                $search = array_filter($json_enrol, function ($value) use ($items) {
                    return ($value->id == $items['id_soal']);
                });
            }
            $is_check = empty($search) ? 'default' : 'success';
            $is_order = '<span class="label label-lg label-'.$is_check.' arrowed arrowed-right bolder">'.ctk($items['order_soal']).'</span>';
            $is_order .= empty($search) ? ' <label class="pos-rel">
                    <input value="'.encode($items['id_soal']).'" name="item_select[]" id="item_select" type="checkbox" class="ace input-lg"/>
                <span class="lbl"></span> </label>' : 
                ' <button id="delete-btn" itemid="'.encode($items['id_soal']).'" itemname="'.limit_text($items['isi_soal'], 50).'"
                    class="btn btn-white btn-danger btn-round btn-mini">
                    <i class="fa fa-trash-o red"></i>
                </button>';
            
            $row[] = ($items['status_soal'] == '0') ? '' : $is_order;
            $row[] = limit_text($items['isi_soal'], 100);
            $row[] = ctk($items['materi_soal']);
            $row[] = '<strong>'.ctk($items['nama_bank']).'</strong> <br> '.$items['jenis_bank'];
            
            $data['table'][] = $row;
            $no++;
        }
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
        //is done
        $data['is_done'] = ($result['status_jawab'] == '1') ? true : false;
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
                    class="btn btn-round '.st_soal($val, $data['is_done']). ' '.$now.'" style="margin:2px">
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
                    $st_benar = ($soal['valid_quiz'] == '1') ? 'alert alert-success' : 'alert alert-danger';
                } else {
                    $st_benar = intval(element('nilai', $item)) == 0 ? '' : 'alert alert-success';
                }
                $is_benar = ($data['is_done']) ? $st_benar : '';
                $data['content_opsi'] .= '<div class="profile-info-row '.$st_benar.'">
                    <div class="profile-info-name"><label class="control-label">
                        <input '.$checked.' name="opsi" id="opsi-check"
                            value="'. element('key', $item) .'" type="radio" class="is-done ace" disabled /><span class="lbl bolder"> </span>
                        </label><label class="bolder bigger-130">'. $string[$index] .'</label>
                    </div><div class="profile-info-value"><span class="bigger-130">'.element('isi', $item).
                        '</span><br>'.$this->_is_file(element('file', $item)).'</div></div>';
                $index++;
            }
        }else{
            $is_done = ($data['is_done']) ? '' : 'hide';
            $is_benar = ($soal['valid_quiz'] == '1') ? 'checked' : '';
            $is_salah = ($soal['valid_quiz'] == '0') ? 'checked' : '';
            
            $data['content_opsi'] = '<div class="profile-info-row">
                <div class="profile-info-name">Jawaban</div><div class="profile-info-value">
                    <textarea name="essay" id="essay" rows="5" cols="1" class="is-done width-100 bolder" disabled>'. $soal['essay_quiz'] .'</textarea>
                    <div class="space-4"></div><button id="btn-simpan" type="button" disabled class="is-done btn btn-success btn-sm btn-bold btn-white">
                    <i class="ace-icon fa fa-save"></i>Simpan Jawaban</button>
                </div>
            </div>
            <div class="space-12 '.$is_done.'"></div>
            <div class="profile-info-row '.$is_done.'">
                <div class="profile-info-name">
                    <strong>Validasi</strong>
                </div>
                <div class="profile-info-value">
                    <label class="control-label">
                        <input '.$is_benar.' name="valid" value="1" type="radio" class="ace" />
                        <span class="lbl green bolder"> BENAR </span>
                    </label>&nbsp;&nbsp;&nbsp;
                    <label class="control-label">
                        <input '.$is_salah.' name="valid" value="0" type="radio" class="ace" />
                        <span class="lbl red bolder"> SALAH </span>
                    </label>
                </div>
            </div>
            <div class="profile-info-row '.$is_done.'">
                <div class="profile-info-name">
                    <strong>Nilai</strong>
                </div>
                <div class="profile-info-value">
                    <input value="'. $soal['nilai_quiz'] .'" name="nilai" id="nilai" type="number" class="col-sm-2 col-xs-12" min="0" max="100" placeholder="Nilai (0-100)">
                </div>
            </div>
            <div class="profile-info-row '.$is_done.'">
                <div class="profile-info-name">
                    <strong>Catatan</strong>
                </div>
                <div class="profile-info-value">
                    <textarea name="catatan" id="catatan" rows="3" cols="1" placeholder="Catatan Penilaian" class="width-100">'. $soal['note_quiz'] .'</textarea>
                    <div class="space-6"></div>
                    <button id="btn-respon" type="button" class="btn btn-info btn-bold btn-white btn-sm">
                        <i class="ace-icon fa fa-save"></i>
                        Simpan Respon
                    </button>
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
    function _table_rekap() {
        $id = decode($this->input->post('id'));
        
        $result = $this->m_module->getId($id);
        if(empty($result)){
            jsonResponse(array('status' => false, 'msg' => 'Data tidak ditemukan'));
        }
        $list = $this->db->join('m_mhs m','m.id_mhs = j.peserta_id','left')->get_where('lm_jawab j', array('module_id' => $id));
        if($list->num_rows() < 1){
            jsonResponse(array('status' => false, 'msg' => 'Data tidak ditemukan'));
        }
        $data = array('table' => array());
        $no = 1;
        foreach ($list->result_array() as $items) {
            $row = array();
            switch ($items['status_jawab']) {
                case '0': $st_jawab = '<span class="label label-warning arrowed-in-right arrowed">PROGRESS</span>';
                    break; 
                case '1': $st_jawab = '<span class="label label-success arrowed-in-right arrowed">SELESAI</span>';
                    break;
                default: $st_jawab = '<span class="label label-default arrowed-in-right arrowed">PENDING</span>';
                    break;
            }
            $is_done = ($items['status_jawab'] == '1') ? true : false;
            $sisa_quiz = range_date(date('Y-m-d H:i:s'), $items['mulai_jawab'], $items['selesai_jawab']);
            $st_sesi = ($is_done || !$sisa_quiz['st']) ? format_date($items['mulai_jawab'],2).'<br><strong class="green">'.format_date($items['selesai_jawab'],2).'</strong>' :
                '<strong>'.format_date($items['mulai_jawab'],3).' - '.format_date($items['selesai_jawab'],3).'</strong><br>
                <span class="label label-danger bolder">'.$sisa_quiz['rs'].'</span>';
            
            $json_skor = json_decode($items['skor_jawab'], true);
            $skor = (int) element('nilai', $json_skor);
            $jumlah_soal = (int) element('jumlah', $json_skor);
            $nilai = ($result['is_quiz'] == 'PILIHAN-GANDA' && $jumlah_soal > 0) ? round($skor/$jumlah_soal*100) : $skor;
            $is_file = empty($items['file_jawab']) ? '':'&nbsp;&nbsp;&nbsp;<b>File</b> : '.st_file($items['file_jawab'], 1);
                    
            $btn_skor = ($result['jenis_module'] == 'QUIZ') ? '<button itemid="'. encode($items['id_jawab']) .'" id="skor-btn" 
                    class="tooltip-info btn btn-white btn-info btn-sm btn-round" data-rel="tooltip" title="Hitung Skor">
                    <span class=""><i class="ace-icon fa fa-calculator bigger-120"></i></span>
                </button>
                <a href="'.site_url($this->module.'/add/'.encode($items['id_jawab'])).'"  data-rel="tooltip" title="Lihat Hasil" target="_blank" 
                    class="tooltip-success btn btn-white btn-success btn-sm btn-round">
                    <span class="green"><i class="fa fa-list-ol bigger-120"></i></span>
                </a>' : '<input type="number" class="bolder center input-small" name="skor[]" id="skor'. encode($items['id_jawab']) .'" placeholder="Nilai">
                <button itemid="'. encode($items['id_jawab']) .'" id="simpan-btn" 
                    class="tooltip-success btn btn-white btn-success btn-sm btn-round" data-rel="tooltip" title="Simpan Skor">
                    <span class=""><i class="ace-icon fa fa-save bigger-120"></i></span>
                </button>';
            $btn_aksi = '<button itemid="'.encode($items['id_jawab']).'" itemname="'.$items['status_jawab'].'" itemprop="'.$items['valid_jawab'].'" id="edit-btn"
                    class="tooltip-warning btn btn-white btn-warning btn-sm btn-round" data-rel="tooltip" title="Ubah Data">
                    <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span>
                </button>
                <button itemid="'.encode($items['id_jawab']).'" itemname="'.ctk($items['nama_mhs'] ?? $this->sessionname).'" id="delete-btn" 
                    class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Hapus Data">
                    <span class="red"><i class="ace-icon fa fa-trash-o"></i></span>
                </button>';
            $row[] = $no;
            $row[] = empty($items['nama_mhs']) ? $this->sessionname
                : '<strong>'.ctk($items['nama_mhs']).'</strong><br><span class="blue">'.ctk($items['nim']).'</span>';
            $row[] = $st_sesi;
            $row[] = '<b>Nilai</b> : <span class="bigger-120">[ <strong class="blue">'.$nilai.'</strong> ]</span>'.$is_file;
            $row[] = '<small>'. ctk($items['note_jawab']).'</small>';
            $row[] = $st_jawab.st_aktif($items['valid_jawab']);
            $row[] = (empty($this->did) && $this->sessionlevel != '1') ? '':'<div class="action-buttons">'.$btn_skor.' '.$btn_aksi.'</div>';
            
            $data['table'][] = $row;
            $no++;
        }
        jsonResponse(array('data' => $data, 'status' => true, 'msg' => 'Data ditemukan'));
    }
    function _table_mhs() {
        $id = decode($this->input->post('id'));
        
        $all_quiz = $this->db->join('m_jurnal j', 'j.id_jurnal = m.jurnal_id', 'inner')
            ->order_by('j.init_jurnal', 'ASC')->order_by('m.update_module', 'ASC')->where_in('jenis_module', array('QUIZ','TUGAS'))
            ->get_where('lm_module m', array('kelas_id' => $id))->result_array();
        
        $list = $this->db->join('m_mhs m', 'n.mhs_id = m.id_mhs', 'left')
            ->order_by('m.nim', 'ASC')->get_where('rf_nilai n', array('kelas_id' => $id));
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
                $query = $this->db->get_where('lm_jawab', 
                    array('module_id' => $val['id_module'], 'peserta_id' => $items['id_mhs'], 'valid_jawab' => '1'))->row_array();
                
                if(!is_null($query)){
                    $json_skor = json_decode($query['skor_jawab'], true);
                    $skor = (int) element('nilai', $json_skor);
                    $jumlah_soal = (int) element('jumlah', $json_skor);
                    $total += $nilai = ($val['is_quiz'] == 'PILIHAN-GANDA' && $jumlah_soal > 0) ? round($skor/$jumlah_soal*100) : $skor;
                }
                $row[] = $nilai > 0 ? '<strong class="orange bigger-110">'.$nilai.'</strong>':null;
            }
            $row[] = $total > 0 ? '<strong class="bigger-120">'.$total.'</strong>':null;
            
            $data['table'][] = $row;
            $no++;
        }
        $data['column'] = $all_quiz;
        
        jsonResponse(array('data' => $data, 'status' => true, 'msg' => 'Data ditemukan'));
    }
    function _get_dosen(){
        $this->load->model(array('m_dosen'));
        
        $key = $this->input->post('key');
        $id = $this->input->get('id');
        
        $where = null;
        if(!is_null($this->did)){
            $where['id_dosen'] = $this->did;
        }
        if(!empty($id)){
            $result = $this->m_dosen->getAll(array('id_dosen' => decode($id)));
        }else{
            $result = $this->m_dosen->getAll($where, $key);
        }
        $data = array();
        foreach ($result['data'] as $val) {
            $text = $val['nidn'].' - '.$val['nama_dosen'];
            $data[] = array("id" => encode($val['id_dosen']), "text" => $text, "status" => $val['status_dosen']);
        }
        jsonResponse($data);
    }
    function _get_kelas(){
        $this->load->model(array('m_kelas'));
        
        $key = $this->input->post('key');
        $periode = decode($this->input->post('periode'));
        $dosen = decode($this->input->post('dosen'));
        $prodi = decode($this->input->post('prodi'));
        
        $id = $this->input->get('id');
        
        $where['id_semester'] = empty($periode) ? $this->smtid : $periode;
        if($dosen != '') {
            $where['id_dosen'] = $dosen;
        }
        if(!is_null($this->did)){
            $where['id_dosen'] = $this->did;
        }
        if($prodi != '') {
            $where['prodi_id'] = $prodi;
        }
        if(!empty($id)){
            $result = $this->m_kelas->getAll(array('id_kelas' => decode($id)));
        }else {
            $result = $this->m_kelas->getAll($where, '' ,$key);
        }
        $data = array();
        foreach ($result['data'] as $val) {
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