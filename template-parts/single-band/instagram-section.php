<?php

class Single_Band_Instagram_Section {

    public function __construct() {

        $this->feed = get_field('single_band_feed');

    }

    public function render() {
        if( !empty( $this->feed ) ) : ?>

        <section class="social">
            <div class="container">
                <h2 class="section__title">FOLLOW ON SOCIALS</h2>
                <?php echo do_shortcode($this->feed); ?>
            </div>
        </section>

        <?php endif;
    }

}