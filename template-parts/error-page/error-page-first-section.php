<?php

class Error_Page_First_Section
{
    public function __construct()
    {
        $content = get_field('content', Page_404::get_ID());
        $this->image = $content['image'];
        $this->title = $content['title'];
        $this->subtitle = $content['subtitle'];

    }

    public function render()
    { ?>

        <section class="errorpage">
            <div class="container">
                <div class="errorpage__block">
                    <?php if( !empty( $this->image ) ) : ?>
                        <div class="errorpage__img img">
                            <img src="<?php echo $this->image; ?>" alt="">
                        </div>
                    <?php endif; ?>
                    <?php if( !empty( $this->title ) ) : ?>
                        <h1><?php echo $this->title; ?></h1>
                    <?php endif; ?>
                    <?php if( !empty( $this->subtitle ) ) : ?>
                        <h2><?php echo $this->subtitle; ?></h2>
                    <?php endif; ?>
                </div>
                <form action="<?php echo Search_Page::get_url(); ?>" class="header__search-form errorpage__form"
                      method="GET">
                    <div class="header__search-block">
                        <input type="text" name="search" placeholder="Start your search..." autocomplete="off">
                        <div class="header__search-button">
                            <button type="submit"></button>
                        </div>
                    </div>
                </form>
            </div>

        </section>

    <?php }

}




