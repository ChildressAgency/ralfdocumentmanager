<?php
if(!defined('ABSPATH')){ exit; }

class Ralfdocs_Search_History_Widget extends WP_Widget{
  function __construct(){
    add_action('acf/init', array($this, 'acf_history_limit_field'));
    
    parent::__construct(
      'ralfdocs_search_history_widget',
      __('Search History Widget', 'ralfdocs'),
      array('description' => __('Show the search history', 'ralfdocs'))
    );
  }

  public function widget($args, $instance){
    $title = apply_filters('widget_title', $instance['title']);

    echo $args['before_widget'];
    if(!empty($title)){
      echo $args['before_title'] . $title . $args['after_title'];
    }

    $search_history = $this->get_search_history($this->id);
    if($search_history != ''){
      $search_history_terms = explode(',', $search_history);
      $search_history_terms_reordered = array_reverse($search_history_terms);

      echo '<div class="sidebar-section-body"><ul>';
      foreach($search_history_terms_reordered as $search_term){
        echo '<li><a href="' . esc_url(add_query_arg('s', $search_term, home_url())) . '">' . esc_html($search_term) . '</a></li>';
      }
      echo '</ul>';
      echo '<a href="#" id="clear-search-history">' . esc_html__('clear all', 'ralfdocs') . '</a></div>';
    }
    else{
      echo '<div class="sidebar-section-body"><ul><li><span class="search-history-empty">' . esc_html__('Your search history is empty.', 'ralfdocs') . '</span></li></ul></div>';
    }
  }

  protected function get_search_history($widget_id){
    //get new search term if its there
    $search_term = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';

    if(isset($_COOKIE['STYXKEY_ralfdocs_search_history'])){
      $search_terms_list = $_COOKIE['STYXKEY_ralfdocs_search_history'];
      //put search terms into array
      $search_terms = explode(',', $search_terms_list);

      $filter_chars = '()^;<>/\'"!';
      //don't do anything if the search term is empty or already in the list
      //also dont do anything if the search term has an invalid char ($filter_chars)
      if(($search_term != '') 
        && (!in_array($search_term, $search_terms))  
        && (strpbrk($search_term, $filter_chars) === false)){

        //get number of terms to save to history
        $history_limit = get_field('search_term_history_limit', 'widget_' . $widget_id);
      
        //if we are at history limit remove first search term
        if((count($search_terms) == $history_limit)){
          array_shift($search_terms);
        }

        //add the new search term to end of array if there is one
        array_push($search_terms, $search_term);
      }
      //convert terms array to string and return
      $new_search_terms_list = implode(',', $search_terms);

      return $new_search_terms_list;
    }
    else{ //no cookie, must be first search or they've been cleared with js function
      return $search_term;
    }
  }

	public function form($instance){
		if(isset($instance['title'])){
			$title = $instance['title'];
		}
		else{
			$title = __('New title', 'ralfdocs');
		}
	?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html__('Title:', 'ralfdocs'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
		</p>
	<?php
  }
  
	public function update($new_instance, $old_instance){
		$instance = array();
		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
		return $instance;
  }
  
  public function acf_history_limit_field(){
    acf_add_local_field_group(array(
      'key' => 'group_5ba111684098e',
      'title' => __('Search History Settings', 'ralfdocs'),
      'fields' => array(
        array(
          'key' => 'field_5ba111791e0a6',
          'label' => __('Search Term History Limit', 'ralfdocs'),
          'name' => 'search_term_history_limit',
          'type' => 'number',
          'instructions' => '',
          'required' => 0,
          'conditional_logic' => 0,
          'wrapper' => array(
            'width' => '25',
            'class' => '',
            'id' => '',
          ),
          'default_value' => 5,
          'placeholder' => '',
          'prepend' => '',
          'append' => '',
          'min' => '',
          'max' => '',
          'step' => 1,
        ),
      ),
      'location' => array(
        array(
          array(
            'param' => 'widget',
            'operator' => '==',
            'value' => 'usaidralf_search_history_widget',
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
}