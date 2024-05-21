<?php
/**
 * Admin Menu.
 *
 * @package astrodj
 */

function astrodj_add_admin_page() {
  // Create Astrodj Admin Page
  add_menu_page( 'Astrodj Theme Options', 'Astrodj', 'manage_options', 'astrodj_admin', 'astrodj_theme_admin_page_cb', get_template_directory_uri() . '/images/astrodj-admin.png', 110 );

  // Create Astrodj Admin Sub Page
  add_submenu_page( 'astrodj_admin', 'Astrodj Theme Options', 'General', 'manage_options', 'astrodj_admin', 'astrodj_theme_admin_page_cb' );
  add_submenu_page( 'astrodj_admin', 'Astrodj SEO', 'SEO', 'manage_options', 'astrodj_admin_seo', 'astrodj_theme_seo_page_cb' );
  add_submenu_page( 'astrodj_admin', 'Astrodj Theme Settings', 'Settings', 'manage_options', 'astrodj_admin_settings', 'astrodj_theme_settings_page_cb' );

  // Activate Custom Settings
  add_action( 'admin_init', 'astrodj_theme_general_settings' );
  add_action( 'admin_init', 'astrodj_theme_seo_settings' );
  add_action( 'admin_init', 'astrodj_theme_other_settings' );
}
add_action( 'admin_menu', 'astrodj_add_admin_page' );

// General Page
function astrodj_theme_general_settings() {
  register_setting( 'astrodj-settings-group', 'astrodj_rights' );
  register_setting( 'astrodj-settings-group', 'astrodj_portfolio_posts_per_page' );
  register_setting( 'astrodj-settings-group', 'astrodj_stock_posts_per_page' );
  register_setting( 'astrodj-settings-group', 'astrodj_cats_posts_per_page' );
  register_setting( 'astrodj-settings-group', 'astrodj_archive_posts_per_page' );

  add_settings_section( 'astrodj-general-options', 'Главные настройки', 'astrodj_general_options_cb', 'astrodj_admin' );

  add_settings_field( 'rights-name', 'Имя автора снимков в Копирайте', 'astrodj_rights_name_cb', 'astrodj_admin', 'astrodj-general-options' );
  add_settings_field( 'portfolio-posts-per-page', 'На страницах галерей отображать не более', 'astrodj_portfolio_posts_per_page_cb', 'astrodj_admin', 'astrodj-general-options' );
  add_settings_field( 'stock-posts-per-page', 'На страницах сток отображать не более', 'astrodj_stock_posts_per_page_cb', 'astrodj_admin', 'astrodj-general-options' );
  add_settings_field( 'cats-posts-per-page', 'На страницах коты отображать не более', 'astrodj_cats_posts_per_page_cb', 'astrodj_admin', 'astrodj-general-options' );
  add_settings_field( 'archive-posts-per-page', 'На страницах архив отображать не более', 'astrodj_archive_posts_per_page_cb', 'astrodj_admin', 'astrodj-general-options' );
}

function astrodj_theme_seo_settings() {
  $post_types = [
    'portfolio' => 'Фотогалерея',
    'stock' => 'Сток',
    'cats' => 'Коты',
    'archive' => 'Архив',
  ];

  register_setting( 'astrodj-seo-settings', 'astrodj_fp_tagline' );

  foreach ( $post_types as $post_type => $title ) {
    register_setting( 'astrodj-seo-settings', "astrodj_seo_title_{$post_type}" );
    register_setting( 'astrodj-seo-settings', "astrodj_seo_description_{$post_type}" );
  }

  add_settings_section( 'astrodj-seo-options', 'SEO настройки', 'astrodj_seo_options_cb', 'astrodj_admin_seo' );

  add_settings_field( 'fp-tagline', 'tagline в title на главной странице (frontpage)', 'astrodj_fp_tagline_cb', 'astrodj_admin_seo', 'astrodj-seo-options' );

  foreach ( $post_types as $post_type => $title ) {
    add_settings_field( "title_{$post_type}", "SEO title на архивной {$title}", "astrodj_title_{$post_type}_cb", 'astrodj_admin_seo', 'astrodj-seo-options' );
    add_settings_field( "description_{$post_type}", "SEO description на архивной {$title}", "astrodj_description_{$post_type}_cb", 'astrodj_admin_seo', 'astrodj-seo-options' );
  }
}

// Settings Page
function astrodj_theme_other_settings() {
  register_setting( 'astrodj-other-settings', 'astrodj_api_key' );
  register_setting( 'astrodj-other-settings', 'astrodj_api_secret' );

  add_settings_section( 'astrodj-other-options', 'Другие настройки', 'astrodj_other_options_cb', 'astrodj_admin_settings' );

  add_settings_field( 'rights-name', 'Пользователь для WP REST API', 'astrodj_api_key_cb', 'astrodj_admin_settings', 'astrodj-other-options' );
  add_settings_field( 'portfolio-posts-per-page', 'Пароль для WP REST API', 'astrodj_api_secret_cb', 'astrodj_admin_settings', 'astrodj-other-options' );
}

function astrodj_general_options_cb() {
  echo 'Настройки главных опций проекта Astrodj';
}

function astrodj_seo_options_cb() {
  echo 'Настройки SEO';
}

