<?php /* Template name: Venues */ ?>
<?php get_header(); ?>
    <main class="wrap__main" >
        <?php ( new Archive_Venue_Breadcrumbs_Section() )->render(); ?>
        <?php ( new Archive_Venue_Venues_Section() )->render(); ?>
        <?php ( new Advertisement_Section( (new Page_Archive_Venue())::get_ID() ) )->render(); ?>
        <?php (new Front_Page_Subscription_Section() )->render(); ?>
    </main>
<?php get_footer(); ?>