<?php get_header(); ?>
    <main class="wrap__main" >
        <?php (new Front_Page_Slider_Section() )->render(); ?>
        <?php (new Front_Page_Cover_Story_Section() )->render(); ?>
        <?php (new Front_Page_Posts_Section() )->render(); ?>
        <?php (new Front_Page_Bands_Section() )->render(); ?>
        <?php (new Front_Page_Gallery_Section() )->render(); ?>
        <?php (new Front_Page_Instagram_Section())->render(); ?>
        <?php (new Front_Page_Join_Us_Section() )->render(); ?>
        <?php (new Front_Page_Subscription_Section() )->render(); ?>
    </main>
<?php get_footer(); ?>