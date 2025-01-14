<?php
defined('BASEPATH') OR exit('No direct script access allowed');
if (!function_exists('jsonResponse')) {

    function jsonResponse($output) {
        $CI = &get_instance();
        $ajax = $CI->config->item('app.debug');

        $ajax_request = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? TRUE : FALSE;
        if ($ajax == 0) {
            (!$ajax_request) ? exit('No direct script access allowed') : '';
        }
        $result = (defined('JSON_PRETTY_PRINT')) ? json_encode($output, JSON_PRETTY_PRINT) : json_encode($output);
        header('Content-Type: application/json');
        print_r($result);
        exit();
    }
}
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
            echo '<link rel="stylesheet" type="text/css" href="' . base_url('app/'.$uri) . '" />';
        }
    }

}
if (!function_exists('load_js')) {

    function load_js(array $array, $async = FALSE) {
        foreach ($array as $uri) {
            if(!$async){
                echo '<script type="text/javascript"  src="' . base_url('app/'.$uri) . '"></script>';
            }else{
                echo '<script async type="text/javascript"  src="' . base_url('app/'.$uri) . '"></script>';
            }
        }
    }

}
if (!function_exists('load_file')) {

    function load_file($src, $img = NULL) {
        $null_ava_img = !is_null($img) ? 'app/img/no-avatar.png' : 'app/img/no-img.jpg';
        if(empty($src)){
            return base_url($null_ava_img);
        }
        if(substr($src, 0, 3) != 'app'){
            $CI = &get_instance();
            $CI->load->library(array('s3'));
            $link = $CI->s3->url($src);
        }else{
            $link = is_file($src) ? base_url($src) : base_url($null_ava_img);
        }
        return $link;
    }

}
if (!function_exists('delete_file')) {

    function delete_file($src) {
        if(empty($src)){
            return false;
        }
        if(substr($src, 0, 3) != 'app'){
            $CI = &get_instance();
            $CI->load->library(array('s3'));
            $CI->s3->remove($src);
        }else{
            (is_file($src)) ? unlink($src) : '';
        }
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

        return $output;
    }
}
if (!function_exists('ip_agent')) {

    function ip_agent() {
        $CI = &get_instance();
        $CI->load->library('user_agent');

        $agent = $CI->input->ip_address();
        if ($CI->agent->is_robot()) {
            $agent .= ' | Robot ' . $CI->agent->robot();
        } else if ($CI->agent->is_mobile()) {
            $agent .= ' | Mobile ' . $CI->agent->mobile();
        } else if ($CI->agent->is_browser()) {
            $agent .= ' | Desktop ';
        } else {
            $agent .= ' | '.$CI->agent->agent_string();
        }
        $agent .= ' - ' . $CI->agent->platform();
        $agent .= ' | ' . $CI->agent->browser() . ' ' . $CI->agent->version();

        return $agent;
    }
}
if (!function_exists('star')) {

    function star($value) {
        if ($value > 0) {
            for ($i = 1; $i <= $value; $i++) {
                echo '<span class="fa fa-stack"><i class="fa fa-star orange"></i></span>';
            }
            for ($i = 1; $i <= 5 - $value; $i++) {
                echo '<span class="fa fa-stack"><i class="fa fa-star-o"></i></span>';
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
if (!function_exists('st_file')) {

    function st_file($src, $file = NULL) {
        $rs = '<i class="bigger-130 fa fa-times red"></i>';
        $down = '&nbsp; | &nbsp;<a class="bigger-130" href="'. load_file($src) .'" target="_blank"><i class="fa fa-download"></i></a>';
        
        if(empty($src)){
            return $rs;
        }
        if(substr($src, 0, 3) != 'app'){
            $rs = '<i class="bigger-130 fa fa-check-square-o green"></i>';
            $rs .= is_null($file) ? '' : $down;
        }else{
            if(is_file($src)){
                $rs = '<i class="bigger-130 fa fa-check green"></i>';
                $rs .= is_null($file) ? '' : $down;
            }
        }
        return $rs; 
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
if (!function_exists('st_tes')) {

    function st_tes($gel) {
        switch ($gel) {
            case 'I':
                $jadwal = 'Gelombang '.$gel.' <br/>Senin, 22 Maret 2021 | 08:00 - 17:00 WIT';
                break;
            case 'II':
                $jadwal = 'Gelombang '.$gel.' <br/>Senin, 12 April 2021 | 08:00 - 17:00 WIT';
                break;
            case 'III':
                $jadwal = 'Gelombang ' . $gel . ' <br/>Senin, 26 April 2021 | 08:00 - 17:00 WIT';
                break;
            case 'IV':
                $jadwal = 'Gelombang ' . $gel . ' <br/>Sabtu, 29 Mei 2021 | 08:00 - 17:00 WIT';
                break;
            case 'V':
                $jadwal = 'Gelombang ' . $gel . ' <br/>Rabu, 16 Juni 2021 | 08:00 - 17:00 WIT';
                break;
            case 'VI':
                $jadwal = 'Gelombang ' . $gel . ' <br/>Sabtu, 19 Juni 2021 | 08:00 - 17:00 WIT';
                break;
            case 'VII':
                $jadwal = 'Gelombang ' . $gel . ' <br/>Sabtu, 26 Juni 2021 | 08:00 - 17:00 WIT';
                break;
            case 'VIII':
                $jadwal = 'Gelombang ' . $gel . ' <br/>Sabtu, 03 Juli 2021 | 08:00 - 17:00 WIT';
                break;
            case 'IX':
                $jadwal = 'Gelombang ' . $gel . ' <br/>Sabtu, 10 Juli 2021 | 08:00 - 17:00 WIT';
                break;
            case 'X':
                $jadwal = 'Gelombang ' . $gel . ' <br/>Sabtu, 17 Juli 2021 | 08:00 - 17:00 WIT';
                break;
            case 'XI':
                $jadwal = 'Gelombang ' . $gel . ' <br/>Sabtu, 31 Juli 2021 | 08:00 - 17:00 WIT';
                break;
            case 'XII':
                $jadwal = 'Gelombang ' . $gel . ' <br/>Sabtu, 14 Agustus 2021 | 08:00 - 17:00 WIT';
                break;
            case 'XIII':
                $jadwal = 'Gelombang ' . $gel . ' <br/>Selasa, 24 Agustus 2021 | 08:00 - 17:00 WIT';
                break;
            case 'XIV':
                $jadwal = 'Gelombang ' . $gel . ' <br/>Selasa, 07 September 2021 | 08:00 - 17:00 WIT';
                break;
            case 'OFF':
                $jadwal = 'Gelombang Offline';
                break;
            case NULL:
                $jadwal = '-';
                break;
            default : 
                $jadwal = $gel.' ?';
                break;
        }
        return $jadwal;
    }
}
if (!function_exists('load_array')) {

    function load_array($type) {
        $val = array();
        switch ($type) {
            case 'tahun':
                $awal = intval(date('Y')) + 1;
                for($i = $awal; $i >= ($awal - 5); $i-- ){
                    $val[] = $i;
                }
                break;
            case 'jalur':
                $val = array(
                    'Reguler','Alih-Jenjang','SBMPTMu','Beasiswa-KIP','Prestasi-Raport','Prestasi-ATBK','Prestasi-Akademik','Prestasi-Seni',
                    'Prestasi-Olahraga','Kader-Muhammadiyah','Hafidz-Quran','Pemda-Misol','Pemda-Wondama','Pemda-RajaAmpat','Pemda-Fakfak',
                    'PPG-Prajabatan', 'PPG-Dalamjabatan', 'PPG-GuruTertentu'
                );
                break;
            case 'gelombang':
                $val = array(
                    'OFF','I', 'II', 'III', 'IV', 'V','VI', 'VII', 'VIII', 'IX',
                    'X', 'XI', 'XII', 'XIII', 'XIV', 'XV'
                );
                break;
            case 'kip':
                $val = array(
                    'PENDING','VALID', 'TIDAK VALID', 'Tanggungan Negara', 'NIK Tidak Sesuai', 'NISN Tidak Sesuai',
                    'Data Dapodik Tidak Sesuai', 'Tahun Lulus Tidak Valid'
                );
                break;
            case 'berkas':
                $val = array(
                    'KTP','Kartu Keluarga','Ijazah SMA/MA Sederajat', 
                    'SKHUN SMA/MA Sederajat', 'Bukti Pembayaran PMB','Raport Kelas X','Raport Kelas XI','Raport Kelas XII','Sertifikat Prestasi'
                );
                break;
            case 'tinggal':
                $val = array(
                    'Rumah Sendiri', 'Kost', 'Kontrakan', 'Asrama'
                );
                break;
            case 'transport':
                $val = array(
                    'Jalan Kaki', 'Sepeda', 'Motor', 'Mobil', 'Umum'
                );
                break;
            case 'status':
                $val = array(
                    'PENDING','PENDAFTARAN','TES SELEKSI','LULUS','VALID','AKTIF','TIDAK AKTIF'
                );
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
            case 'darah':
                $val = array(
                    'A', 'B', 'AB', 'O'
                );
                break;
            case 'golongan':
                $val = array(
                    'Di bawah Rp 1.000.000', 'Rp 1.000.000 - Rp 3.000.000', 'Rp 3.000.000 - Rp 5.000.000', 'Di atas Rp 5.000.000'
                );
                break;
            case 'bekerja':
                $val = array(
                    'Belum', 'Sudah'
                );
                break;
            case 'tanggung':
                $val = array(
                    'Sendiri', 'Orang Tua', 'Wali/Saudara'
                );
                break;
            case 'sumber':
                $val = array(
                    'Sendiri', 'Orang Tua', 'Wali/Saudara'
                );
                break;
        }
        return $val;
    }
}
