<?php

/**
 * Plugin Name: Woo Product Shipping Charges
 * Plugin URI: https://github.com/Mofazzal-Hossain/woo-product-shipping-charges
 * Description: This plugin adds custom shipping charges based on product dimensions in WooCommerce. 
 * Version: 1.0.0
 * Author: Mofazzal Hossain
 * Author URI: mailto:mosharofmd74@gmail.com
 * Text Domain: woo-product-shipping-charges
 */
//  Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define Constants
define("WPSC_VERSION", '1.0.0');
define('WPSC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WPSC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WPSC_PLUGIN_INC', WPSC_PLUGIN_DIR . 'inc');
define('WPSC_PLUGIN_ASSETS', WPSC_PLUGIN_URL . 'assets');


// Enqueue Styles
add_action('wp_enqueue_scripts', 'wpsc_enqueue_scripts');
function wpsc_enqueue_scripts()
{
    wp_enqueue_style('wpsc-public-style', WPSC_PLUGIN_ASSETS . '/css/public-style.css', [], WPSC_VERSION);
    wp_enqueue_script('wpsc-public-main', WPSC_PLUGIN_ASSETS . '/js/public-main.js', ['jquery'], WPSC_VERSION, true);
}


// Include Files
include_once WPSC_PLUGIN_INC . '/functions.php';
