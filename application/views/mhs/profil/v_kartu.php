<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?= $detail['nim'] ?> | <?= $detail['nama_mhs'] ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('app/img/logo.png') ?>"/>
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
                background:url(<?= base_url('app/img/logo.png') ?>);
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
                        <img src="<?= load_file('app/img/logo.png') ?>" class="logo">
                    </th>
                </tr>
                <tr>
                    <td align="center" colspan="4">
                        <div class="repTitle">
                            <font size="3">
                                <strong>
                                    UNIVERSITAS PENDIDIKAN MUHAMMADIYAH (UNIMUDA) SORONG<br/>
                                    <?= strtoupper($prodi['fakultas']) ?>
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
                    <td width="7%"></td>
                    <td width="25%" align="left">NIM</td>
                    <td colspan="2" align="left" class="resize">: 
                        <strong><?= $detail['nim'] ?></strong>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td align="left">Nama Lengkap</td>
                    <td colspan="2" align="left" class="resize">:<strong> <?= $detail['nama_mhs'] ?> </strong> </td>
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
                    <td align="left">Status</td>
                    <td colspan="2" align="left" class="resize">: <?= $detail['status_mhs'] ?></td>
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
                    <td align="left">Tempat Tanggal Lahir</td>
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
                        Selamat Anda telah resmi <strong class="green">DITERIMA</strong> sebagai, <br/>
                        <strong class="blue">Mahasiswa Baru UNIVERSITAS PENDIDIKAN MUHAMMADIYAH (UNIMUDA) Sorong</strong><br/> 
                        Tahun Ajaran <?= $detail['angkatan'].'/'.intval($detail['angkatan']+1) ?> dengan status <strong class="blue">"<?= ($detail['status_mhs']) ?>"</strong>.
                        <br/><br/>Bukti ini dapat dijadikan sebagai <strong class="orange">Kartu Mahasiswa Sementara</strong>, mohon untuk dicetak.
                    </td>
                </tr>
            </tbody>
        </table>
        
        <table width="100%" style="padding-top: 30px">
            <tbody>
                <tr>
                    <td align="center" width="30%" style="font-size: 13px">
                        <br/>Mahasiswa<br/>
                        <br/><br/><br/><br/><br/><br/><br/>
                        <strong style="text-decoration: underline"><?= $detail['nama_mhs'] ?></strong><br/>
                        NIM. <?= $detail['nim'] ?>
                    </td>
                    <td>
                        <img width="90" class="img-thumbnail" src="<?= load_file($detail['foto_mhs'],1) ?>"/>
                    </td>
                    <td></td>
                    <td align="center" width="40%" style="font-size: 13px">
                        Sorong, <?= format_date(date('Y-m-d'),1) ?> <br/>
                        <strong>Biro Admisi <br/> UNIMUDA Sorong</strong>
                        <br/><br/><br/><br/><br/><br/><br/>
                        <strong style="text-decoration: underline">Panitia PMB</strong><br/>
                        
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="footer">
            <table>
                <tr>
                    <td><img src="<?= load_file('app/img/logo.png') ?>" style="max-width: 35px;padding-right: 5px"></td>
                    <td><?= $this->session->userdata('name') . '<br>' . 
                        format_date(date('Y-m-d H:i:s'), 0) . ' <br> ' . ip_agent() ?>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>