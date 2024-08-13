<?php

class Page_Home {

    public static function init_auto() {

        add_action('wp_ajax_load_more_blog', [__CLASS__, 'load_more_blog']);
        add_action('wp_ajax_nopriv_load_more_blog', [__CLASS__, 'load_more_blog']);
        add_action('wp_ajax_not-archived-posts', [__CLASS__, 'nonArchivePosts']);
        add_action('wp_ajax_nopriv_not-archived-posts', [__CLASS__, 'nonArchivePosts']);

        add_action('init', [__CLASS__, 'acf_add_local_field_group']);

    }

    public static function load_more_blog() {

        if( !empty( $_POST['page'] ) ) :

            $args = [
                'post_type'      => 'post',
                'post_status'    => 'publish',
                'paged'          => $_POST['page']
            ];

            if( $_POST['category'] == 'newest-posts' ) :

                $args['posts_per_page'] = 6;
                $args['meta_query'] = [
                    'relation' => 'AND',
                    [
                        'key' => 'post_info_editor_pick',
                        'value' => 0
                    ],
                    [
                        'relation' => 'OR',
                        [
                            'key' => 'archived_post',
                            'compare' => 'NOT EXISTS'
                        ],
                        [
                            'key' => 'archived_post',
                            'value' => 1,
                            'compare' => '!='
                        ],
                    ]
                ];

            elseif ( $_POST['category'] == 'featured-posts' ) :

                $args['posts_per_page'] = 6;
                $args['meta_query'] = [
                    'relation' => 'AND',
                    [
                        'key' => 'post_info_editor_pick',
                        'value' => 1
                    ],
                    [
                        'relation' => 'OR',
                        [
                            'key' => 'archived_post',
                            'compare' => 'NOT EXISTS'
                        ],
                        [
                            'key' => 'archived_post',
                            'value' => 1,
                            'compare' => '!='
                        ],
                    ]
                ];

            elseif( $_POST['category'] == 'archived_posts' ) :

                $args['posts_per_page'] = 12;
                $args['meta_query'] = [
                    [
                        'key' => 'archived_post',
                        'value' => 1
                    ]
                ];

            endif;

            $query = new WP_Query($args);
            $max_num_pages = $query->max_num_pages;
//            var_dump($query->found_posts);

            if( $query->have_posts() ) : ?>
                <?php if( $_POST['addClass'] == '1' ) : ?>
                    <ul class="blog__list blog__list-list blog__list-load">
                <?php else : ?>
                    <ul class="blog__list">
                <?php endif; ?>
                    <?php while( $query->have_posts() ) : $query->the_post(); ?>
                        <?php $post_id = get_the_ID(); ?>
                        <li class="blog__item" >
                            <?php $post_thumbnail = get_the_post_thumbnail_url(); ?>
                            <?php if( !empty( $post_thumbnail ) ) : ?>
                                <div class="blog__item-img img">
                                    <!--<span class="blog__item-flash">Latest Posts</span>-->
                                    <img src="<?php echo $post_thumbnail; ?>" alt="">
                                </div>
                            <?php endif; ?>
                            <?php $post_tags = get_the_terms($post_id, 'category'); ?>
                            <div class="blog__item-text">
                                <?php if( !empty( $post_tags ) ) : ?>
                                    <div class="tickets__categories">
                                        <?php foreach( $post_tags as $item ) : ?>
                                            <?php if( $item->name !== 'Uncategorized' ) : ?>
                                                <a>
                                                    <div class="tickets__categories-name"><?php echo $item->name; ?></div>
                                                </a>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <h4 class="blog__item-date"><?php echo get_the_date('F j, Y'); ?></h4>
                                <a href="<?php echo get_the_permalink(); ?>" class="blog__item-name"><?php echo get_the_title(); ?></a>
                                <?php $content = strip_tags(apply_filters('the_content', get_the_content( post: get_the_ID() ))); ?>
                                <?php if( !empty( $content ) ) : ?>
                                    <p class="blog__item-desc" ><?php echo $content; ?></p>
                                <?php endif; ?>
                                <a href="<?php echo get_the_permalink(); ?>" class="blog__item-link">Read more</a>
                            </div>
                        </li>

                    <?php endwhile; ?>

                </ul>

                <?php if( $max_num_pages > $_POST['page'] ) : ?>
                    <button class="blog__load section__load section__button">
                        Load more
                    </button>
                <?php endif;


            endif;

        endif;

        die;
    }

