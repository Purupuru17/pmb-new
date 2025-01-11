<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends KZ_Controller {
    
    private $module = 'elearning/bank';
    private $module_do = 'elearning/bank_do';
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_bank'));
    }
    function index() {
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Bank','List Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('elearning/bank/v_index', $this->data);
    }
    function add() {
        $this->data['edit'] = $this->m_bank->getEmpty();
        
        $this->data['action'] = $this->module_do . '/add';
        $this->data['title'] = array('Bank','Tambah Data');
        $this->data['breadcrumb'] = array(
            array('title' => $this->uri->segment(1), 'url' => '#'),
            array('title' => $this->uri->segment(2), 'url' => site_url($this->module)),
            array('title' => $this->data['title'][1], 'url' => '')
        );
        $this->load_view('elearning/bank/v_form', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['edit'] = $this->m_bank->getId(decode($id));
        
        $this->data['action'] = $this->module_do . '/edit/' . $id;
        $this->data['title'] = array('Bank','Ubah Data');
        $this->data['breadcrumb'] = array(
            array('title' => $this->uri->segment(1), 'url' => '#'),
            array('title' => $this->uri->segment(2), 'url' => site_url($this->module)),
            array('title' => $this->data['title'][1], 'url' => '')
        );
        $this->load_view('elearning/bank/v_form', $this->data);
    }
    function detail($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['detail'] = $this->m_bank->getId(decode($id));
        
        $this->data['action'] = $this->module_do . '/detail/' . $id;
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Bank', $this->data['detail']['nama_bank']);
        $this->data['breadcrumb'] = array(
            array('title' => $this->uri->segment(1), 'url' => '#'),
            array('title' => $this->uri->segment(2), 'url' => site_url($this->module)),
            array('title' => $this->data['title'][1], 'url' => '')
        );
        $this->load_view('elearning/bank/v_detail', $this->data);
    }
    function delete($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $check = $this->db->get_where('lm_soal', array('bank_id' => decode($id)))->num_rows();
        if($check > 0){
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal dihapus. Data ini terhubung ke data lainnya'));
            redirect($this->module);
        }
        $result = $this->m_bank->delete(decode($id));
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil dihapus'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal dihapus'));
            redirect($this->module);
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
            }
        }else if($routing_module['type'] == 'list') {
            //LIST
            if($routing_module['source'] == 'dosen') {
                $this->_get_dosen();
            }
        }
    }
    //function
    function _table_index() {
        $dosen = decode($this->input->post('dosen'));
        $jenis = $this->input->post('jenis');
        
        $where = null;
        if ($dosen != '') {
            $where['dosen_id'] = $dosen;
        }
        if ($jenis != '') {
            $where['jenis_bank'] = $jenis;
        }
        $list = $this->m_bank->get_datatables($where);
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $items) {
            $no++;
            $row = array();
            $qty_soal = $this->db->where(array('bank_id' => $items['id_bank']))->count_all_results('lm_soal');
            
            $row[] = $no;
            $row[] = '<strong>'.ctk($items['nama_bank']).'</strong>';
            $row[] = ctk($items['jenis_bank']);
            $row[] = '<strong class="orange bigger-110">'.$qty_soal.'</strong>';
            $row[] = ctk($items['nama_dosen']);
            $row[] = '<div class="action-buttons">
                    <a href="'. site_url($this->module .'/detail/'. encode($items['id_bank'])) .'"
                        class="tooltip-success btn btn-white btn-success btn-sm btn-round" data-rel="tooltip" title="Upload Data">
                        <span class="green"><i class="ace-icon fa fa-upload bigger-120"></i></span>
                    </a>
                    <a href="'. site_url($this->module .'/edit/'. encode($items['id_bank'])) .'"
                        class="tooltip-warning btn btn-white btn-warning btn-sm btn-round" data-rel="tooltip" title="Ubah Data">
                        <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span>
                    </a>
                    <a href="#" itemid="'.encode($items['id_bank']).'" itemname="'.ctk($items['nama_bank']).'" id="delete-btn" 
                        class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Hapus Data">
                        <span class="red"><i class="ace-icon fa fa-trash-o"></i></span>
                    </a>
                </div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->m_bank->count_all(),
            "recordsFiltered" => $this->m_bank->count_filtered($where),
            "data" => $data,
        );
        jsonResponse($output);
    }
    function _table_soal() {
        $this->load->model(array('m_soal'));
        
        $id = decode($this->input->post('id'));
        
        $list = $this->m_soal->getAll(array('s.bank_id' => $id));
        if($list['rows'] < 1){
            jsonResponse(array('status' => false, 'msg' => 'Data tidak ditemukan'));
        }
        $data = array('table' => array());
        $no = 1;
        foreach ($list['data'] as $val) {
            $row = array();
            $status = ($val['status_soal'] == '1') ? 'success':'danger'; 
            $opsiA = json_decode($val['opsi_a'], true);
            $opsiB = json_decode($val['opsi_b'], true);
            $opsiC = json_decode($val['opsi_c'], true);
            $opsiD = json_decode($val['opsi_d'], true);
            $opsiE = json_decode($val['opsi_e'], true);

            $row[] = '<span class="badge badge-'.$status.' bolder">'.ctk($val['order_soal']).'</span>';
            $row[] = '<strong>'.limit_text($val['isi_soal'], 100).'</strong>';
            $row[] = ctk($val['materi_soal']);
            $row[] = $this->is_opsi($opsiA).'<br> '.limit_text(element('isi', $opsiA), 50);
            $row[] = $this->is_opsi($opsiB).'<br> '.limit_text(element('isi', $opsiB), 50);
            $row[] = $this->is_opsi($opsiC).'<br> '.limit_text(element('isi', $opsiC), 50);
            $row[] = $this->is_opsi($opsiD).'<br> '.limit_text(element('isi', $opsiD), 50);
            $row[] = $this->is_opsi($opsiE).'<br> '.limit_text(element('isi', $opsiE), 50);
            $row[] = '<div class="action-buttons">
                    <a target="_blank" href="'.site_url('elearning/soal/detail/' . encode($val['id_soal'])).'" 
                        class="tooltip-info btn btn-white btn-info btn-sm btn-round" data-rel="tooltip" title="Lihat Data">
                        <span class="blue"><i class="ace-icon fa fa-search-plus"></i></span>
                    </a></div>';
            
            $data['table'][] = $row;
            $no++;
        }
        jsonResponse(array('data' => $data, 'status' => true, 'msg' => 'Data ditemukan'));
    }
    function _get_dosen(){
        $this->_dosen_id();
        
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
    function is_opsi($opsi) {
        $nilai = element('nilai', $opsi);
        switch ($nilai) {
            case '0':
                return '[<strong>'.$nilai.'</strong>] <i class="fa fa-times red"></i>';
            case null:
            case '':
                return '';
            default:
                return '[ <strong class="green bigger-110">'.$nilai.'</strong> ] <i class="fa fa-check blue"></i>';
        }   
    }
}
