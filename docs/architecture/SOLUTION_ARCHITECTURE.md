# HDS Onderhoudsdiensten — Solution Architecture Document

**Document ID:** SAD-001 | **Version:** 2.0.0 | **Status:** Implementation-Ready
**Language:** Nederlands (nl-NL) | **Date:** July 2026

---

## 1. Executive Summary

This SAD is the definitive technical reference for rebuilding helderduidelijkschoon.nl. It provides implementation-ready architectural specifications across 46 domains, sufficient for a development team to begin Sprint 2 without additional architectural clarification.

**System:** Monolithic WordPress 6.7+ application + WooCommerce + Gravity Forms + 13 plugins. Managed hosting with Cloudflare CDN. Single-language Dutch. 32-page target site. B2B cleaning services in West-Brabant/Zeeland.

**Key Decisions:** Hybrid Block Theme (not FSE). Native Block Editor only (no page builder). Flat URLs. CPTs for vacancies/testimonials; Pages for services. Relevanssi for search. Complianz for cookies. Mollie for payments.

---

## 2. Architecture Principles

| # | Principle | Implementation Rule |
|---|---|---|
| P1 | Rebuild, Don't Repair | Zero code carried forward from old site |
| P2 | No Page Builder Lock-In | Native Block Editor only — any page builder = violation |
| P3 | Content Portability | All content as standard Blocks; no shortcodes in post_content |
| P4 | Performance by Default | PSI 90+ mobile / 95+ desktop mandatory at QA gate |
| P5 | Security in Depth | XML-RPC disabled; custom login URL; 2FA on all accounts |
| P6 | Dutch-First | lang="nl-NL"; all UI in Dutch; no translation plugins |
| P7 | Mobile-First | Mobile-first CSS; touch targets >= 44px |
| P8 | Progressive Enhancement | Forms, navigation, content functional without JS |
| P9 | Client Self-Sufficiency | Block Editor + Gravity Forms + WC admin + Beheergids |
| P10 | Everything is Traced | 274 requirements fully traced in RTM-001 |

---

## 3. System Overview

### 3.1 System Identity

| Attribute | Value |
|---|---|
| Domain | https://helderduidelijkschoon.nl/ |
| Canonical | non-www, HTTPS, trailing slash |
| Language | nl-NL |
| Target Audience | B2B facility managers, VvE boards, construction PMs, schools, factories |
| Service Area | West-Brabant / Zeeland |
| Total Pages | 32 (target) |
| Products | 14 Airfixr SKUs (conditional on client decision) |

### 3.2 Current vs Target

| Dimension | Current | Target |
|---|---|---|
| CMS | WP 6.2.9 | WP 6.7+ |
| Theme | Divi 4.16.1 | Custom Hybrid Block |
| Contact Page | HTTP 500 | HTTP 200, Gravity Forms |
| Primary Service | HTTP 404 | HTTP 200, 300+ words |
| Page Sitemap | HTTP 500 | HTTP 200, valid XML |
| Meta Descriptions | Zero | 32 unique |
| GDPR Compliance | None | Full AVG |
| Schema | 3 types | 9 types |
| Performance | Unknown | PSI 90+ / 95+ |
| Accessibility | Vacatures as JPG | WCAG 2.2 AA |

---

## 4. High-Level Architecture

### 4.1 Architecture Style

Monolithic WordPress application with edge caching. NOT headless. Correct architecture for a 32-page B2B site.

### 4.2 System Context

```
[Visitors: B2B Prospects, Job Seekers, Airfixr Buyers]
    | HTTPS
    v
[Cloudflare CDN/WAF: DNS, SSL, DDoS, Caching, Polish, WAF Rules]
    | Origin
    v
[Managed WordPress Host: PHP 8.2+, Nginx, Redis, MySQL 8.0+]
  |-- WordPress 6.7+ (Theme + WC 9+ + GF + SEO + Cache + Security)
  |-- MySQL 8.0+ / MariaDB 10.6+ (InnoDB, hds_ prefix, utf8mb4)
  |-- Offsite Backup Storage (Daily + Weekly + Monthly)
    |
    +-- [Mollie API] [SMTP SendGrid/Mailgun] [Google GA4/GTM/GSC]
```

### 4.3 Technology Stack

| Layer | Technology | Version |
|---|---|---|
| Edge | Cloudflare | Free/Pro |
| Hosting | Managed WP (Kinsta/WPE/Cloud86) | - |
| CMS | WordPress | 6.7+ |
| Theme | Custom Hybrid Block | 1.0.0 |
| eCommerce | WooCommerce | 9.x+ |
| Forms | Gravity Forms | latest |
| SEO | Rank Math Pro / Yoast Premium | latest |
| Caching | WP Rocket/FlyingPress + Redis | latest |
| Security | Wordfence Premium | latest |
| Cookies | Complianz Premium | latest |
| SMTP | Post SMTP + SendGrid/Mailgun/SES | latest |
| Backup | BlogVault/UpdraftPlus | latest |
| Images | ShortPixel/Imagify | latest |
| Search | Relevanssi | latest |
| Analytics | GA4 via GTM | latest |
| Monitoring | UptimeRobot | free |

### 4.4 Banned Technology

| Banned | Reason |
|---|---|
| Divi, Elementor, WPBakery, any page builder | Lock-in, performance, migration risk |
| Formidable Forms | Currently broken (500 error) |
| PHP < 8.0 | EOL, security |
| jQuery Migrate | Performance overhead |
| XML-RPC | Attack vector |
| Nulled/cracked plugins | Malware, legal |

---

## 5. CMS Architecture

### 5.1 WordPress Configuration

| Setting | Value |
|---|---|
| Permalink | /%postname%/ |
| Category Base | kennisbank |
| Timezone | Europe/Amsterdam |
| Date Format | j F Y |
| Language | Nederlands (nl_NL) |
| Comments | Disabled |
| Pingbacks | Disabled |
| WP Cron | Disabled (server cron) |
| Post Revisions | 10 max |
| Autosave | 300s |
| DISALLOW_FILE_EDIT | true |
| FORCE_SSL_ADMIN | true |
| Auto Core Updates | minor only |
| DB Prefix | hds_ |
| DB Charset | utf8mb4 |

### 5.2 Custom Post Types

| CPT Key | public | has_archive | rewrite | Purpose |
|---|---|---|---|---|
| `hds_testimonial` | `false` | `false` | — | Testimonials (block-queried only) |
| `hds_vacancy` | `true` | `false` | `vacatures` | Job listings |

**Note:** FAQ is NOT a CPT. FAQ content uses Yoast/Rank Math FAQ Block on a standard Page at `/veelgestelde-vragen/` (P18). See ADR D-012.

**Critical:** hds_testimonial set to public=false to avoid URL conflict with /referenties/ Page (P13).

### 5.3 Custom Fields

| Group | Location | Fields |
|---|---|---|
| Service Page Settings | Page (Service template) | subtitle, hero_image, service_icon, cta_override |
| Testimonial Details | hds_testimonial | author_name, company_name, star_rating, related_service |
| Vacancy Details | hds_vacancy | hours_per_week, location, start_date, application_email, deadline, is_active |
| Company Information | Theme Customizer | address, postal_code, city, phone, email, kvk, btw, facebook_url, instagram_url, gbp_url, opening_hours (repeater) |

### 5.4 User Roles

| Role | Key Capabilities |
|---|---|
| Administrator | Full access (min 2 accounts, 2FA enforced) |
| Editor | Pages, posts, form entries (view), analytics (view) |
| Shop Manager | WC products, orders, coupons |
| SEO Manager | SEO plugin settings, analytics (view) |
| Subscriber | Read-only (WC customers) |

---

## 6. WordPress Architecture

### 6.1 Core Systems in Use

| System | Usage |
|---|---|
| WP_Query | All content retrieval (templates + custom blocks) |
| Rewrite Rules | Pretty permalinks + CPT rewrites |
| REST API | WC endpoints + Block Editor communication (user endpoint blocked) |
| Options API | Customizer stores company info as theme_mod |
| Transients API | Backed by Redis object cache |
| Media Library | Images + PDFs; WebP conversion on upload |
| Block Editor | All page content editing |
| Menu System | Primary nav + footer nav |

### 6.2 Disabled Systems

XML-RPC (server-level 403), Comments, Pingbacks, Post via Email, Application Passwords, File Editor.

### 6.3 wp-config.php Critical Settings

```php
$table_prefix = 'hds_';
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
define('WP_DEBUG_LOG', true);
define('DISABLE_WP_CRON', true);
define('WP_POST_REVISIONS', 10);
define('AUTOSAVE_INTERVAL', 300);
define('DISALLOW_FILE_EDIT', true);
define('FORCE_SSL_ADMIN', true);
define('WP_AUTO_UPDATE_CORE', 'minor');
```

