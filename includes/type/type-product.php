<?php

class Type_Product {

    public static function init_auto() {
        add_action('init', [__CLASS__, 'acf_add_local_field_group']);
        add_action('wp_ajax_add-to-cart', [__CLASS__, 'add_to_cart']);
        add_action('wp_ajax_nopriv_add-to-cart', [__CLASS__, 'add_to_cart']);

        add_action('save_post', [__CLASS__, 'add_meta_data']);
    }

    public static function add_to_cart() {

        if( !empty( $_POST['id'] ) and !empty( $_POST['quantity'] ) ) :

            if( !empty( $_POST['variable'] ) ) :

                $product = wc_get_product($_POST['id']);

                foreach( $product->get_available_variations() as $item) :

                    foreach( $item['attributes'] as $subitem ) :

                        if( $subitem == $_POST['variable'] ) :

                            $variation_id = $item['variation_id'];
                            break 2;

                        endif;

                    endforeach;

                endforeach;

                WC()->cart->add_to_cart($_POST['id'], $_POST['quantity'], $variation_id);

            else :

                WC()->cart->add_to_cart( $_POST['id'], $_POST['quantity'] );

            endif;

        endif;

        wp_send_json_success();

    }

    public static function add_meta_data( $post_id ) {

        $post = get_post( $post_id );

        if( $post->post_type != 'product' ) :
            return;
        endif;

        $ticket_info = get_field('ticket_info');

        $shows = get_posts([
            'post_type' => 'show',
            'post_status' => 'publish',
            'meta_query' => [
                [
                    'key' => 'show_info_related_ticket',
                    'value' => $post_id
                ]
            ]
        ]);

        if( !empty( $shows ) and !empty( $ticket_info['event_date'] ) ) :

            $event_date = $ticket_info['event_date'];
            $event_date = explode('/', $event_date);
            $event_date = $event_date[2] . $event_date[1] . $event_date[0];

            foreach ( $shows as $item ) :

                update_post_meta($item->ID, 'event_date', $event_date);

            endforeach;

        endif;

    }

