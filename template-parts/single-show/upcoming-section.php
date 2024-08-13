<?php

class Single_Show_Upcoming_Section {

    public function __construct() {

        $related_live_shows = get_field('related_live_shows');
        $this->title = $related_live_shows['title'];
        $this->shows = $related_live_shows['shows'];

    }

    public function render() {
        if( !empty( $this->shows ) ) : ?>

            <section class="upcoming">
                <div class="container">
                    <div class="article__recommended-flex" >
                        <h3 class="section__title"><?php echo $this->title; ?></h3>
                        <div class="article__recommended-btn">
                            <div class="swiper-button-prev main__prev main__prev-upcoming"></div>
                            <div class="swiper-button-next main__next main__next-upcoming"></div>
                        </div>
                    </div>
                    <div class="upcoming__block swiper">
                        <ul class="tickets__list swiper-wrapper">
                            <?php foreach( $this->shows as $item ) : ?>
                                <?php $show_info = get_field('show_info', $item); ?>
                                <?php $post_thumbnail = get_the_post_thumbnail_url($item); ?>
                                <?php $show_tags = get_terms([
                                    'taxonomy' => 'post_tag',
                                    'object_ids' => [$item]
                                ]); ?>
                                <?php if( !empty( $show_info['related_ticket'] ) ) :  ?>
                                    <?php $product = wc_get_product($show_info['related_ticket']); ?>
                                    <?php $ticket_info = get_field('ticket_info', $show_info['related_ticket']); ?>
                                <?php endif; ?>
                                <li class="swiper-slide tickets__item">
                                    <a href="<?php echo get_the_permalink($item); ?>">

                                        <div class="tickets__img img">
                                            <?php if( !empty( $show_tags ) ) : ?>
                                                <span class="tickets__flash"><?php echo $show_tags[0]->name; ?></span>
                                            <?php endif; ?>
                                            <?php if( !empty( $ticket_info['event_date'] ) ) : ?>
                                                <span class="tickets__flash tickets__date"><?php echo $ticket_info['event_date']; ?></span>
                                            <?php endif; ?>
                                            <?php if( !empty( $post_thumbnail ) ) : ?>
                                                <img src="<?php echo $post_thumbnail; ?>" alt="">
                                            <?php endif; ?>
                                        </div>
                                        <div class ="upcoming__block-flex">
                                            <?php if( !empty( $product ) ) : ?>
                                                <h4 class="tickets__price">
                                                    £ <?php echo $product->get_price(); ?>
                                                </h4>
                                            <?php endif; ?>
                                            <h3 class="tickets__name"><?php echo get_the_title($item); ?></h3>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="" type="button" class="upcoming__load section__button ">
                            View all
                        </a>
                    </div>
                </div>
            </section>

        <?php endif;
    }
}