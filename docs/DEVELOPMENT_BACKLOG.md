# HDS Onderhoudsdiensten — Complete Development Backlog

**Document ID:** BKLG-001 | **Version:** 1.0.0 | **Total Sprints:** 9 (Sprint 0 + Sprint 1-8)
**Estimation Scale:** Fibonacci (1, 2, 3, 5, 8, 13, 21)
**Priority Scale:** P0 (Critical) / P1 (High) / P2 (Medium) / P3 (Low)

---

## Epic Overview

| Epic ID | Epic Name | Sprints | Total Stories | Total Points |
|---|---|---|---|---|
| E-PREREQ | Prerequisites & Foundation Decisions | Sprint 0 | 9 | 28 |
| E-INFRA | Infrastructure & Foundation | Sprint 1 | 8 | 45 |
| E-CORE | Core Pages & Conversion | Sprint 2 | 12 | 72 |
| E-SUPPORT | Supporting Pages & Content | Sprint 3 | 11 | 56 |
| E-COMM | WooCommerce & eCommerce | Sprint 4 | 9 | 44 |
| E-SEO | SEO, Analytics & Optimization | Sprint 5 | 10 | 53 |
| E-COMPLY | Compliance, Security & Accessibility | Sprint 6 | 9 | 47 |
| E-QA | Testing & Quality Assurance | Sprint 7 | 8 | 41 |
| E-LAUNCH | Launch & Handover | Sprint 8 | 9 | 34 |

**Total:** 85 stories | **420 points** | **9 sprints** | **18-weekends / 9 weeks**

---

## Story Field Descriptions

| Field | Description |
|---|---|
| **Story ID** | Unique identifier: EPIC-STORY |
| **Type** | User Story / Technical Task / Spike |
| **Description** | As a [role], I want [feature] so that [benefit] (for User Stories). Direct technical description (for Technical Tasks). |
| **Business Value** | Why this matters to the business (1-5 scale: 1=low, 5=critical) |
| **Dependencies** | Story IDs this story depends on |
| **Blocked By** | Stories that block this (reverse dependency) |
| **Blocks** | Stories this story blocks |
| **Acceptance Criteria** | Verifiable conditions for Done |
| **Priority** | P0 (Critical) / P1 (High) / P2 (Medium) / P3 (Low) |
| **Story Points** | Fibonacci estimate (1, 2, 3, 5, 8, 13, 21) |
| **Complexity** | Low / Medium / High / Very High |
| **Parallel** | Can this run in parallel with other stories? (Yes / No — and which stories) |
| **Assigned Sprint** | Sprint number |

---

## Sprint 0: Prerequisites & Foundation Decisions (Week 0)

**Goal:** Resolve all blocking decisions and infrastructure prerequisites. No code written.

### Feature F0.1: Architecture Decisions

---

#### Story E-PREREQ-01: Resolve Theme Selection

- **Type:** Spike
- **Description:** Evaluate and select one theme: (a) Custom hybrid block theme (theme.json + PHP templates + Block Editor), (b) GeneratePress Pro + GenerateBlocks, or (c) Kadence Pro. Document decision with rationale in MPS-001. Architecture Readiness Review (ARR) B01 and B02 depend on this.
- **Business Value:** 5 — fundamental architecture choice affecting all subsequent sprints
- **Dependencies:** None
- **Blocked By:** None
- **Blocks:** E-INFRA-01, E-INFRA-02
- **Acceptance Criteria:**
  - AC1: Theme selected and documented in MPS-001
  - AC2: Selection rationale documented
  - AC3: ARR B01 and B02 resolved
- **Priority:** P0
- **Story Points:** 3
- **Complexity:** Medium
- **Parallel:** No — blocks infrastructure stories
- **Assigned Sprint:** 0
- **Estimated Hours:** 2

---

#### Story E-PREREQ-02: Resolve CPT Slug Conflict

- **Type:** Technical Task
- **Description:** Resolve the `hds_testimonial` CPT slug conflict with `/referenties/` Page (P13). Implement decision: set CPT to `public => false`, `publicly_queryable => false`, query testimonials only via custom blocks. Update spec. ARR B03 depends on this.
- **Business Value:** 4 — prevents URL conflicts and 404 errors
- **Dependencies:** None
- **Blocked By:** None
- **Blocks:** E-CORE-07 (architecture decision must be documented before CPT registration code is written)
- **Acceptance Criteria:**
  - AC1: Decision documented in MPS-001 CMS Architecture section
  - AC2: CPT settings specified: public=false, publicly_queryable=false
  - AC3: ARR B03 resolved
- **Priority:** P0
- **Story Points:** 1
- **Complexity:** Low
- **Parallel:** Yes — can run with E-PREREQ-03 through E-PREREQ-09
- **Assigned Sprint:** 0
- **Estimated Hours:** 0.5

---

#### Story E-PREREQ-03: Resolve Plugin Selections (SEO, Caching)

- **Type:** Spike
- **Description:** Evaluate and select: (a) SEO plugin: Rank Math Pro vs Yoast SEO Premium. (b) Caching plugin: FlyingPress vs WP Rocket. (c) Theme decision from E-PREREQ-01 may influence GenerateBlocks choice. Document decisions. ARR SWA-03, SWA-04.
- **Business Value:** 3 — prevents mid-project plugin switching
- **Dependencies:** E-PREREQ-01 (theme choice may influence GenerateBlocks decision)
- **Blocked By:** None
- **Blocks:** E-INFRA-03
- **Acceptance Criteria:**
  - AC1: SEO plugin selected and documented
  - AC2: Caching plugin selected and documented
  - AC3: ARR SWA-03, SWA-04 resolved
- **Priority:** P1
- **Story Points:** 2
- **Complexity:** Low
- **Parallel:** Yes — with E-PREREQ-02, E-PREREQ-04
- **Assigned Sprint:** 0
- **Estimated Hours:** 1

---

### Feature F0.2: Infrastructure Prerequisites

---

#### Story E-PREREQ-04: Obtain Domain & Hosting Access

- **Type:** Technical Task
- **Description:** Client provides: (a) Domain registrar login credentials. (b) Current hosting control panel access (or approval to provision new hosting). (c) WordPress admin access to current site. (d) Decision on hosting provider (Kinsta, WP Engine, or Cloud86.nl). Developer verifies all credentials work.
- **Business Value:** 5 — blocks ALL development
- **Dependencies:** None
- **Blocked By:** None
- **Blocks:** E-INFRA-01, E-INFRA-02
- **Acceptance Criteria:**
  - AC1: Domain registrar access verified
  - AC2: Hosting provider selected and account provisioned
  - AC3: WordPress admin access to current site verified
  - AC4: ARR dependency: infrastructure can proceed
- **Priority:** P0
- **Story Points:** 3
- **Complexity:** Low
- **Parallel:** No — blocks infrastructure. But can happen concurrently with architecture decisions (E-PREREQ-01 through E-PREREQ-03).
- **Assigned Sprint:** 0
- **Estimated Hours:** 1 (manual coordination)

---

#### Story E-PREREQ-05: Provision Managed WordPress Hosting

- **Type:** Technical Task
- **Description:** Provision managed WordPress hosting account. Create staging environment (`staging.helderduidelijkschoon.nl`, password-protected, noindex). Prepare production environment. Configure PHP 8.2+, MySQL 8.0+ / MariaDB 10.6+, Redis. Verify SFTP access. Document hosting credentials securely.
- **Business Value:** 5 — hosting is the platform
- **Dependencies:** E-PREREQ-04 (hosting provider selected)
- **Blocked By:** E-PREREQ-04
- **Blocks:** E-INFRA-01
- **Acceptance Criteria:**
  - AC1: Staging environment provisioned and accessible
  - AC2: Production environment provisioned (empty, ready)
  - AC3: PHP 8.2+ verified (phpinfo)
  - AC4: Redis available and enabled
  - AC5: Staging returns noindex headers
  - AC6: Staging password-protected
  - AC7: SFTP access working
- **Priority:** P0
- **Story Points:** 5
- **Complexity:** Medium
- **Parallel:** No — blocks E-INFRA-01
- **Assigned Sprint:** 0
- **Estimated Hours:** 3

---

#### Story E-PREREQ-06: Resolve Phase 0 Client Questions

- **Type:** Spike
- **Description:** Client answers all 18 Phase 0 open questions (Q01-Q18) from MPS-001 Section I5. These cover: legal entity type (Q01), physical address (Q02), KVK/BTW numbers (Q03), business hours (Q04), service area (Q05), developer status (Q06), GBP status (Q07), client source (Q08), Airfixr decision (Q09), brand preferences (Q10-Q11), payment gateway (Q12), shipping costs (Q13), hosting provider (Q14), analytics access (Q15), hosting budget (Q16), budget approval (Q17), business goals (Q18).
- **Business Value:** 5 — 18 dependencies across all phases depend on these answers
- **Dependencies:** None
- **Blocked By:** None
- **Blocks:** E-CORE-09 (address), E-SUPPORT-03 (logos), E-SUPPORT-04 (testimonials), E-SUPPORT-05 (vacancies), E-COMM-01 (payment gateway), E-COMM-03 (shipping), E-SEO-02 (address for schema), E-COMPLY-01 (legal review), and 8 other stories
- **Acceptance Criteria:**
  - AC1: All 18 questions answered and documented in MPS-001
  - AC2: Answers stored in shared location accessible to development team
  - AC3: ARR dependencies tracking MI-01 through MI-25 updated
