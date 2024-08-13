<?php

class Error_Page_Latest_Posts {

    public function __construct() {

        $this->query = new WP_Query([
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 3,
            'meta_key'  => 'popularity',
            'orderby'   => 'meta_value_num',
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
        ]);

        $this->blog_link = get_post_type_archive_link('post');

    }

    public function render() {
        if( $this->query->have_posts() ) : ?>

            <section class="recommended">
                <div class="container">
                    <div class="article__recommended-flex">
                        <h3 class="section__title">POPULAR POSTS</h3>
                        <div class="article__recommended-btn">
                            <div class="swiper-button-prev main__prev main__prev-act"></div>
                            <div class="swiper-button-next main__next main__next-act"></div>
                        </div>
                    </div>
                    <div class="article__recommended-slider swiper-container">
                        <ul class="article__recommended-list swiper-wrapper">
                            <?php while( $this->query->have_posts() ) : $this->query->the_post(); ?>
                                <?php $post_id = get_the_ID(); ?>
                                <?php $post_thumbnail = get_the_post_thumbnail_url(); ?>
                                <?php $post_title = get_the_title(); ?>
                                <li class="blog__item swiper-slide">
                                    <?php if( !empty( $post_thumbnail ) ) : ?>
                                        <div class="blog__item-img img">
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
                                        <h3 class="blog__item-name"><?php echo $post_title; ?></h3>
                                        <?php $content = strip_tags(apply_filters('the_content', get_the_content( post: $post_id ))); ?>
                                        <?php if( !empty( $content ) ) : ?>
                                            <p class="blog__item-desc" ><?php echo $content; ?></p>
                                        <?php endif; ?>
                                        <a href="<?php echo get_the_permalink(); ?>" class="blog__item-link">Read more</a>
                                    </div>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    </div>
                    <a href="<?php echo $this->blog_link; ?>" class="article__view section__button ">
                        View all
                    </a>
                </div>

            </section>

        <?php endif;
    }

}