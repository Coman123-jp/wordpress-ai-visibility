# Security Policy

## Supported Versions

| Version | Supported |
|---------|-----------|
| 1.0.x   | ✅ Yes    |
| < 1.0   | ❌ No     |

Only the latest release receives security fixes. Update to the latest version as soon as possible.

---

## Reporting a Vulnerability

**Do not open a public GitHub issue for security vulnerabilities.**

To report a security issue:

1. Email **TODO: support@pulserank.ai** (replace with the correct address)
2. Include in your report:
   - A description of the vulnerability and its potential impact
   - Steps to reproduce or a proof-of-concept (do not include a working exploit in a public channel)
   - The WordPress version, PHP version, and AI Visibility version affected
   - Your contact information for follow-up

### What to expect

- **Acknowledgement:** We aim to acknowledge receipt within **3 business days**. This is a target, not a guarantee.
- **Assessment:** We will assess severity and impact. We may ask follow-up questions.
- **Fix timeline:** We aim to release a fix within **14 days** for critical issues. Complex issues may take longer; we will keep you informed.
- **Disclosure:** We follow a coordinated disclosure approach. We ask that you allow time for a fix to be released before making the vulnerability public.
- **Credit:** If you wish, we will acknowledge your contribution in the release notes after the fix is published.

We do not currently operate a formal bug bounty programme.

---

## Scope

This policy covers the `ai-visibility` WordPress plugin code in this repository.

Out of scope:
- Vulnerabilities in WordPress core itself
- Vulnerabilities in third-party plugins or themes
- Issues on the [pulserank.ai](https://pulserank.ai) website (contact PulseRank directly for those)

---

## General Security Notes

- The plugin does not make external HTTP requests
- The plugin does not collect, store, or transmit visitor data
- All REST API endpoints check `manage_options` capability
- Settings are sanitized on save and escaped on output
- User-supplied data is never passed to `eval`, `exec`, or equivalent functions
