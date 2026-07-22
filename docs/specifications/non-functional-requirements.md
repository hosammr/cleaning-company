# HDS Onderhoudsdiensten — Non-Functional Requirements

**Document ID:** NFR-001 | **Version:** 1.0.0 | **Status:** Approved for Development
**Project:** helderduidelijkschoon.nl — Ground-Up Rebuild
**Language:** Nederlands (nl-NL) | **Date:** July 2026
**Referenced Documents:** MPS-001, SAD-001, ADR-001, BKLG-001, ARR-001, RTM-001, FS-001, SRC-01 through SRC-08, RS-01 through RS-08

---

## 1. Purpose

This Non-Functional Requirements (NFR) specification defines every quality attribute of the HDS Onderhoudsdiensten platform. Non-functional requirements describe *how* the system performs its functions — the qualities that determine whether the system is operationally acceptable.

This document is binding for all development sprints (Sprint 0–8), for the QA gate in Sprint 7, and for the launch readiness assessment in Sprint 8. Every requirement defined here is traceable to the Requirements Traceability Matrix (RTM-001) and is consistent with the Architecture Decision Record (ADR-001) and Functional Specification (FS-001).

**Key Principle:** Quality is not a feature added after development. Every non-functional requirement defined here must be designed into the system from Sprint 1. Performance, security, accessibility, and SEO are architectural properties, not post-hoc optimizations.

---

## 2. Scope

### 2.1 In Scope

| Quality Domain | Count | Reference |
|---|---|---|
| Performance | 14 requirements | MPS-001 §H3; RTM REQ-PERF-001..014 |
| Availability | 5 requirements | SAD §30; RTM REQ-OPS |
| Scalability | 6 dimensions | SAD §40; ADR §11 |
| Security | 16 requirements | MPS-001 §H1; RTM REQ-SEC-001..016 |
| Privacy & GDPR | 13 requirements | MPS-001 §H2; RTM REQ-CMP-001..013 |
| Accessibility | 20 requirements | MPS-001 §H4; RTM REQ-ACC-001..020 |
| SEO | 28 requirements | MPS-001 §G3–G4; RTM REQ-SEO-001..028 |
| Reliability | 6 requirements | SAD §36; RTM REQ-OPS |
| Maintainability | 8 requirements | SAD §37–39; ADR §6 |
| Compatibility | 4 dimensions | SAD §3; ADR §3 |
| Monitoring | 6 domains | SAD §29; FS §9, §13 |

**Total Non-Functional Requirements:** 110+

### 2.2 Out of Scope

- Service-Level Agreement (SLA) with the client — this NFR defines technical targets; the SLA is a commercial agreement.
- End-user training materials and Beheergids (covered in Sprint 8 handover).
- Third-party API SLAs (Mollie uptime, Cloudflare uptime, SMTP provider uptime).

---

## 3. Performance Requirements

### 3.1 Core Web Vitals (Hard Gates)

These are mandatory thresholds. Any page failing these at the QA gate (Sprint 7) blocks launch.

| Metric | Target | Measurement Tool | RTM ID |
|---|---|---|---|
| Largest Contentful Paint (LCP) | **< 2.5 seconds** | PSI, Lighthouse, WebPageTest | REQ-PERF-003 |
| Interaction to Next Paint (INP) | **< 200 ms** | PSI, Chrome UX Report | REQ-PERF-004 |
| Cumulative Layout Shift (CLS) | **< 0.1** | PSI, Lighthouse | REQ-PERF-005 |
| Time to First Byte (TTFB) | **< 600 ms** | WebPageTest (Amsterdam, Moto G4, 3G Fast) | REQ-PERF-006 |
| Speed Index | **< 3.4 seconds** | Lighthouse | — |

### 3.2 Lighthouse Targets (Hard Gates)

| Category | Target | Applies To | RTM ID |
|---|---|---|---|
| Performance (Mobile) | **≥ 90** | All page templates | REQ-PERF-001 |
| Performance (Desktop) | **≥ 95** | All page templates | REQ-PERF-002 |
| Accessibility | **100** | All page templates | REQ-ACC-017 |
| Best Practices | **100** | All page templates | — |
| SEO | **100** | All page templates | — |

### 3.3 Page Weight Budgets

| Metric | Mobile Target | Desktop Target | RTM ID |
|---|---|---|---|
| Total Page Weight | **< 1.5 MB** | **< 3.0 MB** | REQ-PERF-007 |
| Image Weight | < 800 KB | < 1.5 MB | — |
| JavaScript Weight | < 200 KB | < 400 KB | — |
| CSS Weight | < 50 KB (critical) + deferred | Same | REQ-PERF-008 |
| Font Weight | < 100 KB total | Same | REQ-PERF-010 |

### 3.4 Caching Strategy

**4-layer cache hierarchy (mandatory):**

| Layer | Technology | Cache Duration | Purge Trigger | RTM ID |
|---|---|---|---|---|
| 1. Browser Cache | HTTP `Cache-Control` headers | 1 year for versioned static assets | File version change (`filemtime()`) | — |
| 2. CDN Cache | Cloudflare full-page | 30 days for static; bypass for dynamic | Cloudflare API on WP Rocket purge | REQ-PERF-012 |
| 3. Page Cache | FlyingPress | Until content/plugin/theme change | Post/page update, plugin/theme update | — |
| 4. Object Cache | Redis | TTL-based (transients), LRU eviction | Explicit invalidation or TTL expiry | — |

**Cloudflare Cache Bypass (Mandatory URLs, Never Cached):**

- `/winkelmand/*` (cart)
- `/afrekenen/*` (checkout)
- `/mijn-account/*` (my account)
- `/wp-admin/*` (admin)
- `/wp-json/wc/*` (WooCommerce REST)
- `/?wc-ajax=*` (WooCommerce AJAX)

**Verification:** `CF-Cache-Status: BYPASS` response header on all bypass URLs. `HIT` or `MISS` on all public pages.

### 3.5 Image Optimization

| Requirement | Specification | RTM ID |
|---|---|---|
| Format | WebP primary; PNG/JPEG fallback via `<picture>` | REQ-PERF-007 |
| Compression | Visually lossless (quality ≥ 85) via ShortPixel/Imagify | — |
| Responsive | `srcset` with 400w, 800w, 1200w variants; `sizes` attribute | — |
| Lazy Loading | `loading="lazy"` on all images below fold | REQ-PERF-011 |
| LCP Image | `fetchpriority="high"`; no lazy loading; preloaded in `<head>` if hero image | — |
| Dimensions | Explicit `width` and `height` attributes on all `<img>` elements | — |
| Alt Text | Descriptive Dutch on all non-decorative images | REQ-ACC-006 |
| Custom Sizes | `hds-card` (400×300), `hds-content` (800×600), `hds-hero` (1600×900) | SAD §19 |
| Conversion | Auto WebP on upload (ShortPixel/Imagify); generate all registered sizes | — |

### 3.6 Critical CSS & JavaScript

| Requirement | Specification | RTM ID |
|---|---|---|
| Critical CSS | Inlined in `<head>`; auto-generated by FlyingPress | REQ-PERF-008 |
| Non-Critical CSS | Deferred loading via `media="print" onload="this.media='all'"` or FlyingPress | — |
| JavaScript Loading | `defer` attribute on all scripts; zero render-blocking JS | REQ-PERF-009 |
| jQuery | Removed unless WooCommerce requires it; jQuery Migrate MUST NOT load | REQ-PERF-013 |
| Inline Scripts | Only via `wp_add_inline_script()` for critical configuration data | — |
| Font Loading | Self-hosted Open Sans (WOFF2, subset Latin + Dutch diacritics); `font-display: swap`; preloaded in `<head>` | REQ-PERF-010 |

### 3.7 Compression & CDN

| Requirement | Specification | RTM ID |
|---|---|---|
| Gzip/Brotli | Enabled at web server or Cloudflare level; all text assets compressed | — |
| CDN | Cloudflare: full-page caching, Polish (image optimization), auto-minify (CSS/JS/HTML) | — |
| SSL Termination | Cloudflare Full (Strict); origin certificate from Let's Encrypt or Cloudflare Origin CA | — |
| Minification | Cloudflare auto-minify for HTML/CSS/JS; esbuild for custom JS/CSS build process | — |

### 3.8 Database Performance

| Requirement | Specification | RTM ID |
|---|---|---|
| Storage Engine | InnoDB for all tables | — |
| Charset | `utf8mb4_unicode_ci` | — |
| Query Caching | Redis object cache for WP_Query, transients, options | — |
| Autoloaded Data | Monitored quarterly; critical options autoloaded; non-critical set to `autoload=no` | — |
| Revisions | Max 10 per post; auto-clean revisions older than 30 days (WP-Optimize) | REQ-PERF-014 |
| Transient Cleanup | Expired transients cleaned by WP core + WP-Optimize | REQ-PERF-014 |

---

## 4. Availability

### 4.1 Target Uptime

| Requirement | Target | Measurement | RTM ID |
|---|---|---|---|
| Uptime | **≥ 99.9%** (≤ 43 minutes downtime/month) | UptimeRobot (free tier, 5-min checks) | REQ-OPS-004 |
| Monitoring Interval | 5 minutes | UptimeRobot | — |
| Alert Recipients | Developer + Client (phone/email) | UptimeRobot notification contacts | — |
| Alert Threshold | Downtime > 1 minute triggers alert | UptimeRobot | — |

### 4.2 Maintenance Windows

| Requirement | Specification |
|---|---|
| Scheduled Maintenance | Monthly window for plugin/theme/core updates. Notified to client 48 hours in advance. |
| Unscheduled Maintenance | Emergency patches (security, critical bug). Client notified immediately. |
| Maintenance Impact | Caching minimizes visible impact. Full maintenance mode only for database migrations or major updates. |
| Staging Testing | All updates tested on staging before production. Smoke test: Home, 1 service page, 1 product page, Contact form, mobile view. |

### 4.3 Backup Strategy

