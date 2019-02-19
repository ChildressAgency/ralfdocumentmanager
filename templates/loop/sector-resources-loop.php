<?php
/**
 * Template for displaying the sectors taxonomy archive page
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/loop/sector-resources-loop.php
 */
if(!defined('ABSPATH')){ exit; }

include ralfdocs_get_template('loop/resources-tab.php');
?>

<div class="tab-content">
  <div id="resources" class="facetwp-template">

    <?php
      //echo facetwp_display('template', 'resources_sectors_template');

      if(is_array($tax_terms)){
        $tax_terms = implode(',', $tax_terms);
      }
      if(is_array($resource_terms)){
        $resource_terms = implode(',', $resource_terms);
      }

      echo '<input type="hidden" id="archive-type" value="sectors" />';
      echo '<input type="hidden" id="tax-terms" value="' . $tax_terms . '" />';
      echo '<input type="hidden" id="resource-terms" value="' . $resource_terms . '" />';
      echo '<input type="hidden" id="ajax-page" value="' . $paged . '" />';
      echo '<input type="hidden" id="ajax-post-type" value="resources" />';
    
      if($resources->have_posts()){
        while($resources->have_posts()){
          $resources->the_post();
          $article_id = get_the_ID();
          include ralfdocs_get_template('loop/loop-item.php');
        }
        wp_reset_postdata();
        ralfdocs_pagination($resources, $resources_paged, $ajax_location);
      }
      else{
        include ralfdocs_get_template('loop/no-results.php');
      }
    ?>

  </div>
</div>