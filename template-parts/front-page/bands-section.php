<?php

class Front_Page_Bands_Section
{

    public function __construct()
    {

        $bands_section = get_field('bands_section');
        $this->title = $bands_section['title'];
        $this->subtitle = $bands_section['subtitle'];
        $this->bands = $bands_section['bands'];
        $this->button = $bands_section['button'];

    }

    public function render() {
    ?>
            <section class="music">
                <div class="music__back">
                    <img src="<?= TEMPLATE_PATH ?>/img/discover-back.svg" alt="">
                </div>

                <div class="container">
                    <?php if (!empty($this->title)) : ?>
                        <h2 class="section__title"><?php echo $this->title; ?></h2>
                    <?php endif; ?>
                    <?php if (!empty($this->subtitle)) : ?>
                        <h3 class="music__subtitle"><?php echo $this->subtitle; ?></h3>
                    <?php endif; ?>
                    <?php if( !empty( $this->bands ) ) : ?>
                        <div class="music__block">
                            <form action="" class="filter__form">
                                <input type="text" hidden name="action" value="load_more_band">
                                <input type="text" hidden name="page" value="1" class="page">
                            </form>


                            <ul class="music__list">
                                <?php foreach ( $this->bands as $item ) : ?>
                                    <li class="music__item">
                                        <?php $post_thumbnail = get_the_post_thumbnail_url($item); ?>
                                        <?php if (!empty($post_thumbnail)) : ?>
                                            <div class="music__img img">
                                                <img src="<?php echo $post_thumbnail; ?>" alt="">
                                            </div>
                                        <?php endif; ?>
                                        <div class="music__text">
                                            <div class="music__desc">
                                                <h3 class="music__name"><?php echo get_the_title( $item ); ?></h3>
                                                <p><?php echo strip_tags( apply_filters('the_content', get_the_content(post: $item ) ) ); ?></p>
                                            </div>
                                            <a class="music__link" href="<?php echo get_the_permalink( $item ); ?>">Read more</a>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php  if (!empty( $this->button ) ) : ?>
                                <a href="<?php echo $this->button['url']; ?>" class="section__button music__more section__load"><?php echo $this->button['title']; ?></a>
                            <?php  endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <?php
    }

}