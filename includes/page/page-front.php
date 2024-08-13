<?php

class Page_Front {

    public static function init_auto() {

        add_action( 'wp_ajax_subscribe', [__CLASS__, 'subscribe'] );
        add_action( 'wp_ajax_nopriv_subscribe', [__CLASS__, 'subscribe'] );

        add_action( 'wp_ajax_load_more_band', [__CLASS__, 'load_more_band'] );
        add_action( 'wp_ajax_nopriv_load_more_band', [__CLASS__, 'load_more_band'] );

        add_action( 'wp_ajax_join', [__CLASS__, 'joinAjaxHandler'] );
        add_action( 'wp_ajax_nopriv_join', [__CLASS__, 'joinAjaxHandler'] );

        add_action( 'init', [__CLASS__, 'acf_add_local_field_group'] );
    }

    public static function joinAjaxHandler () {

        unset($_POST['action']);

        $title = 'New application from Join us Form ' . date('m/d/Y, H:i');

        $post_id = wp_insert_post([
            'post_type' => 'application',
            'post_title' => $title,
            'post_status'   => 'publish',
        ]);

        $mail_message = $title . '<br>';

        $admin_mail = get_option('admin_email');

        foreach ( $_POST as $key => $item ) :

            if( !empty( $item ) ) :
                update_field($key, $item, $post_id);
                $message_key = str_replace('_', ' ', $key);
                $message_key = mb_strtoupper(mb_substr($message_key, 0, 1)) . mb_substr($message_key, 1);
                $mail_message .= $message_key . ' : ' . $item . '<br>' ;
            endif;

        endforeach;

        $headers = [
            'From: CMDN <wordpress@cmdn.com>',
            'content-type: text/html',
        ];

        wp_mail($admin_mail, 'New application from site', $mail_message, $headers);

    }

    public static function subscribe() {

        if( !empty( $_POST['email'] ) ) :

            $user_mail = $_POST['email'];

            $query = new WP_Query([
                'post_type'      => 'client',
                'title'          => $user_mail,
                'posts_per_page' => 1,
                'post_status'    => 'publish'
            ]);

            if( empty( $query->posts ) ) :

                $post_id = wp_insert_post([
                    'post_type'     => 'client',
                    'post_title'    => $user_mail,
                    'post_status'   => 'publish',
                ]);

                wp_send_json_success();

            else :

                wp_send_json_error(array( 'message' => 'user_exists' ));

            endif;

        endif;

    }

    public static function load_more_band() {

        if( !empty( $_POST['page'] ) ) :

            $args = [
                'post_type' => 'band',
                'post_status' => 'publish',
                'posts_per_page' => 4,
                'paged' => $_POST['page']
            ];

            $bands_query = new WP_Query($args);
            $posts_count = $bands_query->found_posts;
            $counter = 0;

            $next_page_args = [
                'post_type' => 'band',
                'post_status' => 'publish',
                'posts_per_page' => 4,
                'paged' => $_POST['page'] + 1
            ];

            $next_page_bands_query = new WP_Query($next_page_args);

            if( $bands_query->have_posts() ) : ?>
                <ul class="music__list">
                    <?php while ($bands_query->have_posts()) : $bands_query->the_post(); ?>
                        <?php if ($counter < 4) : ?>
                            <li class="music__item">
                                <?php $post_thumbnail = get_the_post_thumbnail_url(); ?>
                                <?php if ( !empty ($post_thumbnail ) ) : ?>
                                    <div class="music__img img">
                                        <img src="<?php echo $post_thumbnail; ?>" alt="">
                                    </div>
                                <?php endif; ?>
                                <div class="music__text">
                                    <div class="music__desc">
                                        <h3 class="music__name"><?php echo get_the_title(); ?></h3>
                                        <p><?php echo strip_tags(apply_filters('the_content', get_the_content( post: get_the_ID() ))); ?></p>                                    </div>
                                    <a class="music__link" href="<?php echo get_the_permalink(); ?>">Read more</a>
                                </div>
                            </li>
                        <?php endif; ?>
                        <?php $counter++; ?>
                    <?php endwhile; ?>
                </ul>
                <?php if ($next_page_bands_query->have_posts()) : ?>
                    <button class="section__button music__more">Load More</button>
                <?php endif; ?>
            <?php endif;

        endif;

        die;
    }

