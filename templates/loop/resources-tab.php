<?php
/**
 * Tabs for search results loops and taxonomy loop pages
 * This displays the Resources tab as active
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/loop/resources-tab.php
 */
if(!defined('ABSPATH')){ exit; }

if(is_search()){
  $tab_link = add_query_arg(array('s' => $searched_word, 'type' => 'impacts-activities'), home_url());
  $impacts_count = $impacts_activities->found_posts;
}
else{
  $tab_link = add_query_arg(array('type' => 'impacts-activities', 'paged' => 1), $ajax_location);
  $impacts_count = $impacts->found_posts;
}
$resources_count = $resources->found_posts;
?>

<ul class="nav nav-pills nav-justified">
  <li><a href="<?php echo esc_url($tab_link); ?>"><?php echo esc_html__('Impacts / Activities', 'ralfdocs') . ' (' . $impacts_count . ')'; ?></a></li>
  <li class="active"><a href="#"><?php echo esc_html__('Resources', 'ralfdocs') . ' (' . $resources_count . ')'; ?></a></li>
</ul>