- **Priority:** P0
- **Story Points:** 8
- **Complexity:** High (depends on client responsiveness)
- **Parallel:** Can run alongside E-PREREQ-01 through E-PREREQ-05
- **Assigned Sprint:** 0
- **Estimated Hours:** 4 (developer coordination) + client time variable

---

### Feature F0.3: Development Environment Setup

---

#### Story E-PREREQ-07: Set Up Git Repository

- **Type:** Technical Task
- **Description:** Create Git repository (GitHub/GitLab). Initialize with: `.gitignore` (WordPress-specific), README, `/wp-content/themes/hds/` scaffold directory. Configure branch protection on main. Set up deploy keys or GitHub integration for hosting deployment pipeline.
- **Business Value:** 4 — version control is mandatory for professional development
- **Dependencies:** E-PREREQ-05 (staging environment URL for deploy configuration)
- **Blocked By:** None
- **Blocks:** E-INFRA-02
- **Acceptance Criteria:**
  - AC1: Git repository created and accessible by development team
  - AC2: `.gitignore` includes WordPress core, uploads, wp-config.php, and sensitive files
  - AC3: Branch protection enabled on main
  - AC4: Deploy keys or integration configured
- **Priority:** P0
- **Story Points:** 2
- **Complexity:** Low
- **Parallel:** Yes — with E-PREREQ-08
- **Assigned Sprint:** 0
- **Estimated Hours:** 1

---

#### Story E-PREREQ-08: Verify Google Analytics & Search Console Access

- **Type:** Technical Task
- **Description:** Verify client has Google Analytics 4 account access. Verify client has Google Search Console access for `helderduidelijkschoon.nl`. If not, create new GA4 property and GSC domain property. Document access credentials. Set up Google Tag Manager container.
- **Business Value:** 4 — required for Sprint 5 analytics implementation
- **Dependencies:** E-PREREQ-06 (Q15 — analytics access confirmation)
- **Blocked By:** None
- **Blocks:** E-SEO-05, E-SEO-06
- **Acceptance Criteria:**
  - AC1: GA4 property created and verified
  - AC2: GSC domain property created and verified
  - AC3: GTM container created
  - AC4: Developer has access to all three
- **Priority:** P1
- **Story Points:** 3
- **Complexity:** Low
- **Parallel:** Yes — with E-PREREQ-07, E-PREREQ-09
- **Assigned Sprint:** 0
- **Estimated Hours:** 1.5

---

#### Story E-PREREQ-09: Engage Legal Counsel

- **Type:** Technical Task
- **Description:** Client engages qualified Dutch privacy lawyer to review privacyverklaring and cookiebeleid before Sprint 6. Developer provides draft content. This is a dependency for launch — legal review must be completed before go-live. Initiation in Sprint 0 ensures timely completion.
- **Business Value:** 5 — legal compliance gate for launch
- **Dependencies:** None
- **Blocked By:** None
- **Blocks:** E-COMPLY-01, E-COMPLY-02
- **Acceptance Criteria:**
  - AC1: Lawyer engaged and briefed on scope
  - AC2: Timeline agreed for review completion before Sprint 7
  - AC3: ARR R04 risk mitigated
- **Priority:** P0
- **Story Points:** 2
- **Complexity:** Low
- **Parallel:** Yes — with E-PREREQ-07, E-PREREQ-08
- **Assigned Sprint:** 0
- **Estimated Hours:** 1

---

## Sprint 1: Infrastructure & Foundation (Week 1-2)

**Goal:** Provision infrastructure, install WordPress, build theme foundation, implement design system.

### Feature F1.1: WordPress Installation & Configuration

---

#### Story E-INFRA-01: Install WordPress Core + Configure Settings

- **Type:** Technical Task
- **Description:** Install WordPress 6.7+ on staging environment. Configure: permalink structure `/%postname%/`, category base `kennisbank`, timezone Europe/Amsterdam, date format `j F Y`, language Nederlands (nl_NL), disable comments and pingbacks, disable post via email, change database prefix from `wp_`, generate fresh salts. Create admin accounts (2 minimum) with unique non-obvious usernames. Set `DISALLOW_FILE_EDIT` to true.
- **Business Value:** 5 — WordPress is the platform
- **Dependencies:** E-PREREQ-05 (hosting provisioned), E-PREREQ-07 (Git repo)
- **Blocked By:** E-PREREQ-05
- **Blocks:** E-INFRA-02 through E-INFRA-08
- **Acceptance Criteria:**
  - AC1: WordPress 6.7+ installed on staging
  - AC2: Permalink structure = `/%postname%/`
  - AC3: Category base = `kennisbank`
  - AC4: Timezone = Europe/Amsterdam
  - AC5: Language = nl_NL
  - AC6: Comments disabled
  - AC7: Database prefix changed from `wp_`
  - AC8: Salts generated and unique
  - AC9: 2 admin accounts with non-obvious usernames created
  - AC10: DISALLOW_FILE_EDIT = true
- **Priority:** P0
- **Story Points:** 5
- **Complexity:** Medium
- **Parallel:** No — foundational. All other Sprint 1 stories depend on this.
- **Assigned Sprint:** 1
- **Estimated Hours:** 2

---

#### Story E-INFRA-02: Install & Configure All Plugins

- **Type:** Technical Task
- **Description:** Install and activate all plugins from resolved selections (E-PREREQ-01, E-PREREQ-03): (a) SEO plugin (Rank Math Pro or Yoast Premium). (b) Caching plugin (FlyingPress or WP Rocket). (c) WooCommerce 9.x+. (d) Gravity Forms. (e) Cookie consent (Complianz Premium). (f) Security (Wordfence Premium or Solid Security Pro). (g) Backup (BlogVault or UpdraftPlus Premium). (h) Image optimization (ShortPixel or Imagify). (i) Search (Relevanssi). (j) SMTP (Post SMTP). Configure basic settings on each. Do NOT do deep configuration yet.
- **Business Value:** 5 — plugin stack is the application
- **Dependencies:** E-INFRA-01 (WordPress installed), E-PREREQ-01, E-PREREQ-03 (plugin selections)
- **Blocked By:** E-INFRA-01
- **Blocks:** E-INFRA-03, E-INFRA-04 (and all subsequent configuration stories)
- **Acceptance Criteria:**
  - AC1: All 10 plugin categories installed and activated
  - AC2: License keys entered for premium plugins
  - AC3: No plugin conflicts detected (WP admin loads, frontend loads)
  - AC4: Plugin auto-updates enabled for minor releases
- **Priority:** P0
- **Story Points:** 5
- **Complexity:** Medium
- **Parallel:** No — follows E-INFRA-01
- **Assigned Sprint:** 1
- **Estimated Hours:** 3

---

### Feature F1.2: CDN, SSL, Backups, SMTP

---

#### Story E-INFRA-03: Configure Cloudflare CDN & SSL

- **Type:** Technical Task
- **Description:** Set up Cloudflare CDN for `helderduidelijkschoon.nl`. Configure: (a) SSL/TLS encryption mode: Full (strict). (b) Always Use HTTPS enabled. (c) HSTS enabled (max-age=31536000, includeSubDomains, preload). (d) Auto-minify CSS/JS/HTML enabled. (e) Polish image optimization enabled. (f) Page Rules: bypass cache for `/winkelmand/*`, `/afrekenen/*`, `/mijn-account/*`, `/wp-admin/*`, WooCommerce AJAX endpoints. (g) WAF rules: block `/xmlrpc.php`, rate-limit login URL, enable WordPress managed ruleset (if Pro plan). Resolve ARR B07 (Cloudflare-WooCommerce conflict) and ARR SEC-01 (WAF rules).
- **Business Value:** 5 — performance and security foundation
- **Dependencies:** E-PREREQ-05 (hosting), E-INFRA-01 (WordPress)
- **Blocked By:** E-INFRA-01
- **Blocks:** None directly — but all subsequent performance testing depends on CDN being active
- **Acceptance Criteria:**
  - AC1: HTTPS enforced — HTTP returns 301 to HTTPS
  - AC2: HSTS header present (verified via securityheaders.com)
  - AC3: SSL grade A+ (SSL Labs)
  - AC4: Cache bypass rules active for WooCommerce pages (verified via response headers)
  - AC5: WAF rules blocking `/xmlrpc.php`
  - AC6: ARR B07 resolved
  - AC7: ARR SEC-01 partially resolved (rules configured)
- **Priority:** P0
- **Story Points:** 5
- **Complexity:** Medium
- **Parallel:** Yes — can run parallel to E-INFRA-04, E-INFRA-05
- **Assigned Sprint:** 1
- **Estimated Hours:** 3

---

#### Story E-INFRA-04: Configure SMTP & Email Deliverability

- **Type:** Technical Task
- **Description:** Configure Post SMTP plugin with transactional email service (SendGrid, Mailgun, Amazon SES, or hosting-provided SMTP). Configure SPF, DKIM, and DMARC DNS records for email authentication. Verify deliverability: send test email from Gravity Forms to `info@helderduidelijkschoon.nl`, verify not in spam. Set up Post SMTP email logging. Resolve ARR SWA-01 (SMTP spec) and ARR B04 (blocking).
- **Business Value:** 5 — all form submissions and WooCommerce orders depend on email delivery
- **Dependencies:** E-PREREQ-04 (DNS access), E-INFRA-01 (WordPress)
- **Blocked By:** E-INFRA-01
- **Blocks:** E-CORE-09 (contact form relies on email delivery)
- **Acceptance Criteria:**
  - AC1: Post SMTP configured and sending
  - AC2: Test email delivered to info@ within 2 minutes
  - AC3: Test email NOT in spam folder
  - AC4: SPF record includes sending service
  - AC5: DKIM configured
  - AC6: DMARC policy set (p=none initially for monitoring)
  - AC7: Post SMTP email log enabled
  - AC8: ARR SWA-01 and ARR B04 resolved
