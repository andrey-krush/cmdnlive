<?php

class Home_Page_Promo_Section {

    public function __construct() {

        $blog_page_id = get_option( 'page_for_posts' );

        $promo_section = get_field('promo_section', $blog_page_id );
        $this->front_page_link = get_home_url();
        $this->blog_page_title = get_the_title($blog_page_id);
        $this->text = $promo_section['text'];
        $this->background_image = $promo_section['background_image'];

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
                            <a><?php echo $this->blog_page_title; ?></a>
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