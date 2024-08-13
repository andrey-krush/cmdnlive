<?php /* Template name: Bands Page*/ ?>
<?php get_header(); ?>
    <main class="wrap__main band__wrap">
        <?php ( new Promo_Section() )->render(); ?>
        <?php ( new Bands_Page_Terms_Section() )->render(); ?>
        <?php ( new Bands_Page_Bands_Section() )->render(); ?>
        <?php ( new Advertisement_Section( Page_Band_Archive::get_ID() ) )->render(); ?>

    </main>

<?php get_footer(); ?>