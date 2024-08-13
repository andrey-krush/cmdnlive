<?php

class Search_Page_Relevant_Shows
{

    public function __construct()
    {

        if (isset($_GET['search']) and !empty($_GET['search'])) :

            $args = [
                'post_type' => 'show',
                'post_status' => 'publish',
                'posts_per_page' => 6,
                's' => $_GET['search'],
                'meta_query' => [
                    [
                        'key' => 'event_date',
                        'value' => date('Ymd'),
                        'compare' => '>=',
                        'type' => 'NUMERIC'
                    ]
                ]
            ];

            $this->show_query = new WP_Query($args);

        endif;

    }

    public function render()
    {
        if (isset($this->show_query) and $this->show_query->have_posts()) : ?>
            <section class="shows">
                <div class="container">
                    <h2 class="section__title">Relevant shows</h2>
                    <div class="shows__wrap">
                        <div class="shows__block">
                            <div class="shows__day-block">
                                <ul class="shows__list">
                                    <?php while ($this->show_query->have_posts()) : $this->show_query->the_post(); ?>
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
                        </div>
                    </div>
                </div>
            </section>
        <?php endif;
    }
}