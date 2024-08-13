<?php

class Bands_Page_Bands_Section {

    public function __construct() {

        $this->query = new WP_Query([
            'post_type' => 'band',
            'post_status' => 'publish',
            'posts_per_page' => 16,
            'order' => 'ASC',
            'orderby' => 'title',
            'paged' => 1
        ]);

        $this->title = get_field('promo_section')['title_under_categories'];

    }

    public function render() {
        if( $this->query->have_posts() ) : ?>

            <section class="bands">
            <div class="preloader__wrap">
                            <div class="preloader">
                                    <svg viewBox="0 0 102 102" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path class="big-circle" d="M101 51C101 78.6142 78.6142 101 51 101C23.3858 101 1 78.6142 1 51" stroke="#D98C72" stroke-width="2"/>
                                        <path class="small-circle" d="M91 51C91 28.9086 73.0914 11 51 11C28.9086 11 11 28.9086 11 51" stroke="#D98C72" stroke-width="2"/>
                                    </svg>
                            </div>
                    	</div>
                <div class="container">
                    <?php if( !empty( $this->title ) ) : ?>
                        <h2 class="section__title"><?php echo $this->title; ?></h2>
                    <?php endif; ?>
                    <div class="bands__sort">


                    <ul class="bands__list">
                        <?php while ( $this->query->have_posts() ) : $this->query->the_post(); ?>
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
                    <?php if( $this->query->max_num_pages > 1 ) : ?>
                        <button class="bands__load section__load section__button ">
                            LOAD MORE
                        </button>
                    <?php endif; ?>
                     </div>
                </div>
            </section>

        <?php endif;
    }

}