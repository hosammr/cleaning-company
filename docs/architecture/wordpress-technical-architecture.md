# HDS Onderhoudsdiensten — WordPress Technical Architecture

**Document ID:** WTA-001 | **Version:** 1.0.0 | **Status:** Implementation-Ready
**Project:** helderduidelijkschoon.nl — Ground-Up Rebuild
**Language:** Nederlands (nl-NL) | **Date:** July 2026
**Referenced Documents:** SA-001, ADR-001, FS-001, NFR-001, DS-001, PB-001, PVR-001, RTM-001, Epic 1 Implementation, Epic 2 Implementation

---

## 1. Objectives

This WordPress Technical Architecture specifies every technical decision required to implement the HDS Onderhoudsdiensten platform on WordPress 6.7+. It is the single source of truth for backend and frontend developers during Sprint 2–8 execution.

**Objectives:**

| # | Objective | Audience |
|---|---|---|
| O01 | Define the complete WordPress configuration — version, plugins, settings, constants | Backend developers |
| O02 | Specify the theme architecture — file structure, template hierarchy, coding patterns | Frontend + Backend developers |
| O03 | Define the content model — pages, CPTs, custom fields, taxonomies | Content editors + Developers |
| O04 | Specify the Block Editor strategy — core blocks, custom blocks, patterns, restrictions | Frontend developers + Content editors |
| O05 | Define the forms architecture — Gravity Forms configuration, validation, email routing | Backend developers |
| O06 | Specify media management — image sizes, WebP, lazy loading, alt text policy | Content editors + Developers |
| O07 | Define the SEO integration — Rank Math Pro configuration, schema, sitemaps, redirects | SEO specialist + Developers |
| O08 | Specify WooCommerce configuration — products, payments, shipping, emails, templates | Backend developers |
| O09 | Define the plugin architecture — approved list, update policy, removal policy | All developers |
| O10 | Specify security, performance, backup, and deployment configurations | DevOps + Backend developers |

**Relationship to other documents:**
- **SA-001 (Solution Architecture):** High-level system architecture. This document zooms into WordPress-specific implementation.
- **DS-001 (Design System):** Visual design tokens and component specifications. This document defines the WordPress mechanisms that deliver them.
- **FS-001 (Functional Specification):** What the system must do. This document defines how WordPress implements those functions.

---

## 2. WordPress Version Strategy

### 2.1 Core

| Setting | Value | Rationale |
|---|---|---|
| **WordPress Version** | 6.7+ (latest stable) | Required for modern Block Editor features and PHP 8.2+ compatibility |
| **Update Policy** | Minor releases: auto-update. Major releases: test on staging first. | Security patches immediate; feature releases gated |
| **Language** | `nl_NL` (Dutch) | Single-language target market |
| **Locale** | `nl_NL` via `WPLANG` or Settings → General | Affects date formats, translations, core strings |

### 2.2 PHP

| Requirement | Value | `wp-config-env.php` |
|---|---|---|
| **Minimum Version** | 8.2 | `composer.json` `platform.php: "8.2"` |
| **Recommended** | 8.3 | Hosting-dependent |
| **Memory Limit** | 256M (`WP_MEMORY_LIMIT`) | Defined in `wp-config-env.php` |
| **Admin Memory** | 512M (`WP_MAX_MEMORY_LIMIT`) | Defined in `wp-config-env.php` |

**Required PHP Extensions:**

| Extension | Purpose |
|---|---|
| `gd` (with WebP support) | Image resizing + WebP generation |
| `mysqli` | MySQL database driver |
| `pdo_mysql` | PDO driver (backup plugins) |
| `zip` | Plugin/theme updates |
| `intl` | Internationalization (IDNA, locale-aware formatting) |
| `mbstring` | Multibyte string handling (UTF-8) |
| `exif` | Image metadata handling |
| `xml`, `xsl` | XML sitemaps, imports |
| `opcache` | PHP bytecode caching |
| `redis` | Redis object cache (PECL extension) |
| `imagick` | Advanced image processing (optional; fallback to GD) |

### 2.3 MySQL / MariaDB

| Requirement | Value |
|---|---|
| **MySQL** | 8.0+ |
| **MariaDB** | 10.6+ |
| **Storage Engine** | InnoDB |
| **Charset** | `utf8mb4` |
| **Collation** | `utf8mb4_unicode_ci` |
| **Table Prefix** | `hds_` (not `wp_`) |

### 2.4 WordPress Constants (`wp-config-env.php`)

```php
// ── Environment Detection ──
$env = getenv( 'WP_ENV' ) ?: 'production';

// ── Database ──
define( 'DB_NAME',     getenv( 'DB_NAME' ) ?: 'wordpress' );
define( 'DB_USER',     getenv( 'DB_USER' ) ?: 'root' );
define( 'DB_PASSWORD', getenv( 'DB_PASSWORD' ) ?: '' );
define( 'DB_HOST',     getenv( 'DB_HOST' ) ?: 'localhost' );
$table_prefix = getenv( 'DB_PREFIX' ) ?: 'hds_';

// ── Debug (per environment) ──
switch ( $env ) {
    case 'local':
        define( 'WP_DEBUG', true );
        define( 'WP_DEBUG_LOG', true );
        define( 'WP_DEBUG_DISPLAY', false );
        define( 'SCRIPT_DEBUG', true );
        define( 'WP_DEVELOPMENT_MODE', 'theme' );
        break;
    case 'staging':
        define( 'WP_DEBUG', true );
        define( 'WP_DEBUG_LOG', true );
        define( 'WP_DEBUG_DISPLAY', false );
        break;
    case 'production':
        define( 'WP_DEBUG', false );
        define( 'WP_DEBUG_LOG', true );
        define( 'WP_DEBUG_DISPLAY', false );
        break;
}

define( 'WP_ENVIRONMENT_TYPE', $env );

// ── Performance ──
define( 'WP_MEMORY_LIMIT', '256M' );
define( 'WP_MAX_MEMORY_LIMIT', '512M' );
define( 'WP_POST_REVISIONS', 10 );
define( 'AUTOSAVE_INTERVAL', 300 );
define( 'MEDIA_TRASH', true );

// ── Security ──
define( 'DISALLOW_FILE_EDIT', true );
define( 'DISALLOW_FILE_MODS', false );
define( 'FORCE_SSL_ADMIN', $env !== 'local' );

// ── Redis ──
if ( getenv( 'WP_REDIS_HOST' ) ) {
    define( 'WP_REDIS_HOST', getenv( 'WP_REDIS_HOST' ) );
    define( 'WP_REDIS_PORT', getenv( 'WP_REDIS_PORT' ) ?: 6379 );
    define( 'WP_CACHE', true );
}

// ── Updates ──
define( 'WP_AUTO_UPDATE_CORE', 'minor' );

// ── Cron (server-level, disabled in WP) ──
define( 'DISABLE_WP_CRON', true );

// ── Empty trash ──
define( 'EMPTY_TRASH_DAYS', 30 );

// ── Post autosave ──
define( 'AUTOSAVE_INTERVAL', 300 );
```

### 2.5 WordPress Admin Settings

| Setting | Value | Notes |
|---|---|---|
| **Permalink Structure** | `/%postname%/` | Settings → Permalinks |
| **Category Base** | `kennisbank` | Settings → Permalinks → Optional |
| **Timezone** | `Europe/Amsterdam` | Settings → General |
| **Date Format** | `j F Y` | 21 juli 2026 |
| **Time Format** | `H:i` | 14:30 |
| **Language** | `Nederlands` | Settings → General |
| **Comments** | **Disabled** site-wide | Settings → Discussion (uncheck all) |
| **Pingbacks/Trackbacks** | **Disabled** | Settings → Discussion |
| **Search Engine Visibility** | Enabled (production) | Settings → Reading |
| **Media Sizes** | See §11 | Settings → Media |

---

## 3. Theme Architecture

### 3.1 Theme Type

**Custom Hybrid Block Theme** — `theme.json` for design tokens and block configuration + PHP templates for structured layouts + Block Editor for content areas. NOT Full Site Editing (FSE). Templates are PHP files, not HTML block templates.

**Theme Slug:** `hds`
**Theme Directory:** `wp-content/themes/hds/`
**Text Domain:** `hds`
**Theme Version:** `1.0.0`

### 3.2 Theme Structure

```
wp-content/themes/hds/
├── theme.json                         # Design tokens, block styles, template declarations
├── style.css                          # Theme metadata header
├── screenshot.png                     # 1200×900 preview
├── functions.php                      # Bootstrap: constants, setup, inc/ includes
│
├── assets/
│   ├── css/
│   │   ├── main.css                   # Production stylesheet (1200+ lines)
│   │   └── editor.css                 # Block Editor styles (mirror of frontend)
│   ├── js/
│   │   ├── main.js                    # Navigation toggle, keyboard handlers
│   │   └── blocks/                    # Editor scripts for custom blocks
│   │       ├── service-card.js
│   │       ├── testimonial.js
│   │       ├── job-listing.js
│   │       └── contact-info.js
│   ├── images/                        # Theme images (logo.svg placeholder)
│   └── fonts/                         # Self-hosted Open Sans (WOFF2)
│
├── inc/
│   ├── setup.php                      # Image sizes, disable WP features, activation hook
│   ├── cpts.php                       # CPT registration (hds_testimonial, hds_vacancy)
│   ├── custom-fields.php              # 14 register_post_meta() calls
│   ├── customizer.php                 # 11 Company Information Customizer fields
│   ├── helpers.php                    # hds_get_phone(), hds_breadcrumbs(), wrapper functions
│   ├── security.php                   # XML-RPC disable, REST hardening, redirects
│   ├── patterns.php                   # 7+ block patterns (register_block_pattern)
│   ├── blocks.php                     # 4 custom blocks (register_block_type with render_callback)
│   └── schema.php                     # 5 JSON-LD generators
│
├── parts/
│   ├── header.php                     # DOCTYPE → <head> → <body> → skip-link → <header>
│   ├── footer.php                     # 5-col grid → company info → legal → social → copyright
│   ├── breadcrumbs.php               # BreadcrumbList Schema.org microdata
│   └── schema-localbusiness.php       # LocalBusiness JSON-LD
│
├── page-templates/
│   ├── page-service.php               # P02–P08
│   ├── page-category-landing.php      # P09, P10
│   ├── page-about.php                 # P11, P12
│   ├── page-contact.php               # P16
│   ├── page-quote.php                 # P17
│   ├── page-faq.php                   # P18
│   └── page-legal.php                 # P19–P22
│
├── front-page.php                     # P01
├── page.php                           # Default (P13–P15, P23, P32)
├── single.php                         # Blog post
├── archive.php                        # Blog index
├── search.php                         # Search results
├── 404.php                            # Custom 404
├── index.php                          # Ultimate fallback
└── languages/
    └── hds.pot                        # Translation template
```

