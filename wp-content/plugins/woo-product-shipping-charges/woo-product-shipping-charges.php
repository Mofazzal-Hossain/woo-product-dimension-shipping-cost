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
    wp_enqueue_script('wpsc-public-main', WPSC_PLUGIN_ASSETS . '/js/public-main.js', ['jquery'], time(), true);
}

// product dimension input
add_action('woocommerce_before_add_to_cart_button', 'wpsc_before_add_to_cart_button');
function wpsc_before_add_to_cart_button()
{
?>
    <div class="wpsc-product-shipping-dimension">
        <!-- Product Dimension Title -->
        <h3 class="wpsc-dimension-title">
            <?php echo esc_html_e('Product Dimension', 'woo-product-shipping-charges'); ?>
        </h3>
        <!-- input group -->
        <div class="wpsc-input-group">
            <label for="wpsc_product_width">
                <?php echo esc_html_e('Width (CM)', 'woo-product-shipping-charges'); ?>
            </label>
            <input type="number" name="wpsc_product_width" id="wpsc_product_width" class="wpsc-form-input wpsc-width" placeholder="e.g. 30">
        </div>
        <!-- input group -->
        <div class="wpsc-input-group">
            <label for="wpsc_product_height">
                <?php echo esc_html_e('Height (CM)', 'woo-product-shipping-charges'); ?>
            </label>
            <input type="number" name="wpsc_product_height" id="wpsc_product_height" class="wpsc-form-input wpsc-height" placeholder="e.g. 20">
        </div>
        <!-- input group -->
        <div class="wpsc-input-group">
            <label for="wpsc_product_length">
                <?php echo esc_html_e('Length (CM)', 'woo-product-shipping-charges'); ?>
            </label>
            <input type="number" name="wpsc_product_length" id="wpsc_product_length" class="wpsc-form-input wpsc-length" placeholder="e.g. 1">
        </div>
    </div>
<?php
}

// validate woocommerce add to cart based on product dimension
add_filter('woocommerce_add_to_cart_validation', 'wpsc_validate_product_dimension_field', 10, 3);
function wpsc_validate_product_dimension_field($passed_validation, $product_id, $quantity)
{
    if (empty($_POST['wpsc_product_width']) || empty($_POST['wpsc_product_height']) || empty($_POST['wpsc_product_length'])) {
        wc_add_notice(esc_html__('Please fill all the product dimension fields', 'woo-product-shipping-charges'), 'error');
        return false;
    }
    return $passed_validation;
}

// save product dimension input data
add_filter('woocommerce_add_cart_item_data', 'wpsc_save_product_dimension_input', 10, 2);

function wpsc_save_product_dimension_input($cart_item_data, $product_id)
{
    if (isset($_POST['wpsc_product_width']) && isset($_POST['wpsc_product_height']) && isset($_POST['wpsc_product_length'])) {
        $cart_item_data['wpsc_product_width'] = sanitize_text_field($_POST['wpsc_product_width']);
        $cart_item_data['wpsc_product_height'] = sanitize_text_field($_POST['wpsc_product_height']);
        $cart_item_data['wpsc_product_length'] = sanitize_text_field($_POST['wpsc_product_length']);
    }
    return $cart_item_data;
}

// Display product dimension in cart
add_filter('woocommerce_get_item_data', 'wpsc_display_product_dimension_in_cart', 10, 2);

function wpsc_display_product_dimension_in_cart($item_data, $cart_item)
{
    if (isset($cart_item['wpsc_product_width'])) {
        $item_data[] = array(
            'name' => 'Width',
            'value' => esc_html($cart_item['wpsc_product_width']) . ' cm',
        );
    }
    if (isset($cart_item['wpsc_product_height'])) {
        $item_data[] = [
            'name' => 'Height',
            'value' => esc_html($cart_item['wpsc_product_height']) . ' cm',
        ];
    }
    if (isset($cart_item['wpsc_product_length'])) {
        $item_data[] = [
            'name' => 'Length',
            'value' => esc_html($cart_item['wpsc_product_length']) . ' cm',
        ];
    }

    return $item_data;
}


add_action('woocommerce_checkout_create_order_line_item', 'wpsc_save_product_dimensions_to_order', 10, 4);

function  wpsc_save_product_dimensions_to_order($item, $cart_item_key, $values, $order)
{
    error_log(print_r($values, true));
    if (isset($values['wpsc_product_width'])) {
        $item->add_meta_data('Width', $values['wpsc_product_width'] . ' cm');
    }
    if (isset($values['wpsc_product_height'])) {
        $item->add_meta_data('Height', $values['wpsc_product_height'] . ' cm');
    }
    if (isset($values['wpsc_product_length'])) {
        $item->add_meta_data('Length', $values['wpsc_product_length'] . ' cm');
    }
}
// add_action('woocommerce_order_details_before_order_table', 'wpsc_display_product_dimension_in_order', 10, 1);

// function wpsc_display_product_dimension_in_order($order)
// {
//     echo '<h4>' . __('Product Dimensions', 'woo-product-shipping-charges') . '</h4>';
//     echo '<table>
//             <thead>
//                 <tr>
//                     <th>' . __('Width', 'woo-product-shipping-charges') . '</th>
//                     <th>' . __('Height', 'woo-product-shipping-charges') . '</th>
//                     <th>' . __('Length', 'woo-product-shipping-charges') . '</th>
//                 </tr>
//             </thead>
//             <tbody style="text-align: center;">';

//     foreach ($order->get_items() as $item) {
//         $width  = $item->get_meta('Width');
//         $height = $item->get_meta('Height');
//         $length = $item->get_meta('Length');

//         if ($width || $height || $length) {
//             echo '<tr>
//                     <td>' . esc_html($width) . '</td>
//                     <td>' . esc_html($height) . '</td>
//                     <td>' . esc_html($length) . '</td>
//                   </tr>';
//         }
//     }

//     echo '</tbody></table>';
// }

// add_action('woocommerce_admin_order_data_after_order_details', 'wpsc_display_product_dimension_admin', 10, 1);
// function wpsc_display_product_dimension_admin($item)
// {
//     $width  = $item->get_meta('wpsc_product_width');
//     $height = $item->get_meta('wpsc_product_height');
//     $length = $item->get_meta('wpsc_product_length');
//     echo '<h4>' . __('Product Dimensions', 'woo-product-shipping-charges') . '</h4>';
//     echo '<table><thead><tr>
//                 <th>' . __('Width', 'woo-product-shipping-charges') . '</th>
//                 <th>' . __('Height', 'woo-product-shipping-charges') . '</th>
//                 <th>' . __('Length', 'woo-product-shipping-charges') . '</th>
//             </tr>
//         </thead>
//         <tbody style="text-align: center;">';
//     if ($width || $height || $length) {
//         echo '<tr>
//             <td>' . esc_html($width) . ' cm</td>
//             <td>' . esc_html($height) . ' cm</td>
//             <td>' . esc_html($length) . ' cm</td>
//         </tr>';
//     }
//     echo '</tbody></table>';
// }
