<?php

class About_Page_Breadcrumbs_Section {

    public function __construct() {

        $this->front_page_link = get_home_url();
        $this->title = get_the_title();

    }

    public function render() {
        ?>

        <section class="breadcrumbs__wrap">
            <div class="container">
                <div class="breadcrumbs breadcrumbs__relative">
                    <a href="<?php echo $this->front_page_link; ?>">Home</a>
                    <a><?php echo $this->title; ?></a>
                </div>
            </div>
        </section>

        <?php
    }

}