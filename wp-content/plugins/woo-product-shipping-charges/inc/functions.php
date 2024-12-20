<?php

if (!defined('ABSPATH')) {
    exit;
}

// product shipping limit
add_action("woocommerce_product_options_shipping_product_data", "wpsc_add_product_dimension_field");
function wpsc_add_product_dimension_field()
{
    echo '<div class="options_group">';

    // Add Shipping Limit Field
    woocommerce_wp_text_input([
        'id' => '_wpsc_shipping_limit',
        'label' => __('Shipping Charge Limit (cm)', 'woo-product-shipping-charges'),
        'desc_tip' => false,
        'type' => 'number',
        'value' => get_post_meta(get_the_ID(), '_wpsc_shipping_limit', true) ?: 180,
        'description' => __('Enter the default shipping charge limit in centimeters. If the product dimensions exceed this limit, shipping charges will be calculated proportionally based on the product dimensions.', 'woo-product-shipping-charges'),
        'custom_attributes' => [
            'min' => '1',
        ],
    ]);

    echo '</div>';
}

// save product shipping limit
add_action('woocommerce_process_product_meta', 'wpsc_save_product_shipping_limit_meta');
function wpsc_save_product_shipping_limit_meta($product_id)
{
    if (isset($_POST['_wpsc_shipping_limit'])) {
        update_post_meta($product_id, '_wpsc_shipping_limit', sanitize_text_field($_POST['_wpsc_shipping_limit']));
    }
}

// product dimension input
add_action('woocommerce_before_add_to_cart_button', 'wpsc_before_add_to_cart_button');
function wpsc_before_add_to_cart_button()
{
    global $product;
    $product_id = $product->get_id();

    // Get Product Shipping Dimension
    $product_length = get_post_meta($product_id, '_length', true) ?: '';
    $product_width = get_post_meta($product_id, '_width', true) ?: '';
    $product_height = get_post_meta($product_id, '_height', true) ?: '';

?>
    <div class="wpsc-product-shipping-dimension">
        <!-- Product Dimension Title -->
        <h3 class="wpsc-dimension-title">
            <?php echo esc_html_e('Product Dimension', 'woo-product-shipping-charges'); ?>
        </h3>
        <!-- input group -->
        <div class="wpsc-input-group">
            <label for="wpsc_product_length">
                <?php echo esc_html_e('Length (cm)', 'woo-product-shipping-charges'); ?>
            </label>
            <input type="number" name="wpsc_product_length" id="wpsc_product_length" min="1" class="wpsc-form-input wpsc-length" placeholder="e.g. 1" value="<?php echo esc_attr($product_length); ?>">
        </div>
        <!-- input group -->
        <div class="wpsc-input-group">
            <label for="wpsc_product_width">
                <?php echo esc_html_e('Width (cm)', 'woo-product-shipping-charges'); ?>
            </label>
            <input type="number" name="wpsc_product_width" id="wpsc_product_width" min="1" class="wpsc-form-input wpsc-width" placeholder="e.g. 30" value="<?php echo esc_attr($product_width); ?>">
        </div>
        <!-- input group -->
        <div class="wpsc-input-group">
            <label for="wpsc_product_height">
                <?php echo esc_html_e('Height (cm)', 'woo-product-shipping-charges'); ?>
            </label>
            <input type="number" name="wpsc_product_height" id="wpsc_product_height" min="1" class="wpsc-form-input wpsc-height" placeholder="e.g. 20" value="<?php echo esc_attr($product_height); ?>">
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
        $cart_item_data['wpsc_product_length'] = sanitize_text_field($_POST['wpsc_product_length']);
        $cart_item_data['wpsc_product_width'] = sanitize_text_field($_POST['wpsc_product_width']);
        $cart_item_data['wpsc_product_height'] = sanitize_text_field($_POST['wpsc_product_height']);
    }
    return $cart_item_data;
}

