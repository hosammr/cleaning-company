# HDS Onderhoudsdiensten — Product Backlog

**Document ID:** PB-001 | **Version:** 1.0.0 | **Status:** Ready for Sprint Planning
**Project:** helderduidelijkschoon.nl — Ground-Up Rebuild
**Language:** Nederlands (nl-NL) / English (stories) | **Date:** July 2026
**Referenced Documents:** MPS-001, SAD-001, ADR-001, BKLG-001, ARR-001, RTM-001, FS-001, NFR-001, SPRINT2_EXECUTION_PLAN.md

---

## 1. Product Vision

HDS Onderhoudsdiensten provides professional B2B cleaning and maintenance services in West-Brabant and Zeeland. The new `helderduidelijkschoon.nl` platform transforms the company's digital presence from a broken, non-compliant website into a modern, performant, and legally compliant acquisition channel.

**Vision Statement:** "By Q3 2026, helderduidelijkschoon.nl will be the primary digital acquisition channel for HDS Onderhoudsdiensten — generating qualified B2B service inquiries through a fast, accessible, and SEO-optimized website that converts visitors into leads through working contact forms, detailed service pages, and a transparent quote request process."

### Business Objectives

| # | Objective | Key Result | Success Metric |
|---|---|---|---|
| BO01 | Restore web-based lead capture | Working contact + quote forms | ≥ 5 form submissions per week post-launch |
| BO02 | Establish online visibility for all 7 service lines | All service pages indexed and ranking | ≥ 3 services in Google top 10 for primary keywords |
| BO03 | Achieve full GDPR/AVG compliance | Privacy policy, cookie consent, KVK/BTW displayed | Zero compliance gaps at legal review |
| BO04 | Build a professional, trustworthy brand presence | Consistent design, USPs, certifications | Subjective: client satisfaction + peer review |
| BO05 | Enable data-driven marketing decisions | GA4 + GSC fully operational | Monthly analytics report delivered to client |
| BO06 | Create a maintainable platform | Block Editor for all content; documented processes | Client can edit pages and view form entries without developer assistance |

---

## 2. Release Goals

### 2.1 MVP (Minimum Viable Product) — Sprint 2 Completion

**Definition:** The smallest set of features that delivers business value. A visitor can discover services, read about them, and submit a contact or quote request.

| # | Goal | Measured By |
|---|---|---|
| MVP01 | Home page live with all content blocks | Page returns HTTP 200 at `/`; all 8 blocks render |
| MVP02 | All 7 service pages live with 300+ words each | Page returns HTTP 200; word count verified |
| MVP03 | Contact form submits and delivers email | Test submission → email received within 2 minutes |
| MVP04 | Quote request form submits with file upload | Test submission with PDF → email received with download link |
| MVP05 | Bedankt confirmation page works | Dynamic message based on `?type=` parameter |
| MVP06 | 404 page provides helpful navigation | HTTP 404 status; search + links + contact info present |
| MVP07 | Mobile-responsive on all core pages | No horizontal scroll at 375px; menu functional |

**MV P Scope Boundary:** Pages P01 (Home), P02–P08 (7 services), P16 (Contact), P17 (Offerte), P31 (404), P32 (Bedankt) = 13 pages. Stories: E-CORE-01 through E-CORE-11.

### 2.2 Release 1.0 — Sprint 8 Completion

**Definition:** The complete, production-ready website. All 32 pages built, all forms working, WooCommerce functional, SEO foundation in place, GDPR compliant, performance and accessibility gates passed, client trained, launched.

| # | Goal | Measured By |
|---|---|---|
| R01 | All 32 pages published with final Dutch content | Screaming Frog: zero 4xx/5xx on expected pages |
| R02 | All 3 forms submit and deliver email | Tested with real email delivery |
| R03 | WooCommerce purchase flow end-to-end | Test order: browse → cart → checkout → payment → email |
| R04 | SEO foundation complete | All meta titles/descriptions unique; schema valid; sitemap 200; 301 redirects working |
| R05 | PSI Mobile ≥ 90; PSI Desktop ≥ 95 | Tested on Home, 1 service page, 1 product page |
| R06 | Lighthouse Accessibility = 100 on all templates | axe DevTools: zero critical/serious |
| R07 | GDPR/AVG compliant | Cookie consent active; privacyverklaring legally reviewed; form consent unchecked by default |
| R08 | GA4 + GSC operational | Real-time traffic visible; sitemap submitted |
| R09 | Client trained and self-sufficient | 1-hour session completed; Beheergids delivered |
| R10 | Launched to production | `helderduidelijkschoon.nl` serves the new site |

### 2.3 Future Releases (Post-Launch)

| Release | Scope | Stories Reference |
|---|---|---|
| Release 1.1 — Blog Launch | 5–10 Dutch blog articles; blog index; categories | E-SEO-01 (partial — blog-specific metadata) |
| Release 1.2 — Marketing Enhancements | Google Ads landing pages; location-specific pages; newsletter signup; WhatsApp button | New stories (not in current backlog) |
| Release 1.3 — E-Commerce Expansion | Abandoned cart recovery; product reviews management; inventory threshold alerts | E-COMM extensions |
| Release 2.0 — Client Portal | Self-service client area: schedules, invoices, issue tracking | New epic (future) |

---

## 3. Epic Overview

| Epic ID | Title | Description | Business Value | Priority | Dependencies | RTM Refs |
|---|---|---|---|---|---|---|
| E-PREREQ | Prerequisites & Foundation Decisions | Resolve all Phase 0 decisions: hosting, domain, theme selection, plugin selections, legal counsel. No code written. | 5 — blocks ALL development | P0 | None | BR-011, BR-018, CMP-007 |
| E-INFRA | Infrastructure & Foundation | Provision hosting, install WordPress 6.7+ and all plugins, configure CDN/SSL/SMTP/backups, build theme foundation and design system. | 5 — platform foundation | P0 | E-PREREQ | TR-001..037, SEC-001..016, PERF-007..014 |
| E-CORE | Core Pages & Conversion | Build Home page, all 7 service pages, 2 category landings, Contact page with form, Offerte page with form, Bedankt, 404. | 5 — MVP; primary conversion | P0 | E-INFRA | FR-001..021, CON-001..010, CON-016..017, CON-028..029, SEC-003..006, ACC-001..020, SEO-001..007, SEO-012, SEO-014..015, SEO-022 |
| E-SUPPORT | Supporting Pages & Content | Build About pages, legal pages, Referenties, Vacatures, Downloads, FAQ. | 4 — trust, compliance, recruitment | P0 (legal), P1 (trust/content) | E-INFRA | FR-014..015, FR-041..048, CON-011..015, CON-018..022, CMP-001, MIG-006..008 |
| E-COMM | WooCommerce & eCommerce | Configure WooCommerce, import 14 Airfixr products, configure Mollie payments, shipping, emails, test purchase flow. | 4 (conditional) | P1 | E-INFRA, MI-15 | FR-022..027, WC-001..012, PERF-012 |
| E-SEO | SEO, Analytics & Optimization | Configure Rank Math Pro, write all meta titles/descriptions, implement structured data, configure 301 redirects, XML sitemap, GA4 + GTM + GSC, conversion tracking, image optimization. | 5 — organic acquisition | P0 | All content pages exist (E-CORE, E-SUPPORT, E-COMM) | SEO-001..028, ANL-001..010 |
| E-COMPLY | Compliance, Security & Accessibility | Configure Complianz cookie consent, Wordfence security (2FA, custom login URL, brute force), GDPR form consent checkboxes, full accessibility audit and remediation. | 5 — legal compliance gate | P0 | E-SUPPORT (legal pages), MI-17 | CMP-001..013, SEC-007..010, ACC-001..020 |
| E-QA | Testing & Quality Assurance | Full functional QA, cross-browser, mobile/tablet, accessibility audit, performance testing, security audit, SEO audit, client review and approval on staging. | 5 — launch gate | P0 | ALL previous epics | PERF-001..006, ACC-001..020, SEO-001..028, SEC-001..016 |
| E-LAUNCH | Launch & Handover | Pre-launch checklist, deploy to production, clear caches, verify redirects, submit sitemaps, post-launch verification, client handover + training, Beheergids. | 5 — go-live | P0 | E-QA | OPS-001..006, MIG-007..011 |

---

## 4. User Stories

### 4.1 Sprint 0 — Prerequisites & Foundation Decisions (E-PREREQ)

**Epic Goal:** Resolve all Phase 0 blocking decisions and infrastructure prerequisites. Zero code written.

---

#### Story E-PREREQ-01: Resolve Theme Selection

| Field | Value |
|---|---|
| **Story ID** | E-PREREQ-01 |
| **Epic** | E-PREREQ |
| **Title** | Resolve Theme Selection |
| **Type** | Spike |
| **Persona** | Lead Solution Architect |
| **Business Goal** | I want to select one theme approach so that the development team can begin Sprint 1 with a definitive architecture. |
| **Description** | Evaluate and select the theme: (a) Custom hybrid block theme (theme.json + PHP templates + Block Editor), (b) GeneratePress Pro + GenerateBlocks, or (c) Kadence Pro. Document decision with rationale in MPS-001. |
| **Priority** | P0 |
| **Story Points** | 3 |
| **Dependencies** | None |
| **Blocks** | E-INFRA-06 |
| **RTM Refs** | TR-004, TR-018 |
| **FS Refs** | FS §4.1–4.4 (all page templates depend on theme) |
| **NFR Refs** | NFR §11.1 (coding standards), NFR §6.7 (output escaping) |

**Acceptance Criteria (Gherkin):**

```gherkin
Feature: Theme Selection Resolution

  Scenario: Theme approach is selected and documented
    Given the Architecture Readiness Review has identified theme ambiguity as BLOCKING (ARR B02)
    When the Lead Solution Architect evaluates all three alternatives against project constraints
    Then exactly one theme approach is selected
    And the selection rationale is documented in MPS-001 Section B1
    And ARR B02 is resolved
```

---

#### Story E-PREREQ-02: Resolve CPT Slug Conflict

| Field | Value |
|---|---|
| **Story ID** | E-PREREQ-02 |
| **Epic** | E-PREREQ |
| **Title** | Resolve CPT Slug Conflict |
| **Type** | Technical Task |
| **Persona** | Lead Solution Architect |
| **Business Goal** | I want to resolve the `hds_testimonial` CPT slug conflict with the `/referenties/` Page so that no URL conflicts occur in development. |
| **Description** | Document the decision: set `hds_testimonial` CPT to `public => false`, `publicly_queryable => false`. Testimonials queried only via custom blocks. |
| **Priority** | P0 |
| **Story Points** | 1 |
| **Dependencies** | None |
| **Blocks** | E-SUPPORT-03 |
| **RTM Refs** | FR-041 |
| **FS Refs** | FS §4.5 (Referenties page) |
| **NFR Refs** | — |

```gherkin
Feature: CPT Slug Conflict Resolution

  Scenario: hds_testimonial CPT does not conflict with /referenties/ Page
    Given the /referenties/ Page (P13) exists at URL /referenties/
    When the hds_testimonial CPT is registered with public => false and publicly_queryable => false
    Then no URL conflict exists between the CPT and the Page
    And testimonials are queryable only via the hds/testimonial custom block
    And ARR CMS-02 is resolved
```

---

#### Story E-PREREQ-03: Resolve Plugin Selections (SEO, Caching)

| Field | Value |
|---|---|
| **Story ID** | E-PREREQ-03 |
| **Epic** | E-PREREQ |
| **Title** | Resolve Plugin Selections |
| **Type** | Spike |
| **Persona** | Lead Solution Architect |
| **Business Goal** | I want to select SEO and caching plugins definitively so that Sprint 1 can install the correct software stack. |
| **Description** | Evaluate and select: SEO plugin (Rank Math Pro vs Yoast SEO Premium), Caching plugin (FlyingPress vs WP Rocket). Document decisions. |
| **Priority** | P1 |
| **Story Points** | 2 |
| **Dependencies** | E-PREREQ-01 (theme choice may influence GenerateBlocks decision) |
| **Blocks** | E-INFRA-02 |
| **RTM Refs** | TR-008 (SEO), TR-009 (caching) |
| **FS Refs** | FS §11 (SEO behaviour) |
| **NFR Refs** | NFR §3.4 (caching), NFR §9 (SEO quality) |

