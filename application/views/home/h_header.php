<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    @media (max-width:480px) { 
        .site-identity h1 a{
            display: none !important;
        }
        .site-identity span {
            display: none !important;
        }
    }
    .site-identity {
        float: left;
        width: 50%;
        line-height: 100%;
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
</style>
<header class="header-container">
    <!-- Header Top -->
    <div class="header-top">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <ul class="login-details clearfix">
                        <li class="membericon"><span id="jam"></span></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <div class="social-icon pull-right">
                        <a target="_blank" href="https://www.instagram.com/admisiunimuda" class="dribble fa fa-instagram"> </a>
                        <a target="_blank" href="https://www.facebook.com/profile.php?id=100089302566025" class="facebook fa fa-facebook"></a>
                        <a target="_blank" href="https://twitter.com/admisiunimuda" class="twitter fa fa-twitter"></a>
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
                    <a href="<?= site_url() ?>"><img src="<?= load_file('theme/img/logo.png') ?>" alt="<?= ctk($app['judul']) ?>"></a>
                    <h1><a href="<?= site_url() ?>"><?= ctk($app['judul']) ?></a></h1>
                    <span style="text-transform: uppercase;font-size: 1.3em"><?= ctk($app['deskripsi']) ?></span><br/>
                    <span><?= ctk($app['cipta']) ?></span>
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
                    <ul class="clearfix">
                        <li><a href="<?= site_url() ?>">Beranda</a></li>
                        <?= navbar($navbar, 0, NULL) ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</header> 
