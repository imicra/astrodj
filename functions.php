<?php
/**
 * Astrodj functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astrodj
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.2.4' );
}

if ( ! function_exists( 'astrodj_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function astrodj_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on Astrodj, use a find and replace
		 * to change 'astrodj' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'astrodj', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		add_image_size( 'astrodj_lqip', 27, 18, false );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'main' => esc_html__( 'Header', 'astrodj' ),
			'social' => esc_html__( 'Social menu', 'astrodj' ),
			'subpages' => esc_html__( 'Subpages menu', 'astrodj' ),
			'frontpage' => esc_html__( 'Front Page menu', 'astrodj' ),
			'login' => esc_html__( 'Login Page menu', 'astrodj' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'astrodj_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );

		add_theme_support( 'align-wide' );

		add_theme_support( 'responsive-embeds' );

		/**
		 * Disable the block-based widgets editor.
		 */
		remove_theme_support( 'widgets-block-editor' );
	}
endif;
add_action( 'after_setup_theme', 'astrodj_setup' );

/**
 * Add custom image sizes attribute to enhance responsive image functionality.
 *
 * @origin Twenty Seventeen 1.0
 *
 * @param array $attr       Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size       Registered image size or flat array of height and width dimensions.
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
function astrodj_post_thumbnail_sizes_attr( $sizes, $size ) {

	if ( is_post_type_archive() ) {
		$sizes = '(min-width: 768px) 1024px, 100vw';
	}

	if ( is_home() || is_search() || ( is_archive() && ! is_post_type_archive() ) || is_front_page() ) {
		$sizes = '(max-width: 768px) 100vw, 768px';
	}

	return $sizes;
}
// add_filter( 'wp_calculate_image_sizes', 'astrodj_post_thumbnail_sizes_attr', 10, 2 );

/**
 * Exclude astrodj_lqip size from srcset.
 * 
 * https://www.smashingmagazine.com/2016/09/responsive-images-in-wordpress-with-art-direction/
 */
function astrodj_remove_images_below_breakpoint( $sources, $size_array, $image_src, $image_meta, $attachment_id ) {
	$cutoff = $image_meta['sizes']['astrodj_lqip']['width'];
	
	foreach ( $sources as $key => $value ) {
		if ( $cutoff >= $key ) {
			unset( $sources[ $key ] );
		}
	}

	return $sources;
}
add_filter( 'wp_calculate_image_srcset', 'astrodj_remove_images_below_breakpoint', 10, 5 );

/**
 * Turn off attribute loading="lazy" by default for template images & within post content.
 */
function disable_template_image_lazy_loading( $default, $tag_name, $context ) {
	if ( 'img' === $tag_name && 'wp_get_attachment_image' === $context ) {
			return false;
	}

	if ( 'img' === $tag_name && 'the_content' === $context ) {
		return false;
	}

	return $default;
}
add_filter( 'wp_lazy_loading_enabled', 'disable_template_image_lazy_loading', 10, 3 );

/**
 * Disable automatically compressing images.
 */
// add_filter( 'jpeg_quality', function( $quality ) {
// 	return 100;
// } );

/**
 * Disabling the scaling images.
 */
add_filter( 'big_image_size_threshold', '__return_false' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function astrodj_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'astrodj_content_width', 640 );
}
add_action( 'after_setup_theme', 'astrodj_content_width', 0 );

/**
 * Register custom fonts.
 */
