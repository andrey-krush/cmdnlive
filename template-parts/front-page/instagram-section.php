<?php

class Front_Page_Instagram_Section {

    public function __construct() {

        $instagram_section = get_field('instagram_section');
        $this->title = $instagram_section['title'];
        $this->feed  = $instagram_section['feed'];
    }

    public function render() {
        if( !empty( $this->feed ) ) : ?>

            <section class="social">
                <div class="container">
                    <h2 class="section__title"><?php echo $this->title; ?></h2>
                    <?php echo do_shortcode($this->feed); ?>
                </div>
            </section>

        <?php endif;
    }

}