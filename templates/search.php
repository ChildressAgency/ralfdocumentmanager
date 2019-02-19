<?php 
/**
 * Template for displaying ralfdocs search results
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/search.php
 */
get_header(); ?>
  <div class="page-content">
    <div class="container">
      <div class="row">
        <div class="col-sm-4 col-md-3">
          <?php get_sidebar(); ?>
        </div>
        <div class="col-sm-8 col-md-9">
          <?php $searched_word = get_search_query(); ?>
          <h1><?php printf(esc_html__('Search results for "%s"', 'ralfdocs'), $searched_word); ?></h1>
          <main class="results-list">

            <?php do_action('ralfdocs_build_archive_query', 'search', null, null, null, null, null, $searched_word); ?>

          </main>
        </div>
      </div>
    </div>
  </div>
<?php get_footer();