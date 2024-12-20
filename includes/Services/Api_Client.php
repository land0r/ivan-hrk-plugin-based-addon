<?php
/**
 * Class Api_Client
 *
 * Handles API requests to fetch data.
 *
 * @since 1.0.0
 * @package Ivan_Api_Based\Services
 */

namespace Ivan_Api_Based\Services;

/**
 * Api Client for getting data.
 *
 * @since 1.0.0
 */
class Api_Client {

	/**
	 * The API endpoint URL.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private const ENDPOINT = 'https://miusage.com/v1/challenge/1/';

	/**
	 * Fetch data from the API endpoint.
	 *
	 * Sends a GET request to the API and retrieves the response.
	 *
	 * @since 1.0.0
	 *
	 * @return array The decoded API response data.
	 */
	public function fetch_data(): array {
		$response = wp_remote_get( self::ENDPOINT );

		// Handle errors in the API request.
		if ( is_wp_error( $response ) ) {
			return [];
		}

		$body = wp_remote_retrieve_body( $response );

		// Decode the JSON response.
		$data = json_decode( $body, true );

		if ( json_last_error() !== JSON_ERROR_NONE ) {
			return [];
		}

		return $this->format_data( $data );
	}

	/**
	 * Format the API response data.
	 *
	 * Processes the raw data to remove numeric keys and format dates.
	 *
	 * @since 1.0.0
	 *
	 * @param array $data The raw API response data.
	 *
	 * @return array The formatted data.
	 */
	private function format_data( array $data ): array {
		if ( isset( $data['data']['rows'] ) && is_array( $data['data']['rows'] ) ) {
			// Convert rows from associative array with numeric keys to a simple indexed array.
			$data['data']['rows'] = array_values( $data['data']['rows'] );

			// Format the 'date' field to a human-readable format.
			foreach ( $data['data']['rows'] as &$row ) {
				if ( isset( $row['date'] ) ) {
					$row['date'] = gmdate( 'Y-m-d H:i:s', $row['date'] );
				}
			}
		}

		return $data;
	}
}
