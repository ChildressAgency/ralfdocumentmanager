<?php
/*
  Plugin Name: RALF Document Manager
  Description: RALF Document Manager
  Author: The Childress Agency
  Author URI: https://childressagency.com
  Version: 2.0
  Text Domain: ralfdocs
*/

if(!defined('ABSPATH')){ exit; }

define('RALFDOCS_PLUGIN_DIR', dirname(__FILE__));
define('RALFDOCS_PLUGIN_URL', plugin_dir_url(__FILE__));

class Ralf_Docs{

  /*
   * acf field keys for reciprocal relationships of related activities & impacts
   * $related_impacts is the acf relationship field that shows on the activities cpt
   * $related_activities is the acf relationship field that shows on the impacts cpt
  */
  private $related_impacts = 'field_5a980a2e5519d';
  private $related_activities = 'field_5a980a8d64d2a';

  public function __construct(){
    $this->load_dependencies();
    add_action('init', array($this, 'init'));

    $this->setup_acf_reciprocal_relationship();
  }

  public function load_dependencies(){
    require_once RALFDOCS_PLUGIN_DIR . '/vendors/advanced-custom-fields-pro/acf.php';
      add_filter('acf/settings/path', array($this, 'acf_settings_path'));
      add_filter('acf/settings/dir', array($this, 'acf_settings_dir'));

    require_once RALFDOCS_PLUGIN_DIR . '/admin/class-activities-post-type.php';
    require_once RALFDOCS_PLUGIN_DIR . '/admin/class-impacts-post-type.php';
    require_once RALFDOCS_PLUGIN_DIR . '/admin/class-resources-post-type.php';
  }

  public function init(){
    $cpt_activities = new Activities_Post_Type();
    $cpt_impacts = new Impacts_Post_Type();
    $cpt_resources = new Resources_Post_Type();

    $this->load_textdomain();
  }

  public function load_textdomain(){
    load_plugin_textdomain('ralfdocs', false, basename(RALFDOCS_PLUGIN_DIR) . '/languages');
  }

  public function acf_settings_path($path){
    $path = RALFDOCS_PLUGIN_URL . '/vendors/advanced-custom-fields-pro';

    return $path;
  }

  public function acf_settings_dir($dir){
    $dir = RALFDOCS_PLUGIN_DIR . '/vendors/advanced-custom-fields-pro';

    return $dir;
  }

  public function setup_acf_reciprocal_relationship(){
    require_once RALFDOCS_PLUGIN_DIR . '/functions/acf-reciprocal-relationship.php';
    // add the filter for your relationship field
    add_filter('acf/update_value/key=' . $this->related_impacts, array($this, 'acf_reciprocal_relationship'), 10, 3);
    // if you are using 2 relationship fields on different post types
    // add second filter for that fields as well
    add_filter('acf/update_value/key=' . $this->related_activities, array($this, 'acf_reciprocal_relationship'), 10, 3);
  }
}

new Ralf_Docs;