    public static function acf_add_local_field_group() {
        if ( ! function_exists( 'acf_add_local_field_group' ) ) {
            return;
        }

        acf_add_local_field_group( array(
            'key' => 'group_64db765a95390',
            'title' => 'Front page',
            'fields' => array(
                array(
                    'key' => 'field_64db765b17267',
                    'label' => 'Advertisement section',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_64db7d5c17268',
                    'label' => '',
                    'name' => 'advertisement_section',
                    'aria-label' => '',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_64db7d6c17269',
                            'label' => 'Text',
                            'name' => 'text',
                            'aria-label' => '',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_64db7d8a1726a',
                    'label' => 'Slider section',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_64db7da31726b',
                    'label' => '',
                    'name' => 'slider_section',
                    'aria-label' => '',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_64db7daf1726c',
                            'label' => 'Ticker text',
                            'name' => 'ticker_text',
                            'aria-label' => '',
                            'type' => 'textarea',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'rows' => 2,
                            'placeholder' => '',
                            'new_lines' => '',
                        ),
                        array(
                            'key' => 'field_64db7dda1726d',
                            'label' => 'Slider',
                            'name' => 'slider',
                            'aria-label' => '',
                            'type' => 'repeater',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'layout' => 'table',
                            'pagination' => 0,
                            'min' => 0,
                            'max' => 0,
                            'collapsed' => '',
                            'button_label' => 'Add Row',
                            'rows_per_page' => 20,
                            'sub_fields' => array(
                                array(
                                    'key' => 'field_64f5e33f4ddf1',
                                    'label' => 'Post',
                                    'name' => 'post',
                                    'aria-label' => '',
                                    'type' => 'post_object',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'post_type' => array(
                                        0 => 'post',
                                    ),
                                    'post_status' => array(
                                        0 => 'publish',
                                    ),
                                    'taxonomy' => '',
                                    'return_format' => 'id',
                                    'multiple' => 0,
                                    'allow_null' => 0,
                                    'bidirectional' => 0,
                                    'ui' => 1,
                                    'bidirectional_target' => array(
                                    ),
                                    'parent_repeater' => 'field_64db7dda1726d',
                                ),
                                array(
                                    'key' => 'field_64f5e3534ddf2',
                                    'label' => 'Text colour',
                                    'name' => 'text_colour',
                                    'aria-label' => '',
                                    'type' => 'radio',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'choices' => array(
                                        'white' => 'White',
                                        'black' => 'Black',
                                    ),
                                    'default_value' => '',
                                    'return_format' => 'value',
                                    'allow_null' => 0,
                                    'other_choice' => 0,
                                    'layout' => 'vertical',
                                    'save_other_choice' => 0,
                                    'parent_repeater' => 'field_64db7dda1726d',
                                ),
                            ),
                        ),
                    ),
                ),
                array(
                    'key' => 'field_64db841ead6e1',
                    'label' => 'Cover story section',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_64db8431ad6e2',
                    'label' => '',
                    'name' => 'cover_story_section',
                    'aria-label' => '',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_64db8438ad6e3',
                            'label' => 'Title',
                            'name' => 'title',
                            'aria-label' => '',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                        array(
                            'key' => 'field_64db8471ad6e4',
                            'label' => 'Image',
                            'name' => 'image',
                            'aria-label' => '',
                            'type' => 'image',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'url',
                            'library' => 'all',
                            'min_width' => '',
                            'min_height' => '',
                            'min_size' => '',
                            'max_width' => '',
                            'max_height' => '',
                            'max_size' => '',
                            'mime_types' => '',
                            'preview_size' => 'medium',
                        ),
                        array(
                            'key' => 'field_64db8488ad6e5',
                            'label' => 'Interview title',
                            'name' => 'interview_title',
                            'aria-label' => '',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                        array(
                            'key' => 'field_64db8501ad6e6',
                            'label' => 'Interview text',
                            'name' => 'interview_text',
                            'aria-label' => '',
                            'type' => 'textarea',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'rows' => 2,
                            'placeholder' => '',
                            'new_lines' => 'br',
                        ),
                        array(
                            'key' => 'field_64db88d0a9c77',
                            'label' => 'Button',
                            'name' => 'button',
                            'aria-label' => '',
                            'type' => 'link',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_64df1e48bea5e',
                    'label' => 'Posts section',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_64df1e59bea5f',
                    'label' => '',
                    'name' => 'posts_section',
                    'aria-label' => '',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_64df1e69bea60',
                            'label' => 'Posts',
                            'name' => 'posts',
                            'aria-label' => '',
                            'type' => 'relationship',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'post_type' => array(
                                0 => 'post',
                            ),
                            'post_status' => '',
                            'taxonomy' => '',
                            'filters' => array(
                                0 => 'search',
                                1 => 'taxonomy',
                            ),
                            'return_format' => 'object',
                            'min' => '',
                            'max' => 10,
                            'elements' => '',
                            'bidirectional' => 0,
                            'bidirectional_target' => array(
                            ),
                        ),
                        array(
                            'key' => 'field_64df1ec0bea61',
                            'label' => 'Button',
                            'name' => 'button',
                            'aria-label' => '',
                            'type' => 'link',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_64df57869c6b4',
                    'label' => 'Bands section',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_64df57999c6b5',
                    'label' => '',
                    'name' => 'bands_section',
                    'aria-label' => '',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_64df57c09c6b6',
                            'label' => 'Title',
                            'name' => 'title',
                            'aria-label' => '',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                        array(
                            'key' => 'field_64df257c09c6b6',
                            'label' => 'Subtitle',
                            'name' => 'subtitle',
                            'aria-label' => '',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                        array(
                            'key' => 'field_65c3400fd7e98',
                            'label' => 'Bands',
                            'name' => 'bands',
                            'aria-label' => '',
                            'type' => 'relationship',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'post_type' => array(
                                0 => 'band',
                            ),
                            'post_status' => array(
                                0 => 'publish',
                            ),
                            'taxonomy' => '',
                            'filters' => array(
                                0 => 'search',
                                1 => 'taxonomy',
                            ),
                            'return_format' => 'id',
                            'min' => '',
                            'max' => '',
                            'elements' => '',
                            'bidirectional' => 0,
                            'bidirectional_target' => array(
                            ),
                        ),
                        array(
                            'key' => 'field_64df121ec0bea61',
                            'label' => 'Button',
                            'name' => 'button',
                            'aria-label' => '',
                            'type' => 'link',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'array',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_64de0874a945a',
                    'label' => 'Gallery section',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_64de08c9a945b',
                    'label' => '',
                    'name' => 'gallery_section',
                    'aria-label' => '',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_64de09c5d7b93',
                            'label' => 'Title',
                            'name' => 'title',
                            'aria-label' => '',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                        array(
                            'key' => 'field_64de08dea945c',
                            'label' => 'Gallery',
                            'name' => 'gallery',
                            'aria-label' => '',
                            'type' => 'repeater',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'layout' => 'table',
                            'pagination' => 0,
                            'min' => 0,
                            'max' => 0,
                            'collapsed' => '',
                            'button_label' => 'Add Row',
                            'rows_per_page' => 20,
                            'sub_fields' => array(
                                array(
                                    'key' => 'field_64de08f0a945d',
                                    'label' => 'Link',
                                    'name' => 'link',
                                    'aria-label' => '',
                                    'type' => 'url',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'default_value' => '',
                                    'placeholder' => '',
                                    'parent_repeater' => 'field_64de08dea945c',
                                ),
                                array(
                                    'key' => 'field_64de08f8a945e',
                                    'label' => 'Image',
                                    'name' => 'image',
                                    'aria-label' => '',
                                    'type' => 'image',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'return_format' => 'url',
                                    'library' => 'all',
                                    'min_width' => '',
                                    'min_height' => '',
                                    'min_size' => '',
                                    'max_width' => '',
                                    'max_height' => '',
                                    'max_size' => '',
                                    'mime_types' => '',
                                    'preview_size' => 'medium',
                                    'parent_repeater' => 'field_64de08dea945c',
                                ),
                                array(
                                    'key' => 'field_64fe21cc0439562',
                                    'label' => 'Title',
                                    'name' => 'title',
                                    'aria-label' => '',
                                    'type' => 'text',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'default_value' => '',
                                    'maxlength' => '',
                                    'placeholder' => '',
                                    'prepend' => '',
                                    'append' => '',
                                    'parent_repeater' => 'field_64de08dea945c',
                                ),
                                array(
                                    'key' => 'field_64db838d0a9c77',
                                    'label' => 'Photographer link',
                                    'name' => 'photographer_link',
                                    'aria-label' => '',
                                    'type' => 'link',
                                    'instructions' => '',
                                    'required' => 0,
                                    'conditional_logic' => 0,
                                    'wrapper' => array(
                                        'width' => '',
                                        'class' => '',
                                        'id' => '',
                                    ),
                                    'return_format' => 'array',
                                    'parent_repeater' => 'field_64de08dea945c',
                                ),
                            ),
                        ),
                    ),
                ),
                array(
                    'key' => 'field_64fecbb839560',
                    'label' => 'Instagram section',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_64fecbcc39561',
                    'label' => '',
                    'name' => 'instagram_section',
                    'aria-label' => '',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_64fecc0439562',
                            'label' => 'Title',
                            'name' => 'title',
                            'aria-label' => '',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                        array(
                            'key' => 'field_64fecc0939563',
                            'label' => 'Feed',
                            'name' => 'feed',
                            'aria-label' => '',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_64de0d7bb954a',
                    'label' => 'Join us section',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_64de0d8db954b',
                    'label' => '',
                    'name' => 'join_us_section',
                    'aria-label' => '',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_64de0dbeb954c',
                            'label' => 'Image',
                            'name' => 'image',
                            'aria-label' => '',
                            'type' => 'image',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'return_format' => 'url',
                            'library' => 'all',
                            'min_width' => '',
                            'min_height' => '',
                            'min_size' => '',
                            'max_width' => '',
                            'max_height' => '',
                            'max_size' => '',
                            'mime_types' => '',
                            'preview_size' => 'medium',
                        ),
                        array(
                            'key' => 'field_64de0dd0b954d',
                            'label' => 'Title',
                            'name' => 'title',
                            'aria-label' => '',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                        array(
                            'key' => 'field_64de0de1b954e',
                            'label' => 'Subtitle',
                            'name' => 'subtitle',
                            'aria-label' => '',
                            'type' => 'textarea',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'rows' => '',
                            'placeholder' => '',
                            'new_lines' => 'br',
                        ),
                        array(
                            'key' => 'field_64de0debb954f',
                            'label' => 'Button text',
                            'name' => 'button_text',
                            'aria-label' => '',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                    ),
                ),
                array(
                    'key' => 'field_64de13b5df9fd',
                    'label' => 'Subscription section',
                    'name' => '',
                    'aria-label' => '',
                    'type' => 'tab',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'placement' => 'top',
                    'endpoint' => 0,
                ),
                array(
                    'key' => 'field_64de13c2df9fe',
                    'label' => '',
                    'name' => 'subscription_section',
                    'aria-label' => '',
                    'type' => 'group',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'layout' => 'block',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_64de13d0df9ff',
                            'label' => 'Title',
                            'name' => 'title',
                            'aria-label' => '',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                        array(
                            'key' => 'field_64de13d7dfa00',
                            'label' => 'Subtitle',
                            'name' => 'subtitle',
                            'aria-label' => '',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                        array(
                            'key' => 'field_64de13e8dfa01',
                            'label' => 'Button text',
                            'name' => 'button_text',
                            'aria-label' => '',
                            'type' => 'text',
                            'instructions' => '',
                            'required' => 0,
                            'conditional_logic' => 0,
                            'wrapper' => array(
                                'width' => '',
                                'class' => '',
                                'id' => '',
                            ),
                            'default_value' => '',
                            'maxlength' => '',
                            'placeholder' => '',
                            'prepend' => '',
                            'append' => '',
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'page_type',
                        'operator' => '==',
                        'value' => 'front_page',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
            'show_in_rest' => 0,
        ) );
    }

}