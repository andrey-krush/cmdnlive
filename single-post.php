<?php get_header(); ?>
    <main class="wrap__main" >
        <?php (new Front_Page_Slider_Section() )->render(); ?>
        <?php (new Single_Post_Breadcrumbs_Section())->render(); ?>
        <?php (new Single_Post_Content_Section())->render(); ?>
        <?php (new Single_Post_Recommended_Posts_Section())->render(); ?>
        <?php (new Front_Page_Subscription_Section() )->render(); ?>
    </main>
<?php get_footer(); ?>