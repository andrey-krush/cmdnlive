<?php

class Front_Page_Join_Us_Section {

    public function __construct () {

        $join_us_section = get_field('join_us_section');
        $this->title = $join_us_section['title'];
        $this->subtitle = $join_us_section['subtitle'];
        $this->button_text = $join_us_section['button_text'];
        $this->image = $join_us_section['image'];

    }

    public function render() {
        ?>

        <section class="join">
            <div class="main__back img">
                <img src="<?=TEMPLATE_PATH?>/img/back-img.png" alt="">
            </div>
            <div class="container">
                <div class="join__block">
                    <div class="join__octagon img">
                        <img  src="<?=TEMPLATE_PATH?>/img/octagon.svg" alt="">
                    </div>
                    <?php if( !empty( $this->image ) ) : ?>
                        <div class="join__img img">
                            <img src="<?php echo $this->image; ?>" alt="">
                        </div>
                    <?php endif; ?>
                    <?php if( !empty( $this->title ) ) : ?>
                        <h3 class="join__title">
                            <?php echo $this->title; ?>
                        </h3>
                    <?php endif; ?>
                    <?php if( !empty( $this->subtitle ) ) : ?>
                        <p class="join__text"><?php echo $this->subtitle; ?></p>
                    <?php endif; ?>
                    <button class="section__button join__button"><?php echo $this->button_text; ?></button>
                </div>
            </div>
        </section>

        <?php
    }
}