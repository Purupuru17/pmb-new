<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap extends KZ_Controller {
    
    private $module = 'master/rekap';
    private $module_do = 'master/rekap_do';    
    private $url_route = array('id', 'source', 'type');
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_berkas','m_prodi'));
    }
    function index() {
        $this->data['prodi'] = $this->m_prodi->getAll();
        $this->data['berkas'] = $this->m_berkas->getUpload();
        
        $this->data['module'] = $this->module;
        $this->data['act_berkas'] = $this->module.'/edit';
        $this->data['title'] = array('Rekap','List Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>'Mahasiswa', 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('master/rekap/v_index', $this->data);
    }
    function edit() {
        if(!$this->_validation($this->rules_berkas)){
            redirect($this->module);
        }
        $berkas_id = decode($this->input->post('berkasid'));
        
        $data['status_berkas'] = $this->input->post('status');
        $data['update_berkas'] = date('Y-m-d H:i:s');
        $data['log_berkas'] = $this->sessionname.' mengubah data';
            
        $result = $this->m_berkas->update($berkas_id, $data);
        if ($result) {
            $this->session->set_flashdata('notif', notif('success', 'Informasi', 'Data berhasil diubah'));
            redirect($this->module);
        } else {
            $this->session->set_flashdata('notif', notif('danger', 'Peringatan', 'Data gagal diubah'));
            redirect($this->module);
        }
    }
    function ajax() {
        $routing_module = $this->uri->uri_to_assoc(4, $this->url_route);
        if(is_null($routing_module['type'])){
            redirect('');
        }
        if($routing_module['type'] == 'table') {
            //LIST
            if($routing_module['source'] == 'rekap') {
                $this->_table_rekap();
            }
        }
    }
    //FUNCTION
    function _table_rekap() {
        $prodi = decode($this->input->post('prodi'));
        $tahun = $this->input->post('tahun');
        $jalur = $this->input->post('jalur');
        $status = $this->input->post('status');
        $berkas = decode($this->input->post('berkas'));
        $valid = $this->input->post('valid');
        
        $where = null;
        if ($prodi != '') {
            $where['m.prodi_id'] = $prodi;
        }
        if ($tahun != '') {
            $where['m.angkatan'] = $tahun;
        }
        if ($jalur != '') {
            $where['m.jalur_mhs'] = $jalur;
        }
        if ($status != '') {
            $where['m.status_mhs'] = $status;
        }
        if ($valid != '') {
            $where['b.status_berkas'] = $valid;
        }
        if ($berkas != '') {
            $where['b.upload_id'] = $berkas;
        }
        
        $list = $this->m_berkas->get_datatables($where);
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row) {
            $no++;
            $rows = array();
            
            $hide = ($row['status_berkas'] == '1') ? 'hide' : '';
            $aksi = '<div class="action-buttons '.$hide.'">
                        <a href="#" itemid="'. encode($row['id_berkas']) .'" itemprop="'. $row['nama_upload'] .'" id="edit-btn" class="tooltip-warning btn btn-white btn-warning btn-round btn-sm" data-rel="tooltip" title="Ubah Data">
                            <span class="orange"><i class="ace-icon fa fa-pencil-square-o bigger-120"></i></span>
                        </a></div>';
            $link = '<a target="_blank" href="' . site_url('master/daftar/detail/' . encode($row['id_mhs'])) . '">
                        <strong>'. $row['nama_mhs'] .'</strong>
                    </a>';
            
            $rows[] = ctk($no);
            $rows[] = $link .'<br>#'.ctk($row['kode_reg']);
            $rows[] = $row['nama_prodi'] .'<hr class="margin-5">'.ctk($row['jalur_mhs']);
            $rows[] = st_mhs($row['status_mhs']).'<br/>'.format_date($row['update_mhs'],2);
            $rows[] = '<strong class="blue">'.ctk($row['nama_upload']).'</strong><hr class="margin-5">'.ctk($row['tipe_upload']);
            $rows[] = st_span($row['status_berkas']).'<br/>'.st_file($row['file_berkas'], 1);
            $rows[] = ($this->sessionperiode != $row['angkatan']) ? '' : $aksi;

            $data[] = $rows;
        }
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->m_berkas->count_all(),
            "recordsFiltered" => $this->m_berkas->count_filtered($where),
            "data" => $data,
        );
        jsonResponse($output);
    }
    function export() {
        if (!$this->_validation($this->rules_export)) {
            redirect($this->module);
        }
        $where['m.angkatan'] = $this->input->post('tahun');
        $where['m.status_mhs'] = $this->input->post('status');
        $where['b.upload_id'] = decode($this->input->post('berkas'));
        
        $title = $this->input->post('title');
        
        $prodi = $this->input->post('prodi');
        if ($prodi != '') {
            $where['m.prodi_id'] = $prodi;
        }
        $valid = $this->input->post('valid');
        if ($valid != '') {
            $where['b.status_berkas'] = $valid;
        }
        $list = $this->m_berkas->getExcel($where);
        if ($list['rows'] < 1) {
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Tidak ada data Mahasiswa pada pilihan anda'));
            redirect($this->module);
        }
        
        $filename = url_title('MABA ' . $where['m.status_mhs'] .' '. $where['m.angkatan'] .' '.$title.' '. format_date(date('Y-m-d H:i:s'),1), '-', true);
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle($where['m.angkatan']. '-' . $where['m.status_mhs']);

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(40);
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
        //16 Field
        $fields = array('No', 'Kode Registrasi & Berkas', 'Jalur Pendaftaran', 'Program Studi', 'NIM', 
            'NIK', 'Nama Lengkap', 'Tempat Lahir', 'Tanggal Lahir', 'Ibu Kandung',
            'Jenis Kelamin', 'Agama', 'Alamat Sorong', 'Alamat Asal', 'Telepon', 
            'Email', 'NISN', 'Asal Sekolah', 'NPSN');
        $col = 1;
        foreach ($fields as $field) {
            $sheet->setCellValueByColumnAndRow($col, 1, $field);
            $col++;
        }
        $no = 1;
        $row = 2;
        foreach ($list['data'] as $data) {
            $alamat_asal = 'Jln. '. $data['jalan'].' RT '.$data['rt'].' RW '.$data['rw'].' Kelurahan '.$data['kelurahan'];

            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $data['kode_reg'].'|'.$title);
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
        $sheet->getStyle('A1:S' . $row)->applyFromArray($tableStyle);
        $sheet->getStyle('A1:S1')->applyFromArray($boldStyle);

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename.'.xls"');
        header('Cache-Control: max-age=0');

        $writer = new PhpOffice\PhpSpreadsheet\Writer\Xls($spreadsheet);
        $writer->save('php://output');
        exit();
    }
    private $rules_berkas = array(
        array(
            'field' => 'berkasid',
            'label' => 'ID Berkas',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'status',
            'label' => 'Status Berkas',
            'rules' => 'required|trim|xss_clean'
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
        ),
        array(
            'field' => 'berkas',
            'label' => 'Jenis Berkas',
            'rules' => 'required|trim|xss_clean'
        )
    );
}
