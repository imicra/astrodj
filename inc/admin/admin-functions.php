<?php
/**
 * Admin Area functions.
 *
 * @package astrodj
 */

/**
 * Admin Menu.
 */
require_once dirname( __FILE__ ) . '/admin-menu.php';

/**
 * Add an image Media Uploader field to the Taxonomy.
 */
require_once dirname( __FILE__ ) . '/media-uploader.php';

/**
 * Extends Gutenderg.
 */
require_once dirname( __FILE__ ) . '/gutenberg/gutenberg.php';

/**
 * Customizing the Login Page.
 */
require_once dirname( __FILE__ ) . '/login-page.php';

/**
 * Scripts for Theme Settings.
 */
function astrodj_admin_scripts() {
  wp_enqueue_media();

  wp_enqueue_script( 'astrodj-admin-script', get_template_directory_uri() . '/js/admin.inc.js', array( 'jquery' ), '1.0.0', true );
}
add_action( 'admin_enqueue_scripts', 'astrodj_admin_scripts' );

function astrodj_admin_enqueue_scripts() {
  if ( 'astrodj_page_astrodj_admin_settings' === get_current_screen()->id ) {
    wp_enqueue_script( 'astrodj-admin-settings', get_template_directory_uri() . '/inc/admin/js/admin.js', array( 'jquery' ), '1.0.0', true );
  }
}
// add_action( 'admin_enqueue_scripts', 'astrodj_admin_enqueue_scripts' );

// add_action( 'in_admin_header', function(){  
// 	echo '<pre>'. print_r( get_current_screen()->id, 1 ) .'</pre>';
// } );
