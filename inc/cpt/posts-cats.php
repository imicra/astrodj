<?php
/**
 * File posts-cats.php.
 * 
 * Custom Post Type for Cats Photography.
 * 
 * @package Astrodj
 */

function astrodj_cats_posttypes() {

    // Customizer settings
    $cats_title = get_theme_mod( 'cats_title', esc_html__( 'Коты', 'astrodj' ) );
    $cats_subtitle = get_theme_mod( 'cats_subtitle', esc_html__( 'Коты и другие случайные собаки', 'astrodj' ) );
    if ( ! empty( $cats_subtitle ) ) {
        $subtitle = wp_kses_post( $cats_subtitle );
    } else {
        $subtitle = '';
    }
    
    $labels = array(
        'name'               => $cats_title,
        'singular_name'      => __( 'Cats item', 'astrodj' ),
        'menu_name'          => __( 'Коты', 'astrodj' ),
        'name_admin_bar'     => __( 'Cats', 'astrodj' ),
        'add_new'            => __( 'Новое', 'astrodj' ),
        'add_new_item'       => __( 'Добавить снимок', 'astrodj' ),
        'new_item'           => __( 'New Cats item', 'astrodj' ),
        'edit_item'          => __( 'Редактировать пост снимка', 'astrodj' ),
        'view_item'          => __( 'View Cats item', 'astrodj' ),
        'all_items'          => __( 'Все снимки', 'astrodj' ),
        'search_items'       => __( 'Search Cats items', 'astrodj' ),
        'parent_item_colon'  => __( 'Parent Cats item:', 'astrodj' ),
        'not_found'          => __( 'No Cats items found.', 'astrodj' ),
        'not_found_in_trash' => __( 'No Cats items found in Trash.', 'astrodj' ),
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
        'rewrite'             => array( 'slug' => 'cats-photography', 'with_front' => false ),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => true,
        'menu_position'       => 22,
        'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
        'taxonomies'          => array( 'cats-categories' )
    );
    register_post_type( 'cats', $args );
}
add_action( 'init', 'astrodj_cats_posttypes' );


// Cats Taxonomies
function astrodj_cats_taxonomies() {
	
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
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true,
        'rewrite'           => array( 'slug' => 'cats-categories' ),
        'show_in_nav_menus'   => false,
    );

    register_taxonomy( 'cats-categories', array( 'cats' ), $args );
}
add_action( 'init', 'astrodj_cats_taxonomies' );
