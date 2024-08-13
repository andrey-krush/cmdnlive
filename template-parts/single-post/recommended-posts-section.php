<?php

class Single_Post_Recommended_Posts_Section {

    public function __construct() {

        $this->recommended_posts = get_field('post_info')['recommended_posts'] ?? '';
        $this->post_archive_link = get_post_type_archive_link('post');

    }

    public function render() {

        if( !empty( $this->recommended_posts ) ) : ?>

            <section class="article__recommended">
                <div class="container">
                    <div class="article__recommended-flex" >
                        <h3 class="section__title">RECOMMENDED POSTS</h3>
                        <div class="article__recommended-btn">
                            <div class="swiper-button-prev main__prev main__prev-act "></div>
                            <div class="swiper-button-next main__next main__next-act "></div>
                        </div>
                    </div>

                    <div class="article__recommended-slider swiper-container">
                        <ul class="article__recommended-list swiper-wrapper">
                            <?php foreach( $this->recommended_posts as $item ) : ?>
                                <li class="blog__item swiper-slide" >
                                    <?php $thumbnail = get_the_post_thumbnail_url( $item ); ?>
                                    <?php $content = strip_tags(apply_filters( 'the_content', get_the_content( post: $item ))); ?>
                                    <?php if( !empty( $thumbnail ) ) : ?>
                                        <a href="<?php echo get_the_permalink($item); ?>" class="blog__item-img img">
                                            <!--<span class="blog__item-flash">Latest Posts</span>-->
                                            <img src="<?php echo $thumbnail; ?>" alt="">
                                        </a>
                                    <?php endif; ?>
                                    <div class="blog__item-text">
                                        <h4 class="blog__item-date"><?php echo get_the_date( 'F j, Y' ); ?></h4>
                                        <a href="<?php echo get_the_permalink($item); ?>" class="blog__item-name"><?php echo get_the_title($item); ?></a>
                                        <?php if( !empty( $content ) ) : ?>
                                            <p class="blog__item-desc" ><?php echo $content; ?></p>
                                        <?php endif; ?>
                                        <a href="<?php echo get_the_permalink($item); ?>" class="blog__item-link">Read more</a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <a href="<?php echo $this->post_archive_link; ?>" class="article__view section__button">
                        View all
                    </a>
                </div>
            </section>

        <?php endif;

    }

}