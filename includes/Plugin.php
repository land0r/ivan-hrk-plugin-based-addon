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
use Auryn\InjectionException;
use Ivan_Api_Based\Gutenberg\Table_Block;

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
		$this->injector->make( Table_Block::class )->hooks();
	}
}
