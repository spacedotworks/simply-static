<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * The core plugin class
 * @package Simply_Static
 */
class Simply_Static {
	/**
	 * Plugin version
	 * @var string
	 */
	const VERSION = '1.3.0';

	/**
	 * The slug of the plugin; used in actions, filters, i18n, table names, etc.
	 * @var string
	 */
	const SLUG = 'simply_static'; // keep it short; stick to alphas & underscores

	// Base 64 encoded SVG image.
	const ICON_SVG = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+PHN2ZyAgIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyIgICB4bWxuczpjYz0iaHR0cDovL2NyZWF0aXZlY29tbW9ucy5vcmcvbnMjIiAgIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyIgICB4bWxuczpzdmc9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiAgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgICB4bWxuczpzb2RpcG9kaT0iaHR0cDovL3NvZGlwb2RpLnNvdXJjZWZvcmdlLm5ldC9EVEQvc29kaXBvZGktMC5kdGQiICAgeG1sbnM6aW5rc2NhcGU9Imh0dHA6Ly93d3cuaW5rc2NhcGUub3JnL25hbWVzcGFjZXMvaW5rc2NhcGUiICAgaWQ9InN2ZzM0MzQiICAgdmVyc2lvbj0iMS4xIiAgIGlua3NjYXBlOnZlcnNpb249IjAuOTEgcjEzNzI1IiAgIHdpZHRoPSIxODAiICAgaGVpZ2h0PSIzMDAiICAgdmlld0JveD0iMCAwIDE4MCAzMDAiICAgc29kaXBvZGk6ZG9jbmFtZT0iYm9sdC12ZWN0b3ItZ3JheS5zdmciPiAgPG1ldGFkYXRhICAgICBpZD0ibWV0YWRhdGEzNDQwIj4gICAgPHJkZjpSREY+ICAgICAgPGNjOldvcmsgICAgICAgICByZGY6YWJvdXQ9IiI+ICAgICAgICA8ZGM6Zm9ybWF0PmltYWdlL3N2Zyt4bWw8L2RjOmZvcm1hdD4gICAgICAgIDxkYzp0eXBlICAgICAgICAgICByZGY6cmVzb3VyY2U9Imh0dHA6Ly9wdXJsLm9yZy9kYy9kY21pdHlwZS9TdGlsbEltYWdlIiAvPiAgICAgICAgPGRjOnRpdGxlIC8+ICAgICAgPC9jYzpXb3JrPiAgICA8L3JkZjpSREY+ICA8L21ldGFkYXRhPiAgPGRlZnMgICAgIGlkPSJkZWZzMzQzOCIgLz4gIDxzb2RpcG9kaTpuYW1lZHZpZXcgICAgIHBhZ2Vjb2xvcj0iI2ZmZmZmZiIgICAgIGJvcmRlcmNvbG9yPSIjNjY2NjY2IiAgICAgYm9yZGVyb3BhY2l0eT0iMSIgICAgIG9iamVjdHRvbGVyYW5jZT0iMTAiICAgICBncmlkdG9sZXJhbmNlPSIxMCIgICAgIGd1aWRldG9sZXJhbmNlPSIxMCIgICAgIGlua3NjYXBlOnBhZ2VvcGFjaXR5PSIwIiAgICAgaW5rc2NhcGU6cGFnZXNoYWRvdz0iMiIgICAgIGlua3NjYXBlOndpbmRvdy13aWR0aD0iMTUzNiIgICAgIGlua3NjYXBlOndpbmRvdy1oZWlnaHQ9IjgwMSIgICAgIGlkPSJuYW1lZHZpZXczNDM2IiAgICAgc2hvd2dyaWQ9ImZhbHNlIiAgICAgZml0LW1hcmdpbi10b3A9IjAiICAgICBmaXQtbWFyZ2luLWxlZnQ9IjAiICAgICBmaXQtbWFyZ2luLXJpZ2h0PSIwIiAgICAgZml0LW1hcmdpbi1ib3R0b209IjAiICAgICBpbmtzY2FwZTp6b29tPSIyLjE0MjM3MjkiICAgICBpbmtzY2FwZTpjeD0iOC44Njg2Njc5IiAgICAgaW5rc2NhcGU6Y3k9IjE0Ny41MDAwMSIgICAgIGlua3NjYXBlOndpbmRvdy14PSItOCIgICAgIGlua3NjYXBlOndpbmRvdy15PSItOCIgICAgIGlua3NjYXBlOndpbmRvdy1tYXhpbWl6ZWQ9IjEiICAgICBpbmtzY2FwZTpjdXJyZW50LWxheWVyPSJzdmczNDM0IiAvPiAgPHBhdGggICAgIHN0eWxlPSJmaWxsOiM5Y2ExYTY7ZmlsbC1vcGFjaXR5OjEiICAgICBkPSJNIDM5LjksMjMzLjUgODQuNDMzMzMzLDE2MS4xMzMzMyAzOS45LDE2MS4xMzMzMyAxNDAuMSw2Ni41IGwgLTQ0LjUzMzMzMyw3Mi4zNjY2NyA0NC41MzMzMzMsMCB6IiAgICAgaWQ9InBhdGgzNDQ2IiAgICAgaW5rc2NhcGU6Y29ubmVjdG9yLWN1cnZhdHVyZT0iMCIgICAgIHNvZGlwb2RpOm5vZGV0eXBlcz0iY2NjY2NjYyIgLz48L3N2Zz4=';

