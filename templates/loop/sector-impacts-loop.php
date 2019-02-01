<?php
/**
 * Template for displaying the sectors taxonomy archive page
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/loop/sector-impacts-loop.php
 */
if(!defined('ABSPATH')){ exit; }

include ralfdocs_get_template('loop/impacts-activities-tab.php');
?>

<div class="tab-content">
  <div id="impacts">

    <?php
      if(!empty($impacts->posts)){
        foreach($impacts->posts as $post){
          setup_postdata($post);
          $article_id = $post->ID;
          include ralfdocs_get_template('loop/loop-item.php');
        }
        wp_reset_postdata();
        ralfdocs_pagination($impacts);
      }
      else{
        include ralfdocs_get_template('loop/no-results.php');
      }
    ?>

  </div>
</div>