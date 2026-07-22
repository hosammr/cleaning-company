# HDS Onderhoudsdiensten — Final Project Checklist

**Document ID:** FPC-001 | **Version:** 1.0.0 | **Date:** July 2026
**Reviewer:** Senior Solution Architect
**Purpose:** Master checklist for all project phases — from pre-development through post-launch
**Input:** FAR-001, GAP-001, RR-001, IRR-001, MPS-001, RS-07, all Sprint 1-3 documents

---

## How to Use This Checklist

- Each item is a concrete, verifiable action.
- **Blocking** items must be completed before the next phase can start.
- **Critical** items block launch.
- Items are referenced to source documents (GAP-XXX, RR-XXX, FAR-XXX, MPS-XXX) so you can trace why each item matters.

---

## Phase 0: Pre-Development (Sprint 0) — MUST COMPLETE BEFORE SPRINT 1

### 0.1 Client Engagement [BLOCKING]

| # | Item | Status | Reference |
|---|---|---|---|
| 0.1.1 | Client workshop conducted — rebuild commitment confirmed in writing | ☐ | G-E01, R-S01 |
| 0.1.2 | Budget approved and signed off | ☐ | MPS Q17 |
| 0.1.3 | Legal entity type confirmed (BV/eenmanszaak/VOF) | ☐ | MI-18 |
| 0.1.4 | Physical business address provided | ☐ | MI-01, G-E02, RC-01 |
| 0.1.5 | KVK registration number provided | ☐ | MI-02, G-E03, RC-01 |
| 0.1.6 | BTW (VAT) number provided | ☐ | MI-03, G-E03, RC-01 |
| 0.1.7 | Business operating hours provided | ☐ | MI-04, RC-01 |
| 0.1.8 | Service area (municipalities/postcodes) confirmed | ☐ | MI-05, RC-01 |
| 0.1.9 | Airfixr product line — KEEP or REMOVE decision made | ☐ | MI-15, Q09, G-E05, R-S03 |
| 0.1.10 | If Airfixr KEPT: payment gateway selected (Mollie recommended) | ☐ | MI-15, G-T02, MD-03 |
| 0.1.11 | If Airfixr KEPT: shipping costs and delivery policy provided | ☐ | MI-14 |
| 0.1.12 | Dutch privacy lawyer engaged for privacyverklaring review | ☐ | MI-17, G-E07, R-SEC02 |
| 0.1.13 | Primary new-client source confirmed (website/phone/referral/tender) | ☐ | MPS Q08 |
| 0.1.14 | Top 3 business goals for next 12 months confirmed | ☐ | MPS Q18 |
| 0.1.15 | 42 open questions from RS-08 documented and resolved | ☐ | MPS §I5 |

### 0.2 Brand & Design [BLOCKING]

| # | Item | Status | Reference |
|---|---|---|---|
| 0.2.1 | Logo vector file (SVG/AI/EPS) received | ☐ | MI-06, G-E04 |
| 0.2.2 | Brand color palette confirmed or approved | ☐ | MI-07, G-E06 |
| 0.2.3 | Typography preferences confirmed (Open Sans kept or changed) | ☐ | MI-08, G-E06 |
| 0.2.4 | Design tokens locked — no further changes without change request | ☐ | MPS §F4, ADR D-001 |

### 0.3 Infrastructure [BLOCKING]

| # | Item | Status | Reference |
|---|---|---|---|
| 0.3.1 | Managed WordPress hosting selected and provisioned (Kinsta/WP Engine/Cloud86) | ☐ | MI-20, G-E08 |
| 0.3.2 | Staging environment created (staging.helderduidelijkschoon.nl) | ☐ | MPS §B4 |
| 0.3.3 | Production environment created (helderduidelijkschoon.nl) | ☐ | MPS §B4 |
| 0.3.4 | SMTP transactional email service procured and configured | ☐ | G-T01, MD-02, R-T01 |
| 0.3.5 | SPF/DKIM/DMARC DNS records configured and verified | ☐ | G-T01, MD-02 |
| 0.3.6 | Email delivery tested end-to-end (form → SendGrid/Mailgun/SES → inbox) | ☐ | G-T01 |
| 0.3.7 | Post SMTP email logging enabled | ☐ | G-T01 |
| 0.3.8 | Payment gateway procured (Mollie or alternative — if Airfixr KEPT) | ☐ | G-T02, MD-03, R-T02 |
| 0.3.9 | Payment gateway configured in test mode on staging | ☐ | G-T02 |
| 0.3.10 | Cloudflare CDN provisioned (Free tier minimum) | ☐ | G-T03, MD-04 |
| 0.3.11 | Cloudflare SSL configured (Full Strict) | ☐ | G-T03 |
| 0.3.12 | Cloudflare DNS records configured (A, CNAME, MX preserved) | ☐ | MD-04, G-M03 |
| 0.3.13 | Git repository set up with branch protection | ☐ | MPS §B1 |
| 0.3.14 | CI/CD pipeline configured (GitHub Actions) | ☐ | MPS §B1, ADR §3.14 |

