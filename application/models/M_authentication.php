<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_authentication extends CI_Model {

    function cekModule($module, $class, $fungsi, $groupid) {
        $this->db->select('id_menu_aksi');
        $this->db->from('yk_group_menu_aksi');
        $this->db->where('id_group', $groupid);
        $this->db->where('segmen', $module . '/' . $class . '/' . $fungsi);
        $query = $this->db->get();
        $result = $query->result_array();
        if (!empty($result)) {
            return TRUE;
        } else {
            return false;
        }
    }
    
    function isExpired($user) {
        $this->db->select('COUNT(id_user) AS total', FALSE);
        $this->db->from('yk_user');
        $this->db->where('username', $user);
        $this->db->where('expired !=', '0000-00-00');
        $this->db->where('expired <', 'NOW()');
        $query = $this->db->get();
        $result = $query->result_array();
        if($result[0]['total'] > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
    function getAuth($username) {
        $data = array();
        $this->db->from('yk_user u');
        $this->db->join('yk_group g', 'g.id_group = u.id_group', 'inner');
        $this->db->where('username', $username); 
        $this->db->or_where('email', $username); 
        $query = $this->db->get();     
        $result = $query->result_array();
        if(sizeof($result) > 0) {
            $data = $result[0];
        }
        return $data;
    }
    function update($groupId, $auth) {
        $this->db->trans_start();
        $this->db->delete('yk_group_menu_aksi', array('id_group' => $groupId));         
        
        foreach ($auth as $aksi) {            
            if ($aksi['index'] == 'on') {
                $menu = $this->getMenuModule($aksi['id_menu'], $groupId, 1);
                if(sizeof($menu) > 0) {
                    $this->db->set($menu);
                    $this->db->insert('yk_group_menu_aksi');                    
                }
            }
            if ($aksi['add'] == 'on') {
                $menu = $this->getMenuModule($aksi['id_menu'], $groupId, 2);
                if(sizeof($menu) > 0) {
                    $this->db->set($menu);
                    $this->db->insert('yk_group_menu_aksi');
                }
            }
            if ($aksi['edit'] == 'on') {
                $menu = $this->getMenuModule($aksi['id_menu'], $groupId, 3);
                if(sizeof($menu) > 0) {
                    $this->db->set($menu);
                    $this->db->insert('yk_group_menu_aksi');
                }
            }
            if ($aksi['delete'] == 'on') {
                $menu = $this->getMenuModule($aksi['id_menu'], $groupId, 4);
                if(sizeof($menu) > 0) {
                    $this->db->set($menu);
                    $this->db->insert('yk_group_menu_aksi');
                }
            }
            if ($aksi['detail'] == 'on') {
                $menu = $this->getMenuModule($aksi['id_menu'], $groupId, 5);
                if(sizeof($menu) > 0) {
                    $this->db->set($menu);
                    $this->db->insert('yk_group_menu_aksi');
                }
            }
            if ($aksi['cetak'] == 'on') {
                $menu = $this->getMenuModule($aksi['id_menu'], $groupId, 6);
                if(sizeof($menu) > 0) {
                    $this->db->set($menu);
                    $this->db->insert('yk_group_menu_aksi');
                }
            }
            if ($aksi['export'] == 'on') {
                $menu = $this->getMenuModule($aksi['id_menu'], $groupId, 7);
                if(sizeof($menu) > 0) {
                    $this->db->set($menu);
                    $this->db->insert('yk_group_menu_aksi');
                }
            }
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    
    function getMenuModule($menuId, $groupId, $aksiId) {
        $this->db->select("ma.id_menu_aksi AS id_menu_aksi , $groupId AS id_group, CONCAT(m.module_menu, '/', a.fungsi) AS segmen", FALSE);
        $this->db->from('yk_menu_aksi ma');
        $this->db->join('yk_menu m', 'ma.id_menu = m.id_menu', 'inner');
        $this->db->join('yk_aksi a', 'ma.id_aksi = a.id_aksi', 'inner');
        $this->db->where('ma.id_menu', $menuId);
        $this->db->where('ma.id_aksi', $aksiId);
        $query = $this->db->get();
        $result = $query->result_array();
        if (!empty($result)) {
            return $result[0];
        } else {
            return array();
        }        
    }    
}