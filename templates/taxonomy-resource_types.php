<?php get_header(); ?>
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
            do_action('ralfdocs_resource_type_title', $current_resource_type);

            do_action('ralfdocs_resource_type_loop', $current_resource_type);
          ?>
        </main>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>