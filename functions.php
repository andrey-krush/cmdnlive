<?php

define( 'TEMPLATE_PATH', get_template_directory_uri() );
define( 'TEMPLATE_DIR', __DIR__ . '/' );

add_theme_support( 'title-tag' );
add_theme_support('post-thumbnails');
add_theme_support('woocommerce');

require_once __DIR__ .'/includes/theme/autoloader.php';

Sheepfish_Theme_AutoLoader::init_auto();

add_filter("woocommerce_checkout_fields", "custom_override_checkout_fields", 1);
function custom_override_checkout_fields($fields) {

    $fields['billing']['billing_first_name']['priority'] = 1;
    $fields['billing']['billing_last_name']['priority'] = 2;
    $fields['billing']['billing_phone']['priority'] = 3;
    $fields['billing']['billing_email']['priority'] = 4;
    $fields['billing']['billing_country']['priority'] = 5;
    $fields['billing']['billing_city']['priority'] = 6;
    $fields['billing']['billing_address_1']['priority'] = 7;
    $fields['billing']['billing_postcode']['priority'] = 8;

    $fields['billing']['billing_phone']['class'][0] = 'form-row-first';
    $fields['billing']['billing_email']['class'][0] = 'form-row-last';

    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_state']);
    unset($fields['shipping']);

    $unset = true;
    $cart = WC()->cart->get_cart();

    foreach ( $cart as $key => $item ) :

        if (has_term('clothes', 'product_cat', $item['product_id'])) :
            $unset = false;
            break;
        endif;

    endforeach;

    if( $unset ) :

        foreach ( $fields['billing'] as $key => $item ) :

            $fields['billing'][$key]['required'] = false;

        endforeach;

    endif;
//    var_dump($fields);die;
    return $fields;
}

add_filter('woocommerce_default_address_fields', 'custom_woocommerce_default_address_fields', 20);

function custom_woocommerce_default_address_fields( $fields ) {
    
    $fields['postcode']['class'][0] = 'form-row-last';
    $fields['city']['class'][0] = 'form-row-last';
    $fields['address_1']['class'][0] = 'form-row-first';
    $fields['country']['class'][0] = 'form-row-first';

    $fields['city']['priority'] = 6;
    $fields['address_1']['priority'] = 7;
    $fields['postcode']['priority'] = 8;


    return $fields;
}

add_action('woocommerce_cart_contents', 'display_product_ids_in_cart');
function display_product_ids_in_cart() {
    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
        $product_id = $cart_item['product_id'];
        echo '<p class="product-id">' . $product_id . '</p>';
    }
}

function enqueue_swiper() {
    wp_enqueue_style('swiper-css', 'https://unpkg.com/swiper/swiper-bundle.min.css');
    wp_enqueue_script('swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js', array('jquery'), '', true);
}
add_action('wp_enqueue_scripts', 'enqueue_swiper');

function get_stock_status_text( $product, $name, $term_slug ){
    foreach ( $product->get_available_variations() as $variation ){
        if($variation['attributes'][$name] == $term_slug )
            $stock = $variation['is_in_stock'];
    }

    return $stock == 1 ? ' - (In Stock)' : ' - (Out of Stock)';
}

// The hooked function that will add the stock status to the dropdown options elements.
add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'show_stock_status_in_dropdown', 10, 2);
function show_stock_status_in_dropdown( $html, $args ) {
    // Only if there is a unique variation attribute (one dropdown)
    if( sizeof($args['product']->get_variation_attributes()) == 1 ) :

        $options               = $args['options'];
        $product               = $args['product'];
        $attribute             = $args['attribute']; // The product attribute taxonomy
        $name                  = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
        $id                    = $args['id'] ? $args['id'] : sanitize_title( $attribute );
        $class                 = $args['class'];
        $show_option_none      = $args['show_option_none'] ? true : false;
        $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __( 'Choose an option', 'woocommerce' );

        if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
            $attributes = $product->get_variation_attributes();
            $options    = $attributes[ $attribute ];
        }
        if( has_term( 'tickets', 'product_cat', $product->get_id() ) ) :
            if( isset( $_SESSION['select_counter'] ) ) :
                $select_counter_on_page = $_SESSION['select_counter'];
            else :
                $select_counter_on_page = 0;
            endif;

            if( $select_counter_on_page == 0 ) :
                $id .= '-scroll';
            endif;
        endif;

        $html = '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '" data-show_option_none="' . ( $show_option_none ? 'yes' : 'no' ) . '">';
        $html .= '<option value=""></option>';
