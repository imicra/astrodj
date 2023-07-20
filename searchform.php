<?php
/**
 * The template for displaying search form
 *
 * @package Astrodj
 */

?>

<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label' ) ?></span>
    <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Поиск …', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" autocomplete="off" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
    <svg role="presentation" class="i-search" viewBox="0 0 32 32" width="14" height="14" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="3">
      <circle cx="14" cy="14" r="12" />
      <path d="M23 23 L30 30" />
    </svg>
	</label>
</form>