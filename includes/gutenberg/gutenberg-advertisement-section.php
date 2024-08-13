<?php

class Gutenberg_Advertisement_Section {

    public static function init_auto() {

        add_action( 'acf/init', [ __CLASS__, 'acf_register_block_type' ] );
        add_action( 'acf/init', [ __CLASS__, 'acf_add_local_field_group' ] );

    }

    public static function acf_register_block_type() {

        acf_register_block_type( [
            'name' => 'advertisement-block',
            'title' => 'Advertisement block',
            'description' => '',
            'category' => 'custom',
            'icon' => '',
            'post_types' => ['venue'],
            'keywords' => [],
            'mode' => 'edit',
            'align' => 'full',
            'render_callback' => [ __CLASS__, 'render' ],
        ]);

    }

    public static function render() {

        $advertisement_section = get_field('advertisement_section');
        $title = $advertisement_section['title'];
        $subtitle = $advertisement_section['subtitle'];
        $image = $advertisement_section['image'];
        $link = $advertisement_section['link'];

        ?>

            <div class="adv">
                <?php if( !empty( $image ) ) : ?>
                    <div class="adv__img img">
                        <img src="<?php echo $image; ?>" alt="">
                    </div>
                <?php endif; ?>
                <div class="adv__block">
                    <div class="adv__text">
                        <?php if( !empty( $title ) ) : ?>
                            <h3><?php echo $title; ?></h3>
                        <?php endif; ?>
                        <?php if( !empty( $subtitle ) ) : ?>
                            <p><?php echo $subtitle; ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if( !empty( $link ) ) : ?>
                        <a href="<?php echo $link['url']; ?>" class="section__button"><?php echo $link['title']; ?></a>
                    <?php endif; ?>
                </div>
            </div>

        <?php
    }

    public static function acf_add_local_field_group() {
        if ( ! function_exists( 'acf_add_local_field_group' ) ) {
            return;
        }

        acf_add_local_field_group( array(
            'key' => 'group_64e86a81d7a09',
            'title' => 'Advertisement block',
            'fields' => array(
                array(
                    'key' => 'field_64e86a8283498',
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
                            'key' => 'field_64e86a9d83499',
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
                            'key' => 'field_64e86aa28349a',
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
                            'key' => 'field_64e86aa98349b',
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
                            'key' => 'field_64e86ab28349c',
                            'label' => 'Link',
                            'name' => 'link',
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
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/advertisement-block',
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