//         $html .= '<option value="">' . esc_html( $show_option_none_text ) . '</option>';


        if ( ! empty( $options ) ) {
            if ( $product && taxonomy_exists( $attribute ) ) {
                $terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );

                foreach ( $terms as $term ) {
                    if ( in_array( $term->slug, $options ) ) {
                        // HERE Added the function to get the text status
                        $stock_status = get_stock_status_text( $product, $name, $term->slug );

                        // HERE we add a custom class to <option> that are "Out of stock"
                        $option_class = $stock_status === ' - (Out of Stock)' ? ' class="outofstock"' : '';

                        $html .= '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . $option_class . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $term->name )  ) . '</option>';
                    }
                }
            } else {
                foreach ( $options as $option ) {
                    $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
                    // HERE Added the function to get the text status
                    $stock_status = get_stock_status_text( $product, $name, $option );

                    // HERE we add a custom class to <option> that are "Out of stock"
                    $option_class = $stock_status === ' - (Out of Stock)' ? ' class="outofstock"' : '';

                    $html .= '<option value="' . esc_attr( $option ) . '" ' . $selected . $option_class . '>' .
                        esc_html( apply_filters( 'woocommerce_variation_option_name', $option )  ) . '</option>';
                }
            }
        }
        $html .= '</select>';

        if( has_term( 'tickets', 'product_cat', $product->get_id() ) ) :
            if( $select_counter_on_page == 1 ) :
                unset($_SESSION['select_counter']);
            elseif( $select_counter_on_page == 0 ) :
                $_SESSION['select_counter'] = 1;
            endif;
        endif;
    endif;

    return $html;
}

function my_acf_init() {

    acf_update_setting('google_api_key', 'AIzaSyDWfYtTqhLP1aaIr5GN00I3xA62l0kJnzc');
}

add_action('acf/init', 'my_acf_init');

add_action('template_redirect', 'redirects');

function redirects() {

    if( is_account_page() and !is_user_logged_in() ) :

        wp_redirect( home_url() . '#authorization' );

    elseif( is_page_template( 'page-change-password.php' ) and !is_user_logged_in()  ) :

        wp_redirect(home_url());

    endif;
}

