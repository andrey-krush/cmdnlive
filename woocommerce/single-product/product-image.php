<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
	return;
}

global $product;

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters(
	'woocommerce_single_product_image_gallery_classes',
	array(
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'images',
	)
);

$gallery_ids = $product->get_gallery_image_ids();
$post_thumbnail = get_the_post_thumbnail_url();
$post_thumbnail_id = get_post_thumbnail_id();
?>

<?php if( empty( $gallery_ids ) and !empty( $post_thumbnail ) ) : ?>

    <div class="single_image img">
        <img src="<?php echo $post_thumbnail; ?>">
    </div>

<?php elseif( empty( $post_thumbnail ) and ( count($gallery_ids) == 1 ) ): ?>

    <div class="single_image img">
        <img src="<?php echo wp_get_attachment_image_url($gallery_ids[0]); ?>">
    </div>

<?php else : ?>
    <div class="product-images">
        <div class="gallery-top swiper-container">
            <div class="swiper-wrapper">
                <?php
                if( !empty( $post_thumbnail ) ) :
                    echo '<div class="swiper-slide img">';
                    echo wp_get_attachment_image($post_thumbnail_id, 'woocommerce_single');
                    echo '</div>';
                endif;

                foreach ($product->get_gallery_image_ids() as $attachment_id) {
                    echo '<div class="swiper-slide img">';
                    echo wp_get_attachment_image($attachment_id, 'woocommerce_single');
                    echo '</div>';
                }
                ?>
            </div>

        </div>

        <div class="gallery-thumbs swiper-container">
            <div class="swiper-wrapper">
                <?php

                if( !empty( $post_thumbnail ) ) :
                    echo '<div class="swiper-slide img">';
                    echo wp_get_attachment_image($post_thumbnail_id, 'woocommerce_thumbnail');
                    echo '</div>';
                endif;

                foreach ($product->get_gallery_image_ids() as $attachment_id) {
                    echo '<div class="swiper-slide img">';
                    echo wp_get_attachment_image($attachment_id, 'woocommerce_thumbnail');
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
<?php endif; ?>