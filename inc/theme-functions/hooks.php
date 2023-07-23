<?php
/**
 * Hooks.
 *
 * @package astrodj
 */

/**
 * Placeholder Content Murkup.
 * Used for page loading placeholder.
 */
function astrodj_placeholder_content_preloader() {
	?>
	<div id="placeholder__content">
		<div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div>
		<div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div>
		<div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div>
		<div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div>
	</div>
	<?php
}
// add_action( 'astrodj_before_content', 'astrodj_placeholder_content_preloader', 10 );

/**
 * Placeholder Content Murkup.
 * Used for page loading placeholder.
 */
function astrodj_placeholder_frontpage_preloader() {
	?>
	<div id="frontpage__loader">
		<div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div>
		<div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div>
		<div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div>
		<div class="loader-placeholder" style="display: block;"><div></div><div></div><div></div></div>
	</div>
	<?php
}
// add_action( 'astrodj_frontpage_before_content', 'astrodj_placeholder_frontpage_preloader', 10 );

/**
 * Placeholder Content Murkup.
 * Used for page loading placeholder.
 */
function astrodj_placeholder_gallery_preloader() {
	?>
	<div id="placeholder__gallery">
		<div class="placeholder__gallery-item"><?php echo astrodj_get_svg( array( 'icon' => 'image' ) ); ?></div>
		<div class="placeholder__gallery-item"><?php echo astrodj_get_svg( array( 'icon' => 'image' ) ); ?></div>
		<div class="placeholder__gallery-item"><?php echo astrodj_get_svg( array( 'icon' => 'image' ) ); ?></div>
		<div class="placeholder__gallery-item"><?php echo astrodj_get_svg( array( 'icon' => 'image' ) ); ?></div>
		<div class="placeholder__gallery-item"><?php echo astrodj_get_svg( array( 'icon' => 'image' ) ); ?></div>
	</div>
	<?php
}
// add_action( 'astrodj_gallery_before_content', 'astrodj_placeholder_gallery_preloader', 10 );
