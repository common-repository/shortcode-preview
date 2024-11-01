<?php
add_action('admin_menu', 'shortcode_preview_create_menu');
function shortcode_preview_create_menu() {
  add_submenu_page('options-general.php', __('Shortcode Preview', 'shortcode-preview'), __('Shortcode Preview', 'shortcode-preview'), 10, 'shortcode-preview' , 'shortcode_preview_settings_page');
	add_action( 'admin_init', 'shortcode_preview_register_plugin_settings' );
}


function shortcode_preview_register_plugin_settings() {
  if(!get_option('shortcode_preview_types')) {
    add_option('shortcode_preview_types', array('post', 'page'));
  }
	register_setting( 'shortcode-preview-settings', 'shortcode_preview_types' );
}

function shortcode_preview_settings_page() {
  global $wpdb;
  $types = get_option('shortcode_preview_types');
  $args = array(
   'public'   => true,
  );
  $post_types = get_post_types($args, 'names');
?>
<div class="wrap">
   <div class="shortcode-previev-admin-main">
      <h1><?php _e('Shortcode Preview', 'shortcode-preview'); ?></h1>
      <div class="shortcode-previev-admin-main-left">
         <div class="shortcode-previev-admin-block">
            <h2><?php _e('Alloed post types', 'shortcode-preview'); ?></h2>
            <form method="post" action="options.php">
               <?php settings_fields( 'shortcode-preview-settings' ); ?>
               <?php do_settings_sections( 'shortcode-preview-settings' ); ?>
               <table class="form-table">
                  <tbody>
                     <tr class="panels-setting">
                        <th scope="row"><label><?php _e('Post Types', 'shortcode-preview'); ?></label></th>
                        <td>
                           <?php
                              foreach($post_types as $key => $post_type) {
                              $checked = (in_array($post_type, $types)) ? 'checked' : '' ;
                              ?>
                           <label class="shortcode-preview-widefat" for="<?php echo esc_attr($post_type); ?>">
                           <input name="shortcode_preview_types[]" type="checkbox" id="<?php echo esc_attr($post_type); ?>" value="<?php echo esc_attr($post_type); ?>" <?php echo $checked; ?>>
                           <?php echo esc_attr($post_type); ?></label>
                           <?php
                              }
                              ?>
                        </td>
                     </tr>
                  </tbody>
               </table>
               <?php submit_button(); ?>
            </form>
         </div>
         <div class="shortcode-previev-admin-block">
            <h2><?php _e('Clear previews', 'shortcode-preview'); ?></h2>
            <div class="shortcode-preview-delete-all-message"></div>
            <?php
               $ids = shortcode_preview_count_undelited();
               if(count($ids) > 0) {
               ?>
            <a id="shortcode-preview-delete-all" class="button button-primary"><?php echo sprintf(__( 'Delete all %s previews', 'text_domain' ), count($ids)); ?></a>
            <?php
               } else {
               ?>
            <p><?php _e('No previews', 'shortcode-preview'); ?></p>
            <?php
               }
               ?>
         </div>
      </div>
      <div class="shortcode-previev-admin-main-right">
         <div class="shortcode-previev-admin-block">
            <h2><?php _e('Get support', 'shortcode-preview'); ?></h2>
            <p>Email: <a href="mail:plugins@xhats.com">plugins@xhats.com</a></p>
            <p>WordPress forum: <a href="https://wordpress.org/support/plugin/shortcode-preview/">wordpress.org</a></p>
            <img src="<?php echo plugin_dir_url(__FILE__).'img/XHATS.png'; ?>">
         </div>
      </div>
   </div>
</div>
<?php }
