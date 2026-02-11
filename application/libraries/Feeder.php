<?php

use GuzzleHttp\Client;

class Feeder {
    
    protected $CI;
    
    private $baseURI;
    private $username;
    private $password;
    private $token;
    
    function __construct() {
        $this->CI = &get_instance();
        
        // PRIORITAS 1 — ENV
        global $ENV;
        $this->baseURI  = $ENV['FEEDER_HOST'] ?? '';
        $this->username = $ENV['FEEDER_USER'] ?? '';
        $this->password = $ENV['FEEDER_PASS'] ?? '';
        
        // PRIORITAS 2 — Session
        if ($this->CI->session->userdata('setting')['pddikti']) {
            $p = $this->CI->session->userdata('setting')['pddikti'];
            $this->baseURI  = $p['host']     ?? $this->baseURI;
            $this->username = $p['username'] ?? $this->username;
            $this->password = $p['password'] ?? $this->password;
        }
    }
    private function _restAPI($data, $method = 'POST', $url = '', $option = array()) {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        $client = new Client(['base_uri' => 'http://'.$this->baseURI.':3003/ws/live2.php', 
            'timeout' => 30, 'connect_timeout' => 5]);
        
        $option['headers'] = ['Accept' => 'application/json'];
        $option['form_params'] = $data;
        
        try {
            $response = $client->request($method, $url, $option);
            $result = json_decode($response->getBody()->getContents(), true);
            $status = ($result['error_code'] == 0) ? true : false;

            return ['status' => $status, 'msg' => $result['error_desc'], 
                'data' => $result['data'], 'code' => $result['error_code']
            ];
        } catch (Exception $e) {
            $start = microtime(true);        
            $elapsed = round(microtime(true) - $start, 3);
            log_message('error',
                "[FEEDER API] {$data['act']} | EXCEPTION: {$e->getMessage()} | time: {$elapsed}s"
            );
            return ['status' => false, 'msg' => 'Internet tidak stabil', 'code' => $e->getCode()];
        }
    }
    private function _token() {
        $where['act'] = 'GetToken';
        $where['username'] = $this->username;
        $where['password'] = $this->password;
        
        $result = $this->_restAPI($where);
        if($result['status']){
            $this->token = $result['data']['token'];
        }
        return ['status' => $result['status'], 'msg' => $result['msg'], 'code' => $result['code']];
    }
    public function dictionary($key){
        if(empty($this->token)){
            $token = $this->_token();
            if(!$token['status']){
                return $token;
            }
        }
        $data = array('token' => $this->token, 'act' => 'GetDictionary', 'fungsi' => $key);
        return $this->_restAPI($data);
    }
    public function get($action, $where = null){
        if(empty($this->token)){
            $token = $this->_token();
            if(!$token['status']){
                return $token;
            }
        }
        $data = [
            'token' => $this->token,
            'act'   => $action,
        ];
        $filter = element('filter', $where);
        $order = element('order', $where);
        $limit = element('limit', $where);
        $offset = element('offset', $where);
        if (!empty($filter)) {
            $data['filter'] = $filter;
        }
        if (!empty($order)) {
            $data['order'] = $order;
        }
        if (!empty($limit)) {
            $data['limit'] = $limit;
        }
        if (isset($offset)) {
            $data['offset'] = $offset;
        }
        return $this->_restAPI($data);
    }
    public function post($action, $record){
        if(empty($this->token)){
            $token = $this->_token();
            if(!$token['status']){
                return $token;
            }
        }
        $data['token'] = $this->token;
        $data['act'] = $action;
        $data['record'] = $record;
        
        return $this->_restAPI($data);
    }
    public function update($action, $key, $record){
        if(empty($this->token)){
            $token = $this->_token();
            if(!$token['status']){
                return $token;
            }
        }
        $data['token'] = $this->token;
        $data['act'] = $action;
        $data['key'] = $key;
        $data['record'] = $record;
        
        return $this->_restAPI($data);
    }
    public function delete($action, $key){
        if(empty($this->token)){
            $token = $this->_token();
            if(!$token['status']){
                return $token;
            }
        }
        $data['token'] = $this->token;
        $data['act'] = $action;
        $data['key'] = $key;
        
        return $this->_restAPI($data);
    }
    public function ipk($id, $smtid, $is_recent = null){
        $data = array('list' => array(), 'table' => array(), 'sks' => 0, 'ips' => 0, 'total' => 0, 'ipk' => 0);
        $semester = empty($is_recent) ? "AND id_semester <='{$smtid}'" : "AND id_semester <'{$smtid}'";
        $rs = $this->get('GetDetailNilaiPerkuliahanKelas', array('filter' => "id_registrasi_mahasiswa='{$id}' " . $semester));
        
        if(!$rs['status']){
            jsonResponse(array('status' => false, 'msg' => $rs['msg']));
        }
        if(count($rs['data']) > 1){
            $indeks_ips = 0; $indeks_ipk = 0;
            $sks = 0; $sks_ips = 0; $sks_ipk = 0;
            foreach ($rs['data'] as $val) {
                if($smtid == $val['id_semester']){
                    $sks = (!is_null($val['nilai_indeks']) || $val['nilai_indeks'] != '') ? $val['sks_mata_kuliah'] : 0;
                    
                    $indeks_ips += $sks * $val['nilai_indeks'];
                    $sks_ips += $sks;
                    
                    $data['list'][] = $val;
                    $data['sks'] += $val['sks_mata_kuliah']; 
                }
                $sks_ipk = (!empty($val['nilai_indeks']) && $val['nilai_indeks'] > 1) ? $val['sks_mata_kuliah'] : 0;
                $indeks_ipk += $sks_ipk * $val['nilai_indeks'];
    
                $data['table'][] = $val;
                $data['total'] += $sks_ipk;
            }
            $data['ips'] = ($sks_ips > 0) ? round($indeks_ips/$sks_ips,2) : 0;
            $data['ipk'] = ($data['total'] > 0) ? round($indeks_ipk/$data['total'],2) : 0;
        }        
        return $data;
    }
    public function akm($id, $smtid){
        $data = array('table' => array(), 'status_mhs' => null, 'sks' => 0, 'ips' => 0, 'biaya_smt' => 0);
        $rs = $this->get('GetAktivitasKuliahMahasiswa', array('filter' => "id_registrasi_mahasiswa='{$id}'", 'order' => "id_semester asc"));
        
        if(!$rs['status']){
            jsonResponse(array('status' => false, 'msg' => $rs['msg']));
        }
        if(count($rs['data']) > 0){
            foreach ($rs['data'] as $val) {
                if($smtid == $val['id_semester']){
                    $data['status_mhs'] = $val['id_status_mahasiswa'];
                    $data['sks'] = $val['sks_semester'];
                    $data['ips'] = $val['ips'];
                    $data['biaya_smt'] = (int) $val['biaya_kuliah_smt'];
                }
                $data['table'][] = $val;
            }
        }
        return $data;
    }
}