### 3.3 Template Hierarchy Resolution

WordPress resolves which template to use in this priority order:

```
Request: /glasbewassing/ (Page with slug 'glasbewassing')

1. page-templates/page-service.php        ← Template assigned via Page Attributes → Template
   ↓ (if not assigned or not found)
2. page-{slug}.php                        ← page-glasbewassing.php
   ↓ (if not found)
3. page-{id}.php                          ← page-12.php
   ↓ (if not found)
4. page.php                               ← Default page template
   ↓ (if not found)
5. singular.php
   ↓ (if not found)
6. index.php                              ← Ultimate fallback
```

**For the HDS theme:**
- **P01 (Home):** `front-page.php` (bypasses hierarchy — WordPress uses this for the front page automatically)
- **P02–P08 (Services):** `page-templates/page-service.php` (assigned via Template dropdown)
- **P09–P10 (Category Landings):** `page-templates/page-category-landing.php`
- **P11–P12 (About):** `page-templates/page-about.php`
- **P13–P15, P23 (Referenties, Vacatures, Downloads, Luchtreiniging):** `page.php` (default)
- **P16 (Contact):** `page-templates/page-contact.php`
- **P17 (Offerte):** `page-templates/page-quote.php`
- **P18 (FAQ):** `page-templates/page-faq.php`
- **P19–P22 (Legal):** `page-templates/page-legal.php`
- **P29 (Blog index):** `archive.php`
- **P30 (Blog posts):** `single.php`
- **P31 (404):** `404.php`
- **P32 (Bedankt):** `page.php` (default)
- **P24–P28 (WooCommerce):** WooCommerce plugin templates (overridden in theme only if needed)

### 3.4 Template Parts

Template parts are reusable PHP fragments loaded via `get_template_part()`.

| Template Part | File | Used In |
|---|---|---|
| **Header** | `parts/header.php` | Every page (via `get_header()`) |
| **Footer** | `parts/footer.php` | Every page (via `get_footer()`) |
| **Breadcrumbs** | `parts/breadcrumbs.php` | All inner pages (via `hds_breadcrumbs()`) |
| **LocalBusiness Schema** | `parts/schema-localbusiness.php` | Home, Contact, Over HDS |

**Loading pattern:**
```php
// In page templates:
get_template_part( 'parts/breadcrumbs' );

// In functions (header/footer wrappers):
function get_header( $name = null, $args = [] ): void {
    do_action( 'get_header', $name, $args );
    $templates = $name ? [ "parts/header-{$name}.php" ] : [];
    $templates[] = 'parts/header.php';
    locate_template( $templates, true );
}
```

### 3.5 Block Patterns

Registered in `inc/patterns.php` via `register_block_pattern()`. Categorized under `hds-patterns`.

**Current (7 implemented in Epic 1):**
1. `hds/cta-banner` — Full-width colored CTA section
2. `hds/hero-section` — Page hero with H1, subtitle, CTA
3. `hds/usp-grid` — 3-column USP cards
4. `hds/content-with-image` — Two-column content + image
5. `hds/cross-sell-services` — Related services section
6. `hds/contact-info-block` — Company contact details
7. `hds/404-content` — 404 page content structure

**Remaining (9 patterns — implemented as custom blocks or block compositions):**
The following patterns from SAD §14 are delivered via custom blocks (`hds/service-card` renders service cards; `hds/testimonial` renders testimonials; `hds/job-listing` renders vacancies) or via Block Editor composition (Service Icon List, Client Logo Carousel, FAQ Accordion, Download Card List, Latest Blog Posts, Related Posts).

**Loading:**
```php
function hds_register_block_pattern_categories(): void {
    register_block_pattern_category( 'hds-patterns', [
        'label' => __( 'HDS Patronen', 'hds' ),
    ] );
}
add_action( 'init', 'hds_register_block_pattern_categories' );
```

### 3.6 theme.json Strategy

`theme.json` is the central configuration file for the hybrid block theme. It defines:

| Section | Purpose |
|---|---|
| `settings.color.palette` | 11 colors (primary, secondary, accent, neutral, semantic) |
| `settings.typography` | Font families, font sizes (9), font weights |
| `settings.spacing` | Spacing scale (13 sizes, 4px-based) |
| `settings.shadow` | 4 shadow presets |
| `settings.layout` | Content width (780px), wide width (1200px) |
| `settings.custom.hds` | Company defaults (name, phone, email) |
| `styles` | Default colors, typography, element styles (headings, links, buttons) |
| `styles.blocks` | Block-specific styles (core/navigation) |
| `templateParts` | Declares header + footer template parts |
| `customTemplates` | Declares 7 custom page templates |

**Key principle:** `theme.json` defines tokens; CSS (`main.css`) consumes them via `var(--wp--preset--color--primary)`. The Block Editor reads `theme.json` for the editor UI (color picker, font size selector, spacing controls).

### 3.7 Global Styles

Global Styles (Site Editor → Styles) is **not used** for layout control. All layout is in PHP templates. Global Styles is limited to:
- Color palette and typography from `theme.json` (read-only to client)
- Block-level spacing adjustments (client can tweak padding/margins of individual blocks)

The client cannot modify the site-wide design system through the UI. This prevents accidental breakage.

### 3.8 Child Theme Policy

**No child theme.** The `hds` theme is the sole theme. All customizations are made directly in the parent theme and tracked in Git. If a child theme becomes necessary post-launch (e.g., for client-specific CSS overrides), it will be created at that point. `DISALLOW_FILE_EDIT` prevents theme file editing via WP Admin.

### 3.9 Theme Coding Standards

| Standard | Enforcement |
|---|---|
| PHP: WordPress-Core + Security + PHP 8.2 compat | PHP_CodeSniffer (`phpcs.xml`) |
| CSS: BEM-like naming (`.hds-component__element--modifier`) | Stylelint (`.stylelintrc.json`) |
| JS: Vanilla ES6+, no jQuery (theme code) | ESLint (`.eslintrc.js`) |
| All output escaped (`esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses()`) | Manual review + PHPCS |
| All inputs sanitized | Manual review + PHPCS |
| Nonces on all custom forms | Manual review |
| All strings internationalized (`__()`, `_e()`) with textdomain `hds` | Manual review |
| No `eval()`, no `base64_decode()`, no `extract()` | PHPCS + manual review |

---

## 4. Content Architecture

### 4.1 Pages

All 32 content pages (P01–P32) are standard WordPress Pages (`post_type = 'page'`). This is a deliberate choice — services are NOT a Custom Post Type. Reasoning:
- Pages have a well-understood admin UI (clients already know it).
- Pages support the Template dropdown for assigning custom templates.
- Pages support `menu_order` for ordering in navigation and service card grids.
- Pages integrate natively with Rank Math SEO meta fields.
- No need for custom archive pages or custom rewrite rules for services.

**Page Inventory:**

| Page ID | Title (NL) | Slug | Template | Priority |
|---|---|---|---|---|
| P01 | Home | `/` | `front-page.php` | P0 |
| P02 | Glasbewassing | `/glasbewassing/` | Service | P0 |
| P03 | Gevelreiniging | `/gevelreiniging/` | Service | P0 |
| P04 | Reguliere Schoonmaak | `/reguliere-schoonmaak/` | Service | P0 |
| P05 | Vloeronderhoud | `/vloeronderhoud/` | Service | P0 |
| P06 | VVE Service | `/vve-service/` | Service | P0 |
| P07 | Oplevering Schoonmaak | `/oplevering-schoonmaak/` | Service | P0 |
| P08 | Industriele Schoonmaak | `/industriele-schoonmaak/` | Service | P0 |
| P09 | Glas & Gevel | `/glas-en-gevel/` | Category Landing | P1 |
| P10 | Schoonmaakdiensten | `/schoonmaakdiensten/` | Category Landing | P1 |
| P11 | Over HDS | `/over-hds/` | About | P0 |
| P12 | Kwaliteit & Veiligheid | `/kwaliteit-veiligheid/` | About | P0 |
| P13 | Referenties | `/referenties/` | Default | P1 |
| P14 | Vacatures | `/vacatures/` | Default | P1 |
| P15 | Downloads | `/downloads/` | Default | P1 |
| P16 | Contact | `/contact/` | Contact | P0 |
| P17 | Offerte Aanvragen | `/offerte-aanvragen/` | Quote | P1 |
| P18 | Veelgestelde Vragen | `/veelgestelde-vragen/` | FAQ | P2 |
| P19 | Privacyverklaring | `/privacyverklaring/` | Legal | P0 |
| P20 | Cookiebeleid | `/cookiebeleid/` | Legal | P0 |
| P21 | Algemene Voorwaarden | `/algemene-voorwaarden/` | Legal | P0 |
| P22 | Disclaimer | `/disclaimer/` | Legal | P2 |
| P23 | Luchtreiniging | `/luchtreiniging/` | Default | P1 |
| P24 | Winkel | `/winkel/` | WC Shop | P1 |
| P25 | Product (×14) | `/product/{slug}/` | WC Product | P1 |
| P26 | Winkelmand | `/winkelmand/` | WC Cart | P1 |
| P27 | Afrekenen | `/afrekenen/` | WC Checkout | P1 |
| P28 | Mijn Account | `/mijn-account/` | WC Account | P1 |
| P29 | Kennisbank | `/kennisbank/` | Archive | P2 |
| P30 | Blog posts (5–10) | `/kennisbank/{slug}/` | Single | P2 |
| P31 | 404 | — (any 404) | `404.php` | P0 |
| P32 | Bedankt | `/bedankt/` | Default | P0 |

