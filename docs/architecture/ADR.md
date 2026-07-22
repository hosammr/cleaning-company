# HDS Onderhoudsdiensten — Architecture Decision Record

**Document ID:** ADR-001 | **Version:** 1.0.0 | **Status:** Approved for Development
**Project:** helderduidelijkschoon.nl — Ground-Up Rebuild
**Language:** Nederlands (nl-NL) | **Date:** July 2026
**Referenced Documents:** MPS-001, SAD-001, BKLG-001, ARR-001, RS-01 through RS-08, Epic 1 Implementation

---

## 1. Purpose

### 1.1 Objective of the Architecture

This Architecture Decision Record (ADR) captures every architectural decision made for the HDS Onderhoudsdiensten platform rebuild. It serves as the binding technical reference for all development sprints and is the definitive source when implementation questions arise.

The architecture supports a **complete rebuild** of helderduidelijkschoon.nl — a professional B2B cleaning services website serving West-Brabant and Zeeland, with a secondary eCommerce function for Airfixr air purification products.

### 1.2 Business Goals

| # | Goal | Metric |
|---|---|---|
| BG01 | Restore web-based lead capture | Contact form submissions delivering to info@helderduidelijkschoon.nl |
| BG02 | Establish online presence for Reguliere Schoonmaak (primary service, currently HTTP 404) | New page at `/reguliere-schoonmaak/` with 300+ words |
| BG03 | Achieve legal compliance (GDPR/AVG) | Privacyverklaring published, cookie consent active, KVK/BTW displayed |
| BG04 | Enable SEO-based client acquisition | 32 unique meta descriptions, valid XML sitemap, structured data on all pages |
| BG05 | Professional, accessible, performant website | WCAG 2.2 AA, Lighthouse Accessibility 100, PSI mobile 90+ |
| BG06 | Client self-sufficiency post-launch | Block Editor for all content, Gravity Forms admin, WooCommerce admin, written Beheergids |

### 1.3 Technical Goals

| # | Goal | Metric |
|---|---|---|
| TG01 | Eliminate all technical debt | Zero code carried forward; all software on latest stable versions |
| TG02 | No page builder lock-in | Native Block Editor only; all content as standard blocks, no shortcodes |
| TG03 | Defensive security posture | 6-layer security model; XML-RPC disabled; 2FA enforced; custom login URL |
| TG04 | Environment parity | Local, staging, and production run identical software stacks |
| TG05 | Automated deployment | Git push triggers deploy; environment detection via `WP_ENV` variable |
| TG06 | Measurable performance | PSI 90+ mobile, 95+ desktop; LCP < 2.5s; CLS < 0.1 |

---

## 2. Current Architecture Assessment

### 2.1 Existing Stack

| Component | Current | Status |
|---|---|---|
| CMS | WordPress 6.2.9 | 5+ major versions behind |
| Theme | Divi 4.16.1 | 10+ versions behind; performance overhead; shortcode lock-in |
| eCommerce | WooCommerce 8.2.5 | 2+ major versions behind |
| Forms | Formidable Forms | Broken — causing HTTP 500 on `/contact/` |
| SEO | Yoast SEO 21.8.1 | Outdated; page sitemap returns HTTP 500 |
| PHP | Unknown (likely < 8.0) | EOL; security risk |
| XML-RPC | Enabled | Brute-force attack vector |
| Analytics | None | Zero visibility into traffic or conversions |
| Backups | None | Total data loss risk |
| Privacy Policy | None | GDPR/AVG violation |
| Cookie Consent | None | ePrivacy Directive violation |

### 2.2 Known Failures

| Failure | Impact |
|---|---|
| `/contact/` returns HTTP 500 | Zero web-originated leads captured |
| `/reguliere-schoonmaak/` returns HTTP 404 | Primary service line has no web presence |
| `sitemap_index.xml` page-sitemap returns HTTP 500 | Search engines cannot discover all pages |
| Instagram widget broken on every page | Unprofessional appearance on all pages |
| Vacatures page content as JPG images | Completely inaccessible; not indexable; not searchable |

### 2.3 Technical Debt Summary

| Category | Count | Resolution |
|---|---|---|
| Critical failures (HTTP 500/404) | 3 | Rebuild affected pages from scratch |
| Security vulnerabilities | 4 | Fresh install; disable XML-RPC; 2FA; custom login URL |
| Missing legal compliance | 2 | Privacyverklaring + cookie consent |
| Missing analytics | 2 | GA4 + GTM + GSC |
| Thin content (< 150 words) | 9 pages | Expand all to 300+ words |
| Outdated software | All components | Fresh install of latest versions |
| No backup strategy | 1 | Daily automated offsite backups |

### 2.4 Risks Carried from Current Architecture

**None.** This is a ground-up rebuild. Zero code, zero configuration, and zero decisions carry forward from the existing site. Every architectural choice is made fresh.

---

## 3. Target Architecture

### 3.1 CMS

**Decision:** WordPress 6.7+ (latest stable)

**Context:** Client is familiar with WordPress. WooCommerce compatibility is required for the Airfixr product line. The existing site runs WordPress (outdated). No other CMS was evaluated — WordPress is the only CMS that meets the combination of: client familiarity, WooCommerce support, Gravity Forms integration, Dutch localization, and managed hosting availability.

**Constraints:**
- PHP 8.2+ minimum
- MySQL 8.0+ or MariaDB 10.6+ (InnoDB, utf8mb4)
- Database prefix: `hds_` (not `wp_`)
- Permalink structure: `/%postname%/`
- Category base: `kennisbank`
- Language: nl_NL
- Comments and pingbacks disabled site-wide
- Post revisions capped at 10
- DISALLOW_FILE_EDIT enabled
- FORCE_SSL_ADMIN enabled

### 3.2 Theme

**Decision:** Custom Hybrid Block Theme

**Architecture:** Uses `theme.json` for design tokens and block style configuration + PHP templates (`page-service.php`, `front-page.php`, etc.) for structured, predictable layouts + Block Editor for all content areas. This is NOT Full Site Editing (FSE) — PHP templates provide layouts that clients cannot accidentally break. Design tokens flow from `theme.json` into CSS custom properties usable by both the Block Editor and the frontend.

**Alternatives Considered:**
- **GeneratePress Pro + GenerateBlocks:** Faster time-to-market, less custom development, but constrains the design system to GeneratePress conventions. Rejected because the project plan calls for a custom theme foundation (E-INFRA-06, E-INFRA-08) which is already estimated and scheduled.
- **Kadence Pro:** Similar trade-offs to GeneratePress. Rejected for the same reason.
- **True FSE Theme (HTML templates):** Higher risk, less mature pattern. PHP templates were preferred because they provide predictable layouts and are proven in the WordPress ecosystem.

**Theme Key:** `hds`
**Text Domain:** `hds`

### 3.3 Page Builder

**Decision:** Native WordPress Block Editor (Gutenberg) ONLY

**Banned:** Divi, Elementor, WPBakery, Beaver Builder, and all other third-party page builders.

**Justification:** Page builders trap content in shortcodes (`[divi_shortcode]`), creating a migration nightmare. The current site's Divi dependency is a primary reason for the rebuild. Native blocks store content as standard HTML comments in `post_content`, making it portable across themes and future-proof.

### 3.4 Custom Post Types

| CPT Key | Public | Has Archive | Queryable | Rewrite Slug | Purpose |
|---|---|---|---|---|---|
| `hds_testimonial` | `false` | `false` | Block-only | — | Client testimonials queried via `hds/testimonial` custom block |
| `hds_vacancy` | `true` | `false` | `true` | `vacatures` | Job listings displayed on `/vacatures/` page |

**Critical Decision — hds_testimonial:** Set to `public => false` and `publicly_queryable => false` to avoid URL conflict with the `/referenties/` Page (P13). Testimonials are queried exclusively via the `hds/testimonial` custom block on the Referenties page. No archive, no single view, no slug conflict.

**Critical Decision — hds_faq REMOVED (D-012):** The `hds_faq` CPT has been removed from the architecture. FAQ content is managed via the Yoast/Rank Math FAQ Block on a standard Page at `/veelgestelde-vragen/` (P18). Rationale: (a) Editors edit one page — simpler UX, (b) Yoast/Rank Math FAQ blocks auto-generate FAQPage schema, (c) No CPT maintenance overhead, (d) No custom block needed. See ADR D-012 in §4.

### 3.5 Taxonomies

**Decision:** Standard WordPress Categories and Tags for blog posts only.

No custom taxonomies are required for the initial build. Service pages use standard Pages with the Service template. Vacancy categorization uses standard taxonomy if needed. The architecture supports adding custom taxonomies later without structural change.

### 3.6 Custom Fields

| Field Group | Location | Fields |
|---|---|---|
| Service Page Settings | Page (template: Service) | `hds_subtitle`, `hds_hero_image`, `hds_service_icon`, `hds_cta_override` |
| Testimonial Details | `hds_testimonial` | `hds_author_name`, `hds_company_name`, `hds_star_rating`, `hds_related_service` |
| Vacancy Details | `hds_vacancy` | `hds_hours_per_week`, `hds_location`, `hds_start_date`, `hds_application_email`, `hds_deadline`, `hds_is_active` |

