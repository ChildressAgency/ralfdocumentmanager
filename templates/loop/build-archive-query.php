<?php
/**
 * Builds the queries for Sectors page, Resource types archive page and Search
 * both initial setup and ajax calls
 * 
 * Called from do_action ralfdocs_build_archive_query
 * 
 * @param $archive_type
 * @param $tax_terms = sector terms
 * @param $ajax_page
 * @param $ajax_location
 * @param $ajax_post_type
 * @param $resource_terms
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
    if($ajax_post_type != '' && $ajax_post_type == 'resources'){
      $resources_paged = $paged;
      $impacts_paged = 1;
    }
    else{
      $resources_paged = 1;
      $impacts_paged = $paged;
    }

    $terms_to_include = ralfdocs_get_terms_to_include($tax_terms, 'sectors');

    $impacts = new WP_Query(array(
      'post_type' => 'impacts',
      'paged' => $impacts_paged,
      'tax_query' => array(
        array(
          'taxonomy' => 'sectors',
          'field' => 'term_id',
          'terms' => $terms_to_include,
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
          'terms' => $terms_to_include
        )
      )
    ));

    if($ajax_post_type != '' && $ajax_post_type == 'resources'){
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

    $sectors_to_include = ralfdocs_get_terms_to_include($tax_terms, 'sectors');
    $resource_types_to_include = ralfdocs_get_terms_to_include($resource_terms, 'resource_types');

    $resources = new WP_Query(array(
      'post_type' => 'resources',
      'paged' => $paged,
      'tax_query' => array(
        'relation' => 'AND',
        array(
          'taxonomy' => 'resource_types',
          'field' => 'term_id',
          'terms' => $resource_types_to_include
        ),
        array(
          'taxonomy' => 'sectors',
          'field' => 'term_id',
          'terms' => $sectors_to_include
        )
      )
    ));

    echo '<input type="hidden" id="archive-type" value="resource_types" />';
    if(is_array($tax_terms)){
      $tax_terms = implode(',', $tax_terms);
    }
    if(is_array($resource_terms)){
      $resource_terms = implode(',', $resource_terms);
    }
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
      ralfdocs_pagination($resources, $paged, $ajax_location);
    }
    else{
      include ralfdocs_get_template('loop/no-results.php');
    }
    break;

  case 'search':
    if($ajax_page != ''){
      $paged = $ajax_page;
    }
    elseif(get_query_var('paged')){
      $paged = get_query_var('paged');
    }
    else{
      $paged = 1;
    }

    //this will make sure the count on the tabs are correct despite pagination
    if($ajax_post_type != '' && $ajax_post_type == 'resources'){
      $resources_paged = $paged;
      $impacts_paged = 1;
    }
    else{
      $resources_paged = 1;
      $impacts_paged = $paged;
    }

    $terms_to_include = ralfdocs_get_terms_to_include($tax_terms, 'sectors');

    $impacts_activities = new SWP_Query(array(
      'post_type' => array('impacts', 'activities'),
      's' => $searched_word,
      'engine' => 'default',
      'page' => $impacts_paged,
      'fields' => 'all',
      'tax_query' => array(
        array(
          'taxonomy' => 'sectors',
          'field' => 'term_id',
          'terms' => $terms_to_include
        )
      )
    ));

    $resources = new SWP_Query(array(
      'post_type' => 'resources',
      's' => $searched_word,
      'engine' => 'default',
      'page' => $resources_paged,
      'fields' => 'all',
      'tax_query' => array(
        array(
          'taxonomy' => 'sectors',
          'field' => 'term_id',
          'terms' => $terms_to_include
        )
      )
    ));

    if($ajax_post_type != '' && $ajax_post_type == 'resources'){
      include ralfdocs_get_template('loop/resources-search-results.php');
    }
    else{
      if(empty($impacts_activities->posts) && !empty($resources->posts)){
        include ralfdocs_get_template('loop/resources-search-results.php');
      }
      else{
        include ralfdocs_get_template('loop/impacts-activities-search-results.php');
      }
    }

    break;
}

function ralfdocs_get_terms_to_include($tax_terms, $terms_tax){
  $terms_to_include = array();
  $terms_to_exclude = array();

  if($tax_terms == ''){
    $terms_to_include = get_terms(array(
      'taxonomy' => $terms_tax,
      'fields' => 'ids'
    ));
  }
  else{
    if(!is_array($tax_terms)){
      $tax_terms = explode(',', $tax_terms);
    }

    if(is_array($tax_terms)){
      foreach($tax_terms as $tax_term_id){
        $term = get_term($tax_term_id, $terms_tax);
        if($term->parent > 0){
          $terms_to_exclude[] = $term->parent;
        }
      }

      if(!empty($terms_to_exclude)){
        $terms_to_include = array_merge(array_diff($tax_terms, $terms_to_exclude));
      }
      else{
        $terms_to_include = $tax_terms;
      }
    }
    else{
      $terms_to_include = $tax_terms;
    }
  }

  return $terms_to_include;
}