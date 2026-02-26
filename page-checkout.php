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

    <!-- Simplified Header: Brand + Subtitle centered -->
    <header class="cko-header">
        <div class="cko-header__inner">
            <a href="<?php echo esc_url( home_url('/') ); ?>" class="cko-logo" aria-label="Home">
                <?php if ( has_custom_logo() ) : the_custom_logo();
                else : ?><span class="cko-logo__text"><?php bloginfo('name'); ?></span><?php endif; ?>
            </a>
            <p class="cko-subtitle">Complete your subscription order</p>
        </div>
    </header>

    <main class="cko-main">
        <div class="cko-container">
            <?php wc_print_notices(); ?>
            <?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
        </div>
    </main>

</div>

<?php wp_footer(); ?>
</body>
</html>
