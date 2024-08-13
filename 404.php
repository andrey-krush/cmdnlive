<?php /* Template name: 404 */ ?>
<?php get_header(); ?>
<main class="wrap__main error__wrap" >
    <?php ( new Error_Page_Breadcrumbs_Section() )->render(); ?>
    <?php ( new Error_Page_First_Section())->render(); ?>
    <?php ( new Error_Page_Recommended_Posts())->render(); ?>
    <?php ( new Error_Page_Latest_Posts())->render(); ?>
    <?php ( new Error_Page_Advertisement_Section())->render()?>

        <?php /* (new Single_Show_Recommended_Section())->render();*/ ?>
        <?php /* (new Single_Show_Upcoming_Section())->render();*/ ?>
        <?php /* (new Advertisement_Section( get_the_ID() ))->render(); */?>

</main>
<?php get_footer(); ?>
