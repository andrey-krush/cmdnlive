<?php

class Gutenberg_Quote_Section {

    public static function init_auto() {

        add_action( 'acf/init', [ __CLASS__, 'acf_register_block_type' ] );
        add_action( 'acf/init', [ __CLASS__, 'acf_add_local_field_group' ] );

    }

    public static function acf_register_block_type() {
        acf_register_block_type( [
            'name' => 'quote-block',
            'title' => 'Quote block',
            'description' => '',
            'category' => 'custom',
            'icon' => '',
            'post_types' => ['post'],
            'keywords' => [],
            'mode' => 'edit',
            'align' => 'full',
            'render_callback' => [ __CLASS__, 'render' ],
        ]);
    }

    public static function render() {

        $quote = get_field('quote');
        $text_under_quote = get_field('text_under_quote');
        if( !empty( $quote ) ) : ?>

            <blockquote><?php echo $quote; ?>
                <?php if( !empty( $text_under_quote ) ) : ?>
                    <p><?php echo $text_under_quote; ?></p>
                <?php endif; ?>
            </blockquote>

        <?php endif;
    }

    public static function acf_add_local_field_group() {
        if ( ! function_exists( 'acf_add_local_field_group' ) ) {
            return;
        }

        acf_add_local_field_group( array(
            'key' => 'group_64e366a2e88b8',
            'title' => 'Quote block',
            'fields' => array(
                array(
                    'key' => 'field_64e366a3407eb',
                    'label' => 'Quote',
                    'name' => 'quote',
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
                    'key' => 'field_64e366eb407ec',
                    'label' => 'Text under quote',
                    'name' => 'text_under_quote',
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
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/quote-block',
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