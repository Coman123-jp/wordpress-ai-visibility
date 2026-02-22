# Contributing to AI Visibility

Thank you for your interest in contributing. This document explains how to file issues, submit code, and follow project conventions.

---

## Table of Contents

1. [Code of Conduct](#code-of-conduct)
2. [Ways to Contribute](#ways-to-contribute)
3. [Reporting Bugs](#reporting-bugs)
4. [Suggesting Features](#suggesting-features)
5. [Development Setup](#development-setup)
6. [Coding Standards](#coding-standards)
7. [Commit Conventions](#commit-conventions)
8. [Pull Request Process](#pull-request-process)
9. [Changelog Discipline](#changelog-discipline)

---

## Code of Conduct

This project follows the [Contributor Covenant Code of Conduct](CODE_OF_CONDUCT.md).  
By participating you agree to abide by its terms.

---

## Ways to Contribute

- **Bug reports** — [Open a bug report](../../issues/new?template=bug_report.md)
- **Feature requests** — [Open a feature request](../../issues/new?template=feature_request.md)
- **Code** — Fork, branch, implement, and submit a pull request
- **Documentation** — Improve files in `/docs`, fix typos, add examples
- **Testing** — Test on different WordPress / PHP version combinations and report results

---

## Reporting Bugs

Before filing:
1. Search [existing issues](../../issues) to avoid duplicates
2. Reproduce on a clean WordPress install if possible
3. Disable other plugins to rule out conflicts

Use the [bug report template](../../issues/new?template=bug_report.md) and include:
- WordPress version, PHP version, AI Visibility version
- Exact steps to reproduce
- What you expected vs. what happened
- Any relevant PHP error log lines

**Do not** report security vulnerabilities as public issues — see [SECURITY.md](SECURITY.md).

---

## Suggesting Features

Use the [feature request template](../../issues/new?template=feature_request.md).

Include:
- The problem the feature solves
- Your proposed solution or approach
- Any alternatives you considered

Features that align with the plugin's scope (AI crawler visibility for WordPress) are most likely to be accepted.

---

## Development Setup

The `ai-visibility/` folder in this repository is the production-ready plugin.  
If you need to modify the React admin UI, you need the source build:

```bash
# Clone and install dependencies
git clone https://github.com/Coman123-jp/ai-visibility.git
cd ai-visibility

# The source JS lives in ai-visibility/src/admin-app.js
# Build tooling is managed with @wordpress/scripts

# TODO: Add steps if a separate dev branch with package.json is published
```

> **Note:** The `build/` directory contains the compiled JS and is committed to the repository so the plugin works without a Node.js build step for end users.

---

## Coding Standards

### PHP
- Follow [WordPress PHP Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/)
- Use namespaces: `AIVisibility\`
- Sanitize all inputs with appropriate WordPress functions (`sanitize_text_field`, `absint`, etc.)
- Escape all outputs (`esc_html`, `esc_attr`, `esc_url`, `wp_kses_post`)
- All REST API callbacks must have a `permission_callback`
- No inline SQL; use `$wpdb` with prepared statements if DB access is ever needed

### JavaScript
- ESNext (ES2020+), JSX via `@wordpress/scripts`
- Use `@wordpress/element` (not direct React imports) for compatibility
- Follow [WordPress JavaScript Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/javascript/)

### General
- No external network requests from either PHP or JS
- No hardcoded credentials or keys
- Keep functions single-purpose and testable

---

## Commit Conventions

Use concise, imperative-mood commit messages:

```
Add: support for X
Fix: incorrect schema output when Y
Update: bot list to include Z
Remove: deprecated option key
Docs: clarify installation steps
```

Reference issue numbers when applicable: `Fix: llms.txt 404 on subdirectory installs (#42)`

---

## Pull Request Process

1. Fork the repository and create a branch from `main`:
   ```bash
   git checkout -b fix/llms-txt-subdirectory
   ```
2. Make your changes following the coding standards above
3. Update `CHANGELOG.md` under `[Unreleased]` with a short description
4. Open a pull request against `main` using the [PR template](.github/PULL_REQUEST_TEMPLATE.md)
5. A maintainer will review and may request changes before merging

---

## Changelog Discipline

Every PR that changes behaviour, fixes a bug, or adds a feature **must** include an entry in `CHANGELOG.md` under `[Unreleased]`.

Format:
```markdown
### Added / Fixed / Changed / Removed
- Short description of what changed and why (#issue-number)
```

---

Thank you for helping improve AI Visibility.
