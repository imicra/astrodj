<?php
/**
 * Theme Widgets.
 *
 * @package astrodj
 */

/**
 * Widget Latest CPT.
 */
require_once dirname(__FILE__) . '/widget-latest-cpt.php';

/**
 * Widget Photo Filter.
 */
require_once dirname(__FILE__) . '/widget-photo-filter.php';

/**
 * Widget Photo Gallery.
 */
require_once dirname(__FILE__) . '/widget-photo-gallery.php';

/**
 * Add icon to Widget's Tag Cloud Title in sidebar-1.
 */
function astrodj_widget_tag_cloud_icon( $params ) {
	if ( $params[0]['widget_id'] == 'tag_cloud-2' ) {
		$params[0]['before_title'] .= astrodj_get_svg( array( 'icon' => 'tag' ) );
	}

	return $params;
}
add_filter( 'dynamic_sidebar_params', 'astrodj_widget_tag_cloud_icon' );