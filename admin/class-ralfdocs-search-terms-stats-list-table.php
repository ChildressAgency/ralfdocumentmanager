<?php
// Exit if accessed directly
if (!defined('ABSPATH')){ exit; }

if(!class_exists('WP_List_Table')){
  require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class RALFDOCS_Search_Terms_Stats_List_Table extends WP_List_Table{
  public function __construct(){
    parent::__construct([
      'singular' => esc_html__('Search Terms Statistics', 'ralfdocs'),
      'plural' => esc_html__('Search Terms Statistics', 'ralfdocs'),
      'ajax' => false
    ]);
  }

  protected function get_search_term_stats($per_page = 25, $page_number = 1){
    global $wpdb;

    $sql = "SELECT query AS searched_term, COUNT(*) AS searched_count FROM {$wpdb->prefix}swp_log";

    $sql .= ' WHERE query NOT REGEXP "[()^;<>/\'\"!]"';

    if(!empty($_REQUEST['time_period']) && $_REQUEST['time_period'] == 'ninety_days'){
      $sql .= ' AND tstamp >= DATE_ADD(NOW(), INTERVAL -90 DAY)';
    }

    $sql .= ' GROUP BY query';

    if(!empty($_REQUEST['orderby'])){
      $sql .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
      $sql .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' DESC';
    }

    $sql .= ' LIMIT ' . $per_page;
    $sql .= ' OFFSET ' . ($page_number - 1) * $per_page;

    $result = $wpdb->get_results($sql, 'ARRAY_A');

    //link search term
    $result_count = count($result);
    for($r = 0; $r < $result_count; $r++){
      $searched_term_link = '<a href="' . esc_url(add_query_arg('s', $result[$r]['searched_term'], home_url())) . '" target="_blank">' . esc_html($result[$r]['searched_term']) . '</a>';

      $result[$r]['searched_term'] = $searched_term_link;
    }

    return $result;
  }

  public function searched_terms_count(){
    global $wpdb;

    $sql = "SELECT query FROM {$wpdb->prefix}swp_log";

    if(!empty($_REQUEST['time_period']) && $_REQUEST['time_period'] == 'ninety_days'){
      $sql .= ' WHERE tstamp >= DATE_ADD(NOW(), INTERVAL -90 DAY)';
    }

    $sql .= ' GROUP BY query';

    $search_terms_count = $wpdb->get_results($sql, 'ARRAY_N');

    return count($search_terms_count);
  }

  public function no_items(){
    esc_html_e('No search terms were found.', 'ralfdocs');
  }

  public function column_name($item){
    $title = '<strong>' . esc_html($item['name']) . '</strong>';

    return $title;
  }

  public function column_default($item, $column_name){
    switch($column_name){
      case 'searched_term':
      case 'searched_count':
        return $item[$column_name];
      default:
        return print_r($item, true);
    }
  }

  public function get_columns(){
    $columns = array(
      'searched_term' => esc_html__('Searched Term', 'ralfdocs'),
      'searched_count' => esc_html__('Number of Times Searched', 'ralfdocs')
    );

    return $columns;
  }

  public function get_sortable_columns(){
    $sortable_columns = array(
      'searched_term' => array('searched_term', true),
      'searched_count' => array('searched_count', true)
    );

    return $sortable_columns;
  }

  protected function get_views(){
    $status_links = array(
      'all' => '<a href="' .esc_url(get_admin_url('', 'index.php?page=search-term-stats-submenu-page&time_period=all')) . '">' . esc_html__('All', 'ralfdocs') . '</a>',
      'ninety_days' => '<a href="' . esc_url(get_admin_url('', 'index.php?page=search-term-stats-submenu-page&time_period=ninety_days')) . '">' . esc_html__('Last 90 Days', 'ralfdocs') . '</a>'
    );

    return $status_links;
  }

  public function prepare_items(){
    $this->_column_headers = $this->get_column_info();

    $per_page = $this->get_items_per_page('search_terms_per_page', 25);
    $current_page = $this->get_pagenum();
    $total_items = $this->searched_terms_count();

    $this->set_pagination_args([
      'total_items' => $total_items,
      'per_page' => $per_page
    ]);

    $this->items = $this->get_search_term_stats($per_page, $current_page);
  }
}