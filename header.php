<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-133758354-1"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-133758354-1');
    </script>

    <!-- build:css styles/plagins.css -->
    <link rel="stylesheet" href="<?=TEMPLATE_PATH?>/styles/formstyler.css">
    <link rel="stylesheet" href="<?=TEMPLATE_PATH?>/styles/normalize.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css"/>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <!-- add you plagin css here -->

    <!-- endbuild -->

    <!-- build:css styles/main.css -->

    <link rel="stylesheet" href="<?=TEMPLATE_PATH?>/styles/critical.css">


    <!-- add you develop css here -->

    <!-- endbuild -->
    <?php wp_head(); ?>
    <link rel="stylesheet" href="<?=TEMPLATE_PATH?>/styles/index.css">
    <link rel="stylesheet" href="<?=TEMPLATE_PATH?>/styles/woocommerce.css">

    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '3481997802078516');
    fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=3481997802078516&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code -->
</head>
<body>

<div class="wrap">
    <?php
    $show_array = [
        is_product(),
        is_page_template(['page-tickets.php', 'page-clothes.php', 'about-page.php', 'archive-show.php']),
        is_home(),
        is_front_page(),
        is_singular(['post', 'show']),
    ];

    ?>


    <?php if( in_array(true, $show_array) ) : ?>
        <?php ( new Front_Page_Advertisement_Section() )->render(); ?>
    <?php endif; ?>

    <!-- HEADER -->
    <?php $header = get_field('header', 'option'); ?>
    <?php $front_page_url = get_home_url(); ?>
    <header class="header">
        <div class="container">
            <div class="header__block">
                <?php if( !empty( $header['logo'] ) ) : ?>
                    <a href="<?php echo $front_page_url; ?>" class="header__logo header__logo-mob img">
                        <img src="<?php echo $header['logo']; ?>" alt="">
                    </a>
                <?php endif; ?>
                <?php $search_url = (new Search_Page())::get_url(); ?>
                <div class="header__mob">
                    <div class="header__icon header__search"></div>
                    <form action="<?php echo $search_url; ?>" class="header__search-form" method="GET" >
                        <div class="header__search-back"></div>
                        <div class="header__search-block container">
                            <input type="text" name="search" placeholder="Start your search...">
                            <div class="header__search-button">
                                <button type="submit"></button>
                            </div>
                        </div>
                    </form>
                    <div class="header__button header__button-mob">
                        <?php if( is_user_logged_in() ) : ?>
                             <a href="<?php echo get_permalink( wc_get_page_id( 'myaccount')); ?>" class="header__icon header__user "></a>
                        <?php else : ?>
                               <a class="header__icon header__user header__auth"></a>
                        <?php endif; ?>
                        <a href="<?php echo wc_get_cart_url(); ?>" class="header__icon header__bag">
                            <div class="header__bag-counter"><span><?php echo WC()->cart->get_cart_contents_count(); ?></span></div>
                        </a>
                    </div>
                    <div class="header__burger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
                <?php if( !empty( $header['menu_items'] ) ) : ?>
                    <ul class="header__menu">
                        <?php foreach( $header['menu_items'] as $item ) : ?>
                            <li class="header__item">
                                <?php if( $item['is_logo'] ) : ?>
                                    <a href="<?php echo $front_page_url; ?>" class="header__logo img">
                                        <img src="<?php echo $header['logo']; ?>" alt="">
                                    </a>
                                <?php else : ?>
                                    <a href="<?php echo $item['link']['url']; ?>"><?php echo $item['link']['title']; ?></a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <div class="header__button">
                    <?php if( is_user_logged_in() ) : ?>
                        <a href="<?php echo get_permalink( wc_get_page_id( 'myaccount')); ?>" class="header__icon header__user "></a>
                    <?php else : ?>
                        <a class="header__icon header__user header__auth"></a>
                    <?php endif; ?>
                    <a href="<?php echo get_permalink( wc_get_page_id( 'cart' ) ); ?>" class="header__icon header__bag">
                        <div class="header__bag-counter"><span><?php echo WC()->cart->get_cart_contents_count(); ?></span></div>
                    </a>
                </div>
            </div>
        </div>

    </header>