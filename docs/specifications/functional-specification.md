# HDS Onderhoudsdiensten — Functional Specification

**Document ID:** FS-001 | **Version:** 1.0.0 | **Status:** Ready for Development
**Project:** helderduidelijkschoon.nl — Ground-Up Rebuild
**Language:** Nederlands (nl-NL) | **Date:** July 2026
**Referenced Documents:** MPS-001, SAD-001, ADR-001, BKLG-001, ARR-001, RTM-001, SRC-01 through SRC-08, RS-01 through RS-08, Epic 1 Implementation, Epic 2 Implementation

---

## 1. Purpose

This Functional Specification defines the complete functional behavior of the new HDS Onderhoudsdiensten platform at `helderduidelijkschoon.nl`. It describes what the system must do, how it must behave, and how actors interact with it.

This document is the definitive source for functional requirements. It is binding for Sprint 2–8 development and for Sprint 7–8 QA verification. Every feature defined here is traceable to the Requirements Traceability Matrix (RTM-001).

**Key Principle:** This specification describes behavior, not implementation. Implementation details are defined in the Solution Architecture Document (SAD-001) and Architecture Decision Record (ADR-001).

---

## 2. Scope

### 2.1 In Scope

| Category | Count | Details |
|---|---|---|
| **Pages** | 32 | Home, 7 service pages, 2 category landings, 5 about/trust pages, 2 conversion pages, 1 thank-you page, 4 legal pages, 1 product landing, 5 WooCommerce pages, 1 blog index, 1 404 page |
| **Forms** | 3 | Contact form (GF-1), Quote request form (GF-2), Vacancy application form (GF-3) |
| **Custom Post Types** | 3 | `hds_testimonial`, `hds_vacancy`, `hds_faq` |
| **Block Patterns** | 16 | Hero, Service Card Grid, USP Grid, CTA Banner, Content+Image, Service Icon List, Client Logo Carousel, Testimonial Block, FAQ Accordion, Cross-Sell Services, Job Vacancy Card, Download Card List, Contact Info+Map, Latest Blog Posts, Related Posts, 404 Content |
| **Custom Blocks** | 4 | `hds/service-card`, `hds/testimonial`, `hds/job-listing`, `hds/contact-info` |
| **Navigation Menus** | 5 | Primary, Footer-Services, Footer-About, Footer-Airfixr, Footer-Legal |
| **Integrations** | 7 | GA4, GTM, GSC, Mollie, Complianz, Wordfence, Post SMTP |
| **Schema Types** | 9 | WebSite, WebPage, BreadcrumbList, LocalBusiness, Service (x7), FAQPage, Product (x14), JobPosting, Organization |

### 2.2 Out of Scope (Current Release)

- Online booking/appointment system (future enhancement)
- Client self-service portal (future enhancement)
- Automated quoting engine (future enhancement)
- Multilingual support (future enhancement — all strings are internationalized for forward-compatibility)
- Newsletter integration (future enhancement)
- WhatsApp Business button (future enhancement)
- Live chat widget (future enhancement)
- Mobile app for cleaning staff (future enhancement)

### 2.3 Conditional Scope (Client-Decision Dependent)

| Item | Depends On | Default Assumption |
|---|---|---|
| WooCommerce shop (14 Airfixr products) | Client confirms Airfixr product line should remain (MI-15, Q09) | **Assumption:** Airfixr shop is kept |
| Payment gateway (Mollie) | Client selects payment gateway (MI-15) | **Assumption:** Mollie (recommended for Dutch market) |
| Shipping costs | Client provides shipping costs (MI-14) | **Assumption:** Flat rate per shipping class; client defines rates |
| Referenties page content | Client provides logos (MI-10) and testimonials (MI-11) | **Assumption:** Empty state displayed until provided |
| Vacature content as HTML text | Client provides vacancy text (MI-12) | **Assumption:** Placeholder vacancies until provided |
| Algemene Voorwaarden page content | Client provides terms text (MI-16) | **Assumption:** Placeholder page if not provided; must be provided before launch |
| Privacyverklaring legal review | Client engages lawyer (MI-17) | **Assumption:** Developer drafts content; lawyer reviews before launch |

---

## 3. Actors

### 3.1 Visitor (Anonymous, Not Authenticated)

**Description:** Any person who visits the website without logging in. This is the primary actor — every system behavior is designed for Visitors first.

**Capabilities:**
- View all public pages (Home, services, about, contact, legal, blog, shop)
- Submit the Contact form (GF-1)
- Submit the Quote Request form (GF-2)
- Browse the WooCommerce shop, add products to cart
- Complete a WooCommerce purchase (guest checkout enabled)
- Submit a vacancy application (GF-3)
- Search the site
- Accept, reject, or customize cookie consent preferences

**Constraints:**
- Cannot access `/wp-admin/` or any admin functionality
- Cannot view draft/unpublished content
- Cannot view form entry data of other users
- Cannot access the staging environment (password-protected)

### 3.2 Customer (Authenticated, if WooCommerce Account Created)

**Description:** A Visitor who creates a WooCommerce account during or after checkout. This actor only exists if the Airfixr shop is active.

**Capabilities (beyond Visitor):**
- View order history in Mijn Account (`/mijn-account/`)
- Save billing/shipping addresses for faster checkout
- View downloadable products (if any)
- Request account data export (GDPR right of access)
- Request account deletion (GDPR right to erasure)

**Constraints:**
- Same as Visitor for all non-WooCommerce functionality
- Cannot access admin functionality

### 3.3 Company Staff (HDS Employees)

**Description:** HDS Onderhoudsdiensten staff who receive and respond to form submissions and orders.

**Capabilities:**
- Receive email notifications for Contact form submissions to `info@helderduidelijkschoon.nl`
- Receive email notifications for Quote Request submissions
- Receive email notifications for new WooCommerce orders
- Receive vacancy applications

**Constraints:**
- No WordPress admin access (unless granted a specific role)
- Form entries are accessed via email, not via WordPress admin (unless Editor role)

### 3.4 Administrator

**Description:** Full-access WordPress Administrator. Minimum 2 accounts. Both must have 2FA enabled.

**Capabilities:**
- Full WordPress admin access
- Manage all content (pages, posts, CPTs, media)
- View, export, and delete form entries (Gravity Forms)
- Manage WooCommerce products, orders, coupons, and settings
- Manage SEO settings (Rank Math Pro)
- Manage user accounts, roles, and permissions
- Install, update, and configure plugins
- Manage navigation menus
- Manage Customizer settings (company information)
- Access staging environment

**Constraints:**
- 2FA enforced (Wordfence) — no exceptions
- Unique, non-obvious usernames (never "admin", "hds", or "helderduidelijkschoon")
- Minimum 12-character passwords (Wordfence enforced)
- Login via custom URL (not `/wp-admin/` or `/wp-login.php`)

### 3.5 Editor

**Description:** Content editor with permission to manage all content but not plugins or settings.

**Capabilities:**
- Create, edit, publish, and delete pages, posts, and CPTs
- Upload and manage media
- View Gravity Forms entries
- View Rank Math Pro SEO data
- Moderate WooCommerce reviews
- Manage navigation menus

**Constraints:**
- Cannot install/update/delete plugins or themes
- Cannot modify WordPress settings
- Cannot manage user accounts
- Cannot modify Customizer settings (company information)
- Cannot access WooCommerce order data (unless specifically assigned)

### 3.6 SEO Manager

**Description:** Role focused on SEO settings and analytics visibility.

**Capabilities:**
- Access Rank Math Pro settings (meta titles, descriptions, sitemaps, redirects)
- View Google Site Kit analytics data (if installed)
- View Gravity Forms entries (for conversion tracking)

**Constraints:**
- Cannot edit page content (unless also assigned Editor)
- Cannot manage plugins, themes, or WordPress settings
- Cannot manage users

---

## 4. Functional Modules

### 4.1 Homepage

**Purpose:** Primary landing page. Communicates HDS's services, USPs, and trust signals. Drives visitors toward requesting a quote.

**URL:** `/`
**Template:** `front-page.php`
**Minimum word count:** 300+ Dutch words
**H1:** "Helder en Duidelijk voor het Schoonste resultaat!"
**Title tag:** `HDS Onderhoudsdiensten | Schoonmaak- en Onderhoudsdiensten West-Brabant Zeeland`

**Content Blocks (top to bottom):**

| # | Block | Behavior | Conditional? |
|---|---|---|---|
| 1 | Hero Section | H1 tagline, USP summary (1–2 sentences), CTA button "Vrijblijvende offerte" → `/offerte-aanvragen/` | No — always visible |
| 2 | Service Card Grid | 7 service cards. Each: icon, title, 1-sentence description, "Lees meer" link. Queries all pages with Service template, ordered by `menu_order`. | No — always visible |
| 3 | USP Grid | 4–6 USP cards: "Vast opgeleid personeel", "Veiligheid & Certificering", "Een aanspreekpunt", "Maatwerk planning", "Milieubewust (MVO)", "Regio specialist". Each: icon, heading, short text. | No — always visible |
| 4 | Client Logo Carousel | Carousel/grid of client logos. Queries uploaded logo images from a Customizer setting or media category. | **Yes — HIDE entire section if no logos.** |
| 5 | Testimonial Block | 3–5 testimonials with quote, author, company, star rating. Queries `hds_testimonial` CPT. | **Yes — HIDE entire section if no testimonials.** |
| 6 | CTA Banner | "Wilt u een vrijblijvende offerte? Wij denken graag met u mee." Button → `/offerte-aanvragen/` | No — always visible |
| 7 | Service Area | Text: "Wij bedienen bedrijven in heel West-Brabant en Zeeland." Optional map embed if MI-01 (address) is provided. | Text always visible; map embed conditional |
| 8 | Latest Blog Posts | 3 most recent posts. Thumbnail, title, date, excerpt. "Lees meer" link. | **Yes — HIDE entire section if no published posts.** |

**Empty State Handling:**
- Client Logo Carousel: `display: none` on the section wrapper when no logos exist. Section must not render empty space.
- Testimonial Block: `display: none` on the section wrapper when no testimonials exist.
- Latest Blog Posts: `display: none` on the section wrapper when no published posts exist.

**Error States:**
- WP_Query failure for service cards: Fall back to displaying nothing (graceful degradation — other sections remain visible). Log error to `debug.log`.

**Business Rules:**
- The CTA button on the Hero must always link to `/offerte-aanvragen/` (not `/contact/`).
- Service cards must render in `menu_order` sequence, not by date. The canonical order is: (1) Reguliere Schoonmaak, (2) Glasbewassing, (3) Gevelreiniging, (4) Vloeronderhoud, (5) VVE Service, (6) Oplevering Schoonmaak, (7) Industriele Schoonmaak.

**Acceptance Criteria:**
- AC-HP01: Page returns HTTP 200 at `/`
- AC-HP02: All 8 content blocks present and rendering correctly
- AC-HP03: Service Card Grid displays 7 cards with correct links
- AC-HP04: CTA buttons link to `/offerte-aanvragen/`
- AC-HP05: Empty sections hidden (not rendered) when no data
- AC-HP06: Responsive on mobile (375px), tablet (768px), desktop (1024px), wide (1440px)
- AC-HP07: Page content >= 300 words Dutch
- AC-HP08: H1 = tagline
- AC-HP09: Unique title tag and meta description set
- AC-HP10: No PHP errors in debug.log
- AC-HP11: No JavaScript errors in browser console

### 4.2 Service Pages

**Purpose:** Provide detailed information about a specific cleaning service. Enable visitors to evaluate the service and request a quote.

**Pages:** P02–P08 (Glasbewassing, Gevelreiniging, Reguliere Schoonmaak, Vloeronderhoud, VVE Service, Oplevering Schoonmaak, Industriele Schoonmaak)
**Template:** `page-templates/page-service.php`
**Minimum word count:** 300+ Dutch words per page

**Common Structure:**

