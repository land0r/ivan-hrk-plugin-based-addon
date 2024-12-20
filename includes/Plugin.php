<?php
/**
 * ApiBased Bootstrap class
 *
 * @since   1.0.0
 * @license GPLv2 or later
 * @package Ivan_Api_Based
 * @author  Ivan Hryhorenko
 */

namespace Ivan_Api_Based;

use Ivan_Api_Based\Admin\Admin_Page;
use Ivan_Api_Based\Ajax\Clear_Cache;
use Ivan_Api_Based\Ajax\Fetch_Data;
use Ivan_Api_Based\CLI\Refresh_Cache_Command;
use Ivan_Api_Based\Gutenberg\Table_Block;
use Ivan_Api_Based\Services\Api_Client;
use Ivan_Api_Based\Services\Data_Store;

defined( 'ABSPATH' ) || exit;

/**
 * Class Plugin.
 *
 * @since 1.0.0
 */
final class Plugin {

	/**
	 * Instance of this object.
	 *
	 * @since 1.2.0
	 *
	 * @var Plugin $instance Singleton.
	 */
	private static $instance;

	/**
	 * Get the current instance.
	 *
	 * @since 1.2.0
	 *
	 * @return Plugin Current object instance.
	 */
	public static function get_instance(): Plugin {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		$this->loader();
	}

	/**
	 * Load all required classes.
	 *
	 * @since 1.2.0
	 */
	private function loader(): void {
		// Initialize shared services.
		$data_store = new Data_Store( new Api_Client() );

		// Load Gutenberg block functionality.
		$table_block = new Table_Block();

		$table_block->hooks();

		// Load AJAX functionality.
		$fetch_data = new Fetch_Data( $data_store );

		$fetch_data->hooks();

		$clear_cache = new Clear_Cache( $data_store );

		$clear_cache->hooks();

		// Load WP CLI functionality if available.
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			$refresh_cache_command = new Refresh_Cache_Command( $data_store );

			$refresh_cache_command->hooks();
		}

		if ( is_admin() ) {
			// Load Admin Page.
			$admin_page = new Admin_Page( $data_store );

			$admin_page->hooks();
		}
	}
}
