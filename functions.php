<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
	});

	add_filter('template_include', function( $template ) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});

	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class KerrisSite extends Timber\Site {
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'upload_mimes', array( $this, 'add_file_types_to_uploads') );
		parent::__construct();
	}

	function enqueue_scripts() {
        wp_enqueue_script('bundle', get_stylesheet_directory_uri() . '/dist/js/main.min.js', [], filemtime(get_stylesheet_directory() . '/dist/js/main.min.js'), true);
        wp_enqueue_style('bundle-css', get_stylesheet_directory_uri() . '/dist/css/main.min.css', [], filemtime(get_stylesheet_directory() . '/dist/css/main.min.css'));
    }

	public function register_post_types() {

	}

	public function register_taxonomies() {

	}

	function add_file_types_to_uploads($file_types){
		$new_filetypes = array();
		$new_filetypes['svg'] = 'image/svg+xml';
		$file_types = array_merge($file_types, $new_filetypes );
		return $file_types;
	}

	public function add_to_context( $context ) {
		$context['menu'] = new Timber\Menu();
		$context['site'] = $this;
		return $context;
	}

	public function theme_supports() {

	}

	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig_Extension_StringLoader() );
		return $twig;
	}

}

new KerrisSite();