| # | Section | Content |
|---|---|---|
| 1 | Breadcrumbs | Home > [Service Name] |
| 2 | Hero | H1 = service name. Subtitle = `hds_subtitle` custom field (if set). Background image = `hds_hero_image` (if set). CTA button using `hds_cta_override` text or default "Vrijblijvende offerte" → `/offerte-aanvragen/` |
| 3 | Content Area | `the_content()` — Block Editor. Expected structure: intro paragraph, H2 "Onze aanpak", H2 "Diensten" (bullet list), H2 "Veiligheid & Kwaliteit", additional blocks. |
| 4 | Cross-Sell Services | "Gerelateerde diensten" heading. 2–3 related service cards. Editor selects which services per page. |
| 5 | CTA Banner | "Vrijblijvende offerte aanvragen" → `/offerte-aanvragen/` |
| 6 | Optional FAQ | Service-specific FAQ accordion. Editor can add or omit. |

**Per-Page Content Requirements:**

| Page | URL | Key Content Elements |
|---|---|---|
| P02 Glasbewassing | `/glasbewassing/` | Sections: Veiligheid (safety passports, diplomas), Samenwerking (measurement, analysis, check-in/out), Technieken (traditional + advanced). Cross-links: Gevelreiniging (P03), Reguliere Schoonmaak (P04), Oplevering Schoonmaak (P07). |
| P03 Gevelreiniging | `/gevelreiniging/` | **Standardized naming:** H1 = "Gevelreiniging" (NOT "Gevelonderhoud"). Bullet list: impregneren, graffiti verwijderen, daken/goten reinigen, gevel(beplating), rolluiken, zonnepanelen, reclameborden. Cross-links: Glasbewassing (P02), Industriele Schoonmaak (P08). |
| P04 Reguliere Schoonmaak | `/reguliere-schoonmaak/` | **NEW (was HTTP 404).** Target: office managers. Content: daily/weekly scheduling, trained uniformed staff, tailored work planning, check-in/out protocol, single point of contact. Cross-links: Vloeronderhoud (P05), Glasbewassing (P02), VVE Service (P06). |
| P05 Vloeronderhoud | `/vloeronderhoud/` | 7-item service list: marmoleum reinigen/wassen, marmoleum strippen/nieuw wassysteem, natuursteen reinigen/coaten, natuursteen schuren/zoeten, vloerbedekking shampoorallen, houten vloeren boenen/wassen, grote oppervlakken machinaal reinigen. Holiday scheduling mention. Cross-links: Reguliere Schoonmaak (P04), Oplevering Schoonmaak (P07). |
| P06 VVE Service | `/vve-service/` | Services: stairwells, halls, escape balconies, garages. Additional: minor technical maintenance, outdoor cleaning, weed removal. VvE Belang listing mention. Cross-links: Reguliere Schoonmaak (P04), Glasbewassing (P02). |
| P07 Oplevering Schoonmaak | `/oplevering-schoonmaak/` | "0-beurt" concept explained. 5-task bullet list: volledige reiniging incl. glasbewassing, cementresten verwijderen, verfresten verwijderen, stofvrij maken, grofvuil verwijderen/afvoeren. Cross-links: Reguliere Schoonmaak (P04), Glasbewassing (P02), Vloeronderhoud (P05). |
| P08 Industriele Schoonmaak | `/industriele-schoonmaak/` | **Rebuild from scratch (was 60 words).** Bullet list: leidingen reinigen, productievloeren, magazijnstellingen, machines, vet/olie verwijdering. Safety protocols for industrial environments. Minimal production downtime mention. Cross-links: Reguliere Schoonmaak (P04), Gevelreiniging (P03). |

**Cross-Link Rules (Mandatory):**

| Page | Must Link To |
|---|---|
| P02 Glasbewassing | P03, P04, P07 |
| P03 Gevelreiniging | P02, P08 |
| P04 Reguliere Schoonmaak | P05, P02, P06 |
| P05 Vloeronderhoud | P04, P07 |
| P06 VVE Service | P04, P02 |
| P07 Oplevering Schoonmaak | P04, P02, P05 |
| P08 Industriele Schoonmaak | P04, P03 |

**Business Rules:**
- Every service page must have a visible CTA to `/offerte-aanvragen/` (not `/contact/`).
- Every service page must have unique title tag (50–60 chars) and meta description (150–160 chars).
- Every service page must have H1 present exactly once.
- Every service page must have minimum 2 H2 sections.
- Service schema JSON-LD output on every service page.

**Error States:**
- Custom field not set: Hero subtitle and background image are omitted gracefully (no empty elements rendered).
- CTA override not set: Default text "Vrijblijvende offerte" is used.
- Cross-sell services not configured: Section omitted gracefully.

**Acceptance Criteria (per page):**
- AC-SP01: Page returns HTTP 200 at correct URL
- AC-SP02: Content >= 300 words Dutch
- AC-SP03: H1 present exactly once
- AC-SP04: Minimum 2 H2 sections
- AC-SP05: Cross-links to required related services present
- AC-SP06: CTA to `/offerte-aanvragen/` present
- AC-SP07: Title tag and meta description set
- AC-SP08: Service schema valid (Google Rich Results Test)
- AC-SP09: Responsive at all breakpoints
- AC-SP10: No PHP errors or warnings

### 4.3 Category Landing Pages

**Purpose:** SEO landing pages for broader search terms. Aggregate sub-service cards with introductory text.

**Pages:** P09 Glas & Gevel (`/glas-en-gevel/`), P10 Schoonmaakdiensten (`/schoonmaakdiensten/`)
**Template:** `page-templates/page-category-landing.php`
**Minimum word count:** 500+ Dutch words each

**Structure:**

| # | Section | Content |
|---|---|---|
| 1 | Breadcrumbs | Home > [Page Name] |
| 2 | Hero | H1 = "Glas & Gevel Reiniging" or "Schoonmaakdiensten". Introductory paragraph (what these services cover, who they are for). |
| 3 | Service Card Grid | P09: Glasbewassing + Gevelreiniging cards. P10: Reguliere + Vloer + VVE + Oplevering + Industrieel cards. |
| 4 | CTA Banner | "Vrijblijvende offerte aanvragen" → `/offerte-aanvragen/` |

**Business Rules:**
- P09 Glas & Gevel must link only to P02 and P03.
- P10 Schoonmaakdiensten must link to P04, P05, P06, P07, P08.
- Both pages must be linked from the main navigation dropdown parent items (DIENSTEN → Glas & Gevel, DIENSTEN → Schoonmaakdiensten).

**Acceptance Criteria:**
- AC-CL01: Both pages return HTTP 200
- AC-CL02: Both pages >= 500 words Dutch
- AC-CL03: Service Card Grid renders correct sub-services
- AC-CL04: Card links point to correct service pages
- AC-CL05: Unique title tags and meta descriptions set
- AC-CL06: Pages linked from main navigation dropdown parent items

### 4.4 About Pages

**Purpose:** Build trust through company history, values, quality credentials, and recruitment information.

**Pages:** P11 Over HDS (`/over-hds/`), P12 Kwaliteit & Veiligheid (`/kwaliteit-veiligheid/`)
**Template:** `page-templates/page-about.php`
**Minimum word count:** P11: 500+ words; P12: 300+ words

**P11 Over HDS Content:**
- Company intro: Who HDS is, what they do
- History: Founding year, growth (MI-19 — **Assumption:** client provides; if not, omit history section)
- Core values: Kwaliteit, Veiligheid, MVO
- USPs: 6 items from current site (vast personeel, bedrijfsleiding bij opstart, maatwerk, herkenbare kleding, een aanspreekpunt, vrijblijvende offerte)
- Certifications: OSB membership (MI-25 — **Assumption:** link if provided), Arbo compliance, diplomas
- Team photos (MI-09 — **Assumption:** omit if not provided)

**P12 Kwaliteit & Veiligheid Content:**
- Kwaliteit: Continuous improvement, periodic checks, single point of contact, complaints resolution
- Veiligheid: OSB, Arbeidsinspectie, Arbo, RI&E per project, veiligheidspaspoort, diplomas
- MVO: Environmentally conscious products, employee care
- Certification logos (MI-09 — **Assumption:** display only if provided)
- External links to certifying bodies (if URLs confirmed)

**Acceptance Criteria:**
- AC-AB01: Both pages return HTTP 200
- AC-AB02: P11 >= 500 words; P12 >= 300 words Dutch
- AC-AB03: USPs preserved from current site
- AC-AB04: Three H2 sections on P12 (Kwaliteit, Veiligheid, MVO)
- AC-AB05: Title tags and meta descriptions set
- AC-AB06: Responsive at all breakpoints

### 4.5 Referenties Page

**Purpose:** Social proof — client logos, project descriptions, and testimonials.

**URL:** `/referenties/`
**Template:** `page.php` (default)
**Minimum word count:** 300+ words

**Content Blocks:**
1. Intro sentence: "In opdracht van onderstaande opdrachtgevers worden door HDS dagelijkse en/of periodieke werkzaamheden uitgevoerd."
2. Client Logo Grid: Queries uploaded client logos from a Customizer setting or predefined media. Conditional: HIDE section if no logos.
3. Testimonial Block: Queries `hds_testimonial` CPT via `hds/testimonial` custom block. Conditional: HIDE section if no testimonials. Empty state: "Wij horen graag uw ervaring! Deel uw review."

**CPT Conflict Resolution:** The `hds_testimonial` CPT is registered with `public => false` to avoid URL conflict with the `/referenties/` page. Testimonials are queried exclusively via the `hds/testimonial` custom block.

**Acceptance Criteria:**
- AC-RF01: Page returns HTTP 200
- AC-RF02: Content >= 300 words Dutch
- AC-RF03: No URL conflict between CPT and Page
- AC-RF04: Client logos displayed if MI-10 provided; section hidden if not
- AC-RF05: Testimonials displayed if MI-11 provided; empty state shown if not
- AC-RF06: Title tag and meta description set

### 4.6 Vacatures Page

**Purpose:** Attract job applicants through accessible, readable, and structured vacancy listings.

**URL:** `/vacatures/`
**Template:** `page.php` (default)
**Minimum word count:** 300+ words (including vacancy text)

**Content Blocks:**
1. Page heading and introductory paragraph about HDS as an employer
2. Vacancy listings: Queries `hds_vacancy` CPT via `hds/job-listing` custom block. Each card displays: title, hours/week, location, deadline, full description (toggle-to-expand), application button (mailto: link with pre-filled subject).
3. JobPosting structured data per vacancy.

**Critical Requirement:** ZERO scanned JPG images on the page. All content must be machine-readable HTML text. The current site stores vacancy content as scanned images of Word documents — this is completely replaced.

**Empty State:** "Er zijn momenteel geen openstaande vacatures." if no active vacancies.

**Acceptance Criteria:**
- AC-VC01: Page returns HTTP 200
- AC-VC02: ZERO scanned JPG images — all content is HTML text
- AC-VC03: Vacancy cards toggle-to-expand functionality works
- AC-VC04: Application mailto: links pre-fill subject with vacancy title
- AC-VC05: All text selectable and screen-reader accessible
- AC-VC06: Content >= 300 words Dutch (including vacancy text)
- AC-VC07: JobPosting schema valid per vacancy (Google Rich Results Test)

### 4.7 Downloads Page

**Purpose:** Provide downloadable legal and informational documents from the primary domain.

**URL:** `/downloads/`
**Template:** `page.php` (default)
**Minimum word count:** 150+ words

**Content:**
- Introductory text explaining available downloads
- Download Card List block pattern: Each card shows filename, description, download button with file type icon and file size
- PDFs hosted on primary domain (`helderduidelijkschoon.nl`), NOT on legacy domain (`hds-onderhoudsdiensten.nl`)

**Migration Requirement:** All PDFs must be downloaded from the legacy domain, uploaded to the primary domain media library, and served from the primary domain. Internal links must be updated. Legacy domain PDF URLs must issue 301 redirects to new primary domain URLs.

**Acceptance Criteria:**
- AC-DL01: Page returns HTTP 200 at `/downloads/`
- AC-DL02: All PDFs accessible from `helderduidelijkschoon.nl` (not legacy domain)
- AC-DL03: Page content >= 150 words Dutch
- AC-DL04: Download buttons functional
- AC-DL05: Legacy domain PDF URLs redirect to new URLs

