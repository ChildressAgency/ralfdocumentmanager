<?php
/**
 * Template for displaying the Resources search results
 * Resources tab is active and Impacts / Activities tab is linked
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/loop/resources-search-results.php
 */
if(!defined('ABSPATH')){ exit; }

include ralfdocs_get_template('loop/resources-tab.php');
?>

<div class="tab-content">
  <div id="resources">

    <?php
      //echo facetwp_display('template', 'search_resources_template');

      echo '<input type="hidden" id="archive-type" value="search" />';
      if(is_array($tax_terms)){
        $tax_terms = implode(',', $tax_terms);
      }
      echo '<input type="hidden" id="tax-terms" value="' . $tax_terms . '" />';
      echo '<input type="hidden" id="ajax-page" value="' . $paged . '" />';
      echo '<input type="hidden" id="ajax-post-type" value="resources" />';
    
      if(!empty($resources->posts)){
        foreach($resources->posts as $post){
          setup_postdata($post);
          $article_id = $post->ID;
          include ralfdocs_get_template('loop/loop-item.php');
        }
        wp_reset_postdata();
        ralfdocs_pagination($resources);
      }
      else{
        include ralfdocs_get_template('loop/no-results.php');
      }
    ?>

  </div>
</div>