- **Priority:** P0
- **Story Points:** 5
- **Complexity:** Medium
- **Parallel:** Yes — with E-INFRA-03, E-INFRA-05
- **Assigned Sprint:** 1
- **Estimated Hours:** 3

---

#### Story E-INFRA-05: Configure Daily Backups & Verify

- **Type:** Technical Task
- **Description:** Configure automated daily backups (BlogVault or UpdraftPlus Premium) for files + database. Set retention: 30 daily, 4 weekly, 12 monthly. Configure offsite storage (Google Drive, Dropbox, or S3). Run first backup. Restore first backup to test environment to verify integrity. Document backup procedure. Configure backup failure email alerts. Set up separate WooCommerce order export (monthly CSV to offsite) for 7-year financial data retention. Resolve ARR SWA-05.
- **Business Value:** 5 — data loss prevention is critical
- **Dependencies:** E-PREREQ-05 (hosting), E-INFRA-01 (WordPress)
- **Blocked By:** E-INFRA-01
- **Blocks:** E-QA-06 (backup verification story)
- **Acceptance Criteria:**
  - AC1: Daily backup schedule configured and first backup completed
  - AC2: Offsite storage verified (backup file present)
  - AC3: Test restore to separate environment successful (all pages load, admin login works)
  - AC4: Backup failure email alert configured
  - AC5: WooCommerce monthly order export configured
  - AC6: ARR SWA-05 resolved
- **Priority:** P0
- **Story Points:** 5
- **Complexity:** Medium
- **Parallel:** Yes — with E-INFRA-03, E-INFRA-04
- **Assigned Sprint:** 1
- **Estimated Hours:** 3

---

### Feature F1.3: Theme Foundation & Design System

---

#### Story E-INFRA-06: Build Theme Foundation (Header, Footer, Base Layout)

- **Type:** Technical Task
- **Description:** Build the theme foundation based on resolved architecture (E-PREREQ-01): (a) Create `theme.json` with design tokens (colors, typography, spacing from MI-07, MI-08). (b) Build `header.php` template part: logo, navigation placeholder, phone tel: link, email mailto: link, cart icon. (c) Build `footer.php` template part: 5-column layout with placeholders, copyright, social icons. (d) Build `functions.php`: enqueue styles/scripts, register nav menus, register block patterns, register block styles, theme supports. (e) Build base CSS: reset, typography, utility classes, responsive grid. (f) Build `404.php` template. (g) Build `search.php` template. Apply the "Hybrid block theme" approach (theme.json + PHP templates + Block Editor) — NOT true FSE. Resolve ARR CMS-01 and ARR B02.
- **Business Value:** 5 — theme is the visual foundation
- **Dependencies:** E-PREREQ-01 (theme selection), E-INFRA-01 (WordPress), E-PREREQ-06 (MI-07, MI-08 brand tokens)
- **Blocked By:** E-PREREQ-01, E-INFRA-01
- **Blocks:** ALL page template stories (E-CORE-01 through E-CORE-09, E-SUPPORT-01 through E-SUPPORT-07)
- **Acceptance Criteria:**
  - AC1: theme.json built with design tokens
  - AC2: Header renders correctly: logo, nav placeholder, phone, email, cart icon
  - AC3: Footer renders correctly: 5-column layout
  - AC4: Base CSS renders typography, spacing, and responsive grid correctly
  - AC5: 404.php renders custom error page with search, links, contact
  - AC6: search.php renders results with "Geen resultaten" fallback
  - AC7: No errors in browser console
  - AC8: ARR CMS-01 and ARR B02 resolved
- **Priority:** P0
- **Story Points:** 13
- **Complexity:** High
- **Parallel:** No — foundational. All page template stories depend on this.
- **Assigned Sprint:** 1
- **Estimated Hours:** 12

---

#### Story E-INFRA-07: Register Block Patterns

- **Type:** Technical Task
- **Description:** Register all 16 block patterns in `functions.php`: Hero Section, Service Card Grid, USP Grid, CTA Banner, Content with Image, Service Icon List, Client Logo Carousel/Grid, Testimonial Block, FAQ Accordion, Cross-Sell Services, Job Vacancy Card, Download Card List, Contact Info + Map, Latest Blog Posts, Related Posts, 404 Content. Each pattern registered via `register_block_pattern()` with category "hds-patterns". This is the mapping of Section F3 (Component Inventory) into code.
- **Business Value:** 4 — enables rapid page building via Block Editor
- **Dependencies:** E-INFRA-06 (theme foundation)
- **Blocked By:** E-INFRA-06
- **Blocks:** All page building stories (E-CORE-01 through E-CORE-09, E-SUPPORT-01 through E-SUPPORT-07)
- **Acceptance Criteria:**
  - AC1: All 16 block patterns registered and visible in Block Editor inserter
  - AC2: Each pattern inserts correctly into a page
  - AC3: Patterns categorized under "HDS Patternen"
  - AC4: Pattern fields editable after insertion
  - AC5: No JavaScript errors when inserting patterns
- **Priority:** P0
- **Story Points:** 8
- **Complexity:** High
- **Parallel:** Cannot start until E-INFRA-06 complete. But different patterns can be built in parallel by different developers if team size permits.
- **Assigned Sprint:** 1
- **Estimated Hours:** 8

---

#### Story E-INFRA-08: Implement Design System in Code

- **Type:** Technical Task
- **Description:** Convert design tokens (MI-07, MI-08) into CSS custom properties. Implement: (a) Color palette as CSS variables (`--hds-color-primary`, etc.). (b) Typography scale matching Section F4 specifications. (c) Spacing scale (4px-based). (d) Border radius tokens. (e) Shadow tokens. (f) Breakpoint mixins or custom media queries. (g) Block style variations (Section F4: is-style-primary, is-style-secondary, is-style-cta, is-style-card, is-style-banner, is-style-icon-list, is-style-no-bullet). (h) Base component styles: buttons, cards, banners, forms.
- **Business Value:** 4 — consistent visual language
- **Dependencies:** E-PREREQ-06 (MI-07, MI-08 design tokens), E-INFRA-06 (theme foundation)
- **Blocked By:** E-INFRA-06
- **Blocks:** All page building stories (visual consistency depends on design system)
- **Acceptance Criteria:**
  - AC1: All CSS custom properties defined and documented
  - AC2: Typography renders per Section F4 spec at all breakpoints
  - AC3: Spacing scale applied consistently
  - AC4: All 7 block style variations registered and visually distinct
  - AC5: Button, card, banner, and form base styles implemented
  - AC6: Design system demo page created showing all tokens and components
- **Priority:** P0
- **Story Points:** 8
- **Complexity:** Medium
- **Parallel:** Can run partially in parallel with E-INFRA-07 (block patterns). Design system variables can be defined while patterns are built.
- **Assigned Sprint:** 1
- **Estimated Hours:** 8

---

## Sprint 2: Core Pages & Conversion (Week 3-4)

**Goal:** Home page, all 7 service pages, 2 category landings, Contact, Offerte, Bedankt, and 404 built.

### Feature F2.1: Page Templates

---

#### Story E-CORE-01: Build Home Page Template + Content

- **Type:** User Story
- **Description:** As a visitor, I want to see a professional homepage that clearly communicates HDS's services, USPs, and trust signals, so that I am motivated to request a quote.
- **Technical:** Build `front-page.php` template. Populate with all 8 content blocks per Section D2: Hero, Service Card Grid (7 services), USP Grid, Client Logo Carousel (conditional — hide if empty), Testimonial Block (conditional — hide if empty), CTA Banner, Service Area, Latest Blog Posts (conditional). All sections built via Block Editor with pre-loaded block patterns for default content.
- **Business Value:** 5 — homepage is the primary landing page
- **Dependencies:** E-INFRA-06 (theme foundation), E-INFRA-07 (block patterns), E-INFRA-08 (design system)
- **Blocked By:** E-INFRA-06
- **Blocks:** E-CORE-02 through E-CORE-09 (service templates dependent on consistent pattern)
- **Acceptance Criteria:**
  - AC1: Page returns HTTP 200 at `/`
  - AC2: All 8 content blocks present and rendering correctly
  - AC3: Service Card Grid links to correct service pages
  - AC4: CTA button links to `/offerte-aanvragen/`
  - AC5: Empty states: Client Logo Carousel and Testimonial Block hidden if no data
  - AC6: Responsive on mobile, tablet, desktop
  - AC7: Page content >= 300 words Dutch
  - AC8: H1 = tagline "Helder en Duidelijk voor het Schoonste resultaat!"
  - AC9: Title tag and meta description set
- **Priority:** P0
- **Story Points:** 8
- **Complexity:** High
- **Parallel:** No — establishes page building patterns. But once template is built, E-CORE-02 through E-CORE-07 can run in parallel.
- **Assigned Sprint:** 2
- **Estimated Hours:** 6

