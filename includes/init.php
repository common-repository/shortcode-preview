<?php

/**
 * Shortcode Preview
 * Print message
 *
 */
function shortcode_preview_print_ajax_return($status, $message) {
  $return = json_encode(array(
    'status'  => $status,
    'message' => $message
  ));
  return $return;
}


/**
 * Shortcode Preview
 * Register scripts & styles handler.
 *
 */
add_action('admin_enqueue_scripts', 'shortcode_preview_styles_scripts', 110);
function shortcode_preview_styles_scripts() {
    wp_register_style('shortcode-preview-css', plugins_url('css/style.css',__FILE__ ));
    wp_enqueue_style('shortcode-preview-css');
    wp_register_script('shortcode-preview-js', plugins_url('js/script.js',__FILE__ ), array('jquery'), '1.0', true);
    wp_enqueue_script('shortcode-preview-js');
}

/**
 * Shortcode Preview
 * Register admin menu
 *
 */
if(is_admin()) {
  add_action('admin_bar_menu', 'shortcode_preview_add_toolbar_items', 100);
}
function shortcode_preview_add_toolbar_items($admin_bar){
    $admin_bar->add_menu( array(
        'id'    => 'shortcode-preview-id',
        'title' => __('Shortcode Preview', 'shortcode-preview'),
        'href'  => '#',
        'meta'  => array(
          'class' => 'shortcode-previev'
        ),
    ));
}

/**
 * Shortcode Preview
 * Delete shortcode_preview meta on update
 *
 */
add_action('save_post', 'shortcode_preview_clear_mata', 10, 3);
function shortcode_preview_clear_mata($post_id, $post, $update) {
  if($update) {
    delete_post_meta($post_id, 'shortcode_preview');
  }
}

/**
 * Shortcode Preview
 * Get undelited previews ID
 *
 */
function shortcode_preview_count_undelited() {
  global $wpdb;
  $ids = array();
  $sql = $wpdb->get_results("SELECT * FROM {$wpdb->postmeta} as pm INNER JOIN {$wpdb->posts} as p ON pm.post_id = p.ID WHERE pm.meta_key = 'shortcode_preview'");
  if(count($sql) == 0) return;
  foreach($sql as $key => $value) {
    $ids[] = $value->ID;
  }
  return $ids;
}

/**
 * Shortcode Preview
 * Register clean
 *
 */
function shortcode_preview_clean_shortcode($shortcode) {
  $shortcode = str_replace(array('[', ']'), '', $shortcode);
  $shortcode = explode(' ', $shortcode);
  if($shortcode[0]) return $shortcode[0];
}

/**
 * Shortcode Preview
 * Add modal in footer
 *
 */
add_action('admin_footer', 'shortcode_preview_admin_footer_code');
function shortcode_preview_admin_footer_code() {
?>
  <div id="shortcode-preview-modal" class="shortcode-preview-modal">
         <div class="shortcode-preview-modal-content">
            <span id="shortcode-preview-close">&times;</span>
            <div class="shortcode-preview-header">
              <form type="POST" id="shortcode-preview-submit">
                 <input type="text" id="shortcode-preview-input" placeholder="Shortcode" class="regular-text">
                 <select id="shortcode-preview-type">
                 <?php
                  $post_types = get_option('shortcode_preview_types');
                  foreach($post_types as $key => $post_type) {
                 ?>
                   <option value="<?php echo $post_type; ?>"><?php echo $post_type; ?></option>
                 <?php
                  }
                 ?>
                 </select>
                 <input type="submit" name="submit" class="button button-primary" value="<?php _e('Preview', 'shortcode-preview'); ?>">
              </form>
              <div class="shortcode-preview-btn-wrap">
                 <a id="shortcode-preview-delete" class="button button-primary" data-id=""><?php _e('Delete preview', 'shortcode-preview'); ?></a>
                 <a id="shortcode-preview-preview" class="button button-primary" target="_blank"><?php _e('Open in new tab', 'shortcode-preview'); ?></a>
             </div>
            </div>
            <div class="loader"></div>
            <div class="shortcode-preview-result"></div>
         </div>
      </div>
<?php
}
