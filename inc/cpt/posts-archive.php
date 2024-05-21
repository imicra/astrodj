<?php
/**
 * File posts-archive.php.
 * 
 * Custom Post Type for archive Photography.
 * 
 * @package Astrodj
 */

function astrodj_archive_posttypes() {

    // Customizer settings
    $archive_subtitle = get_theme_mod( 'archive_subtitle', esc_html__( 'Архивные фото', 'astrodj' ) );
    if ( ! empty( $archive_subtitle ) ) {
        $subtitle = wp_kses_post( $archive_subtitle );
    } else {
        $subtitle = '';
    }
    
    $labels = array(
        'name'               => 'Архив',
        'singular_name'      => __( 'Архив элемент', 'astrodj' ),
        'menu_name'          => __( 'Архив', 'astrodj' ),
        'name_admin_bar'     => __( 'Архив', 'astrodj' ),
        'add_new'            => __( 'Новое', 'astrodj' ),
        'add_new_item'       => __( 'Добавить снимок', 'astrodj' ),
        'new_item'           => __( 'New archive item', 'astrodj' ),
        'edit_item'          => __( 'Редактировать пост снимка', 'astrodj' ),
        'view_item'          => __( 'View archive item', 'astrodj' ),
        'all_items'          => __( 'Архив', 'astrodj' ),
        'search_items'       => __( 'Search archive items', 'astrodj' ),
        'parent_item_colon'  => __( 'Parent archive item:', 'astrodj' ),
        'not_found'          => __( 'No archive items found.', 'astrodj' ),
        'not_found_in_trash' => __( 'No archive items found in Trash.', 'astrodj' ),
    );
    
    $args = array(
        'labels'              => $labels,
        'description'         => $subtitle,
        'public'              => true,
        'publicly_queryable'  => true,
        'exclude_from_search' => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_rest'        => true,
        'menu_icon'           => 'dashicons-format-gallery',
        'query_var'           => true,
        'rewrite'             => array( 'slug' => 'archive-photography', 'with_front' => false ),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => false,
        'menu_position'       => 23,
        'supports'            => array( 'title', 'thumbnail', 'page-attributes' ),
    );

    register_post_type( 'archive', $args );
}
add_action( 'init', 'astrodj_archive_posttypes' );
