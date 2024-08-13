<?php

class Page_Tickets_Filters_Section
{

    public function __construct()
    {

        $main_ticket_category = get_term_by('slug', 'tickets', 'product_cat');

        $this->venues = get_posts([
            'post_type' => 'venue',
            'post_status' => 'publish',
            'numberposts' => -1
        ]);

        $this->categories = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'parent' => $main_ticket_category->term_id
        ]);

    }

    public function render()
    {
        ?>

        <section class="tickets">
            <div class="container">
                <div class="preloader__wrap">
                    <div class="preloader">
                        <svg viewBox="0 0 102 102" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path class="big-circle"
                                  d="M101 51C101 78.6142 78.6142 101 51 101C23.3858 101 1 78.6142 1 51" stroke="#D98C72"
                                  stroke-width="2"/>
                            <path class="small-circle"
                                  d="M91 51C91 28.9086 73.0914 11 51 11C28.9086 11 11 28.9086 11 51" stroke="#D98C72"
                                  stroke-width="2"/>
                        </svg>
                    </div>
                </div>
                <div class="filter__reset img">
                    <img src="<?= TEMPLATE_PATH ?>/img/reset-filter.svg" alt="">
                </div>
                <div class="filter__mob-wrap">
                    <form action="" class="filter__form">
                        <div class="filter__mob-header">
                            <a href="" class="filter__mob-logo img">
                                <img src="<?= TEMPLATE_PATH ?>/img/logo.svg" alt="">
                            </a>
                            <div class="filter__mob-close"></div>
                        </div>
                        <div class="filter__mob">
                            <button type="button" class="filter__mob-event">
                                Filter event
                            </button>
                        </div>
                        <div class="tickets__filter filter">
                            <div class="filter__item filter__sort filter__dropdown">
                                <div class="filter__header">Sort by</div>
                                <input type="text" class="filter__sort-input" name="sort" hidden>
                                <div class="filter__inner">
                                    <div class="filter__inner-item">Latest</div>
                                    <div class="filter__inner-item">Low price to High price</div>
                                    <div class="filter__inner-item">High price to Low price</div>
                                    <div class="filter__inner-item">Popular</div>
                                </div>
                            </div>
                            <div class="filter__item filter__date">
                                <div class="filter__datepicker-wrap ">
                                    <input type="text" name="date" class="filter__datepicker" value=""
                                           placeholder="Date">
                                </div>
                            </div>
                            <?php if (!empty($this->venues)) : ?>
                                <div class="filter__item filter__venue">
                                    <div class="filter__header">Venue</div>
                                    <select class="filter__select" name="venue">
                                        <option></option>
                                        <?php foreach ($this->venues as $item) : ?>
                                            <option value="<?php echo $item->ID; ?>"><?php echo get_the_title($item->ID); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>
                        </div>
                        <input type="text" hidden name="categories" value="" class="categories__input">
                        <input type="text" hidden name="page" value="1" class="page">
                        <input type="text" hidden name="action" value="show__tickets">
                        <div class="filter__mob-button">
                            <button type="submit" class="section__button filter__mob-show"></button>
                            <button type="reset" class="filter__mob-reset">Reset changes</button>
                        </div>
                    </form>

                </div>
                <?php if (!empty($this->categories)) : ?>
                    <h3 class="tickets__categories-title">Categories</h3>
                    <div class="tickets__categories">
                        <?php foreach ($this->categories as $item) : ?>
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