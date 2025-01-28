<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_do extends KZ_Controller {
    
    private $module = 'transaksi/payment';
    private $module_do = 'transaksi/payment_do';
    private $url_route = array('id', 'source', 'type');   
    const EXPIRED_HOUR = 4320; //24x30x6
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_payment','m_mhs'));
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if($routing_module['type'] == 'action') {
            if($routing_module['source'] == 'invoice') {
                $this->_invoice_add();
            }else if($routing_module['source'] == 'virtual') {
                $this->_virtual_add();
            }
        }
    }
    function _virtual_add() {
        if(!$this->_validation($this->rules_va,'ajax')){
            jsonResponse(array('status' => FALSE, 'msg' => validation_errors()));
        }
        $id = decode($this->input->post('id'));
        $bank =  $this->input->post('bank');
        
        $mhs = $this->m_mhs->getId($id);
        if(empty($mhs)){
            jsonResponse(array('status' => false, 'msg' => 'Mahasiswa tidak ditemukan'));
        }
        $va_mhs = $mhs['virtual_mhs'];
        if(empty($va_mhs)){
            $va_mhs = $mhs['angkatan'].random_string('numeric',4);
            
            $check = $this->db->get_where('m_mhs', array('virtual_mhs' => $va_mhs));
            if($check->num_rows() > 0){
                jsonResponse(array('status' => false, 'msg' => 'Mohon ulangi atau pilih bank lainnya'));
            }
            $update = $this->m_mhs->update($id, array('virtual_mhs' => $va_mhs, 
                'update_mhs' => date('Y-m-d H:i:s'), 'log_mhs' => $this->sessionname.' create VA'));
            if(!$update){
                jsonResponse(array('status' => false, 'msg' => 'Data gagal diperbarui'));
            }
        }
        if(strlen($va_mhs) != 8){
            jsonResponse(array('status' => false, 'msg' => 'VA tidak sesuai. Mohon ulangi atau pilih bank lainnya'));
        }
        switch ($bank) {
            case 'MUAMALAT':
                $this->load->library(array('bmi'));
                $virtual = $this->bmi->virtual();
                break;
            default:
                $virtual = '';
                break;
        }
        jsonResponse(array('data' => $virtual.$va_mhs, 'status' => true, 'msg' => 'Data berhasil diperbarui'));
    }
    function _invoice_add() {
        if(!$this->_validation($this->rules, 'ajax')){
            jsonResponse(array('status' => false, 'msg' => validation_errors()));
        }
        $data['mhs_id'] = decode($this->input->post('mhs'));
        $data['item_id'] = decode($this->input->post('tagihan'));
        $data['total_payment'] = (int) $this->input->post('total');
        $data['bank_payment'] = $this->input->post('bank');
        $data['va_payment'] = $this->input->post('virtual');
        $data['note_payment'] = $this->input->post('note');
        
        $data['id_payment'] = random_string('unique');
        $data['saldo_payment'] = 0;
        $data['status_payment'] = '0';
        $data['status_inquiry'] = '1';
        $data['expired_payment'] = date('c', time() + self::EXPIRED_HOUR * 3600);
        $data['valid_payment'] = 'PENDING';
        $data['buat_payment'] = date('Y-m-d H:i:s');
        $data['log_payment'] = $this->sessionname.' menambahkan data pembayaran';
        
        $mhs = $this->m_mhs->getId($data['mhs_id']);
        if(empty($mhs)){
            jsonResponse(array('status' => false, 'msg' => 'Mahasiswa tidak ditemukan'));
        }
        $today = $this->m_payment->getId(array('mhs_id' => $mhs['id_mhs'], 'DATE(buat_payment)' => date('Y-m-d')));
        if(!is_null($today)){
            //jsonResponse(array('status' => false, 'msg' => 'Hanya dapat melakukan 1x Transaksi dalam 1 hari. Silahkan coba hari berikutnya'));
        }
        $check = $this->m_payment->getId(array('mhs_id' => $mhs['id_mhs'], 'status_payment' => '0', 'status_inquiry' => '1'));
        if(!is_null($check)){
            jsonResponse(array('status' => false, 'msg' => 'Tidak dapat menambahkan Pembayaran Baru. Segera lunasi pembayaran sebelumnya'));
        }
        $hasItem = $this->m_payment->getId(array('mhs_id' => $mhs['id_mhs'], 'item_id' => $data['item_id']));
        if(!is_null($hasItem)){
            jsonResponse(array('status' => false, 'msg' => 'Item Pembayaran sudah pernah ditambahkan sebelumnya'));
        }
        $data['invoice'] = $this->_invoice_auto($mhs);
        $invoice = $this->m_payment->getId(array('invoice' => $data['invoice']));
        if(!is_null($invoice)){
            jsonResponse(array('status' => false, 'msg' => $data['invoice'].' Invoice sudah ada sebelumnya'));
        }
        switch ($data['bank_payment']) {
            case 'MUAMALAT':
                $data['status_inquiry'] = '1';
                $data['expired_payment'] = null;
                break;
            default:
                jsonResponse(array('status' => false, 'msg' => 'Bank tidak ditemukan'));
                break;
        }
        //multi transaction
        $this->db->trans_start();
        $this->db->insert('m_payment', $data);
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            jsonResponse(array('status' => true, 'data' => site_url($this->module.'/detail/'.encode($data['id_payment'])),
                'msg' => 'Data berhasil disimpan'));
        } else {
            jsonResponse(array('status' => false, 'msg' => 'Data gagal disimpan'));
        }
    }
    function _invoice_auto($mhs) {
        $id = $mhs['id_mhs'];
        $sql = "SELECT MAX(RIGHT(invoice, 4)) AS nomor FROM m_payment WHERE mhs_id='$id'";
        $query = $this->db->query($sql);
        if($query->num_rows() > 0){
            $row = $query->row();
            $n = ((int) $row->nomor) + 1;
            $no = sprintf("%'.04d", $n);
        }else {
            $no = "0001";
        }
        return $mhs['kode_reg'].'-'.$no; //0125UNMD001-0001
    }
    private $rules = array(
        array(
            'field' => 'mhs',
            'label' => 'Mahasiswa',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'tagihan',
            'label' => 'Tagihan',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'total',
            'label' => 'Total Pembayaran',
            'rules' => 'required|trim|xss_clean|is_natural|greater_than_equal_to[10000]'
        ),array(
            'field' => 'bank',
            'label' => 'Bank',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'virtual',
            'label' => 'Virtual Account',
            'rules' => 'required|trim|xss_clean|is_natural|min_length[5]'
        ),array(
            'field' => 'note',
            'label' => 'Catatan',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        )
    );
    private $rules_va = array(
        array(
            'field' => 'id',
            'label' => 'Mahasiswa',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'bank',
            'label' => 'Bank',
            'rules' => 'required|trim|xss_clean'
        )
    );
}