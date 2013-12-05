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
    '#markup' => l(t("Advanced Search"), "node/3", array('attributes' => array('class' => array('adv_search')))),
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
  $results = &$variables['results'];
  foreach ($results as $key => &$result) {
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
    // Thumbnail link.
    $variables['results'][$key]['thumbnail'] = l($image, $result['object_url'], $options);
    $truncation_fields = array(
      theme_get_setting('uconn_theme_mods_solr_field') => theme_get_setting('uconn_theme_mods_solr_field'),
      theme_get_setting('uconn_theme_hocr_solr_field') => theme_get_setting('uconn_theme_hocr_solr_field'),
    );
    foreach ($truncation_fields as $field) {
      if (isset($result['solr_doc'][$field]) && !empty($result['solr_doc'][$field]['value'])) {
        if ($field === 'text_nodes_HOCR_hlt') {
          $result['solr_doc'][$field]['value'] .= l(t('...[more]'), $result['object_url'], $options);
        }
        else {
          if (strlen(strip_tags($result['solr_doc'][$field]['value'])) > 240) {
            $result['solr_doc'][$field]['value'] .= l(t('...[more]'), $result['object_url'], $options);
          }
        }
      }
    }
  }
}

/**
 * Implements template_preprocess_HOOK().
 */
function uconn_theme_preprocess_islandora_basic_collection_wrapper(&$variables) {
  drupal_add_js(drupal_get_path('theme', 'uconn_theme') . '/js/uconn_theme_more.js');
  $page_number = (empty($_GET['page'])) ? 0 : $_GET['page'];
  $page_size = (empty($_GET['pagesize'])) ? variable_get('islandora_basic_collection_page_size', '10') : $_GET['pagesize'];
  $islandora_object = $variables['islandora_object'];
  try {
    $dc = $islandora_object['DC']->content;
    $dc_object = DublinCore::importFromXMLString($dc);
    if (isset($islandora_object['MODS'])) {
      $mods_dom_doc = new DOMDocument();
      $mods_dom_doc->loadXML($islandora_object['MODS']->content);
      $mods_xpather = new DOMXPath($mods_dom_doc);
    }
  }
  catch (Exception $e) {
    drupal_set_message(t('Error retrieving object %s %t', array('%s' => $islandora_object->id, '%t' => $e->getMessage())), 'error', FALSE);
  }

  $variables['metadata'] = array();
  if (isset($islandora_object['MODS'])) {

    $title_results = $mods_xpather->query("//*[local-name() = 'title']/text()");
    if (isset($title_results->item(0)->textContent)) {
      $variables['metadata'][t('Title: ')] = $title_results->item(0)->textContent;
    }

    $creator_results = $mods_xpather->query("//*[local-name() = 'name' and @type = 'personal']/*[local-name() = 'namePart']/text()");
    if (isset($creator_results->item(0)->textContent)) {
      $variables['metadata'][t('Creator: ')] = $creator_results->item(0)->textContent;
    }

    $issued_start_results = $mods_xpather->query("//*[local-name() = 'dateIssued' and @point = 'start']/text()");
    $issued_end_results = $mods_xpather->query("//*[local-name() = 'dateIssued' and @point = 'end']/text()");
    if (isset($issued_start_results->item(0)->textContent) && isset($issued_end_results->item(0)->textContent)) {
      $variables['metadata'][t('Date: ')] = $issued_start_results->item(0)->textContent
        . '-' . $issued_start_results->item(0)->textContent;
    }

    $size_results = $mods_xpather->query("//*[local-name() = 'extent']/text()");
    if (isset($size_results->item(0)->textContent)) {
      $variables['metadata'][t('Size: ')] = $size_results->item(0)->textContent;
    }

    $abstract_results = $mods_xpather->query("//*[local-name() = 'abstract']/text()");
    if (isset($abstract_results->item(0)->textContent)) {
      $variables['metadata'][t('Description: ')] = '';
      if (strlen($abstract_results->item(0)->textContent) < 256) {
        $variables['short_description'] = $abstract_results->item(0)->textContent;
      }
      else {
        $variables['more_link'] = '<a href="#" class="uconn-show-more">' . t('[more]') . '</a>';
        $variables['short_description'] = '<span class="uconn-short-description">' . truncate_utf8($abstract_results->item(0)->textContent, 256, TRUE, TRUE) . '</span>';
        $variables['full_description'] = '<span class="uconn-hidden uconn-full-description">' . $abstract_results->item(0)->textContent . '</span>';
      }
    }

    $identifier_results = $mods_xpather->query("//*[local-name() = 'identifier' and @type = 'local']/text()");
    if (isset($identifier_results->item(0)->textContent)) {
      $variables['metadata'][t('Identifier: ')] = $identifier_results->item(0)->textContent;
    }

    $link_results = $mods_xpather->query("//*[local-name() = 'identifier' and @type = 'URL']/text()");
    if (isset($link_results->item(0)->textContent)) {
      $variables['metadata'][t('Permalink: ')] = $link_results->item(0)->textContent;
    }
  }

  $variables['islandora_dublin_core'] = isset($dc_object) ? $dc_object : NULL;
  $variables['dc_array'] = isset($dc_object) ? $dc_object->asArray() : array();
  $variables['islandora_object_label'] = $islandora_object->label;

  list($total_count, $results) = islandora_basic_collection_get_member_objects($islandora_object, $page_number, $page_size);

  pager_default_initialize($total_count, $page_size);
  $variables['collection_pager'] = theme('pager', array('quantity' => 10));
  $display = (empty($_GET['display'])) ? variable_get('islandora_basic_collection_default_view', 'grid') : $_GET['display'];
  $link_text = (empty($_GET['display'])) ? 'grid' : $_GET['display'];
  $query_params = drupal_get_query_parameters($_GET);
  global $base_url;

  $query_params['display'] = 'list';
  $list_link = array(
      'title' => t('List view'),
      'attributes' => array(
          'href' => url("islandora/object/$islandora_object->id", array('query' => $query_params)),
          'class' => array('islandora-view-list'),
      ),
  );
  $query_params['display'] = 'grid';
  $grid_link = array(
      'title' => t('Grid view'),
      'attributes' => array(
          'href' => url("islandora/object/$islandora_object->id", array('query' => $query_params)),
          'class' => array('islandora-view-grid'),
      ),
  );

  if ($display == 'grid') {
    $grid_link['attributes']['class'][] = 'active';
    $collection_content = theme('islandora_basic_collection_grid', array(
        'islandora_object' => $islandora_object,
        'collection_results' => $results,
    )
    );
  }
  else {
    $list_link['attributes']['class'][] = 'active';
    $collection_content = theme('islandora_basic_collection', array(
        'islandora_object' => $islandora_object,
        'collection_results' => $results,
    ));
  }

  $variables['view_links'] = array($grid_link, $list_link);
  $variables['collection_content'] = $collection_content;
}

