<?php
if(!defined('ABSPATH')){ exit; }
?>

<section class="related">
  <h3><?php echo esc_html__('Related Impacts', 'ralfdocs'); ?></h3>
  <?php
    $related_impacts = ralfdocs_get_related_impacts($resource_id);
    if($related_impacts->have_posts()): ?>
      <ul>
        <?php while($related_impacts->have_posts()): $related_impacts->the_post(); ?>
          <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
        <?php endwhile; ?>
      </ul>
  <?php else: ?>
    <p><?php echo esc_html__('No related Impacts.', 'ralfdocs'); ?></p>
  <?php endif; wp_reset_postdata(); ?>
</section>