### 0.4 Content & Resources

| # | Item | Status | Reference |
|---|---|---|---|
| 0.4.1 | Dutch-language content writer identified and engaged | ☐ | G-C01 |
| 0.4.2 | Content writer briefed on: page inventory, word count minimums, tone of voice, SEO keywords | ☐ | MPS §C2, §E1 |
| 0.4.3 | SEO specialist engaged for keyword research | ☐ | G-C02, MPS §E3 |
| 0.4.4 | Client provides testimonial content (5+ names/logos, 3+ testimonials) | ☐ | MI-10, MI-11, G-C03 |
| 0.4.5 | Client provides vacancy text as editable HTML (not JPG images) | ☐ | MI-12, G-C04 |
| 0.4.6 | Client provides Terms & Conditions text | ☐ | MI-16, G-C05 |
| 0.4.7 | Client provides project photos (service pages, team, before/after) — optional, can use stock | ☐ | MI-09 |
| 0.4.8 | Client provides OSB membership link URL | ☐ | MI-25 |
| 0.4.9 | Client provides Google Business Profile access or confirms status | ☐ | MI-21 |

### 0.5 Migration Preparation

| # | Item | Status | Reference |
|---|---|---|---|
| 0.5.1 | Full backup of old site taken (files + database) | ☐ | G-M01, R-M01 |
| 0.5.2 | Old site backup restored to test environment and VERIFIED (all pages, forms, WC, admin) | ☐ | G-M01, R-M01 |
| 0.5.3 | Verified backup stored in 2 locations (offsite cloud + developer local) | ☐ | G-M01 |
| 0.5.4 | All PDFs downloaded from legacy domain `hds-onderhoudsdiensten.nl` | ☐ | G-M06, MD-07 |
| 0.5.5 | Legacy domain PDF URLs documented for redirect mapping | ☐ | G-M06 |
| 0.5.6 | Old site XML content export taken | ☐ | R-M06 |
| 0.5.7 | Old site media library downloaded | ☐ | R-M06 |
| 0.5.8 | If Airfixr KEPT: WooCommerce data exported (products, orders, customers) | ☐ | G-M04 |
| 0.5.9 | Client notified of content freeze date | ☐ | G-M05 |
| 0.5.10 | Current MX records documented (for email preservation during DNS cutover) | ☐ | G-M03, R-M04 |

### 0.6 Document Cleanup [BLOCKING]

| # | Item | Status | Reference |
|---|---|---|---|
| 0.6.1 | Document authority chain defined and published | ☐ | G-D01, IC-01 |
| 0.6.2 | MPS-001 updated: OR choices resolved (theme, SEO plugin, cache plugin) | ☐ | G-D02, IC-02 |
| 0.6.3 | MPS-001 updated: FSE references removed, "Hybrid block theme" used | ☐ | G-T05 |
| 0.6.4 | `hds_faq` CPT removed from ALL documents and ALL code | ☐ | G-T06, IC-05, CT-01 |
| 0.6.5 | ADR updated: D-012 added (FAQ via Yoast/Rank Math block, not CPT) | ☐ | G-T06 |
| 0.6.6 | SA-001 §15.1 database schema diagram updated (remove hds_faq) | ☐ | DB-01 |
| 0.6.7 | Document consolidation decision: merge SAD-001 + SA-001 OR differentiate clearly | ☐ | G-D03, NC-01 |
| 0.6.8 | All cross-references use document IDs (not filenames) | ☐ | IC-06 |

---

## Phase 1: Architecture Finalization

| # | Item | Status | Reference |
|---|---|---|---|
| 1.1 | Block pattern scope finalized — all 16 patterns defined or deferred | ☐ | G-T04, CC-01 |
| 1.2 | 4 custom block render_callback edge cases specified (empty, pagination, sorting) | ☐ | G-T07, CC-02 |
| 1.3 | Theme selection documented (Custom Hybrid Block Theme — ADR D-001) | ☐ | B01, SWA-02 |
| 1.4 | FSE approach clarified (Hybrid, NOT FSE — ADR D-005) | ☐ | B02, CMS-01 |
| 1.5 | CPT slug conflict resolved (hds_testimonial public=false — ADR D-006) | ☐ | B03, CMS-02 |
| 1.6 | Gevelreiniging naming documented as standard (ADR D-013) | ☐ | NC-03, IC-04 |
| 1.7 | Service page ordering defined (menu_order: Reguliere → Glas → Gevel → Vloer → VVE → Oplevering → Industrieel) | ☐ | IA-01 |
| 1.8 | Breadcrumb hierarchy defined (flat: Home → Page Name, per URL structure) | ☐ | IA-02 |
| 1.9 | Mobile navigation IA specified (accordion structure) | ☐ | IA-03 |
| 1.10 | 6 block style variations confirmed (is-style-primary removed — core/button default) | ☐ | CC-03 |
| 1.11 | No child theme policy confirmed | ☐ | WTA §3.8 |

