<?php
if(!defined('ABSPATH')){ exit; }

class RALFDOCS_Resource_Types_Filter_Widget extends WP_Widget{
  function __construct(){
    parent::__construct(
      'ralfdocs_resource_types_filter_widget',
      esc_html__('Resource Types Filter Widget', 'ralfdocs'),
      array('description' => esc_html__('Filter Resources by Type', 'ralfdocs'))
    );
  }

  public function widget($args, $instance){
    if(is_tax('resource_types')){
      $title = apply_filters('widget_title', $instance['title']);

      echo $args['before_widget'];
      if(!empty($title)){
        echo $args['before_title'] . $title . $args['after_title'];
      }

      $chosen_resource_type_filters = array();
      $current_resource_type = get_queried_object();

      if($current_resource_type){
        $chosen_resource_type_filters[] = $current_resource_type->term_id;
        $chosen_resource_type_filters[] = $current_resource_type->parent;
      }

      $parent_resource_types = get_terms(array(
        'taxonomy' => 'resource_types',
        'orderby' => 'term_group',
        'parent' => 0
      ));

      if($parent_resource_types){
        echo '<div id="resources-filter" class="sidebar-section-body"><ul>';

        foreach($parent_resource_types as $parent_resource_type){
          $resource_type_children = get_terms(array(
            'taxonomy' => 'resource_types',
            'orderby' => 'name',
            'parent' => $parent_resource_type->term_id
          ));

          $total_resource_type_count = $parent_resource_type->count;
          if(!empty($resource_type_children) && !is_wp_error($resource_type_children)){
            foreach($resource_type_children as $child){
              $total_resource_type_count = $total_resource_type_count + $child->count;
            }
          }

          echo '<li>';

            $resource_type_parent_checked = (in_array($parent_resource_type->term_id, $chosen_resource_type_filters)) ? ' checked="checked"' : '';
            echo '<label><input type="checkbox" name="resource-type-filter" value="' . $parent_resource_type->term_id . '"' . $resource_type_parent_checked . ' />' . $parent_resource_type->name . ' (' . $total_resource_type_count . ')</label>';

            if(!empty($resource_type_children) && !is_wp_error($resource_type_children)){
              echo '<ul>';
              foreach($resource_type_children as $child){
                $child_checked = (in_array($child->term_id, $chosen_resource_type_filters)) ? ' checked="checked"' : '';
                echo '<li><label><input type="checkbox" name="resource-type-filter" value="' . $child->term_id . '"' . $child_checked . ' />' . $child->name . ' (' . $child->count . ')</label></li>';
              }
              echo '</ul>';
            }

          echo '</li>';
        }
        echo '</ul></div>';
      }

      echo $args['after_widget'];
    }
  }

  public function form($instance){
    if(isset($instance['title'])){
      $title = $instance['title'];
    }
    else{
      $title = esc_html__('New Title', 'ralfdocs');
    } ?>

    <p>
      <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_attr__('Title:', 'ralfdocs'); ?></label>
      <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
    </p>
    <?php
  }

  public function update($new_instance, $old_instance){
    $instance = array();
    $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
    return $instance;
  }
}