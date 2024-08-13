<?php /* Template name: Page ticket info */ ?>
<?php get_header(); ?>
<section class="music">
    <div class="music__back">
        <img src="/wp-content/themes/cmdn/img/discover-back.svg" alt="">
    </div>
    <div class="container">
        <div class="ticket-info">
            <h1 class="ticket-info__title">Ticket info</h1>
            <div class="ticket-info__subtitle">
                <h3>At this page you can verify your ticket information</h3>
            </div>
            <?php
                foreach ( $_GET as $key => $item ) :
                    $_GET[$key] = str_replace( '\"__\"', ' ', $item );
                    $_GET[$key] = str_replace( '\"____\"', '&', $_GET[$key] );
                endforeach;
            ?>
            <div class="info__wrap">
                <section class="info__items">
                    <?php if( isset( $_GET['show_name'] ) and !empty( $_GET['show_name'] ) ) :  ?>
                        <div class="info__item item__show-name">
                            <h3 class="item__title">Show name :</h3>
                            <div class="item__content"><?php echo $_GET['show_name']; ?></div>
                        </div>
                    <?php endif; ?>
                    <div class="info__item item__customer-name">
                        <h3 class="item__title">Customer Name :</h3>
                        <div class="item__content"><?php echo $_GET['customer_name'] ?? ''; ?></div>
                    </div>
                    <div class="info__item item__customer-email">
                        <h3 class="item__title">Customer email :</h3>
                        <div class="item__content"><?php echo $_GET['customer_email'] ?? ''; ?></div>
                    </div>
                    <?php if( isset( $_GET['order_id'] ) and !empty( $_GET['order_id'] ) ) :  ?>
                        <div class="info__item item__order_id">
                            <h3 class="item__title">Order ID:</h3>
                            <div class="item__content"><?php echo $_GET['order_id']; ?></div>
                        </div>
                    <?php endif; ?>
                    <?php if( isset( $_GET['ticket_id'] ) and !empty( $_GET['ticket_id'] ) ) :  ?>
                        <div class="info__item item__ticket-id">
                            <h3 class="item__title">Ticket number:</h3>
                            <div class="item__content"><?php echo $_GET['ticket_id']; ?></div>
                        </div>
                    <?php endif; ?>
                    <div class="info__item item__ticket-price">
                        <h3 class="item__title">Ticket price :</h3>
                        <div class="item__content"><?php echo '<span class="woocommerce-Price-currencySymbol">£</span> ' . $_GET['ticket_price'] ?? ''; ?></div>
                    </div>
                    <?php if( isset( $_GET['ticket_type'] ) and !empty( $_GET['ticket_type'] ) ) :  ?>
                        <div class="info__item item__ticket-type">
                            <h3 class="item__title">Ticket type :</h3>
                            <div class="item__content"><?php echo $_GET['ticket_type']; ?></div>
                        </div>
                    <?php endif; ?>
                    <div class="info__item item__venue-name">
                        <h3 class="item__title">Venue name :</h3>
                        <div class="item__content"><?php echo $_GET['venue_name'] ?? ''; ?></div>
                    </div>
                    <div class="info__item item__event-date">
                        <h3 class="item__title">Event date :</h3>
                        <div class="item__content"><?php echo $_GET['event_date'] ?? ''; ?></div>
                    </div>
                </section>
            </div>
        </div>

    </div>
</section>

<?php get_footer(); ?>

