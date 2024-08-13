<?php

class Page_Band_Archive {


    public static function init_auto() {

        add_action( 'wp_ajax_show__bands', [__CLASS__, 'bandsFiltration'] );
        add_action( 'wp_ajax_nopriv_show__bands', [__CLASS__, 'bandsFiltration'] );
        add_action( 'init', [__CLASS__, 'acf_add_fields'] );

    }

    public static function get_url() {
        $page = get_pages( [
            'meta_key' => '_wp_page_template',
            'meta_value' => 'archive-band.php',
        ]);

        return ( $page && 'publish' === $page[ 0 ]->post_status ) ? get_the_permalink( $page[ 0 ]->ID ) : false;
    }

    public static function get_ID() {
        $page = get_pages( [
            'meta_key' => '_wp_page_template',
            'meta_value' => 'archive-band.php',
        ]);

        return ( $page && 'publish' === $page[ 0 ]->post_status ) ? $page[ 0 ]->ID : false;
    }

    public static function bandsFiltration() {

        $args = [
            'post_type' => 'band',
            'post_status' => 'publish',
            'posts_per_page' => 16,
            'paged' => $_POST['page'],
            'orderby' => 'title',
            'order' => ( $_POST['sort'] == 'Z-A') ? 'DESC' : 'ASC',
            'tax_query' => empty( $_POST['categories'] ) ? [] : [
                [
                    'taxonomy' => 'band_tag',
                    'field'    => 'slug',
                    'terms'    => explode( ',', $_POST['categories'])
                ]
            ]
        ];

        $query = new WP_Query($args);

        if( $query->have_posts() ) : ob_start(); ?>

            <ul class="bands__list">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <?php $post_thumbnail = get_the_post_thumbnail_url(); ?>
                    <li class="bands__item">
                        <?php if( !empty( $post_thumbnail ) ) : ?>
                            <div class="bands__img img">
                                <img src="<?php echo $post_thumbnail; ?>" alt="">
                            </div>
                        <?php endif; ?>
                        <div class="bands__text">
                            <h2 class="bands__title"><?php echo get_the_title(); ?></h2>
                            <?php $content = strip_tags(apply_filters('the_content', get_the_content( post: get_the_ID() ))); ?>
                            <?php if( !empty( $content ) ) : ?>
                                <h3 class="bands__desc"><?php echo $content; ?></h3>
                            <?php endif; ?>
                            <a href="<?php echo get_the_permalink(); ?>" class="bands__link"></a>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
            <?php if( $query->max_num_pages > $_POST['page'] ) : ?>
                <button class="bands__load section__load section__button ">
                    LOAD MORE
                </button>
            <?php endif; ?>

        <?php endif;

        die;

    }

    public static function acf_add_fields() {

            if ( ! function_exists( 'acf_add_local_field_group' ) ) {
                return;
            }

            acf_add_local_field_group( array(
                'key' => 'group_65c346b6698d7',
                'title' => 'Bands page',
                'fields' => array(
                    array(
                        'key' => 'field_65c346b76519b',
                        'label' => 'Promo section',
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
                        'key' => 'field_65c347906519c',
                        'label' => '',
                        'name' => 'promo_section',
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
                                'key' => 'field_65c347bb6519d',
                                'label' => 'Text',
                                'name' => 'text',
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
                                'key' => 'field_65c347d56519e',
                                'label' => 'Background image',
                                'name' => 'background_image',
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
                                'key' => 'field_65c74050aee15',
                                'label' => 'Title under categories',
                                'name' => 'title_under_categories',
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
                    ),
                    array(
                        'key' => 'field_65c346b3276519b',
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
                        'key' => 'field_6421e86a8283498',
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
                                'key' => 'field_64e86a9d83499',
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
                                'key' => 'field_6501c2146b0b47',
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
                                'key' => 'field_64e86aa98349b',
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
                                'key' => 'field_64e86ab28349c',
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
                            'value' => 'archive-band.php',
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