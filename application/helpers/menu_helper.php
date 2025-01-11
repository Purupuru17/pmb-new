<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('menu_active')) {

    function menu_active($module) {
        $CI = & get_instance();

        $menu = $CI->uri->segment(1);
        $str = substr($module, 0, strpos($module, '/'));
        $status = "";
        if (strtolower($str) == $menu) {
            $status = "open active";
        }
        return $status;
    }

}
if (!function_exists('sub_active')) {

    function sub_active($module) {
        $CI = & get_instance();

        $menu = $CI->uri->segment(1);
        $sub = $CI->uri->segment(2);
        $text = strtolower(str_replace('/', '', $module));

        $status = "";
        if ($text == $menu . $sub || $text . '_do' == $menu . $sub) {
            $status = "active";
        }
        return $status;
    }

}
if (!function_exists('sidebar')) {
    function sidebar($data, $parrent, $module) {
        $str = '';
        if (isset($data[$parrent])) {
            // 
            foreach ($data[$parrent] as $value) {
                $child = sidebar($data, $value['id_menu'], $module);
                if ($child) {
                    $str .= '<li class="' . menu_active($value['module_menu']) . '">
                        <a href="#" class="dropdown-toggle">
                            <i class="menu-icon ' . $value['icon_menu'] . '"></i>
                            <span class="menu-text">' . $value['nama_menu'] . '</span>
                            <b class="arrow fa fa-angle-down"></b>
                        </a>
                        <b class="arrow"></b>
                        <ul class="submenu">' . $child . '</ul>
                    </li>';
                }else{
                    $str .= '<li class="' . sub_active($value['module_menu']) . '">
                        <a href="' . site_url($module . $value['module_menu']) . '">
                            <i class="orange ' . $value['icon_menu'] . '"></i>&nbsp;&nbsp;
                            <i class="menu-icon fa fa-caret-right"></i>'. $value['nama_menu'] .'</a>
                        <b class="arrow"></b>
                    </li>';
                }
            }
        }
        return $str;
    }
}
if (!function_exists('breadcrumb')) {
    
    function breadcrumb($breadcrumb) {
        if (isset($breadcrumb) && is_array($breadcrumb)) {

            $buffString = "";
            foreach ($breadcrumb as $values) {

                $title = $values['title'];
                $url = $values['url'];

                $breadcrumContent = "";

                if ($url != "") {
                    $breadcrumContent = '<a href="' . $url . '">' . $title . '</a>';
                } else {
                    $breadcrumContent = $title;
                }
                $buffString .= '<li>' . $breadcrumContent . '</li>';
            }
            return $buffString;
        }
    }

}
//HOME
if (!function_exists('navbar')) {
    
    function navbar($data, $parrent, $module) {
        $str = '';
        if (isset($data[$parrent])) {
            
            foreach ($data[$parrent] as $value) {
                $child = navbar($data, $value['id_nav'], $module);
                if ($child) {
                    $str .= '<li class="parent">
                                <a href="'. site_url($module . $value['url_nav']) .'">
                                <span>'. $value['judul_nav'] .'</span></a>
                                <ul class="sub-menu"><li class="arrow"></li>'.$child.'</ul>
                            </li>';
                }else{
                    if($value['link_nav'] === '1'){
                        $str .= '<li>
                                <a target="_blank" href="'. $value['url_nav'] .'">'. $value['judul_nav'] .'</a>
                            </li>';
                    }else{
                        $str .= '<li>
                                <a href="'. site_url($module . $value['url_nav']) .'">'. $value['judul_nav'] .'</a>
                            </li>';
                    }
                    
                }
            }
        }
        return $str;
    }
}
if (!function_exists('breadhome')) {
    
    function breadhome($breadcrumb) {
        if (isset($breadcrumb) && is_array($breadcrumb)) {

            $buffString = "";
            foreach ($breadcrumb as $values) {

                $title = $values['title'];
                $url = $values['url'];
                $active = '';

                $breadcrumContent = "";

                if ($url != "#") {
                    $breadcrumContent = '<a href="' . $url . '">' . $title . '<i class="fa fa-angle-right"></i></a>';
                }else {
                    $breadcrumContent = '<a href="#">' . $title . '</a>';
                    $active = 'active';
                }
                $buffString .= '<li class="'.$active.'">' . $breadcrumContent . '</li>';
            }
            return $buffString;
        }
    }

}
