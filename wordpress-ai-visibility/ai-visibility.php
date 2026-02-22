<?php
/**
 * Plugin Name: AI Visibility
 * Description: AI visibility, llms.txt, structured data (Schema.org), and robots.txt management for AI bots. Free version of PulseRank.
 * Version: 1.0.0
 * Author: PulseRank
 * Author URI: https://pulserank.ai
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ai-visibility
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define core constants.
if ( ! defined( 'AI_VISIBILITY_VERSION' ) ) {
	define( 'AI_VISIBILITY_VERSION', '1.0.0' );
}

if ( ! defined( 'AI_VISIBILITY_PLUGIN_FILE' ) ) {
	define( 'AI_VISIBILITY_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'AI_VISIBILITY_PLUGIN_DIR' ) ) {
	define( 'AI_VISIBILITY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'AI_VISIBILITY_PLUGIN_URL' ) ) {
	define( 'AI_VISIBILITY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// Load core classes.
require_once AI_VISIBILITY_PLUGIN_DIR . 'includes/class-ai-visibility.php';
require_once AI_VISIBILITY_PLUGIN_DIR . 'admin/class-ai-visibility-admin.php';

/**
 * Bootstrap the plugin.
 */
function ai_visibility_init() {
	// Register REST API routes.
	add_action( 'rest_api_init', [ '\\AIVisibility\\Visibility', 'register_routes' ] );

	// Admin UI.
	add_action( 'admin_menu', [ '\\AIVisibility\\Admin', 'register_menu' ] );
	add_action( 'admin_enqueue_scripts', [ '\\AIVisibility\\Admin', 'enqueue_assets' ] );

	// Frontend hooks: llms.txt, robots.txt, schema, meta tags.
	add_action( 'template_redirect', [ '\\AIVisibility\\Visibility', 'maybe_output_llms_txt' ], 0 );
	add_filter( 'robots_txt', [ '\\AIVisibility\\Visibility', 'filter_robots_txt' ], 10, 2 );
	add_action( 'wp_head', [ '\\AIVisibility\\Visibility', 'output_schema' ] );
	add_action( 'wp_head', [ '\\AIVisibility\\Visibility', 'output_meta_tags' ], 1 );
}
add_action( 'plugins_loaded', 'ai_visibility_init' );

/**
 * Activation hook.
 */
function ai_visibility_activate() {
	// Flush rewrite rules so llms.txt works immediately.
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'ai_visibility_activate' );

/**
 * Deactivation hook.
 */
function ai_visibility_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'ai_visibility_deactivate' );

/**
 * Uninstall cleanup.
 * Removes all plugin options on uninstall.
 */
function ai_visibility_uninstall_cleanup() {
	$options = [
		'aiv_llms_enabled',
		'aiv_business_name',
		'aiv_business_description',
		'aiv_address',
		'aiv_phone',
		'aiv_opening_hours',
		'aiv_sitemap_url',
		'aiv_email',
		'aiv_key_urls',
		'aiv_logo_url',
		'aiv_price_range',
		'aiv_social_profiles',
		'aiv_visibility_mode',
		'aiv_schema_level',
		'aiv_voice_enabled',
		'aiv_business_type',
		'aiv_country_code',
	];

	foreach ( $options as $option ) {
		delete_option( $option );
	}
}
register_uninstall_hook( __FILE__, 'ai_visibility_uninstall_cleanup' );