### 4.2 Posts (Blog)

Standard WordPress Posts (`post_type = 'post'`) for blog articles.

| Setting | Value |
|---|---|
| **Category Base** | `kennisbank` |
| **Permalink** | `/kennisbank/%postname%/` (no date prefix) |
| **Comments** | Disabled |
| **Pingbacks** | Disabled |
| **Initial Posts** | 5–10 Dutch articles (Sprint 5) |

### 4.3 Categories & Tags

- **Categories:** Standard hierarchical taxonomy. Used for blog post categorization.
- **Tags:** Standard non-hierarchical taxonomy. Optional — use only if blog has cross-cutting topics.
- **No custom taxonomies** are required for the initial build.

### 4.4 Media

Managed through the standard WordPress Media Library. See §11 for detailed media management configuration.

### 4.5 Navigation Menus

Five menu locations registered in `functions.php`:

```php
register_nav_menus( [
    'primary'         => __( 'Hoofdmenu', 'hds' ),
    'footer-services' => __( 'Footer - Diensten', 'hds' ),
    'footer-about'    => __( 'Footer - Over HDS', 'hds' ),
    'footer-airfixr'  => __( 'Footer - Luchtreiniging', 'hds' ),
    'footer-legal'    => __( 'Footer - Juridisch', 'hds' ),
] );
```

All menus are managed via Appearance → Menus. No hardcoded navigation in theme files.

### 4.6 Widgets

**Not used.** The design uses Block Editor content, block patterns, and template parts instead of traditional WordPress widgets. No widget areas are registered. The "Widgets" admin menu item is irrelevant.

### 4.7 Reusable Blocks (Synced Patterns)

WordPress core feature: Blocks saved in the Block Editor for reuse across pages. **Recommended for:** CTA banners, contact info blocks, cross-sell sections that appear identically on multiple pages. Content editors can create and manage these.

### 4.8 Content Editing Policy

- **Editor:** Native Block Editor (Gutenberg) ONLY. No page builder. No Classic Editor plugin.
- **Content Storage:** Standard block HTML in `post_content` (`<!-- wp:paragraph -->...<!-- /wp:paragraph -->`).
- **No shortcodes** in `post_content`. The only exception is Gravity Forms shortcodes (`[gravityform id="1"]`) which are standard and well-supported.
- **No third-party shortcodes** from page builders, sliders, or legacy plugins.

---

## 5. Custom Post Types

### 5.1 CPT Inventory

| CPT Key | Public | Has Archive | Rewrite Slug | Menu Icon | Purpose |
|---|---|---|---|---|---|
| `hds_testimonial` | `false` | `false` | `false` (no rewrite) | `dashicons-format-quote` | Client testimonials — block-queried only |
| `hds_vacancy` | `true` | `false` | `vacatures` | `dashicons-businessperson` | Job vacancies — displayed on P14 via block |

### 5.2 hds_testimonial

**Purpose:** Store client testimonials that are displayed on the Referenties page (P13) and Homepage via the `hds/testimonial` custom block.

**Why non-public?** The CPT slug `referenties` would conflict with the `/referenties/` Page (P13). Setting `public => false` and `publicly_queryable => false` eliminates the conflict. Testimonials are queried exclusively via `WP_Query` in the `hds/testimonial` block's `render_callback`.

```php
register_post_type( 'hds_testimonial', [
    'labels'              => [ /* ... Dutch labels */ ],
    'public'              => false,
    'publicly_queryable'  => false,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_rest'        => true,    // Exposed to Block Editor
    'has_archive'         => false,
    'supports'            => [ 'title', 'editor' ],
    'menu_icon'           => 'dashicons-format-quote',
    'rewrite'             => false,
] );
```

**Custom Fields (registered via `register_post_meta()` in `inc/custom-fields.php`):**

| Field | Type | Description |
|---|---|---|
| `hds_author_name` | `string` | Name of the person giving the testimonial |
| `hds_company_name` | `string` | Company name of the testimonial giver |
| `hds_star_rating` | `integer` | Rating 1–5 |
| `hds_related_service` | `integer` | Page ID of related service (optional) |

**Relationships:** Optionally related to a Service Page via `hds_related_service` post meta.

**SEO:** No individual testimonial pages (non-public). Schema handled by `hds/testimonial` block output (Review schema, deferred to post-launch).

### 5.3 hds_vacancy

**Purpose:** Store job vacancy listings displayed on the Vacatures page (P14) via the `hds/job-listing` custom block.

**Why no archive?** The CPT display is handled by the `hds/job-listing` block on the `/vacatures/` Page (P14). An archive page would create a duplicate at `/vacatures/` (the rewrite slug) that conflicts with the Page. Setting `has_archive => false` prevents the conflict.

```php
register_post_type( 'hds_vacancy', [
    'labels'              => [ /* ... Dutch labels */ ],
    'public'              => true,
    'publicly_queryable'  => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_rest'        => true,
    'has_archive'         => false,
    'supports'            => [ 'title', 'editor' ],
    'menu_icon'           => 'dashicons-businessperson',
    'rewrite'             => [ 'slug' => 'vacatures' ],
] );
```

**Custom Fields:**

| Field | Type | Description |
|---|---|---|
| `hds_hours_per_week` | `string` | Hours per week (e.g., "32–40") |
| `hds_location` | `string` | Location / standplaats |
| `hds_start_date` | `string` | Desired start date |
| `hds_application_email` | `string` | Email for applications (falls back to info@) |
| `hds_deadline` | `string` | Application deadline |
| `hds_is_active` | `boolean` | Whether the vacancy is currently open |

**Frontend Query (in `hds/job-listing` render_callback):**
```php
$args = [
    'post_type'      => 'hds_vacancy',
    'posts_per_page' => $attributes['count'] ?? 5,
    'post_status'    => 'publish',
    'orderby'        => 'date',
    'order'          => 'DESC',
    'meta_query'     => [
        [ 'key' => 'hds_is_active', 'value' => '1', 'compare' => '=' ],
    ],
];
$query = new WP_Query( $args );
```

**SEO:** JobPosting schema generated per vacancy in `inc/schema.php` via `hds_get_jobposting_schema($vacancy_id)`. Output on the `/vacatures/` page.

### 5.4 FAQ — NOT a CPT

**Decision (Post-PVR Correction C01):** FAQ uses the Yoast/Rank Math FAQ Block on a standard Page at `/veelgestelde-vragen/` (P18). The `hds_faq` CPT that was registered in Epic 1 has been removed. Rationale:
- Editors edit one page (simpler).
- Yoast/Rank Math FAQ blocks auto-generate FAQPage schema.
- No CPT maintenance, no custom block needed.
- Consistent with BKLG story E-SUPPORT-07.

---

## 6. Taxonomies

### 6.1 Taxonomies in Use

| Taxonomy | Type | Applies To | Purpose |
|---|---|---|---|
| `category` (built-in) | Hierarchical | `post` (blog) | Blog post categorization |
| `post_tag` (built-in) | Non-hierarchical | `post` (blog) | Blog post tagging (optional) |

### 6.2 No Custom Taxonomies

No custom taxonomies are registered for the initial build. The information architecture uses Page templates and CPTs (not taxonomies) to organize content:
- Services are Pages (not posts in a "service" category).
- Vacancies are a CPT (not posts in a "vacancy" category).
- Testimonials are a CPT (not posts in a "testimonial" category).

This approach gives each content type its own admin menu, custom fields, and query patterns — cleaner than mixing everything into posts with categories.

---

## 7. Custom Fields

### 7.1 Custom Field Strategy

**No ACF.** All custom fields use WordPress core `register_post_meta()` — available since WordPress 5.0 and fully REST API-compatible. Rationale:
- No plugin dependency. Zero license cost. No update risk.
- Fields are available in the Block Editor via the REST API.
- Fields are queryable via `WP_Query` meta queries.
- Simpler than ACF for the modest number of fields needed (14 total).

**Alternative Rejected:** Advanced Custom Fields (ACF). While ACF provides a GUI for field management, the 14 fields in this project are simple (text, integer, boolean). ACF would add a plugin dependency with no significant benefit.

**Field Registration Pattern (all in `inc/custom-fields.php`):**

```php
function hds_register_service_fields(): void {
    register_post_meta( 'page', 'hds_subtitle', [
        'show_in_rest'  => true,       // Accessible in Block Editor
        'single'        => true,       // Single value, not array
        'type'          => 'string',
        'description'   => __( 'Ondertitel onder de paginatitel in de hero sectie.', 'hds' ),
        'auth_callback' => fn() => current_user_can( 'edit_pages' ),
    ] );
    // ... repeat for hds_hero_image, hds_service_icon, hds_cta_override
}
add_action( 'init', 'hds_register_service_fields' );
```

### 7.2 Field Groups

#### Service Page Settings

| Field | Type | Default | Applies To | Description |
|---|---|---|---|---|
| `hds_subtitle` | `string` | `''` | Page (Service template) | Hero subtitle below H1 |
| `hds_hero_image` | `integer` | `0` | Page (Service template) | Media ID for hero background |
| `hds_service_icon` | `string` | `''` | Page (Service template) | Icon identifier for service cards |
| `hds_cta_override` | `string` | `''` | Page (Service template) | Override default CTA text |

#### Testimonial Details

| Field | Type | Default | Applies To | Description |
|---|---|---|---|---|
| `hds_author_name` | `string` | `''` | `hds_testimonial` | Testimonial author |
| `hds_company_name` | `string` | `''` | `hds_testimonial` | Author's company |
| `hds_star_rating` | `integer` | `0` | `hds_testimonial` | 1–5 star rating |
| `hds_related_service` | `integer` | `0` | `hds_testimonial` | Related service page ID |

#### Vacancy Details

