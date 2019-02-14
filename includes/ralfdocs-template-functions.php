<?php
/**
 * Public and common template functions
 */
if(!defined('ABSPATH')){ exit; }

function ralfdocs_get_template($template_name){
  //return RALFDOCS_Template_Functions::get_plugin_template($template_name);
  return (new RALFDOCS_Template_Functions)->find_template($template_name);
}

function ralfdocs_convert_to_int_array($article_ids_string){
  return (new RALFDOCS_Template_Functions)->convert_to_int_array($article_ids_string);
}

function ralfdocs_get_field_excerpt($field_name){
  return (new RALFDOCS_Template_Functions)->get_field_excerpt($field_name);
}

function ralfdocs_get_article_meta($article_id){
  //$template_function = new RALFDOCS_Template_Functions();
  return (new RALFDOCS_Template_Functions)->get_article_meta($article_id);
}

function ralfdocs_get_impacts_by_sector($impact_ids){
  return (new RALFDOCS_Template_Functions)->get_impacts_by_sector($impact_ids);
}

function ralfdocs_get_related_activities($article_id, $article_type = 'impacts'){
  return (new RALFDOCS_Template_Functions)->get_related_activities($article_id, $article_type);
}

function ralfdocs_get_related_impacts($resource_id){
  return (new RALFDOCS_Template_Functions)->get_related_impacts($resource_id);
}

function ralfdocs_pagination($the_query = ''){
  return (new RALFDOCS_Template_Functions)->pagination($the_query);
}

