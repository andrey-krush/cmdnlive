<?php /* Template name: Search */ ?>
<?php get_header(); ?>
    <main class="wrap__main search" >
        <?php ( new Search_Page_Search_Section() )->render(); ?>
        <?php ( new Search_Page_Relevant_Tickets() )->render(); ?>
        <?php ( new Search_Page_Relevant_Posts() )->render(); ?>
        <?php ( new Search_Page_Relevant_Shows() )->render(); ?>
        <?php ( new Search_Page_Relevant_Groups() )->render(); ?>
        <?php (new Front_Page_Subscription_Section() )->render(); ?>
    </main>
<?php get_footer(); ?>
