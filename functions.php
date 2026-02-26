<?php
add_action( 'wp_enqueue_scripts', 'blocksy_child_enqueue_styles' );
function blocksy_child_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}



/**
 * Custom Checkout — Functions Snippet
 * blocksy-child/functions.php
 */

/* ================================================================
   1. Load Assets on Checkout only
================================================================ */
add_action( 'wp_enqueue_scripts', 'cko_assets' );
function cko_assets() {
    if ( ! is_checkout() ) return;

    wp_enqueue_style(
        'cko-style',
        get_stylesheet_directory_uri() . '/checkout-assets/checkout.css',
        [],
        filemtime( get_stylesheet_directory() . '/checkout-assets/checkout.css' )
    );

    wp_enqueue_script(
        'cko-script',
        get_stylesheet_directory_uri() . '/checkout-assets/checkout.js',
        [ 'jquery', 'wc-checkout' ],
        filemtime( get_stylesheet_directory() . '/checkout-assets/checkout.js' ),
        true
    );
}


/* ================================================================
   2. Remove Blocksy styles/scripts on Checkout
================================================================ */
add_action( 'wp_enqueue_scripts', 'cko_remove_blocksy', 100 );
function cko_remove_blocksy() {
    if ( ! is_checkout() ) return;

    wp_dequeue_style( 'blocksy-main' );
    wp_dequeue_style( 'blocksy-child-styles' );
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'global-styles' );
    wp_dequeue_style( 'classic-theme-styles' );
    wp_dequeue_script( 'blocksy-main' );
    wp_dequeue_script( 'blocksy-child-main' );
}


/* ================================================================
   3. Keep only 4 billing fields
================================================================ */
add_filter( 'woocommerce_checkout_fields', 'cko_simplify_fields' );
function cko_simplify_fields( $fields ) {
    // Keep only these billing fields
    $keep = [ 'billing_first_name', 'billing_last_name', 'billing_email', 'billing_phone' ];
    foreach ( $fields['billing'] as $key => $field ) {
        if ( ! in_array( $key, $keep ) ) {
            unset( $fields['billing'][ $key ] );
        }
    }

    // Make email and phone full width
    if ( isset( $fields['billing']['billing_email'] ) ) {
        $fields['billing']['billing_email']['class'] = [ 'form-row-wide' ];
    }
    if ( isset( $fields['billing']['billing_phone'] ) ) {
        $fields['billing']['billing_phone']['class'] = [ 'form-row-wide' ];
    }

    // Remove shipping fields
    $fields['shipping'] = [];

    return $fields;
}

// Disable "Ship to different address"
add_filter( 'woocommerce_cart_needs_shipping_address', '__return_false' );


/* ================================================================
   4. Remove shipping fields HTML via buffer
================================================================ */
add_action( 'woocommerce_checkout_before_customer_details', 'cko_buffer_start' );
function cko_buffer_start() {
    if ( ! is_checkout() ) return;
    ob_start();
}

add_action( 'woocommerce_checkout_after_customer_details', 'cko_buffer_clean' );
function cko_buffer_clean() {
    if ( ! is_checkout() ) return;
    $html = ob_get_clean();

    // Remove shipping fields div only
    $html = preg_replace(
        '/<div class="woocommerce-shipping-fields">[\s\S]*?<\/div>\s*<\/div>/U',
        '',
        $html
    );

    echo $html;
}


/* ================================================================
   5. Order Summary heading
================================================================ */
add_action( 'woocommerce_checkout_before_order_review', 'cko_order_heading' );
function cko_order_heading() {
    if ( ! is_checkout() ) return;
    echo '<h3 class="cko-order-heading">Order Summary</h3>';
}


/* ================================================================
   6. Trust Badges below Place Order button
================================================================ */
add_action( 'woocommerce_review_order_after_submit', 'cko_trust_badges' );
function cko_trust_badges() {
    if ( ! is_checkout() ) return;
    ?>
    <div class="checkout-trust-badges">
        <div class="trust-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                <path d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <span>Instant Activation</span>
        </div>
        <div class="trust-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
            </svg>
            <span>30,000+ Live Channels</span>
        </div>
        <div class="trust-item">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
            <span>24/7 Support</span>
        </div>
        <p class="secure-note">Secure payment powered by encrypted SSL</p>
    </div>
    <?php
}


/* ================================================================
   7. Change Place Order button text
================================================================ */
add_filter( 'woocommerce_order_button_text', 'cko_order_button_text' );
function cko_order_button_text() {
    return 'Complete Order';
}
