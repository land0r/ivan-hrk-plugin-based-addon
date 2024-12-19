<?php
/**
 * Class Fetch_Data
 *
 * Handles AJAX requests for fetching plugin data.
 *
 * @since 1.0.0
 * @package Ivan_Api_Based\Ajax
 */

namespace Ivan_Api_Based\Ajax;

use Ivan_Api_Based\Services\Data_Store;

/**
 * Handles AJAX requests for fetching data.
 *
 * @since 1.0.0
 */
class Fetch_Data {

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
	 * Registers AJAX actions globally (frontend and backend).
	 *
	 * @since 1.0.0
	 */
	public function hooks(): void {
		// Register AJAX actions for both authenticated and unauthenticated users.
		add_action( 'wp_ajax_ivan_api_based_fetch_data', [ $this, 'handle_ajax_request' ] );
		add_action( 'wp_ajax_nopriv_ivan_api_based_fetch_data', [ $this, 'handle_ajax_request' ] );
	}

	/**
	 * Handles AJAX requests to fetch data.
	 *
	 * @since 1.0.0
	 */
	public function handle_ajax_request(): void {
		// Verify the nonce for security.
		$nonce = filter_input( INPUT_POST, 'nonce', FILTER_SANITIZE_STRING );

		if ( ! wp_verify_nonce( $nonce, 'ivan_api_based_nonce' ) ) {
			wp_send_json_error( [ 'message' => __( 'Invalid nonce.', 'ivan-api-based-addon' ) ], 400 );
			return;
		}

		// Fetch data from the data store.
		$data = $this->data_store->get_data();

		// Return data as JSON.
		if ( ! empty( $data ) ) {
			wp_send_json_success( $data );
		} else {
			wp_send_json_error( [ 'message' => __( 'No data available.', 'ivan-api-based-addon' ) ] );
		}
	}
}
