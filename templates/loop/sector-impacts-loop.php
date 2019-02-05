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
  <div id="impacts" class="facetwp-template">

    <?php
      echo facetwp_display('template', 'impacts_sectors_template');
      /*if(!empty($impacts->posts)){
        foreach($impacts->posts as $post){
          setup_postdata($post);
          $article_id = $post->ID;
          include ralfdocs_get_template('loop/loop-item.php');
        }
        wp_reset_postdata();
        ralfdocs_pagination($impacts);
      }*/
/*
      if($impacts->have_posts()){
        while($impacts->have_posts()){
          $impacts->the_post();
          $article_id = get_the_ID();
          include ralfdocs_get_template('loop/loop-item.php');
          //facetwp_display('template', 'impacts_sectors_template');
        }
        wp_reset_postdata();
        ralfdocs_pagination($impacts);
      }
      else{
        include ralfdocs_get_template('loop/no-results.php');
      }*/
    ?>

  </div>
</div>