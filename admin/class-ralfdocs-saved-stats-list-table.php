<?php
// Exit if accessed directly
if (!defined('ABSPATH')){ exit; }

if(!class_exists('WP_List_Table')){
  require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class RALFDOCS_Saved_Stats_List_Table extends WP_List_Table{
  public function __construct(){
    parent::__construct([
      'singular' => esc_html__('Saved to Report Statistics', 'ralfdocs'),
      'plural' => esc_html__('Saved to Report Statistics', 'ralfdocs'),
      'ajax' => false
    ]);
  }

  protected function get_saved_stats($per_page = 25, $page_number = 1){
    global $wpdb;

    $sql = 'SELECT article_id, COUNT(*) AS saved_count FROM saved_reports';

    if(!empty($_REQUEST['time_period']) && $_REQUEST['time_period'] == 'ninety_days'){
      $sql .= ' WHERE saved_date >= DATE_ADD(NOW(), INTERVAL -90 DAY)';
    }
    
    $sql .= ' GROUP BY article_id';

    if(!empty($_REQUEST['orderby'])){
      $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
      $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' DESC';
    }

    $sql .= ' LIMIT ' . $per_page;
    $sql .= ' OFFSET ' . ($page_number -1) * $per_page;

    $result = $wpdb->get_results($sql, 'ARRAY_A');

    //change article id to title and link - still called article_id in array
    $result_count = count($result);
    for($r = 0; $r < $result_count; $r++){
      $article_title = get_the_title($result[$r]['article_id']);
      $article_link = get_permalink($result[$r]['article_id']);

      $result[$r]['article_id'] = '<a href="' . esc_url($article_link) . '" target="_blank">' . esc_html($article_title) . '</a>';
    }

    return $result;
  }

  protected function saved_to_report_count(){
    global $wpdb;

    $sql = 'SELECT article_id FROM saved_reports';

    if(!empty($_REQUEST['time_period']) && $_REQUEST['time_period'] == 'ninety_days'){
      $sql .= ' WHERE saved_date >= DATE_ADD(NOW(), INTERVAL -90 DAY)';
    }

    $sql .= ' GROUP BY article_id';

    $report_count = $wpdb->get_results($sql, 'ARRAY_N');

    return count($report_count);
  }

  public function no_items(){
    esc_html_e('No saved reports were found.', 'ralfdocs');
  }

  public function column_name($item){
    $title = '<strong>' . esc_html($item['name']) . '</strong>';

    return $title;
  }

  public function column_default($item, $column_name){
    switch($column_name){
      case 'article_id':
      case 'saved_count':
        return $item[$column_name];
      default:
        return print_r($item, true);
    }
  }

  public function get_columns(){
    $columns = array(
      'article_id' => esc_html__('Article Name', 'ralfdocs'),
      'saved_count' => esc_html__('Number of Times Saved to Report', 'ralfdocs')
    ); 

    return $columns;
  }

  public function get_sortable_columns(){
    $sortable_columns = array(
      'article_id' => array('article_id', true),
      'saved_count' => array('saved_count', true)
    );

    return $sortable_columns;
  }

  protected function get_views(){
    $status_links = array(
      'all' => '<a href="' . esc_url(get_admin_url('', 'index.php?page=saved-statistics-submenu-page&time_period=all')) . '">' . esc_html__('All', 'ralfdocs') . '</a>',
      'ninety_days' => '<a href="' . esc_url(get_admin_url('', 'index.php?page=saved-statistics-submenu-page&time_period=ninety_days')) . '">' . esc_html__('Last 90 Days', 'ralfdocs') . '</a>'
    );

    return $status_links;
  }

  public function prepare_items(){
    $this->_column_headers = $this->get_column_info();

    $per_page = $this->get_items_per_page('saved_stats_per_page', 25);
    $current_page = $this->get_pagenum();
    $total_items = $this->saved_to_report_count();

    $this->set_pagination_args([
      'total_items' => $total_items,
      'per_page' => $per_page
    ]);

    $this->items = $this->get_saved_stats($per_page, $current_page);
  }
}