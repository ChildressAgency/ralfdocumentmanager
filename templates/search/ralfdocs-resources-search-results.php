<?php
if(!defined('ABSPATH')){ exit; }

$resources = new SWP_Query(array(
  'post_type' => 'resources',
  's' => $searched_word,
  'engine' => 'default',
  'posts_per_page' => -1,
  'fields' => 'all'
));

if(!empty($resources->posts)): foreach($resources->posts as $post):
  setup_postdata($post);
  $resource_id = get_the_ID(); ?>

  <div class="loop-item">
    <h2 class="loop-item-title">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h2>
    <div class="loop-item-meta">
      <?php 
        if(has_term($searched_word, 'priority_keywords')){
          echo '<span class="priority"></span>';
        }

        do_action('ralfdocs_article_meta', $resource_id);
      ?>
    </div>
  </div>
<?php endforeach; else: ?>
<p><?php echo esc_html__('Sorry, no resources found.', 'ralfdocs'); ?></p>
<?php endif; wp_reset_postdata();
