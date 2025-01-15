<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Payment extends KZ_Controller {
    
    private $module = 'transaksi/payment';
    private $module_do = 'transaksi/payment_do';
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        
        $this->load->model(array('m_payment'));
        $this->_getMhs();
    }
    function index() {
        $this->load->model(array('m_prodi'));
        
        $this->data['prodi'] = $this->m_prodi->getAll();
        $this->data['tagihan'] = $this->db->get_where('tmp_item');
        $this->data['bank'] = ['MUAMALAT'];
        $this->data['is_mahasiswa'] = ($this->sessiongroup != '1') ? $this->mid : NULL;
        
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Pembayaran','List Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('transaksi/payment/v_index', $this->data);
    }
    function add() {
        $this->data['bank'] = ['MUAMALAT'];
        $this->data['is_mahasiswa'] = ($this->sessiongroup != '1') ? $this->mid : NULL;
        
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Pembayaran','Tambah Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('transaksi/payment/v_form', $this->data);
    }
    function detail($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $detail = $this->m_payment->getBill(decode($id));
        if(empty($detail)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data Pembayaran tidak ditemukan'));
            redirect($this->module);
        }
        $this->data['is_modal'] = ($detail['status_payment'] != '1') ? '' : 'PAID';
        $this->data['detail'] = $detail;
        
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Pembayaran', $detail['nama_mhs']);
        $this->data['breadcrumb'] = array( 
            array('title'=>$this->uri->segment(1), 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('transaksi/payment/v_detail', $this->data);
    }
    function cetak($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->load->library(array('fungsi'));
        
        $detail = $this->m_payment->getBill(decode($id));
        if(is_null($detail)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data tidak ditemukan'));
            redirect($this->module);
        }
        $this->data['detail'] = $detail;
        $this->data['judul'] = array('INVOICE TAGIHAN', 'BIRO PENGELOLAAN KEUANGAN DAN ASET (BPKA)');
        
        $html = $this->load->view('transaksi/payment/v_print', $this->data, true);
        $this->fungsi->PdfGenerate($html, url_title('Tagihan '.$detail['invoice'].' '.$detail['nama_mhs'], '-', true));
    }
    function delete($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $check = $this->m_payment->getId(decode($id));
        if(in_array($check['status_payment'], ['1'])){
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal dihapus'));
            redirect($this->module);
        }
        $result = $this->m_payment->delete(decode($id));
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil dihapus'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal dihapus'));
            redirect($this->module);
        }
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if($routing_module['type'] == 'table') {
            //TABLE
            if($routing_module['source'] == 'mhs') {
                $this->_table_mhs();
            }
        }else if($routing_module['type'] == 'list') {
            if($routing_module['source'] == 'mahasiswa') {
                $this->_get_mhs();
            }if($routing_module['source'] == 'tagihan') {
                $this->_get_tagihan();
            }
        }else if($routing_module['type'] == 'chart') {
            //TABLE
            if($routing_module['source'] == 'payment') {
                $this->_chart_pay();
            }
        }
    }
    //function
    function _table_mhs() {
        $prodi = decode($this->input->post('prodi'));
        $tahun = $this->input->post('tahun');
        $status = $this->input->post('status');
        $tagihan = decode($this->input->post('tagihan'));
        $bank = $this->input->post('bank');
        $bulan = $this->input->post('bulan');
        
        $where = null;
        if(!empty($this->mid) && $this->sessiongroup != '1'){
            $where['mhs_id'] = $this->mid;
        }
        if ($prodi != '') {
            $where['prodi_id'] = $prodi;
        }
        if ($tahun != '') {
            $where['angkatan'] = $tahun;
        }
        if ($status != '') {
            $where['status_payment'] = $status;
        }
        if ($tagihan != '') {
            $where['item_id'] = $tagihan;
        }
        if ($bank != '') {
            $where['bank_payment'] = $bank;
        }
        if ($bulan != '') {
            $where['DATE_FORMAT(paid_payment,"%m-%Y")'] = $bulan;
        }
        $list = $this->m_payment->get_datatables($where);

        $data = array();
        $no = $_POST['start'];
        foreach ($list as $items) {
            $no++;
            $row = array();
            
            $btn_aksi = '<a href="'. site_url($this->module .'/detail/'. encode($items['id_payment'])) .'" 
                class="tooltip-info btn btn-white btn-info btn-sm btn-round" data-rel="tooltip" title="Lihat Data">
                    <span class="blue"><i class="ace-icon fa fa-search-plus bigger-120"></i></span>
                </a>
                <a target="_blank" href="'. site_url('master/daftar/detail/'. encode($items['id_mhs'])) .'" 
                class="tooltip-info btn btn-white btn-default btn-mini btn-round" data-rel="tooltip" title="Mahasiswa">
                    <span class=""><i class="ace-icon fa fa-external-link"></i></span>
                </a>';
            
            $is_total = ($items['status_payment'] == '1') ? 'grey' : 'red';
            $is_paid = empty($items['paid_payment']) ? '' : '<br/><i class="fa fa-calendar-check-o green"></i> 
                <small>'.format_date($items['paid_payment'],2).'</small>';
            $is_up = empty($items['update_payment']) ? '' : '<br/><i class="fa fa-calendar grey"></i> 
                <small>'.format_date($items['update_payment'],2).'</small>';
            
            $row[] = ctk($no);
            $row[] = '<strong>'.ctk($items['nama_mhs']).'</strong><br>
                <span class="blue">'.ctk($items['invoice']).'</span>';
            $row[] = $items['nama_prodi'].'<br/>'.ctk($items['angkatan']);
            $row[] = '<strong class="blue bigger-110">'.ctk($items['va_payment']).'</strong><br/><strong>'
                .ctk($items['bank_payment']).'</strong>';
            $row[] = '<strong class="'.$is_total.' bigger-110">'.rupiah($items['total_payment']).'</strong><br/>
                <i class="fa fa-calendar-plus-o"></i> <small class="grey">'.format_date($items['buat_payment'],2).'</small>';
            $row[] = st_aktif($items['status_payment'],null,'pay').$is_paid;
            $row[] = '<small>'.$items['note_payment'].'</small>'.$is_up;
            $row[] = '<div class="action-buttons">'.$btn_aksi.'</div>';
            
            $data[] = $row;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->m_payment->count_all($where),
            "recordsFiltered" => $this->m_payment->count_filtered($where),
            "data" => $data,
        );
        jsonResponse($output);
    }
    function _chart_pay() {
        $prodi = decode($this->input->post('prodi'));
        $tahun = $this->input->post('tahun');
        $status = $this->input->post('status');
        $tagihan = decode($this->input->post('tagihan'));
        $bulan = empty($this->input->post('bulan')) ? date('m-Y') : $this->input->post('bulan');
        
        $where['DATE_FORMAT(paid_payment,"%m-%Y")'] = $bulan;
        $date = DateTime::createFromFormat('m-Y', $bulan);
        if ($date && $date->format('m-Y') === $bulan) {
            $bulan = $date->format('F Y');
        }
        if ($prodi != '') {
            $where['prodi_id'] = $prodi;
        }
        if ($tahun != '') {
            $where['angkatan'] = $tahun;
        }
        if ($status != '') {
            $where['status_payment'] = $status;
        }
        if ($tagihan != '') {
            $where['item_id'] = $tagihan;
        }
        $result = $this->db->select('DATE_FORMAT(paid_payment,"%Y-%m-%d") AS day')->group_by('DATE(paid_payment)')
            ->join('m_mhs m', 'm.id_mhs = p.mhs_id', 'left')->get_where('m_payment p', $where);
        
        $data = array('Tbni' => 0, 'Tbri' => 0, 'Tbmi' => 0, 'total' => 0, 'range' => '');
        if($result->num_rows() < 1){
            jsonResponse(array('data' => $data, 'status' => false, 'msg' => 'Data tidak ditemukan'));
        }
        foreach ($result->result_array() as $item) {
            $row = array();
            $row['day'] = '<strong class="bigger-120">'.format_date($item['day']).'</strong>';
            $row['bni'] = $this->m_payment->getSum(array('bank_payment' => 'BNI', 'DATE(paid_payment)' => $item['day']));
            $row['bri'] = $this->m_payment->getSum(array('bank_payment' => 'BRI', 'DATE(paid_payment)' => $item['day']));
            $row['bmi'] = $this->m_payment->getSum(array('bank_payment' => 'Muamalat', 'DATE(paid_payment)' => $item['day']));

            $data['item'][] = $row;
            $data['Tbni'] += $row['bni']; $data['Tbri'] += $row['bri']; $data['Tbmi'] += $row['bmi'];
        }
        $data['total'] = rupiah($data['Tbni'] + $data['Tbri'] + $data['Tbmi']);
        $data['range'] = $bulan;
        
        jsonResponse(array('data' => $data, 'status' => true, 'msg' => 'Data ditemukan'));
    }
    function _get_mhs(){
        $key = $this->input->post('key');
        $id = (!empty($this->mid) && $this->sessiongroup != '1') ? $this->mid : decode($this->input->get('id'));
        
        $where = null;
        if(!empty($id)){
            $where['id_mhs'] = $id;
        }
        $this->db->join('m_prodi p', 'm.prodi_id = p.id_prodi', 'left')->order_by('nama_mhs', 'ASC');
        if(!empty($key)){
            $this->db->group_start()
            ->like('kode_reg', $key, 'both')
            ->or_like('nama_mhs', $key, 'both')->group_end();
        }
        $result = $this->db->get_where('m_mhs m', $where);
        $data = array();
        foreach ($result->result_array() as $val) {
            $text = $val['kode_reg'].' - '.$val['nama_mhs'].' ['.$val['angkatan'].' - '.$val['nama_prodi'].']';
            $status = ($val['status_mhs'] == 'AKTIF') ? true : false;
            $data[] = array("id" => encode($val['id_mhs']), "text" => $text, "status" => $status);
        }
        jsonResponse($data);
    }
    function _get_tagihan(){
        $result = $this->db->order_by('status_item', 'DESC')
            ->get_where('tmp_item');
        $data = array();
        foreach ($result->result_array() as $val) {
            $text = $val['nama_item'].' ['. rupiah($val['total_item']) .']';
            $data[] = array("id" => encode($val['id_item']), "text" => $text, "total" => $val['total_item']);
        }
        jsonResponse($data);
    }
}