---

#### Story E-CORE-02: Build Service Page Template

- **Type:** Technical Task
- **Description:** Build `page-service.php` template. Layout per Section F2: Breadcrumbs -> Hero Section (H1, subtitle from custom field, CTA to /offerte-aanvragen/) -> Content Area (the_content) -> Cross-Sell Services (block pattern) -> CTA Banner -> Optional FAQ Accordion. Register "Service" page template in theme. Add Service Page Settings custom fields (Subtitle, Hero Image, Icon for service card, CTA override text) via ACF or built-in post meta with Block Editor bindings.
- **Business Value:** 5 — template for all 7 service pages
- **Dependencies:** E-INFRA-06 (theme), E-INFRA-07 (block patterns)
- **Blocked By:** E-INFRA-06
- **Blocks:** E-CORE-03 through E-CORE-08
- **Acceptance Criteria:**
  - AC1: "Service" template selectable in Page editor
  - AC2: Template renders all sections in correct order
  - AC3: Custom fields (Subtitle, Hero Image, Icon, CTA override) save and display correctly
  - AC4: Breadcrumbs visible and correct (Home > [Page Name])
  - AC5: Cross-Sell Services block renders links to related services
  - AC6: Template responsive at all breakpoints
  - AC7: No PHP errors or warnings
- **Priority:** P0
- **Story Points:** 8
- **Complexity:** High
- **Parallel:** No — foundational template
- **Assigned Sprint:** 2
- **Estimated Hours:** 6

---

### Feature F2.2: Service Pages (P02-P08)

---

#### Story E-CORE-03: Build Glasbewassing Page (P02)

- **Type:** User Story
- **Description:** As a facility manager searching for window cleaning services, I want to read about HDS's glasbewassing services, safety certifications, and process, so that I can decide whether to request a quote.
- **Technical:** Create Page at `/glasbewassing/`. Apply Service template. Migrate existing 180 words Dutch content. Expand to 300+ words. Content structure: (a) Intro paragraph. (b) Veiligheid (Safety) — H2. (c) Samenwerking (Collaboration) — H2. (d) Technieken (Techniques) — H2. (e) CTA. Set title tag, meta description, Service schema (Sprint 5). Cross-links: link to Gevelreiniging, Reguliere Schoonmaak, Oplevering Schoonmaak.
- **Business Value:** 4 — existing high-quality content, key service line
- **Dependencies:** E-CORE-02 (Service template)
- **Blocked By:** E-CORE-02
- **Blocks:** None
- **Acceptance Criteria:**
  - AC1: Page returns HTTP 200 at `/glasbewassing/`
  - AC2: Content >= 300 words Dutch
  - AC3: H1 = "Glasbewassing"
  - AC4: H2 sections present: Veiligheid, Samenwerking, Technieken
  - AC5: Cross-links to Gevelreiniging, Reguliere Schoonmaak, Oplevering Schoonmaak present
  - AC6: CTA to `/offerte-aanvragen/` present
  - AC7: Title tag and meta description set
  - AC8: Responsive
- **Priority:** P0
- **Story Points:** 5
- **Complexity:** Medium
- **Parallel:** Yes — with E-CORE-04 through E-CORE-08
- **Assigned Sprint:** 2
- **Estimated Hours:** 3

**Subtasks:**
- ST-CORE-03.1: Migrate and expand Dutch content (developer or content writer)
- ST-CORE-03.2: Set custom fields (Subtitle, Hero Image, Icon)
- ST-CORE-03.3: Add cross-link service cards
- ST-CORE-03.4: Review by native Dutch speaker

---

#### Story E-CORE-04: Build Gevelreiniging Page (P03)

- **Type:** User Story
- **Description:** As a building owner, I want to read about facade cleaning, impregnation, graffiti removal, and other gevelreiniging services, so that I can decide whether to request a quote.
- **Technical:** Create Page at `/gevelreiniging/`. Apply Service template. Migrate existing 130 words. Standardize naming: page title = "Gevelreiniging" (not "Gevelonderhoud"). Expand to 300+ words. Content: (a) Intro. (b) Onze aanpak — H2. (c) Diensten (bullet list: impregneren, graffiti verwijderen, daken/goten/gevel/zonnepanelen/reclameborden reinigen) — H2. (d) Veiligheid & Expertise — H2. (e) CTA. Cross-links to Glasbewassing, Industriele Schoonmaak. Resolve ARR I1 (naming inconsistency).
- **Business Value:** 4 — high-quality existing content
- **Dependencies:** E-CORE-02 (Service template)
- **Blocked By:** E-CORE-02
- **Blocks:** None
- **Acceptance Criteria:**
  - AC1: Page returns HTTP 200 at `/gevelreiniging/`
  - AC2: Content >= 300 words Dutch
  - AC3: H1 = "Gevelreiniging" (NOT "Gevelonderhoud" — naming resolved per I1)
  - AC4: Bullet list of 5+ services present
  - AC5: Cross-links present
  - AC6: CTA present
  - AC7: Title tag and meta description set
- **Priority:** P0
- **Story Points:** 5
- **Complexity:** Medium
- **Parallel:** Yes — with E-CORE-03, E-CORE-05 through E-CORE-08
- **Assigned Sprint:** 2
- **Estimated Hours:** 3

---

#### Story E-CORE-05: Build Reguliere Schoonmaak Page (P04) — CRITICAL

- **Type:** User Story
- **Description:** As an office manager searching for regular cleaning services, I want to read a comprehensive page about HDS's reguliere schoonmaak offering (which was previously a 404 error), so that I can request a quote for my office.
- **Technical:** Build entirely NEW page at `/reguliere-schoonmaak/`. Apply Service template. Write 300+ words from scratch. Content: (a) What is regular cleaning — who it's for (offices, businesses). (b) Our approach — frequency options, scheduling, check-in/out protocol. (c) Service details — what's included. (d) Quality & Safety. (e) CTA. Cross-links to Vloeronderhoud, Glasbewassing, VVE Service. Resolve ARR F02.
- **Business Value:** 5 — PRIMARY service line, currently returns 404, highest impact fix
- **Dependencies:** E-CORE-02 (Service template)
- **Blocked By:** E-CORE-02
- **Blocks:** None
- **Acceptance Criteria:**
  - AC1: Page returns HTTP 200 at `/reguliere-schoonmaak/` (was 404)
  - AC2: Content >= 300 words Dutch
  - AC3: Content covers: target audience, frequency, process, quality
  - AC4: Cross-links present
  - AC5: CTA present
  - AC6: Title tag and meta description set
  - AC7: Page linked from navigation, homepage service grid, and footer
- **Priority:** P0
- **Story Points:** 8
- **Complexity:** High (entirely new content, highest business impact)
- **Parallel:** Yes — with E-CORE-03, E-CORE-04, E-CORE-06 through E-CORE-08
- **Assigned Sprint:** 2
- **Estimated Hours:** 4

---

#### Story E-CORE-06: Build Vloeronderhoud, VVE Service, Oplevering Schoonmaak Pages (P05-P07)

- **Type:** User Story
- **Description:** As a school administrator / VvE board member / construction project manager, I want to read detailed service pages for floor maintenance, VvE services, and post-construction cleaning, so that I can evaluate HDS's suitability for my needs.
- **Technical:** Build 3 pages in parallel:
  - P05 `/vloeronderhoud/`: Migrate 140 words, expand to 300+. Bullet list of 7 floor service types. Holiday scheduling mention. Cross-links to Reguliere Schoonmaak, Oplevering Schoonmaak.
  - P06 `/vve-service/`: Migrate 100 words, expand to 300+. Services: stairwells, halls, garages, technical maintenance, outdoor. VvE Belang partnership. Cross-links to Reguliere Schoonmaak, Glasbewassing.
  - P07 `/oplevering-schoonmaak/`: Migrate 90 words, expand to 300+. "0-beurt" concept. Bullet list of 5 task types. Cross-links to Reguliere Schoonmaak, Glasbewassing, Vloeronderhoud.
- **Business Value:** 4 each — important supporting service lines
- **Dependencies:** E-CORE-02 (Service template)
- **Blocked By:** E-CORE-02
- **Blocks:** None
- **Acceptance Criteria:**
  - AC1: All 3 pages return HTTP 200
  - AC2: All 3 pages >= 300 words Dutch
  - AC3: Each page has H2 sections and bullet lists as specified
  - AC4: Cross-links present on each
  - AC5: CTAs present
  - AC6: Title tags and meta descriptions set
- **Priority:** P0
- **Story Points:** 8 (combined — 3 points each if estimated separately)
- **Complexity:** Medium
- **Parallel:** Yes — all 3 pages can be built simultaneously. Also parallel with E-CORE-03 through E-CORE-05, E-CORE-08.
- **Assigned Sprint:** 2
- **Estimated Hours:** 6 (2 per page)

---

#### Story E-CORE-07: Build Industriele Schoonmaak Page (P08)

