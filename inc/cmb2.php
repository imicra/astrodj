<?php
/**
 * Get the bootstrap! CMB2
 * 
 * @package astrodj
 */
if ( file_exists( __DIR__ . '/cmb2/init.php' ) ) {
  require_once __DIR__ . '/cmb2/init.php';
} elseif ( file_exists(  __DIR__ . '/CMB2/init.php' ) ) {
  require_once __DIR__ . '/CMB2/init.php';
}

add_action( 'cmb2_admin_init', 'cmb2_astrodj_metaboxes' );

function cmb2_astrodj_metaboxes() {
    
    // Start with an underscore to hide fields from custom fields list
    $prefix = 'astrodj_';

    /**
     * Initiate the metabox for Post meta
     */
    $post_meta = new_cmb2_box( array(
        'id'            => $prefix . 'post_meta_metabox',
        'title'         => __( 'Данные Поста', 'cmb2' ),
        'object_types'  => array( 'post' ), // Post type
        //'show_on'       => array( 'key' => 'id', 'value' => array( 2 ) ),
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        'closed'     => true, // Keep the metabox closed by default
    ) );

    $post_meta->add_field( array(
        'name' => 'Показать',
        'desc' => 'Вкл./откл. показ этих данных',
        'id'   => $prefix .  'postmeta_checkbox',
        'type' => 'checkbox',
    ) );

    $post_meta->add_field( array(
        'name' => 'Дата',
        'id'   => $prefix .  'textdate_timestamp',
        'type' => 'text_date_timestamp',
        // 'timezone_meta_key' => 'wiki_test_timezone',
    ) );

    $post_meta->add_field( array(
        'name'             => 'Камера',
        'desc'             => 'Выбор камеры',
        'id'               => $prefix . 'select_camera',
        'type'             => 'select',
        'show_option_none' => true,
        'default'          => 'canon',
        'options'          => array(
            'canon1dx' => __( 'Canon EOS-1D X', 'cmb2' ),
            'canon'    => __( 'Canon 550D', 'cmb2' ),
            'smena'    => __( 'Смена-7', 'cmb2' ),
        ),
    ) );
}

/**
 * Initiate the metabox for Portfolio post type
 * and Show in REST them
 */
function astrodj_register_portfolio_metabox() {
    $prefix = 'astrodj_';

    $portfolio_meta = new_cmb2_box( array(
        'id'            => $prefix . 'portfolio_meta_metabox',
        'title'         => __( 'Формат Снимка', 'cmb2' ),
        'object_types'  => array( 'portfolio', 'stock', 'cats', 'archive' ), // Post type
        //'show_on'       => array( 'key' => 'id', 'value' => array( 2 ) ),
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        'closed'        => false, // Keep the metabox closed by default
        'show_in_rest'  => true
    ) );

    $portfolio_meta->add_field( array(
        'name'             => 'Формат снимка',
        'desc'             => 'Размер и ориентация снимка в сетке галереи',
        'id'               => $prefix . 'radio_orientation',
        'type'             => 'radio_inline',
        'show_option_none' => 'Нормальная',
        'options'          => array(
            'featured' => 'Большая',
            'vertical' => 'Вертикальная',
            'panorama' => 'Панорама',
        ),
    ) );
        
    $portfolio_post = new_cmb2_box( array(
        'id'            => $prefix . 'portfolio_post_metabox',
        'title'         => __( 'Мета', 'cmb2' ),
        'object_types'  => array( 'portfolio', 'stock', 'cats' ), // Post type
        //'show_on'       => array( 'key' => 'id', 'value' => array( 2 ) ),
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        'closed'        => true, // Keep the metabox closed by default
        'show_in_rest'  => true
    ) );
        
    // Date/Time
    $portfolio_post->add_field( array(
        'name' => 'Date/Time Picker Combo (UNIX timestamp)',
        'id'   => $prefix . 'portfolio_datetime_timestamp',
        'type' => 'text_datetime_timestamp',
        'desc' => 'Дата съемки',
    ) );

    // Location
    $portfolio_post->add_field( array(
        'name' => 'Место Съемки',
        'desc' => 'Не используется в iframe',
        'type' => 'title',
        'id'   => $prefix . 'image_location_title'
    ) );

    $portfolio_post->add_field( array(
        'name'    => 'Страна',
        'desc'    => 'Страна',
        'default' => 'Россия',
        'id'      => $prefix . 'image_location_country',
        'type'    => 'text_small'
    ) );

    $portfolio_post->add_field( array(
        'name'    => 'Регион',
        'desc'    => 'Регион',
        'default' => 'Коми',
        'id'      => $prefix . 'image_location_region',
        'type'    => 'text_small'
    ) );

    $portfolio_post->add_field( array(
        'name'    => 'Место',
        'desc'    => 'Место',
        'id'      => $prefix . 'image_location_location',
        'type'    => 'text_medium',
    ) );

    // Short Details
    $short_detail_block = new_cmb2_box( array(
        'id'            => $prefix . 'short_detail_metabox',
        'title'         => __( 'Детали съемки', 'cmb2' ),
        'object_types'  => array( 'portfolio' ), // Post type
        //'show_on'       => array( 'key' => 'id', 'value' => array( 2 ) ),
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        'closed'        => true, // Keep the metabox closed by default
        'show_in_rest'  => true
    ) );

    $short_detail_block->add_field( array(
        'name' => 'Описание',
        'description' => 'Write a short description for this entry',
        'id'   => $prefix . 'short_detail_description',
        'type' => 'textarea_small',
    ) );

    $detail_field_id = $short_detail_block->add_field( array(
        'id'          => $prefix . 'short_detail_repeat_group',
        'type'        => 'group',
        'description' => __( 'Generates reusable form entries', 'cmb2' ),
        // 'repeatable'  => false, // use false if you want non-repeatable group
        'options'     => array(
            'group_title'       => __( 'Entry {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
            'add_button'        => __( 'Add Another Entry', 'cmb2' ),
            'remove_button'     => __( 'Remove Entry', 'cmb2' ),
            'sortable'          => true,
            // 'closed'         => true, // true to have the groups closed by default
            // 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
        ),
    ) );

    $short_detail_block->add_group_field( $detail_field_id, array(
        'name' => 'Участок снимка',
        'id'   => $prefix . 'short_detail_item',
        'type' => 'text',
        // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
    ) );

    $short_detail_block->add_group_field( $detail_field_id, array(
        'name' => 'Параметры',
        'id'   => $prefix . 'short_detail_item_param',
        'type' => 'text',
        // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
    ) );
}
add_action( 'cmb2_init', 'astrodj_register_portfolio_metabox' );