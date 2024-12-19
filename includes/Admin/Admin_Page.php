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
	}

	/**
	 * Adds the admin menu page.
	 *
	 * @since 1.0.0
	 */
	public function add_menu_page(): void {
		add_menu_page(
			__( 'API Data', 'ivan-api-based-addon' ),
			__( 'API Data', 'ivan-api-based-addon' ),
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
		$data             = $this->data_store->get_data();
		$cache_expiration = $this->get_cache_expiration_human_readable();
		$cache_cleared    = filter_input( INPUT_GET, 'cache_cleared', FILTER_VALIDATE_BOOLEAN );
		?>
		<div id="api-based-header">
			<h1><?php esc_html_e( 'Ivan Dashboard', 'ivan-api-based-addon' ); ?></h1>
		</div>
		<div id="wpbody" role="main">
			<div id="wpbody-content">
				<div class="wrap" id="api-based-content">
					<?php if ( $cache_cleared ) : ?>
						<div class="notice notice-success">
							<p><?php esc_html_e( 'Cache cleared successfully.', 'ivan-api-based-addon' ); ?></p>
						</div>
					<?php endif; ?>

					<div class="api-based-page-title">
						<a href="#" class="tab active">General</a>
					</div>

					<div class="api-based-page-content">
						<table class="widefat fixed">
							<thead>
							<tr>
								<th><?php esc_html_e( 'Key', 'ivan-api-based-addon' ); ?></th>
								<th><?php esc_html_e( 'Value', 'ivan-api-based-addon' ); ?></th>
							</tr>
							</thead>
							<tbody>
							<?php if ( ! empty( $data ) ) : ?>
								<?php foreach ( $data as $key => $value ) : ?>
									<tr>
										<td><?php echo esc_html( $key ); ?></td>
										<td><?php echo esc_html( is_array( $value ) ? wp_json_encode( $value ) : $value ); ?></td>
									</tr>
								<?php endforeach; ?>
							<?php else : ?>
								<tr>
									<td colspan="2"><?php esc_html_e( 'No cached data available.', 'ivan-api-based-addon' ); ?></td>
								</tr>
							<?php endif; ?>
							</tbody>
						</table>

						<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
							<?php wp_nonce_field( 'clear_cache_action' ); ?>
							<input type="hidden" name="action" value="clear_cache">
							<p>
								<button type="submit" class="api-based-btn api-based-btn-orange">
									<?php esc_html_e( 'Clear Cache', 'ivan-api-based-addon' ); ?>
								</button>
							</p>

							<p>
								<?php if ( $cache_expiration ) : ?>
									<small>
										<?php
										// Translators: Cache expires on: %s.
										printf( esc_html__( 'Cache expires on: %s', 'ivan-api-based-addon' ), esc_html( $cache_expiration ) );
										?>
									</small>
								<?php else : ?>
									<span><?php esc_html_e( 'Cache is empty or expired.', 'ivan-api-based-addon' ); ?></span>
								<?php endif; ?>
							</p>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Retrieves the cache expiration in a human-readable format.
	 *
	 * @since 1.0.0
	 *
	 * @return string|null Human-readable expiration date or null if no cache exists.
	 */
	private function get_cache_expiration_human_readable(): ?string {
		$cache_key       = $this->data_store->get_cache_key();
		$expiration_time = get_option( '_transient_timeout_' . $cache_key );

		if ( $expiration_time && $expiration_time > time() ) {
			return gmdate( 'Y-m-d H:i:s', $expiration_time );
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
}