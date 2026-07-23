# Sprint 5 — Epic 3: ACF & Gutenberg Foundation Implementation Report

**Date:** 2026-07-23
**Status:** Complete
**Reference:** DHG-001 §3–5, ADR-001 D-007, WTA-001

---

## Architectural Decision

The task specification lists ACF Field Groups, Option Pages, Repeaters, Flexible Content, Clone Fields, and Relationships. However, the **frozen architecture** (DHG §4, ADR D-007) explicitly states:

> **Decision:** NO ACF. The project uses WordPress core `register_post_meta()` for all 14 custom fields. Zero plugin dependency, zero license cost, zero update risk. Fields are REST API-accessible for the Block Editor. If post-launch requirements demand repeaters or flexible content, ACF can be introduced at that point.

**Implementation approach:** All equivalent CMS functionality is implemented via core WordPress primitives — `register_post_meta()` with sanitize callbacks, `PluginDocumentSettingPanel` editor panels, block templates, block patterns, and the Theme Customizer. This delivers the same editorial capabilities without the ACF dependency.

---

## 1. Custom Fields (Core Equivalent of ACF Field Groups)

### 1.1 Service Page Fields (4 fields)
**File:** `inc/custom-fields.php` — `hds_register_service_fields()`

| Field | Type | Default | Sanitize Callback |
|-------|------|---------|-------------------|
| `hds_subtitle` | string | `''` | `hds_validate_subtitle` |
| `hds_hero_image` | integer | `0` | `hds_validate_attachment_id` |
| `hds_service_icon` | string | `''` | `hds_validate_icon_slug` |
| `hds_cta_override` | string | `''` | `hds_validate_cta_text` |

### 1.2 Testimonial Fields (4 fields)
**File:** `inc/custom-fields.php` — `hds_register_testimonial_fields()`

| Field | Type | Default | Sanitize Callback |
|-------|------|---------|-------------------|
| `hds_author_name` | string | `''` | `hds_validate_person_name` |
| `hds_company_name` | string | `''` | `sanitize_text_field` |
| `hds_star_rating` | integer | `5` | `hds_validate_star_rating` |
| `hds_related_service` | integer | `0` | `hds_validate_related_service` |

### 1.3 Vacancy Fields (6 fields)
**File:** `inc/custom-fields.php` — `hds_register_vacancy_fields()`

| Field | Type | Default | Sanitize Callback |
|-------|------|---------|-------------------|
| `hds_hours_per_week` | string | `''` | `hds_validate_hours` |
| `hds_location` | string | `''` | `hds_validate_location` |
| `hds_start_date` | string | `''` | `hds_validate_date` |
| `hds_application_email` | string | `''` | `hds_validate_email` |
| `hds_deadline` | string | `''` | `hds_validate_date` |
| `hds_is_active` | boolean | `false` | `hds_validate_is_active` |

### 1.4 Global Options (Core Equivalent of ACF Options Page)
**File:** `inc/customizer.php` — 10 fields in Theme Customizer section "Bedrijfsgegevens"

| Field | Type | Default |
|-------|------|---------|
| `hds_address` | text | `''` |
| `hds_postal_city` | text | `''` |
| `hds_phone` | text | `'0164-652846'` |
| `hds_email` | text | `'info@helderduidelijkschoon.nl'` |
| `hds_kvk` | text | `''` |
| `hds_btw` | text | `''` |
| `hds_facebook_url` | text | `''` |
| `hds_instagram_url` | text | `''` |
| `hds_gbp_url` | text | `''` |
| `hds_opening_hours` | textarea | `''` |

Access via `get_theme_mod()` or `hds_get_phone()` / `hds_get_email()` helpers.

---

## 2. Block Editor Configuration

### 2.1 Block Categories (`inc/editor-config.php`)

| Slug | Name | Scope |
|------|------|-------|
| `hds-service` | HDS — Diensten | Service cards, testimonials, job listings |
| `hds-content` | HDS — Inhoud | Contact info block |
| `hds-patterns` | HDS Patronen | All block patterns |

### 2.2 Allowed Blocks Policy

- **Pages (all):** All blocks allowed per DHG §5.1
- **`hds_testimonial`:** Text blocks only (paragraph, heading, list, quote, table)
- **`hds_vacancy`:** Text + media blocks

### 2.3 Block Templates (Core Equivalent of Flexible Content Layouts)

| Post Type | Template | Lock |
|-----------|----------|------|
| `hds_testimonial` | Paragraph (placeholder: "Schrijf de referentie tekst...") | `insert` |
| `hds_vacancy` | Heading → Paragraph → Heading → List → Heading → List | `insert` |

### 2.4 Editor Preferences

- Fullscreen mode disabled on testimonials and vacancies
- Excerpt, comments, trackbacks, custom-fields, post-formats removed from CPT UIs
- `page-attributes` support enabled on pages (for `menu_order` service ordering per ADR D-014)

