<?php
if(have_posts()){
  while(have_posts()){
    the_post();
    $article_id = get_the_ID(); ?>

    <div class="loop-item">
      <h2 class="loop-item-title">
        <a href="<?php echo esc_url(get_permalink($article_id)); ?>"><?php echo esc_html(get_the_title($article_id)); ?></a>
      </h2>
      <div class="loop-item-meta">
        <?php 
          if(is_search()){
            $searched_word = get_search_query(); 
            if(has_term($searched_word, 'priority_keywords', $article_id)){
              echo '<span class="priority"></span>';
            }
          }

          include ralfdocs_get_template('loop/article-meta.php');
        ?>
      </div>
    </div>
    
<?php
  }
  echo facetwp_display('pager');
}
else{
  echo '<p>Nothing found.</p>';
}
?>