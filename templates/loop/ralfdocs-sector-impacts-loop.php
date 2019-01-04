<?php
if(!defined('ABSPATH')){ exit; }

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$impacts = new WP_Query(array(
  'post_type' => 'impacts',
  'post_status' => 'publish',
  'paged' => $paged,
  'tax_query' => array(
    array(
      'taxonomy' => 'sectors',
      'field' => 'term_id',
      'terms' => $current_sector->term_id
    )
  )
));

if($impacts->have_posts()): while($impacts->have_posts()): $impacts->the_post();
  $impact_id = get_the_ID(); ?>

  <div class="loop-item">
    <h2 class="loop-item-title">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h2>
    <div class="loop-item-meta">
      <?php do_action('ralfdocs_article_meta', $impact_id); ?>
    </div>
  </div>
<?php endwhile; else: ?>
<p><?php echo esc_html__('Sorry, nothing was found.', 'ralfdocs'); ?></p>
<?php endif; ralfdocs_pagination(); wp_reset_postdata();
