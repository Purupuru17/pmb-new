<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= $detail['nama_mhs'] ?> | <?= $detail['nim'] ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="shortcut icon" type="image/x-icon" href="<?= load_file('private/logo.png','base64') ?>"/>
        <style>
            html, .signature{
                font-family: Century Gothic,CenturyGothic,AppleGothic,sans-serif;
            }
            td { 
                font-family:Verdana, Arial, Helvetica, sans-serif; 
                font-size: 12px;
            }
            td.resize {
                width: 45%;
                font-size: 13px;
                /*padding-bottom: 5px;*/
                padding-top: 5px;
            }
            th {
                font-size: 12px;
                text-align: center;
            }
            .watermark {
                background:url(<?= load_file('private/logo.png','base64') ?>);
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
            .hide {
                display: none;
            }
            .footer{
                width: 100%; 
                position: fixed;
                bottom: 0px;
                font-size: 8px !important;
                border-top: 1px double black;
            }
            .img-thumbnail {
                border-radius: 4px;
                display: inline-block;
                height: auto;
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
                            <?= element('menteri', $univ) ?>
                        </font>
                        <img src="<?= load_file('private/logo.png','base64') ?>" class="logo">
                    </th>
                </tr>
                <tr>
                    <td align="center" colspan="4">
                        <div class="repTitle">
                            <font size="3">
                                <strong>
                                    <?= element('nama', $univ) ?><br/>
                                    <?= strtoupper($title[1]) ?>
                                </strong>
                            </font>
                            <br/>
                            <?= element('alamat', $univ) ?>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td align="center" colspan="4">
                        <br/>
                        <font size="4"><strong style="text-decoration: underline"><?= $title[0] ?></strong></font>
                        <br/><br/>
                    </td>
                </tr>
                <tr>
                    <td width="7%"></td>
                    <td width="25%" align="left">Nama Lengkap</td>
                    <td colspan="2" align="left" class="resize">: 
                        <strong><?= $detail['nama_mhs'] ?></strong>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">Fakultas</td>
                    <td colspan="2" align="left" class="resize">:<strong> <?= $prodi['fakultas'] ?></strong> </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">Program Studi</td>
                    <td colspan="2" align="left" class="resize">:<strong> <?= $prodi['nama_prodi'] ?></strong> </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">Angkatan</td>
                    <td colspan="2" align="left" class="resize">: <?= $detail['angkatan'] ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">NIM</td>
                    <td colspan="2" align="left" class="resize">: 
                        <strong><?= $detail['nim'] ?></strong>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">NISN</td>
                    <td colspan="2" align="left" class="resize">: <?= $detail['nisn'] ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">NIK</td>
                    <td colspan="2" align="left" class="resize">: <?= $detail['nik'] ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">Jenis Kelamin</td>
                    <td colspan="2" align="left" class="resize">: <?= $detail['kelamin_mhs'] ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">Tempat, Tanggal Lahir</td>
                    <td colspan="2" align="left" class="resize">: 
                        <?= $detail['tempat_lahir'] ?>, 
                        <?= format_date($detail['tgl_lahir'],1) ?>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">Agama</td>
                    <td colspan="2" align="left" class="resize">: <?= $detail['agama'] ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">Telepon</td>
                    <td colspan="2" align="left" class="resize">: <?= $detail['telepon_mhs'] ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">Email</td>
                    <td colspan="2" align="left" class="resize">: <?= $detail['email_mhs'] ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">Alamat</td>
                    <td colspan="2" align="left" class="resize">: <?= $detail['alamat_mhs'] ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">Kode Registrasi</td>
                    <td colspan="2" align="left" class="resize">: <?= $detail['kode_reg'] ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">Status Pendaftaran</td>
                    <td colspan="2" align="left" class="resize">: <?= $detail['status_mhs'] ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">Jalur Pendaftaran</td>
                    <td colspan="2" align="left" class="resize">: <?= $detail['jalur_mhs'] ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">Tanggal Daftar</td>
                    <td colspan="2" align="left" class="resize">: <?= format_date($detail['tgl_daftar'], 0) ?></td>
                </tr>
                <tr>
                    <td></td>
                    <td style="text-align:left" colspan="3">
                        <br/><br/>
                        Selamat Anda telah dinyatakan,
                        <p style="text-align:center; font-size: 24px; margin: 0px;color:#13550b"><b><i>LULUS</i></b></p>
                        
                        <br>dan resmi diterima sebagai
                        <strong class="blue" style="font-size: 14px;text-decoration: underline">Mahasiswa Baru</strong>  
                        Tahun Ajaran <strong><?= $detail['angkatan'].'/'.intval($detail['angkatan']+1) ?>.</strong> 
                        <br/>Bukti ini dapat dijadikan sebagai <strong class="orange">Kartu Mahasiswa Sementara</strong>, mohon untuk dicetak.
                    </td>
                </tr>
            </tbody>
        </table>
        
        <table width="100%" style="padding-top: 10px">
            <tbody>
                <tr>
                    <td align="center" width="35%" style="font-size: 13px">
                        &nbsp;<br/>Mahasiswa<br/>
                        <br/><br/><br/><br/><br/><br/><br/>
                        <strong style="text-decoration: underline"><?= ucwords(strtolower($detail['nama_mhs'])) ?></strong><br/>
                        NIK. <?= $detail['nik'] ?>
                    </td>
                    <td>
                        <img width="90" class="img-thumbnail" src="<?= load_file($detail['foto_mhs']) ?>"/>
                    </td>
                    <td></td>
                    <td align="center" width="40%" style="font-size: 13px">
                        <?= element('kota', $univ) ?>, <?= format_date(date('Y-m-d'),1) ?> <br/>
                        <strong><?= $title[1] ?></strong>
                        <br/><br/><br/><br/><br/><br/><br/>
                        <strong style="text-decoration: underline">Panitia Seleksi</strong><br/>
                        &nbsp;
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="footer">
            <table>
                <tr>
                    <td><img src="<?= load_file('private/logo.png','base64') ?>" style="max-width: 40px;padding-right: 5px"></td>
                    <td><?= $this->session->userdata('name') . '<br>' . format_date(date('Y-m-d H:i:s'), 0) . ' @ ' . ip_agent() ?></td>
                </tr>
            </table>
        </div>
    </body>
</html>