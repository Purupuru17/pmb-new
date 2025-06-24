<?php

use Dompdf\Dompdf;
use Dompdf\Options;

class Fungsi{
    
    protected $ci;
    
    function __construct() {
        $this->ci = get_instance();
    }
    function PdfGenerate($html, $filename='', $attach = 0 ,$paper = 'A4', $orientation = 'portrait', $stream = TRUE) {
        $tmp = sys_get_temp_dir();
        if (ob_get_level()) {
            ob_end_clean();
        }
        $options = new Options();
        $options->set('logOutputFile', '');
        $options->set('isRemoteEnabled', TRUE);
        $options->set('isFontSubsettingEnabled', TRUE);
        $options->set('defaultMediaType', 'all');
        
        $options->set('fontDir', $tmp);
        $options->set('fontCache', $tmp);
        $options->set('tempDir', $tmp);
        $options->set('chroot', $tmp);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();
        if ($stream) {
            $dompdf->stream($filename.".pdf", array("Attachment" => $attach));
        } else {
            return $dompdf->output();
        }
    }
    function SetPaging($url = NULL, $rows = NULL, $limit = NULL) {
        $this->ci->load->library(array('pagination'));

        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        
        $config['first_tag_open'] = '<li>';
        $config['first_link'] = '<<';
        $config['first_tag_close'] = '</li>';
        
        $config['last_tag_open'] = '<li>';
        $config['last_link'] = '>>';
        $config['last_tag_close'] = '</li>';
       
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        
        $config['cur_tag_open'] = '<li class="active"><span>';
        $config['cur_tag_close'] = '</span></li>';
        $config['num_tag_open'] = '<li><span>';
        $config['num_tag_close'] = '</span></li>';
        
        $config['attributes'] = array('class' => 'bigger-120');
        $config['base_url'] = $url;
        $config['total_rows'] = ($rows > 0) ? $rows : 0;
        $config['per_page'] = ($limit > 0) ? $limit : 0;
        $config['page_query_string'] = TRUE;
        $config['use_page_numbers'] = TRUE;
        $config['query_string_segment'] = 'page';
        
        $this->ci->pagination->initialize($config);
        return $this->ci->pagination->create_links();
    }
    function SendEmail($type, $from_email, $data, $to_email, $subject = '', $reply = '') {
        $from = array();
        $config = array();
        
        switch($from_email) {
            case 'admin' :
                $from = array('user' => 'admin@koputoko.com', 'pass' => '7c7FwNELKkjs');
                break;
            case 'cs' :
                $from = array('user' => 'cs@koputoko.com', 'pass' => '41Nkx86QEfyG');
                break;
            case 'no':
                $from = array('user' => 'no-reply@koputoko.com', 'pass' => 'GGH97lsGLmMhIl');
                break;
            default:
                $from = array('user' => 'galihbayu17@gmail.com', 'pass' => 'mrkcydtbahiggipj');
                break;
        }
        $config['protocol'] = "smtp";
        $config['smtp_host'] = "smtp.gmail.com";
        $config['smtp_crypto'] = "ssl";
        $config['smtp_port'] = "465";
        $config['smtp_user'] = $from['user'];
        $config['smtp_pass'] = $from['pass'];
        $config['charset'] = "utf-8";
        $config['mailtype'] = "html";
        $config['newline'] = "\r\n";
        $config['crlf'] = "\r\n";
        $config['wordwrap'] = TRUE;
        
        $this->ci->load->library('email');
        $this->ci->email->initialize($config);
        $this->ci->email->from($from['user'], APP_NAME);
        if(!empty($reply)){
            $this->ci->email->reply_to($reply);
        }
        $this->ci->email->to($to_email);
        $this->ci->email->subject($subject);
        $this->ci->email->message($this->ci->load->view('email/' . $type . '-html', $data, TRUE));
        $this->ci->email->set_alt_message($this->ci->load->view('email/' . $type . '-txt', $data, TRUE));
        
        $send = ($this->ci->email->send()) ? TRUE : FALSE;
        return [
            'rs' => $send,
            'msg' => 'Email belum terkirim. Silahkan coba lagi'//$this->email->print_debugger()
        ];
    }
}