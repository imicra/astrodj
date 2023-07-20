<?php
/**
 * Displays Account Login in Sidebar.
 *
 * @package astrodj
 */

?>

<section id="astrodj-account" class="widget widget_astrodj_account">
  <div class="widget_account__wrapper">
    <?php
    $frontpage_avatar = get_theme_mod( 'frontpage_avatar_img', esc_url( get_template_directory_uri() . '/images/avatar.jpg' ) );
    $frontpage_username = get_theme_mod( 'frontpage_username', esc_html__( 'Vas Zhigalov', 'astrodj' ) );

    if ( ! empty( $frontpage_avatar ) ) :
      $frontpage_avatar_img_ID = attachment_url_to_postid( $frontpage_avatar );
      $avatar_url = wp_get_attachment_image_src( $frontpage_avatar_img_ID, 'full' );
    ?>
    <figure class="widget_account__logo">
      <a href="<?php echo wp_login_url(); ?>" class="custom-logo-link">
        <img width="<?php echo $avatar_url[1] ?>" height="<?php echo $avatar_url[2] ?>" class="custom-logo" src="<?php echo $frontpage_avatar ?>" alt="Vaz Zhigalov">
      </a>
      <?php if ( ! empty( $frontpage_username ) ) : ?>
      <figcaption class="user"><?php echo $frontpage_username ?></figcaption>
      <?php endif; ?>
    </figure>
    <?php else :

      wp_loginout( $_SERVER['REQUEST_URI'], true );

    endif; ?>
    <div class="widget_account__btn">
      <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Написать автору</a>
    </div>
    <?php if ( is_user_logged_in() ) : ?>
    <div class="widget_account__logout">
      <?php wp_loginout( $_SERVER['REQUEST_URI'], true ); ?>
    </div>
    <?php endif; ?>
  </div>
</section>