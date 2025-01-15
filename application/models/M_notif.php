<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_notif extends CI_Model {

    private $id = 'id_notif';
    private $table = 'yk_notif';

    function __construct() {
        parent::__construct();
    }
    //INSERT
    function insert($data) {
        $this->db->set($this->id, 'UUID()', FALSE);
        
        $this->db->insert($this->table, $data);
        return $this->db->affected_rows() > 0 ? true : false;
    }
    function insertAll($value, $level) {
        $this->db->trans_start();
        $user_id = NULL;
        
        switch($level) {
            //Langsung
            case 1 :
                $user_id = $value['send_id'];
                break;
            /*Opsi
            case 2 :
                $this->db->select('user_id')->from('tmp_shop')->where('shop_id', $value['send_id']);
                $user_id = $this->db->get()->row_array()['user_id'];
                break;
            */
            case 3 :
                $this->db->select('user_id')->from('tmp_cst')->where('cst_id', $value['send_id']);
                $user_id = $this->db->get()->row_array()['user_id'];
                break;
            //Admin
            default:
                $user_id = 1;
        }
        if(!is_null($user_id)){
            $data['from_id'] = $value['from_id'];
            $data['send_id'] = $user_id;
            $data['status_notif'] = '0';
            $data['subject_notif'] = $value['subject'];
            $data['msg_notif'] = $value['msg'];
            $data['link_notif'] = $value['link'];
            $data['buat_notif'] = date('Y-m-d H:i:s');
            $this->insert($data);
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    //BATCH
    function insertBatch($post, $from_id) {
        $this->db->trans_start();
        
        $rs_user = NULL;
        switch($post['user']) {
            /*
            case 'shop' :
                $this->db->select('user_id')->from('tmp_shop');
                $rs_user = $this->db->get()->result_array();
                break;
            case 'cst' :
                $this->db->select('user_id')->from('tmp_cst');
                $rs_user = $this->db->get()->result_array();
                break;
            */
            default:
                $row['from_id'] = $from_id;
                $row['send_id'] = decode($post['user']);
                $row['subject_notif'] = $post['subject'];
                $row['msg_notif'] = $post['pesan'];
                $row['link_notif'] = $post['link'];
                $row['status_notif'] = '0';
                $row['buat_notif'] = date('Y-m-d H:i:s');
                
                $this->insert($row);
                $this->db->trans_complete();
                return $this->db->trans_status();
        }
        $data = array();
        foreach ($rs_user as $item) {
            $row = array();
            $row['from_id'] = $from_id;
            $row['send_id'] = $item['user_id'];
            $row['subject_notif'] = $post['subject'];
            $row['msg_notif'] = $post['pesan'];
            $row['link_notif'] = $post['link'];
            $row['status_notif'] = '0';
            $row['buat_notif'] = date('Y-m-d H:i:s');

            $data[] = $row;
        }
        $this->db->insert_batch($this->table, $data);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    //UPDATE
    function update($id, $data) {
        if(is_array($id)){
            $this->db->where($id);
        }else{
            $this->db->where($this->id, $id);
        }
        $this->db->update($this->table, $data);
        return $this->db->affected_rows() > 0 ? true : false;
    }
    //DELETE
    function delete($id) {
        if(is_array($id)){
            $this->db->where($id);
        }else{
            $this->db->where($this->id, $id);
        }
        $this->db->delete($this->table);
        return $this->db->affected_rows() > 0 ? true : false;
    }
    function deleteAll($id) {
        $this->db->trans_start();
        
        $notif_id = explode(",", $id);
        foreach ($notif_id as $val) {
            $this->delete(decode($val));
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    //GET
    function getAll($where = NULL, $limit = NULL, $orwhere = NULL) {
        $this->db->from('yk_notif n');
        $this->db->join('yk_user u','n.send_id = u.id_user','left');
        if(!is_null($where)){
            $this->db->where($where);
        }
        if(!is_null($orwhere)){
            $this->db->or_where($orwhere);
        }
        if(!is_null($limit)){
            $this->db->limit($limit);
        }
        $this->db->order_by('n.buat_notif','desc');
        
        $get = $this->db->get();
        
        return [
            'rows' => $get->num_rows(),
            'data' => $get->result_array()
        ];
    }
    function getId($id) {
        $this->db->from($this->table)->where($this->id, $id);
        return $this->db->get()->row_array();
    }
    function getEmpty() {
        $data[$this->id] = NULL;
        $data['subject_notif'] = NULL;
        $data['msg_notif'] = NULL;
        $data['link_notif'] = NULL;
       
        return $data;
   }
}
