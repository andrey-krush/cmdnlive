<?php

class Single_Show_Recommended_Section {

    public function __construct() {

        $related_posts = get_field('related_posts');
        $this->title = $related_posts['title'];
        $this->posts = $related_posts['posts'];
        $this->blog_url = get_post_type_archive_link('post');

    }

    public function render() {
        if( !empty( $this->posts ) ) : ?>

            <section class="recommended">
                <div class="container">
                    <div class="article__recommended-flex" >
                        <?php if( !empty( $this->title ) ) : ?>
                            <h3 class="section__title"><?php echo $this->title; ?></h3>
                        <?php endif; ?>
                        <div class="article__recommended-btn">
                            <div class="swiper-button-prev main__prev main__prev-act "></div>
                            <div class="swiper-button-next main__next main__next-act "></div>
                        </div>
                    </div>
                    <div class="article__recommended-slider swiper-container">
                        <ul class="article__recommended-list swiper-wrapper">
                            <?php foreach( $this->posts as $item ) : ?>
                                <?php $post_thumbnail = get_the_post_thumbnail_url( $item ); ?>
                                <li class="blog__item swiper-slide" >
                                    <?php if( !empty( $post_thumbnail ) ) : ?>
                                        <a href="<?php echo get_the_permalink($item); ?>" class="blog__item-img img">
                                            <img src="<?php echo $post_thumbnail; ?>" alt="">
                                        </a>
                                    <?php endif; ?>
                                    <div class="blog__item-text">
                                        <h4 class="blog__item-date"><?php echo get_the_date( 'F m, Y' ); ?></h4>
                                        <a href="<?php echo get_the_permalink($item); ?>" class="blog__item-name"><?php echo get_the_title($item); ?></a>
                                        <p class="blog__item-desc"><?php echo strip_tags(apply_filters( 'the_content', get_the_content( post: $item ) )); ?></p>
                                        <a href="<?php echo get_the_permalink($item); ?>" class="blog__item-link">Read more</a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <a href="<?php echo $this->blog_url; ?>" class="article__view section__button ">
                        View all
                    </a>
                </div>
            </section>

        <?php endif;
    }

}