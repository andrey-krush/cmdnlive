<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart'); ?>


<main class="wrap__main">
    <?php (new Promo_Section())->render(); ?>
    <section class="cart">
        <div class="container">
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
            <div class="checkout__step">
                <a class="active">1. Cart</a>
                <a href="<?php echo wc_get_checkout_url(); ?>">2. Current information & payment</a>

            </div>
            <div class="cart__header">
                <h2 class="cart__header-title">Cart</h2>
                <h2 class="cart__header-count"><?php echo WC()->cart->get_cart_contents_count(); ?> goods</h2>
            </div>

            <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                <?php do_action('woocommerce_before_cart_table'); ?>


                <div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">

                    <div class="cart__list">
                        <?php do_action('woocommerce_before_cart_contents'); ?>

                        <?php
                        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                            $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                            /**
                             * Filter the product name.
                             *
                             * @param string $product_name Name of the product in the cart.
                             * @param array $cart_item The product in the cart.
                             * @param string $cart_item_key Key for the product in the cart.
                             * @since 2.1.0
                             */
                            $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);

                            if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                                $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                                ?>


                                <div data-cart_item="<?php echo $cart_item_key; ?>" class="cart__item woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                                    <div class="product-thumbnail">
                                        <?php
                                        $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key);

                                        if (!$product_permalink) {
                                            echo $thumbnail; // PHPCS: XSS ok.
                                        } else {
                                            printf('<a class="cart__img img" href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
                                        }
                                        ?>

                                    </div>
                                    <div class="cart__item-info">

                                        <div class="product-price cart__price"
                                             data-title="<?php esc_attr_e('Price', 'woocommerce'); ?>">
                                            <?php
                                            echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                                            ?>
                                        </div>


                                        <div class="cart__name product-name"
                                             data-title="<?php esc_attr_e('Product', 'woocommerce'); ?>">
                                            <?php
                                            if (!$product_permalink) {
                                                echo wp_kses_post($product_name . '&nbsp;');
                                            } else {
                                                /**
                                                 * This filter is documented above.
                                                 *
                                                 * @since 2.1.0
                                                 */
                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                            }

                                            do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                                            // Meta data.
                                            echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.

                                            // Backorder notification.
                                            if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                                echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('Available on backorder', 'woocommerce') . '</p>', $product_id));
                                            }
                                            ?>
                                        </div>
                                        <?php if (has_term('tickets', 'product_cat', $cart_item['product_id'])) : ?>
                                            <?php $ticket_info = get_field('ticket_info', $cart_item['product_id']); ?>
                                            <?php if (!empty($date)) : ?>
                                                <div class="shows__date shows__info">
                                                    <?php $date = $ticket_info['event_date']; ?>
                                                    <?php $date = DateTime::createFromFormat('d/m/Y', $date); ?>
                                                    <h4><?php echo $date->format('l, j F, Y'); ?>
                                                        , <?php echo $ticket_info['event_time']; ?></h4>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($ticket_info['venue'])) : ?>
                                                <?php $location = get_field('venue_info', $ticket_info['venue'])['location']; ?>
                                                <?php if (!empty($location)) : ?>
                                                    <div class="shows__location shows__info">
                                                        <a class="shows__map" data-lat='<?php echo $location['lat']; ?>'
                                                           data-lng='<?php echo $location['lng']; ?>'><?php echo get_the_title($ticket_info['venue']); ?></a>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <div class="cart__size">

                                            </div>
                                        <?php else : ?>
                                            <div class="cart__size">
                                            </div>
                                        <?php endif; ?>
                                        <div class="cart__quantity">
                                            <div class="product-quantity"
                                                 data-title="<?php esc_attr_e('Quantity', 'woocommerce'); ?>">
                                                <?php
                                                if ($_product->is_sold_individually()) {
                                                    $min_quantity = 1;
                                                    $max_quantity = 1;
                                                } else {
                                                    $min_quantity = 0;
                                                    $max_quantity = $_product->get_max_purchase_quantity();
                                                }

                                                $product_quantity = woocommerce_quantity_input(
                                                    array(
                                                        'input_name' => "cart[{$cart_item_key}][qty]",
                                                        'input_value' => $cart_item['quantity'],
                                                        'max_value' => $max_quantity,
                                                        'min_value' => $min_quantity,
                                                        'product_name' => $product_name,
                                                    ),
                                                    $_product,
                                                    false
                                                );

                                                echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item); // PHPCS: XSS ok.
                                                ?>
                                            </div>
                                            <div class="product-subtotal"
                                                 data-title="<?php esc_attr_e('Subtotal', 'woocommerce'); ?>">
                                                <?php
                                                echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // PHPCS: XSS ok.
                                                ?>
                                            </div>

                                        </div>


                                    </div>


                                    <div class="product-remove">
                                        <span></span>

                                    </div>


                                </div>
                                <?php
                            }
                        }
                        ?>

                        <?php do_action('woocommerce_cart_contents'); ?>





                        <?php do_action('woocommerce_after_cart_contents'); ?>
                    </div>
                    <div class="cart__check">
                        <div>
                            <div class="actions">

                                <?php if (wc_coupons_enabled()) { ?>
                                    <div class="coupon">
                                        <label for="coupon_code"
                                               class="screen-reader-text"><?php esc_html_e('Coupon:', 'woocommerce'); ?></label>
                                        <input type="text" name="coupon_code" class="input-text" id="coupon_code"
                                               value=""
                                               placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>"/>
                                        <button type="button"
                                                class="button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>"
                                                name="apply_coupon">Apply</button>
                                        <?php do_action('woocommerce_cart_coupon'); ?>
                                    </div>
                                <?php } ?>

                                <button type="submit"
                                        class="button<?php echo esc_attr(wc_wp_theme_get_element_class_name('button') ? ' ' . wc_wp_theme_get_element_class_name('button') : ''); ?>"
                                        name="update_cart"
                                        value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"><?php esc_html_e('Update cart', 'woocommerce'); ?></button>

                                <?php do_action('woocommerce_cart_actions'); ?>

                                <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
                            </div>
                        </div>


                        <?php do_action('woocommerce_before_cart_collaterals'); ?>

                        <div class="cart-collaterals">
                            <?php
                            /**
                             * Cart collaterals hook.
                             *
                             * @hooked woocommerce_cross_sell_display
                             * @hooked woocommerce_cart_totals - 10
                             */
                            do_action('woocommerce_cart_collaterals');
                            ?>
                        </div>

                        <?php do_action('woocommerce_after_cart'); ?>
                    </div>

                    <?php do_action('woocommerce_after_cart_table'); ?>
            </form>
        </div>
    </section>
</main>




