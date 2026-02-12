<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Bank_do extends KZ_Controller {
    
    private $module = 'master/bank';
    private $module_do = 'master/bank_do';
            
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_bank'));
    }
    function add() {
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module.'/add');
        }
        $data['nama_bank'] = strtoupper($this->input->post('nama'));
        $data['jenis_bank'] = $this->input->post('jenis');
        
        $result = $this->m_bank->insert($data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil disimpan'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal disimpan'));
            redirect($this->module.'/add');
        }
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        if(!$this->fungsi->Validation($this->rules)){
            redirect($this->module.'/edit/'.$id);
        }
        $data['nama_bank'] = strtoupper($this->input->post('nama'));
        $data['jenis_bank'] = $this->input->post('jenis');
        
        $result = $this->m_bank->update(decode($id), $data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil diubah'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal diubah'));
            redirect($this->module.'/edit/'.$id);
        }
    }
    function detail($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $this->load->model(array('m_soal'));
        
        $bank = $this->m_bank->getId(decode($id));
        if(is_null($bank)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Bank soal tidak ditemukan'));
            redirect($this->module);
        }
        if(empty($_FILES['file']['name'])){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Tidak ada file yang di unggah'));
            redirect($this->module.'/detail/'.$id);
        }
        $arr_file = explode('.', $_FILES['file']['name']);
        $extension = end($arr_file);
        if(!in_array($extension, array('xls','xlsx'))){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Format file tidak didukung'));
            redirect($this->module.'/detail/'.$id);
        }
        $reader = ($extension == 'xls') ? new PhpOffice\PhpSpreadsheet\Reader\Xls() : new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();
        
        if($highestRow < 2){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Tidak ada data dalam file'));
            redirect($this->module.'/detail/'.$id);
        }
        $data = array();
        for($row = 2; $row <= $highestRow; $row++){
            $item = array();
            
            $order = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
            $isi = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
            $materi = $worksheet->getCellByColumnAndRow(3, $row)->getValue();

            $value1 = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
            $value2 = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
            $value3 = $worksheet->getCellByColumnAndRow(6, $row)->getValue();
            $value4 = $worksheet->getCellByColumnAndRow(7, $row)->getValue();
            $value5 = $worksheet->getCellByColumnAndRow(8, $row)->getValue();
            
            if(!empty($order) && !empty($isi)){
                
                if($bank['jenis_bank'] == 'KUESIONER'){
                    $opsi1 = array('key' => '2-A', 'isi' => $value1, 'nilai' => 1, 'file' => '');
                    $opsi2 = array('key' => '2-B', 'isi' => $value2, 'nilai' => 2, 'file' => '');
                    $opsi3 = array('key' => '2-C', 'isi' => $value3, 'nilai' => 3, 'file' => '');
                    $opsi4 = array('key' => '2-D', 'isi' => $value4, 'nilai' => 4, 'file' => '');
                    $opsi5 = array('key' => '2-E', 'isi' => $value5, 'nilai' => 5, 'file' => '');
                    //non-acak
                    $opsi_soal = [$opsi1,$opsi2,$opsi3,$opsi4,$opsi5];
                    
                    $item['opsi_a'] = empty($value1) ? null : json_encode($opsi_soal[0]);
                    $item['opsi_b'] = empty($value2) ? null : json_encode($opsi_soal[1]);
                    $item['opsi_c'] = empty($value3) ? null : json_encode($opsi_soal[2]);
                    $item['opsi_d'] = empty($value4) ? null : json_encode($opsi_soal[3]);
                    $item['opsi_e'] = empty($value5) ? null : json_encode($opsi_soal[4]);
                    
                }else if($bank['jenis_bank'] == 'PILIHAN-GANDA'){
                    $opsi1 = array('key' => '1-A', 'isi' => $value1, 'nilai' => 1, 'file' => '');
                    $opsi2 = array('key' => '1-B', 'isi' => $value2, 'nilai' => 0, 'file' => '');
                    $opsi3 = array('key' => '1-C', 'isi' => $value3, 'nilai' => 0, 'file' => '');
                    $opsi4 = array('key' => '1-D', 'isi' => $value4, 'nilai' => 0, 'file' => '');
                    $opsi5 = array('key' => '1-E', 'isi' => $value5, 'nilai' => 0, 'file' => '');
                    //acak
                    $item['opsi_a'] = null;
                    $item['opsi_b'] = null;
                    $item['opsi_c'] = null;
                    $item['opsi_d'] = null;
                    $item['opsi_e'] = null;
                    if(!empty($value1)){
                        $opsi_soal = [$opsi1];
                        $item['opsi_a'] = json_encode($opsi_soal[0]);
                    }
                    if(!empty($value2)){
                        $opsi_soal = [$opsi1,$opsi2];
                        shuffle($opsi_soal);shuffle($opsi_soal);
                        $item['opsi_a'] = json_encode($opsi_soal[0]);
                        $item['opsi_b'] = json_encode($opsi_soal[1]);
                    }
                    if(!empty($value3)){
                        $opsi_soal = [$opsi1,$opsi2,$opsi3];
                        shuffle($opsi_soal);shuffle($opsi_soal);
                        $item['opsi_a'] = json_encode($opsi_soal[0]);
                        $item['opsi_b'] = json_encode($opsi_soal[1]);
                        $item['opsi_c'] = json_encode($opsi_soal[2]);
                    }
                    if(!empty($value4)){
                        $opsi_soal = [$opsi1,$opsi2,$opsi3,$opsi4];
                        shuffle($opsi_soal);shuffle($opsi_soal);
                        $item['opsi_a'] = json_encode($opsi_soal[0]);
                        $item['opsi_b'] = json_encode($opsi_soal[1]);
                        $item['opsi_c'] = json_encode($opsi_soal[2]);
                        $item['opsi_d'] = json_encode($opsi_soal[3]);
                    }
                    if(!empty($value5)){
                        $opsi_soal = [$opsi1,$opsi2,$opsi3,$opsi4,$opsi5];
                        shuffle($opsi_soal);shuffle($opsi_soal);
                        $item['opsi_a'] = json_encode($opsi_soal[0]);
                        $item['opsi_b'] = json_encode($opsi_soal[1]);
                        $item['opsi_c'] = json_encode($opsi_soal[2]);
                        $item['opsi_d'] = json_encode($opsi_soal[3]);
                        $item['opsi_e'] = json_encode($opsi_soal[4]);
                    }
                }
                $item['id_soal'] = random_string('unique');
                $item['bank_id'] = decode($id);
                $item['materi_soal'] = ucwords($materi);
                $item['isi_soal'] = $isi;
                $item['status_soal'] = '1';
                $item['order_soal'] = $order;
                $item['update_soal'] = date('Y-m-d H:i:s');
                $item['log_soal'] = $this->sessionname.' import excel';
                
                $data[] = $item;
            }
        }
        if(count($data) < 1){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Tidak ada data yang tersimpan'));
            redirect($this->module.'/detail/'.$id);
        }
        $result = $this->m_soal->insertBatch($data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', count($data).' Data berhasil disimpan'));
            redirect($this->module.'/detail/'.$id);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal disimpan'));
            redirect($this->module.'/detail/'.$id);
        }
    }
    private $rules = array(
        array(
            'field' => 'nama',
            'label' => 'Nama Bank',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'jenis',
            'label' => 'Jenis Bank',
            'rules' => 'required|trim|xss_clean'
        )
    );
}
