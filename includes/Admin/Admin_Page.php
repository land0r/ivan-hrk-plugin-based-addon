<?php
/**
 * Class Admin_Page
 *
 * Renders the admin page for displaying API data and cache controls.
 *
 * @since 1.0.0
 *
 * @package Ivan_Api_Based\Admin
 */

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
			[ $this, 'render_page' ]
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
		$data          = $this->data_store->get_data();
		$cache_cleared = filter_input( INPUT_GET, 'cache_cleared', FILTER_VALIDATE_BOOLEAN );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Ivan Dashboard', 'ivan-api-based-addon' ); ?></h1>

			<?php if ( $cache_cleared ) : ?>
				<div class="notice notice-success">
					<p><?php esc_html_e( 'Cache cleared successfully.', 'ivan-api-based-addon' ); ?></p>
				</div>
			<?php endif; ?>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
				<?php wp_nonce_field( 'clear_cache_action' ); ?>
				<input type="hidden" name="action" value="clear_cache">
				<button type="submit" class="button button-secondary">
					<?php esc_html_e( 'Clear Cache', 'ivan-api-based-addon' ); ?>
				</button>
			</form>

			<h2><?php esc_html_e( 'Cached Data', 'ivan-api-based-addon' ); ?></h2>
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
		</div>
		<?php
	}
}
