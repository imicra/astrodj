<?php
/**
 * File posts-stock.php.
 * 
 * Custom Post Type for Stock Photography.
 * 
 * @package Astrodj
 */

function astrodj_stock_posttypes() {

    // Customizer settings
    $stock_subtitle = get_theme_mod( 'stock_subtitle', esc_html__( 'Интересные снимки, но не шедевры', 'astrodj' ) );
    if ( ! empty( $stock_subtitle ) ) {
        $subtitle = wp_kses_post( $stock_subtitle );
    } else {
        $subtitle = '';
    }
    
    $labels = array(
        'name'               => 'Сток',
        'singular_name'      => __( 'Stock item', 'astrodj' ),
        'menu_name'          => __( 'Сток', 'astrodj' ),
        'name_admin_bar'     => __( 'Stock', 'astrodj' ),
        'add_new'            => __( 'Новое', 'astrodj' ),
        'add_new_item'       => __( 'Добавить снимок', 'astrodj' ),
        'new_item'           => __( 'New Stock item', 'astrodj' ),
        'edit_item'          => __( 'Редактировать пост снимка', 'astrodj' ),
        'view_item'          => __( 'View Stock item', 'astrodj' ),
        'all_items'          => __( 'Все снимки', 'astrodj' ),
        'search_items'       => __( 'Search Stock items', 'astrodj' ),
        'parent_item_colon'  => __( 'Parent Stock item:', 'astrodj' ),
        'not_found'          => __( 'No Stock items found.', 'astrodj' ),
        'not_found_in_trash' => __( 'No Stock items found in Trash.', 'astrodj' ),
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
        'rewrite'             => array( 'slug' => 'stock-photography', 'with_front' => false ),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => true,
        'menu_position'       => 22,
        'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
        'taxonomies'          => array( 'stock-categories' )
    );
    register_post_type( 'stock', $args );
}
add_action( 'init', 'astrodj_stock_posttypes' );


// Stock Taxonomies
function astrodj_stock_taxonomies() {
	
    // Category
    $labels = array(
        'name'              => 'Категории фильтра',
        'singular_name'     => 'Категория фильтра',
        'search_items'      => 'Найти Категорию фильтра',
        'all_items'         => 'Все Категории фильтра',
        'parent_item'       => 'Родитель Категории фильтра',
        'parent_item_colon' => 'Родитель Категории фильтра:',
        'edit_item'         => 'Редактировать Категорию фильтра',
        'update_item'       => 'Обновить Категорию фильтра',
        'add_new_item'      => 'Добавить Категорию фильтра',
        'new_item_name'     => 'Новая Категория фильтра',
        'menu_name'         => 'Категория фильтра',
    );

    $args = array(
        'public'             => true,
        'publicly_queryable' => false,
        'hierarchical'       => true,
        'labels'             => $labels,
        'show_ui'            => true,
        'show_admin_column'  => true,
        'query_var'          => true,
        'show_in_rest'       => true,
        'rewrite'            => array( 'slug' => 'stock-categories', 'with_front' => false ),
        'show_in_nav_menus'  => false,
    );

    register_taxonomy( 'stock-categories', array( 'stock' ), $args );
}
add_action( 'init', 'astrodj_stock_taxonomies' );