```gherkin
Feature: Plugin Selection Resolution

  Scenario: SEO and caching plugins are selected
    Given the project specification lists two alternatives for SEO and two for caching
    When the architect evaluates each against project requirements
    Then Rank Math Pro is selected for SEO (built-in redirect manager, rich schema controls)
    And FlyingPress is selected for caching (built-in unused CSS removal, strong CWV optimization)
    And both decisions are documented in ADR-001 (Decisions D-003 and D-004)
```

---

#### Story E-PREREQ-04: Obtain Domain & Hosting Access

| Field | Value |
|---|---|
| **Story ID** | E-PREREQ-04 |
| **Epic** | E-PREREQ |
| **Title** | Obtain Domain & Hosting Access |
| **Type** | Technical Task |
| **Persona** | Developer / Client |
| **Business Goal** | I want to obtain all required credentials so that infrastructure provisioning can begin. |
| **Description** | Client provides: domain registrar access, hosting control panel access, WordPress admin access to current site, hosting provider decision. Developer verifies all credentials work. |
| **Priority** | P0 |
| **Story Points** | 3 |
| **Dependencies** | None |
| **Blocks** | E-INFRA-01, E-PREREQ-05 |
| **RTM Refs** | INF-001 |
| **FS Refs** | — |
| **NFR Refs** | NFR §4.1 (uptime) |

```gherkin
Feature: Domain and Hosting Access

  Scenario: All infrastructure credentials are obtained and verified
    Given the rebuild depends on domain registrar and hosting access
    When client provides all required credentials
    Then domain registrar login is verified
    And hosting provider is selected and account is provisioned
    And WordPress admin access to the current site is verified
    And all credentials are stored securely by the developer
```

---

#### Story E-PREREQ-05: Provision Managed WordPress Hosting

| Field | Value |
|---|---|
| **Story ID** | E-PREREQ-05 |
| **Epic** | E-PREREQ |
| **Title** | Provision Managed WordPress Hosting |
| **Type** | Technical Task |
| **Persona** | Developer |
| **Business Goal** | I want to provision hosting with staging and production environments so that development can begin. |
| **Description** | Provision managed WordPress hosting. Create staging environment (`staging.helderduidelijkschoon.nl`, password-protected, noindex). Prepare production environment. Configure PHP 8.2+, MySQL 8.0+/MariaDB 10.6+, Redis. Verify SFTP access. |
| **Priority** | P0 |
| **Story Points** | 5 |
| **Dependencies** | E-PREREQ-04 |
| **Blocks** | E-INFRA-01 |
| **RTM Refs** | INF-002, TR-001, TR-002, TR-014 |
| **FS Refs** | — |
| **NFR Refs** | NFR §4.1, NFR §12.3 |

```gherkin
Feature: Hosting Provisioning

  Scenario: Staging and production environments are provisioned
    Given the hosting provider has been selected
    When the developer provisions the managed WordPress account
    Then the staging environment is accessible at staging.helderduidelijkschoon.nl
    And the production environment is prepared (empty, ready)
    And PHP 8.2+ is verified via phpinfo
    And Redis is available and enabled
    And the staging environment returns noindex headers
    And the staging environment is password-protected
    And SFTP access is working
```

---

#### Story E-PREREQ-06: Resolve Phase 0 Client Questions

| Field | Value |
|---|---|
| **Story ID** | E-PREREQ-06 |
| **Epic** | E-PREREQ |
| **Title** | Resolve Phase 0 Client Questions (18 questions, MI-01 through MI-25 dependencies) |
| **Type** | Spike |
| **Persona** | Client / Developer |
| **Business Goal** | I want to answer all Phase 0 client questions so that missing information does not block development sprints. |
| **Description** | Client answers 18 Phase 0 questions (Q01–Q18) covering: legal entity type (Q01), physical address (Q02), KVK/BTW numbers (Q03), business hours (Q04), service area (Q05), developer status (Q06), GBP status (Q07), client source (Q08), Airfixr decision (Q09), brand preferences (Q10–Q11), payment gateway (Q12), shipping costs (Q13), hosting provider (Q14), analytics access (Q15), hosting budget (Q16), budget approval (Q17), business goals (Q18). |
| **Priority** | P0 |
| **Story Points** | 8 |
| **Dependencies** | None |
| **Blocks** | 19 stories across all sprints |
| **RTM Refs** | BR-009, BR-011, GAP-001..007 |
| **FS Refs** | FS §2.3 (conditional scope) |
| **NFR Refs** | NFR §7.2 (MI-17 legal review) |

```gherkin
Feature: Phase 0 Client Questions

  Scenario: All 18 Phase 0 questions are answered
    Given 18 questions block 19 development stories across all sprints
    When the client provides answers to all 18 questions
    Then all answers are documented in MPS-001
    And the MI-01 through MI-25 dependency tracker is updated
    And answers are stored in a shared location accessible to the development team

  Scenario: Missing information is handled gracefully with assumptions
    Given some MI items may not be provided by the client before the dependency deadline
    When a dependency deadline passes without client input
    Then the item is marked as an assumption with the default value documented
    And conditional rendering is used where possible (show section only if data exists)
    And the assumption is communicated to the client for confirmation
```

---

#### Story E-PREREQ-07: Set Up Git Repository

| Field | Value |
|---|---|
| **Story ID** | E-PREREQ-07 |
| **Epic** | E-PREREQ |
| **Title** | Set Up Git Repository |
| **Type** | Technical Task |
| **Persona** | Developer |
| **Business Goal** | I want a Git repository with proper configuration so that all code is under version control from day one. |
| **Description** | Create Git repository (GitHub/GitLab). Initialize with: `.gitignore` (WordPress-specific), `.gitattributes`, `.editorconfig`, theme scaffold directory. Configure branch protection on `main`. Set up deploy keys or GitHub integration. |
| **Priority** | P0 |
| **Story Points** | 2 |
| **Dependencies** | None |
| **Blocks** | E-INFRA-02 |
| **RTM Refs** | TR-019 |
| **FS Refs** | — |
| **NFR Refs** | NFR §11.3 (folder conventions), NFR §11.6 (theme customization policy) |

```gherkin
Feature: Git Repository Setup

  Scenario: Git repository is created and configured
    Given the project requires version control from day one
    When the developer creates the Git repository
    Then the repository is created and accessible by the development team
    And .gitignore excludes WordPress core, uploads, wp-config.php, env files, caches, and IDE files
    And .gitattributes enforces LF line endings
    And branch protection is enabled on the main branch
    And deploy keys or GitHub integration is configured for hosting deployment
```

---

#### Story E-PREREQ-08: Verify Google Analytics & Search Console Access

| Field | Value |
|---|---|
| **Story ID** | E-PREREQ-08 |
| **Epic** | E-PREREQ |
| **Title** | Verify Google Analytics & Search Console Access |
| **Type** | Technical Task |
| **Persona** | Developer |
| **Business Goal** | I want to verify GA4, GSC, and GTM access so that analytics implementation in Sprint 5 is not blocked by access issues. |
| **Description** | Verify client has GA4 account access and GSC access. If not, create new GA4 property, GSC domain property, and GTM container. |
| **Priority** | P1 |
| **Story Points** | 3 |
| **Dependencies** | E-PREREQ-06 (Q15 — analytics access) |
| **Blocks** | E-SEO-05, E-SEO-06 |
| **RTM Refs** | ANL-001, ANL-002 |
| **FS Refs** | FS §13.4 (analytics monitoring) |
| **NFR Refs** | NFR §13.4 (analytics monitoring) |

```gherkin
Feature: Analytics Access Verification

  Scenario: GA4, GSC, and GTM access is verified
    Given analytics tracking is required for Sprint 5 implementation
    When the developer verifies access to Google services
    Then a GA4 property is created and verified
    And a GSC domain property is created and verified
    And a GTM container is created
    And the developer has access to all three services
```

---

#### Story E-PREREQ-09: Engage Legal Counsel

| Field | Value |
|---|---|
| **Story ID** | E-PREREQ-09 |
| **Epic** | E-PREREQ |
| **Title** | Engage Legal Counsel for Privacy Policy Review |
| **Type** | Technical Task |
| **Persona** | Client |
| **Business Goal** | I want to engage a qualified Dutch privacy lawyer so that the privacyverklaring is legally reviewed before launch. |
| **Description** | Client engages qualified Dutch privacy lawyer to review privacyverklaring and cookiebeleid before Sprint 6. Developer provides draft content. |
| **Priority** | P0 |
| **Story Points** | 2 |
| **Dependencies** | None |
| **Blocks** | E-COMPLY-01, E-COMPLY-02 |
| **RTM Refs** | CMP-001, CMP-013 |
| **FS Refs** | FS §4.19 (legal pages), FS §7.2 (privacy policy) |
| **NFR Refs** | NFR §7.2 (privacy policy legal review) |

```gherkin
Feature: Legal Counsel Engagement

  Scenario: Privacy lawyer is engaged for legal review
    Given the privacyverklaring and cookiebeleid require legal review before launch
    When the client engages a qualified Dutch privacy lawyer
    Then the lawyer is briefed on the review scope
    And a timeline is agreed for review completion before Sprint 7
    And ARR R04 (legal review delay risk) is mitigated
```

---

### 4.2 Sprint 1 — Infrastructure & Foundation (E-INFRA)

**Epic Goal:** Provision infrastructure, install WordPress, build theme foundation, implement design system. Sprint 1 must complete before ANY page template work begins.

---

#### Story E-INFRA-01: Install WordPress Core + Configure Settings

| Field | Value |
|---|---|
| **Story ID** | E-INFRA-01 |
| **Epic** | E-INFRA |
| **Title** | Install WordPress 6.7+ + Configure Core Settings |
| **Type** | Technical Task |
| **Persona** | Developer |
| **Business Goal** | I want WordPress installed and configured on the staging environment so that all subsequent development has a platform to run on. |
| **Description** | Install WordPress 6.7+ on staging. Configure: permalink structure `/%postname%/`, category base `kennisbank`, timezone Europe/Amsterdam, date format `j F Y`, language nl_NL, disable comments and pingbacks, disable post via email, change database prefix to `hds_`, generate fresh salts, create 2 admin accounts with non-obvious usernames, set `DISALLOW_FILE_EDIT=true`. |
| **Priority** | P0 |
| **Story Points** | 5 |
| **Dependencies** | E-PREREQ-05 (hosting) |
| **Blocks** | E-INFRA-02 through E-INFRA-08 |
| **RTM Refs** | TR-001..003, TR-023, TR-033, SEC-011..013 |
| **FS Refs** | — |
| **NFR Refs** | NFR §3.8 (database), NFR §6.4 (firewall), NFR §12.3 (PHP), NFR §12.4 (WP) |

```gherkin
Feature: WordPress Core Installation

  Scenario: WordPress is installed and configured
    Given managed WordPress hosting is provisioned on staging
    When the developer installs WordPress 6.7+
    Then WordPress 6.7+ is installed and accessible on staging
    And permalink structure is set to /%postname%/
    And category base is set to kennisbank
    And timezone is Europe/Amsterdam
    And language is nl_NL
    And comments and pingbacks are disabled site-wide
    And database prefix is changed from wp_ to hds_
    And salts are generated fresh and unique
    And 2 admin accounts exist with non-obvious usernames
    And DISALLOW_FILE_EDIT is set to true

  Scenario: WordPress is not installed on production yet
    Given the project is in Sprint 1
    When the developer provisions hosting
    Then the production environment remains empty (ready, not yet populated)
    And all work is done on the staging environment
```

---

#### Story E-INFRA-02: Install & Configure All Plugins

| Field | Value |
|---|---|
| **Story ID** | E-INFRA-02 |
| **Epic** | E-INFRA |
| **Title** | Install & Configure All Plugins |
| **Type** | Technical Task |
| **Persona** | Developer |
| **Business Goal** | I want all plugins installed and activated so that the complete application stack is ready for development. |
| **Description** | Install and activate: WooCommerce 9.x+, Gravity Forms, Rank Math Pro, FlyingPress, Complianz Premium, Wordfence Premium, Post SMTP, BlogVault/UpdraftPlus, ShortPixel/Imagify, Relevanssi, WP-Optimize. Enter license keys for premium plugins. Verify no plugin conflicts. Enable auto-updates for minor releases. |
| **Priority** | P0 |
| **Story Points** | 5 |
| **Dependencies** | E-INFRA-01, E-PREREQ-03 |
| **Blocks** | E-INFRA-03 through E-INFRA-08 |
| **RTM Refs** | TR-006..012, TR-031..032 |
| **FS Refs** | FS §4.11 (Relevanssi search), FS §4.15 (Gravity Forms), FS §4.16 (Complianz) |
| **NFR Refs** | NFR §3.4 (caching plugins), NFR §6.4 (Wordfence), NFR §7.1 (Complianz) |

