<?php
if(!defined('ABSPATH')){ exit; }
?>

<h1 class="sector-title">
  <?php 
    $sector_icon = get_field('sector_icon', 'sectors_' . $current_sector->term_id);
    if($sector_icon): ?>
      <img src="<?php echo $sector_icon; ?>" class="img-circle img-responsive" alt="<?php echo esc_attr($current_sector->name) . ' ' . esc_attr__('Sector', 'ralfdocs'); ?>" style="background-color:<?php the_field('sector_color', 'sectors_' . $current_sector->term_id); ?>" />
  <?php endif; ?>
  <?php echo esc_html($current_sector->name); ?>
</h1>
