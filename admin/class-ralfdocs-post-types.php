<?php
if(!defined('ABSPATH')){ exit; }

if(!class_exists('RALFDOCS_Post_Types')){
  class RALFDOCS_Post_Types{
    /*
    * acf field keys for reciprocal relationships of related activities & impacts
    * $related_impacts is the acf relationship field that shows on the activities cpt
    * $related_activities is the acf relationship field that shows on the impacts cpt
    */
    private $related_impacts;
    private $related_activities;

    public function __construct(){
      $this->related_impacts = 'field_5a980a2e5519d';
      $this->related_activities = 'field_5a980a8d64d2a';

      add_action('init', array($this, 'init'));
      add_action('acf/init', array($this, 'acf_init'));

      add_filter('acf/update_value/key=' . $this->get_related_impacts_field(), array($this, 'acf_reciprocal_relationship'), 10, 3);
      add_filter('acf/update_value/key=' . $this->get_related_activities_field(), array($this, 'acf_reciprocal_relationship'), 10, 3);
      add_filter('acf/fields/relationship/result/key=' . $this->get_related_impacts_field(), array($this, 'acf_related_impacts_relationship_display'), 10, 4);
    }

    public function init(){
      $this->create_post_types();
      $this->create_taxonomies();
    }

    public function acf_init(){
      $this->add_cpt_options();
      $this->add_acf_field_groups();
    }

    public function create_post_types(){
      $activity_labels = array(
        'name' => _x('Activities', 'post type general name', 'ralfdocs'),
        'singular_name' => esc_html_x('Activity', 'post type singular name', 'ralfdocs'),
        'menu_name' => esc_html_x('Activities', 'admin menu', 'ralfdocs'),
        'name_admin_bar' => esc_html_x('Activity', 'add new on admin bar', 'ralfdocs'),
        'add_new' => esc_html_x('Add New', 'activity', 'ralfdocs'),
        'add_new_item' => esc_html__('Add New Activity', 'ralfdocs'),
        'new_item' => esc_html__('New Activity', 'ralfdocs'),
        'edit_item' => esc_html__('Edit Activity', 'ralfdocs'),
        'view_item' => esc_html__('View Activity', 'ralfdocs'),
        'view_items' => esc_html__('View Activities', 'ralfdocs'),
        'all_items' => esc_html__('All Activities', 'ralfdocs'),
        'search_items' => esc_html__('Search Activities', 'ralfdocs'),
        'not_found' => esc_html__('No Activities Found', 'ralfdocs'),
        'not_found_in_trash' => esc_html__('No Activities Found in Trash', 'ralfdocs')
      );
      $activity_args = array(
        'labels' => $activity_labels,
        'description' => esc_html__('RALF Activities', 'ralfdocs'),
        'public' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-store',
        'supports' => array('title', 'author', 'revisions', 'editor')
      );
      register_post_type('activities', $activity_args);
    
      $impacts_labels = array(
        'name' => esc_html_x('Impacts', 'post type general name', 'ralfdocs'),
        'singular_name' => esc_html_x('Impact', 'post type singular name', 'ralfdocs'),
        'menu_name' => esc_html_x('Impacts', 'admin menu', 'ralfdocs'),
        'name_admin_bar' => esc_html_x('Impact', 'add new on admin bar', 'ralfdocs'),
        'add_new' => esc_html_x('Add New', 'impact', 'ralfdocs'),
        'add_new_item' => esc_html__('Add New Impact', 'ralfdocs'),
        'new_item' => esc_html__('New Impact', 'ralfdocs'),
        'edit_item' => esc_html__('Edit Impact', 'ralfdocs'),
        'view_item' => esc_html__('View Impact', 'ralfdocs'),
        'view_items' => esc_html__('View Impacts', 'ralfdocs'),
        'all_items' => esc_html__('All Impacts', 'ralfdocs'),
        'search_items' => esc_html__('Search Impacts', 'ralfdocs'),
        'not_found' => esc_html__('No Impacts Found', 'ralfdocs'),
        'not_found_in_trash' => esc_html__('No Impacts Found in Trash', 'ralfdocs')
      );
      $impacts_args = array(
        'labels' => $impacts_labels,
        'description' => esc_html__('RALF Impacts', 'ralfdocs'),
        'public' => true,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-lightbulb',
        'supports' => array('title', 'author', 'revisions', 'editor')
      );
      register_post_type('impacts', $impacts_args);
    
      $resources_labels = array(
        'name' => esc_html_x('Resources','post type general name', 'ralfdocs'),
        'singular_name' => esc_html_x('Resource', 'post type singular name', 'ralfdocs'),
        'menu_name' => esc_html_x('Resources', 'admin menu', 'ralfdocs'),
        'name_admin_bar' => esc_html_x('Resource', 'add new on admin bar', 'ralfdocs'),
        'add_new' => esc_html_x('Add New', 'resource', 'ralfdocs'),
        'add_new_item' => esc_html__('Add New Resource', 'ralfdocs'),
        'new_item' => esc_html__('New Resource', 'ralfdocs'),
        'edit_item' => esc_html__('Edit Resource', 'ralfdocs'),
        'view_item' => esc_html__('View Resource', 'ralfdocs'),
        'view_items' => esc_html__('View Resources', 'ralfdocs'),
        'all_items' => esc_html__('All Resources', 'ralfdocs'),
        'search_items' => esc_html__('Search Resources', 'ralfdocs'),
        'not_found' => esc_html__('No Resources Found', 'ralfdocs'),
        'not_found_in_trash' => esc_html__('No Resources Found in Trash', 'ralfdocs')
      );
      $resources_args = array(
        'labels' => $resources_labels,
        'description' => esc_html__('RALF Resources', 'ralfdocs'),
        'public' => true,
        'menu_position' => 7,
        'menu_icon' => 'dashicons-book-alt',
        'supports' => array('title', 'author', 'revisions', 'editor')
      );
      register_post_type('resources', $resources_args);    
    }

    public function create_taxonomies(){
      register_taxonomy('sectors',
        array('impacts', 'resources', 'questions'),
        array(
          'hierarchical' => true,
          'show_admin_column' => true,
          'public' => true,
          'labels' => array(
            'name' => esc_html_x('Sectors', 'taxonomy general name', 'ralfdocs'),
            'singular_name' => esc_html_x('Sector', 'taxonomy singular name', 'ralfdocs'),
            'search_items' => esc_html__('Search Sectors', 'ralfdocs'),
            'all_items' => esc_html__('All Sectors', 'ralfdocs'),
            'parent_item' => esc_html__('Parent Sector', 'ralfdocs'),
            'parent_item_colon' => esc_html__('Parent Sector:', 'ralfdocs'),
            'edit_item' => esc_html__('Edit Sector', 'ralfdocs'),
            'update_item' => esc_html__('Update Sector', 'ralfdocs'),
            'add_new_item' => esc_html__('Add New Sector', 'ralfdocs'),
            'new_item_name' => esc_html__('New Sector Name', 'ralfdocs'),
            'menu_name' => esc_html__('Sectors', 'ralfdocs')
          )
        )
      );
      register_taxonomy('impact_tags',
        'impacts',
        array(
          'hierarchical' => false,
          'show_admin_column' => true,
          'public' => true,
          'labels' => array(
            'name' => esc_html_x('Impact Tags', 'taxonomy general name', 'ralfdocs'),
            'singular_name' => esc_html_x('Impact Tag', 'taxonomy singular name', 'ralfdocs'),
            'search_items' => esc_html__('Search Impact Tags', 'ralfdocs'),
            'popular_items' => esc_html__('Popular Impact Tags', 'ralfdocs'),
            'all_items' => esc_html__('All Impact Tags', 'ralfdocs'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => esc_html__('Edit Impact Tag', 'ralfdocs'),
            'update_item' => esc_html__('Update Impact Tag', 'ralfdocs'),
            'add_new_item' => esc_html__('Add New Impact Tag', 'ralfdocs'),
            'new_item_name' => esc_html__('New Impact Tag Name', 'ralfdocs'),
            'separate_items_with_commas' => esc_html__('Separate Impact Tags with commas', 'ralfdocs'),
            'add_or_remove_items' => esc_html__('Add or Remove Impact Tags', 'ralfdocs'),
            'choose_from_most_used' => esc_html__('Choose from the most used Impact Tags', 'ralfdocs'),
            'not_found' => esc_html__('No Impact Tags Found', 'ralfdocs'),
            'menu_name' => esc_html__('Impact Tags', 'ralfdocs')
          )
        )
      );
      register_taxonomy('resource_types',
        'resources',
        array(
          'hierarchical' => true,
          'show_admin_column' => true,
          'public' => true,
          'labels' => array(
            'name' => esc_html_x('Resource Types', 'taxonomy general name', 'ralfdocs'),
            'singular_name' => esc_html_x('Resource Type', 'taxonomy singular name', 'ralfdocs'),
            'search_items' => esc_html__('Search Resource Types', 'ralfdocs'),
            'all_items' => esc_html__('All Resource Types', 'ralfdocs'),
            'parent_item' => esc_html__('Parent Resource Type', 'ralfdocs'),
            'parent_item_colon' => esc_html__('Parent Resource Type:', 'ralfdocs'),
            'edit_item' => esc_html__('Edit Resource Type', 'ralfdocs'),
            'update_item' => esc_html__('Update Resource Type', 'ralfdocs'),
            'add_new_item' => esc_html__('Add New Resource Type', 'ralfdocs'),
            'new_item_name' => esc_html__('New Resource Type Name', 'ralfdocs'),
            'menu_name' => esc_html__('Resource Types', 'ralfdocs')
          )
        )
      );
      register_taxonomy('priority_keywords',
        array('impacts', 'activities', 'resources'),
        array(
          'hierarchical' => false,
          'show_admin_column' => false,
          'public' => true,
          'labels' => array(
            'name' => esc_html_x('Priority Keywords', 'taxonomy general name', 'ralfdocs'),
            'singular_name' => esc_html_x('Priority Keyword', 'taxonomy singular name', 'ralfdocs'),
            'search_items' => esc_html__('Search Priority Keywords', 'ralfdocs'),
            'popular_items' => esc_html__('Popular Priority Keywords', 'ralfdocs'),
            'all_items' => esc_html__('All Priority Keywords', 'ralfdocs'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => esc_html__('Edit Priority Keyword', 'ralfdocs'),
            'update_item' => esc_html__('Update Priority Keyword', 'ralfdocs'),
            'add_new_item' => esc_html__('Add New Priority Keyword', 'ralfdocs'),
            'new_item_name' => esc_html__('New Priority Keyword Name', 'ralfdocs'),
            'separate_items_with_commas' => esc_html__('Separate Priority Keywords with Commas', 'ralfdocs'),
            'add_or_remove_items' => esc_html__('Add or Remove Priority Keywords', 'ralfdocs'),
            'choose_from_most_used' => esc_html__('Choose from the most used Priority Keywords', 'ralfdocs'),
            'not_found' => esc_html__('No Priority Keywords Found', 'ralfdocs'),
            'menu_name' => esc_html__('Priority Keywords', 'ralfdocs')
          )
        )
      );  
    }

    public function add_acf_field_groups(){
      /*
       * activities cpt acf field groups
      */

      // activities cpt color setting
      acf_add_local_field_group(array(
        'key' => 'group_5c06db3c4e000',
        'title' => esc_html__('Activities Articles Settings', 'ralfdocs'),
        'fields' => array(
          array(
            'key' => 'field_5c06db454973e',
            'label' => esc_html__('Activities Color', 'ralfdocs'),
            'name' => 'activities_color',
            'type' => 'color_picker',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'default_value' => '',
          ),
        ),
        'location' => array(
          array(
            array(
              'param' => 'options_page',
              'operator' => '==',
              'value' => 'acf-options-activities-settings',
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
      
      acf_add_local_field_group(array(
        'key' => 'group_5a980a1c27c80',
        'title' => esc_html__('Activities Settings', 'ralfdocs'),
        'fields' => array(
          array(
            'key' => 'field_5a980a245519c',
            'label' => esc_html__('Conditions', 'ralfdocs'),
            'name' => 'conditions',
            'type' => 'wysiwyg',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'default_value' => '',
            'tabs' => 'all',
            'toolbar' => 'full',
            'media_upload' => 1,
            'delay' => 0,
          ),
          array(
            'key' => 'field_5a980a2e5519d',
            'label' => esc_html__('Related Impacts', 'ralfdocs'),
            'name' => 'related_impacts',
            'type' => 'relationship',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'post_type' => array(
              0 => 'impacts',
            ),
            'taxonomy' => array(
            ),
            'filters' => array(
              0 => 'search',
              1 => 'taxonomy',
            ),
            'elements' => '',
            'min' => '',
            'max' => '',
            'return_format' => 'id',
          ),
          array(
            'key' => 'field_5c06db6fdc09b',
            'label' => esc_html__('Related Resources', 'ralfdocs'),
            'name' => 'related_resources',
            'type' => 'relationship',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'post_type' => array(
              0 => 'resources',
            ),
            'taxonomy' => '',
            'filters' => array(
              0 => 'search',
              1 => 'taxonomy',
            ),
            'elements' => '',
            'min' => '',
            'max' => '',
            'return_format' => 'id',
          ),
        ),
        'location' => array(
          array(
            array(
              'param' => 'post_type',
              'operator' => '==',
              'value' => 'activities',
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

      /*
       * Impacts cpt settings
      */

      acf_add_local_field_group(array(
        'key' => 'group_5c06dbba85ef8',
        'title' => esc_html__('Impacts Articles Settings', 'ralfdocs'),
        'fields' => array(
          array(
            'key' => 'field_5c06dbc4838c9',
            'label' => esc_html__('Impacts Color', 'ralfdocs'),
            'name' => 'impacts_color',
            'type' => 'color_picker',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'default_value' => '',
          ),
        ),
        'location' => array(
          array(
            array(
              'param' => 'options_page',
              'operator' => '==',
              'value' => 'acf-options-impacts-settings',
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
      
      acf_add_local_field_group(array(
        'key' => 'group_5a980a747f31e',
        'title' => esc_html__('Impacts Settings', 'ralfdocs'),
        'fields' => array(
          array(
            'key' => 'field_5c06dbf2ac3fd',
            'label' => esc_html__('Related Resources', 'ralfdocs'),
            'name' => 'related_resources',
            'type' => 'relationship',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'post_type' => array(
              0 => 'resources',
            ),
            'taxonomy' => '',
            'filters' => array(
              0 => 'search',
              1 => 'taxonomy',
            ),
            'elements' => '',
            'min' => '',
            'max' => '',
            'return_format' => 'id',
          ),
        ),
        'location' => array(
          array(
            array(
              'param' => 'post_type',
              'operator' => '==',
              'value' => 'impacts',
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

      /*
       * resources cpt settings
      */

      acf_add_local_field_group(array(
        'key' => 'group_5c06dc2441cdb',
        'title' => esc_html__('Resource Type Settings', 'ralfdocs'),
        'fields' => array(
          array(
            'key' => 'field_5c06dc2c1bb60',
            'label' => esc_html__('Abbreviation', 'ralfdocs'),
            'name' => 'abbreviation',
            'type' => 'text',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'maxlength' => '',
          ),
          array(
            'key' => 'field_5c06dc391bb61',
            'label' => esc_html__('Resource Type Color', 'ralfdocs'),
            'name' => 'resource_type_color',
            'type' => 'color_picker',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'default_value' => '#002f6c',
          ),
        ),
        'location' => array(
          array(
            array(
              'param' => 'taxonomy',
              'operator' => '==',
              'value' => 'resource_types',
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
      
      acf_add_local_field_group(array(
        'key' => 'group_5c06dc6c13758',
        'title' => esc_html__('Resources Articles Settings', 'ralfdocs'),
        'fields' => array(
          array(
            'key' => 'field_5c06dc75eac38',
            'label' => esc_html__('Resources Color', 'ralfdocs'),
            'name' => 'resources_color',
            'type' => 'color_picker',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'default_value' => '',
          ),
        ),
        'location' => array(
          array(
            array(
              'param' => 'options_page',
              'operator' => '==',
              'value' => 'acf-options-resources-settings',
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
      
      acf_add_local_field_group(array(
        'key' => 'group_5c06dc91d2bad',
        'title' => esc_html__('Resources Settings', 'ralfdocs'),
        'fields' => array(
          array(
            'key' => 'field_5c06dc9ca0a15',
            'label' => esc_html__('Original Resource URL', 'ralfdocs'),
            'name' => 'original_resource_url',
            'type' => 'url',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
          ),
        ),
        'location' => array(
          array(
            array(
              'param' => 'post_type',
              'operator' => '==',
              'value' => 'resources',
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

      /*
       * sector taxonomy settings
      */

      acf_add_local_field_group(array(
        'key' => 'group_5b043d750ef61',
        'title' => esc_html__('Sector Settings', 'ralfdocs'),
        'fields' => array(
          array(
            'key' => 'field_5b043d7fdffaf',
            'label' => esc_html__('Sector Icon Type', 'ralfdocs'),
            'name' => 'sector_icon_type',
            'type' => 'select',
            'instructions' => esc_html__('Select whether icon will be png or svg (svg preferred).', 'ralfdocs'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'choices' => array(
              'png' => 'png',
              'svg' => 'svg',
            ),
            'default_value' => array(
              0 => 'png',
            ),
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 0,
            'ajax' => 0,
            'return_format' => 'value',
            'placeholder' => '',
          ),
          array(
            'key' => 'field_5b043e0cdffb0',
            'label' => esc_html__('Sector Icon', 'ralfdocs'),
            'name' => 'sector_icon',
            'type' => 'image',
            'instructions' => esc_html__('Image should be a png about 200x200px with no background.	The background color circle will be added automatically.', 'ralfdocs'),
            'required' => 0,
            'conditional_logic' => array(
              array(
                array(
                  'field' => 'field_5b043d7fdffaf',
                  'operator' => '==',
                  'value' => 'png',
                ),
              ),
            ),
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'return_format' => 'url',
            'preview_size' => 'full',
            'library' => 'all',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => 'png',
          ),
          array(
            'key' => 'field_5b043e60dffb1',
            'label' => esc_html__('Sector Icon', 'ralfdocs'),
            'name' => 'sector_icon',
            'type' => 'url',
            'instructions' => esc_html__('Enter full file path to the svg file. The svg should not have a background - the background color will be added automatically.', 'ralfdocs'),
            'required' => 0,
            'conditional_logic' => array(
              array(
                array(
                  'field' => 'field_5b043d7fdffaf',
                  'operator' => '==',
                  'value' => 'svg',
                ),
              ),
            ),
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'default_value' => '',
            'placeholder' => '',
          ),
          array(
            'key' => 'field_5b043ef7c86cf',
            'label' => esc_html__('Sector Color', 'ralfdocs'),
            'name' => 'sector_color',
            'type' => 'color_picker',
            'instructions' => esc_html__('Select a color for the sector. This will be used as the background circle for the sector icon.', 'ralfdocs'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'default_value' => '#8cc63f',
          ),
        ),
        'location' => array(
          array(
            array(
              'param' => 'taxonomy',
              'operator' => '==',
              'value' => 'sectors',
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

    public function add_cpt_options(){
      acf_add_options_sub_page(array(
        'page_title' => esc_html__('Activities Settings', 'ralfdocs'),
        'menu_title' => esc_html__('Activities Settings', 'ralfdocs'),
        'parent_slug' => 'edit.php?post_type=activities'
      ));
      acf_add_options_sub_page(array(
        'page_title' => esc_html__('Impacts Settings', 'ralfdocs'),
        'menu_title' => esc_html__('Impacts Settings', 'ralfdocs'),
        'parent_slug' => 'edit.php?post_type=impacts'
      ));
      acf_add_options_sub_page(array(
        'page_title' => esc_html__('Resources Settings', 'ralfdocs'),
        'menu_title' => esc_html__('Resources Settings', 'ralfdocs'),
        'parent_slug' => 'edit.php?post_type=resources'
      ));    
    }

    public function acf_related_impacts_relationship_display($title, $post, $field, $post_id){
      //$impact_tag_names = get_field('impact_tag_names', $post->ID);
      //$impact_tag_names = get_the_tags($post->ID);
      $impact_tag_names = get_the_terms($post->ID, 'impact_tags');
      $impact_tag_name_list = array();
    
      if(!empty($impact_tag_names)){
        foreach($impact_tag_names as $impact_tag_name){
          $impact_tag_name_list[] = $impact_tag_name->name;
        }
    
        $title .= ' [' . implode(', ', $impact_tag_name_list) . ']';
      }
    
      return $title;
    }
    
    public function acf_reciprocal_relationship($value, $post_id, $field) {
      // https://github.com/Hube2/acf-filters-and-functions/blob/master/acf-reciprocal-relationship.php
      
      // set the two fields that you want to create
      // a two way relationship for
      // these values can be the same field key
      // if you are using a single relationship field
      // on a single post type
      
      // the field key of one side of the relationship
      $key_a = $this->related_impacts;
      // the field key of the other side of the relationship
      // as noted above, this can be the same as $key_a
      $key_b = $this->related_activities;
      
      // figure out wich side we're doing and set up variables
      // if the keys are the same above then this won't matter
      // $key_a represents the field for the current posts
      // and $key_b represents the field on related posts
      if ($key_a != $field['key']) {
        // this is side b, swap the value
        $temp = $key_a;
        $key_a = $key_b;
        $key_b = $temp;
      }
      
      // get both fields
      // this gets them by using an acf function
      // that can gets field objects based on field keys
      // we may be getting the same field, but we don't care
      $field_a = acf_get_field($key_a);
      $field_b = acf_get_field($key_b);
      
      // set the field names to check
      // for each post
      $name_a = $field_a['name'];
      $name_b = $field_b['name'];
      
      // get the old value from the current post
      // compare it to the new value to see
      // if anything needs to be updated
      // use get_post_meta() to a avoid conflicts
      $old_values = get_post_meta($post_id, $name_a, true);
      // make sure that the value is an array
      if (!is_array($old_values)) {
        if (empty($old_values)) {
          $old_values = array();
        } else {
          $old_values = array($old_values);
        }
      }
      // set new values to $value
      // we don't want to mess with $value
      $new_values = $value;
      // make sure that the value is an array
      if (!is_array($new_values)) {
        if (empty($new_values)) {
          $new_values = array();
        } else {
          $new_values = array($new_values);
        }
      }
      
      // get differences
      // array_diff returns an array of values from the first
      // array that are not in the second array
      // this gives us lists that need to be added
      // or removed depending on which order we give
      // the arrays in
      
      // this line is commented out, this line should be used when setting
      // up this filter on a new site. getting values and updating values
      // on every relationship will cause a performance issue you should
      // only use the second line "$add = $new_values" when adding this
      // filter to an existing site and then you should switch to the
      // first line as soon as you get everything updated
      // in either case if you have too many existing relationships
      // checking end updated every one of them will more then likely
      // cause your updates to time out.
      //$add = array_diff($new_values, $old_values);
      $add = $new_values;
      $delete = array_diff($old_values, $new_values);
      
      // reorder the arrays to prevent possible invalid index errors
      $add = array_values($add);
      $delete = array_values($delete);
      
      if (!count($add) && !count($delete)) {
        // there are no changes
        // so there's nothing to do
        return $value;
      }
      
      // do deletes first
      // loop through all of the posts that need to have
      // the recipricol relationship removed
      for ($i=0; $i<count($delete); $i++) {
        $related_values = get_post_meta($delete[$i], $name_b, true);
        if (!is_array($related_values)) {
          if (empty($related_values)) {
            $related_values = array();
          } else {
            $related_values = array($related_values);
          }
        }
        // we use array_diff again
        // this will remove the value without needing to loop
        // through the array and find it
        $related_values = array_diff($related_values, array($post_id));
        // insert the new value
        update_post_meta($delete[$i], $name_b, $related_values);
        // insert the acf key reference, just in case
        update_post_meta($delete[$i], '_'.$name_b, $key_b);
      }
      
      // do additions, to add $post_id
      for ($i=0; $i<count($add); $i++) {
        $related_values = get_post_meta($add[$i], $name_b, true);
        if (!is_array($related_values)) {
          if (empty($related_values)) {
            $related_values = array();
          } else {
            $related_values = array($related_values);
          }
        }
        if (!in_array($post_id, $related_values)) {
          // add new relationship if it does not exist
          $related_values[] = $post_id;
        }
        // update value
        update_post_meta($add[$i], $name_b, $related_values);
        // insert the acf key reference, just in case
        update_post_meta($add[$i], '_'.$name_b, $key_b);
      }
      
      return $value;
      
    } // end function acf_reciprocal_relationship

    public function get_related_impacts_field(){
      return $this->related_impacts;
    }

    public function get_related_activities_field(){
      return $this->related_activities;
    }
  }
}