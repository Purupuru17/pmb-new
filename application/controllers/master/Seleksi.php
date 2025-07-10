<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Seleksi extends KZ_Controller {

    private $module = 'master/seleksi';
    private $module_do = 'master/seleksi_do';
    private $url_route = array('id', 'source', 'type');

    function __construct() {
        parent::__construct();
        $this->load->model(array('m_prodi', 'm_mhs'));
    }
    function index() {
        $this->data['prodi'] = $this->m_prodi->getAll();

        $this->data['module'] = $this->module;
        $this->data['title'] = array('Seleksi', 'List Data');
        $this->data['breadcrumb'] = array(
            array('title' => 'Mahasiswa', 'url' => '#'),
            array('title' => $this->uri->segment(2), 'url' => '')
        );
        $this->load_view('master/seleksi/v_index', $this->data);
    }
    function add($id = NULL) {
        if(empty(decode($id))) {
            redirect($this->module);
        }
        $this->data['prodi'] = $this->m_prodi->getAll();
        $this->data['detail'] = $this->m_mhs->getId(decode($id));
        
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Seleksi', 'NIM PDDikti');
        $this->data['breadcrumb'] = array(
            array('title' => 'Mahasiswa', 'url' => '#'),
            array('title' => $this->uri->segment(2), 'url' => site_url($this->module)),
            array('title' => $this->data['title'][1], 'url' => '')
        );
        $this->load_view('master/seleksi/v_add', $this->data);
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if (is_null($routing_module['type'])) {
            redirect('');
        }
        if ($routing_module['type'] == 'table') {
            //LIST
            if ($routing_module['source'] == 'mhs') {
                $this->_table_mhs();
            } else if ($routing_module['source'] == 'feeder') {
                $this->_get_mhs();
            } else if ($routing_module['source'] == 'nim') {
                $this->_generate_nim();
            }  else if ($routing_module['source'] == 'insert_all') {
                $this->_insert_all();
            }
        }
    }
    //FUNCTION
    function _table_mhs() {
        $prodi = decode($this->input->post('prodi'));
        $tahun = $this->input->post('tahun');
        $jalur = $this->input->post('jalur');
        $status = $this->input->post('status');
        $kip = $this->input->post('kip');
        
        $where = null;
        if ($prodi != '') {
            $where['m.prodi_id'] = $prodi;
        }
        if ($tahun != '') {
            $where['m.angkatan'] = $tahun;
        }
        if ($jalur != '') {
            $where['m.jalur_mhs'] = $jalur;
        }
        if ($status != '') {
            $where['m.status_mhs'] = $status;
        }
        if ($kip != '') {
            $where['m.kip_mhs'] = $kip;
        }
        
        $list = $this->m_mhs->get_datatables($where);

        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row) {
            $no++;
            $rows = array();

            $set = ($row['set_by'] == '0') ? ' <i class="fa fa-flag blue"></i>' : '';
            $bio = empty($row['id_bio']) ? ' <i class="fa fa-question bigger-110 red"></i>' : ' <i class="fa fa-check-square-o green"></i>';
            $feeder = empty($row['id_reg']) ? ' <i class="fa fa-question bigger-110 red"></i>' : ' <i class="fa fa-check-square-o green"></i>';
            
            $btn_aksi = !in_array($row['status_mhs'], array('LULUS','VALID','AKTIF')) ? '' : '<a href="' . site_url($this->module . '/add/' . encode($row['id_mhs'])) . '" 
                    class="tooltip-success btn btn-white btn-success btn-round btn-sm" data-rel="tooltip" title="Tambah NIM">
                    <span class="green"><i class="ace-icon fa fa-graduation-cap bigger-120"></i></span>
                </a>';
            $btn_aksi .= '<a target="_blank" href="'. site_url('master/daftar/detail/'. encode($row['id_mhs'])) .'" class="tooltip-info btn btn-white btn-info btn-round btn-sm" data-rel="tooltip" title="Lihat Data">
                    <span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span>
                </a>';
            $btn_aksi .= empty($row['nim']) || !empty($row['id_reg']) ? '' : '<button itemid="'.encode($row['id_mhs']).'" itemname="'.ctk($row['nama_mhs']).'" id="insert-btn" 
                    class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Insert NIM">
                    <span class="red"><i class="ace-icon fa fa-paper-plane-o"></i></span>
                </button>';
            
            $rows[] = ctk($no);
            $rows[] = '<strong>#'. $row['kode_reg'] .'</strong>'.$set.'<br><small>'.$row['kip_mhs'].'</small>';
            $rows[] = '<strong>'.$row['nama_mhs'].'</strong> '.$bio;
            $rows[] = '<strong class="green">' . ctk($row['nim']) . '</strong> ' . $feeder;
            $rows[] = ctk($row['nama_prodi']);
            $rows[] = st_mhs($row['status_mhs']).'<br/><small>'.format_date($row['tgl_daftar'],2).'</small>';
            $rows[] = '<div class="action-buttons">'.$btn_aksi.'</div>';

            $data[] = $rows;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->m_mhs->count_all(),
            "recordsFiltered" => $this->m_mhs->count_filtered($where),
            "data" => $data,
        );
        jsonResponse($output);
    }
    function _get_mhs() {
        $id = decode($this->input->post('id'));
        $nim = $this->input->post('nim');
        
        $mhs = $this->m_mhs->getId($id);
        if(empty($mhs)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Data tidak ditemukan'));
        }
        $filter = "nik='{$mhs['nik']}'";
        if(!empty($nim)){
            $filter = "nik='{$mhs['nik']}' AND nim='{$nim}'";
        }
        $this->load->library(array('feeder'));
        $rs = $this->feeder->get('GetDataLengkapMahasiswaProdi', array('limit' => 1, 'filter' => $filter));
        
        if(!$rs['status']) {
            jsonResponse(array('data' => NULL, 'status' => false, 'msg' => $rs['msg']));
        }
        if(count($rs['data']) < 1) {
            jsonResponse(array('data' => NULL, 'status' => false, 'msg' => 'Data tidak ditemukan'));
        }
        if($mhs['nama_mhs'] != $rs['data'][0]['nama_mahasiswa']){
            jsonResponse(array('data' => $rs['data'][0], 'status' => false, 'msg' => 'Nama tidak sesuai dengan data PDDikti'));
        }
        //update id bio
        $this->m_mhs->update($id, array('id_bio' => $rs['data'][0]['id_mahasiswa'],
            'status_mhs' => 'VALID', 'update_mhs' => date('Y-m-d H:i:s'),
            'log_mhs' => $this->sessionname . ' mengubah Biodata'
        ));
        if($mhs['nim'] != $rs['data'][0]['nim']){
            jsonResponse(array('data' => $rs['data'][0], 'status' => false, 'msg' => 'Data NIM tidak sesuai dengan data PDDikti'));
        }
        //update id reg
        $this->m_mhs->update($id, array('id_bio' => $rs['data'][0]['id_mahasiswa'],
            'id_reg' => $rs['data'][0]['id_registrasi_mahasiswa'],
            'status_mhs' => 'AKTIF',
            'update_mhs' => date('Y-m-d H:i:s'), 'log_mhs' => $this->sessionname . ' mengubah Riwayat Pendidikan'
        ));
        jsonResponse(array('data' => $rs['data'][0], 'status' => true, 'msg' => 'Data ditemukan'));
    }
    function _generate_nim() {
        if (!$this->_validation($this->rules_nim,'ajax')) {
            jsonResponse(array('nim' => NULL, 'status' => FALSE, 'msg' => validation_errors()));
        }
        $id = decode($this->input->post('prodi'));
        $tahun = (int)$this->input->post('tahun');
        
        $prodi = $this->m_prodi->getId($id);
        $nim = $this->m_mhs->getNIM($prodi['kode_prodi'], $tahun);

        if (!empty($nim)) {
            jsonResponse(array('nim' => $nim, 'status' => TRUE, 'msg' => 'Generate NIM berhasil dibuat'));
        } else {
            jsonResponse(array('nim' => NULL, 'status' => FALSE, 'msg' => 'Generate NIM gagal dibuat'));
        } 
    }
    private $rules_nim = array(
        array(
            'field' => 'prodi',
            'label' => 'Program Studi',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'tahun',
            'label' => 'Angkatan',
            'rules' => 'required|trim|xss_clean|is_natural'
        )
    );
    
    function edit() {
//        $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
//        $spreadsheet = $reader->load('theme/img/ppg.xlsx');
//        $sheet = $spreadsheet->getActiveSheet()->toArray();
//        unset($sheet[0]);
        
        $result = $this->db->get_where('m_mhs', array('prodi_id' => '2edd800d-c39c-4bb2-8c85-c1a1e3f3cf40',
            'status_mhs' => 'LULUS', 'nim <>' => ''));
        if($result->num_rows() < 1){
            exit(0);
        }
        $output = array();
        $no = 1;
        foreach ($result->result_array() as $val) {
            //if(!empty($val[])){
            
                //$up = $this->db->where('id_mhs', $val['id_mhs'])->update('m_mhs', ['nim' => $nim, 'status_mhs' => 'LULUS']);
                //if($up){
                    $output[] = array(
                        'nim' => $nim,
                        'nama' => $val['nama_mhs'],
                        'status' => 'LULUS'
                    );
//                }else{
//                    $output[] = array(
//                        'nim' => $val['nim'],
//                        'nama' => $val['nama_mhs'],
//                        'status' => 'GAGAL'
//                    );
//                }
                $no++;
                $nim++;
            //}
        }
        $this->_into_tables($output,1);
    }
    function _into_tables($rs, $type = NULL){
        if(is_null($type)){
            if(!$rs['status']){
                echo json_encode($rs);
                exit();
            }
            $data = $rs['data'];
        }else{
            $data = $rs;
        }
        if(count($data) < 1){
            echo 'Data Kosong';
            exit();
        }
        $i = 0;
        $str = '<style>
        td, th {
          border: 1px solid #ddd;
          text-align: center;
          padding: 8px;
          white-space: nowrap;
        }
        table {
          font-family: arial, sans-serif;
          font-size:12px;
          width: auto;
          border-collapse: collapse;
        }
        </style><table>';
        foreach ($data as $row) {
            if (!$i) {
                $str .= '<tr>';
                $str .= '<th>no.</th>';
                foreach (array_keys($row) as $k => $v) {
                    $str .= '<th>';
                    $str .= $v;
                    $str .= '</th>';
                }
                $str .= '</tr>';
            }
            $str .= '<tr>';
            $i++;
            $style = '';
            foreach ($row as $k => $v) {
                if (strtolower($k) == 'soft_delete' && $v == '1') {
                    $style = 'style="text-decoration:line-through"';
                }
            }
            $str .= "<td $style >$i.</td>";
            foreach ($row as $k => $v) {
                $str .= "<td $style>";
                if (!is_array($v))
                    $str .= $v;
                $str .= '&nbsp;</td>';
            }
            $str .= '</tr>';
        }
        $str .= '</table>';
        
        echo $str;
        exit();
    }
    function _insert_all() {
        $id = decode($this->input->post('id'));
        
        $mhs = $this->m_mhs->getId($id);
        if(empty($mhs)) {
            jsonResponse(array('status' => FALSE, 'msg' => 'Data mahasiswa tidak ditemukan'));
        }
        switch ($mhs['agama']) {
            case 'Islam':
                $agama = 1;
                break;
            case 'Kristen':
                $agama = 2;
                break;
            case 'Katolik':
                $agama = 3;
                break;
            case 'Hindu':
                $agama = 4;
                break;
            case 'Budha':
                $agama = 5;
                break;
            case 'Konghucu':
                $agama = 6;
                break;
            default:
                $agama = 99;
                break;
        }
        $data['nama_mahasiswa'] = $mhs['nama_mhs'];
        $data['jenis_kelamin'] = ($mhs['kelamin_mhs'] == 'Perempuan') ? 'P' : 'L';
        $data['tempat_lahir'] = ($mhs['tempat_lahir']);
        $data['tanggal_lahir'] = $mhs['tgl_lahir'];
        $data['id_agama'] = $agama;
        $data['nik'] = $mhs['nik'];
        $data['nisn'] = $mhs['nisn'];
        $data['kewarganegaraan'] = 'ID';
        $data['jalan'] = $mhs['jalan'];
        $data['rt'] = $mhs['rt'];
        $data['rw'] = $mhs['rw'];
        $data['dusun'] = $mhs['kelurahan'];
        $data['kelurahan'] = $mhs['kelurahan'];
        $data['id_wilayah'] = $mhs['kecamatan'];
        $data['handphone'] = $mhs['telepon_mhs'];
        $data['email'] = $mhs['email_mhs'];
        $data['penerima_kps'] = '0';
        $data['nama_ibu_kandung'] = $mhs['ibu_kandung'];
        $data['id_kebutuhan_khusus_mahasiswa'] = '0';
        $data['id_kebutuhan_khusus_ayah'] = '0';
        $data['id_kebutuhan_khusus_ibu'] = '0';

        $this->load->library(array('feeder'));
        //Insert Biodata
        $rs = $this->feeder->post('InsertBiodataMahasiswa', $data);
        if(!$rs['status']) {
           jsonResponse(array('data' => null, 'status' => false, 'msg' => $rs['msg']));
        }
        if(count($rs['data']) < 1) {
            jsonResponse(array('data' => null, 'status' => false, 'msg' => 'Data gagal tersimpan'));
        }
        if(empty($rs['data']['id_mahasiswa'])) {
            jsonResponse(array('data' => null, 'status' => false, 'msg' => 'Data gagal tersimpan. ID Biodata Mahasiswa tidak ditemukan'));
        }
        //Update MHS
        $id_bio = $rs['data']['id_mahasiswa'];
        $this->m_mhs->update($id, array('id_bio' => $id_bio,
            'status_mhs' => 'VALID', 'update_mhs' => date('Y-m-d H:i:s'), 'log_mhs' => $this->sessionname . ' insert Biodata'));
        
        $prodi = $mhs['prodi_id']; //decode($this->input->post('prodi'));
        $nim = $mhs['nim']; //$this->input->post('nim');
        $tahun = $mhs['angkatan']; //$this->input->post('tahun');
        $periode = $this->config->item('app.periode'); //$this->input->post('periode');
        $tanggal = $this->config->item('app.tanggal'); //$this->input->post('tanggal');
        $jenis = $this->config->item('app.jenis_daftar'); //$this->input->post('jenis');
        
        $cek_nim = $this->m_mhs->getId(array('nim' => $nim));
        if(!is_null($cek_nim)){
            if($cek_nim['id_mhs'] != $id){
                jsonResponse(array('data' => $cek_nim,'status' => FALSE, 'msg' => 'NIM sudah terpakai di data PMB'));    
            }
        }       
        //Cek NIM
        $check = $this->feeder->get('GetListMahasiswa', array('limit' => 2, 'filter' => "nim='{$nim}'"));
        if(!$check['status']) {
            jsonResponse(array('data' => null,'status' => false, 'msg' => $check['msg']));
        }
        if(count($check['data']) > 0) {
            jsonResponse(array('data' => $check['data'][0], 'status' => false, 'msg' => 'NIM sudah terpakai di Feeder PDDikti'));
        }
        //Insert Riwayat
        $akm['id_mahasiswa'] = $id_bio;
        $akm['nim'] = $nim;
        $akm['id_jenis_daftar'] = $jenis;
        $akm['id_jalur_daftar'] = 12;
        $akm['id_periode_masuk'] = $periode;
        $akm['tanggal_daftar'] = $tanggal;
        $akm['id_perguruan_tinggi'] = 'aa90e1dd-4905-440c-93c3-68753ef9061e';
        $akm['id_prodi'] = $prodi;
        $akm['id_pembiayaan'] = 1;
        $akm['biaya_masuk'] = 800000;
        
        if(in_array($jenis, ['13','16','17','18'])){
            $akm['id_perguruan_tinggi_asal'] = 'aa90e1dd-4905-440c-93c3-68753ef9061e';
            $akm['id_prodi_asal'] = $prodi;
        }
        if(in_array($jenis, ['17','18'])){
            $akm['id_pembiayaan'] = 3;
        }
        $rs_akm = $this->feeder->post('InsertRiwayatPendidikanMahasiswa', $akm);
        if(!$rs_akm['status']) {
           jsonResponse(array('data' => null, 'status' => false, 'msg' => $rs_akm['msg']));
        }
        if(count($rs_akm['data']) < 1) {
            jsonResponse(array('data' => null, 'status' => false, 'msg' => 'Data gagal tersimpan'));
        }
        if(empty($rs_akm['data']['id_registrasi_mahasiswa'])) {
            jsonResponse(array('data' => null, 'status' => false, 'msg' => 'Data gagal tersimpan. ID Riwayat Pendidikan tidak ditemukan : '.json_encode($rs['data'])));
        }
        //Update MHS
        $this->m_mhs->update($id, array('id_reg' => $rs_akm['data']['id_registrasi_mahasiswa'],
            'prodi_id' => $prodi, 'nim' => $nim, 'angkatan' => $tahun, 'status_mhs' => 'AKTIF',
            'update_mhs' => date('Y-m-d H:i:s'), 'log_mhs' => $this->sessionname . ' insert Riwayat Pendidikan'));
        
        jsonResponse(array('status' => true, 'msg' => 'Data '.$mhs['nama_mhs'].
            ' berhasil tersimpan : '.$rs_akm['data']['id_registrasi_mahasiswa']));
    }
}
