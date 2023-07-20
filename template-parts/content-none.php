<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Astrodj
 */

?>

<section class="no-results not-found">
	<header class="page-header-search">
		<h1 class="page-title">
		<?php
		if ( is_search() ) :
			/* translators: %s = search query */
			printf( esc_html__( 'Ничего не найдено по &ldquo;%s&rdquo;', 'astrodj'), get_search_query() );
		else :
			esc_html_e( 'Nothing Found', 'astrodj' );
		endif;
		?>
		</h1>
	</header><!-- .page-header -->

	<div class="page-content">
		<?php
		if ( is_home() && current_user_can( 'publish_posts' ) ) :

			printf(
				'<p>' . wp_kses(
					/* translators: 1: link to WP admin new post page. */
					__( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'astrodj' ),
					array(
						'a' => array(
							'href' => array(),
						),
					)
				) . '</p>',
				esc_url( admin_url( 'post-new.php' ) )
			);

		elseif ( is_search() ) :
			?>

			<p><?php esc_html_e( 'Попробуйте другое сочетание.', 'astrodj' ); ?></p>
			<?php
			get_search_form();

		else :
			?>

			<p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'astrodj' ); ?></p>
			<?php
			get_search_form();

		endif;
		?>
	</div><!-- .page-content -->
	<?php
	if ( is_search() ) :
	?>
		<h2 class="page-title secondary-title"><?php esc_html_e( 'Последние записи:', 'astrodj' ); ?></h2>
		<?php
		// Get the latest posts
		$args = array(
			'posts_per_page' => 5
		);

		$latest_posts_query = new WP_Query( $args );

		// The Loop
		if ( $latest_posts_query->have_posts() ) {
				while ( $latest_posts_query->have_posts() ) {
					$latest_posts_query->the_post();

					// Get the standard index page content
					get_template_part( 'template-parts/content', get_post_format() );
				}
		}

		/* Restore original Post Data */
		wp_reset_postdata();

	endif;
	?>
</section><!-- .no-results -->