### 4.8 Contact Page

**Purpose:** Primary conversion page. Enable visitors to contact HDS via a working form with GDPR-compliant consent.

**URL:** `/contact/`
**Template:** `page-templates/page-contact.php`
**Minimum word count:** 150+ words

**Layout:** Two-column (desktop). Left column (60%): Contact form (GF-1). Right column (40%): Contact Information Block.

**Left Column — Contact Form (GF-1):**
See Section 6.1 for full form specification.

**Right Column — Contact Information Block:**

| Element | Conditional | Content |
|---|---|---|
| Phone | Always visible | 0164-652846 — clickable `tel:` link |
| Email | Always visible | info@helderduidelijkschoon.nl — clickable `mailto:` link |
| Address | If MI-01 provided | [Straat + Huisnummer], [Postcode] [Plaats] |
| KVK | If MI-02 provided | KVK: [XXXXXXXX] |
| BTW | If MI-03 provided | BTW: [NLXXXXXXXXXB01] |
| Business Hours | If MI-04 provided | [Openingstijden per dag] |
| Social Links | Always visible | Facebook icon link + Instagram icon link |
| Map | If MI-01 provided | Google Maps iframe embed (only load after cookie consent — wrap in Complianz consent placeholder) |

**Conditional Rendering Rules:**
- Address section: Rendered only if `hds_address` Customizer field is not empty.
- KVK/BTW section: Rendered only if `hds_kvk` or `hds_btw` Customizer fields are not empty.
- Business hours section: Rendered only if `hds_opening_hours` Customizer field is not empty.
- Map: Rendered only if address is known. Wrapped in cookie consent placeholder (Complianz).

**Post-Submit Behavior:** Redirect to `/bedankt/?type=contact`. See Section 4.9.

**Business Rules:**
- The phone number and email address must be consistent with the footer and LocalBusiness schema. They are sourced from the Customizer as a single source of truth.
- The contact page must be linked from: main navigation, homepage icon grid, footer, and all service page CTAs.

**Acceptance Criteria:**
- AC-CT01: Page returns HTTP 200 at `/contact/` (was HTTP 500 on current site)
- AC-CT02: Two-column layout renders correctly on desktop
- AC-CT03: All form fields present and functional (see Section 6.1)
- AC-CT04: reCAPTCHA v3 active (badge visible)
- AC-CT05: Privacy checkbox unchecked by default, links to `/privacyverklaring/`
- AC-CT06: Form submits successfully → redirect to `/bedankt/?type=contact`
- AC-CT07: Confirmation email delivered to user within 2 minutes (not in spam)
- AC-CT08: Notification email delivered to info@ within 2 minutes
- AC-CT09: Entry stored in Gravity Forms database
- AC-CT10: Phone clickable (tel:), email clickable (mailto:)
- AC-CT11: Form validation errors display inline (Dutch language)
- AC-CT12: Keyboard-navigable and screen-reader compatible
- AC-CT13: Conditional sections (address/KVK/BTW/hours/map) display or hide correctly based on Customizer values

### 4.9 Bedankt Page

**Purpose:** Post-form-submission confirmation page. Acknowledges the user's submission and provides next steps.

**URL:** `/bedankt/`
**Template:** `page.php` (default)
**Minimum word count:** 50+ words

**Dynamic Content Based on `?type=` Parameter:**

| Parameter | Heading | Message |
|---|---|---|
| `?type=contact` | "Bedankt voor uw bericht" | "Wij streven ernaar binnen 1 werkdag te reageren." |
| `?type=offerte` | "Bedankt voor uw offerte aanvraag" | "Wij streven ernaar binnen 1 werkdag contact met u op te nemen voor een vrijblijvende offerte." |
| No parameter | "Bedankt" | Generic thank-you message plus phone number fallback. |

**Content:**
- Dynamic heading and message based on `?type=` query parameter
- Phone number (0164-652846) as fallback contact method
- Links to: Home, Diensten, Contact

**SEO Rules:**
- `<meta name="robots" content="noindex, nofollow">` — must never appear in search results
- Excluded from XML sitemap
- Page slug `/bedankt/` excluded from sitemap via Rank Math settings

**Acceptance Criteria:**
- AC-BK01: Page returns HTTP 200 at `/bedankt/`
- AC-BK02: Dynamic message changes based on `?type=` parameter
- AC-BK03: Phone number visible as fallback
- AC-BK04: Noindex meta tag present in page source
- AC-BK05: Page excluded from XML sitemap
- AC-BK06: Responsive

### 4.10 WooCommerce Shop

**Purpose:** Sell Airfixr air purification products (secondary business line). **Assumption:** Client confirms Airfixr product line should remain (Q09).

**Pages:**
| Page | URL | Template |
|---|---|---|
| Shop | `/winkel/` | WooCommerce archive-product.php |
| Product (x14) | `/product/{slug}/` | WooCommerce single-product.php |
| Cart | `/winkelmand/` | WooCommerce cart.php |
| Checkout | `/afrekenen/` | WooCommerce checkout.php |
| My Account | `/mijn-account/` | WooCommerce myaccount.php |

**WooCommerce Configuration:**

| Setting | Value |
|---|---|
| Currency | EUR (€) |
| Currency Position | Left |
| Thousand Separator | `.` |
| Decimal Separator | `,` |
| Decimals | 2 |
| Prices Entered With Tax | No (excl. BTW) — **Assumption:** Client confirms excl. BTW |
| Display Suffix | "excl. BTW" |
| Tax Rate | 21% (Dutch standard BTW) |
| Guest Checkout | Enabled |
| Coupons | Enabled |
| Reviews | Enabled (moderated) |
| Inventory Management | Enabled |
| Backorders | Disabled |

**Payment Gateway:** Mollie (iDEAL, Bancontact, credit cards, PayPal, SEPA) + Bank Transfer (BACS) fallback. **Assumption:** Client confirms Mollie (MI-15).

**Shop Intro:** `/winkel/` must include an introductory paragraph explaining the Airfixr product line and its connection to HDS's cleaning services. Minimum 100 words.

**Airfixr Landing Page:** `/luchtreiniging/` (P23) — dedicated landing page introducing Airfixr with links to the shop. Minimum 300 words.

**Cloudflare Cache Bypass (Mandatory):** Cart, checkout, and account pages must NEVER be cached by Cloudflare CDN. Configurable via Cloudflare Page Rules.

**Acceptance Criteria:**
- AC-WC01: Shop page returns HTTP 200 at `/winkel/`
- AC-WC02: All 14 products accessible and display correctly
- AC-WC03: Cart functionality works (add, update quantity, remove)
- AC-WC04: Checkout process functional
- AC-WC05: Payment gateway test transaction completes
- AC-WC06: Order confirmation email delivered
- AC-WC07: Guest checkout functional
- AC-WC08: Cloudflare cache bypass verified for cart/checkout/account pages (CF-Cache-Status: BYPASS)
- AC-WC09: Shop intro text >= 100 words

### 4.11 Search

**Purpose:** Allow visitors to find content by keyword.

**Location:** Available on search results page (`/search.php`), optionally in footer or header as a search icon/form.

**Search Engine:** Relevanssi (free version). Indexes: Pages, Posts, CPTs (`hds_vacancy`). Dutch language stemming enabled. Results sorted by relevance.

**Search Results Page (`/search.php`):**
- H1: "Zoekresultaten voor: [query]"
- Results list: Each result shows title (linked), excerpt, "Lees meer" link
- Pagination if > 10 results
- No results fallback: "Geen resultaten gevonden. Probeer een andere zoekterm." + search form displayed again

**Empty Search Query:** If user submits empty search, redirect back to the referring page or show search form without results.

**Acceptance Criteria:**
- AC-SR01: Search returns relevant results for Dutch queries
- AC-SR02: "glasbewassing" returns the Glasbewassing page as first result
- AC-SR03: No results shows fallback message with search form
- AC-SR04: Empty query handled gracefully (no results, no error)
- AC-SR05: Results paginated (>10 results)
- AC-SR06: Search form present on 404 page

### 4.12 Navigation

**Purpose:** Enable visitors to discover and access all site content.

**Primary Navigation (Desktop):**

```
[LOGO]    DIENSTEN v    OVER HDS v    LUCHTREINIGING v    CONTACT    [TEL]
```

**DIENSTEN Dropdown:**
```
Glas & Gevel                → /glas-en-gevel/
  Glasbewassing             → /glasbewassing/
  Gevelreiniging            → /gevelreiniging/

Schoonmaakdiensten          → /schoonmaakdiensten/
  Reguliere Schoonmaak      → /reguliere-schoonmaak/
  Vloeronderhoud            → /vloeronderhoud/
  VVE Service               → /vve-service/
  Oplevering Schoonmaak     → /oplevering-schoonmaak/
  Industriele Schoonmaak    → /industriele-schoonmaak/
```

**OVER HDS Dropdown:**
```
Over HDS                   → /over-hds/
Kwaliteit & Veiligheid     → /kwaliteit-veiligheid/
Referenties                → /referenties/
Vacatures                  → /vacatures/
Downloads                  → /downloads/
```

**LUCHTREINIGING Dropdown:**
```
Over Airfixr               → /luchtreiniging/
Winkel                     → /winkel/
Mijn Account               → /mijn-account/
```

**Mobile Navigation:** Hamburger icon (☰) toggles an accordion menu. Each parent item expands/collapses to show children. All items reachable without horizontal scroll. Touch targets >= 44×44px. Keyboard: Enter/Space to toggle, Escape to close. `aria-expanded` attribute managed dynamically.

**Footer Navigation:** 5-column layout on desktop; stacks on mobile:
- Column 1: DIENSTEN (all 7 services)
- Column 2: OVER HDS (all 4 sub-pages)
- Column 3: CONTACT (phone, email, address/KVK/BTW conditionally)
- Column 4: LUCHTREINIGING (Airfixr landing, shop, account)
- Column 5: JURIDISCH (Privacyverklaring, Cookiebeleid, Algemene Voorwaarden, Disclaimer)

**Breadcrumbs:** Visible on all inner pages (not Home). Flat structure: Home > [Page Name]. Rendered with `aria-label="Kruimelpad"`. Schema BreadcrumbList auto-generated.

**Business Rules:**
- All 32 pages must be reachable from either main navigation or footer.
- The Contact page must be linked from main nav, footer, and homepage.
- The Offerte Aanvragen page must be linked from all service pages and homepage CTAs.
- No orphan pages: every page reachable from at least 2 other locations (internal links + nav + footer).

**Acceptance Criteria:**
- AC-NAV01: All navigation links point to correct URLs (zero broken links)
- AC-NAV02: Desktop dropdowns open on hover; close on mouse-out
- AC-NAV03: Mobile hamburger opens/closes on tap; all items reachable
- AC-NAV04: Touch targets >= 44×44px on mobile
- AC-NAV05: Keyboard navigation functional (Tab, Enter/Space, Escape)
- AC-NAV06: `aria-expanded` reflects menu state
- AC-NAV07: Footer navigation displays all 5 columns on desktop; stacks on mobile
- AC-NAV08: Breadcrumbs visible on all inner pages (not Home)
- AC-NAV09: Zero orphan pages (Screaming Frog crawl)

### 4.13 Header

**Purpose:** Global site header present on every page.

**Elements:**
- **Logo:** Company logo. If MI-06 (vector logo) provided, display SVG. If not, display text "HDS Onderhoudsdiensten" as fallback site title. Logo links to `/`.
- **Primary Navigation:** See Section 4.12.
- **Phone:** Clickable `tel:0164-652846` link with phone icon. Visible on desktop; icon-only on mobile.
- **Email:** Clickable `mailto:info@helderduidelijkschoon.nl` link (optional — visible on desktop only).
- **Cart Icon:** WooCommerce cart icon with item count. Visible only if WooCommerce is active and shop is enabled.
- **Skip to Content:** First focusable element on every page. `href="#main"`. `class="skip-link screen-reader-text"`. Visible on `:focus`.