/**
 * Implements hook_islandora_solr_object_result_alter().
 */
function uconn_theme_islandora_solr_object_result_alter(&$search_results, $query_processor) {
  if (isset($search_results['solr_doc'][theme_get_setting('uconn_theme_mods_solr_field')])) {
    if (is_array($search_results['solr_doc'][theme_get_setting('uconn_theme_mods_solr_field')])) {
      foreach ($search_results['solr_doc'][theme_get_setting('uconn_theme_mods_solr_field')] as &$multi) {
        $multi = truncate_utf8(
          $multi,
          256,
          TRUE
        );
      }
    }
    else {
      $search_results['solr_doc'][theme_get_setting('uconn_theme_mods_solr_field')] = truncate_utf8(
        $search_results['solr_doc'][theme_get_setting('uconn_theme_mods_solr_field')],
        256,
        TRUE
      );
    }
  }
  // Only need to truncate when the default query is used.
  if ($query_processor->internalSolrQuery == variable_get('islandora_solr_base_query', '*:*') && isset($search_results['solr_doc'][theme_get_setting('uconn_theme_hocr_solr_field')])) {
    // Probably not multi-valued but we'll do it anyway.
    if (is_array($search_results['solr_doc'][theme_get_setting('uconn_theme_hocr_solr_field')])) {
      foreach ($search_results['solr_doc'][theme_get_setting('uconn_theme_hocr_solr_field')] as &$multi) {
        $multi = truncate_utf8(
          $multi,
          256,
          TRUE
        );
      }
    }
    else {
      $search_results['solr_doc'][theme_get_setting('uconn_theme_hocr_solr_field')] = truncate_utf8(
        $search_results['solr_doc'][theme_get_setting('uconn_theme_hocr_solr_field')],
        256,
        TRUE
      );
    }
  }
}

/**
 * Implements hook_islandora_solr_query_alter().
 */
function uconn_theme_islandora_solr_query_alter($islandora_solr_query) {
  $islandora_solr_query->solrParams['hl.fragsize'] = 256;
}
