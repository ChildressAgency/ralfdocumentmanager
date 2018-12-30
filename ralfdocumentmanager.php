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
    add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    add_action('widgets_init', array($this, 'init_widgets'));
    add_action('plugins_loaded', array($this, 'housekeeping_tasks'));
    add_action('acf/init', array($this, 'general_settings_acf_fields'));

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

  public function general_settings_acf_fields(){
    acf_add_options_page(array(
      'page_title' => __('General Settings', 'ralfdocs'),
      'menu_title' => __('General Settings', 'ralfdocs'),
      'menu_slug' => 'general-settings',
      'capability' => 'edit_posts',
      'redirect' => false
    ));

    acf_add_local_field_group(array(
      'key' => 'group_5bcde52f3147b',
      'title' => __('Report History Settings', 'ralfdocs'),
      'fields' => array(
        array(
          'key' => 'field_5bcde537c6286',
          'label' => __('How long to store reports?', 'ralfdocs'),
          'name' => 'how_long_to_store_reports',
          'type' => 'number',
          'instructions' => __('Enter number of days to keep reports before they are deleted.', 'ralfdocs'),
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '25',
            'class' => '',
            'id' => '',
          ),
          'default_value' => '',
          'placeholder' => '',
          'prepend' => '',
          'append' => 'days',
          'min' => '',
          'max' => '',
          'step' => 1,
        ),
      ),
      'location' => array(
        array(
          array(
            'param' => 'options_page',
            'operator' => '==',
            'value' => 'general-settings',
          ),
        ),
      ),
      'menu_order' => 0,
      'position' => 'normal',
      'style' => 'default',
      'label_placement' => 'top',
      'instruction_placement' => 'label',
      'hide_on_screen' => '',
      'active' => 1,
      'description' => '',
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

    register_widget('Ralfdocs_Sectors_Widget');
    register_widget('Ralfdocs_Search_History_Widget');
    register_widget('Ralfdocs_View_Report_Widget');
  }
  public function housekeeping_tasks(){
    $this->delete_old_reports();
    $this->email_admin_reports();
  }

  public function delete_old_reports(){
    $how_long_to_store_reports = get_field('how_long_to_store_reports', 'option');

    global $wpdb;
    
    $wpdb->query($wpdb->prepare("
      DELETE from emailed_reports
      WHERE datediff(now(), email_date) > %d", $how_long_to_store_reports));    
  }

  public function email_admin_reports(){
    if(isset($_GET['email_admin_reports'])){
      require_once RALFDOCS_PLUGIN_DIR . '/admin/email_admin_reports.php';
    }
  }
} // end Ralf_Docs class

new Ralf_Docs;