<?php

class Archive_Venue_Breadcrumbs_Section {

    public function __construct() {

        $this->front_page_link = home_url();

    }

    public function render() {
        ?>

            <section class="breadcrumbs__wrap">
                <div class="container">
                    <div class="breadcrumbs breadcrumbs__relative breadcrumbs__dark ">
                        <a href="<?php echo $this->front_page_link; ?>">Home</a>
                        <a>Venues</a>
                    </div>
                </div>
            </section>

        <?php
    }
}