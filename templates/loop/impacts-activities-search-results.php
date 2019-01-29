<?php
/**
 * Template for displaying the Impacts and Activities search results
 * Impacts / Activities tab is active and Resources tab is linked.
 */
if(!defined('ABSPATH')){ exit; }

include ralfdocs_get_template('loop/impacts-activities-tab.php');
?>

<div class="tab-content">
  <div id="impacts-activities">

    <?php 
      $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

      if(!empty($impacts_activities->posts)){
        foreach($impacts_activities->posts as $post){
          setup_postdata($post);
          include ralfdocs_get_template('loop/loop-item.php');
        }
        wp_reset_postdata();
        ralfdocs_pagination();
      }
      else{
        include ralfdocs_get_template('loop/no-results.php');
      }
    ?>

  </div>
</div>