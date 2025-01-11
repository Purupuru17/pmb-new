<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends KZ_Controller {
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
    }
    function routing() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if ($routing_module['type'] == 'list') {
            //LIST
            if ($routing_module['source'] == 'notif') {
                $this->_notif_user();
            }
        }else if($routing_module['type'] == 'action') {
            //ACTION
            if ($routing_module['source'] == 'session') {
                $this->_update_session();
            }
        }
    }    
    function _update_session() {
        $this->load->model(array('m_user'));
        $data = array(
            'last_login' => date('Y-m-d H:i:s', strtotime(date('h:i:sa')))
        );
        if(!empty($this->loggedin)){
            $result = $this->m_user->update($this->sessionid, $data);
            if($result){
                jsonResponse(array('data' => '0', 'status' => TRUE));
            }
        }
    }
    function _notif_user() {
        $this->load->model(array('m_notif'));
        
        if(!empty($this->loggedin)){
            $id = $this->input->post('id');
            if($id != ''){
                $this->m_notif->update(decode($id), array('status_notif' => '1'));
                jsonResponse(array('data' => NULL, 'item' => 0 ,'status' => FALSE));
            }
            
            $unread = $this->m_notif->unRead($this->sessionid);
            $rs = $this->m_notif->getAll(array('n.send_id' => $this->sessionid), 10);
            
            if($rs['rows'] > 0){
                $data = array();
//                foreach ($rs['data'] as $item) {
//                    $row = array();
//                    $row['id'] = encode($item['id_notif']);
//                    $row['subject'] =  $item['subject_notif'];
//                    $row['msg'] = $item['msg_notif'];
//                    $row['time'] =  selisih_wkt($item['buat_notif']);
//                    $row['link'] = site_url($item['link_notif']);
//                    $row['status'] = ($item['status_notif'] == '0') ? 'un-read' : '';
//                    $data[] = $row;
//                }
                jsonResponse(array('data' => $data, 'item' => $unread ,'status' => TRUE));
            }else{
                jsonResponse(array('data' => NULL, 'item' => 0 ,'status' => FALSE));
            }
        }
    }
}
