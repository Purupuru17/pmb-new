<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html class="no-js">
    <head>
        <!-- Basic page needs ============================================ -->
        <?php
            $param = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
            $meta = isset($meta) ? $meta : [];
            $meta_title_default = $app['deskripsi'];
            $meta_desc_default = $app['deskripsi'];
            $meta_author_default = $app['cipta'];
            $meta_url_default = current_url() . $param;
            $meta_img_default = load_file('app/img/logo.png', 1);
        ?>
        <title><?php echo element('title', $meta, $meta_title_default).' | '.$app['judul']; ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="Copyright" content="<?php echo element('author', $meta, $meta_author_default); ?>" />
        
        <!-- Mobile on Android -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, shrink-to-fit=no">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="theme-color" content="<?= $theme[10] ?>" />
        <meta name="application-name" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app['judul']; ?>">
        <meta name="msapplication-navbutton-color" content="<?= $theme[10] ?>">   
        <!-- Mobile on iOS -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="<?= $theme[10] ?>">
        <meta name="apple-mobile-web-app-title" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app['judul']; ?>">   
        
        <link rel="shortcut icon" type="image/x-icon" href="<?= load_file('app/img/logo.png') ?>"/>  
        <link rel="manifest" href="<?= base_url('manifest.json') ?>">
        <link rel="canonical" href="<?php echo element('url', $meta, $meta_url_default); ?>">
        <link rel="amphtml" href="<?php echo element('amp_url', $meta, $meta_url_default); ?>">
        
        <!-- SEARCH ENGINE -->
        <meta name="keywords" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app['judul']; ?>" />
        <meta name="description" content="<?php echo limit_text(element('description', $meta, $meta_desc_default),200); ?>">
        <meta name="author" content="<?php echo element('author', $meta, $meta_author_default); ?>">
        <meta name="rating" content="general">
        
        <meta itemprop="name" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app['judul']; ?>" />
        <meta itemprop="description" content="<?php echo limit_text(element('description', $meta, $meta_desc_default),200); ?>" />
        <meta itemprop="image" content="<?php echo element('thumbnail', $meta, $meta_img_default); ?>" />

        <!-- FACEBOOK META -  Change what to your own FB-->
        <meta property="fb:app_id" content="MY_FB_ID">
        <meta property="fb:pages" content="MY_FB_FAGE_ID" />
        <meta property="og:title" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app['judul']; ?>">
        <meta property="og:type" content="article">
        <meta property="og:url" content="<?php echo element('url', $meta, $meta_url_default); ?>">
        <meta property="og:site_name" content="<?php echo element('author', $meta, $meta_author_default); ?>">
        <meta property="og:image" content="<?php echo element('thumbnail', $meta, $meta_img_default); ?>" >
        <meta property="og:description" content="<?php echo limit_text(element('description', $meta, $meta_desc_default),200); ?>">
        
        <meta property="article:publisher" content="<?php echo element('author', $meta, $meta_author_default); ?>">
        <meta property="article:author" content="<?php echo element('author', $meta, $meta_author_default); ?>">
        <meta property="article:tag" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app['judul']; ?>">

        <!-- TWITTER META - Change what to your own twitter-->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:description" content="<?php echo limit_text(element('description', $meta, $meta_desc_default),200); ?>">
        <meta name="twitter:site" content="<?php echo element('author', $meta, $meta_author_default); ?>">
        <meta name="twitter:creator" content="@my_twitter">
        <meta name="twitter:title" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app['judul']; ?>">
        <meta name="twitter:image:src" content="<?php echo element('thumbnail', $meta, $meta_img_default); ?>"> 
        <meta name="twitter:domain" content="<?php echo element('url', $meta, $meta_url_default); ?>" />
        
        <?php
        load_css(array(
            "frontend/eventon/css/font-awesome.min.css",
            "frontend/eventon/css/bootstrap.min.css",
            "frontend/eventon/css/source-sans.css?family=Source+Sans+Pro:200,300,400,600,700,900,200italic,300italic,400italic,600italic,700italic,900italic",
            "frontend/eventon/css/jquery.ui.theme.css",
            "frontend/eventon/css/owl.carousel.css",
            "frontend/eventon/css/main.css"
        ));
        
        load_js(array(
            "frontend/eventon/js/vendor/jquery.min.js",
            "frontend/eventon/js/vendor/modernizr-2.6.2.min.js",
            "frontend/eventon/js/vendor/cloudflare.js"
        ));
        ?>
    </head>
    <body>
        <!-- Header -->
        <?php $this->load->view('home/h_header'); ?>

        <?php
        if($this->uri->segment(1) === '' || is_null($this->uri->segment(1))){
        ?>
        <!-- Banner -->
        <div class="banner" style="background: none;">
            <div class="container">
                <div class="center" style="padding-top: 20px;padding-bottom: 20px;">
                    <div class="owl-testimonial">
                        <?php
                        foreach ($galeri['data'] as $bk) {
                        ?>
                        <div class="testimonials">
                            <div class="testimonials-content">
                                <a href="<?= site_url('galeri/' . $bk['slug_galeri']) ?>">
                                    <img  class="lazyload blur-up" src="<?= load_file($bk['foto_galeri']) ?>" alt="<?= $bk['judul_galeri'] ?>">
                                </a>
                            </div>
                        </div>
                        <?php } ?> 
                    </div>
                    
                </div>     
            </div>
        </div>
        <!-- banner -->
        <?php } ?>

        <?= $content ?> 

        <!-- Footer -->
        <?php $this->load->view('home/h_footer'); ?>
        <?php
        load_js(array(
            "backend/assets/js/lazy/lazysizes.min.js",
            "frontend/eventon/js/plugins.js",
            "frontend/eventon/js/main.js"
        ));
        ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/UpUp/1.0.0/upup.min.js"></script>
        <script async type="text/javascript">
            $(document).ready(function() {
                const filesToCache = [
                    "app/backend/puru.css",
                    "app/img/logo.png",
                    "app/backend/assets/js/lazy/lazysizes.min.js",
                    
                    "app/frontend/eventon/css/font-awesome.min.css",
                    "app/frontend/eventon/css/jquery.datetimepicker.css",
                    "app/frontend/eventon/css/bootstrap.min.css",
                    "app/frontend/eventon/css/main.css",
                    "app/frontend/eventon/css/source-sans.css",
                    "app/frontend/eventon/css/jquery.ui.theme.css",
                    "app/frontend/eventon/css/owl.carousel.css",
                    
                    "app/frontend/eventon/js/vendor/modernizr-2.6.2.min.js",
                    "app/frontend/eventon/js/vendor/cloudflare.js",
                    "app/frontend/eventon/js/vendor/buttons.js",
                    "app/frontend/eventon/js/vendor/jquery.min.js",
                    "app/frontend/eventon/js/plugins.js",
                    "app/frontend/eventon/js/main.js"
                ];
                UpUp.start({
                    'cache-version': '<?= APP_VER ?>',
                    'content-url': '<?= site_url() ?>',
                    'content': 'No Internet Connection',
                    'service-worker-url': "<?= base_url('sw.js') ?>",
                    'assets': filesToCache
                });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                setInterval(function timer() {
                    now = new Date();
                    if (now.getTimezoneOffset() == 0)
                        (a = now.getTime() + (7 * 60 * 60 * 1000))
                    else
                        (a = now.getTime());
                    now.setTime(a);
                    var tahun = now.getFullYear()
                    var hari = now.getDay()
                    var bulan = now.getMonth()
                    var tanggal = now.getDate()
                    var hariarray = new Array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu")
                    var bulanarray = new Array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember")

                    var waktu = hariarray[hari] + ", " + tanggal + " " + bulanarray[bulan] + " " + tahun + " | " + (((now.getHours() < 10) ? "0" : "") + now.getHours() + ":" + ((now.getMinutes() < 10) ? "0" : "") + now.getMinutes() + ":" + ((now.getSeconds() < 10) ? "0" : "") + now.getSeconds() + (" WIT "));
                    $("#jam").html(waktu);
                }, 1000);
                
                setTimeout(function() {
                    $('body').addClass('loaded');
                }, 2000);
                
                var color_satu = "<?= $theme[10] ?>";
                var color_dua = "<?= $theme[11] ?>";

//                jQuery(".header, .breaking-news .the-title, .widget > h3, .button, .hover-effect, .block-title, #wp-calendar td#today, .small-button, #writecomment p input[type=submit]").css("background-color", color);
//                jQuery(".widget .meta a, .mobile-menu, .viewer").css("color", color);
                jQuery(".main-header").css("background", color_satu);
                jQuery(".header-top, .main-footer, .find-events a").css("background", color_dua);

                return false;
            });
        </script>
    </body>
</html>