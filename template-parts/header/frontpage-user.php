<?php
/**
 * Displays Avatar and Nickname on Frontpage
 *
 * @package astrodj
 */

$frontpage_avatar = get_theme_mod( 'frontpage_avatar_img', esc_url( get_template_directory_uri() . '/images/avatar.jpg' ) );
$frontpage_username = get_theme_mod( 'frontpage_username', esc_html__( 'Vas Zhigalov', 'astrodj' ) );

if ( ! empty( $frontpage_avatar ) ) {
  $frontpage_avatar_img_ID = attachment_url_to_postid( $frontpage_avatar );
  $avatar_url = wp_get_attachment_image_src( $frontpage_avatar_img_ID, 'full' );
}
?>

<figure class="avatar">
  <?php if ( ! empty( $frontpage_avatar ) ) : ?>
  <a class="fancybox" href="<?php echo $frontpage_avatar ?>">
    <img width="<?php echo $avatar_url[1] ?>" height="<?php echo $avatar_url[2] ?>" src="<?php echo $frontpage_avatar ?>" alt="">
  </a>
  <?php endif; ?>
  <?php if ( ! empty( $frontpage_username ) ) : ?>
  <figcaption class="user"><?php echo $frontpage_username ?></figcaption>
  <?php endif; ?>
</figure>
