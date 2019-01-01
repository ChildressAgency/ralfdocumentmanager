<?php
if(!defined('ABSPATH')){ exit; }

if(!class_exists('RALFDOCS_Email_Report')){
  class RALFDOCS_Email_Report{
    public function __construct(){
      add_shortcode('email_form', array($this, 'email_report_form'));
      add_shortcode('report_button', array($this, 'report_button_container'));

      add_action('wp_ajax_nopriv_send_rtf_report', array($this, 'send_rtf_report'));
      add_action('wp_ajax_send_rtf_report', array($this, 'send_rtf_report'));
      add_action('wp_ajax_nopriv_record_report_save', array($this, 'record_report_save'));
      add_action('wp_ajax_record_report_save', array($this, 'record_report_save'));  
    }

    public function email_report_form($atts){
      $article_ids = implode(',', $atts['activity_ids']);
      $nonce = wp_create_nonce('email_rtf_report_' . $article_ids);

      $form_content = '<div class="email-report hidden-print">
                        <h3>' . esc_html__('Email this report', 'ralfdocs') . '</h3>
                        <div class="form-group">
                          <input type="text" required id="email-addresses" name="email-addresses" class="form-control" placeholder="' . esc_html__('Enter a comma-separated list of email addresses.', 'ralfdocs') . '" />
                        </div>
                        <button class="btn-main btn-report send-email" data-nonce="' . $nonce . '" data-article_ids="' . $article_ids . '">' . esc_html__('Send Email', 'ralfdocs') . '</button>
                        <p class="email-response"></p>
                      </div>';
  
      return $form_content;  
    }

    public function send_rtf_report(){
      $article_ids = $_POST['article_ids'];
  
      //sanitize email addresses
      $entered_email_addresses = $_POST['email-addresses'];
      $email_addresses = $this->sanitize_email_addresses($entered_email_addresses);
  
      //log emailed reports
      $report_url_id = $this->log_email_reports($email_addresses, $article_ids);
  
      //put article_ids string into array
      $article_ids_array = explode(',', $article_ids);
  
      //verify nonce
      if(check_ajax_referer('email_rtf_report_' . $article_ids, 'nonce', false) == false){
        wp_send_json_error();
      }
  
      //generate the html for the report 
      $html_report = $this->get_report($article_ids_array);
  
      //using vsword to convert the html into a docx
      require_once RALFDOCS_PLUGIN_DIR . '/vendors/vsWord/VsWord.php';
      VsWord::autoLoad();
  
      $doc = new VsWord();
      $parser = new HtmlParser($doc);
      $parser->parse($html_report);
  
      //create the filename and path to save to, and where mailer will find the attachment
      //todo: delete the file after emailing? -check with client
      $upload_dir = wp_upload_dir();
      $upload_dir_base = $upload_dir['basedir'];
      $ralf_reports_folder = $upload_dir_base . '/ralf_reports/';
      $ralf_report_name = $ralf_reports_folder . 'ralf_report_' . date("mdY-His") . '.docx';
  
      $doc->saveAs($ralf_report_name);
  
      //create the email variables
      $to = $email_addresses;
      $subject = esc_html__('Your RALF Impact Report', 'ralfdocs');
      //$headers = 'From: USAID RALF <jcampbell@childressagency.com>';
      $headers = '';
      $message = esc_html__('Your RALF Impact Report is attached to this email. Your chosen articles are listed below:', 'ralfdocs') . "\r\n\r\n";
  
      //show the title of each article in the message body
      foreach($report_ids_array as $report_id){
        $message .= ' - ' . esc_html(get_the_title($report_id)) . "\r\n";
      }
  
      //link to the report using querystrings with the ids
      $message .= "\r\n" . esc_html__('Here is a link back to your report: ', 'ralfdocs') . esc_url(home_url('view-report/' . $report_url_id));
      //$message .= "\r\n" . $ralf_report_name;
  
      //send the email with attachment
      $result = wp_mail($to, $subject, $message, $headers, $ralf_report_name);
  
      //reply to the webpage
      if($result == true){
        wp_send_json_success(esc_html__('Report email sent!', 'ralfdocs'));
      }
      else{
        wp_send_json_error();
      }
    }
  
    public function sanitize_email_addresses($entered_email_addresses){
      $email_addresses = explode(',', $entered_email_addresses);
      $sanitized_email_addresses = [];
      foreach($email_addresses as $email_address){
        $sanitized_email_addresses[] = sanitize_email($email_address);
      }
  
      return implode(',', $sanitized_email_addresses);
    } 
    
    public function log_email_reports($email_addresses, $article_ids){
      global $wpdb;
  
      $emails = explode(',', $email_addresses);
      $email_domains = [];
      foreach($emails as $email){
        $email_parts = explode('@', $email);
        $email_domains[] = $email_parts[1];
      }
  
      $email_domains_str = implode(',', $email_domains);
  
      $wpdb->insert(
        'emailed_reports',
        array(
          'email_domains' => esc_sql($email_domains_str),
          'report_ids' => esc_sql($article_ids),
        )
      );
  
      return $wpdb->insert_id;
    } 
    
    public function get_report($article_ids){
      $rtf_report = '<h1>' . esc_html__('Report of Activities and Associated Impacts', 'ralfdocs') . '</h1>';
  
      $activities_report = new WP_Query(array(
        'post_type' => array('activities', 'impacts', 'resources'),
        'posts_per_page' => -1,
        'post__in' => $article_ids,
        'orderby' => 'post_type',
        'order' => 'DESC'
      ));
      //$rtf_report = print_r($activities_report, true);
  
      if($activities_report->have_posts()){
        while($activities_report->have_posts()){
          $activities_report->the_post();
  
          $rtf_report .= '<h2>' . esc_html(get_the_title()) . '</h2>';
          $rtf_report .= '<p>' . wp_kses_post(get_the_content()) . '</p>';
          
          $conditions = get_field('conditions');
          if($conditions){
            $rtf_report .= '<h3>' . esc_html__('CONDITIONS', 'ralfdocs') . '</h3>';
            $rtf_report .= wp_kses_post(str_replace("& ", "and ", get_field('conditions')));
          }
  
          $impact_ids = get_field('related_impacts', false, false);
          if(!empty($impact_ids)){
            $impacts_by_sector = usaidralf_get_impacts_by_sector($impact_ids);
            $rtf_report .= '<h3>' . esc_html__('IMPACT BY SECTOR', 'ralfdocs') . '</h3>';
  
            foreach($impacts_by_sector as $sector){
              foreach($sector['impacts'] as $impact){
                $rtf_report .= '<h4>' . esc_html(str_replace("& ", "and ", $impact->impact_title)) . '</h4>';
                $rtf_report .= '<p>' . esc_html(str_replace("& ", "and ", $impact->impact_description)) . '</p>';
              }
            }
          }
  
        }
      } wp_reset_postdata();
  
      return $rtf_report;
    }

    public function record_report_save(){
      $article_id = $_POST['article_id'];
      //verify nonce
      if(check_ajax_referer('report_button_' . $article_id, 'nonce', false) == false){
        wp_send_json_error();
      }
      else{
        global $wpdb;
        $wpdb->insert(
          'saved_reports',
          array(
            'article_id' => $article_id
          ),
          array(
            '%d'
          )
        );
      }
    } 
    
    public function report_button_container(){
      if(is_singular('activities') || is_singular('impacts') || is_singular('resources') || is_page('view-report')){
        $article_id = get_the_ID();
        $nonce = wp_create_nonce('report_button_' . $article_id);

        $btn_container = '<div class="report-button hidden-print" data-article_id="' . $article_id . '" data-nonce="' . $nonce . '"></div>';

        return $btn_container;
      }
    }
  }
}