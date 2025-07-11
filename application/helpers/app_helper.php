<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('notif')) {

    function notif($type, $title, $message) {
        $alert = '<div class="alert alert-' . $type . '">' .
                '<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>' .
                '<strong>' . $title . ' ! </strong></br>' . $message . '<br />' .
                '</div>';

        return $alert;
    }
}
if (!function_exists('load_css')) {

    function load_css(array $array) {
        foreach ($array as $uri) {
            echo '<link rel="stylesheet" type="text/css" href="' . base_url(''.$uri) . '" />';
        }
    }

}
if (!function_exists('load_js')) {

    function load_js(array $array, $async = FALSE) {
        foreach ($array as $uri) {
            if(!$async){
                echo '<script type="text/javascript"  src="' . base_url(''.$uri) . '"></script>';
            }else{
                echo '<script async type="text/javascript"  src="' . base_url(''.$uri) . '"></script>';
            }
        }
    }

}
if (!function_exists('load_file')) {

    function load_file($src, $type = NULL) {
        $default_img = empty($type) ? 'theme/img/no-img.jpg' : 'theme/img/no-avatar.png';
        if (empty($src)) {
            return base_url($default_img);
        }
        if(!is_keyword($src, ['img','upload'])){
            $CI = &get_instance();
            $CI->load->library(array('s3'));
            return $CI->s3->url($src);
        }
        $full_path = FCPATH . $src;
        if (!file_exists($full_path)) {
            return base_url($default_img);
        }
        if ($type == 'base64') {
            $ext = pathinfo($full_path, PATHINFO_EXTENSION);
            $data = file_get_contents($full_path);
            $base64 = 'data:image/' . $ext . ';base64,' . base64_encode($data);
            return $base64;
        }
        return base_url($src);
    }

}
if (!function_exists('st_file')) {

    function st_file($src, $file = NULL) {
        if(empty($src)){
            return '<i class="bigger-130 fa fa-times red"></i>';
        }
        $download = '&nbsp; | &nbsp;<a class="bigger-120" href="'. htmlspecialchars(load_file($src)) .'" target="_blank"><i class="fa fa-download"></i></a>';
        if(!is_keyword($src, ['img','upload'])){
            $status = '<i class="bigger-120 fa fa-check-square-o green"></i>';
            $status .= is_null($file) ? '' : $download;
            return $status;
        }
        if (file_exists(FCPATH . $src)) {
            $status = '<i class="bigger-120 fa fa-check green"></i>';
            $status .= is_null($file) ? '' : $download;
            return $status;
        }
        return '<i class="bigger-130 fa fa-times red"></i>';
    }
}
if (!function_exists('delete_file')) {

    function delete_file($src) {
        if(empty($src)){
            return false;
        }
        if(!is_keyword($src, ['img','upload'])){
            try {
                $CI = &get_instance();
                $CI->load->library(array('s3'));
                
                $delete = $CI->s3->remove($src);
                if ($delete['@metadata']['statusCode'] === 204) {
                    return true;
                }
                return false;
            } catch (Exception $ex) {
                return false;
            }
        }
        if (file_exists(FCPATH . $src)) {
            try {
                unlink($src);
                return true;
            } catch (Exception $ex) {
                return false;
            }
        }
    }
}
if (!function_exists('star')) {

    function star($value) {
        if ($value > 0) {
            for ($i = 1; $i <= $value; $i++) {
                echo '<span class="fa fa-stack"><i class="fa fa-star fa-stack-2x orange"></i></span>';
            }
            for ($i = 1; $i <= 5 - $value; $i++) {
                echo '<span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x orange"></i></span>';
            }
        }
    }
}
if (!function_exists('st_aktif')) {

    function st_aktif($value, $yes_no = null, $pay = null) {
        if(!is_null($yes_no)){
            return ($value == '1') ? '<span class="label label-success label-white">YA</span>' : '<span class="label label-danger label-white">TIDAK</span>';
        }
        $label = !is_null($pay) ? array('LUNAS','BELUM LUNAS') : array('AKTIF','TIDAK AKTIF');
        switch ($value) {
            case '1':
                $status = '<span class="label label-success arrowed-in-right arrowed">'.$label[0].'</span>';
                break;
            case '0':
                $status = '<span class="label label-danger arrowed-in-right arrowed">'.$label[1].'</span>';
                break;
            default:
                $status = '<span class="label label-default arrowed-in-right arrowed">PENDING</span>';
                break;
        }
        return $status;
    }
}
if (!function_exists('st_span')) {

    function st_span($value) {
        $status = '';

        if ($value == '1') {
            $status = '<span class="label label-success arrowed-in-right arrowed">VALID</span>';
        } else if ($value == '2') {
            $status = '<span class="label label-danger arrowed-in-right arrowed">REUPLOAD</span>';
        } else {
            $status = '<span class="label label-default arrowed-in-right arrowed">PENDING</span>';
        }
        return $status;
    }
}
if (!function_exists('st_mhs')) {

    function st_mhs($value) {
        $warning = array('TES SELEKSI','TES SUSULAN','REUPLOAD PEMBAYARAN');
        $info = array('VALID');
        $danger = array('BELUM LULUS','TIDAK AKTIF');
        $success = array('LULUS','AKTIF');
        
        if (in_array($value, $success)) {
            $status = '<span class="label label-success arrowed-in-right arrowed">'.$value.'</span>';
        } else if (in_array($value, $info)) {
            $status = '<span class="label label-info arrowed-in-right arrowed">'.$value.'</span>';
        }else if (in_array($value, $warning)) {
            $status = '<span class="label label-warning arrowed-in-right arrowed">'.$value.'</span>';
        }else if (in_array($value, $danger)) {
            $status = '<span class="label label-danger arrowed-in-right arrowed">'.$value.'</span>';
        }else {
            $status = '<span class="label label-default arrowed-in-right arrowed">'.$value.'</span>';
        }
        return $status;
    }
}
if (!function_exists('st_soal')) {

    function st_soal($quiz, $is_done = false) {
        switch ($quiz['status_quiz']) {
            case '0': $val = 'btn-default';
                break;
            case '1': $val = 'btn-info';
                break;
            case '2': $val = 'btn-yellow';
                break;
            default : $val = '';
                break;
        }
        if($is_done){
            switch ($quiz['valid_quiz']) {
                case '0': $val = 'btn-danger';
                    break;
                case '1': $val = 'btn-success';
                    break;
                default : $val = '';
                    break;
            }
        }
        return $val;
    }
}
if (!function_exists('load_array')) {

    function load_array($type) {
        $val = array();
        switch ($type) {
            case 'tahun':
                $awal = intval(date('Y'));
                for($i = $awal + 1; $i >= ($awal - 5); $i-- ){
                    $val[] = $i;
                }
                break;
            case 'periode':
                $awal = intval(date('Y'));
                $val = [];
                for($i = $awal - 1; $i <= $awal; $i++ ){
                    $val[] = $i . '1';
                    $val[] = $i . '2';
                }
                break;
            case 'jalur':
                $val = array(
                    'Reguler','Alih-Jenjang','SBMPTMu','Beasiswa-KIP','Prestasi-Raport','Prestasi-ATBK','Prestasi-Akademik','Prestasi-Seni',
                    'Prestasi-Olahraga','Kader-Muhammadiyah','Hafidz-Quran','Pemda-Misol','Pemda-Wondama','Pemda-RajaAmpat','Pemda-Fakfak',
                    'PPG-Prajabatan', 'PPG-Dalamjabatan', 'PPG-GuruTertentu'
                );
                break;
            case 'kip':
                $val = array(
                    'PENDING','VALID', 'TIDAK VALID', 'Tanggungan Negara', 'NIK Tidak Sesuai',
                    'NIK DKTS Bansos','NISN Tidak Sesuai',
                    'Data Dapodik Tidak Sesuai', 'Tahun Lulus Tidak Valid'
                );
                break;
            case 'status':
                $val = array(
                    'PENDING','PENDAFTARAN','TES SELEKSI','LULUS','VALID','AKTIF','TIDAK AKTIF'
                );
                break;
            case 'jenis_daftar':
                $val = [
                        ['id' => '1', 'text' => 'Peserta Didik Baru'], ['id' => '2', 'text' => 'Pindahan'],
                        ['id' => '17', 'text' => 'PPG PGP / PLPG'], ['id' => '18', 'text' => 'PPG Non PGP / PLPG'],
                        ['id' => '13', 'text' => 'RPL Perolehan SKS'], ['id' => '16', 'text' => 'RPL Transfer SKS']
                    ];
                break;
            case 'agama':
                $val = array(
                    'Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu', 'Lainnya'
                );
                break;
            case 'didik':
                $val = array(
                    'SD', 'SMP/MTS', 'SMA/MA', 'S1', 'S2', 'S3', 'Lainnya'
                );
                break;
            case 'kerja':
                $val = array(
                    'PNS', 'TNI/POLRI', 'Pegawai Swasta', 'Wirausaha', 'Petani',
                    'Nelayan','Buruh','Pensiunan','Ibu Rumah Tangga','Lainnya'
                );
                break;
            case 'golongan':
                $val = array(
                    'Di bawah Rp 1.000.000', 'Rp 1.000.000 - Rp 3.000.000', 'Rp 3.000.000 - Rp 5.000.000', 'Di atas Rp 5.000.000'
                );
                break;
        }
        return $val;
    }
}
if (!function_exists('encode')) {

    function encode($param, $url_safe = TRUE) {
        if(is_null($param) || $param == '' ){
            return '';
        }        
        $CI = &get_instance();
        $secret_key = $CI->config->item('encryption_key');
        $secret_iv = $CI->config->item('encrypt_iv');
        $encrypt_method = $CI->config->item('encrypt_method');
        // hash
        $key = hash('sha256', $secret_key);
        // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        //do the encryption given text/string/number
        $result = openssl_encrypt($param, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($result);
        
        if ($url_safe) {
            $output = strtr($output, array('+' => '.', '=' => '-', '/' => '~'));
        }
        return $output;
    }
}
if (!function_exists('decode')) {

    function decode($param, $url_safe = TRUE) {
        $CI = &get_instance();
        $secret_key = $CI->config->item('encryption_key');
        $secret_iv = $CI->config->item('encrypt_iv');
        $encrypt_method = $CI->config->item('encrypt_method');
        
        if ($url_safe){
            $param = strtr($param, array('.' => '+', '-' => '=', '~' => '/'));
        }
        // hash
        $key = hash('sha256', $secret_key);
        // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        //do the decryption given text/string/number
        $output = openssl_decrypt(base64_decode($param), $encrypt_method, $key, 0, $iv);

        return $output == false ? null : $output;
    }
}
if (!function_exists('ip_agent')) {

    function ip_agent() {
        $CI = &get_instance();
        $CI->load->library('user_agent');
        
        $ip = $CI->input->ip_address();
        $platform = $CI->agent->platform() ?: 'Unknown Platform';
        $browser = $CI->agent->browser();
        $version = $CI->agent->version();
        
        if ($CI->agent->is_robot()) {
            $device = 'Robot: ' . $CI->agent->robot();
        } elseif ($CI->agent->is_mobile()) {
            $device = 'Mobile: ' . $CI->agent->mobile();
        } elseif ($CI->agent->is_browser()) {
            $device = 'Desktop';
        } else {
            $device = 'Unknown: ' . $CI->agent->agent_string();
        }
        return "{$ip} | {$device} | {$platform} | {$browser} {$version}";
    }
}
if (!function_exists('jsonResponse')) {

    function jsonResponse($output, $code = 200) {
        $CI = &get_instance();
        $ajax_request = $CI->input->is_ajax_request();
        $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
        
        $is_allowed = true;
        if (!empty($origin)) {
            $host = parse_url($origin, PHP_URL_HOST);
            $is_allowed =
                preg_match('/(^|\.)unimudasorong\.ac\.id$/', $host) ||
                in_array($host, $CI->config->item('app.allowed_domain'));
        }
        if (ENVIRONMENT === 'production') {
            if (!$is_allowed || !$ajax_request) {
                $CI->output->set_status_header(403)->set_output('Forbidden Access : '.$origin)->_display();
                exit();
            }
            $CI->output->set_header("Access-Control-Allow-Origin: $origin")
                ->set_header("Access-Control-Allow-Credentials: true");
        }
        $CI->output
            ->set_status_header($code)
            ->set_content_type('application/json', 'utf-8')
            ->set_header('Cache-Control: no-store, no-cache, must-revalidate')
            ->set_output(json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
            ->_display();
        exit();
    }
}
