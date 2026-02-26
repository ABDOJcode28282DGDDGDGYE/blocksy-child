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
   3. Keep only 4 billing fields + remove shipping & additional
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

    // Make email full width (span 2 cols)
    if ( isset( $fields['billing']['billing_email'] ) ) {
        $fields['billing']['billing_email']['class'] = [ 'form-row-wide' ];
    }
    if ( isset( $fields['billing']['billing_phone'] ) ) {
        $fields['billing']['billing_phone']['class'] = [ 'form-row-wide' ];
    }

    // Remove shipping fields
    $fields['shipping'] = [];

    // Remove order notes / additional fields
    if ( isset( $fields['order'] ) ) {
        $fields['order'] = [];
    }

    return $fields;
}

// Disable "Ship to different address"
add_filter( 'woocommerce_cart_needs_shipping_address', '__return_false' );

// Disable order notes
add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );


/* ================================================================
   4. Remove shipping fields HTML & additional fields HTML
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

    // Remove shipping fields div
    $html = preg_replace(
        '/<div class="woocommerce-shipping-fields">[\s\S]*?<\/div>\s*<\/div>/U',
        '',
        $html
    );

    // Remove additional fields div
    $html = preg_replace(
        '/<div class="woocommerce-additional-fields">[\s\S]*?<\/div>\s*<\/div>\s*<\/div>/Us',
        '',
        $html
    );

    echo $html;
}


/* ================================================================
   5. Add "Select Your Device" section after billing fields
================================================================ */
add_action( 'woocommerce_after_checkout_billing_form', 'cko_device_selector' );
function cko_device_selector() {
    if ( ! is_checkout() ) return;
    ?>
    </div><!-- close .woocommerce-billing-fields -->
    <div class="cko-device-section">
        <h3 class="cko-section-heading">
            <span class="cko-step-num">2</span>
            Select Your Device
        </h3>
        <div class="cko-device-grid">
            <label class="cko-device-card">
                <input type="radio" name="cko_device" value="smart_tv" checked>
                <span class="cko-device-card__inner">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/><path d="M7 7l3 3-3 3"/></svg>
                    <span>Smart TV</span>
                </span>
            </label>
            <label class="cko-device-card">
                <input type="radio" name="cko_device" value="android_box">
                <span class="cko-device-card__inner">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/></svg>
                    <span>Android Box</span>
                </span>
            </label>
            <label class="cko-device-card">
                <input type="radio" name="cko_device" value="fire_tv_stick">
                <span class="cko-device-card__inner">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/><path d="M15 7l-3 3 3 3"/></svg>
                    <span>Fire TV Stick</span>
                </span>
            </label>
            <label class="cko-device-card">
                <input type="radio" name="cko_device" value="mobile_tablet">
                <span class="cko-device-card__inner">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2"/><path d="M12 18h.01"/></svg>
                    <span>Mobile/Tablet</span>
                </span>
            </label>
            <label class="cko-device-card">
                <input type="radio" name="cko_device" value="mag_box">
                <span class="cko-device-card__inner">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/></svg>
                    <span>MAG Box</span>
                </span>
            </label>
            <label class="cko-device-card">
                <input type="radio" name="cko_device" value="other">
                <span class="cko-device-card__inner">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/></svg>
                    <span>Other</span>
                </span>
            </label>
        </div>
        <input type="hidden" name="billing_cko_device" id="billing_cko_device" value="smart_tv">
    <div style="display:none;"><!-- dummy open to balance the close from WooCommerce -->
    <?php
}


/* ================================================================
   6. Save device choice to order meta
================================================================ */
add_action( 'woocommerce_checkout_update_order_meta', 'cko_save_device' );
function cko_save_device( $order_id ) {
    if ( ! empty( $_POST['cko_device'] ) ) {
        update_post_meta( $order_id, '_cko_device', sanitize_text_field( $_POST['cko_device'] ) );
    }
}

// Show device in admin order page
add_action( 'woocommerce_admin_order_data_after_billing_address', 'cko_show_device_admin' );
function cko_show_device_admin( $order ) {
    $device = get_post_meta( $order->get_id(), '_cko_device', true );
    if ( $device ) {
        echo '<p><strong>Device:</strong> ' . esc_html( ucwords( str_replace('_', ' ', $device) ) ) . '</p>';
    }
}


/* ================================================================
   7. Order Summary heading
================================================================ */
add_action( 'woocommerce_checkout_before_order_review', 'cko_order_heading' );
function cko_order_heading() {
    if ( ! is_checkout() ) return;
    echo '<h3 class="cko-order-heading">Order Summary</h3>';
}


/* ================================================================
   8. Trust Badges below Place Order button
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
   9. Change Place Order button text
================================================================ */
add_filter( 'woocommerce_order_button_text', 'cko_order_button_text' );
function cko_order_button_text() {
    return 'Complete Order';
}