	/**
	 * Singleton instance
	 * @var Simply_Static
	 */
	protected static $instance = null;

	/**
	 * An instance of the options structure containing all options for this plugin
	 * @var Simply_Static_Options
	 */
	protected $options = null;

	/**
	 * View object
	 * @var Simply_Static_View
	 */
	protected $view = null;

	/**
	 * Disable usage of "new"
	 * @return void
	 */
	protected function __construct() {}

	/**
	 * Disable cloning of the class
	 * @return void
	 */
	protected function __clone() {}

	/**
	 * Disable unserializing of the class
	 * @return void
	 */
	public function __wakeup() {}

	/**
	 * Return an instance of the Simply Static plugin
	 * @return Simply_Static
	 */
	public static function instance()
	{
		if ( null === self::$instance )
		{
			self::$instance = new self();
			self::$instance->includes();
			self::$instance->options = new Simply_Static_Options( self::SLUG );
			self::$instance->view = new Simply_Static_View();

			// Check for pending file download
			add_action( 'plugins_loaded', array( self::$instance, 'download_file' ) );
			// Load the text domain for i18n
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
			// Enqueue admin styles
			add_action( 'admin_enqueue_scripts', array( self::$instance, 'enqueue_admin_styles' ) );
			// Enqueue admin scripts
			add_action( 'admin_enqueue_scripts', array( self::$instance, 'enqueue_admin_scripts' ) );
			// Add the options page and menu item.
			add_action( 'admin_menu', array( self::$instance, 'add_plugin_admin_menu' ), 2 );
			// Handle AJAX requests
			add_action( 'wp_ajax_generate_static_archive', array( self::$instance, 'generate_static_archive' ) );
			add_action( 'wp_ajax_render_export_log', array( self::$instance, 'render_export_log' ) );
			add_action( 'wp_ajax_render_activity_log', array( self::$instance, 'render_activity_log' ) );
		}

		return self::$instance;
	}

	/**
	 * Initialize singleton instance
	 * @param string $bootstrap_file
	 * @return Simply_Static
	 */
	public static function init( $bootstrap_file )
	{
		$instance = self::instance();

		// Activation
		register_activation_hook( $bootstrap_file, array( $instance, 'activate' ) );

		return $instance;
	}

