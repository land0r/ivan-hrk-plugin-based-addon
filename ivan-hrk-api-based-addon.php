<?php
/**
 * Plugin Name:       Ivan Hrk Api Based Addon
 * Description:       A WordPress plugin for retrieving and displaying data from a remote API with a custom Gutenberg block, admin panel, and WP CLI command.
 * Requires at least: 6.6
 * Requires PHP:      8.0
 * Version:           1.0.0
 * Author:            The WordPress Contributors
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
use Auryn\Injector;

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
 * Run plugin function.
 *
 * @since 1.0.0
 *
 * @throws Exception If something went wrong.
 */
function run_ivan_hrk_api_based_addon() {
	require_once IVAN_API_BASED_PATH . 'vendor/autoload.php';

	$injector = new Injector();

	( $injector->make( Plugin::class ) )->run();

	do_action( 'ivan_api_based_init', $injector );
}

add_action( 'plugins_loaded', 'run_ivan_hrk_api_based_addon' );
