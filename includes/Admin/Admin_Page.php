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
			__( 'Ivan API Data', 'ivan-hrk-api-based-addon' ),
			__( 'Ivan API Data', 'ivan-hrk-api-based-addon' ),
			'manage_options',
			'ivan-hrk-api-based-addon',
			[ $this, 'render_page' ],
			'dashicons-admin-tools',
			4
		);
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
			<h1><?php esc_html_e( 'Ivan Dashboard', 'ivan-hrk-api-based-addon' ); ?></h1>
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
								<?php esc_html_e( 'Clear Cache', 'ivan-hrk-api-based-addon' ); ?>
							</button>
						</p>

						<p>
							<?php if ( $cache_expiration ) : ?>
								<small>
									<?php echo esc_html( $cache_expiration ); ?>
								</small>
							<?php else : ?>
								<span><?php esc_html_e( 'Cache is empty or expired.', 'ivan-hrk-api-based-addon' ); ?></span>
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
			esc_html_e( 'No cached data available.', 'ivan-hrk-api-based-addon' );
			return;
		}
		?>
		<table class="wp-list-table widefat fixed striped table-view-list">
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
	 * @return string Remaining time or null if the cache is empty/expired.
	 */
	private function get_cache_remaining_time() {
		$cache_key       = $this->data_store->get_cache_key();
		$expiration_time = get_option( '_transient_timeout_' . $cache_key );

		if ( $expiration_time && $expiration_time > time() ) {
			// Use human_time_diff for a simpler, human-readable format.
			return sprintf(
				/* translators: Expires in some time. */
				__( 'Expires in %s', 'ivan-hrk-api-based-addon' ),
				human_time_diff( time(), $expiration_time )
			);
		}

		return __( 'No cached data.', 'ivan-hrk-api-based-addon' );
	}

	/**
	 * Enqueue styles for the admin area.
	 *
	 * @param string $hook Hook.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles( string $hook ): void {
		if ( $hook !== 'toplevel_page_ivan-hrk-api-based-addon' ) {
			return;
		}

		wp_enqueue_style(
			'api-based-admin-styles',
			IVAN_API_BASED_URL . '/build/css/admin.css',
			[],
			IVAN_API_BASED_VERSION
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
		if ( $hook !== 'toplevel_page_ivan-hrk-api-based-addon' ) {
			return;
		}

		wp_enqueue_script(
			'api-based-admin-scripts',
			IVAN_API_BASED_URL . '/build/js/admin.js',
			[ 'jquery' ],
			IVAN_API_BASED_VERSION,
			true
		);

		// Handle for the view script.
		wp_localize_script(
			'api-based-admin-scripts',
			'IvanApiBasedAddon',
			[
				'ajax_url'      => admin_url( 'admin-ajax.php' ),
				'nonce'         => wp_create_nonce( 'ivan_api_based_nonce' ),
				'clearingCache' => __( 'Clearing...', 'ivan-hrk-api-based-addon' ),
				'errorMessage'  => __( 'Error clearing cache.', 'ivan-hrk-api-based-addon' ),
				'btnText'       => __( 'Clear Cache', 'ivan-hrk-api-based-addon' ),
			]
		);
	}
}
