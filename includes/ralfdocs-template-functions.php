<?php
if(!defined('ABSPATH')){ exit; }

function ralfdocs_get_template($template_name){
  return RALFDOCS_Template_Functions::get_template($template_name);
}

function ralfdocs_convert_to_int_array($article_ids_string){
  return RALFDOCS_Template_Functions::convert_to_int_array($article_ids_string);
}

function ralfdocs_get_field_excerpt($field_name){
  return RALFDOCS_Template_Functions::get_field_excerpt($field_name);
}

function ralfdocs_get_article_meta($article_id){
  return RALFDOCS_Template_Functions::get_article_meta($article_id);
}

if(!class_exists('RALFDOCS_Template_Functions')){
class RALFDOCS_Template_Functions{
  public function __construct(){

  }

  public static function get_template($template_name){
    $template_file = $this->locate_template($template_name);

    return $template_file;
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
      $related_activities = usaidralf_get_related_activities($article_id, $article_type->name);
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
    $article_ids_list = explode(',', $_GET['article_ids']);

    $article_ids = array_map(
      function($value){ return (int)$value; },
      $article_ids_list
    );

    return $article_ids;
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

    $template_file = $this->locate_template($template_name);
    return $template_file;
  }

  public function locate_template($template_name){
    $template_path = '/templates/';

    $template = locate_template(array(
      $template_name,
      $template_path . $template_name
    ), TRUE);

    if(empty($template)){
      $template = plugin_dir_path(dirname(__FILE__)) . '/templates/' . $template_name;
    }

    return $template;
  }

  public function view_report_header(){
    include ralfdocs_get_template('ralfdocs-view-report-header.php');
  }

  public function view_report_loop(){
    include ralfdocs_get_template('ralfdocs-view-report-loop.php');
  }

  public function article_meta($article_id){
    include ralfdocs_get_template('ralfdocs-article-meta.php');
  }
}
}