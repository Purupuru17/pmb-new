<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Sub-banner -->      
<section class="sub-banner newsection">
    <div class="container">
        <h2 class="title">Detail Artikel</h2>
    </div>
</section>

<!-- Events -->
<section class="events text-left newsection">
    <div class="container">
        <div class="row">
            <!-- col-md-9 -->
            <div class="col-md-9 col-sm-9">
                <!--Event Detail  -->
                <section class="event-detail newsection">
                    <h2 class="main-title ">
                        <a href="#"><?= $detail['judul_artikel'] ?></a>
                    </h2>
                    <!-- meta -->
                    <ul class="meta clearfix">
                        <li class="date"><i class="icon fa fa-calendar"></i><?= format_date($detail['update_artikel'],0) ?></li>
                        <li><a href="#"><i class="icon fa fa-user"></i><?= $detail['log_artikel'] ?></a></li>
                        <li><a href="#"><i class="icon fa fa-bookmark"></i><?= $detail['judul_jenis'] ?></a></li>
                    </ul>
                    <!-- event-detail-img -->
                    <div class="event-detail-img">
                        <img class="lazyload blur-up" src="<?= load_file($detail['foto_artikel']) ?>" alt="<?= ctk($detail['judul_artikel']) ?>">
                    </div>
                    <!-- Social Icon -->
                    <div class="social-icon">
                        <a class="facebook facebook-pagle" style="background-color:<?= $detail['color_jenis'] ?>; text-transform: uppercase" href="<?= site_url('tag/' . $detail['slug_jenis']) ?>"><?= $detail['judul_jenis'] ?></a>
                        
                        <a href="whatsapp://send?text=<?= site_url(uri_string()) ?>" class="fa linkedin">WA</a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= site_url(uri_string()) ?>" target="_blank" class="facebook fa fa-facebook"></a>
                        <a href="https://twitter.com/intent/tweet?text=<?= site_url(uri_string()) ?>" target="_blank"  class="twitter fa fa-twitter"></a>
                        <a href="#" class=" googleplus fa fa-google-plus"></a>
                        <a href="#" class="linkedin fa fa-linkedin"></a>
                    </div>
                    <p><?= $detail['isi_artikel'] ?></p>
                </section>
            </div>
            <!-- Col-md-3 -->
            <div class="col-md-3 col-sm-3">
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
                    <div class="widget tag">
                        <h3 class="title">Kategori</h3>
                        <div class="the-category"></div>
                    </div>
                    <div class="widget clearfix">
                        <h3 class="title">Artikel Terbaru</h3>
                        <div class="the-berita"></div>
                    </div> 
                </aside>
            </div>
        </div> 
    </div> 
</section>
<script async type="text/javascript">
    $(document).ready(function () {
        //Terbaru
        $.ajax({
            url: module + "/ajax/type/list/source/artikel",
            type: 'POST', dataType: "json",
            data: { jenis: '', tipe: '', order: 'DESC', limit: 10,
                [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey')
            },
            success: function (result) {
                $.each(result.data, function (index, value) {
                    $(".the-berita").append(`<div class="top-ppost">
                        <div class="date">
                            <p>${value.update}</p>
                        </div>
                        <div class="content">
                            <h4 class="title"><a href="${value.slug}">${value.judul}</a></h4>
                            <a href="${value.tag}" class="meta">
                                <i class="icon fa fa-bookmark"></i>${value.jenis}
                            </a>
                    </div></div>`);
                });
            },
            error: function (xhr, ajax, err) {
                console.log('Error : ' + xhr.responseText);
            }
        });
        //Jenis
        $.ajax({
            url: module + "/ajax/type/list/source/jenis",
            type: 'POST', dataType: "json",
            data: {
                [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey')
            },
            success: function (result) {
                $.each(result.data, function (index, value) {
                    $(".the-category").append(`<a href="${value.slug_jenis}">${value.judul_jenis} (${value.total_artikel})</a>`);
                });
            },
            error: function (xhr, ajax, err) {
                console.log('Error : ' + xhr.responseText);
            }
        });
    });
</script>