<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends KZ_Controller {
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_artikel'));
    }
    function index() {
        $this->load->model(array('m_jenis','m_galeri'));
        $this->data['galeri'] = $this->m_galeri->getAll(array('status_galeri' => '1'), 'desc', 10);
        $this->data['terbaru'] = $this->m_artikel->getAll(array('a.status_artikel' => '1'), 'desc', 8);
        $this->_visit();
        
        $this->load_home('home/h_home', $this->data);
    }
    function pages($slug = NULL){
        if(is_null($slug)){
            redirect('');
        }
        $this->load->model(array('m_page','m_kutipan'));
        $pages = $this->m_page->getSlug($slug);
        if(is_null($pages)){
            redirect('home/err_404');
        }
        $this->data['detail'] = $pages;
        $this->data['populer'] = $this->m_artikel->getAll(array(
            'a.status_artikel' => '1',
            'a.is_popular' => '1',
        ), 'RANDOM', 10);
        $this->data['kutipan'] = $this->m_kutipan->getAll(NULL, 'RANDOM', 5);
        
        $this->data['meta'] = array(
            'title' => ($pages) ? $pages['judul_page'] : NULL, 
            'description' => ($pages) ? $pages['isi_page'] : NULL,
            'thumbnail' => ($pages) ? base_url($pages['foto_page']) : NULL
        );
        $this->load_home('home/page/h_pages', $this->data);
    }
    function artikel($slug = NULL){
        if(is_null($slug)){
            redirect('');
        }
        $this->load->model(array('m_jenis'));
        
        $artikel = $this->m_artikel->getSlug($slug);
        if(is_null($artikel)){
            redirect('home/err_404');
        }
        $viewer = intval($artikel['view_artikel']) + 1; 
        $this->m_artikel->update($artikel['id_artikel'], array('view_artikel' => $viewer));
        
        $artikel['view_artikel'] = $viewer;
        $this->data['detail'] = $artikel;
        $this->data['terbaru'] = $this->m_artikel->getAll(array('a.status_artikel' => '1'), 'desc', 10);
        $this->data['jenis'] = $this->m_jenis->getAll();
        
        $this->data['meta'] = array(
            'title' => ($artikel) ? $artikel['judul_artikel'] : NULL, 
            'description' => ($artikel) ? $artikel['isi_artikel'] : NULL,
            'thumbnail' => ($artikel) ? base_url($artikel['foto_artikel']) : NULL
        );
        $this->load_home('home/page/h_artikel', $this->data);
    }
    function tag($slug = NULL) {
        if(is_null($slug)){
            redirect('');
        }
        $this->load->model(array('m_jenis','m_kutipan'));
        $this->load->library(array('fungsi'));
        
        $param = $this->input->get(null, TRUE);
        $search = element('q', $param);
        $page = element('pg', $param, 1);
        $url = empty($search) ? current_url() . '?' : current_url() . '?q=' . $search;
        $limit = 16;
        $offset = ($page) ? ($page - 1) * $limit : 0;

        $title = 'Semua Kategori';
        $where['a.status_artikel'] = '1';
        if($slug != 'all'){
            $jenis = $this->m_jenis->getSlug($slug);
            if(is_null($jenis)){
                redirect('');
            }
            $where['j.slug_jenis'] = $slug;
            $title = $jenis['judul_jenis'];
        }
        $data = $this->m_artikel->getAll($where, 'desc', $limit, $offset, $search);
        $count = $this->m_artikel->countAll($where, $search);
        
        $this->data['populer'] = $this->m_artikel->getAll(array(
            'a.status_artikel' => '1',
            'a.is_popular' => '1',
        ), 'RANDOM', 10);
        $this->data['terbaru'] = $data;
        $this->data['jenis'] = $this->m_jenis->getAll();
        $this->data['kutipan'] = $this->m_kutipan->getAll(NULL, 'RANDOM', 5);
        
        $this->data['title'] = $title ;
        $this->data['pagination'] = $this->fungsi->SetPaging($url, $count, $limit);

        $this->load_home('home/page/h_tag', $this->data);
    }
    function err_404() {
        $this->data['breadcrumb'] = array(
            array('title' => 'Halaman Tidak Ditemukan', 'url' => '#')
        );
        $this->load_view('errors/html/error_404', $this->data);
    }
    function _visit() {
        $this->load->model(array('m_visitor'));
        
        $today = $this->m_visitor->get_today();
        $yesterday = $this->m_visitor->get_yesterday();
        $last_week = $this->m_visitor->get_week();
        $last_month = $this->m_visitor->get_month();
        
        $this->data['visit_today'] = isset($today['visits']) ? $today['visits'] : 0;
        $this->data['visit_yesterday'] = isset($yesterday['visits']) ? $yesterday['visits'] : 0;
        $this->data['visit_week'] = isset($last_week['visits']) ? $last_week['visits'] : 0;
        $this->data['visit_month'] = isset($last_month['visits']) ? $last_month['visits'] : 0;
    }
}
