<?php
/**
 * AI Visibility — Admin UI bootstrap.
 *
 * @package AIVisibility
 * @license GPL-2.0-or-later
 */

namespace AIVisibility;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Admin UI bootstrap for AI Visibility.
 */
class Admin {
	/**
	 * Register admin menu page.
	 */
	public static function register_menu() {
		add_menu_page(
			__( 'AI Visibility', 'ai-visibility' ),
			__( 'AI Visibility', 'ai-visibility' ),
			'manage_options',
			'ai-visibility',
			[ __CLASS__, 'render_root_page' ],
			'dashicons-visibility',
			30
		);
	}

	/**
	 * Enqueue admin scripts and styles.
	 *
	 * @param string $hook Current admin page hook.
	 */
	public static function enqueue_assets( $hook ) {
		if ( 'toplevel_page_ai-visibility' !== $hook ) {
			return;
		}

		// CSS — loaded from assets/css (not bundled by webpack).
		$css_file = AI_VISIBILITY_PLUGIN_DIR . 'assets/css/admin.css';
		wp_enqueue_style(
			'ai-visibility-admin',
			AI_VISIBILITY_PLUGIN_URL . 'assets/css/admin.css',
			[],
			file_exists( $css_file ) ? filemtime( $css_file ) : AI_VISIBILITY_VERSION
		);

		// JS — loaded from build/ (produced by @wordpress/scripts).
		$asset_file = AI_VISIBILITY_PLUGIN_DIR . 'build/admin-app.asset.php';
		$asset      = file_exists( $asset_file ) ? require $asset_file : [
			'dependencies' => [ 'wp-element', 'wp-i18n', 'wp-api-fetch' ],
			'version'      => AI_VISIBILITY_VERSION,
		];

		wp_enqueue_script(
			'ai-visibility-admin',
			AI_VISIBILITY_PLUGIN_URL . 'build/admin-app.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);

		wp_localize_script( 'ai-visibility-admin', 'AIVisibilityConfig', [
			'root'      => '/ai-visibility/v1/',
			'nonce'     => wp_create_nonce( 'wp_rest' ),
			'homeUrl'   => esc_url_raw( home_url( '/' ) ),
			'adminUrl'  => esc_url_raw( admin_url() ),
			'pluginUrl' => esc_url_raw( AI_VISIBILITY_PLUGIN_URL ),
		] );
	}

	/**
	 * Render the root admin page shell — hydrated by the JS admin app.
	 */
	public static function render_root_page() {
		echo '<div class="wrap aiv-admin"><div id="aiv-root"></div></div>';
	}
}
