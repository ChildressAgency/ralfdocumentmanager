<?php 
/**
 * Template for displaying sector loop
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/taxonomy-sectors.php
 */
get_header(); ?>
<div class="page-content">
  <div class="container">
    <div class="row">
      <div class="col-sm-4 col-md-3">
        <?php get_sidebar(); ?>
      </div>
      <div class="col-sm-8 col-md-9">
        <main class="results-list">
          <?php
            $current_sector = get_queried_object();

            include ralfdocs_get_template('loop/sector-title.php');

            do_action('ralfdocs_build_archive_query', 'sectors', $current_sector->term_id);
          ?>

        </main>
      </div>
    </div>
  </div>
</div>
<?php get_footer();