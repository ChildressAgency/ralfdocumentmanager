<?php get_header(); ?>
<div class="page-content">
  <div class="container">
    <div class="row">
      <div class="col-sm-4 col-md-3">
        <?php get_sidebar(); ?>
      </div>
      <div class="col-sm-8 col-md-9">
        <main class="results-list">

          <?php do_action('ralfdocs_quick_select_results'); ?>

        </main>
      </div>
    </div>
  </div>
</div>
<?php get_footer();