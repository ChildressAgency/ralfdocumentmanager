<?php
if(!defined('ABSPATH')){ exit; }

if(isset($_POST['factor']) && !empty($_POST['factor'])){
  $impact_tag_ids = $_POST['factor'];
  $impact_tag_ids = array_map('intval', $impact_tag_ids);

  $impact_tag_names = [];
  foreach($impact_tag_ids as $index => $impact_tag){
    $term = get_term_by('id', $impact_tag, 'impact_tags');
    $impact_tag_names[] = $term->name;
  }
  $impact_tag_names = implode(', ', $impact_tag_names);
  echo '<h1>' . sprintf(esc_html__('Showing results for "%s"', 'ralfdocs'), $impact_tag_names) . '</h1>';

  $paged = get_query_var('paged') ? get_query_var('paged') : 1;
  $factors = new WP_Query(array(
    'post_type' => 'impacts',
    'post_status' => 'publish',
    'paged' => $paged,
    'tax_query' => array(
      array(
        'taxonomy' => 'impact_tags',
        'field' => 'term_id',
        'terms' => $impact_tag_ids
      )
    )
  ));

  if($factors->have_posts()): while($factors->have_posts()):
    $factors->the_post();
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
    <p><?php echo esc_html__('Sorry, nothing was found for your selected factors.', 'ralfdocs'); ?></p>
  <?php endif; ralfdocs_pagination(); wp_reset_postdata();
}
else{
  echo '<p>' . esc_html__('You did not select any factors.', 'ralfdocs') . '</p>';
} ?>
