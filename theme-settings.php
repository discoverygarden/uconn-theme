<?php
/**
 * Implements hook_form_system_theme_settings_alter().
 *
 * @param $form
 *   Nested array of form elements that comprise the form.
 * @param $form_state
 *   A keyed array containing the current state of the form.
 */
function uconn_theme_form_system_theme_settings_alter(&$form, &$form_state, $form_id = NULL)  {
  // Work-around for a core bug affecting admin themes. See issue #943212.
  if (isset($form_id)) {
    return;
  }

  // Create the form using Forms API: http://api.drupal.org/api/7
  $form['uconn_theme_mods_solr_field'] = array(
    '#type'          => 'textfield',
    '#title'         => t('Solr MODS abstract field.'),
    '#default_value' => theme_get_setting('uconn_theme_mods_solr_field'),
    '#description'   => t("This field will be truncated."),
  );

  // Remove some of the base theme's settings.
  unset($form['themedev']['zen_wireframes']); // We don't need to toggle wireframes on this site.

  // We are editing the $form in place, so we don't need to return anything.
}
