<?php
/**
 * Template for showing related resources on single Activities pages
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/related/related-resources.php
 */
if(!defined('ABSPATH')){ exit; }
?>

<section class="related">
  <h3><?php echo esc_html__('Related Resources', 'ralfdocs'); ?></h3>
  <?php 
    $related_resources = get_field('related_resources', $article_id);
    if($related_resources): ?>
      <ul>
        <?php foreach($related_resources as $resource): ?>
          <li><a href="<?php echo esc_url(get_permalink($resource)); ?>"><?php echo esc_html(get_the_title($resource)); ?></a></li>
        <?php endforeach; ?>
      </ul>
  <?php else: ?>
    <p><?php echo esc_html__('No related Resources', 'ralfdocs'); ?></p>
  <?php endif; ?>
</section>