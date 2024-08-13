<?php

class Page_Archive_Venue {

    public static function init_auto() {

        add_action( 'init', [__CLASS__, 'acf_add_local_field_group']);
        add_action( 'wp_ajax_load_more_venues', [__CLASS__, 'load_more_venues'] );
        add_action( 'wp_ajax_nopriv_load_more_venues', [__CLASS__, 'load_more_venues'] );

    }
    public static function get_url() {
        $page = get_pages( [
            'meta_key' => '_wp_page_template',
            'meta_value' => 'archive-venue.php',
        ]);

        return ( $page && 'publish' === $page[ 0 ]->post_status ) ? get_the_permalink( $page[ 0 ]->ID ) : false;
    }

    public static function get_ID() {
        $page = get_pages( [
            'meta_key' => '_wp_page_template',
            'meta_value' => 'archive-venue.php',
        ]);

        return ( $page && 'publish' === $page[ 0 ]->post_status ) ? $page[ 0 ]->ID : false;
    }

    public static function load_more_venues() {

        if( !empty( $_POST['page'] ) ) :

            $args = [
                'post_type'      => 'venue',
                'post_status'    => 'publish',
                'posts_per_page' => 4,
                'paged'          => $_POST['page']
            ];

            $query = new WP_Query($args);

            if( $query->have_posts() ) : ?>

                <ul class="venues__list">
                    <?php while( $query->have_posts() ) : $query->the_post(); ?>
                        <li class="venues__item">
                            <?php $post_thumbnail = get_the_post_thumbnail_url(); ?>
                            <?php if( !empty( $post_thumbnail ) ) : ?>
                                <div class="venues__img img">
                                    <img src="<?php echo $post_thumbnail; ?>" alt="">
                                </div>
                            <?php endif; ?>
                            <h3 class="venues__title"><?php echo get_the_title(); ?></h3>
                            <?php $content = strip_tags(apply_filters('the_content', get_the_content( post: get_the_ID() ))); ?>
                            <?php if( !empty( $content ) ) : ?>
                                <h4 class="venues__desc"><?php echo $content; ?></h4>
                            <?php endif; ?>
                            <a href="<?php echo get_the_permalink(); ?>" class="venues__link">Read more</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
                <?php if( $query->max_num_pages > $_POST['page'] ) : ?>
                    <!--load more-->
                <?php endif; ?>

            <?php endif;

        endif;

        die;
    }
    public static function acf_add_local_field_group() {
        if ( ! function_exists( 'acf_add_local_field_group' ) ) {
            return;
        }

        acf_add_local_field_group( array(
            'key' => 'group_64e8a1f8a9ab6',
            'title' => 'Archive venues',
            'fields' => array(
                array(
                    'key' => 'field_64e8a1f960d93',
                    'label' => 'Venues section',
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
                    'key' => 'field_64e8a21660d94',
                    'label' => '',
                    'name' => 'venues_section',
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
                            'key' => 'field_64e8a21f60d95',
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
                            'key' => 'field_64e8a22d60d96',
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
                            'media_upload' => 1,
                            'delay' => 0,
                        ),
                    ),
                ),
                array(
                    'key' => 'field_64ef2ded2f18a',
                    'label' => 'Advertisement section',
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
                    'key' => 'field_64ef2e962f18b',
                    'label' => '',
                    'name' => 'advertisement_section',
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
                            'key' => 'field_64ef2ea32f18c',
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
                            'key' => 'field_64ef2ebc2f18d',
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
                            'key' => 'field_64ef2ed72f18e',
                            'label' => 'Image',
                            'name' => 'image',
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
                        array(
                            'key' => 'field_64ef2ee32f18f',
                            'label' => 'Link',
                            'name' => 'link',
                            'aria-label' => '',
                            'type' => 'link',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'page_template',
                        'operator' => '==',
                        'value' => 'archive-venue.php',
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