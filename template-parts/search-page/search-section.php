<?php

class Search_Page_Search_Section
{

    public function __construct() {

        $this->main_page_link = home_url();

    }

    public function render()
    {
        ?>

        <div class="search__top">
            <div class="container">
                <div class="preloader__wrap">
                    <div class="preloader">
                        <svg viewBox="0 0 102 102" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path class="big-circle"
                                  d="M101 51C101 78.6142 78.6142 101 51 101C23.3858 101 1 78.6142 1 51"
                                  stroke="#D98C72" stroke-width="2"/>
                            <path class="small-circle"
                                  d="M91 51C91 28.9086 73.0914 11 51 11C28.9086 11 11 28.9086 11 51"
                                  stroke="#D98C72" stroke-width="2"/>
                        </svg>
                    </div>
                </div>
                <div class="breadcrumbs">
                    <a href="<?php echo $this->main_page_link; ?>">Home</a>
                    <a>Search</a>
                </div>
                <h3 class="section__title">Search</h3>
                <form class="search__form" method="GET">
                    <div class="search__input">
                        <input type="text" name="search" placeholder="<?php echo $_GET['search']; ?>">
                    </div>
                    <button type="submit"></button>
                </form>
            </div>
        </div>

        <?php
    }
}