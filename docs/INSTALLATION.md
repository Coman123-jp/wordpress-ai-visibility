# Installation Guide — AI Visibility

This guide covers all installation methods and post-install verification steps.

---

## Requirements

| Requirement | Minimum |
|-------------|---------|
| WordPress | 5.8 |
| PHP | 7.4 |
| MySQL / MariaDB | Standard WordPress requirement |
| Server write access | Not required (no file writes) |

---

## Method 1 — WordPress Admin (ZIP Upload)

This is the recommended method for most users.

1. Go to the [Releases](https://github.com/Coman123-jp/ai-visibility/releases) page
2. Download `ai-visibility.zip` from the latest release
3. In your WordPress admin, go to **Plugins → Add New**
4. Click **Upload Plugin** at the top
5. Choose the downloaded ZIP file and click **Install Now**
6. Click **Activate Plugin**
7. Go to **AI Visibility** in the left sidebar to configure settings

**Verify:** Visit `https://yourdomain.com/llms.txt` — if enabled in settings, you should see your llms.txt content.

---

## Method 2 — Manual (FTP / SSH / cPanel)

Use this if your server does not allow ZIP uploads or you prefer direct access.

1. Download and unzip `ai-visibility.zip` on your local machine
2. Connect to your server via FTP, SSH, or cPanel File Manager
3. Upload the `ai-visibility/` folder to:
   ```
   /wp-content/plugins/ai-visibility/
   ```
4. In WordPress admin, go to **Plugins → Installed Plugins**
5. Find **AI Visibility** and click **Activate**

### Folder structure after upload

```
/wp-content/plugins/ai-visibility/
├── ai-visibility.php
├── readme.txt
├── admin/
│   └── class-ai-visibility-admin.php
├── assets/
│   └── css/admin.css
├── build/
│   ├── admin-app.js
│   └── admin-app.asset.php
└── includes/
    └── class-ai-visibility.php
```

---

## Method 3 — Clone from Source (Developers)

The `ai-visibility/` folder in this repository is the production-ready plugin — no build step is needed for deployment.

```bash
git clone https://github.com/Coman123-jp/ai-visibility.git
cp -r ai-visibility/ai-visibility /path/to/wp-content/plugins/
```

Then activate from **Plugins → Installed Plugins** in WordPress admin.

---

## Post-Install Checklist

- [ ] Plugin activated (no error on Plugins screen)
- [ ] **AI Visibility** menu item visible in sidebar
- [ ] AI Visibility Mode set to your preference (Open / Balanced / Block)
- [ ] llms.txt enabled and business details filled in
- [ ] Visit `yourdomain.com/llms.txt` and confirm it loads
- [ ] Schema level selected (Basic / Enhanced / Full)
- [ ] View page source on the front end: confirm JSON-LD and meta tags are present

---

## Updating the Plugin

### Via WordPress Admin
Use the standard WordPress plugin update process when a new version is released.

### Via ZIP (manual)
Deactivate → delete the old plugin → re-upload the new ZIP → re-activate.  
Your settings are stored in `wp_options` and will not be lost.

### Via Git
```bash
git pull origin main
cp -r ai-visibility/ai-visibility /path/to/wp-content/plugins/
```

---

## Uninstalling

1. Go to **Plugins → Installed Plugins**
2. Deactivate **AI Visibility**
3. Click **Delete**

On deletion, the uninstall hook removes all `aiv_*` options from `wp_options`. No data is left behind.

---

## Troubleshooting Installation

**Plugin does not appear in the Plugins list after upload**  
→ Check that the ZIP was uploaded intact. The outer folder inside the ZIP must be named `ai-visibility`.

**"The plugin does not have a valid header" error**  
→ The main file must be at `ai-visibility/ai-visibility.php`. If you see a different path, re-download the ZIP from the Releases page.

**`llms.txt` returns a 404**  
→ Go to **Settings → Permalinks** and click **Save Changes** to flush rewrite rules, then try again.

See [Troubleshooting](TROUBLESHOOTING.md) for more issues.
