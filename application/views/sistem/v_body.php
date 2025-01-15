<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
            $param = $_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '';
            $meta = isset($meta) ? $meta : [];
            $meta_title_default = isset($breadcrumb) ? breadcrumb($breadcrumb, 'title') : ctk($app['deskripsi']);
            $meta_desc_default = ctk($app['deskripsi']);
            $meta_author_default = $app['judul'];
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
                'backend/assets/css/select2.css',
                'backend/assets/css/jquery.gritter.css',
                'backend/assets/css/datepicker.css',
                'backend/assets/css/colorpicker.css',
                'backend/assets/css/ace-fonts.css',
                'backend/assets/fonts/poppins/font.css?family=Poppins:300,400,500,600,700',
                
                'backend/puru.css',
                
                'frontend/shop/css/font-awesome/css/font-awesome.min.css'
            ));
            load_js(array(
                'backend/assets/js/ace-extra.js',
                'backend/assets/js/jquery.js'
            ));
        ?>
        <link rel="stylesheet" href="<?= base_url('app/backend/assets/css/ace.css') ?>" class="ace-main-stylesheet" id="main-ace-style" />
    </head>
    <body class="no-skin">
        <!-- #section:basics/navbar.layout -->
        <?php
        $this->load->view('sistem/v_header');
        ?>
        <!-- /section:basics/navbar.layout -->
        <div class="main-container" id="main-container">
            <script type="text/javascript">
                try {
                    ace.settings.check('main-container', 'fixed');
                } catch (e) {
                }
            </script>
            <!-- #section:basics/sidebar -->
            <?php
            $this->load->view('sistem/v_sidebar');
            ?>
            <!-- /section:basics/sidebar -->
            <div class="main-content">
                <div class="main-content-inner">
                    <?= $content ?>
                </div>
            </div><!-- /.main-content -->
            <?php
            $this->load->view('sistem/v_footer');
            ?>
        </div><!-- /.main-container -->

        <?php
            load_js(array(
                'backend/assets/js/bootstrap.js',
                'backend/assets/js/jquery.gritter.js',
                'backend/assets/js/lazy/lazysizes.min.js',
                'backend/sweetalert.min.js',
                
                'backend/assets/js/ace/elements.scroller.js',
                'backend/assets/js/ace/elements.colorpicker.js',
                'backend/assets/js/ace/elements.fileinput.js',
                'backend/assets/js/ace/elements.aside.js',
                'backend/assets/js/ace/ace.js',
                'backend/assets/js/ace/ace.ajax-content.js',
                'backend/assets/js/ace/ace.touch-drag.js',
                'backend/assets/js/ace/ace.sidebar.js',
                'backend/assets/js/ace/ace.sidebar-scroll-1.js',
                'backend/assets/js/ace/ace.submenu-hover.js',
                'backend/assets/js/ace/ace.widget-box.js',
                'backend/assets/js/ace/ace.settings.js',
                'backend/assets/js/ace/ace.settings-skin.js',
                'backend/assets/js/ace/ace.widget-on-reload.js'
            ));
        ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/UpUp/1.0.0/upup.min.js"></script>
        <script async type="text/javascript">
            const filesToCache = [
                'app/backend/assets/css/bootstrap.css',
                'app/backend/assets/css/font-awesome.css',
                'app/backend/assets/css/select2.css',
                'app/backend/assets/css/jquery.gritter.css',
                'app/backend/assets/css/datepicker.css',
                'app/backend/assets/css/colorpicker.css',
                'app/backend/assets/css/ace-fonts.css',
                'app/backend/assets/fonts/poppins/font.css?family=Poppins:300,400,500,600,700',
                'app/backend/assets/css/ace.css',

                'app/backend/puru.css',
                'app/img/logo.png',

                'app/frontend/shop/css/font-awesome/css/font-awesome.min.css',

                'app/backend/assets/js/ace-extra.js',
                'app/backend/assets/js/jquery.js',

                'app/backend/assets/js/bootstrap.js',
                'app/backend/assets/js/jquery.gritter.js',
                'app/backend/assets/js/lazy/lazysizes.min.js',
                'app/backend/sweetalert.min.js',

                'app/backend/assets/js/dataTables/jquery.dataTables.js',
                'app/backend/assets/js/dataTables/jquery.dataTables.bootstrap.js',

                'app/backend/assets/js/ace/elements.scroller.js',
                'app/backend/assets/js/ace/elements.colorpicker.js',
                'app/backend/assets/js/ace/elements.fileinput.js',
                'app/backend/assets/js/ace/elements.aside.js',
                'app/backend/assets/js/ace/ace.js',
                'app/backend/assets/js/ace/ace.ajax-content.js',
                'app/backend/assets/js/ace/ace.touch-drag.js',
                'app/backend/assets/js/ace/ace.sidebar.js',
                'app/backend/assets/js/ace/ace.sidebar-scroll-1.js',
                'app/backend/assets/js/ace/ace.submenu-hover.js',
                'app/backend/assets/js/ace/ace.widget-box.js',
                'app/backend/assets/js/ace/ace.settings.js',
                'app/backend/assets/js/ace/ace.settings-skin.js',
                'app/backend/assets/js/ace/ace.widget-on-reload.js'
            ];
            $(function() {
                UpUp.start({
                    'cache-version': '<?= APP_VER ?>',
                    'content-url': '<?= site_url() ?>',
                    'content': 'No Internet Connection',
                    'service-worker-url': "<?= base_url('sw.js') ?>",
                    'assets': filesToCache
                });
                $.extend($.gritter.options, { 
                    position: 'top-right',
                    fade_in_speed: 'medium', 
                    fade_out_speed: 1500,
                    time: 3000
                });
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
                    $(".jam").html(waktu);
                }, 1000);
                
                var nav = "<?= $theme[2] ?>";
                var side = "<?= $theme[3] ?>";
                var bread = "<?= $theme[4] ?>";
                var contain = "<?= $theme[5] ?>";
                var hover = "<?= $theme[6] ?>";
                var compact = "<?= $theme[7] ?>";
                var horizon = "<?= $theme[8] ?>";
                var item = "<?= $theme[9] ?>";
                
                if (nav === "1") {
                    $('.navbar').addClass('navbar-fixed-top');
                } else {
                    $('.navbar').removeClass('navbar-fixed-top');
                }
                if (bread === "1") {
                    $('.breadcrumbs').addClass('breadcrumbs-fixed');
                } else {
                    $('.breadcrumbs').removeClass('breadcrumbs-fixed');
                }
                if (contain === "1") {
                    $('.navbar-container').addClass('container');
                    $('.main-container').addClass('container');
                } else {
                    $('.navbar-container').removeClass('container');
                    $('.main-container').removeClass('container');
                }

                //SIDEBAR
                if (side === "1") {
                    $('.sidebar').addClass('sidebar-fixed sidebar-scroll');
                } else {
                    $('.sidebar').removeClass('sidebar-fixed sidebar-scroll');
                }
                if (compact === "1") {
                    $('.sidebar').addClass('compact');
                } else {
                    $('.sidebar').removeClass('compact');
                }
                if (horizon === "1") {
                    $('.sidebar').addClass('h-sidebar navbar-collapse no-gap lower-highlight');
                    $('.navbar').addClass('h-navbar navbar-collapse');
                } else {
                    $('.sidebar').removeClass('h-sidebar navbar-collapse no-gap lower-highlight');
                    $('.navbar').removeClass('h-navbar navbar-collapse');
                }
                if (compact === "1") {
                    $('.sidebar').addClass('compact');
                } else {
                    $('.sidebar').removeClass('compact');
                }
                if (hover === "1") {
                    $('ul.nav > li').addClass('hover');
                }else{
                    $('ul.nav > li').removeClass('hover');
                }
                
                if (item === "1") {
                    $('ul.nav > li').addClass('highlight');
                } else {
                    $('ul.nav > li').removeClass('highlight');
                }
            });
        </script>
        <script type="text/javascript">
            $(document).on('click', '.unread', function() {
                autoload_module($(this).attr("id"));
            });
            function myNotif(judul,teks,code,opsi = null){
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
                
                if(opsi !== null){
                    swal(judul, teks, type);
                }else{
                    $.gritter.add({
                        title: judul + ' !',
                        text: '<span class="bigger-130">' + teks + '</span>',
                        sticky: false,
                        before_open: function(){
                            if($('.gritter-item-wrapper').length >= 3){
                                return false;
                            }
                        },
                        class_name: 'gritter gritter-light gritter-' + type
                    });
                }
                return false;
            }
            function to_number(angka) {
                var number = Number(angka.replace(/[^0-9\,]+/g, ""));
                return number;
            }
            function to_rupiah(angka) {
                var rupiah = '';
                var angkarev = angka.toString().split('').reverse().join('');
                for (var i = 0; i < angkarev.length; i++)
                    if (i % 3 == 0)
                        rupiah += angkarev.substr(i, 3) + '.';

                return rupiah.split('', rupiah.length - 1).reverse().join('');

            }
            function autoload_module(id = '') {
                $.ajax({
                    url: "<?= site_url('non_login/login/ajax/type/list/source/autoload'); ?>",
                    type: "POST",
                    dataType: "json",
                    data: {id: id},
                    success: function(rs) {
                        if (rs.status) {
                            $("#li-notif").html(rs.html);
                            //console.log(rs);
                        }
                        $("span#item-notif").html(rs.item);
                        $("span#new-notif").html(rs.item);
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(thrownError);
                    }
                });
            }
        </script>
        <?php if (!empty($this->session->userdata('id'))): ?>
        <script type="text/javascript">
            $(function() {
                autoload_module();
            });
        </script>
        <?php endif; ?>
        <!--ACE THEME JS-->
        <script type="text/javascript">
            if ('ontouchstart' in document.documentElement)
                document.write("<script src='<?= base_url('app/backend/assets/js/jquery.mobile.custom.js') ?>'>" + "<" + "/script>");
        </script>
        <script type="text/javascript">
            $(function() {
                var $sidebar = $('.sidebar').eq(0);
                if (!$sidebar.hasClass('h-sidebar'))
                    return;
                $(document).on('settings.ace.top_menu', function(ev, event_name, fixed) {
                    if (event_name !== 'sidebar_fixed')
                        return;

                    var sidebar = $sidebar.get(0);
                    var $window = $(window);

                    //return if sidebar is not fixed or in mobile view mode
                    var sidebar_vars = $sidebar.ace_sidebar('vars');
                    if (!fixed || (sidebar_vars['mobile_view'] || sidebar_vars['collapsible'])) {
                        $sidebar.removeClass('lower-highlight');
                        //restore original, default marginTop
                        sidebar.style.marginTop = '';

                        $window.off('scroll.ace.top_menu')
                        return;
                    }


                    var done = false;
                    $window.on('scroll.ace.top_menu', function(e) {

                        var scroll = $window.scrollTop();
                        scroll = parseInt(scroll / 4);//move the menu up 1px for every 4px of document scrolling
                        if (scroll > 17)
                            scroll = 17;


                        if (scroll > 16) {
                            if (!done) {
                                $sidebar.addClass('lower-highlight');
                                done = true;
                            }
                        }
                        else {
                            if (done) {
                                $sidebar.removeClass('lower-highlight');
                                done = false;
                            }
                        }

                        sidebar.style['marginTop'] = (scroll) + 'px';
                    }).triggerHandler('scroll.ace.top_menu');

                }).triggerHandler('settings.ace.top_menu', ['sidebar_fixed', $sidebar.hasClass('sidebar-fixed')]);
                $(window).on('resize.ace.top_menu', function() {
                    $(document).triggerHandler('settings.ace.top_menu', ['sidebar_fixed', $sidebar.hasClass('sidebar-fixed')]);
                });
            });

            $(function() {
                var skin_class = '<?= $theme[0] ?>';

                if ($('#ace-skins-stylesheet').length == 0) {
                    //let's load skins stylesheet only when needed!
                    var ace_style = $('head').find('link.ace-main-stylesheet');
                    if (ace_style.length == 0) {
                        ace_style = $('head').find('link[href*="/ace.min.css"],link[href*="/ace-part2.min.css"]');
                        if (ace_style.length == 0) {
                            ace_style = $('head').find('link[href*="/ace.css"],link[href*="/ace-part2.css"]');
                        }
                    }

                    var stylesheet_url = ace_style.first().attr('href').replace(/(\.min)?\.css$/i, '-skins$1.css');
                    $.ajax({
                        'url': stylesheet_url
                    }).done(function() {
                        var new_link = jQuery('<link />', {type: 'text/css', rel: 'stylesheet', 'id': 'ace-skins-stylesheet'})
                        if (ace_style.length > 0) {
                            new_link.insertAfter(ace_style.last());
                        }
                        else
                            new_link.appendTo('head');

                        new_link.attr('href', stylesheet_url);
                        ganti_skin(skin_class);
                        if (window.Pace && Pace.running)
                            Pace.stop();
                    });
                }
                else {
                    ganti_skin(skin_class);
                }

                function ganti_skin(skin_class) {
                    //skin cookie tip
                    var body = $(document.body);
                    body.removeClass('no-skin skin-1 skin-2 skin-3');
                    //if(skin_class != 'skin-0') {
                    body.addClass(skin_class);
                    ace.data.set('skin', skin_class);
                    //save the selected skin to cookies
                    //which can later be used by your server side app to set the skin
                    //for example: <body class="<?php //echo $_COOKIE['ace_skin']; ?>"
                    //} else ace.data.remove('skin');
                    var skin3_colors = ['red', 'blue', 'green', ''];
                    //undo skin-1
                    $('.ace-nav > li.grey').removeClass('dark');

                    //undo skin-2
                    $('.ace-nav > li').removeClass('no-border margin-1');
                    $('.ace-nav > li:not(:last-child)').removeClass('light-pink').find('> a > ' + ace.vars['.icon']).removeClass('pink').end().eq(0).find('.badge').removeClass('badge-warning');
                    $('.sidebar-shortcuts .btn')
                            .removeClass('btn-pink btn-white')
                            .find(ace.vars['.icon']).removeClass('white');

                    //undo skin-3
                    $('.ace-nav > li.grey').removeClass('red').find('.badge').removeClass('badge-yellow');
                    $('.sidebar-shortcuts .btn').removeClass('btn-primary btn-white')
                    var i = 0;
                    $('.sidebar-shortcuts .btn').each(function() {
                        $(this).find(ace.vars['.icon']).removeClass(skin3_colors[i++]);
                    })

                    var skin0_buttons = ['btn-success', 'btn-info', 'btn-warning', 'btn-danger'];
                    if (skin_class == 'no-skin') {
                        var i = 0;
                        $('.sidebar-shortcuts .btn').each(function() {
                            $(this).attr('class', 'btn ' + skin0_buttons[i++ % 4]);
                        })

                        $('.sidebar[data-sidebar-scroll=true]').ace_sidebar_scroll('updateStyle', '');
                        $('.sidebar[data-sidebar-hover=true]').ace_sidebar_hover('updateStyle', 'no-track scroll-thin');
                    }

                    else if (skin_class == 'skin-1') {
                        $('.ace-nav > li.grey').addClass('dark');
                        var i = 0;
                        $('.sidebar-shortcuts')
                                .find('.btn').each(function() {
                            $(this).attr('class', 'btn ' + skin0_buttons[i++ % 4]);
                        })

                        $('.sidebar[data-sidebar-scroll=true]').ace_sidebar_scroll('updateStyle', 'scroll-white no-track');
                        $('.sidebar[data-sidebar-hover=true]').ace_sidebar_hover('updateStyle', 'no-track scroll-thin scroll-white');
                    }

                    else if (skin_class == 'skin-2') {
                        $('.ace-nav > li').addClass('no-border margin-1');
                        $('.ace-nav > li').addClass('light-pink').find('> a > ' + ace.vars['.icon']).addClass('pink').end().eq(0).find('.badge').addClass('badge-warning');
                        //:not(:last-child)
                        $('.sidebar-shortcuts .btn').attr('class', 'btn btn-white btn-pink')
                                .find(ace.vars['.icon']).addClass('white');

                        $('.sidebar[data-sidebar-scroll=true]').ace_sidebar_scroll('updateStyle', 'scroll-white no-track');
                        $('.sidebar[data-sidebar-hover=true]').ace_sidebar_hover('updateStyle', 'no-track scroll-thin scroll-white');
                    }

                    //skin-3
                    //change shortcut buttons classes, this should be hard-coded if you want to choose this skin
                    else if (skin_class == 'skin-3') {
                        body.addClass('no-skin');//because skin-3 has many parts of no-skin as well

                        $('.ace-nav > li.grey').addClass('red').find('.badge').addClass('badge-yellow');

                        var i = 0;
                        $('.sidebar-shortcuts .btn').each(function() {
                            $(this).attr('class', 'btn btn-primary btn-white');
                            $(this).find(ace.vars['.icon']).addClass(skin3_colors[i++]);
                        })

                        $('.sidebar[data-sidebar-scroll=true]').ace_sidebar_scroll('updateStyle', 'scroll-dark no-track');
                        $('.sidebar[data-sidebar-hover=true]').ace_sidebar_hover('updateStyle', 'no-track scroll-thin');
                    }

                    //some sizing differences may be there in skins, so reset scrollbar size
                    $('.sidebar[data-sidebar-scroll=true]').ace_sidebar_scroll('reset')
                    //$('.sidebar[data-sidebar-hover=true]').ace_sidebar_hover('reset')

                    if (ace.vars['old_ie'])
                        ace.helper.redraw(document.body, true);
                }
            });
        </script>
    </body>
</html>