**Behavior:**
- Sticky header — remains visible on scroll.
- On mobile, phone number text is hidden; only clickable phone icon remains.
- Cart count updates dynamically when products are added (WooCommerce AJAX).

**Acceptance Criteria:**
- AC-HD01: Header renders on every page
- AC-HD02: Logo links to `/`
- AC-HD03: Phone link functional (`tel:0164-652846`)
- AC-HD04: Skip to content link visible on Tab and functional
- AC-HD05: Cart icon visible when WooCommerce active
- AC-HD06: Sticky header does not overlap content

### 4.14 Footer

**Purpose:** Global site footer present on every page. Displays company information, legal links, and secondary navigation.

**Elements:**
- **5-column navigation grid:** See Section 4.12.
- **Company Information:** Phone, email, address (if MI-01), KVK (if MI-02), BTW (if MI-03). Sourced from Customizer as single source of truth.
- **Social Icons:** Facebook link icon + Instagram link icon. Link to respective profiles. `rel="noopener noreferrer"`. `target="_blank"` with accessible warning.
- **Copyright:** "© [YEAR] HDS Onderhoudsdiensten". Year auto-updated via `gmdate('Y')`.
- **Cookie Settings:** "Cookie-instellingen" link that re-opens Complianz consent modal. Provided by Complianz plugin.

**Conditional Rendering:**
- Address: Displayed only if `hds_address` Customizer field is not empty.
- KVK: Displayed only if `hds_kvk` is not empty.
- BTW: Displayed only if `hds_btw` is not empty.
- Social icons: Displayed only if corresponding URLs are set.

**Business Rules:**
- NAP (Name, Address, Phone) must be consistent across footer, Contact page, and LocalBusiness schema. All sourced from Customizer.
- Legal links (Privacyverklaring, Cookiebeleid, Algemene Voorwaarden, Disclaimer) must be present in footer on every page.
- The Instagram widget from the current site (broken, displaying "Instagram did not return a 200") must be removed. Replaced with a static icon link.

**Acceptance Criteria:**
- AC-FT01: Footer renders on every page
- AC-FT02: NAP consistent across footer, Contact page, and schema
- AC-FT03: Legal links present and functional
- AC-FT04: Social icons link to correct profiles
- AC-FT05: Copyright year auto-updated
- AC-FT06: No broken Instagram widget

### 4.15 Forms

**Purpose:** Capture leads (contact, quote) and job applications.

**Form Inventory:**

| Form | ID | Page | Fields | Features |
|---|---|---|---|---|
| Contact | GF-1 | `/contact/` | 9 | reCAPTCHA v3, honeypot, privacy checkbox |
| Offerte Aanvragen | GF-2 | `/offerte-aanvragen/` | 13 | Multi-select services, postcode validation, file upload (5MB) |
| Vacature Application | GF-3 | `/vacatures/` | 6 | CV upload (5MB), per-vacancy targeting |

**Detailed form specifications:** See Section 6.

**Common Form Behavior (All Forms):**
- **Loading State:** On submit, button text changes to "Versturen..." with spinner animation. Button disabled to prevent double-submit. Gravity Forms AJAX submission enabled on all forms.
- **Error Display:** Inline field errors in Dutch. Red text. Programmatically associated with field via `aria-describedby`.
- **Server Error:** "Er is een fout opgetreden bij het verzenden. Probeer het opnieuw of neem telefonisch contact op via 0164-652846."
- **Spam Detection:** reCAPTCHA v3 + honeypot. reCAPTCHA fails silently (no user-visible error). Honeypot catches bot submissions.
- **Email Configuration:** Confirmation email to user (Dutch, branded). Notification email to `info@helderduidelijkschoon.nl`. Both delivered via Post SMTP within 2 minutes.
- **Entry Storage:** All entries stored in Gravity Forms database. Retention: 12 months (contact/quote), 6 months (vacancy). Auto-delete after retention period.
- **File Upload Security:** Server-side MIME validation (beyond client-side extension check). Files renamed on upload. Download link in notification email (NOT inline attachment). Max 5MB. Allowed types: PDF, JPG, JPEG, PNG, DOC, DOCX.

### 4.16 Cookie Consent

**Purpose:** GDPR/AVG and ePrivacy Directive compliance. Obtain informed consent before loading non-functional cookies.

**Plugin:** Complianz Premium (Dutch market configuration)

**Behavior:**
1. **First Visit:** Banner appears at bottom or center of screen. Three options: "Accepteren" (accept all), "Weigeren" (reject all non-functional), "Instellingen aanpassen" (customize).
2. **Customize Modal:** Per-category toggles. Default: functional ON (always on), statistics OFF, marketing OFF.
3. **No Cookies Before Consent:** No Google Analytics, Facebook, or marketing cookies loaded before user action. Verified via DevTools Network tab — zero GA/Facebook requests before consent.
4. **After Consent:** Complianz sends consent signals to GTM. GTM Consent Mode v2 activates appropriate tags.
5. **Consent Logging:** Timestamp, anonymized IP, consent string logged by Complianz.
6. **Cookie Settings Button:** Floating button or footer link to re-open consent preferences post-consent.
7. **Cookiebeleid Page:** Auto-generated by Complianz at `/cookiebeleid/`. Linked from banner, footer, and privacyverklaring.

**Business Rules:**
- Functional cookies (WordPress session, WooCommerce cart, Complianz itself) are always loaded.
- The reCAPTCHA v3 badge must not be obscured by the cookie banner.
- Google Maps embed on Contact page must only load after cookie consent (wrap in Complianz consent placeholder).
- The cookiebeleid page must be published and linked from the footer before launch.

**Acceptance Criteria:**
- AC-CC01: Cookie banner appears on first visit (fresh browser / incognito)
- AC-CC02: No GA/Facebook cookies loaded before consent (DevTools verified)
- AC-CC03: "Accepteren" enables all categories
- AC-CC04: "Weigeren" disables all non-functional categories
- AC-CC05: "Instellingen aanpassen" opens per-category toggle modal
- AC-CC06: Consent logged (Complianz scan log)
- AC-CC07: Cookiebeleid page published and accessible at `/cookiebeleid/`
- AC-CC08: Cookie settings accessible post-consent (floating button or footer link)
- AC-CC09: reCAPTCHA badge not obscured by cookie banner
- AC-CC10: Google Maps only loads after cookie consent

### 4.17 404 Page

**Purpose:** Provide helpful navigation when a visitor lands on a non-existent URL.

**Template:** `404.php`
**HTTP Status:** Must return true HTTP 404 status code.

**Content:**
- Heading: "Pagina niet gevonden"
- Message: "De pagina die u zoekt bestaat niet of is verplaatst."
- Search bar (prominent)
- Key links: Home, Diensten overview, Contact, Veelgestelde Vragen
- Contact info: Phone 0164-652846 (clickable), Email info@helderduidelijkschoon.nl (clickable)

**Acceptance Criteria:**
- AC-40401: Navigate to non-existent URL returns HTTP 404
- AC-40402: Page displays heading, message, search bar, key links, and contact info
- AC-40403: Search bar functional
- AC-40404: Phone and email links functional
- AC-40405: Responsive

### 4.18 Search Results Page

**See Section 4.11 Search.**

### 4.19 Legal Pages

**Purpose:** GDPR/AVG legal compliance and trust.

| Page | URL | Content Source | Min Words | Legal Review |
|---|---|---|---|---|
| Privacyverklaring | `/privacyverklaring/` | Drafted by developer; reviewed by lawyer | 500+ | **YES — before launch** |
| Cookiebeleid | `/cookiebeleid/` | Auto-generated by Complianz | Auto | **YES — review auto-content** |
| Algemene Voorwaarden | `/algemene-voorwaarden/` | Client provides (MI-16) | 500+ | **YES — by client's legal counsel** |
| Disclaimer | `/disclaimer/` | Drafted by developer | 200+ | Recommended |

**Template:** `page-templates/page-legal.php`

**Common Structure:** H1 → Content Area → "Laatst bijgewerkt: [date]" auto-generated from `get_the_modified_date()`.

**Business Rules:**
- All legal pages must be linked from the footer on every page.
- Privacyverklaring must be linked from all form consent checkboxes.
- Noindex is NOT applied to legal pages (they should be indexable for transparency).

**Acceptance Criteria:**
- AC-LG01: All 4 legal pages return HTTP 200
- AC-LG02: Privacyverklaring content drafted and ready for legal review
- AC-LG03: Cookiebeleid page auto-populated by Complianz
- AC-LG04: Algemene Voorwaarden page published (placeholder if MI-16 not provided)
- AC-LG05: "Laatst bijgewerkt" date visible on each page
- AC-LG06: All 4 pages linked from footer

### 4.20 Blog (Kennisbank)

**Purpose:** Content marketing and SEO. Build topical authority for cleaning-service-related search terms.

**Blog Index:** `/kennisbank/` — grid of post cards with image, title, date, excerpt, "Lees meer" link. Pagination. Empty state: "Binnenkort verschijnen hier de eerste artikelen." with link to Contact page.

**Blog Posts (5–10 initial):** `/kennisbank/{slug}/` (no date prefix — permanent URLs). Minimum 500 words each. Structure: Featured image, H1, date/category meta, content, related posts.

**Business Rules:**
- Blog posts use standard WordPress Posts with category base `kennisbank`.
- Permalink: `/kennisbank/{slug}/` — no year/month/date prefix. Permanent URLs for SEO.
- Comments: Disabled on blog posts (site-wide setting).

**Acceptance Criteria (for Sprint 5 — future):**
- AC-BL01: Blog index returns HTTP 200
- AC-BL02: Blog posts accessible at `/kennisbank/{slug}/`
- AC-BL03: Empty state displayed when no posts
- AC-BL04: Pagination functional
- AC-BL05: Related posts displayed on single post view

---

## 5. User Flows

### 5.1 Primary Conversion Flow — Facility Manager Requests Quote

```
1. Arrive on Homepage (/)
   └─ Read tagline and USP summary

2. Click "SCHOONMAAK" in Service Card Grid
   └─ Navigate to /reguliere-schoonmaak/ (P04)

3. Read service details
   ├─ Intro: What is regular cleaning
   ├─ Onze aanpak: Process, frequency, check-in/out
   ├─ Diensten: What's included
   └─ Veiligheid & Kwaliteit: Certifications, training

4. Review related services
   └─ Cross-Sell block: Vloeronderhoud, Glasbewassing, VVE Service

5. Click CTA: "Vrijblijvende offerte"
   └─ Navigate to /offerte-aanvragen/ (P17)

6. Complete Quote Request Form (GF-2)
   ├─ Fill: Naam*, Bedrijf*, E-mailadres*, Telefoonnummer*
   ├─ Select: Gewenste dienst (checkboxes)
   ├─ Fill: Postcode / Plaats*
   ├─ Optional: Upload file (situatietekening/foto)
   ├─ Check: Privacy akkoord*
   └─ Click: "Offerte aanvragen"

7. reCAPTCHA v3 verification (invisible)
   └─ Score check → Pass

8. Redirect to /bedankt/?type=offerte
   └─ "Bedankt voor uw offerte aanvraag"
   └─ "Wij streven ernaar binnen 1 werkdag contact op te nemen."

9. System Actions:
   ├─ Entry stored in Gravity Forms database
   ├─ Confirmation email sent to user (Dutch, branded)
   └─ Notification email sent to info@ (with file download link)

10. End of flow — visitor satisfied, HDS has qualified lead.
```

### 5.2 Alternative Flow — Visitor Calls Instead of Form

```
1-4. Same as 5.1

5. Click phone number in header
   └─ tel:0164-652846 → Device opens phone dialer

6. HDS staff answers
   └─ Staff records inquiry manually

7. End of flow — conversion via phone.
```

### 5.3 VvE Board Member Flow

```
1. Arrive from Google: "vve schoonmaak [regio]"
   └─ Land on /vve-service/ (P06)

2. Read service details
   ├─ Services: stairwells, halls, garages
   ├─ Additional: technical maintenance, outdoor
   └─ VvE Belang listing: external validation link

3. Click cross-link: Reguliere Schoonmaak
   └─ Explore complementary service

4. Click CTA: "Vrijblijvende offerte"
   └─ Navigate to /offerte-aanvragen/

5. Complete Quote Request Form
   ├─ Select: "VVE Service" from Gewenste dienst checkboxes
   └─ Fill remaining fields

6-10. Same as 5.1 steps 7-10.
```

