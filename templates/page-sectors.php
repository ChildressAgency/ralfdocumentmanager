<?php
/**
 * Page template for displaying sector loop
 * all changes done with ajax
 * 
 * Can be overridden with custom template file here:
 * THEME_STYLESHEET_DIRECTORY/ralfdocs-template/page-sectors.php
 */
get_header(); ?>
<div class="page-content">
  <div class="container">
    <div class="row">
      <div class="col-sm-4 col-md-3">
        <?php get_sidebar(); ?>
      </div>
      <div class="col-sm-8 col-md-9">
        <?php
          $tax_terms = '';
          $sector_title = 'Sectors';
          if(isset($_GET['sector_term'])){
            $tax_terms = $_GET['sector_term'];

            $sector_term = get_term_by('id', $tax_terms, 'sectors');
            $sector_icon_url = get_field('sector_icon', 'sectors_' . $tax_terms);
            $sector_color = get_field('sector_color', 'sectors_' . $tax_terms);
            $sector_title = $sector_term->name;
          }
        ?>
        <div class="sector-icon-title">
          <div class="sector-icon-bg sector-icon-med" style="background-color:<?php echo $sector_color; ?>">
            <img src="<?php echo esc_url($sector_icon_url); ?>" class="img-responsive" alt="<?php echo esc_html($sector_title); ?> Sector" />
          </div>
          <h1 class="sector-title"><?php echo esc_html($sector_title); ?> Sector</h1>
        </div>
        <main class="results-list">
          <?php
            do_action('ralfdocs_build_archive_query', 'sectors', $tax_terms);
          ?>
        </main>
      </div>
    </div>
  </div>
</div>
<?php get_footer();