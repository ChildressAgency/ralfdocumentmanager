<?php 
/**
 * Template for displaying resource-types taxonomy archive
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/taxonomy-resource_types.php
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
            $current_resource_type = get_queried_object();
            include ralfdocs_get_template('loop/resource-type-title.php');

            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            $resources = new WP_Query(array(
              'post_type' => 'resources',
              'posts_per_page' => 10,
              'paged' => $paged,
              'tax_query' => array(
                array(
                  'taxonomy' => 'resource_types',
                  'field' => 'term_id',
                  'terms' => $current_resource_type->term_id
                )
              )
            ));

            if(!empty($resources->posts)){
              foreach($resources->posts as $post){
                setup_postdata($post);
                $article_id = $post->ID;
                include ralfdocs_get_template('loop/loop-item.php');
              }
              wp_reset_postdata();
              ralfdocs_pagination($resources);
            }
            else{
              include ralfdocs_get_template('loop/no-results.php');
            }
          ?>

        </main>
      </div>
    </div>
  </div>
</div>
<?php get_footer();