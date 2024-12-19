<?php
/**
 * Class Refresh_Cache_Command
 *
 * Provides a WP CLI command to force refresh of cached data.
 *
 * @since 1.0.0
 * @package Ivan_Api_Based\CLI
 */

namespace Ivan_Api_Based\CLI;

use WP_CLI;
use Ivan_Api_Based\Services\Data_Store;

/**
 * WP CLI command to refresh cached data.
 *
 * @since 1.0.0
 */
class Refresh_Cache_Command {

	/**
	 * Data store instance.
	 *
	 * @since 1.0.0
	 *
	 * @var Data_Store
	 */
	private $data_store;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Data_Store $data_store The data store instance.
	 */
	public function __construct( Data_Store $data_store ) {
		$this->data_store = $data_store;
	}

	/**
	 * Registers the WP CLI command.
	 *
	 * @since 1.0.0
	 */
	public function hooks(): void {
		WP_CLI::add_command( 'ivan-api-based refresh-cache', [ $this, 'refresh_cache' ] );
	}

	/**
	 * Refreshes the cache by clearing the transient.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args       Command arguments.
	 * @param array $assoc_args Associative command arguments.
	 */
	public function refresh_cache( $args, $assoc_args ): void { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		$this->data_store->clear_cache();

		WP_CLI::success( __( 'Cache cleared successfully. Data will be refreshed on the next AJAX request.', 'ivan-api-based-addon' ) );
	}
}
