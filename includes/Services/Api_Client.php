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
	private $endpoint = 'https://miusage.com/v1/challenge/1/';

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
		$response = wp_remote_get( $this->endpoint );

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

		return $data;
	}
}
