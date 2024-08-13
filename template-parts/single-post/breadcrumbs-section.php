<?php

class Single_Post_Breadcrumbs_Section {

    public function __construct() {

        $this->front_page_url = get_home_url();
        $this->blog_url = get_post_type_archive_link('post');
        $this->title = get_the_title();
    }

    public function render() {
        ?>

            <section class="breadcrumbs__wrap">
                <div class="container">
                    <div class="breadcrumbs breadcrumbs__relative">
                        <a href="<?php echo $this->front_page_url; ?>">Home</a>
                        <a href="<?php echo $this->blog_url; ?>">Blog</a>
                        <a><?php echo $this->title; ?></a>
                    </div>
                </div>
            </section>

        <?php
    }

}