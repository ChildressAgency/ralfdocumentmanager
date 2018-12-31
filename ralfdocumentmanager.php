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

if(!class_exists('Ralf_Docs')){
class Ralf_Docs{

  public function __construct(){
    $this->load_dependencies();
    add_action('plugins_loaded', array($this, 'background_admin_tasks'));
    add_action('init', array($this, 'init'));
    add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    add_action('acf/init', array($this, 'admin_settings_acf_options_page'));
    add_action('widgets_init', array($this, 'init_widgets'));

    add_filter('pre_get_posts', array($this, 'ralfdocs_search_filter'));
  }

  public function load_dependencies(){
    require_once RALFDOCS_PLUGIN_DIR . '/vendors/advanced-custom-fields-pro/acf.php';
      add_filter('acf/settings/path', array($this, 'acf_settings_path'));
      add_filter('acf/settings/dir', array($this, 'acf_settings_dir'));

    require_once RALFDOCS_PLUGIN_DIR . '/admin/class-ralfdocs-post-types.php';
    require_once RALFDOCS_PLUGIN_DIR . '/admin/widgets/class-ralfdocs-sectors-widget.php';
    require_once RALFDOCS_PLUGIN_DIR . '/admin/widgets/class-ralfdocs-search-history-widget.php';
    require_once RALFDOCS_PLUGIN_DIR . '/admin/widgets/class-ralfdocs-view-report-widget.php';
    require_once RALFDOCS_PLUGIN_DIR . '/admin/class-ralfdocs-background-admin-tasks.php';
  }

  public function init(){
    $this->rewrite_report_url();
    $this->load_textdomain();

    $ralfdocs_post_types = new RALFDOCS_Post_Types();

  }

  public function load_textdomain(){
    load_plugin_textdomain('ralfdocs', false, basename(RALFDOCS_PLUGIN_DIR) . '/languages');
  }

  public function enqueue_scripts(){
    //wp_enqueue_style('ralfdocs-css', RALFDOCS_PLUGIN_URL . 'css/ralfdocs-style.css');
    wp_register_script(
      'js-cookie', 
      RALFDOCS_PLUGIN_URL . 'js/js-cookie.js',
      array('jquery'),
      '',
      true
    );

    wp_register_script(
      'ralfdocs-scripts',
      RALFDOCS_PLUGIN_URL . 'js/ralfdocs-scripts.js',
      array('jquery'),
      '',
      true
    );

    wp_enqueue_script('js-cookie');
    wp_enqueue_script('ralfdocs-scripts');

    wp_localize_script('ralfdocs-scripts', 'ralfdocs_settings', array(
      'ralfdocs_ajaxurl' => admin_url('admin-ajax.php'),
      'send_label' => __('Email Report', 'ralfdocs'),
      'error' => __('Sorry, something went wrong. Please try again.', 'ralfdocs'),
      'save_to_report_label' => __('Save To Report', 'ralfdocs'),
      'remove_from_report_label' => __('Remove From Report', 'ralfdocs'),
      'added_to_report_label' => __('Added to report!', 'ralfdocs'),
      'removed_from_report_label' => __('Removed from report', 'ralfdocs'),
      'valid_email_address_error' => __('Please enter only valid email addresses.', 'ralfdocs')
    ));    
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

  public function admin_settings_acf_options_page(){
    acf_add_options_page(array(
      'page_title' => __('RALF Documents Settings', 'ralfdocs'),
      'menu_title' => __('RALF Documents Settings', 'ralfdocs'),
      'menu_slug' => 'ralfdocs-settings',
      'capability' => 'edit_posts',
      'redirect' => false
    ));
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

    register_widget('RALFDOCS_Sectors_Widget');
    register_widget('RALFDOCS_Search_History_Widget');
    register_widget('RALFDOCS_View_Report_Widget');
  }
  public function background_admin_tasks(){
    $background_admin_tasks = new RALFDOCS_Background_Admin_Tasks();
  }
} // end Ralf_Docs class
}
new Ralf_Docs;