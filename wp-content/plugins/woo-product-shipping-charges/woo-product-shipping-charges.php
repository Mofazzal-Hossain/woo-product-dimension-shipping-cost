<?php

/**
 * Plugin Name: Woo Product Shipping Charges
 * Plugin URI: mosharofmd74@gmail.com
 * Description: Add Shipping Charges to WooCommerce Products
 * Version: 1.0.0
 * Author: Mofazzal Hossain
 * Author URI: mosharofmd74@gmail.com
 * Text Domain: woo-product-shipping-charges
 */

//  Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load Text Domain
add_action('load_textdomain', 'wpsc_load_textdomain');
function wpsc_load_textdomain()
{
    load_plugin_textdomain('woo-product-shipping-charges', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

// Define Constants
define("WPSC_VERSION", '1.0.0');
define('WPSC_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WPSC_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WPSC_PLUGIN_ASSETS', WPSC_PLUGIN_URL . 'assets');


// Enqueue Styles
add_action('wp_enqueue_scripts', 'wpsc_enqueue_scripts');
function wpsc_enqueue_scripts()
{
    wp_enqueue_style('wpsc-public-style', WPSC_PLUGIN_ASSETS . '/css/public-style.css', [], time());
    wp_enqueue_script('wpsc-public-main', WPSC_PLUGIN_ASSETS . '/js/public-main.js', ['jquery'], time(), true);
}


// Include Files
include_once WPSC_PLUGIN_DIR . 'inc/functions.php';