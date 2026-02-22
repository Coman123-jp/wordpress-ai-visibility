# Changelog

All notable changes to **AI Visibility** are documented here.

Format follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).  
Versioning follows [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

_Nothing unreleased yet._

---

## [1.0.0] — 2026-02-22

### Added
- Initial public release — visibility features split from the [PulseRank](https://pulserank.ai) platform
- **llms.txt** generation: dynamic endpoint at `/llms.txt` with configurable business name, description, address, phone, opening hours, email, logo URL, key page URLs, social profiles, and sitemap URL
- **AI Visibility Mode**: Open / Balanced / Block — updates both `robots.txt` rules and HTML `<meta>` tags for 30+ named AI bot user-agents simultaneously
- **Schema.org structured data** at three levels:
  - Basic: `WebSite` + `WebPage`
  - Enhanced: adds `LocalBusiness` / `Organization` with address, phone, and hours
  - Full: adds `Article`, `BreadcrumbList`, and `SpeakableSpecification`
- **Voice & Multimodal optimization**: `SpeakableSpecification` schema for voice assistant indexing
- **Schema Preview** panel in WordPress admin (renders live JSON-LD output)
- **Social Profiles**: configurable `sameAs` URLs for Schema.org entity
- **Cache-aware save**: auto-purges LiteSpeed Cache, WP Rocket, W3 Total Cache, WP Super Cache, WP Fastest Cache, Autoptimize, SG Optimizer, Cloudflare
- REST API endpoints:
  - `GET  /wp-json/ai-visibility/v1/visibility` — retrieve settings
  - `POST /wp-json/ai-visibility/v1/visibility` — save settings
  - `GET  /wp-json/ai-visibility/v1/visibility/schema-preview` — preview JSON-LD
- Activation hook: `flush_rewrite_rules()` so `/llms.txt` works immediately
- Deactivation hook: `flush_rewrite_rules()` cleanup
- Uninstall hook: removes all `aiv_*` options from `wp_options`
- React-based admin UI built with `@wordpress/scripts` (no jQuery dependency)
- Zero external API calls, zero cookies, zero visitor data collection

---

[Unreleased]: https://github.com/Coman123-jp/ai-visibility/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/Coman123-jp/ai-visibility/releases/tag/v1.0.0
