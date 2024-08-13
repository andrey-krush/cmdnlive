<?php /* Template name: Change password */ ?>
<?php get_header(); ?>

    <main class="wrap__main" >
        <?php ( new Page_Change_Password_Breadcrumbs_Section())->render(); ?>
        <?php ( new Page_Change_Password_Form_Section())->render(); ?>
    </main>

<?php get_footer(); ?>
