<?php

class Type_Application {

    public static function init_auto () {
        add_action( 'init', [__CLASS__, 'register_post_type'] );
    }

    public static function register_post_type() {

        register_post_type('application', [
            'label' => null,
            'labels' => [
                'name'               => 'Applications',
                'singular_name'      => 'Application',
                'add_new'            => 'Add new application',
                'add_new_item'       => 'Adding new application',
                'edit_item'          => 'Editing application',
                'new_item'           => 'New application',
                'view_item'          => 'View application',
                'search_items'       => 'Find application',
                'not_found'          => 'Not found',
                'not_found_in_trash' => 'Not found in trash',
                'parent_item_colon'  => '',
                'menu_name'          => 'Applications',
            ],
            'public'             => true,
            'publicly_queryable' => false,
            'show_in_menu'       => true,
            'has_archive'        => false,
        ]);

    }

}