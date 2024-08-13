<?php

class Myaccount_Orders_Tab_Content
{

    public function __construct($orders = false)
    {

        $current_user = wp_get_current_user();
        $this->user_id = $current_user->data->ID;

        $this->tickets = [];
        $this->clothes = [];

        foreach ($orders as $item) :

            foreach ($item->get_items() as $key => $order_item) :

                $order_item_id = $order_item->get_product_id();

                if (has_term('tickets', 'product_cat', $order_item_id)) :
                    $this->tickets[] = $order_item;
                else :
                    $this->clothes[] = $order_item;
                endif;

            endforeach;

        endforeach;

    }

    public function render()
    {
        ?>

        <div class="tab__item order">

            <!-- Якщо замовлення пусті то: -->
            <?php if( empty( $this->tickets ) and empty( $this->clothes ) ) : ?>
                <div class="order__empty">
                    <div class="order__empty-img img">
                        <img src="<?=TEMPLATE_PATH?>/img/wallet.svg" alt="">
                    </div>
                    <div class="order__empty-title">
                        <h3>There have been no orders yet</h3>
                    </div>
                    <div class="order__empty-subtitle">
                        <h4>Looks like you haven't added anything to your cart yet</h4>
                    </div>
                    <a href="<?php echo ( new Tickets_Page())::get_url();    ?>" class="section__btn order__empty-link">Shop Now</a>
                </div>
            <?php else : ?>
                <div class="profile__header-order profile__header ">
                    <?php if (!empty($this->tickets) and !empty($this->clothes)) : ?>
                        <span class="tab__order active">Tickets</span>
                        <span class="tab__order">Merch & Fashion</span>
                    <?php elseif (!empty($this->tickets) and empty($this->clothes)) : ?>
                        <span class="tab__order active">Tickets</span>
                    <?php elseif (empty($this->tickets) and !empty($this->clothes)) : ?>
                        <span class="tab__order active">Merch & Fashion</span>
                    <?php endif; ?>
                </div>
                <div class="tab__content">
                    <?php if (!empty($this->tickets)) : ?>
                        <div class="tab__item-order">
                            <ul class="order__list">
                                <?php foreach ($this->tickets as $item) : ?>
                                    <?php $order = $item->get_order(); ?>
                                    <?php $post_thumbnail = get_the_post_thumbnail_url($item->get_product_id()); ?>
                                    <li class="order__item">
                                        <a href="<?php echo get_the_permalink($item->get_product_id()); ?>">
                                            <div class="order__info-block">
                                                <?php if (!empty($post_thumbnail)) : ?>
                                                    <div class="order__img img">
                                                        <img src="<?php echo $post_thumbnail; ?>" alt="">
                                                    </div>
                                                <?php endif; ?>
                                                <?php $ticket_info = get_field('ticket_info', $item->get_product_id()); ?>
                                                <div class="order__info">
                                                    <?php if (!empty($ticket_info['event_date'])) : ?>
                                                        <?php $date = DateTime::createFromFormat('d/m/Y', $ticket_info['event_date']); ?>
                                                        <h3 class="order__date">
                                                            <?php echo $date->format('l, d F, Y'); ?>
                                                            <?php if (!empty($ticket_info['event_time'])) : ?>
                                                                , <?php echo $ticket_info['event_time']; ?>
                                                            <?php endif; ?>
                                                        </h3>
                                                    <?php endif; ?>
                                                    <?php if (!empty($ticket_info['venue'])) : ?>
                                                        <h3 class="order__place"><?php echo get_the_title($ticket_info['venue']); ?></h3>
                                                    <?php endif; ?>
                                                    <h3 class="order__name"><?php echo get_the_title($item->get_product_id()); ?></h3>
                                                    <h3 class="order__count"><?php echo $item->get_quantity(); ?></h3>
                                                </div>
                                            </div>
                                            <div class="order__info-block">
                                                <div class="order__status">
                                                    <p><?php echo ucfirst($order->get_status()); ?></p>
                                                    <h4>№<?php echo $item->get_order_id(); ?></h4>
                                                </div>
                                                <div class="order__status">
                                                    <p>Date: <?php echo $order->get_date_created()->format('m/d/Y'); ?></p>
                                                    <p>To pay: £<?php echo $item->get_total(); ?></p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($this->clothes)) : ?>
                        <div class="tab__item-order">
                            <ul class="order__list">
                                <?php foreach ($this->clothes as $item) : ?>
                                    <?php $order = $item->get_order(); ?>
                                    <li class="order__item">
                                        <a href="<?php echo get_the_permalink($item->get_product_id()); ?>">
                                            <div class="order__info-block">
                                                <?php $post_thumbnail = get_the_post_thumbnail_url($item->get_product_id()); ?>
                                                <?php if (!empty($post_thumbnail)) : ?>
                                                    <div class="order__img img">
                                                        <img src="<?php echo $post_thumbnail; ?>" alt="">
                                                    </div>
                                                <?php endif; ?>
                                                <div class="order__info">
                                                    <!--                                                <h3 class="order__place">Jazz Cafe</h3>-->
                                                    <h3 class="order__name"><?php echo get_the_title($item->get_product_id()); ?></h3>
                                                    <h3 class="order__count"><?php echo $item->get_quantity(); ?></h3>
                                                </div>
                                            </div>
                                            <div class="order__info-block">
                                                <div class="order__status">
                                                    <p><?php echo ucfirst($order->get_status()); ?></p>
                                                    <h4>№<?php echo $item->get_order_id(); ?></h4>
                                                </div>
                                                <div class="order__status">
                                                    <p>Date: <?php echo $order->get_date_created()->format('m/d/Y'); ?></p>
                                                    <p>To pay: £<?php echo $item->get_total(); ?></p>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>

        <?php
    }
}