```gherkin
Feature: Plugin Installation

  Scenario: All 13 plugins are installed and activated
    Given WordPress 6.7+ is installed on staging
    When the developer installs all plugins from the approved list
    Then all 13 plugin categories are installed and activated
    And license keys are entered for premium plugins
    And the WordPress admin loads without errors
    And the frontend loads without errors
    And plugin auto-updates are enabled for minor releases

  Scenario: No plugin conflicts exist
    Given all plugins are installed
    When the developer verifies the site
    Then WP admin loads without PHP errors in debug.log
    And the frontend loads without JavaScript errors in the browser console
    And no known plugin conflicts are detected
```

---

#### Story E-INFRA-03: Configure Cloudflare CDN & SSL

| Field | Value |
|---|---|
| **Story ID** | E-INFRA-03 |
| **Epic** | E-INFRA |
| **Title** | Configure Cloudflare CDN & SSL |
| **Type** | Technical Task |
| **Persona** | Developer |
| **Business Goal** | I want Cloudflare CDN configured so that the site has edge caching, SSL, and WAF protection. |
| **Description** | Set up Cloudflare CDN for `helderduidelijkschoon.nl`. Configure: SSL/TLS Full (Strict), Always Use HTTPS, HSTS, auto-minify, Polish image optimization, cache bypass rules for WooCommerce pages, WAF rules (block xmlrpc.php, rate-limit login, WordPress managed ruleset if Pro). |
| **Priority** | P0 |
| **Story Points** | 5 |
| **Dependencies** | E-PREREQ-05 (hosting), E-INFRA-01 (WordPress) |
| **Blocks** | None directly |
| **RTM Refs** | SEC-001, SEC-016, PERF-012 |
| **FS Refs** | FS §12.5 (WC cache bypass) |
| **NFR Refs** | NFR §3.4 (cache bypass URLs), NFR §3.7 (CDN), NFR §6.4 (firewall layer 2) |

```gherkin
Feature: Cloudflare CDN Configuration

  Scenario: Cloudflare CDN is fully configured
    Given the domain is pointed to Cloudflare nameservers
    When the developer configures Cloudflare settings
    Then HTTPS is enforced (HTTP returns 301 to HTTPS)
    And the HSTS header is present (max-age=31536000; includeSubDomains; preload)
    And SSL grade is A+ via SSL Labs
    And auto-minify for CSS/JS/HTML is enabled
    And Polish image optimization is enabled

  Scenario: WooCommerce pages bypass CDN cache
    Given WooCommerce is installed and configured
    When Cloudflare Page Rules are configured
    Then /winkelmand/* returns CF-Cache-Status: BYPASS
    And /afrekenen/* returns CF-Cache-Status: BYPASS
    And /mijn-account/* returns CF-Cache-Status: BYPASS
    And /wp-admin/* returns CF-Cache-Status: BYPASS

  Scenario: WAF rules block attack vectors
    Given Cloudflare WAF is configured
    When a request is made to /xmlrpc.php
    Then the request is blocked (403 or challenge)
    And login URL is rate-limited
```

---

#### Story E-INFRA-04: Configure SMTP & Email Deliverability

| Field | Value |
|---|---|
| **Story ID** | E-INFRA-04 |
| **Epic** | E-INFRA |
| **Title** | Configure SMTP & Email Deliverability |
| **Type** | Technical Task |
| **Persona** | Developer / Client |
| **Business Goal** | I want SMTP configured with SPF/DKIM/DMARC so that all form submissions and WooCommerce orders reliably deliver email. |
| **Description** | Configure Post SMTP with transactional email service (SendGrid/Mailgun/SES or hosting-provided SMTP). Configure SPF, DKIM, DMARC DNS records. Verify deliverability: send test email to `info@helderduidelijkschoon.nl`, verify not in spam. Enable Post SMTP email logging. |
| **Priority** | P0 |
| **Story Points** | 5 |
| **Dependencies** | E-PREREQ-04 (DNS access), E-INFRA-01 (WordPress) |
| **Blocks** | E-CORE-09 (contact form email delivery) |
| **RTM Refs** | TR-013, SEC-001 |
| **FS Refs** | FS §4.15 (email notifications), FS §9.4 (form error — SMTP down) |
| **NFR Refs** | NFR §4.1, NFR §10.3 (email log) |

```gherkin
Feature: SMTP Email Configuration

  Scenario: SMTP is configured and delivering email
    Given Post SMTP plugin is installed
    When the developer configures the transactional email service
    Then Post SMTP is configured and sending email
    And a test email is delivered to info@helderduidelijkschoon.nl within 2 minutes
    And the test email is NOT in the spam folder
    And Post SMTP email log is enabled

  Scenario: Email authentication records are configured
    Given DNS access is available
    When the developer configures email authentication
    Then the SPF record includes the sending service
    And DKIM is configured for the sending domain
    And DMARC policy is set (p=none initially for monitoring)
```

---

#### Story E-INFRA-05: Configure Daily Backups & Verify

| Field | Value |
|---|---|
| **Story ID** | E-INFRA-05 |
| **Epic** | E-INFRA |
| **Title** | Configure Daily Backups & Test Restore |
| **Type** | Technical Task |
| **Persona** | Developer |
| **Business Goal** | I want automated daily backups configured and a test restore verified so that the site is protected against data loss. |
| **Description** | Configure automated daily backups (BlogVault/UpdraftPlus). Set retention: 30 daily, 4 weekly, 12 monthly. Configure offsite storage. Run first backup. Restore to test environment to verify integrity. Configure backup failure email alerts. Set up WooCommerce monthly order CSV export for 7-year retention. |
| **Priority** | P0 |
| **Story Points** | 5 |
| **Dependencies** | E-PREREQ-05 (hosting), E-INFRA-01 (WordPress) |
| **Blocks** | E-QA-06 (backup verification in QA) |
| **RTM Refs** | OPS-001, CMP-006 |
| **FS Refs** | — |
| **NFR Refs** | NFR §4.3 (backup strategy), NFR §4.4 (recovery), NFR §6.11 (backup encryption) |

```gherkin
Feature: Daily Backup Configuration

  Scenario: Daily backups are configured and verified
    Given WordPress is installed on staging
    When the developer configures the backup plugin
    Then the daily backup schedule is configured
    And the first backup is completed successfully
    And offsite storage is verified (backup file present in cloud storage)
    And backup failure email alerts are configured

  Scenario: Backup restore is tested
    Given a backup exists in offsite storage
    When the developer restores the backup to a test environment
    Then all pages load correctly
    And the admin login works
    And forms submit successfully
    And WooCommerce checkout functions (if active)
    And the backup restore procedure is documented

  Scenario: WooCommerce order data is separately exported
    Given WooCommerce is configured
    When the developer sets up the export
    Then a monthly CSV export of all orders is configured
    And the export is stored in offsite cloud storage
    And the retention period is 7 years (Dutch financial data requirement)
```

---

#### Story E-INFRA-06: Build Theme Foundation (Header, Footer, Base Layout)

| Field | Value |
|---|---|
| **Story ID** | E-INFRA-06 |
| **Epic** | E-INFRA |
| **Title** | Build Theme Foundation |
| **Type** | Technical Task |
| **Persona** | Developer |
| **Business Goal** | I want the complete theme scaffold built so that all page template development can proceed. |
| **Description** | Build the theme foundation: (a) `theme.json` with design tokens, (b) `header.php` template part, (c) `footer.php` template part (5-column), (d) `functions.php` bootstrap, (e) base CSS (reset, typography, utilities, responsive grid), (f) `404.php`, (g) `search.php`. Hybrid block theme approach (theme.json + PHP templates + Block Editor). NOT FSE. |
| **Priority** | P0 |
| **Story Points** | 13 |
| **Dependencies** | E-PREREQ-01 (theme selection), E-INFRA-01 (WordPress), E-PREREQ-06 (MI-07, MI-08 brand tokens if available; if not, use defaults) |
| **Blocks** | ALL page template stories (E-CORE-01 through E-CORE-09, E-SUPPORT-01 through E-SUPPORT-07) |
| **RTM Refs** | TR-018, TR-020, TR-034, ACC-002..005, ACC-009..012, ACC-015..016, UIX-001..004 |
| **FS Refs** | FS §4.1 (Homepage), FS §4.13 (Header), FS §4.14 (Footer), FS §4.17 (404) |
| **NFR Refs** | NFR §8 (accessibility), NFR §11.1 (coding standards), NFR §11.3 (folder conventions) |

```gherkin
Feature: Theme Foundation Construction

  Scenario: Theme.json is built with design tokens
    Given the theme approach is resolved to hybrid block theme
    When the developer creates theme.json
    Then design tokens are defined: color palette, typography (Open Sans), font sizes, spacing scale, shadows, layout widths
    And block styles for buttons, groups, and lists are configured
    And custom templates (Service, Contact, Quote, Category Landing, About, Legal, FAQ) are declared

  Scenario: Header renders correctly
    Given the theme is activated
    When the developer views any page
    Then the logo (or site title fallback) is displayed and links to /
    And the primary navigation is rendered via wp_nav_menu
    And the phone number 0164-652846 is displayed as a clickable tel: link
    And the cart icon is displayed (conditional on WooCommerce active)
    And the skip-to-content link is the first focusable element

  Scenario: Footer renders correctly
    Given the theme is activated
    When the developer views any page
    Then the footer displays a 5-column navigation grid
    And company information is rendered from Customizer values
    And legal links (Privacyverklaring, Cookiebeleid, Algemene Voorwaarden, Disclaimer) are present
    And the copyright line displays "© [YEAR] HDS Onderhoudsdiensten"
    And social icons link to Facebook and Instagram profiles

  Scenario: 404 page renders correctly
    Given the theme is activated
    When the developer navigates to a non-existent URL
    Then the page returns HTTP 404
    And the heading "Pagina niet gevonden" is displayed
    And a search bar is present and functional
    And key links (Home, Diensten, Contact, FAQ) are displayed
    And phone and email links are functional

  Scenario: Search results page renders correctly
    Given the theme is activated
    When the developer searches for a term
    Then results are displayed with title, excerpt, and "Lees meer" link
    And pagination is displayed if more than 10 results
    And "Geen resultaten gevonden" is displayed when no results exist
```

---

#### Story E-INFRA-07: Register Block Patterns & Custom Blocks

| Field | Value |
|---|---|
| **Story ID** | E-INFRA-07 |
| **Epic** | E-INFRA |
| **Title** | Register Block Patterns & Custom Blocks |
| **Type** | Technical Task |
| **Persona** | Developer |
| **Business Goal** | I want all block patterns and custom blocks registered so that page content can be built rapidly via the Block Editor. |
| **Description** | Register 16 block patterns (Hero, Service Card Grid, USP Grid, CTA Banner, Content+Image, Service Icon List, Client Logo Carousel, Testimonial Block, FAQ Accordion, Cross-Sell Services, Job Vacancy Card, Download Card List, Contact Info+Map, Latest Blog Posts, Related Posts, 404 Content). Register 4 custom blocks with `render_callback` (hds/service-card, hds/testimonial, hds/job-listing, hds/contact-info). Register 7 block styles (primary, secondary, cta, card, banner, icon-list, no-bullet). |
| **Priority** | P0 |
| **Story Points** | 8 |
| **Dependencies** | E-INFRA-06 (theme foundation) |
| **Blocks** | All page building stories |
| **RTM Refs** | TR-021, UIX-008..013 |
| **FS Refs** | FS §4.1 (homepage blocks), FS §4.2 (service page cross-sell), FS §4.5 (testimonials), FS §8.3 (contact info) |
| **NFR Refs** | NFR §8.2 (keyboard-operable patterns), NFR §11.2 (block naming) |

```gherkin
Feature: Block Pattern Registration

  Scenario: All block patterns are registered and visible in the Block Editor
    Given the theme is activated
    When the developer opens the Block Editor on any page
    Then all 16 block patterns are visible in the inserter under "HDS Patronen"
    And the Hero Section pattern inserts correctly
    And the CTA Banner pattern inserts correctly
    And patterns are editable after insertion
    And no JavaScript errors occur when inserting patterns

Feature: Custom Block Registration

  Scenario: All 4 custom blocks are registered
    Given the theme is activated
    When the developer opens the Block Editor
    Then the hds/service-card block is available and renders a page selector
    And the hds/testimonial block is available with count and rating controls
    And the hds/job-listing block is available with count and empty state controls
    And the hds/contact-info block is available with field visibility toggles
    And all 4 blocks render correctly on the frontend via their render_callbacks
```