### 6.4 Database Architecture

| Table Group | Prefix | Purpose |
|---|---|---|
| WP Core | hds_posts, hds_postmeta, hds_terms, hds_options, hds_users, hds_usermeta | CMS data |
| WooCommerce | hds_wc_orders (HPOS), hds_wc_order_addresses, hds_wc_product_meta_lookup | Orders + products |
| Gravity Forms | hds_gf_form, hds_gf_entry, hds_gf_entry_meta | Form entries |
| Relevanssi | hds_relevanssi, hds_relevanssi_log | Search index |

All tables: utf8mb4_unicode_ci, InnoDB. Monthly: clean revisions >30 days. Quarterly: audit autoloaded options (<800KB).

---

## 7. Theme Architecture

### 7.1 Theme Type: Hybrid Block Theme

Uses theme.json for design tokens + PHP templates for layouts + Block Editor for content areas. NOT Full Site Editing. PHP templates provide predictable layouts clients cannot break.

### 7.2 File Structure

```
wp-content/themes/hds/
|-- theme.json                         # Design tokens, block styles
|-- style.css                          # Theme metadata
|-- functions.php                      # Bootstrap
|-- index.php                          # Fallback
|-- screenshot.png                     # Preview
|-- assets/
|   |-- css/ (base, layout, components, blocks)
|   |-- js/ (navigation, main)
|   |-- fonts/ (open-sans-*.woff2)
|   |-- images/ (logo.svg)
|-- inc/
|   |-- setup.php                     # Theme supports, menus, image sizes
|   |-- enqueue.php                   # CSS/JS enqueuing (filemtime versioning)
|   |-- custom-post-types.php         # CPT registration
|   |-- custom-fields.php             # Field groups
|   |-- customizer.php                # Company info section
|   |-- block-patterns.php            # 16 patterns
|   |-- block-styles.php              # 7 style variations
|   |-- template-functions.php        # Helper functions
|   |-- schema.php                    # JSON-LD generation
|-- template-parts/
|   |-- header.php                    # Logo, nav, phone, email, cart
|   |-- footer.php                    # 5-col layout
|   |-- breadcrumbs.php               # Visible + schema
|   |-- schema-localbusiness.php      # LocalBusiness JSON-LD
|-- page-templates/
|   |-- page-service.php              # P02-P08
|   |-- page-category-landing.php     # P09, P10
|   |-- page-about.php                # P11, P12
|   |-- page-contact.php              # P16
|   |-- page-quote.php               # P17
|   |-- page-faq.php                  # P18
|   |-- page-legal.php               # P19-P22
|-- front-page.php                    # P01
|-- page.php                          # Default
|-- single.php                        # Blog post
|-- archive.php                       # Blog index + vacancy archive
|-- search.php                        # Search results
|-- 404.php                           # Custom 404
```

### 7.3 Template Hierarchy

```
/glasbewassing/ -> page (slug: glasbewassing)
  -> template: page-templates/page-service.php
    -> get_header() -> Hero + the_content() + Cross-Sell + CTA -> get_footer()
```

---

## 8. Plugin Architecture

### 8.1 Inventory

| Plugin | License | Critical | Purpose |
|---|---|---|---|
| WooCommerce 9.x+ | Free | Conditional | eCommerce |
| Mollie for WC | Free | Conditional | iDEAL, cards, PayPal |
| Gravity Forms | Premium | Yes | Contact, quote, job forms |
| Rank Math Pro/Yoast Prem | Premium | Yes | SEO: meta, sitemaps, schema |
| WP Rocket/FlyingPress | Premium | Yes | Page cache + CSS/JS opt |
| Complianz Premium | Premium | Yes | AVG cookie consent |
| Wordfence Premium | Premium | Yes | Firewall, 2FA, malware |
| Post SMTP | Free | Yes | Email delivery |
| BlogVault/UpdraftPlus | Premium | Yes | Daily backups |
| ShortPixel/Imagify | Prem/Freemium | No | WebP conversion |
| Relevanssi | Free | No | Dutch search |
| WP-Optimize | Free | No | DB maintenance |

### 8.2 Plugin Dependency Graph

```
WP 6.7+
 |-- WC 9.x+ -> Mollie Plugin -> Mollie API
 |-- Gravity Forms -> Post SMTP -> SendGrid/Mailgun/SES
 |-- SEO Plugin (Rank Math/Yoast)
 |-- Cache Plugin (WP Rocket) -> Cloudflare API (purge)
 |-- Complianz -> GTM Consent Signals -> GA4
 |-- Wordfence (Security)
 |-- Relevanssi (Search) -> hds_posts + CPT indices
 |-- Backup Plugin -> Offsite Storage
 |-- ShortPixel (Images)
 |-- WP-Optimize (DB)
```

### 8.3 Critical Interactions

| Interaction | Rule | Verification |
|---|---|---|
| Complianz -> GTM | Consent signals before marketing tags | Tag Assistant: no GA4 hits before consent |
| WP Rocket -> Cloudflare | Purge CDN cache on page cache clear | CF-Cache-Status: MISS then HIT |
| WC -> Cloudflare | Cart/checkout/account NEVER cached | CF-Cache-Status: BYPASS on WC pages |
| Mollie -> Cloudflare | Webhook URL not blocked by WAF | Test payment: order status updates |
| GF -> Post SMTP | All notifications via SMTP | Test email delivered in 2 min, not spam |
| Relevanssi -> WP_Query | relevanssi_do_query() active on search | "glasbewassing" returns correct page |

### 8.4 Update Policy

| Type | Auto | Pre-Deploy Testing |
|---|---|---|
| Minor/Patch | Yes | None |
| Major | No | Staging: smoke test (Home, Contact, WC purchase, Search) |
| WP Core Minor | Yes | None |
| WP Core Major | No | Staging: full regression |

---

## 9. Content Architecture

### 9.1 Content Storage Model

```
wp_posts:     post_title, post_name, post_content (BLOCK HTML), post_type, post_status
wp_postmeta:  _wp_page_template, hds_*, rank_math_* (or _yoast_*)
```

All content as standard WordPress Blocks (HTML comments). Zero shortcodes in post_content. Custom fields in postmeta with consistent hds_ prefix.

### 9.2 Content Types

| Content | WP Type | Editor | Min Words |
|---|---|---|---|
| Service pages (7) | Page (Service template) | Block Editor | 300+ |
| Category landings (2) | Page (Cat Landing template) | Block Editor | 500+ |
| About pages (2) | Page (About template) | Block Editor | 500+/300+ |
| Contact/Quote (2) | Page + Gravity Forms shortcode | Block Editor | 150+ |
| Legal pages (4) | Page (Legal template) | Block Editor | 150-500+ |
| Blog posts (5-10) | Post | Block Editor | 500+ |
| Testimonials | hds_testimonial CPT | WP Admin | Quote text |
| Vacancies | hds_vacancy CPT | WP Admin | Description |
| FAQ items | Page + FAQ Block | Block Editor | 300+ combined |
| Company info | Customizer | Customizer UI | N/A |
| Navigation | WP Menu System | Appearance > Menus | N/A |
| Forms | Gravity Forms | GF Admin | N/A |

---

## 10. Information Architecture

### 10.1 Site Structure (Full Sitemap)

```
helderduidelijkschoon.nl/                           [HOME — P01]
|-- /glasbewassing/                                  [Service — P02]
|-- /gevelreiniging/                                 [Service — P03]
|-- /reguliere-schoonmaak/                           [Service — P04]  NEW (was 404)
|-- /vloeronderhoud/                                 [Service — P05]
|-- /vve-service/                                    [Service — P06]
|-- /oplevering-schoonmaak/                          [Service — P07]
|-- /industriele-schoonmaak/                         [Service — P08]
|-- /glas-en-gevel/                                  [Landing — P09]  NEW
|-- /schoonmaakdiensten/                             [Landing — P10]  NEW
|-- /over-hds/                                       [About — P11]
|-- /kwaliteit-veiligheid/                           [About — P12]
|-- /referenties/                                    [Trust — P13]
|-- /vacatures/                                      [Recruit — P14]
|-- /downloads/                                      [Resources — P15]
|-- /contact/                                        [Conversion — P16]  REBUILD (was 500)
|-- /offerte-aanvragen/                              [Conversion — P17]  NEW
|-- /bedankt/                                        [System — P32]  NEW
|-- /veelgestelde-vragen/                            [FAQ — P18]  NEW
|-- /privacyverklaring/                              [Legal — P19]  NEW
|-- /cookiebeleid/                                   [Legal — P20]  NEW
|-- /algemene-voorwaarden/                           [Legal — P21]  NEW
|-- /disclaimer/                                     [Legal — P22]  NEW
|-- /luchtreiniging/                                 [Product — P23]  NEW
|-- /winkel/                                         [Shop — P24]
|-- /product/{slug}/ (x14)                           [Product — P25]
|-- /winkelmand/                                     [Cart — P26]
|-- /afrekenen/                                      [Checkout — P27]
|-- /mijn-account/                                   [Account — P28]
|-- /kennisbank/                                     [Blog Index — P29]  NEW
|-- /kennisbank/{slug}/                              [Blog Post — P30]  NEW
|-- (any 404)                                        [404 — P31]  NEW
```

