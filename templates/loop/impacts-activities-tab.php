<?php
/**
 * Tabs for search results loops and taxonomy loop pages
 * This displays the Impacts / Activities tab as active
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/loop/impacts-activities-tab.php
 * 
 */
if(!defined('ABSPATH')){ exit; }

if(is_search()){
  $tab_link = add_query_arg(array('s' => $searched_word, 'type' => 'resources'), home_url());
}
else{
  $tab_link = add_query_arg(array('type' => 'resources', 'paged' => 1));
}
?>

<ul class="nav nav-pills nav-justified">
  <li class="active"><a href="#"><?php echo esc_html__('Impacts / Activities', 'ralfdocs'); ?></a></li>
  <li><a href="<?php echo esc_url($tab_link); ?>"><?php echo esc_html__('Resources', 'ralfdocs'); ?></a></li>
</ul>