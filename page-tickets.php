<?php /* Template name: Tickets */ ?>
<?php get_header(); ?>
    <main class="wrap__main" >
        <?php (new Front_Page_Slider_Section() )->render(); ?>
        <?php (new Promo_Section())->render(); ?>
        <?php (new Page_Tickets_Filters_Section())->render(); ?>
        <?php (new Page_Tickets_Recommended_Tickets())->render(); ?>
        <?php (new Page_Tickets_Upcoming_Tickets())->render(); ?>
        <?php (new Front_Page_Subscription_Section() )->render(); ?>
    </main>
<?php get_footer(); ?>