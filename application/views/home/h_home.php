<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Background -->
<section class="newsection background clearfix">
    <div class="container">
        <div class="border-cover">
            <div class="background-content clearfix">
                <h2 class="title">Ayo Gabung Bersama UNIMUDA</h2>
                <p>
                    Silahkan buat Akun dan lengkapi Data Diri anda dengan klik <b>DAFTAR</b>.<br/>
                    Jika ada pertanyaan dan ingin berkonsultasi, silahkan klik <b>KONSULTASI</b>
                </p>
                <div class="both-btn clearfix">
                    <div class="but-ticket">
                        <a style="background:#efbd05 !important" target="_blank" href="<?= site_url('register') ?>">Klik <b>DAFTAR!</b></a>
                    </div>

                    <div class="but-ticket">
                        <a target="_blank" href="https://api.whatsapp.com/send?phone=6282397004684&text=Hai PMB UNIMUDA"> Klik <b>KONSULTASI!</b></a>
                    </div>

                    <span class="round">atau</span>
                </div>

            </div>   
        </div>
    </div>
</section>

<!-- Events -->
<section class="events">
    <div class="container">
        <div class="event-container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="event">
                        <div class="event-content">
                            <iframe width="100%" height="515" src="https://www.youtube.com/embed/ILYgYmgbCkA?autoplay=1&mute=1" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- row -->
    </div>  
</section> 
<!-- Events -->
<section class="events">
    <div class="container">
        <h2 class="title">Alur Pendaftaran Online</h2>
        <p class="contentcenter">
            Anda dapat mendaftar online melalui Smartphone, PC atau Laptop.
            Tim akan segera melakukan Verifikasi Data dan Berkas anda dalam waktu 24 jam.
        </p>
        <div class="event-container">
            <div class="row">
                <div class="col-md-3">
                    <div class="event">
                        <div class="eventsicon">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="event-content">
                            <h3 class="title">DAFTAR ONLINE</h3>
                            <p>Buat Akun untuk mendapatkan akses ke Website PMB UNIMUDA Sorong melalui link pendaftaran</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="event">
                        <div class="eventsicon">
                            <i class="fa fa-pencil-square-o"></i>
                        </div>
                        <div class="event-content">
                            <h3 class="title">MELENGKAPI DATA</h3>
                            <p>Lengkapi seluruh data yang meliputi Data Diri, Data Orang Tua/Wali dan Berkas Persyaratan </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="event">
                        <div class="eventsicon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <div class="event-content">
                            <h3 class="title">JADWAL & SELEKSI</h3>
                            <p>Tes Seleksi akan dilaksanakan secara Offline maupun Online. Jadwal Seleksi dapat dilihat langsung melalui website ini</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="event">
                        <div class="eventsicon">
                            <i class="fa fa-check-square-o"></i>
                        </div>
                        <div class="event-content">
                            <h3 class="title">MABA</h3>
                            <p>Selamat anda telah Resmi menjadi Mahasiswa Baru UNIMUDA Sorong. MATRAS <?= date('Y') ?> Siap Menanti! </p>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- row -->
    </div>  
</section>  
<!-- Background  -->
<section class="background clearfix newsection">
    <div class="container">
        <div class="border-cover">
            <div class="background-content">
                <h2 class="title">Jadwal Pendaftaran (Gelombang)</h2>
                <p></p> 
                <div class="row ">

                    <div class="col-md-4 col-sm-6">
                        <div class="event">
                            <div class="eventsicon">
                                <strong>I</strong>
                            </div>
                            <div class="event-content">
                                <h3 style="color:#5ab4e6">11 November 2023 - 17 Mei 2024</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="event">
                            <div class="eventsicon">
                                <strong>II</strong>
                            </div>
                            <div class="event-content">
                                <h3 style="color:#5ab4e6">20 Mei - 16 Agustus 2024</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 col-sm-6">
                        <div class="event">
                            <div class="eventsicon">
                                <strong>III</strong>
                            </div>
                            <div class="event-content">
                                <h3 style="color:#5ab4e6">18 Agustus - 30 September 2024</h3>
                            </div>
                        </div>
                    </div>


                </div>
            </div> 
        </div>
    </div>  
</section>

<!-- Events -->
<section class="events newsection">
    <div class="container">
        <h2 class="title">Agenda dan Berita</h2>
        <p class="contentcenter">
            Segala informasi berupa Agenda, Berita dan Pengumuman terkait Penerimaan Mahasiswa Baru (PMB) UNIMUDA Sorong akan 
            disampaikan melalui Website resmi ini.
        </p>
        <div class="event-container">
            <div class="row">
                <?php
                foreach ($terbaru['data'] as $tb) {
                    ?>
                    <div class="col-md-3">
                        <div class="event" style="margin-bottom: 20px; min-height:460px">
                            <div class="eventsimg">
                                <a href="<?= site_url('artikel/' . $tb['slug_artikel']) ?>">
                                    <img style="height: 200px" class="lazyload blur-up" src="<?= load_file($tb['foto_artikel']) ?>" alt="<?= ctk($tb['judul_artikel']) ?>">
                                </a>
                            </div>
                            <div class="event-content">
                                <a href="<?= site_url('artikel/' . $tb['slug_artikel']) ?>">
                                    <h3 class="title">
                                        <?= limit_text($tb['judul_artikel'], 20) ?>
                                    </h3>
                                </a>
                                <ul class="meta">
                                    <li><?= format_date($tb['update_artikel'], 2) ?></li>
                                </ul>
                                <span class="sep"></span>
                                <p><?= limit_text($tb['isi_artikel'], 200) ?></p>
                                <a href="<?= site_url('artikel/' . $tb['slug_artikel']) ?>" class="btn btn-pri" style="background-color:<?= $tb['color_jenis'] ?>"><?= $tb['judul_jenis'] ?></a>
                            </div>
                            <div class="links clearfix">
                                <ul>
                                    <li><a href="<?= site_url('artikel/' . $tb['slug_artikel']) ?>"><i class="icon fa fa-share"></i></a></li>
                                    <li><a href="#"><i class="icon fa fa-eye"></i><?= angka($tb['view_artikel']) ?></a></li>
                                    <li><a href="#" ><i class="icon fa fa-heart"></i>0</a> </li> 
                                </ul> 
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>

        </div>
        <a href="<?= site_url('tag/all') ?>" class="btn btn-border btn-lg">Lebih Banyak</a>
    </div>  
</section>  
