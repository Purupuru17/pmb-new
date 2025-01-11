<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Daftar extends KZ_Controller {
    
    private $module = 'master/daftar';
    private $module_do = 'master/daftar_do';    
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_mhs','m_prodi'));
    }
    function index() {
        $this->data['prodi'] = $this->m_prodi->getAll();
        
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Pendaftaran','List Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>'Mahasiswa', 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('master/daftar/v_index', $this->data);
    }
    function add() {
        $this->data['edit'] = $this->m_mhs->getEmpty();
        $this->data['prodi'] = $this->m_prodi->getAll();
        $this->data['kode_reg'] = $this->m_mhs->getNomor('UNMD');
        
        $this->data['form'] = 'add';
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Pendaftaran','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>'Mahasiswa', 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('master/daftar/v_form', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['edit'] = $this->m_mhs->getId(decode($id));
        $this->data['prodi'] = $this->m_prodi->getAll();
        
        $this->data['form'] = 'edit';
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('Pendaftaran','Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>'Mahasiswa', 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('master/daftar/v_form', $this->data);
    }
    function detail($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->load->model(array('m_ortu','m_berkas'));
        $this->load->library(array('cat'));
        
        $this->data['detail'] = $this->m_mhs->getId(decode($id));
        $this->data['user'] = $this->m_mhs->getTMP(array('mhs_id' => decode($id)));
        $this->data['ortu'] = $this->m_ortu->getAll(array('mhs_id' => decode($id)));
        $this->data['berkas'] = $this->m_berkas->getAll(array('mhs_id' => decode($id)));
        
        $auth = $this->cat->Auth($this->data['detail']);
        $this->data['params'] = $auth['data'];
        $this->data['url_auth'] = $auth['url'].'GetSkor';
        
        $this->data['module'] = $this->module;
        $this->data['act_berkas'] = $this->module_do.'/detail/'.$id.'/';
        $this->data['title'] = array('Pendaftaran', $this->data['detail']['nama_mhs']);
        $this->data['breadcrumb'] = array( 
            array('title'=>'Mahasiswa', 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('master/daftar/v_detail', $this->data);
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if($routing_module['type'] == 'table') {
            //LIST
            if($routing_module['source'] == 'mhs') {
                $this->_table_mhs();
            }else if($routing_module['source'] == 'wilayah') {
                $this->_get_wilayah();
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
            
            $set = ($row['set_by'] == '0') ? ' <i class="fa fa-check blue bigger-110"></i>' : '';
            $aksi = '<div class="action-buttons">
                        <a href="'. site_url($this->module .'/detail/'. encode($row['id_mhs'])) .'" class="tooltip-info btn btn-white btn-info btn-round btn-sm" data-rel="tooltip" title="Lihat Data">
                            <span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span>
                        </a>
                        <a href="'. site_url($this->module .'/edit/'. encode($row['id_mhs'])) .'" class="tooltip-warning btn btn-white btn-warning btn-round btn-sm" data-rel="tooltip" title="Ubah Data">
                            <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span>
                        </a></div>';
            
            $rows[] = ctk($no);
            $rows[] = '<strong>#'. $row['kode_reg'] .'</strong> <br>'.ctk($row['jalur_mhs']);
            $rows[] = '<strong class="blue">'. $row['nama_mhs'] .'</strong><hr class="margin-5">'.$row['nik'];
            $rows[] = ctk($row['nama_prodi']).'<hr class="margin-5"><small>'. str_replace('|', ', ', ctk($row['opsi_prodi']).'</small>');
            $rows[] = ctk($row['nisn']).'<hr class="margin-5">'.ctk($row['sekolah']);
            $rows[] = ctk($row['kelamin_mhs']).'<br/>'.ctk($row['telepon_mhs']);//.'<br/>'.ctk($row['ibu_kandung']);
            $rows[] = st_mhs($row['status_mhs']).$set.'<br/>'.format_date($row['tgl_daftar'],2);
            $rows[] = $aksi;

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
    function _get_wilayah(){
        $id = $this->input->get('id');
        $query = $this->input->post('q');
            
        if(!empty($id)){
            $this->db->where('id_wilayah', $id);
        }else{
            $this->db->like('nama_wilayah', $query); 
        }
        $result = $this->db->get('m_wilayah');
        
        $data = array();
        foreach ($result->result_array() as $val) {
            $data[] = array("id" => ($val['id_wilayah']), "text" => $val['nama_wilayah']);
        }
        jsonResponse($data);
    }
}
