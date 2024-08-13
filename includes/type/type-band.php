<?php

class Type_Band {

    public static function init_auto() {

        add_action('init', [ __CLASS__, 'register_band_post_type' ]);
        add_action('init', [ __CLASS__, 'register_band_post_taxonomy' ]);
        add_action('init', [__CLASS__, 'acf_add_local_field_group']);

    }

    public static function register_band_post_type()
    {

        register_post_type('band', [
            'label' => null,
            'labels' => [
                'name' => 'Bands',
                'singular_name' => 'Band',
                'add_new' => 'Add new band',
                'add_new_item' => 'Adding new band',
                'edit_item' => 'Editing band',
                'new_item' => 'New band',
                'view_item' => 'View band',
                'search_items' => 'Find band',
                'not_found' => 'Not found',
                'not_found_in_trash' => 'Not found in trash',
                'parent_item_colon' => '',
                'menu_name' => 'Bands',
            ],
            'public'             => true,
            'supports'           => ['title', 'editor', 'thumbnail'],
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'has_archive'        => false,
            'taxonomies'         =>  ['band_tag'],
        ]);

    }

    public static function register_band_post_taxonomy () {

        register_taxonomy( 'band_tag', [ 'band' ], [
            'label'                 => '', // определяется параметром $labels->name
            'labels'                => [
                'name'              => 'Tags',
                'singular_name'     => 'Tag',
                'search_items'      => 'Search Tags',
                'all_items'         => 'All Tags',
                'view_item '        => 'View Tag',
                'parent_item'       => 'Parent Tag',
                'parent_item_colon' => 'Parent Tag:',
                'edit_item'         => 'Edit Tag',
                'update_item'       => 'Update Tag',
                'add_new_item'      => 'Add New Tag',
                'new_item_name'     => 'New Tag Name',
                'menu_name'         => 'Tag',
                'back_to_items'     => '← Back to Tag',
            ],
            'public'                => true,
            'hierarchical'          => false,
        ] );

    }

    public static function acf_add_local_field_group() {
        if ( ! function_exists( 'acf_add_local_field_group' ) ) {
            return;
        }

        acf_add_local_field_group( array(
            'key' => 'group_64e36df5952dc',
            'title' => 'Band',
            'fields' => array(
                array(
                    'key' => 'field_64e36df65884c',
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
                    'key' => 'field_64e36e125884d',
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
                            'key' => 'field_64e36e295884e',
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
                            'key' => 'field_64e74e3725df8',
                            'label' => 'Related articles',
                            'name' => 'related_articles',
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
                                1 => 'taxonomy',
                            ),
                            'return_format' => 'object',
                            'min' => '',
                            'max' => '',
                            'elements' => '',
                            'bidirectional' => 0,
                            'bidirectional_target' => array(
                            ),
                        ),
                        array(
                            'key' => 'field_64ff03b66f73b',
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
                        'value' => 'band',
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