| Requirement | Specification | RTM ID |
|---|---|---|
| Full Backup (Files + Database) | Daily (nightly), automated | REQ-OPS-001 |
| Retention | 30 daily, 4 weekly, 12 monthly | — |
| Storage | Offsite cloud (Google Drive, Dropbox, or S3 via BlogVault/UpdraftPlus) | — |
| Pre-Update Backup | Auto-triggered before every plugin/theme/core update | — |
| WooCommerce Order Export | Monthly CSV export to offsite storage; 7-year retention (Dutch financial data requirement) | REQ-CMP-006 |
| Backup Verification | Monthly test restore to staging environment | REQ-OPS-001 |
| Verification Checklist | All pages load, forms submit, WooCommerce checkout works, admin login works | — |
| Failure Alert | Email to developer if backup fails | — |

### 4.4 Recovery Strategy

| Scenario | Recovery Time Objective (RTO) | Recovery Point Objective (RPO) | RTM ID |
|---|---|---|---|
| Server failure (hosting outage) | < 4 hours | < 24 hours (from last nightly backup) | REQ-OPS-004 |
| Malware / defacement | < 4 hours | < 24 hours | — |
| Accidental content deletion | < 1 hour (restore from revision history) or < 4 hours (restore from backup) | < 24 hours | — |
| DNS / domain issue | < 2 hours | N/A | — |

**Rollback Procedure:**
1. Restore most recent verified backup to staging environment.
2. Verify integrity: all pages, forms, WooCommerce, admin login.
3. Deploy restored staging to production.
4. Clear all caches (FlyingPress, Cloudflare, Redis).
5. Verify site operational.

**Recovery Runbook:** Documented with step-by-step instructions, emergency contacts, DNS provider login location, hosting support phone. Printed copy provided to client.

### 4.5 Disaster Recovery

| Requirement | Specification |
|---|---|
| Runbook | Documented before Sprint 7. Tested during monthly backup verification. |
| Client Copy | Printed copy with hosting support phone, developer emergency contact, step-by-step restore instructions. |
| Old Site Backup | Full backup of old live site verified (test restore) before old site takedown at launch. |

---

## 5. Scalability

### 5.1 Traffic Growth

**Assumption:** The site serves < 100 concurrent users as a local B2B cleaning company website. Managed WordPress hosting with Cloudflare CDN handles this without architectural change.

| Growth Scenario | Architecture Response | RTM ID |
|---|---|---|
| Moderate growth (2–5× traffic) | Vertical scaling: upgrade hosting plan (more PHP workers, more RAM, more SSD) | — |
| Significant growth (10×+, e.g., Google Ads campaign) | Cloudflare absorbs cacheable traffic; Redis reduces database load; vertical hosting upgrade | — |
| Massive growth (requires horizontal scaling) | Cloudflare load balancing across multiple WP instances; shared Redis; externalized media (S3/Cloudflare R2) | — |

### 5.2 Content Growth

| Growth Dimension | Architecture Support |
|---|---|
| Additional service pages (new services) | Create new Page with "Service" template; add to navigation menu; add cross-links. Zero code changes. |
| Additional blog posts (ongoing content marketing) | Standard WordPress Posts. Category base `kennisbank`. Pagination handles unlimited posts. |
| Additional PDFs / downloads | Upload to Media Library; Display via Download Card List block pattern. |
| Additional testimonials | Add to `hds_testimonial` CPT via admin. Automatically queried by `hds/testimonial` block. |
| Additional vacancies | Add to `hds_vacancy` CPT via admin. Automatically queried by `hds/job-listing` block. |

### 5.3 Future Services

**Supported without architecture change:** New service pages are standard WordPress Pages with the "Service" template. The Service Card Grid on the homepage queries all pages with the Service template automatically. No code changes required to add a new service.

### 5.4 Future Locations

**Supported with new pages:** Location-specific landing pages (e.g., `/schoonmaakbedrijf-bergen-op-zoom/`) can be added as Pages. Each location page would include location-specific content, LocalBusiness schema, and internal linking.

**Multi-location (franchise model, 18+ months):** Would require multi-location CPT or WordPress multisite. The block-based content is portable. Not implemented in current scope.

### 5.5 Future WooCommerce Products

**Supported without architecture change:** Unlimited products via WooCommerce. Product categories, attributes, and variations are standard WooCommerce features. New Airfixr models or entirely new product lines require no code changes.

### 5.6 Future Languages

**Supported with plugin + content translation:** All 100+ user-facing strings are internationalized with `__()` / `_e()` and textdomain `hds`. Adding a second language requires:
1. Install WPML or Polylang.
2. Configure `hreflang` tags.
3. Translate all 32+ page contents.

**Assumption:** No international expansion is planned in the next 18 months. The internationalization is forward-compatibility, not an immediate feature.

---

## 6. Security

### 6.1 Authentication

| Requirement | Specification | RTM ID |
|---|---|---|
| Login URL | Custom URL (Wordfence); NOT `/wp-admin/` or `/wp-login.php/` | REQ-SEC-008 |
| 2FA | Wordfence 2FA on ALL Administrator, Editor, and Shop Manager accounts. No exceptions. | REQ-SEC-010 |
| 2FA Method | TOTP (Time-based One-Time Password) via authenticator app (Google Authenticator, Authy) | — |
| Login Attempts | Maximum 3 failed attempts → IP lockout (Wordfence brute force) | REQ-SEC-009 |
| Lockout Duration | Configurable (default: 4 hours) | — |
| Application Passwords | Disabled | — |
| XML-RPC | Disabled at web server level (403 Forbidden) | REQ-SEC-002 |

### 6.2 Password Policy

| Requirement | Specification | RTM ID |
|---|---|---|
| Minimum Length | 12 characters (Wordfence enforced) | — |
| Complexity | Enforced by Wordfence: uppercase, lowercase, digit, special character | — |
| Password Reset | Email-based. Maximum 1 reset request per hour per user. | — |
| Session Timeout | 48 hours (WordPress default, extended via "Remember Me" to 14 days) | — |
| Force Logout | On password change, all existing sessions invalidated | — |
| Password Rotation | Quarterly (operational policy, not technically enforced) | — |

### 6.3 Authorization & User Roles

| Role | Key Capabilities | 2FA Required |
|---|---|---|
| Administrator | Full access: manage content, plugins, themes, users, settings, WooCommerce | **Yes** |
| Editor | CRUD pages/posts/CPTs; view forms entries; view analytics | **Yes** |
| Shop Manager | Manage WooCommerce products, orders, coupons | **Yes** |
| SEO Manager | Access SEO plugin settings; view analytics | Recommended |
| Subscriber | Read-only; own WooCommerce account | Optional |

**Principle of Least Privilege:** Each role receives only the capabilities required for their function. No role inherits capabilities it does not need.

### 6.4 Firewall (6-Layer Defense)

| Layer | Implementation | RTM ID |
|---|---|---|
| 1. Transport | HTTPS only; HSTS `max-age=31536000; includeSubDomains; preload`; TLS 1.3 | REQ-SEC-001 |
| 2. CDN/Edge | Cloudflare WAF: block `/xmlrpc.php`, rate-limit login, WordPress managed ruleset (Pro plan); DDoS protection; SSL Full (Strict) | REQ-SEC-016 |
| 3. Server | XML-RPC disabled (403); directory listing disabled; file permissions (dirs 755, files 644, wp-config.php 400); `hds_` table prefix; `DISALLOW_FILE_EDIT=true`; SFTP only | REQ-SEC-002, REQ-SEC-011, REQ-SEC-012 |
| 4. Authentication | Custom login URL; 2FA on all elevated accounts; brute force protection (max 3 attempts); user enumeration prevention (block `?author=N`, `/wp-json/wp/v2/users`) | REQ-SEC-007..010 |
| 5. Application WAF | Wordfence Premium: WAF, daily malware scan, file integrity monitoring | REQ-SEC-007 |
| 6. Application Logic | Input sanitization; output escaping; nonce verification on all custom forms; capability checks; prepared SQL statements | REQ-SEC-014..015 |

### 6.5 Rate Limiting & Brute Force

| Target | Limit | Implementation | RTM ID |
|---|---|---|---|
| Login attempts | 3 failures → IP lockout | Wordfence brute force | REQ-SEC-009 |
| Custom login URL | Rate-limited | Cloudflare WAF + Wordfence | REQ-SEC-016 |
| `/xmlrpc.php` | Blocked (403) | Nginx deny rule + Cloudflare WAF | REQ-SEC-002 |
| REST API users endpoint | Blocked (403) | `rest_endpoints` filter | — |
| Author archives (`?author=N`) | Redirect to homepage (301) | `template_redirect` hook | — |

### 6.6 Security Headers

| Header | Value | Implementation | RTM ID |
|---|---|---|---|
| `Strict-Transport-Security` | `max-age=31536000; includeSubDomains; preload` | Cloudflare / web server | REQ-SEC-001 |
| `X-Frame-Options` | `SAMEORIGIN` | Nginx / Cloudflare | — |
| `X-Content-Type-Options` | `nosniff` | Nginx / Cloudflare | — |
| `Referrer-Policy` | `strict-origin-when-cross-origin` | Nginx / Cloudflare | — |
| `Permissions-Policy` | Minimal: `geolocation=(), microphone=(), camera=()` | Nginx / Cloudflare | — |
| `Content-Security-Policy` | **Not enforced at launch.** Evaluate post-launch. Complex to configure with third-party scripts (GA4, GTM, reCAPTCHA, Mollie). | — | — |

### 6.7 Input Validation & Output Escaping

| Requirement | Specification | RTM ID |
|---|---|---|
| Input Sanitization | `sanitize_text_field()`, `sanitize_email()`, `sanitize_textarea_field()`, `intval()`, `absint()` | REQ-SEC-014 |
| Output Escaping | `esc_html()` (HTML context), `esc_attr()` (attribute context), `esc_url()` (URL context), `wp_kses()` (allowed HTML), `esc_textarea()` (textarea context) | REQ-SEC-015 |
| SQL | `$wpdb->prepare()` for all custom queries. No raw string interpolation. | REQ-SEC-014 |
| Nonces | `wp_nonce_field()` + `check_admin_referer()` or `wp_verify_nonce()` on all custom forms | REQ-SEC-004 |
| Capability Checks | `current_user_can()` before all privileged operations | REQ-SEC-014 |

