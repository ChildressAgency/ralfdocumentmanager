<?php 
if(!defined('ABSPATH')){ exit; }

$resources = new WP_Query(array(
  'post_type' => 'resources',
  'post_status' => 'publish',
  'tax_query' => array(
    array(
      'taxonomy' => 'sectors',
      'field' => 'term_id',
      'terms' => $current_sector->term_id
    )
  )
));

if($resources->have_posts()): while($resources->have_posts()): $resources->the_post();
  $resource_id = get_the_ID(); ?>

  <div class="loop-item">
    <h2 class="loop-item-title">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h2>
    <div class="loop-item-meta">
      <?php do_action('ralfdocs_article_meta', $resource_id); ?>
    </div>
  </div>
<?php endwhile; else: ?>
<p><?php echo esc_html__('Sorry, no resources found.', 'ralfdocs'); ?></p>
<?php endif; wp_reset_postdata();