<?php
if(!defined('ABSPATH')){ exit; }

if(!class_exists('RALFDOCS_Activator')){
  class RALFDOCS_Activator{
    public static function create_emailed_reports_table(){
      global $wpdb;
      $charset_collate = $wpdb->get_charset_collate();
      $table_name = 'emailed_reports';

      $sql = "CREATE TABLE $table_name (
              ID int(11) NOT NULL AUTO_INCREMENT,
              email_domains varchar(255) NOT NULL,
              report_ids varchar(255) NOT NULL,
              email_date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
              PRIMARY KEY  (ID)
      );";

      require_once ABSPATH . 'wp-admin/includes/upgrade.php';
      dbDelta($sql);
    }

    public static function create_saved_reports_table(){
      global $wpdb;
      $charset_collate = $wpdb->get_charset_collate();
      $table_name = 'saved_reports';

      $sql = "CREATE TABLE $table_name (
              ID bigint(20) NOT NULL AUTO_INCREMENT,
              article_id bigint(20) NOT NULL,
              saved_date datetime DEFAULT CURRENT_TIMESTAMP NOT NULL
              PRIMARY KEY  (ID)
      );";

      require_once ABSPATH . 'wp-admin/includes/upgrade.php';
      dbDelta($sql);
    }
  }
}