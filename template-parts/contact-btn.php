<?php
/**
 * Template part for displaying contact button in sidebar.
 *
 * @package Astrodj
 */

$page = get_page_by_path( 'contact' );

if ( 'draft' !== $page->post_status ) :
?>
  <div class="widget_account__btn">
    <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Написать автору</a>
  </div>
<?php
endif;
