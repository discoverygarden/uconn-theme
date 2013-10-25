<?php
/**
 * @file
 * Contains the theme's functions to manipulate Drupal's default markup.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728096
 */


/**
 * Override or insert variables into the maintenance page template.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("maintenance_page" in this case.)
 */
/* -- Delete this line if you want to use this function
function uconn_theme_preprocess_maintenance_page(&$variables, $hook) {
  // When a variable is manipulated or added in preprocess_html or
  // preprocess_page, that same work is probably needed for the maintenance page
  // as well, so we can just re-use those functions to do that work here.
  uconn_theme_preprocess_html($variables, $hook);
  uconn_theme_preprocess_page($variables, $hook);
}
// */

/**
 * Override or insert variables into the html templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("html" in this case.)
 */
/* -- Delete this line if you want to use this function
function uconn_theme_preprocess_html(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');

  // The body tag's classes are controlled by the $classes_array variable. To
  // remove a class from $classes_array, use array_diff().
  //$variables['classes_array'] = array_diff($variables['classes_array'], array('class-to-remove'));
}
// */

/**
 * Override or insert variables into the page templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */
/* -- Delete this line if you want to use this function
function uconn_theme_preprocess_page(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the node templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
/* -- Delete this line if you want to use this function
function uconn_theme_preprocess_node(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');

  // Optionally, run node-type-specific preprocess functions, like
  // uconn_theme_preprocess_node_page() or uconn_theme_preprocess_node_story().
  $function = __FUNCTION__ . '_' . $variables['node']->type;
  if (function_exists($function)) {
    $function($variables, $hook);
  }
}
// */

/**
 * Override or insert variables into the comment templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("comment" in this case.)
 */
/* -- Delete this line if you want to use this function
function uconn_theme_preprocess_comment(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the region templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("region" in this case.)
 */
/* -- Delete this line if you want to use this function
function uconn_theme_preprocess_region(&$variables, $hook) {
  // Don't use Zen's region--sidebar.tpl.php template for sidebars.
  //if (strpos($variables['region'], 'sidebar_') === 0) {
  //  $variables['theme_hook_suggestions'] = array_diff($variables['theme_hook_suggestions'], array('region__sidebar'));
  //}
}
// */

/**
 * Override or insert variables into the block templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
/* -- Delete this line if you want to use this function
function uconn_theme_preprocess_block(&$variables, $hook) {
  // Add a count to all the blocks in the region.
  // $variables['classes_array'][] = 'count-' . $variables['block_id'];

  // By default, Zen will use the block--no-wrapper.tpl.php for the main
  // content. This optional bit of code undoes that:
  //if ($variables['block_html_id'] == 'block-system-main') {
  //  $variables['theme_hook_suggestions'] = array_diff($variables['theme_hook_suggestions'], array('block__no_wrapper'));
  //}
}
// */

function uconn_theme_form_islandora_solr_simple_search_form_alter(&$form, &$form_state, $form_id) {
  $link = array(
    '#markup' => l(t("Advanced Search"), "node/6", array('attributes' => array('class' => array('adv_search')))),
  );
  $form['simple']['advanced_link'] = $link;
}

// Book reader meta styling and detailing //
function uconn_theme_islandora_internet_archive_bookreader_book_info(&$vars) {
  $object = $vars['object'];
  $rows = array();

  $to_string = function($o) {
        return (string) $o;
      };

  if (isset($object['MODS']) && islandora_datastream_access(FEDORA_VIEW_OBJECTS, $object['MODS'])) {
    $xml = simplexml_load_string($object['MODS']->content);
    $xml->registerXPathNamespace('mods', 'http://www.loc.gov/mods/v3');

    $fields = array(
      array('Title', '/mods:mods/mods:titleInfo/mods:title[1]'),
      array('Creator', '/mods:mods/mods:name[@type="personal"]/mods:namePart'),
      array('Role', '/mods:mods/mods:name[@type="personal"]/mods:role/mods:roleTerm'),
      array('Date', '/mods:mods/mods:originInfo/mods:dateIssued[1]'),
      array('Description', '/mods:mods/mods:abstract'),
      array('Link', '/mods:mods/mods:identifier[@type="hdl"]'),
      array('Identifier', '/mods:mods/mods:identifier[@type="local"]'),
      array('Genre', '/mods:mods/mods:genre'),
      array('Topic', '/mods:mods/mods:subject/mods:topic'),
      array('Geographic', '/mods:mods/mods:subject/mods:geographic'),
      array('Temporal', '/mods:mods/mods:subject/mods:temporal'),
      array('Citation', '/mods:mods/mods:note[@type="Preferred Citation"]'),
    );

    foreach ($fields as $field => $columns) {
      $xpath = (isset($columns[1]) ? $columns[1] : '');
      $values = array();
      if ($xpath) {
        $values = $xml->xpath($xpath);
        if (count($values) > 0) {
          $values = array_map($to_string, $values);
          $rows[] = array(array(
              'class' => array('metadata-field'),
              'data' => $columns[0]
            ), array(
              'class' => array('metadata-value'),
              'data' => implode('; ', $values)
              ));
        }
      }
    }
  }

  $content = theme('table', array(
    'caption' => '',
    'empty' => t('No Information specified.'),
    'attributes' => array(),
    'colgroups' => array(),
    'header' => array(t('Field'), t('Values')),
    'rows' => $rows,
    'sticky' => FALSE));


  return $content;
}

/**
 * Prepares variables for islandora_solr templates.
 */
function uconn_theme_preprocess_islandora_solr(&$variables) {
  $results = $variables['results'];
  foreach ($results as $key => $result) {
    // Thumbnail.
    $path = url($result['thumbnail_url'], array('query' => $result['thumbnail_url_params']));
    $image = theme('image', array('path' => $path));

    $options = array('html' => TRUE);
    if (isset($result['object_label'])) {
      $options['attributes']['title'] = $result['object_label'];
    }
    if (isset($result['object_url_params'])) {
      $options['query'] = $result['object_url_params'];
    }
    if (isset($result['object_url_fragment'])) {
      $options['fragment'] = $result['object_url_fragment'];
    }
    // Customization trim of abstract.
    if (isset($result['solr_doc'][theme_get_setting('uconn_theme_mods_solr_field')]['value'])) {
      $variables['results'][$key]['solr_doc'][theme_get_setting('uconn_theme_mods_solr_field')]['value'] = substr(
        $result['solr_doc'][theme_get_setting('uconn_theme_mods_solr_field')]['value'],
        0,
        150
      );
    }
    // End customization trim of abstract.
    // Thumbnail link.
    $variables['results'][$key]['thumbnail'] = l($image, $result['object_url'], $options);
  }
}
