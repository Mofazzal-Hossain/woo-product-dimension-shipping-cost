<?php

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

// save product dimension to order
add_action('woocommerce_checkout_create_order_line_item', 'wpsc_save_product_dimensions_to_order', 10, 4);

function  wpsc_save_product_dimensions_to_order($item, $cart_item_key, $values, $order)
{
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

// Display product dimension in order
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

// Display product dimension in admin order table
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


// Limit to one product in cart
add_filter("woocommerce_add_to_cart_validation", "wpsc_limit_to_one_product", 10, 3);

function wpsc_limit_to_one_product($passed_validation, $product_id, $quantity)
{
    if (WC()->cart->get_cart_contents_count() > 0) {
        wc_add_notice(__('You can only add one product to the cart at a time. Please remove the current product before adding a new one.', 'woo-product-shipping-charges'), 'error');
        return false;
    }

    return $passed_validation;
}


// Calculate shipping charge based on product dimension
add_action('woocommerce_package_rates', 'wpsc_calculate_shipping_charge_based_on_dimension', 10, 2);

function wpsc_calculate_shipping_charge_based_on_dimension($rates, $package)
{


    foreach (WC()->cart->get_cart() as $cart_item) {
        $wpsc_width = isset($cart_item['wpsc_product_width']) ? $cart_item['wpsc_product_width'] : 0;
        $wpsc_height = isset($cart_item['wpsc_product_height']) ? $cart_item['wpsc_product_height'] : 0;
        $wpsc_length = isset($cart_item['wpsc_product_length']) ? $cart_item['wpsc_product_length'] : 0;

        $total_dimension = $wpsc_width * $wpsc_height * $wpsc_length;
        $charge_multiplier = floatval($total_dimension / 181);

        // shipping charge
        foreach ($rates as $rate_id => $rate) {
            $rates[$rate_id]->cost = $rates[$rate_id]->cost * $charge_multiplier;

            // tax rate 
            if(!empty($rates[$rate_id]->taxes)){
                foreach($rates[$rate_id]->taxes as $tax_id => $tax){
                    $rates[$rate_id]->taxes[$tax_id] = $tax * $charge_multiplier;
                }
            }
        }
        break;
    }

    return $rates;
}
