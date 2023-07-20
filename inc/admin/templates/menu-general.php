<?php
/**
 * Admin General Menu Page Template.
 *
 * @package astrodj
 */
?>

<h1>Astrodj Настройки</h1>
<?php settings_errors(); ?>
<form method="post" action="options.php">
  <?php settings_fields( 'astrodj-settings-group' ); ?>
  <?php do_settings_sections( 'astrodj_admin' ); ?>
  <?php submit_button(); ?>
</form>