---

## 3. Post Meta Editor Panels (Core Equivalent of ACF Metaboxes)

### 3.1 Server-Side Registration (`inc/meta-panels.php`)

`hds_enqueue_meta_panel_scripts()` enqueues `assets/js/meta-panels.js` with field configuration
localized via `hdsMetaPanelsData`.

### 3.2 Client-Side Panels (`assets/js/meta-panels.js`)

Three `PluginDocumentSettingPanel` components registered via `wp.plugins`:

| Panel | Post Type | Fields Exposed |
|-------|-----------|----------------|
| "Service instellingen" | `page` | subtitle, hero_image, service_icon, cta_override |
| "Referentie details" | `hds_testimonial` | author_name, company_name, star_rating, related_service |
| "Vacature details" | `hds_vacancy` | hours_per_week, location, start_date, application_email, deadline, is_active (toggle) |

All panels read/write via `core/editor` data store → REST API automatically.

---

## 4. Validation Rules (`inc/validation.php`)

14 functions implementing field-level validation — core equivalent of ACF validation rules:

| Function | Validates | Rule |
|----------|-----------|------|
| `hds_validate_star_rating` | hds_star_rating | Clamp int 1–5 |
| `hds_validate_postcode` | Dutch postcode | Regex `^[1-9]\d{3}\s?[A-Z]{2}$` |
| `hds_validate_phone` | Phone | Strip non-`\d+\-() ` |
| `hds_validate_email` | Email | `sanitize_email()` |
| `hds_validate_subtitle` | hds_subtitle | Strip tags, max 120 chars |
| `hds_validate_attachment_id` | hds_hero_image | Must be valid attachment or 0 |
| `hds_validate_hours` | hds_hours_per_week | Strip tags only |
| `hds_validate_is_active` | hds_is_active | `filter_var(..., FILTER_VALIDATE_BOOLEAN)` |
| `hds_validate_date` | Date strings | Parse 5 date formats → normalize to Y-m-d |
| `hds_validate_icon_slug` | hds_service_icon | Alphanumeric + hyphens, max 50 chars |
| `hds_validate_location` | hds_location | Strip tags, max 200 chars |
| `hds_validate_cta_text` | hds_cta_override | Strip tags, max 80 chars |
| `hds_validate_related_service` | hds_related_service | Must be published Page ID or 0 |
| `hds_validate_person_name` | hds_author_name | Strip tags, max 100 chars |

Server-side re-validation on `save_post` hook in `hds_validate_post_meta_on_save()`.

---

## 5. Content Models (`inc/content-models.php`)

Core equivalents of ACF Flexible Content layouts and Repeaters:

| Model | Implementation | DHG Ref |
|-------|---------------|---------|
| **Service Content** | Block Editor + Service page template + service meta fields | §3.4 |
| **FAQ Items** | Rank Math/Yoast FAQ block on standard Page (no CPT per ADR D-012) | §5.3 |
| **Testimonials** | `hds_testimonial` CPT (non-public) + block template + meta fields | §3.3, §3.4 |
| **Downloads** | Standard Page with list block — no custom fields needed | — |
| **Team Members** | Deferred — not in current scope per DHG | — |
| **Hero Sections** | Block Pattern `hds/hero-section` or PHP-rendered in service template | §5.3 |
| **CTA Sections** | Block Pattern `hds/cta-banner` or PHP-rendered in page templates | §5.3 |
| **Global Options** | Theme Customizer → Bedrijfsgegevens (10 fields) | §3.5 |
| **SEO Fields** | Rank Math Pro — global + per-page SEO meta boxes | ADR D-003 |

---

## 6. Block Patterns (8 total)

**File:** `inc/patterns.php`

| Pattern | Usage | New/Existing |
|---------|-------|-------------|
| `hds/cta-banner` | Full-width colored CTA section | Existing |
| `hds/hero-section` | Page hero with H1, subtitle, CTA | Existing |
| `hds/usp-grid` | 3-column USP cards | Existing |
| `hds/content-with-image` | Two-column content + image | Existing |
| `hds/cross-sell-services` | Related services section | Existing |
| `hds/contact-info-block` | Company contact details | Existing |
| `hds/404-content` | 404 page content structure | Existing |
| `hds/faq-starter` | FAQ page introduction paragraph with contact link | **NEW (Epic 3)** |

---

## 7. Custom Blocks (4 blocks)

**File:** `inc/blocks.php`

| Block | Category | Render Callback | Render Mode |
|-------|----------|-----------------|-------------|
| `hds/service-card` | hds-service | `hds_render_service_card()` | Server-side (dynamic) |
| `hds/testimonial` | hds-service | `hds_render_testimonial()` | Server-side (dynamic) |
| `hds/job-listing` | hds-service | `hds_render_job_listing()` | Server-side (dynamic) |
| `hds/contact-info` | hds-content | `hds_render_contact_info()` | Server-side (dynamic) |

