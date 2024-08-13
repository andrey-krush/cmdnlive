<?php /* Template name: Clothes */ ?>
<?php get_header(); ?>
    <main class="wrap__main" >
        <?php (new Front_Page_Slider_Section() )->render(); ?>
        <?php (new Promo_Section() )->render(); ?>
        <?php (new Page_Clothes_Products_Section() )->render(); ?>
        <?php (new Advertisement_Section( (new Clothes_Page())::get_ID() ) )->render(); ?>
        <?php (new Front_Page_Subscription_Section() )->render(); ?>
    </main>
<?php get_footer(); ?>
