<?php

class Front_Page_Posts_Section {

    public function __construct() {

        $this->latest_posts = get_posts([
            'post_type' => 'post',
            'post_status' => 'publish',
            'numberposts' => 10,
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => 'archived_post',
                    'value' => 1,
                    'compare' => '!='
                ],
                [
                    'key' => 'archived_post',
                    'compare' => 'NOT EXISTS'
                ]
            ]
        ]);

        $this->popular_posts = get_posts([
            'post_type' => 'post',
            'post_status' => 'publish',
            'numberposts' => 10,
            'meta_key'  => 'popularity',
            'orderby'   => 'meta_value_num',
            'meta_query' => [
                'relation' => 'OR',
                [
                    'key' => 'archived_post',
                    'value' => 1,
                    'compare' => '!='
                ],
                [
                    'key' => 'archived_post',
                    'compare' => 'NOT EXISTS'
                ]
            ]
        ]);

        $this->editor_pick_posts = get_posts([
            'post_type' => 'post',
            'post_status' => 'publish',
            'numberposts' => 10,
            'meta_query'  => [
                'relation' => 'AND',
                [
                    'key' => 'post_info_editor_pick',
                    'value' => '1'
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

        $posts_section = get_field( 'posts_section' );
        $this->main_posts = $posts_section['posts'];
        $this->button = $posts_section['button'];

    }

    private static function render_posts_tab( $posts_object ) {

        if( !empty( $posts_object ) ) : ?>

            <div class="posts__tab-item tab__item">
                <div class="posts__slider swiper" >
                    <ul class="posts__list swiper-wrapper ">
                        <?php foreach( $posts_object as $item ) : ?>
                            <?php $post_thumbnail = get_the_post_thumbnail_url( $item->ID ); ?>
                            <?php $title = get_the_title( $item->ID ); ?>
                            <?php $permalink = get_the_permalink( $item->ID ); ?>
                            <?php $content = strip_tags(apply_filters( 'the_content', get_the_content( post: $item->ID ))); ?>
                            <li class="posts__item swiper-slide">
                                <!--<h3 class="posts__flash">Latest Posts</h3>-->
                                <?php if( !empty( $post_thumbnail ) ) :  ?>
                                    <div class="posts__img img">
                                        <img src="<?php echo $post_thumbnail; ?>" alt="">
                                    </div>
                                <?php endif; ?>
                                <div class="posts__desc">
                                    <div class="posts__text">
                                        <h3 class="posts__title"><?php echo $title; ?></h3>
                                        <?php if( !empty( $content ) ) : ?>
                                            <h4 class="posts__subtitle"><?php echo $content; ?></h4>
                                        <?php endif; ?>
                                    </div>
                                    <a href="<?php echo $permalink; ?>" class="posts__link"></a>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>

        <?php endif;
    }

    public function render() {
        ?>

            <section class="posts">
                <div class="main__back img">
                    <img src="<?=TEMPLATE_PATH?>/img/back-img.png" alt="">
                </div>
                <div class="container">
                    <div class="posts__block">
                        <div class="posts__tab tabs">
                             <div class="posts__header">
                                <div class="posts__nav">
                                   <div class="swiper-button-prev posts__prev"></div>
                                   <div class="swiper-button-next posts__next"></div>
                                </div>
                                <div class="posts__tab-wrap">
                                    <span class="tab active">All</span>
                                    <span class="tab">Latest Posts</span>
                                    <span class="tab">Popular Posts</span>
                                    <span class="tab">Editor's Picks Posts</span>
                                </div>
                            </div>
                            <div class="posts__tab-content">
                                <?php self::render_posts_tab( $this->main_posts ); ?>
                                <?php self::render_posts_tab( $this->latest_posts ); ?>
                                <?php self::render_posts_tab( $this->popular_posts ); ?>
                                <?php self::render_posts_tab( $this->editor_pick_posts ); ?>
                            </div>
                        </div>
                        <?php if( !empty( $this->button ) ) : ?>
                            <a href="<?php echo $this->button['url']; ?>" class="section__button posts__button"><?php echo $this->button['title']; ?></a>
                        <?php endif; ?>
                    </div>

                </div>
            </section>

        <?php
    }
}