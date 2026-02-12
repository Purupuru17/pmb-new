<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
</style>
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
                    <span style="padding-top: 15px; font-size: 1.8em"><?= ctk($app_session['judul']) ?></span><br/>
                    <span><?= ctk($app_session['deskripsi']) ?></span>
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
