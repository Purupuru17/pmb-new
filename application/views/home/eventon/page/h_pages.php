<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Sub Banner -->
<section class="sub-banner newsection">
    <div class="container">
        <h2 class="title"><?= $detail['judul_page'] ?></h2>
    </div>
</section>
<!-- Events -->
<section class="events newsection" style="padding-top:20px;">
    <div class="container">
        <div class="row">
            <div class="single-blog col-md-12">
                <section class="event-container clearfix">
                    <div class="event clearfix">
                        <div class="eventsimg">
                            <img class="lazyload blur-up" src="<?= load_file($detail['foto_page']) ?>" alt="<?= ctk($detail['judul_page']) ?>">
                        </div>
                        <div class="event-content" style="display:none;">
                            <h3 class="title"><a href="#"><?= $detail['judul_page'] ?></a></h3>
                            <ul class="meta clearfix">
                                <li class="date"><i class="icon fa fa-calendar"></i><?= format_date($detail['update_page'],0) ?></li>
                                <li><a href="#"><i class="icon fa fa-user"></i>by <?= $detail['log_page'] ?></a></li>
                            </ul>
                            
                        </div>
                        <p><?= ($detail['isi_page']) ?></p>
                    </div>
                </section>
            </div>
            <!-- col-md-3 -->
            <div class="col-md-3" style="display:none;">
                <aside id="aside" class="aside-bar-style-two clearfix">
                    <div class="header">
                        <small>Cari yang anda inginkan dalam</small>
                        <h2 class="title">ARTIKEL</h2>
                        <span class="arrow-down"></span>
                    </div>
                    <div class="widget border-remove clearfix">
                        <div class="eventform-con ">
                            <form method="GET" action="<?= site_url('tag/all') ?>" name="form-search">
                                <div class="form-input search-location">
                                    <input type="text" required="" name="q" placeholder="Kata Kunci..." >
                                    <button type="submit" class="icon fa fa-search"></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="widget categories clearfix">
                        <h3 class="title">Artikel Populer</h3>
                        <ul class="the-berita">
                            
                        </ul>
                    </div>
                    <div class="widget">
                        <h3 class="title">Kutipan</h3>  
                        <div class="owl-testimonial">
                            
                            <div class="testimonials">
                                <div class="testimonials-content">
                                    <p> ????? </p>
                                    <span class="arrow-down"></span>
                                </div>
                                <p class="name">by  ????? </p>
                            </div>
                            
                            
                        </div>
                    </div>
                </aside>
            </div>
        </div> 
    </div> 
</section>
<script async type="text/javascript">
    $(document).ready(function () {
        //Populer
        $.ajax({
            url: module + "/ajax/type/list/source/artikel",
            type: 'POST', dataType: "json",
            data: { jenis: '', tipe: 'populer', order: 'RANDOM', limit: 10,
                [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey')
            },
            success: function (result) {
                $.each(result.data, function (index, value) {
                     $(".the-berita").append(`<li><a href="${value.slug}">
                        <strong>${value.judul}</strong>
                        <span class="numbers">${value.view}</span>
                    </a></li>`);
                });
            },
            error: function (xhr, ajax, err) {
                console.log('Error : ' + xhr.responseText);
            }
        });
    });
</script>
