<?php
/**
 * Template for displaying the Impacts and Activities search results
 * Impacts / Activities tab is active and Resources tab is linked.
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/loop/impacts-activities-search-results.php
 */
if(!defined('ABSPATH')){ exit; }

include ralfdocs_get_template('loop/impacts-activities-tab.php');
?>

<div class="tab-content">
  <div id="impacts-activities">

    <?php 
      if(!empty($impacts_activities->posts)){
        foreach($impacts_activities->posts as $post){
          setup_postdata($post);
          $article_id = $post->ID;
          include ralfdocs_get_template('loop/loop-item.php');
        }
        wp_reset_postdata();
        ralfdocs_pagination($impacts_activities);
      }
      else{
        include ralfdocs_get_template('loop/no-results.php');
      }
    ?>

  </div>
</div>