| Field | Type | Default | Applies To | Description |
|---|---|---|---|---|
| `hds_hours_per_week` | `string` | `''` | `hds_vacancy` | Hours per week |
| `hds_location` | `string` | `''` | `hds_vacancy` | Work location |
| `hds_start_date` | `string` | `''` | `hds_vacancy` | Desired start date |
| `hds_application_email` | `string` | `''` | `hds_vacancy` | Application email |
| `hds_deadline` | `string` | `''` | `hds_vacancy` | Closing date |
| `hds_is_active` | `boolean` | `false` | `hds_vacancy` | Vacancy active? |

### 7.3 Company Information (Customizer)

Stored as `theme_mod` values in the Theme Customizer — NOT as `register_post_meta()`. This is correct because company information is global (site-wide), not per-page.

| Field | Customizer Key | Type | Default |
|---|---|---|---|
| Address | `hds_address` | `text` | `''` |
| Postal Code + City | `hds_postal_city` | `text` | `''` |
| Phone | `hds_phone` | `text` | `'0164-652846'` |
| Email | `hds_email` | `text` | `'info@helderduidelijkschoon.nl'` |
| KVK | `hds_kvk` | `text` | `''` |
| BTW | `hds_btw` | `text` | `''` |
| Facebook URL | `hds_facebook_url` | `text` | `''` |
| Instagram URL | `hds_instagram_url` | `text` | `''` |
| GBP URL | `hds_gbp_url` | `text` | `''` |
| Opening Hours | `hds_opening_hours` | `textarea` | `''` |

**Reading in templates:**
```php
$phone = get_theme_mod( 'hds_phone', '0164-652846' );
$email = get_theme_mod( 'hds_email', 'info@helderduidelijkschoon.nl' );
```

### 7.4 No Repeaters, No Flexible Content, No Conditional Logic Fields

These would require ACF. The 14 fields are simple enough that they don't need repeaters or conditional logic. If post-launch requirements demand complex field structures, ACF can be introduced at that point.

---

## 8. Gutenberg Block Strategy

### 8.1 Core Blocks

All WordPress core blocks are **allowed by default** (no block restrictions via `allowed_block_types_all` filter). Content editors can use any core block. The theme's `theme.json` and CSS provide appropriate styling for all core blocks.

**Commonly used core blocks:**
- `core/paragraph`, `core/heading`, `core/list`, `core/list-item`
- `core/image`, `core/gallery`
- `core/columns`, `core/column`, `core/group`
- `core/button`, `core/buttons`
- `core/table`
- `core/quote`, `core/pullquote`
- `core/embed` (YouTube, Vimeo)
- `core/spacer`, `core/separator`

### 8.2 Custom Blocks

Four custom blocks registered in `inc/blocks.php`. Each has a PHP `render_callback` for dynamic server-side rendering and a JS editor script using `ServerSideRender` for the Block Editor preview.

| Block Name | Category | Purpose | Editor Script | Render Callback |
|---|---|---|---|---|
| `hds/service-card` | `hds-patterns` | Single service card | `assets/js/blocks/service-card.js` | `hds_render_service_card()` |
| `hds/testimonial` | `hds-patterns` | Testimonial grid | `assets/js/blocks/testimonial.js` | `hds_render_testimonial()` |
| `hds/job-listing` | `hds-patterns` | Vacancy cards | `assets/js/blocks/job-listing.js` | `hds_render_job_listing()` |
| `hds/contact-info` | `hds-patterns` | Company info block | `assets/js/blocks/contact-info.js` | `hds_render_contact_info()` |

**Registration pattern:**

```php
function hds_register_custom_blocks(): void {
    $blocks = [
        'service-card' => 'hds_render_service_card',
        'testimonial'  => 'hds_render_testimonial',
        'job-listing'  => 'hds_render_job_listing',
        'contact-info' => 'hds_render_contact_info',
    ];

    foreach ( $blocks as $name => $callback ) {
        wp_register_script(
            "hds-{$name}",
            HDS_URI . "/assets/js/blocks/{$name}.js",
            [ 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-data', 'wp-server-side-render' ],
            HDS_VERSION,
            true
        );

        register_block_type( "hds/{$name}", [
            'editor_script'   => "hds-{$name}",
            'render_callback' => $callback,
            'attributes'      => hds_get_block_attributes( $name ),
        ] );
    }
}
add_action( 'init', 'hds_register_custom_blocks' );
```

**Key Rule:** No `save()` function in JS — all blocks use `return null` (dynamic rendering via PHP). This ensures blocks always reflect current data, not a saved snapshot.

### 8.3 Reusable Blocks (Synced Patterns)

**Recommended for:** CTA Banners, Contact Info Blocks, and Cross-Sell Service sections that appear identically across multiple pages. Editors can edit the synced pattern once and all instances update.

**How to create:** Block Editor → Options (⋮) → "Create pattern" → Choose "Synced". The pattern appears in the inserter.

### 8.4 Block Patterns

See §3.5. Unsynced patterns (layouts that can be inserted and then customized per instance).

### 8.5 Block Styles

Seven custom block style variations registered via `register_block_style()` in `functions.php`:

| Style Name | Applied To | Visual Effect |
|---|---|---|
| `is-style-secondary` | `core/button` | Outlined button, transparent bg, primary border |
| `is-style-cta` | `core/button` | Larger button, accent orange bg, white text |
| `is-style-card` | `core/group` | White bg, border-radius, shadow |
| `is-style-banner` | `core/group` | Colored bg, full-width, centered text |
| `is-style-icon-list` | `core/list` | Custom checkmark bullets, no default bullets |
| `is-style-no-bullet` | `core/list` | No bullets, no left padding |

### 8.6 Block Categories

One custom category: `hds-patterns` — "HDS Patronen". All HDS block patterns and custom blocks use this category for easy discovery in the Block Editor inserter.

---

## 9. Template Mapping

### 9.1 Page → Template Mapping

| Page | Slug | Template File | Template Name (Dropdown) |
|---|---|---|---|
| P01 Home | — (front page) | `front-page.php` | N/A (automatic) |
| P02 Glasbewassing | `glasbewassing` | `page-templates/page-service.php` | Service |
| P03 Gevelreiniging | `gevelreiniging` | `page-templates/page-service.php` | Service |
| P04 Reguliere Schoonmaak | `reguliere-schoonmaak` | `page-templates/page-service.php` | Service |
| P05 Vloeronderhoud | `vloeronderhoud` | `page-templates/page-service.php` | Service |
| P06 VVE Service | `vve-service` | `page-templates/page-service.php` | Service |
| P07 Oplevering Schoonmaak | `oplevering-schoonmaak` | `page-templates/page-service.php` | Service |
| P08 Industriele Schoonmaak | `industriele-schoonmaak` | `page-templates/page-service.php` | Service |
| P09 Glas & Gevel | `glas-en-gevel` | `page-templates/page-category-landing.php` | Category Landing |
| P10 Schoonmaakdiensten | `schoonmaakdiensten` | `page-templates/page-category-landing.php` | Category Landing |
| P11 Over HDS | `over-hds` | `page-templates/page-about.php` | About |
| P12 Kwaliteit & Veiligheid | `kwaliteit-veiligheid` | `page-templates/page-about.php` | About |
| P13 Referenties | `referenties` | `page.php` | Default |
| P14 Vacatures | `vacatures` | `page.php` | Default |
| P15 Downloads | `downloads` | `page.php` | Default |
| P16 Contact | `contact` | `page-templates/page-contact.php` | Contact |
| P17 Offerte Aanvragen | `offerte-aanvragen` | `page-templates/page-quote.php` | Offerte Aanvragen |
| P18 Veelgestelde Vragen | `veelgestelde-vragen` | `page-templates/page-faq.php` | FAQ |
| P19 Privacyverklaring | `privacyverklaring` | `page-templates/page-legal.php` | Legal |
| P20 Cookiebeleid | `cookiebeleid` | `page-templates/page-legal.php` | Legal |
| P21 Algemene Voorwaarden | `algemene-voorwaarden` | `page-templates/page-legal.php` | Legal |
| P22 Disclaimer | `disclaimer` | `page-templates/page-legal.php` | Legal |
| P23 Luchtreiniging | `luchtreiniging` | `page.php` | Default |
| P32 Bedankt | `bedankt` | `page.php` | Default |

### 9.2 WooCommerce Pages

All WooCommerce pages (P24–P28) use the **WooCommerce plugin templates**, not HDS theme templates. The HDS theme's `parts/header.php` and `parts/footer.php` wrap the WooCommerce content automatically via WordPress's standard template loading. No WooCommerce template overrides are needed at launch.

**Assumption:** If WooCommerce checkout accessibility issues are found (ARR A11Y-01), template overrides in `wp-content/themes/hds/woocommerce/` will be created during Sprint 6 (E-COMPLY-07).

### 9.3 Blog Pages

- **Blog Index (P29):** Uses `archive.php`. WordPress routes `/kennisbank/` to the post archive because category base is `kennisbank`.
- **Blog Post (P30):** Uses `single.php`.
- **Search Results:** Uses `search.php`.
- **404:** Uses `404.php`.

---

## 10. Forms Architecture

### 10.1 Gravity Forms Configuration

All three forms are built and managed in Gravity Forms admin (Forms → New Form). No PHP form processing code in the theme. Gravity Forms handles: rendering, validation, entry storage, email notifications, reCAPTCHA integration.

| Form | GF Form ID | Title | Shortcode | Fields | Post-Submit |
|---|---|---|---|---|---|
| Contact | GF-1 | "Contactformulier" | `[gravityform id="1" title="false" description="false" ajax="true"]` | 9 | Redirect → `/bedankt/?type=contact` |
| Offerte | GF-2 | "Offerte Aanvraag" | `[gravityform id="2" title="false" description="false" ajax="true"]` | 13 | Redirect → `/bedankt/?type=offerte` |
| Vacature | GF-3 | "Vacature Sollicitatie" | `[gravityform id="3" title="false" description="false" ajax="true"]` | 6 | Redirect → `/bedankt/?type=vacature` |

**Form shortcodes are placed in the page content via Block Editor:** Use the Gravity Forms block or a Shortcode block with the shortcode above.

