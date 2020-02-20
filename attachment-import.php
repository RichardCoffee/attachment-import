<?php
/**
 *  Post Attachment Mods
 *
 * @package   attachment-import
 * @author    Richard Coffee <richard.coffee@gmail.com>
 * @copyright 2020 Richard Coffee
 * @license   GPLv2  <need uri here>
 * @link      link
 *
 * @wordpress-plugin
 * Plugin Name:       Post Attachment Mods
 * Plugin URI:        rtcenterprises.net
 * Description:       Modify post content to fix lost attachments.
 * Version:           0.1.0
 * Requires at least: 4.7.0
 * Requires WP:       4.7.0
 * Tested up to:      5.3.2
 * Requires PHP:      5.3.6
 * Author:            Richard Coffee
 * Author URI:        http://rtcenterprises.net
 * GitHub URI:        github uri needed if using plugin-update-checker
 * License:           GPLv2
 * License URI:       uri where license can be found and read
 * Text Domain:       rtc-attach-import
 * Domain Path:       /languages
 * Tags:              what, where, when, who, how, why
 */

defined( 'ABSPATH' ) || exit;
/*
# https://github.com/helgatheviking/Nav-Menu-Roles/blob/master/nav-menu-roles.php
if ( ! defined('ABSPATH') || ! function_exists( 'is_admin' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
} //*/

define( 'PAM_ATTACH_DIR' , plugin_dir_path( __FILE__ ) );

require_once( 'functions.php' );

$plugin = PAM_Plugin_Attach::get_instance( array( 'file' => __FILE__ ) );

register_activation_hook( __FILE__, array( 'PAM_Register_Plugin', 'activate' ) );
