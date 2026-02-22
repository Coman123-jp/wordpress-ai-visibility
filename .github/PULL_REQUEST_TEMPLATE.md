## Summary

<!-- One paragraph: what does this PR do and why? -->

---

## Type of Change

- [ ] Bug fix (non-breaking change that fixes an issue)
- [ ] New feature (non-breaking change that adds functionality)
- [ ] Breaking change (fix or feature that changes existing behaviour)
- [ ] Documentation update
- [ ] Refactor (no functional change)

---

## Related Issue

<!-- Link to the issue this PR addresses. Use "Closes #123" to auto-close on merge. -->

Closes #

---

## Testing

**How was this tested?**

- [ ] Manually tested on WordPress <!-- version --> + PHP <!-- version -->
- [ ] Tested with the default WordPress theme (Twenty Twenty-Four or similar)
- [ ] Tested with all other plugins deactivated
- [ ] Tested with at least one caching plugin active

**Steps to verify the change works correctly:**

1. 
2. 
3. 

---

## Screenshots (if applicable)

<!-- Include before/after screenshots for UI changes -->

---

## Checklist

- [ ] My code follows the [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/wordpress-coding-standards/) for PHP
- [ ] I have added an entry to `CHANGELOG.md` under `[Unreleased]`
- [ ] All user-facing strings are passed through `__()` / `esc_html__()` for translation
- [ ] All inputs are sanitized; all outputs are escaped
- [ ] No external HTTP requests are introduced
- [ ] No new options are added to `wp_options` without a corresponding uninstall cleanup
- [ ] No hardcoded credentials, API keys, or secrets