### 10.2 Validation

All validation is configured in Gravity Forms (per-field settings):

| Field | Validation |
|---|---|
| Email fields | `is_email` validation. Dutch error: "Vul een geldig e-mailadres in." |
| Required fields | `required`. Dutch error: "Dit veld is verplicht." |
| GF-2 Postcode | Custom regex: `/^[1-9][0-9]{3}\s?[A-Z]{2}$/i`. Dutch error: "Vul een geldige postcode in (bijv. 1234 AB)." |
| GF-1 Bericht | Minimum 10 characters. Dutch error: "Uw bericht moet minimaal 10 tekens bevatten." |
| Privacy checkbox | Must be checked. Dutch error: "U moet akkoord gaan met de privacyverklaring." |
| File upload (GF-2, GF-3) | Max 5MB. Allowed extensions: pdf, jpg, jpeg, png, doc, docx. Errors in Dutch. |

### 10.3 Email Routing

**Post SMTP** handles all email delivery. Configured in Post SMTP → Settings.

**Gravity Forms Notifications (per form):**

| Form | Notification | To | From | Subject |
|---|---|---|---|---|
| GF-1 | Admin Notification | `info@helderduidelijkschoon.nl` | `info@helderduidelijkschoon.nl` | "Nieuw contactformulier bericht" |
| GF-1 | User Confirmation | `{Email:3}` (field merge tag) | `info@helderduidelijkschoon.nl` | "Bedankt voor uw bericht — HDS Onderhoudsdiensten" |
| GF-2 | Admin Notification | `info@helderduidelijkschoon.nl` | `info@helderduidelijkschoon.nl` | "Nieuwe offerte aanvraag" |
| GF-2 | User Confirmation | `{Email:3}` | `info@helderduidelijkschoon.nl` | "Bedankt voor uw offerte aanvraag — HDS Onderhoudsdiensten" |
| GF-3 | Admin Notification | `info@helderduidelijkschoon.nl` | `info@helderduidelijkschoon.nl` | "Nieuwe sollicitatie" |
| GF-3 | User Confirmation | `{Email:2}` | `info@helderduidelijkschoon.nl` | "Bedankt voor uw sollicitatie — HDS Onderhoudsdiensten" |

**File uploads:** Attachments are NOT sent inline. A download link is included in the notification email. Gravity Forms stores uploaded files in `wp-content/uploads/gravity_forms/`.

### 10.4 Spam Protection

- **reCAPTCHA v3:** Registered in Gravity Forms → Settings → reCAPTCHA. Site key + secret key. Invisible to user. Badge displayed per Google ToS.
- **Honeypot:** Gravity Forms built-in anti-spam honeypot enabled on all forms. Hidden field — if filled by bot, submission is silently blocked.
- **No additional spam plugins** (Akismet not needed — comments are disabled).

### 10.5 GDPR Compliance in Forms

- Privacy checkbox on all 3 forms. **Unchecked by default.** Required. Label includes link to `/privacyverklaring/`.
- Entry retention: Gravity Forms → Settings → "Auto-delete entries after 12 months" (GF-1, GF-2), 6 months (GF-3).
- Export/erase: Gravity Forms → Import/Export → Export Entries (filter by email for GDPR requests).

---

## 11. Media Management

### 11.1 WordPress Media Settings

| Size | Dimensions | Crop | Usage |
|---|---|---|---|
| Thumbnail | 150×150 | Yes | Admin thumbnails |
| Medium | 600×600 | No | Content images |
| Large | 1200×1200 | No | Full-size content |
| `hds-card` | 400×300 | Yes | Service cards, testimonial cards |
| `hds-content` | 800×600 | No | In-content images |
| `hds-hero` | 1600×900 | Yes | Hero background images |

**Disabled default sizes:** `1536x1536`, `2048x2048` (removed via `remove_image_size()` in `inc/setup.php`).

**Max upload size:** 10 MB (`upload_max_filesize = 10M` in `php.ini`).

### 11.2 File Naming Convention

- Lowercase, hyphens between words.
- Dutch descriptive keywords.
- Pattern: `[subject]-[context]-[location].[ext]`
- Examples: `glasbewassing-kantoor-bergen-op-zoom.webp`, `vloeronderhoud-marmoleum-school.webp`

### 11.3 WebP Strategy

**Auto-conversion on upload** via ShortPixel or Imagify plugin. When a JPG/PNG is uploaded, the plugin:
1. Compresses the original (quality 85+).
2. Generates a WebP version.
3. Generates all registered image sizes in both formats.

**Frontend delivery** via `<picture>` element (WordPress 5.8+ supports this via `wp_get_attachment_image()` with WebP). Plugin handles the `<picture>` markup generation.

### 11.4 Lazy Loading

WordPress 5.5+ adds `loading="lazy"` to all `wp_get_attachment_image()` output automatically for content images. No additional configuration needed.

**Exceptions (explicit):**
- LCP image (hero): `fetchpriority="high"` and `loading="eager"`.
- Above-fold images: `loading="eager"`.

### 11.5 Responsive Images

WordPress 4.4+ generates `srcset` and `sizes` attributes automatically for all `wp_get_attachment_image()` output. No additional code needed. All registered image sizes are included in the `srcset`.

### 11.6 Alt Text Policy

- **Required:** Alt text on all non-decorative images. Entered in Media Library at upload.
- **Decorative:** `alt=""` (empty alt attribute). Screen readers skip.
- **Policy:** Content editors are trained (Sprint 8 Beheergids) to write descriptive Dutch alt text.
- **Verification:** Screaming Frog scan during QA (Sprint 7). Zero images with missing alt text (excluding decorative).

---

## 12. SEO Integration

### 12.1 Rank Math Pro Configuration

**Primary SEO plugin.** Configuration checklist:

| Setting | Value |
|---|---|
| Site Type | Organization |
| Organization Name | HDS Onderhoudsdiensten |
| Organization Logo | Customizer logo |
| Default Social Share Image | 1200×630px branded graphic |
| Breadcrumbs | Enabled, Home label = "Home" |
| XML Sitemap | Enabled, include: Pages, Posts, Products. Exclude: Attachment pages, Author archives. |
| robots.txt | Auto-generated |
| Redirect Manager | 7 × 301 + 2 × 410 rules configured |
| 404 Monitor | Enabled |

### 12.2 Per-Page Metadata

Set via Rank Math Pro meta box on each page/post edit screen.

| Field | Specification |
|---|---|
| **Title** | `[Page Title] — HDS Onderhoudsdiensten`. 50–60 chars. Unique per page. |
| **Description** | 150–160 chars. Keyword + location + value proposition + CTA. Unique per page. |
| **Focus Keyword** | Primary keyword for the page (e.g., "glasbewassing") |
| **Canonical URL** | Auto (self-referencing) |
| **Robots Meta** | Default: index, follow. Bedankt page: noindex, nofollow. |

### 12.3 Schema (Rank Math Auto)

Rank Math Pro automatically generates:
- `WebSite` with `SearchAction`
- `WebPage`
- `BreadcrumbList`
- `Article` (blog posts)
- `Product` (via WooCommerce integration)
- `FAQPage` (from Yoast/Rank Math FAQ blocks)
- `LocalBusiness` — **disabled** in Rank Math (custom implementation in theme)

### 12.4 Schema (Theme Custom)

Generated in `inc/schema.php` and output via `wp_head` at priority 5.

- `Organization` with `sameAs` — all pages
- `LocalBusiness` (HomeAndConstructionBusiness) — Home, Contact, Over HDS
- `Service` — each service page (P02–P08)
- `JobPosting` — per vacancy on P14

**No duplication:** Rank Math's built-in LocalBusiness schema is disabled to avoid duplicate output with the theme's custom implementation.

### 12.5 XML Sitemap

Rank Math Pro generates sub-sitemaps:

| Sitemap | URL | Content |
|---|---|---|
| Sitemap Index | `/sitemap_index.xml` | Links to sub-sitemaps |
| Page Sitemap | `/page-sitemap.xml` | All public pages (excludes noindex) |
| Post Sitemap | `/post-sitemap.xml` | Blog posts |
| Product Sitemap | `/product-sitemap.xml` | WooCommerce products |

**Excluded:** Attachment pages, author archives, `/bedankt/` (noindex), cart, checkout, account pages.

**Submission:** Submitted to Google Search Console and Bing Webmaster Tools at launch (Sprint 8).

### 12.6 Redirect Strategy

All redirects managed by Rank Math Pro → Redirect Manager.

| Type | Old URL | New URL / Status |
|---|---|---|
| 301 | `/glasbewassing` (no slash) | `/glasbewassing/` |
| 301 | `/vve` | `/vve-service/` |
| 301 | `/vve/` | `/vve-service/` |
| 301 | `/?page_id=318` | `/reguliere-schoonmaak/` |
| 301 | `http://*` | `https://*` |
| 301 | `http://www.*` | `https://*` (non-www) |
| 410 | `/2015/06/29/hallo-wereld/` | Gone |
| 410 | `/2015/08/25/kwaliteit-veiligheid/` | Gone |

**Verification:** Every redirect tested manually via `httpstatus.io` before launch.

### 12.7 robots.txt

Auto-generated by Rank Math Pro. Manual review before launch:

```
User-agent: *
Disallow: /wp-admin/
Disallow: /wp-includes/
Allow: /wp-admin/admin-ajax.php
Disallow: /wp-content/plugins/
Disallow: /*?* (except WooCommerce)
Sitemap: https://helderduidelijkschoon.nl/sitemap_index.xml
```

### 12.8 Canonical Rules

- All pages: self-referencing canonical (Rank Math auto).
- Trailing slash: canonical URL includes trailing slash. Non-slash variant → 301 before canonical applies.
- Paginated archives: canonical points to page 1.
- No cross-domain canonicals.

---

## 13. WooCommerce Architecture

### 13.1 Products

- **Product Count:** 14 Airfixr products imported from current site.
- **Product Types:** Simple products (no variations confirmed — **Assumption:** products are simple).
- **Categories:** Product categories for Airfixr unit types (e.g., "Units", "Filters", "Lamps", "Accessories").
- **Attributes:** Not configured at launch. Deferred until product complexity demands it.

