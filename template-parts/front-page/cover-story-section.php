<?php

class Front_Page_Cover_Story_Section {

    public function __construct() {

        $cover_story_section = get_field('cover_story_section');
        $this->title = $cover_story_section['title'];
        $this->interview_title = $cover_story_section['interview_title'];
        $this->interview_text = $cover_story_section['interview_text'];
        $this->image = $cover_story_section['image'];
        $this->button = $cover_story_section['button'];

    }

    public function render() {
        ?>

        <section class="banner">
            <div class="preloader__wrap">
                <div class="preloader">
                        <svg viewBox="0 0 102 102" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path class="big-circle" d="M101 51C101 78.6142 78.6142 101 51 101C23.3858 101 1 78.6142 1 51" stroke="#D98C72" stroke-width="2"/>
                            <path class="small-circle" d="M91 51C91 28.9086 73.0914 11 51 11C28.9086 11 11 28.9086 11 51" stroke="#D98C72" stroke-width="2"/>
                        </svg>
                </div>
        	</div>


            <div class="container">
                <a class="banner__logo img"><img src="<?=TEMPLATE_PATH?>/img/logo-footer.svg" alt=""></a>
                        <?php if( !empty( $this->title ) ) : ?>
                            <a class="banner__title"><?php echo $this->title; ?></a>
                        <?php endif; ?>
                <?php if( !empty( $this->image ) ) : ?>
                            <div class="banner__img img">
                                <img src="<?php echo $this->image; ?>" alt="">
                            </div>
                <?php endif; ?>
                <div class="banner__text">
                    <?php if( !empty( $this->interview_title ) ) : ?>
                        <h3 class="banner__subtitle"><?php echo $this->interview_title; ?></h3>
                    <?php endif; ?>
                    <?php if( !empty( $this->interview_text ) ) : ?>
                    <p><?php echo $this->interview_text; ?></p>
                    <?php endif; ?>
                </div>
                <?php if( !empty( $this->button ) ) : ?>
                    <a href="<?php echo $this->button['url']; ?>" class="banner__read section__button"><?php echo $this->button['title']; ?></a>
                <?php endif; ?>
            </div>
        </section>

        <?php
    }
}