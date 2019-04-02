<?php
//https://rudrastyh.com/wordpress/ajax-post-filters.html
if(!defined('ABSPATH')){ exit; }

class RALFDOCS_Sectors_Filter_Widget extends WP_Widget{
  function __construct(){
    parent::__construct(
      'ralfdocs_sector_filter_widget',
      esc_html__('Sector Filter Widget', 'ralfdocs'),
      array('description' => esc_html__('Filter Archives or Search Results by Sector', 'ralfdocs'))
    );
  }

  public function widget($args, $instance){
    if(is_page('sectors') || is_tax('resource_types') || is_search()){
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

            //don't count the parent if there is a child also selected
            $child_selected_with_parent = array();
            foreach($sector_children as $sector_child){
              $child_selected_with_parent[] = $sector_child->term_id;
            }

          //get total number of sectors under the parent sector
          if(is_search()){
            $sector_search_term = new SWP_Query(array(
              'post_type' => 'impacts',
              's' => get_search_query(),
              'engine' => 'default',
              'fields' => 'ids',
              'tax_query' => array(
                'relation' => 'AND',
                array(
                  'taxonomy' => 'sectors',
                  'field' => 'term_id',
                  'terms' => $sector->term_id
                ),
                array(
                  'taxonomy' => 'sectors',
                  'field' => 'term_id',
                  'terms' => $child_selected_with_parent,
                  'operator' => 'NOT IN'
                )
              )
            ));

            //$sector_count = count($sector_search_term->posts);
            $sector_count = $sector_search_term->post_count;
          }
          else{
            $parents_without_children_selected = new WP_Query(array(
              'post_type' => 'impacts',
              'fields' => 'ids',
              'posts_per_page' => -1,
              'tax_query' => array(
                'relation' => 'AND',
                array(
                  'taxonomy' => 'sectors',
                  'field' => 'term_id',
                  'terms' => $sector->term_id,
                  //'include_children' => false
                ),
                array(
                  'taxonomy' => 'sectors',
                  'field' => 'term_id',
                  'terms' => $child_selected_with_parent,
                  'operator' => 'NOT IN'
                )
              )
            ));
            //var_dump($parents_without_children_selected);
            $sector_count = $parents_without_children_selected->post_count;
          }

          if(!empty($sector_children) && !is_wp_error($sector_children)){
            $sector_child_count = 0;
            foreach($sector_children as $child){
              if(is_search()){
                $sector_search_term_child = new SWP_Query(array(
                  'post_type' => 'impacts',
                  's' => get_search_query(),
                  'engine' => 'default',
                  'fields' => 'ids',
                  'tax_query' => array(
                    array(
                      'taxonomy' => 'sectors',
                      'field' => 'term_id',
                      'terms' => $child->term_id
                    )
                  )
                ));
                //$total_sector_count = $sector_count + $sector_search_term_child->post_count;
                $sector_child_count += $sector_search_term_child->post_count;
              }
              else{
                $sector_child_count += $child->count;
              }
            }
            $total_sector_count = $sector_count + $sector_child_count;
          }
          else{
            $total_sector_count = $sector_count;
          }

          echo '<li>';
            //display the parent sector checkbox
            $sector_parent_checked = (in_array($sector->term_id, $chosen_sector_filters)) ? ' checked="checked"' : '';
            echo '<label><input type="checkbox" name="sector-filter" value="' . $sector->term_id . '"' . $sector_parent_checked . ' class="article-filter" />' . $sector->name . ' (' . $total_sector_count . ')</label>';

            //add checkboxes for child sectors
            if(!empty($sector_children) && !is_wp_error($sector_children)){
              echo '<ul>';
              foreach($sector_children as $child){
                if(is_search()){
                  $sector_search_children = new SWP_Query(array(
                    'post_type' => 'impacts',
                    's' => get_search_query(),
                    'engine' => 'default',
                    'fields' => 'ids',
                    'tax_query' => array(
                      array(
                        'taxonomy' => 'sectors', 
                        'field' => 'term_id',
                        'terms' => $child->term_id
                      )
                    )
                  ));
                  $sector_children_count = count($sector_search_children->posts);
                }
                else{
                  $sector_children_count = $child->count;
                }

                $child_checked = (in_array($child->term_id, $chosen_sector_filters)) ? ' checked="checked"' : '';
                echo '<li><label><input type="checkbox" name="sector-filter" value="' . $child->term_id . '"' . $child_checked . ' class="article-filter" />' . $child->name . ' (' . $sector_children_count . ')</label></li>';
              }
              echo '</ul>';
            }

          echo '</li>';
        }
        echo '</ul><a href="#" id="clear-sectors-filters" class="widget-clear">' . esc_html__('clear all', 'ralfdocs') . '</a></div>';
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