---

#### Story E-INFRA-08: Implement Design System in Code

| Field | Value |
|---|---|
| **Story ID** | E-INFRA-08 |
| **Epic** | E-INFRA |
| **Title** | Implement Design System in Code |
| **Type** | Technical Task |
| **Persona** | Developer |
| **Business Goal** | I want the design system implemented as CSS custom properties and component styles so that all pages are visually consistent. |
| **Description** | Convert design tokens into CSS custom properties in `assets/css/main.css`. Implement: color palette, typography scale, spacing scale, border radius tokens, shadow tokens, breakpoint mixins, block style variations, base component styles (buttons, cards, banners, forms, navigation). |
| **Priority** | P0 |
| **Story Points** | 8 |
| **Dependencies** | E-PREREQ-06 (MI-07, MI-08 — design tokens; if not provided, use default palette and Open Sans), E-INFRA-06 (theme foundation) |
| **Blocks** | All page building stories |
| **RTM Refs** | TR-018, ACC-001, ACC-007, ACC-011 |
| **FS Refs** | FS §4.13 (header styling), FS §4.14 (footer styling) |
| **NFR Refs** | NFR §3.6 (CSS delivery), NFR §8.1 (color contrast), NFR §8.2 (touch targets) |

```gherkin
Feature: Design System Implementation

  Scenario: CSS custom properties are defined
    Given the design tokens are resolved (defaults or client-provided MI-07/MI-08)
    When the developer implements main.css
    Then CSS custom properties are defined for all colors (primary, secondary, accent, neutral, status)
    And typography scale is implemented per the specification
    And spacing scale is implemented (4px-based)
    And border radius and shadow tokens are defined
    And breakpoint mixins are defined (mobile-first: min-width)

  Scenario: Component styles are implemented
    Given the CSS custom properties are defined
    When the developer implements component styles
    Then button variants render correctly (.btn-cta, .btn, is-style-secondary)
    And card component renders with white bg, border-radius, shadow
    And banner component renders with colored background
    And form elements are styled consistently
    And navigation dropdown is styled
    And mobile menu toggle is styled

  Scenario: Block style variations are visually distinct
    Given the theme is activated
    When the developer applies block styles in the Block Editor
    Then is-style-primary renders filled primary button
    And is-style-secondary renders outlined button
    And is-style-cta renders larger button with arrow
    And is-style-card renders card with shadow
    And is-style-banner renders full-width colored background
    And is-style-icon-list renders custom bullet icons
    And is-style-no-bullet removes bullets
```

---

### 4.3 Sprint 2 — Core Pages & Conversion (E-CORE)

**Epic Goal:** Build Home page, all 7 service pages, 2 category landings, Contact, Offerte, Bedankt, and 404. This is the MVP.

---

#### Story E-CORE-01: Build Home Page Template + Content

| Field | Value |
|---|---|
| **Story ID** | E-CORE-01 |
| **Epic** | E-CORE |
| **Title** | Build Home Page Template + Content |
| **Type** | User Story |
| **Persona** | Visitor (facility manager / business owner) |
| **Business Goal** | As a visitor, I want a professional homepage that clearly communicates HDS's services, USPs, and trust signals, so that I am motivated to request a quote. |
| **Description** | Build `front-page.php`. Populate with all 8 content blocks: Hero, Service Card Grid (7 services), USP Grid, Client Logo Carousel (conditional — hide if empty), Testimonial Block (conditional — hide if empty), CTA Banner, Service Area, Latest Blog Posts (conditional — hide if empty). |
| **Priority** | P0 |
| **Story Points** | 8 |
| **Dependencies** | E-INFRA-06 (theme), E-INFRA-07 (block patterns), E-INFRA-08 (design system) |
| **Blocks** | E-CORE-02 (service template pattern reference) |
| **RTM Refs** | FR-013, CON-001, SEO-012, UIX-008..012 |
| **FS Refs** | FS §4.1 (Homepage full specification) |
| **NFR Refs** | NFR §8.5 (ARIA landmarks) |

```gherkin
Feature: Home Page

  Scenario: Home page renders all 8 content blocks
    Given the theme is activated and block patterns are registered
    When the developer views the homepage at /
    Then the Hero section displays the tagline "Helder en Duidelijk voor het Schoonste resultaat!" as H1
    And the CTA button links to /offerte-aanvragen/
    And the Service Card Grid displays 7 service cards in menu_order sequence
    And the USP Grid displays 4-6 USP cards
    And the CTA Banner displays with link to /offerte-aanvragen/
    And the Service Area section displays the regional coverage text

  Scenario: Empty sections are hidden when no data exists
    Given no client logos have been uploaded
    And no testimonials have been added to the CPT
    And no blog posts have been published
    When the developer views the homepage
    Then the Client Logo Carousel section is not rendered (display: none)
    And the Testimonial Block section is not rendered (display: none)
    And the Latest Blog Posts section is not rendered (display: none)

  Scenario: Home page meets content standards
    Given the homepage content is published
    When the content is audited
    Then the page contains >= 300 words of Dutch content
    And a unique title tag is set
    And a unique meta description is set
    And no lorem ipsum or placeholder text is present

  Scenario: Home page is responsive
    Given the homepage is viewed
    When the viewport is set to 375px (mobile)
    Then the layout stacks vertically without horizontal scroll
    When the viewport is set to 768px (tablet)
    Then the Service Card Grid displays 2 columns
    When the viewport is set to 1024px (desktop)
    Then the Service Card Grid displays 3 columns
```

---

#### Story E-CORE-02: Build Service Page Template

| Field | Value |
|---|---|
| **Story ID** | E-CORE-02 |
| **Epic** | E-CORE |
| **Title** | Build Service Page Template |
| **Type** | Technical Task |
| **Persona** | Developer |
| **Business Goal** | I want the Service page template built so that all 7 service pages have a consistent, structured layout. |
| **Description** | Build `page-templates/page-service.php`. Layout: Breadcrumbs → Hero (H1 + subtitle + CTA) → Content Area (the_content) → Cross-Sell Services → CTA Banner → Optional FAQ. Register "Service" template. Add custom fields: subtitle, hero image, service icon, CTA override text via `register_post_meta()`. |
| **Priority** | P0 |
| **Story Points** | 8 |
| **Dependencies** | E-INFRA-06 (theme), E-INFRA-07 (block patterns) |
| **Blocks** | E-CORE-03 through E-CORE-08 |
| **RTM Refs** | FR-004..010, TR-021, ACC-004 |
| **FS Refs** | FS §4.2 (Service pages full specification) |
| **NFR Refs** | NFR §3.2 (Lighthouse templates), NFR §8.5 (schema output) |

```gherkin
Feature: Service Page Template

  Scenario: Service template is selectable and renders correctly
    Given the theme is activated
    When the developer creates a new Page and selects the "Service" template
    Then the template is listed in the Page Attributes → Template dropdown
    And the page renders with Breadcrumbs → Hero → Content → Cross-Sell → CTA → FAQ sections
    And custom fields (hds_subtitle, hds_hero_image, hds_service_icon, hds_cta_override) save and display correctly
    And the Service schema JSON-LD is output in the page head

  Scenario: Custom field defaults work when fields are empty
    Given a Service template page is created without setting any custom fields
    When the page is viewed
    Then the Hero section displays the page title as H1
    And no subtitle is displayed (no empty element)
    And no hero background image is displayed (no empty element)
    And the CTA button uses default text "Vrijblijvende offerte"
    And the CTA button links to /offerte-aanvragen/

  Scenario: Service template is responsive
    Given a service page is created
    When the viewport is tested at 375px, 768px, 1024px, 1440px
    Then the layout is responsive at all breakpoints
    And no PHP errors or warnings are logged
```

---

#### Stories E-CORE-03 through E-CORE-08: Service Pages (P02–P08) and Category Landings (P09–P10)

**Note:** Stories E-CORE-03 through E-CORE-08 are grouped for brevity. Each follows the same pattern — create a Page with Service or Category Landing template, write 300+ (service) or 500+ (landing) words of Dutch content, set cross-links, set CTA.

| Story ID | Page | URL | Word Count | Service Type | RTM Refs | FS Ref |
|---|---|---|---|---|---|---|
| E-CORE-03 | P02 Glasbewassing | `/glasbewassing/` | 300+ | Existing — Migrate + Expand | FR-004, CON-002, SEO-001 | FS §4.2 |
| E-CORE-04 | P03 Gevelreiniging | `/gevelreiniging/` | 300+ | Existing — Standardize naming | FR-005, CON-003, SEO-002 | FS §4.2 |
| E-CORE-05 | P04 Reguliere Schoonmaak ★CRITICAL | `/reguliere-schoonmaak/` | 300+ | NEW (was 404) | FR-006, CON-004, SEO-003 | FS §4.2 |
| E-CORE-06 | P05 Vloeronderhoud + P06 VVE Service + P07 Oplevering Schoonmaak | respective URLs | 300+ each | Migrate + Expand (3 pages) | FR-007..009, CON-005..007, SEO-004..006 | FS §4.2 |
| E-CORE-07 | P08 Industriele Schoonmaak | `/industriele-schoonmaak/` | 300+ | Rebuild from scratch (was 60 words) | FR-010, CON-008, SEO-007 | FS §4.2 |
| E-CORE-08 | P09 Glas & Gevel + P10 Schoonmaakdiensten | `/glas-en-gevel/`, `/schoonmaakdiensten/` | 500+ each | NEW | FR-011..012, CON-009..010, SEO-008..009 | FS §4.3 |

**Shared Acceptance Criteria (Gherkin):**

```gherkin
Feature: Service Pages

  Scenario Outline: Each service page renders correctly
    Given the Service template is built and the page is created
    When the developer views "<url>"
    Then the page returns HTTP 200
    And the H1 is present exactly once
    And the content is >= "<wordCount>" words of Dutch
    And minimum 2 H2 sections are present
    And the CTA links to /offerte-aanvragen/
    And the Service schema JSON-LD is valid via Google Rich Results Test
    And a unique title tag (50–60 chars) is set
    And a unique meta description (150–160 chars) is set
    And the page is responsive at 375px, 768px, 1024px, 1440px
    And no PHP errors are logged

    Examples:
      | url                           | wordCount |
      | /glasbewassing/               | 300       |
      | /gevelreiniging/              | 300       |
      | /reguliere-schoonmaak/        | 300       |
      | /vloeronderhoud/              | 300       |
      | /vve-service/                 | 300       |
      | /oplevering-schoonmaak/       | 300       |
      | /industriele-schoonmaak/      | 300       |

  Scenario: P03 Gevelreiniging uses standardized naming
    Given the service page at /gevelreiniging/ is created
    When the page is viewed
    Then the H1 reads "Gevelreiniging"
    And the H1 does NOT read "Gevelonderhoud"
    And the URL is /gevelreiniging/
    And the nav label is "Gevelreiniging"

  Scenario: P04 Reguliere Schoonmaak is accessible (was HTTP 404 on current site)
    Given the service page at /reguliere-schoonmaak/ is created
    When the developer navigates to the page
    Then the page returns HTTP 200
    And the old URL /?page_id=318 returns 301 to /reguliere-schoonmaak/
    And the page is linked from navigation, homepage service grid, and footer

  Scenario: Cross-links to related services are present on each service page
    Given each service page is created
    When the cross-sell section is configured
    Then P02 Glasbewassing links to P03, P04, P07
    And P03 Gevelreiniging links to P02, P08
    And P04 Reguliere Schoonmaak links to P05, P02, P06
    And P05 Vloeronderhoud links to P04, P07
    And P06 VVE Service links to P04, P02
    And P07 Oplevering Schoonmaak links to P04, P02, P05
    And P08 Industriele Schoonmaak links to P04, P03

Feature: Category Landing Pages

  Scenario Outline: Each category landing page renders correctly
    Given the Category Landing template is built and the page is created
    When the developer views "<url>"
    Then the page returns HTTP 200
    And the content is >= "<wordCount>" words of Dutch
    And the Service Card Grid displays the correct sub-services
    And card links point to correct service pages
    And the CTA Banner links to /offerte-aanvragen/
    And the page is linked from the main navigation dropdown parent item

    Examples:
      | url                    | wordCount | subServices                                          |
      | /glas-en-gevel/        | 500       | Glasbewassing (P02), Gevelreiniging (P03)            |
      | /schoonmaakdiensten/   | 500       | P04, P05, P06, P07, P08 (all 5)                     |
```

