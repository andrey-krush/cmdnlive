<?php

class Search_Page_Relevant_Posts {

    public function __construct() {

        if( isset( $_GET['search'] ) and !empty( $_GET['search'] ) ) :

            $args = [
                'post_type'      => 'post',
                'post_status'    => 'publish',
                'posts_per_page' => 6,
                's'              => $_GET['search']
            ];

            $this->query = new WP_Query($args);

        endif;

    }

    public function render() {

        if( isset( $this->query ) and $this->query->have_posts() ) : ?>

            <section class="blog">
                <div class="container">
                    <h2 class="section__title">Relevant posts</h2>
                    <div class="blog__block">
                        <ul class="blog__list">
                            <?php while( $this->query->have_posts() ) : $this->query->the_post(); ?>
                                <li class="blog__item" >
                                    <?php $post_thumbnail = get_the_post_thumbnail_url(); ?>
                                    <?php if( !empty( $post_thumbnail ) ) : ?>
                                        <a href="<?php echo get_the_permalink(); ?>" class="blog__item-img img">
                                            <!--<span class="blog__item-flash">Latest Posts</span>-->
                                            <img src="<?php echo $post_thumbnail; ?>" alt="">
                                        </a>
                                    <?php endif; ?>
                                    <div class="blog__item-text">
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
                    </div>
                </div>
            </section>

        <?php endif;

    }
}