<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Astrodj
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main">

			<section class="error-404 not-found">
				<header class="page-header-not">
					<h1 class="page-title"><?php esc_html_e( 'Oops! Такой страницы не найдено.', 'astrodj' ); ?></h1>
				</header><!-- .page-header -->

				<div class="page-content">
					<p><?php esc_html_e( 'Такое бывает при неверном УРЛ. Попробуйте одну из ссылок ниже или форму поиска.', 'astrodj' ); ?></p>

					<?php get_search_form(); ?>

					<div class="widget widget_categories">
						<h2 class="widget-title"><?php esc_html_e( 'Самые используемые Категории', 'astrodj' ); ?></h2>
						<ul>
							<?php
							wp_list_categories( array(
								'orderby'    => 'count',
								'order'      => 'DESC',
								'show_count' => 1,
								'title_li'   => '',
								'number'     => 20,
							) );
							?>
						</ul>
					</div><!-- .widget -->

					<?php
					/* translators: %1$s: smiley */
					$astrodj_archive_content = '<p>' . sprintf( esc_html__( 'Попробуйте посмотреть в архиве по месяцам. %1$s', 'astrodj' ), convert_smilies( ':)' ) ) . '</p>';
					the_widget( 'WP_Widget_Archives', 'dropdown=1', "after_title=</h2>$astrodj_archive_content" );

					the_widget( 'WP_Widget_Tag_Cloud', 'title=Теги' );
					?>

				</div><!-- .page-content -->
			</section><!-- .error-404 -->

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
