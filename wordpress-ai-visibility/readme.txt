=== AI Visibility ===
Contributors: pulserank
Tags: ai, llms, schema, robots, seo
Requires at least: 5.8
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Control how AI engines see your WordPress site. Manage llms.txt, robots.txt AI rules, Schema.org, and voice optimization.

== Description ==

**AI Visibility** gives you full control over how AI platforms like ChatGPT, Google Gemini, Perplexity, Claude, and others interact with your WordPress site.

= Features =

* **llms.txt Generation** — Create a machine-readable file at `/llms.txt` that tells AI agents about your business: name, description, contact info, key URLs, social profiles, and more.
* **AI Visibility Mode** — One-click switch between Open, Balanced, and Block modes. Automatically updates both `robots.txt` rules and HTML meta tags for 30+ AI bot user-agents.
* **Structured Data (Schema.org)** — Injects JSON-LD structured data into your pages with three detail levels: Basic, Enhanced, and Full. Includes WebSite, WebPage, LocalBusiness, Article, BreadcrumbList, and more.
* **Voice & Multimodal Optimization** — Adds SpeakableSpecification schema to make your content ready for voice assistants and image-based AI results.
* **Social Profiles** — Add your social media URLs to enhance Schema.org `sameAs` connections.
* **Schema Preview** — Preview your structured data output directly from the admin panel.
* **Cache-Aware** — Automatically clears popular caching plugins (LiteSpeed, WP Rocket, W3 Total Cache, WP Super Cache, etc.) when settings change.
* **No License Required** — 100% free, no premium locks, no Freemius SDK.

= AI Bots Managed =

Robots.txt rules are generated for: GPTBot, ChatGPT-User, OAI-SearchBot, ClaudeBot, Claude-Web, anthropic-ai, Google-Extended, GoogleOther, PerplexityBot, bingbot, BingPreview, msnbot, meta-externalagent, FacebookBot, Applebot, Amazonbot, Bytespider, CCBot, cohere-ai, YouBot, AI2Bot, Diffbot, and more.

= Want More? =

**[PulseRank](https://pulserank.ai)** is the premium version with AI analytics, bot tracking dashboards, content optimization audits, conversion tracking, email reports, and real-time bot activity monitoring.

== Installation ==

1. Upload the `ai-visibility` folder to `/wp-content/plugins/`.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Go to **AI Visibility** in your admin sidebar to configure settings.

== Frequently Asked Questions ==

= What is llms.txt? =

llms.txt is a plain-text file (similar to robots.txt) that provides machine-readable business metadata to AI agents like ChatGPT, Gemini, and Perplexity, helping them understand and accurately represent your site.

= Will this slow down my site? =

No. The plugin only adds lightweight meta tags and JSON-LD to your page head. There are no database tables, no tracking scripts, and no external API calls.

= Does this work with caching plugins? =

Yes. When you save settings, AI Visibility automatically purges caches from LiteSpeed, WP Rocket, WP Super Cache, W3 Total Cache, WP Fastest Cache, Autoptimize, SG Optimizer, and Cloudflare.

= What's the difference between this and PulseRank? =

AI Visibility is the free visibility-only version. PulseRank adds AI analytics dashboards, bot tracking, content optimization, conversion tracking, email reports, and more.

== Changelog ==

= 1.0.0 =
* Initial release — extracted visibility features from PulseRank.
* llms.txt generation with full business metadata.
* AI Visibility Mode (Open / Balanced / Block).
* Schema.org structured data (Basic / Enhanced / Full).
* Voice & Multimodal optimization with SpeakableSpecification.
* robots.txt filtering for 30+ AI bot user-agents.
* Meta tag output for AI crawl control.
* Cache-aware settings saving.

== Upgrade Notice ==

= 1.0.0 =
First release of AI Visibility — free AI visibility management for WordPress.
