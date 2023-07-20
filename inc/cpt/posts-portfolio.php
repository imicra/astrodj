<?php
/**
 * File posts-portfolio.php.
 * 
 * Custom Post Type for Portfolio.
 * 
 * @package Astrodj
 */

function astrodj_portfolio_posttypes() {

    // Customizer settings
    $portfolio_title = get_theme_mod( 'portfolio_title', esc_html__( 'Фотогалерея', 'astrodj' ) );
    $portfolio_subtitle = get_theme_mod( 'portfolio_subtitle', esc_html__( 'Мои маленькие фотошедевры', 'astrodj' ) );
    if ( ! empty( $portfolio_subtitle ) ) {
        $subtitle = wp_kses_post( $portfolio_subtitle );
    } else {
        $subtitle = '';
    }
    
    $labels = array(
        'name'               => $portfolio_title,
        'singular_name'      => __( 'Portfolio item', 'astrodj' ),
        'menu_name'          => __( 'ФотоГалерея', 'astrodj' ),
        'name_admin_bar'     => __( 'Portfolio', 'astrodj' ),
        'add_new'            => __( 'Новое', 'astrodj' ),
        'add_new_item'       => __( 'Добавить снимок', 'astrodj' ),
        'new_item'           => __( 'New Portfolio item', 'astrodj' ),
        'edit_item'          => __( 'Редактировать пост снимка', 'astrodj' ),
        'view_item'          => __( 'View Portfolio item', 'astrodj' ),
        'all_items'          => __( 'Все снимки', 'astrodj' ),
        'search_items'       => __( 'Search Portfolio items', 'astrodj' ),
        'parent_item_colon'  => __( 'Parent Portfolio item:', 'astrodj' ),
        'not_found'          => __( 'No Portfolio items found.', 'astrodj' ),
        'not_found_in_trash' => __( 'No Portfolio items found in Trash.', 'astrodj' ),
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
        'rewrite'             => array( 'slug' => 'photo-gallery', 'with_front' => false ),
        'capability_type'     => 'post',
        'has_archive'         => true,
        'hierarchical'        => true,
        'menu_position'       => 21,
        'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail', 'page-attributes' ),
        'taxonomies'          => array( 'portfolio-categories', 'technics' )
    );
    register_post_type( 'portfolio', $args );
}
add_action( 'init', 'astrodj_portfolio_posttypes' );


// Portfolio Taxonomies
function astrodj_portfolio_taxonomies() {
	
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
        'rewrite'           => array( 'slug' => 'portfolio-categories' ),
    );

    register_taxonomy( 'portfolio-categories', array( 'portfolio' ), $args );

    // Tags (non-hierarchical)
    $labels = array(
        'name'                       => 'Техники съемки',
        'singular_name'              => 'Техника съемки',
        'search_items'               => 'Найти Технику съемки',
        'popular_items'              => 'Популярные Техники',
        'all_items'                  => 'Все Техники',
        'parent_item'                => null,
        'parent_item_colon'          => null,
        'edit_item'                  => 'Редактировать Технику',
        'update_item'                => 'Обновить Технику',
        'add_new_item'               => 'Добавить Технику',
        'new_item_name'              => 'Новое имя Техники',
        'separate_items_with_commas' => 'Разделить Техники запятыми',
        'add_or_remove_items'        => 'Добавить или удалить Техники',
        'choose_from_most_used'      => 'Выбрать популярные Техники',
        'not_found'                  => 'Не найдено Техник.',
        'menu_name'                  => 'Техники съемки',
    );

    $args = array(
        'hierarchical'          => false,
        'labels'                => $labels,
        'show_ui'               => true,
        'show_admin_column'     => true,
        'update_count_callback' => '_update_post_term_count',
        'query_var'             => true,
        'show_in_rest'          => true,
        'rewrite'               => array( 'slug' => 'technics' ),
    );

    register_taxonomy( 'technics', array( 'portfolio', 'stock' ), $args );
}
add_action( 'init', 'astrodj_portfolio_taxonomies' );
