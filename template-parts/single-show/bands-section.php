<?php

class Single_Show_Bands_Section {

    public function __construct() {

        $related_ticket = get_field('show_info')['related_ticket'];

        $bands_section = get_field('bands_section', $related_ticket);
        $this->title = $bands_section['title'];
        $this->text = $bands_section['text'];
        $this->bands = $bands_section['bands'];

    }

    public function render() {
        if( !empty( $this->text ) or !empty( $this->bands ) ) : ?>

            <section class="bands">
                <div class="container">
                    <?php if( !empty( $this->title ) ) : ?>
                        <h2 class="section__title"><?php echo $this->title; ?></h2>
                    <?php endif; ?>
                    <?php if( !empty( $this->text ) ) : ?>
                        <div class="bands__subtitle">
                            <?php echo $this->text; ?>
                        </div>
                    <?php endif; ?>
                    <?php if( !empty( $this->bands ) ) : ?>
                        <ul class="bands__list">
                            <?php foreach( $this->bands as $item ) : ?>
                                <li class="bands__item">
                                    <?php $post_thumbnail = get_the_post_thumbnail_url($item); ?>
                                    <?php if( !empty( $post_thumbnail ) ) : ?>
                                        <div class="bands__img img">
                                            <img src="<?php echo $post_thumbnail; ?>" alt="">
                                        </div>
                                    <?php endif; ?>
                                    <div class="bands__text">
                                        <h2 class="bands__title"><?php echo get_the_title($item); ?></h2>
                                        <h3 class="bands__desc"><?php echo strip_tags(apply_filters( 'the_content', get_the_content( post: $item ) )); ?></h3>
                                        <a href="<?php echo get_the_permalink( $item ); ?>" class="bands__link"></a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </section>

        <?php endif;
    }
}