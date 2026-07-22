# HDS Onderhoudsdiensten — Developer Handoff & Implementation Guide

**Document ID:** DHG-001 | **Version:** 1.0.0 | **Status:** Approved for Sprint 5 Implementation
**Project:** helderduidelijkschoon.nl — Ground-Up Rebuild | **Date:** July 2026

**Role:** This is the single document a new developer needs to begin implementation. Every architectural decision, design specification, behavior rule, and checklist is consolidated here with direct references to source documents for detail.

---

## 1. Project Overview

### 1.1 Implementation Objectives

| # | Objective | Target |
|---|---|---|
| O01 | Restore web-based lead capture | Contact + Quote forms delivering to info@helderduidelijkschoon.nl within 2 minutes |
| O02 | Rebuild all 32 pages with Block Editor content | Zero shortcodes. Zero page builders. Zero Divi remnants. |
| O03 | Achieve PSI 90+ mobile / 95+ desktop | Pre-launch QA gate (Sprint 7). FlyingPress + Redis + Cloudflare. |
| O04 | WCAG 2.2 AA on every page template | Lighthouse Accessibility = 100. axe DevTools zero critical/serious. |
| O05 | Full GDPR/AVG compliance at launch | Privacyverklaring + Cookie consent + KVK/BTW display + Data retention |
| O06 | Zero broken pages or sitemaps | `/contact/` HTTP 200 (was 500). `/reguliere-schoonmaak/` HTTP 200 (was 404). `/page-sitemap.xml` HTTP 200 |
| O07 | Client can edit all content via Block Editor | Beheergids delivered (Sprint 8). No developer needed for content changes. |

### 1.2 Technology Stack

| Layer | Technology | Version | Document |
|---|---|---|---|
| **CMS** | WordPress | 6.7+ | WTA-001 §2 |
| **PHP** | — | 8.2+ | ADR-001 §3.1 |
| **Database** | MySQL / MariaDB (InnoDB, utf8mb4) | 8.0+ / 10.6+ | WTA-001 §2.3 |
| **Theme** | Custom Hybrid Block (`hds`) | 1.0.0 | ADR D-001, D-005 |
| **Page Builder** | Native Block Editor ONLY | Core | ADR D-002 |
| **eCommerce** | WooCommerce | 9.x+ | FS-001 §4.10 |
| **Forms** | Gravity Forms (Premium) | latest | ADR D-006 |
| **SEO** | Rank Math Pro | latest | ADR D-003 |
| **Caching** | FlyingPress + Redis | latest | ADR D-004 |
| **CDN / WAF** | Cloudflare | Free/Pro | SA-001 §4.2 |
| **Security** | Wordfence Premium | latest | ADR §3.12 |
| **Cookie Consent** | Complianz Premium | latest | WTA-001 §14 |
| **SMTP** | Post SMTP + SendGrid/Mailgun/SES | latest | NFR-001 §11.8 |
| **Backups** | BlogVault / UpdraftPlus Premium | latest | SA-001 §18 |
| **Images** | ShortPixel / Imagify | latest | WTA-001 §11 |
| **Search** | Relevanssi | latest | SA-001 §7.3 |
| **Analytics** | GA4 via GTM | latest | SEO-001 §14.4 |
| **CI/CD** | GitHub Actions | — | SA-001 §16 |

### 1.3 Architecture Summary

**Type:** Monolithic WordPress application with edge caching via Cloudflare CDN. NOT headless. Hybrid block theme (`theme.json` + PHP templates + Block Editor). 32 pages, 2 CPTs (testimonial, vacancy), 4 custom blocks, 7 block patterns, 7 page templates.

**Key Architectural Decisions (ADR-001):**
| ID | Decision | Reference |
|---|---|---|
| D-001 | Custom Hybrid Block Theme (not FSE, not GeneratePress/Kadence) | ADR §3.2 |
| D-002 | Native Block Editor only — all page builders BANNED | ADR §3.3 |
| D-003 | Rank Math Pro (built-in redirect manager) | ADR §3.8 |
| D-004 | FlyingPress (built-in unused CSS removal) | ADR §3.9 |
| D-005 | PHP templates for layout + Block Editor for content | ADR §3.2 |
| D-006 | hds_testimonial CPT non-public (block-queried only) | ADR §3.4 |
| D-007 | Company info in Theme Customizer (single NAP source) | ADR §3.6 |
| D-012 | FAQ via Yoast/Rank Math FAQ Block — NO CPT | ADR D-012 |
| D-013 | "Gevelreiniging" as canonical service name | ADR D-013 |
| D-014 | Service page ordering by menu_order | ADR D-014 |
| D-015 | Hide empty conditional sections entirely | ADR D-015 |
| D-016 | Breadcrumbs follow URL hierarchy (flat) | ADR D-016 |

### 1.4 Development Principles

| # | Principle | Rule |
|---|---|---|
| P1 | **Rebuild, Don't Repair** | Zero code, config, or decisions carry forward from old site |
| P2 | **No Page Builder** | Any third-party page builder = violation. Block Editor ONLY. |
| P3 | **Content Portability** | All content as standard Block HTML. No shortcodes in `post_content`. |
| P4 | **Performance by Default** | PSI 90+/95+ mandatory at QA gate. Performance is architecture, not optimization. |
| P5 | **Mobile-First CSS** | Base styles target 320px. Desktop styles are additive via `min-width` queries. |
| P6 | **Dutch-First** | `lang="nl-NL"`. All UI strings in Dutch. All strings internationalized via `__()`/`_e()`. |
| P7 | **Accessibility Built-In** | WCAG 2.2 AA as baseline. Keyboard, screen reader, contrast — all verified before launch. |
| P8 | **Everything Traced** | Every task maps to RTM-001 requirement. Every component maps to DS-001. |

---

## 2. Repository Structure

### 2.1 Project Root

```
cleaning-company/
├── .github/workflows/deploy.yml       # CI/CD pipeline
├── .editorconfig                       # Editor settings
├── .eslintrc.js                        # JavaScript linting
├── .stylelintrc.json                   # CSS linting
├── phpcs.xml                           # PHP linting (WordPress-Core)
├── .gitignore                          # Git ignore rules
├── .gitattributes                      # Git attributes
├── .env.example                        # Environment template
├── composer.json                       # PHP dependencies
├── package.json                        # JS dependencies
├── docker-compose.yml                  # Local Docker environment
├── Docker/
│   ├── nginx/default.conf              # Nginx configuration
│   └── php/
│       ├── Dockerfile                  # PHP container
│       └── php.ini                     # PHP configuration
├── wp-config-env.php                   # Environment-aware WP config
├── Makefile                            # Build/utility commands
│
├── wp-content/
│   └── themes/hds/                     # THEME — implementation target
│
└── docs/                               # DOCUMENTATION
    ├── ProjectAnalysis.md              # SRC-01
    ├── ContentInventory.md             # SRC-02
    ├── BusinessRequirements.md         # SRC-03
    ├── FeatureList.md                  # SRC-04
    ├── SEOAudit.md                     # SRC-05
    ├── SiteMap.md                      # SRC-06
    ├── UserJourney.md                  # SRC-07
    ├── ImprovementSuggestions.md       # SRC-08
    ├── MASTER_PROJECT_SPECIFICATION.md # MPS-001
    ├── REQUIREMENTS_TRACEABILITY_MATRIX.md # RTM-001
    ├── ARCHITECTURE_READINESS_REVIEW.md    # ARR-001
    ├── SPRINT2_EXECUTION_PLAN.md
    ├── DEVELOPMENT_BACKLOG.md
    ├── architecture/
    │   ├── ADR.md                      # ADR-001
    │   ├── SOLUTION_ARCHITECTURE.md    # SAD-001
    │   ├── solution-architecture.md    # SA-001
    │   └── wordpress-technical-architecture.md # WTA-001
    ├── specifications/
    │   ├── functional-specification.md     # FS-001
    │   └── non-functional-requirements.md  # NFR-001
    ├── design/
    │   ├── design-system-specification.md          # DS-001
    │   ├── ux-wireframes-specification.md          # UXW-001
    │   ├── high-fidelity-ui-specification.md       # HFUI-001
    │   ├── responsive-interaction-specification.md # RIS-001
    │   └── ui-ux-specification.md
    ├── seo/
    │   └── seo-implementation-specification.md # SEO-001
    ├── planning/
    │   ├── product-backlog.md
    │   └── development-execution-plan.md
    ├── review/
    │   ├── project-validation-report.md
    │   ├── 01_Final_Architecture_Review.md
    │   ├── 02_Gap_Analysis.md
    │   ├── 03_Risk_Register.md
    │   ├── 04_Implementation_Readiness_Report.md
    │   ├── 05_Final_Project_Checklist.md
    │   └── 06_Architecture_Closure_Report.md
    ├── rebuild-spec/
    │   ├── 01_Architecture_Sitemap.md
    │   ├── 02_Navigation_URLs_Migration.md
    │   ├── 03_SEO_Metadata_Strategy.md
    │   ├── 04_Performance_Accessibility_Security_GDPR.md
    │   ├── 05_Components_CMS_Templates.md
    │   ├── 06_Backup_Deployment_GapAnalysis.md
    │   ├── 07_Checklists.md
    │   └── 08_Launch_Risks_Questions_Future.md
    └── development/
        └── developer-handoff-guide.md   # THIS DOCUMENT (DHG-001)
```