add_action('woocommerce_checkout_order_processed', 'add_tickets_meta_to_order');
function add_tickets_meta_to_order( $order_id ) {

    global $woocommerce;
    $order = wc_get_order($order_id);

    if( isset($_POST['create_account']) and ($_POST['create_account'] == 'on') ) :

        $name = $_POST['billing_first_name'];
        $last_name = $_POST['billing_last_name'];
        $email = $_POST['billing_email'];
        $password = $_POST['password'];

        if ( !empty( get_user_by_email( $email ) ) ) {
            asdasd();
        }

        $userdata = [

            'user_pass'  => $password,
            'user_login' => $email,
            'user_email' => $email,
            'first_name' => $name,
            'last_name'  => $last_name,

        ];

        $user_id = wp_insert_user( $userdata );

        update_user_meta( $user_id, 'billing_email', $email );
        update_user_meta( $user_id, 'billing_first_name', $name );
        update_user_meta( $user_id, 'billing_last_name', $last_name );
        update_user_meta( $user_id, 'billing_phone', $_POST['billing_phone'] );
        update_user_meta( $user_id, 'billing_country', $_POST['billing_country'] );
        update_user_meta( $user_id, 'billing_city', $_POST['billing_city'] );
        update_user_meta( $user_id, 'billing_address_1', $_POST['billing_address_1'] );
        update_user_meta( $user_id, 'billing_postcode', $_POST['billing_postcode'] );

        $headers = 'content-type: text/html';
        $message = 'You successfully registered on ' . home_url();
        wp_mail( $email, 'Successful registration', $message, $headers );

        $order->set_customer_id($user_id);
        update_post_meta( $order_id, '_customer_user', $user_id );

        $login_data['user_login'] = $email;
        $login_data['user_password'] = $password;

        if( $_POST['remember'] ) :
            $login_data['remember'] = true;
        else :
            $login_data['remember'] = false;
        endif;

        $login_data = wp_signon( $login_data, true );
    endif;

    $tickets_order_meta = [];

    $coupons = WC()->cart->get_applied_coupons();

    if( !empty( $coupons ) ) :

        $coupon_code = $coupons[0];
        $coupon_object = new WC_Coupon($coupon_code);

        $coupon_type = $coupon_object->get_discount_type();
        $amount_of_discount = $coupon_object->get_amount();

        if( $coupon_type == 'fixed_cart' ) :

            $discount_for_item = $amount_of_discount / WC()->cart->get_cart_contents_count();

        else :

            $discount_for_item = $amount_of_discount;

        endif;

    endif;


    if( empty( get_option('tickets_sold') ) ) :
        update_option('tickets_sold', 1);
    endif;

    foreach( WC()->cart->get_cart() as $key => $item ) :

        if( has_term( 'tickets', 'product_cat', $item['product_id'] ) ) :

            $variation_type = '';

            $ticket_total = $item['line_subtotal'] / $item['quantity'];
            $ticket_discount = 0;

            if( isset( $discount_for_item ) ) :

                if( $coupon_type == 'fixed_cart' ) :
                    $ticket_discount = $discount_for_item;
                else :
                    $ticket_discount = $ticket_total / 100 * $discount_for_item;
                endif;

            endif;


            $product = wc_get_product($item['product_id']);
            $is_variable = $product->is_type('variable');

            if( $is_variable ) :
                $item_id = $item['product_id'] . '_' . $item['variation_id'];
                $variation_type = $item['variation'][array_keys($item['variation'])[0]];
            else :
                $item_id = $item['product_id'];
            endif;

            add_post_meta($order_id, 'tickets', $item['product_id']);

            for( $i = 1; $i <= $_POST['total_tickets_quantity']; $i++ ) :

                if( isset( $_POST['name_' . $item_id . '_' . $i] ) ) :

                    $tickets_sold = get_option('tickets_sold');

                    $ticket_array = [
                        'name' => $_POST['name_' . $item_id . '_' . $i],
                        'lastname' => $_POST['lastname_' . $item_id . '_' . $i],
                        'email' => $_POST['email_' . $item_id . '_' . $i],
                        'ticket_price' => $ticket_total - $ticket_discount,
                        'ticket_discount' => $ticket_discount,
                        'ticket_total_price' => $ticket_total,
                        'ticket_variation' => $variation_type,
                        'ticket_sold_id' => $tickets_sold
                    ];

                    $tickets_order_meta[$item_id][] = $ticket_array;
                    update_option( 'tickets_sold', $tickets_sold + 1);

                endif;

            endfor;

        endif;

    endforeach;

    if( !empty( $tickets_order_meta ) ) :

        update_post_meta($order_id, 'tickets_order_meta', $tickets_order_meta);

    endif;

}

require_once get_template_directory() . '/vendor/autoload.php';


function rewrite_wc_cart_totals_coupon_html( $coupon ) {
    if ( is_string( $coupon ) ) {
        $coupon = new WC_Coupon( $coupon );
    }

    $discount_amount_html = '';

    $amount               = WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax );
    $discount_amount_html = '-' . wc_price( $amount );

    if ( $coupon->get_free_shipping() && empty( $amount ) ) {
        $discount_amount_html = __( 'Free shipping coupon', 'woocommerce' );
    }

    $discount_amount_html = apply_filters( 'woocommerce_coupon_discount_amount_html', $discount_amount_html, $coupon );
    $coupon_html          = $discount_amount_html . ' <a data-coupon="' . esc_attr( $coupon->get_code() ) . '"></a>';

    echo wp_kses( apply_filters( 'woocommerce_cart_totals_coupon_html', $coupon_html, $coupon, $discount_amount_html ), array_replace_recursive( wp_kses_allowed_html( 'post' ), array( 'a' => array( 'data-coupon' => true ) ) ) ); // phpcs:ignore PHPCompatibility.PHP.NewFunctions.array_replace_recursiveFound
}

add_action( 'woocommerce_thankyou', 'adding_customers_details_to_thankyou', 10, 1 );
function adding_customers_details_to_thankyou( $order_id ) {

    if ( ! $order_id ) return;

    if( is_user_logged_in() ) return;

    $order = wc_get_order($order_id); // Get an instance of the WC_Order object

    wc_get_template( 'order/order-details-customer.php', array('order' => $order ));
}

