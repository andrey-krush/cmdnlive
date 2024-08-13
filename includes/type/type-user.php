<?php

class Type_User {

    public static function init_auto() {

        add_action('wp_ajax_registration', [__CLASS__, 'ajaxRegistrationHandler']);
        add_action('wp_ajax_nopriv_registration', [__CLASS__, 'ajaxRegistrationHandler']);

        add_action('wp_ajax_login', [__CLASS__, 'ajaxLoginHandler']);
        add_action('wp_ajax_nopriv_login', [__CLASS__, 'ajaxLoginHandler']);

        add_action('wp_ajax_send_mail_reset_password', [__CLASS__, 'ajaxSendMailResetPassword']);
        add_action('wp_ajax_nopriv_send_mail_reset_password', [__CLASS__, 'ajaxSendMailResetPassword']);

        add_action('wp_ajax_reset_password', [__CLASS__, 'ajaxResetPassword']);
        add_action('wp_ajax_nopriv_reset_password', [__CLASS__, 'ajaxResetPassword']);

        add_action('wp_ajax_change_profile', [__CLASS__, 'updateUserInfo']);
        add_action('wp_ajax_nopriv_change_profile', [__CLASS__, 'updateUserInfo']);

        add_action('wp_ajax_logout', [__CLASS__, 'logout']);
        add_action('wp_ajax_nopriv_logout', [__CLASS__, 'logout']);

        add_action('init', [__CLASS__, 'acf_add_local_field_group']);

    }

    public static function ajaxRegistrationHandler() {

        $email = $_POST['email'];
        $password = $_POST['password'];
        $first_name = $_POST['name'];
        $last_name = $_POST['lastname'];

        if ( !empty( get_user_by_email( $email ) ) ) {
            wp_send_json_error( 'user_mail_exists', 400 );
        }

        $userdata = array(

            'user_pass'  => $password,
            'user_login' => $email,
            'user_email' => $email,
            'first_name' => $first_name,
            'last_name'  => $last_name,

        );

        $user_id = wp_insert_user( $userdata );

        update_user_meta( $user_id, 'billing_email', $email );
        update_user_meta( $user_id, 'billing_first_name', $first_name );
        update_user_meta( $user_id, 'billing_last_name', $last_name );

        $headers = 'content-type: text/html';
        $message = 'You successfully registered on ' . home_url();
        wp_mail( $email, 'Successful registration', $message, $headers );

        wp_send_json_success();

    }

    public static function ajaxLoginHandler() {

        $email = $_POST['email'];
        $password = $_POST['password'];

        if ( !get_user_by_email( $email ) ) {
            wp_send_json_error( 'user_not_exists', 400 );
        }

        $login_data['user_login'] = $email;
        $login_data['user_password'] = $password;

        if( $_POST['remember'] ) :
            $login_data['remember'] = true;
        else :
            $login_data['remember'] = false;
        endif;

        $login_user = wp_signon( $login_data, true );
        if ( !empty( $login_user->errors ) ) {
            wp_send_json_error( 'incorrect_password', 400 );
        }

        wp_send_json_success( [ 'redirect_url' =>  wc_get_page_permalink( 'myaccount' ) ], 200 );
        die;

    }

    public static function ajaxSendMailResetPassword() {

        $email = $_POST['email'];

        $user = get_user_by( 'email', $email );

        if ( !empty( $user->ID ) ) {

            $resetLink = home_url().'?confirm='.base64_encode($email).'#reset_password';
            $subject = 'Reset password';
            $message = 'To reset your password follow the link - ' . $resetLink;

            $headers = array('Content-Type: text/html; charset=UTF-8');
            wp_mail($user->user_email, $subject, $message, $headers);
        } else {
            wp_send_json_error( 'user_not_exists', 400 );
        }

    }

    public static function ajaxResetPassword() {

        if( !empty( $_POST['email'] ) ) :

            $email = base64_decode($_POST['email']);
            $pass_new = $_POST['password'];
            $pass_new_repeat = $_POST['password_confirm'];

            $user = get_user_by( 'email', $email );

            if ( $pass_new != $pass_new_repeat ) {
                wp_send_json_error( 'passwords_not_match', 400 );
            }

            if ( empty( $user->ID ) ) {
                wp_send_json_error( 'user_not_exists', 400 );
            }

            wp_set_password( $pass_new, $user->ID );

            wp_send_json_success();

        endif;

        die;
    }

    public static function updateUserInfo() {

        global $wpdb;

        $user_id = $_POST['user_id'];

        update_user_meta( $user_id, 'first_name', $_POST['name'] );
        update_user_meta( $user_id, 'last_name', $_POST['lastname'] );
        update_user_meta( $user_id, 'billing_first_name', $_POST['name'] );
        update_user_meta( $user_id, 'billing_last_name', $_POST['lastname'] );
        update_user_meta( $user_id, 'billing_phone', $_POST['phoneAccount'] );

        $user_by_email = get_user_by('email', $_POST['email']);

        if( !empty( $user_by_email ) and ( $user_id != $user_by_email->ID )  ) :

            wp_send_json_error( 'user_mail_exists', 400 );

        endif;

        wp_update_user([
            'ID' => $user_id,
            'user_email' => $_POST['email'],
        ]);

        update_user_meta( $user_id, 'billing_email', $_POST['email'] );

        update_field('birthdate', $_POST['birthdate'], 'user_' . $user_id);

        $wpdb->update(
            $wpdb->users,
            array('user_login' => $_POST['email']),
            array('ID' => $user_id)
        );

        wp_send_json_success();
        die;
    }

    public static function logout() {

        wp_logout();

        wp_redirect( home_url() );
        die;

    }

    public static function acf_add_local_field_group () {

        if ( ! function_exists( 'acf_add_local_field_group' ) ) {
            return;
        }

        acf_add_local_field_group( array(
            'key' => 'group_64e36369d4243',
            'title' => 'Author',
            'fields' => array(
                array(
                    'key' => 'field_64e3636bb4c94',
                    'label' => 'Author photo',
                    'name' => 'author_photo',
                    'aria-label' => '',
                    'type' => 'image',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'return_format' => 'url',
                    'library' => 'all',
                    'min_width' => '',
                    'min_height' => '',
                    'min_size' => '',
                    'max_width' => '',
                    'max_height' => '',
                    'max_size' => '',
                    'mime_types' => '',
                    'preview_size' => 'medium',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'user_form',
                        'operator' => '==',
                        'value' => 'edit',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ) );

        acf_add_local_field_group( array(
            'key' => 'group_64f733349ad84',
            'title' => 'User',
            'fields' => array(
                array(
                    'key' => 'field_64f73335e909a',
                    'label' => 'Birthdate',
                    'name' => 'birthdate',
                    'aria-label' => '',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'maxlength' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'user_form',
                        'operator' => '==',
                        'value' => 'edit',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ) );

    }

}