<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends KZ_Controller {

    private $module = 'mhs/profil';
    private $module_do = 'mhs/profil_do';
    private $url_route = array('id', 'source', 'type');

    function __construct() {
        parent::__construct();
        
        $this->load->model(array('m_mhs'));
        $this->_getMhs();
    }
    function index() {
        $detail = $this->m_mhs->getId($this->mid);
        if(is_null($detail)) {
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect('beranda');
        }
        if(empty($detail['agama']) || empty($detail['kecamatan'])){
            redirect($this->module.'/edit');
        }
        $this->data['valid_test'] = (in_array($detail['status_mhs'], ['PENDAFTARAN','TES SELEKSI'])) ? FALSE : FALSE;
        $this->data['detail'] = $detail;
        $this->data['user'] = $this->m_mhs->getTMP(array('mhs_id' => $this->mid));
        
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Pendaftaran', $detail['nama_mhs']);
        $this->data['breadcrumb'] = array(
            array('title' => 'Mahasiswa', 'url' => '#'),
            array('title' => $this->uri->segment(2), 'url' => '')
        );
        $this->load_view('mhs/profil/v_index', $this->data);
    }
    function edit() {
        $this->data['edit'] = $this->m_mhs->getId($this->mid);
        
        $this->data['module'] = $this->module;
        $this->data['action'] = $this->module_do . '/edit/';
        $this->data['title'] = array('Profil', 'Ubah Data');
        $this->data['breadcrumb'] = array(
            array('title' => 'Mahasiswa', 'url' => '#'),
            array('title'=> $this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title' => $this->data['title'][1], 'url' => '')
        );
        $this->load_view('mhs/profil/v_form', $this->data);
    }
    function cetak() {
        $this->load->model(array('m_prodi'));
        $this->load->library(array('fungsi'));
        
        $detail = $this->m_mhs->getId($this->mid);
        if(is_null($detail)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect($this->module);
        }
        if(!in_array($detail['status_mhs'], ['LULUS','VALID','AKTIF'])){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan'
                , 'Mohon maaf anda belum dinyatakan LULUS. KTM hanya terbit ketika Status Mahasiswa : LULUS & AKTIF'));
            redirect($this->module);
        }
        $this->data['detail'] = $detail;
        $this->data['prodi'] = $this->m_prodi->getId($detail['prodi_id']);
        
        $title = 'KARTU MAHASISWA SEMENTARA';
        $this->data['judul'] = array($title, null);
        $this->fungsi->PdfGenerate($this->load->view('mhs/profil/v_kartu', $this->data, true), 
            url_title($title.' '.$detail['nim'].' '.$detail['nama_mhs'], '-', true));
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if($routing_module['type'] == 'table') {
            //LIST
            if($routing_module['source'] == 'wilayah') {
                $this->_get_wilayah();
            }
        }
    }
    //function
    function _get_wilayah(){
        $id = $this->input->get('id');
        $keyword = $this->input->post('key');
        $opsi = $this->input->post('opsi');
            
        if(!empty($id)){
            $this->db->where('id_wilayah', $id);
        }else{
            if($opsi == 'Kab'){
                $this->db->where('RIGHT(id_wilayah, 2) =', '00');
                $this->db->where('RIGHT(id_wilayah, 4) !=', '0000');
            }else{
                $this->db->where('LEFT(id_wilayah, 4) =', substr($opsi, 0, 4));
                $this->db->where('RIGHT(id_wilayah, 2) !=', '00');
            }
            $this->db->like('nama_wilayah', $keyword);
        }
        $result = $this->db->get('m_wilayah');
        
        $data = array();
        foreach ($result->result_array() as $val) {
            $data[] = array("id" => ($val['id_wilayah']), "text" => $val['nama_wilayah']);
        }
        jsonResponse($data);
    }
}
