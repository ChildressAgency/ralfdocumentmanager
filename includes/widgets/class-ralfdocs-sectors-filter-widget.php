<?php
//https://rudrastyh.com/wordpress/ajax-post-filters.html
if(!defined('ABSPATH')){ exit; }

class RALFDOCS_Sectors_Filter_Widget extends WP_Widget{
  function __construct(){
    parent::__construct(
      'ralfdocs_filter_widget',
      esc_html__('Filter Widget', 'ralfdocs'),
      array('description' => esc_html__('Filter Archives or Search Results by Sector', 'ralfdocs'))
    );
  }

  public function widget($args, $instance){
    if(is_page('sectors') || is_tax('resource_types')){
      $title = apply_filters('widget_title', $instance['title']);
      $chosen_sector_filters = array();

      if(isset($_GET['sector_term'])){
        $current_sectors = get_term($_GET['sector_term'], 'sectors');
      }
      else{
        $current_sectors = get_queried_object();
      }
      
      if($current_sectors){
        $chosen_sector_filters[] = $current_sectors->term_id;
        $chosen_sector_filters[] = $current_sectors->parent;
      }

      echo $args['before_widget'];
      if(!empty($title)){
        echo $args['before_title'] . $title . $args['after_title'];
      }

      //show sectors filter
      //$current_sector = $query_vars['sectors'];
      $parent_sectors = get_terms(array(
        'post_type' => 'impacts',
        'taxonomy' => 'sectors',
        'orderby' => 'term_group',
        'parent' => 0
      ));
      //var_dump($parent_sectors);
      if($parent_sectors){
        echo '<div id="sectors-filter" class="sidebar-section-body"><ul>';

        foreach($parent_sectors as $sector){
          //get sector children
          $sector_children = get_terms(array(
            'post_type' => 'impacts',
            'taxonomy' => 'sectors',
            'orderby' => 'name',
            'parent' => $sector->term_id
          ));

          //get total number of sectors under the parent sector
          $total_sector_count = $sector->count;
          if(!empty($sector_children) && !is_wp_error($sector_children)){
            foreach($sector_children as $child){
              $total_sector_count = $total_sector_count + $child->count;
            }
          }

          echo '<li>';
            //display the parent sector checkbox
            $sector_parent_checked = (in_array($sector->term_id, $chosen_sector_filters)) ? ' checked="checked"' : '';
            echo '<input type="checkbox" name="sector-filter" value="' . $sector->term_id . '"' . $sector_parent_checked . ' />' . $sector->name . ' (' . $total_sector_count . ')';

            //add checkboxes for child sectors
            if(!empty($sector_children) && !is_wp_error($sector_children)){
              echo '<ul>';
              foreach($sector_children as $child){
                $child_checked = (in_array($child->term_id, $chosen_sector_filters)) ? ' checked="checked"' : '';
                echo '<li><input type="checkbox" name="sector-filter" value="' . $child->term_id . '"' . $child_checked . ' />' . $child->name . ' (' . $child->count . ')</li>';
              }
              echo '</ul>';
            }

          echo '</li>';
        }
        echo '</ul></div>';
        echo $args['after_widget'];
      }
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