### 10.2 Page-to-Template Mapping

| Pages | Template | Priority |
|---|---|---|
| P01 | front-page.php | P0 |
| P02-P08 | page-templates/page-service.php | P0 |
| P09-P10 | page-templates/page-category-landing.php | P1 |
| P11-P12 | page-templates/page-about.php | P0 |
| P13-P15, P23 | page.php (default) | P1 |
| P16 | page-templates/page-contact.php | P0 |
| P17 | page-templates/page-quote.php | P1 |
| P18 | page-templates/page-faq.php | P2 |
| P19-P22 | page-templates/page-legal.php | P0 |
| P24-P28 | WooCommerce templates (plugin) | P1 |
| P29 | archive.php | P2 |
| P30 | single.php | P2 |
| P31 | 404.php | P0 |
| P32 | page.php | P0 |
## 11. URL Architecture
### 11.1 URL Conventions
Protocol: HTTPS only (HTTP->301). Non-www canonical (www->301). Trailing slash consistent WITH slash. Slug: Dutch, lowercase, hyphens, no diacritics. Depth: max 1 from root. Exceptions: /product/{slug}/, /kennisbank/{slug}/. No file extensions. No query parameters for pages. Blog: /kennisbank/{slug}/ (no date prefix). WC base: /winkel/.