### 6.8 XSS Prevention

| Measure | Specification |
|---|---|
| Escaping | All dynamic output escaped based on context (see §6.7). Default: `esc_html()`. |
| Sanitization | All user inputs sanitized before storage. |
| No Inline Scripts | Only via `wp_add_inline_script()` with proper escaping. |
| No `eval()` | Banned. No `eval()`, no `base64_decode()`, no `extract()` in theme code. |

### 6.9 CSRF Protection

| Measure | Specification | RTM ID |
|---|---|---|
| All Custom Forms | Protected by WordPress nonces (`wp_nonce_field()` for form, `wp_verify_nonce()` for processing) | REQ-SEC-004 |
| Gravity Forms | Built-in CSRF protection. No additional configuration needed. | — |
| WooCommerce | Built-in CSRF protection. No additional configuration needed. | — |

### 6.10 File Upload Restrictions

| Requirement | Specification | RTM ID |
|---|---|---|
| Max File Size | 5 MB per upload | REQ-FR-019 |
| Allowed Types (via Gravity Forms) | PDF, JPG, JPEG, PNG, DOC, DOCX | REQ-FR-019 |
| Server-Side MIME Validation | PHP `finfo` (Fileinfo) to detect true MIME type. NOT client-side extension check. | REQ-FR-019 |
| File Rename | Uploaded files renamed (random string or sanitized original). Original filename NOT preserved. | REQ-FR-019 |
| Virus Scanning | **Not implemented at launch.** **Assumption:** Low-risk profile. Evaluate ClamAV integration if threat model changes. | — |
| PHP Execution Prevention | `.htaccess` / Nginx rule: `deny all` for PHP files in `/wp-content/uploads/`. Default WordPress protection active. | — |

### 6.11 Backup Encryption

| Requirement | Specification |
|---|---|
| In Transit | Backups transferred via HTTPS/TLS to offsite storage. |
| At Rest | **Assumption:** Cloud storage provider (Google Drive, Dropbox, S3) provides at-rest encryption. No additional client-side encryption layer. |
| Backup File Access | Offsite storage access restricted to developer and client. Credentials stored securely. |

---

## 7. Privacy & GDPR (AVG)

### 7.1 Cookie Consent

| Requirement | Specification | RTM ID |
|---|---|---|
| Consent Banner | Complianz Premium (Dutch market). Three options: "Accepteren", "Weigeren", "Instellingen aanpassen". | REQ-CMP-002 |
| Prior Consent | No non-functional cookies loaded before user action. Verified via DevTools Network tab — zero GA/Facebook requests before consent. | REQ-CMP-003 |
| Per-Category Consent | Functional (always on), Statistics (off by default), Marketing (off by default). User can toggle each. | — |
| Consent Logging | Timestamp, anonymized IP, consent string logged by Complianz. | REQ-CMP-004 |
| Cookie Settings Post-Consent | Floating button or footer link to re-open consent preferences. | — |
| Cookiebeleid Page | Auto-generated by Complianz at `/cookiebeleid/`. Linked from banner, footer, and privacyverklaring. | REQ-CMP-002 |
| GTM Consent Mode v2 | Complianz sends consent signals to GTM. Marketing tags deferred until consent. Analytics tags fire with restricted data when consent is denied. | REQ-CMP-010 |
| reCAPTCHA Badge | Positioned so it is not obscured by cookie banner. | — |
| Google Maps | Embedded only after cookie consent (wrap in Complianz consent placeholder). | — |

### 7.2 Privacy Policy

| Requirement | Specification | RTM ID |
|---|---|---|
| Page | `/privacyverklaring/` — published, accessible, linked from footer on every page | REQ-CMP-001 |
| Content | Data controller identity, processing purposes, legal basis (consent, legitimate interest, contract), retention periods, data subject rights (access, rectification, erasure, restriction, portability, objection), right to complain to Autoriteit Persoonsgegevens, third-party sharing, international transfers (if any). | — |
| Legal Review | **MUST be reviewed by qualified Dutch privacy lawyer before launch** (MI-17). | REQ-CMP-013 |
| Link Locations | Footer (every page), all form consent checkboxes, cookie consent banner. | REQ-CMP-001 |

### 7.3 Data Retention

| Data Type | Retention Period | Deletion Mechanism | RTM ID |
|---|---|---|---|
| Contact form entries (GF-1) | 12 months | Gravity Forms auto-delete | REQ-CMP-006 |
| Quote request entries (GF-2) | 12 months | Gravity Forms auto-delete | REQ-CMP-006 |
| Vacancy applications (GF-3) | 6 months | Gravity Forms auto-delete | — |
| WooCommerce orders | 7 years (Dutch financial data) | Manual or automated export + deletion after 7 years | REQ-CMP-006 |
| WooCommerce customer accounts | Until account deletion requested or 7 years after last order | Built-in WooCommerce + WP user deletion | — |
| WordPress revisions | 10 revisions max; auto-clean > 30 days | WP-Optimize scheduled | — |
| Analytics data (GA4) | 14 months (GA4 default) | GA4 auto-deletion | — |
| Server logs | 30 days | Log rotation | — |
| Backup files | 30 daily + 4 weekly + 12 monthly; oldest rotate out | Automated rotation | — |
| Mail server logs (Post SMTP) | 90 days | Plugin configuration | — |

### 7.4 Right to Erasure (AVG Art. 17)

| Data Subject Request | Procedure |
|---|---|
| Delete WordPress user account | Admin deletes user via WP Admin → Users. Confirmation of deletion sent to data subject. |
| Delete form entries | Admin exports then deletes entries from Gravity Forms (search by email). Confirmation sent. |
| Delete WooCommerce customer data | Admin deletes customer via WooCommerce → Customers. Orders anonymized if > 7 years; retained if < 7 years (legal obligation). |
| Delete from backups | Not practically feasible (backups are immutable). Data will age out of backup retention (max 12 months). Documented in privacyverklaring. |
| Response Time | Within 30 days (AVG requirement). Developer processes within 5 business days. |

### 7.5 Right to Data Portability (AVG Art. 20)

| Data | Export Format |
|---|---|
| WordPress user profile | WP Admin → Export Personal Data (built-in) |
| WooCommerce order history | CSV export or WP Admin → Export Personal Data |
| Form entries | Gravity Forms → Export Entries (filtered by email) → CSV |

### 7.6 Third-Party Integrations — Privacy Assessment

| Integration | Purpose | Data Shared | Safeguards | RTM ID |
|---|---|---|---|---|
| Google Analytics 4 | Traffic + conversion analytics | Anonymized IP, page views, events | IP anonymization enabled (GA4 default); data retention 14 months; DPA with Google | REQ-CMP-010 |
| Google Tag Manager | Script management | None directly (GTM is a container) | No data stored by GTM | — |
| Google reCAPTCHA v3 | Spam protection | User behavior score | Google's reCAPTCHA privacy policy; data processed in EU/US | — |
| Mollie | Payment processing | Order amount, customer name, email | Mollie is a Dutch payment processor; PCI-DSS Level 1; data processed within EU | — |
| Cloudflare | CDN, WAF, DDoS protection | IP address (for security), cached pages | DPA available (Cloudflare); EU data centers | — |
| Post SMTP / SendGrid | Email delivery | Email content, sender, recipients | DPA with SendGrid/Mailgun/SES; emails in transit encrypted via TLS | — |
| Complianz | Cookie consent management | Consent preferences, anonymized IP | Data stored in local WordPress database; no external data sharing | — |
| Managed WordPress Host | Site hosting | All site data (database, files, emails) | DPA signed with hosting provider; EU data centers | REQ-CMP-007 |
| ShortPixel/Imagify | Image compression | Image files for optimization | Images processed and returned; not stored by service | — |

**Data Processing Agreements (DPAs):** Required with hosting provider, Google (analytics), and any third-party processor that handles personal data. Client is responsible for signing DPAs. Developer verifies DPA status before launch.

### 7.7 Breach Notification Procedure

| Requirement | Specification | RTM ID |
|---|---|---|
| Detection | Wordfence alert, client report, uptime alert, or admin observation | — |
| Containment | Take site offline or place in maintenance mode; change all passwords; block attacker IPs | — |
| Investigation | Review server logs, Wordfence logs, file integrity monitoring; determine scope and data affected | — |
| Notification to Autoriteit Persoonsgegevens | Within 72 hours of detection if personal data is breached | REQ-CMP-008 |
| Notification to Affected Users | Without undue delay if high risk to their rights and freedoms | REQ-CMP-008 |
| Remediation | Restore clean backup; patch vulnerability; harden against recurrence | — |
| Post-Mortem | Document root cause, timeline, impact, and preventive measures | — |

---

## 8. Accessibility (WCAG 2.2 AA)

**Compliance Target:** WCAG 2.2 Level AA — ALL success criteria. Success Criterion 2.5.8 (Target Size, AAA) adopted as AA requirement (≥ 44×44px touch targets).

### 8.1 Perceivable

| # | Requirement | WCAG SC | Implementation | RTM ID |
|---|---|---|---|---|
| A01 | Text color contrast ≥ 4.5:1 (normal), ≥ 3:1 (large text) | 1.4.3 | Defined in `theme.json` color palette. Tested via axe DevTools / WebAIM. | REQ-ACC-001 |
| A02 | UI component contrast ≥ 3:1 | 1.4.11 | Button borders, form input borders, focus indicators. | REQ-ACC-001 |
| A03 | Alt text on all non-decorative images | 1.1.1 | Descriptive Dutch alt text in Media Library. Decorative: `alt=""`. | REQ-ACC-006 |
| A04 | Semantic heading hierarchy (H1 → H2 → H3, no skipped levels) | 1.3.1 | Enforced by page templates. Content editors use proper heading levels in Block Editor. | REQ-ACC-004 |
| A05 | ARIA landmarks: `banner`, `navigation`, `main`, `contentinfo`, `search` | 1.3.1 | HTML5 elements preferred over ARIA roles. `<header role="banner">`, `<nav>`, `<main>`, `<footer role="contentinfo">`. | REQ-ACC-005 |
| A06 | 200% zoom: no content loss, no horizontal scroll | 1.4.4 | Responsive design with `max-width` containers and flexible grids. | REQ-ACC-009 |
| A07 | No auto-playing media; respect `prefers-reduced-motion` | 2.3.1, 2.3.2 | No auto-play video/audio. CSS animations wrapped in `@media (prefers-reduced-motion: no-preference)`. | REQ-ACC-010 |

