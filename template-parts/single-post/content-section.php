<?php

class Single_Post_Content_Section {

    public function __construct() {

        $popularity = get_post_meta( get_the_ID(), 'popularity', true );

        if( !empty( $popularity ) ) :
            $popularity = $popularity + 1;
            update_post_meta(get_the_ID(), 'popularity', $popularity);
        else :
            update_post_meta( get_the_ID(), 'popularity', 1 );
        endif;

        $post_id = get_the_ID();

        $this->title = get_the_title();
        $this->post_thumbnail = get_the_post_thumbnail_url();
        $this->content = apply_filters( 'the_content', get_the_content() );
        $this->permalink = get_the_permalink();
        $this->tags = get_the_terms( $post_id, 'post_tag' );

        $this->title_share = str_replace('&', '', $this->title);
        $this->title_share = str_replace('#038;', '', $this->title_share);
        $this->title_share = str_replace('&amp;', '', $this->title_share);
        $this->title_share = str_replace('#', '', $this->title_share);

        $author_id = get_post_field ('post_author', $post_id );
        $this->author_name = get_the_author_meta( 'display_name', $author_id );
        $this->author_photo = get_field('author_photo', 'user_' . $author_id);
    }

    public function render() {
        ?>

        <section class="article">
            <div class="container">
                <!--<div class="article__back">-->
                <!--    <button></button>-->
                <!--</div>-->
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
                    <div class="article__author ">
                        <?php if( !empty( $this->author_photo ) ) : ?>
                            <div class="article__author-img img">
                                <img src="<?php echo $this->author_photo; ?>" alt="">
                            </div>
                        <?php endif; ?>
                        <?php if( !empty( $this->author_name ) ) : ?>
                            <span class="article__author-name"><?php echo $this->author_name; ?></span>
                        <?php endif; ?>
                    </div>
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