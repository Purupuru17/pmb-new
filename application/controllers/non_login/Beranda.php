<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Beranda extends KZ_Controller {
    
    private $module = 'non_login/beranda';
    private $url_route = array('id', 'source', 'type'); 
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_mhs'));
    }
    function index() {
        empty($this->sessionid) ? redirect('non_login/login') : null;
        
        $this->data['groupid'] = $this->sessiongroup;
        $this->_statistik();
        
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Beranda','');
        $this->data['breadcrumb'] = array( 
            array('title'=>'Beranda', 'url'=>'#')
        );
        $this->load_view('non_login/v_home', $this->data);
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if ($routing_module['type'] == 'chart') {
            //CHART
            if ($routing_module['source'] == 'maba') {
                $this->_chart_maba();
            }else if ($routing_module['source'] == 'prodi') {
                $this->_chart_prodi();
            }else if ($routing_module['source'] == 'sekolah') {
                $this->_chart_sekolah();
            }
        }
    }
    function err_404() {
        $this->data['breadcrumb'] = array( 
            array('title'=>'Halaman Tidak Ditemukan', 'url'=>'#')
        );
        $this->load_view('errors/html/error_404', $this->data);
    }
    function err_module() {
        $this->data['breadcrumb'] = array( 
            array('title'=>'Gagal Akses Module', 'url'=>'#')
        );
        $this->load_view('errors/html/error_module', $this->data);
    }
    //function
    function _chart_maba() {
        $awal = empty($this->input->post('awal')) ? date('Y-m-d') : $this->input->post('awal');
        $akhir = empty($this->input->post('akhir')) ? date('Y-m-d') : $this->input->post('akhir');
        $range = format_date($awal,1).' s/d '.format_date($akhir,1);
    
        $where['m.angkatan'] = $this->input->post('tahun');
        $rs = $this->m_mhs->get_chart_range($where, 'prodi', $awal, $akhir);
        
        $data = array();
        $total = 0;
        if($rs['rows'] > 0){
            foreach ($rs['data'] as $item) {
                $row = array();
                $row['maba'] = (int) $item['maba'];
                $row['day'] = $item['prodi'];
                
                $data[] = $row;
                $total += $item['maba'];
            }
        }else{
            $data[] = array(array('maba' => 0, 'day' => ''));
        }
        jsonResponse(array('data' => $data, 'total' => $total, 'range' => $range));
    }
    function _chart_prodi() {
        $where['m.angkatan'] = $this->input->post('tahun');
        $rs = $this->m_mhs->get_chart_range($where, 'prodi');
        
        $data = array();
        $total = 0;
        if($rs['rows'] > 0){
            foreach ($rs['data'] as $item) {
                $row = array();
                $row['maba'] = (int) $item['maba'];
                $row['prodi'] = $item['prodi'];
                
                $data[] = $row;
                $total += $item['maba'];
            }
        }else{
            $data[] = array(array('maba' => 0, 'prodi' => ''));
        }
        jsonResponse(array('data' => $data, 'total' => $total, 'range' => 'Program Studi'));
    }
    function _chart_sekolah() {
        $where['m.angkatan'] = $this->input->post('tahun');
        $rs = $this->m_mhs->get_chart_range($where, 'sekolah');
        
        $data = array();
        $total = 0;
        if($rs['rows'] > 0){
            foreach ($rs['data'] as $item) {
                $row = array();
                $row['maba'] = (int) $item['maba'];
                $row['npsn'] = $item['npsn'].' <br> '.$item['sekolah'];
                
                $data[] = $row;
                $total += $item['maba'];
            }
        }else{
            $data[] = array(array('maba' => 0, 'npsn' => ''));
        }
        jsonResponse(array('data' => $data, 'total' => $total, 'range' => 'Asal Sekolah'));
    }
    function _statistik() {
        $where['m.angkatan'] = date('Y');
        $where['p.fakultas'] = 'Fakultas Keguruan dan Ilmu Pendidikan';
        $this->data['fkip'] = $this->m_mhs->getAll($where)['rows'];
        
        $where['p.fakultas'] = 'Fakultas Sains Teknologi';
        $this->data['fst'] = $this->m_mhs->getAll($where)['rows'];
        
        $where['p.fakultas'] = 'Fakultas Ilmu Sosial dan Humaniora';
        $this->data['fishum'] = $this->m_mhs->getAll($where)['rows'];
    }
}
