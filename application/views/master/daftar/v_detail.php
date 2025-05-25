<?php $this->load->view('sistem/v_breadcrumb'); ?>
<style>
    .profile-info-name{
        width: 160px;
    }
    th, td {
        text-align:center;
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
        <div id="rs-check" class="col-xs-12">
            <?= $this->session->flashdata('notif'); ?>
        </div>
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="">
                <div id="user-profile-2" class="user-profile">
                    <div class="tabbable">
                        <ul class="nav nav-tabs padding-18">
                            <li class="active">
                                <a data-toggle="tab" href="#home">
                                    <i class="green ace-icon fa fa-user bigger-120"></i>
                                    Profil
                                </a>
                            </li>
                            <li class="hide">
                                <a data-toggle="tab" href="#seleksi">
                                    <i class="red ace-icon fa fa-paste bigger-120"></i>
                                    Seleksi Mandiri
                                </a>
                            </li>
                            <li class="">
                                <a data-toggle="tab" href="#card">
                                    <i class="blue ace-icon fa fa-upload bigger-120"></i>
                                    Berkas Persyaratan
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#ortu">
                                    <i class="purple ace-icon fa fa-users bigger-120"></i>
                                    Orang Tua/Wali
                                </a>
                            </li>
                            <li>
                                <a data-toggle="tab" href="#payment">
                                    <i class="orange ace-icon fa fa-money bigger-120"></i>
                                    Pembayaran
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content no-border padding-24">
                            
                            <div id="home" class="tab-pane in active">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-2 center">
                                        <span class="profile-picture">
                                            <img src="<?= load_file($detail['foto_mhs'], 1) ?>" id="avatar" class="img-responsive" />
                                        </span>
                                        <div class="space space-4"></div>
                                        <a href="<?= site_url($module .'/edit/'. encode($detail['id_mhs'])) ?>" class="btn btn-block btn-bold btn-warning btn-white">
                                            <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                            <span class="">Ubah Profil</span>
                                        </a>
                                        <div class="space space-4"></div>
                                        <?= $nilai_seleksi ?>
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
                                                        <div class="profile-info-name"> KIP Mahasiswa </div>
                                                        <div class="profile-info-value">
                                                            <span><?= $detail['kip_mhs'] ?></span>
                                                        </div>
                                                    </div>
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
                                                            <span><?= element('nama_wilayah', $kecamatan) ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Kota/Kabupaten </div>
                                                        <div class="profile-info-value">
                                                            <span><?= element('nama_wilayah', $kabupaten) ?></span>
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
                            <div id="seleksi" class="tab-pane">
                                <div class="profile-feed row">
                                    <?php $this->load->view('master/daftar/v_seleksi'); ?>
                                </div><!-- /.row -->
                            </div><!-- /#feed -->
                            
                            <div id="ortu" class="tab-pane">
                                <div class="profile-feed row">
                                    <?php $this->load->view('master/daftar/v_ortu'); ?>
                                </div><!-- /.row -->
                            </div><!-- /#feed -->
                            
                            <div id="card" class="tab-pane">
                                <div class="profile-feed row">
                                    <?php $this->load->view('master/daftar/v_berkas'); ?>
                                </div><!-- /.row -->
                            </div>
                            
                            <div id="payment" class="tab-pane">
                                <div class="profile-feed row">
                                    <?php $this->load->view('master/daftar/v_payment'); ?>
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