### 8.2 Operable

| # | Requirement | WCAG SC | Implementation | RTM ID |
|---|---|---|---|---|
| A08 | All interactive elements keyboard-focusable and operable | 2.1.1, 2.1.2 | Semantic HTML buttons, links, form elements. Custom widgets (accordion, dropdown) have keyboard handlers. | REQ-ACC-002 |
| A09 | Skip to content link — first focusable element; visible on focus | 2.4.1 | `<a class="skip-link screen-reader-text" href="#main">`. Visible on `:focus`. | REQ-ACC-003 |
| A10 | Visible focus indicator on all interactive elements | 2.4.7 | CSS `:focus-visible` outline (2px solid primary color). Never use `outline: none` without replacement. | — |
| A11 | Descriptive link text (no "klik hier") | 2.4.4 | "Lees meer over glasbewassing" not "Klik hier". Screen-reader text for context if needed. | REQ-ACC-008 |
| A12 | Touch targets ≥ 44×44px | 2.5.8 (AAA) | All navigation links, buttons, form inputs, and icons meet this minimum. | REQ-ACC-011 |
| A13 | Consistent navigation order across all pages | 3.2.3 | Header → Content → Footer order. Navigation menu items in fixed order. | REQ-ACC-015 |
| A14 | Consistent component identification | 3.2.4 | Same-function icons, buttons, and links use consistent labels across all pages. | REQ-ACC-016 |

### 8.3 Understandable

| # | Requirement | WCAG SC | Implementation | RTM ID |
|---|---|---|---|---|
| A15 | `lang="nl-NL"` on `<html>` element | 3.1.1 | Set in `header.php` via `language_attributes()` | REQ-ACC-012 |
| A16 | English blocks (if any) have `lang="en"` | 3.1.2 | Applied to individual elements/blocks containing English text | — |
| A17 | Unique, descriptive `<title>` on every page | 2.4.2 | Managed via Rank Math Pro. Pattern: `[Page Title] — HDS Onderhoudsdiensten` | REQ-ACC-013 |

### 8.4 Robust

| # | Requirement | WCAG SC | Implementation | RTM ID |
|---|---|---|---|---|
| A18 | Form labels, required markers, error association | 1.3.1, 3.3.1, 3.3.2 | All fields have `<label>`. Required: `aria-required="true"`. Errors: `aria-describedby` linking to field. | REQ-ACC-007 |
| A19 | Dynamic content updates announced via `aria-live` | 4.1.3 | WooCommerce cart updates, form submission feedback, search results. | REQ-ACC-014 |
| A20 | Screen reader compatibility | — | Tested with NVDA (Windows) and VoiceOver (Mac/iOS). All forms, navigation, and content announced correctly. | REQ-ACC-018 |

### 8.5 Testing Protocol

| Test | Tool / Method | Threshold | RTM ID |
|---|---|---|---|
| Automated | axe DevTools | Zero critical issues. Zero serious issues. | REQ-ACC-001..020 |
| Automated | WAVE | Zero errors. | — |
| Automated | Lighthouse Accessibility | Score = 100 on every page template. | REQ-ACC-017 |
| Manual Keyboard | Tab through Home, 2 service pages, Contact form, 1 product page | All elements reachable and operable. Focus indicator visible at all times. | REQ-ACC-002 |
| Manual Screen Reader | NVDA (Windows) or VoiceOver (Mac) | Content announced correctly. Form labels, errors, and confirmations announced. | REQ-ACC-018 |
| Color Contrast | WebAIM Contrast Checker or axe DevTools | All color combinations pass AA thresholds. | REQ-ACC-001 |
| 200% Zoom | Browser zoom to 200% | No content loss. No horizontal scroll. | REQ-ACC-009 |
| Real Mobile | VoiceOver (iOS) or TalkBack (Android) | Usable on real device. Minimum 3 pages tested. | REQ-ACC-018 |
| WooCommerce Checkout | axe DevTools + keyboard + screen reader | Specific test for WooCommerce checkout accessibility. | REQ-ACC-019 |
| Dropdown Keyboard | Manual keyboard test | Enter/Space to open; Tab through items; Escape to close; focus returns to trigger. | REQ-ACC-020 |

---

## 9. SEO Quality Requirements

### 9.1 Metadata

| Requirement | Specification | RTM ID |
|---|---|---|
| Title Tags | Unique per page. 50–60 characters. Pattern: `[Page Title] — HDS Onderhoudsdiensten`. | REQ-SEO-001..021 |
| Meta Descriptions | Unique per page. 150–160 characters. Include: primary keyword, location, value proposition, CTA. | REQ-SEO-001..021 |
| Zero Empty | Screaming Frog scan: zero pages with empty title or meta description. | REQ-SEO-001 |
| Zero Duplicate | Screaming Frog scan: zero duplicate titles or descriptions across pages. | REQ-SEO-001 |
| Implementation | Rank Math Pro per-page meta fields. | — |

### 9.2 Canonical URLs

| Requirement | Specification | RTM ID |
|---|---|---|
| Self-Referencing | All pages have self-referencing canonical URL. No cross-domain canonicals. | REQ-SEO-024 |
| Trailing Slash | Canonical always includes trailing slash. Non-slash variant → 301 before canonical applies. | — |
| Pagination | Paginated archives: canonical points to page 1 of the series. | — |

### 9.3 Structured Data (Schema)

All 9 schema types must be present and valid. Verification via Google Rich Results Test for every type.

| Schema Type | Pages | Implementation | RTM ID |
|---|---|---|---|
| `WebSite` with `SearchAction` | All | Rank Math Pro auto | — |
| `WebPage` | All | Rank Math Pro auto | — |
| `BreadcrumbList` | All inner pages | Rank Math Pro + theme | — |
| `LocalBusiness` (HomeAndConstructionBusiness) | Home, Contact, Over HDS | Custom JSON-LD (`inc/schema.php`) | REQ-SEO-025 |
| `Service` | P02–P08 (each service page) | Custom JSON-LD per page | REQ-SEO-026 |
| `FAQPage` | P18 (Veelgestelde Vragen) | Rank Math Pro auto from FAQ blocks | REQ-SEO-027 |
| `Product` | P25 ×14 (WooCommerce products) | WooCommerce auto | — |
| `JobPosting` | Per vacancy on P14 | Custom JSON-LD per vacancy | — |
| `Organization` with `sameAs` | All | Custom JSON-LD (`inc/schema.php`) | — |

**Validation:** All schema types must pass Google Rich Results Test before launch. Zero errors, zero warnings.

### 9.4 XML Sitemap

| Requirement | Specification | RTM ID |
|---|---|---|
| Index URL | `/sitemap_index.xml` — returns HTTP 200 with valid XML | REQ-SEO-022 |
| Sub-Sitemaps | `page-sitemap.xml`, `post-sitemap.xml`, `product-sitemap.xml` | REQ-SEO-022 |
| Excluded | Attachment pages, author archives, noindex pages (Bedankt, legal if marked noindex), cart, checkout, account | REQ-SEO-023 |
| Attachment Pages | Zero attachment pages in sitemap (was ~50 on current site) | REQ-SEO-023 |
| Submission | Submitted to Google Search Console and Bing Webmaster Tools at launch | — |

### 9.5 robots.txt

| Requirement | Specification | RTM ID |
|---|---|---|
| URL | `/robots.txt` — returns HTTP 200 | REQ-SEO-024 |
| Generation | Auto-generated by Rank Math Pro | — |
| Disallow Rules | `/wp-admin/`, `/wp-includes/`, `/wp-content/plugins/`, query parameters (non-WC), blocked API endpoints | — |
| Crawl-Delay | Not set (Google ignores it; unnecessary with proper caching) | — |

### 9.6 OpenGraph & Twitter Cards

| Requirement | Specification | RTM ID |
|---|---|---|
| Generation | Auto-generated by Rank Math Pro for all pages | — |
| Tags | `og:title`, `og:description`, `og:image` (1200×630px social share image), `og:url`, `og:type`, `og:locale` (nl_NL) | — |
| Twitter | `twitter:card` (summary_large_image), `twitter:title`, `twitter:description`, `twitter:image` | — |
| Fallback | Site-wide social share image if page has no featured image | — |
| Validation | Facebook Sharing Debugger and Twitter Card Validator — zero errors | — |

### 9.7 Redirects

| Requirement | Specification | RTM ID |
|---|---|---|
| Type | 301 (permanent) only. Never 302 or 307. | REQ-SEO-028 |
| Chains | Zero redirect chains (A → B → C forbidden; A → C required) | REQ-SEO-028 |
| Implementation | Rank Math Pro redirect manager | — |
| Rules (7 × 301) | `/glasbewassing` → `/glasbewassing/`, `/vve` → `/vve-service/`, `/vve/` → `/vve-service/`, `/?page_id=318` → `/reguliere-schoonmaak/`, `http://` → `https://`, `www.` → non-www | REQ-SEO-028 |
| Rules (2 × 410) | `/2015/06/29/hallo-wereld/`, `/2015/08/25/kwaliteit-veiligheid/` | — |
| Testing | Every rule tested manually via `httpstatus.io` before launch | REQ-SEO-028 |

### 9.8 Internal Linking

| Requirement | Specification | RTM ID |
|---|---|---|
| Service Cross-Links | Each service page links to 2–3 related services (cross-link matrix defined in FS §4.2) | REQ-SEO-010 |
| Navigation Coverage | All pages linked from main navigation and/or footer | — |
| Zero Orphans | Every page reachable from at least 2 locations (internal links + nav + footer) | REQ-SEO-010 |
| Homepage Coverage | Homepage service card grid links to all 7 services | REQ-FR-013 |

