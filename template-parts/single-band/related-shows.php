<?php

class Single_Band_Related_Shows {

    public function __construct() {

        $this->related_shows = get_field('post_info')['related_shows'];
        $this->tickets_page_url = ( new Page_Archive_Show())::get_url();

        $band_id = get_the_ID();

        $band_tickets = get_posts([
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
                    'key' => 'bands_section_bands',
                    'value' => '"'. $band_id . '"',
                    'compare' => 'LIKE'
                ],
                [
                    'key' => 'ticket_info_event_date',
                    'value' => date('Ymd'),
                    'compare' => '>=',
                    'type' => 'NUMERIC'
                ]
            ]
        ]);

        if( !empty( $band_tickets ) ) :

            $args = [
                'post_type' => 'show',
                'post_status' => 'publish',
                'numberposts' => -1,
            ];

            $args['meta_query']['relation'] = 'OR';

            foreach ( $band_tickets as $item ) :

                $args['meta_query'][] = [
                    'key' => 'show_info_related_ticket',
                    'value' => $item->ID
                ];

            endforeach;

            $this->band_shows = get_posts($args);

        endif;

    }

    public function render() {
        if( !empty( $this->band_shows ) ) : ?>

            <section class="upcoming">
                <div class="container">
                    <div class="article__recommended-flex" >
                        <h3 class="section__title">Upcoming shows</h3>
                        <div class="article__recommended-btn">
                            <div class="swiper-button-prev main__prev main__prev-upcoming"></div>
                            <div class="swiper-button-next main__next main__next-upcoming"></div>
                        </div>
                    </div>
                    <div class="upcoming__block swiper">
                        <ul class="tickets__list swiper-wrapper">
                            <?php foreach( $this->band_shows as $item ) : ?>
                                <?php $show_info = get_field('show_info', $item->ID); ?>
                                <?php $post_thumbnail = get_the_post_thumbnail_url($item->ID); ?>
                                <?php $show_date = get_post_meta($item->ID, 'event_date', true); ?>
                                <?php if( !empty( $show_info['related_ticket'] ) ) : ?>
                                    <?php $product = wc_get_product($show_info['related_ticket']); ?>
                                <?php endif; ?>
                                <?php $categories = get_terms([
                                        'taxonomy' => 'post_tag',
                                        'object_ids' => [$item->ID]
                                ]);?>
                                <li class="swiper-slide tickets__item">
                                    <a href="<?php echo get_the_permalink($item->ID); ?>">
                                        <div class="tickets__img img">
                                            <?php if( !empty( $categories ) ) : ?>
                                                <span class="tickets__flash"><?php echo $categories[0]->name; ?></span>
                                            <?php endif; ?>
                                            <?php if( !empty( $show_date ) ): ?>
                                                <?php $show_date = DateTime::createFromFormat('Ymd', $show_date); ?>
                                                <?php $show_date = $show_date->format('d/m/Y'); ?>
                                                <span class="tickets__flash tickets__date"><?php echo $show_date; ?></span>
                                            <?php endif; ?>
                                            <?php if( !empty( $post_thumbnail ) ) : ?>
                                                <img src="<?php echo $post_thumbnail; ?>" alt="">
                                            <?php endif; ?>
                                        </div>
                                        <div class ="upcoming__block-flex">
                                             <?php if( isset($product) ) : ?>
                                                <h4 class="tickets__price">
                                                    £ <?php echo $product->get_price(); ?>
                                                </h4>
                                             <?php endif; ?>
                                             <h3 class="tickets__name"><?php echo get_the_title($item->ID); ?></h3>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                        <a href="<?php echo $this->tickets_page_url; ?>" type="button" class="upcoming__load section__button ">
                            View all
                        </a>
                    </div>
                </div>
            </section>

        <?php endif;
    }
}