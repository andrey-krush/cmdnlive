<?php

class Cart_Page {

    public static function init_auto() {

        add_action('wp_ajax_nopriv_delete_item_from_cart', [ __CLASS__, 'delete_item_from_cart' ]);
        add_action('wp_ajax_delete_item_from_cart', [ __CLASS__, 'delete_item_from_cart' ]);

        add_action('wp_ajax_nopriv_change_item_quantity', [ __CLASS__, 'change_item_quantity' ]);
        add_action('wp_ajax_change_item_quantity', [ __CLASS__, 'change_item_quantity' ]);

        add_action('wp_ajax_nopriv_apply_coupon', [ __CLASS__, 'apply_coupon' ]);
        add_action('wp_ajax_apply_coupon', [ __CLASS__, 'apply_coupon' ]);

        add_action('wp_ajax_nopriv_remove_coupon', [ __CLASS__, 'remove_coupon' ]);
        add_action('wp_ajax_remove_coupon', [ __CLASS__, 'remove_coupon' ]);

    }

    public static function delete_item_from_cart() {

        if( !empty( $_POST['cart_item'] ) ) :

            WC()->cart->remove_cart_item( $_POST['cart_item'] );

        endif;

        $items_count = WC()->cart->get_cart_contents_count();

        if( $items_count > 0 ) :

            wp_send_json_success( array( 'items_count' => $items_count, 'total_price' => WC()->cart->cart_contents_total ) );

        else :

            wp_send_json_success( array('message' => 'cart_empty') );

        endif;

    }

    public static function change_item_quantity() {

        if( !empty( $_POST['quantity'] ) and !empty( $_POST['cart_item'] ) ) :

            if( $_POST['quantity'] == 0 ) :

                WC()->cart->remove_cart_item( $_POST['cart_item'] );

            else :

                WC()->cart->set_quantity( $_POST['cart_item'], $_POST['quantity']);

            endif;

        endif;

        $items_count = WC()->cart->get_cart_contents_count();

        if( $items_count > 0 ) :

            wp_send_json_success( array( 'items_count' => $items_count, 'total_price' => WC()->cart->cart_contents_total ) );

        else :

            wp_send_json_success( array('message' => 'cart_empty') );

        endif;

    }

    public static function apply_coupon() {

        if( empty( $_POST['coupon'] ) ) :
            wp_send_json_error('coupon_empty', 400);
        endif;

        if( empty( WC()->cart->get_applied_coupons() ) ) :

            global $woocommerce;

            $coupon_code = sanitize_text_field( $_POST['coupon'] );

            if ( !WC()->cart->apply_coupon($coupon_code) ) :

                wp_send_json_error('coupon_not_exist', 400);
                die;
            endif;


            $discount_array = WC()->cart->get_coupon_discount_totals();
            $discount_array_keys = array_keys($discount_array);
            $discount = $discount_array[$discount_array_keys[0]];

            wp_send_json_success( array( 'total_price' => WC()->cart->cart_contents_total, 'discount_amount' => $discount ) );
            die;

        else :
            wp_send_json_error('coupon_already_applied');
        endif;

    }

    public static function remove_coupon() {

        WC()->cart->remove_coupons();
        WC()->cart->calculate_totals();
        wp_send_json_success( array( 'total_price' => WC()->cart->cart_contents_total ) );

    }

}