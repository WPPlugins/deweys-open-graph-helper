<?php
  global $post;

  $og_title = get_post_meta($post->ID, 'og_title', true);
  if (!$og_title) $og_title = '';

  $og_desc = get_post_meta($post->ID, 'og_desc', true);
  if (!$og_desc) $og_desc = false;

  $og_type = get_post_meta($post->ID, 'og_type', true);
  if (!$og_type) $og_type = '';
?>
<fieldset class="meta-fieldset">
  <legend>Open Graph Tags</legend>

  <div class="meta-item">
    <label for="og_title">Title (Defaults to Post Title)</label>
    <input type="text" class="widefat" name="og_title" value="<?= ($og_title) ? htmlspecialchars($og_title) : '' ?>">
  </div>

  <div class="meta-item">
    <label for="og_desc">Description (Defaults to Post Excerpt)</label>
    <textarea name="og_desc" class="widefat" name="og_desc"><?= ($og_desc) ? htmlspecialchars($og_desc) : '' ?></textarea>
  </div>

  <div class="meta-item">
    <label for="og_type">Type (Defaults to 'article')</label>
    <input type="text" class="widefat" name="og_type" value="<?= ($og_type) ? htmlspecialchars($og_type) : '' ?>">
  </div>
</fieldset>
