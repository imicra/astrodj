<?php
/**
 * Component for display Exif Data Murkup.
 *
 * @package astrodj
 */

/**
 * Prints HTML with Exif meta information for the portfolio cpt post thumbnail.
 * Block design.
 */
function astrodj_portfolio_exif() {
	global $post;
	
	$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
	$exif_check = (bool) get_post_meta( $post_thumbnail_id, 'media_exif', true );
	$metas = get_post_meta( $post_thumbnail_id );

	if ( false !== $exif_check ) : ?>

		<div class="media-exif__content">

			<?php	if ( isset( $metas['media_camera'] ) ) { ?>

				<div class="media-exif__content--item media-exif__content--item-camera">
					<div class="media-exif__content--icon">
						<?php echo astrodj_get_svg( array( 'icon' => 'camera-dslr' ) ); ?>
					</div>
					<div class="media-exif__content--inner">
						<span>
							<?php echo astrodj_insert_break( 'EOS', get_post_meta( $post_thumbnail_id, 'media_camera', true ), true ); ?>
						</span>
						<?php
						if ( '0' !== ( $metas['media_lens'][0] ) ) { ?>
							<span>
								<?php echo astrodj_insert_break( 'f/', get_post_meta( $post_thumbnail_id, 'media_lens', true ), false ); ?>
							</span>
						<?php } ?>
					</div>
				</div>

			<?php }

			if ( !empty( $metas['media_aperture'][0] ) ) { ?>
				<div class="media-exif__content--item">
					<?php echo astrodj_get_svg( array( 'icon' => 'camera-aperture' ) ); ?>
					<span>
						ƒ/<?php echo get_post_meta( $post_thumbnail_id, 'media_aperture', true ); ?>
					</span>
				</div>
			<?php }
			
			if ( !empty( $metas['media_focal'][0] ) ) { ?>
				<div class="media-exif__content--item">
					<?php echo astrodj_get_svg( array( 'icon' => 'camera-focus' ) ); ?>
					<span>
						<?php echo get_post_meta( $post_thumbnail_id, 'media_focal', true ); ?> mm
					</span>
				</div>
			<?php }

			if ( !empty( $metas['media_time'][0] ) ) { ?>
				<div class="media-exif__content--item">
					<?php echo astrodj_get_svg( array( 'icon' => 'camera-timer-2' ) ); ?>
					<span>
						<?php echo get_post_meta( $post_thumbnail_id, 'media_time', true ); ?>c
					</span>
				</div>
			<?php }
			
			if ( !empty( $metas['media_iso'][0] ) ) { ?>
				<div class="media-exif__content--item">
					<?php echo astrodj_get_svg( array( 'icon' => 'camera-iso-1' ) ); ?>
					<span>
						<?php echo get_post_meta( $post_thumbnail_id, 'media_iso', true ); ?>
					</span>
				</div>
			<?php } ?>

		</div><!-- .media-exif__content -->

	<?php endif; //exif_check;
}

/**
 * Prints HTML with Exif meta information for the portfolio cpt post thumbnail.
 * Inline design.
 */
function astrodj_portfolio_exif_inline() {
	global $post;

	$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
	$exif_check = (bool) get_post_meta( $post_thumbnail_id, 'media_exif', true );
	$metas = get_post_meta( $post_thumbnail_id );

	if ( false !== $exif_check ) : ?>

		<div class="media-exif__content">

		<div class="media-exif__content--inner_1">
			<?php	if ( isset( $metas['media_camera'] ) ) { ?>
				<div class="media-exif__content--item">
					<div class="media-exif__content--icon">
						<?php echo astrodj_get_svg( array( 'icon' => 'camera-dslr' ) ); ?>
					</div>
					<span>
						<?php echo astrodj_insert_break( 'EOS', get_post_meta( $post_thumbnail_id, 'media_camera', true ), true ); ?>
					</span>
				</div>
			<?php }

			if ( '0' !== ( $metas['media_lens'][0] ) ) { ?>
				<div class="media-exif__content--item">
					<div class="media-exif__content--icon">
						<?php echo astrodj_get_svg( array( 'icon' => 'camera-lens-1' ) ); ?>
					</div>
					<span>
						<?php echo astrodj_insert_break( 'f/', get_post_meta( $post_thumbnail_id, 'media_lens', true ), false ); ?>
					</span>
				</div>
			<?php } ?>
		</div>

		<div class="media-exif__content--inner_2">
			<?php if ( !empty( $metas['media_aperture'][0] ) ) { ?>
				<div class="media-exif__content--item">
					<?php echo astrodj_get_svg( array( 'icon' => 'camera-aperture' ) ); ?>
					<span>
						ƒ/<?php echo get_post_meta( $post_thumbnail_id, 'media_aperture', true ); ?>
					</span>
				</div>
			<?php }
			
			if ( !empty( $metas['media_focal'][0] ) ) { ?>
				<div class="media-exif__content--item">
					<?php echo astrodj_get_svg( array( 'icon' => 'camera-focus' ) ); ?>
					<span>
						<?php echo get_post_meta( $post_thumbnail_id, 'media_focal', true ); ?> mm
					</span>
				</div>
			<?php }

			if ( !empty( $metas['media_time'][0] ) ) { ?>
				<div class="media-exif__content--item">
					<?php echo astrodj_get_svg( array( 'icon' => 'camera-timer-2' ) ); ?>
					<span>
						<?php echo get_post_meta( $post_thumbnail_id, 'media_time', true ); ?>c
					</span>
				</div>
			<?php }
			
			if ( !empty( $metas['media_iso'][0] ) ) { ?>
				<div class="media-exif__content--item">
					<?php echo astrodj_get_svg( array( 'icon' => 'camera-iso-1' ) ); ?>
					<span>
						<?php echo get_post_meta( $post_thumbnail_id, 'media_iso', true ); ?>
					</span>
				</div>
			<?php } ?>
		</div>

		</div><!-- .media-exif__content -->

	<?php endif; //exif_check;
}