// Display product dimension in cart
add_filter('woocommerce_get_item_data', 'wpsc_display_product_dimension_in_cart', 10, 2);

function wpsc_display_product_dimension_in_cart($item_data, $cart_item)
{
    // display product length
    if (isset($cart_item['wpsc_product_length'])) {
        $item_data[] = [
            'name' => 'Length',
            'value' => esc_html($cart_item['wpsc_product_length']) . ' cm',
        ];
    }
    // display product width
    if (isset($cart_item['wpsc_product_width'])) {
        $item_data[] = array(
            'name' => 'Width',
            'value' => esc_html($cart_item['wpsc_product_width']) . ' cm',
        );
    }
    // display product height
    if (isset($cart_item['wpsc_product_height'])) {
        $item_data[] = [
            'name' => 'Height',
            'value' => esc_html($cart_item['wpsc_product_height']) . ' cm',
        ];
    }


    return $item_data;
}

// save product dimension to order
add_action('woocommerce_checkout_create_order_line_item', 'wpsc_save_product_dimensions_to_order', 10, 4);

function  wpsc_save_product_dimensions_to_order($item, $cart_item_key, $values, $order)
{

    if (isset($values['wpsc_product_length'])) {
        $item->add_meta_data('Length', $values['wpsc_product_length'] . ' cm');
    }
    if (isset($values['wpsc_product_width'])) {
        $item->add_meta_data('Width', $values['wpsc_product_width'] . ' cm');
    }
    if (isset($values['wpsc_product_height'])) {
        $item->add_meta_data('Height', $values['wpsc_product_height'] . ' cm');
    }
}

// Limit to one product in cart
add_filter("woocommerce_add_to_cart_validation", "wpsc_limit_to_one_product", 10, 3);

function wpsc_limit_to_one_product($passed_validation, $product_id, $quantity)
{
    // Check if there is already one product in the cart
    if (WC()->cart->get_cart_contents_count() > 0) {
        wc_add_notice(__('You can only add one product to the cart at a time. Please remove the current product before adding a new one.', 'woo-product-shipping-charges'), 'error');
        return false;
    }

    return $passed_validation;
}


// Calculate shipping charge based on product dimension
add_filter('transient_shipping-transient-version', '__return_false', 10, 2);
add_action('woocommerce_package_rates', 'wpsc_calculate_shipping_charge_based_on_dimension', 10, 2);

function wpsc_calculate_shipping_charge_based_on_dimension($rates, $package)
{

    foreach (WC()->cart->get_cart() as $cart_item) {

        // Retrieve the shipping limit for the product
        $product_id = $cart_item['product_id'];
        $shipping_limit = get_post_meta($product_id, '_wpsc_shipping_limit', true);

        // Set default shipping limit if not defined
        $shipping_limit = !empty($shipping_limit) ? $shipping_limit : 180;

        $wpsc_length = isset($cart_item['wpsc_product_length']) ? $cart_item['wpsc_product_length'] : 0;
        $wpsc_width = isset($cart_item['wpsc_product_width']) ? $cart_item['wpsc_product_width'] : 0;
        $wpsc_height = isset($cart_item['wpsc_product_height']) ? $cart_item['wpsc_product_height'] : 0;

        $total_dimension = $wpsc_width * $wpsc_height * $wpsc_length;
        $charge_multiplier = intval($total_dimension / $shipping_limit) + 1;

        // shipping charge
        foreach ($rates as $rate_id => $rate) {
            $rates[$rate_id]->cost = $rates[$rate_id]->cost * $charge_multiplier;

            // tax rate 
            if (!empty($rates[$rate_id]->taxes)) {
                foreach ($rates[$rate_id]->taxes as $tax_id => $tax) {
                    $rates[$rate_id]->taxes[$tax_id] = $tax * $charge_multiplier;
                }
            }
        }
        break;
    }

    return $rates;
}
