<?php defined('BASEPATH') OR exit('No direct script access allowed');
class KZ_Controller extends CI_Controller {
    
    public $loggedin = false;
    public $sessionid = null;
    public $sessionusr = null;
    public $sessionname = null;
    public $sessiongroup = null;
    public $sessionfoto = null;
    
    public $periode = null;
    public $mid = null;
    
    function __construct() {
        parent::__construct();
        
        date_default_timezone_set('Asia/Jayapura');
        $this->load->helper(array('app','format','menu','security'));
        
        $this->_refresh();
        $this->_session();
        $this->_authentication();
    }
    //auth
    function _session() {
        $this->load->library(array('session'));
        
        $this->loggedin = $this->session->userdata('logged');
        $this->sessionid = $this->session->userdata('id');
        $this->sessionusr = $this->session->userdata('usr');
        $this->sessionname = $this->session->userdata('name');
        $this->sessiongroup = $this->session->userdata('groupid');
        $this->sessionfoto = $this->session->userdata('foto');
        
        $this->periode = $this->session->userdata('periode');
    }
    function _authentication() {
        $this->load->model(array('m_authentication'));
        
        $module_non_login = array('non_login','login','register','home','pages','galeri','artikel','tag');
        $module_login = array('beranda','logout');

        $module = ($this->uri->segment(1) == '' ? 'home' : $this->uri->segment(1));
        $class = ($this->uri->segment(2) == '' ? 'home' : $this->uri->segment(2));
        //Delete _do
        if (substr($class, strlen($class) - 3, 3) == '_do'){
            $class = substr($class, 0, strlen($class) - 3);
        }
        $fungsi = ($this->uri->segment(3) == '' ? 'index' : $this->uri->segment(3));
        //Check XSS
        $url_param = $module.' '.$class.' '.$fungsi.' '.$_SERVER['QUERY_STRING'];
        if ($this->security->xss_clean($url_param, TRUE) === FALSE){
            redirect('non_login/beranda/err_404');
        }
        //Check Module
        if (in_array($module, $module_non_login)) {
        } else if (in_array($module, $module_login) AND isset($this->sessionid)) {
        } else if(strpos($fungsi, 'ajax') !== false){
        } else if ($this->m_authentication->cekModule($module, $class, $fungsi, $this->sessiongroup)) {
        } else if (!$this->m_authentication->cekModule($module, $class, $fungsi, $this->sessiongroup) AND $this->sessionid) {
            redirect('non_login/beranda/err_module');
        } else {
            redirect('');
        }
    }    
    //Load view
    function load_view($template, $data = '') {
        $this->load->model(array('m_menu','m_aplikasi','m_nav','m_group'));
        
        $sidebar = $this->m_menu->getNavMenu($this->sessiongroup);
        $arrside = array();
        if (!is_null($sidebar)) {
            foreach ($sidebar['data'] as $side) {
                $arrside[$side['parent_menu']][] = $side;
            }
            $data['sidebar'] = $arrside;
        }
        $data['app'] = $this->m_aplikasi->getAll();
        $data['theme'] = explode(",",$data['app']['tema']);  
        $data['group_role'] = $this->m_group->getRole(array('r.user_id' => $this->sessionid,'r.group_id !=' => $this->sessiongroup));
        
        $this->data['content'] = $this->load->view($template, $data, TRUE);
        $this->load->view('sistem/v_body', $this->data);
    }
    function load_home($template, $data = '') {
        $this->load->model(array('m_aplikasi','m_nav'));
        
        $topbar = $this->m_nav->getNav();
        $arrtop = array();
        if (!is_null($topbar)) {
            foreach ($topbar['data'] as $top) {
                $arrtop[$top['parent_nav']][] = $top;
            }
            $data['navbar'] = $arrtop;
        }
        $data['app'] = $this->m_aplikasi->getAll();
        $data['theme'] = explode(",",$data['app']['tema']);
        
        $this->data['content'] = $this->load->view($template, $data, TRUE);
        $this->load->view('home/h_body', $this->data);
    }    
    //Library
    function _validation($rules, $delimiter = NULL) {
        $this->load->library(array('form_validation'));
        
        $this->form_validation->set_rules($rules);
        $this->form_validation->set_message('required', 'Kolom %s harus diisi.');
        $this->form_validation->set_message('min_length', 'Kolom %s harus minimal %s karakter.');
        $this->form_validation->set_message('valid_email', 'Format %s tidak sesuai.');
        $this->form_validation->set_message('numeric', 'Kolom %s harus berupa angka.');
        $this->form_validation->set_message('is_natural', 'Kolom %s harus berupa angka.');
        $this->form_validation->set_message('xss_clean', 'Programer yang baik tidak akan bertindak iseng dengan programer lainnya.');
        $this->form_validation->set_error_delimiters('<div class="">', '</div>');
        if(!is_null($delimiter)){
            $this->form_validation->set_error_delimiters('', '<br/>');
        }
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', validation_errors()));
            return FALSE;
        }else{
            return TRUE;
        }
    }
    //Upload Image
    function _upload_img($post, $name, $path, $width = 0, $ratio = FALSE, $height = 0){
        $this->load->library(array('upload','image_lib'));
        
        $file = $_FILES[$post]['tmp_name'];
        if(empty($file)){
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'File tidak dapat ditemukan'));
            return NULL;
        }
        list($get_width, $get_height) = getimagesize($file);
        if($get_width < $width){
            $width = $get_width;
        }
        $cfg['file_name'] = $name.'-'.$get_width.'-'.$get_height;
        $cfg['upload_path'] = './' . $path;
        $cfg['allowed_types'] = $this->config->item('app.allowed_img');
        $cfg['max_size'] = $this->config->item('app.max_img');
        //Upload Image
        $this->upload->initialize($cfg);
        if($this->upload->do_upload($post)) {
            $upload = $this->upload->data('file_name');
            //Compress Config
            $resize['image_library'] = 'gd2';
            $resize['source_image'] = './' . $path . $upload;
            $resize['create_thumb'] = FALSE;
            $resize['maintain_ratio'] = ($ratio) ? TRUE : FALSE;
            $resize['quality'] = '100%';
            $resize['width'] = ($width == 0) ? $this->config->item('app.resize') : $width;
            $resize['height'] = ($height == 0) ? $width : $height;
            $resize['new_image']= './' . $path . $upload;
            //Compress Image
            $this->image_lib->initialize($resize);
            if($this->image_lib->resize()){
                return $path . $upload;
            }else{
                (is_file($path . $upload)) ? unlink($path . $upload) : '';    
                $this->session->set_flashdata('notif', notif('danger', 'Peringatan Foto Resize', strip_tags($this->image_lib->display_errors())));
                return NULL;
            }
        }else{
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan Foto', strip_tags($this->upload->display_errors())));
            return NULL;
        }
    }
    //Cache
    function _refresh(){
        // any valid date in the past
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        // always modified right now
        $this->output->set_header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        // HTTP/1.1
        $this->output->set_header("Cache-Control: private, no-store, max-age=0, no-cache, must-revalidate, post-check=0, pre-check=0");
        // HTTP/1.0
        $this->output->set_header("Pragma: no-cache");
    }
    //tmp id
    function _getMhs() {
        if(empty($this->session->userdata('mid'))){
            $rs = $this->db->get_where('tmp_mhs', array('user_id' => $this->sessionid));
            if($rs->num_rows() > 0){
                $this->session->set_userdata(array('mid' => $rs->row_array()['mhs_id']));
            }
        }
        $this->mid = $this->session->userdata('mid');
    }
}
