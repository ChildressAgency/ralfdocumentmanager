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

//if(is_search()){
if($archive_type == 'search'){
  //$tab_link = add_query_arg(array('s' => $searched_word, 'type' => 'resources'), home_url());
  //var_dump($impacts_activities);
  $impacts_count = $impacts_activities->found_posts;
  $ia_post_type = 'impacts,activities';
}
else{
  //$tab_link = add_query_arg(array('type' => 'resources', 'paged' => 1), $ajax_location);
  $impacts_count = $impacts->found_posts;
  $ia_post_type = 'impacts';
}
$resources_count = $resources->found_posts;

?>

<ul class="nav nav-pills nav-justified">
  <li class="active"><a href="#" class="post-type-tab" data-post_type="<?php echo $ia_post_type; ?>"><?php echo esc_html__('Impacts / Activities', 'ralfdocs') . ' (' . $impacts_count . ')'; ?></a></li>
  <li><a href="#" class="post-type-tab" data-post_type="resources"><?php echo esc_html__('Resources', 'ralfdocs') . ' (' . $resources_count . ')'; ?></a></li>
</ul>