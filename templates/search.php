<?php get_header(); ?>
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
              $impacts_activities = new SWP_Query(array(
                'post_type' => array('impacts', 'activities'),
                's' => $searched_word,
                'engine' => 'default',
                'posts_per_page' => 10,
                'page' => $paged,
                'fields' => 'all'
              ));

              $resources = new WP_Query(array(
                'post_type' => 'resources',
                's' => $searched_word,
                'engine' => 'default',
                'posts_per_page' => 10,
                'page' => $paged,
                'fields' => 'all'
              ));
              
              include ralfdocs_get_template('loop/tabs.php');

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