<?php

class Error_Page_Advertisement_Section {

    public function __construct() {

        $advertisement_section = get_field('advertisement_section', Page_404::get_ID() );
        $this->is_block = $advertisement_section['is_block'];
        $this->title = $advertisement_section['title'];
        $this->text = $advertisement_section['text'];
        $this->image = $advertisement_section['image'];
        $this->link = $advertisement_section['link'];

    }

    public function render() {
        if( $this->is_block ) : ?>

            <section class="adv__wrap">
                <div class="container">
                    <div class="adv">
                        <?php if( !empty( $this->image ) ) : ?>
                            <div class="adv__img img">
                                <img src="<?php echo $this->image; ?>" alt="">
                            </div>
                        <?php endif; ?>
                        <div class="adv__block">
                            <div class="adv__text">
                                <?php if( !empty( $this->title ) ) : ?>
                                    <h3><?php echo $this->title; ?></h3>
                                <?php endif; ?>
                                <?php if( !empty( $this->text ) ) : ?>
                                    <?php echo $this->text; ?>
                                <?php endif; ?>
                            </div>
                            <?php if( !empty( $this->link ) ) : ?>
                                <a href="<?php echo $this->link['url']; ?>" class="section__button"><?php echo $this->link['title']; ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </section>

        <?php endif;
    }
}