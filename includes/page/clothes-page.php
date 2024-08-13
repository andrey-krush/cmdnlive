<?php

class Clothes_Page
{

    public static function init_auto()
    {

        add_action('wp_ajax_filter_clothes', [__CLASS__, 'filter_clothes']);
        add_action('wp_ajax_nopriv_filter_clothes', [__CLASS__, 'filter_clothes']);
        add_action('init', [__CLASS__, 'acf_add_local_field_group']);

    }

    public static function get_url()
    {
        $page = get_pages([
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-clothes.php',
        ]);

        return ($page && 'publish' === $page[0]->post_status) ? get_the_permalink($page[0]->ID) : false;
    }

    public static function get_ID()
    {
        $page = get_pages([
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-clothes.php',
        ]);

        return ($page && 'publish' === $page[0]->post_status) ? $page[0]->ID : false;
    }

    public static function filter_clothes()
    {

        $args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 12,
            'paged' => $_POST['page'],
            'tax_query' => [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => ['clothes']
                ]
            ]
        ];

        if (!empty($_POST['categories'])) :

            $categories = explode(',', $_POST['categories']);

            $args['tax_query'] = [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $categories
                ]
            ];

        endif;

        switch ($_POST['sort']) {

            case 'Latest' :
                $args['orderby'] = 'date';
                break;
            case 'Popular' :
                $args['orderby'] = 'meta_value_num';
                $args['meta_key'] = 'popularity';
                break;
            case 'High price to Low price' :
                $args['order'] = 'DESC';
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
                break;
            case 'Low price to High price' :
                $args['order'] = 'ASC';
                $args['meta_key'] = '_price';
                $args['orderby'] = 'meta_value_num';
                break;
        }

        $query = new WP_Query($args);

        if ($query->have_posts()) : ?>

            <ul class="shop__list" data-posts_count="<?php echo $query->found_posts; ?>">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
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
            <?php if ($query->max_num_pages > $_POST['page']) : ?>
                <button class="section__button section__load shop__load">
                    load more
                </button>
            <?php endif; ?>
            <?php wp_reset_postdata(); ?>
        <?php endif;

        die;
    }

    public static function acf_add_local_field_group()
    {
        if (!function_exists('acf_add_local_field_group')) {
            return;
        }

        acf_add_local_field_group(array(
            'key' => 'group_64ef43234b99a',
            'title' => 'Clothes page',
            'fields' => array(
                array(
                    'key' => 'field_64ef43245757f',
                    'label' => 'Promo section',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_64ef457457580',
                    'label' => '',
                    'name' => 'promo_section',
                    'aria-label' => '',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_64ef457f57581',
                            'label' => 'Text',
                            'name' => 'text',
                            'aria-label' => '',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                        array(
                            'key' => 'field_64ef458557582',
                            'label' => 'Background image',
                            'name' => 'background_image',
                            'aria-label' => '',
                            'type' => 'image',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'url',
                            'library' => 'all',
                            'min_width' => '',
                            'min_height' => '',
                            'min_size' => '',
                            'max_width' => '',
                            'max_height' => '',
                            'max_size' => '',
                            'mime_types' => '',
                            'preview_size' => 'medium',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_64f07b13f9d0b',
                    'label' => 'Advertisement section',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_64f07b2af9d0c',
                    'label' => '',
                    'name' => 'advertisement_section',
                    'aria-label' => '',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_64f07b81f9d0d',
                            'label' => 'Title',
                            'name' => 'title',
                            'aria-label' => '',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                        array(
                            'key' => 'field_64f07b87f9d0e',
                            'label' => 'Text',
                            'name' => 'text',
                            'aria-label' => '',
                            'type' => 'wysiwyg',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'tabs' => 'all',
                            'toolbar' => 'full',
                            'media_upload' => 1,
                            'delay' => 0,
                        ),
                        array(
                            'key' => 'field_64f07b94f9d0f',
                            'label' => 'Image',
                            'name' => 'image',
                            'aria-label' => '',
                            'type' => 'image',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'url',
                            'library' => 'all',
                            'min_width' => '',
                            'min_height' => '',
                            'min_size' => '',
                            'max_width' => '',
                            'max_height' => '',
                            'max_size' => '',
                            'mime_types' => '',
                            'preview_size' => 'medium',
                        ),
                        array(
                            'key' => 'field_64f07b9cf9d10',
                            'label' => 'Link',
                            'name' => 'link',
                            'aria-label' => '',
                            'type' => 'link',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'page_template',
                        'operator' => '==',
                        'value' => 'page-clothes.php',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ));
    }
}