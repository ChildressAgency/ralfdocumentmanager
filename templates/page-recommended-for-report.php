<?php
/**
 * Template for displaying recommended articles for report based on question tree
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/page-recommended-for-report.php
 */
get_header();

$current_sector_id = $_GET['sector'];
$current_sector = get_term($current_sector_id, 'sectors');
$sector_image = get_field('question_tree_background_image', 'sectors_' . $current_sector_id);
$sector_image_css = get_field('question_tree_background_image_css', 'sectors_' . $current_sector_id);
?>

<div id="question-tree" style="background-image:url(<?php echo esc_url($sector_image['url']); ?>); <?php echo esc_html($sector_image_css); ?>">
  <div class="container">
    <?php do_action('ralfdocs_back_button'); ?>
    <main class="results-list">
      <?php include ralfdocs_get_template('loop/sector-title.php'); ?>
      <h1><?php echo esc_html__('Recommended Activities, Associated Impacts and Resources', 'ralfdocs'); ?></h1>

      <?php
        $article_ids = array();
        if(isset($_GET['article_ids'])){
          $article_ids = ralfdocs_convert_to_int_array($_GET['article_ids']);

          if($article_ids && $article_ids[0] != 0){
            $recommended_articles = new WP_Query(array(
              'post_type' => array('activities', 'impacts', 'resources'),
              'posts_per_page' => -1,
              'post__in' => $article_ids,
              'orderby' => 'post_type'
            ));

            if($recommended_articles->have_posts()){
              while($recommended_articles->have_posts()){
                $recommended_articles->the_post();
                do_action('ralfdocs_view_report_loop');
              }
            } wp_reset_postdata();
          }
        }
        else{
          echo '<p>' . esc_html__('Sorry, we could not find any recommended articles.', 'ralfdocs') . '</p>';
        }
      ?>
    </main>
  </div>
  <div class="questions-start-over">
    <a href="<?php echo esc_url(home_url('question-tree')); ?>">start over</a>
  </div>
  <div class="full-page-overlay"></div>
</div>
<?php get_footer();