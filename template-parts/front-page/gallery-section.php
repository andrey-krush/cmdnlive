<?php

class Front_Page_Gallery_Section {

    public function __construct() {

        $gallery_section = get_field('gallery_section');
        $this->gallery = $gallery_section['gallery'];
        $this->title = $gallery_section['title'];

    }

    public function render() {

        if( !empty( $this->gallery ) ) : ?>

            <section class="gifs">
            <div class="main__back img">
                <img src="<?=TEMPLATE_PATH?>/img/back-img.png" alt="">
            </div>
            <div class="container">
                <?php if( !empty( $this->title ) ) : ?>
                    <h2 class="section__title"><?php echo $this->title; ?></h2>
                <?php endif; ?>
                <ul class="gifs__list">
                    <?php foreach ( $this->gallery as $item ) : ?>
                        <?php if( !empty( $item['image'] ) ) : ?>
                            <li class="gifs__item img">
                                <a class="img" href="<?php echo $item['link']; ?>"><img src="<?php echo $item['image']; ?>" alt=""></a>
                                <div class="gifs__desc">
                                    <?php if( !empty( $item['title'] ) ) : ?>
                                        <h3><?php echo $item['title']; ?></h3>
                                    <?php endif; ?>
                                    <?php if( !empty( $item['photographer_link'] ) ) : ?>
                                        <p>Photographer - <a href="<?php echo $item['photographer_link']['url']; ?>"><?php echo $item['photographer_link']['title']; ?></a></p>
                                    <?php endif; ?>
                                </div>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </section>

        <?php endif;
    }
}