	/**
	 * Performs activation
	 * @return void
	 */
	public function activate()
	{
		$version = $this->options->get( 'version' );

		// Never installed?
		if ( null === $version ) {
			$this->options
				->set( 'destination_scheme', sist_origin_scheme() )
				->set( 'destination_host', sist_origin_host() )
				->set( 'temp_files_dir', trailingslashit( plugin_dir_path( dirname( __FILE__ ) ) . 'static-files' ) )
				->set( 'additional_urls', '' )
				->set( 'delivery_method', 'zip' )
				->set( 'local_dir', '' )
				->set( 'delete_temp_files', '1' );
		}

		// perform migrations
		if ( version_compare( $version, self::VERSION, '<' ) ) {

			// version 1.2 introduced the ability to specify additional files
			if ( version_compare( $version, '1.2.0', '<' ) ) {
				$this->options
					->set( 'additional_files', '' );
			}

			if ( version_compare( $version, '1.3.0', '<' ) ) {
				// version 1.3 changed the options key
				if ( get_option( 'simply-static' ) ) {
		            update_option( 'simply_static', get_option( 'simply-static' ) );
		            delete_option( 'simply-static' );
		        }

				// and added a database table for tracking urls/progress
				Simply_Static_Page::create_table();
			}

			// always update the version and save
			$this->options
				->set( 'version', self::VERSION )
				->save();
		}

	}

	/**
	 * Include required files
	 * @return void
	 */
	private function includes() {
		require plugin_dir_path( dirname( __FILE__ ) ) . 'vendor/autoload.php'; // 3rd-party libs
		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simply-static-options.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simply-static-view.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simply-static-url-extractor.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simply-static-url-fetcher.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simply-static-url-response.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simply-static-archive-creator.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simply-static-model.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simply-static-page.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-simply-static-archive-manager.php';
		require plugin_dir_path( dirname( __FILE__ ) ) . 'includes/misc-functions.php';
	}

	/**
	 * Enqueue admin-specific style sheets for this plugin's admin pages only
	 * @return void
	 */
	public function enqueue_admin_styles() {
		// Plugin admin CSS. Tack on plugin version.
		wp_enqueue_style( self::SLUG . '-admin-styles', plugin_dir_url( dirname( __FILE__ ) ) . 'css/admin.css', array(), time() );
	}

