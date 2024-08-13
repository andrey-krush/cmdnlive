<?php

class About_Page_Content_Section {

    public function __construct() {

        $post_id = get_the_ID();

        $this->title = get_the_title();
        $this->content = apply_filters('the_content', get_the_content());
        $this->post_thumbnail = get_the_post_thumbnail_url();
        $this->permalink = get_the_permalink();

    }

    public function render() {
        ?>

        <section class="article">
            <div class="container">
                <div class="article__container">
                    <?php if( !empty( $this->post_thumbnail ) ) : ?>
                        <div class="article__img img">
                            <img src="<?php echo $this->post_thumbnail; ?>" alt="">
                        </div>
                    <?php endif; ?>
                    <div class="article__content">
                        <h1><?php echo $this->title; ?></h1>
                        <?php if( !empty( $this->content ) ) : ?>
                            <?php echo $this->content; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}