### 13.2 Checkout

- **Guest Checkout:** Enabled.
- **Account Creation:** Optional (checkbox at checkout).
- **Terms:** Link to `/algemene-voorwaarden/` (P21). Checkbox required.
- **Privacy:** Link to `/privacyverklaring/` (P19). Reference in checkout text, not a separate checkbox (GDPR compliance is via the dedicated privacy page link).
- **Checkout Fields:** Default WooCommerce fields. Dutch labels (nl_NL WooCommerce translation).

### 13.3 Payment Gateway — Mollie

**Assumption:** Client confirms Mollie (MI-15). If not, replace with client-chosen gateway.

| Setting | Value |
|---|---|
| **Mode** | Test (staging), Live (production) |
| **API Keys** | Live + Test keys from Mollie Dashboard |
| **Webhook URL** | `https://helderduidelijkschoon.nl/wc-api/mollie_return/` |
| **Methods** | iDEAL, Bancontact, Credit Card (Visa/MC), PayPal, SEPA Direct Debit |
| **Fallback** | Bank Transfer (BACS) — for B2B invoice-based payment |

**Webhook Security:** Mollie webhook URL must bypass Cloudflare WAF (add to Cloudflare Firewall Rules allowlist).

### 13.4 Emails

| Email Type | Enabled | Recipient |
|---|---|---|
| New Order | Yes | `info@helderduidelijkschoon.nl` |
| Processing Order | Yes | Customer |
| Completed Order | Yes | Customer |
| Failed Order | Yes | `info@helderduidelijkschoon.nl` |
| Cancelled Order | Yes | Both |
| Refunded Order | Yes | Customer |
| Customer Invoice | Yes | Customer |
| Customer Note | Yes | Customer |
| Password Reset | Yes | Customer |
| New Account | Yes | Customer |

**Email Branding:**
- From name: "HDS Onderhoudsdiensten"
- From email: `info@helderduidelijkschoon.nl`
- Logo: HDS logo (embedded)
- Language: Dutch (nl_NL)
- Delivery: Via Post SMTP

### 13.5 Taxes

| Setting | Value |
|---|---|
| **Prices Entered With Tax** | No (excl. BTW) |
| **Display Suffix** | "excl. BTW" |
| **Tax Rate** | 21% (Dutch standard BTW) |
| **Tax Class** | Standard |
| **Display in Cart/Checkout** | Excluding tax + tax line |

**Assumption:** Client confirms excl. BTW (B2B standard). If client wants incl. BTW (B2C), reconfigure.

### 13.6 Shipping

**Assumption:** Client provides shipping costs (MI-14). If not, implement flat-rate placeholder.

| Setting | Value |
|---|---|
| **Zones** | Nederland (default) |
| **Classes** | "Klein pakket" (filters, lamps), "Groot pakket" (Airfixr units) |
| **Methods** | Flat rate OR free over €X,00 (client decision) |

### 13.7 Product Templates

WooCommerce default templates are used. No HDS theme overrides at launch.

**Template Source:** `wp-content/plugins/woocommerce/templates/`

**If overrides are needed (Sprint 6 for accessibility):**
```
wp-content/themes/hds/woocommerce/
├── checkout/
│   └── form-checkout.php        ← If accessibility fixes needed
├── single-product/
│   └── add-to-cart.php
└── ...
```

---

## 14. Plugin Architecture

### 14.1 Approved Plugins

| # | Plugin | License | Critical? | Purpose | Alternative Rejected | Reason for Selection |
|---|---|---|---|---|---|---|
| 1 | **WooCommerce** 9.x+ | Free | Conditional | eCommerce | Shopify, Magento | Client familiar; WordPress native; large ecosystem |
| 2 | **Gravity Forms** | Premium | Yes | Forms (GF-1/2/3) | Formidable Forms (broken), WPForms, WS Form | Market leader for complex WordPress forms; conditional logic; file uploads; GDPR consent fields |
| 3 | **Rank Math Pro** | Premium | Yes | SEO | Yoast SEO Premium | Built-in redirect manager (Yoast needs Redirection plugin); 404 monitor; richer free tier |
| 4 | **FlyingPress** | Premium | Yes | Page caching | WP Rocket | Built-in unused CSS removal; stronger CWV optimization |
| 5 | **Complianz Premium** | Premium | Yes | Cookie consent | Cookiebot, Borlabs Cookie | Dutch-specific configuration; GTM Consent Mode v2 integration; auto cookiebeleid page |
| 6 | **Wordfence Premium** | Premium | Yes | Security WAF | Solid Security Pro | Market leader; 2FA; brute force; malware scan; custom login URL |
| 7 | **Post SMTP** | Free | Yes | Email delivery | WP Mail SMTP, FluentSMTP | Free; email logging; SendGrid/Mailgun/SES integration |
| 8 | **BlogVault / UpdraftPlus** | Premium | Yes | Backups | Jetpack Backup, host-managed | 30/4/12 retention; offsite cloud; one-click restore; WC CSV export |
| 9 | **ShortPixel / Imagify** | Prem/Freemium | No | WebP conversion | Converter for Media, WebP Express | Auto-convert on upload; bulk optimization; CDN delivery option |
| 10 | **Relevanssi** | Free | No | Site search | SearchWP (premium) | Free; Dutch stemming; relevance sorting; custom field indexing |
| 11 | **WP-Optimize** | Free | No | Database cleanup | Advanced Database Cleaner | Reputable; scheduled cleanup; revisions/transient/spam removal |
| 12 | **Mollie for WooCommerce** | Free | Conditional | Payment gateway | Stripe, PayPal standalone | Dutch provider; iDEAL support; modern API; free plugin |
| 13 | **Google Site Kit** (optional) | Free | No | Analytics dashboard | Manual GA4/GTM/GSC setup | Unified Google services dashboard in WP Admin; simplifies client access |

### 14.2 Plugin Update Policy

| Update Type | Action | Verification |
|---|---|---|
| Minor/Patch (security) | Auto-update enabled | None (automated) |
| Major release | Test on staging first | Smoke test: Home, 1 service page, 1 product page, Contact form, mobile view |
| Update cycle | Monthly maintenance window | All plugins checked; updates applied to staging; tested; applied to production |

### 14.3 Plugin Removal Policy

1. Plugin is deactivated (not used).
2. Wait 1 week (verify no issues from deactivation).
3. Delete plugin files.
4. Clean up any leftover database tables/options via WP-Optimize.

**Rule:** No inactive plugins remain installed. Zero tolerance for "just in case" plugins.

### 14.4 Plugin Evaluation Criteria (for future additions)

| Criterion | Threshold |
|---|---|
| Last updated | Within the last 6 months |
| Active installations | ≥ 10,000 |
| WordPress version tested | Compatible with 6.7+ |
| PHP version tested | Compatible with 8.2+ |
| Support responsiveness | Issues resolved within 2 weeks |
| Performance impact | No measurable LCP increase (>50ms) |
| Security record | No known vulnerabilities (Wordfence scan) |

---

## 15. Security

### 15.1 WordPress Roles & Capabilities

| Role | Key Capabilities | Accounts |
|---|---|---|
| **Administrator** | Full access: manage content, plugins, themes, users, settings, WooCommerce | 2 minimum (developer + client site owner) |
| **Editor** | CRUD pages, posts, CPTs; view GF entries; view Rank Math data; moderate WC reviews | 1 (client content manager) |
| **Shop Manager** | Manage WC products, orders, coupons; view WC reports | 1 (if Airfixr shop active) |
| **SEO Manager** | Access Rank Math settings; view analytics | 0–1 (optional) |
| **Subscriber** | Read-only; own WC account | WooCommerce customers |

### 15.2 Login Protection

| Measure | Implementation | Plugin |
|---|---|---|
| **Custom Login URL** | Not `/wp-admin/` or `/wp-login.php` | Wordfence |
| **2FA** | TOTP (authenticator app) on all Admin/Editor/Shop Manager accounts | Wordfence |
| **Brute Force Protection** | 3 failed attempts → IP lockout (4 hours) | Wordfence |
| **Password Policy** | Minimum 12 characters; complexity enforced | Wordfence |
| **User Enumeration** | Block `?author=N`, `/wp-json/wp/v2/users` | Theme (`inc/security.php`) |
| **Application Passwords** | Disabled | — |

### 15.3 File Permissions

| Path | Permission | Owner |
|---|---|---|
| All directories | `755` | `wp:wp` |
| All files | `644` | `wp:wp` |
| `wp-config.php` | `400` | `wp:wp` |
| `.htaccess` | `644` | `wp:wp` |
| `wp-content/uploads/` | `755` | `wp:www-data` |

### 15.4 XML-RPC

**Disabled at web server level** (Nginx: `location = /xmlrpc.php { deny all; return 403; }`). Additionally disabled in theme (`inc/security.php`: `add_filter( 'xmlrpc_enabled', '__return_false' )`). Defense in depth.

### 15.5 REST API Policy

| Endpoint | Access | Implementation |
|---|---|---|
| Standard REST endpoints | Logged-in users (cookie auth) | Default WordPress behavior |
| `/wp/v2/users` | **Blocked** | `rest_endpoints` filter in `inc/security.php` |
| WooCommerce REST | Consumer Key + Secret if needed | Not used at launch |
| Gravity Forms REST | Not exposed | Default plugin configuration |

### 15.6 Security Headers

Applied at Nginx or Cloudflare level (not in WordPress):

| Header | Value |
|---|---|
| `Strict-Transport-Security` | `max-age=31536000; includeSubDomains; preload` |
| `X-Frame-Options` | `SAMEORIGIN` |
| `X-Content-Type-Options` | `nosniff` |
| `Referrer-Policy` | `strict-origin-when-cross-origin` |
| `Permissions-Policy` | `geolocation=(), microphone=(), camera=()` |

**Content-Security-Policy:** Not enforced at launch. Third-party scripts (GA4, GTM, reCAPTCHA, Mollie) complicate CSP implementation. Evaluate post-launch.

---

## 16. Performance

