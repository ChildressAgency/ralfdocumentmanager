<?php 
/**
 * Template for displaying resource-types taxonomy archive
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/taxonomy-resource_types.php
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
            $current_resource_type = get_queried_object();
            include ralfdocs_get_template('loop/resource-type-title.php');

            do_action('ralfdocs_build_archive_query', 'resource_types', $current_resource_type->term_id);
          ?>

        </main>
      </div>
    </div>
  </div>
</div>
<?php get_footer();