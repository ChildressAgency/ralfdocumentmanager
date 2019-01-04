<?php get_header(); ?>
<div class="page-content">
  <div class="container">
    <div class="row">
      <div class="col-sm-4 col-md-3">
        <?php get_sidebar(); ?>
      </div>
      <div class="col-sm-8 col-md-9">
        <main class="result">
          <div class="go-back">
            <a href="javascript:history.back(-1);"><?php _e('BACK', 'usaidralf'); ?></a>
          </div>
          <?php 
            if(have_posts()): while(have_posts()): 
              the_post(); 
              $resource_id = get_the_ID(); ?>

              <article class="ralf-article">
                <header class="result-header">
                  <h1><?php the_title(); ?></h1>
                  <div class="result-meta">
                    <?php usaidralf_show_article_meta('resources', $resource_id); ?>
                  </div>
                </header>

                <section class="result-content">
                  <?php the_content(); ?>
                </section>
                <?php echo do_shortcode('[report_button]'); ?>

                <section class="related">
                  <h3><?php _e('Related Activities.', 'usaidralf'); ?></h3>
                  <?php 
                    $related_activities = usaidralf_get_related_activities($resource_id);
                    if($related_activities->have_posts()): ?>
                      <ul>
                        <?php while($related_activities->have_posts()): $related_activities->the_post(); ?>
                          <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                        <?php endwhile; ?>
                      </ul>
                  <?php else: ?>
                    <p><?php _e('No related Activities', 'usaidralf'); ?></p>
                  <?php endif; wp_reset_postdata(); ?>
                </section>

                <section class="related">
                  <h3><?php _e('Related Impacts', 'usaidralf'); ?></h3>
                  <?php
                    $related_impacts = usaidralf_get_related_impacts($resource_id);
                    if($related_impacts->have_posts()): ?>
                      <ul>
                        <?php while($related_impacts->have_posts()): $related_impacts->the_post(); ?>
                          <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                        <?php endwhile; ?>
                      </ul>
                  <?php else: ?>
                    <p><?php _e('No related Impacts.', 'usaidralf'); ?></p>
                  <?php endif; wp_reset_postdata(); ?>
                </section>
              </article>

          <?php endwhile; endif; ?>
        </main>
      </div>
    </div>
  </div>
</div>
<?php get_footer(); ?>