- **Type:** User Story
- **Description:** As a factory manager, I want to read a detailed page about industrial cleaning that covers equipment, safety protocols, and production-downtime minimization, so that I can confidently request a quote (current page has only 60 words).
- **Technical:** Rebuild page at `/industriele-schoonmaak/`. Apply Service template. Replace 60-word single paragraph with 300+ word comprehensive page. Content: (a) What is industrial cleaning — industries served. (b) Our approach — minimal downtime, safety protocols. (c) Services (bullet list: leidingen, productievloeren, magazijnstellingen, machines, vet/olie verwijdering). (d) Veiligheid & Certificering. (e) CTA. Cross-links to Reguliere Schoonmaak, Gevelreiniging.
- **Business Value:** 4 — thin content currently hurts credibility
- **Dependencies:** E-CORE-02 (Service template)
- **Blocked By:** E-CORE-02
- **Blocks:** None
- **Acceptance Criteria:**
  - AC1: Page returns HTTP 200 at `/industriele-schoonmaak/`
  - AC2: Content >= 300 words (was ~60 words)
  - AC3: Bullet list of industrial cleaning services present
  - AC4: Safety protocols section present
  - AC5: Cross-links present
  - AC6: CTA present
  - AC7: Title tag and meta description set
- **Priority:** P0
- **Story Points:** 5
- **Complexity:** Medium
- **Parallel:** Yes — with E-CORE-03 through E-CORE-06
- **Assigned Sprint:** 2
- **Estimated Hours:** 3

---

#### Story E-CORE-08: Build Category Landing Pages (P09, P10)

- **Type:** User Story
- **Description:** As a search engine visitor searching for broad terms ("glas en gevel reiniging", "schoonmaakdiensten"), I want to land on a well-structured overview page that aggregates sub-service cards so I can find the specific service I need.
- **Technical:** Build `page-category-landing.php` template. Create 2 pages:
  - P09 `/glas-en-gevel/`: 500+ words. Intro paragraph. Service Card Grid with Glasbewassing + Gevelreiniging cards. CTA Banner.
  - P10 `/schoonmaakdiensten/`: 500+ words. Intro paragraph. Service Card Grid with all 5 sub-services. CTA Banner.
- **Business Value:** 3 — SEO landing pages, internal link value
- **Dependencies:** E-CORE-02 (Service template), E-CORE-03, E-CORE-04 (service pages must exist for card links)
- **Blocked By:** E-INFRA-06
- **Blocks:** None
- **Acceptance Criteria:**
  - AC1: Both pages return HTTP 200
  - AC2: Both pages >= 500 words Dutch
  - AC3: Service Card Grid renders correctly on each page
  - AC4: Card links point to correct service pages
  - AC5: Title tags and meta descriptions set
  - AC6: Pages linked from main navigation dropdown parent items
- **Priority:** P1
- **Story Points:** 5
- **Complexity:** Medium
- **Parallel:** Yes — with E-CORE-03 through E-CORE-07
- **Assigned Sprint:** 2
- **Estimated Hours:** 3

---

### Feature F2.3: Conversion Pages

---

#### Story E-CORE-09: Build Contact Page + Contact Form (P16) — CRITICAL

- **Type:** User Story
- **Description:** As a potential client, I want to contact HDS via a working contact form, so that I can request information or a quote (current contact page returns HTTP 500 — zero web leads captured).
- **Technical:** Build `page-contact.php` template. Create Page at `/contact/`. Two-column layout: Form (left) + Contact Info Block (right). Configure Gravity Forms contact form per Section G1.1 specifications (9 fields: Naam, Bedrijf, E-mailadres, Telefoonnummer, Onderwerp dropdown, Bericht textarea, Privacy checkbox, reCAPTCHA v3, Submit button). Post-submit: redirect to `/bedankt/?type=contact`, confirmation email, notification to info@. Contact Info Block: phone (tel: link), email (mailto: link), address (if MI-01 provided), KVK/BTW (if MI-02/MI-03 provided), business hours (if MI-04 provided), social links, map embed (if address known). Resolve ARR F01.
- **Business Value:** 5 — primary conversion page, currently broken, blocks ALL online lead capture
- **Dependencies:** E-CORE-02 (templates approach established), E-INFRA-04 (SMTP must be working), E-PREREQ-06 (MI-01 through MI-04 for contact info block)
- **Blocked By:** E-INFRA-04 (SMTP), E-PREREQ-06 (address/KVK/BTW)
- **Blocks:** E-CORE-10 (Offerte form builds on same patterns), E-COMPLY-04 (GDPR consent verification)
- **Acceptance Criteria:**
  - AC1: Page returns HTTP 200 at `/contact/` (was 500)
  - AC2: Two-column layout renders correctly
  - AC3: All 9 form fields present and functional
  - AC4: reCAPTCHA v3 active (badge visible)
  - AC5: Privacy checkbox unchecked by default, links to /privacyverklaring/
  - AC6: Form submits successfully: redirect to /bedankt/?type=contact
  - AC7: Confirmation email delivered to user within 2 minutes
  - AC8: Notification email delivered to info@ within 2 minutes
  - AC9: Entry stored in Gravity Forms database
  - AC10: Contact Info Block: phone clickable (tel:), email clickable (mailto:)
  - AC11: Form validation errors display inline (Dutch)
  - AC12: Page accessible (keyboard-navigable, screen-reader compatible)
- **Priority:** P0
- **Story Points:** 13
- **Complexity:** Very High
- **Parallel:** No — critical path. Must be completed before E-CORE-10.
- **Assigned Sprint:** 2
- **Estimated Hours:** 8

**Subtasks:**
- ST-CORE-09.1: Build page-contact.php template with two-column layout
- ST-CORE-09.2: Build Gravity Forms contact form with all 9 fields
- ST-CORE-09.3: Configure reCAPTCHA v3 keys
- ST-CORE-09.4: Configure confirmation and notification email templates (Dutch)
- ST-CORE-09.5: Build Contact Info Block with conditional rendering (address/KVK/BTW/hours only if provided)
- ST-CORE-09.6: Test form submission, email delivery, entry storage
- ST-CORE-09.7: Test form validation (required fields, email format)
- ST-CORE-09.8: Test spam blocking (honeypot + reCAPTCHA)
- ST-CORE-09.9: Accessibility testing (keyboard, screen reader, axe)
- ST-CORE-09.10: Responsive testing (mobile form usability)

---

#### Story E-CORE-10: Build Offerte Aanvragen Page + Quote Form (P17)

- **Type:** User Story
- **Description:** As a potential client ready to request a quote, I want a dedicated quote request form that collects project-specific details (service type, building type, postcode, file upload), so that HDS can provide an accurate vrijblijvende offerte.
- **Technical:** Build `page-quote.php` template. Create Page at `/offerte-aanvragen/`. Configure Gravity Forms quote form per Section G1.2 (13 fields including multi-checkbox services, building type dropdown, postcode validation, file upload). Post-submit: redirect to `/bedankt/?type=offerte`, confirmation email with summary, notification to info@ with file download link.
- **Business Value:** 5 — primary lead qualification form
- **Dependencies:** E-CORE-09 (contact form patterns), E-INFRA-04 (SMTP)
- **Blocked By:** E-CORE-09
- **Blocks:** E-SEO-07 (conversion tracking)
- **Acceptance Criteria:**
  - AC1: Page returns HTTP 200 at `/offerte-aanvragen/`
  - AC2: All 13 form fields present and functional
  - AC3: Gewenste dienst checkboxes include all 7 services + Anders
  - AC4: Postcode field validates Dutch format (NNNN AA)
  - AC5: File upload accepts PDF, JPG, PNG, DOCX up to 5MB
  - AC6: File upload rejects wrong types and oversized files with clear errors
  - AC7: Form submits successfully: redirect to /bedankt/?type=offerte
  - AC8: Confirmation email includes submitted data summary
  - AC9: Notification email includes download link for uploaded file (not inline attachment)
  - AC10: Privacy checkbox unchecked by default
  - AC11: All validation errors in Dutch
- **Priority:** P1
- **Story Points:** 8
- **Complexity:** High
- **Parallel:** Can start after E-CORE-09 is substantially complete (Gravity Forms patterns established)
- **Assigned Sprint:** 2
- **Estimated Hours:** 5

---

#### Story E-CORE-11: Build Bedankt Page (P32)

- **Type:** Technical Task
- **Description:** Build "Bedankt" (Thank You) page at `/bedankt/`. Content: heading "Bedankt voor uw bericht", subtext based on query parameter (`?type=contact` vs `?type=offerte`), expected response time ("Wij streven ernaar binnen 1 werkdag te reageren"), phone number as fallback, links to key pages. No form on this page. Noindex meta tag (prevent search engines from indexing thank-you page).
- **Business Value:** 3 — post-conversion confirmation
- **Dependencies:** E-CORE-09 (form redirects must be configured to point here)
- **Blocked By:** None
- **Blocks:** None
- **Acceptance Criteria:**
  - AC1: Page returns HTTP 200 at `/bedankt/`
  - AC2: Dynamic message changes based on `?type=` parameter
  - AC3: Phone number visible as fallback
  - AC4: Noindex meta tag present
  - AC5: Page excluded from XML sitemap
  - AC6: Responsive
- **Priority:** P0
- **Story Points:** 2
- **Complexity:** Low
- **Parallel:** Yes — with E-CORE-10
- **Assigned Sprint:** 2
- **Estimated Hours:** 1

---

## Sprint 3: Supporting Pages & Content (Week 5)

### Feature F3.1: About & Trust Pages

---

#### Story E-SUPPORT-01: Build About Page Template + Over HDS (P11)

