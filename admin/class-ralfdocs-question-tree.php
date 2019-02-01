<?php
/**
 * Creates the post types for the ralfdocs question tree
 */
if(!defined('ABSPATH')){ exit; }

if(!class_exists('RALFDOCS_Question_Tree')){
  class RALFDOCS_Question_Tree{
    public function __construct(){
      add_action('init', array($this, 'create_post_type'));
      add_action('acf/init', array($this, 'acf_init'));

      add_action('wp_ajax_nopriv_ralfdocs_show_first_question', array($this, 'ralfdocs_show_first_question'));
      add_action('wp_ajax_ralfdocs_show_first_question', array($this, 'ralfdocs_show_first_question'));
    }

    public function acf_init(){
      $this->add_sector_options();
      $this->question_tree_settings();
      $this->prepared_reports_settings();
    }

    public function ralfdocs_show_first_question(){
      $qt_page = get_page_by_path('question-tree');
      $qt_page_id = $qt_page->ID;

      $question = get_field('first_question', $qt_page_id);
      $sectors = get_terms(array(
        'taxonomy' => 'sectors',
        'hide_empty' => true,
        'parent' => 0
      )); ?>

      <form id="qt-choices">
      <h3><?php echo esc_html($question); ?></h3>
      <ul class="qt-options list-unstyled">

        <?php 
          foreach($sectors as $sector){
            $question = get_field('question_link', 'sectors_' . $sector->term_id);
            
            if($question){
              $question_link = get_permalink($question[0]->ID); ?>

                <li class="radio">
                  <label>
                    <input type="radio" name="qt-answers" value="<?php echo esc_url($question_link); ?>" data-next_type="Next" />
                    <?php echo esc_html($sector->name); ?>
                    <span class="radio-btn"></span>
                  </label>
                </li>

              <?php
            } //endif
          } //endforeach
        ?>

      </ul>
      <a href="#" id="qt-btn" class="btn-main btn-hide">Next</a>
      </form>
      <?php wp_die();
    }

    public function create_post_type(){
      $questions_labels = array(
        'name' => esc_html_x('Questions', 'post type general name', 'ralfdocs'),
        'singular_name' => esc_html_x('Question', 'post type singular name', 'ralfdocs'),
        'menu_name' => esc_html_x('Question Tree', 'admin menu name', 'ralfdocs'),
        'name_admin_bar' => esc_html__('Questions', 'ralfdocs'),
        'add_new' => esc_html__('Add New', 'ralfdocs'),
        'add_new_item' => esc_html__('Add New Question', 'ralfdocs'),
        'new_item' => esc_html__('New Question', 'ralfdocs'),
        'edit_item' => esc_html__('Edit Question', 'ralfdocs'),
        'view_item' => esc_html__('View Question', 'ralfdocs'),
        'view_items' => esc_html__('View Questions', 'ralfdocs'),
        'all_items' => esc_html__('All Questions', 'ralfdocs'),
        'search_items' => esc_html__('Search Questions', 'ralfdocs'),
        'not_found' => esc_html__('No Questions Found', 'ralfdocs'),
        'not_found_in_trash' => esc_html__('No Questions Found In Trash', 'ralfdocs')
      );
      $questions_args = array(
        'labels' => $questions_labels,
        'description' => esc_html__('RALF Question Tree Questions', 'ralfdocs'),
        'public' => true,
        'menu_position' => 8,
        'menu_icon' => 'dashicons-networking',
        'supports' => array(
          'title',
          'author',
          'revisions',
          'custom_fields'
        )
      );
      register_post_type('questions', $questions_args);

      $prepared_reports_labels = array(
        'name' => esc_html_x('Prepared Reports', 'post type general name', 'ralfdocs'),
        'singular_name' => esc_html_x('Prepared Report', 'post type singular name', 'ralfdocs'),
        'menu_name' => esc_html_x('Prepared Reports', 'admin menu name', 'ralfdocs'),
        'name_admin_bar' => esc_html__('Prepared Reports', 'ralfdocs'),
        'add_new' => esc_html__('Add New', 'ralfdocs'),
        'add_new_item' => esc_html__('Add New Prepared Report', 'ralfdocs'),
        'new_item' => esc_html__('New Prepared Report', 'ralfdocs'),
        'edit_item' => esc_html__('Edit Prepared Report', 'ralfdocs'),
        'view_item' => esc_html__('View Prepared Report', 'ralfdocs'),
        'view_items' => esc_html__('View Prepared Reports', 'ralfdocs'),
        'all_items' => esc_html__('Prepared Reports', 'ralfdocs'),
        'search_items' => esc_html__('Search Prepared Reports', 'ralfdocs'),
        'not_found' => esc_html__('No Prepared Reports Found', 'ralfdocs'),
        'not_found_in_trash' => esc_html__('No Prepared Reports Found In Trash', 'ralfdocs')
      );
      $prepared_reports_args = array(
        'labels' => $prepared_reports_labels,
        'description' => esc_html__('Pre-Built Reports for Question Tree'),
        'public' => true,
        'show_in_menu' => 'edit.php?post_type=questions',
        'supports' => array(
          'title',
          'author',
          'revisions',
          'custom_fields'
        )
      );
      register_post_type('prepared_reports', $prepared_reports_args);
    }

    public function add_sector_options(){
      //add question tree options for sectors
      acf_add_local_field_group(array(
        'key' => 'group_5c5367653581a',
        'title' => esc_html__('Sector Question Tree Settings', 'ralfdocs'),
        'fields' => array(
          array(
            'key' => 'field_5c53676e8c040',
            'label' => esc_html__('Question Tree Background Image', 'ralfdocs'),
            'name' => 'question_tree_background_image',
            'type' => 'image',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'return_format' => 'array',
            'preview_size' => 'medium',
            'library' => 'all',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => '',
          ),
          array(
            'key' => 'field_5c54b20f29dbe',
            'label' => esc_html__('Question Tree Background Image CSS', 'ralfdocs'),
            'name' => 'question_tree_background_image_css',
            'type' => 'text',
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
            'maxlength' => '',
          ),
          array(
            'key' => 'field_5c5367e88c042',
            'label' => esc_html__('Question Link', 'ralfdocs'),
            'name' => 'question_link',
            'type' => 'relationship',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'post_type' => array(
              0 => 'questions',
            ),
            'taxonomy' => '',
            'filters' => array(
              0 => 'search',
              1 => 'post_type',
              2 => 'taxonomy',
            ),
            'elements' => '',
            'min' => '',
            'max' => '1',
            'return_format' => 'object',
          ),
        ),
        'location' => array(
          array(
            array(
              'param' => 'taxonomy',
              'operator' => '==',
              'value' => 'sectors',
            ),
          ),
        ),
        'menu_order' => 10,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => 1,
        'description' => '',
      ));      
    }

    public function question_tree_settings(){
      acf_add_local_field_group(array(
        'key' => 'group_5c5327bc17102',
        'title' => esc_html__('Question Tree Settings', 'ralfdocs'),
        'fields' => array(
          array(
            'key' => 'field_5c5327c9b6d4c',
            'label' => esc_html__('Answers', 'ralfdocs'),
            'name' => 'answers',
            'type' => 'repeater',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'collapsed' => '',
            'min' => 0,
            'max' => 0,
            'layout' => 'row',
            'button_label' => esc_html__('Add Answer', 'ralfdocs'),
            'sub_fields' => array(
              array(
                'key' => 'field_5c5334a396c31',
                'label' => esc_html__('Answer', 'ralfdocs'),
                'name' => 'answer',
                'type' => 'text',
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
                'maxlength' => '',
              ),
              array(
                'key' => 'field_5c534d9886046',
                'label' => esc_html__('Answer Link', 'ralfdocs'),
                'name' => 'answer_link',
                'type' => 'relationship',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                  'width' => '',
                  'class' => '',
                  'id' => '',
                ),
                'post_type' => array(
                  0 => 'questions',
                  1 => 'prepared_reports',
                ),
                'taxonomy' => '',
                'filters' => array(
                  0 => 'search',
                  1 => 'post_type',
                  2 => 'taxonomy',
                ),
                'elements' => '',
                'min' => '',
                'max' => '1',
                'return_format' => 'object',
              ),
            ),
          ),
        ),
        'location' => array(
          array(
            array(
              'param' => 'post_type',
              'operator' => '==',
              'value' => 'questions',
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
      
      acf_add_local_field_group(array(
        'key' => 'group_5c54579c2a879',
        'title' => esc_html__('Question Tree Page Settings', 'ralfdocs'),
        'fields' => array(
          array(
            'key' => 'field_5c5457a9106f6',
            'label' => esc_html__('Background Image', 'ralfdocs'),
            'name' => 'background_image',
            'type' => 'image',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'return_format' => 'array',
            'preview_size' => 'full',
            'library' => 'all',
            'min_width' => '',
            'min_height' => '',
            'min_size' => '',
            'max_width' => '',
            'max_height' => '',
            'max_size' => '',
            'mime_types' => '',
          ),
          array(
            'key' => 'field_5c5457b7106f7',
            'label' => esc_html__('Background Image CSS', 'ralfdocs'),
            'name' => 'background_image_css',
            'type' => 'text',
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
            'maxlength' => '',
          ),
       		array(
            'key' => 'field_5c545dd4dd6a3',
            'label' => esc_html__('First Question', 'ralfdocs'),
            'name' => 'first_question',
            'type' => 'text',
            'instructions' => esc_html__('First question for the sector list.', 'ralfdocs'),
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
            'maxlength' => '',
          ),
        ),
        'location' => array(
          array(
            array(
              'param' => 'page',
              'operator' => '==',
              'value' => '739',
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

    public function prepared_reports_settings(){
      acf_add_local_field_group(array(
        'key' => 'group_5c534f6d67720',
        'title' => esc_html__('Prepared Reports', 'ralfdocs'),
        'fields' => array(
          array(
            'key' => 'field_5c534f829116d',
            'label' => esc_html__('Report Articles', 'ralfdocs'),
            'name' => 'report_articles',
            'type' => 'relationship',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
              'width' => '',
              'class' => '',
              'id' => '',
            ),
            'post_type' => array(
              0 => 'activities',
              1 => 'impacts',
              2 => 'resources',
            ),
            'taxonomy' => '',
            'filters' => array(
              0 => 'search',
              1 => 'post_type',
              2 => 'taxonomy',
            ),
            'elements' => '',
            'min' => '',
            'max' => '',
            'return_format' => 'object',
          ),
        ),
        'location' => array(
          array(
            array(
              'param' => 'post_type',
              'operator' => '==',
              'value' => 'prepared_reports',
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
  } //end class
}