<?php
/**
 * AI Visibility — Core Visibility class.
 *
 * @package AIVisibility
 * @license GPL-2.0-or-later
 */

namespace AIVisibility;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles llms.txt generation, AI visibility mode, structured data (Schema.org),
 * robots.txt filtering, and voice/multimodal optimization.
 */
class Visibility {
	/**
	 * Cached visibility settings.
	 *
	 * @var array|null
	 */
	protected static $settings_cache = null;

	/**
	 * Option names.
	 */
	const OPTION_LLMS_ENABLED    = 'aiv_llms_enabled';
	const OPTION_BUSINESS_NAME   = 'aiv_business_name';
	const OPTION_BUSINESS_DESC   = 'aiv_business_description';
	const OPTION_ADDRESS         = 'aiv_address';
	const OPTION_PHONE           = 'aiv_phone';
	const OPTION_OPENING_HOURS   = 'aiv_opening_hours';
	const OPTION_SITEMAP_URL     = 'aiv_sitemap_url';
	const OPTION_BUSINESS_EMAIL  = 'aiv_email';
	const OPTION_KEY_URLS        = 'aiv_key_urls';
	const OPTION_LOGO_URL        = 'aiv_logo_url';
	const OPTION_PRICE_RANGE     = 'aiv_price_range';
	const OPTION_SOCIAL_PROFILES = 'aiv_social_profiles';
	const OPTION_VISIBILITY      = 'aiv_visibility_mode';
	const OPTION_SCHEMA_LEVEL    = 'aiv_schema_level';
	const OPTION_VOICE_ENABLED   = 'aiv_voice_enabled';
	const OPTION_BUSINESS_TYPE   = 'aiv_business_type';
	const OPTION_COUNTRY_CODE    = 'aiv_country_code';

	/**
	 * Get cached visibility settings.
	 *
	 * @return array
	 */
	protected static function get_cached_settings() {
		if ( null !== self::$settings_cache ) {
			return self::$settings_cache;
		}

		self::$settings_cache = [
			'mode'            => get_option( self::OPTION_VISIBILITY, 'open' ),
			'level'           => get_option( self::OPTION_SCHEMA_LEVEL, 'basic' ),
			'voice'           => (bool) get_option( self::OPTION_VOICE_ENABLED, false ),
			'llms'            => (bool) get_option( self::OPTION_LLMS_ENABLED, false ),
			'business_name'   => (string) get_option( self::OPTION_BUSINESS_NAME, '' ),
			'business_desc'   => (string) get_option( self::OPTION_BUSINESS_DESC, '' ),
			'address'         => (string) get_option( self::OPTION_ADDRESS, '' ),
			'phone'           => (string) get_option( self::OPTION_PHONE, '' ),
			'hours'           => (string) get_option( self::OPTION_OPENING_HOURS, '' ),
			'email'           => (string) get_option( self::OPTION_BUSINESS_EMAIL, '' ),
			'logo'            => (string) get_option( self::OPTION_LOGO_URL, '' ),
			'price_range'     => (string) get_option( self::OPTION_PRICE_RANGE, '' ),
			'key_urls'        => get_option( self::OPTION_KEY_URLS, [] ),
			'social_profiles' => get_option( self::OPTION_SOCIAL_PROFILES, [] ),
			'sitemap'         => get_option( self::OPTION_SITEMAP_URL, '' ),
			'business_type'   => (string) get_option( self::OPTION_BUSINESS_TYPE, 'LocalBusiness' ),
			'country_code'    => (string) get_option( self::OPTION_COUNTRY_CODE, '' ),
		];

		if ( ! is_array( self::$settings_cache['key_urls'] ) ) {
			self::$settings_cache['key_urls'] = [];
		}
		if ( ! is_array( self::$settings_cache['social_profiles'] ) ) {
			self::$settings_cache['social_profiles'] = [];
		}

		return self::$settings_cache;
	}

