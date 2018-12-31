<?php
if(!defined('ABSPATH')){ exit; }

class RALFDOCS_Sectors_Widget extends WP_Widget{
	function __construct(){
		parent::__construct(
			'ralfdocs_sectors_widget',
			esc_html__('Sectors Widget', 'ralfdocs'),
			array('description' => esc_html__('A list of Sectors', 'ralfdocs'))
		);
	}
	public function widget($args, $instance){
		$title = apply_filters('widget_title', $instance['title']);

		echo $args['before_widget'];
		if(!empty($title)){
			echo $args['before_title'] . $title . $args['after_title'];
		}

    $sectors = get_terms(array('taxonomy' => 'sectors', 'orderby' => 'term_group', 'parent' => 0));
    if($sectors){
      echo '<div class="sidebar-section-body"><ul>';
      foreach($sectors as $sector){
        echo '<li><a href="' . esc_url(get_term_link($sector)) . '">' . esc_html($sector->name) . ' (' . $sector->count . ')' . '</a></li>';
        $sub_sectors = get_terms(array('taxonomy' => 'sectors', 'orderby' => 'name', 'parent' => $sector->term_id));
        if(!empty($sub_sectors) && !is_wp_error($sub_sectors)){
          foreach($sub_sectors as $sub_sector){
            echo '<li><a href="' . esc_url(get_term_link($sub_sector)) . '"> - ' . esc_html($sub_sector->name) . ' (' . $sub_sector->count . ')' . '</a></li>';
          }
        }
      }
    }
    echo '</ul></div>';
		echo $args['after_widget'];
	}

	public function form($instance){
		if(isset($instance['title'])){
			$title = $instance['title'];
		}
		else{
			$title = esc_html__('New title', 'ralfdocs');
		}
	?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_attr_e('Title:', 'ralfdocs'); ?></label>
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
