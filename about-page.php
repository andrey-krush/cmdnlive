<?php /* Template name: About us */ ?>
<?php get_header(); ?>
    <main class="wrap__main band" >
        <?php (new Front_Page_Slider_Section() )->render(); ?>
        <?php ( new About_Page_Breadcrumbs_Section() )->render(); ?>
        <?php ( new About_Page_Content_Section() )->render(); ?>
        <?php (new Front_Page_Subscription_Section() )->render(); ?>
    </main>
<?php get_footer(); ?>