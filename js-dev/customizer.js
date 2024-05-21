/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	// Site title and description.
	wp.customize( 'astrodj_blogname', function( value ) {
		value.bind( function( to ) {
			$( '.site-title a' ).text( to );
		} );
	} );
	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( to ) {
			$( '.site-description' ).text( to );
		} );
	} );

	// Header text color.
	wp.customize( 'header_textcolor', function( value ) {
		value.bind( function( to ) {
			if ( 'blank' === to ) {
				$( '.site-title, .site-description' ).css( {
					'clip': 'rect(1px, 1px, 1px, 1px)',
					'position': 'absolute'
				} );
			} else {
				$( '.site-title, .site-description' ).css( {
					'clip': 'auto',
					'position': 'relative'
				} );
				$( '.site-title a, .site-description' ).css( {
					'color': to
				} );
			}
		} );
	} );

	// Frontpage Header Image
	wp.customize( 'frontpage_avatar_img', function( value ) {
		value.bind( function( to ) {
			$('.front-page__hero .avatar').find('img').attr( 'src', to );
		} );
	} );

	// Frontpage Title
	wp.customize( 'frontpage_username', function( value ) {
		value.bind( function( to ) {
			$('.front-page__hero .avatar .user').text( to );
		} );
	} );

	// Portfolio Header Image
	wp.customize( 'portfolio_header_img', function( value ) {
		value.bind( function( to ) {
			$('.site-header__img').css( 'background-image', 'url(' + to + ')' );
		} );
	} );

	// Portfolio Title
	wp.customize( 'portfolio_title', function( value ) {
		value.bind( function( to ) {
			$('.archive-portfolio .archive-title, .archive-portfolio .site-title span').text( to );
		} );
	} );

	// Portfolio Subtitle
	wp.customize( 'portfolio_subtitle', function( value ) {
		value.bind( function( to ) {
			$('.archive-portfolio .site-description').text( to );
		} );
	} );

	// Stock Title
	wp.customize( 'stock_title', function( value ) {
		value.bind( function( to ) {
			$('.archive-stock .site-title span, .archive-portfolio .archive-title').text( to );
		} );
	} );

	// Stock Subtitle
	wp.customize( 'stock_subtitle', function( value ) {
		value.bind( function( to ) {
			$('.archive-stock .site-description').text( to );
		} );
	} );

	// Cats Title
	wp.customize( 'cats_title', function( value ) {
		value.bind( function( to ) {
			$('.archive-stock .site-title span, .archive-portfolio .archive-title').text( to );
		} );
	} );

	// Cats Subtitle
	wp.customize( 'cats_subtitle', function( value ) {
		value.bind( function( to ) {
			$('.archive-stock .site-description').text( to );
		} );
	} );
} )( jQuery );
