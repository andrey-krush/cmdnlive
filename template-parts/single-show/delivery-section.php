<?php

class Single_Show_Delivery_Section {

    public function __construct() {

        $promo_section = get_field('shows_promo_section', 'option');
        $this->is_section = $promo_section['is_section'];
        $this->text = $promo_section['text'];
        $this->background_image = $promo_section['background_image'];

        $this->title = get_the_title();
        $this->front_page_link = home_url();

        $this->shows_page = get_pages([
            'meta_key' => '_wp_page_template',
            'meta_value' => 'archive-show.php',
        ]);

        if( !empty( $this->shows_page ) ) :
            $this->shows_page_title = get_the_title($this->shows_page[0]->ID);
            $this->shows_page_permalink = get_the_permalink($this->shows_page[0]->ID);
        endif;

    }

    public function render() {
        if( $this->is_section and !empty( $this->background_image ) ) : ?>

            <section class="delivery">
                <div class="delivery__block">
                    <div class="main__back img">
                        <img src="<?php echo $this->background_image; ?>" alt="">
                    </div>
                    <div class="container">
                        <div class="breadcrumbs">
                            <a href="<?php echo $this->front_page_link; ?>">Home</a>
                            <?php if( !empty( $this->shows_page_title ) ) : ?>
                                <a href="<?php echo $this->shows_page_permalink; ?>"><?php echo $this->shows_page_title; ?></a>
                            <?php endif; ?>
                            <a><?php echo $this->title; ?></a>
                        </div>
                        <div class="delivery__title">
                            <h2><?php echo $this->text; ?></h2>
                        </div>
                    </div>
                </div>
            </section>

        <?php endif;
    }
}