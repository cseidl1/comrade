<?php
/**
 * Define Comrade's namespace.
 * 
 * @since 1.0.0
 */
namespace Comrade\Classes;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly.

/**
 * Comrade Taxonomy Class.
 *
 * Re-usable class for registering post type taxonomies.
 *
 * @since 1.0.0
 */
class Taxonomy {
	/**
	 * The arguments to use when registering the taxonomy.
	 * 
	 * @access  private
	 * @since   1.0.0
	 * @var     string
	 */
	private $args;
	
	/**
	 * The plural name for the taxonomy.
	 * 
	 * @access  private
	 * @since   1.0.0
	 * @var     string
	 */
	private $plural;
	
	/**
	 * The post type to register the taxonomy for.
	 * 
	 * @access  private
	 * @since   1.0.0
	 * @var     string
	 */
	private $post_type;
	
	/**
	 * The singular name for the taxonomy.
	 * 
	 * @access  private
	 * @since   1.0.0
	 * @var     string
	 */
	private $singular;

	/**
	 * The key of the taxonomy.
	 * 
	 * @access  private
	 * @since   1.0.0
	 * @var     string
	 */
	private $token;

	/**
	 * Class constructor.
	 * 
	 * @access  public
	 * @since   1.0.0
	 * @param   string $post_type The post type key.
	 * @param   string $token     The taxonomy key.
	 * @param   string $singular  Singular name.
	 * @param   string $plural    Plural  name.
	 * @param   array  $args      Array of argument overrides.
	 */
	public function __construct ( $post_type = 'thing', $token = 'thing-category', $singular = '', $plural = '', $args = array() ) {
		$this->post_type = $post_type;
		$this->token = esc_attr( $token );
		$this->singular = esc_html( $singular );
		$this->plural = esc_html( $plural );

		if ( '' == $this->singular ) $this->singular = __( 'Category', 'comrade' );
		if ( '' == $this->plural ) $this->plural = __( 'Categories', 'comrade' );

		$this->args = wp_parse_args( $args, $this->_get_default_args() );
	}

	/**
	 * Return an array of default arguments.
	 * @access  private
	 * @since   1.0.0
	 * @return  array Default arguments.
	 */
	private function _get_default_args () {
		return array( 'labels' => $this->_get_default_labels(), 'public' => true, 'hierarchical' => true, 'show_ui' => true, 'show_admin_column' => true, 'query_var' => true, 'show_in_nav_menus' => false, 'show_tagcloud' => false );
	}

	/**
	 * Return an array of default labels.
	 * @access  private
	 * @since   1.0.0
	 * @return  array Default labels.
	 */
	private function _get_default_labels () {
		return array(
			    'name'                => sprintf( _x( '%s', 'taxonomy general name', 'comrade' ), $this->plural ),
			    'singular_name'       => sprintf( _x( '%s', 'taxonomy singular name', 'comrade' ), $this->singular ),
			    'search_items'        => sprintf( __( 'Search %s', 'comrade' ), $this->plural ),
			    'all_items'           => sprintf( __( 'All %s', 'comrade' ), $this->plural ),
			    'parent_item'         => sprintf( __( 'Parent %s', 'comrade' ), $this->singular ),
			    'parent_item_colon'   => sprintf( __( 'Parent %s:', 'comrade' ), $this->singular ),
			    'edit_item'           => sprintf( __( 'Edit %s', 'comrade' ), $this->singular ),
			    'update_item'         => sprintf( __( 'Update %s', 'comrade' ), $this->singular ),
			    'add_new_item'        => sprintf( __( 'Add New %s', 'comrade' ), $this->singular ),
			    'new_item_name'       => sprintf( __( 'New %s Name', 'comrade' ), $this->singular ),
			    'menu_name'           => sprintf( __( '%s', 'comrade' ), $this->plural )
			  );
	}

	/**
	 * Register the taxonomy.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function register () {
		register_taxonomy( esc_attr( $this->token ), esc_attr( $this->post_type ), (array)$this->args );
	}
}