---

## Phase 2: Development Standards Confirmation

| # | Item | Status | Reference |
|---|---|---|---|
| 2.1 | PHP coding standards enforced (PHPCS with WordPress-Core + Security + PHP 8.2) | ☐ | WTA §3.9 |
| 2.2 | CSS coding standards enforced (Stylelint, BEM-like naming) | ☐ | WTA §3.9 |
| 2.3 | JavaScript coding standards enforced (ESLint, vanilla ES6+, no jQuery in theme) | ☐ | WTA §3.9 |
| 2.4 | All strings internationalized (__() / _e() with textdomain 'hds') | ☐ | ADR D-011 |
| 2.5 | All output escaped (esc_html, esc_attr, esc_url, wp_kses) | ☐ | SA §13.2 |
| 2.6 | All inputs sanitized (sanitize_text_field, sanitize_email, etc.) | ☐ | SA §13.2 |
| 2.7 | Nonces on all custom forms | ☐ | SA §13.2 |
| 2.8 | Prepared SQL statements (no direct DB queries with user input) | ☐ | SA §13.2 |
| 2.9 | No eval(), no base64_decode(), no extract() | ☐ | SA §13.2 |
| 2.10 | image filenaming convention documented (lowercase-hyphens-dutch-keywords.webp) | ☐ | SAD §39.5 |
| 2.11 | Custom field naming convention (hds_ prefix, lowercase underscores) | ☐ | SAD §39.3 |
| 2.12 | PHP function naming convention (hds_ prefix) | ☐ | SAD §39.1 |
| 2.13 | CSS class naming convention (.hds-component__element--modifier) | ☐ | SAD §39.2 |
| 2.14 | Block/pattern naming convention (hds/block-name, hds/pattern-name) | ☐ | SAD §39.6 |

---

## Phase 3: Pre-Sprint 2 Content & Setup

| # | Item | Status | Reference |
|---|---|---|---|
| 3.1 | Executable test cases created for all P0 pages and forms (test steps, not just IDs) | ☐ | G-Q01, TS-02 |
| 3.2 | Screen reader accessibility test script created | ☐ | G-Q03, A11-I1 |
| 3.3 | Dynamic content accessibility test cases added (aria-live for cart, cookie, search) | ☐ | A11-I2 |
| 3.4 | Complianz Premium license procured | ☐ | G-S02, MD-06 |
| 3.5 | Keyword research completed — top 20 keywords mapped to target pages | ☐ | G-C02, SEO-01 |
| 3.6 | Google Analytics 4 property created | ☐ | MPS §G5 |
| 3.7 | Google Tag Manager container created | ☐ | MPS §G5 |
| 3.8 | Google Search Console property verified | ☐ | MPS §G5 |
| 3.9 | Google Business Profile claimed/verified | ☐ | MPS §G3, L01 |
| 3.10 | API Integration Specification created (Mollie, Cloudflare, Post SMTP, Gravity Forms REST) | ☐ | G-D04, API-01 |
| 3.11 | Error Handling & Logging Strategy created | ☐ | G-D05 |

---

## Phase 4: Pre-Launch Architecture Checklist [CRITICAL — ALL MUST BE COMPLETE]

### 4.1 Content

| # | Item | Status | Reference |
|---|---|---|---|
| 4.1.1 | All 32 pages published with final Dutch content | ☐ | MPS LR01 |
| 4.1.2 | All service pages ≥ 300 words | ☐ | MPS §E1 |
| 4.1.3 | All category landings ≥ 500 words | ☐ | MPS §E1 |
| 4.1.4 | All About/Over HDS pages ≥ 500 words | ☐ | MPS §E1 |
| 4.1.5 | Zero lorem ipsum or placeholder text on any page | ☐ | MPS DC23 |
| 4.1.6 | Phone number (0164-652846) correct on all pages | ☐ | MPS DC24 |
| 4.1.7 | Email (info@helderduidelijkschoon.nl) correct on all pages | ☐ | MPS DC24 |
| 4.1.8 | KVK + BTW in footer (if provided by client) | ☐ | MPS LR14 |
| 4.1.9 | All content reviewed by native Dutch speaker | ☐ | MPS LR25 |
| 4.1.10 | Cross-link rules verified on all service pages | ☐ | F-I04 |

### 4.2 Forms & Conversion