### 9.9 URL Consistency

| Rule | Specification |
|---|---|
| Protocol | HTTPS only; HTTP → 301 |
| www | Non-www canonical; www → 301 |
| Trailing Slash | Consistently WITH trailing slash |
| Language | Dutch, lowercase, hyphens; no diacritics in slugs |
| Depth | Max 1 from root. Exceptions: `/product/{slug}/`, `/kennisbank/{slug}/` |
| Extensions | No `.html`, `.php`, `.asp` |
| Blog URLs | `/kennisbank/{slug}/` (no date prefix — permanent URLs) |

---

## 10. Reliability

### 10.1 Error Handling

| Scenario | Behavior | RTM ID |
|---|---|---|
| 404 (Page Not Found) | Custom `404.php` returns true HTTP 404. Search bar + key links + phone + email. | REQ-FR-016 |
| 404 Monitoring | Rank Math 404 monitor logs all 404 hits. Reviewed weekly. High-traffic 404s → 301. | — |
| 500 (Application Error) | `WP_DEBUG=false`, `WP_DEBUG_DISPLAY=false`, `WP_DEBUG_LOG=true` on production. User sees generic error or cached page. | REQ-TR-033, REQ-TR-035 |
| 500 (Server Down) | Cloudflare serves cached version ("Always Online") if enabled. Server-level `50x.html` fallback. | — |
| Form Submission Error (SMTP down) | Entry stored in Gravity Forms database. User sees success page (redirect to `/bedankt/`). Admin notified. | — |
| Form Validation Error | Inline Dutch error messages. `aria-describedby` association. First error receives focus. | REQ-ACC-007 |
| File Upload Error (size) | "Het bestand is te groot. Maximale grootte: 5 MB." | REQ-FR-019 |
| File Upload Error (type) | "Dit bestandstype is niet toegestaan. Toegestane types: PDF, JPG, PNG, DOCX." | REQ-FR-019 |
| Spam Detection | Honeypot + reCAPTCHA v3. Silent failure for spam (no user-visible message). | REQ-SEC-003 |
| reCAPTCHA Blocks Legitimate User | Honeypot catches most spam. Phone number visible as fallback on all form pages. | — |
| Out of Stock (WooCommerce) | "Niet op voorraad" with disabled add-to-cart button. | — |
| Empty Query (Search) | Redirect back or show search form with message "Voer een zoekterm in." | — |
| No Results (Search) | "Geen resultaten gevonden. Probeer een andere zoekterm." + search form. | REQ-FR-018 |

### 10.2 Graceful Degradation

| Scenario | Degradation Behavior |
|---|---|
| JavaScript Disabled | Navigation menu fully visible (no hamburger). Forms submit via traditional POST (no AJAX). CTA banners and hero sections visible as static content. |
| CSS Disabled (extreme) | Content readable in linear order. Semantic HTML ensures structure is preserved. Skip link, headings, lists, links all functional. |
| Images Blocked | `alt` text displayed. Content remains understandable. |
| Cookies Blocked | Functional cookies required for WooCommerce cart and WordPress login. Cookie consent banner reappears on each visit (no consent cookie set). |
| CDN Down | Origin server serves content directly. Cloudflare provides "Always Online" cached version if configured. |

### 10.3 Logging

| Log | Retention | Access | RTM ID |
|---|---|---|---|
| PHP Error Log (`debug.log`) | 30 days | Developer via SFTP or WP Admin | REQ-TR-035 |
| WordPress Debug Log | 30 days | Developer | — |
| Wordfence Security Log | 90 days | Wordfence dashboard | — |
| Gravity Forms Entries | 12 months (contact/quote); 6 months (vacancy) | Gravity Forms admin | REQ-CMP-006 |
| WooCommerce Logs | 7 years | WooCommerce → Status → Logs | REQ-CMP-006 |
| Post SMTP Email Log | 90 days | Post SMTP dashboard | — |
| Backup Log | 12 months | Backup plugin dashboard | — |
| Rank Math 404 Log | Ongoing | Rank Math dashboard | — |

### 10.4 Monitoring

| Monitor | Tool | Check Interval | Alert Threshold | RTM ID |
|---|---|---|---|---|
| Uptime | UptimeRobot | 5 minutes | Downtime > 1 minute | REQ-OPS-004 |
| SSL Expiry | UptimeRobot + Cloudflare | Daily | < 30 days to expiry | — |
| Backup Status | Backup plugin dashboard | Weekly manual check | Backup failure | — |
| Disk Usage | Hosting dashboard | Weekly manual check | > 80% used | — |
| Malware | Wordfence (daily auto scan) | Daily | Malware detected | — |
| Performance | Weekly PSI API check | Weekly | PSI mobile < 90 | — |

### 10.5 Alerts

| Alert | Recipient | Channel | RTM ID |
|---|---|---|---|
| Downtime | Developer + Client | Email (UptimeRobot) | REQ-OPS-004 |
| SSL Expiry | Developer | Email (UptimeRobot) | — |
| Backup Failure | Developer | Email (backup plugin) | — |
| Malware Detected | Developer | Email (Wordfence) | — |
| Disk > 80% | Developer | Email (hosting) | — |
| PSI Mobile < 90 | Developer | Manual weekly check | — |

---

## 11. Maintainability

### 11.1 Coding Standards

| Language | Standard | Enforcement | RTM ID |
|---|---|---|---|
| PHP | WordPress Coding Standards (Core + Docs + Security + PHP + DB + WP) | PHP_CodeSniffer + `phpcs.xml` | REQ-SEC-014 |
| CSS | BEM-like naming (`.hds-component__element--modifier`); mobile-first; no `!important` except utilities | Stylelint + `.stylelintrc.json` | — |
| JavaScript | Vanilla ES6+; `defer` loading; no jQuery unless WC requires it; no `console.log()` in production | ESLint + `.eslintrc.js` | — |
| Editor | UTF-8; LF line endings; tabs for PHP/JS/CSS; final newline | `.editorconfig` | — |

### 11.2 Naming Conventions

| Category | Convention | Example |
|---|---|---|
| PHP Functions | `hds_` prefix; snake_case | `hds_get_phone()`, `hds_render_service_card()` |
| CSS Classes | `.hds-component__element--modifier` | `.hds-card__title`, `.hds-card--featured` |
| Custom Fields | `hds_` prefix; lowercase underscores | `hds_subtitle`, `hds_hero_image` |
| CPT Keys | `hds_` prefix; singular | `hds_testimonial`, `hds_vacancy` |
| Block Names | `hds/block-name` | `hds/service-card`, `hds/testimonial` |
| Block Patterns | `hds/pattern-name` | `hds/hero-section`, `hds/cta-banner` |
| Image Filenames | lowercase-hyphens-dutch-keywords | `glasbewassing-kantoor-bergen-op-zoom.webp` |
| URL Slugs | lowercase-hyphens-dutch | `/reguliere-schoonmaak/` |

### 11.3 Folder & File Conventions

| Convention | Detail |
|---|---|
| Theme Root | `wp-content/themes/hds/` |
| Inc Files | `inc/` — one concern per file. Required by `functions.php`. |
| Templates | `page-templates/page-{name}.php` |
| Template Parts | `parts/{name}.php` |
| Assets | `assets/css/`, `assets/js/`, `assets/images/`, `assets/fonts/` |
| Block JS | `assets/js/blocks/{block-name}.js` |
| Languages | `languages/hds.pot` (POT file for translations) |

### 11.4 Media Conventions

| Convention | Detail | RTM ID |
|---|---|---|
| Organization | WordPress default year/month folder structure | — |
| Descriptive Filenames | Lowercase, hyphens, Dutch keywords | — |
| Alt Text | Required at upload for all non-decorative images | REQ-ACC-006 |
| Formats | WebP primary; PNG/JPEG accepted; auto-conversion on upload | REQ-PERF-007 |
| Logo | SVG vector preferred; PNG fallback | — |

### 11.5 Plugin Policy

| Policy | Detail |
|---|---|
| Source | Official WordPress.org repository or trusted premium vendors ONLY. No nulled/cracked plugins. |
| Count | Targeted 13 plugins in production. Each must justify its inclusion. |
| Updates | Minor/patch: auto-update enabled. Major: tested on staging before production. |
| Deactivation | Plugins not in use removed. No inactive plugins left installed. |
| Evaluation | New plugins evaluated against: performance impact, security record, update frequency, WP version compatibility, conflict potential. |

### 11.6 Theme Customization Policy

| Policy | Detail |
|---|---|
| Theme Edits | No direct edits to theme files via WordPress admin (`DISALLOW_FILE_EDIT=true`). All changes via Git + deployment pipeline. |
| Custom CSS | Minimal custom CSS via Customizer "Additional CSS" for minor tweaks only. Core styles in theme `assets/css/main.css`. |
| Child Theme | **Not used.** Customizations are in the parent theme itself (no other theme is parent). The theme `hds` is the sole theme. |

### 11.7 Documentation

| Document | Audience | Location |
|---|---|---|
| Master Project Specification (MPS-001) | Developer + Client + Architect | `docs/MASTER_PROJECT_SPECIFICATION.md` |
| Solution Architecture Document (SAD-001) | Developer + Architect | `docs/architecture/SOLUTION_ARCHITECTURE.md` |
| Architecture Decision Record (ADR-001) | Developer + Architect | `docs/architecture/ADR.md` |
| Functional Specification (FS-001) | Developer + QA + Client | `docs/specifications/functional-specification.md` |
| Non-Functional Requirements (NFR-001) | Developer + QA | `docs/specifications/non-functional-requirements.md` |
| Requirements Traceability Matrix (RTM-001) | Project Manager + QA | `docs/REQUIREMENTS_TRACEABILITY_MATRIX.md` |
| Development Backlog (BKLG-001) | Developer + PM | `docs/DEVELOPMENT_BACKLOG.md` |
| Beheergids (Website Management Guide) | Client | Delivered in Sprint 8 (Dutch, written guide) |
| Recovery Runbook | Client + Developer | Printed copy delivered in Sprint 7 |

