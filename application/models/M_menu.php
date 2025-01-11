<?php defined('BASEPATH') OR exit('No direct script access allowed');

class M_menu extends CI_Model {

    var $id = 'id_menu';
    var $table = 'yk_menu';

    function __construct() {
        parent::__construct();
    }
    //INSERT
    function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    function insertAksi($data, $aksi) {
        $this->db->trans_start();
        
        $menu_id = $this->insert($data);    
        $menu_aksi = array(
            'id_menu' => $menu_id,
            'id_aksi' => NULL
        );
        
        if($aksi['index'] == 1) {
            $menu_aksi['id_aksi'] = 1;
            $this->db->set($menu_aksi);
            $this->db->insert('yk_menu_aksi');            
        }
        if($aksi['add'] == 1) {
            $menu_aksi['id_aksi'] = 2;
            $this->db->set($menu_aksi);
            $this->db->insert('yk_menu_aksi');
        }
        if($aksi['edit'] == 1) {
            $menu_aksi['id_aksi'] = 3;
            $this->db->set($menu_aksi);
            $this->db->insert('yk_menu_aksi');
        }
        if($aksi['delete'] == 1) {
            $menu_aksi['id_aksi'] = 4;
            $this->db->set($menu_aksi);
            $this->db->insert('yk_menu_aksi');
        }
        if($aksi['detail'] == 1) {
            $menu_aksi['id_aksi'] = 5;
            $this->db->set($menu_aksi);
            $this->db->insert('yk_menu_aksi');
        }
        if($aksi['cetak'] == 1) {
            $menu_aksi['id_aksi'] = 6;
            $this->db->set($menu_aksi);
            $this->db->insert('yk_menu_aksi');
        }
        if($aksi['export'] == 1) {
            $menu_aksi['id_aksi'] = 7;
            $this->db->set($menu_aksi);
            $this->db->insert('yk_menu_aksi');
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    
    //UPDATE
    function update($id, $data) {
        $this->db->where($this->id, $id);
        $row = $this->db->update($this->table, $data);
        return $row;
    }
    function updateAksi($id, $data, $aksi) {
        $this->db->trans_start();
        
        $this->update($id, $data);
        $module = $data['module_menu'];
        
        $query = $this->db->get_where('yk_menu_aksi', array('id_menu' => $id, 'id_aksi' => 1));
        $jmlh = $query->num_rows();
        if($jmlh == 0 && $aksi['index'] == 1) {
            $this->db->set(array('id_menu' => $id, 'id_aksi' => 1));
            $this->db->insert('yk_menu_aksi');            
        } else if($jmlh == 1 && $aksi['index'] == 0) {
            $this->db->delete('yk_menu_aksi', array('id_menu' => $id, 'id_aksi' => 1));            
        } else if($jmlh == 1 && $aksi['index'] == 1) {
            $result = $query->result_array();
            $id_ma = $result[0]['id_menu_aksi'];
            $this->db->set(array('segmen' => $module.'/index'));
            $this->db->where('id_menu_aksi', $id_ma);
            $this->db->update('yk_group_menu_aksi');            
        }
        
        $query = $this->db->get_where('yk_menu_aksi', array('id_menu' => $id, 'id_aksi' => 2));
        $jmlh = $query->num_rows();
        if($jmlh == 0 && $aksi['add'] == 1) {
            $this->db->set(array('id_menu' => $id, 'id_aksi' => 2));
            $this->db->insert('yk_menu_aksi');            
        } else if($jmlh == 1 && $aksi['add'] == 0) {
            $this->db->delete('yk_menu_aksi', array('id_menu' => $id, 'id_aksi' => 2));            
        } else if($jmlh == 1 && $aksi['add'] == 1) {
            $result = $query->result_array();
            $id_ma = $result[0]['id_menu_aksi'];
            $this->db->set(array('segmen' => $module.'/add'));
            $this->db->where('id_menu_aksi', $id_ma);
            $this->db->update('yk_group_menu_aksi');            
        }
        
        $query = $this->db->get_where('yk_menu_aksi', array('id_menu' => $id, 'id_aksi' => 3));
        $jmlh = $query->num_rows();
        if($jmlh == 0 && $aksi['edit'] == 1) {
            $this->db->set(array('id_menu' => $id, 'id_aksi' => 3));
            $this->db->insert('yk_menu_aksi');            
        } else if($jmlh == 1 && $aksi['edit'] == 0) {
            $this->db->delete('yk_menu_aksi', array('id_menu' => $id, 'id_aksi' => 3));            
        } else if($jmlh == 1 && $aksi['edit'] == 1) {
            $result = $query->result_array();
            $id_ma = $result[0]['id_menu_aksi'];
            $this->db->set(array('segmen' => $module.'/edit'));
            $this->db->where('id_menu_aksi', $id_ma);
            $this->db->update('yk_group_menu_aksi');            
        }
        
        $query = $this->db->get_where('yk_menu_aksi', array('id_menu' => $id, 'id_aksi' => 4));
        $jmlh = $query->num_rows();
        if($jmlh == 0 && $aksi['delete'] == 1) {
            $this->db->set(array('id_menu' => $id, 'id_aksi' => 4));
            $this->db->insert('yk_menu_aksi');            
        } else if($jmlh == 1 && $aksi['delete'] == 0) {
            $this->db->delete('yk_menu_aksi', array('id_menu' => $id, 'id_aksi' => 4));            
        } else if($jmlh == 1 && $aksi['delete'] == 1) {
            $result = $query->result_array();
            $id_ma = $result[0]['id_menu_aksi'];
            $this->db->set(array('segmen' => $module.'/delete'));
            $this->db->where('id_menu_aksi', $id_ma);
            $this->db->update('yk_group_menu_aksi');            
        }
        
        $query = $this->db->get_where('yk_menu_aksi', array('id_menu' => $id, 'id_aksi' => 5));
        $jmlh = $query->num_rows();
        if($jmlh == 0 && $aksi['detail'] == 1) {
            $this->db->set(array('id_menu' => $id, 'id_aksi' => 5));
            $this->db->insert('yk_menu_aksi');            
        } else if($jmlh == 1 && $aksi['detail'] == 0) {
            $this->db->delete('yk_menu_aksi', array('id_menu' => $id, 'id_aksi' => 5));            
        } else if($jmlh == 1 && $aksi['detail'] == 1) {
            $result = $query->result_array();
            $id_ma = $result[0]['id_menu_aksi'];
            $this->db->set(array('segmen' => $module.'/detail'));
            $this->db->where('id_menu_aksi', $id_ma);
            $this->db->update('yk_group_menu_aksi');            
        }
        
        $query = $this->db->get_where('yk_menu_aksi', array('id_menu' => $id, 'id_aksi' => 6));
        $jmlh = $query->num_rows();
        if($jmlh == 0 && $aksi['cetak'] == 1) {
            $this->db->set(array('id_menu' => $id, 'id_aksi' => 6));
            $this->db->insert('yk_menu_aksi');            
        } else if($jmlh == 1 && $aksi['cetak'] == 0) {
            $this->db->delete('yk_menu_aksi', array('id_menu' => $id, 'id_aksi' => 6));            
        } else if($jmlh == 1 && $aksi['cetak'] == 1) {
            $result = $query->result_array();
            $id_ma = $result[0]['id_menu_aksi'];
            $this->db->set(array('segmen' => $module.'/cetak'));
            $this->db->where('id_menu_aksi', $id_ma);
            $this->db->update('yk_group_menu_aksi');            
        }
        
        $query = $this->db->get_where('yk_menu_aksi', array('id_menu' => $id, 'id_aksi' => 7));
        $jmlh = $query->num_rows();
        if($jmlh == 0 && $aksi['export'] == 1) {
            $this->db->set(array('id_menu' => $id, 'id_aksi' => 7));
            $this->db->insert('yk_menu_aksi');            
        } else if($jmlh == 1 && $aksi['export'] == 0) {
            $this->db->delete('yk_menu_aksi', array('id_menu' => $id, 'id_aksi' => 7));            
        } else if($jmlh == 1 && $aksi['export'] == 1) {
            $result = $query->result_array();
            $id_ma = $result[0]['id_menu_aksi'];
            $this->db->set(array('segmen' => $module.'/export'));
            $this->db->where('id_menu_aksi', $id_ma);
            $this->db->update('yk_group_menu_aksi');            
        }
                
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    
    //DELETE
    function delete($id) {
        $this->db->where($this->id, $id);
        $row = $this->db->delete($this->table);
        return $row;
    }
    function deleteSub($id) {
        $this->db->trans_start();
        
        $this->delete($id);
        
        $this->db->where('parent_menu', $id);
        $this->db->delete($this->table);
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    
    //Menu
    function getAll() {
        $this->db->from($this->table);
        $this->db->order_by('parent_menu', 'asc');
        
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
    function getParent($where = NULL) {
        $this->db->from($this->table);
        $this->db->where('parent_menu =','0');
        if(!is_null($where)){
            $this->db->where($where);
        }
        $get = $this->db->get();
        return [
            'rows' => $get->num_rows(),
            'data' => $get->result_array()
        ];
    }
    function getVisible() {
        $this->db->from($this->table);
        $this->db->where('status_menu =','1');
        
        $get = $this->db->get();
        return [
            'rows' => $get->num_rows(),
            'data' => $get->result_array()
        ];
    } 
    function getMenuAksi($menuId) {
        $result = array(
            'index' => false,
            'add' => false,
            'edit' => false,
            'delete' => false,
            'detail' => false,
            'cetak' => false,
            'export' => false
        );
        $this->db->select('ma.id_aksi');
        $this->db->from('yk_menu m');
        $this->db->join('yk_menu_aksi ma', 'm.id_menu = ma.id_menu', 'inner');
        $this->db->where('m.id_menu', $menuId);
        
        $query = $this->db->get()->result_array();
        foreach($query as $record) {
            if($record['id_aksi'] == 1) {
                $result["index"] = true;
            } else if($record['id_aksi'] == 2) {
                $result["add"] = true;
            } else if($record['id_aksi'] == 3) {
                $result["edit"] = true;
            } else if($record['id_aksi'] == 4) {
                $result["delete"] = true;
            } else if($record['id_aksi'] == 5) {
                $result['detail'] = true;
            } else if($record['id_aksi'] == 6) {
                $result['cetak'] = true;
            } else if($record['id_aksi'] == 7) {
                $result['export'] = true;
            }
        }
        return $result;
    }
    function getEmpty() {
        $data['id_menu'] = NULL;
        $data['parent_menu'] = NULL;
        $data['nama_menu'] = NULL;
        $data['module_menu'] = NULL;
        $data['status_menu'] = NULL;
        $data['icon_menu'] = NULL;
        $data['order_menu'] = NULL;
       
        return $data;
   }
    function getAksiEmpty() {
        $result = array(
            'index' => false,
            'add' => false,
            'edit' => false,
            'delete' => false,
            'detail' => false,
            'cetak' => false,
            'export' => false
        );
        return $result;
    }
    function getAuthenticationMenu($groupId) {
        $sql = "SELECT * FROM ( SELECT m.id_menu, m.nama_menu, m.parent_menu, m.module_menu,
            sum(1-abs( sign(ma.id_aksi-1))) AS `index`, 
            sum(1-abs( sign(ma.id_aksi-2))) AS `add`, 
            sum(1-abs( sign(ma.id_aksi-3))) AS `edit`, 
            sum(1-abs( sign(ma.id_aksi-4))) AS `delete`, 
            sum(1-abs( sign(ma.id_aksi-5))) AS `detail`, 
            sum(1-abs( sign(ma.id_aksi-6))) AS `cetak`, 
            sum(1-abs( sign(ma.id_aksi-7))) AS `export` 
        FROM yk_group_menu_aksi gma INNER JOIN yk_menu_aksi ma ON gma.id_menu_aksi = ma.id_menu_aksi 
        INNER JOIN yk_menu m ON ma.id_menu = m.id_menu WHERE gma.id_group = ? GROUP BY m.nama_menu 
        UNION 
        SELECT mm.id_menu, mm.nama_menu, mm.parent_menu, mm.module_menu , 0, 0, 0, 0, 0, 0, 0 
        FROM yk_menu mm WHERE mm.id_menu NOT IN (SELECT yma.id_menu FROM yk_menu_aksi yma
        INNER JOIN yk_group_menu_aksi ygma ON yma.id_menu_aksi = ygma.id_menu_aksi WHERE ygma.id_group = ?)) a ORDER BY module_menu asc";
        
        $query = $this->db->query($sql, array($groupId, $groupId));
        return $query->result_array();
    }
    function getNavMenu($group_id) {
        $this->db->distinct();
        $this->db->select('m.id_menu, m.nama_menu, m.parent_menu, m.module_menu, m.status_menu, m.icon_menu, m.order_menu');
        $this->db->from('yk_menu m');
        $this->db->join('yk_menu_aksi ma', 'm.id_menu = ma.id_menu', 'inner');
        $this->db->join('yk_group_menu_aksi gma', 'ma.id_menu_aksi = gma.id_menu_aksi', 'inner');
        
        $this->db->where(array('m.status_menu' => 1, 'gma.id_group' => $group_id));
        $this->db->order_by('m.order_menu', 'asc');
        $get = $this->db->get();
        return [
            'rows' => $get->num_rows(),
            'data' => $get->result_array()
        ];
    }
}