### 2.2 Theme Implementation Target

```
wp-content/themes/hds/
├── theme.json                         # Design tokens, block styles, template declarations
├── style.css                          # Theme metadata (Name, Author, Version, Text Domain)
├── screenshot.png                     # 1200×900 theme preview
├── functions.php                      # Bootstrap: constants, requires inc/*.php
├── index.php                          # Ultimate fallback
│
├── assets/
│   ├── css/
│   │   ├── main.css                   # Production stylesheet (all styles)
│   │   └── editor.css                 # Block Editor mirror styles
│   ├── js/
│   │   ├── main.js                    # Navigation toggle, keyboard a11y, progressive enhancements
│   │   └── blocks/                    # Editor scripts for custom blocks
│   │       ├── service-card.js
│   │       ├── testimonial.js
│   │       ├── job-listing.js
│   │       └── contact-info.js
│   ├── images/                        # Theme images (logo.svg)
│   └── fonts/                         # Self-hosted Open Sans (WOFF2, subset Latin + Dutch)
│
├── inc/
│   ├── setup.php                      # Image sizes, disable unused WP features, activation hook
│   ├── cpts.php                       # CPT registration: hds_testimonial, hds_vacancy
│   ├── custom-fields.php              # 14 register_post_meta() calls (no ACF)
│   ├── customizer.php                 # 10 Company Information Customizer fields
│   ├── helpers.php                    # hds_get_phone(), hds_breadcrumbs(), etc.
│   ├── security.php                   # XML-RPC disable, REST hardening, author/attachment redirects
│   ├── patterns.php                   # 7 block patterns (register_block_pattern)
│   ├── blocks.php                     # 4 custom blocks (register_block_type with render_callback)
│   └── schema.php                     # 5 JSON-LD generators
│
├── parts/
│   ├── header.php                     # DOCTYPE → <head> → skip-link → <header>
│   ├── footer.php                     # 5-col grid → company info → legal → social → copyright
│   ├── breadcrumbs.php               # BreadcrumbList with Schema.org microdata
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
├── front-page.php                     # P01 (Home)
├── page.php                           # Default page (P13–P15, P23, P32)
├── single.php                         # Blog post (P30)
├── archive.php                        # Blog index (P29)
├── search.php                         # Search results
├── 404.php                            # Custom 404 (P31)
└── languages/
    └── hds.pot                        # Translation template
```

---

## 3. WordPress Implementation

### 3.1 Theme Architecture

**Type:** Custom Hybrid Block Theme — `theme.json` provides design tokens; PHP templates provide structured layouts; Block Editor provides content areas. NOT Full Site Editing.

**Theme Slug:** `hds` | **Text Domain:** `hds` | **Theme Version:** `1.0.0`

**`functions.php` Boot Sequence:**
```php
define( 'HDS_VERSION', '1.0.0' );
define( 'HDS_DIR', get_template_directory() );
define( 'HDS_URI', get_template_directory_uri() );

require_once HDS_DIR . '/inc/setup.php';
require_once HDS_DIR . '/inc/cpts.php';
require_once HDS_DIR . '/inc/custom-fields.php';
require_once HDS_DIR . '/inc/customizer.php';
require_once HDS_DIR . '/inc/helpers.php';
require_once HDS_DIR . '/inc/security.php';
require_once HDS_DIR . '/inc/patterns.php';
require_once HDS_DIR . '/inc/blocks.php';
require_once HDS_DIR . '/inc/schema.php';
```

### 3.2 Template Hierarchy (Resolution Order)

| URL | Post Type | Template Resolved |
|---|---|---|
| `/` | Front Page | `front-page.php` |
| `/glasbewassing/` | Page (Service template) | `page-templates/page-service.php` |
| `/glas-en-gevel/` | Page (Category Landing) | `page-templates/page-category-landing.php` |
| `/over-hds/` | Page (About template) | `page-templates/page-about.php` |
| `/contact/` | Page (Contact template) | `page-templates/page-contact.php` |
| `/offerte-aanvragen/` | Page (Quote template) | `page-templates/page-quote.php` |
| `/veelgestelde-vragen/` | Page (FAQ template) | `page-templates/page-faq.php` |
| `/privacyverklaring/` | Page (Legal template) | `page-templates/page-legal.php` |
| `/referenties/` | Page (Default) | `page.php` |
| `/vacatures/` | Page (Default) | `page.php` |
| `/downloads/` | Page (Default) | `page.php` |
| `/luchtreiniging/` | Page (Default) | `page.php` |
| `/bedankt/` | Page (Default) | `page.php` |
| `/kennisbank/` | Archive (Post) | `archive.php` |
| `/kennisbank/{slug}/` | Single Post | `single.php` |
| `/winkel/` | WooCommerce Shop | WooCommerce plugin template |
| `/product/{slug}/` | WooCommerce Product | WooCommerce plugin template |
| `/winkelmand/` | WooCommerce Cart | WooCommerce plugin template |
| `/afrekenen/` | WooCommerce Checkout | WooCommerce plugin template |
| `/?s=query` | Search | `search.php` |
| Any 404 | 404 | `404.php` |

### 3.3 Custom Post Types

**File:** `inc/cpts.php`

| CPT Key | Public | Has Archive | Rewrite | Supports | Purpose |
|---|---|---|---|---|---|
| `hds_testimonial` | `false` | `false` | `false` | title, editor | Client testimonials — block-queried ONLY |
| `hds_vacancy` | `true` | `false` | `vacatures` | title, editor | Job vacancies — displayed via `hds/job-listing` |

> ⚠️ `hds_faq` CPT does NOT exist. FAQ is managed via Yoast/Rank Math FAQ Block on standard Page (ADR D-012). The `hds_theme_activation()` function in `inc/setup.php:79` still references `hds_register_faq_cpt()` — this must be removed during Sprint 5 cleanup.

### 3.4 Custom Fields (Post Meta)

**File:** `inc/custom-fields.php` | **Method:** `register_post_meta()` — NO ACF dependency.

**Service Page Settings** (applied to Page with Service template):
| Field | Type | Default | Description |
|---|---|---|---|
| `hds_subtitle` | string | `''` | Hero subtitle text |
| `hds_hero_image` | integer | `0` | Media ID for hero background |
| `hds_service_icon` | string | `''` | Phosphor icon name for service cards |
| `hds_cta_override` | string | `''` | Custom CTA button text |

**Testimonial Details** (applied to `hds_testimonial`):
| Field | Type | Default | Description |
|---|---|---|---|
| `hds_author_name` | string | `''` | Testifier name |
| `hds_company_name` | string | `''` | Company name |
| `hds_star_rating` | integer | `0` | Rating 1-5 |
| `hds_related_service` | integer | `0` | Related service page ID |

**Vacancy Details** (applied to `hds_vacancy`):
| Field | Type | Default | Description |
|---|---|---|---|
| `hds_hours_per_week` | string | `''` | Hours (e.g., "32-40") |
| `hds_location` | string | `''` | Work location |
| `hds_start_date` | string | `''` | Desired start date |
| `hds_application_email` | string | `''` | Application email |
| `hds_deadline` | string | `''` | Closing date |
| `hds_is_active` | boolean | `false` | Vacancy active? |