---

## 12. Compatibility

### 12.1 Browser Support

| Browser | Minimum Version | Testing |
|---|---|---|
| Google Chrome | Latest 2 versions | Full QA on all page templates |
| Mozilla Firefox | Latest 2 versions | Full QA on all page templates |
| Apple Safari | Latest 2 versions (macOS + iOS) | Full QA on all page templates |
| Microsoft Edge | Latest 2 versions | Full QA on all page templates |

**Testing Scope:** All 13 page templates tested on each browser. Consistent rendering. All functionality works (forms, search, navigation, WooCommerce). Mobile browsers tested on real devices (iPhone 14+, Android Chrome).

### 12.2 Device Support

| Device | Viewport | Testing |
|---|---|---|
| Mobile (Small) | 375px (iPhone SE) | Responsive layout; no horizontal scroll; touch targets ≥ 44px; forms usable |
| Mobile (Large) | 414px (iPhone 14) | Same as above |
| Tablet | 768px (iPad) | Service Card Grid → 2 columns; Contact page → 2 columns |
| Desktop (Small) | 1024px | Service Card Grid → 3 columns; Navigation dropdowns visible |
| Desktop (Wide) | 1440px+ | Full layout; maximum content width 1200px centered |

### 12.3 PHP Version

| Requirement | Version | RTM ID |
|---|---|---|
| Minimum | PHP 8.2 | REQ-TR-001 |
| Extensions | `gd` (with WebP), `mysqli`, `pdo_mysql`, `zip`, `intl`, `mbstring`, `exif`, `xml`, `xsl`, `opcache`, `redis`, `imagick` | — |
| Memory Limit | 256M (`WP_MEMORY_LIMIT`), 512M (`WP_MAX_MEMORY_LIMIT`) | — |
| Upload | `upload_max_filesize=10M`, `post_max_size=12M`, `max_execution_time=120` | — |
| OPcache | Enabled; `memory_consumption=128`, `max_accelerated_files=10000` | — |

### 12.4 WordPress Version

| Requirement | Version | RTM ID |
|---|---|---|
| Minimum | WordPress 6.7 | REQ-TR-003 |
| Tested Up To | WordPress 6.8 | — |
| Auto Updates | Minor releases: enabled. Major releases: tested on staging before production. | — |

### 12.5 WooCommerce Compatibility

| Requirement | Version | RTM ID |
|---|---|---|
| Minimum | WooCommerce 9.x+ | REQ-TR-007 |
| HPOS | High-Performance Order Storage enabled | — |
| Database Prefix | Compatible with `hds_` prefix (not `wp_`) | — |

---

## 13. Monitoring

### 13.1 Server Monitoring

| Metric | Tool | Frequency | Alert | RTM ID |
|---|---|---|---|---|
| Uptime | UptimeRobot | 5 minutes | Downtime > 1 min → Developer + Client | REQ-OPS-004 |
| SSL Certificate | UptimeRobot + Cloudflare | Daily | < 30 days to expiry → Developer | — |
| Disk Usage | Hosting dashboard or WP-Optimize | Weekly | > 80% → Developer | — |
| PHP Errors | `debug.log` file | Weekly review | Spike in errors → Developer | REQ-TR-035 |
| Database Size | WP-Optimize or hosting dashboard | Monthly | Abnormal growth → Developer | — |

### 13.2 Performance Monitoring

| Metric | Tool | Frequency | Alert | RTM ID |
|---|---|---|---|---|
| PSI Mobile Score | PSI API or DebugBear | Weekly (automated) | < 90 → Developer | REQ-PERF-001 |
| Core Web Vitals | Google Search Console | Weekly (manual review) | LCP > 2.5s or CLS > 0.1 → Developer | REQ-PERF-003, REQ-PERF-005 |
| Page Weight | WebPageTest | Monthly | > 1.5 MB mobile → Developer | REQ-PERF-007 |
| Cache Hit Rate | Cloudflare dashboard | Monthly | < 80% → Developer investigation | — |

### 13.3 SEO Monitoring

| Metric | Tool | Frequency | RTM ID |
|---|---|---|---|
| Crawl Errors (404, 500) | Google Search Console | Daily (first 30 days post-launch); weekly thereafter | — |
| Sitemap Status | Google Search Console | Weekly | REQ-SEO-022 |
| Indexed Pages Count | Google Search Console | Weekly; compare to baseline | — |
| Search Impressions / Clicks / CTR | Google Search Console | Weekly; compare to pre-migration baseline | — |
| Keyword Rankings | Google Search Console | Weekly | — |
| Backlinks | Ahrefs/Semrush or GSC | Monthly | — |
| 404 Hits | Rank Math 404 Monitor | Weekly | — |

### 13.4 Analytics Monitoring

| Metric | Tool | Frequency | RTM ID |
|---|---|---|---|
| GA4 Real-Time Traffic | GA4 | Immediate post-launch | REQ-ANL-001 |
| Conversion Events | GA4 Events report | Weekly | REQ-ANL-004..009 |
| Monthly Report (Traffic, Conversions, Sources, Rankings) | Looker Studio + GA4 | Monthly to Client | REQ-ANL-010 |

### 13.5 Security Monitoring

| Metric | Tool | Frequency | RTM ID |
|---|---|---|---|
| Malware Scan | Wordfence | Daily (auto) | REQ-SEC-007 |
| File Changes | Wordfence file integrity monitor | Real-time (auto) | — |
| Firewall Blocks | Wordfence Live Traffic | Weekly review | REQ-SEC-016 |
| Login Attempts (Failed) | Wordfence security log | Weekly review | REQ-SEC-009 |

### 13.6 Error Tracking

| Error Type | Detection | Response |
|---|---|---|
| PHP Fatal Errors | `debug.log` review (weekly) or Wordfence alert | Investigate and fix within 24 hours |
| JavaScript Errors | Browser console test (QA gate); manual check on key pages | Fix before deployment |
| Form Email Delivery Failure | Post SMTP email log (weekly review) | Investigate SMTP configuration; verify email delivery |
| Backup Failure | Backup plugin email alert | Investigate and re-run within 24 hours |
| 404 Spikes | Rank Math 404 monitor (weekly review) | Create 301 redirects for high-traffic 404s |

---

## 14. Acceptance Criteria

Every non-functional requirement must be verified at the QA gate (Sprint 7) or post-launch. This section defines the verifiable pass/fail conditions for each quality domain.

### 14.1 Performance Acceptance

| # | Criterion | Pass Condition | RTM ID |
|---|---|---|---|
| AC-NF01 | PSI Mobile ≥ 90 | Tested on Home, 1 service page, 1 product page | REQ-PERF-001 |
| AC-NF02 | PSI Desktop ≥ 95 | Same pages | REQ-PERF-002 |
| AC-NF03 | LCP < 2.5s | PSI / Lighthouse on all page templates | REQ-PERF-003 |
| AC-NF04 | CLS < 0.1 | PSI / Lighthouse on all page templates | REQ-PERF-005 |
| AC-NF05 | TTFB < 600ms | WebPageTest (Amsterdam, Moto G4, 3G Fast) | REQ-PERF-006 |
| AC-NF06 | Mobile page weight < 1.5 MB | WebPageTest on Home + 1 service page + 1 product page | REQ-PERF-007 |
| AC-NF07 | WebP images served with `<picture>` fallback | DevTools: WebP for supporting browsers; fallback for others | REQ-PERF-007 |
| AC-NF08 | Critical CSS inlined in `<head>` | View page source: critical CSS in `<style>` tag in `<head>` | REQ-PERF-008 |
| AC-NF09 | No render-blocking JavaScript | PSI "Eliminate render-blocking resources" = 0 | REQ-PERF-009 |
| AC-NF10 | Cloudflare cache bypass for WC pages | `CF-Cache-Status: BYPASS` on cart/checkout/account | REQ-PERF-012 |
| AC-NF11 | No jQuery Migrate loaded | DevTools Sources: `jquery-migrate.min.js` absent | REQ-PERF-013 |

### 14.2 Availability Acceptance

| # | Criterion | Pass Condition | RTM ID |
|---|---|---|---|
| AC-NF12 | UptimeRobot monitoring active | Dashboard shows green status; alerts configured | REQ-OPS-004 |
| AC-NF13 | Daily backup completed successfully | Backup plugin dashboard: last backup = today, status = success | REQ-OPS-001 |
| AC-NF14 | Backup restore tested | Restore latest backup to staging; verify pages/forms/WC/admin | REQ-OPS-001 |

### 14.3 Security Acceptance

| # | Criterion | Pass Condition | RTM ID |
|---|---|---|---|
| AC-NF15 | HTTPS enforced + HSTS header present | `curl -I https://helderduidelijkschoon.nl` → 200; HSTS header present | REQ-SEC-001 |
| AC-NF16 | XML-RPC disabled (403) | `curl -I /xmlrpc.php` → HTTP 403 | REQ-SEC-002 |
| AC-NF17 | 2FA enabled on all admin accounts | Wordfence dashboard: 2FA active for all Admin/Editor/Shop Manager | REQ-SEC-010 |
| AC-NF18 | Custom login URL active | `/wp-admin/` and `/wp-login.php` redirect or blocked | REQ-SEC-008 |
| AC-NF19 | Login lockout after 3 failures | Test: 3 failed login attempts → IP locked out | REQ-SEC-009 |
| AC-NF20 | REST user endpoint blocked | `curl /wp-json/wp/v2/users` → 403 or empty | — |
| AC-NF21 | File editor disabled | WP Admin: Appearance → Theme File Editor / Plugins → Plugin File Editor absent | REQ-SEC-011 |
| AC-NF22 | DB prefix changed from `wp_` | Database inspection: table prefix = `hds_` | REQ-SEC-012 |
| AC-NF23 | All forms have honeypot field | Form HTML source: honeypot input present | REQ-SEC-003 |
| AC-NF24 | File upload MIME validation active | Test: upload renamed .exe as .pdf → rejected by server | REQ-FR-019 |

