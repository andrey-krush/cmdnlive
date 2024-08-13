<?php get_header(); ?>
    <main class="wrap__main show" >
        <?php (new Front_Page_Slider_Section() )->render(); ?>
        <?php (new Single_Show_Delivery_Section())->render(); ?>
        <?php (new Single_Show_Info_Section())->render(); ?>
        <?php (new Single_Show_Bands_Section())->render(); ?>
        <?php (new Single_Show_Recommended_Section())->render(); ?>
        <?php (new Single_Show_Upcoming_Section())->render(); ?>
        <?php (new Advertisement_Section( get_the_ID() ))->render(); ?>
        <?php (new Front_Page_Subscription_Section() )->render(); ?>
    </main>
<?php get_footer(); ?>