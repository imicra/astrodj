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

$portfolio_header_img = get_theme_mod( 'portfolio_header_img' );

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

	<?php if ( has_nav_menu( 'main' ) ) :
		get_template_part( 'template-parts/navigation/navigation', 'main' );
	endif; ?>
	
	<header id="masthead" class="site-header no-image">
		<?php
			get_template_part( 'template-parts/header/header', 'image' );
		?>
		<div class="site-branding">
			<div class="site-branding__options">
				<?php
				astrodj_the_custom_logo();
				if ( is_front_page() || is_home() ) :
					?>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<?php
				elseif ( is_post_type_archive() ) :
					$post_type = get_post_type_object( get_post_type( $post ) );
					$title = $post_type->labels->name;
					if ( ! empty( $title ) ) :
					?>
					<h1 class="site-title">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
						- <span><?php echo $title; ?></span>
					</h1>
					<?php
					endif;
				else :
					?>
					<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
					<?php
				endif;
				?>
				<?php if ( ! is_post_type_archive() && ! is_page() && ! is_404() ) : ?>
				<div class="header-search">
					<?php get_search_form(); ?>
				</div>
				<?php endif; ?>
			</div><!-- .site-branding__options -->
			<div class="site-branding__description">
				<?php
				$astrodj_description = get_bloginfo( 'description', 'display' );
				if ( is_post_type_archive() ) :
					?>
					<span class="site-description"><?php echo get_the_post_type_description(); ?></span>
					<?php
				elseif ( $astrodj_description || is_customize_preview() ) :
					?>
					<p class="site-description"><?php echo $astrodj_description; /* WPCS: xss ok. */ ?></p>
				<?php endif; ?>
				<div class="site-branding__count">
					<?php
					$total = '';

					if ( ! is_page() && ! is_post_type_archive() ) :
						$total = wp_count_posts( 'post', 'readable' )->publish;

						echo '<span>' . $total . ' Записей</span>';

					elseif ( is_post_type_archive( 'stock' ) ) :
						$total = wp_count_posts( 'stock', 'readable' )->publish;
						echo '<span>' . $total . ' Фото</span>';

					elseif ( is_post_type_archive( 'portfolio' ) ) :
						$total = wp_count_posts( 'portfolio', 'readable' )->publish;
						echo '<span>' . $total . ' Фото</span>';
					endif;

					?>
				</div><!-- .site-branding__count -->
			</div><!-- .site-branding__description -->
		</div><!-- .site-branding -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
