<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html class="no-js">
    <head>
        <?php
            $param = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
            $meta = isset($meta) ? $meta : [];
            $meta_title_default = isset($breadcrumb) ? breadcrumb($breadcrumb, 'title') : ctk($app_session['deskripsi']);
            $meta_desc_default = ctk($app_session['deskripsi']);
            $meta_author_default = $app_session['judul'];
            $meta_url_default  = current_url() . $param;
            $meta_img_default  = base_url($app_session['logo']);    
        ?>
        <title><?php echo element('title', $meta, $meta_title_default).' | '.$app_session['judul']; ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="Copyright" content="<?php echo element('author', $meta, $meta_author_default); ?>" />
        
        <!-- Mobile on Android -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, shrink-to-fit=no">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="theme-color" content="<?= $app_theme['webcolor'] ?>" />
        <meta name="application-name" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app_session['judul']; ?>">
        <meta name="msapplication-navbutton-color" content="<?= $app_theme['webcolor'] ?>">   
        <!-- Mobile on iOS -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="<?= $app_theme['webcolor'] ?>">
        <meta name="apple-mobile-web-app-title" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app_session['judul']; ?>">   
        
        <link rel="shortcut icon" type="image/x-icon" href="<?= load_file('private/logo.png') ?>"/>  
        <link rel="manifest" href="<?= base_url('manifest.json') ?>">
        <link rel="canonical" href="<?php echo element('url', $meta, $meta_url_default); ?>">
        <link rel="amphtml" href="<?php echo element('amp_url', $meta, $meta_url_default); ?>">
        
        <!-- SEARCH ENGINE -->
        <meta name="keywords" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app_session['judul']; ?>" />
        <meta name="description" content="<?php echo limit_text(element('description', $meta, $meta_desc_default),200); ?>">
        <meta name="author" content="<?php echo element('author', $meta, $meta_author_default); ?>">
        <meta name="rating" content="general">
        
        <meta itemprop="name" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app_session['judul']; ?>" />
        <meta itemprop="description" content="<?php echo limit_text(element('description', $meta, $meta_desc_default),200); ?>" />
        <meta itemprop="image" content="<?php echo element('thumbnail', $meta, $meta_img_default); ?>" />

        <!-- FACEBOOK META -  Change what to your own FB-->
        <meta property="fb:app_id" content="MY_FB_ID">
        <meta property="fb:pages" content="MY_FB_FAGE_ID" />
        <meta property="og:title" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app_session['judul']; ?>">
        <meta property="og:type" content="article">
        <meta property="og:url" content="<?php echo element('url', $meta, $meta_url_default); ?>">
        <meta property="og:site_name" content="<?php echo element('author', $meta, $meta_author_default); ?>">
        <meta property="og:image" content="<?php echo element('thumbnail', $meta, $meta_img_default); ?>" >
        <meta property="og:description" content="<?php echo limit_text(element('description', $meta, $meta_desc_default),200); ?>">
        
        <meta property="article:publisher" content="<?php echo element('author', $meta, $meta_author_default); ?>">
        <meta property="article:author" content="<?php echo element('author', $meta, $meta_author_default); ?>">
        <meta property="article:tag" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app_session['judul']; ?>">

        <!-- TWITTER META - Change what to your own twitter-->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:description" content="<?php echo limit_text(element('description', $meta, $meta_desc_default),200); ?>">
        <meta name="twitter:site" content="<?php echo element('author', $meta, $meta_author_default); ?>">
        <meta name="twitter:creator" content="@my_twitter">
        <meta name="twitter:title" content="<?php echo element('title', $meta, $meta_title_default).' | '.$app_session['judul']; ?>">
        <meta name="twitter:image:src" content="<?php echo element('thumbnail', $meta, $meta_img_default); ?>"> 
        <meta name="twitter:domain" content="<?php echo element('url', $meta, $meta_url_default); ?>" />
        
        <?php
        load_css(array(
            "theme/eventon/css/font-awesome.min.css",
            "theme/eventon/css/bootstrap.min.css",
            "theme/eventon/css/source-sans.css?family=Source+Sans+Pro:200,300,400,600,700,900,200italic,300italic,400italic,600italic,700italic,900italic",
            "theme/eventon/css/jquery.ui.theme.css",
            "theme/eventon/css/owl.carousel.css",
            "theme/eventon/css/main.css"
        ));
        
        load_js(array(
            "theme/eventon/js/vendor/jquery.min.js",
            "theme/eventon/js/vendor/modernizr-2.6.2.min.js",
            "theme/eventon/js/vendor/cloudflare.js"
        ));
        ?>
        <style>
            .site-identity {
                float: left;
                width: 70%;
                line-height: 1;
            }
            .site-identity h1 {
                font-size: 2em !important;
                margin: .2em 0 0 0;
                display: block;
                font-weight: bold;
                line-height: 1em !important;
            }
            .site-identity h1 a{
                color: white;
            }
            .site-identity span {
                font-size: 1.2em;
                display: inline-block;
                font-weight: bold;
                color: white;
            }
            .site-identity img {
                max-width: 70px;
                float: left;
                margin: 0 10px 0 0;
            }
            .main-nav{
                float: left;
            }
            .main-nav li {
                padding: 5px 0 !important;
                margin-right: 20px;
            }
            .main-nav a{
                font-size: 14px;
                font-weight: bold;
                text-transform: uppercase;
                color: #ffffff;
            }
            .background{
                background: none;
            }
            .white{
                color: white !important;
            }
            iframe{
                display: block;
                vertical-align: baseline;
                top: 0;
                left: 0;
                background-color: transparent;
                object-fit: cover;
                -o-object-fit: cover;
                -o-object-position: center;
                object-position: center;
            }
            .sub-banner {
                padding: 10px 0;
            }
        </style>
    </head>
    <body>
        <!-- Header -->
        <?php $this->load->view('home/'.APP_THEME.'/h_header'); ?>

        <?php
        if($this->uri->segment(1) === '' || is_null($this->uri->segment(1))){
        ?>
        <!-- Banner -->
        <div class="banner" style="background: none;">
            <div class="container">
                <div class="center" style="padding-top: 20px;padding-bottom: 20px;">
                    <div class="the-galeri">
                        
                    </div>
                </div>     
            </div>
        </div>
        <!-- banner -->
        <?php } ?>

        <?= $content ?> 

        <!-- Footer -->
        <?php $this->load->view('home/'.APP_THEME.'/h_footer'); ?>
        <?php
        load_js(array(
            "theme/aceadmin/assets/js/lazy/lazysizes.min.js",
            "theme/eventon/js/plugins.js",
            "theme/eventon/js/main.js"
        ));
        ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/UpUp/1.0.0/upup.min.js"></script>
        <script async type="text/javascript">
            const module = "<?= site_url($module) ?>";
            const color_utama = "<?= $app_theme['webcolor'] ?>";
            const color_kedua = "<?= $app_theme['webcolor_other'] ?>";
            const filesToCache = [
                "private/logo.png",
                "theme/aceadmin/assets/js/lazy/lazysizes.min.js",

                "theme/eventon/css/font-awesome.min.css",
                "theme/eventon/css/jquery.datetimepicker.css",
                "theme/eventon/css/bootstrap.min.css",
                "theme/eventon/css/main.css",
                "theme/eventon/css/source-sans.css",
                "theme/eventon/css/jquery.ui.theme.css",
                "theme/eventon/css/owl.carousel.css",

                "theme/eventon/js/vendor/modernizr-2.6.2.min.js",
                "theme/eventon/js/vendor/cloudflare.js",
                "theme/eventon/js/vendor/buttons.js",
                "theme/eventon/js/vendor/jquery.min.js",
                "theme/eventon/js/plugins.js",
                "theme/eventon/js/main.js"
            ];
            $(function() {
                jsfHome();
                jsfTheme();
            });
            function jsfHome() {
                //Navigasi Menu
                $.ajax({
                    url: module + "/ajax/type/list/source/menu",
                    type: 'POST', dataType: "json",
                    data: {
                        [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey')
                    },
                    success: function (rs) {
                        //Menu
                        let menuHTML = '<li><a href="<?= site_url() ?>">Beranda</a></li>';
                        const parentMenus = rs.data.filter(menu => menu.parent_nav == 0);
                        parentMenus.forEach(parentMenu => {
                            const subMenus = rs.data.filter(menu => menu.parent_nav == parentMenu.id_nav);
                            if (subMenus.length > 0) {
                                menuHTML += `<li class="parent"><a href="${parentMenu.url_nav}"><span>${parentMenu.judul_nav}</span></a>
                                        <ul class="sub-menu"><li class="arrow"></li>${subMenus.map(subMenu => `<li><a href="${subMenu.url_nav}">${subMenu.judul_nav}</a></li>`).join('')}</ul>
                                    </li>`;
                            } else {
                                menuHTML += `<li><a href="${parentMenu.url_nav}">${parentMenu.judul_nav}</a></li>`;
                            }
                        });
                        $(".the-menu").html(menuHTML);
                    },
                    error: function (xhr, ajax, err) {
                        console.log('Error : ' + xhr.responseText);
                    }
                });
                //Slide Galeri
                $.ajax({
                    url: module + "/ajax/type/list/source/galeri",
                    type: 'POST', dataType: "json",
                    data: { jenis: '0', order: 'DESC', limit: 5,
                        [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey')
                    },
                    success: function (result) {
                        $.each(result.data, function (index, value) {
                            $(".the-galeri").append(`<div class="testimonials">
                                <div class="testimonials-content">
                                <a href="${value.slug}">
                                    <img  class="lazyload blur-up" src="${value.foto}" alt="${value.judul}">
                                </a>
                            </div></div>`);
                        });
                        if($(".the-galeri").length > 0){ 
                            $(".the-galeri").owlCarousel({
                                items:1,
                                loop:true,
                                margin:20,
                                dots:false,
                                nav:false,
                                autoplay:true,
                                navSpeed:600,
                                responsive:{
                                    1170:{
                                        items:1
                                    }
                                }
                            });
                        }
                    },
                    error: function (xhr, ajax, err) {
                        console.log('Error : ' + xhr.responseText);
                    }
                });
                //Visitor
                $.ajax({
                    url: "<?= site_url('non_login/login/ajax/type/action/source/autoload'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {
                        [$('meta[name="csrf-token"]').attr('content')] : $('meta[name="csrf-token"]').attr('accesskey'),
                        page_url: window.location.href,
                        referrer: document.referrer,
                        page_name: window.location.pathname.replace(/^\/+|\/+$/g, ''),
                        query_string: window.location.search.replace(/^\?/, '')
                    },
                    success: function(rs) {
                        if (rs.status) {
                            console.log(rs.data);
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(thrownError);
                    }
                });
            }
            function jsfTheme() {
                UpUp.start({
                    'cache-version': '<?= APP_VER ?>', 'content-url': '<?= site_url() ?>',
                    'content': 'No Internet Connection',
                    'service-worker-url': "<?= base_url('sw.js') ?>", 'assets': filesToCache
                });
                //Waktu
                setInterval(function () {
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
                    $(".waktu").html(waktu);
                }, 1000);
                //Theme
                setTimeout(function() {
                    $('body').addClass('loaded');
                    
                    $(".main-header").css("background", color_utama);
                    $(".header-top, .main-footer, .find-events a").
                        css("background", color_kedua);
                }, 1000);
            }
        </script>
    </body>
</html>