| # | Item | Status | Reference |
|---|---|---|---|
| 4.2.1 | Contact form (GF-1) submits successfully — email delivered to info@ within 2 minutes | ☐ | MPS AC-F01 |
| 4.2.2 | Contact form — confirmation email delivered to user | ☐ | MPS AC-F01 |
| 4.2.3 | Contact form — entry stored in database | ☐ | MPS AC-F01 |
| 4.2.4 | Offerte form (GF-2) submits successfully with file upload | ☐ | MPS |
| 4.2.5 | Vacature form (GF-3) submits successfully with CV upload | ☐ | MPS |
| 4.2.6 | All forms: reCAPTCHA v3 active + honeypot field present | ☐ | MPS SEC-003 |
| 4.2.7 | All forms: privacy checkbox unchecked by default, links to privacyverklaring | ☐ | MPS CMP-005 |
| 4.2.8 | All forms: inline validation errors in Dutch with aria-describedby | ☐ | MPS ACC-007 |
| 4.2.9 | All forms: AJAX submission with loading state ("Versturen..." + spinner + disabled) | ☐ | UX-01 |
| 4.2.10 | Bedankt page dynamic heading based on ?type= parameter | ☐ | F-I02 |
| 4.2.11 | reCAPTCHA badge visible and not obscured by cookie banner | ☐ | MAC07, A11Y-02 |

### 4.3 WooCommerce (if Airfixr KEPT)

