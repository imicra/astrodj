<?php
/**
 * Customizing the Login Page.
 *
 * @package astrodj
 */

/**
 * Add custom logo to the login page.
 */
function astrodj_filter_login_head() {

  $frontpage_avatar = get_theme_mod( 'frontpage_avatar_img', esc_url( get_template_directory_uri() . '/images/avatar.jpg' ) );
 
  if ( ! empty( $frontpage_avatar ) ) :

    $frontpage_avatar_img_ID = attachment_url_to_postid( $frontpage_avatar );
    $avatar = wp_get_attachment_image_src( $frontpage_avatar_img_ID, 'full' );

    ?>
    <style type="text/css">
      body {
        display: flex;
        flex-direction: column;
      }

      .menu-login-container {
        display: flex;
        justify-content: center;
        background-color: #fff;
      }

      .menu-login-container .menu {
        display: flex;
        list-style: none;
        overflow-x: auto;
        overflow-y: hidden;
      }

      .menu-login-container .menu li {
        padding: 1.5em 0;
        border-bottom: 3px solid transparent;
      }

      .menu-login-container .menu li:hover {
        border-bottom: 3px solid #0091dc;
      }

      .menu-login-container .menu a {
        font-size: 1.1rem;
        color: #222;
        padding: 1.5em 1em;
        text-decoration: none;
      }

      .login-permission {
        margin-top: 2em;
        margin-bottom: 2em;
        text-align: center;
        line-height: 1.6;
      }

      .login-permission__big {
        font-size: 1.1rem;
        font-weight: 700;
      }

      .login-permission__small {
        font-size: 1rem;
      }

      #login {
        flex-grow: 1;
        padding: 0 0 3em;
        margin: 0 auto;
      }

      .login h1 a {
        background-image: url(<?php echo esc_url( $avatar[0] ); ?>);
        background-size: 80px;
        height: 80px;
        width: 80px;
        border: 4px solid #fff;
        border-radius: 50%;
      }

      .site-footer {
        font-size: 1.04rem;
        padding: 2em 1em;
        color: #fff;
        background-color: #1a1a1a;
        text-align: center;
      }

      #nav,
      #backtoblog {
        display: none;
      }
    </style>
    <?php
  endif;
}
add_action( 'login_head', 'astrodj_filter_login_head', 100 );

/**
 * Logo link
 */
function astrodj_login_logo_url() {
  return home_url();
}
add_filter( 'login_headerurl', 'astrodj_login_logo_url' );

/**
 * Heading text
 */
function astrodj_login_logo_url_text() {
  return 'Astrodj.ru';
}
add_filter( 'login_headertext', 'astrodj_login_logo_url_text' );

/**
 * Header Page
 */
function astrodj_login_header() {
  if ( has_nav_menu( 'login' ) ) :
    wp_nav_menu( array(
      'theme_location' => 'login',
      'menu_id'        => 'login-menu',
    ) );
  endif;

  echo '<div class="login-permission">';
  echo '<div class="login-permission__big">Вход на сайт только для Astrodj</div>';
  echo '<div class="login-permission__small">(вернитесь назад или воспользуйтесь навигацией)</div>';
  echo '</div>';
}
add_action( 'login_head', 'astrodj_login_header', 100 );

/**
 * Footer Page
 */
function astrodj_login_footer() {
  ?>
  <footer id="colophon" class="site-footer">
		<div class="site-info">
			<div class="site-rights">
				<span class="dev">
					&copy; 2019 - <?php echo date( 'Y' ); ?> astrodj.ru
				</span>
			</div>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
  <?php
}
add_action( 'login_footer', 'astrodj_login_footer', 100 );

/**
 * Remove the Language dropdown
 */
add_filter( 'login_display_language_dropdown', '__return_false' );
