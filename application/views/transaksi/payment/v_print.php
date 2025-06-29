<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= $detail['kode_reg'] ?> | <?= $detail['nama_mhs'] ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="shortcut icon" type="image/x-icon" href="<?= load_file('theme/img/logo.png','base64') ?>"/>
        <style>
            html, .signature{
                font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif;
            }
            td { 
                font-family:Verdana, Arial, Helvetica, sans-serif; 
                font-size: 12px;
            }
            th {
                font-size: 12px;
                text-align: center;
            }
            .watermark {
                background:url(<?= load_file('theme/img/logo.png','base64') ?>);
                background-repeat: no-repeat;
                background-position: center center;
                opacity: 0.06;
            }
            .logo{
                width: 70px;
                position: absolute;
                z-index: 1;
                left: 10px;
                top: 0px;
            }
            .red {
                color: #dd5a43 !important 
            }
            .green {
                color: #69aa46 !important;
            }
            .grey {
                color: #777777 !important;
            }
            .hide {
                display: none;
            }
            .footer{
                position: fixed;
                bottom: 0px;
                font-size: 8px !important;
                border-top: 1px double black;
            }
            .bigger-120 {
                font-size: 120% !important;
            }
            .bigger-150 {
                font-size: 150% !important;
            }
            .bigger-250 {
                font-size: 250% !important;
            }
            .center {
                text-align: center;
            }
        </style>
    </head>
    <body class="watermark">
        <table width="100%" style="padding-bottom:10px;margin-bottom:10px;">
            <tbody>
                <tr>
                    <th colspan="4">
                        <font size="2">
                            KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI
                        </font>
                        <img src="<?= load_file('theme/img/logo.png','base64') ?>" class="logo">
                    </th>
                </tr>
                <tr>
                    <td align="center" colspan="4">
                        <div class="repTitle">
                            <font size="3">
                                <strong>
                                    UNIVERSITAS PENDIDIKAN MUHAMMADIYAH (UNIMUDA) SORONG<br/>
                                    <?= $judul[1] ?>
                                </strong>
                            </font>
                            <br/>
                            SK. MENRISTEKDIKTI No. 547/KPT/I/2018<br/>
                            Jln. KH. Ahmad Dahlan No. 01 Malawele Aimas Kabupaten Sorong
                            Telp. (0951) 324409, 327873 Fax. (0951) 324409
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan="4">
                        <br/>
                        <font size="4"><strong style="text-decoration: underline"><?= $judul[0] ?></strong></font>
                        <br/><br/>
                    </td>
                </tr>
                <tr>
                    <td align="left" width="15%"><strong>Nama Mahasiswa</strong></td>
                    <td align="left" width="45%"><strong>: <?= $detail['nama_mhs'] ?> </strong> </td>
                    <td align="left" width="15%"><strong>Kode Registrasi</strong></td>
                    <td align="left"><strong>:</strong> <?= $detail['kode_reg'] ?> </td>
                </tr>
                <tr>
                    <td align="left"><strong>NIM</strong></td>
                    <td align="left"><strong>: <?= $detail['nim'] ?> </strong> (<?= $detail['status_mhs'] ?>) </td>
                    <td align="left"><strong>Angkatan</strong></td>
                    <td align="left"><strong>:</strong> <?= $detail['angkatan'] ?> </td>
                </tr>
            </tbody>
        </table>
        
        <table width="100%" border="1|0" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Invoice</th>
                    <td>
                        <strong class="bigger-120"><?= ctk($detail['invoice']) ?></strong><br/>
                        <small class="grey"> <?= format_date($detail['buat_payment'], 2) ?> </small>
                    </td>
                </tr>
                <tr>
                    <th>Status VA</th>
                    <td>
                        <?= st_aktif($detail['status_inquiry']) ?><br/>
                        <small class="grey"> <?= format_date($detail['update_payment'], 2) ?> </small>
                    </td>
                </tr>
                <tr>
                    <th>Bank Tujuan</th>
                    <th><strong class="bigger-120"><?= ctk($detail['bank_payment']) ?></strong></th>
                </tr>
                <tr>
                    <th>Virtual Account</th>
                    <th>
                        <span style="letter-spacing: 2px;" class="red bigger-150">
                            <?= ctk($detail['va_payment']) ?>
                        </span>
                    </th>
                </tr>
                <tr>
                    <th>Total Tagihan</th>
                    <th><strong class="bigger-120"><?= rupiah($detail['total_payment']) ?></strong></th>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <?php
                        $is_expired = ($detail['status_payment'] != '1' && !empty($detail['expired_payment']) 
                             && strtotime($detail['expired_payment']) <= time()) ? st_mhs('EXPIRED','danger') : '';
                        
                        echo '<strong class="bigger-120">';
                        echo st_aktif($detail['status_payment'],null,'pay').$is_expired.'<br/>
                            </strong><small class="grey">'.format_date($detail['paid_payment'],2).'</small>';
                        ?>
                    </td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td>
                        <?= ctk($detail['note_payment']) ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="center">
                        <strong class="red bigger-250"><?= rupiah($detail['total_payment']) ?></strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <ul class="list-unstyled">
                            <li>
                                <i class="ace-icon fa fa-check green"></i>
                                <small>Total Tagihan tidak termasuk Biaya Admin</small>
                            </li>
                            <li>
                                <i class="ace-icon fa fa-check green"></i>
                                <small>Pembayaran harus sesuai dengan <b>Bank Tujuan</b></small>
                            </li>
                            <li>
                                <i class="ace-icon fa fa-check green"></i>
                                <small>Pembayaran harus sesuai dengan <b>Total Tagihan</b></small>
                            </li>
                            <li>
                                <i class="ace-icon fa fa-check green"></i>
                                <small>Pembayaran hanya dilayani pada saat jam kerja <b>(08.00 WIT s/d 17.00 WIT)</b></small>
                            </li>
                            <li>
                                <i class="ace-icon fa fa-check green"></i>
                                <small>Segera selesaikan pembayaran dalam 3x24 jam</small>
                            </li>
                        </ul>
                    </td>
                </tr>
            </thead>
        </table>

        <table width="100%" style="padding-top: 30px">
            <tbody>
                <tr>
                    <td colspan="2"></td>
                    <td align="center" width="40%" style="font-size: 13px">
                        Sorong, <?= format_date(date('Y-m-d'),1) ?> <br/>
                        <strong>Staf BPKA</strong>
                        <br/><br/><br/><br/><br/><br/><br/>
                        <strong><?= $this->session->userdata('name') ?></strong>
                    </td>
                    <td width="15%"></td>
                </tr>
            </tbody>
        </table>
        
        <div class="footer">
            <table>
                <tr>
                    <td><img src="<?= load_file('theme/img/logo.png','base64') ?>" style="max-width: 35px;padding-right: 5px"></td>
                    <td><?= $this->session->userdata('name') . '<br>' . 
                        format_date(date('Y-m-d H:i:s'), 0) . ' <br> ' . ip_agent() ?>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>