function astrodj_fonts_url() {
	$fonts_url = '';

	/*
	 * Translators: If there are characters in your language that are not
	 * supported by Libre Franklin, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$source_sans_pro = _x( 'on', 'Source Sans Pro font: on or off', 'astrodj' );
	$pt_serif = _x( 'on', 'PT Serif font: on or off', 'astrodj' );

	$font_families = array();

	if ( 'off' !== $source_sans_pro ) {
		$font_families[] = 'Source Sans Pro:400,600,700';
	}

	if ( 'off' !== $pt_serif ) {
		$font_families[] = 'PT Serif:400,400i,700,700i';
	}


	if ( in_array( 'on', array( $source_sans_pro, $pt_serif ) ) ) {

		$query_args = array(
			'family' => urlencode( implode( '|', $font_families ) ),
			'display' => urlencode( 'swap' ),
			'subset' => urlencode( 'cyrillic' ),
		);

		$fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return esc_url_raw( $fonts_url );
}

/**
 * Add preconnect for Google Fonts.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function astrodj_resource_hints( $urls, $relation_type ) {
	if ( wp_style_is( 'astrodj-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter( 'wp_resource_hints', 'astrodj_resource_hints', 10, 2 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function astrodj_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'astrodj' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'astrodj' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Photo Filter Portfolio', 'astrodj' ),
		'id'            => 'filter-portfolio',
		'description'   => esc_html__( 'Add widget Photo Filter for Portfolio archive.', 'astrodj' ),
		'before_widget' => '<section id="%1$s" class="%2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Photo Filter Stock', 'astrodj' ),
		'id'            => 'filter-stock',
		'description'   => esc_html__( 'Add widget Photo Filter for Stock archive.', 'astrodj' ),
		'before_widget' => '<section id="%1$s" class="%2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Photo Filter Cats', 'astrodj' ),
		'id'            => 'filter-cats',
		'description'   => esc_html__( 'Add widget Photo Filter for Cats archive.', 'astrodj' ),
		'before_widget' => '<section id="%1$s" class="%2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Front Page Widgets', 'astrodj' ),
		'id'            => 'frontpage-1',
		'description'   => esc_html__( 'Add frontpage widgets here.', 'astrodj' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer Widgets', 'astrodj' ),
		'id'            => 'footer-1',
		'description'   => esc_html__( 'Add footer widgets here.', 'astrodj' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'astrodj_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function astrodj_scripts() {
	wp_enqueue_style( 'astrodj-fonts', astrodj_fonts_url(), array(), null );

	wp_enqueue_style( 'astrodj-style-libs', get_template_directory_uri() . '/css/libs.min.css', array(), '1.0' );

	wp_enqueue_style( 'astrodj-style', get_template_directory_uri() . '/css/style.min.css', array(), _S_VERSION );

	wp_enqueue_script( 'astrodj-scripts-libs', get_template_directory_uri() . '/js/libs.min.js', array( 'jquery' ), '1.0', true );

	wp_enqueue_script( 'astrodj-scripts', get_template_directory_uri() . '/js/scripts.min.js', array( 'jquery' ), _S_VERSION, true );

	if ( is_front_page() ) {
    wp_enqueue_script( 'astrodj-frontpage-infinite', get_template_directory_uri() . '/js/frontpage.inc.js', array( 'jquery' ), _S_VERSION, true );
    wp_localize_script( 'astrodj-frontpage-infinite', 'frontpage_data',
			array(
        'rest_url' => rest_url( 'wp/v2/posts/')
			)
		);
	}
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	wp_localize_script( 'astrodj-scripts', 'main_data',
		array(
			'site_url'    => site_url( '/' ),
			'ajax_url'    => admin_url( 'admin-ajax.php' ),
			'rest_media'  => rest_url( 'wp/v2/media/'),
			'is_singular' => is_singular(),
			'site_name'   => get_bloginfo( 'name', 'display' ),
			'debug'       => WP_DEBUG,
			'api_key'     => get_option( 'astrodj_api_key' ),
			'api_secret'  => password_hash( get_option( 'astrodj_api_secret' ), PASSWORD_DEFAULT )
		)
	);
}
add_action( 'wp_enqueue_scripts', 'astrodj_scripts' );

/**
 * Отключаем принудительную проверку новых версий WP, плагинов и темы в админке,
 * чтобы она не тормозила, когда долго не заходил и зашел...
 * Все проверки будут происходить незаметно через крон или при заходе на страницу: "Консоль > Обновления".
 *
 * @see https://wp-kama.ru/filecode/wp-includes/update.php
 * @author Kama (https://wp-kama.ru)
 * @link https://wp-kama.ru/id_8514/uskoryaem-adminku-wordpress-otklyuchaem-proverki-obnovlenij.html
 * @version 1.1
 */
if ( is_admin() ) {
	// отключим проверку обновлений при любом заходе в админку...
	remove_action( 'admin_init', '_maybe_update_core' );
	remove_action( 'admin_init', '_maybe_update_plugins' );
	remove_action( 'admin_init', '_maybe_update_themes' );

	// отключим проверку обновлений при заходе на специальную страницу в админке...
	remove_action( 'load-plugins.php', 'wp_update_plugins' );
	remove_action( 'load-themes.php', 'wp_update_themes' );

	/**
	 * отключим проверку необходимости обновить браузер в консоли - мы всегда юзаем топовые браузеры!
	 * эта проверка происходит раз в неделю...
	 * @see https://wp-kama.ru/function/wp_check_browser_version
	 */
	add_filter( 'pre_site_transient_browser_'. md5( $_SERVER['HTTP_USER_AGENT'] ), '__return_empty_array' );
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Theme Widgets.
 */
require get_template_directory() . '/inc/widgets/widgets.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Custom Post Types.
 */
require get_template_directory() . '/inc/cpt/general.php';

/**
 * CMB 2.
 */
require get_template_directory() . '/inc/cmb2.php';

/**
 * Media Uploader custom fields.
 */
require get_template_directory() . '/inc/media-uploader.php';

/**
 * Theme functions.
 */
require get_template_directory() . '/inc/theme-functions/theme-functions.php';

/**
 * Admin functions.
 */
require get_template_directory() . '/inc/admin/admin-functions.php';

/**
 * Frontpage ajax initial posts query.
 */
require get_template_directory() . '/inc/frontpage-query.php';

/**
 * Single Post ajax Navigation.
 */
require get_template_directory() . '/inc/post-navigation.php';

/**
 * Archive pages ajax Pagination with Load More button.
 */
require get_template_directory() . '/inc/archive-pagination.php';

/**
 * Archive Pages Links Ajax Loading.
 */
require get_template_directory() . '/inc/archive-links.php';

/**
 * Page Navigation Ajax by Subpages.
 */
require get_template_directory() . '/inc/subpages-navigation.php';

/**
 * Widget photo filter ajax.
 */
require get_template_directory() . '/inc/widget-filter.php';

/**
 * Header Image Ajax Loading.
 */
// require get_template_directory() . '/inc/header-ajax.php';

/**
 * Contact Form Ajax Processing.
 */
require get_template_directory() . '/inc/contact-form.php';

/**
 * Search Form Ajax Processing.
 */
require get_template_directory() . '/inc/search-form.php';

/**
 * Portfolio Ajax Infinite Scroll Loader.
 */
require get_template_directory() . '/inc/portfolio-infinite-scroll.php';

/**
 * Load SVG icon functions.
 */
require get_template_directory() . '/inc/icon-functions.php';

/**
 * WP REST API functions.
 */
require get_template_directory() . '/inc/rest-api.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

/**
 * Load WooCommerce compatibility file.
 */
if ( class_exists( 'WooCommerce' ) ) {
	require get_template_directory() . '/inc/woocommerce.php';
}
