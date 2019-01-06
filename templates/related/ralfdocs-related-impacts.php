<?php
if(!defined('ABSPATH')){ exit; }

$impacts_by_sector = ralfdocs_get_impacts_by_sector($impact_ids); ?>

<section class="impact-by-sector">
  <h2><?php echo esc_html__('IMPACT BY SECTOR', 'ralfdocs'); ?><span class="dashicons dashicons-excerpt-view" data-toggle="tooltip" data-position="top" title="<?php echo esc_attr__('Expand All', 'ralfdocs'); ?>"></span></h2>
  <div class="panel-group" id="impacts-accordion" role="tablist" aria-multiselectable="true">
    <?php 
        $i = 0;
      foreach($impacts_by_sector as $sector):
        $acf_sector_id = 'sectors_' . $sector['sector_id'];
        foreach($sector['impacts'] as $impact): ?>

          <div class="panel panel-default">
            <div class="panel-heading" role="tab" id="impact-title<?php echo $i; ?>">
              <h3 class="panel-title">
                <a href="<?php echo esc_url(get_permalink($impact->impact_id)); ?>" class="sector-popout hidden-print" target="_blank">
                  <?php echo esc_html($impact->impact_title); ?>
                  <span class="dashicons dashicons-external" data-toggle="tooltip" data-position="top" title="<?php echo esc_attr__($sector['sector_name']); ?>"></span>
                </a>
              </h3>
              <div class="impact-by-sector-meta">
                <a href="#impact<?php echo $report_article_id . '-' . $i; ?>" class="meta-btn report-expand hidden-print collapsed" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="impact<?php echo $i; ?>"></a>
                <?php
                  //get all sectors for this impact to use for meta btns
                  do_action('ralfdocs_article_meta', $impact->impact_id);
                ?>
              </div>
            </div>
            <div class="clearfix"></div>
            <div id="impact<?php echo $report_article_id . '-' . $i; ?>" class="panel-collapse collapse print-visible" role="tabpanel" aria-labelledby="impact-title<?php echo $i; ?>">
              <div class="panel-body">
                <?php echo wp_kses_post($impact->impact_description); ?>
              </div>
            </div>
          </div>
        
      <?php $i++; endforeach; ?>
    <?php endforeach; ?>
  </div>
</section>