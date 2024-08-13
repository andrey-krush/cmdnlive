<?php

class Archive_Show_Show_Section
{

    public function __construct()
    {

        $timestamp = strtotime('now');
        $date_object = new DateTime('@' . $timestamp);

        $today_args = [
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

        $this->date_query = new WP_Query($today_args);

        if (!$this->date_query->have_posts()) :


            for ($i = 1; $i <= 90; $i++) :

                $timestamp = strtotime('+' . $i . ' day');
                $date_object = new DateTime('@' . $timestamp);

                $date_args = [
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

                $this->date_query = new WP_Query($date_args);

                if ($this->date_query->have_posts()) :

                    $first_date = $date_object;
                    break;

                endif;

            endfor;

        else :

            $first_date = $date_object;

        endif;

        if (isset($first_date)) :

            $this->first_date_data = $first_date->format('Ymd');

            $this->first_date_text = $first_date->format('d F, l');

            for ($i = 1; $i <= 90; $i++) :

                $date_object = new DateTime($first_date->format('Y-m-d') . '+' . $i . ' day');

                $date_args = [
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

                $this->second_date_query = new WP_Query($date_args);

                if ($this->second_date_query->have_posts()) :

                    $second_date = $date_object;
                    break;

                endif;

            endfor;

        endif;

        if (isset($second_date)) :

            $this->second_date_text = $second_date->format('d F, l');

            for ($i = 1; $i <= 90; $i++) :

                $date_object = new DateTime($second_date->format('Y-m-d') . '+' . $i . ' day');

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

                $this->third_date_query = new WP_Query($args);

                if ($this->third_date_query->have_posts()) :

                    $third_date = $date_object;
                    break;

                endif;

            endfor;

        endif;

        if (isset($third_date)) :

            $this->third_date_text = $third_date->format('d F, l');

            for ($i = 1; $i <= 90; $i++) :

                $date_object = new DateTime($third_date->format('Y-m-d') . '+' . $i . ' day');

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

                $this->fourth_date_query = new WP_Query($args);

                if ($this->fourth_date_query->have_posts()) :

                    $this->fourth_date = $date_object;
                    break;

                endif;

            endfor;

        endif;

    }

    public function render()
    {
        ?>

        <section class="shows" data-today="<?php echo $this->first_date_data; ?>">
            <div class="container">
                <div class="shows__calendar ">
                    <form action="" class="filter__form">
                        <input type="text" name="date" class="filter__datepicker" value="" placeholder="DATE">
                        <input type="text" hidden name="action" value="filter__shows-by-day">
                        <div class="filter__reset img">
                                           <img src="https://dev.cmdn.sheep.fish/wp-content/themes/cmdn/img/reset-filter.svg" alt="">
                        </div>
                    </form>
                </div>
                <h2 class="section__title">CMDN Live Shows</h2>
                <div class="shows__wrap">
                    <div class="shows__block">
                        <?php if (isset($this->first_date_text) and $this->date_query->have_posts()) : ?>
                            <div class="shows__day-block">
                                <div class="shows__day"><?php echo $this->first_date_text; ?></div>
                                <ul class="shows__list">
                                    <?php while ($this->date_query->have_posts()) : $this->date_query->the_post(); ?>
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
                                                            <h4 class="tickets__price">
                                                                £<?php echo $product->get_price(); ?></h4>
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
                        <?php endif; ?>
                        <?php if (isset($this->second_date_text) and $this->second_date_query->have_posts()) : ?>
                            <div class="shows__day-block">
                                <div class="shows__day"><?php echo $this->second_date_text; ?></div>
                                <ul class="shows__list">
                                    <?php while ($this->second_date_query->have_posts()) : $this->second_date_query->the_post(); ?>
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
                                                            <h4 class="tickets__price">
                                                                £<?php echo $product->get_price(); ?></h4>
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
                                                        <?php endif; ?></a>
                                                    </div>
                                                </div>

                                            </div>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <?php if (isset($this->third_date_text) and $this->third_date_query->have_posts()) : ?>
                            <div class="shows__day-block">
                                <div class="shows__day"><?php echo $this->third_date_text; ?></div>
                                <ul class="shows__list">
                                    <?php while ($this->third_date_query->have_posts()) : $this->third_date_query->the_post(); ?>
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
                                                            <h4 class="tickets__price">
                                                                £<?php echo $product->get_price(); ?></h4>
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
                        <?php endif; ?>
                        <?php if (isset($this->fourth_date)) : ?>
                            <button data-date="<?php echo $this->fourth_date->format('Ymd'); ?>"
                                    class="shows__load section__button">Load more
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}