### 5.4 Job Seeker Flow

```
1. Arrive from Google: "vacature schoonmaak [regio]" or direct
   └─ Land on /vacatures/ (P14)

2. Read employer intro
   └─ "Wordt u onze collega?"

3. Browse active vacancies
   ├─ Click vacancy card to expand details
   ├─ Read: title, hours, location, description, deadline
   └─ JobPosting schema visible to search engines

4. Click "Solliciteer nu" button
   └─ mailto: link opens email client
   └─ Subject pre-filled: "Sollicitatie: [Vacature Title]"
   └─ To: application email from vacancy meta

5. Job seeker sends application
   └─ System: No form submission (mailto: only)
   └─ Future: Gravity Forms application form (GF-3)

6. End of flow.
```

### 5.5 Airfixr Buyer Flow (Conditional on WooCommerce)

```
1. Arrive from Google: "luchtreiniger kopen" or through site
   └─ Land on /luchtreiniging/ (P23) or /winkel/ (P24)

2. Browse products
   └─ Product grid with images, titles, prices (excl. BTW)

3. Click product → /product/{slug}/
   └─ View: images, description, price, specifications

4. Click "In winkelmand"
   └─ AJAX add-to-cart (no page reload)
   └─ Cart count updates in header

5. Click cart icon → /winkelmand/
   └─ Review order, adjust quantities

6. Click "Doorgaan naar afrekenen" → /afrekenen/

7. Fill checkout form
   ├─ Billing details
   ├─ Select payment method (iDEAL, Bancontact, etc.)
   └─ Agree to Algemene Voorwaarden (checkbox)

8. Click "Plaats bestelling"
   ├─ Mollie redirect for payment
   └─ Complete payment at Mollie

9. Return to /afrekenen/order-received/
   └─ Order confirmation

10. System Actions:
    ├─ Order stored in WooCommerce
    ├─ Order confirmation email to customer (Dutch, branded)
    ├─ Order notification email to info@
    └─ Inventory updated

11. End of flow.
```

---

## 6. Form Specifications

### 6.1 Contact Form (GF-1)

**Location:** `/contact/` (left column)
**Plugin:** Gravity Forms

| # | Field Name | Type | Required | Validation | Notes |
|---|---|---|---|---|---|
| 1 | Naam | Single Line Text | Yes | Not empty | |
| 2 | Bedrijf | Single Line Text | No | — | |
| 3 | E-mailadres | Email | Yes | Valid email format; typo detection if possible | |
| 4 | Telefoonnummer | Phone | No | Accepts Dutch formats (+31, 06-, local) | |
| 5 | Onderwerp | Dropdown | Yes | One of: "Offerte aanvragen", "Vraag over diensten", "Klacht of opmerking", "Anders" | |
| 6 | Bericht | Paragraph Text | Yes | Min 10 characters | |
| 7 | Privacy akkoord | Checkbox | Yes | Must be checked | **Unchecked by default.** Label: "Ik ga akkoord met de [privacyverklaring](/privacyverklaring/)." Link to privacyverklaring required. |
| 8 | Anti-spam (hidden) | Hidden | — | Honeypot | Invisible to user. If filled, submission silently blocked. |
| 9 | reCAPTCHA v3 | Hidden | Yes | Score threshold (Gravity Forms default) | Invisible. Badge displayed per Google ToS. |
| — | Verzenden | Submit | — | — | Button label: "Verstuur bericht" |

**Validation Error Messages (Dutch):**

| Error | Dutch Message |
|---|---|
| Required field empty | "Dit veld is verplicht." |
| Invalid email | "Vul een geldig e-mailadres in." |
| Message too short | "Uw bericht moet minimaal 10 tekens bevatten." |
| Privacy not accepted | "U moet akkoord gaan met de privacyverklaring." |
| Server error | "Er is een fout opgetreden. Probeer het opnieuw of bel 0164-652846." |

**Post-Submit:**
- Redirect: `/bedankt/?type=contact`
- Confirmation email to user (Dutch, branded):
  - Subject: "Bedankt voor uw bericht — HDS Onderhoudsdiensten"
  - Body: "Beste [Naam], bedankt voor uw bericht. Wij streven ernaar binnen 1 werkdag te reageren. Met vriendelijke groet, HDS Onderhoudsdiensten | 0164-652846 | info@helderduidelijkschoon.nl"
- Notification email to `info@helderduidelijkschoon.nl`:
  - Subject: "Nieuw contactformulier bericht"
  - Body: All submitted field values.
- Entry stored in Gravity Forms (`wp_gf_entry` table).

### 6.2 Quote Request Form (GF-2)

**Location:** `/offerte-aanvragen/`
**Plugin:** Gravity Forms

| # | Field Name | Type | Required | Validation | Notes |
|---|---|---|---|---|---|
| 1 | Naam | Single Line Text | Yes | Not empty | |
| 2 | Bedrijf | Single Line Text | Yes | Not empty | |
| 3 | E-mailadres | Email | Yes | Valid email format | |
| 4 | Telefoonnummer | Phone | Yes | Dutch format | |
| 5 | Gewenste dienst | Checkboxes (Multi) | Yes | At least 1 selected | 8 options: Glasbewassing, Gevelreiniging, Reguliere Schoonmaak, Vloeronderhoud, VVE Service, Oplevering Schoonmaak, Industriele Schoonmaak, Anders |
| 6 | Type gebouw | Dropdown | No | — | Kantoor, Wooncomplex/VvE, School, Zorginstelling, Fabriek/Magazijn, Bouwproject, Anders |
| 7 | Postcode / Plaats | Single Line Text | Yes | Dutch postcode regex: `/^[1-9][0-9]{3}\s?[A-Z]{2}$/i` | "1234 AB" = valid; "1234" = invalid |
| 8 | Beschrijving aanvraag | Paragraph Text | No | — | |
| 9 | Gewenste planning | Dropdown | No | — | Zo snel mogelijk, Binnen 2 weken, Binnen 1 maand, Binnen 3 maanden, Oriënterend / geen haast |
| 10 | Hoe heeft u ons gevonden? | Dropdown | No | — | Google / Zoekmachine, VvE Belang, Social media, Collega / Relatie, Anders |
| 11 | Bestand uploaden | File Upload | No | Max 5MB; types: PDF, JPG, JPEG, PNG, DOC, DOCX | Server-side MIME validation. Files renamed on upload. |
| 12 | Privacy akkoord | Checkbox | Yes | Must be checked | **Unchecked by default.** Same label as GF-1. |
| 13 | Anti-spam (hidden) | Hidden | — | Honeypot | Same as GF-1. |
| 14 | reCAPTCHA v3 | Hidden | Yes | Score threshold | Same as GF-1. |
| — | Offerte aanvragen | Submit | — | — | Button label: "Offerte aanvragen" |

**Additional Validation:**
- Postcode: Must match Dutch format (NNNN AA). Server-side regex validation.
- File upload: Client-side size/type warning. Server-side MIME validation (not just extension). Rejected if MIME type doesn't match allowed list.

**Post-Submit:**
- Redirect: `/bedankt/?type=offerte`
- Confirmation email to user (Dutch, branded):
  - Subject: "Bedankt voor uw offerte aanvraag — HDS Onderhoudsdiensten"
  - Body: Includes summary of submitted data. "Wij streven ernaar binnen 1 werkdag contact met u op te nemen."
- Notification email to `info@helderduidelijkschoon.nl`:
  - Subject: "Nieuwe offerte aanvraag"
  - Body: All submitted field values + download link for uploaded file (NOT inline attachment).
- Entry stored in Gravity Forms.

### 6.3 Vacancy Application Form (GF-3)

**Location:** `/vacatures/` per vacancy
**Plugin:** Gravity Forms

| # | Field Name | Type | Required | Notes |
|---|---|---|---|---|
| 1 | Naam | Single Line Text | Yes | |
| 2 | E-mailadres | Email | Yes | |
| 3 | Telefoonnummer | Phone | Yes | |
| 4 | Motivatie | Paragraph Text | Yes | |
| 5 | CV uploaden | File Upload | Yes | Max 5MB; PDF, DOC, DOCX only |
| 6 | Privacy akkoord | Checkbox | Yes | Unchecked by default |
| 7 | Anti-spam (hidden) | Hidden | — | Honeypot |
| 8 | reCAPTCHA v3 | Hidden | Yes | |
| — | Sollicitatie versturen | Submit | — | |

**Post-Submit:**
- Confirmation email to applicant: "Bedankt voor uw sollicitatie. Wij nemen zo spoedig mogelijk contact met u op."
- Notification email to `info@helderduidelijkschoon.nl` (or per-vacancy application email) with CV download link.

---

## 7. Search Behaviour

### 7.1 Search Indexing

- **Engine:** Relevanssi (free tier)
- **Indexed Content:** Pages, Posts (`post`), `hds_vacancy` CPT
- **Excluded from Index:** `hds_testimonial` CPT (non-public), `hds_faq` CPT (non-public), attachment pages, author archives
- **Custom Field Indexing:** Relevanssi configured to index `post_content` and `post_title` only. SEO meta fields (Rank Math) NOT indexed to avoid duplicate content in search results.
- **Stemming:** Dutch language stemming enabled (Relevanssi supports Dutch).
- **Search Logic:** AND logic by default (all terms must match). Fallback to OR if no AND results.

### 7.2 Search Interface

- **Search Form Location:** 404 page (prominent), Search Results page (re-displayed), and optionally in footer or header.
- **Search Form Markup:** Standard WordPress `get_search_form()` with `<label>` hidden via `screen-reader-text`, `<input type="search">`, and `<button type="submit">`. Placeholder text: "Zoeken..."

### 7.3 Search Results

- **URL:** `/zoeken/?s={query}`. The page `/zoeken/` does not exist; all search queries use the WordPress search query parameter `?s=`.
- **Template:** `search.php`
- **Results Per Page:** 10
- **Result Display:** Title (linked to post/page), excerpt, "Lees meer" link
- **Ordering:** Relevance score (Relevanssi default)
- **Pagination:** Standard WP pagination if > 10 results

### 7.4 Empty Search States

- **No query:** Redirect to homepage or display search form with message: "Voer een zoekterm in."
- **No results:** Display: "Geen resultaten gevonden. Probeer een andere zoekterm." + search form.

---

## 8. Navigation Behaviour

### 8.1 Desktop Navigation

- **Hover:** Parent item (DIENSTEN, OVER HDS, LUCHTREINIGING) shows dropdown on `:hover`. Dropdown has subtle transition (CSS `opacity` + `transform`).
- **Mouse Out:** Dropdown hides after 200ms delay (prevents accidental close).
- **Click:** Parent item navigates to landing page (DIENSTEN → `/schoonmaakdiensten/`, OVER HDS → `/over-hds/`, LUCHTREINIGING → `/luchtreiniging/`).
- **Active State:** Current page or ancestor highlighted in navigation (CSS `.current-menu-item`, `.current-page-ancestor`).

### 8.2 Mobile Navigation

- **Trigger:** Hamburger icon (☰). `aria-controls="primary-menu"`, `aria-expanded="false"`.
- **Open:** Tap hamburger → `aria-expanded="true"`. Menu slides in or appears below header. Hamburger becomes close icon (✕).
- **Dropdown:** Parent items with children show expand arrow. Tap parent → children accordion open (without navigating). Tap parent again → children collapse.
- **Navigate:** Tap child item → navigate to page; menu closes.
- **Close:** Tap close icon (✕) or press Escape → menu closes. `aria-expanded="false"`. Focus returns to hamburger.
- **Touch Targets:** Minimum 44×44px. Sufficient spacing between items.

### 8.3 Keyboard Navigation