---

#### Story E-CORE-09: Build Contact Page + Contact Form (CRITICAL)

| Field | Value |
|---|---|
| **Story ID** | E-CORE-09 |
| **Epic** | E-CORE |
| **Title** | Build Contact Page + Contact Form (GF-1) |
| **Type** | User Story |
| **Persona** | Visitor (potential client) |
| **Business Goal** | As a potential client, I want to contact HDS via a working contact form, so that I can request information or a quote. |
| **Description** | Build `page-templates/page-contact.php`. Create Page at `/contact/`. Two-column layout (form left 60%, contact info right 40%). Configure Gravity Forms contact form (GF-1: 9 fields + reCAPTCHA v3 + honeypot). Configure confirmation email to user and notification email to info@. Build Contact Info Block with conditional rendering (address, KVK, BTW, hours only if Customizer values exist). |
| **Priority** | P0 |
| **Story Points** | 13 |
| **Dependencies** | E-CORE-02 (templates approach), E-INFRA-04 (SMTP), E-PREREQ-06 (MI-01 through MI-04 for contact info block — conditional if not provided) |
| **Blocks** | E-CORE-10 (quote form reuses Gravity Forms patterns) |
| **RTM Refs** | FR-001..003, CON-016, SEO-014, SEO-025, SEC-003..006, ACC-007, ACC-014 |
| **FS Refs** | FS §4.8 (Contact page), FS §6.1 (GF-1 form specification) |
| **NFR Refs** | NFR §6.10 (file upload if applicable), NFR §8.4 (form accessibility) |

```gherkin
Feature: Contact Page

  Scenario: Contact page renders correctly
    Given the Contact template is built and the page is created at /contact/
    When the developer navigates to /contact/
    Then the page returns HTTP 200 (was HTTP 500 on current site)
    And a two-column layout is displayed on desktop (form 60%, contact info 40%)
    And the H1 is present
    And the contact form (GF-1) is rendered with all 9 fields

  Scenario: Contact form fields are present and functional
    Given the Contact form (GF-1) is configured in Gravity Forms
    When the developer inspects the form
    Then field 1 "Naam" is a required text field
    And field 2 "Bedrijf" is an optional text field
    And field 3 "E-mailadres" is a required email field with format validation
    And field 4 "Telefoonnummer" is an optional phone field
    And field 5 "Onderwerp" is a required dropdown with 4 options
    And field 6 "Bericht" is a required textarea with minimum 10 characters
    And field 7 "Privacy akkoord" is a required checkbox, unchecked by default, linking to /privacyverklaring/
    And field 8 is a hidden honeypot field
    And field 9 is reCAPTCHA v3 (invisible, badge visible per Google ToS)
    And the submit button reads "Verstuur bericht"

  Scenario: Contact form validates inputs
    Given the Contact form is displayed
    When the user submits with empty required fields
    Then "Dit veld is verplicht." is displayed inline in Dutch for each empty required field
    When the user enters an invalid email address
    Then "Vul een geldig e-mailadres in." is displayed inline
    When the user enters a message shorter than 10 characters
    Then "Uw bericht moet minimaal 10 tekens bevatten." is displayed
    When the user submits without checking the privacy checkbox
    Then "U moet akkoord gaan met de privacyverklaring." is displayed

  Scenario: Contact form submits successfully
    Given all required fields are filled with valid data
    And the privacy checkbox is checked
    When the user clicks "Verstuur bericht"
    Then the button changes to "Versturen..." with a spinner and disabled state
    And the user is redirected to /bedankt/?type=contact
    And a confirmation email is delivered to the user within 2 minutes (not in spam)
    And a notification email with all field values is delivered to info@helderduidelijkschoon.nl within 2 minutes
    And the entry is stored in Gravity Forms database

  Scenario: Contact form blocks spam
    Given the honeypot field is filled (by a bot)
    When the form is submitted
    Then the submission is silently blocked
    And no entry is stored in the database
    And no notification email is sent

  Scenario: Contact Info Block renders conditionally
    Given the Contact page is viewed
    When Customizer fields are inspected
    Then the phone and email sections are always visible
    And the address section is visible only if hds_address is not empty
    And the KVK/BTW section is visible only if hds_kvk or hds_btw is not empty
    And the business hours section is visible only if hds_opening_hours is not empty
    And the map embed is visible only if the address is known and cookie consent is given

  Scenario: Contact page is accessible
    Given the Contact page is viewed
    When the page is audited
    Then all form fields have <label> elements
    And required fields are marked with aria-required
    And form error messages are linked to fields via aria-describedby
    And all elements are keyboard-focusable and operable
    And axe DevTools reports zero critical or serious issues
```

---

#### Story E-CORE-10: Build Offerte Aanvragen Page + Quote Form

| Field | Value |
|---|---|
| **Story ID** | E-CORE-10 |
| **Epic** | E-CORE |
| **Title** | Build Offerte Aanvragen Page + Quote Form (GF-2) |
| **Type** | User Story |
| **Persona** | Visitor (ready to request a quote) |
| **Business Goal** | As a potential client ready to request a quote, I want a dedicated quote form that collects project-specific details so that HDS can provide an accurate vrijblijvende offerte. |
| **Description** | Build `page-templates/page-quote.php`. Create Page at `/offerte-aanvragen/`. Configure Gravity Forms quote form (GF-2: 13 fields + file upload + reCAPTCHA). Post-submit: redirect to `/bedankt/?type=offerte`. Confirmation email with summary. Notification email with file download link. |
| **Priority** | P1 |
| **Story Points** | 8 |
| **Dependencies** | E-CORE-09 (Gravity Forms patterns established), E-INFRA-04 (SMTP) |
| **Blocks** | E-SEO-07 (conversion tracking) |
| **RTM Refs** | FR-019, CON-017, SEO-015, SEC-003..006 |
| **FS Refs** | FS §4.2 (quote page), FS §6.2 (GF-2 form specification) |
| **NFR Refs** | NFR §6.10 (file upload restrictions) |

```gherkin
Feature: Quote Request Page

  Scenario: Quote page renders correctly
    Given the Quote template is built and the page is created at /offerte-aanvragen/
    When the developer navigates to /offerte-aanvragen/
    Then the page returns HTTP 200
    And the quote form (GF-2) is rendered with all 13 fields
    And the H1 is present

  Scenario: Quote form collects project-specific details
    Given the Quote form (GF-2) is configured
    When the developer inspects the form
    Then fields for Naam, Bedrijf, E-mailadres, Telefoonnummer are present and required
    And "Gewenste dienst" checkboxes include all 7 services plus "Anders"
    And "Type gebouw" dropdown includes 7 building types
    And "Postcode / Plaats" field validates Dutch postcode format (1234 AB)
    And the file upload field accepts PDF, JPG, JPEG, PNG, DOC, DOCX up to 5MB
    And the privacy checkbox is unchecked by default

  Scenario: File upload validates server-side
    Given the Quote form is displayed
    When the user uploads a .exe file renamed to .pdf
    Then the upload is rejected by server-side MIME validation
    When the user uploads a file larger than 5MB
    Then "Het bestand is te groot. Maximale grootte: 5 MB." is displayed
    When the user uploads a valid 1MB PDF file
    Then the file is accepted and renamed on the server

  Scenario: Quote form submits successfully
    Given all required fields are filled with valid data
    And a valid file is uploaded
    When the user submits the form
    Then the user is redirected to /bedankt/?type=offerte
    And a confirmation email with a summary of submitted data is sent to the user
    And a notification email with all data and a download link (not inline attachment) for the uploaded file is sent to info@
```

---

#### Story E-CORE-11: Build Bedankt Page (P32)

| Field | Value |
|---|---|
| **Story ID** | E-CORE-11 |
| **Epic** | E-CORE |
| **Title** | Build Bedankt Page |
| **Type** | Technical Task |
| **Persona** | Developer |
| **Business Goal** | I want a Bedankt page that dynamically displays the correct confirmation message based on the form that was submitted. |
| **Description** | Build `page.php` (default) Page at `/bedankt/`. Content: dynamic heading based on `?type=` query parameter (contact vs offerte). Phone number fallback. Links to key pages. Noindex meta tag. Excluded from XML sitemap. |
| **Priority** | P0 |
| **Story Points** | 2 |
| **Dependencies** | E-CORE-09 (form redirects must be configured) |
| **Blocks** | None |
| **RTM Refs** | FR-017, CON-029, SEO-022 |
| **FS Refs** | FS §4.9 (Bedankt page) |
| **NFR Refs** | NFR §9.4 (sitemap exclusion) |

```gherkin
Feature: Bedankt Page

  Scenario: Bedankt page displays dynamic message for contact form
    Given the Contact form has been submitted
    When the user is redirected to /bedankt/?type=contact
    Then the heading reads "Bedankt voor uw bericht"
    And the message reads "Wij streven ernaar binnen 1 werkdag te reageren."
    And the phone number 0164-652846 is visible as fallback contact

  Scenario: Bedankt page displays dynamic message for quote form
    Given the Quote form has been submitted
    When the user is redirected to /bedankt/?type=offerte
    Then the heading reads "Bedankt voor uw offerte aanvraag"
    And the message reads "Wij streven ernaar binnen 1 werkdag contact met u op te nemen voor een vrijblijvende offerte."

  Scenario: Bedankt page is not indexed by search engines
    Given the Bedankt page is published
    When a search engine crawls the page
    Then the page has <meta name="robots" content="noindex, nofollow">
    And the page is excluded from the XML sitemap
```

---

### 4.4 Sprint 3 — Supporting Pages (E-SUPPORT)

**Epic Goal:** Build About pages, legal pages, Referenties, Vacatures, Downloads, FAQ. Stories E-SUPPORT-01 through E-SUPPORT-07 run in parallel since they are independent content.

```gherkin
Feature: Supporting Pages

  Scenario Outline: Each supporting page renders and meets content standards
    Given the relevant page template is built
    When the developer creates "<page>" at "<url>"
    Then the page returns HTTP 200
    And the content is >= "<wordCount>" words of Dutch
    And a unique title tag and meta description are set
    And the page is responsive at all breakpoints
    And no PHP errors are logged

    Examples:
      | page                     | url                          | wordCount |
      | P11 Over HDS             | /over-hds/                   | 500       |
      | P12 Kwaliteit & Veiligheid| /kwaliteit-veiligheid/       | 300       |
      | P13 Referenties          | /referenties/                | 300       |
      | P14 Vacatures            | /vacatures/                  | 300       |
      | P15 Downloads            | /downloads/                  | 150       |
      | P18 Veelgestelde Vragen  | /veelgestelde-vragen/        | 300       |

  Scenario: Legal pages are published and linked
    Given the Legal template is built
    When the developer creates each legal page
    Then P19 /privacyverklaring/ returns HTTP 200
    And P20 /cookiebeleid/ returns HTTP 200 (auto-populated by Complianz)
    And P21 /algemene-voorwaarden/ returns HTTP 200
    And P22 /disclaimer/ returns HTTP 200
    And all 4 legal pages are linked from the footer on every page
    And "Laatst bijgewerkt: [date]" is displayed on each legal page

  Scenario: Vacatures page contains no scanned images
    Given the Vacatures page (P14) is built
    When the page source is inspected
    Then zero JPG images of Word document scans are present
    And all vacancy content is machine-readable HTML text
    And vacancy cards have toggle-to-expand functionality
    And the application mailto: link pre-fills the subject with the vacancy title
    And JobPosting schema is output for each active vacancy

  Scenario: Downloads page serves PDFs from primary domain
    Given PDFs have been migrated from hds-onderhoudsdiensten.nl
    When the Downloads page (P15) is viewed
    Then all PDF download links point to helderduidelijkschoon.nl (not the legacy domain)
    And download cards display file name, description, file type icon, and file size
```

---

### 4.5 Sprint 4 — WooCommerce (E-COMM)

**Epic Goal:** Configure WooCommerce, import 14 Airfixr products, configure Mollie payments, shipping, emails, test purchase flow. **Conditional on client decision (MI-15, Q09).**

