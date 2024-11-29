**Woo Product Shipping Charges** <br>
Contributors: **Mofazzal-Hossain** <br>
Donate link: https://www.fiverr.com/mofazzal98 <br>
Tags: shipping, product dimensions, WooCommerce, cart, checkout <br>
Requires at least: 4.7 <br>
Tested up to: 6.7.1 <br>
Stable tag: 1.0.0 <br>
Requires PHP: 7.0 <br>
License: GPLv2 or later <br>
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A comprehensive WooCommerce plugin for managing product dimensions and calculating dynamic shipping charges based on user input.

**Description:** Woo Product Shipping Charges is a powerful WooCommerce plugin that enables seamless management of product dimensions and shipping calculations. The plugin allows users to input custom dimensions (Width, Height, Length) directly on the product page and calculates shipping charges dynamically. Additionally, it provides robust cart and checkout functionality tailored to these customizations.

**Key Features:**
1. **Custom Dimension Inputs** - Adds fields for Width, Height, and Length on the single product page, displayed before the "Add to Cart" button.
2. **Disable Quantity Updates** - Prevents users from modifying product quantities on the single product page.
3. **Save User Input** - Captures and stores user-provided dimension values for each product.
4. **Cart Page Display** - Displays the saved dimensions in the cart for review.
5. **Quantity Lock in Cart** - Restricts quantity changes for products in the cart.
6. **Single Product Cart Limit** - Limits the cart to one product at a time.
7. **Dynamic Shipping Charges** - Calculates shipping costs based on the input dimensions.
8. **Checkout Integration** - Shows saved dimensions and dynamically calculated shipping charges during checkout.
9. **Order Details Display**:
   - Displays saved dimensions and shipping costs on the Thank You page.
   - Includes this information in order confirmation emails.
   - Makes them visible on the admin order edit screen.
10. **Default Shipping Charge Limit** - Sets a threshold for shipping costs. If exceeded, shipping is calculated dynamically based on dimensions. Dimension values are pre-filled in the input fields.

**Installation**

1. Upload the plugin files to the `/wp-content/plugins/woo-product-shipping-charges` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Configure default shipping settings in the WooCommerce settings page.

**Frequently Asked Questions**

**How do I add dimension fields to a product?** <br>
--The dimension input fields are automatically added to all single product pages. Users can fill in Width, Height, and Length values before adding the product to the cart.

**Can I disable quantity updates?** <br>
--Yes, the plugin restricts users from changing product quantities on the single product page and cart to maintain accurate shipping calculations.

**How does dynamic shipping calculation work?** <br>
--Shipping charges are calculated in real-time based on the input dimensions. 

**Can I display dimension details in order confirmation emails?** <br>
--Yes, the plugin includes saved dimensions and shipping charges in order confirmation emails sent to the customer.

**What happens if a user adds a second product to the cart?** <br>
--The plugin enforces a single-product cart policy, ensuring only one product is allowed at a time for precise shipping calculations.

**Can I customize the default shipping charge limit?** <br>
--Yes, you can set a threshold for default shipping charges. If the calculated shipping exceeds this limit, the dynamic calculation takes over.


**Changelog**

**1.0.0**
* Initial Release

**Upgrade Notice**

**1.0.0**
* Initial release of Woo Product Shipping Charges.

**Customization Example**

Modify the cart page display of dimensions using the following code snippet:

```php
function custom_cart_dimension_display($dimension_display, $cart_item) {
    $custom_display = sprintf(
        'Width: %s cm, Height: %s cm, Length: %s cm',
        $cart_item['custom_width'] ?? 'N/A',
        $cart_item['custom_height'] ?? 'N/A',
        $cart_item['custom_length'] ?? 'N/A'
    );
    return $custom_display;
}
add_filter('wps_shipping_cart_display', 'custom_cart_dimension_display', 10, 2);
