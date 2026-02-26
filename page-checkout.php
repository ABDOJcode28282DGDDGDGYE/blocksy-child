<?php
/**
 * Custom Checkout Page Template
 * المسار: blocksy-child/page-checkout.php
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

    <header class="cko-header">
        <div class="cko-header__inner">

            <a href="<?php echo esc_url( home_url('/') ); ?>" class="cko-logo" aria-label="Home">
                <?php if ( has_custom_logo() ) : the_custom_logo();
                else : ?><span class="cko-logo__text"><?php bloginfo('name'); ?></span><?php endif; ?>
            </a>

            <nav class="cko-steps" aria-label="Checkout progress">
                <div class="cko-step cko-step--done">
                    <span class="cko-step__num">
                        <svg width="11" height="11" viewBox="0 0 12 12" fill="none">
                            <path d="M2 6l3 3 5-5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    <span class="cko-step__label">Cart</span>
                </div>
                <span class="cko-step__line"></span>
                <div class="cko-step cko-step--active">
                    <span class="cko-step__num">2</span>
                    <span class="cko-step__label">Information</span>
                </div>
                <span class="cko-step__line"></span>
                <div class="cko-step">
                    <span class="cko-step__num">3</span>
                    <span class="cko-step__label">Payment</span>
                </div>
            </nav>

            <div class="cko-secure-badge">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
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

    <footer class="cko-footer">
        <div class="cko-footer__inner">
            <span>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.</span>
            <div class="cko-footer__links">
                <?php if ( get_privacy_policy_url() ) : ?>
                    <a href="<?php echo esc_url( get_privacy_policy_url() ); ?>">Privacy Policy</a>
                <?php endif; ?>
                <a href="<?php echo esc_url( wc_get_page_permalink('terms') ); ?>">Terms</a>
                <a href="<?php echo esc_url( home_url('/contact') ); ?>">Support</a>
            </div>
            <div class="cko-pay-icons">
                <svg viewBox="0 0 38 24" width="38" height="24"><rect width="38" height="24" rx="4" fill="#1A1F71"/><text x="19" y="16.5" text-anchor="middle" fill="white" font-size="10" font-weight="bold" font-family="Arial">VISA</text></svg>
                <svg viewBox="0 0 38 24" width="38" height="24"><rect width="38" height="24" rx="4" fill="#252525"/><circle cx="15" cy="12" r="7" fill="#EB001B"/><circle cx="23" cy="12" r="7" fill="#F79E1B"/><path d="M19 6.8a7 7 0 0 1 0 10.4A7 7 0 0 1 19 6.8z" fill="#FF5F00"/></svg>
                <svg viewBox="0 0 38 24" width="38" height="24"><rect width="38" height="24" rx="4" fill="#003087"/><text x="19" y="16" text-anchor="middle" fill="white" font-size="7.5" font-weight="bold" font-family="Arial">PayPal</text></svg>
            </div>
        </div>
    </footer>

</div>

<?php wp_footer(); ?>
</body>
</html>