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
          //break up into do_action that builds the archive queries depending on variable passed.
          // variable might be sectors tax, resource_types tax or search.
          // Setup ajax pagination that then uses the do_action to build queries based on info
          // kept in hidden inputs maybe.  Filter ajaxing could use same do_action to build query
          // with new filter info, adding those to hidden inputs so ajax pagination continues work
          // with the filtered results.  Tabs will likely need to be ajax as well using the same
          // do_action.

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