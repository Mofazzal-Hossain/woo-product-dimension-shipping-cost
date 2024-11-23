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
define('WPSC_PLUGIN_DIR', plugin_dir_url(__FILE__));
define('WPSC_PLUGIN_INC', plugin_dir_url(__FILE__) . 'inc');
define('WPSC_PLUGIN_ASSETS', plugin_dir_url(__FILE__) . 'assets');


// Enqueue Styles
add_action('wp_enqueue_scripts', 'wpsc_enqueue_scripts');
function wpsc_enqueue_scripts()
{
    wp_enqueue_style('wpsc-public-style', WPSC_PLUGIN_ASSETS . '/css/public-style.css', [], time());
    wp_enqueue_style('wpsc-public-main', WPSC_PLUGIN_ASSETS . '/js/public-main.js', [], time(), true);
}

// 
add_action('woocommerce_before_add_to_cart_button', 'wpsc_before_add_to_cart_button');
function wpsc_before_add_to_cart_button(){
    ?>
        <div class="wpsc-product-shipping-dimension">
            <h3 for="wpsc-product-shipping-dimension">
                <?php echo esc_html_e('Shipping Dimension', 'woo-product-shipping-charges'); ?>
            </h3>
            <div class="wpsc-input-group">
                <label for="wpsc-product-width">
                    <?php echo esc_html_e('Width (CM)', 'woo-product-shipping-charges'); ?>
                </label>
                <input type="number" name="wpsc-product-width" id="wpsc-product-width" class="wpsc-form-input wpsc-width" placeholder="e.g. 30">
            </div>
            <div class="wpsc-input-group">
                <label for="wpsc-product-height">
                    <?php echo esc_html_e('Height (CM)', 'woo-product-shipping-charges'); ?>
                </label>
                <input type="number" name="wpsc-product-height" id="wpsc-product-height" class="wpsc-form-input wpsc-height" placeholder="e.g. 20">
            </div>
            <div class="wpsc-input-group">
                <label for="wpsc-product-length">
                    <?php echo esc_html_e('Length (CM)', 'woo-product-shipping-charges'); ?>
                </label>
                <input type="number" name="wpsc-product-length" id="wpsc-product-length" class="wpsc-form-input wpsc-length" placeholder="e.g. 1">
            </div>
        </div>
    <?php
}