- **Type:** User Story
- **Description:** As a prospective client or job seeker, I want to read about HDS's company history, values, team, and certifications, so that I can trust the company before making contact.
- **Technical:** Build `page-about.php` template. Create Page at `/over-hds/`. Content (500+ words): (a) Company intro — who HDS is. (b) History — founding year, growth (MI-19). (c) Core values (Kwaliteit, Veiligheid, MVO) — maintained from current site. (d) USPs — vast personeel, bedrijfsleiding bij opstart, maatwerk, herkenbare kleding, een aanspreekpunt. (e) Certifications — OSB membership (MI-25), Arbo compliance, diplomas. (f) Team — optional (MI-09 photos if provided).
- **Business Value:** 4 — trust building
- **Dependencies:** E-INFRA-06 (theme), E-PREREQ-06 (MI-19 founding year, MI-25 OSB link)
- **Blocked By:** E-INFRA-06
- **Blocks:** None
- **Acceptance Criteria:**
  - AC1: Page returns HTTP 200 at `/over-hds/`
  - AC2: Content >= 500 words Dutch
  - AC3: Core values and USPs preserved from current site
  - AC4: OSB link functional (if MI-25 provided)
  - AC5: Title tag and meta description set
  - AC6: Responsive
- **Priority:** P0
- **Story Points:** 5
- **Complexity:** Medium
- **Parallel:** Yes — with E-SUPPORT-02
- **Assigned Sprint:** 3
- **Estimated Hours:** 3

---

#### Story E-SUPPORT-02: Build Kwaliteit & Veiligheid Page (P12)

- **Type:** User Story
- **Description:** As a compliance-conscious client, I want to verify HDS's quality processes, safety certifications, and MVO commitment, so that I can be confident in their professional standards.
- **Technical:** Create Page at `/kwaliteit-veiligheid/`. Apply About template. Migrate existing 150 words. Expand to 300+ words. Content: (a) Kwaliteit — continuous improvement, periodic checks, complaints resolution, single point of contact. (b) Veiligheid — OSB, Arbeidsinspectie, Arbo, RI&E per project, veiligheidspaspoort, diplomas. (c) MVO — environmentally conscious products, employee care. Add certification logos if provided (MI-09). Add hyperlinks to OSB and certifying bodies if URLs confirmed.
- **Business Value:** 4 — quality credential page
- **Dependencies:** E-INFRA-06 (theme), E-SUPPORT-01 (About template)
- **Blocked By:** E-INFRA-06
- **Blocks:** None
- **Acceptance Criteria:**
  - AC1: Page returns HTTP 200 at `/kwaliteit-veiligheid/`
  - AC2: Content >= 300 words Dutch
  - AC3: Three H2 sections (Kwaliteit, Veiligheid, MVO) present
  - AC4: Certification logos displayed (if provided)
  - AC5: External links functional (OSB, etc.)
  - AC6: Title tag and meta description set
- **Priority:** P0
- **Story Points:** 3
- **Complexity:** Low
- **Parallel:** Yes — with E-SUPPORT-01
- **Assigned Sprint:** 3
- **Estimated Hours:** 2

---

#### Story E-SUPPORT-03: Build Referenties Page + Testimonial CPT (P13)

- **Type:** User Story
- **Description:** As a prospective client, I want to see client logos, project descriptions, and testimonials, so that I have social proof of HDS's quality.
- **Technical:** Create Page at `/referenties/`. Content: (a) Intro sentence. (b) Client Logo Grid (block pattern — query client logos from uploaded images if MI-10 provided). (c) Testimonial Block (register `hds_testimonial` CPT with `public => false` per E-PREREQ-02 resolution — no slug conflict). (d) Testimonial submission form (Gravity Forms). Empty state handling: if no testimonials exist, show "Wij horen graag uw ervaring!" with link to submission form.
- **Business Value:** 4 — social proof is critical for B2B trust
- **Dependencies:** E-PREREQ-02 (CPT slug resolution), E-PREREQ-06 (MI-10 client logos, MI-11 testimonials)
- **Blocked By:** E-PREREQ-02
- **Blocks:** None
- **Acceptance Criteria:**
  - AC1: Page returns HTTP 200 at `/referenties/`
  - AC2: `hds_testimonial` CPT registered with public=false
  - AC3: No URL conflict with /referenties/ page
  - AC4: Client logos displayed (if MI-10 provided), section hidden if not
  - AC5: Testimonials displayed (if MI-11 provided), empty state shown if not
  - AC6: Testimonial submission form functional
  - AC7: Content >= 300 words Dutch
- **Priority:** P1
- **Story Points:** 8
- **Complexity:** High (CPT + conditional rendering + empty states)
- **Parallel:** Yes — with E-SUPPORT-01, E-SUPPORT-02
- **Assigned Sprint:** 3
- **Estimated Hours:** 5

---

#### Story E-SUPPORT-04: Rebuild Vacatures Page + Job CPT + Application Form (P14)

- **Type:** User Story
- **Description:** As a job seeker, I want to read vacancy details in readable text (currently only as unreadable JPG images), and apply online with my CV, so that I can pursue employment at HDS.
- **Technical:** Rebuild Page at `/vacatures/`. Register `hds_vacancy` CPT with fields: hours/week, location, start date, application email, deadline, active toggle. Build "Job Vacancy Card" block pattern (toggle-to-expand). Build Gravity Forms application form per Section G1.3 (6 fields + CV upload). JobPosting structured data per vacancy (Sprint 5). Resolve ARR F13.
- **Business Value:** 4 — recruitment page, currently completely inaccessible
- **Dependencies:** E-PREREQ-02 (CPT approach), E-PREREQ-06 (MI-12 — vacancy text as HTML)
- **Blocked By:** E-PREREQ-06 (client must provide vacancy text as editable HTML)
- **Blocks:** None
- **Acceptance Criteria:**
  - AC1: Page returns HTTP 200 at `/vacatures/`
  - AC2: ZERO scanned JPG images on page (all content is HTML text)
  - AC3: Vacancy cards toggle-to-expand functionality works
  - AC4: Application form per vacancy functional (CV upload works)
  - AC5: All text selectable and screen-reader accessible
  - AC6: Content >= 300 words Dutch (including vacancy text)
  - AC7: ARR F13 resolved
- **Priority:** P1
- **Story Points:** 8
- **Complexity:** High
- **Parallel:** Yes — with E-SUPPORT-01 through E-SUPPORT-03
- **Assigned Sprint:** 3
- **Estimated Hours:** 5

---

### Feature F3.2: Legal Pages & Downloads

---

#### Story E-SUPPORT-05: Build Legal Pages (P19-P22)

- **Type:** User Story
- **Description:** As a website visitor, I want to access the privacyverklaring, cookiebeleid, algemene voorwaarden, and disclaimer, so that I understand how my data is handled and the legal terms of engagement.
- **Technical:** Build `page-legal.php` template (simple: H1 -> Content -> Last Updated Date). Create 4 pages:
  - P19 `/privacyverklaring/`: Full privacy policy. Draft content covering: data controller, processing purposes, legal basis, retention periods, data subject rights, third-party sharing, international transfers, complaint right, contact details. **Must be reviewed by legal counsel before launch.**
  - P20 `/cookiebeleid/`: Auto-generated by Complianz plugin (Sprint 6). Create page shell now.
  - P21 `/algemene-voorwaarden/`: HTML version of terms & conditions. **Requires MI-16 (client to provide terms text).**
  - P22 `/disclaimer/`: Liability limitation, IP, external links, accuracy, applicable law.
- **Business Value:** 5 — GDPR/AVG legal compliance. Critical for launch.
- **Dependencies:** E-PREREQ-06 (MI-16 terms text), E-PREREQ-09 (legal counsel engaged)
- **Blocked By:** E-PREREQ-06 (MI-16)
- **Blocks:** E-COMPLY-01, E-COMPLY-02
- **Acceptance Criteria:**
  - AC1: All 4 pages return HTTP 200
  - AC2: Privacyverklaring content drafted and ready for legal review
  - AC3: Cookiebeleid page shell created (Complianz will populate in Sprint 6)
  - AC4: Algemene Voorwaarden page has content (if MI-16 provided; placeholder if not)
  - AC5: Disclaimer content published
  - AC6: All pages linked from footer
  - AC7: "Laatst bijgewerkt" (Last Updated) date visible on each
- **Priority:** P0 (Privacyverklaring, Cookiebeleid, Algemene Voorwaarden) / P2 (Disclaimer)
- **Story Points:** 8
- **Complexity:** Medium
- **Parallel:** Yes — with E-SUPPORT-01 through E-SUPPORT-04
- **Assigned Sprint:** 3
- **Estimated Hours:** 4

---

#### Story E-SUPPORT-06: Migrate Downloads & PDFs from Legacy Domain (P15)

- **Type:** Technical Task
- **Description:** Download all PDFs from legacy domain `hds-onderhoudsdiensten.nl`. Upload to WordPress media library on primary domain. Rebuild Downloads page at `/downloads/` with "Download Card List" block pattern (filename, description, download button with file type icon and file size). Update internal links to primary domain PDF URLs. Configure 301 redirects on legacy domain from old PDF URLs to new PDF URLs. Resolve ARR F18.
- **Business Value:** 4 — legal documents must be accessible from primary domain
- **Dependencies:** E-PREREQ-04 (domain access for legacy domain redirects)
- **Blocked By:** None
- **Blocks:** None
- **Acceptance Criteria:**
  - AC1: All PDFs accessible from `helderduidelijkschoon.nl`
  - AC2: Downloads page content >= 150 words (was ~10 words)
  - AC3: Download cards show: file name, description, file type icon, file size
  - AC4: Download tracking configured (GA4 event)
  - AC5: 301 redirects from old PDF URLs to new URLs (if legacy domain access available)
  - AC6: Zero dependency on legacy domain for PDF access
  - AC7: ARR F18 resolved
