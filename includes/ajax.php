<?php

/**
 * Shortcode Preview
 * Generate preview
 *
 */
add_action('wp_ajax_shortcode_preview_generate_preview', 'shortcode_preview_generate_preview');
function shortcode_preview_generate_preview() {
	$shortcode = $_REQUEST['shortcode'];
	$clean_shortcode = shortcode_preview_clean_shortcode($_REQUEST['shortcode']);
	$type = $_REQUEST['type'];
  $post_types = get_option('shortcode_preview_types');

	if(!current_user_can('administrator')) {
		$return = array(
			'status' => false,
			'message' => __('Admin only!', 'shortcode-preview')
		);
	} elseif(!in_array($type, $post_types)) {
		$return = array(
			'status' => false,
			'message' => __('This post type is now allowed!', 'shortcode-preview')
		);
  } elseif(!shortcode_exists($clean_shortcode)) {
		$return = array(
			'status' => false,
			'message' => __('Shortcode does`t exists!', 'shortcode-preview')
		);
  } else {
    $post_id = wp_insert_post(array (
       'post_type'    => $type,
       'post_title'   => "Shortcode preview [{$clean_shortcode}]",
       'post_content' => $shortcode,
       'post_status'  => 'publish',
    ));
    add_post_meta($post_id, 'shortcode_preview', $post_id, true);
		$return = array(
			'status' => true,
			'data'   => array('post_id' => $post_id, 'url' => get_the_permalink($post_id))
		);
  }
	wp_send_json($return);
	wp_die();
}

/**
 * Shortcode Preview
 * Delete preview
 *
 */
add_action('wp_ajax_shortcode_preview_delete_preview', 'shortcode_preview_delete_preview');
function shortcode_preview_delete_preview() {
	$post_id = $_REQUEST['post_id'];
  if(!current_user_can('administrator')) {
		$return = array(
			'status' => false,
			'message' => __('Admin only!', 'shortcode-preview')
		);
  } elseif(!get_post_status($post_id)) {
		$return = array(
			'status' => false,
			'message' => __('Preview does`t exists!', 'shortcode-preview')
		);
  } elseif(get_post_meta($post_id, 'shortcode_preview', true) != $post_id) {
		$return = array(
			'status' => false,
			'message' => __('Wrong ID!', 'shortcode-preview')
		);
  } else {
    wp_delete_post($post_id, true);
		$return = array(
			'status' => true,
			'message' => __('The preview was deleted!', 'shortcode-preview')
		);
  }
	wp_send_json($return);
	wp_die();
}


/**
 * Shortcode Preview
 * Delete all previews
 *
 */
add_action('wp_ajax_shortcode_preview_delete_preview_all', 'shortcode_preview_delete_preview_all');
function shortcode_preview_delete_preview_all() {
  if(!current_user_can('administrator')) {
		$return = array(
			'status' => false,
			'message' => __('Admin only!', 'shortcode-preview')
		);
  } else {
    $ids = shortcode_preview_count_undelited();
    if(count($ids) > 0) {
      foreach($ids as $key => $id) {
        wp_delete_post($id, true);
      }
    }
		$return = array(
			'status' => true,
			'message' => __('All the previews was deleted!', 'shortcode-preview')
		);
  }
	wp_send_json($return);
	wp_die();
}