```gherkin
Feature: WooCommerce Configuration and Purchase Flow

  Scenario: WooCommerce core is configured
    Given WooCommerce 9.x+ is installed
    When the developer configures the settings
    Then the shop page is accessible at /winkel/
    And the cart page is accessible at /winkelmand/
    And the checkout page is accessible at /afrekenen/
    And the account page is accessible at /mijn-account/
    And the currency is set to EUR with Dutch thousand/decimal separators
    And prices display "excl. BTW" suffix

  Scenario: All 14 Airfixr products are imported correctly
    Given the product data is exported from the old site
    When the developer imports products to the new site
    Then all 14 products are visible at /winkel/
    And product images display correctly (WebP optimized)
    And prices match the old site (verified via spot-check of 5 products)
    And stock status is correct
    And special characters (ë, ï, é) render correctly
    And shop intro text >= 100 words of Dutch

  Scenario: Mollie payment gateway processes payments
    Given Mollie is installed and configured in test mode
    When the developer performs a test purchase
    Then the payment gateway is displayed at checkout
    And a test payment via iDEAL completes successfully
    And the order status is updated after webhook delivery
    And multiple payment methods are displayed (iDEAL, Bancontact, cards, PayPal, SEPA)

  Scenario: Full purchase flow works end-to-end
    Given WooCommerce is fully configured
    When the developer walks through the purchase flow
    Then browsing the shop → viewing product → adding to cart → checkout → payment completes without errors
    And the cart allows quantity updates and item removal
    And guest checkout is functional
    And logged-in checkout is functional
    And the order confirmation email is delivered to the customer
    And the new order notification email is delivered to info@
    And the order is visible in WooCommerce admin
    And the checkout is usable on mobile (responsive)
```

---

### 4.6 Sprint 5 — SEO & Analytics (E-SEO)

**Epic Goal:** Complete SEO foundation, structured data, analytics, and conversion tracking. All content pages must exist before Sprint 5 begins.

```gherkin
Feature: SEO Foundation

  Scenario: SEO metadata is complete
    Given all 32 pages are published with content
    When the SEO audit is performed
    Then every page has a unique <title> of 50–60 characters
    And every page has a unique <meta description> of 150–160 characters
    And Screaming Frog reports zero empty titles or descriptions
    And Screaming Frog reports zero duplicate titles or descriptions

  Scenario: All structured data validates
    Given the schema generation code is active
    When each schema type is tested via Google Rich Results Test
    Then LocalBusiness schema validates with zero errors on Home, Contact, and Over HDS
    And Service schema validates on all 7 service pages
    And FAQPage schema validates on /veelgestelde-vragen/
    And Product schema validates on all 14 product pages
    And JobPosting schema validates on each active vacancy

  Scenario: XML sitemap and robots.txt are functional
    Given Rank Math Pro is configured
    When the developer checks SEO endpoints
    Then /sitemap_index.xml returns HTTP 200 with valid XML
    And /page-sitemap.xml returns HTTP 200 (was 500 on current site)
    And zero attachment pages are included in the sitemap
    And /robots.txt returns HTTP 200 with correct disallow rules

  Scenario: All 301 redirects work correctly
    Given the redirect rules are configured in Rank Math Pro
    When each old URL is tested via httpstatus.io
    Then /glasbewassing (no slash) returns 301 to /glasbewassing/
    And /vve returns 301 to /vve-service/
    And /vve/ returns 301 to /vve-service/
    And /?page_id=318 returns 301 to /reguliere-schoonmaak/
    And all HTTP variants redirect to HTTPS non-www
    And zero redirect chains exist (A → B → C is an A → C 301)

Feature: Analytics Configuration

  Scenario: GA4 and GTM are operational
    Given the GA4 property and GTM container are set up
    When the developer verifies tracking
    Then GA4 real-time reports show page views
    And GTM snippet is present in <head>

  Scenario: Conversion events fire correctly
    Given GA4 and GTM are configured
    When each conversion trigger occurs
    Then phone_click event fires when tel: link is clicked
    And email_click event fires when mailto: link is clicked
    And form_submission event fires on redirect to /bedankt/?type=contact
    And quote_request event fires on redirect to /bedankt/?type=offerte
    And add_to_cart event fires when a product is added
    And purchase event fires when an order is completed
    And all events are visible in GA4 real-time reports
```

---

### 4.7 Sprint 6 — Compliance & Security (E-COMPLY)

**Epic Goal:** GDPR compliance, cookie consent, security hardening, accessibility audit and remediation.

```gherkin
Feature: GDPR and Cookie Compliance

  Scenario: Cookie consent banner functions correctly
    Given Complianz Premium is configured
    When a fresh browser visits the site for the first time
    Then the cookie banner appears with three options: Accepteren, Weigeren, Instellingen aanpassen
    And zero GA/Facebook cookies are loaded before consent (DevTools verified)
    And clicking "Accepteren" enables all categories
    And clicking "Weigeren" disables all non-functional categories
    And consent is logged with timestamp and anonymized IP
    And the cookie settings button is accessible post-consent
    And the reCAPTCHA badge is not obscured by the cookie banner

  Scenario: GDPR form consent is verified
    Given all 3 forms are configured
    When the developer inspects each form
    Then the privacy checkbox is unchecked by default on GF-1 (Contact)
    And the privacy checkbox is unchecked by default on GF-2 (Offerte)
    And the privacy checkbox is unchecked by default on GF-3 (Vacature)
    And each privacy label includes a link to /privacyverklaring/

Feature: Security Hardening

  Scenario: Authentication security is active
    Given Wordfence Premium is configured
    When the developer tests the security configuration
    Then 2FA is enforced on all Administrator, Editor, and Shop Manager accounts
    And the custom login URL is active (not /wp-admin/ or /wp-login.php)
    And 3 failed login attempts result in IP lockout
    And the REST API user endpoint is blocked

  Scenario: XML-RPC and attack surface are closed
    Given security hardening is applied
    When the developer tests endpoints
    Then /xmlrpc.php returns HTTP 403
    And /?author=1 redirects to homepage
    And /wp-json/wp/v2/users returns 403 or empty
    And DISALLOW_FILE_EDIT is true (Theme/Plugin File Editor absent from admin)

Feature: Accessibility Compliance

  Scenario: All templates pass accessibility audit
    Given all 13 page templates are built
    When the accessibility audit is performed
    Then axe DevTools reports zero critical and zero serious issues on all templates
    And WAVE reports zero errors on all templates
    And Lighthouse Accessibility scores 100 on all templates
    And all interactive elements are keyboard-focusable and operable
    And a screen reader test confirms forms, navigation, and content are correctly announced
    And all color combinations pass WCAG AA contrast thresholds
    And touch targets are >= 44px on all mobile navigation and interactive elements
```

---

### 4.8 Sprint 7 — Testing & QA (E-QA)

**Epic Goal:** Complete testing across all categories. Client review and approval on staging.

```gherkin
Feature: Comprehensive QA

  Scenario: Functional QA passes
    Given all pages and forms are built
    When the full functional QA checklist is executed
    Then all 32 pages return HTTP 200 (or appropriate status)
    And Screaming Frog crawl reports zero broken internal links
    And Screaming Frog crawl reports zero orphan pages
    And all 3 forms submit and deliver email within 2 minutes

  Scenario: Cross-browser testing passes
    Given all pages are built
    When pages are tested in Chrome, Firefox, Safari, and Edge
    Then all 13 page templates render consistently across browsers
    And all functionality works (forms, search, navigation, WooCommerce)

  Scenario: Mobile testing passes
    Given all pages are built
    When pages are tested at 375px, 768px, 1024px, and 1440px
    Then no horizontal scroll exists at any breakpoint
    And the mobile menu is fully functional
    And touch targets are >= 44px

  Scenario: Performance testing passes
    Given all pages are optimized
    When performance is tested
    Then PSI Mobile score is >= 90 on Home, 1 service page, and 1 product page
    And PSI Desktop score is >= 95 on the same pages
    And LCP is < 2.5 seconds
    And CLS is < 0.1
    And TTFB is < 600ms

  Scenario: Client approves the staging site
    Given all QA gates are passed
    When the client reviews the staging site
    Then client provides explicit approval for launch
    And any change requests are documented and tracked
```

---

### 4.9 Sprint 8 — Launch & Handover (E-LAUNCH)

**Epic Goal:** Deploy to production, verify, handover.

```gherkin
Feature: Launch

  Scenario: Pre-launch checklist is complete
    Given all QA gates are passed
    When the pre-launch checklist is verified
    Then all 25 checklist items are marked complete
    And old site full backup is verified via test restore
    And DNS TTL is lowered to 300 seconds

  Scenario: Deploy to production succeeds
    Given the pre-launch checklist is complete
    When the developer deploys to production
    Then the new site is live at helderduidelijkschoon.nl
    And all caches are cleared (FlyingPress, Cloudflare, Redis)
    And all 301 redirects are verified on production
    And the XML sitemap is submitted to GSC and Bing
    And GA4 real-time reports show traffic
    And the contact form test submission delivers email from production

  Scenario: Client handover is complete
    Given the site is launched
    When the handover is performed
    Then a 1-hour training session is completed with the client
    And the Beheergids (Website Management Guide, Dutch) is delivered
    And all admin credentials are transferred securely
    And the client signs off on acceptance criteria
```

---

## 5. Acceptance Criteria (Gherkin Format)

All acceptance criteria throughout this backlog are written in Gherkin format following the pattern:

```
Feature: [Feature Name]

  Scenario: [Scenario Name]
    Given [precondition]
    When [action]
    Then [expected outcome]
    And [additional expected outcome]
```

**Quality Rules for Acceptance Criteria:**
1. Every story has at least 3 Gherkin scenarios covering: success path, error path, and edge case.
2. Every AC is independently testable (no AC depends on another AC being run first).
3. Every AC maps to at least one RTM acceptance criterion ID.
4. ACs reference specific URLs, field names, file sizes, and word counts — no vague terms.

---

## 6. Definition of Ready

A User Story is **Ready for Development** when ALL of the following are true:

| # | Criterion | Verified By |
|---|---|---|
| DOR01 | Story has a clear title, persona, business goal, and description | Product Owner |
| DOR02 | All dependencies are identified and either resolved or acknowledged as assumptions | Developer |
| DOR03 | Acceptance criteria are written in Gherkin format and are independently testable | Developer + QA |
| DOR04 | Story is estimated in Story Points (Fibonacci) | Development Team |
| DOR05 | Story is assigned to a Sprint | Scrum Master |
| DOR06 | All prerequisite stories in the same Sprint are either complete or also Ready | Scrum Master |
| DOR07 | Any required client input (MI items) is received or an explicit assumption is documented | Product Owner |
| DOR08 | The story does not depend on any future-Sprint deliverables unless an explicit stub/mock is defined | Developer |
| DOR09 | The story's RTM requirements, FS references, and NFR references are documented | Developer |
| DOR10 | No external blocker exists (hosting access, DNS access, plugin license keys) | Developer |

---

## 7. Definition of Done

A User Story is **Done** when ALL of the following are true:

| # | Criterion | Verified By |
|---|---|---|
| DOD01 | All Gherkin acceptance criteria pass | Developer self-test |
| DOD02 | Code is committed to the appropriate Git branch | Developer |
| DOD03 | Code passes PHPCS (`composer phpcs`) with zero errors | CI pipeline or developer |
| DOD04 | Code passes ESLint (`npm run lint:js`) with zero errors | CI pipeline or developer |
| DOD05 | Code passes Stylelint (`npm run lint:css`) with zero errors | CI pipeline or developer |
| DOD06 | No PHP errors logged in `debug.log` on staging | Developer |
| DOD07 | No JavaScript errors in browser console on the affected pages | Developer |
| DOD08 | Delivered page(s) render correctly on mobile (375px), tablet (768px), and desktop (1024px+) | Developer |
| DOD09 | Relevant NFR gates are verified (accessibility axe check, performance if applicable) | Developer |
| DOD10 | Feature is deployed to staging environment | CI/CD pipeline |
| DOD11 | Dependencies for downstream stories are not broken | Developer |
| DOD12 | Documentation is updated if the story changes architectural decisions | Developer |
| DOD13 | Client review items (if applicable) are flagged for Sprint 7 QA review | Developer |

---

## 8. Story Estimation

All stories are estimated using Fibonacci Story Points.

| Points | Complexity | Typical Effort | Example |
|---|---|---|---|
| **1** | Trivial | < 1 hour | Resolve a single decision (E-PREREQ-02) |
| **2** | Very Simple | 1–2 hours | Setup Git repo (E-PREREQ-07), Bedankt page (E-CORE-11) |
| **3** | Simple | 2–4 hours | Single plugin configuration, single landing page (E-COMM-06) |
| **5** | Moderate | 4–8 hours | Install & configure (E-INFRA-01), single service page (E-CORE-03) |
| **8** | Complex | 1–2 days | Theme foundation (E-INFRA-06), multi-page stories (E-CORE-06) |
| **13** | Very Complex | 2–4 days | Full-page template + form + email flow (E-CORE-09) |
| **21** | Epic | > 1 week | Not used at story level; reserved for spikes requiring research |

