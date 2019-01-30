<?php
/**
 * Template for displaying items in search and taxonomy results
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/loop/loop-item.php
 */
if(!defined('ABSPATH')){ exit; }
?>

<div class="loop-item">
  <h2 class="loop-item-title">
    <a href="<?php echo esc_url(get_permalink($article_id)); ?>"><?php echo esc_html(get_the_title($article_id)); ?></a>
  </h2>
  <div class="loop-item-meta">
    <?php 
      if(has_term($searched_word, 'priority_keywords', $post)){
        echo '<span class="priority"></span>';
      }

      include ralfdocs_get_template('article-meta.php');
    ?>
  </div>
</div>