- **Priority:** P1
- **Story Points:** 5
- **Complexity:** Medium
- **Parallel:** Yes — with E-SUPPORT-01 through E-SUPPORT-05
- **Assigned Sprint:** 3
- **Estimated Hours:** 3

---

#### Story E-SUPPORT-07: Build Veelgestelde Vragen (FAQ) Page (P18)

- **Type:** User Story
- **Description:** As a prospect researching cleaning services, I want to find answers to common questions about pricing, service area, methods, and scheduling, so that I can make an informed decision without calling.
- **Technical:** Build `page-faq.php` template. Create Page at `/veelgestelde-vragen/`. Build 10-15 FAQ items using Yoast/Rank Math FAQ Block (auto-generates FAQPage schema). Questions in Dutch covering: pricing, service area, frequency, eco-friendly products, insurance, contracts, response time, etc. Content written based on business rules from SRC-03. Note: FAQ CPT from CMS spec is NOT needed — use Yoast FAQ Block on a standard Page instead (simpler, resolves CMS-03 ambiguity).
- **Business Value:** 3 — reduces phone inquiries for common questions
- **Dependencies:** E-INFRA-06 (theme)
- **Blocked By:** E-INFRA-06
- **Blocks:** E-SEO-04 (FAQ schema validation)
- **Acceptance Criteria:**
  - AC1: Page returns HTTP 200 at `/veelgestelde-vragen/`
  - AC2: 10-15 FAQ items present with expand/collapse functionality
  - AC3: FAQPage structured data auto-generated and valid
  - AC4: Total FAQ content >= 300 words
  - AC5: Responsive and accessible (keyboard-navigable accordion)
- **Priority:** P2
- **Story Points:** 5
- **Complexity:** Low
- **Parallel:** Yes — with E-SUPPORT-01 through E-SUPPORT-06
- **Assigned Sprint:** 3
- **Estimated Hours:** 3

---

## Sprint 4: WooCommerce & eCommerce (Week 5-6)

### Feature F4.1: WooCommerce Configuration

---

#### Story E-COMM-01: Configure WooCommerce Core Settings

- **Type:** Technical Task
- **Description:** Configure WooCommerce settings per Section G2.1: shop/cart/checkout/account pages assigned, currency EUR, thousand/digit separators (Dutch format), prices entered excl. BTW, tax rate 21%, weight unit kg, dimension cm, coupons enabled, reviews enabled (moderated), guest checkout enabled, inventory management enabled, backorders disabled. Set privacy policy and terms pages to P19 and P21.
- **Business Value:** 5 — eCommerce foundation
- **Dependencies:** E-INFRA-02 (WooCommerce installed)
- **Blocked By:** E-INFRA-02
- **Blocks:** E-COMM-02 through E-COMM-07
- **Acceptance Criteria:**
  - AC1: Shop page at `/winkel/` accessible
  - AC2: Cart page at `/winkelmand/` accessible
  - AC3: Checkout page at `/afrekenen/` accessible
  - AC4: My Account at `/mijn-account/` accessible
  - AC5: Currency = EUR, separators = Dutch format (dot thousand, comma decimal)
  - AC6: Prices display "excl. BTW" suffix
  - AC7: Privacy and terms pages linked in checkout
- **Priority:** P1
- **Story Points:** 3
- **Complexity:** Low
- **Parallel:** No — foundational for all WC stories
- **Assigned Sprint:** 4
- **Estimated Hours:** 2

---

#### Story E-COMM-02: Import 14 Airfixr Products

- **Type:** Technical Task
- **Description:** Export 14 Airfixr products from current WooCommerce installation. Import to new WooCommerce on staging. Verify: product titles, descriptions, prices (excl. BTW), images, stock status, categories. Spot-check 5 products for data integrity per ARR MIG-02 recommendation. Add shop intro text to `/winkel/` page (100+ words explaining Airfixr product line).
- **Business Value:** 4 — product catalog migration
- **Dependencies:** E-COMM-01 (WC configured)
- **Blocked By:** E-COMM-01
- **Blocks:** E-COMM-07 (purchase flow testing)
- **Acceptance Criteria:**
  - AC1: All 14 products imported and visible at `/winkel/`
  - AC2: Product images display correctly (WebP optimized)
  - AC3: Prices match old site (verified via spot-check)
  - AC4: Stock status correct
  - AC5: Shop intro text >= 100 words Dutch
  - AC6: Special characters render correctly (ë, ï, é)
- **Priority:** P1
- **Story Points:** 5
- **Complexity:** Medium
- **Parallel:** No — follows E-COMM-01
- **Assigned Sprint:** 4
- **Estimated Hours:** 3

---

#### Story E-COMM-03: Configure Payment Gateway (Mollie or Chosen)

- **Type:** Technical Task
- **Description:** Install and configure Mollie for WooCommerce (or client-chosen alternative). Set up Mollie dashboard: webhook URL configured, API keys set (test mode initially). Test payment flow: iDEAL, Bancontact, credit card, SEPA. Configure payment method display order. Configure bank transfer (BACS) as fallback for B2B invoice-based payment. Resolve ARR B08 (webhook) and ARR HD03 (webhook dependency).
- **Business Value:** 5 — payment is the core of eCommerce
- **Dependencies:** E-PREREQ-06 (MI-15 payment gateway decision), E-COMM-01 (WC configured)
- **Blocked By:** E-COMM-01
- **Blocks:** E-COMM-07 (purchase flow testing)
- **Acceptance Criteria:**
  - AC1: Payment gateway installed and configured in test mode
  - AC2: Webhook URL configured and verified (Mollie dashboard)
  - AC3: Test payment via iDEAL successful
  - AC4: Order status updated after webhook delivery
  - AC5: Multiple payment methods display at checkout
  - AC6: ARR B08 resolved
  - AC7: ARR HD03 resolved
- **Priority:** P0 (if shop is kept)
- **Story Points:** 5
- **Complexity:** Medium
- **Parallel:** No — follows E-COMM-01
- **Assigned Sprint:** 4
- **Estimated Hours:** 3

---

#### Story E-COMM-04: Configure Shipping

- **Type:** Technical Task
- **Description:** Configure shipping zones (Nederland). Create shipping classes: "Klein pakket" (filters, lamps, UV-C lamps) and "Groot pakket" (Airfixr units). Set shipping rates per class (flat rate or weight-based). If MI-14 (shipping costs) not provided, implement flat rate with placeholder values and note for client review. Configure free shipping threshold if client offers it (MI-14 decision).
- **Business Value:** 4 — shipping is required for eCommerce
- **Dependencies:** E-PREREQ-06 (MI-14 shipping costs), E-COMM-01 (WC configured)
- **Blocked By:** E-COMM-01
- **Blocks:** E-COMM-07
- **Acceptance Criteria:**
  - AC1: Shipping zone "Nederland" configured
  - AC2: Shipping classes created and assigned to products
  - AC3: Shipping rates display at checkout
  - AC4: Free shipping threshold configured (if applicable)
  - AC5: Shipping calculated correctly in test purchase
- **Priority:** P1
- **Story Points:** 3
- **Complexity:** Low
- **Parallel:** Yes — with E-COMM-03
- **Assigned Sprint:** 4
- **Estimated Hours:** 2

---

#### Story E-COMM-05: Configure WooCommerce Email Notifications

- **Type:** Technical Task
- **Description:** Configure all WooCommerce email notifications (New Order, Processing, Completed, Cancelled, Failed, Refund, Customer Invoice, Customer Note, Reset Password, New Account). Customize email templates: branded with HDS logo, Dutch language, from "HDS Onderhoudsdiensten" <info@helderduidelijkschoon.nl>. Test email delivery for each notification type via Post SMTP log.
- **Business Value:** 4 — transactional emails are required for customer communication
- **Dependencies:** E-INFRA-04 (SMTP), E-COMM-01 (WC configured)
- **Blocked By:** E-INFRA-04
- **Blocks:** E-COMM-07
- **Acceptance Criteria:**
  - AC1: All 10 email notification types enabled
  - AC2: Email templates branded with HDS logo
  - AC3: From name: "HDS Onderhoudsdiensten"
  - AC4: From address: info@helderduidelijkschoon.nl
  - AC5: Test email delivered for New Order and Completed Order
  - AC6: All emails in Dutch
- **Priority:** P1
- **Story Points:** 3
- **Complexity:** Low
- **Parallel:** Yes — with E-COMM-03, E-COMM-04
- **Assigned Sprint:** 4
- **Estimated Hours:** 2

---

#### Story E-COMM-06: Build Luchtreiniging Landing Page (P23)

- **Type:** User Story
- **Description:** As a visitor interested in air purification, I want to understand what Airfixr is, why HDS sells it, and how it connects to cleaning services, so that I can make an informed purchase decision.
- **Technical:** Create Page at `/luchtreiniging/`. Content (300+ words): (a) What is Airfixr and why air purification matters. (b) Connection to cleaning services. (c) Product highlights with images and links to individual product pages. (d) CTA: "Bekijk alle producten" -> `/winkel/`. Internal links from relevant service pages to this page.
- **Business Value:** 3 — cross-sell bridge between cleaning services and products
- **Dependencies:** E-COMM-02 (products must exist for product highlights)
- **Blocked By:** None
- **Blocks:** None
- **Acceptance Criteria:**
  - AC1: Page returns HTTP 200 at `/luchtreiniging/`
  - AC2: Content >= 300 words Dutch
  - AC3: Product highlights display with images and shop links
  - AC4: CTA to /winkel/ present
  - AC5: Cross-links from service pages to this page exist
  - AC6: Title tag and meta description set