### 3.5 Theme Customizer (Company Information)

**File:** `inc/customizer.php` | **Section:** "Bedrijfsgegevens"

| Setting ID | Type | Default | Usage |
|---|---|---|---|
| `hds_address` | text | `''` | Street + number |
| `hds_postal_city` | text | `''` | Postal code + city |
| `hds_phone` | text | `'0164-652846'` | Phone number |
| `hds_email` | text | `'info@helderduidelijkschoon.nl'` | Email |
| `hds_kvk` | text | `''` | KVK number |
| `hds_btw` | text | `''` | BTW number |
| `hds_facebook_url` | text | `''` | Facebook URL |
| `hds_instagram_url` | text | `''` | Instagram URL |
| `hds_gbp_url` | text | `''` | Google Business Profile URL |
| `hds_opening_hours` | textarea | `''` | Opening hours |

**Usage in templates:**
```php
$phone = get_theme_mod( 'hds_phone', '0164-652846' );
// Helper: hds_get_phone() does this with fallback
```

### 3.6 Navigation Menus

**File:** `functions.php` (register_nav_menus)
| Location | Slug | Purpose |
|---|---|---|
| Hoofdmenu | `primary` | Desktop + mobile navigation |
| Footer — Diensten | `footer-services` | Footer column 1 |
| Footer — Over HDS | `footer-about` | Footer column 2 |
| Footer — Luchtreiniging | `footer-airfixr` | Footer column 4 |
| Footer — Juridisch | `footer-legal` | Footer column 5 |

### 3.7 Widgets

NOT USED. No widget areas registered. All content via Block Editor + Template Parts + Block Patterns.

---

## 4. ACF Strategy

**Decision:** NO ACF. The project uses WordPress core `register_post_meta()` for all 14 custom fields.

**Rationale (ADR D-007):**
- Zero plugin dependency. Zero license cost. Zero update risk.
- Fields are REST API-accessible for the Block Editor.
- 14 simple fields (text, integer, boolean) don't justify ACF's overhead.
- If post-launch requirements demand repeaters or flexible content, ACF can be introduced at that point.

**Developer Note:** If ACF is introduced post-launch, migrate existing `register_post_meta()` fields to ACF and remove `inc/custom-fields.php`. This is a straightforward migration — post meta keys remain identical (`hds_` prefix).

---

## 5. Gutenberg Strategy

### 5.1 Core Blocks

All WordPress core blocks are **allowed by default**. No block restrictions via `allowed_block_types_all` filter. The `theme.json` and `main.css` provide appropriate styling for all core blocks.

### 5.2 Custom Blocks (4)

**File:** `inc/blocks.php` | **Editor Scripts:** `assets/js/blocks/`

| Block Name | Category | Purpose | Render Callback |
|---|---|---|---|
| `hds/service-card` | `hds-patterns` | Single service card with icon, title, excerpt, link | `hds_render_service_card()` |
| `hds/testimonial` | `hds-patterns` | Testimonial grid (queries hds_testimonial CPT) | `hds_render_testimonial()` |
| `hds/job-listing` | `hds-patterns` | Vacancy cards (queries hds_vacancy CPT, active only) | `hds_render_job_listing()` |
| `hds/contact-info` | `hds-patterns` | Company info from Customizer (phone, email, address, KVK, BTW) | `hds_render_contact_info()` |

**Key Rule:** All blocks use dynamic rendering (`render_callback`). No `save()` function in JS — all blocks return `null`. This ensures blocks always reflect current data.

### 5.3 Block Patterns (7)

**File:** `inc/patterns.php` | **Category:** `hds-patterns`

| Pattern | Usage |
|---|---|
| `hds/cta-banner` | Full-width colored CTA section |
| `hds/hero-section` | Page hero with H1, subtitle, CTA |
| `hds/usp-grid` | 3-column USP cards |
| `hds/content-with-image` | Two-column content + image |
| `hds/cross-sell-services` | Related services section |
| `hds/contact-info-block` | Company contact details |
| `hds/404-content` | 404 page content structure |

**Remaining 9 patterns** from the original 16-pattern specification are delivered via custom blocks (service cards, testimonials, job listings, contact info) or Block Editor compositions (FAQ Accordion via Yoast block, Service Icon List via core list block, Client Logo Carousel via core gallery, Download Card List, Latest Blog Posts, Related Posts).

### 5.4 Block Style Variations (6)

| Style Name | Applied To | Effect |
|---|---|---|
| `is-style-secondary` | `core/button` | Outlined button |
| `is-style-cta` | `core/button` | Large CTA button (accent/orange) |
| `is-style-card` | `core/group` | White bg, border-radius, shadow |
| `is-style-banner` | `core/group` | Colored full-width banner |
| `is-style-icon-list` | `core/list` | Custom checkmark bullets |
| `is-style-no-bullet` | `core/list` | No bullets |

> Note: `is-style-primary` removed — `core/button` already renders as primary by default (redundant).

### 5.5 Block Categories

One custom category: `hds-patterns` — "HDS Patronen". All HDS patterns and custom blocks use this category.

---

## 6. Component Mapping

### 6.1 UI Component → Implementation

| UI Component | DS-001 Ref | HFUI Ref | RIS Ref | Implementation File | RTM REQ |
|---|---|---|---|---|---|
| **Header** | §7.1 | §14.1 | §4 | `parts/header.php` | REQ-FR-031, REQ-ACC-003 |
| **Footer** | §7.5 | §14.2 | §5 | `parts/footer.php` | REQ-FR-033, REQ-UIX-004 |
| **Desktop Navigation** | §7.2 | §14.1 | §3.1 | `parts/header.php` + CSS | REQ-FR-031, REQ-ACC-020 |
| **Mobile Navigation** | §7.3 | §14.1 | §3.3 | `assets/js/main.js` + CSS | REQ-FR-032, REQ-ACC-020 |
| **Breadcrumbs** | §7.4 | §14.3 | — | `parts/breadcrumbs.php` | REQ-FR-034, REQ-SEO |
| **Hero Section** | §9.1 | §2.1, §3.1 | — | Pattern `hds/hero-section` | REQ-FR-013, REQ-UIX-008 |
| **Service Card** | §8.2 | §2.2 | §8 | `hds/service-card` block | REQ-FR-004..010 |
| **USP Grid** | §9.2 | §2.3 | — | Pattern `hds/usp-grid` | REQ-UIX-010 |
| **CTA Banner** | §8.4 | §2.6 | — | Pattern `hds/cta-banner` | REQ-UIX-011 |
| **Testimonial Block** | §8.3 | §2.5 | §8 | `hds/testimonial` block | REQ-FR-041..043 |
| **FAQ Accordion** | §8.5 | §11 | §16 | Yoast/Rank Math FAQ Block | REQ-SEO-027 |
| **Contact Form (GF-1)** | §6 | §9.3 | §6 | Gravity Forms shortcode | REQ-FR-001..003 |
| **Quote Form (GF-2)** | §6 | §10.3 | §6 | Gravity Forms shortcode | REQ-FR-019 |
| **Vacancy Card** | §8.1 | §7 | §8 | `hds/job-listing` block | REQ-FR-044..045 |
| **Blog Card** | §8.9 | §12.1 | §8 | Pattern | — |
| **Product Card** | §8.10 | §13.1 | — | WooCommerce template | — |
| **Cookie Banner** | §8.6 | §14.6 | §17 | Complianz Premium | REQ-CMP-002 |
| **404 Page** | §9.9 | §16.5 | §21.1 | `404.php` + Pattern `hds/404-content` | REQ-FR-016 |
| **Pagination** | §7.6 | §14.8 | — | `the_posts_pagination()` | — |

### 6.2 Page → Template Mapping

