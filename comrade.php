<?php
/**
 * Plugin Name: Comrade
 * Plugin URI: http://websemiotics.com/comrade/
 * Description: A customization framework that enhances site performance and 
 * appearance, with support for major plugins.
 * Version: 0.0.1
 * Author: Chris Seidl
 * Author URI: http://websemiotics.com/
 * Requires at least: 4.0.0
 * Tested up to: 4.0.0
 *
 * Text Domain: comrade
 * Domain Path: /languages/
 *
 * @author Chris Seidl
 */

/**
 * Define Comrade's namespace.
 * 
 * @since 1.0.0
 */
namespace Comrade;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Define the main Comrade autoloader
 * 
 * Uses PHP's spl_autoload_register() to autoload Comrade's class files.
 * 
 * @since 1.0.0
 */
function autoload( $classname ) {
	$sep = DIRECTORY_SEPARATOR;
	$namespace = strtolower( __NAMESPACE__ );
	$directory = WP_PLUGIN_DIR . $sep . $namespace . $sep . 'classes' . $sep;
	
	$class = str_replace( '_', '-', strtolower( $classname ) );
	
	$file = explode( '\\', $class );
	if ( is_array( $file ) ) {
		$file = end( $file );
	}
	$file = $directory . 'class-' . $file . '.php';
	
	if ( file_exists( $file ) ) {
		require_once( $file );
	}
}

/**
 * Register the autoload function.
 * 
 * @since 1.0.0
 */
spl_autoload_register( __NAMESPACE__ . '\\autoload' );

/**
 * Returns Comrade Engine class to load the plugin.
 *
 * @since  1.0.0
 * @return void
 */
function load() {
	return new Classes\Engine();
}

add_action( 'plugins_loaded', 'Comrade\load' );