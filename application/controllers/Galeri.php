<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Galeri extends KZ_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_galeri'));
    }
    function index() {
        $this->load->library(array('fungsi'));
        
        $param = $this->input->get(null, TRUE);
        $page = element('pg', $param, 1);
        $url = current_url() . '?';
        $limit = 12;
        $offset = ($page) ? ($page - 1) * $limit : 0;
        
        $data = $this->m_galeri->getAll(array('status_galeri' => '1'), 'desc', $limit, $offset);
        $count = $this->m_galeri->countAll(array('status_galeri' => '1'));
        
        $this->data['galeri'] = $data;
        $this->data['pagination'] = $this->fungsi->SetPaging($url, $count, $limit);
        
        $this->load_home('home/page/h_galeri', $this->data);
    }
    function detail($slug = NULL) {
        if(is_null($slug)){
            redirect('');
        }
        $detail = $this->m_galeri->getSlug($slug);
        if(is_null($detail)){
            redirect('home/err_404');
        }
        $this->data['detail'] = $detail;
        $this->data['meta'] = array(
            'title' => ($detail) ? $detail['judul_galeri'] : NULL, 
            'description' => ($detail) ? $detail['isi_galeri'] : NULL,
            'thumbnail' => ($detail) ? base_url($detail['foto_galeri']) : NULL
        );
        $this->load_home('home/page/h_galeri_detail', $this->data);
    }
}
