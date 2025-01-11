<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            $param = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
            $meta = isset($meta) ? $meta : [];
            $meta_title_default = $app['judul'] .' | '.ctk($app['deskripsi']);
            $meta_desc_default = ctk($app['deskripsi']);
            $meta_author_default = $app['cipta'];
            $meta_url_default  = current_url() . $param;
            $meta_img_default  = base_url($app['logo']);    
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
            'backend/assets/css/bootstrap.css',
            'backend/assets/css/font-awesome.css',
            'backend/assets/css/ace-fonts.css',
            'backend/assets/css/ace-rtl.css',
            'backend/assets/css/buatan.css',
            'backend/assets/css/jquery.gritter.css',
        ));
        load_js(array(
            'backend/assets/js/ace-extra.js',
            'backend/assets/js/jquery.js',
            'backend/assets/js/bootstrap.js',
            'backend/assets/js/jquery.validate.js',
            'backend/assets/js/ace/elements.fileinput.js',
            'backend/assets/js/ace/ace.js',
            'backend/assets/js/jquery.gritter.js',
            'backend/assets/js/lazy/lazysizes.min.js'
        ));
        ?>
        <!-- ace styles -->
        <link rel="stylesheet" href="<?= base_url('app/backend/assets/css/ace.css') ?>" class="ace-main-stylesheet" id="main-ace-style" />

    </head>

    <body class="login-layout">
        <div class="main-container">
            <div class="main-content">
                <?= $content ?>
            </div><!-- /.main-content -->
        </div><!-- /.main-container -->
        <script type="text/javascript">
            if ('ontouchstart' in document.documentElement)
                document.write("<script src='<?= base_url('app/backend/assets/js/jquery.mobile.custom.js') ?>'>" + "<" + "/script>");
            
            jQuery(function($) {
                var login = "<?= $theme[1] ?>";
                if (login === "1") {
                    $('body').attr('class', 'login-layout');
                    $('#id-text2').attr('class', 'white');
                    $('#id-company-text').attr('class', 'blue');
                } else if (login === "2") {
                    $('body').attr('class', 'login-layout blur-login');
                    $('#id-text2').attr('class', 'white');
                    $('#id-company-text').attr('class', 'light-blue');
                } else {
                    $('body').attr('class', 'login-layout light-login');
                    $('#id-text2').attr('class', 'red');
                    $('#id-company-text').attr('class', 'blue');
                }
            });
            function myNotif(judul,teks,code){
                var type = '';
                if(code === 1){
                    type = 'success';
                }else if(code === 2){
                    type = 'warning';
                }else if(code === 3){
                    type = 'error';
                }else{
                    type = 'info';
                }
                
                $.gritter.add({
                    title: judul + ' !',
                    text: '<span class="bigger-130">' + teks + '.</span>',
                    sticky: false,
                    class_name: 'gritter-' + type
                });
            }
        </script>
    </body>
</html>