    public static function acf_add_local_field_group() {

        if ( ! function_exists( 'acf_add_local_field_group' ) ) {
            return;
        }

        acf_add_local_field_group( array(
            'key' => 'group_6511964691a63',
            'title' => 'Clothes info',
            'fields' => array(
                array(
                    'key' => 'field_65119647aafc1',
                    'label' => 'Related products',
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
                    'key' => 'field_65119665aafc2',
                    'label' => '',
                    'name' => 'related_products_section',
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
                            'key' => 'field_65119670aafc3',
                            'label' => 'Related products',
                            'name' => 'related_products',
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
                                0 => 'product',
                            ),
                            'post_status' => array(
                                0 => 'publish',
                            ),
                            'taxonomy' => array(
                                0 => 'product_cat:clothes',
                            ),
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
                        'value' => 'product',
                    ),
                    array(
                        'param' => 'post_taxonomy',
                        'operator' => '==',
                        'value' => 'product_cat:clothes',
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
            'key' => 'group_64f1d6627edb2',
            'title' => 'Ticket info',
            'fields' => array(
                array(
                    'key' => 'field_64f1d663597cc',
                    'label' => 'Ticket info',
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
                    'key' => 'field_64f1d7783d451',
                    'label' => '',
                    'name' => 'ticket_info',
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
                            'key' => 'field_64f1d7893d452',
                            'label' => 'Event date',
                            'name' => 'event_date',
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
                            'display_format' => 'd/m/Y',
                            'return_format' => 'd/m/Y',
                            'first_day' => 1,
                        ),
                        array(
                            'key' => 'field_64f9d42953863',
                            'label' => 'Event time',
                            'name' => 'event_time',
                            'aria-label' => '',
                            'type' => 'time_picker',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'display_format' => 'H:i',
                            'return_format' => 'H:i',
                        ),
                        array(
                            'key' => 'field_64f1d7d63d453',
                            'label' => 'Venue',
                            'name' => 'venue',
                            'aria-label' => '',
                            'type' => 'post_object',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'post_type' => array(
                                0 => 'venue',
                            ),
                            'post_status' => array(
                                0 => 'publish',
                            ),
                            'taxonomy' => '',
                            'return_format' => 'id',
                            'multiple' => 0,
                            'allow_null' => 0,
                            'bidirectional' => 0,
                            'ui' => 1,
                            'bidirectional_target' => array(
                            ),
                        ),
                        array(
                            'key' => 'field_64f1d930a963b',
                            'label' => 'Recommended',
                            'name' => 'recommended',
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
                            'key' => 'field_65420de3e0cb6',
                            'label' => 'Sponsored by',
                            'name' => 'sponsored_by',
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
                        array(
                            'key' => 'field_65420defe0cb7',
                            'label' => 'Sponsor logo',
                            'name' => 'sponsor_logo',
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
                            'return_format' => 'id',
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
                ),
                array(
                    'key' => 'field_650d8c8f543d1',
                    'label' => 'Under description',
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
                    'key' => 'field_650d8c9c543d2',
                    'label' => '',
                    'name' => 'under_description',
                    'aria-label' => '',
                    'type' => 'repeater',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'table',
                    'pagination' => 0,
                    'min' => 0,
                    'max' => 0,
                    'collapsed' => '',
                    'button_label' => 'Add Row',
                    'rows_per_page' => 20,
                    'sub_fields' => array(
                        array(
                            'key' => 'field_650d8cab543d3',
                            'label' => 'Image / Text',
                            'name' => 'is_text',
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
                            'ui_on_text' => 'Image',
                            'ui_off_text' => 'Text',
                            'ui' => 1,
                            'parent_repeater' => 'field_650d8c9c543d2',
                        ),
                        array(
                            'key' => 'field_650d8cd0543d4',
                            'label' => 'Image',
                            'name' => 'image',
                            'aria-label' => '',
                            'type' => 'image',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_650d8cab543d3',
                                        'operator' => '==',
                                        'value' => '1',
                                    ),
                                ),
                            ),
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
                            'parent_repeater' => 'field_650d8c9c543d2',
                        ),
                        array(
                            'key' => 'field_650d8ced543d5',
                            'label' => 'Text',
                            'name' => 'text',
                            'aria-label' => '',
                            'type' => 'textarea',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => array(
                                array(
                                    array(
                                        'field' => 'field_650d8cab543d3',
                                        'operator' => '!=',
                                        'value' => '1',
                                    ),
                                ),
                            ),
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'rows' => 2,
                            'placeholder' => '',
                            'new_lines' => '',
                            'parent_repeater' => 'field_650d8c9c543d2',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_650d88fb245da',
                    'label' => 'Bands section',
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
                    'key' => 'field_650d8908245db',
                    'label' => '',
                    'name' => 'bands_section',
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
                            'key' => 'field_65420de3213e0cb6',
                            'label' => 'Title',
                            'name' => 'title',
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
                        array(
                            'key' => 'field_650d8926245dc',
                            'label' => 'Text',
                            'name' => 'text',
                            'aria-label' => '',
                            'type' => 'wysiwyg',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'tabs' => 'all',
                            'toolbar' => 'full',
                            'media_upload' => 0,
                            'delay' => 0,
                        ),
                        array(
                            'key' => 'field_650d8933245dd',
                            'label' => 'Bands',
                            'name' => 'bands',
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
                                0 => 'band',
                            ),
                            'post_status' => array(
                                0 => 'publish',
                            ),
                            'taxonomy' => '',
                            'filters' => array(
                                0 => 'search',
                                1 => 'taxonomy',
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
                        'value' => 'product',
                    ),
                    array(
                        'param' => 'post_taxonomy',
                        'operator' => '==',
                        'value' => 'product_cat:tickets',
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