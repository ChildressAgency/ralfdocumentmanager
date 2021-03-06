<?php
/**
 * Template for showing sector title on sector taxonomy archive page
 * and question tree pages.
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/loop/sector-title.php
 */
if(!defined('ABSPATH')){ exit; }
?>

<h1 class="sector-title">
  <?php 
    $sector_icon = get_field('sector_icon', 'sectors_' . $current_sector->term_id);
    if($sector_icon): ?>
      <div class="sector-icon-bg-small" style="background-color:<?php the_field('sector_color', 'sectors_' . $current_sector->term_id); ?>">
        <img src="<?php echo esc_url($sector_icon); ?>" class="img-circle img-responsive" alt="<?php echo esc_attr($current_sector->name) . ' ' . esc_attr__('Sector', 'ralfdocs'); ?>" />
      </div>
  <?php endif; ?>
  <?php echo esc_html($current_sector->name); ?>
</h1>