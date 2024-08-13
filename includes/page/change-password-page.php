<?php

class Change_Password_Page {

    public static function init_auto() {

        add_action( 'wp_ajax_change_password', [__CLASS__, 'change_password'] );

    }
    public static function get_url() {

        $page = get_pages( [
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-change-password.php',
        ]);

        return ( $page && 'publish' === $page[ 0 ]->post_status ) ? get_the_permalink( $page[ 0 ]->ID ) : false;
    }

    public static function get_ID() {

        $page = get_pages( [
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-change-password.php',
        ]);

        return ( $page && 'publish' === $page[ 0 ]->post_status ) ? $page[ 0 ]->ID : false;
    }

    public static function change_password() {

        $old_pass = $_POST['password'];
        $new_pass = $_POST['passwordNew'];

        $user = wp_get_current_user();

        if( wp_check_password( $old_pass, $user->data->user_pass ) ) :
            wp_set_password( $new_pass, $user->ID );
        else :
            wp_send_json_error('old_pass_incorrect', 400);
        endif;


        die;

    }
}