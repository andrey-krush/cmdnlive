<?php

class Single_Band_Breadcrumbs_Section {

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
                    <a href="<?php echo Page_Band_Archive::get_url(); ?>">Bands</a>
                    <a><?php echo $this->title; ?></a>
                </div>
            </div>
        </section>

        <?php
    }
}