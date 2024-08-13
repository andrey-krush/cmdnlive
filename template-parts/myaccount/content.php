<?php

class Myaccount_Content {

    public function __construct(){

        $args = [
            'customer_id' => get_current_user_id()
        ];

        $this->orders = wc_get_orders($args);

    }

    public function render() {
        ?>


        <section class="profile">
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
                <div class="tabs">
                    <div class="profile__header">
                        <span class="tab active">My profile</span>
                        <?php if( !empty( $this->orders ) ) : ?>
                            <span class="tab">My orders</span>
                        <?php endif; ?>
                    </div>
                    <div class="tab__content">
                        <?php (new Myaccount_Profile_Tab_Content())->render(); ?>
                        <?php (new Myaccount_Orders_Tab_Content( $this->orders ))->render(); ?>
                    </div>
                </div>
            </div>
        </section>

        <?php
    }
}