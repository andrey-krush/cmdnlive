<?php

class Type_Venue {

    public static function init_auto() {

        add_action('init', [ __CLASS__, 'register_venue_post_type' ]);

        add_action('init', [ __CLASS__, 'acf_add_local_field_group']);

    }

    public static function register_venue_post_type()
    {

        register_post_type('venue', [
            'label' => null,
            'labels' => [
                'name' => 'Venues',
                'singular_name' => 'Venue',
                'add_new' => 'Add new venue',
                'add_new_item' => 'Adding new venue',
                'edit_item' => 'Editing venue',
                'new_item' => 'New venue',
                'view_item' => 'View venue',
                'search_items' => 'Find venue',
                'not_found' => 'Not found',
                'not_found_in_trash' => 'Not found in trash',
                'parent_item_colon' => '',
                'menu_name' => 'Venues',
            ],
            'public'             => true,
            'supports'           => ['title', 'editor', 'thumbnail'],
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'has_archive'        => true,
            'taxonomies'         =>  ['post_tag'],
        ]);

    }

    public static function acf_add_local_field_group() {
        if ( ! function_exists( 'acf_add_local_field_group' ) ) {
            return;
        }

        acf_add_local_field_group( array(
            'key' => 'group_64facfe679f06',
            'title' => 'Venue',
            'fields' => array(
                array(
                    'key' => 'field_64facfe809f8a',
                    'label' => 'Venue info',
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
                    'key' => 'field_64facff909f8b',
                    'label' => '',
                    'name' => 'venue_info',
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
                            'key' => 'field_64fad00309f8c',
                            'label' => 'Location',
                            'name' => 'location',
                            'aria-label' => '',
                            'type' => 'google_map',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'center_lat' => '',
                            'center_lng' => '',
                            'zoom' => '',
                            'height' => '',
                        ),
                        array(
                            'key' => 'field_64ff0ba6d23b7',
                            'label' => 'Related shows',
                            'name' => 'related_shows',
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
                                0 => 'product_cat:tickets',
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
                        'value' => 'venue',
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