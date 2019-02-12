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
      //echo facetwp_display('template', 'search_impacts_resources_template');

      echo '<input type="hidden" id="archive-type" value="search" />';
      if(is_array($tax_terms)){
        $tax_terms = implode(',', $tax_terms);
      }
      echo '<input type="hidden" id="tax-terms" value="' . $tax_terms . '" />';
      echo '<input type="hidden" id="ajax-page" value="' . $paged . '" />';
      echo '<input type="hidden" id="ajax-post-type" value="impacts" />';
    
      if(!empty($impacts_activities->posts)){
        foreach($impacts_activities->posts as $post){
          setup_postdata($post);
          $article_id = $post->ID;
          include ralfdocs_get_template('loop/loop-item.php');
        }
        wp_reset_postdata();
        ralfdocs_pagination($impacts_activities, $impacts_paged, $ajax_location);
      }
      else{
        include ralfdocs_get_template('loop/no-results.php');
      }
    ?>

  </div>
</div>