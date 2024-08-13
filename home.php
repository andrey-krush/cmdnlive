<?php get_header(); ?>
    <main class="wrap__main" >
        <?php (new Front_Page_Slider_Section() )->render(); ?>
        <?php ( new Home_Page_Promo_Section() )->render(); ?>
        <?php ( new Home_Page_Posts_Section() )->render(); ?>
        <?php (new Front_Page_Subscription_Section() )->render(); ?>
    </main>
<?php get_footer(); ?>