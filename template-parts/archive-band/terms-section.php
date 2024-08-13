<?php

class Bands_Page_Terms_Section {

    public function __construct() {

        $this->terms = get_terms([
            'taxonomy' => 'band_tag',
            'hide_empty' => false
        ]);

    }

    public function render() {
        ?>

            <section class="tickets">
                <div class="container">
                    <div class="preloader__wrap" style="display: none;">
                        <div class="preloader">
                            <svg viewBox="0 0 102 102" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path class="big-circle"
                                      d="M101 51C101 78.6142 78.6142 101 51 101C23.3858 101 1 78.6142 1 51" stroke="#D98C72"
                                      stroke-width="2"></path>
                                <path class="small-circle"
                                      d="M91 51C91 28.9086 73.0914 11 51 11C28.9086 11 11 28.9086 11 51" stroke="#D98C72"
                                      stroke-width="2"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="filter__mob-wrap">
                        <form action="" class="filter__form">

                            <div class="tickets__filter filter">
                                <div class="filter__item filter__sort filter__dropdown">
                                    <div class="filter__header">Sort by</div>
                                    <input type="text" class="filter__sort-input" name="sort" hidden="" autocomplete="off">
                                    <div class="filter__inner">
                                        <div class="filter__inner-item">A-Z</div>
                                        <div class="filter__inner-item">Z-A</div>

                                    </div>
                                </div>

                            </div>
                            <input type="text" hidden="" name="categories" value="" class="categories__input"
                                   autocomplete="off">
                            <input type="text" hidden="" name="page" value="1" class="page" autocomplete="off">
                            <input type="text" hidden="" name="action" value="show__bands" autocomplete="off">
                            <div class="filter__mob-button">
                                <button type="submit" class="section__button filter__mob-show">Show (2)</button>
                                <button type="reset" class="filter__mob-reset">Reset changes</button>
                            </div>
                        </form>

                    </div>

                    <?php if( !empty( $this->terms ) ) : ?>
                        <h3 class="tickets__categories-title">Categories</h3>
                        <div class="tickets__categories">
                            <?php foreach ( $this->terms as $item ) : ?>
                                <a data-category_slug="<?php echo $item->slug; ?>">
                                    <div class="tickets__categories-name"><?php echo $item->name; ?></div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

        <?php
    }

}