<?php

class Single_Venue_Related_Shows {

    public function __construct() {

        $this->related_shows = get_field('venue_info')['related_shows'];
        $this->shows_page_link = ( new Page_Archive_Show())::get_url();

        $venue_id = get_the_ID();

        $venue_tickets = get_posts([
            'post_type' => 'product',
            'post_status' => 'publish',
            'numberposts' => -1,
            'tax_query' => [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => ['tickets']
                ]
            ],
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => 'ticket_info_venue',
                    'value' => $venue_id,
                ],
                [
                    'key' => 'ticket_info_event_date',
                    'value' => date('Ymd'),
                    'compare' => '>=',
                    'type' => 'NUMERIC'
                ]
            ]
        ]);

        if( !empty( $venue_tickets ) ) :

            $args = [
                'post_type' => 'show',
                'post_status' => 'publish',
                'numberposts' => -1,
            ];

            $args['meta_query']['relation'] = 'OR';

            foreach ( $venue_tickets as $item ) :

                $args['meta_query'][] = [
                    'key' => 'show_info_related_ticket',
                    'value' => $item->ID
                ];

            endforeach;

            $this->venue_shows = get_posts($args);

        endif;

    }

    public function render() {

        if( !empty( $this->venue_shows ) ) : ?>

            <section class="recommended related ">
                <div class="container">
                    <div class="related__container">
                        <div class="article__recommended-flex" >
                            <h3 class="section__title">Related Live Shows</h3>
                            <div class="article__recommended-btn">
                                <div class="swiper-button-prev main__prev"></div>
                                <div class="swiper-button-next main__next"></div>
                            </div>
                        </div>
                        <div class="related-slider swiper-container">
                            <ul class="tickets__list swiper-wrapper">
                                <?php foreach( $this->venue_shows as $item ) : ?>
                                    <?php $post_thumbnail = get_the_post_thumbnail_url($item->ID); ?>
                                    <?php $show_info = get_field('show_info', $item->ID); ?>
                                    <?php if( !empty( $show_info['related_ticket'] ) ) : ?>
                                        <?php $product = wc_get_product($show_info['related_ticket']); ?>
                                        <?php $post_info = get_field('post_info', $item->ID)?>
                                    <?php endif; ?>
                                    <?php $show_tags = get_terms([
                                        'taxonomy' => 'post_tag',
                                        'object_ids' => [$item->ID]
                                    ]); ?>
                                    <li class="swiper-slide tickets__item">
                                        <a href="<?php echo get_the_permalink($item->ID); ?>">
                                            <div class="tickets__img img">
                                                <?php if( !empty( $show_tags ) ) : ?>
                                                    <span class="tickets__flash"><?php echo $show_tags[0]->name; ?></span>
                                                <?php endif; ?>
                                                <?php if( !empty( $post_info['event_date'] ) ): ?>
                                                    <span class="tickets__flash tickets__date"><?php echo $post_info['event_date']; ?></span>
                                                <?php endif; ?>
                                                <?php if( !empty( $post_thumbnail ) ) : ?>
                                                    <img src="<?php echo $post_thumbnail; ?>" alt="">
                                                <?php endif; ?>
                                            </div>
                                            <?php if( !empty( $product ) ) : ?>
                                                <h4 class="tickets__price">
                                                    £ <?php echo $product->get_price(); ?>
                                                </h4>
                                            <?php endif; ?>
                                            <h3 class="tickets__name"><?php echo get_the_title($item->ID); ?></h3>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <a href="<?php echo $this->shows_page_link; ?>" class="related__view section__button">
                            View all
                        </a>
                    </div>
                </div>
            </section>

        <?php endif;
    }
}