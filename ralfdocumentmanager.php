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

  public function __construct(){
    $this->load_dependencies();
    add_action('init', array($this, 'init'));
    add_action('widgets_init', array($this, 'init_widgets'));

    $this->setup_acf_reciprocal_relationship();

    add_filter('pre_get_posts', array($this, 'ralfdocs_search_filter'));
  }

  public function load_dependencies(){
    require_once RALFDOCS_PLUGIN_DIR . '/vendors/advanced-custom-fields-pro/acf.php';
      add_filter('acf/settings/path', array($this, 'acf_settings_path'));
      add_filter('acf/settings/dir', array($this, 'acf_settings_dir'));

    require_once RALFDOCS_PLUGIN_DIR . '/admin/class-ralfdocs-post-types.php';
    require_once RALFDOCS_PLUGIN_DIR . '/admin/class-ralfdocs-sectors-widget.php';
    require_once RALFDOCS_PLUGIN_DIR . '/admin/class-ralfdocs-search-history-widget.php';
    require_once RALFDOCS_PLUGIN_DIR . '/admin/class-view-report-widget.php';
  }

  public function init(){
    $this->rewrite_report_url();

    $ralfdocs_post_types = new Ralfdocs_Post_Types();

    $this->load_textdomain();
  }

  public function load_textdomain(){
    load_plugin_textdomain('ralfdocs', false, basename(RALFDOCS_PLUGIN_DIR) . '/languages');
  }

  public function rewrite_report_url(){
    add_rewrite_tag('%report_id%', '([^&]+)');
    add_rewrite_rule('^view-report/([^.]*)$', 'index.php?pagename=view-report&report_id=$matches[1]', 'top');
  }

  public function ralfdocs_search_filter($query){
    if($query->is_search && !is_admin()){
      $query->set('post_type', array('activities', 'impacts', 'resources'));
    }

    return $query;
  }

  public function acf_settings_path($path){
    $path = RALFDOCS_PLUGIN_URL . '/vendors/advanced-custom-fields-pro';

    return $path;
  }

  public function acf_settings_dir($dir){
    $dir = RALFDOCS_PLUGIN_DIR . '/vendors/advanced-custom-fields-pro';

    return $dir;
  }

  public function init_widgets(){
    register_sidebar(array(
      'name' => __('RALF Documents Sidebar', 'ralfdocs'),
      'id' => 'ralfdocs-sidebar',
      'description' => __('Sidebar for the RALF Documents results pages.', 'ralfdocs'),
      'before_widget' => '<div class="sidebar-section">',
      'after_widget' => '</div>',
      'before_title' => '<h4>',
      'after_title' => '</h4>'
    ));

    register_widget('Ralfdocs_Sectors_Widget');
    register_widget('Ralfdocs_Search_History_Widget');
    register_widget('Ralfdocs_View_Report_Widget');
  }
}

new Ralf_Docs;