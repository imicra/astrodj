<?php
/**
 * Dropdown Menu.
 *
 * @package astrodj
 */

 function astrodj_dropdown_menu() {
  ?>
  <div class="dropdown-wrapper">
    <button class="dropdown-button" aria-describedby="tooltip">
      <?php
      echo astrodj_get_svg( array( 'icon' => 'dropdown-cicle' ) );
      ?>
    </button>
    <div class="dropdown-menu__wrapper" role="tooltip">
      <div class="dropdown-menu">
        <?php do_action( 'astrodj_dropdown_menu_content' ); ?>
      </div><!-- .dropdown-menu -->
      <div class="dropdown-menu-tip" data-popper-arrow></div>
    </div><!-- .dropdown-menu__wrapper -->
  </div><!-- .dropdown-wrapper -->
  <?php
 }

/**
 * Portfolio and Stock.
 */
function astrodj_dropdown_menu_content_post_type_archive() {
  if ( is_singular( array( 'portfolio', 'stock', 'cats' ) ) ) :

  global $post;
  $url = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );

  ?>
  <ul>
    <li>
      <a href="<?php echo $url[0]; ?>" target="_blank">Полный размер</a>
    </li>
  </ul>
  <?php
  endif;
}
add_action( 'astrodj_dropdown_menu_content', 'astrodj_dropdown_menu_content_post_type_archive' );

/**
 * Front Page.
 */
function astrodj_dropdown_menu_content_front_page() {
  if ( is_front_page() ) :
  ?>
  <nav id="frontpage-navigation" class="frontpage-navigation">
    <?php
    wp_nav_menu( array(
      'theme_location' => 'frontpage',
      'container'      => false,
      'menu_id'        => 'frontpage-menu',
    ) );
    ?>
  </nav><!-- #site-navigation -->
  <?php
  endif;
}
add_action( 'astrodj_dropdown_menu_content', 'astrodj_dropdown_menu_content_front_page' );

function astrodj_dropdown_menu_content_main_nav() {
  if ( ! is_singular( array( 'portfolio', 'stock', 'cats' ) ) && ! is_front_page() ) :
  ?>
    <nav id="sub-navigation" class="sub-navigation">
      <?php
      wp_nav_menu( array(
        'theme_location' => 'sub_main',
        'container'      => false,
        'menu_id'        => 'sub-menu',
      ) );
      ?>
    </nav>
  <?php
  endif;
}
add_action( 'astrodj_dropdown_menu_content', 'astrodj_dropdown_menu_content_main_nav' );
