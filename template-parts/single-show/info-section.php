<?php

class Single_Show_Info_Section
{

    public function __construct()
    {

        $this->show_tags = get_terms([
            'taxonomy' => 'post_tag',
            'object_ids' => [get_the_ID()]
        ]);

        $this->show_info = get_field('show_info');

        if (!empty($this->show_info['related_ticket'])) :
            $this->ticket_info = get_field('ticket_info', $this->show_info['related_ticket']);
            $this->ticket_link = get_the_permalink($this->show_info['related_ticket']);
        endif;

        if (!empty($this->ticket_info['venue'])) :
            $this->venue_name = get_the_title($this->ticket_info['venue']);
            $this->venue_info = get_field('venue_info', $this->ticket_info['venue']);
        endif;

        $this->show_permalink = get_the_permalink();
        $this->title = get_the_title();
        $this->title_share = str_replace('&', '', $this->title);
        $this->title_share = str_replace('#038;', '', $this->title_share);
        $this->title_share = str_replace('&amp;', '', $this->title_share);

        $this->content = get_the_content();

        $info_section = get_field('info_section');
        $this->info_section_title = $info_section['title'];
        $this->info_section_image = $info_section['image'];
        $this->info_section_text = $info_section['info_text'];
    }

    public function render()
    {
        ?>

        <section class="oneproduct">
            <div class="preloader__wrap">
                <div class="preloader">
                    <svg viewBox="0 0 102 102" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path class="big-circle" d="M101 51C101 78.6142 78.6142 101 51 101C23.3858 101 1 78.6142 1 51"
                              stroke="#D98C72" stroke-width="2"/>
                        <path class="small-circle" d="M91 51C91 28.9086 73.0914 11 51 11C28.9086 11 11 28.9086 11 51"
                              stroke="#D98C72" stroke-width="2"/>
                    </svg>
                </div>
            </div>
            <div class="container">
                <div class="oneproduct__block">
                    <?php if (!empty($this->show_info['gallery'])) : ?>
                        <?php if( count( $this->show_info['gallery'] ) !== 1 ) : ?>
                            <div class="oneproduct__gallery">
                                <div class="product-images">
                                    <div class="gallery-top swiper-container">
                                        <div class="swiper-wrapper">
                                            <?php
                                            foreach ($this->show_info['gallery'] as $item) {
                                                echo '<div class="swiper-slide img">';
                                                echo wp_get_attachment_image($item['image']['ID'], 'woocommerce_single');
                                                echo '</div>';
                                            }
                                            ?>
                                        </div>
    
                                    </div>
                                    <div class="gallery-thumbs swiper-container">
                                        <div class="swiper-wrapper">
                                            <?php
                                            foreach ($this->show_info['gallery'] as $item) {
                                                echo '<div class="swiper-slide img">';
                                                echo wp_get_attachment_image($item['image']['ID'], 'woocommerce_thumbnail');
                                                echo '</div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-pagination"></div>
                        <?php else : ?>
                            <div class="oneproduct__gallery single_image">
                                <img src="<?php echo $this->show_info['gallery'][0]['image']['url']; ?>">
                            </div>    
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="oneproduct__desc">
                        <div class="oneproduct__social">
                            <div class="article__social-wrap">
                                <div class="article__social">
                                    <span>Share</span>
                                    <a href="https://www.instagram.com/?url=<?php echo $this->permalink; ?>" target="_blank" class="article__social-item img article__instagram">
                                        <img src="<?=TEMPLATE_PATH?>/img/instagram.svg" alt="">
                                    </a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $this->permalink; ?>" class="article__social-item img article__facebook">
                                        <img src="<?=TEMPLATE_PATH?>/img/facebook.svg" alt="">
                                    </a>
                                    <a class="article__social-item img article__copy" data-copy="<?php echo $this->permalink; ?>">
                                        <img src="<?=TEMPLATE_PATH?>/img/link.svg" alt="">
                                        <span class="article__copy-mess">Click to copy</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="oneproduct__info">
                            <h1 class="product_title entry-title"><?php echo $this->title; ?></h1>
                            <?php if (!empty($this->show_info['related_ticket'])) : ?>
                                <?php $product = wc_get_product($this->show_info['related_ticket']); ?>
                                <div class="price__variant">
                                    <div class="woocommerce-variation-price"><span class="price">
                                            <span class="woocommerce-Price-amount amount">
                                                <bdi>
                                                    <span class="woocommerce-Price-currencySymbol">£</span>
                                                        <?php echo $product->get_price(); ?>
                                                </bdi>
                                            </span>
                                        </span>
                                    </div>
                                    <div class="woocommerce-variation-availability">
                                        <?php $availability = $product->get_availability(); ?>
                                        <?php if ($availability['class'] == 'in-stock') : ?>
                                            <p class="stock in-stock">
                                                In stock
                                            </p>
                                        <?php else : ?>
                                            <p class="stock out-of-stock">
                                                Out of stock
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="oneproduct__address">
                            <?php if (!empty($this->venue_info['location'])) : ?>
                                <div class="shows__location shows__info">
                                    <p><?php echo $this->venue_info['location']['address']; ?></p>
                                    <a class="shows__map" data-lng="<?php echo $this->venue_info['location']['lng']; ?>" data-lat="<?php echo $this->venue_info['location']['lat']; ?>"><?php echo $this->venue_name; ?></a>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($this->ticket_info['event_date'])) : ?>
                                <?php $date = $this->ticket_info['event_date']; ?>
                                <?php $date = DateTime::createFromFormat('d/m/Y', $date); ?>
                                <div class="shows__date shows__info">
                                    <p><?php echo $date->format('l'); ?></p>
                                    <h4><?php echo $date->format('d F, Y'); ?></h4>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($this->show_tags)) : ?>
                            <div class="oneproduct__categories">
                                <div class="tickets__categories">
                                    <?php foreach ($this->show_tags as $item) : ?>
                                        <div class="tickets__categories-name"><?php echo $item->name; ?></div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if( !empty( $this->ticket_link ) ) : ?>
                            <div class='show__buy'><a href="<?php echo $this->ticket_link; ?>" class="section__btn">Buy tickets</a></div>
                        <?php endif; ?>
                        <?php if (!empty($this->content)) : ?>
                            <div class="oneproduct__text">
                                <?php echo $this->content; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="show__recommended">
                    <?php if (!empty($this->info_section_image)) : ?>
                        <div class="show__recommended-img img">
                            <img src="<?php echo $this->info_section_image; ?>" alt="">
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($this->info_section_text) or !empty($this->info_section_title)) : ?>
                        <div class="show__recommended-text">
                            <?php if (!empty($this->info_section_title)) : ?>
                                <h2><?php echo $this->info_section_title; ?></h2>
                            <?php endif; ?>
                            <?php if (!empty($this->info_section_text)) : ?>
                                <?php echo $this->info_section_text; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <?php
    }
}