### Story Point Summary by Sprint

| Sprint | Epic | Stories | Points | Cumulative |
|---|---|---|---|---|
| Sprint 0 | E-PREREQ | 9 | 28 | 28 |
| Sprint 1 | E-INFRA | 8 | 45 | 73 |
| Sprint 2 | E-CORE | 11 | 72 | 145 |
| Sprint 3 | E-SUPPORT | 7 | 35 | 180 |
| Sprint 4 | E-COMM | 7 | 27 | 207 |
| Sprint 5 | E-SEO | 10 | 53 | 260 |
| Sprint 6 | E-COMPLY | 7 | 30 | 290 |
| Sprint 7 | E-QA | 8 | 41 | 331 |
| Sprint 8 | E-LAUNCH | 9 | 34 | 365 |
| **Total** | **9 Epics** | **76** | **365** | — |

---

## 9. Sprint Planning Recommendation

### 9.1 Sprint 3 — Supporting Pages & Content (Week 5)

**Stories:** E-SUPPORT-01 through E-SUPPORT-07 (7 stories, 35 points)

**Rationale:** All Sprint 3 stories are independent — they use different page templates and different content. They can all run in parallel. Sprint 3 has the most parallelism of any sprint, making it the most efficient use of a 2-developer team. Legal pages (E-SUPPORT-05) must complete so that the footer legal links are live and the privacyverklaring is ready for legal review (MI-17 deadline before Sprint 6).

### 9.2 Sprint 4 — WooCommerce & eCommerce (Week 5–6)

**Stories:** E-COMM-01 through E-COMM-07 (7 stories, 27 points)

**Rationale:** Sprint 4 overlaps with Sprint 3 in timing (Week 5–6). It is staffed by 1 developer while the other completes Sprint 3. E-COMM-01 (WC core config) must run first. E-COMM-02 (product import) follows. E-COMM-03, E-COMM-04, E-COMM-05 run in parallel. E-COMM-06 (Luchtreiniging landing) runs independently. E-COMM-07 (purchase flow test) depends on all prior E-COMM stories.

**Note:** Sprint 4 is conditional on client decision to keep the Airfixr shop (Q09, MI-15). If the client decides to remove the shop, Sprint 4 is reduced to ~5 points (only E-COMM-01 for minimal WC configuration).

### 9.3 Sprint 5 — SEO, Analytics & Optimization (Week 6–7)

**Stories:** E-SEO-01 through E-SEO-10 (10 stories, 53 points)

**Rationale:** Sprint 5 requires all content pages to exist (from Sprints 2, 3, 4). It is the content-dependent sprint. E-SEO-01 (Rank Math configuration) must run first. E-SEO-02 through E-SEO-04 (structured data) follow. E-SEO-05 through E-SEO-07 (analytics) run in parallel. E-SEO-08 through E-SEO-10 (sitemaps, redirects, image optimization) run in parallel after content exists. Staffed by 1 developer (the other moves to Sprint 6 preparation).

### 9.4 Sprint 6 — Compliance, Security & Accessibility (Week 7)

**Stories:** E-COMPLY-01 through E-COMPLY-07 (7 stories, 30 points)

**Rationale:** Sprint 6 is the legal compliance gate. It must complete before the launch decision (Sprint 8). Complianz configuration (E-COMPLY-01), WordPress security hardening (E-COMPLY-02), and form consent verification (E-COMPLY-04) are all parallel. The accessibility audit and remediation (E-COMPLY-07) requires all page templates to exist. Staffed by 1 developer.

---

## 10. Risk Assessment

### Risks by Epic

| Epic | Risk ID | Risk Description | Probability | Impact | Mitigation |
|---|---|---|---|---|---|
| E-PREREQ | R-PR01 | Client does not provide required information (MI-01..25) on time | High | High — blocks dependent stories | Track MI items against deadlines. Use conditional rendering (hide sections if data missing). Document assumptions. Communicate dependencies early. |
| E-PREREQ | R-PR02 | Hosting provisioning delayed by provider | Low | Critical — blocks all development | Select hosting provider in Week 0. Have backup provider ready. |
| E-INFRA | R-IN01 | SMTP misconfiguration causes silent email failure | Medium | Critical — forms appear to work but emails are not delivered | Configure SPF/DKIM/DMARC. Enable Post SMTP email log. Test email delivery as Sprint 1 gate check. |
| E-INFRA | R-IN02 | Plugin conflict discovered late (e.g., Complianz + WP Rocket) | Low | Medium — requires reconfiguration or plugin replacement | All plugins are well-established premium plugins. Test interaction during Sprint 1. |
| E-CORE | R-CR01 | Service page content writing takes longer than estimated | Medium | Medium — pages launch with thin content | Write structural content first (H2 sections, bullet lists). Expand prose in Sprint 3 if needed. Focus on MVP pages first. |
| E-CORE | R-CR02 | Gravity Forms file upload PHP limits too low | Low | Medium — file upload silently fails | Set php.ini: upload_max_filesize=10M, post_max_size=12M, max_execution_time=120. Verify before E-CORE-10. |
| E-CORE | R-CR03 | reCAPTCHA v3 blocks legitimate users with low scores | Low | Medium — legitimate leads blocked | Honeypot catches most spam. Phone number visible on form pages as fallback. Monitor form completion rate post-launch. |
| E-SUPPORT | R-SU01 | Client does not provide vacancy text as HTML (MI-12) | Medium | Low — vacancies page shows empty state | Empty state: "Er zijn momenteel geen openstaande vacatures." Placeholder until client provides text. |
| E-SUPPORT | R-SU02 | Client does not provide testimonial content (MI-11) or client logos (MI-10) | High | Low — sections hidden, not broken | Conditional empty states: testimonials hidden, logo carousel hidden. Pages remain functional. |
| E-COMM | R-EC01 | Client decides to remove Airfixr shop (Q09) | Medium | Medium — Sprint 4 scope reduced | WC core remains installed (required for WooCommerce). Product import and payment gateway stories are skipped. |
| E-COMM | R-EC02 | Mollie webhook blocked by Cloudflare WAF | Low | High — payments not confirmed | Add webhook URL to Cloudflare WAF allowlist. Test webhook delivery in test mode before launch. |
| E-SEO | R-SE01 | Page sitemap returns 500 (same issue as current site) | Low | High — SEO blocking | Use Rank Math Pro (not Yoast). Verify sitemap immediately after configuration. Monitor via GSC. |
| E-COMPLY | R-CM01 | Legal review of privacyverklaring not completed before launch (MI-17) | Medium | Critical — cannot launch legally | Engage lawyer in Sprint 0 (E-PREREQ-09). Draft privacyverklaring content in Sprint 3. Set hard deadline: review complete by Sprint 7. |
| E-QA | R-QA01 | Performance does not meet PSI 90+ mobile target | Medium | Medium — launch with caveat or delay | Performance optimization is progressive. FlyingPress + Cloudflare + image optimization address most issues. Address specific issues during Sprint 7. |
| E-LAUNCH | R-LN01 | DNS propagation delay causes users to see mixed old/new site | Medium | Medium — inconsistent experience | Lower TTL to 300 seconds 24 hours before launch. Verify via whatsmydns.net. Restore TTL after launch. |

---

## 11. Dependency Matrix

### Story Dependencies

```
Sprint 0 ─────────────────────────────────────────────────────────────
  E-PREREQ-01 (Theme) ────────────────────> E-INFRA-06
  E-PREREQ-02 (CPT Slug) ─────────────────> E-SUPPORT-03
  E-PREREQ-03 (Plugins) ──────────────────> E-INFRA-02
  E-PREREQ-04 (Domain/Hosting) ───────────> E-PREREQ-05
  E-PREREQ-05 (Hosting provisioned) ──────> E-INFRA-01
  E-PREREQ-06 (Client answers) ───────────> E-CORE-09, E-SUPPORT-03..05, E-SEO-02, E-COMM-01..04
  E-PREREQ-07 (Git repo) ─────────────────> E-INFRA-02
  E-PREREQ-08 (GA4/GSC) ──────────────────> E-SEO-05, E-SEO-06
  E-PREREQ-09 (Legal counsel) ────────────> E-COMPLY-01, E-COMPLY-02

Sprint 1 ─────────────────────────────────────────────────────────────
  E-INFRA-01 (WP install) ────────────────> E-INFRA-02 ──────> E-INFRA-03 (parallel)
                                            E-INFRA-02 ──────> E-INFRA-04 (parallel)
                                            E-INFRA-02 ──────> E-INFRA-05 (parallel)
  E-INFRA-06 (Theme foundation) ──────────────────────────────────> ALL page stories
    E-INFRA-06 ───────────────────────────> E-INFRA-07 (patterns) ──┐
    E-INFRA-08 (design system) ──────────── parallel with E-INFRA-07 ┘

Sprint 2 ─────────────────────────────────────────────────────────────
  E-CORE-01 (Home) ───────────────────────> E-CORE-02 (Service template)
    E-CORE-02 ────────────────────────────> E-CORE-03..08 (parallel — 6 stories)
  E-CORE-09 (Contact) ────────────────────> E-CORE-10 (Quote)
  E-CORE-11 (Bedankt) ────────────────────> parallel with E-CORE-10

Sprint 3 ─────────────────────────────────────────────────────────────
  E-SUPPORT-01..07 — ALL PARALLEL (independent content, different templates)

Sprint 4 ─────────────────────────────────────────────────────────────
  E-COMM-01 (WC config) ──────────────────> E-COMM-02 (product import)
  E-COMM-01 ──────────────────────────────> E-COMM-03 (payments) ─── parallel
  E-COMM-01 ──────────────────────────────> E-COMM-04 (shipping) ─── parallel
  E-COMM-01 ──────────────────────────────> E-COMM-05 (emails) ───── parallel
  E-COMM-06 (Luchtreiniging) ─────────────> parallel with all above
  ALL ────────────────────────────────────> E-COMM-07 (purchase flow test)

Sprint 5 ─────────────────────────────────────────────────────────────
  ALL content pages ──────────────────────> E-SEO-01..10 (all SEO tasks)

Sprint 6 ─────────────────────────────────────────────────────────────
  E-SUPPORT-05 (legal pages) ─────────────> E-COMPLY-01 (Complianz), E-COMPLY-04 (form consent)
  E-COMPLY-01..07 — MOSTLY PARALLEL

Sprint 7 ─────────────────────────────────────────────────────────────
  ALL previous epics ─────────────────────> E-QA-01..08 (all QA gates)

Sprint 8 ─────────────────────────────────────────────────────────────
  E-QA complete ──────────────────────────> E-LAUNCH-01..09 (sequential launch)
```

### Parallel Execution Opportunities

| Story Group | Stories | Why Parallel |
|---|---|---|
| Sprint 0 Tasks | E-PREREQ-01..03, E-PREREQ-07..09 | All independent architecture/planning decisions |
| Sprint 1 Infrastructure | E-INFRA-03, E-INFRA-04, E-INFRA-05 | All depend on E-INFRA-01/02 but not on each other |
| Service Pages | E-CORE-03..08 (6 stories) | Same template, independent content per page |
| Supporting Pages | E-SUPPORT-01..07 (7 stories) | All independent content, different templates |
| WooCommerce Config | E-COMM-03, E-COMM-04, E-COMM-05 | Depend on E-COMM-01 but not on each other |
| SEO Tasks | E-SEO-02..04, E-SEO-05..07, E-SEO-08..10 | Grouped by sub-domain; parallel within groups |
| Compliance Tasks | E-COMPLY-01..07 | Most are independent configurations |

---

## 12. Traceability Matrix

Every story maps to RTM requirements, Functional Specification sections, and Non-Functional Requirements.

### Full Story Traceability

