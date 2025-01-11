<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Prodi extends KZ_Controller {
    
    private $module = 'master/prodi';
    private $module_do = 'master/prodi_do';  
    
    function __construct() {
        parent::__construct();
        $this->load->model(array('m_mhs','m_prodi'));
    }
    function index() {
        $this->data['list'] = $this->m_prodi->getAll();
        
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Program Studi','List Data');
        $this->data['breadcrumb'] = array( 
            array('title'=>'Mahasiswa', 'url'=>'#'),
            array('title'=>$this->uri->segment(2), 'url'=> '')
        );
        $this->load_view('master/prodi/v_index', $this->data);
    }
    function add($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $prodi = $this->m_prodi->getId(decode($id));
        $this->data['daftar'] = $this->m_mhs->getAll(array(
            'm.prodi_id' => decode($id),
            'm.nim' => null,
            'm.status_mhs !=' => 'Tidak Aktif'
        ));
        
        $this->data['status'] = load_array('status');
        $this->data['detail'] = $prodi;
        $this->data['module'] = $this->module;
        $this->data['title'] = array($prodi['nama_prodi'], 'Pembuatan NIM');
        $this->data['breadcrumb'] = array( 
            array('title'=>'Mahasiswa', 'url'=>'#'),
            array('title'=>'Program Studi', 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('master/prodi/v_add', $this->data);
    }
    function detail($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        $prodi = $this->m_prodi->getId(decode($id));
        $this->data['daftar'] = $this->m_mhs->getAll(array('m.prodi_id' => decode($id)));
        
        $this->data['status'] = load_array('status');
        $this->data['detail'] = $prodi;
        $this->data['module'] = $this->module;
        $this->data['title'] = array('Program Studi', $prodi['nama_prodi']);
        $this->data['breadcrumb'] = array( 
            array('title'=>'Mahasiswa', 'url'=>'#'),
            array('title'=>'Program Studi', 'url'=> site_url($this->module)),
            array('title'=>$this->data['title'][1], 'url'=>'')
        );
        $this->load_view('master/prodi/v_detail', $this->data);
    }
    function export($id = NULL) {
        if(empty(decode($id))){
            redirect($this->module);
        }
        if(!$this->_validation($this->rules)){
            redirect($this->module.'/detail/'.$id);
        }
        
        $data['m.prodi_id'] = decode($id); 
        $data['m.status_mhs'] = $this->input->post('status');
        $tahun = $data['m.angkatan'] = $this->input->post('angkatan');
        
        $prodi = $this->m_prodi->getId(decode($id));
        $mhs = $this->m_mhs->getAll($data);
        if ($mhs['rows'] < 1) {
            $this->session->set_flashdata('notif', notif('warning', 'Peringatan', 'Tidak ada data Mahasiswa pada pilihan anda'));
            redirect($this->module.'/detail/'.$id);
        } 
        
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $objPHPExcel->getActiveSheet();
        
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $prodi['nama_prodi'].' '.$prodi['fakultas']);
        $sheet->setTitle('DATA MAHASISWA BARU');
        $sheet->mergeCells('A1:F2');
        
        $sheet->getColumnDimension('A')->setWidth(5);  
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(20);
        $sheet->getColumnDimension('D')->setWidth(20);
        $sheet->getColumnDimension('E')->setWidth(10);
        
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(20);
        
        $sheet->getColumnDimension('H')->setWidth(20);
        $sheet->getColumnDimension('I')->setWidth(20);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(20);
        $sheet->getColumnDimension('L')->setWidth(20);
        $sheet->getColumnDimension('M')->setWidth(30);
        
        $fields = array('No','NIM','Nama','NIK',
            'Angkatan','Status','Tanggal Daftar','Tempat Lahir','Tanggal Lahir',
            'Jenis Kelamin', 'Agama', 'Telepon', 'Alamat'
        );//14 Field
        $col = 1;
        foreach ($fields as $field) {
            $sheet->setCellValueByColumnAndRow($col, 5, $field);
            $col++;
        }
        
        $no = 1;
        $row = 6;
        foreach ($mhs['data'] as $data) {
            $sheet->setCellValue('A'.$row, $no);
            $sheet->setCellValue('B'.$row, is_null($data['nim']) ? '' : $data['nim']);
            $sheet->setCellValue('C'.$row, $data['nama_mhs']);
            $sheet->setCellValue('D'.$row, $data['nik']);
            $sheet->setCellValue('E'.$row, is_null($data['angkatan']) ? '' : $data['angkatan']);
            
            $sheet->setCellValue('F'.$row, $data['status_mhs']);
            $sheet->setCellValue('G'.$row, format_date($data['tgl_daftar'],1));
            
            $sheet->setCellValue('H'.$row, $data['tempat_lahir']);
            $sheet->setCellValue('I'.$row, format_date($data['tgl_lahir'],1));
            $sheet->setCellValue('J'.$row, $data['kelamin_mhs']);
            $sheet->setCellValue('K'.$row, $data['agama']);
            $sheet->setCellValue('L'.$row, $data['telepon_mhs']);
            $sheet->setCellValue('M'.$row, $data['alamat_mhs']);
            
            $no++;
            $row++;
        }      
        // Redirect output to a client’s web browser (Xlsx)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$prodi['nama_prodi'].' '.$tahun.'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Xlsx');
        $writer->save('php://output');
        exit();
    }
    private $rules = array(
        array(
            'field' => 'status',
            'label' => 'Status Mahasiswa',
            'rules' => 'required|trim|xss_clean'
        ),array(
            'field' => 'angkatan',
            'label' => 'Angkatan',
            'rules' => 'required|trim|xss_clean'
        )
    );
}