	/**
	 * Clear settings cache (call after saving settings).
	 */
	public static function clear_cache_settings() {
		self::$settings_cache = null;
	}

	/**
	 * Register REST API routes for managing visibility settings.
	 */
	public static function register_routes() {
		register_rest_route(
			'ai-visibility/v1',
			'/visibility',
			[
				'methods'             => 'GET',
				'callback'            => [ __CLASS__, 'get_settings' ],
				'permission_callback' => [ __CLASS__, 'can_manage' ],
			]
		);

		register_rest_route(
			'ai-visibility/v1',
			'/visibility',
			[
				'methods'             => 'POST',
				'callback'            => [ __CLASS__, 'update_settings' ],
				'permission_callback' => [ __CLASS__, 'can_manage' ],
			]
		);

		register_rest_route(
			'ai-visibility/v1',
			'/visibility/schema-preview',
			[
				'methods'             => 'GET',
				'callback'            => [ __CLASS__, 'schema_preview' ],
				'permission_callback' => [ __CLASS__, 'can_manage' ],
			]
		);
	}

	/**
	 * Permission callback for REST API endpoints.
	 *
	 * @return bool True if user can manage options.
	 */
	public static function can_manage() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * GET handler: return current visibility settings.
	 *
	 * @param \WP_REST_Request $request REST request.
	 * @return \WP_REST_Response
	 */
	public static function get_settings( $request ) { // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		$s = self::get_cached_settings();

		$sitemap = $s['sitemap'];
		if ( empty( $sitemap ) ) {
			$sitemap = self::detect_sitemap_url();
		}

		return rest_ensure_response(
			[
				'site_url'             => home_url( '/' ),
				'sitemap_url'          => $sitemap,
				'visibility_mode'      => $s['mode'],
				'schema_level'         => $s['level'],
				'voice_enabled'        => $s['voice'],
				'llms_enabled'         => $s['llms'],
				'business_name'        => $s['business_name'],
				'business_description' => $s['business_desc'],
				'address'              => $s['address'],
				'phone'                => $s['phone'],
				'opening_hours'        => $s['hours'],
				'business_email'       => $s['email'],
				'logo_url'             => $s['logo'],
				'price_range'          => $s['price_range'],
				'key_urls'             => $s['key_urls'],
				'social_profiles'      => $s['social_profiles'],
				'business_type'        => $s['business_type'],
				'country_code'         => $s['country_code'],
			]
		);
	}

