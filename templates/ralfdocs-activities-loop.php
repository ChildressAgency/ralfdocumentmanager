<?php
if(!defined('ABSPATH')){ exit; }

$activity_id = get_the_ID(); ?>

<article class="ralf-article">
  <header class="result-header">
    <h1><?php the_title(); ?></h1>
    <div class="results-meta">
      <?php do_action('ralfdocs_article_meta', $activity_id); ?>
    </div>
  </header>

  <section class="result-content">
    <div class="activity-description">
      <p><?php the_content(); ?></p>
    </div>
    <?php if(get_field('conditions')): ?>
      <div class="activity-conditions">
        <h2><?php echo esc_html__('POTENTIAL CONDITIONS', 'ralfdocs'); ?></h2>
        <?php echo wp_kses_post(get_field('conditions')); ?>
      </div>
    <?php endif; ?>
  </section>
    <?php echo do_shortcode('[report_button]'); ?>
  
  <?php 
    $impact_ids = get_field('related_impacts', false, false);
    if(!empty($impact_ids)){
      do_action('ralfdocs_related_impacts', $impact_ids);
    }
  ?>

    <section class="related">
      <h3><?php echo esc_html__('Related Resources', 'ralfdocs'); ?></h3>
      <?php 
        $related_resources = get_field('related_resources', $activity_id);
        if($related_resources){
          do_action('ralfdocs_related_resources', $related_resources);
        }
        else{
          echo '<p>' . esc_html__('No related Resources', 'ralfdocs') . '</p>';
        }
      ?>
    </section>
</article>