### 11.2 Reserved/Blocked URLs
/xmlrpc.php: 403 (Nginx deny). /wp-admin/ non-whitelisted: 403 (Cloudflare WAF). /wp-login.php: redirect to custom (Wordfence). /?author={N}: 403. /wp-json/wp/v2/users: 403 (REST filter). /*?* non-WC query strings: disallowed in robots.txt.

### 12. Navigation Architecture
Desktop: [LOGO] DIENSTEN v OVER HDS v LUCHTREINIGING v CONTACT [TEL] [CART]. Dropdowns: DIENSTEN has Glas & Gevel (Glasbewassing, Gevelreiniging) + Schoonmaakdiensten (Reguliere, Vloer, VVE, Oplevering, Industrieel). OVER HDS has Over HDS, Kwaliteit, Referenties, Vacatures, Downloads. LUCHTREINIGING has Over Airfixr, Winkel, Mijn Account.

Mobile: hamburger icon toggles accordion. No JS dependency. aria-expanded. Keyboard: Enter/Space toggle, Escape close. Touch >= 44px.

Footer: 5-column. DIENSTEN, OVER HDS, CONTACT, LUCHTREINIGING, JURIDISCH. Company info from Customizer. Legal links. Social icons. Copyright line.

Breadcrumbs: Home > [Page Name] (flat). Exception: Home > Winkel > [Product]. Rank Math/Yoast + theme integration. Schema BreadcrumbList auto. Visible on all inner pages, not Home.

### 13. Page Template Architecture
Home (front-page.php): Hero -> Service Card Grid (conditional) -> USP Grid -> Client Logo Carousel (conditional:hide if empty) -> Testimonial (conditional) -> CTA Banner -> Service Area -> Latest Blog Posts (conditional). ALL sections editable via Block Editor (the_content()).

Service Page (page-templates/page-service.php): Breadcrumbs -> Hero (H1+subtitle+CTA) -> Content Area (intro, H2 approach, H2 services, H2 safety) -> Cross-Sell Services -> CTA Banner -> Optional FAQ. Min 300 words.

Contact (page-templates/page-contact.php): Breadcrumbs -> H1 -> Two-Column: Form 60% (Gravity Forms) + Contact Info 40% (phone tel:, email mailto:, address if MI-01, KVK/BTW if MI-02/03, hours if MI-04, social, map).

Other: Category Landing (Hero+Intro+Card Grid+CTA, 500+w). About (Hero+Content+Image+CTA). Quote (extended form+file upload). FAQ (H1+Intro+AccordionBlock, FAQPage schema). Legal (H1+Content+LastUpdated). Default (H1+Content). Blog single (Breadcrumbs+FeaturedImage+H1+Meta+Content+RelatedPosts+CTA). Blog index (H1+PostGrid+Pagination). Search (H1+Results+Pagination+GeenResultaten). 404 (PaginaNietGevonden+Search+Links+Contact).

### 14. Component Architecture
Types: Template Part (PHP in /template-parts/), Block Pattern (register_block_pattern), Custom Block (register_block_type with render callback), Block Style Variation (register_block_style), Plugin Component.

Global (every page): Header (logo,nav,phone,email,cart), Main Navigation (WP Menu), Footer (5-col), Cookie Banner (Complianz), Cookie Settings (Complianz), Breadcrumbs (SEO+Theme), Skip to Content, Back to Top (JS).

Block Patterns (16): hero-section, service-card-grid, usp-grid, cta-banner, content-with-image, service-icon-list, client-logo-carousel, testimonial-block, faq-accordion, cross-sell-services, job-vacancy-card, download-card-list, contact-info-map, latest-blog-posts, related-posts, 404-content.

Custom Blocks (4): hds/service-card (queries Page by ID), hds/testimonial (queries hds_testimonial CPT), hds/job-listing (queries hds_vacancy CPT, active only), hds/contact-info (reads Customizer theme_mod).

Block Styles (7): is-style-primary, is-style-secondary, is-style-cta (buttons), is-style-card, is-style-banner (groups), is-style-icon-list, is-style-no-bullet (lists).

Decision: Dynamic data from DB? NO->Block Pattern. YES->Custom Block.

### 15. Block Architecture
Block Pattern: register_block_pattern('hds/hero-section', [title, categories=['hds-patterns'], content='<!-- wp:group... blocks ...-->' ]). Also register_block_pattern_category('hds-patterns', [label]).

Custom Block: register_block_type('hds/testimonial', [editor_script, render_callback, attributes]). render_callback function queries data and returns HTML.

Block Style: register_block_style('core/button', [name=>'primary', label]).

### 16. Design System Integration
theme.json defines: color palette (primary, secondary, accent, neutral, success, warning, error), typography (heading font family, body font: Open Sans), font sizes (h1-h3, body, small: clamp() based responsive), spacing scale (4px-base: 4,8,12,16,24,32,48,64,96), custom properties (breakpoints, container 1200px, border radius tokens).

Typography scale: H1 2.25-3rem/1.75-2.25rem, H2 1.75-2.25/1.375-1.75, H3 1.375-1.75/1.125-1.5, H4 1.125-1.375/1-1.25, Body 1-1.125/1rem 400 1.6-1.7, Small 0.875rem, Button 1rem 600, Nav 1/1.125rem 500-600.

Breakpoints: Mobile 0-767px, Tablet 768-1023px, Desktop 1024-1279px, Wide 1280px+.

Icons: Phosphor/Font Awesome 6. Inline SVG preferred. .hds-icon: width:1em, height:1em.

### 17. SEO Architecture
SEO Plugin (Rank Math Pro/Yoast Premium): meta titles/descriptions per page, XML sitemaps (page, post, product), robots.txt, canonical URLs, Open Graph + Twitter Card tags, auto schema (WebSite, WebPage, BreadcrumbList, Article, Product), redirect manager.

Theme Custom Schema: LocalBusiness JSON-LD (Home, Contact, Over HDS via schema-localbusiness.php), Service JSON-LD (P02-P08 via schema.php per page), FAQPage (auto from FAQ Block), JobPosting (P14 per vacancy).

Theme Technical SEO: semantic HTML, breadcrumbs (visible+schema), image alt text, internal linking (cross-links per spec).

Metadata Template: [Page Title] -- HDS Onderhoudsdiensten | [Location]. Meta: 150-160 chars, keyword+location+value+CTA.

Structured Data Types (9): WebSite, WebPage, BreadcrumbList, LocalBusiness (HomeAndConstructionBusiness), Service (x7), FAQPage, Product (x14), JobPosting (per vacancy), Organization (sameAs).

XML Sitemap: /sitemap_index.xml (200), /page-sitemap.xml (200, was 500), /post-sitemap.xml, /product-sitemap.xml. Excluded: attachment pages, author archives, noindex pages (Bedankt, legal), cart, checkout, account.

### 18. Structured Data Architecture
LocalBusiness JSON-LD output via template-parts/schema-localbusiness.php. Included in header.php on Home/Contact/Over HDS. Fields: name, description, url, telephone (+31164652846), email, address (PostalAddress), geo (GeoCoordinates), openingHoursSpecification, areaServed (GeoCircle), priceRange, image, sameAs (FB, IG). Note: address/geo/hours require MI-01 and MI-04.

Service schema via hds_get_service_schema($post_id) in inc/schema.php. Fields: Service type, name, description, provider (Organization), areaServed (City), serviceType.

FAQPage: auto from Yoast/Rank Math FAQ Block. BreadcrumbList: auto from SEO plugin + theme.

### 19. Media Architecture
All images: WebP primary, PNG/JPEG fallback via picture. Compression: visually lossless (quality 85+) via ShortPixel/Imagify. Responsive: srcset 400w/800w/1200w + sizes. Lazy below fold. fetchpriority=high on LCP. Explicit width+height. Alt text: Dutch descriptive (non-decorative), empty (decorative). Filenames: lowercase-hyphens-dutch-keywords.webp.

WP Media Sizes: Thumbnail 150x150 (crop), Medium 600x600, Large 1200x1200, hds-card 400x300 (crop), hds-content 800x600, hds-hero 1600x900 (crop). Unused default sizes disabled.

Logo: SVG vector (primary), PNG 400x162 @2x fallback. In Customizer. Favicon: ICO 32x32+16x16, Apple Touch 180x180, Android 192x192+512x512.

PDFs: migrated from hds-onderhoudsdiensten.nl to helderduidelijkschoon.nl media library. Downloaded, validated, uploaded. Internal links updated. Legacy domain 301 redirects.

### 20. Forms Architecture
Three forms: Contact (GF-1, /contact/, 8 fields+reCAPTCHA), Offerte (GF-2, /offerte-aanvragen/, 12 fields+reCAPTCHA+file upload), Vacature (GF-3, /vacatures/, 5 fields+CV upload+reCAPTCHA).

Contact fields: Naam(text*), Bedrijf(text), E-mail(email*), Telefoon(tel,Dutch format), Onderwerp(dropdown*:Offerte/Vraag/Klacht/Anders), Bericht(textarea*,min10), Privacy(checkbox*,unchecked,links to /privacyverklaring/), reCAPTCHA v3(invisible)+Honeypot.

Quote adds: Gewenste dienst(checkboxes*), Type gebouw(dropdown), Postcode/Plaats(text*,NNNN AA regex), Beschrijving(textarea), Planning(dropdown), Hoe gevonden(dropdown), Bestand uploaden(file,max5MB,pdf/jpg/png/docx).

All forms: from info@..., to info@... (configurable), entries in DB (12mo retention contact/quote, 6mo vacature), reCAPTCHA v3+honeypot, Dutch errors inline with aria-describedby, redirect to /bedankt/?type={form}. File upload: server-side MIME validation, rename, size check. Emails: branded, Dutch, Post SMTP delivery, attachment via download link not inline.

### 21. WooCommerce Architecture
Configuration: Shop /winkel/, Cart /winkelmand/, Checkout /afrekenen/, Account /mijn-account/, Terms /algemene-voorwaarden/, Privacy /privacyverklaring/. EUR, dot thousand comma decimal, 2 decimals, excl. BTW, 21% tax, kg, cm, coupons enabled, reviews enabled(moderated), guest checkout enabled, inventory enabled, backorders disabled, HPOS enabled.

Payment: Mollie (iDEAL, Bancontact, cards, PayPal, SEPA) + Bank Transfer (BACS) fallback. Webhook configured in Mollie dashboard, bypasses Cloudflare WAF.

Shipping: Zone Nederland. Classes: Klein pakket (filters/lamps/UV-C), Groot pakket (Airfixr units). Rates: client decision (MI-14). Default flat rate per class.

Emails: all 10 types enabled. Branded (HDS logo), Dutch, from info@... via Post SMTP.

### 22. Security Architecture
6-layer defense:
1. Transport: HTTPS only, HSTS max-age=31536000 preload, TLS 1.3, SPF+DKIM+DMARC
2. CDN/Edge: Cloudflare WAF (block xmlrpc, rate-limit login, WP managed ruleset), DDoS, bot mgmt, SSL Full Strict
3. Server: XML-RPC disabled(403), dir listing disabled, file perms(dirs755/files644/wp-config400), wp-config above root or locked, DB prefix hds_, DISALLOW_FILE_EDIT=true, SFTP only
4. Authentication: Custom login URL (Wordfence), 2FA all admin accounts, wordfence brute force(max3), user enum prevention(block ?author=N, /wp-json/wp/v2/users)
5. App Firewall: Wordfence WAF, malware scan(daily), file integrity monitoring
6. App Logic: Input sanitization, output escaping, nonce verification, capability checks, prepared SQL

SSL: Cloudflare Full(strict), min TLS 1.3, Let's Encrypt(origin)+Cloudflare(edge), auto-renew.

Access Control: Admin(full), Editor(CRUD content+view forms+view analytics), Shop Mgr(WC products/orders/coupons), SEO Mgr(SEO settings+view analytics), Subscriber(read-only, own WC account).

### 23. Authentication & Authorization
Login: custom URL via Wordfence (not /wp-admin or /wp-login.php). Login limiting: 3 failures=IP lockout. 2FA: Wordfence on all Admin/Editor/Shop Manager accounts. Application passwords: disabled. XML-RPC: disabled.

Password policy: min 12 chars (Wordfence enforced). Password reset: email-based, max 1/hour/user.

Sessions: WP default cookie-based. Session timeout: 48h. Remember Me: 14 days. Force logout on password change.

API auth: REST API uses cookie auth for logged-in users. WC REST API: Consumer Key+Secret if needed. No API consumers at launch.

### 24. Performance Architecture
Budgets: LCP<2.5s, FID<100ms, CLS<0.1, INP<200ms, TTFB<600ms, Mobile weight<1.5MB, Desktop<3MB, SpeedIndex<3.4s, PSI mobile 90+, desktop 95+.

Implementation: Page cache(WP Rocket/FlyingPress), Object cache(Redis), Browser cache(1yr versioned), Critical CSS inline(head, auto via cache plugin), Non-critical CSS deferred, JS deferred(no render-blocking), No jQuery unless WC requires, No jQuery Migrate, Self-hosted fonts(font-display:swap+preload), WebP images(explicit dims, no CLS), Lazy below fold, fetchpriority=high on LCP, CDN(Cloudflare full-page+Polish+auto-minify), Clean DB(no old revisions/spam/transient garbage).

### 25. Caching Strategy
4-layer cache: [Browser 1yr versioned] -> [Cloudflare CDN full-page(bypass WC/admin/AJAX), static 30d] -> [WP Rocket page cache(all public, cleared on content change)] -> [Redis object cache(queries/transients/WP_Query)] -> [PHP OPCache(compiled bytecode)].

Cloudflare Page Rules bypass: /winkelmand/*, /afrekenen/*, /mijn-account/*, /wp-admin/*, /wp-json/wc/*, /?wc-ajax=*.

Purge triggers: post/page updated->WP Rocket->Cloudflare API. WC product updated->purge product+shop+category. Plugin/theme updated->purge all.

### 26. Image Optimization Strategy
Pipeline: Upload original(JPG/PNG) -> ShortPixel/Imagify compress(quality 85) -> Convert WebP -> Generate WP sizes(all) -> Serve via picture(WebP primary + fallback) -> Lazy below fold -> CDN cache.

picture element: source srcset(type=image/webp) + img srcset(fallback) with sizes attribute. Explicit width+height. loading=lazy, decoding=async.

Hero: fetchpriority=high, preloaded in head. Content: lazy. Thumbnails: fixed dims, object-fit:cover. Decorative: alt="" role=presentation. SVGs: inline, aria-hidden=true if decorative.

### 27. Accessibility Architecture (WCAG 2.2 AA)
Target: WCAG 2.2 AA all criteria + 2.5.8 Target Size (AAA adopted as AA, 44px).

Requirements (20): Color contrast 4.5:1/3:1, Keyboard all elements, Skip to content, Semantic HTML H1-H6 no skips, ARIA landmarks, Alt text all images, Form labels+errors(aria-describedby), Descriptive link text(no klik hier), 200% zoom usable no horizontal scroll, No auto-play prefers-reduced-motion, Touch >=44px, lang=nl-NL, Unique page titles, aria-live dynamic content, Consistent navigation, Consistent component IDs, Lighthouse=100, Screen reader forms/nav/shop, WC checkout accessibility, Dropdown keyboard(Enter/Space/Esc).

Testing: axe DevTools(zero critical+serious), WAVE(zero errors), Lighthouse(=100), Manual keyboard(tab-through every page), Manual screen reader(NVDA VoiceOver), Color contrast(all pass AA), 200% zoom(no loss no scroll), Real mobile(VoiceOver iOS TalkBack Android, min 3 pages).

### 28. Analytics Architecture
GA4: property HDS Onderhoudsdiensten, data stream helderduidelijkschoon.nl, enhanced measurement all enabled, retention 14mo, IP anonymization enabled(default), bot filtering enabled, internal filter(office IP MI).

GTM: all scripts via GTM, snippet in head, Consent Mode v2(Complianz->GTM consent signals), marketing tags deferred until consent, Data Layer pushes for conversions.

Events: phone_click(tel:), email_click(mailto:), form_submission(/bedankt/?type=contact), quote_request(/bedankt/?type=offerte), add_to_cart(WC), purchase(WC), cookie_consent_accepted(banner Accept).

Reporting: monthly to client(traffic, conversions, landing pages, sources, SEO). Looker Studio dashboard post-launch.

### 29. Logging & Monitoring
Logs: PHP error(debug.log, 30d), Security(Wordfence, 90d), Form submissions(GF entries, 12mo auto-delete), WC orders(WC logs, 7yr), Backup(backup plugin, 12mo), Email(Post SMTP, 90d), Uptime(UptimeRobot, 12mo).

Alerts: uptime<99.9% 24h -> Dev+Client, SSL<30d -> Dev, backup failure -> Dev, disk>80% -> Dev, malware detected -> Dev, PSI mobile<85 weekly -> Dev, form email failure>5% -> Dev, 404>10/day GSC -> Dev.

### 30. Backup & Recovery Strategy
Schedule: Full daily(nightly) 30d+4w+12m retention. Pre-update before every plugin/WP update. DB-only every 6h 7d. WC order export monthly CSV 7yr. All offsite cloud.

RTO/RPO: Server failure<4h/<24h. Malware<4h/<24h. Accidental deletion<1h(revision)/<4h(backup)/<24h. DNS<2h/N/A.

Verification: monthly restore to staging, verify pages/forms/WC/admin. Runbook documented, printed copy for client with emergency contacts+step-by-step.

---

## 31. Deployment Architecture

### 31.1 CI/CD Pipeline

Developer local -> git push to branch -> DeployHQ/GitHub Actions/WP Engine Git Push -> deploy to target environment -> clear caches (WP Rocket + Cloudflare) -> smoke tests (future: Playwright) -> notify.

### 31.2 Environments

| Environment | URL | PHP | WP_DEBUG | Indexing | Access |
|---|---|---|---|---|---|
| Local | hds.local | 8.2+ | true | n/a | Developer only |
| Staging | staging.helderduidelijkschoon.nl | 8.2+ (prod mirror) | true | noindex + password | Dev + Client |
| Production | helderduidelijkschoon.nl | 8.2+ | false (log only) | index, follow | Public + Dev + Client |

### 31.3 Workflow

git push staging branch -> auto-deploy to staging -> dev + client QA -> client sign-off -> merge staging to main -> auto-deploy to production -> clear caches (WP Rocket + Cloudflare + Redis) -> smoke test -> verify GA4 + GSC.

### 31.4 Rollback

Backup from immediately before deployment -> restore to staging -> deploy to production. Time: <30 min plugin update, <2 hours complete site failure. Old site backup verified before old site takedown.

---

## 32. Environment Strategy

### 32.1 Local Development

hds.local. PHP 8.2+, WordPress 6.7+, all plugins. WP_DEBUG=true. Developer-only access. Local by Flywheel or DevKinsta recommended for easy setup.

### 32.2 Staging

staging.helderduidelijkschoon.nl. Production-mirror stack (identical PHP, WP, plugins). WP_DEBUG=true. noindex + nofollow meta tag on all pages. Password-protected via .htaccess or hosting-level authentication. Database may be production copy (anonymized for GDPR if contains personal data). Used for: client review, QA testing, update testing before production.

### 32.3 Production

helderduidelijkschoon.nl. PHP 8.2+, WordPress 6.7+, all plugins. WP_DEBUG=false. WP_DEBUG_LOG=true. WP_DEBUG_DISPLAY=false. Public access. Developer + Client admin access.

---

## 33. CI/CD Recommendations

### 33.1 Deployment Service

DeployHQ or GitHub Actions (recommended). Git-based deployment. Branch-to-environment mapping: dev branches -> local only, staging branch -> staging environment (auto-deploy), main branch -> production environment (manual approval gate after merge to main).

### 33.2 Pipeline Steps

1. Pre-deploy: trigger backup (auto)
2. Deploy: sync files to target environment
3. Post-deploy: clear WP Rocket page cache, purge Cloudflare cache (API), flush Redis object cache
4. Smoke test: Homepage returns 200, Contact form test submission, WC product page loads, Mobile menu works
5. Future: Playwright automated end-to-end tests

### 33.3 Recommended Smoke Tests

| Test | Endpoint | Expected |
|---|---|---|
| Homepage | GET / | HTTP 200, contains tagline |
| Contact Form | POST /contact/ | Redirect to /bedankt/, email delivered |
| WC Product | GET /winkel/ | HTTP 200, products visible |
| Mobile Menu | Responsive viewport | Hamburger functional |

---

## 34. Migration Architecture

### 34.1 Current Site Profile

WordPress 6.2.9 + Divi 4.16.1 + WooCommerce 8.2.5 + Formidable Forms. Hosting: unknown. Legacy domain: hds-onderhoudsdiensten.nl (PDF hosting).

### 34.2 Pre-Migration Tasks

1. Full Screaming Frog crawl: export all URLs, status codes, titles, meta descriptions
2. Export GSC data (16 months): queries, pages, clicks, impressions, CTR, average position
3. Document all backlinks (Ahrefs, Semrush, or GSC)
4. Export Google Business Profile data (NAP, categories, reviews)
5. Screenshot every current page for visual archive
6. Export current WordPress media library (images + PDFs)
7. Export WooCommerce products, orders, customers
8. Download all PDFs from legacy domain
9. Verify domain registrar login credentials
10. Verify hosting control panel login credentials
11. Document current DNS records (A, CNAME, MX, TXT)
12. Verify email delivery still functions after DNS changes

### 34.3 During Migration (Staging)

All 32 pages built. All images optimized. All PDFs migrated to primary domain. All forms built + tested. WooCommerce products imported + configured. All 301 redirects configured + tested. SEO metadata written for all pages. All structured data validated (Google Rich Results Test). XML Sitemap generated + validated. robots.txt configured. Cookie consent banner configured + tested. GA4 + GTM configured (Tag Assistant verified). Performance optimization: PSI 90+ mobile. Accessibility audit: zero critical issues. Cross-browser testing (Chrome, Firefox, Safari, Edge). Mobile/tablet testing on real devices. Client review + approval.

### 34.4 Launch Day

1. Final backup of old live site (verified: restore to test environment)
2. Staging database: search-replace for production domain
3. Production environment: SSL verified
4. Deploy files to production
5. Import database to production
6. Clear all caches (WP Rocket, Cloudflare, Redis)
7. Verify 301 redirects on production
8. Contact form test submission on production
9. Offerte form test with attachment on production
10. WooCommerce test purchase on production
11. Submit XML Sitemap to Google Search Console + Bing Webmaster Tools
12. Verify robots.txt accessible
13. Cookie consent banner verified on production (fresh browser)
14. Verify GA4 real-time reports showing traffic
15. Verify all old URLs returning correct 301 or 410

### 34.5 DNS TTL Procedure

24 hours before launch: lower DNS TTL to 300 seconds (5 minutes). Verify propagation via whatsmydns.net. Proceed with launch. 24 hours after launch: restore TTL to normal (3600 or hosting default).

### 34.6 Content Freeze

Notify client: "Do not edit the old website from [date]. All content updates should be documented and provided to the development team for inclusion in the new site." Take final content snapshot of old site at start of migration. If critical business information changes during migration (phone, address), client notifies developer directly.

---

## 35. Redirect Architecture

### 35.1 Implementation

Rank Math Pro built-in redirect manager (or Redirection plugin if using Yoast SEO). All rules tested pre-launch. Zero redirect chains enforced (verify via httpstatus.io).

### 35.2 301 Permanent Redirects

| Old URL | New URL | Reason |
|---|---|---|
| /glasbewassing (no slash) | /glasbewassing/ | Trailing slash standardization |
| /vve | /vve-service/ | Canonical URL |
| /vve/ | /vve-service/ | Canonical URL |
| /?page_id=318 | /reguliere-schoonmaak/ | Broken ID-based URL |
| http://helderduidelijkschoon.nl/* | https://helderduidelijkschoon.nl/* | HTTPS enforcement |
| http://www.helderduidelijkschoon.nl/* | https://helderduidelijkschoon.nl/* | www + HTTPS |
| https://www.helderduidelijkschoon.nl/* | https://helderduidelijkschoon.nl/* | www removal |

### 35.3 410 Gone (Permanently Removed)

| URL | Reason |
|---|---|
| /2015/06/29/hallo-wereld/ | Default WordPress post deleted |
| /2015/08/25/kwaliteit-veiligheid/ | Suspected redirect post deleted |

### 35.4 Legacy Domain Resolution

hds-onderhoudsdiensten.nl PDF URLs: 301 redirect to new helderduidelijkschoon.nl PDF URLs. Decision: point legacy domain to primary OR maintain redirects (client decides).

### 35.5 Attachment Pages

Redirect attachment page URLs to parent post/page. Remove from XML Sitemap. Use Yoast "Redirect attachment URLs to parent post" setting if using Yoast, or manual redirect rules.

### 35.6 Post-Launch Monitoring

Daily (30 days): Check GSC for crawl errors and 404s. Weekly: verify all redirects still working (httpstatus.io or Screaming Frog).

---

## 36. Error Handling Strategy

### 36.1 404 Error Page (404.php)

Heading: "Pagina niet gevonden". Message: "De pagina die u zoekt bestaat niet of is verplaatst." Search bar (prominent). Links: Home, Diensten overview, Contact, Veelgestelde Vragen. Phone: 0164-652846. Email: info@helderduidelijkschoon.nl. Must return actual HTTP 404 status code.

### 36.2 500 Error (Server Error)

Server-level custom 500 error page (not WordPress-dependent, in case PHP/WP itself is down). Message: "Er is een technische storing. Onze excuses voor het ongemak. Neem telefonisch contact op: 0164-652846." Production: WP_DEBUG=false, WP_DEBUG_DISPLAY=false. Errors logged to /wp-content/debug.log via WP_DEBUG_LOG=true.

### 36.3 Form Error Handling

Validation failure: inline field errors (Gravity Forms default). Red text, Dutch language. Programmatic association via aria-describedby. Server error during submission: "Er is een fout opgetreden bij het verzenden. Probeer het opnieuw of neem telefonisch contact op via 0164-652846." Logged for developer. Spam detection: silent failure (reCAPTCHA v3 blocks without user-visible error). Backend logs spam attempts. File upload too large: "Het bestand is te groot. Maximale grootte: 5 MB." File upload wrong type: "Dit bestandstype is niet toegestaan. Toegestane types: PDF, JPG, PNG, DOCX." Server-side validation: MIME type check beyond client-side extension check.

### 36.4 WooCommerce Error Handling

Out of stock: "Niet op voorraad" with disabled add-to-cart button. Cart errors: inline notice (WooCommerce default). Checkout errors: inline field errors with clear correction instructions in Dutch. Payment failure: specific error message from Mollie gateway. All WC errors logged in WooCommerce -> Status -> Logs for admin review.

---

## 37. Coding Standards

### 37.1 PHP

WordPress Coding Standards enforced via PHP_CodeSniffer + WPCS ruleset. Yoda conditions for equality checks. Strict comparisons (===). All output escaped: esc_html() for HTML context, esc_attr() for attributes, esc_url() for URLs, wp_kses() for allowed HTML. All inputs sanitized: sanitize_text_field(), sanitize_email(), etc. Nonces on all custom forms: wp_nonce_field() + check_admin_referer() or wp_verify_nonce(). Prepared SQL statements: $wpdb->prepare(). No eval(). No base64_decode(). No extract(). Internationalization: __() and _e() for all user-facing strings with textdomain 'hds'. Translation functions even for single-language site (forward-compatibility).

### 37.2 CSS

BEM-like naming for custom components: .hds-[block]__[element]--[modifier]. CSS custom properties for design tokens: var(--hds-color-primary). Mobile-first media queries: min-width breakpoints. No !important except for utility override classes (.hds-sr-only, .hds-hidden). No ID selectors for styling. Maximum nesting depth: 3 levels.

### 37.3 JavaScript

Vanilla JavaScript (no jQuery dependency unless WooCommerce requires it). ES6+ syntax. No inline scripts (use wp_add_inline_script sparingly for config data). Event delegation on dynamic elements. Progressive enhancement: core functionality (navigation, forms) works without JavaScript. No console.log() in production code.

### 37.4 File Organization

One class or concern per PHP file in /inc/. Helper functions in template-functions.php. Template parts in /template-parts/. Assets in /assets/ with css/js/fonts/images subdirectories.

### 37.5 Versioning & Cache Busting

wp_enqueue_style() and wp_enqueue_script() with filemtime() for automatic cache busting on file change. Example: wp_enqueue_style('hds-base', HDS_THEME_URI.'/assets/css/base.css', [], filemtime(HDS_THEME_DIR.'/assets/css/base.css')).

---

## 38. Folder Structure

```
wp-content/themes/hds/                   # Theme root
|-- theme.json                           # Design tokens, block styles, theme supports
|-- style.css                            # Theme metadata (Theme Name, Author, etc.)
|-- functions.php                        # Bootstrap: require inc/*.php
|-- index.php                            # Fallback template
|-- screenshot.png                       # Theme preview (1200x900)
|-- assets/
|   |-- css/
|   |   |-- base.css                     # Reset, typography (theme.json vars), utilities
|   |   |-- layout.css                   # Grid, header, footer, content area
|   |   |-- components.css               # Cards, banners, buttons, forms, navigation
|   |   |-- blocks.css                   # Custom block styles (is-style-* variations)
|   |-- js/
|   |   |-- navigation.js                # Mobile menu, dropdown keyboard, focus trap
|   |   |-- main.js                      # Back-to-top, smooth scroll, progressive enhancements
|   |-- fonts/
|   |   |-- open-sans-regular.woff2      # Self-hosted (subset: Latin + Dutch diacritics)
|   |   |-- open-sans-600.woff2
|   |   |-- open-sans-700.woff2
|   |-- images/
|       |-- logo.svg                     # Site SVG logo
|-- inc/
|   |-- setup.php                        # Theme supports, nav menus, image sizes, disabled features
|   |-- enqueue.php                      # CSS/JS enqueuing (filemtime versioning)
|   |-- custom-post-types.php            # Register hds_testimonial, hds_vacancy CPTs
|   |-- custom-fields.php                # ACF field groups or register_post_meta
|   |-- customizer.php                   # Company Information section
|   |-- block-patterns.php               # register_block_pattern() for all 16 patterns
|   |-- block-styles.php                 # register_block_style() for 7 style variations
|   |-- template-functions.php           # Helpers: get_company_info(), hds_breadcrumbs(), etc.
|   |-- schema.php                       # JSON-LD schema generation functions
|-- template-parts/
|   |-- header.php                       # Logo, nav, phone (tel:), email (mailto:), cart icon
|   |-- footer.php                       # 5-col layout, company info, legal links, social, copyright
|   |-- breadcrumbs.php                  # Visible breadcrumb render + Schema BreadcrumbList
|   |-- schema-localbusiness.php         # JSON-LD LocalBusiness output
|-- page-templates/
|   |-- page-service.php                 # Service pages (P02-P08)
|   |-- page-category-landing.php        # Category landing pages (P09, P10)
|   |-- page-about.php                   # About pages (P11, P12)
|   |-- page-contact.php                 # Contact page (P16)
|   |-- page-quote.php                   # Offerte Aanvragen (P17)
|   |-- page-faq.php                     # Veelgestelde Vragen (P18)
|   |-- page-legal.php                   # Legal pages (P19-P22)
|-- front-page.php                       # Home page (P01)
|-- page.php                             # Default page template (P13-P15, P23, P32)
|-- single.php                           # Blog post (P30)
|-- archive.php                          # Blog index (P29) + vacancy archive
|-- search.php                           # Search results
|-- 404.php                              # Custom 404 (P31)
```

---

## 39. Naming Conventions

### 39.1 PHP Functions

Prefix: hds_. Theme setup: hds_setup(). Hook callbacks: hds_on_init(), hds_register_cpts(). Template functions: hds_get_company_info(), hds_breadcrumbs(). Schema generation: hds_get_localbusiness_schema(), hds_get_service_schema($post_id). Block render callbacks: hds_render_testimonial($attributes), hds_render_job_listing($attributes). CPT labels: 'HDS Referentie', 'HDS Vacature'.

### 39.2 CSS Classes

Custom components: .hds-[component] (e.g., .hds-hero, .hds-card, .hds-banner). Child elements: .hds-[component]__[element] (e.g., .hds-card__title, .hds-card__image). Modifiers: .hds-[component]--[modifier] (e.g., .hds-card--featured). WordPress block styles use native naming: .is-style-primary, .is-style-cta, .is-style-card.

### 39.3 Custom Fields / Post Meta

Prefix: hds_. Examples: hds_subtitle, hds_hero_image, hds_star_rating, hds_author_name, hds_company_name, hds_hours_per_week. Consistent lowercase with underscores.

### 39.4 CPT Keys

Post type keys: hds_[type]. Examples: hds_testimonial, hds_vacancy, hds_faq. Rewrite slugs: Dutch descriptive (vacatures, faq).

### 39.5 Image Filenames

Lowercase-hyphens-dutch-keywords.webp. Service images: [service]-[context]-[location].webp. Examples: glasbewassing-kantoor-bergen-op-zoom.webp, vloeronderhoud-marmoleum-school.webp.

### 39.6 Block Names

Block registration: hds/[block-name]. Examples: hds/service-card, hds/testimonial, hds/job-listing, hds/contact-info. Block patterns: hds/[pattern-name]. Examples: hds/hero-section, hds/cta-banner, hds/service-card-grid.

---

## 40. Future Scalability

### 40.1 Capacity Planning

Current: <100 concurrent users (local B2B site). Managed WordPress hosting handles this without scaling. Vertical scaling: upgrade hosting plan (more PHP workers, more RAM, more SSD) — handled by hosting provider, no architecture change. Horizontal scaling (not planned): Cloudflare load balancing across multiple WordPress instances with shared Redis and externalized media (S3/Cloudflare R2). Only if business grows significantly.

### 40.2 Near-Term Enhancements (0-6 Months)

Google Ads landing pages (new template). Location-specific service pages (/schoonmaakbedrijf-bergen-op-zoom/ etc., confirmed by MI-05). Case study / portfolio pages (new CPT or blog posts). Newsletter integration (Mailchimp/MailerLite + signup form). WhatsApp Business button (floating on mobile). Online booking system (Calendly/Bookly integration). Live chat widget (Tidio).

### 40.3 Medium-Term (6-18 Months)

Client self-service portal (custom WP user area: schedules, issues, invoices). Automated quoting engine (multi-step form with pricing logic). Multilingual English (WPML/Polylang + hreflang — only if international expansion planned). Review aggregation (Google + Facebook reviews on site). Advanced analytics dashboard (Google Looker Studio). Job application tracking system (ATS integration). Mobile app for cleaning staff (job scheduling, check-in/out, photo reporting).

### 40.4 Long-Term (18+ Months)

Headless WordPress (API-driven, React/Vue frontend). CRM integration (HubSpot, Simileader, Teamleader — contact form integration with CRM). IoT/sensor-based cleaning (data integration from smart sensors). Franchise/multi-location model (WordPress multisite or multi-location architecture). E-learning platform for staff training (LMS integration).

### 40.5 Architecture Support for Scalability

Block-based theme: content portable across any theme. No page builder lock-in: migration to headless or new theme is straightforward. Custom Post Types: independently queryable via REST API for headless/mobile app consumption. Gravity Forms: API integrations support complex workflows, 40+ third-party integrations. GTM: new marketing tags added without developer intervention. Git-based deployment: rollback, code review, multi-developer workflows built in. JSON-LD schema: clean separation from HTML, easily extended.

---

## 41. Risks & Constraints

### 41.1 Technical Risks

| Risk | Mitigation |
|---|---|
| Data loss during migration | Full backup before any step. Test restore verified. Offsite storage. |
| Email delivery interruption | Post SMTP + SPF/DKIM/DMARC. Email log. Weekly deliverability test. |
| Performance degradation after launch | Pre-launch benchmarks. Post-launch monitoring. CDN + caching configured pre-launch. |
| Plugin conflict post-launch | Identical staging stack. Full smoke test before deployment. |
| Security breach | Wordfence + 2FA + XML-RPC disabled + auto-updates + daily malware scans. |
| Backup failure | Daily verification. Monthly test restore. Alerts on failure. |
| Hosting outage | Managed host with 99.9%+ SLA. UptimeRobot monitoring. Client has support number. |
| Domain expiry | Auto-renew enabled. Client reminded at 90/60/30 days before expiry. |

### 41.2 Content Risks

| Risk | Mitigation |
|---|---|
| Client delays providing MI items | Early communication. Phase 0 deadline. Parallel work. Default values where acceptable. |
| Legal review delays | Lawyer engaged Sprint 0. Draft content ready Sprint 3. |
| Client logos not available (permissions) | Section hidden if empty. Logos added incrementally post-launch. |
| Project photos not available | Stock photography as fallback. Real photo shoot planned post-launch. |

### 41.3 Business Risks

| Risk | Mitigation |
|---|---|
| Temporary traffic/ranking drop | URLs preserved. 301 redirects. Sitemap submitted. GSC daily monitoring. Normalize in 2-4 weeks. |
| Budget exceeded | Fixed-scope specification. Phase-based delivery. Change requests as separate scope. |
| Airfixr product line irrelevant | /luchtreiniging/ landing page explains connection. If removed, WC scope reduced. |

### 41.4 Hard Constraints

| # | Constraint | Enforced By |
|---|---|---|
| C01 | No third-party page builders | Code review. Theme architecture. |
| C02 | Native Block Editor only | WordPress configuration. No page builder plugins installed. |
| C03 | PHP 8.2+ | Hosting verification. wp-config.php check. |
| C04 | WordPress 6.7+ | Fresh install. |
| C05 | All custom code in Git | Git repository. Deployment pipeline. |
| C06 | Fonts self-hosted | No Google Fonts enqueue in theme. |
| C07 | XML-RPC disabled | Server-level Nginx/Apache rule. Verified by curl. |
| C08 | DISALLOW_FILE_EDIT = true | wp-config.php constant. |
| C09 | DB prefix changed from wp_ | wp-config.php $table_prefix. |
| C10 | All output escaped | PHP_CodeSniffer + code review. |
| C11 | Legal pages reviewed before launch | Launch gate. No launch without lawyer sign-off. |

---

## 42. Assumptions

| ID | Assumption | Validation |
|---|---|---|
| ASM01 | Client wants complete rebuild (not repair) | Client sign-off in Phase 0 |
| ASM02 | Client has domain/hosting/WP/Google account access | Verified in Phase 0 |
| ASM03 | WordPress is confirmed CMS | Client confirmation Phase 0 |
| ASM04 | Client accepts block-based theme (no page builder) | Client confirmation Phase 0 |
| ASM05 | Client provides MI-01 through MI-25 before phase deadlines | Tracked against Phase 0-3 deadlines |
| ASM06 | Client engages legal counsel for privacy review | Confirmation before Phase 3 |
| ASM07 | WooCommerce webshop for Airfixr retained | Client confirmation Phase 0 |
| ASM08 | Only Dutch market (West-Brabant/Zeeland) served | Client confirmation Phase 0 |
| ASM09 | 8-9 week timeline acceptable | Client sign-off on roadmap |
| ASM10 | Current hosting can be replaced/upgraded | Verified in Phase 0 |
| ASM11 | GA4 analytics acceptable (with consent mode) | Client confirmation Phase 0 |
| ASM12 | Client maintains site post-launch with dev support | Discuss in Phase 0 |
| ASM13 | No international expansion in 18 months | Client confirmation Phase 0 |

---

## 43. Development Guidelines

### 43.1 Environment Setup

Local development: Local by Flywheel or DevKinsta for easy WordPress local setup. VS Code with PHP IntelliSense and WordPress Snippets extensions. Git for version control (GitHub or GitLab). WP-CLI for command-line WordPress management.

### 43.2 Development Workflow

Create feature branch from dev -> develop locally -> commit with descriptive messages -> push to dev branch -> test locally -> create Pull Request -> code review -> merge to staging branch -> auto-deploy to staging -> client QA -> merge to main -> auto-deploy to production.

### 43.3 Commit Message Format

[feat] New service page template. [fix] Contact form email not sending. [refactor] Extract breadcrumb logic into template function. [style] Update button hover states per design tokens. [docs] Add ADR-006 for SMTP decision. [perf] Defer non-critical CSS. [test] Add smoke tests for WC checkout. [chore] Update plugin dependencies.

### 43.4 Code Review Checklist

Coding standards met (PHP_CodeSniffer clean). Output escaped. Inputs sanitized. Nonces present on custom forms. No debug code (console.log, var_dump). No commented-out code. Responsive tested (mobile/tablet/desktop). Accessibility tested (axe DevTools clean, keyboard navigable). No performance regression. Git commit message descriptive. Documentation updated if architecture changed.

### 43.5 Pre-Commit Checks

PHP_CodeSniffer + WPCS ruleset. ESLint for JavaScript. Zero errors allowed before commit.

---

## 44. Architecture Decision Records (ADRs)

### ADR-001: Hybrid Block Theme (Not FSE)

**Decision:** Use Hybrid Block Theme: theme.json for design tokens + PHP templates for layouts + Block Editor for content areas. NOT Full Site Editing (FSE). **Rationale:** PHP templates are predictable, version-controlled, and cannot be accidentally broken by clients in the Site Editor. theme.json provides centralized design token management. Block Editor gives clients flexibility within predefined layouts. Forward-compatible with potential FSE migration. **Rejected:** True FSE theme (too immature, client could break layouts). GeneratePress/Kadence (accepted as fallback — reduces Sprint 1 effort but less control). **Consequences:** Developer must maintain PHP templates. Client cannot modify template layouts without developer intervention (acceptable for this B2B site).

### ADR-002: Native Block Editor Only (No Page Builder)

**Decision:** Use ONLY native Block Editor (Gutenberg) for all content layout. No third-party page builder. **Rationale:** Content stored as standard Block HTML comments — portable across themes, future-proof, no vendor lock-in. WordPress core commitment to Block Editor. Performance: no additional CSS/JS from page builder frameworks. **Rejected:** Divi (lock-in, performance, currently broken), Elementor (same), any page builder (same). **Consequences:** Custom block patterns must be built. Client trained on Block Editor.

### ADR-003: Flat URL Structure (Max Depth 1)

**Decision:** Maintain flat URL structure. All pages at root level. Exceptions: /product/{slug}/, /kennisbank/{slug}/. **Rationale:** Preserves existing URL equity for already-ranked pages. Simpler redirects (fewer rules). SEO-friendly (shorter, keyword-rich). **Consequences:** Breadcrumbs show flat hierarchy (Home > Page). Category landing pages don't nest URLs. Acceptable tradeoff.

### ADR-004: CPT for Vacancies, Pages for Services

**Decision:** Services use standard Pages (P02-P08). Vacancies use CPT hds_vacancy. Testimonials use CPT hds_testimonial with public=false. **Rationale:** Services are static content (infrequent edits, no archive) — Pages are simpler. Vacancies have a lifecycle (publish -> expire) and benefit from CPT structure (archive, structured data per post). Testimonials need CPT for structured data but must avoid URL conflict with /referenties/ Page. **Consequences:** No service archive. Vacancy CPT requires custom block for display.

### ADR-005: Relevanssi for Search

**Decision:** Relevanssi (free tier) as search engine. **Rationale:** Partial matching (fuzzy) for Dutch queries. Relevance-sorted results. Custom field indexing. Better than WP native search (exact match only, no relevance scoring). **Rejected:** Native WP search (insufficient). Elasticsearch/Algolia (overkill for <50 pages). **Consequences:** Index rebuild after content import. Minor CPU overhead on indexing.

### ADR-006: Post SMTP + External SMTP Service

**Decision:** Mandatory transactional email service via Post SMTP + SendGrid/Mailgun/Amazon SES. **Rationale:** Reliable delivery for form notifications and WC orders. Hosting-provided SMTP is often unreliable (blacklisting, no monitoring). SPF/DKIM/DMARC for authentication. Post SMTP provides email logging. **Consequences:** External dependency. Monthly sending limits (adequate for B2B volume <1000 emails/month).

### ADR-007: Redirect via SEO Plugin

**Decision:** Manage 301 redirects through SEO plugin (Rank Math built-in redirect manager or Redirection plugin if using Yoast). **Rationale:** 301 management in same tool as SEO metadata. No .htaccess manual editing. Redirect logging and 404 monitoring built-in. **Consequences:** Plugin dependency. Both Rank Math and Redirection are well-maintained and widely used.

---

## 45. Implementation Roadmap

| Sprint | Week | Goal | Key Deliverables |
|---|---|---|---|
| **Sprint 0** | 0 | Prerequisites | Architecture decisions, hosting provisioned, client answers Q01-Q18, Git repo, GA4/GSC |
| **Sprint 1** | 1-2 | Foundation | WP 6.7+ install, all plugins, Cloudflare+SSL, backups, theme foundation, design system, 16 block patterns |
| **Sprint 2** | 3-4 | Core Pages | Home page, 7 service pages (300+w), 2 landings (500+w), Contact form, Offerte form, Bedankt, 404 |
| **Sprint 3** | 5 | Supporting Pages | About pages, Referenties+CPT, Vacatures+CPT, Downloads, FAQ, Legal pages (4) |
| **Sprint 4** | 5-6 | WooCommerce | WC config, 14 products, Mollie payment, shipping, WC emails, Luchtreiniging landing |
| **Sprint 5** | 6-7 | SEO+Analytics | 32 meta titles/descriptions, 9 schema types, 301 redirects, sitemap, GA4+GTM, conversion tracking |
| **Sprint 6** | 7 | Compliance+Security | Complianz, GDPR consent, Wordfence+2FA, accessibility audit, legal review |
| **Sprint 7** | 8 | QA | Full functional, SEO, performance, cross-browser, mobile, client approval |
| **Sprint 8** | 8-9 | Launch | Pre-launch, deploy, verify 301, submit sitemap, post-launch verification, handover+training |

---

## 46. Definition of Done

### 46.1 Story-Level Definition of Done

A user story is **Done** when all 18 conditions are met:

1. Code written to WordPress Coding Standards (PHP_CodeSniffer + WPCS clean)
2. All output escaped, all inputs sanitized
3. Nonces present on all custom forms
4. Feature works on staging environment
5. Responsive: mobile, tablet, desktop verified
6. Cross-browser: Chrome, Firefox, Safari, Edge (latest 2 versions)
7. Accessibility: axe DevTools zero critical + zero serious issues
8. Lighthouse Accessibility score = 100 (if applicable)
9. Keyboard navigation test passed for all interactive elements
10. All acceptance criteria met (per story definition in BKLG-001)
11. Content reviewed by native Dutch speaker (if Dutch content)
12. Tested on real mobile device (if UI change)
13. No debug code (console.log, var_dump, WP_DEBUG_DISPLAY)
14. Git commit with descriptive message
15. Code review passed (if applicable)
16. Client approved (if client-facing feature)
17. Documentation updated (if architectural change: update SAD, ADR, RTM)
18. Deployed to staging and verified

### 46.2 Project-Level Definition of Done

The project is **Done** when all 8 conditions are met:

1. All 274 requirements traced and verified in RTM-001
2. All 85 user stories completed per Story-Level Definition of Done
3. All 32 pages published with final Dutch content
4. All 312 acceptance criteria from MPS-001 Section 37 met
5. Pre-launch checklist (MPS-001 Section J2) all 25 items complete
6. Post-launch verification (MPS-001 Section J3) all items verified
7. Client sign-off on Launch Readiness Report (MPS-001 Section J4)
8. Handover completed: client self-sufficient, "Website Beheergids" delivered

---

## Appendix A: Reference Documents

| Document | Title | Role |
|---|---|---|
| MPS-001 | Master Project Specification | Requirements, IA, sitemap, full specs |
| ARR-001 | Architecture Readiness Review | 45 issues, 12 blockers, readiness score 74/100 |
| RTM-001 | Requirements Traceability Matrix | 274 requirements, full bidirectional traceability |
| BKLG-001 | Development Backlog | 85 stories, 420 points, 9 sprints |
| RS-01..08 | Rebuild Specification | Detailed implementation specifications |

## Appendix B: Glossary

| Term | Definition |
|---|---|
| ADR | Architectural Decision Record |
| AVG | Algemene Verordening Gegevensbescherming (Dutch GDPR) |
| BTW | Belasting over de Toegevoegde Waarde (Dutch VAT, 21%) |
| CLS | Cumulative Layout Shift (Core Web Vital) |
| CPT | Custom Post Type |
| FSE | Full Site Editing (block-based theme editing with HTML templates) |
| GA4 | Google Analytics 4 |
| GBP | Google Business Profile |
| GSC | Google Search Console |
| GTM | Google Tag Manager |
| HPOS | High-Performance Order Storage (WooCommerce) |
| HSTS | HTTP Strict Transport Security |
| KVK | Kamer van Koophandel (Dutch Chamber of Commerce) |
| LCP | Largest Contentful Paint (Core Web Vital) |
| MI-XX | Missing Information item (see MPS-001 Section A4) |
| NAP | Name, Address, Phone (consistent business identity) |
| PSI | Google PageSpeed Insights |
| RPO | Recovery Point Objective |
| RTO | Recovery Time Objective |
| SAD | Solution Architecture Document |
| TTFB | Time to First Byte |
| WAF | Web Application Firewall |
| WCAG | Web Content Accessibility Guidelines |

---

**END OF SOLUTION ARCHITECTURE DOCUMENT — Version 2.0.0**

This SAD is the definitive technical reference for the HDS Onderhoudsdiensten website rebuild. It covers all 46 architecture domains with implementation-ready detail. All 7 architectural decisions are documented with rationale in ADRs (Section 44). All risks are identified with mitigation strategies (Section 41). All 13 assumptions are explicitly stated (Section 42). This document is sufficient for a development team to begin Sprint 2 without additional architectural clarification.
