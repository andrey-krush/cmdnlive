<?php

use Dompdf\Dompdf;
use BenMajor\ImageResize\Image;
use Dompdf\Options;
use chillerlan\QRCode\{QRCode, QROptions};

class Type_Order
{

    public static function init_auto()
    {

        add_action('woocommerce_payment_complete', [__CLASS__, 'send_tickets']);
        add_action('init', [__CLASS__, 'add_cron_delete_pdfs']);
        add_action('delete_action', [__CLASS__, 'delete_pdfs_daily']);

    }

    public static function send_tickets($order_id)
    {

        if (!$order_id) :
            return;
        endif;

        $tickets = get_post_meta($order_id, 'tickets_order_meta', true);

        if (empty($tickets)) :
            return;
        endif;

        foreach ($tickets as $key => $item)  :

            $variation_id = '';

            if (str_contains($key, '_')) :

                $product = explode('_', $key);
                $product_id = $product[0];
                $variation_id = $product[1];

                $title_for_filename = get_the_title($product_id);

                $variation = wc_get_product($variation_id);
                $variation_value = $variation->get_attributes()[array_keys($variation->get_attributes())[0]];

                if (!empty($variation_value)) :
                    $title_for_filename .= '-' . $variation_value;
                endif;

            else :

                $product_id = $key;
                $title_for_filename = get_the_title($product_id);

            endif;


            foreach ($item as $subitem) :

                $name_for_filename = str_replace(' ', '_', $subitem['name']);
                $lastname_for_filename = str_replace(' ', '_', $subitem['lastname']);
                $filename = $name_for_filename . '-' . $lastname_for_filename . '-' . $title_for_filename . '-' . date('Ymd');

                if( isset( $variation_id ) and !empty( $variation_id ) ) :

                    $html = self::generate_html($subitem, $product_id, $order_id, $subitem['ticket_sold_id'], $variation_id);

                else :

                    $html = self::generate_html($subitem, $product_id, $order_id, $subitem['ticket_sold_id'], '');

                endif;

                $options = new Options();
                $options->set('enable_html5_parser', true);

                $dompdf = new Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();
                $content = $dompdf->output();
                file_put_contents(wp_get_upload_dir()['basedir'] . '/pdf/' . $filename . '.pdf', $content);

                $headers = array(
                    'From: CMDN <cmdn@cmdn.com>',
                    'content-type: text/html'
                );
                $subject = 'Thank you for buying a ticket !';
                $attachments = array(wp_get_upload_dir()['basedir'] . '/pdf/' . $filename . '.pdf');

                wp_mail($subitem['email'], $subject, 'Your ticket: ', $headers, $attachments);

            endforeach;

        endforeach;

    }

