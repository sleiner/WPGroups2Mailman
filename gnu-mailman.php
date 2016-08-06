<?php
/**
 * GNU WordPress Mailman Integration
 *
 * @package   Mailman
 *
 * @author	Ryan Gyure <me@ryan.gy>, Simon Leiner <simon@leiner.me>
 * @license   GPL-2.0+
 * @link	  https://github.com/sleiner/WPGroups2Mailman
 * @copyright 2014 Ryan Gure, 2016 Simon Leiner
 *
 * @wordpress-plugin
 * Plugin Name:	   WPGroups2Mailman
 * Plugin URI:		https://github.com/sleiner/WPGroups2Mailman
 * Description:	   GNU-Mailman integration with Wordpress
 * Version:		   1.1.0
 * Author:			Ryan Gyure, Simon Leiner
 * Author URI:		http://www.ryangyure.com/, https://www.leiner.me
 * License:		   GPL-2.0+
 * License URI:	   http://www.gnu.org/licenses/gpl-2.0.txt
 * GitHub Plugin URI: https://github.com/sleiner/WPGroups2Mailman
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'GM_PLUGIN_DIR' ) ) {
	define( 'GM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'GM_PLUGIN_VERSION' ) ) {
	define( 'GM_PLUGIN_VERSION', '1.0.5' );
}

if ( ! defined( 'GM_PLUGIN_FILE' ) ) {
	define( 'GM_PLUGIN_FILE', __FILE__ );
}
if ( defined( 'GROUPS_CORE_VERSION' ) ) {
	define( 'GM_GROUPS_ACTIVE', true );
}

// File Includes.
require_once( GM_PLUGIN_DIR . 'includes/install.php' );
require_once( GM_PLUGIN_DIR . 'includes/Mailman.php' );
require_once( GM_PLUGIN_DIR . 'includes/functions.php' );
require_once( GM_PLUGIN_DIR . 'includes/auto-functions.php' );

if ( defined( 'GM_GROUPS_ACTIVE' ) ) {
	require_once( GM_PLUGIN_DIR . 'includes/groups-integration.php' );
} else {
	require_once( GM_PLUGIN_DIR . 'includes/user-forms.php' );
}


// Admin Only Includes.
if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {
	require_once( GM_PLUGIN_DIR . 'includes/admin/menu-links.php' );
	require_once( GM_PLUGIN_DIR . 'includes/admin/process-data.php' );
	require_once( GM_PLUGIN_DIR . 'includes/admin/settings-page.php' );
	require_once( GM_PLUGIN_DIR . 'includes/admin/mailing-lists-page.php' );
	require_once( GM_PLUGIN_DIR . 'includes/admin/admin-page.php' );
}
?>
