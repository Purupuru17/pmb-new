<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Soal extends KZ_Controller {
    
    private $module = 'elearning/soal';
    private $module_do = 'elearning/soal_do';
    private $url_route = array('id', 'source', 'type');
    private $is_admin = null;
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_soal','m_bank'));
        
        $this->_dosen_id();
        $this->is_admin = (empty($this->did) && $this->sessionlevel == '1') 
            ? null:array('dosen_id' => $this->did);
    }
    function index() {
        $this->data['bank'] = $this->m_bank->getAll($this->is_admin);
        
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Soal','List Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('elearning/soal/v_index', $this->data);
    }
    function add() {
        $this->data['edit'] = $this->m_soal->getEmpty();
        $this->data['bank'] = $this->m_bank->getAll($this->is_admin);
        
        $this->data['opsi_array'] = array();
        $this->data['action'] = $this->module_do . '/add';
        $this->data['title'] = array('Soal','Tambah Data');
        $this->data['breadcrumb'] = array(
            array('title' => $this->uri->segment(1), 'url' => '#'),
            array('title' => $this->uri->segment(2), 'url' => site_url($this->module)),
            array('title' => $this->data['title'][1], 'url' => '')
        );
        $this->load_view('elearning/soal/v_form', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $edit = $this->m_soal->getId(decode($id));
        $this->data['bank'] = $this->m_bank->getAll($this->is_admin);
        $opsi_arr = array();
        
        if (!empty($edit['opsi_a'])) { $opsi_arr[] = json_decode($edit['opsi_a'], true); }
        if (!empty($edit['opsi_b'])) { $opsi_arr[] = json_decode($edit['opsi_b'], true); }
        if (!empty($edit['opsi_c'])) { $opsi_arr[] = json_decode($edit['opsi_c'], true); }
        if (!empty($edit['opsi_d'])) { $opsi_arr[] = json_decode($edit['opsi_d'], true); }
        if (!empty($edit['opsi_e'])) { $opsi_arr[] = json_decode($edit['opsi_e'], true); }
        
        $this->data['edit'] = $edit;
        $this->data['opsi_array'] = $opsi_arr;
        $this->data['action'] = $this->module_do . '/edit/' . $id;
        $this->data['title'] = array('Soal','Ubah Data');
        $this->data['breadcrumb'] = array(
            array('title' => $this->uri->segment(1), 'url' => '#'),
            array('title' => $this->uri->segment(2), 'url' => site_url($this->module)),
            array('title' => $this->data['title'][1], 'url' => '')
        );
        $this->load_view('elearning/soal/v_form', $this->data);
    }
    function detail($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $opsi_arr = array();
        $soal = $this->m_soal->getId(decode($id));
        
        if (!empty($soal['opsi_a'])) { $opsi_arr[] = json_decode($soal['opsi_a'], true); }
        if (!empty($soal['opsi_b'])) { $opsi_arr[] = json_decode($soal['opsi_b'], true); }
        if (!empty($soal['opsi_c'])) { $opsi_arr[] = json_decode($soal['opsi_c'], true); }
        if (!empty($soal['opsi_d'])) { $opsi_arr[] = json_decode($soal['opsi_d'], true); }
        if (!empty($soal['opsi_e'])) { $opsi_arr[] = json_decode($soal['opsi_e'], true); }
        
        $this->data['detail'] = $soal;
        $this->data['opsi_array'] = $opsi_arr;
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Soal','Detail Data');
        $this->data['breadcrumb'] = array(
            array('title' => $this->uri->segment(1), 'url' => '#'),
            array('title' => $this->uri->segment(2), 'url' => site_url($this->module)),
            array('title' => $this->data['title'][1], 'url' => '')
        );
        $this->load_view('elearning/soal/v_detail', $this->data);
    }
    function delete($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $check = $this->db->get_where('lmrf_quiz', array('soal_id' => decode($id)))->num_rows();
        if($check > 0){
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal dihapus. Data ini terhubung ke data lainnya'));
            redirect($this->module);
        }
        $file = $this->m_soal->getId(decode($id));
        //delete
        $result = $this->m_soal->delete(decode($id));
        if ($result) {
            delete_file($file['file_soal']);
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
            }
        }
    }
    //function
    function _table_index() {
        $bank = decode($this->input->post('bank'));
        
        $where = $this->is_admin;
        if ($bank != '') {
            $where['bank_id'] = $bank;
        }
        $list = $this->m_soal->get_datatables($where);
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $items) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<strong>'.limit_text($items['isi_soal'], 50).'</strong>';
            $row[] = ctk($items['materi_soal']);
            $row[] = ctk($items['nama_bank']).'<br><small>'.ctk($items['jenis_bank']).'</small>';
            $row[] = '<span class="badge badge-info bolder">'.ctk($items['order_soal']).'</span> '.
                    st_aktif($items['status_soal']);
            $row[] = '<div class="action-buttons">
                    <a href="'. site_url($this->module .'/detail/'. encode($items['id_soal'])) .'"
                        class="tooltip-info btn btn-white btn-info btn-sm btn-round" data-rel="tooltip" title="Lihat Data">
                        <span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span>
                    </a>
                    <a href="'. site_url($this->module .'/edit/'. encode($items['id_soal'])) .'"
                        class="tooltip-warning btn btn-white btn-warning btn-sm btn-round" data-rel="tooltip" title="Ubah Data">
                        <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span>
                    </a>
                    <a href="#" itemid="'.encode($items['id_soal']).'" itemname="'.limit_text($items['isi_soal'],100).'" id="delete-btn" 
                        class="tooltip-error btn btn-white btn-danger btn-mini btn-round" data-rel="tooltip" title="Hapus Data">
                        <span class="red"><i class="ace-icon fa fa-trash-o"></i></span>
                    </a>
                </div>';
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->m_soal->count_all(),
            "recordsFiltered" => $this->m_soal->count_filtered($where),
            "data" => $data,
        );
        jsonResponse($output);
    }
}
