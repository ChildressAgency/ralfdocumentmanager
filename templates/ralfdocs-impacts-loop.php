<?php
if(!defined('ABSPATH')){ exit; }

$impact_id = get_the_ID();
$sectors = get_the_terms($impact_id, 'sectors'); ?>

<article class="ralf-article">
  <header class="result-header">
    <h1><?php the_title(); ?></h1>
    <div class="result-meta">
      <?php do_action('ralfdocs_article_meta', $impact_id); ?>
    </div>
  </header>

  <section class="result-content">
    <?php the_content(); ?>
  </section>
  <?php echo do_shortcode('[report_button]'); ?>

  <section class="related">
    <h3><?php echo esc_html__('Related Activities', 'ralfdocs'); ?></h3>
    <?php 
      $related_activities = ralfdocs_get_related_activities($impact_id, 'impacts');
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

  <section class="related">
    <h3><?php echo esc_html__('Related Resources', 'ralfdocs'); ?></h3>
    <?php 
      $related_resources = get_field('related_resources', $impact_id);
      if($related_resources): ?>
        <ul>
          <?php foreach($related_resources as $resource): ?>
            <li><a href="<?php echo get_permalink($resource); ?>"><?php echo get_the_title($resource); ?></a></li>
          <?php endforeach; ?>
        </ul>
    <?php else: ?>
      <p><?php echo esc_html__('No related Resources', 'ralfdocs'); ?></p>
    <?php endif; ?>
  </section>
</article>
