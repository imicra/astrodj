<?php
/**
 * WP REST API functions.
 * 
 * @package astrodj
 */

/**
 * Check if is request to WP REST API.
 */
function astrodj_is_request_to_rest_api() {
  if ( empty( $_SERVER['REQUEST_URI'] ) ) {
    return false;
  }

  $rest_prefix = trailingslashit( rest_get_url_prefix() );
  $request_uri = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );

  // Check if the request is to the WP API endpoints.
  $is_rest_api_request = ( false !== strpos( $request_uri, $rest_prefix ) );

  return $is_rest_api_request;
}

/**
 * Basic Authentication.
 */
function astrodj_rest_authenticate( $user_id ) {
  // Do not authenticate twice and check if is a request to our endpoint in the WP REST API.
  if ( ! empty( $user_id ) || ! astrodj_is_request_to_rest_api() ) {
    return $user_id;
  }

  $api_key      = '';
  $api_secret   = '';

  // If the $_GET parameters are present, use those first.
  if ( ! empty( $_GET['astrodj_api_key'] ) && ! empty( $_GET['astrodj_api_secret'] ) ) { // WPCS: CSRF ok.
    $api_key    = $_GET['astrodj_api_key']; // WPCS: CSRF ok, sanitization ok.
    $api_secret = $_GET['astrodj_api_secret']; // WPCS: CSRF ok, sanitization ok.
  }

  // If the above is not present, we will do full basic auth.
  if ( ! $api_key && ! empty( $_SERVER['PHP_AUTH_USER'] ) && ! empty( $_SERVER['PHP_AUTH_PW'] ) ) {
    $api_key    = $_SERVER['PHP_AUTH_USER']; // WPCS: CSRF ok, sanitization ok.
    $api_secret = $_SERVER['PHP_AUTH_PW']; // WPCS: CSRF ok, sanitization ok.
  }

  // Stop if don't have any key.
  if ( ! $api_key || ! $api_secret ) {
    return $user_id;
  }

  // get user if keys are right
  if ( ! empty( $_GET['astrodj_api_key'] ) && ! empty( $_GET['astrodj_api_secret'] ) ) { // for $_GET parameters
    if ( $api_key === get_option( 'astrodj_api_key' ) && password_verify( get_option( 'astrodj_api_secret' ), $api_secret ) ) {
      $user = get_userdata( 1 );
      $user_id = $user->ID;
    }
  } elseif ( ! empty( $_SERVER['PHP_AUTH_USER'] ) && ! empty( $_SERVER['PHP_AUTH_PW'] ) ) { // for basic auth
    if ( $api_key === get_option( 'astrodj_api_key' ) && $api_secret === get_option( 'astrodj_api_secret' ) ) {
      $user = get_userdata( 1 );
      $user_id = $user->ID;
    }
  }

  return $user_id;
}
add_filter( 'determine_current_user', 'astrodj_rest_authenticate', 15 );

/**
 * Require Authentication for All Requests.
 * 
 * @link https://developer.wordpress.org/rest-api/frequently-asked-questions/#require-authentication-for-all-requests
 */
function astrodj_rest_authentication_errors( $result ) {
  // If a previous authentication check was applied,
  // pass that result along without modification.
  if ( true === $result || is_wp_error( $result ) ) {
    return $result;
  }

  // No authentication has been performed yet.
  // Return an error if user is not logged in.
  if ( ! is_user_logged_in() ) {
      return new WP_Error(
          'rest_not_logged_in',
          __( 'You are not currently logged in.' ),
          array( 'status' => 401 )
      );
  }

  // Our custom authentication check should have no effect
  // on logged-in requests
  return $result;
}
add_filter( 'rest_authentication_errors', 'astrodj_rest_authentication_errors' );
