<!-- Sub-banner -->      
<section class="sub-banner newsection">
    <div class="container">
        <h2 class="title">Galeri Foto</h2>
    </div>
</section>
<!-- Events -->
<section class="newsection gallery">
    <div class="container">
        <div class="row">
            <?php
            foreach ($galeri['data'] as $gl) {
                ?>
                <div class="col-md-3 col-sm-3">
                    <div class="event-gallery-content">
                        <div class="gallery-event-img">
                            <a href="<?= site_url('galeri/detail/' . $gl['slug_galeri']) ?>">
                                <img  class="lazyload blur-up" src="<?= load_file($gl['foto_galeri']) ?>" alt="<?= ctk($gl['judul_galeri']) ?>">
                            </a>
                        </div>
                        <div class="content">
                            <a href="<?= site_url('galeri/detail/' . $gl['slug_galeri']) ?>">
                                <h3 class="title"><?= $gl['judul_galeri'] ?></h3>
                            </a>
                            <p><?= format_date($gl['update_galeri'],0) ?></p>
                        </div>
                    </div>
                </div> 
            <?php } ?>
        </div>
        <!-- Pagination -->
        <ul class="pagination clearfix">
            <?= $pagination ?>
        </ul>
    </div>    
</section>