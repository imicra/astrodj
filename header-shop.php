<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astrodj
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'astrodj' ); ?></a>

	<header id="masthead" class="site-header">
		<?php
			get_template_part( 'template-parts/header/header', 'image' );
		?>
		<div class="site-branding">
			<div class="site-branding__options">
				<?php
				astrodj_the_custom_logo();
				
				$title = get_theme_mod( 'shop_tpl_title', esc_html__( 'ФотоМагазин', 'astrodj' ) );

				?>
				<p class="site-title">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php astrodj_blog_name(); ?></a>
					<?php if ( ! empty( $title ) ) : ?>
					- <span><?php echo $title; ?></span>
					<?php
					endif;
					?>
				</p>
			</div><!-- .site-branding__options -->
			<div class="site-branding__description">
				<?php
				$description = get_theme_mod( 'shop_tpl_subtitle', esc_html__( 'Здесь можно купить фотографии и фотокалендари', 'astrodj' ) );

				if ( ! empty( $description ) ) :
				?>
					<span class="site-description"><?php echo $description; ?></span>
				<?php endif; ?>
			</div><!-- .site-branding__description -->
		</div><!-- .site-branding -->
	</header><!-- #masthead -->
	
	<?php
	if ( has_nav_menu( 'main' ) ) :

		get_template_part( 'template-parts/navigation/navigation', 'main' );

	endif;
	?>
	<div id="content" class="site-content">
