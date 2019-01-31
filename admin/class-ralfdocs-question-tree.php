<?php
/**
 * Creates the post types for the ralfdocs question tree
 */
if(!defined('ABSPATH')){ exit; }

if(!class_exists('RALFDOCS_Question_Tree')){
  class RALFDOCS_Question_Tree{
    public function __construct(){
      add_action('init', array($this, 'create_post_type'));
    }
  }

  public function create_post_type(){
    $questions_labels = array(
      'name' => esc_html_x('Questions', 'post type general name', 'ralfdocs'),
      'singular_name' => esc_html_x('Question', 'post type singular name', 'ralfdocs'),
      'menu_name' => esc_html_x('Question Tree', 'admin menu name', 'ralfdocs'),
      'name_admin_bar' => esc_html__('Questions', 'ralfdocs'),
      'add_new' => esc_html__('Add New', 'ralfdocs'),
      'add_new_item' => esc_html__('Add New Question', 'ralfdocs'),
      'new_item' => esc_html__('New Question', 'ralfdocs'),
      'edit_item' => esc_html__('Edit Question', 'ralfdocs'),
      'view_item' => esc_html__('View Question', 'ralfdocs'),
      'view_items' => esc_html__('View Questions', 'ralfdocs'),
      'all_items' => esc_html__('All Questions', 'ralfdocs'),
      'search_items' => esc_html__('Search Questions', 'ralfdocs'),
      'not_found' => esc_html__('No Questions Found', 'ralfdocs'),
      'not_found_in_trash' => esc_html__('No Questions Found In Trash', 'ralfdocs')
    );
    $questions_args = array(
      'labels' => $questions_labels,
      'description' => esc_html__('RALF Question Tree Questions', 'ralfdocs'),
      'public' => true,
      'menu_position' => 9,
      'menu_icon' => 'dashicons-networking',
      'supports' => array(
        'title',
        'author',
        'revisions',
        'custom_fields'
      )
    );
  }
}