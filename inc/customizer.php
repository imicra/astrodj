<?php
/**
 * Astrodj Theme Customizer
 *
 * @package Astrodj
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function astrodj_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial( 'blogname', array(
			'selector'        => '.site-title a',
			'render_callback' => 'astrodj_customize_partial_blogname',
		) );
		$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
			'selector'        => '.site-description',
			'render_callback' => 'astrodj_customize_partial_blogdescription',
		) );
	}

	/**
	 * Title Tagline section
	 */
	$wp_customize->add_setting( 'astrodj_blogname', array(
		'default'           => esc_html__( 'Astrodj', 'astrodj' ),
		'sanitize_callback' => 'astrodj_sanitize_input',
		'transport'	        => 'postMessage',
	) );

	$wp_customize->add_control( 'astrodj_blogname', array(
		'label'         => esc_html__( 'Название сайта в Хэдере', 'astrodj' ),
		'section'       => 'title_tagline',
		'priority'      => 50,
		'type'          => 'text',
	));

	/**
	 * Front Page Avatar
	 */
	$wp_customize->add_section( 'frontpage_avatar', array(
		'title'           => __( 'Astrodj Аватар', 'astrodj' ),
		'description'     => 'Аватар на главной странице',
		'priority'        => 10,
		'active_callback' => 'astrodj_return_is_frontpage',
	) );

	$wp_customize->add_setting( 'frontpage_avatar_img', array(
		'default'						=> get_template_directory_uri() . '/images/avatar.jpg',
		'sanitize_callback' => 'esc_url',
		'transport'         => 'postMessage',
	));

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'frontpage_avatar_img', array(
		'label'    => esc_html__( 'Аватар', 'astrodj' ),
		'section'  => 'frontpage_avatar',
		'priority' => 1,
	)));

	/**
	 * Front Page Settings
	 */
	$wp_customize->add_section( 'frontpage_setting', array(
		'title'           => __( 'Astrodj Главная', 'astrodj' ),
		'description'     => '',
		'priority'        => 11,
		'active_callback' => 'astrodj_return_is_frontpage',
	) );

	$wp_customize->add_setting( 'frontpage_username', array(
		'default'           => esc_html__( 'Vas Zhigalov', 'astrodj' ),
		'sanitize_callback' => 'astrodj_sanitize_input',
		'transport'	        => 'postMessage',
	) );

	$wp_customize->add_control( 'frontpage_username', array(
		'label'         => esc_html__( 'Ник', 'astrodj' ),
		'section'       => 'frontpage_setting',
		'priority'      => 10,
		'type'          => 'text',
	));

	/**
	 * Portfolio Header Image (archive-portfolio.php has header template header-noimage.php)
	 */
	$wp_customize->add_section( 'portfolio_header', array(
		'title'           => __( 'Astrodj Изображение заголовка', 'astrodj' ),
		'description'     => 'Изображение заголовка на странице Портфолио',
		'priority'        => 10,
		'active_callback' => 'astrodj_return_is_page_portfolio',
	) );

	$wp_customize->add_setting( 'portfolio_header_img', array(
		'sanitize_callback' => 'esc_url',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'portfolio_header_img', array(
		'label'    => esc_html__( 'Изображение заголовка', 'astrodj' ),
		'section'  => 'portfolio_header',
		'priority' => 1,
	)));

	/**
	 * Portfolio Settings
	 */
	$wp_customize->add_section( 'portfolio_setting', array(
		'title'           => __( 'Astrodj Портфолио', 'astrodj' ),
		'description'     => '',
		'priority'        => 11,
		'active_callback' => 'astrodj_return_is_page_portfolio',
	) );

	$wp_customize->add_setting( 'portfolio_title', array(
		'default'           => esc_html__( 'Фотогалерея', 'astrodj' ),
		'sanitize_callback' => 'astrodj_sanitize_input',
		'transport'	        => 'postMessage',
	) );

	$wp_customize->add_control( 'portfolio_title', array(
		'label'         => esc_html__( 'Заголовок', 'astrodj' ),
		'section'       => 'portfolio_setting',
		'priority'      => 10,
		'type'          => 'text',
	));

	$wp_customize->add_setting( 'portfolio_subtitle', array(
		'default'           => esc_html__( 'Мои маленькие фотошедевры', 'astrodj' ),
		'sanitize_callback' => 'astrodj_sanitize_input',
		'transport'	        => 'postMessage',
	) );

	$wp_customize->add_control( 'portfolio_subtitle', array(
		'label'         => esc_html__( 'Подзаголовок', 'astrodj' ),
		'section'       => 'portfolio_setting',
		'priority'      => 20,
		'type'          => 'text',
	));

	/**
	 * Stock Header Image
	 */
	$wp_customize->add_section( 'stock_header', array(
		'title'           => __( 'Astrodj Изображение заголовка', 'astrodj' ),
		'description'     => 'Изображение заголовка на странице Сток',
		'priority'        => 10,
		'active_callback' => 'astrodj_return_is_page_stock',
	) );

	$wp_customize->add_setting( 'stock_header_img', array(
		'sanitize_callback' => 'esc_url',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'stock_header_img', array(
		'label'    => esc_html__( 'Изображение заголовка', 'astrodj' ),
		'section'  => 'stock_header',
		'priority' => 1,
	)));

	/**
	 * Stock Settings
	 */
	$wp_customize->add_section( 'stock_setting', array(
		'title'           => __( 'Astrodj Сток', 'astrodj' ),
		'description'     => '',
		'priority'        => 10,
		'active_callback' => 'astrodj_return_is_page_stock',
	) );

	$wp_customize->add_setting( 'stock_title', array(
		'default'           => esc_html__( 'Сток', 'astrodj' ),
		'sanitize_callback' => 'astrodj_sanitize_input',
		'transport'	        => 'postMessage',
	) );

	$wp_customize->add_control( 'stock_title', array(
		'label'         => esc_html__( 'Заголовок', 'astrodj' ),
		'section'       => 'stock_setting',
		'priority'      => 10,
		'type'          => 'text',
	));

	$wp_customize->add_setting( 'stock_subtitle', array(
		'default'           => esc_html__( 'Интересные снимки, но не шедевры', 'astrodj' ),
		'sanitize_callback' => 'astrodj_sanitize_input',
		'transport'	        => 'postMessage',
	) );

	$wp_customize->add_control( 'stock_subtitle', array(
		'label'         => esc_html__( 'Подзаголовок', 'astrodj' ),
		'section'       => 'stock_setting',
		'priority'      => 20,
		'type'          => 'text',
	));

	/**
	 * Cats Header Image
	 */
	$wp_customize->add_section( 'cats_header', array(
		'title'           => __( 'Astrodj Изображение заголовка', 'astrodj' ),
		'description'     => 'Изображение заголовка на странице Коты',
		'priority'        => 10,
		'active_callback' => 'astrodj_return_is_page_cats',
	) );

	$wp_customize->add_setting( 'cats_header_img', array(
		'sanitize_callback' => 'esc_url',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'cats_header_img', array(
		'label'    => esc_html__( 'Изображение заголовка', 'astrodj' ),
		'section'  => 'cats_header',
		'priority' => 1,
	)));

	/**
	 * Cats Settings
	 */
	$wp_customize->add_section( 'cats_setting', array(
		'title'           => __( 'Astrodj Коты', 'astrodj' ),
		'description'     => '',
		'priority'        => 10,
		'active_callback' => 'astrodj_return_is_page_cats',
	) );

	$wp_customize->add_setting( 'cats_title', array(
		'default'           => esc_html__( 'Коты', 'astrodj' ),
		'sanitize_callback' => 'astrodj_sanitize_input',
		'transport'	        => 'postMessage',
	) );

	$wp_customize->add_control( 'cats_title', array(
		'label'         => esc_html__( 'Заголовок', 'astrodj' ),
		'section'       => 'cats_setting',
		'priority'      => 10,
		'type'          => 'text',
	));

	$wp_customize->add_setting( 'cats_subtitle', array(
		'default'           => esc_html__( 'Коты и другие случайные собаки', 'astrodj' ),
		'sanitize_callback' => 'astrodj_sanitize_input',
		'transport'	        => 'postMessage',
	) );

	$wp_customize->add_control( 'cats_subtitle', array(
		'label'         => esc_html__( 'Подзаголовок', 'astrodj' ),
		'section'       => 'cats_setting',
		'priority'      => 20,
		'type'          => 'text',
	));

	/**
	 * Archive Header Image
	 */
	$wp_customize->add_section( 'archive_header', array(
		'title'           => __( 'Astrodj Изображение заголовка', 'astrodj' ),
		'description'     => 'Изображение заголовка на странице Архив',
		'priority'        => 10,
		'active_callback' => 'astrodj_return_is_page_archive',
	) );

	$wp_customize->add_setting( 'archive_header_img', array(
		'sanitize_callback' => 'esc_url',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'archive_header_img', array(
		'label'    => esc_html__( 'Изображение заголовка', 'astrodj' ),
		'section'  => 'archive_header',
		'priority' => 1,
	)));

	/**
	 * Archive Settings
	 */
	$wp_customize->add_section( 'archive_setting', array(
		'title'           => __( 'Astrodj Архив', 'astrodj' ),
		'description'     => '',
		'priority'        => 10,
		'active_callback' => 'astrodj_return_is_page_archive',
	) );

	$wp_customize->add_setting( 'archive_title', array(
		'default'           => esc_html__( 'Архив', 'astrodj' ),
		'sanitize_callback' => 'astrodj_sanitize_input',
		'transport'	        => 'postMessage',
	) );

	$wp_customize->add_control( 'archive_title', array(
		'label'         => esc_html__( 'Заголовок', 'astrodj' ),
		'section'       => 'archive_setting',
		'priority'      => 10,
		'type'          => 'text',
	));

	$wp_customize->add_setting( 'archive_subtitle', array(
		'default'           => esc_html__( 'Архивные фото', 'astrodj' ),
		'sanitize_callback' => 'astrodj_sanitize_input',
		'transport'	        => 'postMessage',
	) );

	$wp_customize->add_control( 'archive_subtitle', array(
		'label'         => esc_html__( 'Подзаголовок', 'astrodj' ),
		'section'       => 'archive_setting',
		'priority'      => 20,
		'type'          => 'text',
	));
}
add_action( 'customize_register', 'astrodj_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function astrodj_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function astrodj_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

if ( ! function_exists( 'astrodj_sanitize_input' ) ) {

	/**
	 * Sanitize variables to allow HTML tags
	 *
	 * @param string $input Text to sanitize.
	 */
	function astrodj_sanitize_input( $input ) {
	    return wp_kses_post( force_balance_tags( $input ) );
	}
}

/**
 * Render section for Front page only.
 *
 * @return void
 */
function astrodj_return_is_frontpage() {
  return is_front_page();
}

/**
 * Render section for Portfolio page only.
 *
 * @return void
 */
function astrodj_return_is_page_portfolio() {
  return is_post_type_archive( 'portfolio' );
}

/**
 * Render section for Stock page only.
 *
 * @return void
 */
function astrodj_return_is_page_stock() {
  return is_post_type_archive( 'stock' );
}

/**
 * Render section for Cats page only.
 *
 * @return void
 */
function astrodj_return_is_page_cats() {
  return is_post_type_archive( 'cats' );
}

/**
 * Render section for Archive page only.
 *
 * @return void
 */
function astrodj_return_is_page_archive() {
  return is_post_type_archive( 'archive' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function astrodj_customize_preview_js() {
	wp_enqueue_script( 'astrodj-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'astrodj_customize_preview_js' );

/* Include customizer Custom Styles */
require_once get_template_directory() . '/inc/custom-styles.php';
