<?php
/*
  Cronjobs
    Weekly report:
      0 0 * * 0 /usr/bin/wget https://cycat25.com/?email_admin_reports=weekly

    Monthly Report
     0 0 1 * * /usr/bin/wget https://cycat25.com/?email_admin_reports=monthly
*/
if(!defined('ABSPATH')){ exit; }

if(!class_exists('RALFDOCS_Background_Admin_Tasks')){
  class RALFDOCS_Background_Admin_Tasks{
    public function __construct(){
      add_action('acf/init', array($this, 'options'));
      $this->delete_old_reports();

      if(isset($_GET['email_admin_reports'])){
        $this->email_admin_reports();
      }
    }

    public function options(){
      acf_add_options_sub_page(array(
        'page_title' => 'Admin Report Settings',
        'menu_title' => 'Admin Report Settings',
        'parent_slug' => 'ralfdocs-settings'
      ));

      acf_add_options_sub_page(array(
        'page_title' => 'Report History Settings',
        'menu_title' => 'Report History Settings',
        'parent_slug' => 'ralfdocs-settings'
      ));

      acf_add_local_field_group(array(
        'key' => 'group_5bcde52f3147b',
        'title' => __('Report History Settings', 'ralfdocs'),
        'fields' => array(
          array(
            'key' => 'field_5bcde537c6286',
            'label' => __('How long to store reports?', 'ralfdocs'),
            'name' => 'how_long_to_store_reports',
            'type' => 'number',
            'instructions' => __('Enter the number of days to keep reports before they are deleted.', 'ralfdocs'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '25',
              'class' => '',
              'id' => ''
            ),
            'default_value' => '',
            'placeholder' => '',
            'prepend' => '',
            'append' => 'days',
            'min' => '',
            'max' => '',
            'step' => 1
          )
        ),
        'location' => array(
          array(
            array(
              'param' => 'options_page',
              'operator' => '==',
              'value' => 'ralfdocs-settings'
            )
          )
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => ''
      ));

      acf_add_local_field_group(array(
        'key' => 'group_5bf5dc4ee5038',
        'title' => __('Email Reports Settings', 'ralfdocs'),
        'fields' => array(
          array(
            'key' => 'field_5bf5dc59b7941',
            'label' => __('Number of results to include in report', 'ralfdocs'),
            'name' => 'number_of_results',
            'type' => 'number',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'default_value' => 20,
            'placeholder' => '',
            'prepend' => '',
            'append' => '',
            'min' => '',
            'max' => '',
            'step' => 1,
          ),
          array(
            'key' => 'field_5bf5dca7b7942',
            'label' => __('Saved to Report', 'ralfdocs'),
            'name' => 'saved_to_report',
            'type' => 'group',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'layout' => 'block',
            'sub_fields' => array(
              array(
                'key' => 'field_5bf5dd04b7943',
                'label' => __('Email Addresses to Send Weekly', 'ralfdocs'),
                'name' => 'weekly_email_addresses',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '50',
                  'class' => '',
                  'id' => '',
                ),
                'collapsed' => '',
                'min' => 0,
                'max' => 0,
                'layout' => 'table',
                'button_label' => __('Add Email Address', 'ralfdocs'),
                'sub_fields' => array(
                  array(
                    'key' => 'field_5bf5dd38b7944',
                    'label' => __('Email Address', 'ralfdocs'),
                    'name' => 'email_address',
                    'type' => 'email',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                      'width' => '',
                      'class' => '',
                      'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                  ),
                ),
              ),
              array(
                'key' => 'field_5bf5dd69b7945',
                'label' => __('Email Addresses to Send Monthly', 'ralfdocs'),
                'name' => 'monthly_email_addresses',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '50',
                  'class' => '',
                  'id' => '',
                ),
                'collapsed' => '',
                'min' => 0,
                'max' => 0,
                'layout' => 'table',
                'button_label' => __('Add Email Address', 'ralfdocs'),
                'sub_fields' => array(
                  array(
                    'key' => 'field_5bf5dd81b7946',
                    'label' => __('Email Address', 'ralfdocs'),
                    'name' => 'email_address',
                    'type' => 'email',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                      'width' => '',
                      'class' => '',
                      'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                  ),
                ),
              ),
            ),
          ),
          array(
            'key' => 'field_5bf5ddb3b7947',
            'label' => __('Searched Terms', 'ralfdocs'),
            'name' => 'searched_terms',
            'type' => 'group',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'layout' => 'block',
            'sub_fields' => array(
              array(
                'key' => 'field_5bf5ddd8b7948',
                'label' => __('Email Addresses to Send Weekly', 'ralfdocs'),
                'name' => 'weekly_email_addresses',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '50',
                  'class' => '',
                  'id' => '',
                ),
                'collapsed' => '',
                'min' => 0,
                'max' => 0,
                'layout' => 'table',
                'button_label' => __('Add Email Address', 'ralfdocs'),
                'sub_fields' => array(
                  array(
                    'key' => 'field_5bf5ddedb7949',
                    'label' => __('Email Address', 'ralfdocs'),
                    'name' => 'email_address',
                    'type' => 'email',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                      'width' => '',
                      'class' => '',
                      'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                  ),
                ),
              ),
              array(
                'key' => 'field_5bf5de13b794a',
                'label' => __('Email Addresses to Send Monthly', 'ralfdocs'),
                'name' => 'monthly_email_addresses',
                'type' => 'repeater',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '50',
                  'class' => '',
                  'id' => '',
                ),
                'collapsed' => '',
                'min' => 0,
                'max' => 0,
                'layout' => 'table',
                'button_label' => __('Add Email Address', 'ralfdocs'),
                'sub_fields' => array(
                  array(
                    'key' => 'field_5bf5de27b794b',
                    'label' => __('Email Address', 'ralfdocs'),
                    'name' => 'email_address',
                    'type' => 'email',
                    'instructions' => '',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                      'width' => '',
                      'class' => '',
                      'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                  ),
                ),
              ),
            ),
          ),
        ),
        'location' => array(
          array(
            array(
              'param' => 'options_page',
              'operator' => '==',
              'value' => 'ralfdocs-settings',
            ),
          ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
      ));      
    }

    public function delete_old_reports(){
      $how_long_to_store_reports = get_field('how_long_to_store_reports', 'option');
  
      global $wpdb;
      
      $wpdb->query($wpdb->prepare("
        DELETE from emailed_reports
        WHERE datediff(now(), email_date) > %d", $how_long_to_store_reports));    
    }  

    public function email_admin_reports(){
      $results_limit = get_field('number_of_results', 'option');
      $saved_to_report = get_field('saved_to_report', 'option');
      $searched_terms = get_field('searched_terms', 'option');

      if($_GET['email_admin_reports'] == 'weekly'){
        $this->email_saved_to_report($saved_to_report['weekly_email_addresses'], $results_limit);
        $this->email_searched_terms($searched_terms['weekly_email_addresses'], $results_limit);
      }
      elseif($_GET['email_admin_reports'] == 'monthly'){
        $this->email_saved_to_report($saved_to_report['monthly_email_addresses'], $results_limit);
        $this->email_searched_terms($searched_terms['monthly_email_addresses'], $results_limit);
      }
      else{
        return;
      }
    }

    function email_saved_to_report($email_addresses, $results_limit){
      if(!empty($email_addresses)){
        $sql_select_from = 'SELECT article_id, COUNT(*) AS saved_count FROM saved_reports';
        $sql_where = ' WHERE saved_date >= DATE_ADD(NOW(), INTERVAL -90 DAY)';
        $sql_group_by = ' GROUP BY article_id ORDER BY saved_count DESC LIMIT ' . $results_limit;
    
        $all_time_results = $this->get_saved_to_report_results($sql_select_from . $sql_group_by);
        $ninety_days_results = $this->get_saved_to_report_results($sql_select_from . $sql_where . $sql_group_by);
    
        //create message table, one for all-time and on fro 90-days
        $message = '<h3>' . esc_html__('Articles Saved to Report History - All Time', 'ralfdocs') . '</h3>';
        $message .= create_saved_to_report_table($all_time_results);
    
        $message .= '<br /><h3>' . esc_html__('Articles Saved to Report History - Last 90 Days', 'ralfdocs') . '</h3>';
        $message .= $this->create_saved_to_report_table($ninety_days_results);
    
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $subject = sprintf(esc_html__("Articles Saved to Report History - %s Report", 'ralfdocs'), ucfirst($_GET['email_admin_reports']));
    
        //send mail to each email address separately
        foreach($email_addresses as $email_address){
          wp_mail($email_address['email_address'], $subject, $message, $headers);
          //echo $message;
        }
      }
    }
    
    function email_searched_terms($email_addresses, $results_limit){
      if(!empty($email_addresses)){
        global $wpdb;
    
        $sql_select_from = "SELECT query AS searched_term, COUNT(*) AS searched_count, hits FROM {$wpdb->prefix}swp_log";
        $sql_select_from .= ' WHERE query NOT REGEXP "[()^;<>/\'\"!]"';
    
        $sql_group_by = ' GROUP BY query ORDER BY searched_count DESC LIMIT ' . $results_limit;
    
        $sql_time_period = ' AND tstamp >= DATE_ADD(NOW(), INTERVAL -90 DAY)';
    
        $all_time_results = $this->get_searched_term_results($sql_select_from . $sql_group_by);
        $ninety_days_results = $this->get_searched_term_results($sql_select_from . $sql_time_period . $sql_group_by);
    
        // create message table, one for all-time and one for 90-days
        $message = '<h3>' . esc_html__('Search Term History - All Time', 'ralfdocs') . '</h3>';
        $message .= $this->create_search_term_results_table($all_time_results);
    
        $message .= '<br /><h3>' . esc_html__('Search Term History - Last 90 Days', 'ralfdocs') . '</h3>';
        $message .= $this->create_search_term_results_table($ninety_days_results);
    
        $headers = array('Content-Type: text/html; charset=UTF-8');
        $subject = sprintf(esc_html__("Search History - %s Report", 'ralfdocs'), ucfirst($_GET['email_admin_reports']));
    
        //send mail to each email address separately
        foreach($email_addresses as $email_address){
          wp_mail($email_address['email_address'], $subject, $message, $headers);
        }
      }
    }
    
    function get_searched_term_results($sql){
      global $wpdb;
    
      $results = $wpdb->get_results($sql, 'ARRAY_A');
    
      $results_count = count($results);
      for($r = 0; $r < $results_count; $r++){
        $searched_term_link = '<a href="' . esc_url(add_query_arg('s', $results[$r]['searched_term'], home_url())) . '" target="_blank">' . esc_html($results[$r]['searched_term']) . '</a>';
    
        $results[$r]['searched_term'] = $searched_term_link;
      }
    
      return $results;
    }
    
    function get_saved_to_report_results($sql){
      global $wpdb;
    
      $results = $wpdb->get_results($sql, 'ARRAY_A');
    
      $results_count = count($results);
      for($r = 0; $r < $results_count; $r++){
        $article_title = get_the_title($results[$r]['article_id']);
        //$article_link = esc_url(get_permalink($results[$r]['article_id']));
        $article_post_type = get_post_type($results[$r]['article_id']);
        $article_link = home_url($article_post_type . '/' . sanitize_title($article_title));
    
        $results[$r]['article_id'] = '<a href="' . esc_url($article_link) . '" target="_blank">' . esc_html($article_title) . '</a>';
      }
    
      return $results;
    }
    
    function create_search_term_results_table($results){
      $table = '<table cellpadding="1" cellspacing="0" style="text-align:left; width:100%;"><thead><tr>'
              . '<th>' . esc_html__('Search Term', 'ralfdocs') . '</th>'
              . '<th>' . esc_html__('Hits', 'ralfdocs') . '</th>'
              . '<th>' . esc_html__('Searched Count', 'ralfdocs') . '</th>'
              . '</tr></thead><tbody>';
      
      foreach($results as $search_term){
        $table .= '<tr>'
                . '<td style="border-bottom:1px solid #000;">' . esc_html($search_term['searched_term']) . '</td>'
                . '<td style="border-bottom:1px solid #000;">' . esc_html($search_term['hits']) . '</td>'
                . '<td style="border-bottom:1px solid #000;">' . esc_html($search_term['searched_count']) . '</td>'
                . '</tr>';
      }
    
      $table .= '</tbody></table>';
      return $table;
    }
    
    function create_saved_to_report_table($results){
      $table = '<table cellpadding="1" cellspacing="0" style="text-align:left; width:100%;"><thead><tr>'
              . '<th>' . esc_html__('Article', 'ralfdocs') . '</th>'
              . '<th>' . esc_html__('Saved Count', 'ralfdocs') . '</th>'
              . '</tr></thead><tbody>';
      
      foreach($results as $article){
        $table .= '<tr>'
                . '<td style="border-bottom:1px solid #000;">' . esc_html($article['article_id']) . '</td>'
                . '<td style="border-bottom:1px solid #000;">' . esc_html($article['saved_count']) . '</td>'
                . '</tr>';
      }
    
      $table .= '</tbody></table>';
      return $table;
    }
  }
}