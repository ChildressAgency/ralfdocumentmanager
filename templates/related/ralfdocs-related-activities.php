<?php
if(!defined('ABSPATH')){ exit; }
?>

<section class="related">
  <h3><?php echo esc_html__('Related Activities', 'ralfdocs'); ?></h3>
  <?php 
    $related_activities = ralfdocs_get_related_activities($article_id, $article_type);
    if($related_activities->have_posts()): ?>
      <ul>
        <?php while($related_activities->have_posts()): $related_activities->the_post(); ?>
          <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
        <?php endwhile; ?>
      </ul>
  <?php else: ?>
    <p><?php echo esc_html__('No related Activities', 'ralfdocs'); ?></p>
  <?php endif; wp_reset_postdata(); ?>
</section>
