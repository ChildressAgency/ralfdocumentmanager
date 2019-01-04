<?php 
if(!defined('ABSPATH')){ exit; }

$resource_id = get_the_ID(); ?>

<article class="ralf-article">
  <header class="result-header">
    <h1><?php the_title(); ?></h1>
    <div class="result-meta">
      <?php do_action('ralfdocs_article_meta', $resource_id); ?>
    </div>
  </header>

  <section class="result-content">
    <?php the_content(); ?>
  </section>
  <?php echo do_shortcode('[report_button]'); ?>

  <?php do_action('ralfdocs_related_activities', $resource_id, 'resources'); ?>

  <?php do_action('ralfdocs_resources_related_impacts', $resource_id); ?>
</article>
