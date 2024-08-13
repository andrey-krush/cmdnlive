<?php

class Error_Page_Breadcrumbs_Section {

    public function __construct() {

        $this->front_page_link = home_url();
        $this->title = get_the_title( Page_404::get_ID() );

    }

    public function render() {
        ?>

            <section class="breadcrumbs__wrap">
                <div class="container">
                    <div class="breadcrumbs breadcrumbs__relative breadcrumbs__dark ">
                        <a href="<?php echo $this->front_page_link; ?>">Home</a>
                        <a><?php echo $this->title; ?></a>
                    </div>
                    <div class="preloader__wrap">
                                    <div class="preloader">
                                            <svg viewBox="0 0 102 102" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path class="big-circle" d="M101 51C101 78.6142 78.6142 101 51 101C23.3858 101 1 78.6142 1 51" stroke="#D98C72" stroke-width="2"/>
                                                <path class="small-circle" d="M91 51C91 28.9086 73.0914 11 51 11C28.9086 11 11 28.9086 11 51" stroke="#D98C72" stroke-width="2"/>
                                            </svg>
                                    </div>
                            	</div>
                </div>
            </section>

        <?php
    }
}