<?php
if(!$valid_ktm){
    echo '<div class="alert alert-warning bigger-120">
        <button type="button" class="close" data-dismiss="alert">
                <i class="ace-icon fa fa-times"></i>
        </button>
        <strong>Informasi !</strong><br/>
        KTM belum dapat dicetak. Mohon menunggu Validasi Panitia PMB
        <br>
    </div>';
}else{
?>
<div class="col-xs-12">
    <div class="widget-box transparent">
        <div class="widget-header widget-header-small">
            <div class="widget-toolbar">
                <div class="btn-group btn-overlap">
                    <a onclick="printDiv()"
                       class="btn btn-white btn-primary btn-bold btn-sm">
                       <i class="ace-icon red bigger-130 fa fa-file-pdf-o"></i> Cetak KTM
                    </a>
                </div>
            </div>
        </div>
        <div class="widget-body">
            <div class="widget-main">
                <div id="printArea">
                    <link rel="stylesheet" href="<?= base_url('app/backend/assets/css/bootstrap.css') ?>" />
                    <link rel="stylesheet" href="<?= base_url('app/backend/assets/css/ace.css') ?>" class="ace-main-stylesheet" id="main-ace-style" />
                    <style type="text/css">
                        #card { 
                            font-family:'Poppins', sans-serif;
                        }
                        img#foto{
                            margin:10px;
                        }
                    </style>
                    <table id="card" align="center">
                        <tbody>
                            <tr>
                                <td colspan="2" class="center">
                                    <div align="center">
                                        <img width="80" src="<?= load_file('app/img/logo.png') ?>" class="img-responsive" />
                                    </div>
                                    <span class="bigger-120">
                                        <?= $detail['fakultas'] ?><br/>
                                        Universitas Pendidikan Muhammadiyah (UNIMUDA) Sorong
                                    </span><br/>
                                    <span style="font-size: 10px;line-height: 1">
                                        Jl. KH. Ahmad Dahlan No.1 Mariat Pantai Aimas, Sorong - Papua Barat<br/>
                                        Telp. 0951-324409, 325714 Fax. 0951-324409<br/>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="space-6"></div>
                                    <p class="bolder center" style="font-size: 14px; text-decoration: underline">
                                        BUKTI PENERIMAAN MAHASISWA BARU
                                    </p>
                                    <div class="space-12"></div>
                                </td>
                            </tr>
                            <tr>
                                <td><img id="foto" width="100" src="<?= load_file($detail['foto_mhs'], 1) ?>" class="img-responsive img-circle" /></td>
                                <td colspan="1">
                                    <table id="card-isi" align="">
                                        <tr>
                                            <td width="100">Nama</td>
                                            <td width="10">:</td>
                                            <td class="bolder"><?= $detail['nama_mhs'] ?></td>
                                        </tr>
                                        <tr class="<?= empty($detail['nim']) ? 'hide' : '' ?>">
                                            <td>NIM</td>
                                            <td>:</td>
                                            <td class="bolder"><?= $detail['nim'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Program Studi</td>
                                            <td>:</td>
                                            <td class="bolder"><?= $detail['nama_prodi'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Daftar</td>
                                            <td>:</td>
                                            <td><?= format_date($detail['tgl_daftar'],0) ?></td>
                                        </tr>
                                        <tr>
                                            <td>NIK</td>
                                            <td>:</td>
                                            <td><?= $detail['nik'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal Lahir</td>
                                            <td>:</td>
                                            <td><?= $detail['tempat_lahir'] . ', ' . format_date($detail['tgl_lahir'], 1) ?></td>
                                        </tr>
                                        <tr>
                                            <td>Jenis Kelamin</td>
                                            <td>:</td>
                                            <td><?= $detail['kelamin_mhs'] ?></td>
                                        </tr>
                                        <tr>
                                            <td>Alamat</td>
                                            <td>:</td>
                                            <td><?= limit_text($detail['alamat_mhs'],40) ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="center">
                                    <div class="space-6"></div>
                                    Selamat Anda telah resmi <strong class="green">DITERIMA</strong> sebagai <br/>
                                    <strong class="blue">Calon Mahasiswa Baru UNIVERSITAS PENDIDIKAN MUHAMMADIYAH (UNIMUDA) Sorong</strong><br/> 
                                    Tahun Ajaran <?= $detail['angkatan'].'/'.intval($detail['angkatan']+1) ?> dengan status <strong class="blue">"<?= ($detail['status_mhs']) ?>"</strong>.
                                    <br/>Bukti ini dapat dijadikan sebagai <strong class="orange">Kartu Mahasiswa Sementara</strong>, mohon untuk dicetak.
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="center">
                                    <div class="space-12"></div>
                                    
                                    Sorong, <?= format_date(date('Y-m-d'), 1) ?><br/>
                                    <div class="space-12"></div>
                                    <span style="font-size: 10px; font-style: italic">
                                        (ttd)
                                    </span><br/>
                                    <div class="space-12"></div>
                                    <span class="bolder">UPT PMB UNIMUDA Sorong</span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="smaller-50" style="font-style: italic">
                                    *token : [<?= encode($detail['tgl_daftar']) ?>]
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script> 
    function printDiv() { 
        var divContents = document.getElementById("printArea").innerHTML; 
        var a = window.open('', '', 'height=800, width=800'); 
        a.document.write('<html>'); 
        a.document.write('<body>'); 
        a.document.write(divContents); 
        a.document.write('</body></html>'); 
        a.document.close(); 
        a.print(); 
    } 
</script> 
<?php } ?>
