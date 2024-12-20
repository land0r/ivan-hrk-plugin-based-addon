<?php
/**
 * Class Data_Store.
 *
 * Handles data retrieval and caching.
 *
 * @since 1.0.0
 *
 * @package Ivan_Api_Based\Services
 */

namespace Ivan_Api_Based\Services;

/**
 * Data Transient cache storage.
 *
 * @since 1.0.0
 */
class Data_Store {

	/**
	 * Cache key for storing data.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private const CACHE_KEY = 'ivan_api_based_data';

	/**
	 * API client instance.
	 *
	 * @since 1.0.0
	 *
	 * @var Api_Client
	 */
	private $api_client;

	/**
	 * Constructor.
	 *
	 * Initializes the data store with an API client.
	 *
	 * @since 1.0.0
	 *
	 * @param Api_Client $api_client The API client for fetching data.
	 */
	public function __construct( Api_Client $api_client ) {
		$this->api_client = $api_client;
	}

	/**
	 * Retrieves data from the cache or API.
	 *
	 * @since 1.0.0
	 *
	 * @return array The retrieved data.
	 */
	public function get_data(): array {
		// Check if data is in the cache.
		$cached_data = get_transient( $this->get_cache_key() );

		if ( $cached_data ) {
			return $cached_data;
		}

		// If no cache, fetch data from the API.
		$data = $this->api_client->fetch_data();

		// Cache the data if it's valid.
		if ( ! empty( $data ) ) {
			set_transient( $this->get_cache_key(), $data, $this->get_cache_expiration() );
		}

		return $data;
	}

	/**
	 * Clears the cached data.
	 *
	 * @since 1.0.0
	 */
	public function clear_cache() {
		return delete_transient( $this->get_cache_key() );
	}

	/**
	 * Retrieves the cache key.
	 *
	 * @since 1.0.0
	 *
	 * @return string The cache key.
	 */
	public function get_cache_key(): string {
		return apply_filters( 'ivan_api_based_services_data_store_cache_key', self::CACHE_KEY );
	}

	/**
	 * Retrieves the cache expiration time.
	 *
	 * @since 1.0.0
	 *
	 * @return int The cache expiration time in seconds.
	 */
	public function get_cache_expiration(): int {
		return HOUR_IN_SECONDS;
	}
}
