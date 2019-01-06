<?php
if(!defined('ABSPATH')){ exit; }

$impact_id = get_the_ID();
$sectors = get_the_terms($impact_id, 'sectors'); ?>

<article class="ralf-article">
  <header class="result-header">
    <h1><?php the_title(); ?></h1>
    <div class="result-meta">
      <?php do_action('ralfdocs_article_meta', $impact_id); ?>
    </div>
  </header>

  <section class="result-content">
    <?php the_content(); ?>
  </section>
  <?php echo do_shortcode('[report_button]'); ?>

  <?php do_action('ralfdocs_related_activities', $impact_id, 'impacts'); ?>

  <?php do_action('ralfdocs_related_resources', $impact_id); ?>
</article>