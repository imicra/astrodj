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
		<div class="site-branding">
			<div class="site-branding__options">
				<?php astrodj_the_custom_logo(); ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			</div><!-- .site-options -->
		</div><!-- .site-branding -->
	</header><!-- #masthead -->
	
	<?php if ( has_nav_menu( 'main' ) ) :
			get_template_part( 'template-parts/navigation/navigation', 'main' );
	endif; ?>

	<div id="content" class="site-content margin__hidden">