- **Tab:** Focus moves sequentially through all interactive elements.
- **Enter/Space:** Activates focused link or button.
- **Dropdown (Desktop):** `Enter` on parent → dropdown opens. `Tab` → focus moves into dropdown items. `Escape` → dropdown closes, focus returns to parent.
- **Mobile Menu:** `Enter/Space` on hamburger → open. `Escape` → close. `Tab` within open menu → focus trapped in menu.

### 8.4 Footer Navigation

- Always visible on every page.
- Links in 5 columns on desktop. Stack to single column on mobile.
- Legal links always present.
- Company info sourced from Customizer — if fields are empty, sections are hidden gracefully.

---

## 9. Error Handling

### 9.1 404 Errors

**Template:** `404.php`
**HTTP Status:** 404

**Content:** "Pagina niet gevonden. De pagina die u zoekt bestaat niet of is verplaatst." + Search bar + Links (Home, Diensten, Contact, FAQ) + Phone + Email.

**Post-Launch Monitoring:** 404 errors logged by Rank Math 404 monitor. Reviewed weekly. High-traffic 404 URLs redirected (301) if appropriate.

### 9.2 500 Errors

**Server-Level (if PHP/WP is down):** Custom `50x.html` error page at the web server level. Message: "Er is een technische storing. Onze excuses voor het ongemak. Neem telefonisch contact op: 0164-652846."

**Application-Level:** `WP_DEBUG_DISPLAY=false` on staging and production. Errors logged to `/wp-content/debug.log` via `WP_DEBUG_LOG=true`. Production: `WP_DEBUG=false` (only log, never display).

### 9.3 Form Validation Errors

- **Display:** Inline below the field. Red text. Dutch language.
- **Association:** Error message linked to field via `aria-describedby`.
- **Focus:** First field with error receives focus.
- **Messages:** Specific, actionable. See Section 6 for per-field error messages.

### 9.4 Missing Content (Empty States)

| Scenario | Behavior |
|---|---|
| No testimonials | Testimonial section hidden on homepage. Referenties page shows "Wij horen graag uw ervaring!" |
| No client logos | Client Logo Carousel section hidden on homepage. Referenties page shows empty state message. |
| No blog posts | Latest Blog Posts section hidden on homepage. Blog index shows "Binnenkort verschijnen hier de eerste artikelen." + link to Contact. |
| No active vacancies | "Er zijn momenteel geen openstaande vacatures." |
| No search results | "Geen resultaten gevonden. Probeer een andere zoekterm." + search form. |
| Customizer field empty | Conditional sections (address, KVK, BTW, hours) hidden — no empty elements rendered. |
| Custom field not set | Service hero subtitle/image omitted — no empty elements rendered. |

### 9.5 Form Submission Errors

- **SMTP Down:** Entry stored in Gravity Forms database. Error logged. Admin notified. User sees success page (redirect to `/bedankt/`). Entry is NOT lost — it's in the database.
- **File Upload Too Large:** Client-side warning. Server-side rejection. Error: "Het bestand is te groot. Maximale grootte: 5 MB."
- **File Upload Wrong Type:** Client-side warning. Server-side rejection. Error: "Dit bestandstype is niet toegestaan. Toegestane types: PDF, JPG, PNG, DOCX."
- **reCAPTCHA Blocks Legitimate User:** Honeypot catches most spam so reCAPTCHA rarely falls back. Phone number visible on form page as alternative contact method.
- **PHP Limits Too Low:** `upload_max_filesize=10M`, `post_max_size=12M`, `max_execution_time=120` configured at server/PHP level. Verified before launch.

---

## 10. Content Publishing Rules

### 10.1 Content Creation

- All content created via Block Editor (no page builder, no shortcodes).
- Service pages use "Service" template with custom fields for subtitle, hero image, icon, CTA override.
- Blog posts use standard WordPress Posts.
- Legal pages use "Legal" template.
- Content revisions: WordPress revisions enabled (max 10 per post).

### 10.2 Content Approval

- No formal approval workflow at launch. Editors have `publish_pages` and `publish_posts` capabilities.
- Revisions provide rollback capability.
- **Assumption:** 1–2 content editors. If team grows, implement PublishPress or Edit Flow.

### 10.3 Minimum Content Standards

| Page Type | Minimum Words | Required Elements |
|---|---|---|
| Service pages | 300+ Dutch | H1, ≥ 2 H2 sections, CTA, cross-links |
| Category landings | 500+ Dutch | H1, intro paragraph, service card grid, CTA |
| About pages | 300–500+ Dutch | H1, H2 sections |
| Contact/Quote | 150+ Dutch | H1, form, CTA |
| Legal pages | 150–500+ Dutch | H1, content, "Laatst bijgewerkt" date |
| Blog posts | 500+ Dutch | H1, featured image, content |

### 10.4 Prohibited Content

- No lorem ipsum or placeholder text in production.
- No scanned images as content (current Vacatures page violation resolved).
- No PDFs hosted on legacy domain — all on primary domain.
- No "Hello World" default post — delete and issue 410 Gone.

### 10.5 Content Freeze During Migration

Before content migration begins (Sprint 2), client is notified: "Do not edit the old website from this date forward. All content updates should be documented and provided to the development team for inclusion in the new site."

---

## 11. SEO Behaviour

### 11.1 Metadata

- **Title Tags:** Unique per page. Pattern: `[Page Title] — HDS Onderhoudsdiensten`. Length: 50–60 characters.
- **Meta Descriptions:** Unique per page. Length: 150–160 characters. Structure: primary keyword + location + value proposition + CTA.
- **Implementation:** Rank Math Pro per-page fields.
- **Verification:** Screaming Frog scan: zero empty, zero duplicate.

### 11.2 Canonical URLs

- Self-referencing canonicals on all pages.
- Trailing slash canonical. Non-slash variant → 301 before canonical applies.
- Paginated archives: canonical points to page 1.

### 11.3 Open Graph & Twitter Cards

- **Auto-generated by:** Rank Math Pro.
- **Tags:** `og:title`, `og:description`, `og:image` (social share image 1200×630px), `og:url`, `og:type`, `og:locale` (nl_NL), `twitter:card` (summary_large_image).
- **Fallback:** If page has no featured image, use site-wide social share image.
- **Verification:** Facebook Sharing Debugger + Twitter Card Validator.

### 11.4 Structured Data

All 9 schema types output as JSON-LD (not microdata). Generated by combination of Rank Math Pro (auto) + theme (custom).

| Schema | Source | Pages |
|---|---|---|
| WebSite + SearchAction | Rank Math auto | All |
| WebPage | Rank Math auto | All |
| BreadcrumbList | Rank Math + theme | All inner pages |
| LocalBusiness (HomeAndConstructionBusiness) | Theme (`inc/schema.php`) | Home, Contact, Over HDS |
| Service | Theme per page | P02–P08 |
| FAQPage | Rank Math auto from FAQ blocks | P18 |
| Product | WooCommerce auto | P25 ×14 |
| JobPosting | Theme per vacancy | P14 |
| Organization with sameAs | Theme | All |

**Verification:** All schema validated via Google Rich Results Test before launch.

### 11.5 XML Sitemap

- **URL:** `/sitemap_index.xml` (HTTP 200, valid XML)
- **Sub-Sitemaps:** `page-sitemap.xml`, `post-sitemap.xml`, `product-sitemap.xml`
- **Excluded:** Attachment pages, author archives, noindex pages (Bedankt, legal if noindexed), cart, checkout, account.
- **Submission:** Submitted to Google Search Console and Bing Webmaster Tools at launch.

### 11.6 robots.txt

- **Location:** `/robots.txt` (auto-generated by Rank Math Pro)
- **Disallow:** `/wp-admin/`, `/wp-includes/`, `/wp-content/plugins/`, query parameters (non-WC), personal data.
- **Verification:** Returns HTTP 200 with valid content.

### 11.7 Redirects (301 and 410)

| Type | Old URL | New URL / Status |
|---|---|---|
| 301 | `/glasbewassing` (no slash) | `/glasbewassing/` |
| 301 | `/vve` | `/vve-service/` |
| 301 | `/vve/` | `/vve-service/` |
| 301 | `/?page_id=318` | `/reguliere-schoonmaak/` |
| 301 | `http://helderduidelijkschoon.nl/*` | `https://helderduidelijkschoon.nl/*` |
| 301 | `http://www.helderduidelijkschoon.nl/*` | `https://helderduidelijkschoon.nl/*` |
| 301 | `https://www.helderduidelijkschoon.nl/*` | `https://helderduidelijkschoon.nl/*` |
| 410 | `/2015/06/29/hallo-wereld/` | Gone |
| 410 | `/2015/08/25/kwaliteit-veiligheid/` | Gone |

**Implementation:** Rank Math Pro redirect manager. All redirects tested manually before launch. Zero redirect chains. All verified via `httpstatus.io`.

---

## 12. WooCommerce Behaviour

### 12.1 Product Display

- **Shop Page:** Product grid with images, titles, prices (excl. BTW). Filterable by category if product categories exist. Pagination.
- **Single Product:** Images (gallery), title, price (excl. BTW), description, specifications, add-to-cart button, quantity selector.
- **Inventory:** Managed per product. Low-stock threshold: 2. Low-stock notification email to `info@helderduidelijkschoon.nl`. Out-of-stock: "Niet op voorraad" with disabled add-to-cart.

### 12.2 Cart & Checkout

- **Cart:** Product list with thumbnail, title, price, quantity (editable), subtotal, total. Remove item button. "Doorgaan naar afrekenen" CTA. "Verder winkelen" link → `/winkel/`.
- **Checkout:** Billing form. Payment method selection (Mollie: iDEAL, Bancontact, credit cards, PayPal, SEPA). Terms checkbox linking to `/algemene-voorwaarden/`. "Plaats bestelling" button.
- **Guest Checkout:** Enabled. Account creation optional (checkbox at checkout).

### 12.3 Payment Processing

- **Mollie Integration:** Redirect to Mollie-hosted payment page. Webhook returns payment status to WooCommerce.
- **Fallback:** Bank Transfer (BACS) — manual payment, order on hold until payment received.
- **Order Status Flow:** Pending → Processing (payment confirmed) → Completed (order fulfilled).

### 12.4 Emails

- **Order Confirmation:** To customer. Dutch. Branded. Order summary.
- **Order Processing:** To customer. Payment confirmed.
- **Order Completed:** To customer. Order fulfilled.
- **New Order Notification:** To `info@helderduidelijkschoon.nl`.
- **All Emails:** From "HDS Onderhoudsdiensten" <info@helderduidelijkschoon.nl>. Via Post SMTP.

### 12.5 Cloudflare Cache Bypass

**Mandatory:** The following URLs must NEVER be cached by Cloudflare:
- `/winkelmand/*`
- `/afrekenen/*`
- `/mijn-account/*`
- `/wp-json/wc/*`
- `/?wc-ajax=*`

Verified via response headers: `CF-Cache-Status: BYPASS`.

---

## 13. Security Behaviour

### 13.1 Authentication

- Custom login URL (not `/wp-admin/` or `/wp-login.php`). Configured via Wordfence.
- 2FA via Wordfence on ALL Administrator, Editor, and Shop Manager accounts.
- Login limiting: 3 failed attempts → IP lockout (Wordfence brute force protection).
- Password policy: Minimum 12 characters enforced by Wordfence.
- Session timeout: 48 hours. Force logout on password change.

### 13.2 Attack Surface Reduction

- XML-RPC disabled at web server level (returns 403).
- REST API user endpoint blocked (`/wp-json/wp/v2/users`).
- Author archives disabled (`?author=N` → 301 redirect to homepage).
- Directory listing disabled.
- File editor disabled (`DISALLOW_FILE_EDIT=true`).

### 13.3 Data Security

- All form inputs sanitized (`sanitize_text_field()`, `sanitize_email()`, etc.).
- All output escaped (`esc_html()`, `esc_attr()`, `esc_url()`, `wp_kses()`).
- All custom forms protected by nonces.
- Database queries use `$wpdb->prepare()`.
- Database prefix changed from `wp_` to `hds_`.
- No `eval()`, no `base64_decode()`, no `extract()` in theme code.

### 13.4 Malware Protection

