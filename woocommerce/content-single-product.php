<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action('woocommerce_before_single_product');

if (post_password_required()) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}

$popularity = get_post_meta(get_the_ID(), 'popularity', true);

if (!empty($popularity)) :
    $popularity = $popularity + 1;
    update_post_meta(get_the_ID(), 'popularity', $popularity);
else :
    update_post_meta(get_the_ID(), 'popularity', 1);
endif;

?>

<?php if (has_term('clothes', 'product_cat', $product->get_id())) : ?>
    <main class="wrap__main clothes">
<?php else : ?>
    <main class="wrap__main tickets__wrap">
<?php endif; ?>
        <?php (new Front_Page_Slider_Section() )->render(); ?>
        <?php $promo_section = get_field('products_promo_section', 'option'); ?>
        <?php if ($promo_section['is_section']) : ?>
            <section class="delivery">
                <div class="delivery__block">
                    <?php if (!empty($promo_section['background_image'])) : ?>
                        <div class="main__back img">
                            <img src="<?php echo $promo_section['background_image']; ?>" alt="">
                        </div>
                    <?php endif; ?>
                    <div class="container">
                        <div class="breadcrumbs">
                            <a href="<?php echo home_url(); ?>">Home</a>
                            <?php if (has_term('clothes', 'product_cat', get_the_ID())) : ?>
                                <a href="<?php echo (new Clothes_Page())::get_url(); ?>">Clothes</a>
                            <?php elseif (has_term('tickets', 'product_cat', get_the_ID())) : ?>
                                <a href="<?php echo (new Tickets_Page())::get_url(); ?>">Tickets</a>
                            <?php endif; ?>
                            <a><?php echo get_the_title(); ?></a>
                        </div>
                        <?php if (!empty($promo_section['text'])) : ?>
                            <div class="delivery__title">
                                <?php if (has_term('clothes', 'product_cat', get_the_ID())) : ?>
                                    <h2><?php echo $promo_section['text']; ?></h2>
                                <?php elseif (has_term('tickets', 'product_cat', get_the_ID())) : ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        <section class="oneproduct" data-id="<?php echo get_the_ID(); ?>">
            <div class="preloader__wrap">
                <div class="preloader">
                    <svg viewBox="0 0 102 102" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path class="big-circle" d="M101 51C101 78.6142 78.6142 101 51 101C23.3858 101 1 78.6142 1 51"
                              stroke="#D98C72" stroke-width="2"/>
                        <path class="small-circle" d="M91 51C91 28.9086 73.0914 11 51 11C28.9086 11 11 28.9086 11 51"
                              stroke="#D98C72" stroke-width="2"/>
                    </svg>
                </div>
            </div>
            <div class="scroll__info">
                <div class="container">

                    <?php
                    /**
                     * Hook: woocommerce_single_product_summary.
                     *
                     * @hooked woocommerce_template_single_title - 5
                     * @hooked woocommerce_template_single_rating - 10
                     * @hooked woocommerce_template_single_price - 10
                     * @hooked woocommerce_template_single_excerpt - 20
                     * @hooked woocommerce_template_single_add_to_cart - 30
                     * @hooked woocommerce_template_single_meta - 40
                     * @hooked woocommerce_template_single_sharing - 50
                     * @hooked WC_Structured_Data::generate_product_data() - 60
                     */
                    do_action('woocommerce_single_product_summary');
                    ?>
                </div>
            </div>
            <div class="container">
                <!--            <div class="article__back">-->
                <!--                <button></button>-->
                <!--            </div>-->
                <div id="product-<?php the_ID(); ?>" <?php wc_product_class('', $product); ?>>

                    <div class="oneproduct__block">
                        <div class="oneproduct__gallery">
                            <?php
                            /**
                             * Hook: woocommerce_before_single_product_summary.
                             *
                             * @hooked woocommerce_show_product_sale_flash - 10
                             * @hooked woocommerce_show_product_images - 20
                             */
                            do_action('woocommerce_before_single_product_summary');
                            ?>
                        </div>
                        <div class="swiper-pagination"></div>

                        <div class="oneproduct__desc">
                            <div class="summary entry-summary ">
                                <div class="oneproduct__social">
                                    <div class="article__social-wrap">
                                        <div class="article__social">
                                            <?php $permalink = get_the_permalink(); ?>
                                            <?php $title = get_the_title(); ?>
                                            <?php
                                            $title_share = str_replace('&', '', $title);
                                            $title_share = str_replace('#038;', '', $title_share);
                                            $title_share = str_replace('&amp;', '', $title_share);
                                            $title_share = str_replace('#', '', $title_share);
                                            ?>
                                            <span>Share</span>
                                            <a href="https://www.instagram.com/?url=<?php echo $permalink; ?>"
                                               target="_blank" class="article__social-item img article__instagram">
                                                <img src="<?= TEMPLATE_PATH ?>/img/instagram.svg" alt="">
                                            </a>
                                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $permalink; ?>"
                                               target="_blank" class="article__social-item img article__facebook">
                                                <img src="<?= TEMPLATE_PATH ?>/img/facebook.svg" alt="">
                                            </a>
                                            <a class="article__social-item img article__copy"
                                               data-copy="<?php echo $permalink; ?>">
                                                <img src="<?= TEMPLATE_PATH ?>/img/link.svg" alt="">
                                                <span class="article__copy-mess">Click to copy</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                /**
                                 * Hook: woocommerce_single_product_summary.
                                 *
                                 * @hooked woocommerce_template_single_title - 5
                                 * @hooked woocommerce_template_single_rating - 10
                                 * @hooked woocommerce_template_single_price - 10
                                 * @hooked woocommerce_template_single_excerpt - 20
                                 * @hooked woocommerce_template_single_add_to_cart - 30
                                 * @hooked woocommerce_template_single_meta - 40
                                 * @hooked woocommerce_template_single_sharing - 50
                                 * @hooked WC_Structured_Data::generate_product_data() - 60
                                 */
                                do_action('woocommerce_single_product_summary');
                                ?>
                            </div>
                            <?php if (has_term('tickets', 'product_cat', get_the_ID())) : ?>
                                <?php $ticket_info = get_field('ticket_info'); ?>
                                <div class="oneproduct__address">
                                    <?php if (!empty($ticket_info['venue'])) : ?>
                                        <?php $venue = get_field('venue_info', $ticket_info['venue']); ?>
                                        <?php if (!empty($venue['location'])) : ?>
                                            <div class="shows__location shows__info">
                                                <p><?php echo $venue['location']['address']; ?></p>
                                                <a data-lat="<?php echo $venue['location']['lat']; ?>"
                                                   data-lng="<?php echo $venue['location']['lng']; ?>"
                                                   class="shows__map"><?php echo get_the_title($ticket_info['venue']); ?></a>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php if (!empty($ticket_info['event_date'])) : ?>
                                        <?php $date_object = DateTime::createFromFormat('d/m/Y', $ticket_info['event_date']); ?>
                                        <div class="shows__date shows__info">
                                            <p><?php echo $date_object->format('l'); ?></p>
                                            <h4><?php echo $date_object->format('d F, Y'); ?></h4>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            <?php $tickets_main_category = get_term_by('slug', 'tickets', 'product_cat'); ?>
                            <?php if (!empty($tickets_main_category)) : ?>
                                <?php $product_subcategories = get_terms([
                                    'taxonomy' => 'product_cat',
                                    'parent' => $tickets_main_category->term_id,
                                    'object_ids' => [get_the_ID()]
                                ]);
                                ?>
                            <?php endif; ?>
                            <?php if (!empty($product_subcategories)) : ?>
                                <div class="oneproduct__categories">
                                    <div class="tickets__categories">
                                        <?php foreach ($product_subcategories as $item) : ?>
                                            <div class="tickets__categories-name"><?php echo $item->name; ?></div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (has_term('tickets', 'product_cat', get_the_ID())) : ?>
                                <?php if (!empty($ticket_info['sponsor'])) : ?>
                                    <div class="oneproduct__sponsored">
                                        <h4>Sponsored by <span><?php echo $ticket_info['sponsor']; ?></span></h4>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>


                            <?php $short_description = $product->get_short_description(); ?>
                            <?php if (!empty($short_description)) : ?>
                                <div class="oneproduct__text">
                                    <?php echo $short_description; ?>
                                </div>
                            <?php endif; ?>
                            <?php $description = $product->get_description(); ?>
                            <?php if (has_term('tickets', 'product_cat', get_the_ID()) and !empty($description)) : ?>
                                <div class="oneproduct__description">
                                    <span class="oneproduct__description-drop">Description</span>
                                    <div class="oneproduct__description-inner">
                                        <?php echo $description; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php $under_description = get_field('under_description'); ?>
                            <?php if (!empty($under_description)) : ?>
                                <div class="oneproduct__bottom">
                                    <?php foreach ($under_description as $item) : ?>

                                        <?php if (($item['is_text'] == true) and !empty($item['image'])) : ?>
                                            <div class="oneproduct__bottom-img img">
                                                <img src="<?php echo $item['image']; ?>" alt="">
                                            </div>
                                        <?php elseif ($item['is_text'] == false and !empty($item['text'])) : ?>
                                            <p><?php echo $item['text']; ?></p>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php if (has_term('tickets', 'product_cat', get_the_ID())) : ?>
            <?php $bands_section = get_field('bands_section'); ?>
            <?php $title = $bands_section['title']; ?>
            <?php $text = $bands_section['text']; ?>
            <?php $bands = $bands_section['bands']; ?>
            <?php if (!empty($text) or !empty($bands)) : ?>
                <section class="bands">
                    <div class="container">
                        <?php if( !empty( $title ) ) : ?>
                            <h2 class="section__title"><?php echo $title; ?></h2>
                        <?php endif; ?>
                        <?php if (!empty($text)) : ?>
                            <div class="bands__subtitle">
                                <?php echo $text; ?>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($bands)) : ?>
                            <ul class="bands__list">
                                <?php foreach ($bands as $item) : ?>
                                    <?php $post_thumbnail = get_the_post_thumbnail_url($item); ?>
                                    <li class="bands__item">
                                        <?php if (!empty($post_thumbnail)) : ?>
                                            <div class="bands__img img">
                                                <img src="<?php echo $post_thumbnail; ?>" alt="">
                                            </div>
                                        <?php endif; ?>
                                        <div class="bands__text">
                                            <h2 class="bands__title"><?php echo get_the_title($item); ?></h2>
                                            <?php $content = strip_tags(apply_filters('the_content', get_the_content(post: $item))); ?>
                                            <?php if (!empty($content)) : ?>
                                                <h3 class="bands__desc"><?php echo $content; ?></h3>
                                            <?php endif; ?>
                                            <a href="<?php echo get_the_permalink($item); ?>" class="bands__link"></a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </section>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (has_term('clothes', 'product_cat', get_the_ID())) : ?>
            <?php $related_products_section = get_field('related_products_section'); ?>
            <?php $related_products = $related_products_section['related_products']; ?>
            <?php if (!empty($related_products)) : ?>
                <section class="oneproduct__related">
                    <div class="container">
                        <h2 class="oneproduct__related-title">Related products</h2>
                        <ul class="shop__list">
                            <?php foreach ($related_products as $item) : ?>
                                <?php $product = wc_get_product($item); ?>
                                <li class="shop__item">
                                    <?php $post_thumbnail = get_the_post_thumbnail_url($item); ?>
                                    <?php if (!empty($post_thumbnail)) : ?>
                                        <a class="shop__img img" href="<?php echo get_the_permalink($item); ?>"> <img
                                                    src="<?php echo $post_thumbnail; ?>" alt=""></a>
                                    <?php endif; ?>
                                    <div class="shop__desc">
                                        <h3 class="shop__title"><?php echo get_the_title($item); ?></h3>
                                        <?php if( $product->is_type('simple') ) : ?>
                                            <?php if ($product->is_on_sale()) : ?>
                                                <div class="tickets__price-flex">
                                                    <h4 class="tickets__price">
                                                        £ <?php echo $product->get_sale_price(); ?>
                                                    </h4>
                                                    <h4 class="tickets__price-old">
                                                        £ <?php echo $product->get_regular_price(); ?>
                                                    </h4>
                                                </div>
                                            <?php else : ?>
                                                <h4 class="tickets__price">
                                                    £ <?php echo $product->get_price(); ?>
                                                </h4>
                                            <?php endif; ?>
                                        <?php elseif( $product->is_type('variable') ) : ?>
                                            <?php if ($product->is_on_sale()) : ?>
                                                <?php $display_price = ''; ?>
                                                <?php $variations = $product->get_available_variations(); ?>
                                                <?php $regular_price = intval($product->get_variation_regular_price('min')); ?>
                                                <?php foreach ( $variations as $item ) : ?>
                                                    <?php if( $item['display_regular_price'] == $regular_price and ( $item['display_price'] != $regular_price ) ) :  ?>
                                                        <?php $display_price = $item['display_price']; ?>
                                                        <?php break; ?>
                                                    <?php endif;  ?>
                                                <?php endforeach; ?>
                                                <?php if( empty(  $display_price) ) : ?>
                                                    <h4 class="tickets__price">
                                                        £ <?php echo $regular_price; ?>
                                                    </h4>
                                                <?php else : ?>
                                                    <div class="tickets__price-flex">
                                                        <h4 class="tickets__price">
                                                            £ <?php echo $display_price; ?>
                                                        </h4>
                                                        <h4 class="tickets__price-old">
                                                            £ <?php echo $regular_price; ?>
                                                        </h4>
                                                    </div>
                                                <?php endif; ?>
                                            <?php else : ?>
                                                <h4 class="tickets__price">
                                                    £ <?php echo intval( $product->get_variation_regular_price('min' )); ?>
                                                </h4>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <a class="shop__link" href="<?php echo get_the_permalink($item); ?>">Details</a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </section>
            <?php endif; ?>
        <?php endif; ?>
        <?php (new Front_Page_Subscription_Section() )->render(); ?>
    </main>

    <?php do_action('woocommerce_after_single_product'); ?>


