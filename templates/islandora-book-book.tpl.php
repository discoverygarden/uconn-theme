<?php
/**
 * @file
 * Template file to style output.
 */
?>
<?php if (isset($viewer)): ?>
  <div id="book-viewer">
    <?php print $viewer; ?>
  </div>
<?php endif; ?>
<a class="dwn_btn" href="<?php print url("islandora/object/{$object}/datastream/PDF/download"); ?>">Download</a>
<!-- @todo Add table of metadata values -->
