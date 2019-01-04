<?php
if(!defined('ABSPATH')){ exit; }
?>

<h1 class="sector-title">
  <?php
    $resource_type_icon = get_field('resource_type_icon', 'resource_types_' . $current_resource_type->term_id);
    if($resource_type_icon): ?>
      <img src="<?php echo esc_url($resource_type_icon); ?>" class="img-circle img-responsive" alt="<?php echo esc_attr($current_resource_type->name) . ' ' . esc_html__('Resource Type', 'ralfdocs'); ?>" style="background-color:<?php the_field('resource_type_color', 'resource_types_' . $current_resource_type->term_id); ?>;" />
  <?php endif; ?>
  <?php echo esc_html($current_resource_type->name); ?>
</h1>
