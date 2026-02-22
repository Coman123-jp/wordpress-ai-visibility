# Troubleshooting — AI Visibility

This guide covers common issues. If your problem is not listed, [open a bug report](https://github.com/Coman123-jp/ai-visibility/issues/new?template=bug_report.md).

---

## Before You Start

Gather this information before debugging — it will be required if you open an issue:

- WordPress version: **Dashboard → Updates**
- PHP version: **Tools → Site Health → Info → Server**
- AI Visibility version: **Plugins → Installed Plugins**
- Active theme name and version
- List of other active plugins
- Any PHP error log lines related to `ai-visibility`

---

## Issue: llms.txt Returns a 404

**Symptom:** `yourdomain.com/llms.txt` shows a WordPress "Page not found" error.

**Causes and fixes:**

1. **Rewrite rules not flushed after activation**  
   → Go to **Settings → Permalinks** and click **Save Changes** (no other changes needed).

2. **llms.txt is not enabled in settings**  
   → Go to **AI Visibility → llms.txt** and confirm the enable toggle is on and settings are saved.

3. **Caching plugin serving a cached 404**  
   → Purge your cache manually, then test in an incognito window.

4. **Custom permalink structure conflict**  
   → Try switching to the **Post name** permalink structure temporarily and test. If it works, your custom structure may be conflicting. Report the specific structure in an issue.

---

## Issue: Admin Panel Is Blank

**Symptom:** The AI Visibility settings page loads but shows only a blank white area or a spinner that never resolves.

**Causes and fixes:**

1. **JavaScript error in the browser console**  
   → Open DevTools (F12 → Console tab). Copy any red error messages and include them in your bug report.

2. **REST API is blocked**  
   → Visit `yourdomain.com/wp-json/ai-visibility/v1/visibility` in your browser while logged in as an admin. If it shows an error or redirects, your REST API may be blocked by a security plugin or server rule.  
   → Check for plugins like Wordfence, iThemes Security, or Kinsta/WP Engine rules that block `/wp-json/`.

3. **Script does not load**  
   → Go to **Settings → Permalinks** and save. Then check the page source of the AI Visibility admin page for a `<script src="...build/admin-app.js">` tag. If the file path looks wrong, check that the plugin folder name is exactly `ai-visibility`.

4. **Conflicting plugin**  
   → Temporarily deactivate all other plugins except AI Visibility and test. Re-enable them one by one to identify the conflict.

---

## Issue: Settings Not Saving

**Symptom:** Clicking Save returns "Saved" but settings revert on page reload.

**Causes and fixes:**

1. **REST API permissions blocked**  
   → Confirm you are logged in as a user with the `manage_options` capability (typically an Administrator). The REST API endpoints require this capability.

2. **Nonce expired**  
   → If your session has been idle for a long time, the nonce may need refreshing. Hard-reload the page (Ctrl+Shift+R / Cmd+Shift+R) and try saving again.

3. **Caching plugin caching the REST API response**  
   → Some caching plugins cache `/wp-json/` responses. Ensure REST API caching is disabled.

4. **Object cache conflict**  
   → If you use a persistent object cache (Redis, Memcached), try disabling it temporarily. If settings then save correctly, the object cache layer may be interfering with `wp_options` writes.

---

## Issue: Structured Data Not Appearing in Page Source

**Symptom:** You enabled structured data but there is no JSON-LD block in the front-end page source.

**Causes and fixes:**

1. **Schema level is set to "none" / disabled**  
   → Confirm the schema level is set to Basic, Enhanced, or Full in settings.

2. **Page is served from cache**  
   → Purge your cache and view the source in an incognito window.

3. **Theme overrides `wp_head`**  
   → Some page builder themes or heavily customised themes do not call `wp_head()`. Check your theme's `header.php` for `<?php wp_head(); ?>`. If missing, this is a theme bug.

4. **Another plugin dequeues `wp_head` actions**  
   → Test with all other plugins deactivated.

---

## Issue: AI Visibility Mode Change Has No Effect on robots.txt

**Symptom:** After switching modes, `yourdomain.com/robots.txt` still shows old rules.

**Causes and fixes:**

1. **Cache not purged**  
   → The plugin attempts to auto-purge supported caching plugins on save. If your cache plugin is not in the supported list, purge it manually.

2. **Another plugin or theme is managing robots.txt**  
   → Yoast SEO, RankMath, and some security plugins also hook into `robots_txt`. Check for conflicts. AI Visibility uses the `robots_txt` filter with priority 10.

3. **Static `robots.txt` file exists on disk**  
   → If a physical `robots.txt` file exists in your WordPress root, WordPress will serve that file and the `robots_txt` filter will not run. Delete or rename the static file.

---

## Enabling PHP Debug Logging

To capture PHP errors:

1. Add to `wp-config.php`:
   ```php
   define( 'WP_DEBUG', true );
   define( 'WP_DEBUG_LOG', true );
   define( 'WP_DEBUG_DISPLAY', false );
   ```
2. Check `/wp-content/debug.log` after reproducing the issue
3. Include relevant lines in your bug report (remove any sensitive site URLs)

---

## Opening a Bug Report

If none of the above resolves your issue:

1. Reproduce the problem on a clean WordPress install if possible
2. Collect the information listed in [Before You Start](#before-you-start)
3. [Open a bug report](https://github.com/Coman123-jp/ai-visibility/issues/new?template=bug_report.md) using the template

Please do not open a public issue for security vulnerabilities — see [SECURITY.md](../SECURITY.md).
