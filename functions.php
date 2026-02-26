<?php
add_action( 'wp_enqueue_scripts', 'blocksy_child_enqueue_styles' );
function blocksy_child_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}



/**
 * Custom Checkout — Functions Snippet
 * أضف هذا الكود في نهاية: blocksy-child/functions.php
 */

/* ════════════════════════════════════════════
   1. تحميل Assets في صفحة Checkout فقط
════════════════════════════════════════════ */
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


/* ════════════════════════════════════════════
   2. إزالة Blocksy styles/scripts في Checkout
════════════════════════════════════════════ */
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


/* ════════════════════════════════════════════
   3. Order Summary heading
════════════════════════════════════════════ */
add_action( 'woocommerce_checkout_before_order_review', 'cko_order_heading' );
function cko_order_heading() {
    if ( ! is_checkout() ) return;
    echo '<h3 class="cko-order-heading">Order Summary</h3>';
}


/* ════════════════════════════════════════════
   4. Trust Badges تحت زر Place Order
════════════════════════════════════════════ */
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
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            <span>24/7 Support</span>
        </div>
        <p class="secure-note">Secure payment powered by encrypted SSL</p>
    </div>
    <?php
}



/* ════════════════════════════════════════════
   6. Show All Fields Properly
   عرض جميع الحقول بشكل صحيح
════════════════════════════════════════════ */

// أ. إظهار جميع حقول الشحن
add_filter( 'woocommerce_cart_needs_shipping_address', '__return_true' );

// ب. عدم حذف أي حقول
add_filter( 'woocommerce_checkout_fields', 'cko_show_all_fields' );
function cko_show_all_fields( $fields ) {
    // لا تحذف أي حقول - اعرض الكل
    return $fields;
}

// ج. التأكد أن جميع الحقول والملاحظات تظهر دائماً
add_filter( 'woocommerce_enable_order_notes_field', '__return_true' );

// د. عرض حقول إضافية مخصصة
add_filter( 'woocommerce_checkout_fields', 'cko_add_custom_fields' );
function cko_add_custom_fields( $fields ) {
    // التأكد من عدم إخفاء أي حقول موجودة
    if ( isset( $fields['billing'] ) ) {
        foreach ( $fields['billing'] as $key => $field ) {
            if ( isset( $field['required'] ) ) {
                $field['class'] = isset( $field['class'] ) ? $field['class'] : [];
                if ( !is_array( $field['class'] ) ) {
                    $field['class'] = array( $field['class'] );
                }
                $fields['billing'][$key] = $field;
            }
        }
    }
    return $fields;
}
