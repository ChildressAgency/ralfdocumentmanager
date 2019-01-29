<?php
/**
 * Tabs for search results loops and taxonomy loop pages
 * This displays the Resources tab as active
 */
if(!defined('ABSPATH')){ exit; }
?>

<ul class="nav nav-pills nav-justified">
  <li><a href="<?php echo esc_url(add_query_arg('type', 'impacts-activities')); ?>"><?php echo esc_html__('Impacts / Activities', 'ralfdocs'); ?></a></li>
  <li class="active"><a href="#"><?php echo esc_html__('Resources', 'ralfdocs'); ?></a></li>
</ul>