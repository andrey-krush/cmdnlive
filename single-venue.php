<?php get_header(); ?>
    <main class="wrap__main venue" >
        <?php ( new Single_Venue_Breadcrumbs_Section() )->render(); ?>
        <?php ( new Single_Venue_Content_Section() )->render(); ?>
        <?php ( new Single_Venue_Related_Shows() )->render(); ?>
        <?php (new Front_Page_Subscription_Section() )->render(); ?>
    </main>
<?php get_footer(); ?>