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

            <ul class="nav nav-pills nav-justified" role="tablist">
              <li role="presentation" class="active"><a href="#impacts-activities" aria-controls="impacts-activities" role="tab" data-toggle="tab"><?php echo esc_html__('Impacts / Activities', 'ralfdocs'); ?></a></li>
              <li role="presentation"><a href="#resources" aria-controls="resources" role="tab" data-toggle="tab"><?php echo esc_html__('Resources', 'ralfdocs'); ?></a></li>
            </ul>

            <div class="tab-content">
              <div id="impacts-activities" class="tab-pane fade in active" role="tabpanel">

                <?php do_action('ralfdocs_impacts_activities_search_results', $searched_word); ?>

              </div>

              <div id="resources" class="tab-pane fade" role="tabpanel">

                <?php do_action('ralfdocs_resources_search_results', $searched_word); ?>

              </div>
            </div>

          </main>
        </div>
      </div>
    </div>
  </div>
<?php get_footer();