	/**
	 * Enqueue admin-specific javascript files for this plugin's admin pages only
	 * @return void
	 */
	public function enqueue_admin_scripts() {
		// Plugin admin CSS. Tack on plugin version.
		wp_enqueue_script( self::SLUG . '-admin-styles', plugin_dir_url( dirname( __FILE__ ) ) . 'js/admin.js', array(), time() );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 * @return void
	 */
	public function add_plugin_admin_menu() {

		// Add main menu item
		add_menu_page(
			__( 'Simply Static Settings', self::SLUG ),
			__( 'Simply Static', self::SLUG ),
			'manage_options',
			self::SLUG,
			array( self::$instance, 'display_generate_page' ),
			self::ICON_SVG
		);

		add_submenu_page(
			self::SLUG,
			__( 'Generate Static Site', self::SLUG ),
			__( 'Generate', self::SLUG ),
			'manage_options',
			self::SLUG,
			array( self::$instance, 'display_generate_page' )
		);

		add_submenu_page(
			self::SLUG,
			__( 'Simply Static Settings', self::SLUG ),
			__( 'Settings', self::SLUG ),
			'manage_options',
			self::SLUG . '_settings',
			array( self::$instance, 'display_settings_page' )
		);
	}

	/**
	 * Handle requests for generating a static archive and send a response via ajax
	 *
	 * Expects a POST param called 'perform' which is one of: start, continue,
	 * cancel. Those actions are passed along to the Archive Manager which will
	 * dole out small portions of work to the Archive Creator, slowly building
	 * out a static archive over repeated requests.
	 * @return void
	 */
	function generate_static_archive() {
		$action = $_POST['perform'];

		$archive_manager = new Simply_Static_Archive_Manager( $this->options );

		$archive_manager->perform( $action );

		$state_name = $archive_manager->get_state_name();
		$done = $archive_manager->has_finished();

		$activity_log_html = $this->view
			->set_template( '_activity_log' )
			->assign( 'status_messages', $archive_manager->get_status_messages() )
			->render_to_string();

		// send json response and die()
		wp_send_json( array(
			'state_name' => $state_name,
			'activity_log_html' => $activity_log_html,
			'done' => $done
		) );
	}

	/**
	 * Render the activity log and send it via ajax
	 * @return void
	 */
	public function render_activity_log() {
		$archive_manager = new Simply_Static_Archive_Manager( $this->options );

		$content = $this->view
			->set_template( '_activity_log' )
			->assign( 'status_messages', $archive_manager->get_status_messages() )
			->render_to_string();

		// send json response and die()
		wp_send_json( array(
			'html' => $content
		) );
	}

	/**
	 * Render the export log and send it via ajax
	 * @return void
	 */
	public function render_export_log() {
		$static_pages = Simply_Static_Page::all();

		$content = $this->view
			->set_template( '_export_log' )
			->assign( 'static_pages', $static_pages )
			->render_to_string();

		// send json response and die()
		wp_send_json( array(
			'html' => $content
		) );
	}

	/**
	 * Render the page for generating a static site
	 * @return void
	 */
	public function display_generate_page() {
		if ( $this->check_system_requirements() ) {
			$this->view->assign( 'system_requirements_check_failed', true );
		}

		$archive_manager = new Simply_Static_Archive_Manager( $this->options );

		// render the activity log to a string
		$partial_view = new Simply_Static_View();
		$activity_log_html = $partial_view
			->set_template( '_activity_log' )
			->assign( 'status_messages', $archive_manager->get_status_messages() )
			->render_to_string();

		// render the export log to a string
		$partial_view = new Simply_Static_View();
		$static_pages = Simply_Static_Page::all();
		$export_log_html = $this->view
			->set_template( '_export_log' )
			->assign( 'static_pages', $static_pages )
			->render_to_string();

		$this->view
			->set_layout( 'admin' )
			->set_template( 'generate' )
			->assign( 'activity_log', $activity_log_html )
			->assign( 'export_log', $export_log_html )
			->assign( 'archive_generation_ready_to_start', $archive_manager->has_finished() )
			->render();
	}

	/**
	 * Render the options page
	 * @return void
	 */
	public function display_settings_page() {
		if ( isset( $_POST['_settings'] ) ) {
			$this->save_options();
			$message = __( 'Settings saved.', self::SLUG );
			$this->view->add_flash( 'updated', $message );
		}

		$this->view
			->set_layout( 'admin' )
			->set_template( 'settings' )
			->assign( 'origin_scheme', sist_origin_scheme() )
			->assign( 'origin_host', sist_origin_host() )
			->assign( 'destination_scheme', $this->options->get( 'destination_scheme' ) )
			->assign( 'destination_host', $this->options->get( 'destination_host' ) )
			->assign( 'temp_files_dir', $this->options->get( 'temp_files_dir' ) )
			->assign( 'additional_urls', $this->options->get( 'additional_urls' ) )
			->assign( 'additional_files', $this->options->get( 'additional_files' ) )
			->assign( 'delivery_method', $this->options->get( 'delivery_method' ) )
			->assign( 'local_dir', $this->options->get( 'local_dir' ) )
			->assign( 'delete_temp_files', $this->options->get( 'delete_temp_files' ) )
			->render();
	}

	/**
	 * Save the options from the options page
	 * @return void
	 */
	public function save_options() {
		$this->options
			->set( 'destination_scheme', filter_input( INPUT_POST, 'destination_scheme' ) )
			->set( 'destination_host', untrailingslashit( filter_input( INPUT_POST, 'destination_host', FILTER_SANITIZE_URL ) ) )
			->set( 'temp_files_dir', sist_trailingslashit_unless_blank( filter_input( INPUT_POST, 'temp_files_dir' ) ) )
			->set( 'additional_urls', filter_input( INPUT_POST, 'additional_urls' ) )
			->set( 'additional_files', filter_input( INPUT_POST, 'additional_files' ) )
			->set( 'delivery_method', filter_input( INPUT_POST, 'delivery_method' ) )
			->set( 'local_dir', sist_trailingslashit_unless_blank( filter_input( INPUT_POST, 'local_dir' ) ) )
			->set( 'delete_temp_files', filter_input( INPUT_POST, 'delete_temp_files' ) )
			->save();
	}

	/**
	 * Loads the plugin language files
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain(
			self::SLUG,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}

	/**
	 * Loads the plugin language files
	 * @return array $errors associative array containing errored field names and an array of error messages
	 */
	public function check_system_requirements() {
		global $wp_filesystem;

		$errors = array();

		$destination_host = $this->options->get( 'destination_host' );
		if ( strlen( $destination_host ) === 0 ) {
			$errors['destination_host'][] = __( 'Destination URL cannot be blank', self::SLUG );
		}

		$temp_files_dir = $this->options->get( 'temp_files_dir' );
		if ( strlen( $temp_files_dir ) === 0 ) {
			$errors['temp_files_dir'][] = __( 'Temporary Files Directory cannot be blank', self::SLUG );
		} else {
			if ( file_exists( $temp_files_dir ) ) {
				if ( ! is_writeable( $temp_files_dir ) ) {
					$errors['delivery_method'][] = sprintf( __( 'Temporary Files Directory is not writeable: %s', self::SLUG ), $temp_files_dir );
				}
			} else {
				$errors['delivery_method'][] = sprintf( __( 'Temporary Files Directory does not exist: %s', self::SLUG ), $temp_files_dir );
			}
		}


		if ( strlen( get_option( 'permalink_structure' ) ) === 0 ) {
			$errors['permalink_structure'][] = sprintf( __( "Your site does not have a permalink structure set. You can select one on <a href='%s'>the Permalink Settings page</a>.", self::SLUG ), admin_url( '/options-permalink.php' ) );
		}

		if ( $this->options->get( 'delivery_method' ) == 'zip' ) {
			if ( ! extension_loaded('zip') ) {
				$errors['delivery_method'][] = __( "Your server does not have the PHP zip extension enabled. Please visit <a href='http://www.php.net/manual/en/book.zip.php'>the PHP zip extension page</a> for more information on how to enable it.", self::SLUG );
			}
		}

		if ( $this->options->get( 'delivery_method' ) == 'local' ) {
			$local_dir = $this->options->get( 'local_dir' );

			if ( strlen( $local_dir ) === 0 ) {
				$errors['delivery_method'][] = __( 'Local Directory cannot be blank', self::SLUG );
			} else {
				if ( file_exists( $local_dir ) ) {
					if ( ! is_writeable( $local_dir ) ) {
						$errors['delivery_method'][] = sprintf( __( 'Local Directory is not writeable: %s', self::SLUG ), $local_dir );
					}
				} else {
					$errors['delivery_method'][] = sprintf( __( 'Local Directory does not exist: %s', self::SLUG ), $local_dir );
				}
			}
		}

		$additional_urls = sist_string_to_array( $this->options->get( 'additional_urls' ) );
		foreach ( $additional_urls as $url ) {
			if ( ! sist_is_local_url( $url  ) ) {
				$errors['additional_urls'][] = sprintf( __( 'An Additional URL does not start with <code>%s</code>: %s', self::SLUG ),
				sist_origin_url(),
				$url );
			}
		}

		$additional_files = sist_string_to_array( $this->options->get( 'additional_files' ) );
		foreach ( $additional_files as $file ) {
			if ( stripos( $file, get_home_path() ) !== 0 && stripos( $file, WP_PLUGIN_DIR ) !== 0 && stripos( $file, WP_CONTENT_DIR ) !== 0  ) {
				$errors['additional_urls'][] = sprintf( __( 'An Additional File or Directory is not located within an expected directory: %s<br />It should be in one of these directories (or a subdirectory):<br  /><code>%s</code><br /> <code>%s</code><br /> <code>%s</code>', self::SLUG ),
				$file,
				get_home_path(),
				WP_PLUGIN_DIR,
				WP_CONTENT_DIR );
			}
		}

		return $errors;
	}

	/**
	 * Check for a pending file download; prompt user to download file
	 * @return null
	 */
	public function download_file() {
		if ( isset( $_GET[ self::SLUG . '_zip_download' ] ) ) {
			$filename = $_GET[ self::SLUG . '_zip_download' ];
			header( 'Content-Description: File Transfer' );
			header( 'Content-Disposition: attachment; filename=' . $filename );
			header( 'Content-Type: application/zip, application/octet-stream; charset=' . get_option( 'blog_charset' ), true );
			header( 'Pragma: no-cache' );
			header( 'Expires: 0' );
			readfile( path_join( self::$instance->options->get( 'temp_files_dir' ), $filename ) );
			exit();
		}
	}
}