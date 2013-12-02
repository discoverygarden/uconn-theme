<?php

/**
 * @file
 * islandora-basic-collection-wrapper.tpl.php
 *
 * @TODO: needs documentation about file and variables
 */
?>
<div class="islandora-basic-collection-wrapper">
  <?php if (!empty($dc_array['dc:description']['value'])): ?>
    <p><?php print $dc_array['dc:description']['value']; ?></p>
    <hr />
  <?php endif; ?>
  <div class="islandora-basic-collection clearfix">
    <span class="islandora-basic-collection-display-switch">
      <ul class="links inline">
        <?php foreach ($view_links as $link): ?>
          <li>
            <a <?php print drupal_attributes($link['attributes']) ?>><?php print filter_xss($link['title']) ?></a>
          </li>
        <?php endforeach ?>
      </ul>
    </span>
    <div class="islandora-basic-collection-wrapper-metadata">
   <?php if (!empty($metadata)): ?>
      <?php foreach ($metadata as $metadatum_label => $metadatum): ?>
        <div class="islandora-basic-collection-wrapper-metadatum">
          <span><?php print $metadatum_label; ?></span>
          <span><?php print $metadatum; ?></span>
        </div>
      <?php endforeach ?>
   <?php endif; ?>
    </div>
    <?php print $collection_pager; ?>
    <?php print $collection_content; ?>
    <?php print $collection_pager; ?>
  </div>
</div>