| Story ID | Epic | RTM Refs | FS Refs | NFR Refs |
|---|---|---|---|---|
| E-PREREQ-01 | E-PREREQ | TR-004, TR-018 | FS §4.1–4.4 | NFR §11.1, NFR §6.7 |
| E-PREREQ-02 | E-PREREQ | FR-041 | FS §4.5 | — |
| E-PREREQ-03 | E-PREREQ | TR-008, TR-009 | FS §11 | NFR §3.4, NFR §9 |
| E-PREREQ-04 | E-PREREQ | INF-001 | — | NFR §4.1 |
| E-PREREQ-05 | E-PREREQ | INF-002, TR-001..002, TR-014 | — | NFR §4.1, NFR §12.3 |
| E-PREREQ-06 | E-PREREQ | BR-009, BR-011, GAP-001..007 | FS §2.3 | NFR §7.2 |
| E-PREREQ-07 | E-PREREQ | TR-019 | — | NFR §11.3, NFR §11.6 |
| E-PREREQ-08 | E-PREREQ | ANL-001, ANL-002 | FS §13.4 | NFR §13.4 |
| E-PREREQ-09 | E-PREREQ | CMP-001, CMP-013 | FS §4.19, FS §7.2 | NFR §7.2 |
| E-INFRA-01 | E-INFRA | TR-001..003, TR-023, TR-033, SEC-011..013 | — | NFR §3.8, NFR §6.4, NFR §12.3, NFR §12.4 |
| E-INFRA-02 | E-INFRA | TR-006..012, TR-031..032 | FS §4.11, FS §4.15, FS §4.16 | NFR §3.4, NFR §6.4, NFR §7.1 |
| E-INFRA-03 | E-INFRA | SEC-001, SEC-016, PERF-012 | FS §12.5 | NFR §3.4, NFR §3.7, NFR §6.4 |
| E-INFRA-04 | E-INFRA | TR-013, SEC-001 | FS §4.15, FS §9.4 | NFR §4.1, NFR §10.3 |
| E-INFRA-05 | E-INFRA | OPS-001, CMP-006 | — | NFR §4.3, NFR §4.4, NFR §6.11 |
| E-INFRA-06 | E-INFRA | TR-018, TR-020, TR-034, ACC-002..005, ACC-009..012, ACC-015..016, UIX-001..004 | FS §4.1, FS §4.13, FS §4.14, FS §4.17 | NFR §8, NFR §11.1, NFR §11.3 |
| E-INFRA-07 | E-INFRA | TR-021, UIX-008..013 | FS §4.1, FS §4.2, FS §4.5, FS §8.3 | NFR §8.2, NFR §11.2 |
| E-INFRA-08 | E-INFRA | TR-018, ACC-001, ACC-007, ACC-011 | FS §4.13, FS §4.14 | NFR §3.6, NFR §8.1, NFR §8.2 |
| E-CORE-01 | E-CORE | FR-013, CON-001, SEO-012, UIX-008..012 | FS §4.1 | NFR §8.5 |
| E-CORE-02 | E-CORE | FR-004..010, TR-021, ACC-004 | FS §4.2 | NFR §3.2, NFR §8.5 |
| E-CORE-03..08 | E-CORE | FR-004..012, CON-002..010, SEO-001..009, SEO-026 | FS §4.2, FS §4.3 | NFR §3.1, NFR §9.3 |
| E-CORE-09 | E-CORE | FR-001..003, CON-016, SEO-014, SEO-025, SEC-003..006, ACC-007, ACC-014 | FS §4.8, FS §6.1 | NFR §6.10, NFR §8.4 |
| E-CORE-10 | E-CORE | FR-019, CON-017, SEO-015, SEC-003..006 | FS §4.2, FS §6.2 | NFR §6.10 |
| E-CORE-11 | E-CORE | FR-017, CON-029, SEO-022 | FS §4.9 | NFR §9.4 |
| E-SUPPORT-01..07 | E-SUPPORT | FR-014..015, FR-041..048, CON-011..015, CON-018..022, CMP-001, MIG-006..008 | FS §4.4–4.7, FS §4.19 | NFR §7.2, NFR §8, NFR §9 |
| E-COMM-01..07 | E-COMM | FR-022..027, WC-001..012, PERF-012 | FS §4.10, FS §12 | NFR §3.4, NFR §12.5 |
| E-SEO-01..10 | E-SEO | SEO-001..028, ANL-001..010 | FS §11 | NFR §3, NFR §9, NFR §13 |
| E-COMPLY-01..07 | E-COMPLY | CMP-001..013, SEC-007..010, ACC-001..020 | FS §4.16, FS §4.19, FS §7, FS §13, FS §14 | NFR §6, NFR §7, NFR §8 |
| E-QA-01..08 | E-QA | PERF-001..006, ACC-001..020, SEO-001..028, SEC-001..016 | FS §16 | NFR §14 |
| E-LAUNCH-01..09 | E-LAUNCH | OPS-001..006, MIG-007..011 | — | NFR §4.4 |

### Traceability Summary

| Coverage Metric | Value |
|---|---|
| Total RTM Requirements | 274 |
| Requirements Traced to Stories | 274 (100%) |
| Total FS Sections Referenced | All sections |
| Total NFR Sections Referenced | All sections |
| Total Acceptance Criteria (Gherkin) | 100+ scenarios |
| Zero Orphan Stories | Confirmed |
| Zero Untraced Requirements | Confirmed |

---

## 13. Final Prioritized Product Backlog

Presented in implementation order sorted by business value. Within each priority tier, stories are ordered by dependency chain.

### P0 — Critical (Must Complete for Launch)

| # | Story ID | Title | Sprint | Points |
|---|---|---|---|---|
| 1 | E-PREREQ-04 | Obtain Domain & Hosting Access | 0 | 3 |
| 2 | E-PREREQ-05 | Provision Managed WordPress Hosting | 0 | 5 |
| 3 | E-PREREQ-06 | Resolve Phase 0 Client Questions | 0 | 8 |
| 4 | E-PREREQ-01 | Resolve Theme Selection | 0 | 3 |
| 5 | E-PREREQ-02 | Resolve CPT Slug Conflict | 0 | 1 |
| 6 | E-PREREQ-07 | Set Up Git Repository | 0 | 2 |
| 7 | E-PREREQ-09 | Engage Legal Counsel | 0 | 2 |
| 8 | E-INFRA-01 | Install WordPress + Configure | 1 | 5 |
| 9 | E-INFRA-02 | Install & Configure All Plugins | 1 | 5 |
| 10 | E-INFRA-04 | Configure SMTP & Email | 1 | 5 |
| 11 | E-INFRA-05 | Configure Daily Backups | 1 | 5 |
| 12 | E-INFRA-06 | Build Theme Foundation | 1 | 13 |
| 13 | E-INFRA-07 | Register Block Patterns & Blocks | 1 | 8 |
| 14 | E-INFRA-08 | Implement Design System | 1 | 8 |
| 15 | E-INFRA-03 | Configure Cloudflare CDN & SSL | 1 | 5 |
| 16 | E-CORE-01 | Build Home Page | 2 | 8 |
| 17 | E-CORE-02 | Build Service Page Template | 2 | 8 |
| 18 | E-CORE-09 | Build Contact Page + Form ★CRITICAL | 2 | 13 |
| 19 | E-CORE-05 | Build Reguliere Schoonmaak ★CRITICAL | 2 | 8 |
| 20 | E-CORE-03 | Build Glasbewassing | 2 | 5 |
| 21 | E-CORE-04 | Build Gevelreiniging | 2 | 5 |
| 22 | E-CORE-06 | Build Vloer+VVE+Oplevering (3 pages) | 2 | 8 |
| 23 | E-CORE-07 | Build Industriele Schoonmaak | 2 | 5 |
| 24 | E-CORE-10 | Build Offerte Aanvragen + Quote Form | 2 | 8 |
| 25 | E-CORE-11 | Build Bedankt Page | 2 | 2 |
| 26 | E-SUPPORT-05 | Build Legal Pages (P19–P22) | 3 | 8 |
| 27 | E-SUPPORT-01 | Build Over HDS (P11) | 3 | 5 |
| 28 | E-SUPPORT-02 | Build Kwaliteit & Veiligheid (P12) | 3 | 3 |
| 29 | E-SEO-01 | Configure Rank Math + Meta | 5 | 8 |
| 30 | E-SEO-02 | Implement LocalBusiness Schema | 5 | 5 |
| 31 | E-SEO-05 | Set Up GA4 via GTM | 5 | 5 |
| 32 | E-SEO-07 | Configure 301 Redirects | 5 | 3 |
| 33 | E-SEO-08 | Generate XML Sitemap | 5 | 3 |
| 34 | E-COMPLY-01 | Configure Cookie Consent (Complianz) | 6 | 5 |
| 35 | E-COMPLY-02 | Configure Wordfence Security | 6 | 5 |
| 36 | E-COMPLY-04 | Verify GDPR Form Consent | 6 | 3 |
| 37 | E-COMPLY-07 | Full Accessibility Audit + Fix | 6 | 8 |
| 38 | E-QA-01 | Functional QA | 7 | 8 |
| 39 | E-QA-03 | Performance Testing | 7 | 5 |
| 40 | E-QA-07 | Client Review + Approval | 7 | 3 |
| 41 | E-LAUNCH-01 | Pre-Launch Checklist | 8 | 3 |
| 42 | E-LAUNCH-03 | Deploy to Production | 8 | 5 |
| 43 | E-LAUNCH-05 | Post-Launch Verification | 8 | 5 |
| 44 | E-LAUNCH-08 | Handover + Training | 8 | 5 |

### P1 — High (Should Complete for Launch)

| # | Story ID | Title | Sprint | Points |
|---|---|---|---|---|
| 45 | E-PREREQ-03 | Resolve Plugin Selections | 0 | 2 |
| 46 | E-PREREQ-08 | Verify GA4/GSC Access | 0 | 3 |
| 47 | E-CORE-08 | Build Category Landing Pages | 2 | 5 |
| 48 | E-SUPPORT-03 | Build Referenties Page (P13) | 3 | 8 |
| 49 | E-SUPPORT-04 | Build Vacatures Page (P14) | 3 | 8 |
| 50 | E-SUPPORT-06 | Migrate Downloads + PDFs (P15) | 3 | 5 |
| 51 | E-COMM-01 | Configure WooCommerce Core | 4 | 3 |
| 52 | E-COMM-02 | Import 14 Airfixr Products | 4 | 5 |
| 53 | E-COMM-03 | Configure Payment Gateway (Mollie) | 4 | 5 |
| 54 | E-COMM-04 | Configure Shipping | 4 | 3 |
| 55 | E-COMM-05 | Configure WC Emails | 4 | 3 |
| 56 | E-COMM-06 | Build Luchtreiniging Landing (P23) | 4 | 3 |
| 57 | E-COMM-07 | Test Full WC Purchase Flow | 4 | 5 |
| 58 | E-SEO-03 | Implement Service Schema | 5 | 5 |
| 59 | E-SEO-04 | Implement FAQPage Schema | 5 | 3 |
| 60 | E-SEO-06 | Set Up GTM + Consent Mode v2 | 5 | 5 |
| 61 | E-SEO-09 | robots.txt Configuration | 5 | 2 |
| 62 | E-SEO-10 | Image Optimization (WebP + alt text) | 5 | 5 |
| 63 | E-COMPLY-03 | Disable XML-RPC + Custom Login URL | 6 | 3 |
| 64 | E-COMPLY-05 | Configure 2FA | 6 | 3 |
| 65 | E-COMPLY-06 | GDPR Data Retention Configuration | 6 | 3 |
| 66 | E-QA-02 | Cross-Browser Testing | 7 | 5 |
| 67 | E-QA-04 | Mobile/Tablet Testing | 7 | 5 |
| 68 | E-QA-05 | Security Audit | 7 | 5 |
| 69 | E-LAUNCH-04 | Clear Caches + Verify Redirects | 8 | 3 |
| 70 | E-LAUNCH-06 | Submit Sitemaps to GSC + Bing | 8 | 2 |
| 71 | E-LAUNCH-09 | Launch Readiness Report | 8 | 2 |

### P2 — Nice to Have (Can Defer to Post-Launch)

| # | Story ID | Title | Sprint | Points |
|---|---|---|---|---|
| 72 | E-SUPPORT-07 | Build Veelgestelde Vragen (P18) | 3 | 5 |
| 73 | E-SEO-02 | Blog Post Metadata (future) | 5 | 3 |
| 74 | E-COMPLY-07 | Print Stylesheet (UX enhancement) | 6 | 2 |
| 75 | E-LAUNCH-02 | Take Old Site Backup | 8 | 2 |
| 76 | E-LAUNCH-07 | Client Handover Beheergids | 8 | 3 |

---

**This Product Backlog is internally consistent with all previous project documents (MPS-001, SAD-001, ADR-001, BKLG-001, ARR-001, RTM-001, FS-001, NFR-001). All stories include Gherkin acceptance criteria and are traceable to the RTM. The backlog is ready for Sprint Planning and Agile development execution.**

**END OF PRODUCT BACKLOG — Version 1.0.0**
