# FAQ — AI Visibility

Frequently asked questions, written in the language users search.  
For setup steps see [INSTALLATION.md](INSTALLATION.md). For errors see [TROUBLESHOOTING.md](TROUBLESHOOTING.md).

---

## General

### What does the AI Visibility plugin do?
It gives WordPress site owners technical control over how AI platforms — ChatGPT, Perplexity, Google Gemini, Claude, and others — crawl, read, and represent their site. It generates an `llms.txt` file, manages AI bot rules in `robots.txt`, injects Schema.org structured data as JSON-LD, and adds HTML meta tags for AI crawl control.

### Is AI Visibility free?
Yes. All features described in the README and plugin settings are available at no cost. There is no "Pro" upsell inside the plugin settings and no feature locked behind a licence key.

### What is the difference between AI Visibility and PulseRank?
AI Visibility is the free technical visibility layer — it controls what AI agents see. [PulseRank](https://pulserank.ai) is the premium analytics layer — it measures how often AI agents crawl your site, which pages they read, and how that translates to citations and conversions.

### Who makes this plugin?
PulseRank — [pulserank.ai](https://pulserank.ai). The plugin is open source under the GPL-2.0-or-later licence.

---

## llms.txt

### What is llms.txt?
`llms.txt` is a plain-text file placed at the root of your domain (e.g., `yourdomain.com/llms.txt`) that gives AI language models structured metadata about your business: name, description, address, phone, hours, email, key page URLs, and social profiles. It is analogous to `sitemap.xml` for search engines but designed for AI agents.

### How do I enable llms.txt?
Go to **AI Visibility → llms.txt**, toggle the enable switch, fill in your business details, and save. The file is served dynamically — no physical file is written to disk.

### Why is my llms.txt returning a 404?
Go to **Settings → Permalinks** in WordPress admin and click **Save Changes** without changing anything. This flushes rewrite rules. Then visit `yourdomain.com/llms.txt` again.

### Can I customise what goes into llms.txt?
Yes. Everything in the llms.txt section of the admin panel is configurable: business name, description, address, phone, opening hours, email, logo, key URLs, social profiles, and sitemap URL.

---

## AI Visibility Mode

### What are the three AI Visibility Modes?
- **Open** — All managed AI bots are allowed to crawl and index your site
- **Balanced** — Bots may read content but are instructed not to use it for AI model training
- **Block** — All 30+ managed AI user-agents are disallowed in `robots.txt`

### Does blocking AI bots actually work?
`robots.txt` rules and HTML meta tags are advisory standards. Reputable bots (GPTBot, ClaudeBot, Googlebot) respect them. Some data-harvesting bots may not. This plugin implements all standard methods; it cannot technically prevent page requests.

### Will blocking AI bots affect my Google search ranking?
The **Block** mode targets AI-specific user-agents (GPTBot, Google-Extended, etc.), not `Googlebot`. Your standard Google search indexing is not affected.

### Which AI bots does the plugin manage?
`GPTBot`, `ChatGPT-User`, `OAI-SearchBot`, `ClaudeBot`, `Claude-Web`, `anthropic-ai`, `Google-Extended`, `GoogleOther`, `PerplexityBot`, `bingbot`, `BingPreview`, `msnbot`, `meta-externalagent`, `FacebookBot`, `Applebot`, `Amazonbot`, `Bytespider`, `CCBot`, `cohere-ai`, `YouBot`, `AI2Bot`, `Diffbot`, and others. The full list is in the source: `includes/class-ai-visibility.php`.

---

## Schema.org Structured Data

### What is Schema.org structured data?
JSON-LD markup added to your page `<head>` that describes your content in a machine-readable format. Search engines and AI models use it to understand entities (your business, articles, products) and their attributes.

### What is the difference between Basic, Enhanced, and Full schema levels?
- **Basic** — Adds `WebSite` and `WebPage` schemas only
- **Enhanced** — Adds `LocalBusiness` or `Organization` schema with address, phone, and opening hours
- **Full** — Adds `Article`, `BreadcrumbList`, and `SpeakableSpecification` on top of Enhanced

### I already have schema from Yoast SEO or RankMath. Will there be a conflict?
Duplicate JSON-LD blocks are not technically harmful; validators will read both. However, for cleanliness, if you already have a full schema plugin active, use the **Basic** level in AI Visibility or disable structured data entirely and use the plugin only for llms.txt and AI bot control.

### What is SpeakableSpecification?
A Schema.org property that marks specific sections of a page as suitable for text-to-speech rendering. Voice assistants (Google Assistant, Siri, Alexa) and AI models may use it to identify the most important parts of a page for spoken summaries.

---

## Privacy & Data

### Does this plugin collect any data about my visitors?
No. The plugin does not collect, transmit, or store any visitor data. No analytics, no tracking.

### Does it make any external HTTP requests?
No. All processing is local to your WordPress installation.

### Does it use cookies?
No cookies are set by the plugin.

### Does it log IP addresses?
No.

### Is it GDPR-compliant?
The plugin itself introduces no GDPR obligations because it collects no personal data. You remain responsible for your own site's data practices. This is not legal advice.

---

## Performance

### Will AI Visibility slow down my site?
The plugin adds lightweight JSON-LD and `<meta>` tags to `<head>`. No JavaScript is loaded on the front end. No additional database queries are made after WordPress loads its options cache. Measurable performance impact is not expected, but TODO: no formal benchmark has been published yet.

---

## Troubleshooting

### The admin panel is blank / shows a loading spinner that never resolves
See [TROUBLESHOOTING.md → Admin panel is blank](TROUBLESHOOTING.md#admin-panel-is-blank).

### Settings are not saving
See [TROUBLESHOOTING.md → Settings not saving](TROUBLESHOOTING.md#settings-not-saving).

---

_Can't find your question? [Open an issue](https://github.com/Coman123-jp/ai-visibility/issues/new?template=bug_report.md) on GitHub._
