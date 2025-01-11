<?php defined('BASEPATH') OR exit('No direct script access allowed');

use Firebase\JWT\JWT;

class Jurnal_do extends KZ_Controller {
    
    private $module = 'elearning/jurnal';
    private $module_do = 'elearning/jurnal_do';
    private $url_route = array('id', 'source', 'type');   
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_jurnal'));
        
        $this->_dosen_id();
    }
    function add() {
        if(!$this->_validation($this->rules)){
            redirect($this->module.'/add');
        }
        $data['kelas_id'] = decode($this->input->post('kelas'));
        $data['tgl_jurnal'] = $this->input->post('tanggal');
        $data['init_jurnal'] = $this->input->post('init');
        $data['mode_jurnal'] = $this->input->post('mode');
        $data['ruang_jurnal'] = strtoupper($this->input->post('ruang'));
        $data['waktu_jurnal'] = $this->input->post('waktu');
        $data['status_jurnal'] = $this->input->post('status');
        $data['note_jurnal'] = $this->input->post('note');
        
        $data['update_jurnal'] = date('Y-m-d H:i:s');
        $data['log_jurnal'] = $this->sessionname.' menambahkan data';
        
        $check = $this->m_jurnal->getId(array('kelas_id' => $data['kelas_id'], 'init_jurnal' => $data['init_jurnal']));
        if(!is_null($check)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Pertemuan Ke - <strong>'.$data['init_jurnal'].'</strong> sudah tersimpan sebelumnya'));
            redirect($this->module.'/add');
        }
        $result = $this->m_jurnal->insert($data);
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
        if(!$this->_validation($this->rules)){
            redirect($this->module.'/edit/'.$id);
        }
        $data['kelas_id'] = decode($this->input->post('kelas'));
        $data['tgl_jurnal'] = $this->input->post('tanggal');
        //$data['init_jurnal'] = $this->input->post('init');
        $data['mode_jurnal'] = $this->input->post('mode');
        $data['ruang_jurnal'] = strtoupper($this->input->post('ruang'));
        $data['waktu_jurnal'] = $this->input->post('waktu');
        $data['status_jurnal'] = $this->input->post('status');
        $data['note_jurnal'] = $this->input->post('note');
        
        $data['update_jurnal'] = date('Y-m-d H:i:s');
        $data['log_jurnal'] = $this->sessionname.' mengubah data';
        
        $result = $this->m_jurnal->update(decode($id), $data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil diubah'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal diubah'));
            redirect($this->module.'/edit/'.$id);
        }
    }
    function export() {
        if(!$this->_validation($this->rules_export)){
            redirect($this->module);
        }
        $this->load->model(array('m_kelas','m_prodi'));
         
        $id = decode($this->input->post('kelas'));
        $mode = element('presensi', $this->input->post());
        
        $kelas = $this->m_kelas->getId($id);
        if(is_null($kelas)){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data Kelas tidak ditemukan'));
            redirect($this->module);
        }
        $prodi = $this->m_prodi->getId($kelas['prodi_id']);
        $kuota_mhs = $this->db->where(array('kelas_id' => $id))->count_all_results('rf_nilai');
        //presensi
        if(!empty($mode)){
            $this->_export_presensi($kelas, $prodi);
        }
        $result = $this->m_jurnal->getAll(array('kelas_id' => $id), 'asc');
        if($result['rows'] < 1){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data Jurnal Kuliah tidak ditemukan'));
            redirect($this->module);
        }
        $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load('app/img/jurnal.xlsx');
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Jurnal Kuliah');
        
        $sheet->setCellValue('A2', "UNIVERSITAS PENDIDIKAN MUHAMMADIYAH (UNIMUDA) SORONG \n".strtoupper($prodi['fakultas']));
        $sheet->setCellValue('A4', 'JURNAL PERKULIAHAN');
        $sheet->setCellValue('A5', '');
        $sheet->setCellValue('B7', 'Program Studi');$sheet->setCellValue('C7', $prodi['nama_prodi']);
        $sheet->setCellValue('B8', 'Semester');$sheet->setCellValue('C8', is_periode($kelas['id_semester'],1));
        $sheet->setCellValue('B9', 'Dosen Pengampu');$sheet->setCellValue('C9', $kelas['nama_dosen']);
        $sheet->setCellValue('B10', 'Mata Kuliah');$sheet->setCellValue('C10', $kelas['kode_matkul'].' - '.$kelas['nama_matkul']);
        $sheet->setCellValue('B11', 'Jumlah SKS');$sheet->setCellValueExplicit('C11', $kelas['sks_matkul'],'s');
        $sheet->setCellValue('B12', 'Nama Kelas');$sheet->setCellValueExplicit('C12', $kelas['nama_kelas'],'s');
        $sheet->setCellValue('B13', 'Jumlah Mahasiswa');$sheet->setCellValueExplicit('C13', $kuota_mhs,'s');
        
        $fields = array('No', 'Pertemuan', 'Materi Perkuliahan','Mode Kuliah',
            'Presensi', 'Ruang Kuliah', 'Jam Kuliah', 'Paraf', 'Keterangan');
        $col = 1;
        foreach ($fields as $field) {
            $sheet->setCellValueByColumnAndRow($col, 15, $field);
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
            $col++;
        }
        $no = 1;
        $row = 16;
        $filename = url_title('JURNAL '.$kelas['kode_matkul'].' '.$kelas['nama_matkul'].' '.
            $kelas['nama_kelas'].' '.$prodi['nama_prodi'],'-',true).'.xls';
        foreach ($result['data'] as $val) {
            
            $presensi = '';
            $json_presensi = json_decode($val['presensi_jurnal'], true);
            $counter = ['HADIR' => 0,'SAKIT' => 0,'IZIN' => 0,'PENDING' => 0];
            if(!empty($json_presensi)){
                foreach ($json_presensi as $entry) {
                    if (array_key_exists($entry['status'], $counter)) {
                        $counter[$entry['status']]++;
                    }
                }
                $presensi .= $counter['HADIR'] > 0 ? $counter['HADIR'].'-HADIR' : '';
                $presensi .= $counter['PENDING'] > 0 ? ' '.$counter['PENDING'].'-PENDING' : '';
                $presensi .= $counter['SAKIT'] > 0 ? ' '.$counter['SAKIT'].'-SAKIT' : '';
                $presensi .= $counter['IZIN'] > 0 ? ' '.$counter['IZIN'].'-IZIN' : '';
            }
            $sheet->setCellValue('A' . $row, $no)
                ->setCellValue('B' . $row, 'Ke - ['.$val['init_jurnal'].']  '.format_date($val['tgl_jurnal']))
                ->setCellValue('C' . $row, $val['note_jurnal'])
                ->setCellValue('D' . $row, $val['mode_jurnal'])
                ->setCellValue('E' . $row, $presensi)
                ->setCellValue('F' . $row, $val['ruang_jurnal'])
                ->setCellValue('G' . $row, $val['waktu_jurnal'])
                ->setCellValue('H' . $row, '')
                ->setCellValue('I' . $row, '');

            $no++;
            $row++;
        }
        $last = $row + 3;
        $sheet->setCellValue('B'.$last, 'Mengetahui');
        $sheet->setCellValue('E'.$last, 'Sorong, '.format_date(date('Y-m-d'),1));
        $last++;
        $sheet->setCellValue('B'.$last, 'Ketua Program Studi '.$prodi['nama_prodi']);
        $sheet->setCellValue('E'.$last, 'Dosen Pengampu');
        $last++;$last++;$last++;$last++;$last++;
        $sheet->setCellValue('B'.$last, $prodi['ketua_prodi']);
        $sheet->setCellValue('E'.$last, $kelas['nama_dosen']);
        $last++;
        $sheet->setCellValue('B'.$last, 'NIDN. '.$prodi['nidn_prodi']);
        $sheet->setCellValue('E'.$last, 'NIDN. ');
        
        $tableStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => false),
            'font' => array('size' => 12, 'color' => array('rgb' => '000000'))
        ];
        $boldStyle = array(
            'font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => '000000'))
        );
        $row--;
        $sheet->getStyle('A15:I'.$row)->applyFromArray($tableStyle);
        $sheet->getStyle('A15:I15')->applyFromArray($boldStyle);
        $sheet->getStyle('B7:C13')->applyFromArray($boldStyle);
        
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if($routing_module['type'] == 'action') {
            //ACTION
            if($routing_module['source'] == 'presensi') {
                $this->_config_presensi();
            }else if($routing_module['source'] == 'qrcode') {
                $this->_config_qrcode();
            }
        }
    }
    //function
    function _config_presensi() {
        if(empty($this->did)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Data Dosen tidak ditemukan'));
        }
        if(!$this->_validation($this->rules_presensi,'ajax')){
            jsonResponse(array('status' => FALSE, 'msg' => validation_errors()));
        }
        $id = decode($this->input->post('id'));
        $status = decode($this->input->post('status'));
        $mhsid = array_filter(explode(",", $this->input->post('mhs')));
        
        $check = $this->m_jurnal->getId($id);
        if(empty($check)){
            jsonResponse(array('status' => false, 'msg' => 'Jurnal tidak ditemukan'));
        }
        $json_presensi = json_decode($check['presensi_jurnal'], true);
        $expired = (strtotime(date('Y-m-d')) - strtotime($check['tgl_jurnal']))/86400;
        if($expired > 100 && $this->sessionlevel != '1') {
            jsonResponse(array('status' => false, 
                'msg' => 'Waktu Presensi melebihi <strong>100 HARI</strong> sejak tanggal kuliah : '. format_date($check['tgl_jurnal'])));
        }
        $tambah = array();
        $ubah = array();
        $hapus = array();
        if(empty($status)){
            foreach ($mhsid as $val) {
                $json_presensi = array_filter($json_presensi, function($item) use ($val) {
                    return $item['id'] !== decode($val);
                });
                $json_presensi = array_values($json_presensi);
                $hapus[] = $val;
            }
        }else{
            foreach ($mhsid as $val) {
                //valid akm
                $akm = $this->db->get_where('rf_akm', array('mhs_id' => decode($val), 'semester_id' => $this->smtid))->row_array();
                $new_status = (!empty($akm) && $akm['valid_akm'] == '0') ? 'PENDING':$status;
                
                if(empty($json_presensi)){
                    $json_presensi[] = array('id' => decode($val), 'status' => $new_status, 'buat' => date('Y-m-d H:i:s'));
                    $tambah[] = $val;
                }else{
                    $id_mhs = array_column($json_presensi, 'id');
                    if (!in_array(decode($val), $id_mhs)) {
                        $json_presensi[] = array('id' => decode($val), 'status' => $new_status, 'buat' => date('Y-m-d H:i:s'));
                        $tambah[] = $val;
                    } else {
                        foreach ($json_presensi as &$item) {
                            if ($item['id'] === decode($val)) {
                                $item['status'] = $new_status;
                                $item['buat'] = date('Y-m-d H:i:s');
                                break;
                            }
                        }
                        unset($item);
                        $ubah[] = $val;
                    }
                }
            }
        }
        $data['presensi_jurnal'] = json_encode($json_presensi);
        $data['update_jurnal'] = date('Y-m-d H:i:s');
        $data['log_jurnal'] = $this->sessionname.' mengubah presensi';
        
        if(count($tambah) + count($ubah) + count($hapus) < 1){
            jsonResponse(array('status' => FALSE, 'msg' => 'Tidak ada perubahan data'));
        }
        $result = $this->m_jurnal->update($id, $data);
        if ($result) {
            jsonResponse(array('status' => true, 'msg' => count($tambah).' Data Presensi tersimpan.<br>'.
                count($ubah).' Data Presensi diperbarui.<br>'.count($hapus).' Data Presensi dihapus.'));
        } else {
            jsonResponse(array('status' => FALSE, 'msg' => 'Data gagal diubah'));
        }
    }
    function _config_qrcode() {
        if(empty($this->did)){
            jsonResponse(array('status' => FALSE, 'msg' => 'Data Dosen tidak ditemukan'));
        }
        $this->load->helper('date');
        $id = decode($this->input->post('id'));
        try {
            $payload = ['exp' => now() + 180, 'data' => $id];
            $token = JWT::encode($payload, $this->config->item('encryption_key'), 'HS256');
            
            jsonResponse(array('data' => $token,'status' => TRUE, 'msg' => 'Generate QR Code berhasil'));
        } catch (Exception $e) {
            jsonResponse(array('status' => FALSE, 'msg' => 'Generate QR Code gagal '.$e->getMessage()));
        }
    }
    function _export_presensi($kelas, $prodi) {
        $result = $this->db->order_by('m.nim', 'ASC')->join('m_mhs m', 'n.mhs_id = m.id_mhs', 'left')
            ->get_where('rf_nilai n', array('n.kelas_id' => $kelas['id_kelas']));
        
        if($result->num_rows() < 1){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data Peserta Kelas tidak ditemukan'));
            redirect($this->module);
        }
        $reader = new PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $spreadsheet = $reader->load('app/img/jurnal.xlsx');
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Presensi Mahasiswa');
        
        $sheet->setCellValue('A2', "UNIVERSITAS PENDIDIKAN MUHAMMADIYAH (UNIMUDA) SORONG \n".strtoupper($prodi['fakultas']));
        $sheet->setCellValue('A4', 'PRESENSI PERKULIAHAN');
        $sheet->setCellValue('A5', '');
        $sheet->setCellValue('B7', 'Program Studi');$sheet->setCellValue('C7', $prodi['nama_prodi']);
        $sheet->setCellValue('B8', 'Semester');$sheet->setCellValue('C8', is_periode($kelas['id_semester'],1));
        $sheet->setCellValue('B9', 'Dosen Pengampu');$sheet->setCellValue('C9', $kelas['nama_dosen']);
        $sheet->setCellValue('B10', 'Mata Kuliah');$sheet->setCellValue('C10', $kelas['kode_matkul'].' - '.$kelas['nama_matkul']);
        $sheet->setCellValue('B11', 'Jumlah SKS');$sheet->setCellValueExplicit('C11', $kelas['sks_matkul'],'s');
        $sheet->setCellValue('B12', 'Nama Kelas');$sheet->setCellValueExplicit('C12', $kelas['nama_kelas'],'s');
        
        $fields = array('No', 'NIM', 'Nama Lengkap');
        $col = 1;
        foreach ($fields as $field) {
            $sheet->setCellValueByColumnAndRow($col, 15, $field);
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
            $col++;
        }
        $jurnal = $this->db->order_by('init_jurnal','ASC')->get_where('m_jurnal', array('kelas_id' => $kelas['id_kelas']));
        foreach ($jurnal->result_array() as $column) {
            $sheet->setCellValueByColumnAndRow($col, 15, 'Ke - '.$column['init_jurnal']);
            $col++;
        }
        $no = 1;
        $row = 16;
        $filename = url_title('PRESENSI '.$kelas['kode_matkul'].' '.$kelas['nama_matkul'].' '.
            $kelas['nama_kelas'].' '.$prodi['nama_prodi'],'-',true).'.xls';
        
        foreach ($result->result_array() as $val) {
            $sheet->setCellValue('A' . $row, $no)->setCellValueExplicit('B' . $row, $val['nim'],'s')
                ->setCellValue('C' . $row, $val['nama_mhs']);
            $kolom = 4;
            foreach ($jurnal->result_array() as $array) {
                $json_presensi = empty($array['presensi_jurnal']) ? array() : json_decode($array['presensi_jurnal'], true);
                $search = array_filter($json_presensi, function($arr) use ($val) {
                    return (element('id', $arr) === $val['id_mhs']);
                });
                if(!empty($search)){
                    $found = reset($search);
                    $sheet->setCellValueByColumnAndRow($kolom, $row, (element('status', $found)));
                }
                $kolom++;
            }
            $no++;
            $row++;
        }
        $last = $row + 3;
        $sheet->setCellValue('B'.$last, 'Mengetahui');
        $sheet->setCellValue('E'.$last, 'Sorong, '.format_date(date('Y-m-d'),1));
        $last++;
        $sheet->setCellValue('B'.$last, 'Ketua Program Studi '.$prodi['nama_prodi']);
        $sheet->setCellValue('E'.$last, 'Dosen Pengampu');
        $last++;$last++;$last++;$last++;$last++;
        $sheet->setCellValue('B'.$last, $prodi['ketua_prodi']);
        $sheet->setCellValue('E'.$last, $kelas['nama_dosen']);
        $last++;
        $sheet->setCellValue('B'.$last, 'NIDN. '.$prodi['nidn_prodi']);
        $sheet->setCellValue('E'.$last, 'NIDN. ');
        
        $tableStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => false),
            'font' => array('size' => 12, 'color' => array('rgb' => '000000'))
        ];
        $boldStyle = array(
            'font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => '000000'))
        );
        $row--;
        $sheet->getStyleByColumnAndRow(1,15,$col,$row)->applyFromArray($tableStyle);
        $sheet->getStyleByColumnAndRow(1,15,$col,15)->applyFromArray($boldStyle);
        $sheet->getStyle('B7:C13')->applyFromArray($boldStyle);
        
        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }
    function _valid_date($date) {
        $parts = explode("-", $date);
        if (count($parts) == 3) {
            if (checkdate($parts[1], $parts[2], $parts[0])) {
                return true;
            }
        }
        $this->form_validation->set_message('_valid_date', '%s tidak sesuai format yang seharusnya : yyyy-mm-dd (2012-12-31)');
        return false;
    }
    private $rules = array(
        array(
            'field' => 'kelas',
            'label' => 'Kelas Kuliah',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'init',
            'label' => 'Pertemuan Ke ?',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'tanggal',
            'label' => 'Tanggal Kuliah',
            'rules' => 'required|trim|xss_clean|min_length[10]|callback__valid_date'
        ),array(
            'field' => 'mode',
            'label' => 'Mode',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'ruang',
            'label' => 'Ruang Kuliah',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'waktu',
            'label' => 'Waktu Kuliah',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'status',
            'label' => 'Status Kuliah',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'note',
            'label' => 'Catatan',
            'rules' => 'trim|xss_clean|min_length[10]'
        )
    );
    private $rules_export = array(
        array(
            'field' => 'dosen',
            'label' => 'Dosen',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'kelas',
            'label' => 'Kelas Kuliah',
            'rules' => 'required|trim|xss_clean'
        )
    );
    private $rules_presensi = array(
        array(
            'field' => 'id',
            'label' => 'Jurnal Kuliah',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'status',
            'label' => 'Status',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'mhs',
            'label' => 'Mahasiswa',
            'rules' => 'required|trim|xss_clean'
        )
    );
}