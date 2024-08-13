<?php

class Page_Tickets_Upcoming_Tickets
{

    public function __construct()
    {

        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 12,
            'orderby' => 'meta_value_num',
            'meta_key' => 'ticket_info_event_date',
            'order' => 'ASC',
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
                    'key' => 'ticket_info_recommended',
                    'value' => '0'
                ],
                [
                    'key' => 'ticket_info_event_date',
                    'value' => date('Ymd'),
                    'compare' => '>=',
                    'type' => 'NUMERIC'
                ]
            ]
        ];

        $this->query = new WP_Query($args);
        $this->counter = 1;
    }

    public function render()
    {
        if ($this->query->have_posts()) : ?>

            <section class="upcoming" data-found_posts="<?php echo $this->query->found_posts; ?>">
                <div class="container">
                    <h2 class="section__title">Upcoming</h2>
                    <div class="upcoming__block">
                        <ul class="tickets__list">
                            <?php while ($this->query->have_posts()) : $this->query->the_post(); ?>
                                <?php $post_id = get_the_ID(); ?>
                                <?php $product = wc_get_product($post_id); ?>
                                <li class="tickets__item">
                                    <a href="<?php echo get_the_permalink(); ?>">
                                        <div class="tickets__img img">
                                            <?php ?>
                                            <?php $product_cat = get_terms([
                                                'taxonomy' => 'product_cat',
                                                'object_ids' => [$post_id],
                                            ]); ?>
                                            <?php if (!empty($product_cat)) : ?>
                                                <span class="tickets__flash"><?php echo $product_cat[0]->name; ?></span>
                                            <?php endif; ?>
                                            <span class="tickets__flash tickets__date"><?php echo get_field('ticket_info')['event_date']; ?></span>
                                            <?php $post_thumbnail = get_the_post_thumbnail_url(); ?>
                                            <?php if (!empty($post_thumbnail)) : ?>
                                                <img src="<?php echo $post_thumbnail; ?>" alt="">
                                            <?php endif; ?>
                                        </div>
                                        <div class="tickets__price-wrap">

                                            <?php if( $product->is_type('simple') ) : ?>
                                                <?php if ($product->is_on_sale()) : ?>
                                                    <div class="tickets__price-flex">
                                                        <h4 class="tickets__price">
                                                            £ <?php echo $product->get_sale_price(); ?>
                                                        </h4>
                                                        <h4 class="tickets__price-old">
                                                            £ <?php echo $product->get_regular_price(); ?>
                                                        </h4>
                                                    </div>
                                                <?php else : ?>
                                                    <h4 class="tickets__price">
                                                        £ <?php echo $product->get_price(); ?>
                                                    </h4>
                                                <?php endif; ?>
                                            <?php elseif( $product->is_type('variable') ) : ?>
                                                <?php if ($product->is_on_sale()) : ?>
                                                    <?php $display_price = ''; ?>
                                                    <?php $variations = $product->get_available_variations(); ?>
                                                    <?php $regular_price = intval($product->get_variation_regular_price('min')); ?>
                                                    <?php foreach ( $variations as $item ) : ?>
                                                        <?php if( $item['display_regular_price'] == $regular_price and ( $item['display_price'] != $regular_price ) ) :  ?>
                                                            <?php $display_price = $item['display_price']; ?>
                                                            <?php break; ?>
                                                        <?php endif;  ?>
                                                    <?php endforeach; ?>
                                                    <?php if( empty(  $display_price) ) : ?>
                                                        <h4 class="tickets__price">
                                                            £ <?php echo $regular_price; ?>
                                                        </h4>
                                                    <?php else : ?>
                                                        <div class="tickets__price-flex">
                                                            <h4 class="tickets__price">
                                                                £ <?php echo $display_price; ?>
                                                            </h4>
                                                            <h4 class="tickets__price-old">
                                                                £ <?php echo $regular_price; ?>
                                                            </h4>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php else : ?>
                                                    <h4 class="tickets__price">
                                                        £ <?php echo intval( $product->get_variation_regular_price('min' )); ?>
                                                    </h4>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                        <h3 class="tickets__name"><?php echo get_the_title(); ?></h3>
                                    </a>
                                </li>
                                <?php if ($this->counter == 6 or ($this->query->max_num_pages == 1 and $this->counter == $this->query->found_posts and $this->counter < 6)) : ?>
                                    <?php if ($this->counter % 3 == 2) : ?>
                                        <li class="tickets__item"></li>
                                    <?php elseif ($this->counter % 3 == 1) : ?>
                                        <li class="tickets__item"></li>
                                        <li class="tickets__item"></li>
                                    <?php endif; ?>
                                    <li class="tickets__item" style="width: 100%">
                                        <?php (new Advertisement_Section((new Tickets_Page())::get_ID()))->render(); ?>
                                    </li>
                                <?php endif; ?>
                                <?php $this->counter++; ?>
                            <?php endwhile; ?>
                        </ul>
                        <?php if ($this->query->max_num_pages > 1) : ?>
                            <button type="button" class="upcoming__load section__button section__load">
                                Load more
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

        <?php endif;
    }
}