<!-- Sub-banner -->      
<section class="sub-banner newsection">
    <div class="container">
        <h2 class="title"><?= $title ?></h2>
    </div>
</section>
<!-- Events -->
<section class="events newsection">
    <!-- Container -->
    <div class="container">
        <div class="row">
            <div class="col-md-9 col-sm-9 pull-right">
                <!-- eventform-con -->
                <div class=" eventform-con  fielder-items clearfix">
                    <!-- Event Filter -->
                    <ul class="event-filter">
                        <li class="filter stylelist"><i class=" fa fa-th-list"></i></li>
                        <li class="filter stylegrid"><i class=" fa fa-th"></i></li>
                    </ul>
                </div>
                <!-- Item Grid & Item List -->
                <div class="grid-list event-container clearfix">
                    <div class="row">
                        <?php
                        foreach ($terbaru['data'] as $tb) {
                            ?>
                            <!-- Event -->
                            <div class="event-border col-md-4">
                                <div class="event clearfix">
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
                                            <li class="date"><i class="icon fa fa-calendar"></i><?= format_date($tb['update_artikel'], 0) ?></li>
                                            <li><a href="#"><i class="icon fa fa-user"></i><?= $tb['log_artikel'] ?></a></li>
                                            <li><a href="#"><i class="icon fa fa-bookmark"></i><?= $tb['judul_jenis'] ?></a></li>
                                        </ul>
                                        <p><?= limit_text($tb['isi_artikel'], 100) ?></p>
                                        <a href="<?= site_url('tag/' . $tb['slug_jenis']) ?>" class="btn btn-pri" style="background-color:<?= $tb['color_jenis'] ?>"><?= $tb['judul_jenis'] ?></a>
                                        <a href="<?= site_url('artikel/' . $tb['slug_artikel']) ?>" class="btn btn-border">Selengkapnya</a>
                                    </div>

                                    <div class="links clearfix">
                                        <ul>
                                            <li><a class="st_sharethis_large" ><i class="icon fa fa-share"></i> Bagikan</a></li>
                                            <li><a href="#"><i class="icon fa fa-eye"></i><?= angka($tb['view_artikel']) ?></a></li>
                                            <li><a href="#" ><i class="icon fa fa-heart"></i>0</a> </li> 
                                        </ul> 
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </div> 
                <!-- Pagination -->
                <ul class="pagination clearfix">
                    <?= $pagination ?>
                </ul>
            </div>
            <!-- col-md-3 -->
            <div class="col-md-3 col-sm-3 ">
                <aside id="aside" class="clearfix">
                    <div class="header">
                        <small>Cari yang anda inginkan dalam</small>
                        <h2 class="title">ARTIKEL</h2>
                        <span class="arrow-down"></span>
                    </div>
                    <div class="widget clearfix">
                        <div class="eventform-con">
                            <form method="GET" action="<?= site_url('tag/all') ?>">
                                <div class="form-input search-location">
                                    <input type="text" required="" name="q" placeholder="Kata Kunci...">
                                    <i class="icon icon-s fa fa-pencil-square-o"></i>
                                    <button type="submit" class="icon fa fa-search"></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="widget tag">
                        <h3 class="title">Kategori</h3>
                        <div class="the-category"></div>
                    </div>  
                    <div class="widget">
                        <h3 class="title">Kutipan</h3>  
                        <div class="owl-testimonial the-kutipan">
                            
                        </div>
                    </div>
                </aside>
            </div> 
        </div>
    </div>  
</section>
<script async type="text/javascript">
    $(document).ready(function () {
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
        //Kutipan
        $.ajax({
            url: module + "/ajax/type/list/source/kutip",
            type: 'POST', dataType: "json",
            data: { order: 'RANDOM', limit: 3,
                [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey')
            },
            success: function (result) {
                $.each(result.data, function (index, value) {
                    $(".the-kutipan").append(`<div class="testimonials">
                        <div class="testimonials-content">
                            <p>${value.quote}</p>
                            <span class="arrow-down"></span>
                        </div>
                        <p class="name">by ${value.oleh}</p>
                    </div><br>`);
                });
            },
            error: function (xhr, ajax, err) {
                console.log('Error : ' + xhr.responseText);
            }
        });
    });
</script>