<?php
if(!defined('ABSPATH')){ exit; }
?>

<ul>
  <?php foreach($related_resources as $resource): ?>
    <li><a href="<?php echo esc_url(get_permalink($resource)); ?>"><?php echo esc_html(get_the_title($resource)); ?></a></li>
  <?php endforeach; ?>
</ul>