**Company Information:** Stored in Theme Customizer as `theme_mod` values (`hds_address`, `hds_postal_city`, `hds_phone`, `hds_email`, `hds_kvk`, `hds_btw`, `hds_facebook_url`, `hds_instagram_url`, `hds_gbp_url`, `hds_opening_hours`). Rendered via `get_theme_mod()` in footer, contact page, and LocalBusiness schema. Single source of truth for NAP (Name, Address, Phone) across the entire site.

**Naming Convention:** All custom fields use the `hds_` prefix with lowercase underscores. No exceptions.

### 3.7 Forms

**Decision:** Gravity Forms (Premium)

**Alternatives Considered:** Formidable Forms (currently in use but broken — HTTP 500), WS Form, WPForms. Gravity Forms was selected because it is the market leader for complex WordPress forms with conditional logic, file uploads, GDPR consent fields, and reliable email delivery.

**Form Inventory:**

| Form | ID | Page | Fields | Key Features |
|---|---|---|---|---|
| Contact | GF-1 | `/contact/` | 9 | reCAPTCHA v3, honeypot, privacy checkbox, Dutch errors |
| Offerte Aanvragen | GF-2 | `/offerte-aanvragen/` | 13 | Multi-select services, postcode validation, file upload (5MB, PDF/JPG/PNG/DOCX) |
| Vacature Application | GF-3 | `/vacatures/` | 6 | CV upload (5MB, PDF/DOCX), per-vacancy targeting |

**Form Configuration (All Forms):**
- Confirmation email from: `info@helderduidelijkschoon.nl`
- Notification email to: `info@helderduidelijkschoon.nl`
- Entry storage: WordPress database (12-month retention for contact/quote; 6-month for vacancies)
- Spam protection: reCAPTCHA v3 + honeypot field (both mandatory)
- Accessibility: All fields have `<label>`; required fields marked with `aria-required`; inline Dutch errors via `aria-describedby`
- Loading state: AJAX submission enabled; button text changes to "Versturen..." with spinner and disabled state
- File upload security: Server-side MIME validation beyond client-side extension check; files renamed on upload; downloadable via link in notification email (not inline attachment)
- Post-submit: Redirect to `/bedankt/?type={form}`

### 3.8 SEO

**Decision:** Rank Math Pro (Premium)

**Alternatives Considered:** Yoast SEO Premium. Rank Math Pro was selected for: built-in redirect manager (Yoast requires separate plugin), richer free tier, built-in 404 monitor, and more granular schema controls. Both are capable; the decision was made to avoid mid-project switching.

**SEO Features Configured:**
- Per-page meta titles (50–60 chars) and meta descriptions (150–160 chars)
- XML Sitemaps at `/sitemap_index.xml` with sub-sitemaps for pages, posts, and products
- Excluded from sitemap: attachment pages, author archives, noindex pages (Bedankt, legal pages)
- Open Graph and Twitter Card tags on all pages
- Auto-generated schema: WebSite, WebPage, BreadcrumbList, Article, Product
- 301 redirect manager for all redirect rules
- robots.txt auto-generated

**Theme-Generated Schema (Custom JSON-LD):**
- `LocalBusiness` (HomeAndConstructionBusiness) — Home, Contact, Over HDS pages
- `Service` — Each service page (P02–P08)
- `FAQPage` — `/veelgestelde-vragen/` (auto-generated from Yoast/Rank Math FAQ blocks)
- `JobPosting` — Per vacancy on `/vacatures/`
- `Organization` with `sameAs` linking to Facebook, Instagram, GBP

### 3.9 Caching

**Decision:** FlyingPress (Premium) + Redis object cache

**Alternatives Considered:** WP Rocket. FlyingPress was chosen for its built-in unused CSS removal, stronger Core Web Vitals optimization, and generally more aggressive performance features. Both are excellent — the decision was made to avoid mid-project switching.

**Caching Architecture (4 layers):**

```
[Browser Cache: 1 year for versioned static assets]
    ↓
[Cloudflare CDN: Full-page caching; bypass for WC/admin/AJAX]
    ↓
[FlyingPress: Page cache; cleared on content/plugin/theme changes]
    ↓
[Redis: Object cache for WP_Query, transients, options]
    ↓
[PHP OPCache: Compiled bytecode]
```

**Cloudflare Cache Bypass Rules (Mandatory):**
- `/winkelmand/*`, `/afrekenen/*`, `/mijn-account/*`, `/wp-admin/*`
- `/wp-json/wc/*`, `/?wc-ajax=*`

**Purge Triggers:** Post/page update → FlyingPress → Cloudflare API. WooCommerce product update → purge product + shop + category. Plugin/theme update → purge all.

### 3.10 Image Optimization

**Decision:** ShortPixel or Imagify (auto-WebP conversion on upload)

**Image Pipeline:** Upload (JPG/PNG) → Compress (quality 85+) → Convert to WebP → Generate all registered sizes → Serve via `<picture>` element (WebP primary + fallback) → Lazy load below fold → CDN cache.

**Registered Image Sizes:**
- Thumbnail: 150×150 (crop)
- Medium: 600×600
- Large: 1200×1200
- hds-card: 400×300 (crop)
- hds-content: 800×600
- hds-hero: 1600×900 (crop)

Unused default WordPress sizes disabled.

**Image Standards:**
- Format: WebP with PNG/JPEG fallback via `<picture>`
- Compression: Visually lossless (quality 85+)
- Responsive: `srcset` with 400w, 800w, 1200w variants + `sizes` attribute
- Lazy loading: `loading="lazy"` below fold; `fetchpriority="high"` on LCP image
- Alt text: Descriptive Dutch on all non-decorative images; `alt=""` for decorative
- Filenames: Lowercase-hyphens-Dutch-keywords (e.g., `glasbewassing-kantoor-bergen-op-zoom.webp`)
- Dimensions: Explicit `width` and `height` attributes to prevent CLS

### 3.11 Analytics

**Decision:** Google Analytics 4 (GA4) via Google Tag Manager (GTM)

**Configuration:**
- GA4 property: "HDS Onderhoudsdiensten"
- Data stream: helderduidelijkschoon.nl
- Enhanced Measurement: All events enabled (page views, scrolls, outbound clicks, site search, video engagement, file downloads)
- Data retention: 14 months
- IP anonymization: Enabled (GA4 default)
- Bot filtering: Enabled
- Internal traffic filter: Office IP (client to provide — MI)
- Consent Mode v2: Integrated with Complianz for GTM consent signals

**Conversion Events:**

| Event | Trigger | GA4 Event Name |
|---|---|---|
| Phone call click | `tel:` link clicked | `phone_click` |
| Email click | `mailto:` link clicked | `email_click` |
| Contact form submission | Redirect to `/bedankt/?type=contact` | `form_submission` |
| Quote request submission | Redirect to `/bedankt/?type=offerte` | `quote_request` |
| WooCommerce add to cart | Product added | `add_to_cart` |
| WooCommerce purchase | Order completed | `purchase` |
| Cookie consent accepted | Banner "Accepteren" clicked | `cookie_consent_accepted` |

### 3.12 Security

**Decision:** Wordfence Premium

**6-Layer Security Model:**

| Layer | Measures |
|---|---|
| 1. Transport | HTTPS only; HSTS max-age=31536000 preload; TLS 1.3; SPF + DKIM + DMARC |
| 2. CDN/Edge | Cloudflare WAF (block xmlrpc.php, rate-limit login, WordPress managed ruleset); DDoS protection; SSL Full (Strict) |
| 3. Server | XML-RPC disabled (403); directory listing disabled; file permissions (dirs 755, files 644, wp-config.php 400); DB prefix `hds_`; DISALLOW_FILE_EDIT=true; SFTP only |
| 4. Authentication | Custom login URL (Wordfence); 2FA on ALL admin/editor/shop manager accounts; brute force (max 3 attempts → IP lockout); user enumeration prevention (block `?author=N`, `/wp-json/wp/v2/users`) |
| 5. Application Firewall | Wordfence WAF; malware scan (daily); file integrity monitoring |
| 6. Application Logic | Input sanitization; output escaping (`esc_html`, `esc_attr`, `esc_url`, `wp_kses`); nonce verification on all custom forms; capability checks; prepared SQL statements |

**Hard Constraints:**
- No `eval()`, no `base64_decode()`, no `extract()`
- All user-facing strings internationalized with `__()`/`_e()` and textdomain `hds`
- All output escaped; all inputs sanitized
- No nulled or cracked plugins — official sources only
- No application passwords

### 3.13 Backups

**Decision:** BlogVault or UpdraftPlus Premium

**Schedule:**
| Backup Type | Frequency | Retention | Storage |
|---|---|---|---|
| Full (files + database) | Daily (nightly) | 30 daily, 4 weekly, 12 monthly | Offsite cloud |
| Pre-update | Before every plugin/theme/core update | — | Offsite cloud |
| WooCommerce orders | Monthly CSV export | 7 years (Dutch financial data retention) | Offsite cloud |

**Verification:** Monthly test restore to staging. Verify: all pages load, forms submit, WooCommerce checkout works, admin login works.

