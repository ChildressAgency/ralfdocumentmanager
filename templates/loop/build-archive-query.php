<?php
/**
 * 
 */

if(!defined('ABSPATH')){ exit; }

switch($archive_type){
  case 'sectors':
    if($ajax_page != ''){
      $paged = $ajax_page;
    }
    elseif(get_query_var('paged')){
      $paged = get_query_var('paged');
    }
    else{
      $paged = 1;
    }
    //$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

    // this will make sure the count on the tabs are correct despite pagination
    if(isset($_GET['type']) && $_GET['type'] == 'resources'){
      $resources_paged = $paged;
      $impacts_paged = 1;
    }
    else{
      $resources_paged = 1;
      $impacts_paged = $paged;
    }

    $impacts = new WP_Query(array(
      'post_type' => 'impacts',
      'paged' => $impacts_paged,
      'tax_query' => array(
        array(
          'taxonomy' => 'sectors',
          'field' => 'term_id',
          'terms' => $tax_terms
        )
      )
    ));

    $resources = new WP_Query(array(
      'post_type' => 'resources',
      'paged' => $resources_paged,
      'tax_query' => array(
        array(
          'taxonomy' => 'sectors',
          'field' => 'term_id',
          'terms' => $tax_terms
        )
      )
    ));

    if(isset($_GET['type']) && $_GET['type'] == 'resources'){
      //user clicked the resources tab
      include ralfdocs_get_template('loop/sector-resources-loop.php');
    }
    else{
      /**
       * initial results - resources tab not clicked
       * 
       * if $impacts has no results then default to the resources tab,
       * unless its also empty - then just display the default tab.
       */
      if(empty($impacts->posts) && !empty($resources->posts)){
        include ralfdocs_get_template('loop/sector-resources-loop.php');
      }
      else{
        include ralfdocs_get_template('loop/sector-impacts-loop.php');
      }
    }

    break;
  case 'resource_types':
    if($ajax_page != ''){
      $paged = $ajax_page;
    }
    elseif(get_query_var('paged')){
      $paged = get_query_var('paged');
    }
    else{
      $paged = 1;
    }

    $resources = new WP_Query(array(
      'post_type' => 'resources',
      'paged' => $paged,
      'tax_query' => array(
        array(
          'taxonomy' => 'resource_types',
          'field' => 'term_id',
          'terms' => $tax_terms
        )
      )
    ));

    echo '<input type="hidden" id="archive-type" value="resource_types" />';
    if(is_array($tax_terms)){
      $tax_terms = implode(',', $tax_terms);
    }
    echo '<input type="hidden" id="tax-terms" value="' . $tax_terms . '" />';
    echo '<input type="hidden" id="ajax-page" value="' . $paged . '" />';

    if($resources->have_posts()){
      while($resources->have_posts()){
        $resources->the_post();
        $article_id = get_the_ID();
        include ralfdocs_get_template('loop/loop-item.php');
      }
      wp_reset_postdata();
      ralfdocs_pagination($resources, $paged, $ajax_location);
    }
    else{
      include ralfdocs_get_template('loop/no-results.php');
    }
    break;
  case 'search':

    break;
}