	/**
	 * POST handler: update visibility settings.
	 *
	 * @param \WP_REST_Request $request REST request.
	 * @return \WP_REST_Response
	 */
	public static function update_settings( $request ) {
		$mode  = $request->get_param( 'visibility_mode' );
		$level = $request->get_param( 'schema_level' );

		$allowed_modes  = [ 'open', 'balanced', 'block' ];
		$allowed_levels = [ 'basic', 'enhanced', 'full' ];

		if ( ! in_array( $mode, $allowed_modes, true ) ) {
			$mode = 'open';
		}

		if ( ! in_array( $level, $allowed_levels, true ) ) {
			$level = 'basic';
		}

		update_option( self::OPTION_VISIBILITY, $mode );
		update_option( self::OPTION_SCHEMA_LEVEL, $level );
		update_option( self::OPTION_VOICE_ENABLED, (bool) $request->get_param( 'voice_enabled' ) );
		update_option( self::OPTION_LLMS_ENABLED, (bool) $request->get_param( 'llms_enabled' ) );
		update_option( self::OPTION_BUSINESS_NAME, sanitize_text_field( (string) $request->get_param( 'business_name' ) ) );
		update_option( self::OPTION_BUSINESS_DESC, sanitize_textarea_field( (string) $request->get_param( 'business_description' ) ) );
		update_option( self::OPTION_ADDRESS, sanitize_text_field( (string) $request->get_param( 'address' ) ) );
		update_option( self::OPTION_PHONE, sanitize_text_field( (string) $request->get_param( 'phone' ) ) );
		update_option( self::OPTION_OPENING_HOURS, sanitize_text_field( (string) $request->get_param( 'opening_hours' ) ) );
		update_option( self::OPTION_BUSINESS_EMAIL, sanitize_email( (string) $request->get_param( 'business_email' ) ) );

		$raw_sitemap = (string) $request->get_param( 'sitemap_url' );
		update_option( self::OPTION_SITEMAP_URL, $raw_sitemap ? esc_url_raw( $raw_sitemap ) : '' );

		$raw_logo = (string) $request->get_param( 'logo_url' );
		update_option( self::OPTION_LOGO_URL, $raw_logo ? esc_url_raw( $raw_logo ) : '' );

		update_option( self::OPTION_PRICE_RANGE, sanitize_text_field( (string) $request->get_param( 'price_range' ) ) );

		// Business type (defaults to LocalBusiness).
		$business_type = sanitize_text_field( (string) $request->get_param( 'business_type' ) );
		if ( ! $business_type ) {
			$business_type = 'LocalBusiness';
		}
		update_option( self::OPTION_BUSINESS_TYPE, $business_type );

		// Country code.
		$country_code = sanitize_text_field( (string) $request->get_param( 'country_code' ) );
		update_option( self::OPTION_COUNTRY_CODE, strtoupper( substr( $country_code, 0, 2 ) ) );

		// Key URLs.
		$key_urls = $request->get_param( 'key_urls' );
		if ( ! is_array( $key_urls ) ) {
			$key_urls = [];
		}
		$sanitized_key_urls = [];
		foreach ( $key_urls as $slug => $url ) {
			if ( ! is_scalar( $url ) ) {
				continue;
			}
			$clean_url  = esc_url_raw( (string) $url );
			$clean_slug = sanitize_key( (string) $slug );
			if ( $clean_url && $clean_slug ) {
				$sanitized_key_urls[ $clean_slug ] = $clean_url;
			}
		}
		update_option( self::OPTION_KEY_URLS, $sanitized_key_urls );

		// Social profiles.
		$social_profiles = $request->get_param( 'social_profiles' );
		if ( ! is_array( $social_profiles ) ) {
			$social_profiles = [];
		}
		$sanitized_socials = [];
		foreach ( $social_profiles as $platform => $url ) {
			if ( ! is_scalar( $url ) ) {
				continue;
			}
			$clean_url      = esc_url_raw( (string) $url );
			$clean_platform = sanitize_key( (string) $platform );
			if ( $clean_url && $clean_platform ) {
				$sanitized_socials[ $clean_platform ] = $clean_url;
			}
		}
		update_option( self::OPTION_SOCIAL_PROFILES, $sanitized_socials );

		// Clear internal settings cache.
		self::clear_cache_settings();

		// Clear page cache if available.
		self::clear_cache();

		return self::get_settings( $request );
	}

