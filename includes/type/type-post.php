<?php

class Type_Post {

    public static function init_auto() {

        add_action('save_post', [__CLASS__, 'send_clients_notification']);
        add_action('init', [__CLASS__, 'acf_add_local_field_group']);
        add_action('init', [__CLASS__, 'add_cron_to_archive_posts']);
        add_action('archive_posts', [__CLASS__, 'archive_posts_daily']);
        add_action('save_post', [__CLASS__, 'archivePost']);

    }

    public static function send_clients_notification( $post_id ) {

        $post = get_post( $post_id );

        if( $post->post_type != 'post' ) :
            return;
        endif;

        if( $post->post_status != 'publish' ) :
            return;
        endif;

        if( get_post_meta( $post_id, 'notification_already_sent', true ) == 1 ) :
            return;
        endif;

        $clients = get_posts([
            'post_type'   => 'client',
            'numberposts' => -1,
            'post_status' => 'publish'
        ]);

        $subject = 'New post on CMDN';

        $headers = [
            'From: CMDN <wordpress@cmdn.com>',
            'content-type: text/html'
        ];

        foreach ( $clients as $item ) :

            $message = 'Hi,</br> There a new post "' . $post->post_title . '" on ' . get_the_permalink($post_id);

            wp_mail( $item->post_title, $subject, $message, $headers );

        endforeach;

        update_post_meta( $post_id, 'notification_already_sent', 1);
    }

    public static function add_cron_to_archive_posts()
    {

        if (!wp_next_scheduled('archive_posts')) {
            wp_schedule_event(time(), 'daily', 'archive_posts');
        }

    }

    public static function archivePost( $post_id ) {

        $post = get_post($post_id);

        if( $post->post_type != 'post' )  :
            return;
        endif;

        $is_archive = get_post_meta($post_id, 'archived_post', true);

        if( empty($is_archive) or ($is_archive == 0) ) :

            $expire_date = get_field('post_info', $post_id)['expire_date'] ?? '';

            if( !empty($expire_date) and ( intval($expire_date) <= intval( date( 'Ymd' ) ) ) ) :

                update_post_meta($post_id, 'archived_post', 1);

            endif;

        elseif ( $is_archive == 1 ) :

            $expire_date = get_field('post_info', $post_id)['expire_date'] ?? '';

            if( !empty($expire_date) and ( intval($expire_date) >= intval( date( 'Ymd' ) ) ) ) :

                update_post_meta($post_id, 'archived_post', 0);

            endif;

        endif;

    }

    public static function archive_posts_daily() {

        $posts = get_posts([
            'post_type' => 'post',
            'post_status' => 'publish',
            'numberposts' => -1,
            'meta_query' => [
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
            ]
        ]);

        $date = date('Ymd');

        foreach ( $posts as $item ) :

            $post_expire_date = get_field('post_info', $item->ID)['expire_date'];

            if( !empty($post_expire_date) and ( intval($post_expire_date) <= intval($date) ) ) :

                update_post_meta($item->ID, 'archived_post', 1);

            endif;

        endforeach;

    }

    public static function acf_add_local_field_group() {

        if ( ! function_exists( 'acf_add_local_field_group' ) ) {
            return;
        }

        acf_add_local_field_group( array(
            'key' => 'group_64df20de69509',
            'title' => 'Post',
            'fields' => array(
                array(
                    'key' => 'field_64df20dfc9ca6',
                    'label' => 'Post info',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_64df20f1c9ca7',
                    'label' => '',
                    'name' => 'post_info',
                    'aria-label' => '',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_64df2118c9ca8',
                            'label' => 'Editor pick',
                            'name' => 'editor_pick',
                            'aria-label' => '',
                            'type' => 'true_false',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'message' => '',
                            'default_value' => 0,
                            'ui_on_text' => '',
                            'ui_off_text' => '',
                            'ui' => 1,
                        ),
                        array(
                            'key' => 'field_65c4f3b990b45',
                            'label' => 'Expire date',
                            'name' => 'expire_date',
                            'aria-label' => '',
                            'type' => 'date_picker',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'display_format' => 'm/d/Y',
                            'return_format' => 'Ymd',
                            'first_day' => 1,
                        ),
                        array(
                            'key' => 'field_64e36e69f408a',
                            'label' => 'Recommended posts',
                            'name' => 'recommended_posts',
                            'aria-label' => '',
                            'type' => 'relationship',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'post_type' => array(
                                0 => 'post',
                            ),
                            'post_status' => array(
                                0 => 'publish',
                            ),
                            'taxonomy' => '',
                            'filters' => array(
                                0 => 'search',
                            ),
                            'return_format' => 'id',
                            'min' => '',
                            'max' => '',
                            'elements' => '',
                            'bidirectional' => 0,
                            'bidirectional_target' => array(
                            ),
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'post',
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