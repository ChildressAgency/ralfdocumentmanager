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
          
            $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

            // this will make sure the count on the tabs are correct despite pagination
            if(isset($_GET['type']) && $_GET['type'] == 'resources'){
              $resources_paged = $paged;
              $impacts_paged = 1;
            }
            else{
              $resources_paged = 1;
              $impacts_paged = $paged;
            }

            $impacts = new WP_Query(array(
              'post_type' => 'impacts',
              'paged' => $impacts_paged,
              'tax_query' => array(
                array(
                  'taxonomy' => 'sectors',
                  'field' => 'term_id',
                  'terms' => $current_sector->term_id
                )
              )
            ));

            $resources = new WP_Query(array(
              'post_type' => 'resources',
              'paged' => $resources_paged,
              'tax_query' => array(
                array(
                  'taxonomy' => 'sectors',
                  'field' => 'term_id',
                  'terms' => $current_sector->term_id
                )
              )
            ));

            if(isset($_GET['type']) && $_GET['type'] == 'resources'){
              //user clicked the resources tab
              include ralfdocs_get_template('loop/sector-resources-loop.php');
            }
            else{
              /**
               * initial results - resources tab not clicked
               * 
               * if $impacts has no results then default to the resources tab,
               * unless its also empty - then just display the default tab.
               */
              if(empty($impacts->posts) && !empty($resources->posts)){
                include ralfdocs_get_template('loop/sector-resources-loop.php');
              }
              else{
                include ralfdocs_get_template('loop/sector-impacts-loop.php');
              }
            }
          ?>

        </main>
      </div>
    </div>
  </div>
</div>
<?php get_footer();