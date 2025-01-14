<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Seleksi_do extends KZ_Controller {
    
    private $module = 'master/seleksi';
    private $module_do = 'master/seleksi_do';
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_mhs'));
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
        $id = decode($this->input->post('id'));
        
        $mhs = $this->m_mhs->getId($id);
        if(empty($mhs)) {
            jsonResponse(array('status' => FALSE, 'msg' => 'Data mahasiswa tidak ditemukan'));
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
        $data['id_wilayah'] = $mhs['kecamatan'];
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
            'status_mhs' => 'VALID', 'update_mhs' => date('Y-m-d H:i:s'),
            'log_mhs' => $this->sessionname . ' insert Biodata'));
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
            'prodi_id' => $prodi, 'nim' => $nim, 
            'angkatan' => $tahun, 'status_mhs' => 'AKTIF',
            'update_mhs' => date('Y-m-d H:i:s'), 'log_mhs' => $this->sessionname . ' insert Riwayat Pendidikan'));
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
}
