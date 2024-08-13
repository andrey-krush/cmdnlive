<?php

class Archive_Venue_Venues_Section
{

    public function __construct()
    {

        $venues_section = get_field('venues_section');
        $this->title = $venues_section['title'];
        $this->text = $venues_section['text'];

        $args = [
            'post_type' => 'venue',
            'post_status' => 'publish',
            'posts_per_page' => 4
        ];

        $this->query = new WP_Query($args);

    }

    public function render()
    {
        ?>

        <section class="venues">
            <div class="container ">
                <form action="" class="filter__form">
                    <input type="text" hidden name="action" value="load_more_venues">
                    <input type="text" hidden name="page" value="1" class="page">
                </form>
                <div class="venues__container">
                    <?php if (!empty($this->title)) : ?>
                        <h1 class="section__title">
                            <?php echo $this->title; ?>
                        </h1>
                    <?php endif; ?>
                    <?php if (!empty($this->text)) : ?>
                        <div class="venues__text">
                            <?php echo $this->text; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($this->query->have_posts()) : ?>
                        <ul class="venues__list">
                            <?php while ($this->query->have_posts()) : $this->query->the_post(); ?>
                                <li class="venues__item">
                                    <?php $post_thumbnail = get_the_post_thumbnail_url(); ?>
                                    <?php if (!empty($post_thumbnail)) : ?>
                                        <div class="venues__img img">
                                            <img src="<?php echo $post_thumbnail; ?>" alt="">
                                        </div>
                                    <?php endif; ?>
                                    <h3 class="venues__title"><?php echo get_the_title(); ?></h3>
                                    <?php $content = strip_tags(apply_filters('the_content', get_the_content(post: get_the_ID()))); ?>
                                    <?php if (!empty($content)) : ?>
                                        <h4 class="venues__desc"><?php echo $content; ?></h4>
                                    <?php endif; ?>
                                    <a href="<?php echo get_the_permalink(); ?>" class="venues__link">Read more</a>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                        <?php if ($this->query->max_num_pages > 1) : ?>
                            <button class="venues__load section__load section__button">
                                Load more
                            </button>
                        <?php endif; ?>
                        <?php wp_reset_postdata(); ?>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <?php
    }
}