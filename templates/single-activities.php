<?php 
/**
 * Template for viewing single Activities
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/single-activities.php
 */
get_header(); ?>
  <div class="page-content">
    <div class="container">
      <div class="row">
      <div class="col-sm-4 col-md-3">
          <?php get_sidebar(); ?>
        </div>
        <div class="col-sm-8 col-md-9">
          <main class="result">
            <?php do_action('ralfdocs_back_button'); ?>

            <?php
              if(have_posts()){
                while(have_posts()){
                  the_post();
                  //do_action('ralfdocs_activities_loop');
                  $activity_id = get_the_ID(); ?>

                  <article class="ralf-article">
                    <header class="result-header">
                      <h1><?php the_title(); ?></h1>
                      <div class="results-meta">
                        <?php 
                          $article_id = $activity_id;
                          include ralfdocs_get_template('loop/article-meta.php'); 
                        ?>
                      </div>
                    </header>

                    <section class="result-content">
                      <div class="activity-description">
                        <?php the_content(); ?>
                      </div>

                      <?php if(get_field('conditions')): ?>
                        <div class="activity-conditions">
                          <h2><?php echo esc_html__('POTENTIAL CONDITIONS', 'ralfdocs'); ?></h2>
                          <?php echo wp_kses_post(get_field('conditions')); ?>
                        </div>
                      <?php endif; ?>
                    </section>

                    <?php 
                      echo do_shortcode('[report_button]');
                     
                      $impact_ids = get_field('related_impacts', false, false);
                      do_action('ralfdocs_related_impacts', $impact_ids);

                      do_action('ralfdocs_related_resources', $activity_id);
                    ?>
                    
                  </article>

                <?php
                }
              }
            ?>

          </main>
        </div>
      </div>
    </div>
  </div>
<?php get_footer();