<?php

class Home_Page_Posts_Section
{

    public function __construct()
    {

        $blog_page_id = get_option('page_for_posts');
        $this->blog_page_title = get_the_title($blog_page_id);

        $args = [
            'post_type' => 'post',
            'post_status' => 'publish',

        ];

        $this->hot_tickets_query = new WP_Query(array_merge($args, [
            'posts_per_page' => 3,
            'cat' => '78',
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

        $this->featured_query = new WP_Query(array_merge($args, [
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

        $this->newest_query = new WP_Query( array_merge( $args, [
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


    }

    public function render()
    {
        ?>
            <section class="blog">
                <div class="container">
                    <h2 class="section__title"><?php echo $this->blog_page_title; ?></h2>
                    <form action="" class="filter__form">
                        <input type="text" hidden name="action" value="load_more_blog">
                        <input type="text" hidden name="page" value="1" class="page">
                        <input type="text" hidden name="category" class="category__input">
                        <input type="text" hidden name="addClass" value="0" class="blog__list-ajax">
                    </form>
                    <div class="blog__display-wrap">
                        <button class="blog__display-archived">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 4C13.5212 4 15.5188 6.27264 15.8088 9.018H15.8962C18.1637 9.018 20 10.8049 20 13.009C20 15.2131 18.1625 17 15.8975 17H4.1025C1.83625 17 0 15.2131 0 13.009C0 10.8782 1.71625 9.13736 3.8775 9.02391L4.19125 9.018C4.48375 6.25491 6.47875 4 10 4ZM10 5.18182C7.3575 5.18182 5.68875 6.73709 5.435 9.135C5.4043 9.42672 5.26002 9.69725 5.03017 9.89403C4.80033 10.0908 4.50133 10.1998 4.19125 10.1998H4.10375C2.53375 10.1998 1.25 11.4502 1.25 13.009C1.25 14.569 2.535 15.8182 4.1025 15.8182H15.8975C17.465 15.8182 18.75 14.5678 18.75 13.009C18.75 11.449 17.465 10.1998 15.8975 10.1998H15.8088C15.4989 10.1998 15.2 10.091 14.9702 9.89446C14.7404 9.69793 14.596 9.42769 14.565 9.13618C14.3125 6.74773 12.6362 5.18182 10 5.18182Z"
                                      fill="black"/>
                            </svg>
                            <span>archived posts<span>
                        </button>
                        <div class="blog__display">
                            <div class="blog__display-menu active blog__display-item"></div>
                            <div class="blog__display-list blog__display-item"></div>
                        </div>
                    </div>
                    <div class="blog__block-wrap">
                    <?php if ($this->hot_tickets_query->have_posts()) : ?>
                        <div class="blog__block" data-category='hot_tickets-posts' data-page="1">
                            <h3 class='blog__block-title'>Gig Picks</h3>
                            <ul class="blog__list">
                                <?php while ($this->hot_tickets_query->have_posts()) : $this->hot_tickets_query->the_post(); ?>
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
                        </div>
                    <?php endif; ?>
                    <?php if ($this->featured_query->have_posts()) : ?>
                        <div class="blog__block" data-category='featured-posts' data-page="1">
                            <h3 class='blog__block-title'>Featured posts</h3>
                            <ul class="blog__list">
                                <?php while ($this->featured_query->have_posts()) : $this->featured_query->the_post(); ?>
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
                            <?php if ($this->featured_query->max_num_pages > 1) : ?>
                                <button class="blog__load section__load section__button">
                                    discover more
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->newest_query->have_posts()) : ?>
                        <div class="blog__block" data-category='newest-posts' data-page="1">
                            <h3 class='blog__block-title'>Newest posts</h3>
                            <ul class="blog__list">
                                <?php while ($this->newest_query->have_posts()) : $this->newest_query->the_post(); ?>
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
                                                        <?php if( $item->name !== 'Uncategorized' ) : ?>
                                                            <a>
                                                                <div class="tickets__categories-name"><?php echo $item->name; ?></div>
                                                            </a>
                                                        <?php endif; ?>
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
                            <?php if ($this->newest_query->max_num_pages > 1) : ?>
                                <button class="blog__load section__load section__button">
                                    discover more
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    </div>
                </div>
            </section>

        <?php
    }
}