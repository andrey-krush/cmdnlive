<?php

class Gutenberg_Image_With_Caption_Section {

    public static function init_auto() {

        add_action( 'acf/init', [ __CLASS__, 'acf_register_block_type' ] );
        add_action( 'acf/init', [ __CLASS__, 'acf_add_local_field_group' ] );

    }

    public static function acf_register_block_type() {
        acf_register_block_type( [
            'name' => 'image-with-caption-block',
            'title' => 'Image with caption block',
            'description' => '',
            'category' => 'custom',
            'icon' => '',
            'post_types' => ['post', 'band'],
            'keywords' => [],
            'mode' => 'edit',
            'align' => 'full',
            'render_callback' => [ __CLASS__, 'render' ],
        ]);
    }

    public static function render() {

        $image = get_field('image');
        $caption = get_field('caption');
        if( !empty( $image ) ) : ?>

            <div class="article__content-img">
                <div class="img">
                    <img src="<?php echo $image; ?>" alt="">
                </div>
                <?php if( !empty( $caption ) ) : ?>
                    <h5><?php echo $caption; ?></h5>
                <?php endif; ?>
            </div>

        <?php endif;
    }

    public static function acf_add_local_field_group() {
        if ( ! function_exists( 'acf_add_local_field_group' ) ) {
            return;
        }

        acf_add_local_field_group( array(
            'key' => 'group_64e36a7e63bec',
            'title' => 'Image with caption block',
            'fields' => array(
                array(
                    'key' => 'field_64e36a7f22e49',
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
                    'key' => 'field_64e36aaa22e4a',
                    'label' => 'Caption',
                    'name' => 'caption',
                    'aria-label' => '',
                    'type' => 'wysiwyg',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'tabs' => 'visual',
                    'toolbar' => 'basic',
                    'media_upload' => 0,
                    'delay' => 0,
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/image-with-caption-block',
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