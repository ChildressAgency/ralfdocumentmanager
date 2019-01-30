<?php
/**
 * Template for displaying resource type results loop
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/loop/resource-type-loop.php
 */
if(!defined('ABSPATH')){ exit; }

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