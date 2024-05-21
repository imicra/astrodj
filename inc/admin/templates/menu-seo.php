<?php
/**
 * Admin General Menu Page Template.
 *
 * @package astrodj
 */
?>

<h1>Настройки SEO</h1>
<?php settings_errors(); ?>
<form method="post" action="options.php">
  <?php settings_fields( 'astrodj-seo-settings' ); ?>
  <?php do_settings_sections( 'astrodj_admin_seo' ); ?>
  <?php submit_button(); ?>
</form>