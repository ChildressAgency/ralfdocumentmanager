<?php get_header(); ?>
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
                  do_action('ralfdocs_activities_loop');
                }
              }
            ?>

          </main>
        </div>
      </div>
    </div>
  </div>
<?php get_footer(); ?>