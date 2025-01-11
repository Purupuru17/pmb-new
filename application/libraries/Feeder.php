<?php

use GuzzleHttp\Client;

class Feeder {
    
    const user_pddikti = '141010';
    const pass_pddikti = 'Musimhujan2412!';
    
    protected $_CI;
    public $token = '';

    function __construct() {
        $this->_CI = & get_instance();
        if(empty($this->token)){
            $this->_token();
        }
    }
    private function _restAPI($data, $method = 'POST', $url = '', $option = array()) {
        $uri = (ENVIRONMENT == 0) ? '10.3.5.216' : '103.226.138.149';
        $client = new Client(['base_uri' => 'http://'.$uri.':3003/ws/live2.php', 'timeout' => 30]);
        try {
            $option['headers'] = ['Accept' => 'application/json'];
            $option['form_params'] = $data;
            
            $response = $client->request($method, $url, $option);
            $result = json_decode($response->getBody()->getContents(), true);
            $status = ($result['error_code'] == 0) ? true : false;
            
            return ['status' => $status, 'msg' => $result['error_desc'], 'data' => $result['data'], 'code' => $result['error_code']];
        }catch (Exception $e) {
            return ['status' => false, 'msg' => $e->getMessage(), 'code' => $e->getCode()];
        }
    }
    private function _token() {
        //Get Token
        $where['act'] = 'GetToken';
        $where['username'] = self::user_pddikti;
        $where['password'] = self::pass_pddikti;
        
        $result = $this->_restAPI($where);
        if($result['status']){
            $this->token = $result['data']['token'];
        }
    }
    public function dictionary($key){
        $data = array('token' => $this->token, 'act' => 'GetDictionary', 'fungsi' => $key);
        return $this->_restAPI($data);
    }
    public function get($action, $where = null){
        $limit = element('limit', $where, '');
        $filter = element('filter', $where, '');
        $order = element('order', $where, '');
        $offset = element('offset', $where, 0);
        
        $data = array('token' => $this->token, 'act' => $action,
            'filter' => $filter,
            'order' => $order,
            'limit' => $limit,
            'offset' => $offset
        );
        return $this->_restAPI($data);
    }
    public function post($action, $record){
        $data['token'] = $this->token;
        $data['act'] = $action;
        $data['record'] = $record;
        
        return $this->_restAPI($data);
    }
    public function update($action, $key, $record){
        $data['token'] = $this->token;
        $data['act'] = $action;
        $data['key'] = $key;
        $data['record'] = $record;
        
        return $this->_restAPI($data);
    }
    public function delete($action, $key){
        $data['token'] = $this->token;
        $data['act'] = $action;
        $data['key'] = $key;
        
        return $this->_restAPI($data);
    }
}

