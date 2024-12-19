<?php
/**
 * Handles Gutenberg Table Block registration.
 *
 * @since 1.0.0
 *
 * @package Ivan_Api_Based\Gutenberg
 */

namespace Ivan_Api_Based\Gutenberg;

/**
 * Class Table_Block.
 *
 * @since 1.0.0
 */
class Table_Block {

	/**
	 * Initialize hooks for registering the block.
	 *
	 * @since 1.0.0
	 */
	public function hooks() {
		add_action( 'init', [ $this, 'register_block' ] );
	}

	/**
	 * Registers the block using metadata from block.json.
	 *
	 * @since 1.0.0
	 */
	public function register_block() {
		register_block_type( PLUGIN_NAME_PATH . '/build' );
	}
}
