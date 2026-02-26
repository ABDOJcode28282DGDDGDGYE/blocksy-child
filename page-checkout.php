<?php
/**
 * Custom Checkout Page Template
 * blocksy-child/page-checkout.php
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'body_class', function( $classes ) {
    $keep = [];
    foreach ( $classes as $c ) {
        if ( strpos($c,'woocommerce') !== false || strpos($c,'logged') !== false || $c === 'rtl' ) {
            $keep[] = $c;
        }
    }
    $keep[] = 'custom-checkout-page';
    return $keep;
}, 99 );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title><?php echo esc_html( get_bloginfo('name') ); ?> — Checkout</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div class="cko-page">

    <!-- Header: Logo + Steps + Secure -->
    <header class="cko-header">
        <div class="cko-header__inner">
            <a href="<?php echo esc_url( home_url('/') ); ?>" class="cko-logo" aria-label="Home">
                <?php if ( has_custom_logo() ) : the_custom_logo();
                else : ?><span class="cko-logo__text"><?php bloginfo('name'); ?></span><?php endif; ?>
            </a>

            <nav class="cko-steps" aria-label="Checkout progress">
                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cko-step cko-step--done">
                    <span class="cko-step__num">1</span>
                    <span class="cko-step__label">Cart</span>
                </a>
                <span class="cko-step__sep" aria-hidden="true"></span>
                <span class="cko-step cko-step--active">
                    <span class="cko-step__num">2</span>
                    <span class="cko-step__label">Information</span>
                </span>
                <span class="cko-step__sep" aria-hidden="true"></span>
                <span class="cko-step">
                    <span class="cko-step__num">3</span>
                    <span class="cko-step__label">Payment</span>
                </span>
            </nav>

            <div class="cko-secure">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                </svg>
                <span>Secure Checkout</span>
            </div>
        </div>
    </header>

    <main class="cko-main">
        <div class="cko-container">
            <?php wc_print_notices(); ?>
            <?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="cko-footer">
        <div class="cko-footer__inner">
            <p class="cko-footer__copy">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</p>
            <div class="cko-footer__links">
                <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>">Privacy Policy</a>
                <a href="/terms">Terms of Service</a>
                <a href="/contact">Contact</a>
            </div>
            <div class="cko-footer__cards" aria-label="Accepted payment methods">
                <svg width="38" height="24" viewBox="0 0 38 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Visa">
                    <rect width="38" height="24" rx="4" fill="#1A1F71"/>
                    <text x="19" y="15" fill="white" font-size="9" font-weight="bold" text-anchor="middle" font-family="Arial,sans-serif">VISA</text>
                </svg>
                <svg width="38" height="24" viewBox="0 0 38 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="Mastercard">
                    <rect width="38" height="24" rx="4" fill="#252525"/>
                    <circle cx="15" cy="12" r="6" fill="#EB001B" opacity="0.9"/>
                    <circle cx="23" cy="12" r="6" fill="#F79E1B" opacity="0.9"/>
                </svg>
                <svg width="38" height="24" viewBox="0 0 38 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-label="PayPal">
                    <rect width="38" height="24" rx="4" fill="#003087"/>
                    <text x="19" y="14.5" fill="white" font-size="7" font-weight="bold" text-anchor="middle" font-family="Arial,sans-serif">PayPal</text>
                </svg>
            </div>
        </div>
    </footer>

</div>

<?php wp_footer(); ?>
</body>
</html>
