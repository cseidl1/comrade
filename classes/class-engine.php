<?php
/**
 * Define Comrade's namespace.
 * 
 * @since  1.0.0
 */
namespace Comrade\Classes;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 * Comrade Engine Class.
 * 
 * Loads everything in the Comrade plugin.
 *
 * @since  1.0.0
 */
class Engine {
	/**
	 * The admin object.
	 * 
	 * @var    object
	 * @access public
	 * @since  1.0.0
	 */
	public $admin;

	/**
	 * The plugin directory path.
	 * 
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $plugin_path;

	/**
	 * The plugin directory URL.
	 * 
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $plugin_url;

	/**
	 * The post types we're registering.
	 * 
	 * @var    array
	 * @access public
	 * @since  1.0.0
	 */
	public $post_types = array();

	/**
	 * The settings object.
	 * 
	 * @var    object
	 * @access public
	 * @since  1.0.0
	 */
	public $settings;

	/**
	 * The token.
	 * 
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 * 
	 * @var    string
	 * @access public
	 * @since  1.0.0
	 */
	public $version;

	/**
	 * Cloning is forbidden.
	 *
	 * @since  1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Comrade\Bootstrap cannot be cloned.' ), '1.0.0' );
	}

	/**
	 * Class constructor.
	 * 
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {
		$this->token		= 'comrade';
		$this->plugin_url	= plugin_dir_url( __FILE__ );
		$this->plugin_path	= plugin_dir_path( __FILE__ );
		$this->version		= '1.0.0';
		
		$this->load_settings();
		
		$this->load_admin();
		
		$this->load_post_types();

		register_activation_hook( __FILE__, array( $this, 'install' ) );

		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since  1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Comrade\Bootstrap cannot be unserialized.' ), '1.0.0' );
	}

	/**
	 * Load the localisation file.
	 * 
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'comrade', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Installation. Runs on activation.
	 * 
	 * @access private
	 * @since  1.0.0
	 * @return void
	 */
	private function install() {
		$this->log_version_number();
	}

	/**
	 * Build the Admin area.
	 * 
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	private function load_admin() {
		if ( is_admin() ) {
			$this->admin = new Admin( $this );
		}
	}

	/**
	 * Load supported post types.
	 * 
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	private function load_post_types() {
		// Register an example post type.
		$this->post_types['thing'] = new Post_Type( 
			$this,
			array( 'menu_icon' => 'dashicons-carrot' ),
			__( 'Things', 'comrade' ),
			'thing',
			__( 'Thing', 'comrade' ), 
			''
		);
	}
	
	/**
	 * Load Settings.
	 * 
	 * @access private
	 * @since  1.0.0
	 * @return void
	 */
	 private function load_settings() {
	 	$this->settings = new Settings( $this );
	 }

	/**
	 * Log the plugin version number.
	 * 
	 * @access private
	 * @since  1.0.0
	 * @return void
	 */
	private function log_version_number() {
		update_option( $this->token . '-version', $this->version );
	}
}