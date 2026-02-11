<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('is_periode')) {

    function is_periode($periode) {
        if(empty($periode)){
            return null;
        }
        $tahun = substr($periode,0,4);
        $tipe = substr($periode,4,1);
        $semester = $tahun.'/'.($tahun + 1);
        switch ($tipe) {
            case '1':
                $semester .= ' Ganjil';
                break;
            case '2':
                $semester .= ' Genap';
                break;
            default:
                $semester .= ' Pendek';
                break;
        }
        return $semester;
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

    function st_mhs($value, $type = NULL) {
        $warning = array('TES SELEKSI','TES SUSULAN','REUPLOAD PEMBAYARAN');
        $info = array('VALID');
        $danger = array('BELUM LULUS','TIDAK AKTIF');
        $success = array('LULUS','AKTIF');
        
        if (in_array($value, $info)) {
            $status = '<span class="label label-info arrowed-in-right arrowed">'.$value.'</span>';
        } else if (in_array($value, $warning)) {
            $status = '<span class="label label-warning arrowed-in-right arrowed">'.$value.'</span>';
        } else if (in_array($value, $success)) {
            $status = '<span class="label label-success arrowed-in-right arrowed">'.$value.'</span>';
        } else if (in_array($value, $danger)) {
            $status = '<span class="label label-danger arrowed-in-right arrowed">'.$value.'</span>';
        } else {
            $status = '<span class="label label-default arrowed-in-right arrowed">'.$value.'</span>';
        }
        return !empty($type) ? '<span class="label label-'.$type.' arrowed-in-right arrowed">'.$value.'</span>':$status;
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
if (!function_exists('range_date')) {

    function range_date($check_date, $start_date, $end_date) {
        // Convert to timestamp
        $start_ts = strtotime($start_date);
        $end_ts = strtotime($end_date);
        $check_ts = strtotime($check_date);
        
        $diff = $end_ts - $check_ts;
        $jam   = floor($diff / (60 * 60));
        $menit = $diff - ( $jam * (60 * 60) );
        $detik = $diff % 60;
        
        if( ($check_ts >= $start_ts) && ($check_ts <= $end_ts) ){
            $st = TRUE;
            $rs = $jam .  ' Jam - ' . floor( $menit / 60 ) . ' Menit - ' . $detik . ' Detik' ;
            //$rs = $jam .  ':' . floor( $menit / 60 ) . ':' . $detik;
        }else if($check_ts < $start_ts){
            $st = FALSE;
            $rs = 'Sesi Ini Belum Dimulai';
        }else{
            $st = FALSE;
            $rs = 'Sesi Ini Telah Berakhir';
        }
        return [ 'rs' => $rs, 'st' => $st ];
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
