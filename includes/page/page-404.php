<?php

class Page_404 {


    public static function get_url()
    {
        $page = get_pages([
            'meta_key' => '_wp_page_template',
            'meta_value' => '404.php',
        ]);

        return ($page && 'publish' === $page[0]->post_status) ? get_the_permalink($page[0]->ID) : false;
    }

    public static function get_ID()
    {
        $page = get_pages([
            'meta_key' => '_wp_page_template',
            'meta_value' => '404.php',
        ]);

        return ($page && 'publish' === $page[0]->post_status) ? $page[0]->ID : false;
    }

}