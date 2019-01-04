<?php
if(!defined('ABSPATH')){ exit; }

$report_article_id = get_the_ID(); ?>

<article class="ralf-article">
  <header class="result-header">
    <h2 class="article-heading"><?php the_title(); ?></h2>
    <div class="results-meta">
      <a href="#article_id-<?php echo $report_article_id ?>" class="meta-btn report-expand hidden-print collapsed" role="button" data-toggle="collapse" aria-expanded="false" aria-controls="article_id-<?php echo $report_article_id; ?>" class="collapsed"></a>
      <span class="article-handle"></span>
      <?php do_action('ralfdocs_article_meta', $report_article_id); ?>
    </div>
  </header>

  <div id="article_id-<?php echo $report_article_id; ?>" class="collapse-container panel-collapse collapse print-visible" role="tab-panel" aria-labelledby="article-heading">
    <section class="result-content">
      <div class="activity-description">
        <p><?php the_content(); ?></p>
      </div>
      <?php if(get_field('conditions')): ?>
        <div class="activity-conditions">
          <h2><?php echo esc_html__('CONDITIONS', 'ralfdocs'); ?></h2>
          <?php the_field('conditions'); ?>
        </div>
      <?php endif; ?>
    </section>

    <?php
      $impact_ids = get_field('related_impacts', false, false);
      if(!empty($impact_ids)){
        do_action('ralfdocs_related_impacts', $impact_ids);
      }
    
      echo do_shortcode('[report_button]'); 
    ?>
  </div><!-- end .collapse-container -->
  
</article>