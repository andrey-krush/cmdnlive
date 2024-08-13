<?php

class Front_Page_Slider_Section {

    public function __construct() {

        $slider_section = get_field('slider_section', get_option('page_on_front'));
        $this->ticker_text = $slider_section['ticker_text'];
        $this->slider = $slider_section['slider'];
    }

    public function render() {
        ?>

            <section class="main">
                <?php if( !empty( $this->ticker_text ) ) : ?>
                    <div class="main__news">
                        <marquee direction="left" scrollamount="5"><?php echo $this->ticker_text; ?></marquee>
                    </div>
                <?php endif; ?>
                <?php if( is_front_page() ) : ?>
                    <div class="main__back img">
                        <img src="<?=TEMPLATE_PATH?>/img/back-img.png" alt="">
                    </div>
                    <?php if( !empty( $this->slider ) ) : ?>
                        <div class="container">
                            <div class="main__block">
                                <div class="main__slider swiper-container">
                                    <div class="swiper-wrapper">
                                        <?php foreach( $this->slider as $item ) : ?>
                                            <?php $post_thumbnail = get_the_post_thumbnail_url( $item['post'] ); ?>
                                            <?php if( !empty( $post_thumbnail ) ) : ?>
                                                <div class="main__slide swiper-slide">
                                                    <div class="main__img">
                                                        <a class="img" href="<?php echo get_the_permalink( $item['post'] ); ?>"><img src="<?php echo $post_thumbnail; ?>" alt=""></a>
                                                    </div>
                                                    <?php if( $item['text_colour'] == 'white' ) : ?>
                                                        <div class="main__title main__title-white">
                                                            <h2><?php echo get_the_title($item['post']); ?></h2>
                                                        </div>
                                                    <?php elseif( $item['text_colour'] == 'black' ) : ?>
                                                        <div class="main__title main__title-black">
                                                            <h2><?php echo get_the_title($item['post']); ?></h2>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php if( count( $this->slider ) > 1 ) : ?>
                                    <div class="swiper-button-next main__next"></div>
                                    <div class="swiper-button-prev main__prev"></div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </section>

        <?php
    }

}