<?php
/**
 * Plugin Name:       Ivan Hrk Api Based Addon
 * Description:       A WordPress plugin for retrieving and displaying data from a remote API with a custom Gutenberg block, admin panel, and WP CLI command.
 * Requires at least: 6.6
 * Requires PHP:      8.0
 * Version:           1.2.1
 * Author:            Ivan Hryhorenko
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       ivan-hrk-api-based-addon
 *
 * @package Ivan_Api_Based
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Ivan_Api_Based\Plugin;

/**
 * Path to the plugin root directory.
 *
 * @since 1.0.0
 */
define( 'IVAN_API_BASED_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Url to the plugin root directory.
 *
 * @since 1.0.0
 */
define( 'IVAN_API_BASED_URL', plugin_dir_url( __FILE__ ) );

/**
 * Plugin version.
 *
 * @since 1.0.0
 */
define( 'IVAN_API_BASED_VERSION', '1.2.1' );

require_once IVAN_API_BASED_PATH . 'vendor/autoload.php';

Plugin::get_instance();
