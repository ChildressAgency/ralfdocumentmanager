<?php
/**
 * Template for displaying the Resources search results
 * Resources tab is active and Impacts / Activities tab is linked
 */
if(!defined('ABSPATH')){ exit; }

include ralfdocs_get_template('loop/resources-tab.php');
?>

<div class="tab-content">
  <div id="resources">

    <?php
      $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

      if(!empty($resources->posts)){
        foreach($resources->posts as $post){
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