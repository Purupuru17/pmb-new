<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class KZ_Model extends CI_Model
{
    protected $id = 'id';
    protected $table = 'tb';
    protected $uuid = false;
    protected $softDelete = false;
    protected $deletedAt = 'deleted_at';
    
    protected $alias = '';
    protected $select = '*';
    protected $joins = [];
    protected $columns = [];
    protected $searchable = [];
    protected $order = [];
    protected $defaultOptions = [
        'where'       => null,
        'join'       => [],
        'select'      => '*',
        'like'        => null,
        'key'        => null,
        'order'       => null,
        'group'       => null,
        'limit'       => null,
        'offset'      => 0,
        'withDeleted' => false,
    ];
    
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Insert data
     */
    public function insert($data)
    {
        if ($this->uuid) {
            $this->db->set($this->id, 'UUID()', FALSE);
        }
        $this->db->insert($this->table, $data);
        return $this->db->affected_rows() > 0;
    }
    public function insert_batch($data)
    {
        $this->db->trans_start();
        $this->db->insert_batch($this->table, $data);
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    /**
     * Update data
     */
    public function update($id, $data)
    {
        if (is_array($id)) {
            $this->db->where($id);
        } else {
            $this->db->where($this->id, $id);
        }
        return $this->db->update($this->table, $data);
    }
    public function update_batch($data)
    {
        $this->db->trans_start();
        $this->db->update_batch($this->table, $data, $this->id);
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    /**
     * Delete data
     */
    public function delete($id)
    {
        if (is_array($id)) {
            $this->db->where($id);
        } else {
            $this->db->where($this->id, $id);
        }
        if ($this->softDelete) {
            $data = [$this->deletedAt => date('Y-m-d H:i:s')];
            $this->db->update($this->table, $data);
            
            return $this->db->affected_rows() > 0;
        } else {
            $this->db->delete($this->table);
            
            return $this->db->affected_rows() > 0;
        }
    }
    public function empty()
    {
        $this->db->empty_table($this->table);
        return $this->db->affected_rows();
    }
    /**
     * Restore data (khusus soft delete)
     */
    public function restore($id)
    {
        if (!$this->softDelete) { return false; }

        if (is_array($id)) {
            $this->db->where($id);
        } else {
            $this->db->where($this->id, $id);
        }
        $data = [$this->deletedAt => NULL];
        $this->db->update($this->table, $data);
        
        return $this->db->affected_rows() > 0;
    }
    /**
     * Get all data (otomatis exclude soft delete)
     */
    public function all($where = NULL, $options = [])
    {
        // Merge default dengan parameter yang dikirim
        $opts = array_merge($this->defaultOptions, $options);
        // Table alias
        $table = !empty($opts['join']) ? "{$this->table} {$this->alias}" : $this->table;
        $this->db->select($opts['select']);
        $this->db->from($table);
        // Joins
        if (!empty($opts['join'])) {
            foreach ($opts['join'] as $join) {
                $alias = $join[0] ?? '';
                $on    = $join[1] ?? '';
                $type  = $join[2] ?? 'inner';
                if ($alias && $on) { $this->db->join($alias, $on, $type); }
            }
        }
        // Where
        if (!is_null($where)) {
            $this->db->where($where);
        }
        // Like
        if (!is_null($opts['like']) && !is_null($opts['key'])) {
            $this->db->group_start();
            if (array_keys($opts['like']) === range(0, count($opts['like']) - 1)) {
                $keyword = $opts['key'] ?? '';
                $first = true;
                foreach ($opts['like'] as $column) {
                    if ($first) {
                        $this->db->like($column, $keyword, 'both');
                        $first = false;
                    } else {
                        $this->db->or_like($column, $keyword, 'both');
                    }
                }
            }
            $this->db->group_end();
        }
        // Soft delete
        if ($this->softDelete && !$opts['withDeleted']) {
            $this->db->where("{$table}.{$this->deletedAt} IS NULL", null, false);
        }
        // Group
        if (!is_null($opts['group'])) {
            $this->db->group_by($opts['group']);
        }
        // Order
        if (!is_null($opts['order'])) {
            if (is_array($opts['order'])) {
                $this->db->order_by($opts['order'][0], $opts['order'][1]);
            } else {
                $this->db->order_by($opts['order']);
            }
        }
        // Limit
        if (!is_null($opts['limit'])) {
            $this->db->limit($opts['limit'], $opts['offset']);
        }
        
        $get = $this->db->get();
        return [
            'rows' => $get->num_rows(),
            'data' => $get->result_array()
        ];
    }
    /**
     * Get by ID/single row
     */
    public function get($id, $options = [])
    {
        // Merge default dengan parameter yang dikirim
        $opts = array_merge($this->defaultOptions, $options);
        // Table alias
        $table = !empty($opts['join']) ? "{$this->table} {$this->alias}" : $this->table;
        $this->db->select($opts['select']);
        $this->db->from($table);
        // Joins
        if (!empty($opts['join'])) {
            foreach ($opts['join'] as $join) {
                $alias = $join[0] ?? '';
                $on    = $join[1] ?? '';
                $type  = $join[2] ?? 'inner';
                if ($alias && $on) { $this->db->join($alias, $on, $type); }
            }
        }
        // Where
        if (!empty($id) && is_array($id)) {
            $this->db->where($id);
        } else {
            $this->db->where($this->id, $id);
        }
        // Soft delete
        if ($this->softDelete && !$opts['withDeleted']) {
            $this->db->where("{$table}.{$this->deletedAt} IS NULL", null, false);
        }
        // Group
        if (!is_null($opts['group'])) {
            $this->db->group_by($opts['group']);
        }
        return $this->db->get()->row_array();
    }
    /**
     * Datatable data
     */
    public function datatable($where = [])
    {
        $table = !empty($this->joins) ? $this->table.' '.$this->alias : $this->table;
        $this->db->from($table);
        // === JOIN ===
        if (!empty($this->joins)) {
            foreach ($this->joins as $join) {
                $alias = $join[0] ?? '';
                $on    = $join[1] ?? '';
                $type  = $join[2] ?? 'inner';
                if ($alias && $on) { $this->db->join($alias, $on, $type); }
            }
        }
        // === WHERE ===
        if (!empty($where)) {
            $this->db->where($where);
        }
        // === PENCARIAN ===
        $search = $this->input->post('search')['value'] ?? '';
        if (!empty($search) && !empty($this->searchable)) {
            $this->db->group_start();
            foreach ($this->searchable as $i => $col) {
                if ($i === 0) {
                    $this->db->like($col, $search);
                } else {
                    $this->db->or_like($col, $search);
                }
            }
            $this->db->group_end();
        }
        // === SORTING ===
        $order = $this->input->post('order')[0] ?? null;
        if ($order) {
            $colIndex = $order['column'];
            $colName  = $this->columns[$colIndex] ?? $this->columns[0];
            $dir      = $order['dir'] ?? 'asc';
            $this->db->order_by($colName, $dir);
        } else if (isset($this->order)) {
            $defOrder = $this->order;
            $this->db->order_by(key($defOrder), $defOrder[key($defOrder)]);
        }
        // === PAGINATION ===
        $length = (int) $this->input->post('length');
        $start  = (int) $this->input->post('start');
        if ($length != -1) {
            $this->db->limit($length, $start);
        }
        // === SELECT kolom ===
        $data = $this->db->select($this->select)->get()->result_array();
        
        // === HITUNG TOTAL & FILTERED ===
        $recordsFiltered = $this->_count_filtered($where);
        $recordsTotal    = $this->db->count_all($table);
        
        return [
            'draw'            => intval($this->input->post('draw')),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data'            => $data
        ];
    }
    /**
     * Datatable count data
     */
    private function _count_filtered($where)
    {
        $table = !empty($this->joins) ? $this->table.' '.$this->alias : $this->table;
        $this->db->from($table);
        // === JOIN ===
        if (!empty($this->joins)) {
            foreach ($this->joins as $join) {
                $alias = $join[0] ?? '';
                $on    = $join[1] ?? '';
                $type  = $join[2] ?? 'inner';
                if ($alias && $on) { $this->db->join($alias, $on, $type); }
            }
        }
        // === WHERE ===
        if (!empty($where)) {
            $this->db->where($where);
        }
        // === PENCARIAN ===
        $search = $this->input->post('search')['value'] ?? '';
        if (!empty($search) && !empty($this->searchable)) {
            $this->db->group_start();
            foreach ($this->searchable as $i => $col) {
                if ($i === 0) {
                    $this->db->like($col, $search);
                } else {
                    $this->db->or_like($col, $search);
                }
            }
            $this->db->group_end();
        }
        return $this->db->count_all_results();
    }
}
