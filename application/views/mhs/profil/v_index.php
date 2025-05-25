<?php $this->load->view('sistem/v_breadcrumb'); ?>
<style>
    .profile-info-name{
        width: 160px !important;
    }
</style>
<div class="page-content">
    <div class="page-header">
        <h1>
            <?= $title[1] ?>
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                <?= $title[0] ?>
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <?= $this->session->flashdata('notif'); ?>
        </div>
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="">
                <div id="user-profile-2" class="user-profile">
                    <div class="tabbable">
                        <ul class="nav nav-tabs padding-18">
                            <li class="<?= !in_array($detail['status_mhs'], ['PENDING','PENDAFTARAN','TES SELEKSI']) ? 'active':''?>">
                                <a data-toggle="tab" href="#home">
                                    <i class="green ace-icon fa fa-user bigger-120"></i>
                                    Lihat Profil
                                </a>
                            </li>
                            <li class="<?= in_array($detail['status_mhs'], ['PENDING','PENDAFTARAN','TES SELEKSI']) ? 'active':''?>">
                                <a data-toggle="tab" href="#seleksi">
                                    <i class="red ace-icon fa fa-paste bigger-120"></i>
                                    Tes Seleksi Mandiri
                                </a>
                            </li>
                            <li class="">
                                <a target="_blank" href="<?= site_url('mhs/berkas') ?>">
                                    <i class="orange ace-icon fa fa-upload bigger-120"></i>
                                    Pengajuan Beasiswa
                                </a>
                            </li>
                            <li class="">
                                <a target="_blank" href="<?= site_url('mhs/wali') ?>">
                                    <i class="blue ace-icon fa fa-users bigger-120"></i>
                                    Orang Tua/Wali
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content no-border padding-10">

                            <div id="home" class="tab-pane <?= !in_array($detail['status_mhs'], ['PENDING','PENDAFTARAN','TES SELEKSI']) ? 'in active':''?>">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-2 center">
                                        <span class="profile-picture">
                                            <img src="<?= load_file($detail['foto_mhs'], 1) ?>" id="avatar" class="img-responsive" />
                                        </span>
                                        <div class="space space-4"></div>
                                        <a href="<?= site_url($module .'/edit') ?>" class="btn btn-block btn-bold btn-warning btn-white">
                                            <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                            <span class="">Ubah Profil</span>
                                        </a>
                                        <div class="space space-4"></div>
                                        <a target="_blank" href="<?= site_url($module .'/cetak') ?>" class="btn btn-block btn-bold btn-primary btn-white">
                                            <i class="ace-icon fa fa-credit-card bigger-120"></i>
                                            <span class="">KTM Sementara</span>
                                        </a>
                                    </div><!-- /.col -->
                                    <div class="col-xs-12 col-sm-10">
                                        <h4 class="blue">
                                            <span class="middle bolder"><?= $detail['nama_mhs'] ?></span>
                                        </h4>
                                        <div id="user-profile-1" class="user-profile row">
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="profile-user-info profile-user-info-striped">
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Kode Registrasi </div>
                                                        <div class="profile-info-value">
                                                            <span class="bolder bigger-110">#<?= $detail['kode_reg'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Program Studi </div>
                                                        <?php
                                                            $prodi = explode('|', $detail['opsi_prodi']);
                                                        ?>
                                                        <div class="profile-info-value">
                                                            <span class="bolder green bigger-110"><?= element('nama_prodi', $detail, '') ?></span><br/>
                                                            Pilihan 2 : <span class="bolder"><?= element(0, $prodi, '') ?></span><br/>
                                                            Pilihan 3 : <span class="bolder grey"><?= element(1, $prodi, '') ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> NIM </div>
                                                        <div class="profile-info-value">
                                                            <span class="bolder green"><?= $detail['nim'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Angkatan </div>
                                                        <div class="profile-info-value">
                                                            <span><?= $detail['angkatan'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Status </div>
                                                        <div class="profile-info-value">
                                                            <?= st_mhs($detail['status_mhs']) ?>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Jalur Pendaftaran </div>
                                                        <div class="profile-info-value">
                                                            <span class="bolder"><?= $detail['jalur_mhs'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Tanggal Daftar </div>
                                                        <div class="profile-info-value">
                                                            <span><?= format_date($detail['tgl_daftar'], 0) ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> NISN </div>
                                                        <div class="profile-info-value">
                                                            <span><?= $detail['nisn'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Asal Sekolah </div>
                                                        <div class="profile-info-value">
                                                            <span><?= $detail['sekolah'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> NPSN </div>
                                                        <div class="profile-info-value">
                                                            <span><?= $detail['npsn'] ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="space-4"></div>
                                            </div>

                                            <div class="col-xs-12 col-sm-6">
                                                <div class="profile-user-info profile-user-info-striped">
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Atribut Mahasiswa </div>
                                                        <div class="profile-info-value">
                                                            <span><?= $detail['atribut_mhs'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name">Akun </div>
                                                        <div class="profile-info-value">
                                                            <?= $user['fullname'] ?>
                                                            <?= ($user['status_user'] == '0') ? '<span class="label label-danger label-white arrowed">Tidak Aktif</span>' : '<span class="label label-success label-white arrowed">Aktif</span>' ?>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name">
                                                            <span>Username</span>
                                                        </div>
                                                        <div class="profile-info-value">
                                                            <span><?= $user['username'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name">
                                                            <span>Email</span>
                                                        </div>
                                                        <div class="profile-info-value">
                                                            <span><?= $user['email'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row ">
                                                        <div class="profile-info-name">Log</div>
                                                        <div class="profile-info-value">
                                                            <span>
                                                                <span class="blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= $user['log_user'] ?></span><br/>
                                                                <span class="green"><i class="ace-icon fa fa-pencil"></i> &nbsp;&nbsp;<?= format_date($user['buat_user'],0) ?></span><br/>
                                                                <span class="orange"><i class="ace-icon fa fa-pencil-square-o"></i> &nbsp;<?= format_date($user['update_user'],0) ?></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name">
                                                            <i class="middle ace-icon fa fa-sign-in bigger-150 red"></i>
                                                        </div>
                                                        <div class="profile-info-value">
                                                            <span><?= selisih_wkt($user['last_login']) ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name">
                                                            <i class="middle ace-icon fa fa-laptop bigger-150 purple"></i>
                                                        </div>
                                                        <div class="profile-info-value">
                                                            <span><?= $user['ip_user'] ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="space-4"></div>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="profile-user-info profile-user-info-striped">
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> NIK </div>
                                                        <div class="profile-info-value">
                                                            <span><?= $detail['nik'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Ibu Kandung </div>
                                                        <div class="profile-info-value">
                                                            <span class=""><?= $detail['ibu_kandung'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Tanggal Lahir </div>
                                                        <div class="profile-info-value">
                                                            <span><?= $detail['tempat_lahir'] ?>, 
                                                            <?= format_date($detail['tgl_lahir'],1) ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Jenis Kelamin </div>
                                                        <div class="profile-info-value">
                                                            <span><?= $detail['kelamin_mhs'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Agama </div>
                                                        <div class="profile-info-value">
                                                            <span><?= $detail['agama'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Telepon </div>
                                                        <div class="profile-info-value">
                                                            <span><?= $detail['telepon_mhs'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Email </div>
                                                        <div class="profile-info-value">
                                                            <span><?= $detail['email_mhs'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Alamat di Sorong </div>
                                                        <div class="profile-info-value">
                                                            <span><?= $detail['alamat_mhs'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Alamat KTP </div>
                                                        <div class="profile-info-value">
                                                            <span>Jln. <?= $detail['jalan'] ?>
                                                                RT <?= $detail['rt'] ?> RW <?= $detail['rw'] ?>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Kelurahan </div>
                                                        <div class="profile-info-value">
                                                            <span><?= $detail['kelurahan'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Kecamatan </div>
                                                        <div class="profile-info-value">
                                                            <span><?= $detail['kecamatan'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Kota/Kabupaten </div>
                                                        <div class="profile-info-value">
                                                            <span><?= $detail['kabupaten'] ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row ">
                                                        <div class="profile-info-name">Log :</div>
                                                        <div class="profile-info-value">
                                                            <span>
                                                                <span class="blue"><i class="ace-icon fa fa-user"></i> &nbsp;&nbsp;<?= $detail['log_mhs'] ?></span><br/>
                                                                <span class="orange"><i class="ace-icon fa fa-pencil-square-o"></i> &nbsp;&nbsp;<?= selisih_wkt($detail['update_mhs'],0) ?></span>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="space-4"></div>
                                            </div>
                                        </div>
                                    </div><!-- /.col -->
                                </div><!-- /.row -->
                            </div><!-- /#home -->
                            
                            <!--UBAH-->
                            <div id="seleksi" class="tab-pane <?= in_array($detail['status_mhs'], ['PENDING','PENDAFTARAN','TES SELEKSI']) ? 'in active':''?>">
                                <div class="profile-feed row">
                                    <div class="col-xs-12 <?= ($valid_test) ? 'hide':'' ?>">
                                        <div class="alert alert-warning bigger-120">
                                            <button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
                                            <strong>Informasi !</strong><br/>
                                            Tes Seleksi Mandiri belum dapat dilakukan. Hubungi Panitia PMB untuk informasi lebih lengkap
                                        </div>
                                    </div>
                                    <div class="col-xs-12 <?= ($valid_test) ? '':'hide' ?>">
                                        <div class="well well-sm bigger-110">
                                            &nbsp;1. Tes Seleksi Mandiri dilakukan secara Online melalui Akun Camaba masing-masing<br/>
                                            2. Wajib menggunakan Laptop atau PC untuk mengerjakan Tes Seleksi ini agar hasil optimal<br/>
                                            3. Camaba wajib menyelesaikan seluruh Soal yang tersedia<br/>
                                            4. Silahkan memilih <b>Paket Soal</b> sesuai kebutuhan Program Studi yang anda ambil <br/>
                                            5. Perhatikan Jawaban dan Sisa Waktu yang tersisa saat mengerjakan keseluruhan tes ini<br/>
                                            <div class="space-6"></div>
                                            Catatan : <br/>
                                            * Setelah dinyatakan <b class="green">LULUS</b>, anda diwajibkan membayar <strong>Biaya Registrasi Ulang</strong>.<br/>
                                            * Pembayaran dilakukan melalui rekening <b class="blue">Virtual Account</b> masing-masing.<br/>
                                            * Apabila ada pertanyaan dapat menghubungi Panitia PMB UNIMUDA Sorong secara langsung.
                                        </div>
                                        <div class="space-4"></div>
                                        <p id="one-spin" style="display: none" class="bigger-130 blue" align="center"><i class="fa fa-spinner fa-spin fa-fw fa-2x"></i> Loading . . .</p>
                                        <div class="widget-box transparent">
                                            <div class="widget-header">
                                                <h4 class="widget-title">
                                                    <i class="ace-icon fa fa-check-square-o"></i> 
                                                    Soal Tersedia
                                                </h4>
                                                <div class="widget-toolbar">
                                                    <a href="#" data-action="collapse" class="orange2">
                                                        <i class="ace-icon fa fa-chevron-up bigger-125"></i>
                                                    </a>
                                                </div>
                                                <div class="widget-toolbar no-border">
                                                    <div class="btn-group btn-overlap">
                                                        <button class="btn btn-primary btn-white btn-bold btn-sm" name="cari" id="btn-search" type="button">
                                                            <i class="ace-icon fa fa-search-plus bigger-120"></i>
                                                            Tampilkan
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="widget-body">
                                                <div class="widget-main padding-2 table-responsive">
                                                    <div class="space-2"></div>
                                                    <div class="list-course"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- /.row -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- PAGE CONTENT ENDS -->
        </div>
        <!-- /.col -->
    </div><!-- /.row -->
</div><!-- /.page-content -->
<?php
    load_js(array(
        'theme/aceadmin/assets/js/bootbox.min.js'
    ));
?>
<script type="text/javascript">
    const module = "<?= site_url($module) ?>";
    $(document).ready(function() {
        load_index();
    });
    $(document.body).on("click", "#submit-btn", function(e) {
        var id = $(this).attr("itemid");
        var name = $(this).attr("itemname");
        var title = "<h4 class='red center'><i class='ace-icon fa fa-exclamation-triangle red'></i> Peringatan !</h4>";
        var msg = "<p class='center grey bigger-120'><i class='ace-icon fa fa-hand-o-right blue'></i>" + 
                " Apakah anda yakin akan mulai mengerjakan <br/><strong class='blue bigger-130'>" + name + "</strong> sekarang ? </p>";
        bootbox.confirm({
            title: title, message: msg, 
            buttons: {
                cancel: {
                    label: "<i class='ace-icon fa fa-times bigger-110'></i> Batal", className: "btn btn-sm"
                },
                confirm: {
                    label: "<i class='ace-icon fa fa-check bigger-110'></i> YA, MULAI", className: "btn btn-sm btn-success"
                }
            },
            callback: function(result) {
                if (result === true) {
                    start_session(id);
                }
            }
        });
    });
    $("#btn-search").click(function () {
        load_index();
    });
</script>
<script type="text/javascript">
    function load_index() {
        $("#one-spin").show();
        $(".list-course").html('');
        $.ajax({
            url: module + "/ajax/type/list/source/index",
            type: "POST",
            dataType: "json",
            success: function (rs) {
                if (rs.status) {
                    $(".list-course").html(rs.data.course);
                } else {
                    myNotif('Peringatan', rs.msg, 2);
                }
                $("#one-spin").hide();
            },
            error: function (xhr, ajax, err) {
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
    function start_session(id, url = 'start') {
        var title = '<h4 class="blue center"><i class="ace-icon fa fa fa-spin fa-spinner"></i> Mohon tunggu . . . </h4>';
        var msg = '<p class="center red bigger-120"><i class="ace-icon fa fa-hand-o-right blue"></i>' +
                ' Jangan menutup atau me-refresh halaman ini, silahkan tunggu sampai peringatan ini tertutup sendiri. </p>';
        var progress = bootbox.dialog({title: title,message: msg,closeButton: false});
        $.ajax({
            url: module + "_do/ajax/type/action/source/" + url,
            dataType: "json",
            type: "POST",
            data: { id: id },
            success: function (rs) {
                if (rs.status) {
                    setTimeout(function () {
                        progress.modal("hide");
                        window.open(rs.link, '_blank').focus();
                    }, 3000);
                    myNotif('Informasi', rs.msg, 1, 'swal');
                } else {
                    progress.modal("hide");
                    myNotif('Peringatan', rs.msg, 2, 'swal');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                progress.modal("hide");
                console.log('Error : ' + xhr.responseText);
            }
        });
    }
</script>
