<?php get_header(); ?>
    <main class="wrap__main band" >
        <?php ( new Single_Band_Breadcrumbs_Section() )->render(); ?>
        <?php ( new Single_Band_Content_Section() )->render(); ?>
        <?php ( new Single_Band_Related_Shows() )->render(); ?>
        <?php ( new Single_Band_Related_Posts_Section() )->render(); ?>
        <?php ( new Single_Band_Instagram_Section() )->render(); ?>
        <?php (new Front_Page_Subscription_Section() )->render(); ?>
    </main>
<?php get_footer(); ?>