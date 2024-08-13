<?php

class Type_Client {

    public static function init_auto() {

        add_action('init', [__CLASS__, 'register_client_post_type']);

    }

    public static function register_client_post_type() {

        register_post_type('client', [
            'label' => null,
            'labels' => [
                'name'               => 'Clients',
                'singular_name'      => 'Client',
                'add_new'            => 'Add new client',
                'add_new_item'       => 'Adding new client',
                'edit_item'          => 'Editing client',
                'new_item'           => 'New client',
                'view_item'          => 'View client',
                'search_items'       => 'Find client',
                'not_found'          => 'Not found',
                'not_found_in_trash' => 'Not found in trash',
                'parent_item_colon'  => '',
                'menu_name'          => 'Clients',
            ],
            'public'             => true,
            'publicly_queryable' => false,
            'show_in_menu'       => true,
            'has_archive'        => false,
        ]);
    }

}