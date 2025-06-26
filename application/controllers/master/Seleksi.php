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
        
        $mhs = $this->m_mhs->getId($id);
        if(empty($mhs)){
            jsonResponse(array('status' => FALSE, 'msg' => 'NIM atau ID masih kosong'));
        }
        $this->load->library(array('feeder'));
        $rs = $this->feeder->get('GetDataLengkapMahasiswaProdi', array('limit' => 1, 'filter' => "nik='{$mhs['nik']}'"));
        
        if(!$rs['status']) {
            jsonResponse(array('data' => NULL, 'status' => false, 'msg' => $rs['msg']));
        }
        if(count($rs['data']) < 1) {
            jsonResponse(array('data' => NULL, 'status' => false, 'msg' => 'Data tidak ditemukan'));
        }
        if($mhs['nik'] != $rs['data'][0]['nik']){
            jsonResponse(array('data' => $rs['data'][0], 'status' => false, 'msg' => 'Data NIK tidak sesuai dengan data PDDikti'));
        }
        //update id bio
        $this->m_mhs->update($id, array('id_bio' => $rs['data'][0]['id_mahasiswa'],
            'status_mhs' => 'VALID', 'update_mhs' => date('Y-m-d H:i:s'),
            'log_mhs' => $this->sessionname . ' mengubah Biodata'
        ));
        if(empty($mhs['nim'])){
            jsonResponse(array('data' => NULL, 'status' => false, 'msg' => 'NIM masih kosong'));
        }
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
        $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load('theme/img/ppg.xlsx');
        $sheet = $spreadsheet->getActiveSheet()->toArray();
        unset($sheet[0]);
        
        $output = array();
        $no = 1;
        foreach ($sheet as $val) {
            if(!empty($val[1])){
            
                $rs = $this->db->get_where('m_mhs', array('nik' => $val[3]))->row_array();
                
                if(!empty($rs)){
                    $this->db->where('nik', $val[3])->update('m_mhs', ['nim' => $val[1], 'status_mhs' => 'LULUS']);
                    
                    if($this->db->affected_rows() > 0){
                        $output[] = array(
                            'nim' => $rs['nim'],
                            'nama' => $rs['nama_mhs'],
                            'nik' => $rs['nik'],
                            'nim_excel' => $val[1],
                            'nama_excel' => $val[2],
                            'status' => 'INSERT'
                        );
                    }else{
                        $output[] = array(
                            'nim' => $rs['nim'],
                            'nama' => $rs['nama_mhs'],
                            'nik' => $rs['nik'],
                            'nim_excel' => $val[1],
                            'nama_excel' => $val[2],
                            'status' => $rs['status_mhs']
                        );
                    }
                }else{
                    $output[] = array(
                        'nim' => 'x',
                        'nama' => 'x',
                        'nik' => 'x',
                        'nim_excel' => $val[1],
                        'nama_excel' => $val[2],
                    );
                }
                
                $no++;
            }
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
}
