<?php
/**
 * Contract for a self-contained theme feature.
 *
 * An implementation wires all of its own WordPress hooks inside `register()`
 * — it does not call itself. The theme calls `register()` once, from the
 * boot list in `inc/class-loader.php`.
 *
 * @package Ufo_Boilerplate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! interface_exists( '_Ufo_Service' ) ) {

	interface _Ufo_Service {

		/**
		 * Registers this feature's WordPress hooks. Called once during boot.
		 */
		public static function register();
	}
}