    public static function nonArchivePosts() {

         $args = [
            'post_type' => 'post',
            'post_status' => 'publish',

        ];

        $featured_query = new WP_Query(array_merge($args, [
            'posts_per_page' => 6,
            'meta_query' => [
                    'relation' => 'AND',
                    [
                        'key' => 'post_info_editor_pick',
                        'value' => 1
                    ],
                    [
                        'relation' => 'OR',
                        [
                            'key' => 'archived_post',
                            'compare' => 'NOT EXISTS'
                        ],
                        [
                            'key' => 'archived_post',
                            'value' => 1,
                            'compare' => '!='
                        ],
                    ]
                ]
        ] ) );

        $newest_query = new WP_Query( array_merge( $args, [
            'posts_per_page' => 6,
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => 'post_info_editor_pick',
                    'value' => 0
                ],
                [
                        'relation' => 'OR',
                        [
                            'key' => 'archived_post',
                            'compare' => 'NOT EXISTS'
                        ],
                        [
                            'key' => 'archived_post',
                            'value' => 1,
                            'compare' => '!='
                        ],
                    ]

            ]
        ] ) );

        if ($featured_query->have_posts()) : ?>
            <div class="blog__block" data-category='featured-posts' data-page="1">
                <h3 class='blog__block-title'>Featured posts</h3>
                <?php if( $_POST['addClass'] == '1' ) : ?>
                    <ul class="blog__list blog__list-list blog__list-load">
                <?php else : ?>
                    <ul class="blog__list">
                <?php endif; ?>
                    <?php while ($featured_query->have_posts()) : $featured_query->the_post(); ?>
                        <li class="blog__item">
                            <?php $post_id = get_the_ID(); ?>
                            <?php $post_thumbnail = get_the_post_thumbnail_url(); ?>
                            <?php if (!empty($post_thumbnail)) : ?>
                                <a href="<?php echo get_the_permalink(); ?>" class="blog__item-img img">
                                    <!--<span class="blog__item-flash">Latest Posts</span>-->
                                    <img src="<?php echo $post_thumbnail; ?>" alt="">
                                </a>
                            <?php endif; ?>
                            <?php $post_tags = get_the_terms($post_id, 'category'); ?>
                            <div class="blog__item-text">
                                <?php if( !empty( $post_tags ) ) : ?>
                                    <div class="tickets__categories">
                                        <?php foreach( $post_tags as $item ) : ?>
                                            <a>
                                                <div class="tickets__categories-name"><?php echo $item->name; ?></div>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <h4 class="blog__item-date"><?php echo get_the_date('F j, Y'); ?></h4>
                                <a href="<?php echo get_the_permalink(); ?>"
                                   class="blog__item-name"><?php echo get_the_title(); ?></a>
                                <?php $content = strip_tags(apply_filters('the_content', get_the_content(post: $post_id ))); ?>
                                <?php if (!empty($content)) : ?>
                                    <p class="blog__item-desc"><?php echo $content; ?></p>
                                <?php endif; ?>
                                <a href="<?php echo get_the_permalink(); ?>" class="blog__item-link">Read
                                    more</a>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
                <?php if ($featured_query->max_num_pages > 1) : ?>
                    <button class="blog__load section__load section__button">
                        discover more
                    </button>
                <?php endif; ?>
            </div>
        <?php endif;

        if ($newest_query->have_posts()) : ?>
            <div class="blog__block" data-category='featured-posts' data-page="1">
                <h3 class='blog__block-title'>Newest posts</h3>
                <?php if( $_POST['addClass'] == '1' ) : ?>
                    <ul class="blog__list blog__list-list blog__list-load">
                <?php else : ?>
                    <ul class="blog__list">
                <?php endif; ?>
                    <?php while ($newest_query->have_posts()) : $newest_query->the_post(); ?>
                        <li class="blog__item">
                            <?php $post_id = get_the_ID(); ?>
                            <?php $post_thumbnail = get_the_post_thumbnail_url(); ?>
                            <?php if (!empty($post_thumbnail)) : ?>
                                <a href="<?php echo get_the_permalink(); ?>" class="blog__item-img img">
                                    <!--<span class="blog__item-flash">Latest Posts</span>-->
                                    <img src="<?php echo $post_thumbnail; ?>" alt="">
                                </a>
                            <?php endif; ?>
                            <?php $post_tags = get_the_terms($post_id, 'category'); ?>
                            <div class="blog__item-text">
                                <?php if( !empty( $post_tags ) ) : ?>
                                    <div class="tickets__categories">
                                        <?php foreach( $post_tags as $item ) : ?>
                                            <a>
                                                <div class="tickets__categories-name"><?php echo $item->name; ?></div>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                <h4 class="blog__item-date"><?php echo get_the_date('F j, Y'); ?></h4>
                                <a href="<?php echo get_the_permalink(); ?>"
                                   class="blog__item-name"><?php echo get_the_title(); ?></a>
                                <?php $content = strip_tags(apply_filters('the_content', get_the_content(post: $post_id ))); ?>
                                <?php if (!empty($content)) : ?>
                                    <p class="blog__item-desc"><?php echo $content; ?></p>
                                <?php endif; ?>
                                <a href="<?php echo get_the_permalink(); ?>" class="blog__item-link">Read
                                    more</a>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
                <?php if ($newest_query->max_num_pages > 1) : ?>
                    <button class="blog__load section__load section__button">
                        discover more
                    </button>
                <?php endif; ?>
            </div>
        <?php endif;
        die;

    }

    public static function acf_add_local_field_group() {

        if ( ! function_exists( 'acf_add_local_field_group' ) ) {
            return;
        }

        acf_add_local_field_group( array(
        'key' => 'group_64e47c608b275',
        'title' => 'Home page',
        'fields' => array(
            array(
                'key' => 'field_64e47c6267e47',
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
                'key' => 'field_64e47c8367e48',
                'label' => 'Promo section',
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
                        'key' => 'field_64e47c9967e49',
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
                        'key' => 'field_64e47cac67e4a',
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
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'page_type',
                    'operator' => '==',
                    'value' => 'posts_page',
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