| Page ID | URL | Template File | Page Template Name (Dropdown) |
|---|---|---|---|
| P01 | `/` | `front-page.php` | N/A (automatic) |
| P02-P08 | `/glasbewassing/` etc. | `page-templates/page-service.php` | Service |
| P09-P10 | `/glas-en-gevel/`, `/schoonmaakdiensten/` | `page-templates/page-category-landing.php` | Category Landing |
| P11-P12 | `/over-hds/`, `/kwaliteit-veiligheid/` | `page-templates/page-about.php` | About |
| P13-P15, P23 | `/referenties/`, `/vacatures/`, `/downloads/`, `/luchtreiniging/` | `page.php` | Default |
| P16 | `/contact/` | `page-templates/page-contact.php` | Contact |
| P17 | `/offerte-aanvragen/` | `page-templates/page-quote.php` | Offerte Aanvragen |
| P18 | `/veelgestelde-vragen/` | `page-templates/page-faq.php` | FAQ |
| P19-P22 | `/privacyverklaring/` etc. | `page-templates/page-legal.php` | Legal |
| P24-P28 | WooCommerce | Plugin templates | — |
| P29 | `/kennisbank/` | `archive.php` | — |
| P30 | `/kennisbank/{slug}/` | `single.php` | — |
| P31 | 404 | `404.php` | — |
| P32 | `/bedankt/` | `page.php` | Default |

---

## 7. Frontend Development Rules

### 7.1 HTML

- **DOCTYPE:** `<!DOCTYPE html>`
- **Language:** `<html lang="nl-NL">`
- **Charset:** `<meta charset="UTF-8">`
- **Viewport:** `<meta name="viewport" content="width=device-width, initial-scale=1.0">`
- **Semantic landmarks:** `<header>`, `<nav aria-label="Hoofdmenu">`, `<main id="content">`, `<footer>`, `<section>`, `<article>`, `<aside>`
- **Heading hierarchy:** One H1 per page. H1 → H2 → H3 with no skipped levels
- **Forms:** `<label>` for every input. `aria-required="true"` on required fields
- **Images:** `alt` on every `<img>`. Empty `alt=""` for decorative images
- **Links:** Descriptive text. No "klik hier". External links: `rel="noopener noreferrer"`

### 7.2 CSS

**File:** `assets/css/main.css` — single production stylesheet with modular sections:

1. Reset (`box-sizing`, margin/padding reset)
2. Accessibility (`.screen-reader-text`, `.skip-link`, `:focus-visible`)
3. Custom Properties (CSS variables consuming theme.json tokens)
4. Layout (`.container`, grid, flex utilities)
5. Typography (all element styles consuming theme.json tokens)
6. Header & Navigation (including mobile overlay)
7. Footer
8. Components (cards, buttons, forms, accordion, alerts, tables, pagination)
9. Block Styles (`is-style-*` variations)
10. WooCommerce overrides (only if needed)
11. Responsive (`min-width` media queries at 768, 1024, 1280px)
12. Print stylesheet
13. Reduced motion (`prefers-reduced-motion: reduce`)

**Rules:**
- BEM naming: `.hds-[block]__[element]--[modifier]`
- CSS Custom Properties for design tokens: `var(--wp--preset--color--primary)`
- No hardcoded hex values outside `theme.json`
- Mobile-first: base styles = mobile. Desktop styles = `@media (min-width: ...)`
- No `!important` except utility classes (`.hds-sr-only`, `.hds-hidden`)
- No ID selectors for styling
- Maximum 3 levels of nesting
- `@media (hover: hover)` for hover states (prevents sticky hover on touch)

### 7.3 JavaScript

**File:** `assets/js/main.js` — vanilla JavaScript, no jQuery (theme code only).

**Responsibilities:**
- Mobile menu toggle (hamburger → overlay open/close)
- Keyboard accessibility: Escape closes menus/dropdowns, arrow key navigation
- Back-to-top button visibility (IntersectionObserver)
- Sticky mobile CTA visibility (IntersectionObserver)
- Smooth scroll for anchor links and form error → field

**Rules:**
- ES6+ syntax
- `defer` attribute on all scripts
- No inline scripts (use `wp_add_inline_script()` for config data)
- Progressive enhancement: core functionality works without JavaScript
- No `console.log()` in production code
- Event delegation on dynamic elements

### 7.4 Accessibility Implementation

See §12 for the complete checklist. Key frontend rules:

- Skip-to-content link: first focusable element, visible on `:focus`, links to `<main id="content">`
- Focus ring: `outline: 2px solid var(--wp--preset--color--primary); outline-offset: 2px;`
- Never `outline: none` without a replacement indicator
- All interactive elements: min 44×44px touch targets
- Screen reader text: `.screen-reader-text` utility for visually hidden content
- `aria-expanded` on toggles, `aria-controls` linking toggles to panels
- `aria-live="polite"` for dynamic content updates (cart, search results)
- `aria-live="assertive"` for critical errors
- `aria-busy="true"` during loading states
- `prefers-reduced-motion` respected globally

### 7.5 Responsive Implementation

- Base CSS targets 320px+ viewports
- `@media (min-width: 768px)` — tablet enhancements (2-column grids)
- `@media (min-width: 1024px)` — desktop layout (3+ column grids, hover states, mega menus)
- `@media (min-width: 1280px)` — container clamping to `wideSize` (1200px)
- All grids: `auto-fit`/`auto-fill` with `minmax()` for fluid column adjustment
- Tables: horizontal scroll wrapper on mobile. Never collapse columns.
- Images: explicit `width`/`height` to prevent CLS

### 7.6 Animation Implementation

See RIS-001 §11 for the complete animation table. Key rules:
- Only animate `transform` and `opacity` (compositor-only — no layout triggers)
- Maximum duration: 300ms (except infinite spinners and skeleton loaders)
- Easing: `cubic-bezier(0.4, 0, 0.2, 1)` (standard), decelerate for entering elements, accelerate for exiting elements
- `prefers-reduced-motion: reduce` → all animations set to `0.01ms`

---

## 8. Backend Development Rules

### 8.1 PHP

**File standards:**
- WordPress Coding Standards enforced via PHP_CodeSniffer (`phpcs.xml`)
- Yoda conditions for equality checks
- Strict comparisons (`===`)
- All output escaped: `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses()`
- All inputs sanitized: `sanitize_text_field()`, `sanitize_email()`, etc.
- Nonces on all custom forms: `wp_nonce_field()` + `check_admin_referer()`
- Prepared SQL statements: `$wpdb->prepare()`
- Internationalization: `__()` / `_e()` with textdomain `'hds'`
- No `eval()`, no `base64_decode()`, no `extract()`

### 8.2 Business Logic

- Service card ordering: `menu_order` field on Pages (ADR D-014)
- Cross-sell rules: defined in FS-001 §4.2 per service page
- Empty state hiding: PHP conditional checks before rendering sections (ADR D-015)
- Bedankt page: reads `$_GET['type']` parameter to display dynamic heading
- Company info: single source of truth in Theme Customizer → `get_theme_mod()`

### 8.3 Validation

- Gravity Forms handles all form validation (client-side + server-side)
- File uploads: server-side MIME type check via `finfo(FILEINFO_MIME_TYPE)`
- reCAPTCHA v3: score thresholds configured in Gravity Forms
- Honeypot: hidden field — if filled, submission silently blocked

### 8.4 Security

See §11 for the complete checklist. Key backend rules:
- `DISALLOW_FILE_EDIT = true` in `wp-config.php`
- Database prefix: `hds_` (not `wp_`)
- Admin usernames: never "admin", "hds", or "helderduidelijkschoon"
- WordPress salts: generated fresh from `https://api.wordpress.org/secret-key/1.1/salt/`
- XML-RPC: disabled at server level (Nginx deny)
- REST API user endpoint: blocked via filter in `inc/security.php`
- Author archives: disabled → 301 redirect to home
- Attachment pages: disabled → 301 redirect to parent
- `wp-config.php`: moved above web root or permissions set to 400

### 8.5 Error Handling

- Production: `WP_DEBUG = false`, `WP_DEBUG_LOG = true`, `WP_DEBUG_DISPLAY = false`
- Form errors: Gravity Forms inline validation + error summary
- SMTP failure: Post SMTP logs. Gravity Forms entry serves as backup record.
- 404: custom `404.php` returns true HTTP 404
- 500: server-level static HTML (not WordPress-dependent)
- 503: custom `maintenance.php`

### 8.6 Logging

