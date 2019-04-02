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
    if(is_tax('resource_types') || is_page('sectors') || is_search()){
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
            'post_type' => 'resources',
            'taxonomy' => 'resource_types',
            'orderby' => 'name',
            'parent' => $parent_resource_type->term_id
          ));
          
          
          if(is_search() || isset($_GET['s'])){
            //don't count the parent if there is a child also selected
            $child_selected_with_parent = array();
            foreach($resource_type_children as $resource_type_child){
              $child_selected_with_parent[] = $resource_type_child->term_id;
            }
            $searched_parent_resource_type = new SWP_Query(array(
              'post_type' => 'resources',
              's' => get_search_query(),
              'engine' => 'default',
              'fields' => 'ids',
              'tax_query' => array(
                'relation' => 'AND',
                array(
                  'taxonomy' => 'resource_types',
                  'field' => 'term_id', 
                  'terms' => $parent_resource_type->term_id
                ),
                array(
                  'taxonomy' => 'resource_types',
                  'field' => 'term_id',
                  'terms' => $child_selected_with_parent,
                  'operator' => 'NOT IN'
                )
              )
            ));
            $resource_type_count = $searched_parent_resource_type->post_count;
            //$resource_type_count = count($searched_parent_resource_type->posts);
          }
          else{
            $resource_type_count = $parent_resource_type->count;
          }

          if(!empty($resource_type_children) && !is_wp_error($resource_type_children)){
            $searched_resource_type_child_count = 0;

            foreach($resource_type_children as $child){
              if(is_search()){
                $searched_resource_type_child = new SWP_Query(array(
                  'post_type' => 'resources',
                  's' => get_search_query(),
                  'engine' => 'default',
                  'fields' => 'ids',
                  'tax_query' => array(
                    array(
                      'taxonomy' => 'resource_types',
                      'field' => 'term_id',
                      'terms' => $child->term_id
                    )
                  )
                ));
                //$searched_resource_type_child_count += count($searched_resource_type_child->posts);
                $searched_resource_type_child_count += $searched_resource_type_child->post_count;
              }
              else{
                $searched_resource_type_child_count += $child->count;
              }
            }
            $total_resource_type_count = $resource_type_count + $searched_resource_type_child_count;
          }
          else{
            $total_resource_type_count = $resource_type_count;
          }

          echo '<li>';

            $resource_type_parent_checked = (in_array($parent_resource_type->term_id, $chosen_resource_type_filters)) ? ' checked="checked"' : '';
            echo '<label><input type="checkbox" name="resource-type-filter" value="' . $parent_resource_type->term_id . '"' . $resource_type_parent_checked . ' class="article-filter" />' . $parent_resource_type->name . ' (' . $total_resource_type_count . ')</label>';

            if(!empty($resource_type_children) && !is_wp_error($resource_type_children)){
              echo '<ul>';
              foreach($resource_type_children as $child){
                if(is_search()){
                  $searched_resource_type_child = new SWP_Query(array(
                    'post_type' => 'resources',
                    's' => get_search_query(),
                    'engine' => 'default',
                    'fields' => 'ids',
                    'tax_query' => array(
                      array(
                        'taxonomy' => 'resource_types',
                        'field' => 'term_id',
                        'terms' => $child->term_id
                      )
                    )
                  ));
                  //$resource_type_child_count = count($searched_resource_type_child->posts);
                  $resource_type_child_count = $searched_resource_type_child->post_count;
                }
                else{
                  $resource_type_child_count = $child->count;
                }

                $child_checked = (in_array($child->term_id, $chosen_resource_type_filters)) ? ' checked="checked"' : '';
                echo '<li><label><input type="checkbox" name="resource-type-filter" value="' . $child->term_id . '"' . $child_checked . ' class="article-filter" />' . $child->name . ' (' . $resource_type_child_count . ')</label></li>';
              }
              echo '</ul>';
            }

          echo '</li>';
        }
        echo '</ul><a href="#" id="clear-resource-types-filters" class="widget-clear">' . esc_html__('clear all', 'ralfdocs') . '</a></div>';
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