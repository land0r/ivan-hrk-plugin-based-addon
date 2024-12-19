<?php
/**
 * ApiBased Bootstrap class
 *
 * @since   1.0.0
 * @license GPLv2 or later
 * @package Ivan_Api_Based
 * @author  Ivan Hryhorenko
 */

namespace Ivan_Api_Based;

use Exception;
use Auryn\Injector;
use Ivan_Api_Based\Admin\Admin_Page;
use Ivan_Api_Based\Ajax\Fetch_Data;
use Ivan_Api_Based\CLI\Refresh_Cache_Command;
use Ivan_Api_Based\Gutenberg\Table_Block;
use Ivan_Api_Based\Services\Data_Store;

/**
 * Class Plugin.
 *
 * @since 1.0.0
 *
 * @package Ivan_Api_Based
 */
class Plugin {

	/**
	 * Plugin version.
	 *
	 * @since 1.0.0
	 */
	const VERSION = '1.0.0';
	/**
	 * Dependency Injection Container.
	 *
	 * @since 1.0.0
	 *
	 * @var Injector
	 */
	private $injector;

	/**
	 * Plugin Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param Injector $injector Object.
	 */
	public function __construct( Injector $injector ) {
		$this->injector = $injector;
	}

	/**
	 * Run plugin.
	 *
	 * @since 1.0.0
	 *
	 * @throws Exception Object doesn't exist.
	 */
	public function run(): void {
		$this->injector->share( Data_Store::class );

		$this->injector->make( Table_Block::class )->hooks();

		$this->injector
			/**
			 * Define dependency for Admin_Page.
			 *
			 * @since 1.0.0
			 */
			->define(
				Admin_Page::class,
				[
					':data_store' => $this->injector->make( Data_Store::class ),
				]
			)
			->make( Admin_Page::class )->hooks();

		$this->injector
			/**
			 * Define dependency for Fetch_Data.
			 *
			 * @since 1.0.0
			 */
			->define(
				Fetch_Data::class,
				[
					':data_store' => $this->injector->make( Data_Store::class ),
				]
			)
			->make( Fetch_Data::class )->hooks();

		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			$this->injector
				/**
				 * Define dependency for Refresh_Cache_Command.
				 *
				 * @since 1.0.0
				 */
				->define(
					Refresh_Cache_Command::class,
					[
						':data_store' => $this->injector->make( Data_Store::class ),
					]
				)
				->make( Refresh_Cache_Command::class )->hooks();
		}
	}
}