| Log Type | Location | Retention | Review |
|---|---|---|---|
| PHP errors | `/wp-content/debug.log` | 30 days | Weekly |
| Security | Wordfence dashboard | 90 days | Weekly |
| Form entries | Gravity Forms DB | 12 months (auto-delete) | As needed |
| WooCommerce orders | WooCommerce DB | 7 years (Dutch law) | Monthly export |
| Email delivery | Post SMTP log | 90 days | Weekly |
| Backups | Backup plugin log | 12 months | Monthly |
| Uptime | UptimeRobot | 12 months | Real-time alerts |

### 8.7 Caching

4-layer cache hierarchy:
1. **Browser:** `Cache-Control: max-age=31536000` for versioned static assets
2. **Cloudflare CDN:** Full-page HTML. Bypass for `/winkelmand/*`, `/afrekenen/*`, `/mijn-account/*`, `/wp-admin/*`, `/wp-json/wc/*`, `/?wc-ajax=*`
3. **FlyingPress:** Page cache. Cleared on post/page update, plugin/theme update.
4. **Redis:** Object cache for WP_Query, transients, options

---

## 9. SEO Implementation Checklist

| # | Task | Implementation | Validation |
|---|---|---|---|
| SEO01 | Unique `<title>` per page (50-60 chars) | Rank Math Pro per-page meta | Screaming Frog: zero duplicate/empty |
| SEO02 | Unique `<meta description>` per page (150-160 chars) | Rank Math Pro per-page meta | Screaming Frog: zero duplicate/empty |
| SEO03 | Self-referencing canonical URLs | Rank Math Pro auto | Screaming Frog: zero mismatches |
| SEO04 | Open Graph tags on all pages | Rank Math Pro auto | Facebook Sharing Debugger |
| SEO05 | Twitter Card tags on all pages | Rank Math Pro auto | Twitter Card Validator |
| SEO06 | XML Sitemap returns HTTP 200 | Rank Math Pro → Sitemap Settings | GSC: sitemap status "Success" |
| SEO07 | Zero attachment pages in sitemap | Rank Math Pro setting + `inc/security.php` redirect | Manual XML inspection |
| SEO08 | Image sitemap enabled | Rank Math Pro → Sitemap → Images | GSC image sitemap report |
| SEO09 | robots.txt returns HTTP 200 | Rank Math Pro auto | GSC robots.txt tester |
| SEO10 | All 301 redirects working (7 rules) | Rank Math Pro redirect manager | `httpstatus.io` — all return 301 |
| SEO11 | Zero redirect chains | Rank Math Pro redirect manager | `httpstatus.io` — no A→B→C chains |
| SEO12 | 410 Gone for removed content (2 rules) | Rank Math Pro redirect manager | Returns HTTP 410 |
| SEO13 | HTTPS + HSTS enforced | Cloudflare + `wp-config.php` | `securityheaders.com` |
| SEO14 | BreadcrumbList schema on all inner pages | Rank Math Pro + `parts/breadcrumbs.php` | Google Rich Results Test |
| SEO15 | LocalBusiness schema (Home, Contact, Over HDS) | `parts/schema-localbusiness.php` | Google Rich Results Test |
| SEO16 | Service schema on each service page (P02-P08) | `inc/schema.php` → `hds_get_service_schema()` | Google Rich Results Test |
| SEO17 | FAQPage schema (P18) | Yoast/Rank Math FAQ Block auto | Google Rich Results Test |
| SEO18 | Product schema (P25, x14) | WooCommerce auto | Google Rich Results Test |
| SEO19 | JobPosting schema (P14, per vacancy) | `inc/schema.php` → `hds_get_jobposting_schema()` | Google Rich Results Test |
| SEO20 | hreflang nl + x-default on homepage | Rank Math Pro or manual `<link>` | View source |
| SEO21 | Sitemap submitted to GSC + Bing | Manual post-launch | GSC sitemap report |
| SEO22 | Internal links: zero broken, zero orphan | Screaming Frog crawl | Zero errors |
| SEO23 | All images have `alt` text (Dutch) | Screaming Frog | Zero missing (except decorative `alt=""`) |
| SEO24 | `fb:pages` meta tag | Rank Math Pro → Social Meta | Facebook Sharing Debugger |
| SEO25 | Site search tracking (GA4 event) | GA4 Enhanced Measurement | GA4 real-time → `search` event |
| SEO26 | 404 monitor enabled | Rank Math Pro → 404 Monitor | Weekly review of 404 log |

---

## 10. Performance Checklist

| # | Task | Implementation | Target |
|---|---|---|---|
| PERF01 | PSI Mobile ≥ 90 | Lighthouse / PSI | All page templates |
| PERF02 | PSI Desktop ≥ 95 | Lighthouse / PSI | All page templates |
| PERF03 | LCP < 2.5 seconds | PSI, WebPageTest | All pages |
| PERF04 | CLS < 0.1 | PSI, Lighthouse | All pages |
| PERF05 | TTFB < 600ms | WebPageTest (Amsterdam, Moto G4, 3G Fast) | All pages |
| PERF06 | Total page weight < 1.5 MB (mobile) | WebPageTest | All pages |
| PERF07 | WebP images with `<picture>` fallback | ShortPixel/Imagify auto-convert | DevTools Network: `.webp` requests |
| PERF08 | Critical CSS inlined in `<head>` | FlyingPress auto-generate | View source: `<style>` in `<head>` |
| PERF09 | JavaScript `defer`, no render-blocking | `wp_enqueue_script()` with `defer` | PSI: zero render-blocking JS |
| PERF10 | No jQuery Migrate | Remove dependency | DevTools: jQuery Migrate not loaded |
| PERF11 | Self-hosted fonts, `font-display: swap` | WOFF2 in `assets/fonts/`, preloaded | DevTools: zero Google Fonts requests |
| PERF12 | `loading="lazy"` on below-fold images | WordPress 5.5+ default | PSI: zero offscreen image warnings |
| PERF13 | `fetchpriority="high"` on LCP image | Explicit attribute on hero/featured images | PSI: LCP identified correctly |
| PERF14 | Explicit `width`/`height` on all images | WordPress default image output | PSI: CLS < 0.1 |
| PERF15 | Cloudflare CDN active | Cloudflare DNS + caching | `CF-Cache-Status` header present |
| PERF16 | Cloudflare bypass for WooCommerce pages | Page Rules | `CF-Cache-Status: BYPASS` on WC pages |
| PERF17 | FlyingPress page cache active | Plugin configured | Response headers: `x-flying-press-cache: HIT` |
| PERF18 | Redis object cache active | Server-level Redis + WP plugin | Site Health: object cache active |
| PERF19 | Database clean (no revisions >30d, no spam) | WP-Optimize scheduled cleanup | Query Monitor: zero slow queries |
| PERF20 | Weekly PSI monitoring | PSI API check | Alert if mobile < 90 |

---

## 11. Security Checklist

| # | Task | Implementation | Validation |
|---|---|---|---|
| SEC01 | HTTPS enforced + HSTS | Cloudflare + `FORCE_SSL_ADMIN` | SSL Labs A+ |
| SEC02 | XML-RPC disabled (403) | Server-level deny + filter `xmlrpc_enabled` | `curl -I /xmlrpc.php` = 403 |
| SEC03 | Custom login URL | Wordfence | Not `/wp-admin/` or `/wp-login.php` |
| SEC04 | 2FA on ALL admin accounts | Wordfence 2FA (TOTP) | Login: 2FA prompt |
| SEC05 | Brute force protection | Wordfence: 3 failures → IP lockout | Test: 3 failed logins → locked out |
| SEC06 | `DISALLOW_FILE_EDIT = true` | `wp-config.php` | Appearance → Editor: absent |
| SEC07 | Database prefix changed to `hds_` | `wp-config.php` | PhpMyAdmin: `hds_` tables |
| SEC08 | WordPress salts fresh + unique | `wp-config.php` | Verify via wp-config audit |
| SEC09 | `/wp-json/wp/v2/users` blocked | `inc/security.php` filter | `curl` endpoint returns 403/empty |
| SEC10 | `/?author=N` enumeration blocked | `inc/security.php` redirect | `/?author=1` → 301 to home |
| SEC11 | Attachment pages disabled | `inc/security.php` redirect | `/attachment/` → 301 to parent |
| SEC12 | Wordfence WAF + daily malware scan | Wordfence Premium | Dashboard: scan clean |
| SEC13 | Cloudflare WAF rules active | Cloudflare dashboard | Rules: block xmlrpc, rate-limit login |
| SEC14 | `wp-config.php` permissions 400 | Server file permissions | `ls -l wp-config.php` = 400 |
| SEC15 | SFTP only (no FTP) | Hosting configuration | Port 22 open, port 21 closed |
| SEC16 | Daily backups + monthly test restore | BlogVault/UpdraftPlus | Test restore to staging: all pages work |
| SEC17 | Admin usernames not "admin"/"hds"/"helderduidelijkschoon" | User audit | `wp user list` — verify |
| SEC18 | No nulled/cracked plugins | Plugin audit | All from official WP.org or premium vendor |
| SEC19 | `wp-config.php` above web root or locked | Server configuration | Cannot access via browser |
| SEC20 | CSP headers (optional — P3 enhancement) | Cloudflare or WP plugin | Post-launch: `Content-Security-Policy` |

