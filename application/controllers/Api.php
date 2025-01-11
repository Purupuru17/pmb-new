<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        date_default_timezone_set('Asia/Jayapura');
        $this->load->helper(array('app','format','security'));
        $this->load->model(array('m_payment'));
    }
    function bmi() {
        $this->load->library(array('bmi'));
        
        $params = $this->bmi->auth();
        switch ($params['METHOD']) {
            case 'INQUIRY':
                
                $check = $this->m_payment->getBill(array('va_payment' => $params['VANO']));
                if(is_null($check)){
                    $this->bmi->response([
                        'ERR' => '15', 'METHOD' => $params['METHOD'], 'MSG' => 'VANO Not Found : '.$params['VANO']
                    ]);
                }
                $inquiry = $this->m_payment->getBill(array('va_payment' => $params['VANO'], 'status_payment' => '0'));
                if(is_null($inquiry)){
                    $this->bmi->response([
                        'ERR' => '88', 'METHOD' => $params['METHOD'], 'MSG' => 'The Bills Already Paid : '.$params['VANO']
                    ]);
                }
                $data['status_inquiry'] = '1';
                $data['channel_payment'] = $params['CHANNELID'].'#'.$params['REFNO'];
                $data['update_payment'] = date('Y-m-d H:i:s', strtotime('+2 hours', strtotime($params['TRXDATE'])));
                $data['log_payment'] = 'BMI Inquiry Invoice';

                $update = $this->m_payment->update($inquiry['id_payment'], $data);
                if(!$update){
                    $this->bmi->response([
                        'ERR' => '12', 'METHOD' => $params['METHOD'], 'MSG' => 'Invalid Transaction. Failed Update Inquiry : '.$params['VANO']
                    ]);
                }
                $this->bmi->response([
                    'ERR' => '00', 'METHOD' => $params['METHOD'], 'CCY' => '360',
                    'BILL' => strval($inquiry['total_payment'] * 100), 'CUSTNAME' => strtoupper($inquiry['nama_mhs']), 
                    'DESCRIPTION' => $inquiry['invoice'], 'DESCRIPTION2' => $inquiry['note_payment']
                ]);
                break;
                
            case 'PAYMENT':
                
                $check = $this->m_payment->getBill(array('va_payment' => $params['VANO']));
                if(is_null($check)){
                    $this->bmi->response([
                        'ERR' => '15', 'METHOD' => $params['METHOD'], 'MSG' => 'Bill ID Not Found : '.$params['VANO']
                    ]);
                }
                $payment = $this->m_payment->getBill(array(
                    'va_payment' => $params['VANO'], 'status_payment' => '0', 'status_inquiry' => '1'
                ));
                if(is_null($payment)){
                    $this->bmi->response([
                        'ERR' => '88', 'METHOD' => $params['METHOD'], 'MSG' => 'The Bills Already Paid : '.$params['VANO']
                    ]);
                }
                if(($params['PAYMENT']/100) != $payment['total_payment']){
                    $this->bmi->response([
                        'ERR' => '16', 'METHOD' => $params['METHOD'], 'MSG' => 'Invalid Full Amount : '.$params['VANO']
                    ]);
                }
                $data['status_payment'] = '1';
                $data['paid_payment'] = date('Y-m-d H:i:s', strtotime('+2 hours', strtotime($params['TRXDATE'])));
                $data['update_payment'] = $data['paid_payment'];
                $data['log_payment'] = 'BMI Payment Notification';

                //multi transaction
                $this->db->trans_start();
                $this->db->where('id_payment', $payment['id_payment'])->update('m_payment', $data);
                $this->db->where(array('tipe_id' => $payment['id_payment'], 'tipe_bayar' => '2'))
                    ->update('rf_bayar', array('tgl_bayar' => $data['paid_payment'], 'log_bayar' => $data['log_payment']));

                $this->db->trans_complete();
                if (!$this->db->trans_status()) {
                    $this->bmi->response([
                        'ERR' => '12', 'METHOD' => $params['METHOD'], 'MSG' => 'Invalid Transaction. Failed Update Payment : '.$params['VANO']
                    ]);
                }
                $this->bmi->response([
                    'ERR' => '00', 'METHOD' => $params['METHOD'], 'CCY' => '360',
                    'BILL' => strval($payment['total_payment'] * 100), 'CUSTNAME' => strtoupper($payment['nama_mhs']), 
                    'DESCRIPTION' => $payment['invoice'], 'DESCRIPTION2' => $payment['note_payment']
                ]);
                break;
                
            case 'REVERSAL':
                
                $reversal = $this->m_payment->getBill(array(
                    'va_payment' => $params['VANO'], 'status_payment' => '1', 'status_inquiry' => '1',
                    'paid_payment' => date('Y-m-d H:i:s', strtotime('+2 hours', strtotime($params['PYMTDATE'])))
                ));
                if(is_null($reversal)){
                    $this->bmi->response(['ERR' => '15', 'METHOD' => $params['METHOD']]);
                }
                if(($params['PAYMENT']/100) != $reversal['total_payment']){
                    $this->bmi->response(['ERR' => '16', 'METHOD' => $params['METHOD']]);
                }
                $data['status_inquiry'] = '0';
                $data['status_payment'] = '0';
                $data['update_payment'] = date('Y-m-d H:i:s', strtotime('+2 hours', strtotime($params['TRXDATE'])));
                $data['log_payment'] = 'BMI Reversal Notification';

                $update = $this->m_payment->update($reversal['id_payment'], $data);
                if(!$update){
                    $this->bmi->response(['ERR' => '12', 'METHOD' => $params['METHOD']]);
                }
                log_message('error', json_encode($params));
                $this->bmi->response(['ERR' => '00', 'METHOD' => $params['METHOD']]);
                break;
                
            case 'SIGNON':
            case 'SIGNOFF':
                $this->bmi->response(['ERR' => date('YmdHis').';00;UNIMUDA', 'METHOD' => $params['METHOD']]);
                break;
            default:
                $this->bmi->response(['ERR' => '00', 'METHOD' => $params['METHOD']]);
                break;
        }
    }
    
    function bmi_test() {
        $this->load->library('bmi');
        $data = [
            "CCY" => "360",
            "VANO" => "7949012020049215",
            "TRXDATE" => date('YmdHis'),
            "METHOD" => "PAYMENT",
            "BILL" => "60000000",
            "PAYMENT" => "60000000",
            "USERNAME" => "unimuda@sorong",
            "PASSWORD" => "@bmiunimuda14!#",
            "CHANNELID" => "2",
            "REFNO" => "7383010123456789"
        ];
        print_r($this->bmi->Encode($data));
        exit();
    }
}