**Disaster Recovery RTO/RPO:**
| Scenario | RTO | RPO |
|---|---|---|
| Server failure | < 4 hours | < 24 hours |
| Malware/defacement | < 4 hours | < 24 hours |
| Accidental deletion | < 1 hour (revisions) / < 4 hours (restore) | < 24 hours |
| DNS/domain issue | < 2 hours | N/A |

### 3.14 Deployment

**Decision:** Git-based deployment via GitHub Actions

**Environments:**

| Environment | URL | Access | Debug | Indexing |
|---|---|---|---|---|
| Local | `hds.local` | Developer only | `WP_DEBUG=true` | N/A |
| Staging | `staging.helderduidelijkschoon.nl` | Developer + Client (password) | `WP_DEBUG=true` | `noindex, nofollow` |
| Production | `helderduidelijkschoon.nl` | Public + Developer + Client (admin) | `WP_DEBUG=false` (log only) | `index, follow` |

**Deployment Workflow:**
```
Developer local → Git push to staging branch
  → GitHub Actions auto-deploy to Staging
  → Client review + QA on Staging
  → Merge staging → main
  → GitHub Actions auto-deploy to Production
  → Clear caches (FlyingPress + Cloudflare + Redis)
  → Smoke tests
```

**CI/CD Pipeline Stages (`.github/workflows/deploy.yml`):**
1. Lint (PHPCS + ESLint + Stylelint)
2. Pre-deploy backup (production only)
3. Deploy (SSH rsync to target environment)
4. Post-deploy (`wp cache flush`, `wp rewrite flush`)

**Rollback Strategy:**
- Backup taken immediately before every production deployment
- Rollback: restore pre-deploy backup to staging → deploy to production
- Time objective: < 30 minutes for plugin updates, < 2 hours for complete site failure

### 3.15 Hosting

**Decision:** Managed WordPress Hosting (Kinsta, WP Engine, or Cloud86.nl)

**Assumption:** Hosting provider is a client decision (MI-20). The architecture assumes managed WordPress hosting with the following minimum requirements:
- PHP 8.2+
- MySQL 8.0+ / MariaDB 10.6+
- Redis object cache
- Daily automated backups
- Staging environment
- SFTP access
- Server-level cron (for WP-Cron replacement)

### 3.16 CDN

**Decision:** Cloudflare (Free tier minimum, Pro recommended)

**Configuration:**
- SSL/TLS: Full (Strict)
- Always Use HTTPS: Enabled
- HSTS: Enabled (max-age=31536000, includeSubDomains, preload)
- Auto-minify CSS/JS/HTML: Enabled
- Polish (image optimization): Enabled
- WAF: Block `/xmlrpc.php`; rate-limit login; WordPress managed ruleset (Pro)
- Cache bypass: `/winkelmand/*`, `/afrekenen/*`, `/mijn-account/*`, `/wp-admin/*`, WC AJAX endpoints

### 3.17 Performance

**Performance Budgets (Hard Gates at QA):**

| Metric | Target | Tool |
|---|---|---|
| LCP | < 2.5s | PSI, Lighthouse |
| FID | < 100ms | PSI, Lighthouse |
| CLS | < 0.1 | PSI, Lighthouse |
| INP | < 200ms | PSI, Chrome UX Report |
| TTFB | < 600ms | WebPageTest |
| Total Page Weight (Mobile) | < 1.5 MB | WebPageTest |
| Total Page Weight (Desktop) | < 3 MB | WebPageTest |
| Speed Index | < 3.4s | Lighthouse |
| PSI Mobile | 90+ | PSI |
| PSI Desktop | 95+ | PSI |
| Lighthouse Accessibility | 100 | Lighthouse |

**Performance Implementation:**
- Page cache: FlyingPress
- Object cache: Redis
- Browser cache: 1 year for versioned static assets
- Critical CSS: Inlined in `<head>`, auto-generated by FlyingPress
- Non-critical CSS: Deferred loading
- JavaScript: `defer` attribute; no render-blocking JS
- jQuery: Removed unless WooCommerce requires it; no jQuery Migrate
- Fonts: Self-hosted Open Sans (subset: Latin + Dutch diacritics); `font-display: swap`; preloaded
- Images: WebP via `<picture>`; explicit dimensions; lazy below fold; `fetchpriority="high"` on LCP
- CDN: Full-page caching, Polish, auto-minify
- Database: Clean — no old revisions, no spam, no transient garbage

**Post-Launch Performance Monitoring:** Weekly PSI API checks. Alert if PSI mobile drops below 90. Performance tested on staging before every plugin/theme update.

### 3.18 Monitoring

**Decision:** UptimeRobot (free tier)

**Alerts configured for:**
- Downtime > 1 minute → Developer + Client
- SSL expiry < 30 days → Developer
- Backup failure → Developer
- Disk usage > 80% → Developer
- Malware detected (Wordfence) → Developer

**Logs:** PHP error log (30 days), Wordfence security (90 days), Gravity Forms entries (12 months), WooCommerce (7 years), Post SMTP email log (90 days), backup logs (12 months).

---

## 4. Decision Log

### Decision D-001: Custom Hybrid Block Theme

| Attribute | Value |
|---|---|
| **Decision** | Custom hybrid block theme (`theme.json` + PHP templates + Block Editor), not FSE, not GeneratePress/Kadence |
| **Context** | Theme selection was ambiguous: "Custom block-based theme (FSE-compatible) OR GeneratePress Pro / Kadence Pro." The ambiguity was flagged as BLOCKING (ARR B02). |
| **Alternatives** | (A) GeneratePress Pro + GenerateBlocks — faster time-to-market but constrains design system. (B) Kadence Pro — similar to GeneratePress. (C) True FSE — HTML templates, higher risk, less mature. |
| **Pros** | Full control over design system; content portable across any theme; no third-party lock-in; all templates in version control; theme.json provides Block Editor design token integration |
| **Cons** | More development effort (already estimated in Sprint 1); all templates must be custom-built; requires PHP + WordPress template hierarchy knowledge |
| **Final Decision** | Custom hybrid block theme |
| **Justification** | The Sprint 1 plan already estimates E-INFRA-06 (13 points) and E-INFRA-08 (8 points) for theme foundation and design system. Switching to GeneratePress would invalidate these estimates and require re-planning. The custom theme approach matches the project's architectural principle P2 (No Page Builder Lock-In) and P3 (Content Portability). |

### Decision D-002: No Page Builders

| Attribute | Value |
|---|---|
| **Decision** | Native Block Editor only. All third-party page builders are banned. |
| **Context** | The current site uses Divi, which is 10+ versions behind and is a primary source of technical debt. Content is trapped in `[divi_shortcode]` format. |
| **Alternatives** | (A) Keep Divi but update — rejected because Divi shortcodes trap content. (B) Elementor — rejected because it creates the same lock-in problem. (C) WPBakery/Beaver Builder — rejected for same reason. |
| **Pros** | Content stored as standard HTML (portable); zero shortcode dependency; future-proof; official WordPress editor |
| **Cons** | Less drag-and-drop flexibility; fewer pre-built widgets; learning curve for client (mitigated by block patterns + Beheergids) |
| **Final Decision** | Native Block Editor ONLY |
| **Justification** | This is a hard constraint (Development Constraint DC01). Any page builder would recreate the technical debt the rebuild is meant to eliminate. |

### Decision D-003: Rank Math Pro over Yoast SEO Premium

| Attribute | Value |
|---|---|
| **Decision** | Rank Math Pro |
| **Context** | Both are capable SEO plugins. The spec listed both as options (ARR SWA-03). |
| **Alternatives** | Yoast SEO Premium — equally capable, but requires separate redirect plugin for 301 management. |
| **Pros** | Built-in redirect manager (no separate plugin); built-in 404 monitor; richer free tier; more granular schema controls; one less plugin to manage |
| **Cons** | Different UI than Yoast (familiarity trade-off); slightly younger product |
| **Final Decision** | Rank Math Pro |
| **Justification** | Built-in redirect manager and 404 monitor reduce plugin count and configuration surface area. The redirect map is a critical SEO preservation deliverable — having it integrated into the SEO plugin simplifies maintenance. |

### Decision D-004: FlyingPress over WP Rocket

| Attribute | Value |
|---|---|
| **Decision** | FlyingPress |
| **Context** | Both are premium caching plugins with similar feature sets. The spec listed both (ARR SWA-04). |
| **Alternatives** | WP Rocket — equally capable, more established, but requires separate unused CSS service. |
| **Pros** | Built-in unused CSS removal; stronger Core Web Vitals optimization; competitive pricing |
| **Cons** | Slightly less established than WP Rocket; smaller user community |
| **Final Decision** | FlyingPress |
| **Justification** | Built-in unused CSS removal simplifies the caching stack. This aligns with the performance budgets (PSI 90+ mobile) that require aggressive CSS optimization. |

### Decision D-005: Hybrid Block Theme over FSE

