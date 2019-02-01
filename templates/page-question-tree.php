<?php
/**
 * Template for start of Question Tree
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-templates/page-question-tree.php
 */

get_header();

$page_id = get_the_ID();
$bg_img_id = get_post_meta($page_id, 'background_image', true);
$bg_img = wp_get_attachment_image_src($bg_img_id, 'full');
$bg_img_url = $bg_img[0];
?>

<div id="question-tree" style="background-image:url(<?php echo esc_url($bg_img_url); ?>); <?php echo esc_html(get_field('background_image_css')); ?>">
  <div class="container">
    <?php if(have_posts()): while(have_posts()): the_post(); ?>
      <article>
        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>
        <a href="#" id="qt-start" class="btn-main">Start<i class="glyphicon glyphicon-refresh no-show"></i></a>
      </article>
    <?php endwhile; endif; ?>
  </div>
  <div class="full-page-overlay"></div>
</div>
<?php get_footer();