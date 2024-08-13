<?php

class Front_Page_Advertisement_Section {

    public function __construct() {

        $advertisement_section = get_field('advertisement_section', get_option('page_on_front'));
        $this->advertisement_text = $advertisement_section['text'];
    }

    public function render() {
        if( !empty( $this->advertisement_text ) ) : ?>

            <div class="news">
                <div class="container">
                    <div class="news__wrap">
                        <div class="news__block">
                            <div class="news__close img">
                                <img src="<?=TEMPLATE_PATH?>/img/close.svg" alt="">
                            </div>
                            <h2><?php echo $this->advertisement_text; ?></h2>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif;
    }
}