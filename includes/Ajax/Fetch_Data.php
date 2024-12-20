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
		check_ajax_referer( 'ivan_api_based_nonce', 'nonce' );

		// Get the visible columns from the request.
		$visible_columns = $this->get_visible_columns();

		// Fetch the filtered data.
		$data = $this->fetch_filtered_data( $visible_columns );

		// Send the response.
		$this->send_response( $data );
	}

	/**
	 * Retrieves visible columns from the AJAX request.
	 *
	 * @since 1.0.0
	 *
	 * @return array The sanitized array of visible column IDs.
	 */
	private function get_visible_columns(): array {
		$columns = filter_input( INPUT_POST, 'visible_columns', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		if ( is_array( $columns ) ) {
			return array_map( 'sanitize_text_field', $columns );
		}

		return [];
	}

	/**
	 * Fetches data from the data store, filtered by visible columns if provided.
	 *
	 * @since 1.0.0
	 *
	 * @param array $visible_columns The array of visible column IDs.
	 *
	 * @return array The filtered data.
	 */
	private function fetch_filtered_data( array $visible_columns ): array {
		$storage_data = $this->data_store->get_data();

		// If no visible columns are specified, return all data.
		if ( empty( $visible_columns ) ) {
			return $storage_data;
		}

		return [
			'title' => $storage_data['title'],
			'data'  => $this->filter_storage_data( $storage_data, $visible_columns ),
		];
	}

	/**
	 * Filters data from the data store.
	 *
	 * @since 1.0.0
	 *
	 * @param array $storage_data    The data to send in the response.
	 * @param array $visible_columns The array of visible column IDs.
	 *
	 * @return array The filtered data.
	 */
	private function filter_storage_data( array $storage_data, array $visible_columns ) {
		// Filter headers and rows based on visible columns.
		$filtered_headers = [];
		$filtered_rows    = [];

		foreach ( $storage_data['data']['headers'] as $index => $header ) {
			// Map column indices to their IDs.
			$column_id = array_keys( $storage_data['data']['rows'][0] )[ $index ] ?? null;

			if ( in_array( $column_id, $visible_columns, true ) ) {
				$filtered_headers[] = $header;

				foreach ( $storage_data['data']['rows'] as $row_index => $row ) {
					$filtered_rows[ $row_index ][ $column_id ] = $row[ $column_id ] ?? null;
				}
			}
		}

		return [
			'headers' => $filtered_headers,
			'rows'    => array_values( $filtered_rows ),
		];
	}

	/**
	 * Sends the AJAX response.
	 *
	 * @since 1.0.0
	 *
	 * @param array $storage_data The data to send in the response.
	 *
	 * @return void
	 */
	private function send_response( array $storage_data ): void {
		if ( ! empty( $storage_data['data']['headers'] ) && ! empty( $storage_data['data']['rows'] ) ) {
			wp_send_json_success( $storage_data );
		} else {
			wp_send_json_error( [ 'message' => __( 'No data available.', 'ivan-hrk-api-based-addon' ) ] );
		}
	}
}
