<?php
/**
 * Template for showing quick select form
 * called by quick select shortcode [ralfdocs_quick_select_form]
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/quick-select-form.php
 */
if(!defined('ABSPATH')){ exit; }
?>

<form action="<?php echo esc_html(home_url('quick-select-results')); ?>" method="post">
  <div class="factor-grid terms-grid">
    <?php 
      $impact_tags = get_terms(array(
        'taxonomy' => 'impact_tags',
        'count' => true,
        'number' => $num_filters,
        'hide_empty' => true,
        'orderby' => 'count',
        'order' => 'DESC'
      ));

      foreach($impact_tags as $impact_tag): ?>
        <div class="grid-item">
          <label class="factor-name">
            <input type="checkbox" name="factor[]" value="<?php echo $impact_tag->term_id; ?>" />
            <span><?php echo esc_html($impact_tag->name); ?></span>
          </label>
        </div>
    <?php endforeach; ?>
  </div>
  <div class="clearfix"></div>
  <div class="btns-inline">
    <!--<a href="#" class="btn-alt"><?php echo esc_html__('Load More', 'ralfdocs'); ?></a>-->
    <input type="submit" class="btn-main" value="<?php echo esc_html__('Search', 'ralfdocs'); ?>" />
  </div>
</form>