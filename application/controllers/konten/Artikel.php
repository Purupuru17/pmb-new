<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Artikel extends KZ_Controller {
    
    private $module = 'konten/artikel';
    private $module_do = 'konten/artikel_do';   
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_artikel','m_jenis'));
    }
    function index() {
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Artikel','Daftar Artikel');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('konten/artikel/v_index', $this->data);
    }
    function add() {
        $this->data['jenis'] = $this->m_jenis->getAll();
        $this->data['edit'] = $this->m_artikel->getEmpty();
        
        $this->data['action'] = $this->module_do.'/add';
        $this->data['title'] = array('Artikel','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('konten/artikel/v_form', $this->data);
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->data['jenis'] = $this->m_jenis->getAll();
        $this->data['edit'] = $this->m_artikel->getId(decode($id));
        
        $this->data['action'] = $this->module_do.'/edit/'.$id;
        $this->data['title'] = array('Artikel','Ubah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('konten/artikel/v_form', $this->data);
    }
    function delete($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $data = $this->m_artikel->getId(decode($id));
        $old_img = $data['foto_artikel'];
        
        $result = $this->m_artikel->delete(decode($id));
        if ($result) {
            (is_file($old_img)) ? unlink($old_img) : '';
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
        if($routing_module['type'] == 'list') {
            //LIST
            if($routing_module['source'] == 'data') {
                $this->_list_data();
            }
        }
    }
    //FUNCTION
    function _list_data() {
        $list = $this->m_artikel->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row) {
            $no++;
            $rows = array();
            
            $status = ($row['status_artikel'] == '0') ? '<span class="label label-danger label-white arrowed">Tidak Aktif</span>' : '<span class="label label-success label-white arrowed">Aktif</span>';
            $populer = ($row['is_popular'] == '0') ? '<span class="label label-default label-white arrowed">Tidak</span>' : '<span class="label label-info label-white arrowed">Ya</span>';
            $breaking = ($row['is_breaking'] == '0') ? '<span class="label label-default label-white arrowed">Tidak</span>' : '<span class="label label-warning label-white arrowed">Ya</span>';
            $aksi = '<div class="action-buttons">
                        <a href="'. site_url($this->module .'/edit/'. encode($row['id_artikel'])) .'" class="tooltip-warning btn btn-white btn-warning btn-sm" data-rel="tooltip" title="Ubah Data">
                            <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-130"></i></span>
                        </a>
                        <a href="#" name="'. encode($row['id_artikel']) .'" itemprop="'. ctk($row['judul_artikel']) .'" id="delete-btn" class="tooltip-error btn btn-white btn-danger btn-sm" data-rel="tooltip" title="Hapus Data">
                            <span class="red"><i class="ace-icon fa fa-trash-o bigger-130"></i></span>
                        </a>
                    </div>';
            
            $rows[] = ctk($no);
            $rows[] = '<a target="_blank" href="'. site_url('artikel/' . $row['slug_artikel']).'">'.ctk($row['judul_artikel']).'</a>';
            $rows[] = '<label style="background:'.ctk($row['color_jenis']).'" class="label">'.ctk($row['judul_jenis']).'</label>';
            $rows[] = $status;
            $rows[] = $populer;
            $rows[] = $breaking;
            $rows[] = format_date($row['update_artikel'],2);
            $rows[] = $aksi;

            $data[] = $rows;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->m_artikel->count_all(),
            "recordsFiltered" => $this->m_artikel->count_filtered(),
            "data" => $data,
        );
        jsonResponse($output);
    }
}