    private static function generate_html($user, $product_id, $order_id, $ticket_sold_id, $variation_id = false)
    {

        $order = wc_get_order($order_id);
        $order_date_format = get_the_date('m/d/Y, H:i', $order_id);

        $permalink = get_the_permalink($product_id);
        $title = get_the_title($product_id);
        $blog_url = home_url();

        $ticket_info = get_field('ticket_info', $product_id);
        $event_date = $ticket_info['event_date'];

        $product = wc_get_product($product_id);
        $price = $product->get_price();

        if( !empty( $variation_id ) ) :
            $variation = new WC_Product_Variation($variation_id);
            $price = $variation->get_price();
        endif;

        if (!empty($event_date)) :
            $dateTime = DateTime::createFromFormat('d/m/Y', $event_date);
            $date = $dateTime->format('d F');
        endif;

        if (!empty($ticket_info['venue'])) :

            $venue_title = get_the_title($ticket_info['venue']);
            $venue_info = get_field('venue_info', $ticket_info['venue']);

            if (!empty($venue_info['location'])) :

                $location = $venue_info['location']['address'];

            endif;

        endif;

        $logo_filename = get_template_directory() . '/img/logo.png';

        $ticket_info_page = get_pages([
            'meta_key' => '_wp_page_template',
            'meta_value' => 'page-ticket-info.php',
        ])[0];

        if (!empty($variation_id)) :

            $variation = wc_get_product($variation_id);
            $variation_value = $variation->get_attributes()[array_keys($variation->get_attributes())[0]];

        endif;

        $shows = get_posts([
            'post_type' => 'show',
            'numberposts' => 1,
            'post_status' => 'publish',
            'meta_query' => [
                [
                    'key' => 'show_info_related_ticket',
                    'value' => $product_id
                ]
            ]
        ]);

        if( !empty( $shows ) ) :
            $show_name = get_the_title($shows[0]->ID);
            $show_name = str_replace('&', '"____"', $show_name);
            $show_name = str_replace(' ', '"__"', $show_name);
            $show_name = str_replace('#038;', '', $show_name);
        endif;

        $qr_link = get_the_permalink($ticket_info_page->ID);
        $qr_link .= '?show_name=' . $show_name ?? '';
        $qr_link .= '&customer_name=' .  $user['name'] . ' ' . $user['lastname'];
        $qr_link .= '&customer_email=' . $user['email'];
        $qr_link .= '&order_id=' . $order_id;
        $qr_link .= '&ticket_id=' . $ticket_sold_id;
        $qr_link .= '&ticket_price=' . $price;
        if( !empty( $variation_value ) ) :
            $qr_link .= '&ticket_type=' . $variation_value;
        endif;
        $qr_link .= '&venue_name=' . $venue_title ;
        $qr_link .= '&event_date=' . $date;

//        $qr_link = str_replace('#', '', $qr_link);
        $qr_link = str_replace(' ', '"__"', $qr_link);

        $html = '
                <!DOCTYPE html>
                    <html lang="en">
                    <head>
                        <meta charset="UTF-8">
                        <title>Label</title>
                        <style>

                            body {
                                margin: 0;
                            }
                        
                            h1, h2, h3 {
                                margin: 10px 0;
                            }
                        
                            ul {
                                list-style: none;
                                margin: 0;
                                padding: 0;
                            }
                        
                            a {
                                text-decoration: none;
                            }
                        
                            .img > img {
                                width: 100%;
                                height: 100%;
                                -o-object-fit: cover;
                                object-fit: cover;
                                display: block;
                            }
                        
                            table {
                                border-spacing: 0
                            }
                        
                            p {
                                margin: 0;
                            }
                            .pdf__container{
                        
                            }
                            .pdf {
                                background:#F2F2F2;
                                font-family: "Helvetica";
                        
                            }
                        
                            .pdf__header {
                                background: #212121;
                                padding: 20px 0 ;
                            }
                            .pdf__logo{
                                width: 118px;
                                height: 62px;
                                margin: 0 auto;
                            }
                            .pdf__wrap{
                                flex-grow: 1;
                                padding: 40px 30px;
                            }
                            .pdf__qr{
                        
                            }
                            .pdf__qr-img{
                                min-width: 200px;
                                width: 200px;
                                height: 200px;
                            }
                            .pdf__price{
                                color:#797670;
                                font: 400 10px / 11.5px "Helvetica", sans-serif;
                                margin-bottom: 6px;
                            }
                            .pdf__order{
                                color:#797670;
                                font: 400 10px / 11.5px "Helvetica", sans-serif;
                                margin-bottom: 10px;
                            }
                        
                            .pdf__title {
                                font: 700 20px / 23px "Helvetica", sans-serif;
                                color:#212121;
                                margin-bottom: 10px;
                            }
                        
                            .pdf__date {
                                font: 400 12px / 13.8px "Helvetica", sans-serif;
                                color:#212121;
                                margin-bottom: 8px;
                            }
                        
                            .pdf__place {
                                font: 400 12px / 13.8px "Helvetica", sans-serif;
                                color:#212121;
                                margin-bottom: 4px;
                            }
                            .pdf__address{
                                font: 400 12px / 13.8px "Helvetica", sans-serif;
                                color:#9E9B94;
                                font-style: italic;
                                margin-bottom: 8px;
                            }
                        
                            .pdf__owner {
                                font: 400 12px / 13.8px "Helvetica", sans-serif;
                                color: #212121;
                                margin-bottom: 8px;
                            }
                        
                            .pdf__type {
                                font: 700 12px / 13.8px "Helvetica", sans-serif;
                                color: #212121;
                            }
                            .pdf__img {
                                width: 200px;
                                min-width: 200px;
                                height: 249px;
                                border: 1px solid #212121;
                                box-sizing: border-box;
                            }
                            td{
                                vertical-align: top;
                            }
                        
                            .pdf__ticket-desc h2{
                                font: 700 12px / normal "Helvetica", sans-serif;
                        
                            }
                            .pdf__ticket-desc h3{
                                font: 700 10px / normal "Helvetica", sans-serif;
                        
                        
                            }
                            .pdf__ticket-desc p{
                                font: 400 10px / normal "Helvetica", sans-serif;
                                color: #797670
                            }
                            .pdf__visa{
                                margin: 80px 0 30px;
                                background: #fff;
                                border: 2px solid #212121;
                                padding: 20px;
                                display: flex;
                                justify-content: space-between;
                                gap:10px
                            }
                            .pdf__visa h3{
                                font: 700 10px / 11.5px "Helvetica", sans-serif;
                            }
                            .pdf__visa-img{
                                width: 92px;
                                height: auto;
                            }
                        
                            .pdf-footer{
                                background: #212121;
                                padding: 20px 26px;
                            }
                            .pdf-footer>*{
                                font: 400 12px / 13.8px "Helvetica", sans-serif;
                                color: #FFF;
                            }
                            .pdf-footer a{
                                color: #D98C72;
                                text-decoration-line: underline;
                            }
                        
                            @media print {
                                * {
                                    print-color-adjust: exact;
                                    -webkit-print-color-adjust: exact;
                                }
                            }
                        
                        
                            /*.pdf{*/
                            /*    width: 100vw;*/
                            /*}*/
                            /*.pdf__header{*/
                            /*    width: 100%;*/
                            /*    background: #212121;*/
                            /*    padding: 20px 0;*/
                            /*}*/
                            /*.pdf__logo{*/
                            /*   width: 118px;*/
                            /*    height: 62px;*/
                            /*}*/
                            /*.pdf__qr-img{*/
                        
                            /*}*/
                        </style>
                    </head>
                    <body>
                    <div class="pdf">
                        <div class="pdf__container container">
                            <div class="pdf__header">
                                <div class="pdf__logo">
                                    <img src="data:image/png;base64,' . base64_encode(file_get_contents($logo_filename)) . '" alt="">
                                </div>
                            </div>
                            <div class="pdf__wrap">
                                <table class="pdf__qr">

                                    <tr>
                                         <td>
                                            <div class="pdf__qr-img img">
                                                <img src="' . (new QRCode())->render($qr_link) . '" alt="">
                                            </div>
                                        </td>
                                        <td>
                                    <div class="pdf__desc" style="padding-left:30px">
                                       ';


        $html .= '<h2 class="pdf__price">Price: £' . $price . '.</h2>';


        $html .= '<h2 class="pdf__order">Order: ' . $order_id . ', Ticket: ' . $ticket_sold_id . ', '. $order_date_format . '</h2>';

        if (!empty($title)) :
            $html .= '<h1 class="pdf__title">' . $title . '</h1>';
        endif;

        if (!empty($date)) :
            $html .= '<h2 class="pdf__date">' . $date . '</h2>';
        endif;

        if (!empty($venue_title)) :
            $html .= '<h2 class="pdf__place">' . $venue_title . '</h2>';
        endif;

        if (!empty($location)) :
            $html .= '<h2 class="pdf__address">' . $location . '</h2>';
        endif;

        $html .= '<h2 class="pdf__owner">Ticket owner: ' . $user['name'] . ' ' . $user['lastname'] . '</h2>';


            if (!empty($variation_value)) :
                $html .= '<h2 class="pdf__type">' . $variation_value . '</h2>';
            endif;


        $html .= '                  </div>
        </td>
        </tr>
                               <tr>
                                <td>';

        $post_thumbnail_id = get_post_thumbnail_id($product_id);

        if (!empty($post_thumbnail_id)) :

            //$post_thumbnail_path = get_attached_file($post_thumbnail_id);
            $post_thumbnail_path = wp_get_attachment_image_url($post_thumbnail_id, 'thumb-370x460-cropped', true); 

            $post_thumbnail_content = file_get_contents($post_thumbnail_path);
            $post_thumbnail_extension = pathinfo($post_thumbnail_path, PATHINFO_EXTENSION);

            $html .= '
                <div class="pdf__img img">
                    <img src="data:image/' . $post_thumbnail_extension . ';base64,' . base64_encode($post_thumbnail_content) . '" alt="">
                </div></td><td>
            ';

        endif;

        $html .= '
                  <div class="pdf__ticket-desc" style="padding-left:30px">
                     <h2>THIS IS YOUR TICKET!</h2>
                     <h3>PRINT AND SHOW THE ENTIRE LETTER AT THE ENTRANCE.</h3>
                     <p>A unique barcode can only be used once. Do not copy this ticket or publish it on the Internet. This may prevent you from entering the event. The organizer is not responsible for saving data.</p>
                  </div>
              </td></tr></table>';

        if (isset($ticket_info['sponsored_by']) and !empty($ticket_info['sponsored_by'])) :

            $html .= '
                <div class="pdf__visa">
                    <h3>Sponsored: ' . $ticket_info['sponsored_by'] . '</h3>';

            if (!empty($ticket_info['sponsor_logo'])) :

                $sponsor_logo_path = get_attached_file($ticket_info['sponsor_logo']);
                $sponsor_logo_content = file_get_contents($sponsor_logo_path);
                $sponsor_logo_extension = pathinfo($sponsor_logo_path, PATHINFO_EXTENSION);

                $html .= '
                        <div  class="pdf__visa-img">
                            <img src="data:image/' . $sponsor_logo_extension . ';base64,' . base64_encode($sponsor_logo_content) . '" alt="">
                        </div>';
            endif;

            $html .= '</div>';
        endif;

        $html .= '
                            </div>
                            <div class="pdf-footer">
                                <h2 class="pdf__sponsored">Sponsored by CMDN Live: <a href="' . $blog_url . '">' . $blog_url . '</a></h2>
                            </div>
                        </div>
                    </div>
                    '; ?>

        <?php
//
//        if (isset($ticket_info['sponsor']) and !empty($ticket_info['sponsor'])) :
//            $html .= '<h2 class="pdf__sponsored">Sponsored by <strong>' . $ticket_info['sponsor'] . '</strong></h2>';
//        endif;

//
//        if (!empty($variation_id)) :
//
//            $variation = wc_get_product($variation_id);
//            $variation_value = $variation->get_attributes()[array_keys($variation->get_attributes())[0]];
//
//            if (!empty($variation_value)) :
//                $html .= '<h2 class="pdf__type">' . $variation_value . '</h2>';
//            endif;
//
//        endif;
//

//
        $html .= '</div>
        </body>
        </html>
        ';

        return $html;
    }

    public static function add_cron_delete_pdfs()
    {

        if (!wp_next_scheduled('delete_action')) {
            wp_schedule_event(time(), 'daily', 'delete_action');
        }

    }

    public static function delete_pdfs_daily()
    {

        $log_file = wp_get_upload_dir()['basedir'] . '/daily_log.json'; // Змініть шлях до файлу, якщо потрібно

        $file_date = [
            'date' => date('m/d/Y H:i:s')
        ];

        file_put_contents($log_file, json_encode($file_date) . PHP_EOL, FILE_APPEND);

        $date = new \DateTime('1 month ago');
        $date_string = $date->format('Ymd');

        $pdfs_directory = wp_get_upload_dir()['basedir'] . '/pdf/';
        $files = scandir($pdfs_directory);

        foreach ($files as $item) :

            $file_date = date('Ymd', filemtime($pdfs_directory . $item));

            if ((int)$date_string > (int)$file_date) :

                unlink($pdfs_directory . $item);

            endif;

        endforeach;

    }

}