<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Bmi {
    
    const CLIENT_ID = '7949';
    const PAYMENT_CODE = '0200';
    const USERNAME = 'unimuda@sorong';
    const PASSWORD = '@bmiunimuda14!#';
    
    const JWT_KEY = '1ffe87384e37bf47efd18ee506c14i10g1f0';
    const JWT_ALGO = 'HS256';
    const EXPIRED_HOUR = 3600;

    protected $CI;

    function __construct() {
        $this->CI = & get_instance();
    }
    public function virtual() {
        //BIN4  PRODUK2  RAND10
        //7949 + 02 + 00 + 2024 + 5678
        return self::CLIENT_ID.self::PAYMENT_CODE;
    }
    public function auth() {
        $clientIP = $this->CI->input->server('REMOTE_ADDR');
        //allow IP
        if (!in_array($clientIP, ['127.0.0.1'])) {
            //$this->response(['ERR' => '30', 'MSG' => 'IP not allowed from '.$clientIP]);
        }
        //allow METHOD
        $method = $this->CI->input->server('REQUEST_METHOD');
        if (!in_array($method, ['POST'])) {
            $this->response(['ERR' => '30', 'MSG' => 'Method not '.$method.' allowed from '.$clientIP]);
        }
        //allow Content
        $contentType = $this->CI->input->get_request_header('Content-Type', TRUE);
        if (!in_array($contentType, ['application/json'])) {
            //$this->response(['ERR' => '30', 'MSG' => 'Bad Request: Invalid Content-Type header']);
        }
        //get json
        $getJson = $this->CI->input->raw_input_stream;
        if (empty($getJson)) {
            $this->response(['ERR' => '30', 'MSG' => 'Require missing fields']);
        }
        //decode
        $decodeJson = $this->Decode($getJson);
        if (empty($decodeJson)) {
            $this->response(['ERR' => '55', 'MSG' => 'Username/Password/Encrypt Key Not Valid']);
        }
        if (!$this->Validation($decodeJson, $this->rules)) {
            $this->response(['ERR' => '55', 'MSG' => 'Username/Password/Method Not Found']);
        }
        if (($decodeJson['USERNAME'] != self::USERNAME) || ($decodeJson['PASSWORD'] != self::PASSWORD)){
            $this->response(['ERR' => '55', 'METHOD' => $decodeJson['METHOD'], 'MSG' => 'Username/Password/Encrypt Key Not Valid']);
        }
        return $decodeJson;
    }
    public function response($params, $code = 200, $is_encode = true) {
        //notif
        $this->CI->db->set('id_notif', 'UUID()', false)->insert('yk_notif',array(
            'status_notif' => '1', 'subject_notif' => 'BMI', 
            'msg_notif' => json_encode(['server' => $_SERVER, 
            'raw' => $this->CI->input->raw_input_stream, 'output' => $params])
        ));
        $method = element('METHOD', $params, '');
        $output = $params;
        
        if($params['ERR'] != '00'){
            switch ($method) {
                case 'SIGNON':
                case 'SIGNOFF':
                    $output = $params;
                    break;
                case 'REVERSAL':
                    $output = ['ERR' => $params['ERR'], 'METHOD' => $method];
                    break;
                default:
                    $output = ['ERR' => $params['ERR'], 'METHOD' => $method, 'CCY' => '', 'BILL' => '', 
                        'CUSTNAME' => '', 'DESCRIPTION' => $params['MSG'], 'DESCRIPTION2' => ''];
                    break;
            }
            log_message('error', json_encode($output));
        }
        if($is_encode){
            $output = $this->Encode($output);
        }
        $this->CI->output->set_status_header($code)->set_content_type('application/json', 'utf-8')
            ->set_output($output)
            ->_display();
        exit();
    }
    private static function Encode($data) {
        try {
            $payload = $data;
            
            return JWT::encode($payload, self::JWT_KEY, self::JWT_ALGO);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return null;
        }
    }
    private static function Decode($token) {
        try {
            $decode = JWT::decode($token, new Key(self::JWT_KEY, self::JWT_ALGO));
            
            return json_decode(json_encode($decode), true);
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            return null;
        }
    }
    private function Validation($param, $rules) {
        $this->CI->load->library('form_validation');
        $this->CI->form_validation->set_data($param);
        $this->CI->form_validation->set_rules($rules);
        $this->CI->form_validation->set_error_delimiters('', '');
        if ($this->CI->form_validation->run() == FALSE) {
            return FALSE;
        }
        return TRUE;
    }
    private $rules = array(
        array(
            'field' => 'USERNAME',
            'label' => 'Username',
            'rules' => 'required'
        ),array(
            'field' => 'PASSWORD',
            'label' => 'Password',
            'rules' => 'required'
        ),array(
            'field' => 'METHOD',
            'label' => 'Method',
            'rules' => 'required'
        )
    );
}