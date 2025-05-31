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
            array('jenis_bank' => $result['is_quiz']))->result_array();
        
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
            if($routing_module['source'] == 'index') {
                $this->_table_index();
            }else if($routing_module['source'] == 'soal') {
                $this->_table_soal();
            }else if($routing_module['source'] == 'rekap') {
                $this->_table_rekap();
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
    function _table_index() {
        $where = null;
        $list = $this->m_module->get_datatables($where);
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $items) {
            $no++;
            $row = array();
            
            $json_soal = json_decode($items['soal_module'], true);
            $enrol_soal = !empty($items['soal_module']) && is_array($json_soal) ? 
                array_map(function($item) {  return $item['id']; }, $json_soal) : array();
                    
            $btn_aksi = '<a href="'. site_url($this->module .'/detail/'. encode($items['id_module'])) .'" 
                    class="tooltip-info btn btn-white btn-info btn-round btn-sm" data-rel="tooltip" title="Lihat Data">
                    <span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span>
                </a>
                <a href="'. site_url($this->module .'/edit/'. encode($items['id_module'])) .'"
                    class="tooltip-warning btn btn-white btn-warning btn-sm btn-round" data-rel="tooltip" title="Ubah Data">
                    <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span>
                </a>
                <a href="#" itemid="'.encode($items['id_module']).'" itemname="'.ctk($items['nama_module']).'" id="delete-btn" 
                    class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Hapus Data">
                    <span class="red"><i class="ace-icon fa fa-trash-o"></i></span>
                </a>';
            
            $row[] = $no;
            $row[] = '<strong>'.ctk($items['nama_module']).'</strong>';
            $row[] = '[<strong class="blue bigger-120">'.count($enrol_soal).'</strong>] '.ctk($items['is_quiz']).'<br>'
                .st_aktif($items['is_random'], 'ya');
            $row[] = format_date($items['buka_module'],2).' s/d <br><span class="orange">'
                .format_date($items['tutup_module'],2).'</span>';
            $row[] = '<strong class="red">'.ctk($items['durasi_module']).'</strong> Menit';
            $row[] = st_aktif($items['status_module']);
            $row[] = '<div class="action-buttons">'.$btn_aksi.'</div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->m_module->count_all(),
            "recordsFiltered" => $this->m_module->count_filtered($where),
            "data" => $data,
        );
        jsonResponse($output);
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
                : '<a target="_blank" href="'.site_url('master/daftar/detail/'.encode($items['id_mhs']))
                .'"><b>'.ctk($items['nama_mhs']).'</b></a><br> '. st_mhs($items['status_mhs']);
            $row[] = $st_sesi;
            $row[] = '<b>'.$skor.'</b> / '.$jumlah_soal.'<br><b>Nilai</b> : <span class="bigger-120">[<strong class="blue">'.$nilai.'</strong>]</span>'.$is_file;
            $row[] = '<small>'. ctk($items['note_jawab']).'</small>';
            $row[] = $st_jawab.st_aktif($items['valid_jawab']);
            $row[] = '<div class="action-buttons">'.$btn_skor.' '.$btn_aksi.'</div>';
            
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
    function _is_file($file) {
        if(empty($file)){ return ''; }
        return '<embed toolbar="true" src="'.load_file($file).'" width="30%" class="blur-up img-thumbnail lazyload is-embed">';
    }
}