---

## 12. Accessibility Checklist

| # | Task | WCAG SC | Validation |
|---|---|---|---|
| A11Y01 | Color contrast ≥ 4.5:1 text, ≥ 3:1 large, ≥ 3:1 UI | 1.4.3, 1.4.11 | WebAIM / axe DevTools |
| A11Y02 | Full keyboard navigation — all elements reachable + operable | 2.1.1, 2.1.2 | Manual Tab-through |
| A11Y03 | Skip-to-content link — first focusable element | 2.4.1 | Tab on page load |
| A11Y04 | Semantic heading hierarchy — H1→H2→H3, no skips | 1.3.1 | WAVE / axe DevTools |
| A11Y05 | ARIA landmarks: banner, nav, main, contentinfo | 1.3.1 | axe DevTools |
| A11Y06 | `alt` text on all images | 1.1.1 | Screaming Frog |
| A11Y07 | Form labels + required markers + error association | 1.3.1, 3.3.2 | axe DevTools |
| A11Y08 | Descriptive link text (no "klik hier") | 2.4.4 | Manual audit |
| A11Y09 | 200% zoom usable, no horizontal scroll | 1.4.4 | Browser zoom test |
| A11Y10 | No auto-play, no flash >3/sec | 2.3.1, 2.3.2 | Manual inspection |
| A11Y11 | Touch targets ≥ 44×44px | 2.5.8 (AAA adopted AA) | Manual measurement |
| A11Y12 | `lang="nl-NL"` on `<html>` | 3.1.1 | View source |
| A11Y13 | Unique page titles | 2.4.2 | Screaming Frog |
| A11Y14 | `aria-live` for dynamic content | 4.1.3 | Screen reader test |
| A11Y15 | Consistent navigation order | 3.2.3 | Manual audit |
| A11Y16 | Consistent component identification | 3.2.4 | Manual audit |
| A11Y17 | Lighthouse Accessibility = 100 | — | Lighthouse |
| A11Y18 | axe DevTools: zero critical + serious | — | axe DevTools |
| A11Y19 | WAVE: zero errors | — | WAVE |
| A11Y20 | Screen reader: NVDA / VoiceOver — full page test | — | Manual: Home, Service, Contact |
| A11Y21 | Keyboard: dropdown menu opens on Enter/Space | 2.1.1 | Manual keyboard test |
| A11Y22 | `prefers-reduced-motion` respected | 2.3.1 | Enable OS setting + re-test |
| A11Y23 | WooCommerce checkout accessibility | — | Screen reader + keyboard + axe |

---

## 13. Analytics Implementation

### 13.1 GA4 Configuration

| Setting | Value |
|---|---|
| Property | "HDS Onderhoudsdiensten" |
| Data Stream | helderduidelijkschoon.nl |
| Enhanced Measurement | All enabled: page views, scrolls, outbound clicks, site search, video, file downloads |
| Data Retention | 14 months |
| IP Anonymization | Enabled (GA4 default) |
| Bot Filtering | Enabled |
| Internal Traffic Filter | Client office IP (MI) |

### 13.2 Google Tag Manager

- Container snippet in `<head>` (via Rank Math Pro or manual)
- Consent Mode v2: Complianz → GTM consent signals
- No hardcoded scripts in theme — all via GTM
- Data Layer pushes for conversion events

### 13.3 Conversion Events

| Event | Trigger | GA4 Event Name |
|---|---|---|
| Phone click | `tel:0164-652846` link clicked | `phone_click` |
| Email click | `mailto:info@...` link clicked | `email_click` |
| Contact form | Redirect to `/bedankt/?type=contact` | `form_submission` |
| Quote request | Redirect to `/bedankt/?type=offerte` | `quote_request` |
| Add to cart | WooCommerce product added | `add_to_cart` |
| Purchase | WooCommerce order completed | `purchase` |
| Cookie consent | Banner "Accepteren" clicked | `cookie_consent_accepted` |
| Scroll depth | 25/50/75/100% scroll | `scroll_depth` |
| Site search | `?s=query` | `search` |
| File download | PDF link clicked | `file_download` |

### 13.4 Google Search Console

- Property verified (DNS or HTML file method)
- XML Sitemap submitted at launch
- Daily crawl error monitoring for 30 days post-launch
- Weekly: impressions, clicks, CTR, average position vs pre-migration baseline

---

## 14. Testing Preparation

### 14.1 Test Categories

| Category | Scope | Tools | Threshold |
|---|---|---|---|
| **Functional** | All 32 pages, 3 forms, WC flow, search, nav, links | Manual + Screaming Frog | Zero critical bugs, zero broken links |
| **Cross-Browser** | Chrome, Firefox, Safari, Edge (latest 2) | Manual + BrowserStack | Consistent rendering, all functions |
| **Mobile/Tablet** | iPhone 14+, Android Chrome, iPad | Real devices | Responsive, 44px touch, forms usable |
| **Accessibility** | All page templates | axe, WAVE, Lighthouse, NVDA, keyboard | axe: zero critical/serious, Lighthouse: 100 |
| **Performance** | All page templates | PSI, WebPageTest, GTmetrix | PSI 90+ mobile, 95+ desktop |
| **SEO** | All 32 pages | Screaming Frog, Rich Results Test, GSC | Zero empty/duplicate meta, schema valid |
| **Security** | Server + application | Wordfence scan, manual checklist | Zero critical/high vulnerabilities |
| **GDPR** | Cookie consent, forms, legal pages | Manual + Complianz log | No cookies before consent, checkboxes unchecked |

### 14.2 Test Case Reference

Test cases are defined in RTM-001. 210 test cases across 11 categories. Test case IDs follow the pattern `T-[DOMAIN]-[NNN]` (e.g., `T-CONTACT-01`).

**Critical path smoke tests (manual):**
1. Homepage loads → HTTP 200, H1 visible, service cards render
2. Contact form submit → email delivered to info@ within 2 minutes
3. Offerte form submit with file attachment → email delivered
4. Navigation: all links work, no 404s
5. Mobile menu: opens/closes, all links accessible
6. Search: returns relevant results for "glasbewassing"
7. WooCommerce (if kept): browse → add to cart → checkout → order confirmation
8. 404 page: returns correct status, search bar functional
9. Cookie consent: banner appears, no GA4 cookies before consent

**Future: Playwright automated smoke tests** (P3 — deferred. Budget 1 day if Sprint 7 permits).

---

## 15. Coding Standards

### 15.1 PHP

| Rule | Enforcement |
|---|---|
| WordPress Coding Standards | PHP_CodeSniffer (`phpcs.xml`) with WordPress-Core, WordPress-Docs, WordPress-Security |
| PHP compatibility | 8.2+ |
| Short array syntax | Allowed `[]` |
| Yoda conditions | Required for equality checks |
| Strict comparisons | Required `===` and `!==` |
| Output escaping | `esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses()` |
| Input sanitization | `sanitize_text_field()`, `sanitize_email()`, etc. |
| Nonces | All custom forms: `wp_nonce_field()` + verification |
| Prepared SQL | `$wpdb->prepare()` for all user-input queries |
| Internationalization | `__()` / `_e()` with textdomain `'hds'` |
| No dangerous functions | No `eval()`, `base64_decode()`, `extract()`, `create_function()` |

### 15.2 JavaScript