### 14.4 Privacy & GDPR Acceptance

| # | Criterion | Pass Condition | RTM ID |
|---|---|---|---|
| AC-NF25 | Cookie banner appears on first visit | Fresh browser / incognito: banner visible before any cookies set | REQ-CMP-002 |
| AC-NF26 | No GA/Facebook cookies before consent | DevTools Network: zero GA/Facebook requests before banner interaction | REQ-CMP-003 |
| AC-NF27 | Consent logged | Complianz scan log: consent event recorded with timestamp | REQ-CMP-004 |
| AC-NF28 | Privacyverklaring published + linked from footer | Navigate to `/privacyverklaring/` → 200; footer link present on every page | REQ-CMP-001 |
| AC-NF29 | Form consent checkboxes unchecked by default | Manual check: all 3 forms have privacy checkbox unchecked | REQ-CMP-005 |
| AC-NF30 | Cookiebeleid published | Navigate to `/cookiebeleid/` → 200; linked from footer and banner | — |
| AC-NF31 | Algemene Voorwaarden published | Navigate to `/algemene-voorwaarden/` → 200; linked from footer and WC checkout | — |
| AC-NF32 | Privacyverklaring legally reviewed | Client confirms lawyer review completed (MI-17) | REQ-CMP-013 |
| AC-NF33 | DPA with hosting provider signed | Client confirms DPA in place | REQ-CMP-007 |

### 14.5 Accessibility Acceptance

| # | Criterion | Pass Condition | RTM ID |
|---|---|---|---|
| AC-NF34 | axe DevTools: zero critical + zero serious | Scan all page templates | REQ-ACC-001..020 |
| AC-NF35 | WAVE: zero errors | Scan all page templates | — |
| AC-NF36 | Lighthouse Accessibility = 100 | Scan all page templates | REQ-ACC-017 |
| AC-NF37 | Keyboard: all elements focusable + operable | Manual tab-through on Home, 2 service pages, Contact form, 1 product page | REQ-ACC-002 |
| AC-NF38 | Skip to content link functional | Tab to body → skip link visible → Enter → focus moves to `<main>` | REQ-ACC-003 |
| AC-NF39 | Color contrast passes AA | All color combinations via WebAIM or axe | REQ-ACC-001 |
| AC-NF40 | Touch targets ≥ 44px | Manual measurement on mobile viewport | REQ-ACC-011 |
| AC-NF41 | `lang="nl-NL"` on every page | Check `<html>` element in page source | REQ-ACC-012 |

### 14.6 SEO Acceptance

| # | Criterion | Pass Condition | RTM ID |
|---|---|---|---|
| AC-NF42 | Unique title tags on all pages | Screaming Frog: zero empty, zero duplicate | REQ-SEO-001 |
| AC-NF43 | Unique meta descriptions on all pages | Screaming Frog: zero empty, zero duplicate | REQ-SEO-001 |
| AC-NF44 | XML Sitemap returns 200 with valid XML | Navigate to `/sitemap_index.xml` → 200; valid XML | REQ-SEO-022 |
| AC-NF45 | Zero attachment pages in sitemap | Inspect page-sitemap.xml: zero attachment URLs | REQ-SEO-023 |
| AC-NF46 | robots.txt returns 200 | Navigate to `/robots.txt` → 200; correct disallow rules | REQ-SEO-024 |
| AC-NF47 | All schema types valid | Google Rich Results Test: zero errors on all 9 schema types | REQ-SEO-025..027 |
| AC-NF48 | All 301 redirects working | Test each old URL via `httpstatus.io`: 301 to correct new URL; zero chains | REQ-SEO-028 |
| AC-NF49 | OpenGraph tags valid | Facebook Sharing Debugger: zero errors on Home + 2 service pages | — |
| AC-NF50 | Zero broken internal links | Screaming Frog crawl: zero 4xx on internal links | REQ-SEO-010 |

### 14.7 Compatibility Acceptance

| # | Criterion | Pass Condition | RTM ID |
|---|---|---|---|
| AC-NF51 | Cross-browser: Chrome, Firefox, Safari, Edge | All 13 page templates render consistently; all functionality works | — |
| AC-NF52 | Mobile (375px): no horizontal scroll | Manual test on iPhone SE viewport | — |
| AC-NF53 | Tablet (768px): responsive layout | Manual test on iPad viewport | — |
| AC-NF54 | PHP 8.2+ verified | `phpinfo()` or hosting dashboard | REQ-TR-001 |
| AC-NF55 | WordPress 6.7+ verified | WP Admin → Updates | REQ-TR-003 |
| AC-NF56 | WooCommerce 9.x+ verified | WP Admin → Plugins | REQ-TR-007 |

---

## 15. Requirement Traceability

Every non-functional requirement defined in this document is traceable to the Requirements Traceability Matrix (RTM-001). Below is the consolidated mapping.

### 15.1 Performance Traceability (RTM REQ-PERF-001..014)

| NFR § | RTM ID | Story | AC IDs |
|---|---|---|---|
| 3.1 Core Web Vitals | REQ-PERF-001..006 | E-QA-03 | AC-PERF-01..06 |
| 3.2 Lighthouse | REQ-PERF-001..002 | E-QA-03 | AC-PERF-01..02 |
| 3.3 Page Weight | REQ-PERF-007 | E-QA-03 | AC-PERF-06..07 |
| 3.4 Caching | REQ-PERF-008, REQ-PERF-012 | E-INFRA-03 | AC-PERF-08, AC-PERF-12 |
| 3.5 Images | REQ-PERF-007, REQ-PERF-011 | E-SEO-10 | AC-PERF-07, AC-PERF-11 |
| 3.6 Critical CSS/JS | REQ-PERF-008..010, REQ-PERF-013 | E-INFRA-06 | AC-PERF-08..10, AC-PERF-13 |
| 3.7 Compression/CDN | REQ-PERF-007..008 | E-INFRA-03 | AC-PERF-07..08 |
| 3.8 Database | REQ-PERF-014 | E-COMPLY-07 | AC-PERF-14 |

### 15.2 Security Traceability (RTM REQ-SEC-001..016)

| NFR § | RTM ID | Story | AC IDs |
|---|---|---|---|
| 6.1 Authentication | REQ-SEC-007..010 | E-COMPLY-05 | AC-SEC-06..10 |
| 6.2 Password Policy | REQ-SEC-007..010 | E-COMPLY-05 | AC-SEC-06..10 |
| 6.3 Authorization | REQ-SEC-007..010 | E-COMPLY-05 | AC-SEC-06..10 |
| 6.4 Firewall | REQ-SEC-001..002, REQ-SEC-007, REQ-SEC-011..012, REQ-SEC-016 | E-INFRA-01, E-INFRA-03, E-COMPLY-05 | AC-SEC-01..02, AC-SEC-06, AC-SEC-10..11, AC-SEC-15 |
| 6.5 Rate Limiting | REQ-SEC-002, REQ-SEC-008..009, REQ-SEC-016 | E-COMPLY-05 | AC-SEC-02, AC-SEC-07..08, AC-SEC-15 |
| 6.6 Security Headers | REQ-SEC-001 | E-INFRA-03 | AC-SEC-01 |
| 6.7 Input Validation | REQ-SEC-014..015 | E-INFRA-06 | AC-SEC-13..14 |
| 6.8 XSS Prevention | REQ-SEC-014..015 | E-INFRA-06 | AC-SEC-13..14 |
| 6.9 CSRF Protection | REQ-SEC-004 | E-CORE-09 | AC-SEC-03 |
| 6.10 File Upload | REQ-FR-019 | E-CORE-10 | AC-QUOTE-04..06 |
| 6.11 Backup Encryption | — | E-INFRA-05 | — |

### 15.3 Privacy & GDPR Traceability (RTM REQ-CMP-001..013)

| NFR § | RTM ID | Story | AC IDs |
|---|---|---|---|
| 7.1 Cookie Consent | REQ-CMP-002..004, REQ-CMP-010 | E-COMPLY-01 | AC-F06.1-4, AC-CMP-05 |
| 7.2 Privacy Policy | REQ-CMP-001, REQ-CMP-013 | E-SUPPORT-05 | AC-F05.1-5 |
| 7.3 Data Retention | REQ-CMP-006 | E-COMPLY-04 | AC-CMP-01 |
| 7.4 Right to Erasure | REQ-CMP-009 | E-COMPLY-04 | AC-CMP-04 |
| 7.5 Data Portability | REQ-CMP-009 | E-COMPLY-04 | AC-CMP-04 |
| 7.6 Third-Party DPAs | REQ-CMP-007 | E-PREREQ-09 | AC-CMP-02 |
| 7.7 Breach Notification | REQ-CMP-008 | E-COMPLY-06 | AC-CMP-03 |

### 15.4 Accessibility Traceability (RTM REQ-ACC-001..020)

| NFR § | RTM IDs | Story | AC IDs |
|---|---|---|---|
| 8.1 Perceivable | REQ-ACC-001, REQ-ACC-004..006, REQ-ACC-009..010 | E-COMPLY-07 | AC-A11Y-01..20 |
| 8.2 Operable | REQ-ACC-002..003, REQ-ACC-008, REQ-ACC-011, REQ-ACC-015..016 | E-INFRA-06, E-COMPLY-07 | AC-A11Y-02..03, AC-A11Y-07, AC-A11Y-10, AC-A11Y-14..15 |
| 8.3 Understandable | REQ-ACC-012..013 | E-INFRA-06, E-SEO-01 | AC-A11Y-11..12 |
| 8.4 Robust | REQ-ACC-007, REQ-ACC-014, REQ-ACC-018..019 | E-CORE-09, E-COMM-07 | AC-A11Y-06, AC-A11Y-13, AC-A11Y-17..18 |
| 8.5 Testing | REQ-ACC-001..020 | E-QA-01 | AC-A11Y-01..20 |

### 15.5 SEO Traceability (RTM REQ-SEO-001..028)

