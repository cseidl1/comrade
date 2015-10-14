<?php
/**
 * Define Comrade's namespace.
 * 
 * @since 1.0.0
 */
namespace Comrade\Classes;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Comrade Admin Class.
 * 
 * Render Admin screens using settings from Settings class.
 *
 * @since		1.0.0
 */
class Admin {
	/**
	 * Holds the Comrade Engine object.
	 * 
	 * @var    object
	 * @access private
	 * @since  1.0.0
	 */
	 private $engine;
	 
	/**
	 * The string containing the dynamically generated hook token.
	 * 
	 * @var     string
	 * @access  private
	 * @since   1.0.0
	 */
	private $hook;

	/**
	 * Class constructor.
	 * 
	 * @access  public
	 * @since   1.0.0
	 */
	public function __construct( Engine $engine ) {
		$this->engine = $engine;
		
		// Register the settings with WordPress.
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Register the settings screen within WordPress.
		add_action( 'admin_menu', array( $this, 'register_settings_screen' ) );
	}

	/**
	 * Return marked up HTML for the header tag on the settings screen.
	 * 
	 * @access  public
	 * @since   1.0.0
	 * @param   array  $sections Sections to scan through.
	 * @param   string $title    Title to use, if only one section is present.
	 * @return  string           The current tab key.
	 */
	public function get_admin_header_html( $sections, $title ) {
		$defaults = array(
							'tag' => 'h2',
							'atts' => array( 'class' => 'comrade-wrapper' ),
							'content' => $title
						);

		$args = $this->get_admin_header_data( $sections, $title );

		$args = wp_parse_args( $args, $defaults );

		$atts = '';
		if ( 0 < count ( $args['atts'] ) ) {
			foreach ( $args['atts'] as $k => $v ) {
				$atts .= ' ' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
			}
		}

		$response = '<' . esc_attr( $args['tag'] ) . $atts . '>' . $args['content'] . '</' . esc_attr( $args['tag'] ) . '>' . "\n";

		return $response;
	}

	/**
	 * Register the settings within the Settings API.
	 * 
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function register_settings() {
		$sections = $this->engine->settings->get_settings_sections();
		if ( 0 < count( $sections ) ) {
			foreach ( $sections as $k => $v ) {
				register_setting( 'comrade-settings-' . sanitize_title_with_dashes( $k ), 'comrade-' . $k, array( $this, 'validate_settings' ) );
				add_settings_section( sanitize_title_with_dashes( $k ), $v, array( $this, 'render_settings' ), 'comrade-' . $k, $k, $k );
			}
		}
	}

	/**
	 * Register the admin screen.
	 * 
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function register_settings_screen() {
		$this->hook = add_submenu_page( 'options-general.php', __( 'Comrade', 'comrade' ), __( 'Comrade', 'comrade' ), 'manage_options', 'comrade', array( $this, 'settings_screen' ) );
	}

	/**
	 * Render the settings.
	 * 
	 * @access  public
	 * @param   array $args Arguments.
	 * @since   1.0.0
	 * @return  void
	 */
	public function render_settings( $args ) {
		$token = $args['id'];
		$fields = $this->engine->settings->get_settings_fields( $token );

		if ( 0 < count( $fields ) ) {
			foreach ( $fields as $k => $v ) {
				$args 		= $v;
				$args['id'] = $k;

				add_settings_field( $k, $v['name'], array( $this->engine->settings, 'render_field' ), 'comrade-' . $token , $v['section'], $args );
			}
		}
	}

	/**
	 * Output the markup for the settings screen.
	 * 
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function settings_screen() {
		global $title;
		$sections = $this->engine->settings->get_settings_sections();
		$tab = $this->get_current_tab( $sections );
		?>
		<div class="wrap comrade-wrap">
			<?php
				echo $this->get_admin_header_html( $sections, $title );
			?>
			<form action="options.php" method="post">
				<?php
					settings_fields( 'comrade-settings-' . $tab );
					do_settings_sections( 'comrade-' . $tab );
					submit_button( __( 'Save Changes', 'comrade' ) );
				?>
			</form>
		</div><!--/.wrap-->
		<?php
	}

	/**
	 * Validate the settings.
	 * 
	 * @access  public
	 * @since   1.0.0
	 * @param   array $input Inputted data.
	 * @return  array        Validated data.
	 */
	public function validate_settings( $input ) {
		$sections = $this->engine->settings->get_settings_sections();
		$tab = $this->get_current_tab( $sections );
		return $this->engine->settings->validate_settings( $input, $tab );
	}

	/**
	 * Return an array of data, used to construct the header tag.
	 * 
	 * @access  private
	 * @since   1.0.0
	 * @param   array  $sections Sections to scan through.
	 * @param   string $title    Title to use, if only one section is present.
	 * @return  array            An array of data with which to mark up the header HTML.
	 */
	private function get_admin_header_data( $sections, $title ) {
		$response = array( 'tag' => 'h2', 'atts' => array( 'class' => 'comrade-wrapper' ), 'content' => $title );

		if ( is_array( $sections ) && 1 < count( $sections ) ) {
			$response['content'] = '';
			$response['atts']['class'] = 'nav-tab-wrapper';

			$tab = $this->get_current_tab( $sections );

			foreach ( $sections as $key => $value ) {
				$class = 'nav-tab';
				if ( $tab == $key ) {
					$class .= ' nav-tab-active';
				}

				$response['content'] .= '<a href="' . admin_url( 'options-general.php?page=comrade&tab=' . sanitize_title_with_dashes( $key ) ) . '" class="' . esc_attr( $class ) . '">' . esc_html( $value ) . '</a>';
			}
		}

		return (array)apply_filters( 'comrade-get-admin-header-data', $response );
	}
	
	/**
	 * Return the current tab key.
	 * 
	 * @access  private
	 * @since   1.0.0
	 * @param   array  $sections Sections to scan through for a section key.
	 * @return  string           The current tab key.
	 */
	private function get_current_tab( $sections = array() ) {
		if ( isset ( $_GET['tab'] ) ) {
			$response = sanitize_title_with_dashes( $_GET['tab'] );
		} else {
			if ( is_array( $sections ) && ! empty( $sections ) ) {
				list( $first_section ) = array_keys( $sections );
				$response = $first_section;
			} else {
				$response = '';
			}
		}

		return $response;
	}

}