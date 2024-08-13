<?php

class Page_Clothes_Products_Section {

    public function __construct() {

        $clothes_main_category = get_term_by('slug', 'clothes', 'product_cat');

        $this->secondary_terms = get_terms([
            'taxonomy' => 'product_cat',
            'parent' => $clothes_main_category->term_id,
            'hide_empty' => true
        ]);

        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 12,
            'tax_query' => [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => [ 'clothes' ]
                ]
            ]
        ];

        $this->query = new WP_Query($args);

    }

    public function render() {
        ?>

        <section class="shop" >
            <div class="preloader__wrap">
                <div class="preloader">
                    <svg viewBox="0 0 102 102" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path class="big-circle" d="M101 51C101 78.6142 78.6142 101 51 101C23.3858 101 1 78.6142 1 51"
                              stroke="#D98C72" stroke-width="2"/>
                        <path class="small-circle" d="M91 51C91 28.9086 73.0914 11 51 11C28.9086 11 11 28.9086 11 51"
                              stroke="#D98C72" stroke-width="2"/>
                    </svg>
                </div>
            </div>

            <div class="container">

                <h1 class="section__title">
                    shop
                </h1>
                <div class="filter__mob-wrap ">
                    <form class="filter__form shop__form">
                        <div class="filter__mob-header">
                            <a href="" class="filter__mob-logo img">
                                <img src="<?= TEMPLATE_PATH ?>/img/logo.svg" alt="">
                            </a>
                            <div class="filter__mob-close"></div>
                        </div>
                        <div class="filter__mob">
                            <button type="button" class="filter__mob-event">
                                Filter
                            </button>
                        </div>
                        <div class="tickets__filter filter shop__filter">
                            <div class="filter__item filter__sort filter__dropdown">
                                <input type="text" class="filter__sort-input" name="sort" hidden>
                                <div class="filter__header">Sort by</div>
                                <div class="filter__inner">
                                    <div class="filter__inner-item">Latest</div>
                                    <div class="filter__inner-item">Low price to High price</div>
                                    <div class="filter__inner-item">High price to Low price</div>
                                    <div class="filter__inner-item">Popular</div>
                                    <!--<div class="filter__inner-item">Recommended</div>-->
                                </div>
                            </div>
                        </div>
                        <input type="text" hidden name="action" value="filter_clothes">
                        <input type="text" hidden name="categories" value="" class="categories__input">
                        <input type="text" hidden name="page" value="1" class="page">
                        <div class="filter__mob-button">
                            <button type="submit" class="section__button filter__mob-show"></button>
                            <button type="reset" class="filter__mob-reset">Reset changes</button>
                        </div>
                    </form>

                </div>
                <?php if (!empty($this->secondary_terms)) : ?>
                    <div class="shop__categories tickets__categories">
                        <?php foreach ($this->secondary_terms as $item) : ?>
                            <a data-category_slug="<?php echo $item->slug; ?>">
                                <?php $thumbnail_id = get_term_meta($item->term_id, 'thumbnail_id', true); ?>
                                <?php if (!empty($thumbnail_id)) : ?>
                                    <div class="shop__categories-img img">
                                        <img src="<?php echo wp_get_attachment_url($thumbnail_id); ?>" alt="">
                                    </div>
                                <?php endif; ?>
                                <div class="tickets__categories-name"><?php echo $item->name; ?></div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if ($this->query->have_posts()) : ?>
                    <div class="shop__block">
                        <ul class="shop__list" data-posts_count="<?php echo $this->query->found_posts; ?>" >
                            <?php while ($this->query->have_posts()) : $this->query->the_post(); ?>
                                <li class="shop__item">
                                    <?php $product = wc_get_product(get_the_ID()); ?>
                                    <?php $post_thumbnail = get_the_post_thumbnail_url(); ?>
                                    <?php if (!empty($post_thumbnail)) : ?>
                                        <a href="<?php echo get_the_permalink(); ?>" class="shop__img img"><img
                                                    src="<?php echo $post_thumbnail; ?>" alt=""></a>
                                    <?php endif; ?>
                                    <div class="shop__desc">
                                        <h3 class="shop__title"><?php echo get_the_title(); ?></h3>
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
                                        <a class="shop__link" href="<?php echo get_the_permalink(); ?>">Details</a>
                                    </div>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                        <?php if ($this->query->max_num_pages > 1) : ?>
                            <button class="section__button section__load shop__load">
                                load more
                            </button>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <?php
    }

}