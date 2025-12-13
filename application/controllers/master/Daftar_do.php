<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Daftar_do extends KZ_Controller {
    
    private $module = 'master/daftar';
    private $module_do = 'master/daftar_do';
            
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_mhs'));
    }
    function add() {
        if(!$this->_validation($this->rules)){
            redirect($this->module.'/add');
        }
        $data['prodi_id'] = decode($this->input->post('opsi1'));
        $prodi[] = $this->input->post('opsi2');
        $prodi[] = $this->input->post('opsi3');
        $data['angkatan'] = $this->input->post('tahun');
        $data['jalur_mhs'] = $this->input->post('jalur');
        $data['atribut_mhs'] = strtoupper($this->input->post('atribut'));
        
        $data['nisn'] = $this->input->post('nisn');
        $data['sekolah'] = strtoupper($this->input->post('sekolah'));
        $data['npsn'] = $this->input->post('npsn');
        
        $data['nik'] = $this->input->post('nik');
        $data['nama_mhs'] = strtoupper($this->input->post('nama'));
        $data['ibu_kandung'] = strtoupper($this->input->post('ibu'));
        $data['tempat_lahir'] = ucwords(strtolower($this->input->post('tempat')));
        $data['tgl_lahir'] = $this->input->post('lahir');
        $data['kelamin_mhs'] = $this->input->post('kelamin');
        $data['agama'] = $this->input->post('agama');
        $data['telepon_mhs'] = $this->input->post('telepon');
        $data['email_mhs'] = strtolower($this->input->post('email'));
        $data['alamat_mhs'] = ucwords(strtolower($this->input->post('alamat')));
        
        $data['jalan'] = ucwords(strtolower($this->input->post('jalan')));
        $data['rt'] = $this->input->post('rt');
        $data['rw'] = $this->input->post('rw');
        $data['kelurahan'] = ucwords(strtolower($this->input->post('lurah')));
        $data['kecamatan'] = $this->input->post('camat');
        $data['kabupaten'] = $this->input->post('bupati');

        $data['opsi_prodi'] = implode("|", $prodi);
        $data['id_mhs'] = random_string('unique');
        $data['kode_reg'] = $this->m_mhs->getNomor('UVMD');
        $data['status_mhs'] = 'PENDAFTARAN';
        $data['kip_mhs'] = 'PENDING';
        $data['tgl_daftar'] = date('Y-m-d H:i:s');
        $data['log_mhs'] = $this->sessionname.' menambahkan data';
        $data['set_by'] = '0';
        
        $user['id_user'] = $data['id_mhs'];
        $user['id_group'] = 4;
        $user['fullname'] = $data['nama_mhs'];
        $user['username'] = $data['kode_reg'];
        $user['email'] = $data['email_mhs'];
        $user['password'] = password_hash($data['kode_reg'], PASSWORD_DEFAULT);
        $user['status_user'] = '1';
        $user['log_user'] = $data['log_mhs'];
        $user['ip_user'] = ip_agent();
        $user['buat_user'] = $data['tgl_daftar'];

        $is_nik = $this->m_mhs->isExist(array('nik' => $data['nik']));
        if($is_nik > 0){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data NIK sudah tersimpan sebelumnya'));
            redirect($this->module.'/add');
        }
        $is_kode = $this->m_mhs->isExist(array('kode_reg' => $data['kode_reg']));
        if($is_kode > 0){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Kode Registrasi sudah tersimpan sebelumnya'));
            redirect($this->module.'/add');
        }
        $this->db->trans_start();
        //insert
        $this->db->insert('yk_user', $user);
        $this->db->insert('m_mhs', $data);
        $this->db->insert('tmp_mhs', array('user_id' => $user['id_user'], 'mhs_id' => $data['id_mhs']));
        //complete
        $this->db->trans_complete();
        if ($this->db->trans_status()) {
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
        if(!$this->_validation($this->rules_edit)){
            redirect($this->module.'/edit/'.$id);
        }
        $data['nim'] = $this->input->post('nim');
        $data['prodi_id'] = decode($this->input->post('opsi1'));
        $prodi[] = $this->input->post('opsi2');
        $prodi[] = $this->input->post('opsi3');
        
        $data['opsi_prodi'] = implode("|", $prodi); 
        $data['status_mhs'] = $this->input->post('status');
        $data['jalur_mhs'] = $this->input->post('jalur');
        $data['angkatan'] = $this->input->post('tahun');
        $data['atribut_mhs'] = strtoupper($this->input->post('atribut'));
        $data['kip_mhs'] = $this->input->post('kip');
        
        $data['nisn'] = $this->input->post('nisn');
        $data['sekolah'] = strtoupper($this->input->post('sekolah'));
        $data['npsn'] = $this->input->post('npsn');
        
        $data['nik'] = $this->input->post('nik');
        $data['nama_mhs'] = strtoupper($this->input->post('nama'));
        $data['ibu_kandung'] = strtoupper($this->input->post('ibu'));
        $data['tempat_lahir'] = ucwords(strtolower($this->input->post('tempat')));
        $data['tgl_lahir'] = $this->input->post('lahir');
        $data['kelamin_mhs'] = $this->input->post('kelamin');
        $data['agama'] = $this->input->post('agama');
        $data['telepon_mhs'] = $this->input->post('telepon');
        $data['email_mhs'] = strtolower($this->input->post('email'));
        $data['alamat_mhs'] = ucwords(strtolower($this->input->post('alamat')));
        
        $data['jalan'] = ucwords(strtolower($this->input->post('jalan')));
        $data['rt'] = $this->input->post('rt');
        $data['rw'] = $this->input->post('rw');
        $data['kelurahan'] = ucwords(strtolower($this->input->post('lurah')));
        $data['kecamatan'] = $this->input->post('camat');
        $data['kabupaten'] = $this->input->post('bupati');
        
        $data['update_mhs'] = date('Y-m-d H:i:s');
        $data['log_mhs'] = $this->sessionname.' mengubah data';
        
        if($this->sessiongroup !== '1' && $data['status_mhs'] == 'AKTIF'){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Anda tidak memiliki akses untuk meng-AKTIF-kan mahasiswa'));
            redirect($this->module.'/edit/'.$id);
        }
        $cek = $this->m_mhs->getId(array('nisn' => $data['nisn']));
        if(!is_null($cek) && ($id != encode($cek['id_mhs']))){
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data NISN sudah tersimpan atas nama : ' . $cek['nama_mhs']));
            redirect($this->module.'/edit/'.$id);
        }
        $result = $this->m_mhs->update(decode($id), $data);
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
        if(!$this->_validation($this->rules_berkas)){
            redirect($this->module.'/detail/'.$id);
        }
        $this->load->model(array('m_berkas'));
        $berkas_id = decode($this->input->post('berkas'));
        
        $data['status_berkas'] = $this->input->post('status');
        $data['update_berkas'] = date('Y-m-d H:i:s');
        $data['log_berkas'] = $this->sessionname.' mengubah data';
            
        $result = $this->m_berkas->update($berkas_id, $data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil diubah'));
            redirect($this->module.'/detail/'.$id);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal diubah'));
            redirect($this->module.'/detail/'.$id);
        }
    }
    function export() {
        if (!$this->_validation($this->rules_export)) {
            redirect($this->module);
        }
        $prodi = decode($this->input->post('prodi'));
        $tahun = $this->input->post('tahun');
        $status = $this->input->post('status');
        $jalur = $this->input->post('jalur');
        $kip = $this->input->post('kip');
        
        $where['m.angkatan'] = $tahun;
        $where['m.status_mhs'] = $status;

        if ($prodi != '') {
            $where['m.prodi_id'] = $prodi;
        }
        if ($jalur != '') {
            $where['m.jalur_mhs'] = $jalur;
        }
        if ($kip != '') {
            $where['m.kip_mhs'] = $kip;
        }
        $list = $this->db->order_by('m.tgl_daftar', 'ASC')
            ->join('m_prodi p', 'm.prodi_id = p.id_prodi', 'left')
            ->join('m_ortu o', 'm.id_mhs = o.mhs_id', 'left')
            ->group_by('m.id_mhs')
            ->get_where('m_mhs m', $where);
        
        if ($list->num_rows() < 1) {
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Data Mahasiswa tidak ditemukan'));
            redirect($this->module);
        }
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($where['m.angkatan']. '-' . $where['m.status_mhs']);

        $fields = array('No', 'Kode Registrasi', 'Jalur Pendaftaran', 'Program Studi', 'NIM', 
            'NIK', 'Nama Lengkap', 'Tempat Lahir', 'Tanggal Lahir', 'Ibu Kandung',
            'Jenis Kelamin', 'Agama', 'Telepon', 'Email', 'Alamat Sorong', 'Alamat Asal',
            'Kecamatan', 'Kabupaten', 'NISN', 'Asal Sekolah', 'NPSN', 'Atribut', 'KIP',
            'Ayah/Suami', 'NIK', 'Pendidikan', 'Pekerjaan', 'Penghasilan', 'Ibu/Istri', 'NIK', 'Pekerjaan', 'Telepon', 'Alamat');
        $col = 1;
        foreach ($fields as $field) {
            $sheet->setCellValueByColumnAndRow($col, 1, $field);
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
            $col++;
        }
        $no = 1;
        $row = 2;
        $filename = url_title('MABA ' . $where['m.status_mhs'] .' '. $where['m.angkatan'] 
            .' '. format_date(date('Y-m-d H:i:s'),1), '-', true) . '.xls';
        foreach ($list->result_array() as $data) {
            
            $alamat_asal = 'Jln. '. $data['jalan'].' RT '.$data['rt'].' RW '.$data['rw'].' Kelurahan '.$data['kelurahan'];
            $kecamatan = $this->db->get_where('m_wilayah', ['id_wilayah' => $data['kecamatan']])->row_array();
            $kabupaten = $this->db->get_where('m_wilayah', ['id_wilayah' => $data['kabupaten']])->row_array();
            
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
            $sheet->setCellValueExplicit('M' . $row, $data['telepon_mhs'], 's');
            $sheet->setCellValue('N' . $row, $data['email_mhs']);
            $sheet->setCellValue('O' . $row, $data['alamat_mhs']);
            $sheet->setCellValue('P' . $row, $alamat_asal);
            $sheet->setCellValue('Q' . $row, element('nama_wilayah', $kecamatan));
            $sheet->setCellValue('R' . $row, element('nama_wilayah', $kabupaten));
            
            $sheet->setCellValueExplicit('S' . $row, $data['nisn'], 's');
            $sheet->setCellValue('T' . $row, $data['sekolah']);
            $sheet->setCellValueExplicit('U' . $row, $data['npsn'], 's');
            $sheet->setCellValue('V' . $row, $data['atribut_mhs']);
            $sheet->setCellValue('W' . $row, $data['kip_mhs']);
            
            $sheet->setCellValue('X' . $row, $data['nama_ayah']);
            $sheet->setCellValueExplicit('Y' . $row, $data['nik_ayah'], 's');
            $sheet->setCellValue('Z' . $row, $data['didik_ayah']);
            $sheet->setCellValue('AA' . $row, $data['kerja_ayah']);
            $sheet->setCellValue('AB' . $row, $data['hasil_ayah']);
            $sheet->setCellValue('AC' . $row, $data['nama_ibu']);
            $sheet->setCellValueExplicit('AD' . $row, $data['nik_ibu'], 's');
            $sheet->setCellValue('AE' . $row, $data['kerja_ibu']);
            $sheet->setCellValueExplicit('AF' . $row, $data['telepon_ortu'], 's');
            $sheet->setCellValue('AG' . $row, $data['alamat_ortu']);
            
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
        $sheet->getStyle('A1:AG' . $row)->applyFromArray($tableStyle);
        $sheet->getStyle('A1:AG1')->applyFromArray($boldStyle);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    //function
    function _valid_date($tgl) {
        list($yyyy,$mm,$dd) = explode('-',$tgl);
        $now = intval(date('Y'));
        $min = $now - intval($yyyy);
        
        if(!checkdate($mm,$dd,$yyyy)) {
            $this->form_validation->set_message("_valid_date", "Kolom {field} tidak sesuai format.");
            return FALSE;
        }else if($min < 15 || $min > 80) {
            $this->form_validation->set_message("_valid_date", "Kolom {field} tidak sesuai usia anda. Min : 15 Tahun, Maks : 80 Tahun");
            return FALSE;
        }else {
            return TRUE;
        }
    }
    function _valid_email($address){
        if(!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $address)) {
            $this->form_validation->set_message("_valid_email", "Kolom {field} anda tidak sesuai format.");
            return FALSE;
        }else{
            return TRUE;
        }
    }
    function _valid_zero($str) {
        if(strpos($str, '000000') !== false) {
            $this->form_validation->set_message("_valid_zero", "Format {field} tidak sesuai. Mohon input data sebenarnya");
            return FALSE;
        }else{
            return TRUE;
        }
    }
    private $rules_berkas = array(
        array(
            'field' => 'berkas',
            'label' => 'ID Berkas',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'status',
            'label' => 'Status Berkas',
            'rules' => 'required|trim|xss_clean'
        )
    );
    private $rules_edit = array(
        array(
            'field' => 'nisn',
            'label' => 'NISN',
            'rules' => 'required|callback__valid_zero'
        ),array(
            'field' => 'sekolah',
            'label' => 'Asal Sekolah',
            'rules' => 'required'
        ),array(
            'field' => 'npsn',
            'label' => 'NPSN Sekolah',
            'rules' => 'required|callback__valid_zero'
        )
    );
    private $rules = array(
        array(
            'field' => 'opsi1',
            'label' => 'Pilihan Pertama',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'opsi2',
            'label' => 'Pilihan Kedua',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'opsi3',
            'label' => 'Pilihan Ketiga',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'status',
            'label' => 'Status Mahasiswa',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'jalur',
            'label' => 'Jalur Pendaftaran',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'tahun',
            'label' => 'Periode Masuk',
            'rules' => 'required|trim|xss_clean'
        ),
        // Data Pendidikan
        array(
            'field' => 'nisn',
            'label' => 'NISN',
            'rules' => 'trim|xss_clean|is_natural|min_length[10]|max_length[10]|callback__valid_zero'
        ),array(
            'field' => 'sekolah',
            'label' => 'Asal Sekolah',
            'rules' => 'trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'npsn',
            'label' => 'NPSN Sekolah',
            'rules' => 'trim|xss_clean|is_natural|min_length[8]|max_length[10]|callback__valid_zero'
        ),
        // Data Diri
        array(
            'field' => 'nik',
            'label' => 'NIK',
            'rules' => 'required|trim|xss_clean|is_natural|min_length[16]|max_length[16]'
        ),array(
            'field' => 'nama',
            'label' => 'Nama Mahasiswa',
            'rules' => 'required|trim|xss_clean|min_length[4]'
        ),array(
            'field' => 'ibu',
            'label' => 'Ibu Kandung',
            'rules' => 'required|trim|xss_clean|min_length[3]'
        ),array(
            'field' => 'tempat',
            'label' => 'Tempat Lahir',
            'rules' => 'required|trim|xss_clean|min_length[3]'
        ),array(
            'field' => 'lahir',
            'label' => 'Tanggal Lahir',
            'rules' => 'required|trim|xss_clean|callback__valid_date'
        ),array(
            'field' => 'kelamin',
            'label' => 'Jenis Kelamin',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'agama',
            'label' => 'Agama',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'telepon',
            'label' => 'Telepon',
            'rules' => 'required|trim|xss_clean|is_natural|min_length[11]|max_length[12]'
        ),array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|trim|xss_clean|callback__valid_email'
        ),array(
            'field' => 'alamat',
            'label' => 'Alamat',
            'rules' => 'required|trim|xss_clean|min_length[10]'
        ),
        
        array(
            'field' => 'jalan',
            'label' => 'Nama Jalan',
            'rules' => 'required|trim|xss_clean|min_length[5]'
        ),array(
            'field' => 'rt',
            'label' => 'RT',
            'rules' => 'required|trim|xss_clean|is_natural'
        ),array(
            'field' => 'rw',
            'label' => 'RW',
            'rules' => 'required|trim|xss_clean|is_natural'
        ),array(
            'field' => 'lurah',
            'label' => 'Kelurahan',
            'rules' => 'required|trim|xss_clean|min_length[3]'
        ),array(
            'field' => 'camat',
            'label' => 'Kecamatan',
            'rules' => 'required|trim|xss_clean|min_length[3]'
        ),array(
            'field' => 'bupati',
            'label' => 'Kabupaten',
            'rules' => 'required|trim|xss_clean|min_length[3]'
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