	/**
	 * Clear various caches when visibility settings change.
	 */
	protected static function clear_cache() {
		// WordPress object cache.
		wp_cache_flush();

		// LiteSpeed Cache.
		if ( class_exists( 'LiteSpeed_Cache_API' ) && method_exists( 'LiteSpeed_Cache_API', 'purge_all' ) ) {
			call_user_func( [ 'LiteSpeed_Cache_API', 'purge_all' ] );
		}
		if ( has_action( 'litespeed_purge_all' ) ) {
			do_action( 'litespeed_purge_all' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Third-party LiteSpeed Cache hook.
		}

		// WP Super Cache.
		if ( function_exists( 'wp_cache_clear_cache' ) ) {
			call_user_func( 'wp_cache_clear_cache' );
		}

		// W3 Total Cache.
		if ( function_exists( 'w3tc_flush_all' ) ) {
			call_user_func( 'w3tc_flush_all' );
		}

		// WP Rocket.
		if ( function_exists( 'rocket_clean_domain' ) ) {
			call_user_func( 'rocket_clean_domain' );
		}

		// WP Fastest Cache.
		if ( function_exists( 'wpfc_clear_all_cache' ) ) {
			call_user_func( 'wpfc_clear_all_cache', true );
		}

		// Autoptimize.
		if ( class_exists( 'autoptimizeCache' ) && method_exists( 'autoptimizeCache', 'clearall' ) ) {
			call_user_func( [ 'autoptimizeCache', 'clearall' ] );
		}

		// SG Optimizer.
		if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
			call_user_func( 'sg_cachepress_purge_cache' );
		}

		// Cloudflare.
		if ( class_exists( '\\CF\\WordPress\\Hooks' ) ) {
			do_action( 'cloudflare_purge_everything' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Third-party Cloudflare hook.
		}
	}

	/**
	 * Try to infer a sitemap URL from the site configuration.
	 *
	 * @return string
	 */
	protected static function detect_sitemap_url() {
		$sitemap = '';

		if ( function_exists( 'wp_sitemaps_get_server' ) ) {
			$server = wp_sitemaps_get_server();
			if ( $server && $server->sitemaps_enabled() ) {
				$sitemap = home_url( '/wp-sitemap.xml' );
			}
		}

		if ( ! $sitemap ) {
			$sitemap = home_url( '/sitemap_index.xml' );
		}

		return (string) apply_filters( 'ai_visibility_sitemap_url', $sitemap );
	}

	/**
	 * Schema preview REST endpoint.
	 *
	 * @return \WP_REST_Response
	 */
	public static function schema_preview() {
		header( 'Content-Type: application/json; charset=utf-8' );
		$data = self::build_schema_data();
		return rest_ensure_response( $data );
	}

	/**
	 * Output llms.txt content when the request path is /llms.txt.
	 */
	public static function maybe_output_llms_txt() {
		$uri  = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
		$path = wp_parse_url( $uri, PHP_URL_PATH );

		if ( '/llms.txt' !== $path ) {
			return;
		}

		if ( ! get_option( self::OPTION_LLMS_ENABLED ) ) {
			status_header( 404 );
			echo 'llms.txt is disabled on this site.';
			exit;
		}

		nocache_headers();
		header( 'Content-Type: text/plain; charset=utf-8' );

		echo esc_html( self::generate_llms_content() );
		exit;
	}

	/**
	 * Generate llms.txt text based on stored business data.
	 *
	 * @return string
	 */
	protected static function generate_llms_content() {
		$lines   = [];
		$lines[] = '# AI Visibility llms.txt';
		$lines[] = '# Basic site and business metadata for AI agents.';

		$site_url        = home_url( '/' );
		$sitemap         = get_option( self::OPTION_SITEMAP_URL, '' );
		$business_name   = get_option( self::OPTION_BUSINESS_NAME );
		$business_desc   = get_option( self::OPTION_BUSINESS_DESC );
		$address         = get_option( self::OPTION_ADDRESS );
		$phone           = get_option( self::OPTION_PHONE );
		$hours           = get_option( self::OPTION_OPENING_HOURS );
		$email           = get_option( self::OPTION_BUSINESS_EMAIL );
		$logo            = get_option( self::OPTION_LOGO_URL );
		$price_range     = get_option( self::OPTION_PRICE_RANGE );
		$key_urls        = get_option( self::OPTION_KEY_URLS, [] );
		$social_profiles = get_option( self::OPTION_SOCIAL_PROFILES, [] );

		if ( empty( $sitemap ) ) {
			$sitemap = self::detect_sitemap_url();
		}

		$lines[] = 'site: ' . $site_url;
		if ( $sitemap ) {
			$lines[] = 'sitemap: ' . esc_url_raw( $sitemap );
		}
		if ( $business_name ) {
			$lines[] = 'business_name: ' . sanitize_text_field( $business_name );
		}
		if ( $business_desc ) {
			$lines[] = 'description: ' . sanitize_text_field( str_replace( [ "\r\n", "\r", "\n" ], ' ', $business_desc ) );
		}
		if ( $logo ) {
			$lines[] = 'logo: ' . esc_url_raw( $logo );
		}
		if ( $address ) {
			$lines[] = 'address: ' . sanitize_text_field( $address );
		}
		if ( $phone ) {
			$lines[] = 'phone: ' . sanitize_text_field( $phone );
		}
		if ( $hours ) {
			$lines[] = 'opening_hours: ' . sanitize_text_field( $hours );
		}
		if ( $email ) {
			$lines[] = 'contact_email: ' . sanitize_email( $email );
		}
		if ( $price_range ) {
			$lines[] = 'price_range: ' . sanitize_text_field( $price_range );
		}

		if ( is_array( $key_urls ) ) {
			foreach ( $key_urls as $slug => $url ) {
				$clean_slug = sanitize_key( (string) $slug );
				$clean_url  = esc_url_raw( (string) $url );
				if ( $clean_slug && $clean_url ) {
					$lines[] = 'url_' . $clean_slug . ': ' . $clean_url;
				}
			}
		}

		if ( is_array( $social_profiles ) ) {
			foreach ( $social_profiles as $platform => $url ) {
				$clean_platform = sanitize_key( (string) $platform );
				$clean_url      = esc_url_raw( (string) $url );
				if ( $clean_platform && $clean_url ) {
					$lines[] = 'social_' . $clean_platform . ': ' . $clean_url;
				}
			}
		}

		return implode( "\n", $lines ) . "\n";
	}

	/**
	 * Filter robots.txt based on the chosen AI visibility mode.
	 *
	 * @param string $output Existing robots.txt content.
	 * @param bool   $public Blog public flag.
	 * @return string
	 */
	public static function filter_robots_txt( $output, $public ) {
		$settings = self::get_cached_settings();
		$mode     = $settings['mode'];

		// Comprehensive list of AI bot user-agents.
		$ai_agents = [
			// OpenAI
			'GPTBot',
			'ChatGPT-User',
			'OAI-SearchBot',
			// Anthropic
			'ClaudeBot',
			'Claude-Web',
			'anthropic-ai',
			// Google
			'Google-Extended',
			'GoogleOther',
			'Google-Other',
			// Perplexity
			'PerplexityBot',
			// Microsoft
			'bingbot',
			'BingPreview',
			'msnbot',
			// Meta
			'meta-externalagent',
			'FacebookBot',
			'facebookexternalhit',
			// Apple
			'Applebot',
			// Amazon
			'Amazonbot',
			// ByteDance
			'Bytespider',
			// Common Crawl
			'CCBot',
			// Cohere
			'cohere-ai',
			// You.com
			'YouBot',
			// Other AI/Research bots
			'AI2Bot',
			'Diffbot',
			'omgili',
			'omgilibot',
		];

		$rules = [];

		if ( 'block' === $mode ) {
			foreach ( $ai_agents as $agent ) {
				$rules[] = 'User-agent: ' . $agent;
				$rules[] = 'Disallow: /';
			}
		} elseif ( 'balanced' === $mode ) {
			foreach ( $ai_agents as $agent ) {
				$rules[] = 'User-agent: ' . $agent;
				$rules[] = 'Disallow: /wp-admin/';
			}
		} else {
			$rules[] = '# AI Visibility: open mode';
		}

		if ( empty( $rules ) ) {
			return $output;
		}

		$base_output = rtrim( (string) $output );
		$joined      = implode( "\n", $rules );

		if ( '' !== $base_output ) {
			return $base_output . "\n" . $joined . "\n";
		}

		return $joined . "\n";
	}

	/**
	 * Output meta tags for AI crawlers based on visibility mode.
	 * Hooked to wp_head.
	 */
	public static function output_meta_tags() {
		$settings = self::get_cached_settings();
		$mode     = $settings['mode'];

		if ( 'block' === $mode ) {
			echo '<meta name="robots" content="noai, noimageai">' . "\n";
			echo '<meta name="googlebot" content="noai">' . "\n";
		} elseif ( 'balanced' === $mode ) {
			echo '<meta name="robots" content="noimageai">' . "\n";
		}
	}

	/**
	 * Output JSON-LD schema based on configured schema level.
	 */
	public static function output_schema() {
		$data = self::build_schema_data();
		echo "\n" . '<script type="application/ld+json">' . wp_json_encode( $data ) . '</script>' . "\n";
	}

	/**
	 * Build schema data for output or preview.
	 *
	 * @return array
	 */
	protected static function build_schema_data() {
		$settings = self::get_cached_settings();
		$level    = $settings['level'];

		if ( 'basic' !== $level && 'enhanced' !== $level && 'full' !== $level ) {
			$level = 'basic';
		}

		$site_name       = get_bloginfo( 'name' );
		$site_url        = home_url( '/' );
		$business_name   = $settings['business_name'];
		$business_desc   = $settings['business_desc'];
		$address_raw     = $settings['address'];
		$phone_raw       = $settings['phone'];
		$hours           = $settings['hours'];
		$voice_enabled   = $settings['voice'];
		$logo_url        = $settings['logo'];
		$price_range     = $settings['price_range'];
		$social_profiles = $settings['social_profiles'];

		// Normalize phone to E.164 format.
		$phone = preg_replace( '/[^0-9+]/', '', $phone_raw );
		if ( $phone && '+' !== substr( $phone, 0, 1 ) ) {
			$country_prefixes = [
				'US' => '+1', 'GB' => '+44', 'DE' => '+49', 'FR' => '+33',
				'ES' => '+34', 'IT' => '+39', 'NL' => '+31', 'BE' => '+32',
				'AT' => '+43', 'CH' => '+41', 'PT' => '+351', 'RO' => '+40',
				'PL' => '+48', 'CZ' => '+420', 'SE' => '+46', 'NO' => '+47',
				'DK' => '+45', 'FI' => '+358', 'AU' => '+61', 'NZ' => '+64',
				'CA' => '+1', 'BR' => '+55', 'MX' => '+52', 'JP' => '+81',
				'KR' => '+82', 'IN' => '+91', 'IL' => '+972', 'AE' => '+971',
				'SA' => '+966', 'ZA' => '+27', 'IE' => '+353', 'AR' => '+54',
			];
			$country_code_setting = $settings['country_code'];
			if ( $country_code_setting && isset( $country_prefixes[ $country_code_setting ] ) ) {
				if ( '0' === substr( $phone, 0, 1 ) ) {
					$phone = $country_prefixes[ $country_code_setting ] . substr( $phone, 1 );
				} else {
					$phone = $country_prefixes[ $country_code_setting ] . $phone;
				}
			}
		}

		// Parse address into locality/region/country.
		$address_parts = array_map( 'trim', explode( ',', $address_raw ) );
		$locality      = isset( $address_parts[0] ) && $address_parts[0] ? $address_parts[0] : '';
		$region        = isset( $address_parts[1] ) && $address_parts[1] ? $address_parts[1] : '';

		// Country code: use configured, auto-detect from locale, or leave empty.
		$country = $settings['country_code'];
		if ( ! $country ) {
			$wp_locale = get_locale();
			$parts     = explode( '_', $wp_locale );
			$country   = isset( $parts[1] ) ? strtoupper( $parts[1] ) : '';
		}

		// Business type: configurable, defaults to LocalBusiness.
		$business_type_raw = $settings['business_type'];
		$business_types    = $business_type_raw ? array_map( 'trim', explode( ',', $business_type_raw ) ) : [ 'LocalBusiness' ];

		// Get locale for inLanguage.
		$locale = get_locale();
		$lang   = str_replace( '_', '-', $locale );

		// Build sameAs array from social profiles.
		$same_as = [];
		if ( is_array( $social_profiles ) ) {
			foreach ( $social_profiles as $url ) {
				if ( $url ) {
					$same_as[] = esc_url_raw( $url );
				}
			}
		}

		$graph    = [];
		$org_name = $business_name ? $business_name : $site_name;

		// WebSite (always).
		$website = [
			'@type'      => 'WebSite',
			'@id'        => $site_url . '#website',
			'url'        => $site_url,
			'name'       => $org_name,
			'inLanguage' => $lang,
			'potentialAction' => [
				'@type'       => 'SearchAction',
				'target'      => $site_url . '?s={search_term_string}',
				'query-input' => 'required name=search_term_string',
			],
		];
		$graph[] = $website;

		// WebPage (always) – with speakable if voice enabled.
		$webpage = [
			'@type'      => 'WebPage',
			'@id'        => $site_url . '#webpage',
			'url'        => $site_url,
			'name'       => $org_name,
			'inLanguage' => $lang,
			'isPartOf'   => [ '@id' => $site_url . '#website' ],
			'about'      => [ '@id' => $site_url . '#business' ],
		];
		if ( $voice_enabled ) {
			$webpage['speakable'] = [
				'@type'       => 'SpeakableSpecification',
				'cssSelector' => [ 'h1', '.aiv-voice-summary' ],
			];
		}
		$graph[] = $webpage;

		// Business entity (configurable type).
		$schema_type = count( $business_types ) === 1 ? $business_types[0] : $business_types;
		$business = [
			'@type' => $schema_type,
			'@id'   => $site_url . '#business',
			'name'  => $org_name,
			'url'   => $site_url,
		];
		if ( $business_desc ) {
			$business['description'] = sanitize_text_field( $business_desc );
		}
		if ( $logo_url ) {
			$business['logo']  = esc_url_raw( $logo_url );
			$business['image'] = esc_url_raw( $logo_url );
		}
		if ( $phone ) {
			$business['telephone'] = $phone;
		}
		if ( $locality ) {
			$business['address'] = [
				'@type'           => 'PostalAddress',
				'addressLocality' => $locality,
			];
			if ( $region ) {
				$business['address']['addressRegion'] = $region;
			}
			$business['address']['addressCountry'] = $country;
		}
		if ( $hours ) {
			$business['openingHours'] = sanitize_text_field( $hours );
		}
		if ( $price_range ) {
			$business['priceRange'] = sanitize_text_field( $price_range );
		}
		if ( $region ) {
			$business['areaServed'] = [
				'@type' => 'AdministrativeArea',
				'name'  => $region,
			];
		}
		if ( ! empty( $same_as ) ) {
			$business['sameAs'] = $same_as;
		}
		$graph[] = $business;

		// Enhanced level: per-page Article + simple breadcrumb.
		if ( in_array( $level, [ 'enhanced', 'full' ], true ) && is_singular() ) {
			$post_id = get_queried_object_id();
			if ( $post_id ) {
				$permalink = get_permalink( $post_id );
				$headline  = get_the_title( $post_id );
				$article   = [
					'@type'         => 'Article',
					'@id'           => $permalink . '#article',
					'headline'      => $headline,
					'url'           => $permalink,
					'inLanguage'    => $lang,
					'datePublished' => get_the_date( DATE_W3C, $post_id ),
					'dateModified'  => get_the_modified_date( DATE_W3C, $post_id ),
					'isPartOf'      => [ '@id' => $site_url . '#website' ],
				];
				$graph[] = $article;

				$breadcrumbs = [
					[
						'@type'    => 'ListItem',
						'position' => 1,
						'name'     => $site_name,
						'item'     => $site_url,
					],
					[
						'@type'    => 'ListItem',
						'position' => 2,
						'name'     => $headline,
						'item'     => $permalink,
					],
				];

				$graph[] = [
					'@type'           => 'BreadcrumbList',
					'@id'             => $site_url . '#breadcrumb',
					'itemListElement' => $breadcrumbs,
				];
			}
		}

		return [
			'@context' => 'https://schema.org',
			'@graph'   => $graph,
		];
	}
}
