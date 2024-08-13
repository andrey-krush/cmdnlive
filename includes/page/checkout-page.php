<?php

class Checkout_Page {

    public static function init_auto() {

        add_action('wp_ajax_nopriv_change_checkout_quantity', [__CLASS__, 'change_checkout_quantity']);
        add_action('wp_ajax_change_checkout_quantity', [__CLASS__, 'change_checkout_quantity']);

    }

    public static function change_checkout_quantity() {

        if( isset( $_POST['quantity'] ) and !empty( $_POST['key'] ) ) :

            if( $_POST['quantity'] != 0  ) :

                WC()->cart->set_quantity( $_POST['key'], $_POST['quantity'] );

            else :

                WC()->cart->remove_cart_item($_POST['key']);

            endif;

            wp_send_json_success(['total_price' => WC()->cart->cart_contents_total]);

        endif;

        die;

    }
}