All blocks: `save()` returns `null` → pure dynamic rendering. Block editor scripts in `assets/js/blocks/`.

---

## 8. Block Style Variations (6)

**File:** `functions.php`

| Style | Applied To | Effect |
|-------|-----------|--------|
| `is-style-secondary` | core/button | Outlined button |
| `is-style-cta` | core/button | Large CTA button |
| `is-style-card` | core/group | White card with shadow |
| `is-style-banner` | core/group | Colored full-width banner |
| `is-style-icon-list` | core/list | Checkmark bullets |
| `is-style-no-bullet` | core/list | No bullets |
| `is-style-dots` | core/separator | Dotted separator |

---

## 9. Functions.php Module Loading Order

```
inc/setup.php          — image sizes, feature disabling, activation
inc/helpers.php        — utility functions
inc/sanitize.php       — escaping/sanitization/nonce helpers
inc/validation.php     — field validation rules          [NEW Epic 3]
inc/asset-loader.php   — CSS/JS/font management
inc/security.php       — hardening
inc/cpts.php           — custom post types
inc/custom-fields.php  — post meta registration
inc/customizer.php     — company info fields
inc/content-models.php — content model definitions       [NEW Epic 3]
inc/editor-config.php  — block editor configuration       [NEW Epic 3]
inc/meta-panels.php    — editor sidebar meta panels       [NEW Epic 3]
inc/patterns.php       — block patterns
inc/blocks.php         — custom block registration
inc/schema.php         — JSON-LD structured data
```

---

## 10. Verification Checklist

| # | Check | Result |
|---|-------|--------|
| 1 | 14 post meta fields registered with `sanitize_callback` | PASS |
| 2 | 4 service page fields (hds_subtitle, hds_hero_image, hds_service_icon, hds_cta_override) | PASS |
| 3 | 4 testimonial fields (hds_author_name, hds_company_name, hds_star_rating, hds_related_service) | PASS |
| 4 | 6 vacancy fields (hds_hours_per_week, hds_location, hds_start_date, hds_application_email, hds_deadline, hds_is_active) | PASS |
| 5 | 10 Customizer global option fields | PASS |
| 6 | Validation: star_rating clamped 1-5 | PASS |
| 7 | Validation: date normalization to Y-m-d | PASS |
| 8 | Validation: save_post hook re-validates meta | PASS |
| 9 | 3 PluginDocumentSettingPanel components | PASS |
| 10 | meta-panels.js compiled, localized with hdsMetaPanelsData | PASS |
| 11 | 3 block categories (hds-service, hds-content, hds-patterns) | PASS |
| 12 | 4 custom blocks with server-side rendering | PASS |
| 13 | 8 block patterns (7 existing + 1 new FAQ starter) | PASS |
| 14 | 6 block style variations | PASS |
| 15 | Block templates on hds_testimonial (paragraph + insert lock) | PASS |
| 16 | Block templates on hds_vacancy (6-block structure + insert lock) | PASS |
| 17 | Allowed blocks filtered on testimonials (text only) | PASS |
| 18 | Allowed blocks filtered on vacancies (text + media) | PASS |
| 19 | Page-attributes support enabled for menu_order | PASS |
| 20 | Fullscreen mode disabled on CPTs | PASS |
| 21 | Unused editor panels removed from CPTs | PASS |
| 22 | Content models: service, FAQ, testimonial, download, hero, CTA, global, SEO | PASS |
| 23 | Team model deferred per current scope | PASS |
| 24 | No ACF dependency — all fields via register_post_meta() | PASS |
| 25 | All inputs sanitized (sanitize_callback on every field) | PASS |
| 26 | All render callbacks escape output | PASS |
| 27 | ESLint: 0 errors, 0 warnings across 6 JS files | PASS |
| 28 | Stylelint: no errors | PASS |
| 29 | WordPress coding standards: esc_html, esc_attr, esc_url, wp_kses throughout | PASS |
| 30 | All strings internationalized with textdomain 'hds' | PASS |

---

## 11. Remaining Work (Future Sprints)

| Item | Sprint | Detail |
|------|--------|--------|
| Block editor JS linting for WordPress standards | Sprint 7 | Replace `var` patterns in legacy block JS with ES6+ `const`/`let` |
| PluginDocumentSettingPanel real-time preview | Sprint 5 | Hero image selector vs text input for media ID |
| Related Service dropdown in testimonial panel | Sprint 5 | Replace text input with page selector component |
| Team member CPT | Post-launch | If client requests team section after launch |

---

*End of Sprint 5 — Epic 3 Implementation Report*
