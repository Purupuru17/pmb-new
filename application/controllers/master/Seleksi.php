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
            
            $btn_aksi = !in_array($row['status_mhs'], array('LULUS','VALID','AKTIF')) ? '' : 
                '<a href="' . site_url($this->module . '/add/' . encode($row['id_mhs'])) . '" 
                    class="tooltip-success btn btn-white btn-success btn-round btn-sm" data-rel="tooltip" title="Tambah NIM">
                    <span class="green"><i class="ace-icon fa fa-graduation-cap bigger-120"></i></span>
                </a>';
            $btn_aksi .= '<a target="_blank" href="'. site_url('master/daftar/detail/'. encode($row['id_mhs'])) .'" 
                class="tooltip-info btn btn-white btn-info btn-round btn-mini" data-rel="tooltip" title="Lihat Data">
                    <span class="blue"><i class="ace-icon fa fa-search-plus"></i></span>
                </a>';
            
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
        if (!$this->fungsi->Validation($this->rules_nim,'ajax')) {
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
    function _insert_all() {
        $this->load->library(array('feeder'));
        
        if (!$this->fungsi->Validation($this->rules_insert,'ajax')) {
            jsonResponse(array('status' => FALSE, 'msg' => validation_errors()));
        }
        $limit = 1;
        $select = $this->db->order_by('kode_reg ASC')->limit($limit)
        ->get_where('m_mhs', [
            'prodi_id' => decode($this->input->post('prodi')),
            'status_mhs' => $this->input->post('status'),
            'angkatan' => $this->input->post('tahun'),
            'nim <>' => '' 
        ]);
        if($select->num_rows() < 1){
            jsonResponse(array('status' => FALSE, 'msg' => 'Data tidak ditemukan'));
        }
        $success = array();
        $error = array();
        foreach ($select->result_array() as $mhs) {
            
            $id = $mhs['id_mhs'];
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

            //Insert Biodata
            $rs = $this->feeder->post('InsertBiodataMahasiswa', $data);
            if(!$rs['status']) {
               $error[] = $mhs['nim'].' '.$rs['msg'];
               continue;
            }
            if(count($rs['data']) < 1) {
                $error[] = $mhs['nim'].' data gagal disimpan';
                continue;
            }
            if(empty($rs['data']['id_mahasiswa'])) {
                $error[] = $mhs['nim'].' id biodata tidak ada';
                continue;
            }
            //Update MHS
            $id_bio = $rs['data']['id_mahasiswa'];
            $this->m_mhs->update($id, array('id_bio' => $id_bio,
                'status_mhs' => 'VALID', 'update_mhs' => date('Y-m-d H:i:s'), 'log_mhs' => $this->sessionname . ' insert Biodata'));

            $prodi = $mhs['prodi_id'];
            $nim = $mhs['nim'];
            $tahun = $mhs['angkatan'];
            
            $periode = config_item('pmb')['periode'];
            $tanggal = config_item('pmb')['tanggal'];
            $jenis = config_item('pmb')['jenis'];

            $cek_nim = $this->m_mhs->getId(array('nim' => $nim));
            if(!is_null($cek_nim) && $cek_nim['id_mhs'] != $id){
                $error[] = $mhs['nim'].' nim terpakai di PMB';
                continue;
            }       
            //Cek NIM
            $check = $this->feeder->get('GetListMahasiswa', array('limit' => 2, 'filter' => "nim='{$nim}'"));
            if(!$check['status']) {
                $error[] = $mhs['nim'].' '.$check['msg'];
                continue;
            }
            if(count($check['data']) > 0) {
                $error[] = $mhs['nim'].' nim terpakai di Neofeeder PDDikti';
                continue;
            }
            //Insert Riwayat
            $akm['id_mahasiswa'] = $id_bio;
            $akm['nim'] = $nim;
            $akm['id_jenis_daftar'] = $jenis;
            $akm['id_jalur_daftar'] = 12; //seleksi mandiri
            $akm['id_periode_masuk'] = $periode;
            $akm['tanggal_daftar'] = $tanggal;
            $akm['id_perguruan_tinggi'] = 'aa90e1dd-4905-440c-93c3-68753ef9061e';
            $akm['id_prodi'] = $prodi;
            $akm['id_pembiayaan'] = 1; //mandiri
            $akm['biaya_masuk'] = 2000000;

            if(in_array($jenis, ['13','16','17','18'])){
                $akm['id_perguruan_tinggi_asal'] = 'aa90e1dd-4905-440c-93c3-68753ef9061e';
                $akm['id_prodi_asal'] = $prodi;
            }
            if(in_array($jenis, ['17','18'])){
                $akm['id_jalur_daftar'] = 11; //instansi
                $akm['id_pembiayaan'] = 3; //beasiswa
            }
            $rs_akm = $this->feeder->post('InsertRiwayatPendidikanMahasiswa', $akm);
            if(!$rs_akm['status']) {
               $error[] = $mhs['nim'].' '.$rs_akm['msg'];
               continue;
            }
            if(count($rs_akm['data']) < 1) {
                $error[] = $mhs['nim'].' riwayat data gagal disimpan';
                continue;
            }
            if(empty($rs_akm['data']['id_registrasi_mahasiswa'])) {
                $error[] = $mhs['nim'].' id riwayat tidak ada';
                continue;
            }
            //Update MHS
            $this->m_mhs->update($id, array('id_reg' => $rs_akm['data']['id_registrasi_mahasiswa'],
                'prodi_id' => $prodi, 'nim' => $nim, 'angkatan' => $tahun, 'status_mhs' => 'AKTIF',
                'update_mhs' => date('Y-m-d H:i:s'), 'log_mhs' => $this->sessionname . ' insert Riwayat Pendidikan'));
            
            $success[] = $mhs['nim'].' Data berhasil disimpan';
        }
        jsonResponse(array('status' => true, 'msg' => 
            count($success).' Data berhasil disimpan.<br>'.
            count($error).' Data gagal : '.json_encode($error)
        ));
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
    private $rules_insert = array(
        array(
            'field' => 'prodi',
            'label' => 'Program Studi',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'tahun',
            'label' => 'Angkatan',
            'rules' => 'required|trim|xss_clean|is_natural'
        ),array(
            'field' => 'status',
            'label' => 'Status',
            'rules' => 'required|trim|xss_clean'
        )
    );
}
