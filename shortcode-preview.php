<?php
/*
Plugin Name: Shortcode Preview
Plugin URI:  https://wordpress.org/plugins/shortcode-preview/
Description: Preview any shortcode in one click
Version:     1.2
Author:      ikosotov
Author URI:  https://profiles.wordpress.org/ikosotov/
Text Domain: shortcode-preview
*/



/**
 * Security check
 * Prevent direct access to the file.
 *
 */
if(!defined('ABSPATH')) {
	exit;
}



/**
 * Include plugin files
 *
 */
include_once(plugin_dir_path(__FILE__).'includes/i18n.php');
include_once(plugin_dir_path(__FILE__).'includes/init.php');
include_once(plugin_dir_path(__FILE__).'includes/ajax.php');
include_once(plugin_dir_path(__FILE__).'includes/admin.php');