- Wordfence Premium: Daily malware scan. File integrity monitoring. Firewall.
- Auto-updates enabled for minor WordPress core, plugin, and theme releases.
- Major updates tested on staging before production deployment.

---

## 14. Accessibility Behaviour

**Compliance Target:** WCAG 2.2 Level AA (all success criteria). AAA target size (2.5.8) adopted as AA requirement (≥ 44×44px).

### 14.1 Mandatory Behaviours

| # | Behaviour | WCAG SC |
|---|---|---|
| 1 | Color contrast ≥ 4.5:1 (normal text), ≥ 3:1 (large text, UI components) | 1.4.3, 1.4.11 |
| 2 | All interactive elements focusable and operable via keyboard | 2.1.1, 2.1.2 |
| 3 | Visible focus indicator on all interactive elements | 2.4.7 |
| 4 | Skip to content link — first focusable element; visible on focus | 2.4.1 |
| 5 | Semantic HTML: `header`, `nav`, `main`, `footer`; H1-H2-H3 hierarchy (no skipped levels) | 1.3.1 |
| 6 | Alt text: descriptive Dutch on all non-decorative images; `alt=""` on decorative | 1.1.1 |
| 7 | All form fields have `<label>`; required fields marked with `aria-required`; errors via `aria-describedby` | 1.3.1, 3.3.1, 3.3.2 |
| 8 | Descriptive link text (no "klik hier", no "lees meer" without context) | 2.4.4 |
| 9 | 200% zoom: content remains usable; no horizontal scroll | 1.4.4 |
| 10 | Touch targets ≥ 44×44px | 2.5.8 (AAA) |
| 11 | `lang="nl-NL"` on `<html>` element | 3.1.1 |
| 12 | Unique, descriptive `<title>` on every page | 2.4.2 |
| 13 | Dynamic content updates announced via `aria-live` regions | 4.1.3 |
| 14 | Navigation order and position consistent across all pages | 3.2.3 |
| 15 | Same-function components identified consistently | 3.2.4 |
| 16 | No auto-playing media; respect `prefers-reduced-motion` | 2.3.1, 2.3.2 |

### 14.2 Testing

- **Automated:** axe DevTools (zero critical, zero serious), WAVE (zero errors), Lighthouse Accessibility = 100.
- **Manual Keyboard:** Tab through Home, 2 service pages, Contact form, 1 product page. All elements reachable and operable. Focus visible.
- **Manual Screen Reader:** NVDA (Windows) or VoiceOver (Mac). Test: Homepage navigation, 2 service pages, Contact form (label announcement, error announcement, submit confirmation).
- **Color Contrast:** WebAIM Contrast Checker or axe DevTools. All color combinations pass AA.
- **200% Zoom:** Browser zoom to 200%. No content loss. No horizontal scroll.
- **Real Mobile:** VoiceOver (iOS) or TalkBack (Android). Minimum 3 pages tested.

---

## 15. Performance Behaviour

### 15.1 Performance Budgets (Hard QA Gates)

| Metric | Target | Test Tool |
|---|---|---|
| Largest Contentful Paint (LCP) | < 2.5s | PSI, Lighthouse |
| Cumulative Layout Shift (CLS) | < 0.1 | PSI, Lighthouse |
| Interaction to Next Paint (INP) | < 200ms | PSI, Chrome UX Report |
| Time to First Byte (TTFB) | < 600ms | WebPageTest |
| Total Page Weight (Mobile) | < 1.5 MB | WebPageTest |
| PSI Mobile Score | 90+ | PSI |
| PSI Desktop Score | 95+ | PSI |
| Lighthouse Accessibility | 100 | Lighthouse |

### 15.2 Caching Behaviour

- **Page Cache (FlyingPress):** All public pages cached. Cleared on post/page update, plugin/theme update, or manual clear.
- **Object Cache (Redis):** WP_Query, transients, options cached in memory.
- **Browser Cache:** 1 year for static assets with versioned filenames (via `filemtime()`).
- **CDN Cache (Cloudflare):** Full-page caching. Bypass for WooCommerce cart/checkout/account and admin URLs.

### 15.3 Image Behaviour

- **Format:** WebP with PNG/JPEG fallback via `<picture>` element.
- **Responsive:** `srcset` with 400w, 800w, 1200w variants. `sizes` attribute.
- **Lazy Loading:** `loading="lazy"` on all images below fold. `fetchpriority="high"` on LCP image.
- **Dimensions:** Explicit `width` and `height` attributes to prevent CLS.
- **Compression:** Visually lossless (quality 85+) via ShortPixel/Imagify.

### 15.4 JavaScript Behaviour

- **Loading:** All scripts use `defer` attribute. No render-blocking JavaScript.
- **jQuery:** Removed unless WooCommerce requires it. jQuery Migrate removed.
- **Inline Scripts:** Minimized. Only for critical configuration data (`wp_add_inline_script()`).

### 15.5 CSS Behaviour

- **Critical CSS:** Inlined in `<head>` (auto-generated by FlyingPress).
- **Non-Critical CSS:** Deferred loading.
- **Fonts:** Self-hosted Open Sans (WOFF2). `font-display: swap`. Preloaded in `<head>`.

---

## 16. Acceptance Criteria

### 16.1 Functional Acceptance

| # | Criterion | Pass Condition |
|---|---|---|
| AC-F01 | All 32 pages return HTTP 200 (or appropriate status) | Screaming Frog: zero 4xx/5xx on expected pages |
| AC-F02 | Contact form submits and delivers email | Test submission → email received within 2 minutes |
| AC-F03 | Quote form submits with file upload | Test submission with PDF → email received with download link |
| AC-F04 | WooCommerce purchase flow end-to-end | Test order: Product → Cart → Checkout → Payment → Email |
| AC-F05 | All 301 redirects work correctly | Each old URL tested; zero redirect chains |
| AC-F06 | All internal links resolve to valid pages | Screaming Frog: zero broken internal links |
| AC-F07 | Search returns relevant results | "glasbewassing" → Glasbewassing page as first result |
| AC-F08 | Mobile menu functional on all device sizes | Manual: iPhone SE, iPhone 14, iPad, desktop |
| AC-F09 | All forms have functional anti-spam | Honeypot field blocks bot; reCAPTCHA v3 active |
| AC-F10 | Cookie consent banner appears on first visit | Fresh browser / incognito |

### 16.2 Validation Rules

#### 16.2.1 Form Field Validation

| Form | Field | Validation Rule | Error Message (Dutch) | Implementation |
|---|---|---|---|---|
| GF-1, GF-2, GF-3 | E-mailadres | `is_email` (RFC 5322). Max 254 chars. | "Vul een geldig e-mailadres in." | Gravity Forms built-in |
| GF-1, GF-2, GF-3 | Verplichte velden | Not empty. Not whitespace-only. | "Dit veld is verplicht." | Gravity Forms `required` |
| GF-2 | Postcode / Plaats | Regex: `/^[1-9][0-9]{3}\s?[A-Z]{2}$/i` (Dutch NNNN AA format) | "Vul een geldige postcode in (bijv. 1234 AB)." | Gravity Forms custom regex |
| GF-1 | Bericht | Minimum 10 characters. Max 5000. | "Uw bericht moet minimaal 10 tekens bevatten." | Gravity Forms character limit |
| GF-1, GF-2, GF-3 | Telefoonnummer | Dutch/international format: +31, 06-, or local. Regex: `/^(\+31|0)[1-9][0-9]{7,10}$/` | "Vul een geldig telefoonnummer in." | Gravity Forms custom regex |
| GF-1, GF-2, GF-3 | Privacy akkoord | Checkbox MUST be checked. Unchecked by default. | "U moet akkoord gaan met de privacyverklaring." | Gravity Forms `required` on checkbox |
| GF-2, GF-3 | Bestand uploaden | Max 5 MB. Allowed MIME types: `application/pdf`, `image/jpeg`, `image/png`, `application/msword`, `application/vnd.openxmlformats-officedocument.wordprocessingml.document`. Server-side MIME validation beyond client extension check. | "Het bestand is te groot. Maximale grootte: 5 MB." / "Dit bestandstype is niet toegestaan. Toegestane types: PDF, JPG, PNG, DOCX." | Gravity Forms file upload settings + server-side `finfo` MIME check |

#### 16.2.2 Server-Side Validation (Beyond Client-Side)

| Check | Implementation | Failure Behavior |
|---|---|---|
| File MIME type | `finfo(FILEINFO_MIME_TYPE)` on uploaded file. Reject if MIME doesn't match allowed list. | Delete uploaded file. Return form error. Log attempt. |
| File size (server) | Compare `$_FILES['size']` against `upload_max_filesize` and `post_max_size` PHP limits. | Gravity Forms catches this before PHP error. Form error displayed. |
| reCAPTCHA v3 score | Gravity Forms reCAPTCHA add-on. Score < 0.5 → silently blocked. Score 0.5-0.7 → flagged for review but accepted. Score > 0.7 → accepted. | < 0.5: Form appears to submit but entry is not stored. Anti-spam log entry created. ≥ 0.5: Entry stored normally. |
| Honeypot | Hidden field. If filled (bot), submission silently blocked. | No indication to user. Entry not stored. |
| Nonce verification | `wp_verify_nonce()` on all custom form submissions. | If nonce invalid/expired: "Uw sessie is verlopen. Vernieuw de pagina en probeer opnieuw." |

### 16.3 Loading States

| Component | Loading State | Implementation |
|---|---|---|
| Contact Form (GF-1) | On submit: button text changes to "Versturen..." with disabled state + CSS spinner. Form fields disabled during submission. | Gravity Forms AJAX submission (`ajax="true"` in shortcode). |
| Quote Form (GF-2) | On submit: button text changes to "Offerte versturen..." with disabled state + spinner. File upload shows progress bar. | Gravity Forms AJAX + file upload progress. |
| Vacature Form (GF-3) | On submit: button text changes to "Sollicitatie versturen..." with disabled state + spinner. CV upload shows progress bar. | Gravity Forms AJAX + file upload progress. |
| WooCommerce Add to Cart | Button text changes to "Toevoegen..." with spinner. Cart icon in header updates with count badge. | WooCommerce default AJAX add-to-cart. |
| Cookie Banner Dismiss | Banner fades out over 300ms. Focus moves to the next focusable element in the DOM (typically skip-to-content link or header logo). | CSS transition + JavaScript `element.focus()`. |
| Search Results | While results load (Relevanssi query), a skeleton loader (3 gray placeholder cards) is displayed. | JavaScript: show skeleton on input, hide on results render. Fallback: full page reload if JS disabled. |

### 16.4 Error Handling — Edge Cases

| Error Scenario | User-Facing Behavior | Developer Action |
|---|---|---|
| SMTP email delivery failure | Form submission appears successful to user (redirect to /bedankt/). Entry stored in database. Admin notification email may not be delivered. | Post SMTP logs the failure. Developer reviews email log weekly. If failure rate > 5% in 24 hours, alert triggered. Gravity Forms entry serves as backup record. |
| reCAPTCHA blocks legitimate user (score < 0.5) | Form appears to submit but no confirmation is shown. Page refreshes with form reset (silent failure). | Fallback text below form: "Lukt het niet om het formulier te verzenden? Bel ons op 0164-652846 of mail naar info@helderduidelijkschoon.nl." |
| File upload exceeds PHP `upload_max_filesize` or `post_max_size` | Gravity Forms catches this before PHP fatal error. Inline error: "Het bestand is te groot. Maximale grootte: 5 MB." | PHP limits must be configured: `upload_max_filesize = 10M`, `post_max_size = 12M`, `max_execution_time = 60`. Verified via Site Health. |
| Payment gateway timeout (Mollie) | User sees WooCommerce error notice on checkout: "Er is een fout opgetreden bij het verwerken van uw betaling. Probeer het opnieuw of kies een andere betaalmethode." Order set to "Failed" status. Admin notified. | WooCommerce logs error. Mollie plugin retries webhook delivery (Mollie-side). If webhook arrives late, order status updates from Failed to Processing. |
| Product in cart goes out of stock during checkout | WooCommerce natively displays: "Sorry, [product] is niet meer op voorraad. Verwijder het product om verder te gaan." Product highlighted in red in order review. | Standard WooCommerce behavior. No custom handling needed. |
| Blog post slug conflicts with existing page slug | WordPress admin prevents creation (unique slug enforcement). If created programmatically: WordPress appends "-2" to slug. | Standard WordPress behavior. No custom handling needed. |
| Cloudflare serves stale content after WordPress update | FlyingPress → Cloudflare API cache purge triggered on post/page update. If purge fails: stale content served until TTL expires (max 30 min for HTML, 1 year for static assets — but versioned filenames prevent stale CSS/JS). | FlyingPress + Cloudflare integration configured. Manual Cloudflare cache purge available as backup. |
| 503 Maintenance Mode | Custom `maintenance.php` file in `wp-content/`. Displays: "HDS Onderhoudsdiensten is tijdelijk niet bereikbaar vanwege onderhoud. Probeer het over een paar minuten opnieuw. Telefoon: 0164-652846." HTTP 503 status. | WordPress core maintenance mode (auto-enabled during updates). Custom `maintenance.php` overrides default. |
| Gravity Forms database table missing (plugin deactivated) | Gravity Forms shortcode renders as raw text: `[gravityform id="1"]`. | Health check monitors plugin status. UptimeRobot detects form page change. Immediate alert if forms break. |

