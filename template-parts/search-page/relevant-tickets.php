<?php

class Search_Page_Relevant_Tickets {

    public function __construct() {

        if( isset( $_GET['search'] ) ) :
            $args = [
                'post_type'   => 'product',
                'post_status' => 'publish',
                'numberposts' => -1,
                's'           => $_GET['search'],
                'orderby'     => 'meta_value_num',
                'meta_key'    => 'ticket_info_event_date',
                'order'       => 'ASC',
                'tax_query' => [
                    [
                        'taxonomy' => 'product_cat',
                        'field'     => 'slug',
                        'terms'    => ['tickets']
                    ]
                ],
                'meta_query' => [
                    [
                        'key'     => 'ticket_info_event_date',
                        'value'   => date('Ymd'),
                        'compare' => '>=',
                        'type'    => 'NUMERIC'
                    ]
                ]
            ];

            $this->posts = get_posts($args);

        endif;

    }

    public function render() {
        if( isset( $this->posts ) and !empty( $this->posts ) ) : ?>

            <section class="recommended">
                <div class="container">
                    <h2 class="section__title">Relevant tickets</h2>
                    <ul class="tickets__list">
                        <?php foreach( $this->posts as $item ) : ?>
                            <?php $product = wc_get_product($item->ID); ?>
                            <li class="tickets__item">
                                <a href="<?php echo get_the_permalink($item->ID); ?>">
                                    <div class="tickets__img img">
                                        <?php $product_cat = get_terms([
                                            'taxonomy'   => 'product_cat',
                                            'object_ids' => [$item->ID],
                                        ]); ?>
                                        <?php if( !empty( $product_cat ) ) : ?>
                                            <span class="tickets__flash"><?php echo $product_cat[0]->name; ?></span>
                                        <?php endif; ?>
                                        <span class="tickets__flash tickets__date"><?php echo get_field('ticket_info', $item->ID)['event_date']; ?></span>
                                        <?php $post_thumbnail = get_the_post_thumbnail_url($item->ID); ?>
                                        <?php if( !empty( $post_thumbnail ) ) : ?>
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
                                                <?php foreach ( $variations as $subitem ) : ?>
                                                    <?php if( $subitem['display_regular_price'] == $regular_price and ( $subitem['display_price'] != $regular_price ) ) :  ?>
                                                        <?php $display_price = $subitem['display_price']; ?>
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
                                    <h3 class="tickets__name"><?php echo get_the_title($item->ID); ?></h3>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </section>

        <?php endif;

    }

}