jQuery(document).ready(function ($) {

    // Toggle add to cart button
    function wpscToggleAddToCartButton(){
        const wpscWidth = $('#wpsc_product_width').val();
        const wpscHeight = $('#wpsc_product_height').val();
        const wpscLength = $('#wpsc_product_length').val();
        const wpscAddToCartButton  = $('button[name="add-to-cart"]');
        if(wpscWidth && wpscHeight && wpscLength){
            wpscAddToCartButton.prop('disabled', false);
        }else{
            wpscAddToCartButton.prop('disabled', true);
        }
    }

    // on input change 
    $('.wpsc-form-input').on('input', wpscToggleAddToCartButton);

    // Run on page load
    wpscToggleAddToCartButton();
});