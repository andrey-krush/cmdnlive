<?php

class Gutenberg_Recommended_Posts_Section {

    public static function init_auto() {

        add_action( 'acf/init', [ __CLASS__, 'acf_register_block_type' ] );
        add_action( 'acf/init', [ __CLASS__, 'acf_add_local_field_group' ] );

    }

    public static function acf_register_block_type() {
        acf_register_block_type( [
            'name' => 'recommended-posts-block',
            'title' => 'Recommended posts block',
            'description' => '',
            'category' => 'custom',
            'icon' => '',
            'post_types' => ['venue'],
            'keywords' => [],
            'mode' => 'edit',
            'align' => 'full',
            'render_callback' => [ __CLASS__, 'render' ],
        ]);
    }

    public static function render() {

        $title = get_field('title');
        $recommended_posts = get_field('recommended_posts');
        $post_archive_link = get_post_type_archive_link('post');
        if( !empty( $recommended_posts ) ) : ?>

            <div class="article__recommended">
            <div class="article__recommended-flex" >
                <?php if( !empty( $title ) ) : ?>
                    <h3 class="section__title"><?php echo $title; ?></h3>
                <?php endif; ?>
                <div class="article__recommended-btn">
                    <div class="swiper-button-prev main__prev main__prev-act "></div>
                    <div class="swiper-button-next main__next main__next-act "></div>
                </div>
            </div>
            <div class="article__recommended-slider swiper-container">
                <ul class="article__recommended-list swiper-wrapper">
                    <?php foreach( $recommended_posts as $item ) : ?>
                        <li class="blog__item swiper-slide" >
                            <?php $post_thumbnail = get_the_post_thumbnail_url($item->ID); ?>
                            <?php $content = strip_tags(apply_filters( 'the_content', get_the_content( post: $item->ID ) )); ?>
                            <?php if( !empty( $post_thumbnail ) ) : ?>
                                <a href="<?php echo get_the_permalink($item->ID); ?>"  class="blog__item-img img">
                                    <img src="<?php echo $post_thumbnail; ?>" alt="">
                                </a>
                            <?php endif; ?>
                            <div class="blog__item-text">
                                <h4 class="blog__item-date"><?php echo get_the_date('F j, Y', $item->ID); ?></h4>
                                <a href="<?php echo get_the_permalink($item->ID); ?>" class="blog__item-name"><?php echo get_the_title($item->ID); ?></a>
                                <?php if( !empty( $content ) ) : ?>
                                    <p class="blog__item-desc" ><?php echo $content; ?></p>
                                <?php endif; ?>
                                <a href="<?php echo get_the_permalink($item->ID); ?>" class="blog__item-link">Read more</a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <a href="<?php echo $post_archive_link; ?>" class="article__view section__button section__btn">
                View all
            </a>
        </div>

        <?php endif;
    }

    public static function acf_add_local_field_group() {
        if ( ! function_exists( 'acf_add_local_field_group' ) ) {
            return;
        }

        acf_add_local_field_group( array(
            'key' => 'group_64e863f92e6f7',
            'title' => 'Recommended posts block',
            'fields' => array(
                array(
                    'key' => 'field_64e863fb10cd7',
                    'label' => 'TItle',
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
                    'key' => 'field_64e8641010cd8',
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
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/recommended-posts-block',
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