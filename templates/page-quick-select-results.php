<?php 
/**
 * Template for showing the results from Quick Select options
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/page-quick-select-results.php
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
            if(isset($_POST['factor']) && !empty($_POST['factor'])){
              $impact_tag_ids = $_POST['factor'];
              $impact_tag_ids = array_map('intval', $impact_tag_ids);

              $impact_tag_names = [];
              foreach($impact_tag_ids as $index => $impact_tag){
                $term = get_term_by('id', $impact_tag, 'impact_tags');
                $impact_tag_names[] = $term->name;
              }
              $impact_tag_names = implode(', ', $impact_tag_names);
              echo '<h1>' . sprintf(esc_html__('Showing results for "%s"', 'ralfdocs'), $impact_tag_names) . '</h1>';

              $paged = get_query_var('paged') ? get_query_var('paged') : 1;
              $factors = new WP_Query(array(
                'post_type' => array('impacts', 'activities'),
                'paged' => $paged,
                'tax_query' => array(
                  array(
                    'taxonomy' => 'impact_tags',
                    'field' => 'term_id',
                    'terms' => $impact_tag_ids
                  )
                )
              ));

              if(!empty($factors->posts)){
                foreach($factors->posts as $post){
                  setup_postdata($post);
                  $article_id = $post->ID;
                  include ralfdocs_get_template('loop/loop-item.php');
                }
                wp_reset_postdata();
                ralfdocs_pagination($factors);
              }
              else{
                include ralfdocs_get_template('loop/no-results.php');
              }
            }
            else{
              echo '<p>' . esc_html__('You did not select any factors.', 'ralfdocs') . '</p>';
            }
            ?>

        </main>
      </div>
    </div>
  </div>
</div>
<?php get_footer();