### 16.5 Edge Case — Empty State Specification (Complete)

| Section | Empty State Behavior | Implementation |
|---|---|---|
| Client Logo Carousel (Homepage) | Hide entire section wrapper. No empty space, no placeholder text, no "Coming soon" message. | `$logos = get_theme_mod('hds_client_logos', []); if (empty($logos)) { return; }` |
| Testimonial Block (Homepage + Referenties) | Hide entire section wrapper. No empty carousel. No placeholder. | `$testimonials = new WP_Query(['post_type' => 'hds_testimonial', 'posts_per_page' => -1]); if (!$testimonials->have_posts()) { return; }` |
| Latest Blog Posts (Homepage) | Hide entire section wrapper. No "No posts yet" message. | `$posts = new WP_Query(['post_type' => 'post', 'posts_per_page' => 3]); if (!$posts->have_posts()) { return; }` |
| Blog Index (/kennisbank/) | Display: "Binnenkort verschijnen hier de eerste artikelen over schoonmaak, onderhoud en veiligheid." with CTA link to Contact page. | `if (!have_posts()) { /* render empty state message */ }` |
| Search Results | Display: "Geen resultaten gevonden voor '[search query]'. Probeer een andere zoekterm of neem contact met ons op." Links: Home, Diensten, Contact. | `search.php` template handles this via `if (!have_posts())`. |
| Vacatures Page | If no active vacancies (all `hds_is_active = false`): "Er zijn op dit moment geen openstaande vacatures. Stuur een open sollicitatie naar info@helderduidelijkschoon.nl." | `hds/job-listing` block render_callback returns empty state when query returns zero posts. |
| Referenties Page | If no testimonials and no logos: "Wij zijn trots op onze opdrachtgevers. Binnenkort leest u hier hun ervaringen." If only logos but no testimonials: show logos, hide testimonial section. If only testimonials but no logos: show testimonials, hide logo section. | Conditional rendering per sub-section on P13. |
| Downloads Page | If no PDFs in media library (migration failed or deferred): "Downloads zijn tijdelijk niet beschikbaar. Neem contact met ons op voor de algemene voorwaarden." Phone + email displayed. | Standard Page — content editor controls empty state message. |
| Vacancy Detail (individual card) | If `hds_application_email` is empty: use `info@helderduidelijkschoon.nl` as fallback. If `hds_deadline` is empty: omit deadline line. If `hds_location` is empty: "Standplaats: regio Bergen op Zoom / West-Brabant" (default service area). | `hds/job-listing` render_callback: fallback values per field. |
| Testimonial Card (individual) | If `hds_author_name` is empty but `hds_company_name` exists: show "Medewerker bij [Company]". If `hds_star_rating` is 0: omit star display. If `post_content` (testimonial text) is empty: hide entire card. | `hds/testimonial` render_callback: conditional field rendering. |

### 16.6 Content Acceptance

| # | Criterion | Pass Condition |
|---|---|---|
| AC-C01 | Homepage >= 300 words Dutch | Word count |
| AC-C02 | All 7 service pages >= 300 words Dutch | Word count per page |
| AC-C03 | Category landing pages >= 500 words | Word count per page |
| AC-C04 | No lorem ipsum or placeholder text | Full site text crawl |
| AC-C05 | All pages have unique title + meta description | Screaming Frog: zero empty, zero duplicate |
| AC-C06 | Phone and email correct on all pages | Manual verification |
| AC-C07 | KVK + BTW in footer (if MI-02/MI-03 provided) | Manual verification |
| AC-C08 | All legal pages published | Manual verification |

### 16.3 Technical Acceptance

| # | Criterion | Pass Condition |
|---|---|---|
| AC-T01 | All pages use correct template | Check WP admin Template dropdown |
| AC-T02 | Block Editor used for all content (no shortcodes in `post_content`) | Database query |
| AC-T03 | Zero PHP errors in debug.log | Check log after full crawl |
| AC-T04 | Zero JavaScript errors in browser console | Manual check on each page template |
| AC-T05 | reCAPTCHA v3 badge visible on Contact + Quote pages | Manual visual |
| AC-T06 | Privacy checkboxes unchecked by default | Manual check |
| AC-T07 | All forms have honeypot field | Check form HTML source |
| AC-T08 | File upload: server-side MIME validation | Test with renamed .exe as .pdf → rejected |
| AC-T09 | File upload: files renamed on upload | Check uploads directory |
| AC-T10 | HTTPS enforced + HSTS header present | securityheaders.com |
| AC-T11 | XML-RPC returns 403 | `curl -I /xmlrpc.php` |
| AC-T12 | Cloudflare cache bypass for WC pages | `CF-Cache-Status: BYPASS` on cart/checkout/account |
| AC-T13 | Staging: noindex + password-protected | Verified |
| AC-T14 | 404 page returns HTTP 404 status code | `curl -I /non-existent-page` |

### 16.4 Accessibility Acceptance

| # | Criterion | Pass Condition |
|---|---|---|
| AC-A01 | axe DevTools: zero critical + zero serious on all templates | axe scan |
| AC-A02 | Lighthouse Accessibility = 100 on all templates | Lighthouse |
| AC-A03 | WAVE: zero errors | WAVE scan |
| AC-A04 | Keyboard: all interactive elements focusable + operable | Manual tab-through |
| AC-A05 | Focus indicator visible on all elements | Manual tab-through |
| AC-A06 | Screen reader: forms announced correctly | NVDA / VoiceOver test |
| AC-A07 | Color contrast passes AA on all color combinations | WebAIM / axe |
| AC-A08 | 200% zoom: no content loss, no horizontal scroll | Manual browser zoom |
| AC-A09 | Touch targets >= 44×44px on mobile | Manual measurement |
| AC-A10 | `lang="nl-NL"` on every page | Check `<html>` element |

### 16.5 Performance Acceptance

| # | Criterion | Pass Condition |
|---|---|---|
| AC-P01 | PSI Mobile >= 90 on Home, 1 service page, 1 product page | PSI |
| AC-P02 | PSI Desktop >= 95 on same pages | PSI |
| AC-P03 | LCP < 2.5s | PSI / Lighthouse |
| AC-P04 | CLS < 0.1 | PSI / Lighthouse |
| AC-P05 | TTFB < 600ms | WebPageTest |

---

## 17. Requirement Mapping

Every feature in this Functional Specification is traceable to the Requirements Traceability Matrix (RTM-001). Below is the mapping of FS sections to RTM requirement IDs.

| FS Section | RTM Requirements | Stories |
|---|---|---|
| 4.1 Homepage | REQ-FR-013, REQ-CON-001, REQ-SEO-012, REQ-UIX-008..012, REQ-ACC-001..018 | E-CORE-01 |
| 4.2 Service Pages | REQ-FR-004..010, REQ-CON-002..008, REQ-SEO-001..007, REQ-SEO-026, REQ-ACC-001..018 | E-CORE-03..07 |
| 4.3 Category Landings | REQ-FR-011..012, REQ-CON-009..010, REQ-SEO-008..009 | E-CORE-08 |
| 4.4 About Pages | REQ-FR-014..015, REQ-CON-011..012, REQ-SEO-010..011 | E-SUPPORT-01..02 |
| 4.5 Referenties | REQ-FR-041..043, REQ-CON-013 | E-SUPPORT-03 |
| 4.6 Vacatures | REQ-FR-044..045, REQ-CON-014, REQ-SEO-013, REQ-ACC-006, A13 | E-SUPPORT-04 |
| 4.7 Downloads | REQ-MIG-006..008, REQ-CON-015 | E-SUPPORT-06 |
| 4.8 Contact | REQ-FR-001..003, REQ-CON-016, REQ-SEO-014, REQ-SEC-003..006, REQ-ACC-007 | E-CORE-09 |
| 4.9 Bedankt | REQ-FR-017, REQ-CON-029, REQ-SEO-022 | E-CORE-11 |
| 4.10 WooCommerce | REQ-FR-022..027, REQ-WC-001..012 | E-COMM-01..07 |
| 4.11 Search | REQ-FR-018, REQ-TR-032 (Relevanssi) | E-INFRA-02 |
| 4.12 Navigation | REQ-UIX-001..004, REQ-ACC-002, REQ-ACC-015, REQ-ACC-020 | E-INFRA-06 |
| 4.13 Header | REQ-UIX-001, REQ-ACC-002..003, REQ-ACC-015 | E-INFRA-06 |
| 4.14 Footer | REQ-UIX-004, REQ-ACC-002, REQ-CMP-011 (KVK/BTW) | E-INFRA-06 |
| 4.15 Forms | REQ-FR-001..003, REQ-FR-019..021, REQ-SEC-003..006 | E-CORE-09..10 |
| 4.16 Cookie Consent | REQ-CMP-002..004, REQ-CMP-010 | E-COMPLY-01 |
| 4.17 404 | REQ-FR-016, REQ-CON-028 | E-CORE-01 |
| 4.19 Legal Pages | REQ-FR-046..048, REQ-CON-019..022, REQ-CMP-001 | E-SUPPORT-05 |
| 5. User Flows | REQ-BR-001, REQ-BR-005, REQ-BR-008, REQ-BR-014 | All E-CORE + E-COMM |
| 6. Form Specs | REQ-FR-001..003, REQ-FR-019..021, REQ-SEC-003..006, REQ-ACC-007 | E-CORE-09..10, E-SUPPORT-04 |
| 7. Search | REQ-FR-018 | E-INFRA-02 |
| 9. Error Handling | REQ-FR-016, REQ-FR-018, REQ-UIX-012 (empty states) | E-CORE-01, E-INFRA-06 |
| 11. SEO | REQ-SEO-001..028, REQ-SEO-022..023 (sitemap) | E-SEO-01..10 |
| 12. WooCommerce | REQ-WC-001..012, REQ-PERF-012 (cache bypass) | E-COMM-01..07 |
| 13. Security | REQ-SEC-001..016 | E-INFRA, E-COMPLY |
| 14. Accessibility | REQ-ACC-001..020 | E-COMPLY-07 |
| 15. Performance | REQ-PERF-001..014 | E-INFRA, E-QA |
| 16. Acceptance | All RTM AC IDs | All stories |

**Coverage:** All 274 RTM requirements are represented in this Functional Specification. Zero orphan requirements.

Full traceability details (requirement → story → AC → test case) are available in RTM-001.

---

**This Functional Specification is internally consistent with all Sprint 1 documents (MPS-001, SAD-001, ADR-001, BKLG-001, ARR-001, RS-01 through RS-08, SRC-01 through SRC-08, RTM-001) and the completed Epic 1 and Epic 2 implementations. All assumptions are explicitly marked. Sprint 2 development may proceed with this specification as the definitive functional reference.**

**END OF FUNCTIONAL SPECIFICATION — Version 1.0.0**
