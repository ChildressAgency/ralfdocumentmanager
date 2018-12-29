<?php
if(!defined('ABSPATH')){ exit; }

if(!class_exists('Ralfdocs_Post_Types')){
  class Ralfdocs_Post_Types{
    /*
    * acf field keys for reciprocal relationships of related activities & impacts
    * $related_impacts is the acf relationship field that shows on the activities cpt
    * $related_activities is the acf relationship field that shows on the impacts cpt
    */
    private $related_impacts = 'field_5a980a2e5519d';
    private $related_activities = 'field_5a980a8d64d2a';

    public function __construct(){
      add_action('init', array($this, 'init'));
      add_action('acf/init', array($this, 'acf_init'));

      add_filter('acf/update_value/key=' . $this->related_impacts, array($this, 'acf_reciprocal_relationship'), 10, 3);
      add_filter('acf/update_value/key=' . $this->related_activities, array($this, 'acf_reciprocal_relationship'), 10, 3);
      add_filter('acf/fields/relationship/result/key=' . $this->related_impacts, array($this, 'acf_related_impacts_relationship_display'), 10, 4);
    }

    public function init(){
      $this->create_post_types();
      $this->create_taxonomies();
    }

    public function acf_init(){
      $this->add_acf_cpt_options();
    }

    public function create_post_types(){
      $activity_labels = array(
        'name' => _x('Activities', 'post type general name', 'ralfdocs'),
        'singular_name' => _x('Activity', 'post type singular name', 'ralfdocs'),
        'menu_name' => _x('Activities', 'admin menu', 'ralfdocs'),
        'name_admin_bar' => _x('Activity', 'add new on admin bar', 'ralfdocs'),
        'add_new' => _x('Add New', 'activity', 'ralfdocs'),
        'add_new_item' => __('Add New Activity', 'ralfdocs'),
        'new_item' => __('New Activity', 'ralfdocs'),
        'edit_item' => __('Edit Activity', 'ralfdocs'),
        'view_item' => __('View Activity', 'ralfdocs'),
        'view_items' => __('View Activities', 'ralfdocs'),
        'all_items' => __('All Activities', 'ralfdocs'),
        'search_items' => __('Search Activities', 'ralfdocs'),
        'not_found' => __('No Activities Found', 'ralfdocs'),
        'not_found_in_trash' => __('No Activities Found in Trash', 'ralfdocs')
      );
      $activity_args = array(
        'labels' => $activity_labels,
        'description' => __('RALF Activities', 'ralfdocs'),
        'public' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-store',
        'supports' => array('title', 'author', 'revisions', 'editor')
      );
      register_post_type('activities', $activity_args);
    
      $impacts_labels = array(
        'name' => _x('Impacts', 'post type general name', 'ralfdocs'),
        'singular_name' => _x('Impact', 'post type singular name', 'ralfdocs'),
        'menu_name' => _x('Impacts', 'admin menu', 'ralfdocs'),
        'name_admin_bar' => _x('Impact', 'add new on admin bar', 'ralfdocs'),
        'add_new' => _x('Add New', 'impact', 'ralfdocs'),
        'add_new_item' => __('Add New Impact', 'ralfdocs'),
        'new_item' => __('New Impact', 'ralfdocs'),
        'edit_item' => __('Edit Impact', 'ralfdocs'),
        'view_item' => __('View Impact', 'ralfdocs'),
        'view_items' => __('View Impacts', 'ralfdocs'),
        'all_items' => __('All Impacts', 'ralfdocs'),
        'search_items' => __('Search Impacts', 'ralfdocs'),
        'not_found' => __('No Impacts Found', 'ralfdocs'),
        'not_found_in_trash' => __('No Impacts Found in Trash', 'ralfdocs')
      );
      $impacts_args = array(
        'labels' => $impacts_labels,
        'description' => __('RALF Impacts', 'ralfdocs'),
        'public' => true,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-lightbulb',
        'supports' => array('title', 'author', 'revisions', 'editor')
      );
      register_post_type('impacts', $impacts_args);
    
      $resources_labels = array(
        'name' => _x('Resources','post type general name', 'ralfdocs'),
        'singular_name' => _x('Resource', 'post type singular name', 'ralfdocs'),
        'menu_name' => _x('Resources', 'admin menu', 'ralfdocs'),
        'name_admin_bar' => _x('Resource', 'add new on admin bar', 'ralfdocs'),
        'add_new' => _x('Add New', 'resource', 'ralfdocs'),
        'add_new_item' => __('Add New Resource', 'ralfdocs'),
        'new_item' => __('New Resource', 'ralfdocs'),
        'edit_item' => __('Edit Resource', 'ralfdocs'),
        'view_item' => __('View Resource', 'ralfdocs'),
        'view_items' => __('View Resources', 'ralfdocs'),
        'all_items' => __('All Resources', 'ralfdocs'),
        'search_items' => __('Search Resources', 'ralfdocs'),
        'not_found' => __('No Resources Found', 'ralfdocs'),
        'not_found_in_trash' => __('No Resources Found in Trash', 'ralfdocs')
      );
      $resources_args = array(
        'labels' => $resources_labels,
        'description' => __('RALF Resources', 'ralfdocs'),
        'public' => true,
        'menu_position' => 7,
        'menu_icon' => 'dashicons-book-alt',
        'supports' => array('title', 'author', 'revisions', 'editor')
      );
      register_post_type('resources', $resources_args);    
    }

    public function create_taxonomies(){
      register_taxonomy('sectors',
        array('impacts', 'resources'),
        array(
          'hierarchical' => true,
          'show_admin_column' => true,
          'public' => true,
          'labels' => array(
            'name' => _x('Sectors', 'taxonomy general name', 'ralfdocs'),
            'singular_name' => _x('Sector', 'taxonomy singular name', 'ralfdocs'),
            'search_items' => __('Search Sectors', 'ralfdocs'),
            'all_items' => __('All Sectors', 'ralfdocs'),
            'parent_item' => __('Parent Sector', 'ralfdocs'),
            'parent_item_colon' => __('Parent Sector:', 'ralfdocs'),
            'edit_item' => __('Edit Sector', 'ralfdocs'),
            'update_item' => __('Update Sector', 'ralfdocs'),
            'add_new_item' => __('Add New Sector', 'ralfdocs'),
            'new_item_name' => __('New Sector Name', 'ralfdocs'),
            'menu_name' => __('Sectors', 'ralfdocs')
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
            'name' => _x('Impact Tags', 'taxonomy general name', 'ralfdocs'),
            'singular_name' => _x('Impact Tag', 'taxonomy singular name', 'ralfdocs'),
            'search_items' => __('Search Impact Tags', 'ralfdocs'),
            'popular_items' => __('Popular Impact Tags', 'ralfdocs'),
            'all_items' => __('All Impact Tags', 'ralfdocs'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Impact Tag', 'ralfdocs'),
            'update_item' => __('Update Impact Tag', 'ralfdocs'),
            'add_new_item' => __('Add New Impact Tag', 'ralfdocs'),
            'new_item_name' => __('New Impact Tag Name', 'ralfdocs'),
            'separate_items_with_commas' => __('Separate Impact Tags with commas', 'ralfdocs'),
            'add_or_remove_items' => __('Add or Remove Impact Tags', 'ralfdocs'),
            'choose_from_most_used' => __('Choose from the most used Impact Tags', 'ralfdocs'),
            'not_found' => __('No Impact Tags Found', 'ralfdocs'),
            'menu_name' => __('Impact Tags', 'ralfdocs')
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
            'name' => _x('Resource Types', 'taxonomy general name', 'ralfdocs'),
            'singular_name' => _x('Resource Type', 'taxonomy singular name', 'ralfdocs'),
            'search_items' => __('Search Resource Types', 'ralfdocs'),
            'all_items' => __('All Resource Types', 'ralfdocs'),
            'parent_item' => __('Parent Resource Type', 'ralfdocs'),
            'parent_item_colon' => __('Parent Resource Type:', 'ralfdocs'),
            'edit_item' => __('Edit Resource Type', 'ralfdocs'),
            'update_item' => __('Update Resource Type', 'ralfdocs'),
            'add_new_item' => __('Add New Resource Type', 'ralfdocs'),
            'new_item_name' => __('New Resource Type Name', 'ralfdocs'),
            'menu_name' => __('Resource Types', 'ralfdocs')
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
            'name' => _x('Priority Keywords', 'taxonomy general name', 'ralfdocs'),
            'singular_name' => _x('Priority Keyword', 'taxonomy singular name', 'ralfdocs'),
            'search_items' => __('Search Priority Keywords', 'ralfdocs'),
            'popular_items' => __('Popular Priority Keywords', 'ralfdocs'),
            'all_items' => __('All Priority Keywords', 'ralfdocs'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Priority Keyword', 'ralfdocs'),
            'update_item' => __('Update Priority Keyword', 'ralfdocs'),
            'add_new_item' => __('Add New Priority Keyword', 'ralfdocs'),
            'new_item_name' => __('New Priority Keyword Name', 'ralfdocs'),
            'separate_items_with_commas' => __('Separate Priority Keywords with Commas', 'ralfdocs'),
            'add_or_remove_items' => __('Add or Remove Priority Keywords', 'ralfdocs'),
            'choose_from_most_used' => __('Choose from the most used Priority Keywords', 'ralfdocs'),
            'not_found' => __('No Priority Keywords Found', 'ralfdocs'),
            'menu_name' => __('Priority Keywords', 'ralfdocs')
          )
        )
      );  
    }

    public function add_acf_cpt_options(){
      acf_add_options_sub_page(array(
        'page_title' => __('Activities Settings', 'ralfdocs'),
        'menu_title' => __('Activities Settings', 'ralfdocs'),
        'parent_slug' => 'edit.php?post_type=activities'
      ));
      acf_add_options_sub_page(array(
        'page_title' => __('Impacts Settings', 'ralfdocs'),
        'menu_title' => __('Impacts Settings', 'ralfdocs'),
        'parent_slug' => 'edit.php?post_type=impacts'
      ));
      acf_add_options_sub_page(array(
        'page_title' => __('Resources Settings', 'ralfdocs'),
        'menu_title' => __('Resources Settings', 'ralfdocs'),
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
  }
}