<?php

class Promo_Section {

    public function __construct() {

        $promo_section = get_field('promo_section');
        $this->text = $promo_section['text'];
        $this->background_image = $promo_section['background_image'];
        $this->front_page_link = get_home_url();
        $this->title = get_the_title();

    }

    public function render() {
        if( !empty( $this->background_image ) ) : ?>

            <section class="delivery">
                <div class="delivery__block">
                    <div class="main__back img">
                        <img src="<?php echo $this->background_image; ?>" alt="">
                    </div>
                    <div class="container">
                        <div class="breadcrumbs">
                            <a href="<?php echo $this->front_page_link; ?>">Home</a>
                            <a><?php echo $this->title; ?></a>
                        </div>
                        <?php if( !empty( $this->text ) ) : ?>
                            <div class="delivery__title">
                                <h2><?php echo $this->text; ?></h2>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

        <?php endif;
    }
}