| Attribute | Value |
|---|---|
| **Decision** | Hybrid block theme (`theme.json` + PHP templates), NOT Full Site Editing |
| **Context** | The spec referenced both "FSE-compatible" and PHP template files (e.g., `front-page.php`, `page-service.php`). These are contradictory — a true FSE theme uses HTML block templates, not PHP. ARR CMS-01 flagged this as BLOCKING. |
| **Alternatives** | (A) True FSE — all templates as HTML files in `/templates/`, full Site Editor. (B) Classic PHP theme — no `theme.json`, traditional PHP templates only. |
| **Pros** | PHP templates provide predictable, unbreakable layouts; `theme.json` provides design token integration with Block Editor; clients cannot accidentally restructure service page layouts; proven approach in the WordPress ecosystem |
| **Cons** | Not the "newest" WordPress approach; template changes require developer (PHP skills); templates not editable via Site Editor |
| **Final Decision** | Hybrid block theme |
| **Justification** | PHP templates guarantee consistent layouts across all service pages. The Block Editor controls content areas (`the_content()`), while PHP controls structural layout (Hero position, Cross-Sell section, CTA placement). This separation of concerns prevents clients from accidentally removing critical conversion elements. |

### Decision D-006: hds_testimonial CPT — Non-Public

| Attribute | Value |
|---|---|
| **Decision** | Register `hds_testimonial` with `public => false`, `publicly_queryable => false` |
| **Context** | The CPT slug `referenties` conflicts with the existing Page `/referenties/` (P13). ARR CMS-02 flagged this as BLOCKING. |
| **Alternatives** | (A) Change CPT slug to `getuigenis` — avoids conflict but creates unnecessary single/archive views. (B) Remove CPT entirely — reduces flexibility for querying testimonials. |
| **Pros** | No URL conflict; no unnecessary archive pages; no duplicate content risk; testimonials are block-queried only |
| **Cons** | No individual testimonial URLs (not needed — testimonials display on the Referenties page only) |
| **Final Decision** | `public => false` with block-only querying |
| **Justification** | Testimonials exist solely to be displayed on the Referenties page (P13). They do not need their own URLs. Making the CPT non-public is the cleanest solution — zero conflict, zero risk. |

### Decision D-007: Company Information in Theme Customizer

| Attribute | Value |
|---|---|
| **Decision** | Store company information (address, phone, email, KVK, BTW, social URLs, hours) in Theme Customizer as `theme_mod` values |
| **Context** | NAP (Name, Address, Phone) must be consistent across footer, contact page, and LocalBusiness schema. A single source of truth eliminates inconsistency. |
| **Alternatives** | (A) WordPress Options page — equivalent but requires more code. (B) Hardcoded in theme — violates DRY; client cannot update. (C) ACF Options page — requires ACF plugin. |
| **Pros** | Built-in WordPress feature; live preview in Customizer; single source of truth; no additional plugin dependency; `get_theme_mod()` is performant |
| **Cons** | Fields must be registered manually (done in `inc/customizer.php`); no rich-text fields for opening hours (textarea used) |
| **Final Decision** | Theme Customizer |
| **Justification** | The Customizer is the standard WordPress mechanism for site-wide settings. It requires no additional plugins, provides a live preview, and integrates cleanly with `get_theme_mod()` calls in templates and schema generation. |

### Decision D-008: Single wp-config.php with Environment Detection

| Attribute | Value |
|---|---|
| **Decision** | Use a single `wp-config.php` (implemented as `wp-config-env.php`) that detects environment via `WP_ENV` environment variable |
| **Context** | Three environments (local, staging, production) need different debug, caching, and URL settings. Multiple config files drift over time. |
| **Alternatives** | (A) Separate `wp-config-local.php`, `wp-config-staging.php`, `wp-config-production.php` — more files to maintain. (B) Conditional logic in `wp-config.php` — same approach but less structured. |
| **Pros** | Single file to maintain; environment auto-detected; `WP_DEBUG` auto-set per environment; `WP_ENVIRONMENT_TYPE` correctly set; `FORCE_SSL_ADMIN` auto-enabled on non-local |
| **Cons** | Slightly more complex `wp-config.php`; requires environment variable to be set on each environment |
| **Final Decision** | Single config with `WP_ENV` detection |
| **Justification** | This is implemented in Epic 1 as `wp-config-env.php`. It reads `WP_ENV` (values: `local`, `staging`, `production`) and configures debug, caching, SSL, and URL settings accordingly. This prevents configuration drift between environments. |

### Decision D-009: Custom Blocks for Dynamic Data

| Attribute | Value |
|---|---|
| **Decision** | Use Block Patterns for static layouts; Custom Blocks (with `render_callback`) only when data is queried dynamically from the database |
| **Context** | The spec lists 16 block patterns and 4 custom blocks. The boundary between pattern and block must be clear. |
| **Alternatives** | (A) Everything as custom blocks — more flexible but over-engineered. (B) Everything as patterns — insufficient for dynamic data. |
| **Pros** | Clear rule: dynamic data → custom block; static layout → block pattern. Patterns are simpler (just HTML), custom blocks handle PHP queries |
| **Cons** | Custom blocks require JavaScript registration and PHP render callbacks — more development effort. But only 4 are needed. |
| **Final Decision** | Patterns for static, custom blocks for dynamic |
| **Justification** | The 4 custom blocks (`hds/service-card`, `hds/testimonial`, `hds/job-listing`, `hds/contact-info`) each query the database. Everything else is a pre-built layout (pattern). This rule prevents over-engineering. |

### Decision D-010: No WP-Cron — Server Cron Instead

| Attribute | Value |
|---|---|
| **Decision** | Disable WP-Cron (`define('DISABLE_WP_CRON', true)`) and replace with server-level cron calling `wp-cron.php` every 15 minutes |
| **Context** | WP-Cron fires on every page load (performance hit) or may not fire reliably on low-traffic sites (scheduled tasks delayed). ARR WBP-03 identified this gap. |
| **Alternatives** | (A) Keep WP-Cron — simpler but causes performance issues and unreliable scheduling. |
| **Pros** | Predictable execution; no performance impact on page loads; managed hosts handle this automatically |
| **Cons** | Requires server cron configuration (managed hosts handle this; Docker environment must set it up) |
| **Final Decision** | Server-level cron |
| **Justification** | Managed WordPress hosts (Kinsta, WP Engine, Cloud86) handle this automatically. For local Docker, it can be configured in the PHP container. Performance improvement is measurable on every page load. |

### Decision D-011: Dutch-Specific Translations Even for Single-Language Site

| Attribute | Value |
|---|---|
| **Decision** | All user-facing strings use WordPress internationalization functions (`__()`, `_e()`) with textdomain `hds`, even though the site is single-language Dutch |
| **Context** | The site launches as nl-NL only. Internationalization adds development overhead for a single-language site. |
| **Alternatives** | (A) Hardcoded Dutch strings — simpler but not forward-compatible. |
| **Pros** | Forward-compatible if international expansion occurs; consistent with WordPress coding standards; enables future multilingual without rewriting all templates |
| **Cons** | Slightly more verbose code; `.pot` file must be maintained |
| **Final Decision** | Internationalize all strings |
| **Justification** | This is standard WordPress development practice and is mandated by the PHP coding standards (WordPress-Core). The overhead is minimal and the forward-compatibility value is high. |

### Decision D-012: FAQ via Yoast/Rank Math FAQ Block — No CPT

| Attribute | Value |
|---|---|
| **Decision** | FAQ content is managed via Yoast/Rank Math FAQ Block on a standard Page at `/veelgestelde-vragen/` (P18). No `hds_faq` CPT. |
| **Context** | The architecture initially included an `hds_faq` CPT. Multiple documents referenced it (ADR §3.4, SAD §5.2, RTM §8). The PVR identified this as contradictory (RTM-I02) and the FAR flagged it as BLOCKING (IC-05). |
| **Alternatives** | (A) `hds_faq` CPT — adds a custom block, CPT admin UI, and potential slug conflicts. (B) Manual FAQ content in Block Editor — no structure, no auto-schema. |
| **Pros** | Editors edit one page (simpler UX). Yoast/Rank Math FAQ blocks auto-generate FAQPage schema. No CPT maintenance overhead. No custom block needed. Consistent with BKLG story E-SUPPORT-07. |
| **Cons** | FAQ items cannot be individually queried or reused across pages (not needed at launch). |
| **Final Decision** | FAQ Block on standard Page, no CPT |
| **Justification** | The Yoast/Rank Math FAQ Block provides structured FAQ data, auto-schema generation, and a simple editing experience. The additional complexity of a CPT is not justified for 10-15 FAQ items on a single page. |

### Decision D-013: Gevelreiniging as Canonical Service Name

| Attribute | Value |
|---|---|
| **Decision** | The facade maintenance service is named "Gevelreiniging" (not "Gevelonderhoud"). Page title, navigation label, and H1 all use "Gevelreiniging". Homepage icon may abbreviate to "Gevel". |
| **Context** | The old site used three different labels for the same content: nav said "Gevelonderhoud", homepage icon said "GEVEL", URL slug is `/gevelreiniging/`. This inconsistency was documented across SRC-01, SRC-02, SRC-06. Finding F08 in MPS-001 resolved this. |
| **Alternatives** | (A) "Gevelonderhoud" — used in old site nav but doesn't match URL slug. (B) "Gevelreiniging" — matches URL slug. |
| **Pros** | Consistent naming across title, nav, URL, schema. No user confusion. Matches the existing URL (preserved for SEO). |
| **Cons** | Content writer must know the old title was different (addressed via content migration note). |
| **Final Decision** | "Gevelreiniging" as the canonical term |
| **Justification** | The URL slug `/gevelreiniging/` is the canonical identifier. The page title should match the slug. Old content is preserved; only the title changes. |