function astrodj_other_options_cb() {
  echo 'Настройки других опций проекта Astrodj';
}

// main
function astrodj_rights_name_cb() {
  $astrodj_rights = esc_attr( get_option( 'astrodj_rights' ) );
  echo '<input type="text" name="astrodj_rights" value="' . $astrodj_rights . '" placeholder="Vas Zhigalov" />';
}

function astrodj_portfolio_posts_per_page_cb() {
  $portfolio_posts_per_page = esc_attr( get_option( 'astrodj_portfolio_posts_per_page' ) );
  echo '<input class="small-text" id="astrodj_portfolio_posts_per_page" type="number" name="astrodj_portfolio_posts_per_page" step="1" min="1" value="' . $portfolio_posts_per_page . '" /> записи';
}

function astrodj_stock_posts_per_page_cb() {
  $stock_posts_per_page = esc_attr( get_option( 'astrodj_stock_posts_per_page' ) );
  echo '<input class="small-text" id="astrodj_stock_posts_per_page" type="number" name="astrodj_stock_posts_per_page" step="1" min="1" value="' . $stock_posts_per_page . '" /> записи';
}

function astrodj_cats_posts_per_page_cb() {
  $cats_posts_per_page = esc_attr( get_option( 'astrodj_cats_posts_per_page' ) );
  echo '<input class="small-text" id="astrodj_cats_posts_per_page" type="number" name="astrodj_cats_posts_per_page" step="1" min="1" value="' . $cats_posts_per_page . '" /> записи';
}

function astrodj_archive_posts_per_page_cb() {
  $archive_posts_per_page = esc_attr( get_option( 'astrodj_archive_posts_per_page' ) );
  echo '<input class="small-text" id="astrodj_archive_posts_per_page" type="number" name="astrodj_archive_posts_per_page" step="1" min="1" value="' . $archive_posts_per_page . '" /> записи';
}

// seo
function astrodj_fp_tagline_cb() {
  $astrodj_fp_tagline = esc_attr( get_option( 'astrodj_fp_tagline' ) );
  echo '<input class="large-text" type="text" name="astrodj_fp_tagline" value="' . $astrodj_fp_tagline . '" />';
}

function astrodj_title_portfolio_cb() {
  $title = esc_attr( get_option( 'astrodj_seo_title_portfolio' ) );
  echo '<input class="large-text" type="text" name="astrodj_seo_title_portfolio" value="' . $title . '" />';
}

function astrodj_description_portfolio_cb() {
  $description = esc_attr( get_option( 'astrodj_seo_description_portfolio' ) );
  echo '<textarea class="large-text" name="astrodj_seo_description_portfolio">' . $description . '</textarea>';
}

function astrodj_title_stock_cb() {
  $title = esc_attr( get_option( 'astrodj_seo_title_stock' ) );
  echo '<input class="large-text" type="text" name="astrodj_seo_title_stock" value="' . $title . '" />';
}

function astrodj_description_stock_cb() {
  $description = esc_attr( get_option( 'astrodj_seo_description_stock' ) );
  echo '<textarea class="large-text" name="astrodj_seo_description_stock">' . $description . '</textarea>';
}

function astrodj_title_cats_cb() {
  $title = esc_attr( get_option( 'astrodj_seo_title_cats' ) );
  echo '<input class="large-text" type="text" name="astrodj_seo_title_cats" value="' . $title . '" />';
}

function astrodj_description_cats_cb() {
  $description = esc_attr( get_option( 'astrodj_seo_description_cats' ) );
  echo '<textarea class="large-text" name="astrodj_seo_description_cats">' . $description . '</textarea>';
}

function astrodj_title_archive_cb() {
  $title = esc_attr( get_option( 'astrodj_seo_title_archive' ) );
  echo '<input class="large-text" type="text" name="astrodj_seo_title_archive" value="' . $title . '" />';
}

function astrodj_description_archive_cb() {
  $description = esc_attr( get_option( 'astrodj_seo_description_archive' ) );
  echo '<textarea class="large-text" name="astrodj_seo_description_archive">' . $description . '</textarea>';
}

// other
function astrodj_api_key_cb() {
  $astrodj_api_key = esc_attr( get_option( 'astrodj_api_key' ) );
  echo '<input type="text" name="astrodj_api_key" value="' . $astrodj_api_key . '" />';
}

function astrodj_api_secret_cb() {
  $astrodj_api_secret = esc_attr( get_option( 'astrodj_api_secret' ) );
  echo '<div>';
  echo '<input class="regular-text" type="text" name="astrodj_api_secret" value="' . $astrodj_api_secret . '" />&nbsp;';
  echo '<button class="button astrodj-generate-pw" type="button"><span class="text">Сгенерировать</span></button>';
  echo '<p class="description">Хэшируется для фронт-части сайта</p>';
  echo '</div>';
}

// Main Page
function astrodj_theme_admin_page_cb() {
  require_once( get_template_directory() . '/inc/admin/templates/menu-general.php' );
}

function astrodj_theme_seo_page_cb() {
  require_once( get_template_directory() . '/inc/admin/templates/menu-seo.php' );
}

// Settings Page
function astrodj_theme_settings_page_cb() {
  require_once( get_template_directory() . '/inc/admin/templates/menu-settings.php' );
}