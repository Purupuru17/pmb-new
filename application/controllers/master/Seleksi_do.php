<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Seleksi_do extends KZ_Controller {
    
    private $module = 'master/seleksi';
    private $module_do = 'master/seleksi_do';
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_mhs','m_prodi'));
    }
    function edit($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        if(!$this->_validation($this->rules_edit)){
            redirect($this->module.'/edit/'.$id);
        }
        $data['nim'] = $this->input->post('nim');
        $data['atribut_mhs'] = strtoupper($this->input->post('atribut'));
        
        $data['update_mhs'] = date('Y-m-d H:i:s');
        $data['log_mhs'] = $this->sessionname.' mengubah atribut maba';

        $result = $this->m_mhs->update(decode($id), $data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil diubah'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal diubah'));
            redirect($this->module.'/edit/'.$id);
        }
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if (is_null($routing_module['type'])) {
            redirect('');
        }
        if ($routing_module['type'] == 'action') {
            //ACTION
            if ($routing_module['source'] == 'insert') {
                $this->_insert_bio();
            }else if ($routing_module['source'] == 'update') {
                $this->_insert_nim();
            }else if ($routing_module['source'] == 'delete') {
                $this->_delete_nim();
            }
        }
    }
    //function
    function _insert_bio() {
        if (!$this->_validation($this->rules_insert,'ajax')) {
            jsonResponse(array('status' => FALSE, 'msg' => validation_errors()));
        }
        $id = decode($this->input->post('id'));
        $wilayah = $this->input->post('wilayah');

        $mhs = $this->m_mhs->getId($id);
        if(empty($mhs)) {
            jsonResponse(array('status' => FALSE, 'msg' => 'Tidak ada data mahasiswa'));
        }
        
        switch ($mhs['agama']) {
            case 'Islam':
                $agama = 1;
                break;
            case 'Kristen':
                $agama = 2;
                break;
            case 'Katolik':
                $agama = 3;
                break;
            case 'Hindu':
                $agama = 4;
                break;
            case 'Budha':
                $agama = 5;
                break;
            case 'Konghucu':
                $agama = 6;
                break;
            default:
                $agama = 99;
                break;
        }
        $data['nama_mahasiswa'] = $mhs['nama_mhs'];
        $data['jenis_kelamin'] = ($mhs['kelamin_mhs'] == 'Perempuan') ? 'P' : 'L';
        $data['tempat_lahir'] = ($mhs['tempat_lahir']);
        $data['tanggal_lahir'] = $mhs['tgl_lahir'];
        $data['id_agama'] = $agama;
        $data['nik'] = $mhs['nik'];
        $data['nisn'] = $mhs['nisn'];
        $data['kewarganegaraan'] = 'ID';
        $data['jalan'] = $mhs['jalan'];
        $data['rt'] = $mhs['rt'];
        $data['rw'] = $mhs['rw'];
        $data['dusun'] = $mhs['kelurahan'];
        $data['kelurahan'] = $mhs['kelurahan'];
        $data['id_wilayah'] = $wilayah;
        $data['handphone'] = $mhs['telepon_mhs'];
        $data['email'] = $mhs['email_mhs'];
        $data['penerima_kps'] = '0';
        $data['nama_ibu_kandung'] = $mhs['ibu_kandung'];
        $data['id_kebutuhan_khusus_mahasiswa'] = '0';
        $data['id_kebutuhan_khusus_ayah'] = '0';
        $data['id_kebutuhan_khusus_ibu'] = '0';

        $this->load->library(array('feeder'));
        //Insert Biodata
        $rs = $this->feeder->post('InsertBiodataMahasiswa', $data);
        if(!$rs['status']) {
           jsonResponse(array('data' => null, 'status' => false, 'msg' => $rs['msg']));
        }
        if(count($rs['data']) < 1) {
            jsonResponse(array('data' => null, 'status' => false, 'msg' => 'Data gagal tersimpan'));
        }
        if(empty($rs['data']['id_mahasiswa'])) {
            jsonResponse(array('data' => null, 'status' => false, 'msg' => 'Data gagal tersimpan. ID Biodata Mahasiswa tidak ditemukan'));
        }
        //Update MHS
        $this->m_mhs->update($id, array('id_bio' => $rs['data']['id_mahasiswa'],
            'status_mhs' => 'VALID',
            'update_mhs' => date('Y-m-d H:i:s'),
            'log_mhs' => $this->sessionname . ' insert biodata'));
        jsonResponse(array('data' => $rs['data'], 'status' => true, 'msg' => 'Data berhasil tersimpan'));  
    }
    function _insert_nim() {
        if(!$this->_validation($this->rules_update,'ajax')) {
            jsonResponse(array('status' => FALSE, 'msg' => validation_errors()));
        }
        $id = decode($this->input->post('mid'));
        $prodi = decode($this->input->post('prodi'));
        $nim = $this->input->post('nim');
        $tahun = $this->input->post('tahun');
        
        $mhs = $this->m_mhs->getId($id);
        if(empty($mhs)) {
            jsonResponse(array('data' => array(),'status' => FALSE, 'msg' => 'Tidak ada data mahasiswa'));
        }
        $cek_nim = $this->m_mhs->getId(array('nim' => $nim));
        if(!is_null($cek_nim)){
            if($cek_nim['id_mhs'] != $id){
                jsonResponse(array('data' => $cek_nim,'status' => FALSE, 'msg' => 'NIM sudah terpakai di data PMB'));    
            }
        }       
        $this->load->library(array('feeder'));
        //Cek NIM
        $check = $this->feeder->get('GetListMahasiswa', array('limit' => 2, 'filter' => "nim='{$nim}'"));
        if(!$check['status']) {
            jsonResponse(array('data' => null,'status' => false, 'msg' => $check['msg']));
        }
        if(count($check['data']) > 0) {
            jsonResponse(array('data' => $check['data'][0], 'status' => false, 'msg' => 'NIM sudah terpakai di Feeder PDDikti'));
        }
        
        //Insert Riwayat
        $akm['id_mahasiswa'] = $mhs['id_bio'];
        $akm['nim'] = $nim;
        $akm['id_jenis_daftar'] = 1;
        $akm['id_jalur_daftar'] = 12;
        $akm['id_periode_masuk'] = $tahun . '1';
        $akm['tanggal_daftar'] = $tahun . '-09-01';
        $akm['id_perguruan_tinggi'] = 'aa90e1dd-4905-440c-93c3-68753ef9061e';
        $akm['id_prodi'] = $prodi;
        $akm['id_pembiayaan'] = 1;
        $akm['biaya_masuk'] = 800000;

        $rs = $this->feeder->post('InsertRiwayatPendidikanMahasiswa', $akm);
        if(!$rs['status']) {
           jsonResponse(array('data' => null, 'status' => false, 'msg' => $rs['msg']));
        }
        if(count($rs['data']) < 1) {
            jsonResponse(array('data' => null, 'status' => false, 'msg' => 'Data gagal tersimpan'));
        }
        if(empty($rs['data']['id_registrasi_mahasiswa'])) {
            jsonResponse(array('data' => null, 'status' => false, 'msg' => 'Data gagal tersimpan. ID Riwayat Pendidikan tidak ditemukan : '.json_encode($rs['data'])));
        }
        //Update MHS
        $this->m_mhs->update($id, array('id_reg' => $rs['data']['id_registrasi_mahasiswa'],
            'prodi_id' => $prodi, 'nim' => $nim, 'angkatan' => $tahun,
            'status_mhs' => 'AKTIF',
            'update_mhs' => date('Y-m-d H:i:s'),
            'log_mhs' => $this->sessionname . ' insert riwayat pendidikan'));
        jsonResponse(array('data' => $rs['data'], 'status' => true, 'msg' => 'Data berhasil tersimpan'));
    }
    function _delete_nim() {
        if(!$this->_validation($this->rules_update,'ajax')) {
            jsonResponse(array('status' => FALSE, 'msg' => validation_errors()));
        }
        $id = decode($this->input->post('mid'));
        
        $mhs = $this->m_mhs->getId($id);
        if(empty($mhs)) {
            jsonResponse(array('data' => array(),'status' => FALSE, 'msg' => 'Tidak ada data mahasiswa'));
        }
        if(empty($mhs['id_reg'])) {
            jsonResponse(array('data' => array(),'status' => FALSE, 'msg' => 'Mahasiswa tidak terdaftar di Program Studi manapun'));
        }
        $this->load->library(array('feeder'));
        $check = $this->feeder->get('GetListMahasiswa', array('limit' => 1, 'filter' => "id_registrasi_mahasiswa='{$mhs['id_reg']}'"));
        if(!$check['status']) {
            jsonResponse(array('data' => NULL, 'status' => false, 'msg' => $check['msg']));
        }
        if(count($check['data']) < 1) {
            jsonResponse(array('data' => NULL, 'status' => false, 'msg' => 'Data tidak ditemukan'));
        }
        if(strtotime($mhs['tgl_lahir']) != strtotime($check['data'][0]['tanggal_lahir'])){
            jsonResponse(array('data' => $check['data'][0], 'status' => false, 'msg' => 'Data PMB tidak sesuai dengan data PDDikti'));
        }
       
        $rs = $this->feeder->delete('DeleteRiwayatPendidikanMahasiswa', array('id_registrasi_mahasiswa' => $mhs['id_reg']));
        if(!$rs['status']) {
           jsonResponse(array('data' => null, 'status' => false, 'msg' => $rs['msg']));
        }
        //Update MHS
        $this->m_mhs->update($id, array('id_reg' => null, 'status_mhs' => 'VALID',
            'update_mhs' => date('Y-m-d H:i:s'),
            'log_mhs' => $this->sessionname . ' hapus riwayat pendidikan'));
        jsonResponse(array('data' => $rs['data'], 'status' => true, 'msg' => 'Data berhasil dihapus'));
    }
    function export() {
        if (!$this->_validation($this->rules_export)) {
            redirect($this->module);
        }
        $where['m.angkatan'] = $this->input->post('tahun');
        $where['m.status_mhs'] = $this->input->post('status');

        $prodi = decode($this->input->post('prodi'));
        if ($prodi != '') {
            $where['m.prodi_id'] = $prodi;
        }
        $jalur = $this->input->post('jalur');
        if ($jalur != '') {
            $where['m.jalur_mhs'] = $jalur;
        }
        $list = $this->m_mhs->getAll($where, 'asc');
        if ($list['rows'] < 1) {
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Tidak ada data Mahasiswa pada pilihan anda'));
            redirect($this->module);
        }
        
        $filename = url_title('MABA ' . $where['m.status_mhs'] .' '. $where['m.angkatan'] .' '. format_date(date('Y-m-d H:i:s'),1), '-', true) . '.xls';
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($where['m.angkatan']. '-' . $where['m.status_mhs']);

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(30);
        $sheet->getColumnDimension('E')->setWidth(20);

        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(30);

        $sheet->getColumnDimension('K')->setWidth(20);
        $sheet->getColumnDimension('L')->setWidth(10);
        $sheet->getColumnDimension('M')->setWidth(50);
        $sheet->getColumnDimension('N')->setWidth(50);
        $sheet->getColumnDimension('O')->setWidth(20);

        $sheet->getColumnDimension('P')->setWidth(30);
        $sheet->getColumnDimension('Q')->setWidth(20);
        $sheet->getColumnDimension('R')->setWidth(40);
        $sheet->getColumnDimension('S')->setWidth(20);
        $sheet->getColumnDimension('T')->setWidth(50);
        //16 Field
        $fields = array('No', 'Kode Registrasi', 'Jalur Pendaftaran', 'Program Studi', 'NIM', 
            'NIK', 'Nama Lengkap', 'Tempat Lahir', 'Tanggal Lahir', 'Ibu Kandung',
            'Jenis Kelamin', 'Agama', 'Alamat Sorong', 'Alamat Asal', 'Telepon', 
            'Email', 'NISN', 'Asal Sekolah', 'NPSN', 'Opsi Program Studi');
        $col = 1;
        foreach ($fields as $field) {
            $sheet->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        $no = 1;
        $row = 2;
        foreach ($list['data'] as $data) {
            $alamat_asal = 'Jln. '. $data['jalan'].' RT '.$data['rt'].' RW '.$data['rw'].' Kelurahan '.$data['kelurahan'];
            switch ($data['agama']) {
                case '':
                    $agama = null;
                    break;
                case 'Islam':
                    $agama = 1;
                    break;
                case 'Kristen':
                    $agama = 2;
                    break;
                case 'Katolik':
                    $agama = 3;
                    break;
                case 'Hindu':
                    $agama = 4;
                    break;
                case 'Budha':
                    $agama = 5;
                    break;
                case 'Konghucu':
                    $agama = 6;
                    break;
                default:
                    $agama = 99;
                    break;
            }

            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $data['kode_reg']);
            $sheet->setCellValue('C' . $row, $data['jalur_mhs']);
            $sheet->setCellValueExplicit('D' . $row, is_null($data['nama_prodi']) ? '' : $data['nama_prodi'], 's');
            $sheet->setCellValueExplicit('E' . $row, is_null($data['nim']) ? '' : $data['nim'], 's');
            
            $sheet->setCellValueExplicit('F' . $row, $data['nik'], 's');
            $sheet->setCellValue('G' . $row, $data['nama_mhs']);
            $sheet->setCellValue('H' . $row, $data['tempat_lahir']);
            $sheet->setCellValue('I' . $row, $data['tgl_lahir']);
            $sheet->setCellValue('J' . $row, $data['ibu_kandung']);

            $sheet->setCellValue('K' . $row, $data['kelamin_mhs']);
            $sheet->setCellValue('L' . $row, $data['agama']);
            $sheet->setCellValue('M' . $row, $data['alamat_mhs']);
            $sheet->setCellValue('N' . $row, $alamat_asal);
            $sheet->setCellValueExplicit('O' . $row, $data['telepon_mhs'], 's');
            
            $sheet->setCellValue('P' . $row, $data['email_mhs']);
            $sheet->setCellValueExplicit('Q' . $row, $data['nisn'], 's');
            $sheet->setCellValue('R' . $row, $data['sekolah']);
            $sheet->setCellValueExplicit('S' . $row, $data['npsn'], 's');
            $sheet->setCellValue('T' . $row, $data['opsi_prodi']);
            
            $no++;
            $row++;
        }
        $tableStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => array('horizontal' => 'center', 'vertical' => 'center', 'wrapText' => true)
        ];
        $boldStyle = array(
            'font' => array('size' => 12, 'bold' => true, 'color' => array('rgb' => '000000'))
        );
        $row--;
        $sheet->getStyle('A1')->applyFromArray($tableStyle);
        $sheet->getStyle('A1:T' . $row)->applyFromArray($tableStyle);
        $sheet->getStyle('A1:T1')->applyFromArray($boldStyle);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    private $rules_edit = array(
        array(
            'field' => 'atribut',
            'label' => 'Atribut Kampus',
            'rules' => 'required|trim|xss_clean'
        )
    );
    private $rules_insert = array(
        array(
            'field' => 'id',
            'label' => 'Mahasiswa',
            'rules' => 'required|trim|xss_clean'
        ), array(
            'field' => 'wilayah',
            'label' => 'Kecamatan',
            'rules' => 'required|trim|xss_clean|is_natural'
        )
    );
    private $rules_update = array(
        array(
            'field' => 'mid',
            'label' => 'Mahasiswa',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'nim',
            'label' => 'NIM',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'prodi',
            'label' => 'Program Studi',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'tahun',
            'label' => 'Angkatan',
            'rules' => 'required|trim|xss_clean|is_natural'
        )
    );
    private $rules_export = array(
        array(
            'field' => 'status',
            'label' => 'Status',
            'rules' => 'required|trim|xss_clean'
        ),
        array(
            'field' => 'tahun',
            'label' => 'Angkatan',
            'rules' => 'required|trim|xss_clean'
        )
    );
}
