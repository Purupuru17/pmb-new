<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Background -->
<section class="newsection background clearfix" style="margin-bottom: 0px">
    <div class="container">
        <div class="border-cover">
            <div class="background-content clearfix">
                <h2 class="title">Ayo Gabung Bersama Kami !</h2>
                <p>
                    Silahkan buat Akun dan lengkapi Data Diri anda dengan klik <b>DAFTAR</b>.<br/>
                    Jika ada pertanyaan dan ingin berkonsultasi, silahkan klik <b>KONSULTASI</b>
                </p>
                <div class="both-btn clearfix">
                    <div class="but-ticket">
                        <a style="background:#efbd05 !important" target="_blank" href="<?= site_url('register') ?>">Klik <b>DAFTAR!</b></a>
                    </div>

                    <div class="but-ticket">
                        <a target="_blank" href="https://api.whatsapp.com/send?phone=<?= config_item('kampus')['wa'] ?>&text=Hai <?= ctk($app_session['judul']) ?>"> Klik <b>KONSULTASI!</b></a>
                    </div>

                    <span class="round">atau</span>
                </div>

            </div>   
        </div>
    </div>
</section>

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
                            <p>Buat Akun Pendaftaran untuk mendapatkan akses ke Sistem pada menu Daftar/Registrasi</p>
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
                            <h3 class="title">CAMABA</h3>
                            <p>Selamat anda telah Resmi menjadi Calon Mahasiswa Baru <?= date('Y') ?>. Siapkan diri anda dalam kegiatan pengenalan kampus</p>
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
        <div class="event-container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="event">
                        <div class="event-content">
                            <iframe width="100%" height="515" src="<?= config_item('kampus')['youtube'] ?>" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- row -->
    </div>  
</section> 
<!-- Events -->

<!-- Background  -->
<!--<section class="background clearfix newsection">
    <div class="container">
        <div class="border-cover">
            <div class="background-content">
                <h2 class="title">Jadwal Pendaftaran</h2>
                <p></p> 
                <div class="row ">
                    <div class="col-md-4 col-sm-6">
                        <div class="event">
                            <div class="eventsicon">
                                <strong>I</strong>
                            </div>
                            <div class="event-content">
                                <h3 style="color:#5ab4e6">12 Desember 2024 - 30 Agustus 2025</h3>
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
</section>-->

<!-- Events -->
<section class="events newsection">
    <div class="container">
        <h2 class="title">Agenda dan Berita</h2>
        <p class="contentcenter">
            Segala update informasi berupa Agenda, Berita dan Pengumuman akan 
            disampaikan melalui Website resmi ini
        </p>
        <div class="event-container">
            <div class="row the-berita">
            </div>

        </div>
        <a href="<?= site_url('tag/all') ?>" class="btn btn-border btn-lg">Lebih Banyak</a>
    </div>  
</section>  
<script async type="text/javascript">
    $(document).ready(function() {
        //Berita
        $.ajax({
            url: module + "/ajax/type/list/source/artikel",
            type: 'POST', dataType: "json",
            data: { jenis: 'berita', tipe: '', order: 'DESC', limit: 12,
                [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey')
            },
            success: function (result) {
                $.each(result.data, function (index, value) {
                    $(".the-berita").append(`<div class="col-md-3">
                        <div class="event" style="margin-bottom: 20px; min-height:460px">
                            <div class="eventsimg">
                                <a href="${value.slug}">
                                    <img style="height: 200px" class="lazyload blur-up" src="${value.foto}" alt="${value.judul}">
                                </a>
                            </div>
                            <div class="event-content">
                                <a href="${value.slug}">
                                    <h3 class="title">${value.judul}</h3>
                                </a>
                                <ul class="meta">
                                    <li>${value.update}</li>
                                </ul>
                                <span class="sep"></span>
                                <p>${value.isi}</p>
                                <a href="${value.slug}" class="btn btn-pri" style="background-color:${value.color}">${value.jenis}</a>
                            </div>
                            <div class="links clearfix">
                                <ul>
                                    <li><a href="${value.slug}"><i class="icon fa fa-share"></i></a></li>
                                    <li><a href="#"><i class="icon fa fa-eye"></i>${value.view}</a></li>
                                    <li><a href="#" ><i class="icon fa fa-heart"></i>0</a> </li> 
                                </ul> 
                            </div>
                        </div>
                    </div>`);
                });
            },
            error: function (xhr, ajax, err) {
                console.log('Error : ' + xhr.responseText);
            }
        });
    });
</script>