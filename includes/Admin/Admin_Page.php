<?php

namespace Ivan_Api_Based\Admin;

use Ivan_Api_Based\Services\Data_Store;

/**
 * Admin page render class.
 *
 * @since 1.0.0
 */
class Admin_Page {

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
	 * Registers admin menu hooks.
	 *
	 * @since 1.0.0
	 */
	public function hooks(): void {
		add_action( 'admin_menu', [ $this, 'add_menu_page' ] );
		add_action( 'admin_post_clear_cache', [ $this, 'handle_clear_cache' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	/**
	 * Adds the admin menu page.
	 *
	 * @since 1.0.0
	 */
	public function add_menu_page(): void {
		add_menu_page(
			__( 'Ivan API Data', 'ivan-api-based-addon' ),
			__( 'Ivan API Data', 'ivan-api-based-addon' ),
			'manage_options',
			'ivan-api-based-addon',
			[ $this, 'render_page' ],
			'dashicons-admin-tools',
			4
		);
	}

	/**
	 * Handles the clear cache action.
	 *
	 * @since 1.0.0
	 */
	public function handle_clear_cache(): void {
		// Verify nonce for security.
		check_admin_referer( 'clear_cache_action' );

		$this->data_store->clear_cache();

		wp_safe_redirect( admin_url( 'admin.php?page=ivan-api-based-addon&cache_cleared=1' ) );
		exit;
	}

	/**
	 * Renders the admin page.
	 *
	 * @since 1.0.0
	 */
	public function render_page(): void {
		$data_storage     = $this->data_store->get_data();
		$cache_expiration = $this->get_cache_remaining_time();
		?>
		<div id="api-based-header">
			<h1><?php esc_html_e( 'Ivan Dashboard', 'ivan-api-based-addon' ); ?></h1>
		</div>
		<div id="wpbody" role="main">
			<div id="wpbody-content">
				<div class="wrap" id="api-based-content">
					<div class="api-based-page-title">
						<a href="#" class="tab active">General</a>
					</div>

					<div class="api-based-page-content">
						<?php $this->render_table( $data_storage ); ?>

						<p>
							<button type="button" id="clear-cache-btn" class="api-based-btn api-based-btn-orange">
								<?php esc_html_e( 'Clear Cache', 'ivan-api-based-addon' ); ?>
							</button>
						</p>

						<p>
							<?php if ( $cache_expiration ) : ?>
								<small>
									<?php
									// Translators: Cache will be updated in: %s.
									printf( esc_html__( 'Cache will be updated in: %s', 'ivan-api-based-addon' ), esc_html( $cache_expiration ) );
									?>
								</small>
							<?php else : ?>
								<span><?php esc_html_e( 'Cache is empty or expired.', 'ivan-api-based-addon' ); ?></span>
							<?php endif; ?>
						</p>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Renders the admin table.
	 *
	 * @param array $data_storage Cache result.
	 *
	 * @since 1.0.0
	 */
	public function render_table( array $data_storage ) {
		$storage_data = $data_storage['data'] ?? [];
		$headers      = $storage_data['headers'] ?? [];
		$rows         = $storage_data['rows'] ?? [];

		if ( empty( $rows ) || empty( $headers ) ) {
			esc_html_e( 'No cached data available.', 'ivan-api-based-addon' );
			return;
		}
		?>
		<table class="widefat fixed">
			<?php $this->render_table_header( $headers ); ?>
			<?php $this->render_table_body( $rows ); ?>
		</table>
		<?php
	}

	/**
	 * Renders the admin table header.
	 *
	 * @param array $headers List.
	 *
	 * @since 1.0.0
	 */
	public function render_table_header( array $headers ) {
		?>
		<thead>
		<tr>
			<?php foreach ( $headers as $header ) : ?>
				<th><?php echo esc_html( $header ); ?></th>
			<?php endforeach; ?>
		</tr>
		</thead>
		<?php
	}

	/**
	 * Renders the admin table body.
	 *
	 * @param array $rows List.
	 *
	 * @since 1.0.0
	 */
	public function render_table_body( array $rows ) {
		?>
		<tbody>
		<?php foreach ( $rows as $row ) : ?>
			<tr>
				<?php foreach ( $row as $cell ) : ?>
					<td><?php echo esc_html( $cell ); ?></td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
		</tbody>
		<?php
	}

	/**
	 * Calculates the remaining cache time in a human-readable format.
	 *
	 * @since 1.0.0
	 *
	 * @return string|null Remaining time or null if the cache is empty/expired.
	 */
	private function get_cache_remaining_time() {
		$cache_key       = $this->data_store->get_cache_key();
		$expiration_time = get_option( '_transient_timeout_' . $cache_key );

		if ( $expiration_time && $expiration_time > time() ) {
			$remaining_seconds = $expiration_time - time();

			// Calculate hours and minutes.
			$hours   = floor( $remaining_seconds / 3600 );
			$minutes = floor( ( $remaining_seconds % 3600 ) / 60 );

			// Generate human-readable remaining time.
			if ( $hours > 0 ) {
				return sprintf( _n( '%d hour', '%d hours', $hours, 'ivan-api-based-addon' ), $hours ) . ( $minutes > 0 ? sprintf( _n( ', %d minute', ', %d minutes', $minutes, 'ivan-api-based-addon' ), $minutes ) : '' );
			} elseif ( $minutes > 0 ) {
				return sprintf( _n( '%d minute', '%d minutes', $minutes, 'ivan-api-based-addon' ), $minutes );
			} else {
				return __( 'Less than a minute', 'ivan-api-based-addon' );
			}
		}

		return null;
	}

	/**
	 * Enqueue styles for the admin area.
	 *
	 * @param string $hook Hook.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles( string $hook ): void {
		if ( $hook !== 'toplevel_page_ivan-api-based-addon' ) {
			return;
		}

		wp_enqueue_style(
			'api-based-admin-styles',
			IVAN_API_BASED_URL . '/build/css/admin.css',
			[],
			'1.0.0'
		);
	}

	/**
	 * Enqueue scripts for the admin area.
	 *
	 * @param string $hook Hook.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts( string $hook ) {
		if ( $hook !== 'toplevel_page_ivan-api-based-addon' ) {
			return;
		}

		wp_enqueue_script(
			'api-based-admin-scripts',
			IVAN_API_BASED_URL . '/build/js/admin.js',
			[ 'jquery' ],
			'1.0.0',
			true
		);

		// Handle for the view script.
		wp_localize_script(
			'api-based-admin-scripts',
			'IvanApiBasedAddon',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'ivan_api_based_nonce' ),
			]
		);
	}
}