### 16.1 Caching

| Layer | Technology | Implementation |
|---|---|---|
| **Page Cache** | FlyingPress | Disk-based. Cleared on post/page update, plugin/theme update. All public pages cached. |
| **Object Cache** | Redis | In-memory. WP_Query results, transients, options. WordPress Redis plugin connects WP to Redis. |
| **Browser Cache** | HTTP headers | 1 year for versioned assets (`filemtime()` versioning via `wp_enqueue_style/script`). |
| **CDN Cache** | Cloudflare | Full-page edge caching. Bypass for WC cart/checkout/account, admin, AJAX. |
| **OPcache** | PHP OPcache | Compiled PHP bytecode. `memory_consumption=128M`, `max_accelerated_files=10000`. |

### 16.2 Page Cache Configuration (FlyingPress)

- **Cache all public pages:** Yes.
- **Cache logged-in users:** No.
- **Cache lifespan:** 10 hours (regenerated sooner on content change).
- **Critical CSS:** Auto-generated per page template.
- **Unused CSS:** Auto-removed (load used CSS only).
- **JavaScript:** Deferred. jQuery excluded from deferral if WC requires it.
- **Font optimization:** `font-display: swap`. Preloaded.

### 16.3 Asset Loading

**CSS:**
```php
// functions.php
wp_enqueue_style(
    'hds-style',
    HDS_URI . '/assets/css/main.css',
    [],
    filemtime( HDS_DIR . '/assets/css/main.css' )  // Cache-bust via filemtime
);
```

**JavaScript:**
```php
wp_enqueue_script(
    'hds-script',
    HDS_URI . '/assets/js/main.js',
    [],
    filemtime( HDS_DIR . '/assets/js/main.js' ),
    true  // Load in footer
);
```

**Defer strategy:** WordPress doesn't natively support `defer`. Added via `script_loader_tag` filter:

```php
function hds_add_defer_attribute( string $tag, string $handle ): string {
    if ( 'hds-script' === $handle ) {
        return str_replace( ' src', ' defer src', $tag );
    }
    return $tag;
}
add_filter( 'script_loader_tag', 'hds_add_defer_attribute', 10, 2 );
```

### 16.4 jQuery Policy

- **Theme code:** Zero jQuery dependency. All JS is vanilla.
- **WooCommerce:** WC 9.x may load jQuery for checkout. This is acceptable — WC's jQuery is well-optimized.
- **jQuery Migrate:** MUST NOT load. If WC loads it, find and remove the dependency.

### 16.5 Database Optimization

| Task | Frequency | Tool |
|---|---|---|
| Delete revisions > 30 days | Weekly (auto) | WP-Optimize |
| Delete trashed posts > 30 days | Weekly (auto) | WP-Optimize |
| Delete spam comments | N/A (comments disabled) | — |
| Delete expired transients | Weekly (auto) | WP-Optimize |
| Optimize tables | Monthly (auto) | WP-Optimize |
| Autoloaded data audit | Quarterly (manual) | Query Monitor or WP-Optimize |

### 16.6 Heartbeat API

WordPress Heartbeat API is used for:
- Post locking (Block Editor)
- Session management (admin)

**Not disabled.** The performance impact is negligible for a site with modest concurrent users. If it becomes an issue, limit via:

```php
function hds_heartbeat_settings( array $settings ): array {
    $settings['interval'] = 60; // Default 15s → 60s (reduces admin-ajax requests)
    return $settings;
}
add_filter( 'heartbeat_settings', 'hds_heartbeat_settings' );
```

### 16.7 Cron Strategy

**WP-Cron (pseudo-cron):** Disabled (`DISABLE_WP_CRON = true`). Replaced with server-level cron job:

```
*/15 * * * * wp php /var/www/html/wp-cron.php > /dev/null 2>&1
```

Managed WordPress hosts (Kinsta, WP Engine, Cloud86) provide this automatically. For local Docker, the host machine must configure a cron job.

**Cron-dependent tasks:**
- Scheduled post publishing
- WooCommerce order cleanup
- Backup triggers (if not independently scheduled)
- WP-Optimize scheduled cleanup
- Rank Math SEO analysis (scheduled)

---

## 17. Backup Strategy

### 17.1 Automated Backups

| Configuration | Detail |
|---|---|
| **Plugin** | BlogVault or UpdraftPlus Premium |
| **Schedule** | Daily full backup (files + database), nightly |
| **Retention** | 30 daily, 4 weekly, 12 monthly |
| **Storage** | Offsite cloud (Google Drive, Dropbox, or S3) |
| **Pre-Update** | Auto-backup before every plugin/theme/core update |
| **WC Export** | Monthly CSV export of all orders → offsite storage (7-year retention for Dutch financial law) |

### 17.2 Restore Testing

**Monthly:** Restore latest backup to staging environment. Verify:
1. All pages load (spot-check 5 pages).
2. Admin login works.
3. Forms submit (test GF-1).
4. WooCommerce checkout works (test purchase).
5. Search returns results.

### 17.3 Recovery Runbook

Documented in a separate runbook (Sprint 7 deliverable). Printed copy given to client. Includes:
- Hosting support phone number
- Developer emergency contact
- DNS provider login instructions
- Step-by-step restore procedure
- Emergency contacts list

---

## 18. Deployment Strategy

### 18.1 Environments

| Environment | URL | WP_ENV | WP_DEBUG | Access |
|---|---|---|---|---|
| **Local** | `hds.local` | `local` | `true` | Developer only |
| **Staging** | `staging.helderduidelijkschoon.nl` | `staging` | `true` | Developer + Client (password) |
| **Production** | `helderduidelijkschoon.nl` | `production` | `false` (log only) | Public + Developer + Client (admin) |

### 18.2 Deployment Workflow

```
1. Developer: git push to staging branch
2. GitHub Actions: auto-deploy to staging
3. Staging: Developer + Client QA
4. Developer: merge staging → main
5. GitHub Actions:
   a. Pre-deploy backup (wp db export)
   b. rsync files to production
   c. wp cache flush (FlyingPress)
   d. Cloudflare API → purge cache
   e. wp rewrite flush
6. Post-deploy: Smoke test (Home, Contact, WC, mobile menu)
```

### 18.3 Files Excluded from Deployment

```yaml
# .github/workflows/deploy.yml EXCLUDE list:
/.git/
/.github/
/.codegraph/
/.omo/
/docs/
/Docker/
/node_modules/
/vendor/
/tests/
/.env
/.env.*
! .env.example
/docker-compose.yml
/composer.json
/composer.lock
/package.json
/package-lock.json
/.eslintrc.js
/.stylelintrc.json
/phpcs.xml
/wp-content/debug.log
/wp-content/uploads/         # (media is on production, not in repo)
```

### 18.4 Database Migration (Launch Only)

For the initial launch (Sprint 8), the database is migrated from staging to production:
1. Export staging DB via WP-CLI: `wp db export staging-export.sql`
2. Search-replace URLs: `wp search-replace 'staging.helderduidelijkschoon.nl' 'helderduidelijkschoon.nl'`
3. Import to production DB.
4. Verify.

For ongoing deployments (post-launch), the database is NOT deployed. Only theme files are deployed. Content (pages, posts, form entries) is managed directly on production via WP Admin.

### 18.5 Rollback Strategy

1. Restore pre-deploy backup (auto-taken by GitHub Actions before deployment).
2. If backup is on staging: restore to staging → verify → deploy to production.
3. Time objective: < 30 minutes for plugin/theme updates; < 2 hours for complete site failure.

---

## 19. Coding Standards

### 19.1 PHP

| Rule | Standard |
|---|---|
| **Base** | WordPress Coding Standards (Core + Docs + Security + PHP + DB + WP) |
| **PHP Version** | 8.2+ (typed properties, match expressions, named arguments, enums) |
| **Functions** | `hds_` prefix, snake_case (`hds_get_phone()`) |
| **Type Hints** | All functions use parameter and return type hints (`function hds_get_phone(): string`) |
| **Yoda Conditions** | Enforced (`if ( true === $value )`) |
| **Strict Comparisons** | `===` and `!==`, not `==` and `!=` |
| **Escaping** | `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses()` for all output |
| **Sanitization** | `sanitize_text_field()`, `sanitize_email()`, `absint()` for all inputs |
| **Internationalization** | `__()`, `_e()`, `esc_html__()`, `esc_attr__()` with textdomain `hds` |
| **Nonces** | `wp_nonce_field()` + `wp_verify_nonce()` / `check_admin_referer()` on all custom forms |
| **Capabilities** | `current_user_can()` before privileged operations |
| **SQL** | `$wpdb->prepare()` for all custom queries. NO raw string interpolation. |
| **Banned** | `eval()`, `base64_decode()`, `extract()`, `md5()` for passwords |

### 19.2 CSS

| Rule | Standard |
|---|---|
| **Naming** | BEM-like: `.hds-component__element--modifier` |
| **Custom Properties** | `--hds-color-primary`, `--hds-space-4` (from theme.json / main.css) |
| **Media Queries** | Mobile-first (`min-width`). Breakpoints: 768px, 1024px, 1280px. |
| **No `!important`** | Except for utility override classes (`.hds-sr-only`, `.hds-hidden`) |
| **No ID Selectors** | For styling (IDs for JS hooks and ARIA only) |
| **Nesting** | Maximum 3 levels deep |
| **Performance** | One production file (`main.css`). Minified for production. |

### 19.3 JavaScript

| Rule | Standard |
|---|---|
| **Language** | Vanilla ES6+. No jQuery in theme code. |
| **Loading** | `defer` attribute. `$in_footer = true`. |
| **IIFE** | Wrap in Immediately Invoked Function Expression to avoid global scope pollution. |
| **Strict Mode** | `'use strict';` at top of every script. |
| **No Inline** | No `<script>` tags in templates. Use `wp_add_inline_script()` for config data. |
| **No Console** | `console.log()` removed before production. `console.warn()` and `console.error()` acceptable. |
| **Event Delegation** | Use for dynamic elements (e.g., mobile menu items). |

### 19.4 Accessibility

