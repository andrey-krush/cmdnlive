<?php

class Single_Venue_Breadcrumbs_Section {

    public function __construct() {

        $this->front_page_link = get_home_url();
        $this->post_type_archive_link = get_post_type_archive_link('venue');
        $this->title = get_the_title();

    }

    public function render() {
        ?>

            <section class="breadcrumbs__wrap">
                <div class="container">
                    <div class="breadcrumbs breadcrumbs__relative">
                        <a href="<?php echo $this->front_page_link; ?>">Home</a>
                        <a href="<?php echo $this->post_type_archive_link; ?>">Venues</a>
                        <a><?php echo $this->title; ?></a>
                    </div>
                </div>
            </section>

        <?php
    }
}