<?php

class Search_Page_Relevant_Groups {

    public function __construct() {

        if( isset( $_GET['search'] ) and !empty( $_GET['search'] ) ) :

            $args = [
                'post_type' => 'band',
                'post_status' => 'publish',
                'posts_per_page' => 4,
                's' => $_GET['search']
            ];

            $this->bands_query = new WP_Query($args);

        endif;

    }


    public function render() {
        if( isset( $this->bands_query ) and $this->bands_query->have_posts() ) : ?>

            <section class="music">
                <div class="container">
                <h2 class="section__title">Relevant groups</h2>
                    <div class="music__block">
                        <ul class="music__list">
                            <?php while ($this->bands_query->have_posts()) : $this->bands_query->the_post(); ?>
                                <li class="music__item">
                                    <?php $post_thumbnail = get_the_post_thumbnail_url(); ?>
                                    <?php if (!empty($post_thumbnail)) : ?>
                                        <div class="music__img img">
                                            <img src="<?php echo $post_thumbnail; ?>" alt="">
                                        </div>
                                    <?php endif; ?>
                                    <div class="music__text">
                                        <div class="music__desc">
                                              <h3 class="music__name"><?php echo get_the_title(); ?></h3>
                                            <p><?php echo strip_tags(apply_filters('the_content', get_the_content( post: get_the_ID() ))); ?></p>                                        </div>
                                        <a class="music__link" href="<?php echo get_the_permalink(); ?>">Read more</a>
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