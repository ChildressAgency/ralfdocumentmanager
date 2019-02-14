<?php
/**
 * Template for displaying no results
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/loop/no-results.php
 */
if(!defined('ABSPATH')){ exit; }
?>

<div class="nothing-found">
  <h2><?php echo esc_html__('Sorry, nothing was found. Try choosing a sector to get started.', 'ralfdocs'); ?></h2>
  <div class="row">
    <?php
      $sc = 0;
      $sectors = get_terms(array('taxonomy' => 'sectors', 'parent' => 0, 'orderby' => 'name'));
      
      foreach($sectors as $sector):
        $acf_sector_id = 'sectors_' . $sector->term_id;
        $sector_icon_url = get_field('sector_icon', $acf_sector_id);
        $sector_color = get_field('sector_color', $acf_sector_id);

        if($sc%2==0){ echo '<div class="clearfix visible-sm-block"></div>'; }
        if($sc%3==0){ echo '<div class="clearfix hidden-xs hidden-sm"></div>'; } ?>

        <div class="col-sm-6 col-md-4">
          <!--<a href="<?php //echo esc_url(get_term_link($sector->term_id, 'sectors')); ?>" class="sector-icon">-->
          <a href="<?php echo esc_url(add_query_arg('sector_term', $sector->term_id, home_url('sectors'))); ?>" class="sector-icon">
            <img src="<?php echo esc_url($sector_icon_url); ?>" class="img-circle img-responsive center-block" alt="<?php echo esc_html($sector->name); ?> Sector" style="background-color:<?php echo $sector_color; ?>;" />
            <h3><?php echo esc_html($sector->name); ?></h3>
          </a>
        </div>

    <?php $sc++; endforeach; ?>
  </div>
</div>