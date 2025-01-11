<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal extends KZ_Controller {
    
    private $module = 'elearning/jurnal';
    private $module_do = 'elearning/jurnal_do';    
    private $url_route = array('id', 'source', 'type');
    private $mode_jurnal = array('ONLINE','OFFLINE','PRAKTIKUM');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_jurnal'));
        
        $this->_dosen_id();
    }
    function index() {
        $this->load->model(array('m_prodi'));
        
        $is_smt = ($this->sessionlevel != '1') ? array('periode_aktif !=' => '2') : null;
        $this->data['semester'] = $this->m_semester->getAll($is_smt);
        $this->data['prodi'] = $this->m_prodi->getAll();
        
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Jurnal Kuliah','List Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('elearning/jurnal/v_index', $this->data);
    }
    function add() {
        if(empty($this->did)){
            $this->session->set_flashdata('notif', notif('warning', 'Informasi', 'Data Dosen tidak ditemukan'));
            redirect($this->module);
        }
        $this->data['edit'] = $this->m_jurnal->getEmpty();
        $this->data['mode_jurnal'] = $this->mode_jurnal;
        $this->data['start_date'] = in_array($this->did, $this->config->item('app.except_dosen'))
            || $this->sessionlevel == '1' ? '-12m':'-1m';
        
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Jurnal Kuliah','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('elearning/jurnal/v_form', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        if(empty($this->did)){
            $this->session->set_flashdata('notif', notif('warning', 'Informasi', 'Data Dosen tidak ditemukan'));
            redirect($this->module);
        }
        $this->data['edit'] = $this->m_jurnal->getId(decode($id));
        $this->data['mode_jurnal'] = $this->mode_jurnal;
        $this->data['start_date'] = in_array($this->did, $this->config->item('app.except_dosen'))
            || $this->sessionlevel == '1' ? '-12m':'-1m';
        
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('Jurnal Kuliah','Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('elearning/jurnal/v_form', $this->data);
    }
    function detail($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $result = $this->db->join('m_kelas k','k.id_kelas = j.kelas_id','left')
            ->join('m_prodi p','p.id_prodi = k.prodi_id','left')
            ->get_where('m_jurnal j', array('id_jurnal' => decode($id)))->row_array();
        
        $this->data['detail'] = $result;
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Jurnal Kuliah', 'Presensi');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->data['title'][0], 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('elearning/jurnal/v_detail', $this->data);
    }
    function delete($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        if(empty($this->did)){
            $this->session->set_flashdata('notif', notif('warning', 'Informasi', 'Data Dosen tidak ditemukan'));
            redirect($this->module);
        }
        $check = $this->m_jurnal->getId(decode($id));
        if(!empty(json_decode($check['presensi_jurnal']))){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Tidak dapat di hapus. Perkuliahan ini memiliki presensi Mahasiswa'));
            redirect($this->module);
        }
        $activ = $this->db->get_where('lm_module', array('jurnal_id' => decode($id)));
        if($activ->num_rows() > 0){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Tidak dapat di hapus. Data ini terhubung dengan data yang lain'));
            redirect($this->module);
        }
        $result = $this->m_jurnal->delete(decode($id));
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil dihapus'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal dihapus'));
            redirect($this->module);
        }
    }
    function cetak($id = NULL, $smtid = NULL) {
        $this->load->model(array('m_kelas'));

        $periode = empty($smtid) ? $this->smtid : decode($smtid);

        $result = $this->m_kelas->getAll(array('id_semester' => $periode, 'prodi_id' => decode($id)));
        if($result['rows'] < 1){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data Perkuliahan tidak ditemukan'));
            redirect($this->module);
        }
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Rekap '.format_date(date('Y-m-d'),1));
        $sheet->setCellValue('D1', 'REKAP MENGAJAR DOSEN');
        $sheet->setCellValue('D2', 'PROGRAM STUDI '.strtoupper($result['data'][0]['nama_prodi']));
        $sheet->setCellValue('D3', 'TAHUN AJARAN '.is_periode($periode,1));

        $fields = array('No','Nama Dosen','Kode MK','Nama MK','Kelas','SKS','Pertemuan',
            'Jumlah SKS','Offline','Praktikum','Online');
        $col = 1;
        foreach ($fields as $field) {
            $sheet->setCellValueByColumnAndRow($col, 5, $field);
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
            $col++;
        }
        $no = 1;
        $row = 6;
        $filename = url_title('REKAP '.$result['data'][0]['nama_prodi'].' '.is_periode($periode,1).
            ' per '.format_date(date('Y-m-d'),1),'-',true).'.xls';
        foreach ($result['data'] as $val) {

            $counter = ['OFFLINE' => 0,'PRAKTIKUM' => 0,'ONLINE' => 0];
            $jurnal = $this->db->get_where('m_jurnal', array('kelas_id' => $val['id_kelas']));
            if($jurnal->num_rows() > 0){
                foreach ($jurnal->result_array() as $entry) {
                    if (array_key_exists($entry['mode_jurnal'], $counter)) {
                        $counter[$entry['mode_jurnal']]++;
                    }
                }
                $sheet->setCellValue('A' . $row, $no)
                    ->setCellValue('B' . $row, $val['nama_dosen'])
                    ->setCellValue('C' . $row, $val['kode_matkul'])
                    ->setCellValue('D' . $row, $val['nama_matkul'])
                    ->setCellValue('E' . $row, $val['nama_kelas'])
                    ->setCellValueExplicit('F' . $row, $val['sks_matkul'],'n')
                    ->setCellValueExplicit('G' . $row, $jurnal->num_rows(),'n')
                    ->setCellValueExplicit('H' . $row, $jurnal->num_rows() * $val['sks_matkul'],'n')
                    ->setCellValueExplicit('I' . $row, $counter['OFFLINE'],'n')
                    ->setCellValueExplicit('J' . $row, $counter['PRAKTIKUM'],'n')
                    ->setCellValueExplicit('K' . $row, $counter['ONLINE'],'n');
                $no++;
                $row++;
            }
        }
        $tableStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => false),
            'font' => array('size' => 12, 'color' => array('rgb' => '000000'))
        ];
        $boldStyle = array(
            'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => false),
            'font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => '000000'))
        );
        $row--;
        $sheet->getStyle('A5:K'.$row)->applyFromArray($tableStyle);
        $sheet->getStyle('A5:K5')->applyFromArray($boldStyle);
        $sheet->getStyle('A1:K4')->applyFromArray($boldStyle);

        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
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
            }else if($routing_module['source'] == 'mhs') {
                $this->_table_mhs();
            }
        }else if($routing_module['type'] == 'list') {
            if($routing_module['source'] == 'dosen') {
                $this->_get_dosen();
            }else if($routing_module['source'] == 'kelas') {
                $this->_get_kelas();
            }
        }
    }
    //function
    function _table_index() {
        $periode = decode($this->input->post('periode'));
        $prodi = decode($this->input->post('prodi'));
        $dosen = decode($this->input->post('dosen'));
        $kelas = decode($this->input->post('kelas'));
        
        $where['id_semester'] = $periode;
        if($kelas != '') {
            $where['kelas_id'] = $kelas;
        }
        if($dosen != '') {
            $where['id_dosen'] = $dosen;
        }
        if(!is_null($this->did) && ($this->sessionlevel != '1')){
            $where['id_dosen'] = $this->did;
            if($kelas != '') {
                $where['kelas_id'] = $kelas;
            }
        }
        if ($prodi != '') {
            $where['prodi_id'] = $prodi;
        }
        $list = $this->m_jurnal->get_datatables($where);
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $items) {
            $no++;
            $row = array();
            
            $btn_aksi = ($this->smtid != $items['id_semester']) ? '' : '<a href="'. site_url($this->module .'/detail/'. encode($items['id_jurnal'])) .'" 
                        class="tooltip-success btn btn-white btn-success btn-sm btn-round" data-rel="tooltip" title="Presensi">
                        <span class="green"><i class="ace-icon fa fa-user-plus bigger-120"></i></span>
                    </a>
                    <a href="'. site_url($this->module .'/edit/'. encode($items['id_jurnal'])) .'" 
                        class="tooltip-warning btn btn-white btn-warning btn-sm btn-round" data-rel="tooltip" title="Ubah Data">
                        <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span>
                    </a>
                    <a href="#" itemid="'. encode($items['id_jurnal']) .'" itemprop="'. ctk($items['nama_matkul'].' - '.$items['nama_kelas']) .'" id="delete-btn" 
                        class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Hapus Data">
                        <span class="red"><i class="ace-icon fa fa-trash-o"></i></span>
                    </a>';
            $presensi = '';
            $json_presensi = json_decode($items['presensi_jurnal'], true);
            $counter = ['HADIR' => 0,'SAKIT' => 0,'IZIN' => 0,'PENDING' => 0];
            if(!empty($json_presensi)){
                foreach ($json_presensi as $entry) {
                    if (array_key_exists($entry['status'], $counter)) {
                        $counter[$entry['status']]++;
                    }
                }
                $presensi .= $counter['HADIR'] > 0 ? $counter['HADIR'].'-HADIR' : '';
                $presensi .= $counter['PENDING'] > 0 ? ' '.$counter['PENDING'].'-PENDING' : '';
                $presensi .= $counter['SAKIT'] > 0 ? ' '.$counter['SAKIT'].'-SAKIT' : '';
                $presensi .= $counter['IZIN'] > 0 ? ' '.$counter['IZIN'].'-IZIN' : '';
            }
            
            $row[] = ctk($no);
            $row[] = 'Ke - [<strong class="bigger-110 red">'.ctk($items['init_jurnal']).'</strong>] '.
                    st_mhs($items['mode_jurnal']).'<br/>'.format_date($items['tgl_jurnal']);
            $row[] = '<strong>'.ctk($items['kode_matkul']).'</strong> - <strong class="red">'.
                ctk($items['nama_kelas']).'</strong><br/>'.ctk($items['nama_matkul']);
            $row[] = ctk($items['nama_dosen']).'<br/><small>'.is_periode($items['id_semester'],1).'</small>';
            $row[] = st_aktif($items['status_jurnal']).'</br><small class="bolder">'.$presensi.'</small>';
            $row[] = ctk($items['ruang_jurnal']).'<br/>'.ctk($items['waktu_jurnal']);
            $row[] = '<div class="action-buttons">'.$btn_aksi.'</div>';

            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->m_jurnal->count_all(),
            "recordsFiltered" => $this->m_jurnal->count_filtered($where),
            "data" => $data,
        );
        jsonResponse($output);
    }
    function _table_mhs() {
        $this->load->model(array('m_akm'));
        
        $id = decode($this->input->post('id'));
        
        $check = $this->m_jurnal->getId($id);
        if(empty($check)){
            jsonResponse(array('status' => false, 'msg' => 'Jurnal tidak ditemukan'));
        }
        $json_presensi = empty($check['presensi_jurnal']) ? array() : json_decode($check['presensi_jurnal'], true);
        
        $list = $this->m_akm->getNilai(array('n.kelas_id' => $check['kelas_id']));
        if($list['rows'] < 1){
            jsonResponse(array('status' => false, 'msg' => 'Data tidak ditemukan'));
        }
        $range = '<option value=""> ? </option>';
        foreach (array('HADIR','IZIN','SAKIT') as $val) {
            $range .= '<option value="'.encode($val).'"> '.$val.' </option>';
        }
        $data = array();
        $no = 1;
        foreach ($list['data'] as $items) {
            $row = array();
            
            $search = array_filter($json_presensi, function($val) use ($items) {
                return (element('id',$val) === $items['id_mhs']);
            });
            $is_presensi = '';
            if(!empty($search)){
                $found = reset($search);
                $is_presensi = st_mhs(element('status',$found)).'<br/><small>'.format_date(element('buat',$found),0).'</small>';
            }
            $btn_aksi = (empty($search)) ? '':'<button itemid="'.encode($items['id_mhs']).'" itemname="'.ctk($items['nama_mhs']).'" id="delete-btn" 
                    class="tooltip-error btn btn-white btn-danger btn-mini btn-bold" data-rel="tooltip" title="Hapus Data">
                    <span class="red"><i class="ace-icon fa fa-trash-o bigger-120"></i></span>
                </button> ';
            $btn_aksi .= '<select class="bolder center" name="status[]" id="status'.encode($items['id_mhs']).'">'.$range.'</select>
                <button itemid="'. encode($items['id_mhs']) .'" id="ubah-btn" 
                    class="tooltip-warning btn btn-white btn-warning btn-mini btn-bold" data-rel="tooltip" title="Simpan">
                    <span class="orange"><i class="ace-icon fa fa-pencil-square-o"></i></span> Simpan
                </button>';
            $box = ' <label class="pos-rel">
                <input value="'. encode($items['id_mhs']).'" itemid="'. encode($items['id_mhs']).'" id="input-mhs" 
                    name="mhsid[]" type="checkbox" class="ace ace-checkbox-2 input-lg" />
                <span class="lbl"></span></label>';

            $row[] = $no.' '.$box;
            $row[] = '<strong>'.$items['nama_mhs'].'</strong> <br> '.$items['nim'];
            $row[] = ctk($items['nama_prodi']).' <br> '.ctk($items['angkatan']);
            $row[] = $is_presensi;
            $row[] = '<div class="action-buttons">'.$btn_aksi.'</div>';
            
            $no++;
            $data[] = $row;
        }
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
        }else{
            $result = $this->m_kelas->getAll($where, '' ,$key);
        }
        $data = array();
        foreach ($result['data'] as $val) {
            $text = ctk($val['kode_matkul']).' - '.ctk($val['nama_matkul']).' ['.ctk($val['nama_kelas']).'] - '.ctk($val['nama_prodi']);
            $data[] = array("id" => encode($val['id_kelas']), "text" => $text);
        }
        jsonResponse($data);
    }
}