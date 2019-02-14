<?php 
/**
 * Template for showing single Resources page
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/single-resources.php
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
                //do_action('ralfdocs_resources_loop');
                $resource_id = get_the_ID(); ?>

                <article class="ralf-article">
                  <header class="result-header">
                    <h1><?php the_title(); ?></h1>
                    <div class="result-meta">
                      <?php
                        $article_id = $resource_id;
                        include ralfdocs_get_template('loop/article-meta.php');
                      ?>
                    </div>
                  </header>

                  <section class="result-content">
                    <?php the_content(); ?>
                  </section>
                  <?php 
                    echo do_shortcode('[report_button]');

                    do_action('ralfdocs_related_activities', $resource_id, 'resources');

                    do_action('ralfdocs_resources_related_impacts', $resource_id);
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