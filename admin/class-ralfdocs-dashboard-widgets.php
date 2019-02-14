<?php
if(!defined('ABSPATH')){ exit; }

if(!class_exists('RALFDOCS_Dashboard_Widgets')){
  class RALFDOCS_Dashboard_Widgets{
    public function __construct(){

    }

    public function add_dashboard_widgets(){
      wp_add_dashboard_widget(
        'emailed-reports',
        esc_html__('Emailed Reports', 'ralfdocs'),
        array($this, 'emailed_reports_dashboard_widget')
      );
  
      wp_add_dashboard_widget(
        'saved-to-report-statistics',
        esc_html__('Saved to Report Statistics', 'ralfdocs'),
        array($this, 'saved_statistics_dashboard_widget')
      );
    }

    public function emailed_reports_dashboard_widget(){
      global $wpdb;

      $emailed_reports_table = '<table cellpadding="0" cellspacing="0" class="emailed-reports-table">';
      $emailed_reports_table .= '<thead><tr>'
                              . '<th>' . esc_html__('Emailed Domains', 'ralfdocs') . '</th>'
                              . '<th>' . esc_html__('Activities / Impacts / Resources', 'ralfdocs') . '</th>'
                              . '<th>' . esc_html__('Email Date', 'ralfdocs') . '</th>'
                              . '<th></th>'
                              . '</tr></thead><tbody>';
    
      $emailed_reports = $wpdb->get_results('
        SELECT * 
        FROM emailed_reports
        ORDER BY email_date DESC
        LIMIT 10', 'ARRAY_A');
    
      $emailed_reports_count = count($emailed_reports);
      for($i = 0; $i < $emailed_reports_count; $i++){
        $emailed_reports_table .= '<tr>';
    
        $emailed_reports_table .= '<td style="border-bottom:1px solid #000;">' . esc_html($emailed_reports[$i]['email_domains']) . '</td>';
    
        $report_ids = $emailed_reports[$i]['report_ids'];
        $reports_list = $this->get_reports_list($report_ids);
        $emailed_reports_table .= '<td style="border-bottom:1px solid #000;">' . $reports_list . '</td>';
    
        $emailed_reports_table .= '<td style="border-bottom:1px solid #000;">' . esc_html($emailed_reports[$i]['email_date']) . '</td>';
    
        $emailed_reports_table .= '<td style="border-bottom:1px solid #000;"><a href="' . esc_url(home_url('view-report/?report_ids=' . $report_ids)) . '" target="_blank">' . esc_html__('View Report', 'ralfdocs') . '</a></td>';
    
        $emailed_reports_table .= '</tr>';
      }
    
      $emailed_reports_table .= '</tbody></table>';
    
      echo $emailed_reports_table;
      echo '<p><a href="' . esc_url(get_admin_url('', 'index.php?page=emailed-reports-submenu-page')) . '" class="button">' . esc_html__('View Full List', 'ralfdocs') . '</a></p>';    
    }

    public function saved_statistics_dashboard_widget(){
      global $wpdb;

      $saved_stats_table = '<table cellpadding="0" cellspacing="0" class="saved-stats-table">';
      $saved_stats_table .= '<thead><tr>'
                          . '<th>' . esc_html__('Article', 'ralfdocs') . '</th>'
                          . '<th>' . esc_html__('Count', 'ralfdocs') . '</th>'
                          . '<th></th>'
                          . '</tr></thead><tbody>';
                          
      $saved_stats = $wpdb->get_results('
        SELECT article_id, COUNT(*) AS count
        FROM saved_reports
        GROUP BY article_id
        LIMIT 20', 'ARRAY_A');

      $saved_stats_count = count($saved_stats);
      for($i = 0; $i < $saved_stats_count; $i++){
        $saved_stats_table .= '<tr>';

        $saved_stats_table .= '<td><a href="' . esc_url(get_permalink($saved_stats[$i]['article_id'])) . '" target="_blank">' . esc_html(get_the_title($saved_stats[$i]['article_id'])) . '</a></td>';
        $saved_stats_table .= '<td>' . esc_html($saved_stats[$i]['count']) . '</td>';

        $saved_stats_table .= '</tr>';
      }

      $saved_stats_table .= '</tbody></table>';

      echo $saved_stats_table;
      echo '<p><a href="' . esc_url(get_admin_url('', 'index.php?page=saved-statistics-submenu-page')) . '" class="button">' . esc_html__('View All Stats', 'ralfdocs') . '</a></p>';
    }

    public function get_reports_list($reports){
      global $wpdb;
      $report_ids = explode(',', $reports);
      $article_titles = [];
    
      foreach($report_ids as $report_id){
        $report_title = $wpdb->get_var($wpdb->prepare("
          SELECT post_title
          FROM {$wpdb->prefix}posts
          WHERE ID = %d", $report_id));
    
        $report_title_list = '<li>' . esc_html($report_title) . '</li>';
        $article_titles[] = $report_title_list;
      }
    
      $reports_list = '<ul class="reports-list">' . implode('', $article_titles) . '</ul>';
      return $reports_list;
    }
  }
}