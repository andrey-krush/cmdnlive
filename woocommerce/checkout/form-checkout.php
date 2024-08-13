<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
}

?>


<section class="delivery">
    <div class="delivery__block">
        <div class="main__back img">
            <img src="<?= TEMPLATE_PATH ?>/img/delivery-back.png" alt="">
        </div>
        <div class="container">
            <div class="breadcrumbs">
                <a href="#">Home</a>
                <a href="#">Checkout</a>
            </div>
            <div class="delivery__title">
                <h2>Free shipping for orders over £100</h2>
            </div>
        </div>
    </div>
</section>
<?php $cart = WC()->cart->get_cart(); ?>
<?php $tickets = []; ?>
<?php $clothes = []; ?>
<?php foreach ($cart as $key => $item) :

    if (has_term('tickets', 'product_cat', $item['product_id'])) :
        $tickets[$key] = $item;
    elseif (has_term('clothes', 'product_cat', $item['product_id'])) :
        $clothes[$key] = $item;
    endif;

endforeach;

?>
<section class="checkout__wrap">
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
    <div class="container">
        <div class="order__empty">
            <?php if (!empty($empty_cart['image'])) : ?>
                <div class="order__empty-img img">
                    <img src="<?php echo $empty_cart['image']; ?>" alt="">
                </div>
            <?php endif; ?>
            <?php if (!empty($empty_cart['title'])) : ?>
                <div class="order__empty-title">
                    <h3><?php echo $empty_cart['title']; ?></h3>
                </div>
            <?php endif; ?>
            <?php if (!empty($empty_cart['subtitle'])) : ?>
                <div class="order__empty-subtitle">
                    <h4><?php echo $empty_cart['subtitle']; ?></h4>
                </div>
            <?php endif; ?>
            <a href="<?php echo $empty_cart['button']; ?>" class="section__btn order__empty-link">Shop Now</a>
        </div>
        <div class="checkout__step">
            <a href="<?php echo wc_get_cart_url(); ?>">1. Cart</a>
            <a class="active">2. Current information & payment</a>
        </div>
        <form name="checkout" method="post" class="checkout woocommerce-checkout"
              action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

            <div class="tabs">
                <div class="profile__header">
                    <?php if (!empty($tickets) and !empty($clothes)) : ?>
                        <span class="tab active">Tickets</span>
                        <span class="tab">Merch & Fashion</span>
                    <?php elseif (!empty($tickets) and empty($clothes))  : ?>
                        <span class="tab active">Tickets</span>
                    <?php elseif (!empty($clothes) and empty($tickets))  : ?>
                        <span class="tab active">Merch & Fashion</span>
                    <?php endif; ?>
                </div>
                <div class="tab__content">
                    <?php if (!empty($tickets)) : ?>
                        <div class="tab__item tab__item-tickets">
                            <div class="checkout__tickets">
                                <div class="checkout__tickets-form">
                                    <ul class="checkout__tickets-list">
                                        <?php $total_tickets_quantity = 0; ?>
                                        <?php foreach ($tickets as $key => $item) : ?>
                                            <?php $total_tickets_quantity += $item['quantity']; ?>
                                            <?php $price = $item['line_subtotal'] / $item['quantity']; ?>
                                            <?php $post_thumbnail = get_the_post_thumbnail_url($item['product_id']); ?>
                                            <?php $title = get_the_title($item['product_id']); ?>
                                            <?php $permalink = get_the_permalink($item['product_id']); ?>
                                            <?php $ticket_info = get_field('ticket_info', $item['product_id']); ?>
                                            <?php $event_date = $ticket_info['event_date']; ?>
                                            <?php if (!empty($event_date)) : ?>
                                                <?php $event_date_object = DateTime::createFromFormat('d/m/Y', $event_date); ?>
                                            <?php endif; ?>
                                            <?php if (!empty($ticket_info['venue'])) : ?>
                                                <?php $venue_info = get_field('venue_info', $ticket_info['venue']); ?>
                                                <?php $venue_name = get_the_title($ticket_info['venue']); ?>
                                            <?php endif; ?>
                                            <?php $product = wc_get_product($item['product_id']); ?>
                                            <?php $is_variable = $product->is_type('variable'); ?>
                                            <?php for ($i = 1; $i <= $item['quantity']; $i++) : ?>
                                                <li class="checkout__tickets-item cart__item checkout__item"
                                                    data-item_quanity="<?php echo $item['quantity']; ?>"
                                                    data-item_key="<?php echo $key; ?>">
                                                    <div class="checkout__tickets-top">
                                                        <?php if (!empty($post_thumbnail)) : ?>
                                                            <div class="cart__img img">
                                                                <img src="<?php echo $post_thumbnail; ?>"/>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="cart__item-info">
                                                            <div class="cart__price"><span>£<?php echo $price; ?></span>
                                                            </div>
                                                            <div class="cart__name"><a
                                                                        href="<?php echo $permalink; ?>"><?php echo $title; ?></a>
                                                            </div>
                                                            <?php if (!empty($event_date_object)) : ?>
                                                                <div class="shows__date shows__info">
                                                                    <h4><?php echo $event_date_object->format('l, d F, Y'); ?>
                                                                        <?php if (!empty($ticket_info['event_time'])) : ?>
                                                                            <?php echo $ticket_info['event_time']; ?>
                                                                        <?php endif; ?>
                                                                    </h4>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if (!empty($venue_info['location'])) : ?>
                                                                <div class="shows__location shows__info">
                                                                    <a class="shows__map"
                                                                       data-lat='<?php echo $venue_info['location']['lat']; ?>'
                                                                       data-lng='<?php echo $venue_info['location']['lng']; ?>'>
                                                                        <?php echo $venue_name; ?>
                                                                    </a>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>

                                                    <?php if ($is_variable) : ?>
                                                        <?php $input = $item['product_id'] . '_' . $item['variation_id'] . '_' . $i; ?>
                                                    <?php else : ?>
                                                        <?php $input = $item['product_id'] . '_' . $i; ?>
                                                    <?php endif; ?>

                                                    <div class="checkout__tickets-input">
                                                        <div class="modal__form-content">
                                                            <input name="name_<?php echo $input; ?>" id="firstname_<?php echo $input; ?>"
                                                                   minlength="3" required type="text"/>
                                                            <label class="label" for="firstname">First name</label>
                                                        </div>
                                                        <div class="modal__form-content">
                                                            <input name="lastname_<?php echo $input; ?>" id="lastname_<?php echo $input; ?>"
                                                                   minlength="3" required
                                                                   type="text"/>
                                                            <label class="label" for="lastname">Last name</label>
                                                        </div>
                                                        <div class="modal__form-content">
                                                            <input name="email_<?php echo $input; ?>" type="email"
                                                                   required id="emailReg_<?php echo $input; ?>"/>
                                                            <label class="label" for="emailReg">Email</label>
                                                        </div>
                                                    </div>
                                                    <div class="product-remove">
                                                        <span></span>

                                                    </div>

                                                </li>
                                            <?php endfor; ?>
                                        <?php endforeach; ?>
                                        <input type="hidden" name="total_tickets_quantity" value="<?php echo $total_tickets_quantity; ?>">
                                    </ul>
                                    <button class="checkout__tickets-button section__button" type="button"> next
                                    </button>
                                </div>
                            </div>

                            <div class="checkout__info-tickets">


                                <?php if ($checkout->get_checkout_fields()) : ?>

                                    <?php do_action('woocommerce_checkout_before_customer_details'); ?>


                                    <?php if (!is_user_logged_in()): ?>
                                        <div class="form-group form-create_account">
                                            <input type="checkbox" id="create_account" name="create_account" checked>
                                            <label for="create_account">Create an account?</label>
                                        </div>

                                        <div class="checkout__password">
                                            <p class="form-row  validate-required woocommerce-validated"
                                               data-priority="1"><span
                                                        class="woocommerce-input-wrapper modal__form-content"><input
                                                            type="password"  class="input-text " name="password"
                                                            id="billing_password" placeholder="" required autocomplete="false" min="8"
                                                            data-placeholder=""><label for="billing_password"
                                                                                       class="label">Password</label></span>
                                            </p>
                                        </div>
                                    <?php endif; ?>


                                    <?php do_action('woocommerce_checkout_after_customer_details'); ?>

                                <?php endif; ?>

                                <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>



                                <?php do_action('woocommerce_checkout_before_order_review'); ?>

                                <div id="order_review" class="woocommerce-checkout-review-order">
                                    <h3>Order summary</h3>
                                    <?php do_action('woocommerce_checkout_order_review'); ?>
                                </div>

                                <?php do_action('woocommerce_checkout_after_order_review'); ?>
                            </div>

                        </div>
                    <?php endif; ?>
                    <?php if (!empty($clothes)) : ?>
                        <div class="tab__item">
                            <div class='checkout__clothes'>
                                <ul class="checkout__clothes-list">
                                    <?php foreach ($clothes as $key => $item) : ?>
                                        <?php $permalink = get_the_permalink($item['product_id']); ?>
                                        <?php $title = get_the_title($item['product_id']); ?>
                                        <?php $post_thumbnail = get_the_post_thumbnail_url($item['product_id']); ?>
                                        <?php $price = $item['line_subtotal'] / $item['quantity']; ?>
                                        <?php for ($i = 1; $i <= $item['quantity']; $i++) : ?>
                                            <li class="checkout__tickets-top checkout__item"
                                                data-item_quanity="<?php echo $item['quantity']; ?>"
                                                data-item_key="<?php echo $key; ?>">
                                                <?php if (!empty($post_thumbnail)) : ?>
                                                    <div class="cart__img img"><img
                                                                src="<?php echo $post_thumbnail; ?>"/></div>
                                                <?php endif; ?>
                                                <div class="cart__item-info">
                                                    <div class="cart__price"><span>£<?php echo $price; ?></span></div>
                                                    <div class="cart__name"><a
                                                                href="<?php echo $permalink; ?>"><?php echo $title; ?></a>
                                                    </div>
                                                    <?php if (!empty($item['variation']['attribute_size'])) : ?>
                                                        <div class="cart__size"><?php echo $item['variation']['attribute_size']; ?></div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="product-remove">
                                                    <span></span>

                                                </div>
                                            </li>
                                        <?php endfor; ?>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="checkout__info-wrap">


                                    <?php if ($checkout->get_checkout_fields()) : ?>

                                        <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                                        <div class="col2-set" id="customer_details">
                                            <div class="custom__form">
                                                <?php do_action('woocommerce_checkout_billing'); ?>
                                            </div>


                                            <div class="col-2">
                                                <?php do_action('woocommerce_checkout_shipping'); ?>
                                            </div>
                                        </div>
                                        <?php if (!is_user_logged_in()) : ?>
                                            <div class="form-group">
                                                <input type="checkbox" id="create_account" name="create_account" checked>
                                                <label for="create_account">Create an account?</label>
                                            </div>
                                            <div class="checkout__password">
                                                <p class="form-row  validate-required woocommerce-validated"
                                                   data-priority="1"><span
                                                            class="woocommerce-input-wrapper modal__form-content"><input
                                                                type="password" class="input-text " name="password"
                                                                id="billing_password" min="8" placeholder="" required autocomplete="false"
                                                                data-placeholder=""><label for="billing_password"
                                                                                           class="label">Password</label></span>
                                                </p>
                                            </div>
                                        <?php endif; ?>

                                        <?php do_action('woocommerce_checkout_after_customer_details'); ?>

                                    <?php endif; ?>

                                    <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>



                                    <?php do_action('woocommerce_checkout_before_order_review'); ?>

                                    <div id="order_review" class="woocommerce-checkout-review-order">
                                        <h3>Order summary</h3>
                                        <?php do_action('woocommerce_checkout_order_review'); ?>
                                    </div>

                                    <?php do_action('woocommerce_checkout_after_order_review'); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>
</section>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
