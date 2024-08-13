<?php

class Single_Band_Content_Section {

    public function __construct() {

        $post_id = get_the_ID();

        $this->title = get_the_title();

        $this->title_share = str_replace('&', '', $this->title);
        $this->title_share = str_replace('#038;', '', $this->title_share);
        $this->title_share = str_replace('&amp;', '', $this->title_share);
        $this->title_share = str_replace('#', '', $this->title_share);

        $this->content = apply_filters('the_content', get_the_content());
        $this->post_thumbnail = get_the_post_thumbnail_url();
        $this->permalink = get_the_permalink();
        $this->tags = get_the_terms( $post_id, 'band_tag' );

    }

    public function render() {
        ?>

        <section class="article">
            <div class="container">
<!--                <div class="article__back">-->
<!--                    <button></button>-->
<!--                </div>-->
                <div class="article__container">
                    <?php if( !empty( $this->post_thumbnail ) ) : ?>
                        <div class="article__img img">
                            <img src="<?php echo $this->post_thumbnail; ?>" alt="">
                        </div>
                    <?php endif; ?>
                    <div class="article__social-wrap">
                        <div class="article__social">
                            <span>Share</span>
                            <a href="https://www.instagram.com/?url=<?php echo $this->permalink; ?>" target="_blank" class="article__social-item img article__instagram">
                                <img src="<?=TEMPLATE_PATH?>/img/instagram.svg" alt="">
                            </a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $this->permalink; ?>" class="article__social-item img article__facebook">
                                <img src="<?=TEMPLATE_PATH?>/img/facebook.svg" alt="">
                            </a>
                            <a class="article__social-item img article__copy" data-copy="<?php echo $this->permalink; ?>">
                                <img src="<?=TEMPLATE_PATH?>/img/link.svg" alt="">
                                <span class="article__copy-mess">Click to copy</span>
                            </a>
                        </div>
                    </div>
                    <?php if( !empty( $this->tags ) ) : ?>
                        <div class="article__categories tickets__categories">
                            <?php foreach( $this->tags as $item ) : ?>
                                <span><?php echo $item->name; ?></span>
                            <?php endforeach; ?>
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