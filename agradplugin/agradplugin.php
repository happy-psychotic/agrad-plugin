<?php
/*
Plugin Name: AgradWeb plugin
Version: 1.2
description: Agrad Seo and Security
Author: Saeid Moini
Author URI: https://agrad.ir
*/

require_once plugin_dir_path(__FILE__) . 'security-config.php';
require_once plugin_dir_path(__FILE__) . 'comment-customization.php';
require_once plugin_dir_path(__FILE__) . 'settings-page.php';

//Custom Font Swap
add_filter( 'elementor_pro/custom_fonts/font_display', function( $current_value, $font_family, $data ) {
	return 'swap';
}, 10, 3 );

//Allow svg
add_filter('upload_mimes', 'my_upload_mimes');
function my_upload_mimes($mimes = array()) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}