| Rule | Standard |
|---|---|
| Semantic HTML | `<header>`, `<nav>`, `<main>`, `<footer>`, `<section>`, `<article>` |
| ARIA | Used only when HTML semantics are insufficient (e.g., `aria-expanded`, `aria-controls`, `aria-describedby`) |
| Focus | Visible focus indicators on all interactive elements. Logical tab order. |
| Labels | Every form input has an associated `<label>`. Required: `aria-required="true"`. |
| Alt Text | All non-decorative images have descriptive Dutch alt text. |
| Reduced Motion | CSS animations wrapped in `@media (prefers-reduced-motion: no-preference)`. |

### 19.5 Internationalization Readiness

**Even though the site is single-language Dutch (nl-NL), all user-facing strings are internationalized.** This is forward-compatibility for potential multilingual expansion. If multilingual support is added (WPML/Polylang), all strings are ready.

```php
// Correct:
echo esc_html__( 'Lees meer', 'hds' );

// Incorrect:
echo 'Lees meer';
```

**POT file** (`languages/hds.pot`) must be maintained. All textdomain references are `hds`.

---

## 20. Folder Structure

### 20.1 Repository Root

```
cleaning-company/                           # Git repository root
├── .github/
│   └── workflows/
│       └── deploy.yml                      # CI/CD pipeline
├── Docker/
│   ├── php/
│   │   ├── Dockerfile                      # PHP 8.2 FPM container
│   │   └── php.ini                         # PHP configuration
│   └── nginx/
│       └── default.conf                    # Nginx server config
├── docs/                                   # All project documentation
│   ├── architecture/
│   ├── design/
│   ├── planning/
│   ├── review/
│   ├── specifications/
│   └── rebuild-spec/
├── wp-content/
│   └── themes/
│       └── hds/                            # THEME ROOT (production code)
│           ├── theme.json
│           ├── style.css
│           ├── screenshot.png
│           ├── functions.php
│           ├── index.php
│           ├── front-page.php
│           ├── page.php
│           ├── single.php
│           ├── archive.php
│           ├── search.php
│           ├── 404.php
│           ├── assets/
│           ├── inc/
│           ├── parts/
│           ├── page-templates/
│           └── languages/
├── .editorconfig
├── .env.example
├── .eslintrc.js
├── .gitattributes
├── .gitignore
├── .stylelintrc.json
├── composer.json
├── docker-compose.yml
├── Makefile
├── package.json
├── phpcs.xml
└── wp-config-env.php
```

### 20.2 Files NOT in Repository

Excluded by `.gitignore`:
- WordPress core (`/wp-admin/`, `/wp-includes/`)
- Uploads (`/wp-content/uploads/`)
- Config (`wp-config.php`, `.env`)
- Dependencies (`/vendor/`, `/node_modules/`)
- Caches, logs, backups, IDE files, OS files

---

## 21. Traceability

### 21.1 Requirement Coverage

| WTA Section | RTM Requirement IDs | FS Section | NFR Section | DS Section | SA Section |
|---|---|---|---|---|---|
| 2. WordPress Version | TR-001..003, TR-023 | — | NFR §12.3–12.4 | — | SA §4.2 |
| 3. Theme Architecture | TR-018, TR-020, TR-021, ACC-004..005 | FS §4.1–4.4, FS §4.13–4.14 | NFR §11.1, NFR §11.3 | DS §7 | SA §10 |
| 4. Content Architecture | CON-001..029 | FS §4.1–4.20 | NFR §10.3 | — | SA §15 |
| 5. Custom Post Types | FR-041..045 | FS §4.5–4.6 | — | DS §7.12 | SA §6 |
| 6. Taxonomies | — | FS §4.20 | — | — | — |
| 7. Custom Fields | TR-022 | FS §4.2 | — | — | SA §6 |
| 8. Gutenberg Block Strategy | TR-020, TR-021, UIX-008..013 | FS §4.1–4.2 | NFR §8.2 | DS §14 | SA §10.3 |
| 9. Template Mapping | — | FS §4.1–4.20 | — | DS §8 | SA §11 |
| 10. Forms Architecture | FR-001..003, FR-019..021, SEC-003..006 | FS §6 | NFR §6.10, NFR §7.3 | DS §7.11 | SA §9 |
| 11. Media Management | PERF-007, PERF-011, ACC-006 | — | NFR §3.5 | DS §3.8 | SA §9 |
| 12. SEO Integration | SEO-001..028 | FS §11 | NFR §9 | DS §13 | SA §14 |
| 13. WooCommerce Architecture | FR-022..027, WC-001..012 | FS §4.10, FS §12 | NFR §3.4, NFR §12.5 | DS §8.11 | SA §9 |
| 14. Plugin Architecture | TR-006..012, TR-031..032 | — | NFR §11.5 | — | SA §9 |
| 15. Security | SEC-001..016 | FS §13 | NFR §6 | — | SA §13 |
| 16. Performance | PERF-001..014 | FS §15 | NFR §3 | — | SA §12 |
| 17. Backup Strategy | OPS-001, CMP-006 | — | NFR §4.3–4.5 | — | SA §18 |
| 18. Deployment Strategy | TR-019, OPS-002..003 | — | NFR §4.1 | — | SA §16 |
| 19. Coding Standards | TR-018..022 | — | NFR §11.1–11.2 | — | — |
| 20. Folder Structure | — | — | NFR §11.3 | — | SA §10.2 |

### 21.2 RTM Coverage Summary

| RTM Category | Total Reqs | Mapped in WTA | Coverage |
|---|---|---|---|
| Business (BR) | 18 | 4 (via FS mapping) | 100% (indirect) |
| Functional (FR) | 48 | 32 | 67% (direct) |
| Technical (TR) | 37 | 30 | 81% |
| Security (SEC) | 16 | 16 | 100% |
| SEO (SEO) | 28 | 28 | 100% |
| Performance (PERF) | 14 | 14 | 100% |
| Accessibility (ACC) | 20 | 6 (via DS/FS mapping) | 100% (indirect) |
| Content (CON) | 32 | 32 | 100% |
| WooCommerce (WC) | 12 | 12 | 100% |
| Operational (OPS) | 8 | 6 | 75% |
| Compliance (CMP) | 13 | 6 | 46% (compliance in Sprint 6) |
| **Total** | **274** | **186 direct + 88 indirect** | **100%** |

---

## 22. Implementation Readiness Checklist

### 22.1 Pre-Development Gate (Sprint 1 — COMPLETE ✅)

- [x] WordPress 6.7+ installed on staging
- [x] Database prefix changed to `hds_`
- [x] All 13 plugins installed and activated
- [x] Theme scaffold created (`hds/` with all files from §3.2)
- [x] `theme.json` configured with design tokens
- [x] CPTs registered (`hds_testimonial`, `hds_vacancy`)
- [x] 14 custom fields registered via `register_post_meta()`
- [x] 11 Customizer fields registered
- [x] 4 custom blocks registered with PHP render_callbacks
- [x] 7 block patterns registered
- [x] 7 block style variations registered
- [x] 5 navigation menu locations registered
- [x] `wp-config-env.php` configured with environment detection
- [x] `.gitignore`, `.gitattributes`, `.editorconfig` in place
- [x] CI/CD pipeline configured (`.github/workflows/deploy.yml`)
- [x] Docker local environment configured (`docker-compose.yml`)
- [x] Coding standards tools in place (PHPCS, ESLint, Stylelint)

### 22.2 Pre-Content Gate (Sprint 2 — IN PROGRESS)

- [ ] All 13 page templates built (7 done ✅ — page-service, page-contact, page-quote, page-category-landing, page-about, page-faq, page-legal; 6 standard ✅ — front-page, page, single, archive, search, 404)
- [ ] Gravity Forms GF-1 (Contact) configured with 9 fields + reCAPTCHA + email notifications
- [ ] Gravity Forms GF-2 (Offerte) configured with 13 fields + file upload + reCAPTCHA
- [ ] Gravity Forms GF-3 (Vacature) configured (deferred to Sprint 3)
- [ ] Rank Math Pro basic configuration (site type, social image, breadcrumbs)
- [ ] FlyingPress configured (page cache, critical CSS, JS deferral)
- [ ] Cloudflare CDN configured (SSL Full Strict, cache bypass rules)
- [ ] Post SMTP configured with email delivery verified
- [ ] Daily backups configured with test restore verified
- [ ] 32 pages created in WordPress admin with correct templates assigned (Sprint 2–3 content tasks)
- [ ] All 7 service pages: 300+ words Dutch content + cross-links + CTA
- [ ] Contact Page: form shortcode placed + Contact Info Block configured

### 22.3 Pre-Launch Gate (Sprint 7 — FUTURE)

- [ ] All 32 pages return HTTP 200
- [ ] Screaming Frog: zero broken internal links, zero orphans
- [ ] All forms submit and deliver email within 2 minutes
- [ ] WooCommerce purchase flow end-to-end (if applicable)
- [ ] All meta titles/descriptions unique and populated
- [ ] XML Sitemap returns 200 with valid XML; zero attachment pages
- [ ] All 301 redirects tested and working; zero redirect chains
- [ ] All 9 schema types validated via Google Rich Results Test
- [ ] PSI Mobile ≥ 90, Desktop ≥ 95 on all template types
- [ ] Lighthouse Accessibility = 100; axe DevTools zero critical/serious
- [ ] Cookie consent banner functional; no cookies before consent
- [ ] 2FA on all Admin/Editor/Shop Manager accounts
- [ ] Daily backups verified (test restore successful)
- [ ] Staging: noindex + password-protected
- [ ] Production: index,follow + HTTPS enforced + HSTS
- [ ] Client sign-off on staging
- [ ] Beheergids delivered (Sprint 8)

---

**This WordPress Technical Architecture is the implementation guide for backend and frontend developers. It provides every configuration value, code pattern, and operational procedure needed to build, deploy, and maintain the HDS Onderhoudsdiensten WordPress platform. All decisions are traceable to the RTM, FS, NFR, DS, and SA documents.**

**END OF WORDPRESS TECHNICAL ARCHITECTURE — Version 1.0.0**
