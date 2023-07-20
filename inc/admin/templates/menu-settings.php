<?php
/**
 * Admin General Menu Page Template.
 *
 * @package astrodj
 */
?>

<h1>Astrodj Theme Settings</h1>
<?php settings_errors(); ?>
<form method="post" action="options.php">
  <?php settings_fields( 'astrodj-other-settings' ); ?>
  <?php do_settings_sections( 'astrodj_admin_settings' ); ?>
  <?php submit_button(); ?>
</form>