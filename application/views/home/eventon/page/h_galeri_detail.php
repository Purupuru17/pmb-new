<!-- Sub-banner -->      
<section class="sub-banner newsection">
    <div class="container">
        <h2 class="title">Galeri Foto</h2>
    </div>
</section>
<!-- single-event -->
<section class="single-event newsection">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <img class="lazyload blur-up" src="<?= load_file($detail['foto_galeri']) ?>" alt="<?= ctk($detail['judul_galeri']) ?>">
            </div>
            <div class="col-md-3">
                <div class="single-event-content">
                    <h2 class="title"><?= $detail['judul_galeri'] ?></h2>
                    <ul class="meta">
                        <li> <i class="icon  fa fa-calendar"></i><?= format_date($detail['update_galeri'], 0) ?></li>
                        <li> <i class="icon fa fa-user"></i><a href="#"><?= $detail['log_galeri'] ?></a></li>
                    </ul>
                    <p><?= $detail['isi_galeri'] ?></p>
                </div>
                <div class="share">
                    <h3 class="title">Bagikan</h3>
                    <div class="social-icon">
                        <a href="whatsapp://send?text=<?= site_url(uri_string()) ?>" class="fa linkedin">WA</a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= site_url(uri_string()) ?>" target="_blank" class="facebook fa fa-facebook"></a>
                        <a href="https://twitter.com/intent/tweet?text=<?= site_url(uri_string()) ?>" target="_blank"  class="twitter fa fa-twitter"></a>
                        <a href="#" class=" googleplus fa fa-google-plus"></a>
                        <a href="#" class="linkedin fa fa-linkedin"></a>
                    </div>
                </div>

            </div> 
        </div>
    </div> 
</section> 