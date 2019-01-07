<?php
if(!defined('ABSPATH')){ exit; }
?>

<div class="loop-item-meta">
  <?php
    $article_meta = ralfdocs_get_article_meta($article_id);
    if($article_meta['article_type']['name'] != ''){
      $article_type_class = 'article-type';
      if($article_meta['article_type']['name'] == 'resource'){
        $article_type_class .= ' resource-article-type';
      }
      echo '<span class="' . $article_type_class . '" style="background-color:' . esc_html($article_meta['article_type']['color']) . ';">' . esc_html($article_meta['article_type']['name']) . '</span>';
    }

    if(array_key_exists('sectors', $article_meta) && !empty($article_meta['sectors'])){
      foreach($article_meta['sectors'] as $article_sector){
        echo '<a href="' . esc_url($article_sector['link']) . '" class="meta-btn btn-sector hidden-print" style="background-color:' . $article_sector['color'] . ';">' . esc_html($article_sector['name']) . '</a>';
      }
    }

    if(array_key_exists('related_activities_count', $article_meta) && $article_meta['related_activities_count'] > 0){
      echo '<a href="' . esc_url(get_permalink()) . '" class="meta-btn btn-activities hidden-print" style="background-color:' . esc_html(get_field('activities_color', 'option')) . ';">' . esc_html(sprintf(__('Activities (%d)', 'ralfdocs'), $article_meta['related_activities_count'])) . '</a>';
    }

    if(array_key_exists('related_impacts_count', $article_meta) && $article_meta['related_impacts_count'] > 0){
      echo '<a href="' . esc_url(get_permalink()) . '" class="meta-btn btn-impacts hidden-print" style="background-color:' . esc_html(get_field('impacts_color', 'option')) . ';">' . esc_html(sprintf(__('Impacts (%d)', 'ralfdocs'), $article_meta['related_impacts_count'])) . '</a>';
    }

    if(array_key_exists('resource_types', $article_meta) && !empty($article_meta['resource_types'])){
      foreach($article_meta['resource_types'] as $resource_type){
        echo '<a href="' . esc_url($resource_type['link']) . '" class="meta-btn btn-sector hidden-print" style="background-color:' . esc_html($resource_type['color']) . ';">' . esc_html($resource_type['name']) . '</a>';
      }
    }

    if(isset($resource_id)){
      $original_resource_url = get_field('original_resource_url', $resource_id);
      if($original_resource_url){
        echo '<a href="' . esc_url($original_resource_url) . '" class="meta-btn btn-sector resource-article-type" target="_blank">' . esc_html__('Source', 'ralfdocs') . '</a>';
      }
    }
  ?>
</div>