- **Priority:** P1
- **Story Points:** 3
- **Complexity:** Low
- **Parallel:** Yes — with E-COMM-03 through E-COMM-05
- **Assigned Sprint:** 4
- **Estimated Hours:** 2

---

#### Story E-COMM-07: Test Full WooCommerce Purchase Flow

- **Type:** Technical Task
- **Description:** Execute end-to-end test: (a) Browse shop `/winkel/`. (b) View product detail page. (c) Add to cart. (d) View cart `/winkelmand/` — update quantity, remove item. (e) Proceed to checkout `/afrekenen/` — fill billing details, select payment method. (f) Complete payment (test mode). (g) Verify order confirmation page. (h) Verify New Order email to admin. (i) Verify Processing/Completed email to customer. (j) Verify order appears in WooCommerce admin. (k) Repeat for guest checkout and logged-in checkout.
- **Business Value:** 5 — verifies the complete revenue process
- **Dependencies:** E-COMM-01 through E-COMM-06
- **Blocked By:** E-COMM-01
- **Blocks:** E-QA-01
- **Acceptance Criteria:**
  - AC1: Full purchase flow completes without errors
  - AC2: Cart functionality works (add, update, remove)
  - AC3: Payment processes in test mode
  - AC4: All email notifications delivered
  - AC5: Order visible in WooCommerce admin
  - AC6: Guest checkout functional
  - AC7: Logged-in checkout functional
  - AC8: Mobile checkout usable (responsive)
- **Priority:** P1
- **Story Points:** 5
- **Complexity:** Medium
- **Parallel:** No — depends on all previous E-COMM stories
- **Assigned Sprint:** 4
- **Estimated Hours:** 3

---

## Inter-Sprint Dependency Map

```
Sprint 0 (Prerequisites)  ─────────────────────────────────────────────────────
  E-PREREQ-01 ─┬─> E-INFRA-06  (theme selection)
  E-PREREQ-02 ─┼─> E-SUPPORT-03 (CPT slug)
  E-PREREQ-03 ─┼─> E-INFRA-02  (plugin selections)
  E-PREREQ-04 ─┼─> E-INFRA-01  (domain + hosting)
  E-PREREQ-05 ─┼─> E-INFRA-01  (hosting provisioned)
  E-PREREQ-06 ─┼─> E-CORE-09, E-SUPPORT-03..05, E-SEO-02, E-COMM-01..04
  E-PREREQ-07 ─┼─> E-INFRA-02  (Git repo)
  E-PREREQ-08 ─┼─> E-SEO-05, E-SEO-06  (GA4 + GSC)
  E-PREREQ-09 ─┴─> E-COMPLY-01, E-COMPLY-02 (legal review)

Sprint 1 (Infrastructure) ─────────────────────────────────────────────────────
  E-INFRA-01 ──> E-INFRA-02 ──> E-INFRA-03 (parallel)
                             ──> E-INFRA-04 (parallel)
                             ──> E-INFRA-05 (parallel)
  E-INFRA-06 ─────────────────> E-INFRA-07 ──┐
  E-INFRA-08 (parallel with E-INFRA-07) ─────┤
                                              ├──> ALL page stories

Sprint 2 (Core Pages) ─────────────────────────────────────────────────────────
  E-CORE-01 ──> E-CORE-02 ──> E-CORE-03 through E-CORE-08 (parallel)
  E-CORE-09 ──> E-CORE-10
  E-CORE-11 (parallel with E-CORE-10)

Sprint 3 (Supporting Pages) ───────────────────────────────────────────────────
  E-SUPPORT-01 ────────────────────┐
  E-SUPPORT-02 ────────────────────┤
  E-SUPPORT-03 ────────────────────┤ (all parallel)
  E-SUPPORT-04 ────────────────────┤
  E-SUPPORT-05 ────────────────────┤
  E-SUPPORT-06 ────────────────────┤
  E-SUPPORT-07 ────────────────────┘

Sprint 4 (WooCommerce) ────────────────────────────────────────────────────────
  E-COMM-01 ──> E-COMM-02 ──┐
  E-COMM-01 ──> E-COMM-03 ──┤ (parallel)
  E-COMM-01 ──> E-COMM-04 ──┤ (parallel)
  E-COMM-01 ──> E-COMM-05 ──┤ (parallel)
  E-COMM-06 (parallel with all) ──┘
  ALL ────────> E-COMM-07

Sprint 5 (SEO) ────────────────────────────────────────────────────────────────
  E-SEO-01 ──> E-SEO-02, E-SEO-03, E-SEO-04 (parallel after config)
  E-SEO-05, E-SEO-06, E-SEO-07 (parallel after E-SEO-01)
  E-SEO-08, E-SEO-09, E-SEO-10 (parallel after page content exists)

Sprint 6 (Compliance) ─────────────────────────────────────────────────────────
  E-COMPLY-01 through E-COMPLY-07 (mostly parallel)

Sprint 7 (QA) ─────────────────────────────────────────────────────────────────
  E-QA-01 through E-QA-07 (parallel by category)

Sprint 8 (Launch) ─────────────────────────────────────────────────────────────
  Sequential: E-LAUNCH-01 ──> E-LAUNCH-02..04 ──> E-LAUNCH-05..09
```

---

## Blocker Identification

### Critical Path Stories (Must Complete for Launch)

| Story | Reason | Blocks |
|---|---|---|
| E-PREREQ-04 | Domain/hosting access | Everything |
| E-PREREQ-05 | Hosting provisioned | E-INFRA-01 |
| E-PREREQ-06 | Client answers (18 questions) | 19 stories across all sprints |
| E-PREREQ-09 | Legal counsel engaged | E-COMPLY-01, E-COMPLY-02 |
| E-INFRA-01 | WordPress installed | All development |
| E-INFRA-02 | Plugins installed | All configuration |
| E-INFRA-03 | CDN + SSL | Performance + security |
| E-INFRA-04 | SMTP | Forms + WooCommerce emails |
| E-INFRA-06 | Theme foundation | All page templates |
| E-CORE-02 | Service template | All service pages |
| E-CORE-09 | Contact form | Lead capture — highest business impact fix |

### Parallel Execution Opportunities

| Story Group | Stories | Can Run Together Because |
|---|---|---|
| Service Pages | E-CORE-03 through E-CORE-08 (6 stories) | All use same Service template, independent content |
| Supporting Pages | E-SUPPORT-01 through E-SUPPORT-07 (7 stories) | All independent content, different templates |
| WooCommerce Config | E-COMM-03, E-COMM-04, E-COMM-05 | All depend on E-COMM-01 but not on each other |
| Sprint 0 Tasks | E-PREREQ-01, E-PREREQ-02, E-PREREQ-03, E-PREREQ-07, E-PREREQ-08, E-PREREQ-09 | All independent decisions and setup |
| Sprint 1 Infrastructure | E-INFRA-03, E-INFRA-04, E-INFRA-05 | All depend on E-INFRA-01/02 but not each other |

---

## Story Point Summary by Sprint

| Sprint | Stories | Points | Cumulative | Velocity Assumption |
|---|---|---|---|---|
| Sprint 0 | 9 | 28 | 28 | Prerequisites (non-development) |
| Sprint 1 | 8 | 45 | 73 | 2 developers |
| Sprint 2 | 12 | 72 | 145 | 2 developers |
| Sprint 3 | 11 | 56 | 201 | 2 developers |
| Sprint 4 | 9 | 44 | 245 | 2 developers |
| Sprint 5 | 10 | 53 | 298 | 1 developer |
| Sprint 6 | 9 | 47 | 345 | 1 developer |
| Sprint 7 | 8 | 41 | 386 | 2 developers |
| Sprint 8 | 9 | 34 | 420 | 1 developer |

**Total:** 85 stories, 420 points, 9 sprints, 18 week-ends (9 weeks with 2 developers peak, 1 developer for SEO/Compliance/Launch)

---

## Appendix: Story ID Quick Reference

### Sprint 0: E-PREREQ-01 through E-PREREQ-09
### Sprint 1: E-INFRA-01 through E-INFRA-08
### Sprint 2: E-CORE-01 through E-CORE-12
### Sprint 3: E-SUPPORT-01 through E-SUPPORT-07
### Sprint 4: E-COMM-01 through E-COMM-07
### Sprint 5: E-SEO-01 through E-SEO-10
### Sprint 6: E-COMPLY-01 through E-COMPLY-09
### Sprint 7: E-QA-01 through E-QA-08
### Sprint 8: E-LAUNCH-01 through E-LAUNCH-09

---

**END OF DEVELOPMENT BACKLOG — Version 1.0.0**

**This backlog is implementation-ready. All stories have descriptions, business value, dependencies, acceptance criteria, priority, story points, and complexity estimates. Sprint 5 through Sprint 8 stories are defined in the epics above but detailed with full AC in the MPS-001 and rebuild specification. Each story maps directly to actionable development work.**
