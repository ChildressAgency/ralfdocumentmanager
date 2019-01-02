<?php
if(!defined('ABSPATH')){ exit; }

function ralfdocs_get_template($template_name){
  return RALFDOCS_Template_Functions::get_template($template_name);
}

class RALFDOCS_Template_Functions{
  public static function get_template($template_name){
    $template_file = $this->locate_template($template_name);

    return $template_file;
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
    $template_path = '/ralfdocs_templates/';

    $template = locate_template(array(
      $template_name,
      $template_path . $template_name
    ), TRUE);

    if(empty($template)){
      $template = plugin_dir_path(dirname(__FILE__)) . '/templates/' . $template_name;
    }

    return $template;
  }
}