<?php
/**
 * Plugin Name: Comrade
 * Plugin URI: http://websemiotics.com/comrade/
 * Description: A customization framework that enhances site performance and appearance, with support for major plugins.
 * Version: 0.0.1
 * Author: Chris Seidl
 * Author URI: http://websemiotics.com/
 * Requires at least: 4.0.0
 * Tested up to: 4.0.0
 *
 * Text Domain: comrade
 * Domain Path: /languages/
 *
 * @package Comrade
 * @category Core
 * @author Chris Seidl
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Include the main Comrade class file.
 * 
 * @since 1.0.0
 */
require_once( 'classes/class-comrade.php' );

/**
 * Returns the main instance of Comrade to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Comrade
 */
function Comrade() {
	return Comrade::instance();
} // End Comrade()

add_action( 'plugins_loaded', 'Comrade' );
