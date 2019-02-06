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
          <main class="results-list">
            <?php $searched_word = get_search_query(); ?>
            <h1><?php printf(esc_html__('Search results for "%s"', 'ralfdocs'), $searched_word); ?></h1>

            <?php
              $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

              //this will make sure the count on the tabs are correct despite pagination
              if(isset($_GET['type']) && $_GET['type'] == 'resources'){
                $resources_paged = $paged;
                $impacts_paged = 1;
              }
              else{
                $resources_paged = 1;
                $impacts_paged = $paged;
              }

              $impacts_activities = new SWP_Query(array(
                'post_type' => array('impacts', 'activities'),
                's' => $searched_word,
                'engine' => 'default',
                'page' => $impacts_paged,
                'fields' => 'all'
              ));

              $resources = new SWP_Query(array(
                'post_type' => 'resources',
                's' => $searched_word,
                'engine' => 'default',
                'page' => $resources_paged,
                'fields' => 'all'
              ));
              
              
              if(isset($_GET['type']) && $_GET['type'] == 'resources'){
                // user clicked the resources tab
                include ralfdocs_get_template('loop/resources-search-results.php');
              }
              else{
                /**
                 * initial results - resources tab not clicked
                 * 
                 * if $impacts_activities has no results then default to the resources tab,
                 * unless its also empty - then just display the default tab.
                 */
                if(empty($impacts_activities->posts) && !empty($resources->posts)){
                  include ralfdocs_get_template('loop/resources-search-results.php');
                }
                else{
                  include ralfdocs_get_template('loop/impacts-activities-search-results.php');
                }
              }
            ?>

          </main>
        </div>
      </div>
    </div>
  </div>
<?php get_footer();