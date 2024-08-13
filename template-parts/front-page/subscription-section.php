<?php

class Front_Page_Subscription_Section {

    public function __construct(){

        $subscription_section = get_field('subscription_section', get_option('page_on_front'));
        $this->title = $subscription_section['title'];
        $this->subtitle = $subscription_section['subtitle'];
        $this->button_text = $subscription_section['button_text'];

    }

    public function render() {
        ?>

        <section class="subs" id="subs">
            <div class="container">
                <div class="subs__block">
                    <?php if( !empty( $this->title ) ) : ?>
                        <h2 class="subs__title"><?php echo $this->title; ?></h2>
                    <?php endif; ?>
                    <?php if( !empty( $this->subtitle ) ) : ?>
                        <h3 class="subs__subtitle"><?php echo $this->subtitle; ?></h3>
                    <?php endif; ?>
                    <form action="<?php echo admin_url('admin-ajax.php'); ?>" class="subs__form">
                        <input type="hidden" name="action" value="subscribe">
                        <div class="subs__input">
                            <input type="email" name="email" placeholder="E-mail">
                        </div>
                        <button type="submit" class="subs__button"><?php echo $this->button_text; ?></button>
                    </form>
                </div>
            </div>
        </section>

        <?php
    }
}