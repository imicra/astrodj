<?php
/**
 * Displays Background Header Images
 *
 * @package astrodj
 */

$lq_url = '';
$portfolio_header_img = get_theme_mod( 'portfolio_header_img' );
$stock_header_img = get_theme_mod( 'stock_header_img' );
$cats_header_img = get_theme_mod( 'cats_header_img' );
$archive_header_img = get_theme_mod( 'archive_header_img' );

if ( get_header_image() && ! is_post_type_archive() ) :

  $lq_url = wp_get_attachment_image_url( get_custom_header()->attachment_id, 'medium_large' );
  $hq_url = wp_get_attachment_image_url( get_custom_header()->attachment_id, 'full' );

elseif ( '' !== $portfolio_header_img && is_post_type_archive( 'portfolio' ) ) :

  $post_type_archive_header_img_ID = attachment_url_to_postid( $portfolio_header_img );
  $lq_url = wp_get_attachment_image_url( $post_type_archive_header_img_ID, 'medium_large' );
  $hq_url = wp_get_attachment_image_url( $post_type_archive_header_img_ID, 'full' );

elseif ( '' !== $stock_header_img && is_post_type_archive( 'stock' ) ) :

  $post_type_archive_header_img_ID = attachment_url_to_postid( $stock_header_img );
  $lq_url = wp_get_attachment_image_url( $post_type_archive_header_img_ID, 'medium_large' );
  $hq_url = wp_get_attachment_image_url( $post_type_archive_header_img_ID, 'full' );

elseif ( '' !== $cats_header_img && is_post_type_archive( 'cats' ) ) :

  $post_type_archive_header_img_ID = attachment_url_to_postid( $cats_header_img );
  $lq_url = wp_get_attachment_image_url( $post_type_archive_header_img_ID, 'medium_large' );
  $hq_url = wp_get_attachment_image_url( $post_type_archive_header_img_ID, 'full' );

elseif ( $archive_header_img && is_post_type_archive( 'archive' ) ) :

  $post_type_archive_header_img_ID = attachment_url_to_postid( $archive_header_img );
  $lq_url = wp_get_attachment_image_url( $post_type_archive_header_img_ID, 'medium_large' );
  $hq_url = wp_get_attachment_image_url( $post_type_archive_header_img_ID, 'full' );

endif;

$lq_style_attr = sprintf( 'style="background-image: url(%s);"', $lq_url );
?>

<div class="site-header__bg">
  <?php if ( ! empty( $lq_url ) ) : ?>
    <div class="site-header__bg--lq" <?php echo $lq_style_attr; ?>></div>
    <div id="bgLazy" class="site-header__bg--hq" data-src="<?php echo $hq_url; ?>"></div>
  <?php endif; ?>
</div>