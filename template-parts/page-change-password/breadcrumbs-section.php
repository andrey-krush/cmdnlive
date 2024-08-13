<?php

class Page_Change_Password_Breadcrumbs_Section {

    public function __construct() {

        $this->front_page_link = get_home_url();
        $this->myaccount_link = wc_get_page_permalink( 'myaccount' );
        $this->title = get_the_title();

    }

    public function render() {
        ?>

        <section class="breadcrumbs__wrap">
            <div class="container">
                <div class="breadcrumbs breadcrumbs__relative breadcrumbs__black">
                    <a href="<?php echo $this->front_page_link; ?>">Home</a>
                    <a href="<?php echo $this->myaccount_link; ?>">Profile</a>
                    <a><?php echo $this->title; ?></a>
                </div>
            </div>
        </section>

        <?php
    }

}