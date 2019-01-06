<?php
if (!defined('ABSPATH')){ exit; }

if(!class_exists('RALFDOCS_Dashboard')){
class RALFDOCS_Dashboard{
  protected $emailed_reports_list;
  protected $saved_stats_list;
  protected $search_terms_stats_list;

  public function __construct(){

  }

  public function init(){
    $this->load_dependencies();

    $dashboard_widgets = new RALFDOCS_Dashboard_Widgets();
    add_action('wp_dashboard_setup', array($dashboard_widgets, 'add_dashboard_widgets'));

    add_filter('set-screen-option', array(__CLASS__, 'set_screen'), 10, 3);

    add_action('admin_menu', array($this, 'dashboard_submenus'));

    add_action('load-post.php', array($this, 'saved_count_metabox'));
    add_action('load-post-new.php', array($this, 'saved_count_metabox'));

    add_filter('manage_activities_posts_columns', array($this, 'set_saved_count_column'));
    add_filter('manage_impacts_posts_columns', array($this, 'set_saved_count_column'));
    add_filter('manage_resources_posts_columns', array($this, 'set_saved_count_column'));

    add_action('manage_activities_posts_custom_column', array($this, 'saved_count_column_values'), 10, 2);
    add_action('manage_impacts_posts_custom_column', array($this, 'saved_count_column_values'), 10, 2);
    add_action('manage_resources_posts_custom_column', array($this, 'saved_count_column_values'), 10, 2);

    add_filter('manage_edit-activities_sortable_columns', array($this, 'sortable_saved_count'));
    add_filter('manage_edit-impacts_sortable_columns', array($this, 'sortable_saved_count'));
    add_filter('manage_edit-resources_sortable_columns', array($this, 'sortable_saved_count'));

    add_filter('posts_clauses', array($this, 'orderby_saved_count'), 1, 2);

    add_action('searchwp_stats_after_count', array($this, 'link_stats_keywords'), 10, 2);
  }

  public function load_dependencies(){
    require_once RALFDOCS_PLUGIN_DIR . '/admin/class-ralfdocs-emailed-reports-list-table.php';
    require_once RALFDOCS_PLUGIN_DIR . '/admin/class-ralfdocs-saved-stats-list-table.php';
    require_once RALFDOCS_PLUGIN_DIR . '/admin/class-ralfdocs-search-terms-stats-list-table.php';

    require_once RALFDOCS_PLUGIN_DIR . '/admin/class-ralfdocs-dashboard-widgets.php';
  }

  public static function set_screen($status, $option, $value){
    return $value;
  }

  public function dashboard_submenus(){
    $emailed_reports_submenu = add_submenu_page(
      'index.php',
      esc_html_x('Emailed Reports', 'submenu page title', 'ralfdocs'),
      esc_html_x('Emailed Reports', 'submenu title', 'ralfdocs'),
      'manage_options',
      'emailed-reports-submenu-page',
      array($this, 'show_emailed_reports_submenu')
    );
    add_action("load-$emailed_reports_submenu", array($this, 'emailed_reports_screen_option'));

    $saved_stats_submenu = add_submenu_page(
      'index.php',
      esc_html_x('Saved to Report Statistics', 'submenu page title', 'ralfdocs'),
      esc_html_x('Saved Stats', 'submenu title', 'ralfdocs'),
      'manage_options',
      'saved-statistics-submenu-page',
      array($this, 'show_saved_stats_submenu')
    );
    add_action("load-$saved_stats_submenu", array($this, 'saved_stats_screen_option'));

    $search_term_stats_submenu = add_submenu_page(
      'index.php',
      esc_html_x('Search Terms Statistics', 'submenu page title', 'ralfdocs'),
      esc_html_x('Search Terms Stats', 'submenu title', 'ralfdocs'),
      'manage_options',
      'search-term-stats-submenu-page',
      array($this, 'show_search_term_stats_submenu')
    );
    add_action("load-$search_term_stats_submenu", array($this, 'search_terms_stats_screen_option'));
  }

  public function emailed_reports_screen_option(){
    $option = 'per_page';
    $args = array(
      'label' => esc_html__('Emailed Reports Per Page', 'ralfdocs'),
      'default' => 25,
      'option' => 'emailed_reports_per_page'
    );

    add_screen_option($option, $args);

    $this->emailed_reports_list = new RALFDOCS_Emailed_Reports_List_Table();
  }

  public function saved_stats_screen_option(){
    $option = 'per_page';
    $args = array(
      'label' => esc_html__('Saved Stats Per Page', 'ralfdocs'),
      'default' => 25,
      'option' => 'saved_stats_per_page'
    );

    add_screen_option($option, $args);

    $this->saved_stats_list = new RALFDOCS_Saved_Stats_List_Table();
  }

  public function search_terms_stats_screen_option(){
    $option = 'per_page';
    $args = array(
      'label' => esc_html__('Search Terms Per Page', 'ralfdocs'),
      'default' => 25,
      'option' => 'search_terms_per_page'
    );

    add_screen_option($option, $args);

    $this->search_terms_stats_list = new RALFDOCS_Search_Terms_Stats_List_Table();
  }

  public function show_emailed_reports_submenu(){
    ?>
    <div class="wrap">
      <h2><?php esc_html_e('Emailed Reports', 'ralfdocs'); ?></h2>

      <div id="poststuff">
        <div id="post-body" class="metabox-holder">
          <div id="post-body-content">
            <div class="meta-box-sortables ui-sortable">
              <form method="post">
                <?php
                  $this->emailed_reports_list->prepare_items();
                  $this->emailed_reports_list->display();
                ?>
              </form>
            </div>
          </div>
        </div>
        <br class="clear" />
      </div>
    </div>
    <?php
  }

  public function show_saved_stats_submenu(){
    ?>
    <div class="wrap">
      <h2><?php esc_html_e('Saved to Report Statistics', 'ralfdocs'); ?></h2>

      <div id="poststuff">
        <div id="post-body" class="metabox-holder">
          <div id="post-body-content">
            <?php $this->saved_stats_list->views(); ?>
            <div class="meta-box-sortables ui-sortable">
              <form method="post">
                <?php
                  $this->saved_stats_list->prepare_items();
                  $this->saved_stats_list->display();
                ?>
              </form>
            </div>
          </div>
        </div>
        <br class="clear" />
      </div>

    </div>
    <?php
  }

  public function show_search_term_stats_submenu(){
    ?>
    <div class="wrap">
      <h2><?php esc_html_e('Search Terms Statistics', 'ralfdocs'); ?></h2>

      <div id="poststuff">
        <div id="post-body" class="metabox-holder">
          <div id="post-body-content">
            <?php $this->search_terms_stats_list->views(); ?>
            <div class="meta-box-sortables ui-sortable">
              <form method="post">
                <?php 
                  $this->search_terms_stats_list->prepare_items();
                  $this->search_terms_stats_list->display();
                ?>
              </form>
            </div>
          </div>
        </div>
        <br class="clear" />
      </div>
    </div>
    <?php
  }

  public function saved_count_metabox(){
    add_action('add_meta_boxes', array($this, 'add_saved_count_metabox'));
  }

  public function add_saved_count_metabox($post_type){
    $post_types = array('activities', 'impacts');
    if(in_array($post_type, $post_types)){
      add_meta_box(
        'save-count',
        esc_html__('Number of Times Saved to Report', 'ralfdocs'),
        array($this, 'show_saved_count'),
        $post_type,
        'side'
      );
    }
  }

  public function show_saved_count($post){
    global $wpdb;
    $article_id = $post->ID;

    $saved_count = $this->get_saved_count($article_id);

    echo '<p>' . esc_html($saved_count) . '</p>';
  }

  protected function get_saved_count($article_id){
    global $wpdb;

    $saved_count = $wpdb->get_var($wpdb->prepare("
      SELECT COUNT(*) AS saved_count
      FROM saved_reports
      WHERE article_id = %d", $article_id));

    return $saved_count;
  }

  public function set_saved_count_column($columns){
    //$columns['saved_count'] = __('Number of Times Saved to Report', 'ralfdocs');
    //return $columns;

    return array_merge($columns,array('saved_count' => esc_html__('Number of Times Saved to Report', 'ralfdocs')));
  }

  public function saved_count_column_values($column, $post_id){
    if($column == 'saved_count'){
      $saved_count = $this->get_saved_count($post_id);
      echo $saved_count;
    }
  }

  public function sortable_saved_count($columns){
    $columns['saved_count'] = 'saved_count';
    return $columns;
  }

  public function orderby_saved_count($pieces, $query){
    if(!is_admin()){ return; }

    if($orderby = $query->get('orderby'));
    global $wpdb;

    $order = $query->get('order');
    $orderby = $query->get('orderby');
    if($orderby == 'saved_count'){
      $pieces['fields'] .= ', COUNT(saved_reports.article_id) AS saved_count';
      $pieces['join'] .= " LEFT JOIN saved_reports ON {$wpdb->posts}.ID = saved_reports.article_id";
      $pieces['orderby'] = 'saved_count ' . $order;
      $pieces['groupby'] = "{$wpdb->posts}.ID";
    }
    //var_dump($query);

    return $pieces;
  }

  function link_stats_keywords($query, $args){
    echo '<a href="' . esc_url(add_query_arg('s', $query, home_url())) . '" target="_blank" style="float:right;">' . esc_html__('View Results', 'ralfdocs') . '</a>';
    //var_dump($query);
  }
}
}