| Rule | Enforcement |
|---|---|
| ES6+ syntax | ESLint (`.eslintrc.js`) |
| No jQuery (theme code) | Code review |
| No inline scripts | Code review — use `wp_add_inline_script()` |
| Progressive enhancement | Core functionality works without JS |
| No `console.log()` in production | ESLint rule |

### 15.3 CSS

| Rule | Enforcement |
|---|---|
| BEM-like naming | Stylelint (`.stylelintrc.json`) |
| CSS custom properties for tokens | Code review |
| No hardcoded hex values outside theme.json | Code review |
| Mobile-first (`min-width`) | Code review |
| Max 3 levels of nesting | Stylelint rule |
| No ID selectors | Stylelint rule |


### 15.4 Naming Conventions

| Category | Convention | Example |
|---|---|---|
| PHP functions | `hds_` prefix + snake_case | `hds_get_phone()` |
| CSS classes | `.hds-[block]__[element]--[modifier]` | `.hds-card__title`, `.hds-card--featured` |
| Custom fields | `hds_` prefix + snake_case | `hds_subtitle` |
| CPT keys | `hds_` prefix + snake_case | `hds_testimonial` |
| Block names | `hds/[block-name]` (kebab-case) | `hds/service-card` |
| Pattern names | `hds/[pattern-name]` (kebab-case) | `hds/hero-section` |
| Image filenames | lowercase-hyphens-dutch-keywords | `glasbewassing-kantoor-bergen-op-zoom.webp` |

---

## 16. Git Workflow

### 16.1 Branching Strategy

```
main          ← Production code. Protected. Deploy to production.
  └── staging  ← Pre-production. Deploy to staging for QA.
       └── feature/*  ← Feature branches. Merge to staging via PR.
       └── fix/*      ← Bug fix branches. Merge to staging via PR.
       └── hotfix/*   ← Critical production fixes. Branch from main.
```

### 16.2 Commit Conventions

```
type(scope): description

Types: feat, fix, docs, style, refactor, perf, test, chore, ci
Scope: theme, blocks, patterns, cpts, forms, seo, wc, config, docs
```

**Examples:**
```
feat(theme): add page-service.php template
fix(forms): correct Dutch postcode regex
docs(readme): update developer setup instructions
perf(assets): convert hero image to WebP
```

### 16.3 Pull Request Process

1. Create feature branch from `staging`
2. Implement + commit with conventional commit messages
3. Push branch → open PR to `staging`
4. PR must pass: PHPCS lint, ESLint lint, Stylelint lint
5. At least one review required before merge
6. Squash merge to `staging` (clean history)
7. Deploy to staging automatically via GitHub Actions
8. QA on staging
9. When approved: merge `staging` → `main` via PR
10. Deploy to production (with manual approval gate)

### 16.4 Release Tags

```
v1.0.0  — Initial launch
v1.0.1  — Hotfix
v1.1.0  — Minor feature release
```

Tags created from `main` after production deployment.

---

## 17. Deployment Preparation

### 17.1 Environment Configuration

| Setting | Local | Staging | Production |
|---|---|---|---|
| URL | `hds.local` | `staging.helderduidelijkschoon.nl` | `helderduidelijkschoon.nl` |
| PHP | 8.2+ | 8.2+ (prod mirror) | 8.2+ |
| WP_DEBUG | `true` | `true` | `false` |
| WP_DEBUG_LOG | `true` | `true` | `true` |
| WP_DEBUG_DISPLAY | `false` | `false` | `false` |
| Indexing | N/A | `noindex, nofollow` | `index, follow` |
| Access | Developer only | Developer + Client (password) | Public + Admin |
| Object Cache | Redis (Docker) | Redis | Redis |
| Page Cache | Disabled | FlyingPress (test) | FlyingPress (active) |
| CDN | None | Cloudflare (test) | Cloudflare (active) |
| Email | Mailpit (catch-all) | Post SMTP (test) | Post SMTP (live) |
| Payments | N/A | Mollie (test mode) | Mollie (live) |

### 17.2 Deployment Workflow

1. **Local Dev** → `git push` feature branch
2. **GitHub Actions** → lint (PHPCS + ESLint + Stylelint)
3. **Merge to staging** → auto-deploy to staging via SSH rsync
4. **Post-deploy:** `wp cache flush`, `wp rewrite flush`, FlyingPress clear, Cloudflare purge
5. **QA on staging** → client review + approval
6. **Merge to main** → manual approval gate → auto-deploy to production
7. **Post-deploy:** all caches cleared, smoke tests run, GA4 + GSC verified

### 17.3 Rollback Procedure

1. Identify pre-deploy backup (taken automatically before every production deploy)
2. Restore backup to staging → verify integrity
3. Deploy restored staging to production
4. Clear all caches
5. Verify site operational
6. **RTO:** < 30 min for plugin updates, < 2 hours for complete site failure

### 17.4 Backups

| Type | Frequency | Retention | Storage |
|---|---|---|---|
| Full (files + DB) | Daily (nightly) | 30 daily, 4 weekly, 12 monthly | Offsite cloud |
| Pre-update | Before every plugin/theme/core update | — | Offsite cloud |
| WC orders CSV | Monthly | 7 years (Dutch law) | Offsite cloud |
| Test restore | Monthly | — | Staging environment |

### 17.5 Monitoring

| What | Tool | Alert |
|---|---|---|
| Uptime | UptimeRobot (5-min checks) | Downtime > 1 min → Dev + Client |
| SSL expiry | UptimeRobot + Cloudflare | < 30 days → Dev |
| Backup failure | Backup plugin | Immediate → Dev |
| Malware | Wordfence (daily scan) | Detection → Dev |
| Disk usage | Hosting dashboard | > 80% → Dev |
| PSI score | Weekly PSI API check | Mobile < 90 → Dev |
| 404 spike | Rank Math Pro 404 Monitor | > 10/day on same URL → Dev |
| Email failure | Post SMTP log | > 5% in 24h → Dev |

---

## 18. Traceability Matrix

### 18.1 Sprint → Epic → Story → Task

| Sprint | Epic | Feature | Key Stories | Output |
|---|---|---|---|---|
| **Sprint 0** | E-PREREQ | F0.1-F0.3 | Architecture decisions, infrastructure, dev environment | Hosting, CDN, SMTP provisioned |
| **Sprint 1** | E-INFRA | F1.1-F1.3 | WordPress install, CDN/SSL/backups, theme foundation | `theme.json`, `functions.php`, base CSS |
| **Sprint 2** | E-CORE | F2.1-F2.3 | Page templates, service pages, conversion pages | All 7 service pages, Contact, Offerte, Bedankt, 404 |
| **Sprint 3** | E-SUPPORT | F3.1-F3.2 | About pages, trust pages, legal pages, downloads | Over HDS, Kwaliteit, Referenties, Vacatures, Legal |
| **Sprint 4** | E-COMM | F4.1 | WooCommerce configuration | Shop, products, cart, checkout, payments |
| **Sprint 5** | E-SEO | F5.1-F5.3 | SEO foundation, structured data, analytics | Meta descriptions, schema, GA4, GTM, redirects |
| **Sprint 6** | E-COMPLY | F6.1-F6.2 | GDPR, cookies, security hardening, accessibility | Complianz, Wordfence, a11y audit + fixes |
| **Sprint 7** | E-QA | F7.1 | Comprehensive QA | All 210 test cases executed |
| **Sprint 8** | E-LAUNCH | F8.1 | Launch + handover | Production deploy, verification, Beheergids, training |

### 18.2 Task → RTM Requirement (Key References)

Every implementation task must reference its RTM requirement ID. Full mapping in RTM-001 §5.

| Task Example | RTM REQ ID |
|---|---|
| Build contact form at /contact/ | REQ-FR-001, REQ-SEC-003, REQ-ACC-007 |
| Add Service schema to glasbewassing page | REQ-SEO-026 |
| Implement skip-to-content link | REQ-ACC-003 |
| Configure WP Rocket page caching | REQ-PERF-001, REQ-PERF-002 |
| Set up GA4 conversion tracking | REQ-ANL-004..009 |

### 18.3 Component → Design → RTM

See §6.1 for the full UI Component → Implementation → RTM mapping table.

---

## 19. Final Developer Checklist

The following must be completed before a developer begins Sprint 5 implementation:

