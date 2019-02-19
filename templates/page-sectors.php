<?php
/**
 * Page template for displaying sector loop
 * all changes done with ajax
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-template/page-sectors.php
 */
get_header(); ?>
<div class="page-content">
  <div class="container">
    <div class="row">
      <div class="col-sm-4 col-md-3">
        <?php get_sidebar(); ?>
      </div>
      <div class="col-sm-8 col-md-9">
        <h1 class="sector-title">Sectors</h1>
        <main class="results-list">
          <?php
            $tax_terms = ''; 
            if(isset($_GET['sector_term'])){
              $tax_terms = $_GET['sector_term'];
            }
            do_action('ralfdocs_build_archive_query', 'sectors', $tax_terms);
          ?>
        </main>
      </div>
    </div>
  </div>
</div>
<?php get_footer();