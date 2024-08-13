<?php /* Template name: Shows */ ?>
<?php get_header(); ?>

    <main class="wrap__main" >
        <?php (new Front_Page_Slider_Section() )->render(); ?>
        <?php (new Promo_Section())->render(); ?>
        <?php (new Archive_Show_Show_Section())->render(); ?>
        <?php (new Advertisement_Section( (new Page_Archive_Show())::get_ID() ))->render(); ?>
        <?php (new Front_Page_Subscription_Section() )->render(); ?>
    </main>
<?php get_footer(); ?>