if(!class_exists('RALFDOCS_Template_Functions')){
class RALFDOCS_Template_Functions{
  public function __construct(){

  }

  public function get_article_meta($article_id){
    $article_meta = array();

    $article_type = get_post_type_object(get_post_type($article_id));
    $article_meta['article_type'] = array(
      'name' => $article_type->labels->singular_name,
      'color' => get_field($article_type->name . '_color', 'option')
    );

    $sectors = get_the_terms($article_id, 'sectors');
    if($sectors){
      // sort sectors by parent
      foreach($sectors as $key => $row){
        $sector_parent[$key] = $row->parent;
      }
      array_multisort($sector_parent, SORT_ASC, $sectors);

      $all_sectors = array();
      foreach($sectors as $sector){
        if($sector->parent > 0){
          $all_sectors[] = $sector->parent;
        }
        $all_sectors[] = $sector->term_id;
      }

      $article_sectors = array_unique($all_sectors);
      foreach($article_sectors as $article_sector){
        $sector = get_term_by('id', $article_sector, 'sectors');
        $article_meta['sectors'][] = array(
          'name' => $sector->name,
          'color' => get_field('sector_color', 'sectors_' . $sector->term_id),
          'link' => get_term_link($sector->term_id, 'sectors')
        );
      }
    }

    if($article_type->name == 'impacts' || $article_type->name == 'resources'){
      $related_activities = $this->get_related_activities($article_id, $article_type->name);
      $article_meta['related_activities_count'] = $related_activities->post_count;
    }

    if($article_type->name == 'resources'){
      $related_impacts = $this->get_related_impacts($article_id);
      $article_meta['related_impacts_count'] = $related_impacts->post_count;

      $resource_types = get_the_terms($article_id, 'resource_types');
      foreach($resource_types as $key => $row){
        $resource_types_parent[$key] = $row->parent;
      }
      array_multisort($resource_types_parent, SORT_ASC, $resource_types);

      foreach($resource_types as $resource_type){
        $article_meta['resource_types'][] = array(
          'name' => $resource_type->name,
          'color' => get_field('resource_type_color', 'resource_types_' . $resource_type->term_id),
          'link' => get_term_link($resource_type->term_id, 'resource_types')
        );
      }
    }

    return $article_meta;
  }

  public function get_impacts_by_sector($impact_ids){
    global $wpdb;
    $impact_ids_placeholder = implode(', ', array_fill(0, count($impact_ids), '%d'));

    //$impact_ids_for_query = $this->convert_to_int_array(implode(',', $impact_ids));

    $impacts_with_sector = $wpdb->get_results($wpdb->prepare("
      SELECT $wpdb->posts.ID AS impact_id, $wpdb->posts.post_title AS impact_title, $wpdb->posts.guid AS impact_link, $wpdb->terms.name AS sector, $wpdb->terms.term_id as sector_id, $wpdb->posts.post_content AS impact_description
      FROM $wpdb->posts
        JOIN $wpdb->term_relationships ON $wpdb->posts.ID = $wpdb->term_relationships.object_id
        JOIN $wpdb->terms ON $wpdb->term_relationships.term_taxonomy_id = $wpdb->terms.term_id
        JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
      WHERE $wpdb->term_taxonomy.taxonomy = 'sectors'
        AND $wpdb->posts.ID IN($impact_ids_placeholder)
        AND post_type = 'impacts'", $impact_ids));

    $impacts_by_sector = array();
    foreach($impacts_with_sector as $sector){
      $impacts_by_sector[$sector->sector]['sector_name'] = $sector->sector;
      $impacts_by_sector[$sector->sector]['sector_id'] = $sector->sector_id;
      $impacts_by_sector[$sector->sector]['impacts'][] = $sector;
    }

    ksort($impacts_by_sector);
    return $impacts_by_sector;
  }

  //$article_type can be impacts (default) or resources
  public function get_related_activities($article_id, $article_type = 'impacts'){
    $meta_key = 'related_' . $article_type;
    $activities = new WP_Query(array(
      'post_type' => 'activities',
      'posts_per_page' => -1,
      'post_status' => 'publish',
      'meta_query' => array(
        array(
          'key' => $meta_key,
          'value' => '"' . $article_id . '"',
          'compare' => 'LIKE'
        )
      )
    ));

    return $activities;
  }

  public function convert_to_int_array($article_ids_string){
    $article_ids_list = explode(',', $article_ids_string);

    $article_ids = array_map(
      function($value){ return (int)$value; },
      $article_ids_list
    );

    return $article_ids;
  }

  public function get_related_impacts($resource_id){
    // only used for resources cpt
    $impacts = new WP_Query(array(
      'post_type' => 'impacts',
      'posts_per_page' => -1,
      'post_status' => 'publish',
      'meta_query' => array(
        array(
          'key' => 'related_resources',
          'value' => '"' . $resource_id . '"',
          'compare' => 'LIKE'
        )
      )
    ));

    return $impacts;
  }

  public function pagination($the_query){
    //https://premium.wpmudev.org/blog/load-posts-ajax/
    if($the_query == ''){
      global $wp_query;
      $the_query = $wp_query;
    }

    $paged = get_query_var('paged') ? get_query_var('paged') : 1;

    //global $wp_query;

    if($the_query->max_num_pages <= 1){ return; }

    $big = 999999999;
    $pages = paginate_links(array(
              'base' => str_replace($big, '%#%', html_entity_decode(get_pagenum_link($big))),
              //'format' => '?paged=%#%',
              //'current' => max(1, get_query_var('paged')),
              'current' => max(1, $paged),
              'total' => $the_query->max_num_pages,
              'type' => 'array'
    ));

    if(is_array($pages)){
      //$paged = (get_query_var('paged') == 0) ? 1 : get_query_var('paged');

      echo '<nav aria-label="Page navigation" class="pagination-nav"><ul class="pagination">';

      foreach($pages as $page){
        echo '<li>' . $page . '</li>';
      }

      echo '</ul></nav>';
    }
  }

  public function back_button(){
    ob_start();
    echo '<div class="go-back">';
    echo '<a href="javascript:history.back(-1);">' . esc_html__('BACK', 'ralfdocs') . '</a>';
    echo '</div>';

    return ob_get_clean();
  }

  public function get_field_excerpt($field_name){
    global $post;
    $text = get_field($field_name);

    if($text != ''){
      $text = strip_shortcodes($text);
      $text = apply_filters('the_content', $text);
      $text = str_replace(']]&gt;', ']]&gt;', $text);
      $excerpt_length = 20;
      $excerpt_more = apply_filters('excerpt_more', ' ', '[...]');
      $text = wp_trim_words($text, $excerpt_length, $excerpt_more);
    }
    return apply_filters('the_excerpt', $text);
  }

  public function load_template($template){
    //$template_name = basename($template);
    $template_name = '';

    if(is_singular('activities')){
      $template_name = 'single-activities.php';
    }
    elseif(is_singular('impacts')){
      $template_name = 'single-impacts.php';
    }
    elseif(is_singular('resources')){
      $template_name = 'single-resources.php';
    }
    elseif(is_search()){
      $template_name = 'search.php';
    }
    elseif(is_tax('resource_types')){
      $template_name = 'taxonomy-resource_types.php';
    }
    elseif(is_tax('sectors')){
      $template_name = 'taxonomy-sectors.php';
    }
    elseif(is_page('quick-select-results')){
      $template_name = 'page-quick-select-results.php';
    }
    elseif(is_page('view-report')){
      $template_name = 'page-view-report.php';
    }
    elseif(is_page('question-tree')){
      $template_name = 'page-question-tree.php';
    }
    elseif(is_singular('questions')){
      $template_name = 'single-questions.php';
    }

    if($template_name !== ''){
      return $this->find_template($template_name);
    }

    return $template;
  }

  public function find_template($template_name){
    $template_path = get_stylesheet_directory_uri() . '/ralfdocs-templates/';

    $template = locate_template(array(
      $template_path . $template_name,
      $template_name
    ), TRUE);

    if(!$template){
      //$template = plugin_dir_path(__FILE__) . '/templates/' . $template_name;
      $template = RALFDOCS_PLUGIN_DIR . '/templates/' . $template_name;
    }

    return $template;
  }

  public function view_report_loop(){
    include ralfdocs_get_template('loop/view-report-loop.php');
  }

  public function article_meta($article_id){
    include ralfdocs_get_template('loop/article-meta.php');
  }

  public function related_impacts($impact_ids){
    include ralfdocs_get_template('related/related-impacts.php');
  }

  public function related_resources($article_id){
    include ralfdocs_get_template('related/related-resources.php');
  }

  public function related_activities($article_id, $article_type){
    include ralfdocs_get_template('related/related-activities.php');
  }
}
}