- [ ] Read ADR-001 (all architectural decisions)
- [ ] Read FS-001 (functional behavior for all 32 pages)
- [ ] Read DS-001 (design tokens, component specs)
- [ ] Read HFUI-001 (exact visual values for every screen)
- [ ] Read RIS-001 (interactive behaviors, animations, responsive rules)
- [ ] Read this document §3 (WordPress implementation specifics)
- [ ] Local Docker environment running (`docker-compose up`)
- [ ] WordPress 6.7+ installed at `hds.local`
- [ ] All plugins installed (WooCommerce, Gravity Forms, Rank Math Pro, FlyingPress, Complianz, Wordfence, Post SMTP, Relevanssi, ShortPixel/Imagify, WP-Optimize)
- [ ] Theme activated. Verify `hds` theme shows in Appearance → Themes.
- [ ] `theme.json` validated (no schema errors)
- [ ] `main.css` compiling without errors
- [ ] Linting configured: PHPCS, ESLint, Stylelint passing on existing code
- [ ] Git workflow understood (§16)
- [ ] Coding standards reviewed (§15)
- [ ] Sprint 5 tasks understood from Product Backlog (`docs/planning/product-backlog.md`)

---

## 20. Go-Live Readiness Checklist

All items must be checked before production deployment (Sprint 8):

### 20.1 Content
- [ ] All 32 pages published with final Dutch content. No lorem ipsum.
- [ ] All service pages ≥ 300 words. Category landings ≥ 500 words.
- [ ] Phone + email correct on all pages.

### 20.2 Functionality
- [ ] All 3 forms submit and deliver emails within 2 minutes.
- [ ] WooCommerce purchase flow tested end-to-end (if Airfixr kept).
- [ ] Search returns relevant results. 404 page works.
- [ ] Cookie consent banner works (no cookies before consent).

### 20.3 SEO
- [ ] Every page: unique title + meta description. Zero empty/duplicate.
- [ ] All schema validated (Google Rich Results Test — 9 types).
- [ ] XML Sitemap returns HTTP 200. Zero attachment pages.
- [ ] All 7 × 301 redirects + 2 × 410 Gone configured + verified.
- [ ] robots.txt correct. GSC + Bing sitemap submitted.

### 20.4 Performance
- [ ] PSI mobile ≥ 90 on all page templates.
- [ ] PSI desktop ≥ 95 on all page templates.
- [ ] LCP < 2.5s, CLS < 0.1, TTFB < 600ms.
- [ ] Total page weight < 1.5 MB (mobile).
- [ ] All images WebP. Critical CSS inlined. JS deferred.

### 20.5 Accessibility
- [ ] Lighthouse Accessibility = 100 on all templates.
- [ ] axe DevTools: zero critical + serious issues.
- [ ] Full keyboard navigation tested.
- [ ] Screen reader tested (NVDA or VoiceOver).
- [ ] Color contrast: all combinations pass AA.

### 20.6 Security
- [ ] HTTPS + HSTS. XML-RPC disabled (403). Custom login URL.
- [ ] 2FA on all admin accounts. Brute force protection active.
- [ ] `DISALLOW_FILE_EDIT = true`. DB prefix `hds_`. Salts fresh.
- [ ] Wordfence: active, scan clean. Cloudflare WAF rules active.
- [ ] Daily backups configured + test restore verified.

### 20.7 Legal
- [ ] Privacyverklaring published + reviewed by lawyer.
- [ ] Cookiebeleid published. Consent logging active.
- [ ] All form consent checkboxes unchecked by default.
- [ ] KVK + BTW in footer (if provided by client).

### 20.8 Migration
- [ ] Old site backup taken + test restore verified.
- [ ] DNS TTL lowered to 300s (24h before launch). Verified propagation.
- [ ] Email MX records preserved. Test email delivered.
- [ ] All legacy domain PDFs migrated.
- [ ] Staging: search-replaced for production domain.

### 20.9 Launch Day
- [ ] Site deployed to production. All caches cleared.
- [ ] All 301 redirects verified on production.
- [ ] Contact form test submission on production → email delivered.
- [ ] GA4 real-time shows traffic. GSC sitemap submitted.
- [ ] Server error logs clean. SSL valid.
- [ ] Post-launch verification checklist (§J3 in MPS-001) completed.

---

## 21. Document Quick Reference

| If you need... | Read... |
|---|---|
| Architecture decisions (WHY) | `docs/architecture/ADR.md` (ADR-001) |
| What the system must do (WHAT) | `docs/specifications/functional-specification.md` (FS-001) |
| How the system is built (HOW — system) | `docs/architecture/SOLUTION_ARCHITECTURE.md` (SAD-001) |
| How WordPress implements it (HOW — code) | `docs/architecture/wordpress-technical-architecture.md` (WTA-001) |
| Quality targets (Performance, Security, A11Y) | `docs/specifications/non-functional-requirements.md` (NFR-001) |
| Design tokens + component specs | `docs/design/design-system-specification.md` (DS-001) |
| What goes where on each page | `docs/design/ux-wireframes-specification.md` (UXW-001) |
| Exact visual values for every screen | `docs/design/high-fidelity-ui-specification.md` (HFUI-001) |
| How every interaction behaves | `docs/design/responsive-interaction-specification.md` (RIS-001) |
| SEO implementation details | `docs/seo/seo-implementation-specification.md` (SEO-001) |
| Requirement traceability | `docs/REQUIREMENTS_TRACEABILITY_MATRIX.md` (RTM-001) |
| Sprint backlog + user stories | `docs/planning/product-backlog.md` (PB-001) |
| Consolidated specification | `docs/MASTER_PROJECT_SPECIFICATION.md` (MPS-001) |

---

## 22. Acceptance Criteria

### 22.1 Document Completeness

| # | Criterion | Pass Condition |
|---|---|---|
| AC-DHG01 | Every architecture decision referenced | All 16 ADR decisions mapped to implementation in §1.3 |
| AC-DHG02 | Every page template has an implementation specification | 14 template files mapped in §3.2 + §6.2 |
| AC-DHG03 | Every UI component has an implementation mapping | 20+ components mapped in §6.1 |
| AC-DHG04 | Every SEO task has an implementation instruction | 26 checklist items in §9 |
| AC-DHG05 | Every performance task has an implementation instruction | 20 checklist items in §10 |
| AC-DHG06 | Every security task has an implementation instruction | 19 checklist items in §11 |
| AC-DHG07 | Every accessibility task has an implementation instruction | 23 checklist items in §12 |
| AC-DHG08 | All coding standards documented | PHP, JS, CSS standards in §15 |
| AC-DHG09 | Git workflow documented | Branch strategy, commits, PRs, releases in §16 |
| AC-DHG10 | Deployment process documented | Environments, workflow, rollback in §17 |

### 22.2 Developer Onboarding

| # | Criterion | Pass Condition |
|---|---|---|
| AC-DHG11 | A new developer can set up their local environment from this document | §19 checklist + `docker-compose.yml` + `README.md` (if exists) |
| AC-DHG12 | A new developer can understand the template hierarchy | §3.2 table + §6.2 mapping |
| AC-DHG13 | A new developer can add a new page without asking questions | §3.2 (template hierarchy), §4 (ACF strategy replaced with post meta), §5 (Gutenberg strategy), §6.2 (page → template mapping) |
| AC-DHG14 | A new developer can find the relevant design specification for any component | §21 document quick reference table |
| AC-DHG15 | A new developer can trace any UI component back to its requirement | §6.1 component → RTM mapping + §18 traceability |

### 22.3 Implementation Readiness

| # | Criterion | Pass Condition |
|---|---|---|
| AC-DHG16 | All prerequisite documents exist and are referenced | 14 documents referenced in §21 |
| AC-DHG17 | No architectural ambiguity remains | All 8 ARR blocking issues resolved (ACR-001) |
| AC-DHG18 | No design ambiguity remains | DS-001, UXW-001, HFUI-001, RIS-001 all approved |
| AC-DHG19 | Go-live criteria defined and measurable | 33 checklist items in §20 |

---

*End of Developer Handoff & Implementation Guide — DHG-001 v1.0.0*

*This document is the single source of implementation truth. A developer joining the project today can read this document, set up their environment, understand the architecture, find the design specs, and begin implementing Sprint 5 tasks without asking architectural or design questions.*
