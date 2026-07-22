<?php
/**
 * File posts-shop.php.
 * 
 * Custom Post Type for shop.
 * 
 * @package Astrodj
 */

function astrodj_shop_posttypes() {

    // Customizer settings
    $shop_subtitle = get_theme_mod( 'shop_description', esc_html__( 'Здесь можно купить фотографии и фотокалендари', 'astrodj' ) );
    if ( ! empty( $shop_subtitle ) ) {
        $subtitle = wp_kses_post( $shop_subtitle );
    } else {
        $subtitle = '';
    }
    
    $labels = array(
        'name'               => 'Shop',
        'singular_name'      => __( 'Shop item', 'astrodj' ),
        'menu_name'          => __( 'Shop', 'astrodj' ),
        'name_admin_bar'     => __( 'Shop', 'astrodj' ),
        'add_new'            => __( 'Новое', 'astrodj' ),
        'add_new_item'       => __( 'Добавить', 'astrodj' ),
        'new_item'           => __( 'New shop item', 'astrodj' ),
        'edit_item'          => __( 'Редактировать', 'astrodj' ),
        'view_item'          => __( 'View shop item', 'astrodj' ),
        'all_items'          => __( 'Все товары', 'astrodj' ),
        'search_items'       => __( 'Search shop items', 'astrodj' ),
        'parent_item_colon'  => __( 'Parent shop item:', 'astrodj' ),
        'not_found'          => __( 'No shop items found.', 'astrodj' ),
        'not_found_in_trash' => __( 'No shop items found in Trash.', 'astrodj' ),
    );
    
    $args = array(
        'labels'              => $labels,
        'description'         => $subtitle,
        'public'              => false,
        'publicly_queryable'  => false,
        'exclude_from_search' => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_rest'        => true,
        'menu_icon'           => 'dashicons-cart',
        'query_var'           => false,
        'rewrite'             => array( 'slug' => 'astrodj-shop', 'with_front' => false ),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => true,
        'menu_position'       => 23,
        'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
        'taxonomies'          => array( 'shop-categories', 'shop-tags' )
    );

    register_post_type( 'shop', $args );
}
add_action( 'init', 'astrodj_shop_posttypes' );


// shop Taxonomies
function astrodj_shop_taxonomies() {
	
    // Category
    $labels = array(
        'name'              => 'Категории',
        'singular_name'     => 'Категория',
        'search_items'      => 'Найти Категорию',
        'all_items'         => 'Все Категории',
        'parent_item'       => 'Родитель Категории',
        'parent_item_colon' => 'Родитель Категории:',
        'edit_item'         => 'Редактировать Категорию',
        'update_item'       => 'Обновить Категорию',
        'add_new_item'      => 'Добавить Категорию',
        'new_item_name'     => 'Новая Категория',
        'menu_name'         => 'Категория',
    );

    $args = array(
        'public'             => false,
        'publicly_queryable' => false,
        'hierarchical'       => true,
        'labels'             => $labels,
        'show_ui'            => true,
        'show_admin_column'  => true,
        'query_var'          => false,
        'show_in_rest'       => true,
        'rewrite'            => array( 'slug' => 'shop-categories', 'with_front' => false ),
        'show_in_nav_menus'  => false,
    );

    register_taxonomy( 'shop-categories', array( 'shop' ), $args );

    // Tags (non-hierarchical)
    $labels = array(
        'name'                       => 'Тэги',
        'singular_name'              => 'Тэг',
        'search_items'               => 'Найти Тэг',
        'popular_items'              => 'Популярные Тэги',
        'all_items'                  => 'Все Тэги',
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => 'Редактировать Тэг',
        'update_item'                => 'Обновить Тэг',
        'add_new_item'               => 'Добавить Тэг',
        'new_item_name'              => 'Новое имя Тэга',
        'separate_items_with_commas' => 'Разделить Тэги запятыми',
        'add_or_remove_items'        => 'Добавить или удалить Тэги',
        'choose_from_most_used'      => 'Выбрать популярные Тэги',
        'not_found'                  => 'Не найдено Тэгов.',
        'menu_name'                  => 'Тэги',
    );

    $args = array(
        'public'                => false,
        'publicly_queryable'    => false,
        'hierarchical'          => false,
        'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => false,
        'show_in_rest'          => true,
        'rewrite'               => array( 'slug' => 'shop-tags', 'with_front' => false ),
        'show_in_nav_menus'     => false,
    );

    register_taxonomy( 'shop-tags', array( 'shop' ), $args );
}
add_action( 'init', 'astrodj_shop_taxonomies' );
