<?php
/**
 * Template part for displaying page content in page-contact.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Astrodj
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'astrodj-contact' ); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->

	<?php astrodj_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content();
		?>
		<div class="form__wrapper centered mobile">
			<div class="form__data"></div>
			<div class="form__container">
				<form id="contact-form" class="form" name="contactForm" method="post" action="#">
					<div class="form-group">
						<label for="name">Ваше имя</label>
						<input type="text" name="name" class="form-control" id="name" pattern="^[А-Яа-яA-Za-z ]+\s?[А-Яа-яA-Za-z ]*$" placeholder="Джон Малкович" required>
						<small id="nameHelp" class="form-text text-danger">Error message</small>
					</div>
					<div class="form-group">
						<label for="email">Ваш Email</label>
						<input type="email" name="email" class="form-control" id="email" pattern='^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$' placeholder="user@hoster.com" required>
						<small id="emailHelp" class="form-text text-danger">Error message</small>
					</div>
					<div class="form-group">
						<label for="message">Письмо</label>
						<textarea name="message" class="form-control" id="message" rows="5" cols="30" data-pattern="^\s*[А-Яа-яA-Za-z0-9\.ёЁ]{3,}\.*" placeholder="Текст письма..." required></textarea>
						<small id="nameHelp" class="form-text text-danger">Error message</small>
					</div>
					<div class="form-group">
						<span id="formHelp" class="form-text form-help">Все поля обязательны для заполнения</span>
					</div>
					<div class="form-footer"></div>
				</form>
			</div><!-- .contact-form__container -->
		</div><!-- .form__wrapper -->
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers */
						__( 'Edit <span class="screen-reader-text">%s</span>', 'astrodj' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