| NFR § | RTM IDs | Story | AC IDs |
|---|---|---|---|
| 9.1 Metadata | REQ-SEO-001..021 | E-SEO-01 | AC-SEO-01..03 |
| 9.2 Canonical | REQ-SEO-024 | E-SEO-09 | AC-SEO-05 |
| 9.3 Schema | REQ-SEO-025..027 | E-SEO-02..04 | AC-SEO-06..08 |
| 9.4 Sitemap | REQ-SEO-022..023 | E-SEO-08 | AC-F03 |
| 9.5 robots.txt | REQ-SEO-024 | E-SEO-09 | AC-SEO-05 |
| 9.6 OpenGraph | REQ-SEO-010 | E-SEO-01 | — |
| 9.7 Redirects | REQ-SEO-028 | E-SEO-07 | AC-SEO-09 |
| 9.8 Internal Linking | REQ-SEO-010 | E-CORE-03..08 | AC-crosl-link checks |
| 9.9 URL Consistency | — | E-INFRA-01 | — |

### 15.6 Full Coverage Verification

| Quality Domain | NFR Count | RTM IDs Mapped | Coverage |
|---|---|---|---|
| Performance | 28 | REQ-PERF-001..014 | 100% |
| Availability | 11 | REQ-OPS-001, REQ-OPS-004 | 100% |
| Scalability | 6 dimensions | SAD §40, ADR §11 | 100% |
| Security | 32 | REQ-SEC-001..016 | 100% |
| Privacy & GDPR | 21 | REQ-CMP-001..013 | 100% |
| Accessibility | 20 | REQ-ACC-001..020 | 100% |
| SEO | 25 | REQ-SEO-001..028 | 100% |
| Reliability | 18 | REQ-FR-016, REQ-FR-018..019, REQ-OPS | 100% |
| Maintainability | 15 | ADR §6, SAD §37..39 | 100% |
| Compatibility | 10 | REQ-TR-001, REQ-TR-003, REQ-TR-007 | 100% |
| Monitoring | 18 | REQ-OPS-004, REQ-ANL-001..010 | 100% |

**Total Non-Functional Requirements: 110+**
**Total Acceptance Criteria: 56 (AC-NF01 through AC-NF56)**
**Coverage: 100% of RTM non-functional requirements traceable from this document**

---

## 11. Additional Requirements (Post Final Architecture Review)

### 11.1 Security — Cloudflare WAF Rules

| Rule Name | Expression | Action | Priority | RTM ID |
|---|---|---|---|---|
| Block XML-RPC | `(http.request.uri.path eq "/xmlrpc.php")` | Block (403) | 1 | REQ-SEC-002 |
| Rate-Limit Login | `(http.request.uri.path contains "/wp-login.php" or http.request.uri.path contains "/wp-admin") and (http.request.method eq "POST")` | Rate-Limit (10 req/5min) | 2 | REQ-SEC-009 |
| WordPress Managed Ruleset | Cloudflare Managed Ruleset → "WordPress" (Pro plan) OR custom WAF rules from Cloudflare community | Block known exploit patterns | 3 | REQ-SEC-016 |
| Allow Mollie Webhook | `(http.request.uri.path contains "/wc-api/mollie_return/")` | Allow (bypass WAF) | 1 | REQ-WC |
| Allow Admin AJAX | `(http.request.uri.path contains "/wp-admin/admin-ajax.php")` | Allow (bypass rate-limit) | 2 | REQ-TR |

**Defense in Depth:** XML-RPC is blocked at BOTH Cloudflare WAF level AND Nginx server level. If one fails, the other catches the request.

### 11.2 Security — Incident Response Procedure

| Phase | Actions | Time Target | Owner |
|---|---|---|---|
| **1. Detection** | Wordfence alert, client report, uptime alert, or developer observation. Confirm incident is real (not false positive). | Immediate | Developer |
| **2. Containment** | Put site in maintenance mode. Change all passwords (WP admin, hosting, database, Cloudflare). Block attacker IPs via Cloudflare WAF. | < 30 minutes | Developer |
| **3. Investigation** | Review server logs, Wordfence logs, file integrity check. Identify: entry vector, affected systems, data accessed/modified, duration of access. | < 4 hours | Developer |
| **4. Remediation** | Restore clean backup to staging → verify → deploy to production. Patch vulnerability. Harden affected component. | < 4 hours (from containment) | Developer |
| **5. Notification** | If personal data breached: notify Autoriteit Persoonsgegevens within 72 hours (GDPR Art. 33). Notify affected users. Document notification. | < 72 hours (legal requirement) | Client + Lawyer |
| **6. Post-Mortem** | Document: root cause, timeline, impact, remediation steps, preventive measures. Update security runbook. Review all other sites for same vulnerability. | < 1 week | Developer |

### 11.3 Operations — Plugin Update Testing Procedure

| Step | Action | Time |
|---|---|---|
| 1 | Clone production database to staging (anonymize if personal data). | 5 min |
| 2 | Apply all pending plugin/theme/core updates on staging. | 10 min |
| 3 | Run smoke test: Homepage (HTTP 200, H1 visible), Contact form (submit + email delivered), 1 Service page (content renders), WooCommerce product page (if active), Mobile menu (open/close). | 15 min |
| 4 | Run automated checks: Screaming Frog (zero 4xx/5xx), PSI mobile (verify ≥ 85 — regression alert if dropped below 90), axe DevTools (zero new issues). | 15 min |
| 5 | If all tests pass: apply updates to production. Clear caches. | 10 min |
| 6 | If any test fails: document failure, rollback staging, investigate. Do NOT apply to production. | Variable |
| **Total Time** | **55 minutes per monthly cycle** | |

**Frequency:** Monthly maintenance window. Client notified 48 hours in advance. Emergency patches (security) bypass the window but follow the same procedure (compressed timeline).

### 11.4 Operations — Database Performance Configuration

| Parameter | Minimum Value | Notes |
|---|---|---|
| `innodb_buffer_pool_size` | 256M | Adjust to ~70% of available RAM on server |
| `max_connections` | 50 | Managed WP hosts typically handle this |
| `query_cache_type` | OFF | Deprecated in MySQL 8.0; use Redis instead |
| `table_open_cache` | 2000 | WordPress has many tables |
| `innodb_flush_log_at_trx_commit` | 2 | Acceptable for WP (balance of performance/durability) |

### 11.5 Database Growth Projection

| Data Source | Monthly Growth Estimate | 3-Year Total |
|---|---|---|
| Form entries (Gravity Forms) | ~50 entries × 2 KB = 100 KB/month | ~3.6 MB |
| WooCommerce orders (if Airfixr kept) | ~20 orders × 5 KB = 100 KB/month | ~3.6 MB |
| Blog posts with images | 2 posts × 500 KB images = 1 MB/month | ~36 MB |
| Media uploads (page images) | ~10 images × 150 KB WebP = 1.5 MB/month | ~54 MB |
| WordPress core + plugin data | Minimal growth | ~50 MB |
| **Total Estimated 3-Year Database Size** | | **~150 MB** |
| **Minimum Hosting Storage** | 10 GB (any managed WP plan) | **Sufficient (6.7% utilization)** |

### 11.6 WooCommerce — Deferred Features

| Feature | Reason for Deferral | Priority |
|---|---|---|
| Abandoned cart recovery emails | Low volume shop (<20 orders/month). ROI insufficient. If volume increases post-launch, implement via WooCommerce Follow-Up Emails plugin. | P3 — Post-Launch |
| Inventory threshold alerts | Default low-stock notification to info@ via WooCommerce core. Sufficient for 14-product catalog. Dedicated alert thresholds deferred. | P2 — Sprint 4 if time permits |
| Advanced shipping (table rates) | Client must provide shipping costs (MI-14). Default: flat rate per class. Advanced: post-launch if client requests. | P3 — Post-Launch |

### 11.7 Print Stylesheet

| Requirement | Implementation |
|---|---|
| **Print CSS** | Add `print.css` to theme assets. Hide: header navigation, footer CTAs, cookie banner, sidebar, back-to-top button. Display: full URLs for links (`a[href]::after { content: " (" attr(href) ")"; }`). Ensure content flows naturally with readable typography (serif or sans-serif, 12pt, black on white). |
| **Priority** | P3 — Low. B2B clients (facility managers, VvE boards) may print service pages for decision-making. Low effort (< 2 hours), professional result. |

### 11.8 Acceptance Criteria — Additional (Post-Review)

| ID | Criterion | Pass Condition | RTM ID |
|---|---|---|---|
| AC-NF57 | Email delivered within 2 minutes of form submission | Post SMTP log shows delivery time < 120s | REQ-INF-04 |
| AC-NF58 | Old site backup test restore verified | All pages, forms, WC (if kept), admin login work on restored instance | REQ-MIG-008 |
| AC-NF59 | DNS TTL verified at 300 seconds before launch | whatsmydns.net shows TTL = 300 for helderduidelijkschoon.nl | REQ-MIG-009 |
| AC-NF60 | Cloudflare cache bypass for WC pages verified | CF-Cache-Status: BYPASS header on /winkelmand/, /afrekenen/, /mijn-account/ | REQ-PERF-012 |
| AC-NF61 | Payment gateway webhook delivers order status update | After Mollie test payment, WooCommerce order status = Processing | REQ-WC |
| AC-NF62 | Database collation verified — Dutch diacritics correct | All ë, ï, é, ó, ö, ü render correctly on every page | REQ-MIG-010 |
| AC-NF63 | reCAPTCHA badge not obscured by cookie banner | Visual check: badge visible below cookie banner on all viewport sizes | REQ-ACC-002 |

---

**This Non-Functional Requirements specification is internally consistent with all previous project documents (MPS-001, SAD-001, ADR-001, BKLG-001, ARR-001, RTM-001, FS-001, all source documents, and all rebuild specifications). All assumptions are explicitly marked. Sprint 2 development and Sprint 7 QA verification may proceed with this specification as the definitive quality reference.**

**END OF NON-FUNCTIONAL REQUIREMENTS — Version 1.0.0**
