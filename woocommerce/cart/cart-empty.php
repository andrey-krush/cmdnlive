<?php
/**
 * Empty cart page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

/*
 * @hooked wc_empty_cart_message - 10
 */
do_action( 'woocommerce_cart_is_empty' );

if ( wc_get_page_id( 'shop' ) > 0 ) : ?>

    <?php (new Promo_Section())->render(); ?>
    <?php $empty_cart = get_field('empty_cart'); ?>
    <p class="return-to-shop">
        <div class="order__empty">
            <?php if( !empty( $empty_cart['image'] ) ) : ?>
                <div class="order__empty-img img">
                    <img src="<?php echo $empty_cart['image']; ?>" alt="">
                </div>
            <?php endif; ?>
            <?php if( !empty( $empty_cart['title'] ) ) : ?>
                <div class="order__empty-title">
                    <h3><?php echo $empty_cart['title']; ?></h3>
                </div>
            <?php endif; ?>
            <?php if( !empty( $empty_cart['subtitle'] ) ) : ?>
                <div class="order__empty-subtitle">
                    <h4><?php echo $empty_cart['subtitle']; ?></h4>
                </div>
            <?php endif; ?>
            <a href="<?php echo $empty_cart['button']; ?>" class="section__btn order__empty-link">Shop Now</a>
        </div>
    </p>
<?php endif; ?>
