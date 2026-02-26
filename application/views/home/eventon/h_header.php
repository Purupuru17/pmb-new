<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<header class="header-container">
    <!-- Header Top -->
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <ul class="login-details clearfix">
                        <li class="membericon"><span class="waktu"></span></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <div class="social-icon pull-right">
                        <a target="_blank" href="https://www.instagram.com/<?= config_item('kampus')['ig'] ?>" class="dribble fa fa-instagram"> </a>
                        <a target="_blank" href="https://www.facebook.com/<?= config_item('kampus')['fb'] ?>" class="facebook fa fa-facebook"></a>
                        <a target="_blank" href="https://twitter.com/<?= config_item('kampus')['x'] ?>" class="twitter fa fa-twitter"></a>
                    </div>
                </div>
            </div> 
        </div>     
    </div>
    <!-- Main Header -->
    <div class="main-header affix">
        <!-- Moblie Nav Wrapper -->
        <div class="mobile-nav-wrapper">
            <div class="container ">
                <!-- logo -->
                <div class="site-identity">
                    <a href="<?= site_url() ?>"><img src="<?= load_file('private/logo.png') ?>" alt="<?= ctk($app_session['judul']) ?>"></a>
                    <span style="padding-top: 5px; font-size: 1.8em"><?= ctk($app_session['judul']) ?></span><br/>
                    <span><?= ctk($app_session['deskripsi']) ?></span><br/>
                    <span><?= config_item('kampus')['nama'] ?></span>
                    <br><br>
                </div>  
                <div id="sb-search" class="sb-search">
                    <form method="GET" action="<?= site_url('tag/all') ?>">
                        <input class="sb-search-input" placeholder="Pencarian" type="text" name="q" id="search" required="">
                        <input class="sb-search-submit" type="submit" value="">
                        <span class="sb-icon-search" style="color: #fff"></span>
                    </form>
                </div>
                <!-- moblie memu Icon -->
                <div class="mobile-menu-icon">
                    <i class="fa fa-bars"></i>
                </div> 
                <!--Main Nav -->
                <nav class="main-nav mobile-menu">
                    <ul class="clearfix the-menu">
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header> 