### Decision D-014: Service Page Ordering via menu_order

| Attribute | Value |
|---|---|
| **Decision** | Service pages are ordered by `menu_order` field in navigation and service card grids. Canonical order: (1) Reguliere Schoonmaak, (2) Glasbewassing, (3) Gevelreiniging, (4) Vloeronderhoud, (5) VVE Service, (6) Oplevering Schoonmaak, (7) Industriele Schoonmaak. |
| **Context** | The spec defined 7 service pages but did not define their display order (FAR IA-01). Navigation order affects user perception of importance and should be intentional, not arbitrary. |
| **Alternatives** | (A) Alphabetical — puts "Gevelreiniging" before "Glasbewassing", not ideal. (B) By page ID — arbitrary. |
| **Pros** | Primary service (regular cleaning — highest demand) appears first. Natural progression from interior (cleaning) to exterior (glass, facade) to specialty (floor, VVE, delivery, industrial). |
| **Cons** | Requires setting `menu_order` on each Page. One-time setup. |
| **Final Decision** | Ordered by menu_order with the canonical sequence above |
| **Justification** | Reguliere Schoonmaak is the primary service line (the page that was HTTP 404 on the old site). It should appear first. The sequence follows a logical client journey: general cleaning → specialized exterior → floor care → HOA → project-based. |

### Decision D-015: Conditional Section Empty States

| Attribute | Value |
|---|---|
| **Decision** | Conditional homepage and page sections that depend on client-provided content (logos, testimonials, blog posts) must HIDE entirely when empty — not render with placeholder text or empty wrappers. |
| **Context** | The ARR identified missing empty states (EC05, B11) for: Client Logo Carousel, Testimonial Block, Latest Blog Posts. Without this decision, sections render as empty containers, padding, or borders — looking broken. |
| **Alternatives** | (A) Show placeholder text ("Binnenkort verschijnen hier...") — keeps layout but looks unfinished. (B) Hide section entirely — clean layout but content gaps visible. |
| **Pros** | Clean visual presentation. Sections appear only when content exists. Professional appearance from day one. |
| **Cons** | Client may not realize the capability exists until content is provided (mitigated by Beheergids documentation). |
| **Final Decision** | Hide empty sections with `display: none` on the section wrapper |
| **Justification** | A B2B service website must look professional. Empty sections communicate neglect. Hiding them until content exists ensures the site always looks complete. The decision is reversible — when content is provided, sections appear automatically. |

### Decision D-016: Breadcrumbs Follow URL Hierarchy, Not IA Hierarchy