| # | Item | Status | Reference |
|---|---|---|---|
| 4.3.1 | 14 Airfixr products imported with correct prices, images, stock status | ☐ | MPS |
| 4.3.2 | Full purchase flow tested: Browse → Add to Cart → Checkout → Payment → Order Confirmation | ☐ | MPS LR03 |
| 4.3.3 | Mollie payment webhook verified (order status updates after payment) | ☐ | MAC04, B10 |
| 4.3.4 | WooCommerce emails tested (New Order, Processing, Completed) — all delivered via SMTP | ☐ | MPS |
| 4.3.5 | Cloudflare cache bypass verified for /winkelmand/*, /afrekenen/*, /mijn-account/* | ☐ | MAC05, B07 |
| 4.3.6 | Mollie webhook URL not blocked by Cloudflare WAF | ☐ | HD03 |
| 4.3.7 | Inventory management enabled, low-stock threshold set to 2 | ☐ | WC-03 |
| 4.3.8 | Shop intro text written on /winkel/ (100+ words explaining Airfixr + connection to HDS) | ☐ | MPS |
| 4.3.9 | Luchtreiniging landing page (P23) published with 300+ words | ☐ | MPS |

### 4.4 SEO [CRITICAL]

| # | Item | Status | Reference |
|---|---|---|---|
| 4.4.1 | Every page has unique title tag (50-60 chars) — Screaming Frog verified | ☐ | MPS §I2.2 |
| 4.4.2 | Every page has unique meta description (150-160 chars) — Screaming Frog verified | ☐ | MPS §I2.2 |
| 4.4.3 | Zero duplicate titles or descriptions | ☐ | MPS §I2.2 |
| 4.4.4 | Zero empty titles or descriptions | ☐ | MPS §I2.2 |
| 4.4.5 | H1 present exactly once per page | ☐ | MPS §I2.2 |
| 4.4.6 | H2/H3 hierarchy logical, no skipped levels | ☐ | MPS §I2.2 |
| 4.4.7 | All images have alt text — Screaming Frog verified (zero missing, excluding decorative) | ☐ | MPS §I2.2 |
| 4.4.8 | Self-referencing canonicals on all pages | ☐ | MPS §I2.2 |
| 4.4.9 | Open Graph tags complete on all pages (og:title, og:description, og:image, og:url, og:type, og:locale) | ☐ | MPS §I2.2 |
| 4.4.10 | Twitter Card tags complete | ☐ | MPS §I2.2 |
| 4.4.11 | Social share image (1200×630px) uploaded and configured as default | ☐ | G-C06 |
| 4.4.12 | LocalBusiness schema present + valid (Google Rich Results Test) | ☐ | MPS §I2.2 |
| 4.4.13 | Service schema on each service page (P02-P08) — valid (Rich Results Test) | ☐ | MPS §I2.2 |
| 4.4.14 | FAQPage schema on /veelgestelde-vragen/ — valid (Rich Results Test) | ☐ | MPS §I2.2 |
| 4.4.15 | BreadcrumbList schema on all inner pages | ☐ | MPS §I2.2 |
| 4.4.16 | JobPosting schema per vacancy on /vacatures/ — valid (Rich Results Test) | ☐ | MPS |
| 4.4.17 | Product schema on all product pages — valid (Rich Results Test) | ☐ | MPS |
| 4.4.18 | All 9 schema types validated — zero errors | ☐ | MPS LR09 |
| 4.4.19 | XML Sitemap returns HTTP 200 with valid XML — zero errors | ☐ | MPS LR10 |
| 4.4.20 | Zero attachment pages in XML sitemap | ☐ | MPS |
| 4.4.21 | Image sitemap enabled and submitted | ☐ | SEO-I1 |
| 4.4.22 | robots.txt returns HTTP 200 with correct rules | ☐ | MPS §I2.2 |
| 4.4.23 | All 7 × 301 redirects + 2 × 410 Gone configured and tested | ☐ | MPS LR04 |
| 4.4.24 | Zero redirect chains (verified via httpstatus.io) | ☐ | MPS DC17 |
| 4.4.25 | Attachment pages (50+) redirected to parent pages via catch-all rule | ☐ | RD-01 |
| 4.4.26 | HTTPS enforced: HTTP → 301 to HTTPS + HSTS header | ☐ | MPS LR11 |
| 4.4.27 | Non-www → www redirect working | ☐ | MPS |
| 4.4.28 | Internal links: zero broken (Screaming Frog) | ☐ | MPS LR19 |
| 4.4.29 | Internal links: zero orphan pages (Screaming Frog) | ☐ | MPS LR19 |
| 4.4.30 | Google Search Console verified | ☐ | MPS LR16 |
| 4.4.31 | Bing Webmaster Tools verified | ☐ | MPS |

### 4.5 Performance [CRITICAL]

| # | Item | Status | Reference |
|---|---|---|---|
| 4.5.1 | PSI Mobile score ≥ 90 on all page templates | ☐ | MPS LR05 |
| 4.5.2 | PSI Desktop score ≥ 95 on all page templates | ☐ | MPS LR05 |
| 4.5.3 | LCP < 2.5 seconds on all page templates | ☐ | MPS §H3.1 |
| 4.5.4 | CLS < 0.1 on all page templates | ☐ | MPS §H3.1 |
| 4.5.5 | TTFB < 600ms (WebPageTest, Amsterdam, Moto G4, 3G Fast) | ☐ | MPS §H3.1 |
| 4.5.6 | Total page weight < 1.5 MB (mobile) | ☐ | MPS §H3.1 |
| 4.5.7 | Critical CSS inlined in head — verified via View Source | ☐ | MPS |
| 4.5.8 | JavaScript deferred — no render-blocking JS | ☐ | MPS |
| 4.5.9 | jQuery Migrate NOT loaded | ☐ | MPS |
| 4.5.10 | Fonts self-hosted, font-display: swap, preloaded | ☐ | MPS |
| 4.5.11 | WebP images with picture fallback — verified via DevTools Network tab | ☐ | MPS |
| 4.5.12 | LCP image has fetchpriority="high", no lazy loading | ☐ | MPS |
| 4.5.13 | Below-fold images have loading="lazy" | ☐ | MPS |
| 4.5.14 | Cloudflare CDN active — CF-Cache-Status header present | ☐ | MPS |
| 4.5.15 | FlyingPress page cache active — verified via response headers | ☐ | MPS |
| 4.5.16 | Redis object cache active | ☐ | MPS |
| 4.5.17 | Database clean: zero old revisions (>30 days), zero spam, zero transient garbage | ☐ | MPS |

### 4.6 Accessibility [CRITICAL]

| # | Item | Status | Reference |
|---|---|---|---|
| 4.6.1 | Lighthouse Accessibility = 100 on all page templates | ☐ | MPS LR06 |
| 4.6.2 | axe DevTools: zero critical issues, zero serious issues | ☐ | MPS LR07 |
| 4.6.3 | WAVE: zero errors | ☐ | MPS §H4.2 |
| 4.6.4 | Keyboard navigation: all interactive elements reachable and operable | ☐ | MPS |
| 4.6.5 | Keyboard navigation: visible focus indicator on all elements | ☐ | MPS |
| 4.6.6 | Keyboard navigation: dropdown menu opens on Enter/Space, closes on Escape | ☐ | B12, AS02 |
| 4.6.7 | Skip to content link: first focusable element, visible on focus, links to main | ☐ | MPS |
| 4.6.8 | Screen reader: NVDA (Windows) or VoiceOver (Mac) — all content announced correctly | ☐ | MPS |
| 4.6.9 | Screen reader: forms usable, labels read, errors announced via aria-describedby | ☐ | MPS |
| 4.6.10 | Screen reader: add-to-cart announcement verified (aria-live) | ☐ | A11-I2 |
| 4.6.11 | Screen reader: cart quantity update announcement verified | ☐ | A11-I2 |
| 4.6.12 | Screen reader: cookie banner dismiss moves focus correctly | ☐ | A11-I2 |
| 4.6.13 | Color contrast: all text passes AA (4.5:1 normal, 3:1 large) — WebAIM or axe verified | ☐ | MPS |
| 4.6.14 | Color contrast: all UI components pass AA (3:1) | ☐ | MPS |
| 4.6.15 | 200% zoom: no content loss, no horizontal scroll | ☐ | MPS |
| 4.6.16 | Touch targets ≥ 44×44px on all interactive elements | ☐ | MPS |
| 4.6.17 | lang="nl-NL" on html element | ☐ | MPS |
| 4.6.18 | prefers-reduced-motion respected (no auto-play, no animations >3/sec) | ☐ | MPS |
| 4.6.19 | WooCommerce checkout: accessibility tested (screen reader + keyboard + axe) | ☐ | A11Y-01 |
| 4.6.20 | Mobile accessibility tested on real device (VoiceOver iOS / TalkBack Android, min 3 pages) | ☐ | MPS |

### 4.7 Security [CRITICAL]

| # | Item | Status | Reference |
|---|---|---|---|
| 4.7.1 | HTTPS enforced with HSTS (max-age=31536000; includeSubDomains; preload) | ☐ | MPS LR11 |
| 4.7.2 | SSL Labs grade A+ | ☐ | MPS |
| 4.7.3 | XML-RPC disabled at server level — curl returns HTTP 403 | ☐ | MPS LR18, R-SEC04 |
| 4.7.4 | Custom login URL active (not /wp-admin/ or /wp-login.php) | ☐ | MPS LR18 |
| 4.7.5 | 2FA enforced on ALL Administrator, Editor, and Shop Manager accounts | ☐ | MPS LR18 |
| 4.7.6 | Brute force protection active — 3 failed attempts → IP lockout | ☐ | MPS |
| 4.7.7 | DISALLOW_FILE_EDIT = true in wp-config.php | ☐ | MPS |
| 4.7.8 | Database prefix changed from wp_ to hds_ | ☐ | MPS |
| 4.7.9 | WordPress salts generated fresh (unique, not from example) | ☐ | WBP-01 |
| 4.7.10 | /wp-json/wp/v2/users endpoint blocked | ☐ | MPS |
| 4.7.11 | /?author=N enumeration blocked | ☐ | MPS |
| 4.7.12 | Wordfence Premium active: firewall, daily malware scan, file integrity monitoring | ☐ | MPS |
| 4.7.13 | Cloudflare WAF rules configured: block xmlrpc, rate-limit login, WordPress managed ruleset | ☐ | G-SEC01, SEC-01 |
| 4.7.14 | Daily backups configured and VERIFIED (test restore to staging) | ☐ | MPS LR17 |
| 4.7.15 | Security Incident Response Runbook documented (detection → containment → investigation → remediation → notification → post-mortem) | ☐ | G-S01, SEC-02 |
| 4.7.16 | Admin usernames: never "admin", "hds", or "helderduidelijkschoon" | ☐ | MPS |
| 4.7.17 | No nulled or cracked plugins — all from official sources | ☐ | MPS DC08 |
| 4.7.18 | File permissions: dirs 755, files 644, wp-config.php 400 | ☐ | MPS |
| 4.7.19 | SFTP only (no FTP) | ☐ | MPS |

### 4.8 GDPR / AVG Compliance [CRITICAL]

| # | Item | Status | Reference |
|---|---|---|---|
| 4.8.1 | Privacyverklaring published at /privacyverklaring/ | ☐ | MPS LR12 |
| 4.8.2 | Privacyverklaring reviewed by qualified Dutch privacy lawyer | ☐ | MPS LR12, R-SEC02 |
| 4.8.3 | Privacyverklaring linked from footer on every page | ☐ | MPS |
| 4.8.4 | Cookie consent banner appears on fresh browser (first visit) | ☐ | MPS LR13 |
| 4.8.5 | Zero non-functional cookies loaded before consent — DevTools verified | ☐ | MPS LR13, R-SEC03 |
| 4.8.6 | Consent logged (timestamp, anonymized IP, consent string) | ☐ | MPS LR13 |
| 4.8.7 | GTM Consent Mode v2 active — marketing tags deferred until consent | ☐ | MPS |
| 4.8.8 | Cookiebeleid published at /cookiebeleid/ and reviewed | ☐ | MPS |
| 4.8.9 | Algemene Voorwaarden published at /algemene-voorwaarden/ — client text provided and reviewed | ☐ | MPS |
| 4.8.10 | Disclaimer published at /disclaimer/ | ☐ | MPS |
| 4.8.11 | All form consent checkboxes unchecked by default, link to privacyverklaring | ☐ | MPS |
| 4.8.12 | Data retention configured: form entries auto-delete after 12 months | ☐ | MPS |
| 4.8.13 | WooCommerce order retention: 7 years (Dutch financial data requirement) | ☐ | SWA-05 |
| 4.8.14 | WooCommerce monthly CSV export configured for 7-year retention | ☐ | SWA-05 |
| 4.8.15 | Data Processing Agreement (DPA) signed with hosting provider | ☐ | MPS |
| 4.8.16 | IP anonymization enabled in GA4 | ☐ | MPS |
| 4.8.17 | Right to erasure process documented | ☐ | MPS |
| 4.8.18 | Data breach notification process documented (72 hours to Autoriteit Persoonsgegevens) | ☐ | MPS |

### 4.9 Analytics

| # | Item | Status | Reference |
|---|---|---|---|
| 4.9.1 | GA4 property active — real-time report shows page views | ☐ | MPS LR15 |
| 4.9.2 | GTM container active — Tag Assistant verified | ☐ | MPS |
| 4.9.3 | 7 conversion events firing in GA4 (phone_click, email_click, form_submission, quote_request, add_to_cart, purchase, cookie_consent_accepted) | ☐ | MPS |
| 4.9.4 | Scroll depth tracking configured (25%, 50%, 75%, 100% on service pages + blog posts) | ☐ | AN-I1 |
| 4.9.5 | Site search tracking configured (search_term parameter) | ☐ | AN-I2 |
| 4.9.6 | Custom dimensions configured: form_type, service_page, page_category | ☐ | ANA-01 |
| 4.9.7 | Google Looker Studio dashboard connected to GA4 | ☐ | ANA-02 |

### 4.10 Cross-Browser & Mobile

| # | Item | Status | Reference |
|---|---|---|---|
| 4.10.1 | Chrome (latest 2 versions) — consistent rendering, all functions work | ☐ | MPS LR23 |
| 4.10.2 | Firefox (latest 2 versions) — consistent rendering, all functions work | ☐ | MPS LR23 |
| 4.10.3 | Safari (latest 2 versions) — consistent rendering, all functions work | ☐ | MPS LR23 |
| 4.10.4 | Edge (latest 2 versions) — consistent rendering, all functions work | ☐ | MPS LR23 |
| 4.10.5 | iPhone (iOS Safari) — responsive, no horizontal scroll, forms usable | ☐ | MPS LR24 |
| 4.10.6 | Android (Chrome) — responsive, no horizontal scroll, forms usable | ☐ | MPS LR24 |
| 4.10.7 | iPad — responsive, forms usable | ☐ | MPS LR24 |
| 4.10.8 | Google Mobile-Friendly Test passes | ☐ | MPS |

### 4.11 Migration & Launch

| # | Item | Status | Reference |
|---|---|---|---|
| 4.11.1 | DNS TTL lowered to 300 seconds — 24 hours before launch | ☐ | G-M02, R-M02 |
| 4.11.2 | DNS TTL propagation verified via whatsmydns.net | ☐ | G-M02 |
| 4.11.3 | Old site backup VERIFIED (test restore to test environment — all pages, forms, WC, admin) | ☐ | G-M01, B05 |
| 4.11.4 | Staging database search-replaced for production domain | ☐ | MPS |
| 4.11.5 | Production SSL verified | ☐ | MPS |
| 4.11.6 | Site deployed to production | ☐ | MPS |
| 4.11.7 | All caches cleared (FlyingPress + Cloudflare + Redis) | ☐ | MPS |
| 4.11.8 | All 301 redirects verified on production (httpstatus.io) | ☐ | MPS |
| 4.11.9 | Contact form test submission on production — email delivered | ☐ | MPS |
| 4.11.10 | Offerte form test submission with file attachment on production | ☐ | MPS |
| 4.11.11 | WooCommerce test purchase on production (if Airfixr KEPT) | ☐ | MPS |
| 4.11.12 | XML Sitemap submitted to Google Search Console | ☐ | MPS |
| 4.11.13 | XML Sitemap submitted to Bing Webmaster Tools | ☐ | MPS |
| 4.11.14 | robots.txt accessible and correct | ☐ | MPS |
| 4.11.15 | Cookie consent banner verified on production (fresh browser) | ☐ | MPS |
| 4.11.16 | GA4 real-time reports showing traffic | ☐ | MPS |
| 4.11.17 | All old URLs returning correct 301 or 410 — verified via Screaming Frog | ☐ | MPS |
| 4.11.18 | Email MX records verified unchanged after DNS cutover | ☐ | G-M03, R-M04 |
| 4.11.19 | Test email delivered to info@ within 1 hour of launch | ☐ | G-M03 |
| 4.11.20 | Server error logs clean | ☐ | MPS |
| 4.11.21 | DNS TTL restored to normal — 24 hours after launch | ☐ | G-M02 |

### 4.12 Client Handover

| # | Item | Status | Reference |
|---|---|---|---|
| 4.12.1 | Beheergids (Website Management Guide) written in Dutch with screenshots | ☐ | MN-02 |
| 4.12.2 | Beheergids covers: Login, Page editing, Blog posts, Testimonials, Vacancies, Form entries, Media, SEO, Menus, Customizer | ☐ | MN-02 |
| 4.12.3 | 1-hour client training session completed | ☐ | MPS |
| 4.12.4 | Client can independently: edit a page, create a blog post, view form entries, manage menus | ☐ | MPS |
| 4.12.5 | Post-launch support retainer discussed/agreed | ☐ | MPS |
| 4.12.6 | Launch readiness report delivered to client | ☐ | MPS |
| 4.12.7 | Client sign-off obtained | ☐ | MPS LR20 |

---

## Phase 5: Post-Launch Verification

### 5.1 Immediate (1 Hour After Launch)

| # | Item | Status | Reference |
|---|---|---|---|
| 5.1.1 | Homepage loads on desktop + mobile | ☐ | MPS |
| 5.1.2 | Contact form test submission successful | ☐ | MPS |
| 5.1.3 | Phone/email links work | ☐ | MPS |
| 5.1.4 | All navigation links work | ☐ | MPS |
| 5.1.5 | SSL valid | ☐ | MPS |
| 5.1.6 | GA4 real-time shows users | ☐ | MPS |

### 5.2 Day 1

| # | Item | Status | Reference |
|---|---|---|---|
| 5.2.1 | GSC: sitemap submitted, zero errors | ☐ | MPS |
| 5.2.2 | All email notifications working | ☐ | MPS |
| 5.2.3 | Server error logs clean | ☐ | MPS |
| 5.2.4 | Backup completed successfully | ☐ | MPS |
| 5.2.5 | Screaming Frog: zero unexpected 4xx/5xx | ☐ | MPS |
| 5.2.6 | Tested on real mobile + tablet | ☐ | MPS |
| 5.2.7 | GBP + social links verified | ☐ | MPS |

### 5.3 Week 1

| # | Item | Status | Reference |
|---|---|---|---|
| 5.3.1 | GSC daily crawl error monitoring — zero errors | ☐ | MPS |
| 5.3.2 | Core Web Vitals monitored in GSC | ☐ | MPS |
| 5.3.3 | GA4 conversion events firing — all 7 events active | ☐ | MPS |
| 5.3.4 | Security logs reviewed (Wordfence) | ☐ | MPS |
| 5.3.5 | Form submissions flowing — check spam rate | ☐ | MPS |
| 5.3.6 | 404 monitor reviewed (Rank Math Pro) | ☐ | SEO-04 |
| 5.3.7 | Email deliverability verified (check Post SMTP log) | ☐ | MPS |
| 5.3.8 | Weekly PSI automated check configured | ☐ | PERF-01 |

### 5.4 Week 2

| # | Item | Status | Reference |
|---|---|---|---|
| 5.4.1 | All new URLs submitted for indexing in GSC | ☐ | MPS |
| 5.4.2 | Indexed pages compared to pre-migration baseline | ☐ | MPS |
| 5.4.3 | Search impressions compared to pre-migration baseline | ☐ | MPS |
| 5.4.4 | Keyword rankings compared to pre-migration baseline | ☐ | MPS |
| 5.4.5 | Performance re-test (PSI, WebPageTest) | ☐ | MPS |
| 5.4.6 | All 301 redirects still working (httpstatus.io) | ☐ | MPS |

### 5.5 Week 4 (30-Day Review)

| # | Item | Status | Reference |
|---|---|---|---|
| 5.5.1 | Full SEO audit vs pre-migration baseline | ☐ | MPS |
| 5.5.2 | Client report delivered: traffic, conversions, rankings, technical health | ☐ | MPS |
| 5.5.3 | All plugins/themes/core updated (monthly maintenance window) | ☐ | MNT-01 |
| 5.5.4 | Security logs reviewed — no incidents | ☐ | MPS |
| 5.5.5 | Backup test restore verified (monthly) | ☐ | MPS |
| 5.5.6 | Client satisfaction check | ☐ | MPS |
| 5.5.7 | Database autoloaded data audit (quarterly) | ☐ | WBP-04 |

---

## 6. Summary

| Phase | Total Items | Critical/Blocking |
|---|---|---|
| Phase 0: Pre-Development | 50 | 35 blocking |
| Phase 1: Architecture Finalization | 11 | 0 |
| Phase 2: Development Standards | 14 | 0 |
| Phase 3: Pre-Sprint 2 Setup | 11 | 0 |
| Phase 4: Pre-Launch | 107 | 107 critical |
| Phase 5: Post-Launch | 25 | 0 |
| **Grand Total** | **218** | **142** |

---

*End of Final Project Checklist — FPC-001 v1.0.0*

*This checklist should be reviewed at the start of every sprint. Mark items complete as they are accomplished. Any incomplete item in Phase 0 blocks Sprint 1. Any incomplete item in Phase 4 blocks launch.*
