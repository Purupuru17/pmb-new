<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$hook['post_controller_constructor'][] = array(
    'class' => 'App_hooks',
    'function' => 'is_offline',
    'filename' => 'App_hooks.php',
    'filepath' => 'hooks',
    'params'	=> ''
);
$hook['post_controller_constructor'][] = array(
    'class' => 'App_hooks',
    'function' => 'redirect_ssl',
    'filename' => 'App_hooks.php',
    'filepath' => 'hooks',
    'params'	=> ''
);
$hook['post_controller_constructor'][] = array(
    'class' => 'Track_visitor',
    'function' => 'visitor_track',
    'filename' => 'Track_visitor.php',
    'filepath' => 'hooks'
);
$hook['display_override'][] = array(
    'class' => 'App_hooks',
    'function' => 'compress',
    'filename' => 'App_hooks.php',
    'filepath' => 'hooks'
);
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
