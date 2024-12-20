<?php
/**
 * Class Clear_Cache
 *
 * Handles AJAX requests for clearing the cache.
 *
 * @since 1.0.0
 * @package Ivan_Api_Based\Ajax
 */

namespace Ivan_Api_Based\Ajax;

use Ivan_Api_Based\Services\Data_Store;

/**
 * Handles cache clearing via AJAX.
 *
 * @since 1.0.0
 */
class Clear_Cache {

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
	 * Registers the AJAX action for clearing the cache.
	 *
	 * @since 1.0.0
	 */
	public function hooks(): void {
		// Register AJAX action for authenticated users only.
		add_action( 'wp_ajax_ivan_api_based_clear_cache', [ $this, 'handle_clear_cache_request' ] );
	}

	/**
	 * Handles the AJAX request for clearing the cache.
	 *
	 * @since 1.0.0
	 */
	public function handle_clear_cache_request(): void {
		// Check user capability.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( [ 'message' => __( 'Unauthorized access.', 'ivan-hrk-api-based-addon' ) ], 403 );
			return;
		}

		// Verify the nonce for security.
		$nonce = filter_input( INPUT_POST, 'nonce', FILTER_SANITIZE_STRING );

		if ( ! wp_verify_nonce( $nonce, 'ivan_api_based_nonce' ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'ivan-hrk-api-based-addon' ) ], 400 );
			return;
		}

		// Clear the cache using the Data Store.
		$clearing_status = $this->data_store->clear_cache();

		if ( ! $clearing_status ) {
			wp_send_json_error(
				[
					'message' => __( 'Cache not cleared!', 'ivan-hrk-api-based-addon' ),
				]
			);
		}

		// Respond with success.
		wp_send_json_success(
			[
				'message' => __( 'Cache cleared successfully.', 'ivan-hrk-api-based-addon' ),
			]
		);
	}
}
