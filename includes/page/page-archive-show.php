<?php

class Page_Archive_Show
{

    public static function init_auto()
    {

        add_action('init', [__CLASS__, 'acf_add_local_field_group']);

        add_action('wp_ajax_filter__shows-by-day', [__CLASS__, 'show_shows']);
        add_action('wp_ajax_nopriv_filter__shows-by-day', [__CLASS__, 'show_shows']);

    }

    public static function get_url()
    {
        $page = get_pages([
            'meta_key' => '_wp_page_template',
            'meta_value' => 'archive-show.php',
        ]);

        return ($page && 'publish' === $page[0]->post_status) ? get_the_permalink($page[0]->ID) : false;
    }

    public static function get_ID()
    {
        $page = get_pages([
            'meta_key' => '_wp_page_template',
            'meta_value' => 'archive-show.php',
        ]);

        return ($page && 'publish' === $page[0]->post_status) ? $page[0]->ID : false;
    }

    public static function show_shows()
    {

        $args = [
            'post_type' => 'show',
            'post_status' => 'publish',
            'posts_per_page' => 9999,
        ];

        if (str_contains($_POST['date'], '/')) :

            $date = explode('/', $_POST['date']);

            $args['meta_query'] = [
                [
                    'key' => 'event_date',
                    'value' => $date[2] . $date[1] . $date[0]
                ]
            ];

            $date_object = DateTime::createFromFormat('d/m/Y', $_POST['date']);

            $query = new WP_Query($args);

            if ($query->have_posts()) : ?>

                <div class="shows__day-block">
                    <div class="shows__day"><?php echo $date_object->format('d F, l'); ?></div>
                    <ul class="shows__list">
                        <?php while ($query->have_posts()) : $query->the_post(); ?>
                            <?php $product = ''; ?>
                            <?php $ticket_info = ''; ?>
                            <?php $post_id = get_the_ID(); ?>
                            <?php $post_link = get_the_permalink(); ?>
                            <?php $content = strip_tags(apply_filters('the_content', get_the_content(post: $post_id))); ?>
                            <?php $show_info = get_field('show_info', $post_id); ?>
                            <?php if (!empty($show_info['related_ticket'])) : ?>
                                <?php $ticket_info = get_field('ticket_info', $show_info['related_ticket']); ?>
                                <?php if (!empty($ticket_info['venue'])) : ?>
                                    <?php $venue_info = get_field('venue_info', $ticket_info['venue']); ?>
                                <?php endif; ?>
                                <?php $product = wc_get_product($show_info['related_ticket']); ?>
                            <?php endif; ?>
                            <li class="shows__item">
                                <?php $post_thumbnail = get_the_post_thumbnail_url(); ?>
                                <?php if (!empty($post_thumbnail)) : ?>
                                    <a href="<?php echo $post_link; ?>" class="shows__img img">
                                        <img src="<?php echo $post_thumbnail; ?>" alt="">
                                    </a>
                                <?php endif; ?>
                                <div class="shows__item-block">
                                    <div class="shows__text">
                                        <div class="shows__text-top">
                                            <a href="<?php echo $post_link; ?>"
                                               class="shows__name"><?php echo get_the_title(); ?></a>
                                            <?php if (!empty($product)) : ?>
                                                <h4 class="tickets__price">£<?php echo $product->get_price(); ?></h4>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($content)) : ?>
                                            <p class="shows__desc">
                                                <?php echo $content; ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if (!empty($ticket_info)): ?>
                                            <?php if (!empty($venue_info['location'])) : ?>
                                                <div class="shows__location shows__info">
                                                    <p><?php echo $ticket_info['location']['address']; ?></p>
                                                    <a class="shows__map"
                                                       data-lat="<?php echo $venue_info['location']['lat']; ?>"
                                                       data-lng="<?php echo $venue_info['location']['lng']; ?>"><?php echo get_the_title($ticket_info['venue']); ?></a>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($ticket_info['event_date'])) : ?>
                                                <?php $date = $ticket_info['event_date']; ?>
                                                <?php $date = DateTime::createFromFormat('d/m/Y', $date); ?>
                                                <div class="shows__date shows__info">
                                                    <p><?php echo $date->format('l'); ?></p>
                                                    <h4><?php echo $date->format('d F, Y'); ?></h4>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <div class='shows__link-wrap'>
                                            <a class="shows__link" href="<?php echo $post_link; ?>">Details</a>
                                            <a class="shows__cart" href="<?php echo $post_link; ?>"></a>
                                        </div>
                                    </div>

                                </div>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>

            <?php endif;

        else :

            $args['meta_query'] = [
                [
                    'key' => 'event_date',
                    'value' => $_POST['date']
                ]
            ];

            $first_query = new WP_Query($args);

            if ($first_query->have_posts()) :

                $first_date = DateTime::createFromFormat('Ymd', $_POST['date']);

            else :

                $date = DateTime::createFromFormat('Ymd', $_POST['date']);
                $date = $date->format('Y-m-d');

                for ($i = 1; $i <= 90; $i++) :

                    $date_object = new DateTime($date . '+' . $i . ' day');

                    $args = [
                        'post_type' => 'show',
                        'post_status' => 'publish',
                        'posts_per_page' => 9999,
                        'meta_query' => [
                            [
                                'key' => 'event_date',
                                'value' => $date_object->format('Ymd')
                            ]
                        ]
                    ];

                    $first_query = new WP_Query($args);

                    if ($first_query->have_posts()) :

                        $first_date = $date_object;
                        break;

                    endif;

                endfor;

            endif;


            if (isset($first_date)) :

                for ($i = 1; $i <= 90; $i++) :

                    $date_object = new DateTime($first_date->format('Y-m-d') . ' +' . $i . ' day');

                    $args = [
                        'post_type' => 'show',
                        'post_status' => 'publish',
                        'posts_per_page' => 9999,
                        'meta_query' => [
                            [
                                'key' => 'event_date',
                                'value' => $date_object->format('Ymd')
                            ]
                        ]
                    ];

                    $second_query = new WP_Query($args);

                    if ($second_query->have_posts()) :
                        $second_date = $date_object;
                        break;
                    endif;

                endfor;

            endif;

            if (isset($second_date)) :

                for ($i = 1; $i <= 90; $i++) :

                    $date_object = new DateTime($second_date->format('Y-m-d') . ' +' . $i . ' day');

                    $args = [
                        'post_type' => 'show',
                        'post_status' => 'publish',
                        'posts_per_page' => 9999,
                        'meta_query' => [
                            [
                                'key' => 'event_date',
                                'value' => $date_object->format('Ymd')
                            ]
                        ]
                    ];

                    $third_query = new WP_Query($args);

                    if ($third_query->have_posts()) :
                        $third_date = $date_object;
                        break;
                    endif;

                endfor;

            endif;

            if (isset($third_date)) :

                for ($i = 1; $i <= 90; $i++) :

                    $date_object = new DateTime($third_date->format('Y-m-d') . ' +' . $i . ' day');

                    $args = [
                        'post_type' => 'show',
                        'post_status' => 'publish',
                        'posts_per_page' => 9999,
                        'meta_query' => [
                            [
                                'key' => 'event_date',
                                'value' => $date_object->format('Ymd')
                            ]
                        ]
                    ];

                    $fourth_query = new WP_Query($args);

                    if ($fourth_query->have_posts()) :
                        $fourth_date = $date_object;
                        break;
                    endif;

                endfor;

            endif;


            if (isset($first_date) and $first_query->have_posts()) : ?>

                <div class="shows__day-block">
                    <div class="shows__day"><?php echo $first_date->format('d F, l'); ?></div>
                    <ul class="shows__list">
                        <?php while ($first_query->have_posts()) : $first_query->the_post(); ?>
                            <?php $product = ''; ?>
                            <?php $ticket_info = ''; ?>
                            <?php $post_id = get_the_ID(); ?>
                            <?php $post_link = get_the_permalink(); ?>
                            <?php $show_info = get_field('show_info', $post_id); ?>
                            <?php $content = strip_tags(apply_filters('the_content', get_the_content(post: $post_id))); ?>
                            <?php if (!empty($show_info['related_ticket'])) : ?>
                                <?php $ticket_link = get_the_permalink($show_info['related_ticket']); ?>
                                <?php $ticket_info = get_field('ticket_info', $show_info['related_ticket']); ?>
                                <?php if (!empty($ticket_info['venue'])) : ?>
                                    <?php $venue_info = get_field('venue_info', $ticket_info['venue']); ?>
                                <?php endif; ?>
                                <?php $product = wc_get_product($show_info['related_ticket']); ?>
                            <?php endif; ?>
                            <li class="shows__item">
                                <?php $post_thumbnail = get_the_post_thumbnail_url(); ?>
                                <?php if (!empty($post_thumbnail)) : ?>
                                    <a href="<?php echo $post_link; ?>" class="shows__img img">
                                        <img src="<?php echo $post_thumbnail; ?>" alt="">
                                    </a>
                                <?php endif; ?>
                                <div class="shows__item-block">
                                    <div class="shows__text">
                                        <div class="shows__text-top">
                                            <a href="<?php echo $post_link; ?>"
                                               class="shows__name"><?php echo get_the_title(); ?></a>
                                            <?php if (!empty($product)) : ?>
                                                <h4 class="tickets__price">£<?php echo $product->get_price(); ?></h4>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($content)) : ?>
                                            <p class="shows__desc">
                                                <?php echo $content; ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if (!empty($ticket_info)): ?>
                                            <?php if (!empty($venue_info['location'])) : ?>
                                                <div class="shows__location shows__info">
                                                    <p><?php echo $ticket_info['location']['address']; ?></p>
                                                    <a class="shows__map"
                                                       data-lat="<?php echo $venue_info['location']['lat']; ?>"
                                                       data-lng="<?php echo $venue_info['location']['lng']; ?>"><?php echo get_the_title($ticket_info['venue']); ?></a>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($ticket_info['event_date'])) : ?>
                                                <?php $date = $ticket_info['event_date']; ?>
                                                <?php $date = DateTime::createFromFormat('d/m/Y', $date); ?>
                                                <div class="shows__date shows__info">
                                                    <p><?php echo $date->format('l'); ?></p>
                                                    <h4><?php echo $date->format('d F, Y'); ?></h4>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <div class='shows__link-wrap'>
                                            <a class="shows__link"
                                               href="<?php echo $post_link; ?>">Details</a>
                                            <?php if( !empty( $ticket_link ) ) : ?>
                                                <a class="shows__cart" href="<?php echo $ticket_link; ?>"></a>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                </div>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>

            <?php endif;

            if (isset($second_date) and $second_query->have_posts()) : ?>

                <div class="shows__day-block">
                    <div class="shows__day"><?php echo $second_date->format('d F, l'); ?></div>
                    <ul class="shows__list">
                        <?php while ($second_query->have_posts()) : $second_query->the_post(); ?>
                            <?php $product = ''; ?>
                            <?php $ticket_info = ''; ?>
                            <?php $post_id = get_the_ID(); ?>
                            <?php $show_info = get_field('show_info', $post_id); ?>
                            <?php $post_link = get_the_permalink(); ?>
                            <?php $content = strip_tags(apply_filters('the_content', get_the_content(post: $post_id))); ?>
                            <?php if (!empty($show_info['related_ticket'])) : ?>
                                <?php $ticket_link = get_the_permalink($show_info['related_ticket']); ?>
                                <?php $ticket_info = get_field('ticket_info', $show_info['related_ticket']); ?>
                                <?php if (!empty($ticket_info['venue'])) : ?>
                                    <?php $venue_info = get_field('venue_info', $ticket_info['venue']); ?>
                                <?php endif; ?>
                                <?php $product = wc_get_product($show_info['related_ticket']); ?>
                            <?php endif; ?>
                            <li class="shows__item">
                                <?php $post_thumbnail = get_the_post_thumbnail_url(); ?>
                                <?php if (!empty($post_thumbnail)) : ?>
                                    <a href="<?php echo $post_link; ?>" class="shows__img img">
                                        <img src="<?php echo $post_thumbnail; ?>" alt="">
                                    </a>
                                <?php endif; ?>
                                <div class="shows__item-block">
                                    <div class="shows__text">
                                        <div class="shows__text-top">
                                            <a href="<?php echo $post_link; ?>"
                                               class="shows__name"><?php echo get_the_title(); ?></a>
                                            <?php if (!empty($product)) : ?>
                                                <h4 class="tickets__price">£<?php echo $product->get_price(); ?></h4>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($content)) : ?>
                                            <p class="shows__desc">
                                                <?php echo $content; ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if (!empty($ticket_info)): ?>
                                            <?php if (!empty($venue_info['location'])) : ?>
                                                <div class="shows__location shows__info">
                                                    <p><?php echo $ticket_info['location']['address']; ?></p>
                                                    <a class="shows__map"
                                                       data-lat="<?php echo $venue_info['location']['lat']; ?>"
                                                       data-lng="<?php echo $venue_info['location']['lng']; ?>"><?php echo get_the_title($ticket_info['venue']); ?></a>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($ticket_info['event_date'])) : ?>
                                                <?php $date = $ticket_info['event_date']; ?>
                                                <?php $date = DateTime::createFromFormat('d/m/Y', $date); ?>
                                                <div class="shows__date shows__info">
                                                    <p><?php echo $date->format('l'); ?></p>
                                                    <h4><?php echo $date->format('d F, Y'); ?></h4>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <div class='shows__link-wrap'>
                                            <a class="shows__link"
                                               href="<?php echo $post_link; ?>">Details</a>
                                            <?php if( !empty( $ticket_link ) ) : ?>
                                                <a class="shows__cart" href="<?php echo $ticket_link; ?>"></a>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                </div>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>

            <?php endif;

            if (isset($third_date) and $third_query->have_posts()) : ?>

                <div class="shows__day-block">
                    <div class="shows__day"><?php echo $third_date->format('d F, l'); ?></div>
                    <ul class="shows__list">
                        <?php while ($third_query->have_posts()) : $third_query->the_post(); ?>
                            <?php $product = ''; ?>
                            <?php $ticket_info = ''; ?>
                            <?php $post_id = get_the_ID(); ?>
                            <?php $show_info = get_field('show_info', $post_id); ?>
                            <?php $post_link = get_the_permalink(); ?>
                            <?php $content = strip_tags(apply_filters('the_content', get_the_content(post: $post_id))); ?>
                            <?php if (!empty($show_info['related_ticket'])) : ?>
                                <?php $ticket_link = get_the_permalink($show_info['related_ticket']); ?>
                                <?php $ticket_info = get_field('ticket_info', $show_info['related_ticket']); ?>
                                <?php if (!empty($ticket_info['venue'])) : ?>
                                    <?php $venue_info = get_field('venue_info', $ticket_info['venue']); ?>
                                <?php endif; ?>
                                <?php $product = wc_get_product($show_info['related_ticket']); ?>
                            <?php endif; ?>
                            <li class="shows__item">
                                <?php $post_thumbnail = get_the_post_thumbnail_url(); ?>
                                <?php if (!empty($post_thumbnail)) : ?>
                                    <a href="<?php echo $post_link; ?>" class="shows__img img">
                                        <img src="<?php echo $post_thumbnail; ?>" alt="">
                                    </a>
                                <?php endif; ?>
                                <div class="shows__item-block">
                                    <div class="shows__text">
                                        <div class="shows__text-top">
                                            <a href="<?php echo $post_link; ?>"
                                               class="shows__name"><?php echo get_the_title(); ?></a>
                                            <?php if (!empty($product)) : ?>
                                                <h4 class="tickets__price">£<?php echo $product->get_price(); ?></h4>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($content)) : ?>
                                            <p class="shows__desc">
                                                <?php echo $content; ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if (!empty($ticket_info)): ?>
                                            <?php if (!empty($venue_info['location'])) : ?>
                                                <div class="shows__location shows__info">
                                                    <p><?php echo $ticket_info['location']['address']; ?></p>
                                                    <a class="shows__map"
                                                       data-lat="<?php echo $venue_info['location']['lat']; ?>"
                                                       data-lng="<?php echo $venue_info['location']['lng']; ?>"><?php echo get_the_title($ticket_info['venue']); ?></a>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (!empty($ticket_info['event_date'])) : ?>
                                                <?php $date = $ticket_info['event_date']; ?>
                                                <?php $date = DateTime::createFromFormat('d/m/Y', $date); ?>
                                                <div class="shows__date shows__info">
                                                    <p><?php echo $date->format('l'); ?></p>
                                                    <h4><?php echo $date->format('d F, Y'); ?></h4>
                                                </div>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <div class='shows__link-wrap'>
                                            <a class="shows__link"
                                               href="<?php echo $post_link; ?>">Details</a>
                                            <?php if( !empty( $ticket_link ) ) : ?>
                                                <a class="shows__cart" href="<?php echo $ticket_link; ?>"></a>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                </div>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>

            <?php endif;

            if (isset($fourth_date)) : ?>
                <button data-date="<?php echo $fourth_date->format('Ymd'); ?>" class="shows__load section__button">Load
                    more
                </button>
            <?php endif;

        endif;

        die;

    }

    public static function acf_add_local_field_group()
    {

        if (!function_exists('acf_add_local_field_group')) {
            return;
        }

        acf_add_local_field_group(array(
            'key' => 'group_6501bcbbd45f4',
            'title' => 'Shows',
            'fields' => array(
                array(
                    'key' => 'field_6501bcbdeca02',
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
                    'key' => 'field_6501bccfeca03',
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
                            'key' => 'field_6501bcdceca04',
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
                            'key' => 'field_6501bce3eca05',
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
                    'key' => 'field_6501c11bb0b44',
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
                    'key' => 'field_6501c12bb0b45',
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
                            'key' => 'field_6501c136b0b46',
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
                            'key' => 'field_6501c146b0b47',
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
                            'media_upload' => 0,
                            'delay' => 0,
                        ),
                        array(
                            'key' => 'field_6501c154b0b48',
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
                            'key' => 'field_6501c168b0b49',
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
                        'value' => 'archive-show.php',
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