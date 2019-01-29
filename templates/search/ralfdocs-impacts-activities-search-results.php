<?php
if(!defined('ABSPATH')){ exit; }

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$impacts_activities = new SWP_Query(array(
  'post_type' => array('impacts', 'activities'),
  's' => $searched_word,
  'engine' => 'default',
  'posts_per_page' => 10,
  'page' => $paged,
  'fields' => 'all'
));

/*$impacts_activities = new WP_Query(array(
  'post_type' => array('impacts', 'activities'),
  's' => $searched_word,
  'posts_per_page' => 10,
  'paged' => $paged,
  'fields' => 'all'
));*/

if(!empty($impacts_activities->posts)): foreach($impacts_activities->posts as $post):
  setup_postdata($post);
  //var_dump($post);
//if($impacts_activities->have_posts()): while($impacts_activities->have_posts()): $impacts_activities->the_post();
  $article_id = $post->ID; ?>

  <div class="loop-item">
    <h2 class="loop-item-title">
      <a href="<?php echo esc_url(get_permalink($article_id)); ?>"><?php echo esc_html(get_the_title($article_id)); ?></a>
    </h2>
    <div class="loop-item-meta">
      <?php 
        if(has_term($searched_word, 'priority_keywords', $post)){
          echo '<span class="priority"></span>';
        }

        do_action('ralfdocs_article_meta', $article_id);
      ?>
    </div>
  </div>
      <?php endforeach; wp_reset_postdata(); else: ?>
  <p><?php echo esc_html__('Sorry, nothing was found.', 'ralfdocs'); ?></p>
<?php endif; ralfdocs_pagination(); //wp_reset_postdata();