| Attribute | Value |
|---|---|
| **Decision** | Breadcrumbs follow the URL hierarchy for consistency. Since all pages use a flat URL structure (depth 1), breadcrumbs for all pages except blog posts and products are: Home > [Page Name]. No intermediate levels (e.g., no Home > Glas & Gevel > Glasbewassing). |
| **Context** | FAR IA-02 identified ambiguity: should breadcrumbs reflect the Information Architecture (category landing pages as parents) or the URL hierarchy (flat)? A mismatch creates schema validation errors. |
| **Alternatives** | (A) IA-based breadcrumbs: Home > Glas & Gevel > Glasbewassing — creates mismatch with flat URL `/glasbewassing/`. (B) URL-based breadcrumbs: Home > Glasbewassing — matches canonical URL. |
| **Pros** | Schema validation passes (URL matches breadcrumb). Consistent with flat URL strategy. Simpler implementation (no parent-page detection logic). |
| **Cons** | Does not reflect the IA grouping that category landing pages provide. Category landings serve SEO purposes, not structural navigation. |
| **Final Decision** | URL-based flat breadcrumbs |
| **Justification** | Google validates breadcrumb schema against the canonical URL. A mismatch (breadcrumb showing a parent page that doesn't appear in the URL) causes a schema error. Flat breadcrumbs match flat URLs — simplest correct implementation. |

---

## 5. Information Architecture Alignment

### 5.1 How the Architecture Supports Navigation

**Primary Navigation (Desktop):**
The architecture supports a 4-item primary navigation with dropdowns. The `register_nav_menus()` call in `functions.php` registers `primary` as the main menu location. Dropdown behavior is implemented in CSS (desktop hover) and JavaScript (mobile accordion). The navigation structure is:

```
[LOGO]    DIENSTEN v    OVER HDS v    LUCHTREINIGING v    CONTACT    [TEL]
```

**Theme Implementation:**
- `parts/header.php` renders the navigation via `wp_nav_menu()` with `theme_location => 'primary'`
- Footer navigation uses 4 separate menu locations: `footer-services`, `footer-about`, `footer-airfixr`, `footer-legal`
- All menus are managed via Appearance → Menus by the client
- Mobile: hamburger toggle with `aria-expanded` management in `assets/js/main.js`

### 5.2 How the Architecture Supports Services

**Service Pages (P02–P08):** Standard WordPress Pages with the "Service" template applied. The `page-templates/page-service.php` template provides:
- Breadcrumbs (`hds_breadcrumbs()`)
- Hero section with H1, subtitle (from `hds_subtitle` custom field), optional background image (from `hds_hero_image`), and CTA button (from `hds_cta_override` or default)
- Content area (`the_content()`) — all content managed via Block Editor
- CTA Banner section

**Design Rationale:** Using Pages (not a CPT) for services keeps the admin interface familiar and avoids unnecessary complexity. The Service template enforces a consistent layout across all 7 services. Custom fields handle per-service variations (icon, subtitle, hero image).

### 5.3 How the Architecture Supports Landing Pages

**Category Landing Pages (P09, P10):** Pages with "Category Landing" template. `page-templates/page-category-landing.php` provides Hero → Intro → Service Card Grid → CTA Banner. Content via Block Editor. Minimum 500 words.

### 5.4 How the Architecture Supports SEO

The architecture supports SEO at every layer:
- **WordPress:** Clean permalinks (`/%postname%/`), `title-tag` theme support, semantic HTML5 markup
- **Rank Math Pro:** Meta titles/descriptions, XML sitemaps, robots.txt, canonical URLs, Open Graph, Twitter Cards, auto-schema, redirect manager
- **Theme:** Breadcrumbs (visible + schema), `hreflang="nl"`, custom JSON-LD (LocalBusiness, Service, FAQPage, JobPosting)
- **Performance:** PSI 90+ is an SEO ranking factor; the caching + CDN + image optimization stack is built for this
- **Content:** Minimum word counts enforced; H1-H2-H3 hierarchy; alt text on all images

### 5.5 How the Architecture Supports the Blog

**Blog:** Standard WordPress Posts. Category base set to `kennisbank` (not `category`). Permalink: `/kennisbank/{slug}/` (no date prefix — permanent URLs). Blog index via `archive.php`. Single posts via `single.php`. Pagination via `the_posts_pagination()`.

### 5.6 How the Architecture Supports Downloads

**Downloads Page (P15):** Standard Page. PDFs migrated from legacy domain (`hds-onderhoudsdiensten.nl`) to primary domain media library. Internal links updated. Legacy domain configured with 301 redirects to new PDF URLs.

### 5.7 How the Architecture Supports Contact

**Contact Page (P16):** Page with "Contact" template. `page-templates/page-contact.php` provides a two-column layout:
- Left column (60%): Content area for Gravity Forms shortcode (GF-1)
- Right column (40%): Contact Info Block reading from Theme Customizer

Conditional rendering: address, KVK, BTW, and opening hours sections only appear if the corresponding Customizer fields have values.

### 5.8 How the Architecture Supports Careers

**Vacatures Page (P14):** Page querying `hds_vacancy` CPT via `hds/job-listing` custom block. Each vacancy card is toggle-to-expand. Application form (GF-3) per vacancy. JobPosting structured data per vacancy.

### 5.9 How the Architecture Supports WooCommerce

**Shop:** Standard WooCommerce with Dutch configuration (EUR, excl. BTW, 21% tax). Shop base at `/winkel/`. Cart at `/winkelmand/`. Checkout at `/afrekenen/`. Account at `/mijn-account/`. Payment gateway: Mollie (iDEAL, Bancontact, cards, PayPal, SEPA) + Bank Transfer fallback. **Assumption:** Client confirms Mollie as payment gateway (MI-15).

---

## 6. Technical Standards

### 6.1 Coding Standards

**PHP:** WordPress Coding Standards enforced via PHP_CodeSniffer with `WordPress-Core`, `WordPress-Docs`, `WordPress-Security`, `WordPress-PHP`, `WordPress-DB`, and `WordPress-WP` rulesets. PHP compatibility: 8.2+. Short array syntax allowed. Yoda conditions for equality checks. Strict comparisons (`===`). All output escaped. All inputs sanitized. Nonces on all custom forms. Prepared SQL statements. No `eval()`, no `base64_decode()`. All strings internationalized with textdomain `hds`.

**CSS:** BEM-like naming for custom components (`.hds-component__element--modifier`). CSS custom properties for design tokens (`var(--hds-color-primary)`). Mobile-first media queries (`min-width`). No `!important` except for utility classes. No ID selectors for styling. Maximum nesting depth: 3 levels.

**JavaScript:** Vanilla JavaScript (no jQuery dependency unless WooCommerce requires it). ES6+ syntax. No inline scripts (use `wp_add_inline_script` for config data). Event delegation for dynamic elements. Progressive enhancement: core functionality works without JavaScript. No `console.log()` in production code.

### 6.2 Naming Conventions

| Category | Convention | Example |
|---|---|---|
| PHP functions | `hds_` prefix | `hds_setup()`, `hds_breadcrumbs()` |
| CSS classes | `.hds-component__element--modifier` | `.hds-card__title`, `.hds-card--featured` |
| Custom fields / post meta | `hds_` prefix, lowercase underscores | `hds_subtitle`, `hds_hero_image` |
| CPT keys | `hds_` prefix | `hds_testimonial`, `hds_vacancy` |
| CPT rewrite slugs | Dutch descriptive | `vacatures`, `faq` |
| Block names | `hds/block-name` | `hds/service-card`, `hds/testimonial` |
| Block patterns | `hds/pattern-name` | `hds/hero-section`, `hds/cta-banner` |
| Image filenames | lowercase-hyphens-dutch-keywords | `glasbewassing-kantoor-bergen-op-zoom.webp` |

### 6.3 Folder Structure

```
wp-content/themes/hds/
├── theme.json                          # Design tokens, block styles, template declarations
├── style.css                           # Theme metadata
├── functions.php                       # Bootstrap: require inc/*.php
├── index.php                           # Fallback template
├── assets/
│   ├── css/
│   │   ├── main.css                    # Production stylesheet
│   │   └── editor.css                  # Editor-specific styles
│   ├── js/
│   │   └── main.js                     # Navigation, progressive enhancements
│   ├── images/                         # Theme images (logo, icons)
│   └── fonts/                          # Self-hosted Open Sans
├── inc/
│   ├── cpts.php                        # CPT registration
│   ├── customizer.php                  # Company Information Customizer section
│   ├── helpers.php                     # Template functions (breadcrumbs, getters)
│   ├── security.php                    # Security hardening
│   └── patterns.php                    # Block pattern registration
├── parts/
│   ├── header.php                      # Header template part
│   └── footer.php                      # Footer template part
├── page-templates/
│   ├── page-service.php                # P02–P08
│   ├── page-category-landing.php       # P09, P10
│   ├── page-about.php                  # P11, P12
│   ├── page-contact.php                # P16
│   ├── page-quote.php                  # P17
│   ├── page-faq.php                    # P18
│   └── page-legal.php                  # P19–P22
├── front-page.php                      # P01
├── page.php                            # Default page
├── single.php                          # Blog post
├── archive.php                         # Blog index
├── search.php                          # Search results
└── 404.php                             # Custom 404
```

### 6.4 Asset Structure

**CSS:** One production file (`main.css`) containing reset, typography, layout, components, and block styles. Organized by section comments. Minified for production via esbuild.

**JavaScript:** One production file (`main.js`) containing navigation toggle, keyboard accessibility, and progressive enhancements. Minified for production.

**Fonts:** Self-hosted Open Sans (WOFF2). Regular (400), Semi-Bold (600), Bold (700). Subset to Latin + Dutch diacritics. `font-display: swap`. Preloaded in `<head>`.

### 6.5 Media Organization

WordPress default year/month folder structure. No additional media library organization plugin at launch. Images named descriptively at upload. Alt text required for all non-decorative images.

### 6.6 Slug Conventions

| Rule | Example |
|---|---|
| Dutch, lowercase, hyphens | `/reguliere-schoonmaak/` |
| No diacritics in slugs | `/industriele-schoonmaak/` (not `industriële`) |
| Max depth 1 from root | `/glasbewassing/` (not `/diensten/glasbewassing/`) |
| Blog posts: `/kennisbank/{slug}/` | No date prefix |

### 6.7 URL Conventions

| Rule | Specification |
|---|---|
| Protocol | HTTPS only; HTTP → 301 |
| www | Non-www canonical; www → 301 |
| Trailing slash | Consistently WITH trailing slash; no-slash → 301 |
| Language | Dutch, lowercase, hyphens |
| Extensions | No `.html`, `.php`, `.asp` — clean URLs only |
| Query params | No `?page_id=N` — all pages use descriptive slugs |
| Blog URLs | `/kennisbank/{slug}/` — permanent, no date prefix |

### 6.8 Redirect Conventions

| Rule | Specification |
|---|---|
| Type | 301 (permanent) only — never 302/307 |
| Chains | Zero redirect chains (A → B → C is forbidden) |
| Implementation | Rank Math Pro redirect manager |
| Testing | Every redirect tested manually before launch |

**Redirect Map:**
| Old URL | New URL |
|---|---|
| `/glasbewassing` (no slash) | `/glasbewassing/` |
| `/vve` | `/vve-service/` |
| `/vve/` | `/vve-service/` |
| `/?page_id=318` | `/reguliere-schoonmaak/` |
| `http://.../*` | `https://.../*` |
| `http://www.../*` | `https://.../*` |
| `https://www.../*` | `https://.../*` |
| `/2015/06/29/hallo-wereld/` | 410 Gone |
| `/2015/08/25/kwaliteit-veiligheid/` | 410 Gone |

---

## 7. Performance Targets

### 7.1 Core Web Vitals (Hard Gates)

| Metric | Target |
|---|---|
| LCP (Largest Contentful Paint) | < 2.5s |
| INP (Interaction to Next Paint) | < 200ms |
| CLS (Cumulative Layout Shift) | < 0.1 |
| TTFB (Time to First Byte) | < 600ms |

### 7.2 Lighthouse Scores (Hard Gates)

| Category | Target |
|---|---|
| Performance (Mobile) | 90+ |
| Performance (Desktop) | 95+ |
| Accessibility | 100 |
| Best Practices | 100 |
| SEO | 100 |

### 7.3 Caching Strategy

4-layer cache: Browser (1yr versioned) → Cloudflare CDN (full-page + static) → FlyingPress (page cache) → Redis (object cache) → PHP OPCache.

**Purge triggers:** Post/page update → FlyingPress → Cloudflare API. WC product updated → purge product + shop + category. Bulk content change → purge all.

### 7.4 Image Strategy

**Pipeline:** Upload JPG/PNG → Compress (85+) → Convert WebP → Generate sizes → `<picture>` with WebP + fallback → `srcset` + `sizes` → `loading="lazy"` (below fold) → `fetchpriority="high"` (LCP) → CDN cache.

### 7.5 Lazy Loading

- Images below fold: `loading="lazy"`, `decoding="async"`
- LCP image: `fetchpriority="high"`, no lazy loading
- iframes (maps, videos): `loading="lazy"`
- Fonts: `font-display: swap`
- JavaScript: `defer` attribute

### 7.6 Critical CSS

Auto-generated by FlyingPress. Inlined in `<head>`. Non-critical CSS deferred. Manual verification: WebPageTest filmstrip — above-the-fold content must render in < 2s on Moto G4, 3G Fast.

---

## 8. SEO Architecture

### 8.1 Metadata

**Per Page:** Unique `<title>` (50–60 chars: [Page Title] + "— HDS Onderhoudsdiensten") and `<meta description>` (150–160 chars: keyword + location + value proposition + CTA). Managed via Rank Math Pro. Zero empty or duplicate metadata at launch — verified by Screaming Frog.

### 8.2 Structured Data (9 Types)

| Schema Type | Pages | Implementation |
|---|---|---|
| `WebSite` with SearchAction | All | Rank Math auto |
| `WebPage` | All | Rank Math auto |
| `BreadcrumbList` | All inner pages | Rank Math + theme |
| `LocalBusiness` (HomeAndConstructionBusiness) | Home, Contact, Over HDS | Custom JSON-LD in theme |
| `Service` | P02–P08 | Custom JSON-LD per page |
| `FAQPage` | P18 | Rank Math auto from FAQ blocks |
| `Product` | P25 (x14) | WooCommerce auto |
| `JobPosting` | Per vacancy on P14 | Custom JSON-LD per vacancy |
| `Organization` with `sameAs` | Home | Custom JSON-LD |

All schema validated via Google Rich Results Test before launch.

### 8.3 Internal Linking

Each service page links to 2–3 related services. Homepage service card grid links to all 7 services. Navigation links to all pages. Footer links to all pages. Zero orphan pages at launch — verified by Screaming Frog.

### 8.4 Canonical Rules

- Self-referencing canonicals on all pages
- No canonical pointing to a different URL (except paginated archives)
- Trailing slash canonical (no-slash variant 301 redirects before canonical applies)

### 8.5 XML Sitemap

- URL: `/sitemap_index.xml` (HTTP 200, valid XML)
- Sub-sitemaps: `page-sitemap.xml`, `post-sitemap.xml`, `product-sitemap.xml`
- Excluded: attachment pages, author archives, noindex pages (Bedankt, legal), cart, checkout, account

### 8.6 robots.txt

Auto-generated by Rank Math Pro. Correct disallow rules for: `/wp-admin/`, `/wp-includes/`, `/wp-content/plugins/`, query parameters, and blocked endpoints. Verified: returns 200 with valid content.

### 8.7 OpenGraph / Twitter Cards

Rank Math Pro auto-generates for all pages. Custom social share image: 1200×630px branded graphic. Verified via Facebook Sharing Debugger and Twitter Card Validator.

### 8.8 Breadcrumbs

Visible on all inner pages (not Home). Rendered by `hds_breadcrumbs()` in `inc/helpers.php`. Schema BreadcrumbList auto-generated by Rank Math Pro. Breadcrumb follows URL hierarchy (flat: Home → Page Name), not IA hierarchy.

---

## 9. Security Strategy

### 9.1 Authentication

- Custom login URL via Wordfence (not `/wp-admin/` or `/wp-login.php`)
- 2FA via Wordfence on ALL Administrator, Editor, and Shop Manager accounts (no exceptions)
- Login limiting: 3 failed attempts → IP lockout (Wordfence brute force)
- Minimum 12-character passwords (Wordfence enforced)
- Password reset: email-based, max 1/hour/user
- Session timeout: 48 hours; force logout on password change
- Application passwords disabled
- XML-RPC disabled (server-level 403)

### 9.2 Backups

Daily automated full backups (files + database). Retention: 30 daily, 4 weekly, 12 monthly. Offsite cloud storage. Pre-update backup before every plugin/theme/core update. WooCommerce monthly CSV export (7-year retention). Monthly test restore to staging environment. Backup failure → email alert to developer.

### 9.3 Updates

- Minor/patch (WordPress core, plugins, themes): Auto-update enabled
- Major updates: Test on staging first (smoke test: Home, service page, product page, Contact form, WC purchase)
- Monthly maintenance window for updates

### 9.4 Firewall

- Cloudflare WAF: Block `/xmlrpc.php`; rate-limit login; WordPress managed ruleset (Pro plan)
- Wordfence WAF: Application-level firewall; daily malware scan; file integrity monitoring

### 9.5 Spam Protection

- All forms: reCAPTCHA v3 + honeypot field
- Form entries: stored in database; reviewed before action
- Comments: disabled site-wide

### 9.6 Rate Limiting

- Cloudflare: Rate limit on `/wp-login.php` (or custom login URL)
- Wordfence: 3 failed login attempts → IP lockout
- REST API user endpoint blocked

### 9.7 GDPR/AVG Compliance

- **Privacyverklaring:** Published at `/privacyverklaring/`. Drafted by developer, reviewed by Dutch privacy lawyer before launch. Linked from footer on every page and from all form consent checkboxes.
- **Cookiebeleid:** Published at `/cookiebeleid/`. Auto-generated by Complianz Premium. Review auto-content before launch.
- **Algemene Voorwaarden:** Published at `/algemene-voorwaarden/`. Client provides text (MI-16). **Assumption:** Client provides T&C text; if not, page published as placeholder.
- **Disclaimer:** Published at `/disclaimer/`.
- **Data Processing Agreement:** Signed with hosting provider.
- **Form consent:** All form consent checkboxes unchecked by default. Link to privacyverklaring.
- **Data retention:** Contact/quote entries auto-delete after 12 months. WooCommerce orders retained 7 years.
- **Right to erasure:** Process documented for deleting personal data from WP users, form entries, customer data, and backups.
- **Breach notification:** Process documented for notifying Autoriteit Persoonsgegevens within 72 hours.

### 9.8 Cookie Compliance

Complianz Premium configured for Dutch market:
- Banner on first visit: "Accepteren", "Weigeren", "Instellingen aanpassen"
- No non-functional cookies loaded before consent (verified via DevTools Network tab)
- Per-category consent: functional (always on), statistics, marketing (off by default)
- Consent logged (timestamp, anonymized IP, consent string)
- GTM Consent Mode v2 integration: marketing tags deferred until consent
- Cookiebeleid page auto-generated and linked from banner + footer
- reCAPTCHA badge positioned so it is not obscured by cookie banner

---

## 10. Deployment Strategy

### 10.1 Development Workflow

```
Local Development (hds.local)
  → Git commit + push to staging branch
    → GitHub Actions auto-deploy to Staging
      → Staging: Developer QA + Client review
        → Client sign-off
          → Merge staging → main
            → GitHub Actions auto-deploy to Production
              → Clear caches (FlyingPress + Cloudflare API + Redis)
                → Smoke tests
```

### 10.2 Staging Environment

- **URL:** `staging.helderduidelijkschoon.nl`
- **Access:** Password-protected via `.htaccess` or hosting-level authentication
- **Indexing:** `<meta name="robots" content="noindex, nofollow">` on all pages
- **Debug:** `WP_DEBUG=true`, `WP_DEBUG_LOG=true`, `WP_DEBUG_DISPLAY=false`
- **Stack:** Identical PHP, WordPress, and plugin versions to production
- **Database:** Copy of production (anonymized for GDPR if contains personal data)

### 10.3 Production Environment

- **URL:** `helderduidelijkschoon.nl`
- **Access:** Public; Developer + Client admin access
- **Debug:** `WP_DEBUG=false`, `WP_DEBUG_LOG=true`, `WP_DEBUG_DISPLAY=false`
- **SSL:** HTTPS enforced with HSTS; HTTP → 301; www → 301

### 10.4 Release Process

1. All changes committed and pushed to Git
2. Staging tested and approved by client
3. Full backup taken (auto-triggered)
4. Merge staging → main
5. GitHub Actions deploys to production
6. Clear all caches (FlyingPress, Cloudflare API, Redis)
7. Smoke test: Homepage, 1 service page, 1 product page, Contact form, mobile view
8. Verify GA4 real-time reports showing traffic
9. Verify GSC sitemap status

### 10.5 Rollback Strategy

- Pre-deploy backup verified (restored to test environment)
- Rollback: restore backup → deploy to production
- Time objective: < 30 minutes for plugin/theme updates; < 2 hours for complete site failure
- Old site backup verified before old site takedown at launch

---

## 11. Future Scalability

### 11.1 Additional Services

**Supported by current architecture:** New services are added as standard WordPress Pages with the "Service" template. No code changes required. Add to navigation menu. Add cross-links from related services. Add to homepage service card grid (queried automatically from all pages with Service template).

### 11.2 More Locations

**Supported with changes:** Location-specific landing pages (`/schoonmaakbedrijf-bergen-op-zoom/`, etc.) can be added as Pages. Each would need location-specific content, LocalBusiness schema, and internal linking. The architecture supports this but it is not built in Sprint 2. **Assumption:** Client confirms service area (MI-05) before location pages are built.

**Multi-location (franchise model):** Would require a multi-location CPT or WordPress multisite. Not supported by current architecture. If needed in 18+ months, the block-based content is portable to a multisite or headless frontend.

### 11.3 Multiple Languages

**Supported with changes:** All strings are internationalized with textdomain `hds`. Adding a second language requires: (a) WPML or Polylang plugin, (b) `hreflang` tags, (c) translated content for all 32+ pages. The architecture does not block this but it is not implemented. **Assumption:** Client has no international expansion planned in the next 18 months.

### 11.4 Additional Products

**Fully supported:** WooCommerce handles unlimited products. New Airfixr products or entirely new product lines can be added without code changes. Shop categories, attributes, and variations are all standard WooCommerce.

### 11.5 Marketing Pages

**Fully supported:** Landing pages for Google Ads campaigns, special offers, or seasonal promotions can be created as standard Pages. A "Landing Page" template can be added if needed (Hero + Content + CTA, no navigation/footer for conversion-optimized pages). Block Editor makes content creation fast.

### 11.6 Near-Term Scalability (0–6 Months)

- Google Ads landing pages (new template)
- Location-specific service pages (confirmed by MI-05)
- Case study / portfolio pages (new CPT or blog category)
- Newsletter integration (Mailchimp/MailerLite + Gravity Forms signup)
- WhatsApp Business floating button (mobile)
- Online booking system (Calendly/Bookly integration)

### 11.7 Medium-Term (6–18 Months)

- Client self-service portal (custom WP user area)
- Automated quoting engine (multi-step form with pricing)
- Multilingual (WPML/Polylang) — only if international expansion planned
- Review aggregation (Google + Facebook reviews on site)
- Advanced analytics dashboard (Looker Studio)

### 11.8 Architecture Readiness for Growth

| Growth Vector | Architecture Support |
|---|---|
| More content | Block-based content is portable; no shortcode lock-in |
| More traffic | Cloudflare CDN + FlyingPress + Redis handles scaling; vertical hosting upgrade if needed |
| Headless/mobile app | CPTs have REST API endpoints; content not trapped in page builders |
| Complex workflows | Gravity Forms has 40+ integrations; webhooks; API |
| Marketing tags | GTM-based — new tags added without developer intervention |
| Multi-developer | Git-based workflow with branch deployment; code review support |

---

## 12. Risks and Mitigation

### 12.1 Architectural Risks

| ID | Risk | Severity | Likelihood | Mitigation |
|---|---|---|---|---|
| R01 | Client delays providing required information (MI-01 through MI-25) | HIGH | High | Conditional rendering for missing data; address/KVK/BTW sections hidden until provided; early communication of dependencies |
| R02 | Custom theme development takes longer than 13-point estimate (E-INFRA-06) | HIGH | Medium | Theme foundation is scoped to essentials in Sprint 1; patterns and styles can be refined in Sprint 2; parallel development tracks |
| R03 | Gravity Forms email delivery fails due to SMTP misconfiguration | CRITICAL | Medium | SMTP configured in Sprint 1 (E-INFRA-04); test email delivery within 2 minutes as gate check; Post SMTP email log enabled |
| R04 | WooCommerce + Cloudflare cache conflict causes cart/checkout issues | HIGH | Low | Cache bypass rules configured for all WC pages; verified via response headers before launch |
| R05 | hds_testimonial CPT slug conflict with /referenties/ page | HIGH | None (resolved) | CPT set to `public => false`; testimonials queried via custom block only |
| R06 | DNS TTL not lowered before launch — propagation delay | MEDIUM | Medium | TTL lowered to 300 seconds 24 hours before launch; verified via whatsmydns.net; restored after launch |
| R07 | Backup not verified before old site takedown | CRITICAL | Low | Backup restored to test environment and verified before old site offline |
| R08 | reCAPTCHA v3 blocks legitimate users — no fallback contact | MEDIUM | Low | Honeypot catches most spam; phone number visible as fallback on contact page; optional CAPTCHA bypass for known-good users |
| R09 | Performance degrades post-launch without monitoring | MEDIUM | Medium | Weekly automated PSI checks; alert if mobile drops below 90; staging test before every update |
| R10 | Client edits break page layouts (Block Editor) | MEDIUM | Low | PHP templates lock structural layout; only `the_content()` area is editable; revisions provide rollback |

### 12.2 Information Gaps (Assumptions Requiring Client Input)

| ID | Missing Item | Blocks | Assumption |
|---|---|---|---|
| MI-01 | Physical address | Contact page, footer, schema | Conditional: hidden until provided |
| MI-02 | KVK number | Footer, schema | Conditional: hidden until provided |
| MI-03 | BTW number | Footer, schema | Conditional: hidden until provided |
| MI-04 | Business hours | Contact page, schema | Conditional: hidden until provided |
| MI-05 | Service area | Local SEO, schema | Default: "West-Brabant en Zeeland" from current site tagline |
| MI-06 | Logo vector file | Theme | Placeholder text logo; client must provide SVG/AI/EPS |
| MI-07 | Brand colors | Design system | Default palette in `theme.json`; client can approve or replace |
| MI-08 | Typography | Design system | Default: Open Sans (current site uses it) |
| MI-14 | Shipping costs | WooCommerce | Default: flat rate per class; client to confirm |
| MI-15 | Payment gateway | WooCommerce | Default: Mollie (recommended for Dutch market) |
| MI-16 | Terms & Conditions | Algemene Voorwaarden page | Page published with placeholder if not provided |
| MI-20 | Hosting provider | Everything | Default: managed WordPress hosting (Kinsta/WP Engine/Cloud86) |

---

## 13. Final Architecture Summary

### 13.1 Architecture Blueprint

```
                         [Visitors: B2B Prospects, Job Seekers, Airfixr Buyers]
                              │ HTTPS (HSTS, TLS 1.3)
                              ▼
                         [Cloudflare CDN/WAF]
                              │ DNS, SSL, DDoS, Caching, Polish, WAF Rules
                              │ Cache Bypass: /winkelmand/*, /afrekenen/*, /mijn-account/*, /wp-admin/*
                              ▼
                         [Managed WordPress Hosting]
                              │ PHP 8.2+ | Nginx | Redis | MySQL 8.0+ / MariaDB 10.6+
                              ▼
    ┌────────────────────────────────────────────────────────────────────────────┐
    │                         WordPress 6.7+ (nl_NL)                             │
    │  ┌──────────────────────────────────────────────────────────────────────┐  │
    │  │                    Custom Hybrid Block Theme (hds)                    │  │
    │  │  theme.json → Design Tokens → CSS Custom Properties                  │  │
    │  │  PHP Templates → Structured Layouts (Hero, Content, Cross-Sell, CTA) │  │
    │  │  Block Editor → Content Areas (the_content)                          │  │
    │  │  7 Page Templates | 16 Block Patterns | 4 Custom Blocks              │  │
    │  └──────────────────────────────────────────────────────────────────────┘  │
    │  ┌─────────────────┐ ┌──────────────┐ ┌────────────────┐                   │
    │  │ WooCommerce 9.x+ │ │ Gravity Forms│ │ Rank Math Pro  │                   │
    │  │ 14 Airfixr SKUs  │ │ GF-1,GF-2,   │ │ Meta, Sitemap, │                   │
    │  │ Mollie Payments   │ │ GF-3         │ │ Schema, 301s   │                   │
    │  └─────────────────┘ └──────────────┘ └────────────────┘                   │
    │  ┌─────────────────┐ ┌──────────────┐ ┌────────────────┐                   │
    │  │ FlyingPress      │ │ Wordfence    │ │ Complianz      │                   │
    │  │ Cache + CSS Opt  │ │ WAF, 2FA     │ │ AVG Consent    │                   │
    │  └─────────────────┘ └──────────────┘ └────────────────┘                   │
    │  ┌─────────────────┐ ┌──────────────┐ ┌────────────────┐                   │
    │  │ Post SMTP        │ │ ShortPixel   │ │ Relevanssi     │                   │
    │  │ Email Delivery   │ │ WebP Optim   │ │ Dutch Search   │                   │
    │  └─────────────────┘ └──────────────┘ └────────────────┘                   │
    └────────────────────────────────────────────────────────────────────────────┘
                              │
                              ▼
    ┌────────────────────────────────────────────────────────────────────────────┐
    │  MySQL 8.0+ / MariaDB 10.6+     Offsite Backups (Daily + Retention)        │
    │  Tables: hds_* prefix           GA4 via GTM    GSC Monitoring              │
    │  InnoDB, utf8mb4_unicode_ci     UptimeRobot    Monthly Reporting           │
    └────────────────────────────────────────────────────────────────────────────┘
```

### 13.2 Key Numbers

| Metric | Value |
|---|---|
| Total pages | 32 |
| Page templates | 7 custom + 4 standard |
| Custom Post Types | 3 (hds_testimonial, hds_vacancy, hds_faq) |
| Block patterns | 7 registered in Sprint 1; 16 targeted by Sprint 2 |
| Custom blocks | 4 (hds/service-card, hds/testimonial, hds/job-listing, hds/contact-info) |
| Forms | 3 (Contact, Offerte, Vacature) |
| Plugins (production) | 13 total |
| Schema types | 9 |
| Redirects | 9 rules (7 301 + 2 410) |
| 301 redirects | 7 |
| 410 gone | 2 |
| Navigation menus | 5 locations |
| Customizer fields | 11 |
| Minimum content words (service pages) | 300+ |
| Minimum content words (landing pages) | 500+ |
| Performance target (mobile PSI) | 90+ |
| Accessibility target | WCAG 2.2 AA, Lighthouse 100 |
| Security layers | 6 |
| Cache layers | 4 |
| Deployment environments | 3 (Local, Staging, Production) |
| Total development sprints | 9 |
| Epic 1 stories completed | 7 |

### 13.3 Architecture Principles Recap

| # | Principle | Status |
|---|---|---|
| P1 | Rebuild, Don't Repair | Zero code carried forward |
| P2 | No Page Builder Lock-In | Native Block Editor only |
| P3 | Content Portability | All content as standard blocks |
| P4 | Performance by Default | PSI 90+ mobile / 95+ desktop |
| P5 | Security in Depth | 6-layer security model |
| P6 | Dutch-First | `lang="nl-NL"`, all UI in Dutch |
| P7 | Mobile-First | Mobile-first CSS; 44px+ touch targets |
| P8 | Progressive Enhancement | Core functionality works without JS |
| P9 | Client Self-Sufficiency | Block Editor + GF admin + WC admin + Beheergids |
| P10 | Everything is Traced | RTM-001: 274 requirements traced |

### 13.4 Sprint Alignment

| Epic | Sprint | Status |
|---|---|---|
| E-INFRA (Infrastructure & Foundation) | Sprint 1 | **Epic 1 Complete** — Theme scaffold, environments, coding standards, git, CI/CD |
| E-CORE (Core Pages & Conversion) | Sprint 2 | Ready to begin — All architectural decisions documented in this ADR |

---

**This ADR is internally consistent with all Sprint 1 documents (MPS-001, SAD-001, BKLG-001, ARR-001, RS-01 through RS-08) and the completed Epic 1 implementation. It resolves all BLOCKING issues identified in the Architecture Readiness Review. Sprint 2 may proceed without additional architectural clarification.**

**END OF ARCHITECTURE DECISION RECORD — Version 1.0.0**
