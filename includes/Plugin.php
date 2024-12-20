<?php
/**
 * ApiBased Bootstrap class
 *
 * @since   1.0.0
 * @license GPLv2 or later
 * @package Ivan_Api_Based
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
	 * Singleton instance of the plugin.
	 *
	 * @since 1.2.0
	 *
	 * @var Plugin
	 */
	private static $instance;

	/**
	 * Data store service instance.
	 *
	 * @since 1.2.0
	 *
	 * @var Data_Store
	 */
	private $data_store;

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
		$this->initialize_services();
		$this->initialize_components();
	}

	/**
	 * Initialize core services used across the plugin.
	 *
	 * @since 1.2.0
	 */
	private function initialize_services(): void {
		$this->data_store = new Data_Store( new Api_Client() );
	}

	/**
	 * Initialize and load all plugin components.
	 *
	 * @since 1.2.0
	 */
	private function initialize_components(): void {
		$this->initialize_gutenberg();
		$this->initialize_ajax();
		$this->initialize_admin();
		$this->initialize_wp_cli();
	}

	/**
	 * Initialize Gutenberg components.
	 *
	 * @since 1.2.0
	 */
	private function initialize_gutenberg(): void {
		$table_block = new Table_Block();

		$table_block->hooks();
	}

	/**
	 * Initialize AJAX handlers.
	 *
	 * @since 1.2.0
	 */
	private function initialize_ajax(): void {
		$fetch_data  = new Fetch_Data( $this->data_store );
		$clear_cache = new Clear_Cache( $this->data_store );

		$fetch_data->hooks();
		$clear_cache->hooks();
	}

	/**
	 * Initialize admin-related components.
	 *
	 * @since 1.2.0
	 */
	private function initialize_admin(): void {
		if ( is_admin() ) {
			$admin_page = new Admin_Page( $this->data_store );

			$admin_page->hooks();
		}
	}

	/**
	 * Initialize WP CLI commands.
	 *
	 * @since 1.2.0
	 */
	private function initialize_wp_cli(): void {
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			$refresh_cache_command = new Refresh_Cache_Command( $this->data_store );

			$refresh_cache_command->hooks();
		}
	}
}
