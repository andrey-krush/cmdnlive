<?php

class Tickets_Page
{

    public static function init_auto()
    {

        add_action('wp_ajax_show__tickets', [__CLASS__, 'show_tickets']);
        add_action('wp_ajax_nopriv_show__tickets', [__CLASS__, 'show_tickets']);

        add_action('init', [__CLASS__, 'acf_add_local_field_group']);

    }

    public static function get_url()
    {

        $page = get_pages([
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-tickets.php',
        ]);

        return ($page && 'publish' === $page[0]->post_status) ? get_the_permalink($page[0]->ID) : false;
    }

    public static function get_ID()
    {

        $page = get_pages([
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-tickets.php',
        ]);

        return ($page && 'publish' === $page[0]->post_status) ? $page[0]->ID : false;
    }

    public static function show_tickets()
    {

        $recommended_args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 9999
        ];

        $upcoming_args = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => 12,
            'paged' => $_POST['page']
        ];

        $meta_array = ['relation' => 'AND'];

        if (!empty($_POST['page'])) :
            $upcoming_args['paged'] = $_POST['page'];
        endif;

        if (!empty($_POST['date'])) :

            $date = explode('/', $_POST['date']);

            $meta_array[] =
                [
                    'key' => 'ticket_info_event_date',
                    'value' => $date[2] . $date[1] . $date[0],
                ];

        else :

            $meta_array[] =
                [
                    'key' => 'ticket_info_event_date',
                    'value' => date('Ymd'),
                    'compare' => '>=',
                    'type' => 'NUMERIC'
                ];

        endif;

        if (!empty($_POST['venue'])) :

            $meta_array[] =
                [
                    'key' => 'ticket_info_venue',
                    'value' => $_POST['venue']
                ];

        endif;

        if (!empty($_POST['categories'])) :

            $tax_array = [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => explode(',', $_POST['categories'])
                ]
            ];

        else :

            $tax_array = [
                [
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => ['tickets']
                ]
            ];

        endif;

        switch ($_POST['sort']) {

            case 'Latest' :
                $order_array['orderby'] = 'date';
                break;
            case 'Popular' :
                $order_array['orderby'] = 'meta_value_num';
                $order_array['meta_key'] = 'popularity';
                break;
            case 'High price to Low price' :
                $order_array['order'] = 'DESC';
                $order_array['meta_key'] = '_price';
                $order_array['orderby'] = 'meta_value_num';
                break;
            case 'Low price to High price' :
                $order_array['order'] = 'ASC';
                $order_array['meta_key'] = '_price';
                $order_array['orderby'] = 'meta_value_num';
                break;
            default:
                $order_array['orderby'] = 'meta_value_num';
                $order_array['meta_key'] = 'ticket_info_event_date';
                $order_array['order'] = 'ASC';
                break;

        }

        if ($_POST['page'] == 1) :

            $recommended_array[] = [
                'key' => 'ticket_info_recommended',
                'value' => '1'
            ];

            $recommended_array = array_merge($recommended_array, $meta_array);

            $recommended_args['meta_query'] = $recommended_array;
            $recommended_args['tax_query'] = $tax_array;
            $recommended_args = array_merge($recommended_args, $order_array);
            $recommended_query = new WP_Query($recommended_args);

            if ($recommended_query->have_posts()) : ?>

                <section class="recommended" data-found_posts="<?php echo $recommended_query->found_posts; ?>">
                    <div class="container">
                        <h2 class="section__title">Recommended</h2>
                        <ul class="tickets__list">
                            <?php while ($recommended_query->have_posts()) : $recommended_query->the_post(); ?>
                                <?php $post_id = get_the_ID(); ?>
                                <?php $product = wc_get_product($post_id); ?>
                                <li class="tickets__item">
                                    <a href="<?php echo get_the_permalink(); ?>">
                                        <div class="tickets__img img">
                                            <?php $product_cat = get_terms([
                                                'taxonomy' => 'product_cat',
                                                'object_ids' => [$post_id],
                                            ]); ?>
                                            <?php if (!empty($product_cat)) : ?>
                                                <span class="tickets__flash"><?php echo $product_cat[0]->name; ?></span>
                                            <?php endif; ?>
                                            <span class="tickets__flash tickets__date"><?php echo get_field('ticket_info', $post_id)['event_date']; ?></span>
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
                            <?php endwhile; ?>
                        </ul>
                    </div>
                </section>

            <?php endif;

        endif;

        $non_recommended_array[] = [
            'key' => 'ticket_info_recommended',
            'value' => '0'
        ];

        $non_recommended_array = array_merge($non_recommended_array, $meta_array);

        $upcoming_args = array_merge($upcoming_args, $order_array);
        $upcoming_args['tax_query'] = $tax_array;
        $upcoming_args['meta_query'] = $non_recommended_array;


        $upcoming_query = new WP_Query($upcoming_args);
        $counter = $upcoming_args['posts_per_page'];

        if ($upcoming_query->have_posts()) : ?>


            <?php if ($_POST['page'] == 1) : ?>

                <section class="upcoming">
                <div class="container">
                <h2 class="section__title">Upcoming</h2>

            <?php endif; ?>

            <div class="upcoming__block" data-found_posts="<?php echo $upcoming_query->found_posts; ?>">
                <ul class="tickets__list">
                    <?php while ($upcoming_query->have_posts()) : $upcoming_query->the_post(); ?>
                        <?php $counter++; ?>
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
                        <?php if ($counter == 6 + $upcoming_args['posts_per_page'] or (  ( $upcoming_query->max_num_pages == $_POST['page'] ) and ( $counter == $upcoming_query->found_posts ) and ( $counter < $upcoming_args['posts_per_page'] + 6 ) )) : ?>
                            <?php if ($counter % 3 == 2) : ?>
                                <li class="tickets__item"></li>
                            <?php elseif ($counter % 3 == 1) : ?>
                                <li class="tickets__item"></li>
                                <li class="tickets__item"></li>
                            <?php endif; ?>
                            <li class="tickets__item" style="width: 100%">
                                <?php (new Advertisement_Section((new Tickets_Page())::get_ID()))->render(); ?>
                            </li>
                        <?php endif; ?>
                    <?php endwhile; ?>
                </ul>
                <?php if ($upcoming_query->max_num_pages > $_POST['page']) : ?>
                    <button type="button" class="upcoming__load section__button section__load">
                        Load more
                    </button>
                <?php endif; ?>
            </div>

            <?php if ($_POST['page'] == 1) : ?>
                </div>
                </section>
            <?php endif; ?>

        <?php endif;
        wp_reset_postdata();

        die;
    }

    public static function acf_add_local_field_group()
    {
        if (!function_exists('acf_add_local_field_group')) {
            return;
        }

        acf_add_local_field_group(array(
            'key' => 'group_64f1cef8d63c8',
            'title' => 'Tickets page',
            'fields' => array(
                array(
                    'key' => 'field_64f1d4fd3253e',
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
                    'key' => 'field_64f1d50c3253f',
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
                            'key' => 'field_64f1d51832540',
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
                            'key' => 'field_64f1d52332541',
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
                    'key' => 'field_64f1cefa23eaa',
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
                    'key' => 'field_64f1cf1c23eab',
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
                            'key' => 'field_64f1d1ec23eac',
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
                            'key' => 'field_64f1d1f123ead',
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
                            'key' => 'field_64f1d1ff23eae',
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
                            'key' => 'field_64f1d20923eaf',
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
                        'value' => 'page-tickets.php',
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