function add_custom_button_near_new_order() : void {
    global $pagenow;

    if( !isset( $_SESSION['button_counter'] ) ) :
        $_SESSION['button_counter'] = 1;
    else :
        $_SESSION['button_counter'] += 1;
    endif;

    // Check if we are on the WooCommerce orders page
    if ($pagenow === 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] === 'show') {
        ?>
        <div class="alignleft">
            <a id="download-button-<?php echo $_SESSION['button_counter']; ?>"  class="button button-primary">Download CSV</a>
        </div>
        <script>

            document.getElementById("download-button-<?php echo $_SESSION['button_counter']; ?>").onclick = function(){

                jQuery( document ).ready( function( $ ) {

                    let posts_array = [];

                    $('#the-list tr').each(function () {

                        if( $(this).find('input').prop('checked') ) {
                            posts_array.push( $(this).find('input').val() );
                        }

                    });

                    sendAjaxGetCsv(posts_array);

                });

            };

            function sendAjaxGetCsv( posts_array )
            {
                jQuery.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: "POST",
                    data: {
                        shows : posts_array,
                        action : 'getOrdersCsv'
                    },
                    success: function (data) {
                        console.log(data.data.file_url);
                        window.location = data.data.file_url;

                    },
                    error: function (error) {
                        console.log(error);
                    },
                });

            }
        </script>
        <?php
    }
}

add_action('manage_posts_extra_tablenav', 'add_custom_button_near_new_order');

add_action('wp_ajax_getOrdersCsv', 'getOrdersCsv');

function getOrdersCsv() : void {

    if( !empty( $_POST['shows'] ) ) :

        $args = [
            'numberposts' => -1,
            'post_type'   => wc_get_order_types('view-orders'),
            'post_status' => array_keys(wc_get_order_statuses('Completed', 'Order status', 'woocommerce'))
        ];

        $show_name = get_the_title($_POST['shows'][0]);

        foreach ( $_POST['shows'] as $item ) :

            $ticket = get_field('show_info', $item)['related_ticket'];

            $venue = get_field('ticket_info', $ticket)['venue'];
            $venue_name = get_the_title($venue);

            $event_date = get_field('ticket_info', $ticket)['event_date'];

            $args['meta_query'][] = [
                'key' => 'tickets',
                'value' => (string) $ticket,
                'compare' => 'LIKE'
            ];

        endforeach;

        $orders = get_posts($args);


        if( !empty( $orders ) and !empty( $ticket ) ) :

            unlink(wp_get_upload_dir()['basedir'] . '/export_orders.csv');

            $csv_output = "Order ID, Status of order, Ticket id, Show name, Customer Name, Customer email, Ticket price, Ticket discount, Ticket Total price, Ticket type, Venue name, Event date\n";

            $counter = 0;

            foreach ( $orders as $item ) :

                $order = wc_get_order($item->ID);

                $order_id = $item->ID;
                $order_status = $order->get_status();

                $tickets_metadata = get_post_meta($order_id, 'tickets_order_meta', true);

                foreach ( $tickets_metadata as $key => $subitem ) :

                    if( str_contains( $key, '_' ) ) :
                        $key = explode('_', $key)[0];
                    endif;

                    if( $key == $ticket ) :

                        foreach( $subitem as $subsubitem ) :

                            $user_name = $subsubitem['name'] . ' ' . $subsubitem['lastname'];
                            $user_email = $subsubitem['email'];
                            $ticket_price = $subsubitem['ticket_price'];
                            $total_price = $subsubitem['ticket_total_price'];
                            $ticket_discount = $subsubitem['ticket_discount'];
                            $ticket_variation = $subsubitem['ticket_variation'];

                            $ticket_id = $subsubitem['ticket_sold_id'];

                            $csv_output .= " $order_id, $order_status, $ticket_id, $show_name, $user_name, $user_email, $ticket_price, $ticket_discount, $total_price, $ticket_variation, $venue_name, $event_date\n";

                        endforeach;

                    endif;

                endforeach;

            endforeach;

        endif;

    else :
        wp_send_json_error();
    endif;

    if( !empty( $csv_output  ) ) :

        file_put_contents( wp_get_upload_dir()['basedir'] . '/export_orders.csv', $csv_output);
        wp_send_json_success([ 'file_url' => wp_get_upload_dir()['baseurl'] . '/export_orders.csv' ]);

    endif;

    die;
}

add_filter('acf/fields/relationship/query', 'notShowArchivePosts', 10, 1);

function notShowArchivePosts ( $args ) {

    if( $args['post_type'] == ['post'] ) :

        $args['meta_query'] = [
            'relation' => 'OR',
            [
                'key' => 'archived_post',
                'value' => 1,
                'compare' => '!='
            ],
            [
                'key' => 'archived_post',
                'compare' => 'NOT EXISTS'
            ]
        ];

    endif;

    return $args;

}
