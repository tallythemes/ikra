<?php
$path_dir = trailingslashit(str_replace('\\','/',dirname(__FILE__)));
$path_abs = trailingslashit(str_replace('\\','/',ABSPATH));

define('IKRA_URL', site_url(str_replace( $path_abs, '', $path_dir )));
define('IKRA_DRI', $path_dir);

if( !function_exists('mr_image_resize' )){ require('vandors/mr-image-resize.php'); }

require('includes/script-loader.php');
require('includes/custom-functions.php');

require('contents/banner-popup-content.php');
require('contents/banner-content.php');
require('contents/big-list-content.php');

require('loops/masonry-loop.php');