# HDS Onderhoudsdiensten — Master Project Specification

**Document ID:** MPS-001 | **Version:** 1.0.0 | **Status:** Ready for Sprint Planning
**Project:** helderduidelijkschoon.nl — Ground-Up Rebuild
**Language:** Nederlands (nl-NL) | **Date:** July 2026

---

## Document Control

| Role | Name | Sign-Off |
|---|---|---|
| Lead Solution Architect | — | |
| Technical Lead (Development) | — | |
| Client / Business Owner | — | |
| SEO Architect | — | |
| Legal Reviewer (AVG) | — | |

---

## Part A: Project Foundation

### A1. Single Source of Truth Declaration

**This Master Project Specification is the single source of truth for the HDS Onderhoudsdiensten website rebuild.** Where more detail exists, this document references the relevant section of the detailed rebuild specification (`docs/rebuild-spec/`).

**Document Authority Chain:** In case of conflict between documents, this priority order applies (higher overrides lower):
1. **ADR-001** — Binding architectural decisions (WHY)
2. **FS-001** — Functional behavior (WHAT)
3. **SAD-001 / SA-001** — High-level architecture (HOW — system)
4. **WTA-001** — WordPress implementation (HOW — code)
5. **NFR-001** — Quality attributes
6. **SEO-001** — SEO implementation
7. **MPS-001** — This document (consolidated specification)
8. **RTM-001** — Cross-reference index
9. **RS-01 through RS-08** — Detailed implementation specs (reference)
10. **SRC-01 through SRC-08** — Original site analysis (historical, frozen)

If a higher-ranked document disagrees with a lower-ranked document, the higher document is authoritative. Update the lower document to match.

**Referenced Documents (Detailed Specifications):**

| Ref | Document | Purpose |
|---|---|---|
| RS-01 | `rebuild-spec/01_Architecture_Sitemap.md` | Architecture, IA, URL hierarchy |
| RS-02 | `rebuild-spec/02_Navigation_URLs_Migration.md` | Navigation, URLs, redirects, migration |
| RS-03 | `rebuild-spec/03_SEO_Metadata_Strategy.md` | SEO, metadata, structured data, internal linking |
| RS-04 | `rebuild-spec/04_Performance_Accessibility_Security_GDPR.md` | Performance, accessibility, security, GDPR, forms |
| RS-05 | `rebuild-spec/05_Components_CMS_Templates.md` | Components, design system, CMS, templates, blocks |
| RS-06 | `rebuild-spec/06_Backup_Deployment_GapAnalysis.md` | Backup, deployment, acceptance, dependencies, gaps |
| RS-07 | `rebuild-spec/07_Checklists.md` | All implementation checklists |
| RS-08 | `rebuild-spec/08_Launch_Risks_Questions_Future.md` | Launch readiness, risks, assumptions, questions, future |

**Source Documents (Analysis Reference Only):**

| Ref | Document | Role |
|---|---|---|
| SRC-01 | `ProjectAnalysis.md` | Technical landscape, page inventory, contact info |
| SRC-02 | `ContentInventory.md` | Content audit, navigation, media assets, metadata |
| SRC-03 | `BusinessRequirements.md` | Services, USPs, audiences, business rules, operations |
| SRC-04 | `ImprovementSuggestions.md` | Prioritized fixes, effort estimates |
| SRC-05 | `FeatureList.md` | Current features, gaps, plugins, technical debt |
| SRC-06 | `SiteMap.md` | Current URL hierarchy, inconsistencies, broken links |
| SRC-07 | `SEOAudit.md` | Technical SEO, on-page, local SEO, ranked issues |
| SRC-08 | `UserJourney.md` | Personas, journey maps, touchpoints, failure points |

---

### A2. Consolidated Findings: Duplication, Conflict, and Gap Analysis

The following is a formal analysis of all 8 source documents. Every finding is mapped to implementation requirements.

#### Finding F01: Contact Page is Broken (HTTP 500)

**Source:** SRC-01, SRC-02, SRC-04, SRC-05, SRC-06, SRC-07, SRC-08
**Severity:** CRITICAL — blocks all web-originated lead capture

| Requirement Type | Specification |
|---|---|
| **FR** | Build a fully functional contact page at `/contact/` with Gravity Forms. Form must deliver email to `info@helderduidelijkschoon.nl` and store entries in WordPress database. Confirmation email must be sent to the user. |
| **TR** | Replace Formidable Forms (broken) with Gravity Forms. PHP 8.2+ required. SMTP email delivery via transactional email service (Post SMTP or hosting-provided SMTP). reCAPTCHA v3 + honeypot anti-spam. |
| **UX** | Two-column layout: form (left) + contact info block (right). Form must show inline validation errors. Success redirects to `/bedankt/?type=contact`. Header phone number as visible fallback. |
| **SEO** | Page must have unique title tag and meta description. No index issues (current 500 causes de-indexing). Submit URL for indexing immediately post-launch. |
| **Content** | Dutch page content: heading "Contact", intro paragraph, form, contact info (phone, email, address [MISSING], KVK/BTW [MISSING], hours [MISSING]). |
| **Security** | reCAPTCHA v3 on form. Honeypot fallback. No sensitive data in URL parameters. HTTPS enforced. |
| **Perf** | Page load < 2 seconds. Form submission < 3 seconds. |
| **Accessibility** | All fields have `<label>`. Required fields marked with text and `aria-required`. Error messages via `aria-describedby`. Keyboard-navigable. |
| **Acceptance Criteria** | AC-F01: Form submits successfully. AC-F01: Email delivered to info@ within 5 minutes. AC-F01: Confirmation email delivered to user. AC-F01: Entry stored in database. AC-F01: Page returns HTTP 200. |

#### Finding F02: Reguliere Schoonmaak Page Missing (HTTP 404)

**Source:** SRC-01, SRC-02, SRC-04, SRC-06, SRC-07, SRC-08
**Severity:** CRITICAL — primary service line has zero web presence

| Requirement Type | Specification |
|---|---|
| **FR** | Build new page at `/reguliere-schoonmaak/` with 300+ words of Dutch content describing regular cleaning services for offices and businesses. Must include service details, process description, safety notes, and CTA. |
| **TR** | Use Page template "Service". Built with Block Editor. No shortcode dependency. |
| **UX** | Same layout as other service pages (consistent user expectation). Hero, content blocks, service list, cross-sell section, CTA banner. |
| **SEO** | Primary keyword: "reguliere schoonmaak", "kantoor schoonmaak". Secondary: "schoonmaakbedrijf [regio]". Title tag, meta description, Service schema. |
| **Content** | 300+ words: what regular cleaning includes, frequency options, industries served, quality process, safety, CTA. |
| **Acceptance Criteria** | AC-F02: Page returns HTTP 200. AC-F02: Page has 300+ words. AC-F02: Linked from nav, homepage, and footer. AC-F02: Service schema validated. |

#### Finding F03: Page Sitemap Broken (HTTP 500)

**Source:** SRC-01, SRC-02, SRC-06, SRC-07
**Severity:** HIGH — search engines cannot discover pages via sitemap

| Requirement Type | Specification |
|---|---|
| **FR** | Generate working XML sitemap via Yoast SEO / Rank Math. Include all public pages, blog posts, products. Exclude attachment pages, author archives, noindex pages. |
| **TR** | Yoast SEO Premium or Rank Math Pro. Sitemap at `/sitemap_index.xml` must return HTTP 200 with valid XML. Sub-sitemaps: page-sitemap.xml, post-sitemap.xml, product-sitemap.xml. |
| **SEO** | Submit sitemap to GSC and Bing Webmaster Tools at launch. Monitor for crawl errors daily for 30 days. |
| **Acceptance Criteria** | AC-F03: `/sitemap_index.xml` returns 200. AC-F03: `/page-sitemap.xml` returns 200 (currently 500). AC-F03: Zero attachment pages in sitemap. AC-F03: GSC reports zero sitemap errors. |

#### Finding F04: Zero Meta Descriptions

**Source:** SRC-01, SRC-02, SRC-07
**Severity:** HIGH — zero SERP click-through optimization

| Requirement Type | Specification |
|---|---|
| **FR** | Write unique 150-160 character Dutch meta descriptions for every page (32+ pages). |
| **TR** | Set via Yoast SEO / Rank Math field per page. |
| **SEO** | Each description must include: primary keyword, location, value proposition, CTA. |
| **Content** | Custom-written per page. Not auto-generated. |
| **Acceptance Criteria** | AC-F04: Screaming Frog scan shows zero empty meta descriptions. AC-F04: Zero duplicate meta descriptions. AC-F04: All descriptions 150-160 characters. |

#### Finding F05: No Privacy Policy (GDPR/AVG Violation)

**Source:** SRC-01, SRC-02, SRC-03, SRC-04, SRC-07
**Severity:** CRITICAL — legal non-compliance. Fine risk up to 4% annual turnover or EUR 20M.

| Requirement Type | Specification |
|---|---|
| **FR** | Publish Privacyverklaring at `/privacyverklaring/`. Link from footer on every page. |
| **TR** | WordPress Page with Legal template. |
| **Content** | Full privacy policy: data controller, processing purposes, legal basis, retention, data subject rights, right to complain to Autoriteit Persoonsgegevens, third-party sharing, international transfers. |
| **Legal** | MUST be reviewed by qualified Dutch privacy lawyer before publication. |
| **Acceptance Criteria** | AC-F05: Page published and accessible. AC-F05: Linked from footer on every page. AC-F05: Legally reviewed. AC-F05: Form consent checkboxes link to this page. |

#### Finding F06: No Cookie Consent

**Source:** SRC-01, SRC-02, SRC-03, SRC-04, SRC-07
**Severity:** HIGH — ePrivacy Directive violation

| Requirement Type | Specification |
|---|---|
| **FR** | Implement cookie consent banner via Complianz Premium. No non-functional cookies loaded before consent. Consent logged. |
| **TR** | Complianz Premium. Configured for Dutch market. GTM integration for consent signals. GA4 consent mode v2. |
| **UX** | Banner on first visit. Three options: Accepteren, Weigeren, Instellingen aanpassen. Modal with per-category toggles (all off except functional). Responsive, accessible. |
| **Legal** | Cookiebeleid page auto-generated by Complianz. Linked from banner and footer. |
| **Acceptance Criteria** | AC-F06: Banner appears on fresh browser. AC-F06: No GA/Facebook cookies loaded before consent (DevTools verified). AC-F06: Consent logged. AC-F06: Cookiebeleid page published. |

#### Finding F07: No KVK/BTW/Address Displayed

**Source:** SRC-01, SRC-02, SRC-03, SRC-07
**Severity:** HIGH — legal requirement for Dutch B2B. Trust signal deficit.

| Requirement Type | Specification |
|---|---|
| **FR** | Display KVK number, BTW number, and physical address in footer on every page. Display same in LocalBusiness schema. |
| **TR** | Stored in Theme Customizer / Company Information settings. Rendered in footer template part and schema JSON-LD. |
| **Content** | **MISSING INFORMATION REQUIRED:** Client must provide KVK number, BTW number, and physical address. |
| **Acceptance Criteria** | AC-F07: Footer displays KVK, BTW, and address (once provided). AC-F07: NAP consistent across footer, schema, and Google Business Profile. |

#### Finding F08: Inconsistent Naming — Gevelreiniging vs Gevelonderhoud

**Source:** SRC-01, SRC-02, SRC-06, SRC-07
**Severity:** MEDIUM — three different labels for the same content across nav, icon, and URL

| Requirement Type | Specification |
|---|---|
| **FR** | Standardize page title and nav label to "Gevelreiniging" (matching URL slug `/gevelreiniging/`). Homepage icon abbreviated to "Gevel". |
| **TR** | Update all internal links, nav items, and icon labels. No URL change (URL already `/gevelreiniging/`). |
| **SEO** | Primary keyword: "gevelreiniging". Secondary: "gevelonderhoud" (in content, not in URL/title). |
| **Acceptance Criteria** | AC-F08: Nav label reads "Gevelreiniging". AC-F08: Page title reads "Gevelreiniging". AC-F08: Icon reads "Gevel". AC-F08: URL unchanged `/gevelreiniging/`. |

#### Finding F09: Inconsistent URL — /vve vs /vve-service/

**Source:** SRC-01, SRC-02, SRC-06, SRC-08
**Severity:** MEDIUM — duplicate content signal risk

| Requirement Type | Specification |
|---|---|
| **FR** | Canonical URL is `/vve-service/`. Old URL `/vve` and `/vve/` issue 301 redirect to `/vve-service/`. |
| **TR** | 301 redirect via Yoast SEO redirect manager OR .htaccess rules. |
| **SEO** | 301 (permanent redirect). Self-referencing canonical on `/vve-service/`. |
| **Acceptance Criteria** | AC-F09: `/vve` returns 301 to `/vve-service/`. AC-F09: `/vve/` returns 301 to `/vve-service/`. AC-F09: No redirect chain. AC-F09: Canonical on `/vve-service/` is self-referencing. |

#### Finding F10: Inconsistent Trailing Slash — /glasbewassing vs /glasbewassing/

**Source:** SRC-01, SRC-02, SRC-06
**Severity:** LOW — redirect or duplicate content ambiguity

| Requirement Type | Specification |
|---|---|
| **FR** | Standardize with trailing slash. `/glasbewassing/` canonical. No-slash variant issues 301. |
| **TR** | WordPress permalink setting: trailing slash. Server-level redirect for no-slash variant. |
| **Acceptance Criteria** | AC-F10: `/glasbewassing` returns 301 to `/glasbewassing/`. AC-F10: Canonical on `/glasbewassing/` is self-referencing. |

#### Finding F11: Menu Groups Have No Landing Pages

**Source:** SRC-01, SRC-02, SRC-06
**Severity:** MEDIUM — missed internal link and SEO landing page opportunity

| Requirement Type | Specification |
|---|---|
| **FR** | Create two category landing pages: `/glas-en-gevel/` and `/schoonmaakdiensten/`. Each page aggregates sub-service cards with intro text. |
| **TR** | Use Category Landing page template. Block Editor with Service Card Grid pattern. |
| **SEO** | Each landing page targets broader keywords: "glas en gevel reiniging", "schoonmaakdiensten [regio]". |
| **Content** | 500+ words per page. Intro paragraph, service card grid linking to sub-services, CTA banner. |
| **Acceptance Criteria** | AC-F11: `/glas-en-gevel/` returns 200 with service cards for Glasbewassing and Gevelreiniging. AC-F11: `/schoonmaakdiensten/` returns 200 with service cards for all 5 sub-services. |

#### Finding F12: Thin Content on 9 of 12 Pages

**Source:** SRC-02, SRC-07
**Severity:** HIGH — cannot rank for competitive terms

| Requirement Type | Specification |
|---|---|
| **FR** | All service pages minimum 300 words Dutch content. | **TR** | Block Editor. Service page template enforces minimum content blocks. |
| **SEO** | Each page targets primary and secondary keywords. H2/H3 hierarchy for topical structure. |
| **Content** | See Content Migration Strategy (RS-02 Section 9) for per-page word count targets. |
| **Acceptance Criteria** | AC-F12: Word count check — all service pages >= 300 words. AC-F12: Screaming Frog shows H1 + minimum 2 H2s per service page. |

#### Finding F13: Vacatures Page Content as Scanned Images

**Source:** SRC-01, SRC-02, SRC-05, SRC-08
**Severity:** HIGH — not accessible, not indexable, not searchable

| Requirement Type | Specification |
|---|---|
| **FR** | Rebuild Vacatures page with HTML text content. Add JobPosting structured data. Add application form per vacancy. |
| **TR** | Custom Post Type `hds_vacancy` for vacancy management. Gravity Forms for applications. JobPosting schema per vacancy. |
| **UX** | Vacancy cards with toggle-to-expand details. Application form below each vacancy. |
| **Accessibility** | ALL text content machine-readable. Screen reader compatible. Keyboard-navigable. |
| **Content** | **MISSING INFORMATION REQUIRED:** Client must provide full vacancy text as editable text (not images). |
| **Acceptance Criteria** | AC-F13: Zero scanned images on Vacatures page. AC-F13: All text selectable and readable by screen readers. AC-F13: JobPosting schema validated. AC-F13: Application form functional. |

#### Finding F14: XML-RPC Enabled

**Source:** SRC-01, SRC-04, SRC-05, SRC-06
**Severity:** HIGH — known brute-force attack vector

| Requirement Type | Specification |
|---|---|
| **TR** | Disable XML-RPC at web server level. Return 403 Forbidden. |
| **Security** | Nginx: `location = /xmlrpc.php { deny all; }`. Apache: `.htaccess` deny rule. |
| **Acceptance Criteria** | AC-F14: `/xmlrpc.php` returns 403. AC-F14: WordPress Site Health reports XML-RPC disabled. |

#### Finding F15: All Software Outdated (4+ Major Versions Behind)

**Source:** SRC-01, SRC-04, SRC-05
**Severity:** HIGH — security vulnerabilities, performance degradation, compatibility issues

| Requirement Type | Specification |
|---|---|
| **TR** | WordPress 6.7+, PHP 8.2+, WooCommerce 9.x+, Yoast/Rank Math latest, Gravity Forms latest. All plugins on latest stable. |
| **Security** | Auto-updates enabled for minor releases. Major releases reviewed and tested on staging before production. |
| **Acceptance Criteria** | AC-F15: All software on latest stable versions at launch. AC-F15: Zero plugins with known vulnerabilities (Wordfence scan clean). |

#### Finding F16: No Analytics Tracking

**Source:** SRC-01, SRC-03, SRC-04, SRC-07
**Severity:** MEDIUM — zero visibility into traffic, conversions, or ROI

| Requirement Type | Specification |
|---|---|
| **FR** | Implement GA4 via Google Tag Manager. Configure conversion tracking for all key events (phone clicks, email clicks, form submissions, WooCommerce purchases). |
| **TR** | GTM snippet in `<head>`. GA4 configuration tag. Consent mode v2 integration with Complianz. Data Layer for conversion events. |
| **Acceptance Criteria** | AC-F16: GA4 real-time report shows page views. AC-F16: GA4 events fire for all conversions listed in Section 23 of RS-04. |

#### Finding F17: Instagram Widget Broken

**Source:** SRC-01, SRC-02, SRC-04, SRC-05, SRC-08
**Severity:** LOW — unprofessional appearance on every page

| Requirement Type | Specification |
|---|---|
| **FR** | Remove broken Instagram widget. Replace with static Instagram icon link in footer. |
| **UX** | Simple social icon linking to Instagram profile. No embedded feed unless API is reliable and client confirms activity. |
| **Acceptance Criteria** | AC-F17: No "Instagram did not return a 200" message on any page. AC-F17: Instagram icon in footer links to correct profile. |

#### Finding F18: Legacy Domain for PDF Hosting

**Source:** SRC-01, SRC-02, SRC-06
**Severity:** MEDIUM — single point of failure for legal documents

| Requirement Type | Specification |
|---|---|
| **FR** | Migrate all PDFs from `hds-onderhoudsdiensten.nl` to `helderduidelijkschoon.nl` media library. Set up 301 redirects from old PDF URLs to new. |
| **TR** | Download all PDFs from legacy domain. Upload to WordPress media library. Update all internal links to point to new URLs. Configure redirects on legacy domain OR migrate domain. |
| **Acceptance Criteria** | AC-F18: All PDFs accessible from primary domain. AC-F18: Old PDF URLs redirect to new URLs. AC-F18: Zero dependency on legacy domain. |

---

### A3. Duplicated Information — Resolved

| Duplicate | Source Docs | Resolution |
|---|---|---|
| Service portfolio listing | SRC-01 (3.1), SRC-02 (2.1-2.15), SRC-03 (2.1), SRC-05 (1.1), SRC-07 (2.5), SRC-08 (Journeys) | Consolidated: see Section C1 Information Architecture below. Single source of truth. |
| Navigation structure | SRC-01 (4), SRC-02 (1.1-1.3), SRC-06 (diagram) | Consolidated: see Section D1 Navigation Specification below. |
| URL inconsistencies | SRC-02 (1.3), SRC-06 (URL Inconsistencies Map) | Consolidated: resolved in Findings F08-F10. See Section D4 URL Strategy. |
| Contact information | SRC-01 (5), SRC-02 (4.1, 4.2) | Consolidated: see Section C2.4 Company Information Block. |
| Critical issues list | Appears in all 8 source documents with varying names and severities | Consolidated: Findings F01-F18 above + Gap Analysis (RS-06 Section 39). |
| Technical stack | SRC-01 (2), SRC-05 (6, 7) | Consolidated: see Section B1 Target Architecture below. |
| Business rules | SRC-03 (13) | Preserved as-is in RS-01 through RS-08. Referenced for content creation. |
| USPs and values | SRC-03 (1.2, 1.3) | Preserved as-is. Referenced in homepage and About page content specifications (RS-02). |

---

### A4. Missing Information — Required Before Development

| ID | Missing Item | Blocks Phase | Owner |
|---|---|---|---|
| MI-01 | Physical business address | Phase 2, 3 | Client |
| MI-02 | KVK registration number | Phase 2, 3 | Client |
| MI-03 | BTW (VAT) number | Phase 2, 3 | Client |
| MI-04 | Business operating hours | Phase 2, 3 | Client |
| MI-05 | Service area — specific municipalities/postcodes | Phase 2, 5 | Client |
| MI-06 | Logo vector file (SVG/AI/EPS) | Phase 1 | Client |
| MI-07 | Brand color palette | Phase 1 | Client |
| MI-08 | Brand typography preferences | Phase 1 | Client |
| MI-09 | Project photos (service pages, team, before/after) | Phase 3 | Client |
| MI-10 | Client names and logos for Referenties page (with written permission) | Phase 3 | Client |
| MI-11 | Testimonial text with author names and companies | Phase 3 | Client |
| MI-12 | Full vacancy text (HTML, not images) | Phase 3 | Client |
| MI-13 | Airfixr product descriptions and USPs | Phase 4 | Client |
| MI-14 | Shipping costs and delivery policy | Phase 4 | Client |
| MI-15 | Payment gateway preference (Mollie recommended) | Phase 4 | Client |
| MI-16 | Terms & Conditions text for HTML page | Phase 3 | Client |
| MI-17 | Privacyverklaring legally reviewed | Phase 6 | Client + Lawyer |
| MI-18 | Company legal entity type (BV, eenmanszaak, VOF) | Phase 0 | Client |
| MI-19 | Founding year and company history | Phase 3 | Client |
| MI-20 | Hosting provider decision | Phase 0 | Client |
| MI-21 | Google Business Profile status (claimed? verified?) | Phase 0 | Client |
| MI-22 | Google Analytics account access or new property creation | Phase 5 | Client/Dev |
| MI-23 | Social share image (1200x630 px branded graphic) | Phase 5 | Designer |
| MI-24 | Budget approval for rebuild | Phase 0 | Client |
| MI-25 | OSB membership link URL | Phase 3 | Client |

## Part B: Target Architecture

### B1. Target Technology Stack

| Component | Selection | Version | Constraint |
|---|---|---|---|
| **CMS** | WordPress | 6.7+ (latest stable) | No page builder. Native Block Editor only. |
| **PHP** | — | 8.2+ | Required by modern WP + WooCommerce. |
| **Database** | MySQL or MariaDB | MySQL 8.0+ / MariaDB 10.6+ | InnoDB storage engine. |
| **Theme** | Custom Hybrid Block Theme (`hds`) | 1.0.0 | **RESOLVED (ADR D-001):** Custom hybrid block theme (theme.json + PHP templates + Block Editor). GeneratePress/Kadence rejected. |
| **Page Builder** | Native WordPress Block Editor (Gutenberg) ONLY | Core | Zero tolerance for third-party page builders. **RESOLVED (ADR D-002).** |
| **SEO** | Rank Math Pro | latest | **RESOLVED (ADR D-003):** Built-in redirect manager, 404 monitor. Yoast rejected. |
| **eCommerce** | WooCommerce | 9.x+ (latest stable) | Required for Airfixr products. May be removed if client decides against webshop. |
| **Forms** | Gravity Forms | latest | Replaces broken Formidable Forms. Conditional logic, file upload, GDPR consent fields required. |
| **Caching** | FlyingPress + Redis | latest | **RESOLVED (ADR D-004):** Built-in unused CSS removal. WP Rocket rejected. |
| **CDN** | Cloudflare | free tier minimum | SSL, caching, WAF, image optimization. |
| **Hosting** | Managed WordPress | — | Kinsta, WP Engine, Cloud86.nl. Must support PHP 8.2+, Redis, daily backups, staging environment. |
| **Security** | Wordfence Premium | latest | **RESOLVED:** Firewall, malware scan, 2FA, brute force protection, custom login URL. |
| **Backups** | BlogVault / UpdraftPlus Premium | latest | Daily automated offsite backups. Monthly test restore to staging. |
| **Image Optimization** | ShortPixel / Imagify | latest | Auto WebP conversion. Compression on upload. |
| **Search** | Relevanssi | latest | Dutch language support, relevance sorting. |
| **Monitoring** | UptimeRobot | free tier | Downtime alerts to developer + client. |
| **SMTP** | Post SMTP + SendGrid/Mailgun/SES | latest | **RESOLVED (ADR SWA-01):** Transactional email delivery. SPF/DKIM/DMARC required. |

### B2. Banned Technology (Hard Constraints)

| Banned | Reason |
|---|---|
| Divi Theme | Performance overhead (large CSS/JS), shortcode lock-in, outdated on current site |
| Elementor, WPBakery, Beaver Builder, any page builder | Lock-in risk, migration nightmare, content trapped in shortcodes |
| Formidable Forms | Currently broken (causing 500 error on contact page) |
| PHP < 8.0 | EOL, security risks, performance penalty |
| XML-RPC | Brute-force attack vector — must be disabled at server level |
| Nulled/cracked plugins | Security risk, legal risk, malware vector |
| jQuery Migrate | Performance overhead. Remove dependency. |

### B3. Infrastructure Architecture

```
[Cloudflare CDN]
  |-- SSL termination
  |-- WAF (Web Application Firewall)
  |-- Caching (full-page + static assets)
  |-- Image optimization (Polish)
        |
        v
[Managed WordPress Hosting]
  |-- Production Environment (helderduidelijkschoon.nl)
  |-- Staging Environment (staging.helderduidelijkschoon.nl, noindex, password-protected)
  |-- Offsite Backup Storage (daily automated)
        |
        v
[WordPress 6.7+]
  |-- Custom Block Theme (native Gutenberg)
  |-- Plugins: WooCommerce, Gravity Forms, Yoast/Rank Math, WP Rocket, Complianz, Wordfence, Relevanssi
        |
        v
[MySQL 8.0+ / MariaDB 10.6+]
  |-- InnoDB tables
  |-- wp_ prefix changed from default
```

### B4. Environment Separation

| Environment | URL | Purpose | Access |
|---|---|---|---|
| **Local Dev** | `hds.local` | Developer local machine | Developer only |
| **Staging** | `staging.helderduidelijkschoon.nl` | Pre-production testing, client review, QA | Developer + Client (password) |
| **Production** | `helderduidelijkschoon.nl` | Live public site | Public + Developer + Client (admin) |

**Staging Rules:**
- `noindex, nofollow` meta tag on all pages
- Password-protected via `.htaccess` or hosting-level authentication
- Identical software stack to production
- Database is a copy of production (anonymized for GDPR if needed)

---

## Part C: Information Architecture

### C1. Content Hierarchy

```
helderduidelijkschoon.nl/
|
+-- Diensten (Services)
|   +-- Glasbewassing                   /glasbewassing/
|   +-- Gevelreiniging                  /gevelreiniging/
|   +-- Reguliere Schoonmaak            /reguliere-schoonmaak/
|   +-- Vloeronderhoud                  /vloeronderhoud/
|   +-- VVE Service                     /vve-service/
|   +-- Oplevering Schoonmaak           /oplevering-schoonmaak/
|   +-- Industriele Schoonmaak          /industriele-schoonmaak/
|   +-- Glas & Gevel (landing)          /glas-en-gevel/
|   +-- Schoonmaakdiensten (landing)    /schoonmaakdiensten/
|
+-- Over HDS (About)
|   +-- Over HDS                        /over-hds/
|   +-- Kwaliteit & Veiligheid          /kwaliteit-veiligheid/
|   +-- Referenties                     /referenties/
|   +-- Vacatures                       /vacatures/
|   +-- Downloads                       /downloads/
|
+-- Luchtreiniging (Airfixr Products)
|   +-- Luchtreiniging (landing)        /luchtreiniging/
|   +-- Winkel (Shop)                   /winkel/
|   +-- Product detail pages (14)       /product/{slug}/
|   +-- Winkelmand (Cart)              /winkelmand/
|   +-- Afrekenen (Checkout)           /afrekenen/
|   +-- Mijn Account (My Account)       /mijn-account/
|
+-- Contact
|   +-- Contact                         /contact/
|   +-- Offerte Aanvragen (Quote)       /offerte-aanvragen/
|
+-- Kennisbank (Knowledge Base)
|   +-- Blog index                      /kennisbank/
|   +-- Blog posts                      /kennisbank/{slug}/
|   +-- Veelgestelde Vragen (FAQ)       /veelgestelde-vragen/
|
+-- Juridisch (Legal)
|   +-- Privacyverklaring               /privacyverklaring/
|   +-- Cookiebeleid                    /cookiebeleid/
|   +-- Algemene Voorwaarden            /algemene-voorwaarden/
|   +-- Disclaimer                      /disclaimer/
|
+-- Systeem (System)
    +-- Bedankt (Thank You)             /bedankt/
    +-- 404 (Error Page)                (any non-existent URL)
```

### C2. Complete Page Inventory

| ID | Page Title (NL) | URL | Template | Words | Priority | Status |
|---|---|---|---|---|---|---|
| P01 | Home | `/` | Home | 300+ | P0 | Rebuild |
| P02 | Glasbewassing | `/glasbewassing/` | Service | 300+ | P0 | Migrate + Expand |
| P03 | Gevelreiniging | `/gevelreiniging/` | Service | 300+ | P0 | Migrate + Expand |
| P04 | Reguliere Schoonmaak | `/reguliere-schoonmaak/` | Service | 300+ | P0 | **NEW (was 404)** |
| P05 | Vloeronderhoud | `/vloeronderhoud/` | Service | 300+ | P0 | Migrate + Expand |
| P06 | VVE Service | `/vve-service/` | Service | 300+ | P0 | Migrate + Expand |
| P07 | Oplevering Schoonmaak | `/oplevering-schoonmaak/` | Service | 300+ | P0 | Migrate + Expand |
| P08 | Industriele Schoonmaak | `/industriele-schoonmaak/` | Service | 300+ | P0 | Rebuild (was 60w) |
| P09 | Glas & Gevel | `/glas-en-gevel/` | Cat Landing | 500+ | P1 | **NEW** |
| P10 | Schoonmaakdiensten | `/schoonmaakdiensten/` | Cat Landing | 500+ | P1 | **NEW** |
| P11 | Over HDS | `/over-hds/` | About | 500+ | P0 | Expand |
| P12 | Kwaliteit & Veiligheid | `/kwaliteit-veiligheid/` | About | 300+ | P0 | Expand |
| P13 | Referenties | `/referenties/` | About | 300+ | P1 | Rebuild (was 25w) |
| P14 | Vacatures | `/vacatures/` | About | 300+ | P1 | Rebuild (was images) |
| P15 | Downloads | `/downloads/` | About | 150+ | P1 | Expand + Migrate PDFs |
| P16 | Contact | `/contact/` | Contact | 150+ | P0 | **REBUILD (was 500)** |
| P17 | Offerte Aanvragen | `/offerte-aanvragen/` | Quote | 150+ | P1 | **NEW** |
| P18 | Veelgestelde Vragen | `/veelgestelde-vragen/` | FAQ | 300+ | P2 | **NEW** |
| P19 | Privacyverklaring | `/privacyverklaring/` | Legal | 500+ | P0 | **NEW (legal req)** |
| P20 | Cookiebeleid | `/cookiebeleid/` | Legal | Auto | P0 | **NEW (legal req)** |
| P21 | Algemene Voorwaarden | `/algemene-voorwaarden/` | Legal | 500+ | P0 | **NEW (was PDF only)** |
| P22 | Disclaimer | `/disclaimer/` | Legal | 200+ | P2 | **NEW** |
| P23 | Luchtreiniging | `/luchtreiniging/` | Prod Landing | 300+ | P1 | **NEW** |
| P24 | Winkel | `/winkel/` | Shop | 100+ | P1 | Migrate + Add intro |
| P25 | Product pages (x14) | `/product/{slug}/` | Product | Existing | P1 | Migrate |
| P26 | Winkelmand | `/winkelmand/` | Cart | — | P1 | Standard WC |
| P27 | Afrekenen | `/afrekenen/` | Checkout | — | P1 | Standard WC |
| P28 | Mijn Account | `/mijn-account/` | Account | — | P1 | Standard WC |
| P29 | Kennisbank (Blog) | `/kennisbank/` | Blog Index | — | P2 | **NEW** |
| P30 | Blog posts (5-10) | `/kennisbank/{slug}/` | Blog Post | 500+ | P2 | **NEW** |
| P31 | 404 Pagina | (any 404) | 404 | — | P0 | **NEW** |
| P32 | Bedankt | `/bedankt/` | Thank You | 50+ | P0 | **NEW** |

### C3. Page Groups by Audience

| Group | Pages | Primary Audience |
|---|---|---|
| **Service Discovery** | P02–P08 | B2B: facility managers, VvE boards, construction PMs, school admins, factory managers |
| **Category Landings** | P09–P10 | B2B: search visitors for broader terms |
| **Trust Building** | P11–P15 | All visitors: credibility, recruitment, compliance verification |
| **Conversion** | P16–P17 | All prospects: lead capture |
| **Legal Compliance** | P19–P22 | All visitors + regulators |
| **eCommerce** | P23–P28 | B2B/B2C: Airfixr buyers |
| **Content/SEO** | P18, P29–P30 | All visitors: organic traffic acquisition |

### C4. Company Information Block (Single Source of Truth)

This block appears identically in the footer on every page, in the LocalBusiness schema, and on the Contact page.

```
HDS Onderhoudsdiensten
[Straat + Huisnummer]              -- MI-01
[Postcode] [Plaats]                -- MI-01
Telefoon: 0164-652846             -- Confirmed
E-mail: info@helderduidelijkschoon.nl  -- Confirmed
KVK: [XXXXXXXX]                    -- MI-02
BTW: [NLXXXXXXXXXB01]              -- MI-03
```

All items marked `--` are confirmed from current site. Items marked `MI-XX` reference Missing Information items in Section A4.

---

## Part D: Navigation, URLs, and Redirects

### D1. Navigation Specification

**Primary Navigation (Desktop):**

```
[LOGO]    DIENSTEN v    OVER HDS v    LUCHTREINIGING v    CONTACT    [TEL] [CART]
```

**DIENSTEN Dropdown:**

```
Glas & Gevel                -> /glas-en-gevel/
  Glasbewassing             -> /glasbewassing/
  Gevelreiniging            -> /gevelreiniging/

Schoonmaakdiensten          -> /schoonmaakdiensten/
  Reguliere Schoonmaak      -> /reguliere-schoonmaak/
  Vloeronderhoud            -> /vloeronderhoud/
  VVE Service               -> /vve-service/
  Oplevering Schoonmaak     -> /oplevering-schoonmaak/
  Industriele Schoonmaak    -> /industriele-schoonmaak/
```

**OVER HDS Dropdown:**

```
Over HDS                   -> /over-hds/
Kwaliteit & Veiligheid     -> /kwaliteit-veiligheid/
Referenties                -> /referenties/
Vacatures                  -> /vacatures/
Downloads                  -> /downloads/
```

**LUCHTREINIGING Dropdown:**

```
Over Airfixr               -> /luchtreiniging/
Winkel                     -> /winkel/
Mijn Account               -> /mijn-account/
```

**Mobile Navigation:** Hamburger menu with accordion expand/collapse. All items visible, no horizontal scroll. Touch targets >= 44x44px.

**Footer Navigation:**

```
Column 1: DIENSTEN            Column 2: OVER HDS        Column 3: CONTACT
Glasbewassing                 Over HDS                   Tel: 0164-652846
Gevelreiniging                Kwaliteit & Veiligheid      info@helderduidelijkschoon.nl
Reguliere Schoonmaak          Referenties                 Adres: [MI-01]
Vloeronderhoud                Vacatures                   KVK: [MI-02]
VVE Service                   Downloads                   BTW: [MI-03]
Oplevering Schoonmaak
Industriele Schoonmaak        Column 4: LUCHTREINIGING   Column 5: JURIDISCH
                              Luchtreiniging              Privacyverklaring
                              Winkel                      Cookiebeleid
                                                          Algemene Voorwaarden
                                                          Disclaimer
```

### D2. Homepage Content Blocks (Top to Bottom)

| # | Block Type | Content | Primary Link |
|---|---|---|---|
| 1 | Hero Banner | Tagline + USP summary + CTA | `/offerte-aanvragen/` |
| 2 | Service Card Grid | 7 service cards with icons | Each links to service page |
| 3 | USP Grid | 4-6 cards: vast personeel, veiligheid, aanspreekpunt, maatwerk, milieubewust, regio | — |
| 4 | Client Logo Carousel | Client logos with permission | Link to `/referenties/` |
| 5 | Testimonial Block | 3-5 testimonials with star ratings | — |
| 6 | CTA Banner | "Wilt u een vrijblijvende offerte?" | `/offerte-aanvragen/` |
| 7 | Service Area | Map or text listing service region | — |
| 8 | Latest Blog Posts | 3 most recent articles | `/kennisbank/` |

### D3. Final Sitemap (URL Hierarchy)

```
helderduidelijkschoon.nl/
|-- /                                              [HOME]
|-- /glasbewassing/                                [GLASBEWASSING]
|-- /gevelreiniging/                               [GEVELREINIGING]
|-- /reguliere-schoonmaak/                         [REGULIERE SCHOONMAAK]
|-- /vloeronderhoud/                               [VLOERONDERHOUD]
|-- /vve-service/                                  [VVE SERVICE]
|-- /oplevering-schoonmaak/                        [OPLEVERING SCHOONMAAK]
|-- /industriele-schoonmaak/                       [INDUSTRIELE SCHOONMAAK]
|-- /glas-en-gevel/                                [GLAS & GEVEL]
|-- /schoonmaakdiensten/                           [SCHOONMAAKDIENSTEN]
|-- /over-hds/                                     [OVER HDS]
|-- /kwaliteit-veiligheid/                         [KWALITEIT & VEILIGHEID]
|-- /referenties/                                  [REFERENTIES]
|-- /vacatures/                                    [VACATURES]
|-- /downloads/                                    [DOWNLOADS]
|-- /contact/                                      [CONTACT]
|-- /offerte-aanvragen/                            [OFFERTE AANVRAGEN]
|-- /bedankt/                                      [THANK YOU]
|-- /veelgestelde-vragen/                          [FAQ]
|-- /privacyverklaring/                            [PRIVACY POLICY]
|-- /cookiebeleid/                                 [COOKIE POLICY]
|-- /algemene-voorwaarden/                         [TERMS]
|-- /disclaimer/                                   [DISCLAIMER]
|-- /luchtreiniging/                               [AIRFIXR LANDING]
|-- /winkel/                                       [SHOP]
|-- /winkel/page/{n}/                              [SHOP PAGINATION]
|-- /product/{slug}/                               [PRODUCT x14]
|-- /winkelmand/                                   [CART]
|-- /afrekenen/                                    [CHECKOUT]
|-- /mijn-account/                                 [MY ACCOUNT]
|-- /kennisbank/                                   [BLOG INDEX]
|-- /kennisbank/page/{n}/                          [BLOG PAGINATION]
|-- /kennisbank/{slug}/                            [BLOG POSTS]
```

### D4. URL Strategy

| Rule | Specification |
|---|---|
| **Protocol** | HTTPS only. HTTP -> 301. HSTS max-age=31536000. |
| **www / non-www** | `https://helderduidelijkschoon.nl` canonical. www -> 301. |
| **Trailing Slash** | Consistently WITH trailing slash on all pages. No-slash -> 301. |
| **Language** | Dutch, lowercase, hyphens. No diacritics in slugs. |
| **Depth** | Max 1 level from root. Exception: `/product/{slug}/`, `/kennisbank/{slug}/`. |
| **Extensions** | No `.html`, `.php`, `.asp`. Clean URLs only. |
| **Query Params** | No `?page_id=N` style URLs. All pages use descriptive slugs. |
| **Blog URLs** | `/kennisbank/{slug}/` not `/YYYY/MM/DD/slug/`. Permanent URLs. |
| **Blocked URLs** | `/xmlrpc.php` -> 403. `/wp-admin/` -> 403 rate-limited. `/?author={N}` -> 403. `/wp-json/wp/v2/users` -> blocked. |

### D5. Redirect Strategy (301 Mapping)

**Old-to-New 301 Redirects:**

| Old URL | Status | New URL |
|---|---|---|
| `/glasbewassing` (no slash) | 301 | `/glasbewassing/` |
| `/vve` | 301 | `/vve-service/` |
| `/vve/` | 301 | `/vve-service/` |
| `/?page_id=318` | 301 | `/reguliere-schoonmaak/` |
| `http://helderduidelijkschoon.nl/*` | 301 | `https://helderduidelijkschoon.nl/*` |
| `http://www.helderduidelijkschoon.nl/*` | 301 | `https://helderduidelijkschoon.nl/*` |
| `https://www.helderduidelijkschoon.nl/*` | 301 | `https://helderduidelijkschoon.nl/*` |

**410 Gone (Permanently Removed):**

| Old URL | Status |
|---|---|
| `/2015/06/29/hallo-wereld/` | 410 |
| `/2015/08/25/kwaliteit-veiligheid/` | 410 |

**Technical Requirements for Redirects:**
- All redirects MUST be 301 (permanent), never 302
- Zero redirect chains (A -> B -> C is forbidden; A -> C is required)
- Test every redirect manually before launch
- Monitor GSC for redirect errors post-launch

### D6. Legacy Domain Resolution

`hds-onderhoudsdiensten.nl` currently hosts PDFs. Action plan:
1. Download all PDFs from legacy domain
2. Upload to `helderduidelijkschoon.nl` WordPress media library
3. Update all internal links to new PDF URLs
4. Configure 301 redirects from old PDF URLs to new PDF URLs on legacy domain
5. Once confirmed working, either point legacy domain to primary OR maintain redirects
6. **Decision required from client** on whether to keep or retire `hds-onderhoudsdiensten.nl`

---

## Part E: Content, Media, and SEO Migration

### E1. Content Migration Strategy

**Approach:** Manual migration with rewrite. No automated migration. Existing content is too thin and inconsistent.

| Tier | Pages | Strategy |
|---|---|---|
| **Tier 1: Keep + Expand** | Home, Glasbewassing, Gevelreiniging, Vloeronderhoud, VVE Service, Oplevering Schoonmaak, Kwaliteit & Veiligheid, Over HDS | Migrate existing Dutch copy. Expand to 300-500+ words. Add H2/H3 hierarchy, images, CTAs. |
| **Tier 2: Rebuild from Scratch** | Reguliere Schoonmaak, Industriele Schoonmaak, Referenties, Vacatures, Downloads, Contact | Content broken, extremely thin, or image-based. Write entirely new content. |
| **Tier 3: Create New** | Offerte Aanvragen, Veelgestelde Vragen, Privacyverklaring, Cookiebeleid, Algemene Voorwaarden, Disclaimer, Luchtreiniging, Glas & Gevel landing, Schoonmaakdiensten landing, Blog posts, 404, Bedankt | No content exists. All new content from scratch. |
| **Tier 4: Preserve + Enhance** | WooCommerce products (14), Winkel, Cart, Checkout, My Account | Product data migrated as-is. Shop intro text and category descriptions written. |

**Minimum Content Depth:**

| Page Type | Min Words |
|---|---|
| Service pages | 300+ |
| Category landing pages | 500+ |
| About / Over HDS | 500+ |
| Legal pages | 150-500+ |
| Blog posts | 500+ |
| FAQ (total across all items) | 300+ |

### E2. Media Migration Strategy

| Asset | Action |
|---|---|
| Logo (`hds200x81.png`) | **Request original vector file from client.** If unavailable, recreate as SVG. |
| Homepage icons (8 PNGs) | **Replace entirely.** Use inline SVG icon library (Phosphor or Font Awesome 6). |
| Service page images | **MISSING from most pages.** Client to provide real project photos or approve stock photography. All images: WebP format, 1200px wide min, <150KB, alt text in Dutch. |
| Vacancy JPG posters | **DO NOT MIGRATE.** Replace with HTML text content. |
| Airfixr product images (15) | Migrate. Optimize to WebP. Consistent dimensions. |
| PDFs (on legacy domain) | Migrate to primary domain media library. |
| Social share image | **CREATE NEW.** 1200x630px branded graphic. |

**Image Technical Standards:**
- Format: WebP with PNG/JPEG fallback via `<picture>` element
- Compression: Visually lossless (quality 85+)
- Responsive: `srcset` with 400w, 800w, 1200w variants
- Lazy loading: `loading="lazy"` below fold; `fetchpriority="high"` on LCP image
- Alt text: Descriptive Dutch on all non-decorative images; `alt=""` for decorative
- Filenames: Lowercase, hyphens, Dutch keywords (e.g., `glasbewassing-kantoor-bergen-op-zoom.webp`)
- Dimensions: Explicit `width` and `height` attributes to prevent CLS

### E3. SEO Migration Strategy

**Pre-Migration (before old site taken offline):**
1. Full crawl of current site (Screaming Frog): export all URLs, status codes, titles, meta descriptions
2. Export all GSC data (16 months): queries, pages, clicks, impressions, CTR, average position
3. Document all backlinks (Ahrefs, Semrush, or GSC)
4. Export Google Business Profile data (NAP, categories, reviews)
5. Screenshot every current page for visual archive

**Key SEO Preservation Rules:**
- Working page URLs MUST NOT change: `/glasbewassing/`, `/gevelreiniging/`, `/vloeronderhoud/`, `/vve-service/`, `/oplevering-schoonmaak/`, `/industriele-schoonmaak/`, `/over-hds/`, `/kwaliteit-veiligheid/`, `/referenties/`, `/vacatures/`, `/downloads/`, `/winkel/`, `/product/*`
- Broken pages reused at identical URLs: `/reguliere-schoonmaak/`, `/contact/` (same URL, now working)
- All redirects: 301 permanent, no redirect chains
- New sitemap submitted to GSC immediately after launch

**Post-Migration Monitoring (30 days):**
- Daily: Check GSC for crawl errors and 404s
- Weekly: Compare indexed pages count, search impressions, click-through rate vs pre-migration baseline
- Week 2: Submit all new URLs for indexing in GSC
- Week 4: Full audit: traffic, rankings, indexed pages vs baseline

---

## Part F: CMS Architecture, Templates, Components, Design System

### F1. CMS Architecture (WordPress)

#### WordPress Core Configuration

| Setting | Value |
|---|---|
| Permalink Structure | `/%postname%/` (Post name) |
| Category Base | `kennisbank` |
| Time Zone | Europe/Amsterdam |
| Date Format | `j F Y` |
| Language | Nederlands (nl_NL) |
| Comments | Disabled site-wide |
| Pingbacks/Trackbacks | Disabled |
| Search Engine Visibility | Enabled |
| Media Sizes | Thumbnail: 150x150. Medium: 600x600. Large: 1200x1200. Custom: 400x300, 800x600, 1600x900. Disable unused defaults. |

#### Custom Post Types

| CPT | Slug | Purpose |
|---|---|---|
| `hds_testimonial` | `referenties` | Client testimonials. Fields: quote, author name, company, star rating (1-5), related service. |
| `hds_vacancy` | `vacatures` | Job listings. Fields: hours, location, start date, contact email, deadline, active toggle. |
| `hds_faq` | `faq` | FAQ items for /veelgestelde-vragen/. Title = question, Editor = answer. |

**Note:** All service pages use standard WordPress Pages with Service template. No custom post type for services.

#### Custom Fields (ACF or built-in)

| Field Group | Attached To | Fields |
|---|---|---|
| Service Page Settings | Page (template: Service) | Subtitle, Hero Image, Icon (for service cards), CTA override text |
| Testimonial Details | `hds_testimonial` | Author name, Company, Star rating (1-5), Related service |
| Vacancy Details | `hds_vacancy` | Hours/week, Location, Start date, Application email, Deadline, Active toggle |
| Company Information | Theme Customizer | Address, Postal code, City, Phone, Email, KVK, BTW, Facebook URL, Instagram URL, GBP URL, Opening hours (repeater: day, opens, closes) |

#### User Roles

| Role | Capabilities |
|---|---|
| Administrator | Full access (developer + site owner, minimum 2 accounts) |
| Editor | Manage all content, view form entries, view analytics |
| Shop Manager | Manage WooCommerce products, orders, coupons |
| SEO Manager | Access Yoast/Rank Math, Google Site Kit |
| Subscriber | Read-only (WooCommerce customers) |

### F2. Page Template Specifications

| Template File | Applied To | Key Layout |
|---|---|---|
| `front-page.php` | Home (P01) | Hero -> Service Grid -> USP Grid -> Client Logos -> Testimonials -> CTA Banner -> Service Area -> Latest Posts. ALL sections editable via Block Editor. |
| `page-service.php` | P02-P08 | Breadcrumbs -> Hero (H1 + subtitle + CTA) -> Content Area (the_content with intro, approach, service list, safety) -> Cross-Sell Services -> CTA Banner -> Optional FAQ |
| `page-category-landing.php` | P09, P10 | Hero -> Intro Text -> Service Card Grid -> CTA Banner |
| `page-about.php` | P11, P12 | Hero -> Content with Image -> Additional Content Blocks -> CTA Banner |
| `page-contact.php` | P16 | Breadcrumbs -> H1 -> Two-Column (Form Left, Contact Info Right with phone, email, address, KVK/BTW, hours, social, map) |
| `page-quote.php` | P17 | Similar to Contact but with extended quote form fields |
| `page-faq.php` | P18 | H1 -> Intro -> FAQ Accordion Block (with FAQPage schema) |
| `page-legal.php` | P19-P22 | H1 -> Content (rich text) -> Last Updated Date |
| `page.php` | P13-P15, others | Default: H1 -> Content Area |
| `archive.php` | P29 (Blog Index) | H1 -> Post Grid (cards with image, title, date, excerpt) -> Pagination |
| `single.php` | P30 (Blog Posts) | Breadcrumbs -> Featured Image -> H1 -> Meta (date, category, reading time) -> Content -> Related Posts -> CTA Banner |
| `search.php` | Search Results | H1 -> Results List -> Pagination -> "Geen resultaten" fallback |
| `404.php` | 404 Errors | "Pagina niet gevonden" -> Search Bar -> Key Links -> Contact Info |

### F3. Component Inventory and Reusable Components

#### Global Components (Every Page)

| Component | Implementation |
|---|---|
| Header | Template Part. Logo, nav, phone tel: link, email mailto: link, cart icon. Optional sticky. |
| Main Navigation | Template Part. Desktop dropdown with hover. Mobile hamburger with accordion. |
| Footer | Template Part. 5-column layout. Contact info from Customizer. Legal links. Social icons. |
| Cookie Banner | Complianz plugin (renders globally, first visit only) |
| Cookie Settings Button | Complianz plugin (floating, post-consent) |
| Breadcrumbs | Yoast/Rank Math + theme integration. Visible on all inner pages. |
| Skip to Content Link | Theme. First focusable element on every page. |
| Back to Top Button | Theme. Floating, appears after 300px scroll. |

#### Block Patterns (Pre-Built Layouts)

| Pattern | Used On | Fields |
|---|---|---|
| Hero Section | Home, Service, Category Landings | Heading, Subtitle, CTA URL+Text, BG Image, Overlay Color |
| Service Card Grid | Home, Category Landings | Service selection (multi-select), Columns |
| USP Grid | Home, About | Repeater: icon, title, text. Columns. BG Color. |
| CTA Banner | Home, Service, About, anywhere | Heading, Text, Button URL+Text, BG Color, Full-width toggle |
| Content with Image | Service, About | Image position (left/right/stacked), Heading, Content (rich text) |
| Service Icon List | Service pages | Repeater: icon, text |
| Client Logo Carousel/Grid | Home, Referenties | Repeater: image, alt text, optional link |
| Testimonial Block | Home, Referenties, Service | Quote, Author, Company, Star rating, Photo |
| FAQ Accordion | Veelgestelde Vragen, Service | Repeater: question, answer (rich text). FAQPage schema auto-generated. |
| Cross-Sell Services | Service pages | Service selection (multi-select), Title override |
| Job Vacancy Card | Vacatures | Job title, hours, location, description, contact |
| Download Card List | Downloads | Repeater: filename, description, file URL, file size, file type icon |
| Contact Info + Map | Contact, Footer | Address, phone, email, map iframe embed |
| Latest Blog Posts | Home, Category Landings | Number of posts, category filter, columns |
| Related Posts | Blog post | Number of posts, category filter |
| 404 Content | 404 page | Heading, text, search form |

#### Custom Gutenberg Blocks (If Patterns Insufficient)

| Block | Purpose |
|---|---|
| `hds/service-card` | Single service card renderer. Used in Service Card Grid. |
| `hds/testimonial` | Queries `hds_testimonial` CPT and renders configured template. |
| `hds/job-listing` | Queries `hds_vacancy` CPT and renders configured template. |
| `hds/contact-info` | Renders NAP + map from Company Information settings in Customizer. |

### F4. Design System Requirements

#### Brand Identity (Preserved)

| Element | Value |
|---|---|
| Company Name | HDS Onderhoudsdiensten (unchanged) |
| Tagline | "Helder en Duidelijk voor het Schoonste resultaat!" (unchanged) |
| Domain Branding | HelderDuidelijkSchoon.nl (unchanged) |

#### Design Tokens (MUST Be Defined Before Development)

| Category | Status |
|---|---|
| Color Palette (Primary, Secondary, Accent, Neutral scale, Status colors) | **MI-07: Client to provide or approve** |
| Typography (Heading font, Body font, Sizes, Weights, Line heights) | **MI-08: Client to provide or approve. Default: Open Sans kept unless client wants change.** |
| Spacing Scale (4px-base: 4,8,12,16,20,24,32,40,48,64,80,96,128) | Standard — recommend adoption |
| Border Radius (0, 4, 8, 16, pill) | Standard — recommend adoption |
| Shadows (none, sm, md, lg, xl) | Standard — recommend adoption |
| Breakpoints (Mobile: 0-767, Tablet: 768-1023, Desktop: 1024-1279, Wide: 1280+) | Standard — recommend adoption |
| Container Max-Width (1200px or 1280px) | Design decision pending |
| Icon Library (Phosphor Icons or Font Awesome 6 or custom SVG) | Design decision pending |

#### Typography Specification

| Element | Desktop Size | Mobile Size | Weight | Line Height |
|---|---|---|---|---|
| H1 | 36-48px | 28-36px | 700 | 1.2 |
| H2 | 28-36px | 22-28px | 700 | 1.3 |
| H3 | 22-28px | 18-24px | 600 | 1.3 |
| H4 | 18-22px | 16-20px | 600 | 1.4 |
| Body | 16-18px | 16px | 400 | 1.6-1.7 |
| Small/Caption | 14px | 14px | 400 | 1.5 |
| Button Text | 16px | 16px | 600 | 1.0 |
| Nav Links | 16px | 18px (touch) | 500-600 | 1.0 |

#### Block Style Variations

| Block | Variation | Description |
|---|---|---|
| Button | `is-style-primary` | Filled primary color |
| Button | `is-style-secondary` | Outlined secondary |
| Button | `is-style-cta` | Large CTA with arrow icon |
| Group | `is-style-card` | White bg, border-radius, shadow |
| Group | `is-style-banner` | Colored bg, full-width |
| List | `is-style-icon-list` | Custom icon bullets (checkmark/arrow) |

---

## Part G: Forms, WooCommerce, Local SEO, Structured Data, Analytics

### G1. Forms Specification

#### G1.1 Contact Form (at `/contact/`)

| Field | Type | Required | Notes |
|---|---|---|---|
| Naam | Text | Yes | — |
| Bedrijf | Text | No | — |
| E-mailadres | Email | Yes | Valid format validation |
| Telefoonnummer | Tel | No | Dutch format |
| Onderwerp | Dropdown | Yes | Offerte aanvragen, Vraag over diensten, Klacht/opmerking, Anders |
| Bericht | Textarea | Yes | Min 10 characters |
| Privacy akkoord | Checkbox | Yes | Unchecked default. Link to /privacyverklaring/. |
| Anti-spam | reCAPTCHA v3 + Honeypot | Yes | Invisible to user |
| Submit | Button | — | "Verstuur bericht" |

**Post-submit:** Redirect to `/bedankt/?type=contact`. Confirmation email to user. Notification email to `info@helderduidelijkschoon.nl`. Entry stored in Gravity Forms.

#### G1.2 Quote Request Form (at `/offerte-aanvragen/`)

| Field | Type | Required | Notes |
|---|---|---|---|
| Naam | Text | Yes | |
| Bedrijf | Text | Yes | |
| E-mailadres | Email | Yes | |
| Telefoonnummer | Tel | Yes | |
| Gewenste dienst | Checkboxes (multi) | Yes | All 7 services + Anders |
| Type gebouw | Dropdown | No | Kantoor, Wooncomplex/VvE, School, Zorginstelling, Fabriek/Magazijn, Bouwproject, Anders |
| Postcode / Plaats | Text | Yes | Service area verification |
| Beschrijving | Textarea | No | |
| Gewenste planning | Dropdown | No | Zo snel mogelijk, Binnen 2 weken, Binnen 1 maand, Binnen 3 maanden, Orienterend |
| Hoe gevonden? | Dropdown | No | Google, VvE Belang, Social media, Relatie, Anders |
| Bestand uploaden | File | No | Max 5MB. PDF, JPG, PNG, DOCX. Renamed server-side. |
| Privacy akkoord | Checkbox | Yes | |
| Anti-spam | reCAPTCHA v3 | Yes | |
| Submit | Button | — | "Offerte aanvragen" |

**Post-submit:** Redirect to `/bedankt/?type=offerte`. Confirmation email with summary. Notification to info@ with all data + download link for attachment.

#### G1.3 Vacature Application Form (per vacancy at `/vacatures/`)

| Field | Type | Required |
|---|---|---|
| Naam | Text | Yes |
| E-mailadres | Email | Yes |
| Telefoonnummer | Tel | Yes |
| Motivatie | Textarea | Yes |
| CV uploaden | File | Yes (Max 5MB, PDF/DOCX) |
| Privacy akkoord | Checkbox | Yes |

#### G1.4 Form Configuration (All Forms)

| Setting | Value |
|---|---|
| Confirmation email from | info@helderduidelijkschoon.nl |
| Notification email to | info@helderduidelijkschoon.nl (configurable) |
| Entry storage | WordPress database via Gravity Forms |
| Auto-delete entries | After 12 months |
| Spam protection | reCAPTCHA v3 + honeypot field |
| File upload security | Server-side type validation. Rename files. Scan if possible. |
| Accessibility | All fields have labels. Required fields marked. Errors via aria-describedby. |
| Error display | Inline, red text, programmatically associated with field |
| Success behavior | Redirect to /bedankt/ with query parameter |

### G2. WooCommerce Specification

#### G2.1 Core Configuration

| Setting | Value |
|---|---|
| Shop Page | `/winkel/` |
| Cart Page | `/winkelmand/` |
| Checkout Page | `/afrekenen/` |
| My Account Page | `/mijn-account/` |
| Terms Page | `/algemene-voorwaarden/` |
| Privacy Page | `/privacyverklaring/` |
| Currency | EUR (€) |
| Currency Position | Left |
| Thousand Separator | `.` |
| Decimal Separator | `,` |
| Decimals | 2 |
| Prices Entered With Tax | No (excl. BTW) |
| Display Suffix | "excl. BTW" |
| Tax Rate | 21% (Dutch standard BTW) |
| Weight Unit | kg |
| Dimension Unit | cm |
| Coupons | Enabled |
| Reviews | Enabled (moderated) |
| Guest Checkout | Enabled |
| Inventory Management | Enabled |
| Backorders | Disabled |

#### G2.2 Payment Gateway

| Gateway | Status | Notes |
|---|---|---|
| Mollie | **Recommended** | iDEAL, Bancontact, cards, PayPal, SEPA. Dutch provider. |
| Stripe | Alternative | International. |
| Bank Transfer (BACS) | Built-in | For B2B invoice-based payment. |

**MI-15: Client must decide payment methods and gateway. Mollie recommended for Dutch market.**

#### G2.3 Shipping

| Setting | Value |
|---|---|
| Shipping Zones | Nederland (default) |
| Shipping Classes | "Klein pakket" (filters, lamps), "Groot pakket" (Airfixr units) |
| Methods | Flat rate OR free over €X,00 OR weight-based |

**MI-14: Client must provide shipping costs and delivery policy.**

#### G2.4 WooCommerce Emails

All emails: branded with logo, Dutch language, from "HDS Onderhoudsdiensten" <info@helderduidelijkschoon.nl>. Order confirmation, processing, completed, refund, invoice, and account emails enabled.

#### G2.5 Airfixr Landing Page (`/luchtreiniging/`)

Dedicated page introducing Airfixr product line with explanation of connection to cleaning services. Highlights key products with shop links. CTA: "Bekijk alle producten" -> `/winkel/`.

### G3. Local SEO Requirements

| # | Requirement | Priority |
|---|---|---|
| L01 | Claim/verify Google Business Profile for HDS Onderhoudsdiensten | P0 CRITICAL |
| L02 | Ensure NAP (Name, Address, Phone) identical across website, GBP, Facebook, Instagram, VvE Belang, OSB, and all directories | P0 CRITICAL |
| L03 | Link GBP from website footer. Link website from GBP. | P0 |
| L04 | Add all relevant service categories to GBP (Schoonmaakbedrijf, Glazenwasser, etc.) | P1 |
| L05 | Add service area (all municipalities served) to GBP | P1 |
| L06 | Add business hours to GBP | P1 |
| L07 | Upload 10+ photos to GBP: exterior, team, before/after, vehicles, logo | P1 |
| L08 | Post monthly updates/offers to GBP | P2 |
| L09 | Submit to Dutch business directories: Bedrijvenpagina.nl, MKB.nl, Telefoonboek.nl, Detelefoongids.nl, Drimble.nl, NationaleBedrijvengids.nl | P2 |
| L10 | Verify NAP consistency across all existing listings (VvE Belang, OSB if listed) | P1 |
| L11 | Location-specific landing pages (future): /schoonmaakbedrijf-bergen-op-zoom/, /schoonmaakbedrijf-roosendaal/, etc. | P3 post-launch |

### G4. Structured Data Requirements

| Schema Type | Pages | Priority | Implementation |
|---|---|---|---|
| `LocalBusiness` (HomeAndConstructionBusiness) | Home, Contact, Over HDS | P0 | Custom JSON-LD in theme. All MI-01, MI-02, MI-03, MI-04 fields required. |
| `WebSite` with SearchAction | All pages | P0 | Auto-generated by Yoast/Rank Math |
| `WebPage` | All pages | P0 | Auto-generated |
| `BreadcrumbList` | All inner pages | P0 | Yoast/Rank Math + theme integration |
| `Service` | Each service page (P02-P08) | P1 | Custom JSON-LD per page. name, description, provider, areaServed, serviceType. |
| `FAQPage` | Veelgestelde Vragen (P18) | P2 | Yoast FAQ block auto-generates |
| `Product` (WooCommerce) | Each product (P25 x14) | P1 | Auto-generated by WooCommerce |
| `JobPosting` | Each vacancy on Vacatures (P14) | P2 | Custom per vacancy |
| `Review` | Testimonials with star ratings | P2 | Custom if testimonials include ratings |

**Key Schema Notes:**
- All [MISSING] fields in LocalBusiness schema (address, geo, openingHours) require client-provided information (MI-01, MI-04, MI-05)
- All schema must validate via Google Rich Results Test before launch
- Schema delivered via JSON-LD (not microdata) for clean separation from HTML

### G5. Analytics and Tracking Requirements

#### G5.1 Google Analytics 4 Setup

| Configuration | Detail |
|---|---|
| Property | GA4 — "HDS Onderhoudsdiensten" |
| Data Stream | Web: helderduidelijkschoon.nl |
| Data Retention | 14 months |
| Enhanced Measurement | Page views, scrolls, outbound clicks, site search, video engagement, file downloads — ALL enabled |
| Internal Traffic Filter | Office IP (client to provide) |
| Bot Filtering | Enabled |

#### G5.2 Google Tag Manager

- All tracking scripts deployed via GTM (no hardcoded scripts in theme)
- GTM snippet in `<head>`
- Consent Mode v2 integration with Complianz
- Data Layer pushed for key events

#### G5.3 Conversion Events

| Event | Trigger | GA4 Event Name |
|---|---|---|
| Phone call click | `tel:` link clicked | `phone_click` |
| Email click | `mailto:` link clicked | `email_click` |
| Contact form submission | Redirect to `/bedankt/?type=contact` | `form_submission` |
| Quote request submission | Redirect to `/bedankt/?type=offerte` | `quote_request` |
| WooCommerce add to cart | Product added | `add_to_cart` |
| WooCommerce purchase | Order completed | `purchase` |
| Cookie consent accepted | Banner "Accepteren" clicked | `cookie_consent_accepted` |

#### G5.4 Reporting Cadence

| Report | Frequency | Recipient |
|---|---|---|
| Traffic + Conversions | Monthly | Client |
| Landing Page Performance | Monthly | Client |
| Conversion Source Analysis | Monthly | Client |
| SEO Performance | Monthly | Client |
| Technical Health (uptime, speed, errors) | Monthly | Developer |

---

## Part H: Security, GDPR, Performance, Accessibility, Development Constraints

### H1. Security Requirements

#### H1.1 Server-Level Security

| # | Requirement | Implementation |
|---|---|---|
| S01 | HTTPS only | HSTS header: max-age=31536000; includeSubDomains; preload |
| S02 | XML-RPC disabled | Block at web server level. Return 403. |
| S03 | Directory listing disabled | Options -Indexes |
| S04 | File permissions | Directories: 755, Files: 644, wp-config.php: 400 |
| S05 | wp-config.php secured | Moved above web root OR locked via permissions. Salts rotated. DB prefix changed from `wp_`. |
| S06 | Database security | Strong unique password. User has minimum required privileges (no DROP, ALTER, GRANT). |
| S07 | SFTP only | No FTP. Key-based authentication preferred. |

#### H1.2 Application-Level Security

| # | Requirement | Implementation |
|---|---|---|
| S08 | Security plugin | Wordfence Premium OR Solid Security Pro |
| S09 | 2FA | On ALL admin accounts. No exceptions. |
| S10 | Custom login URL | Not /wp-admin/ or /wp-login.php. |
| S11 | Login limiting | Max 3 failed attempts -> IP lockout. |
| S12 | Admin username policy | Never "admin", "hds", or "helderduidelijkschoon". |
| S13 | Plugin source policy | Official WordPress.org repo or trusted premium vendors ONLY. No nulled/cracked plugins. |
| S14 | Theme code standards | No eval(), no base64_decode(). Output escaped (esc_html, esc_attr, wp_kses). Inputs sanitized. Nonces on all forms. |
| S15 | REST API hardening | Block /wp-json/wp/v2/users endpoint. |
| S16 | File editor disabled | define('DISALLOW_FILE_EDIT', true); |
| S17 | Auto-updates | Enabled for minor core/plugin/theme releases. Major releases tested on staging first. |

#### H1.3 Regular Security Operations

| Task | Frequency |
|---|---|
| Update core/plugins/themes | Weekly |
| Malware scan | Daily (Wordfence auto) |
| Review security logs | Weekly |
| Audit admin accounts | Monthly |
| Backup test restore | Monthly |
| Password rotation | Quarterly |
| External security audit | Annually |

### H2. GDPR / AVG Compliance Requirements

#### H2.1 Required Pages

| Page | URL | Content Source | Legal Review Required |
|---|---|---|---|
| Privacyverklaring | `/privacyverklaring/` | Drafted by developer/client, reviewed by legal counsel | **YES — before launch** |
| Cookiebeleid | `/cookiebeleid/` | Auto-generated by Complianz | **YES — review auto-content** |
| Algemene Voorwaarden | `/algemene-voorwaarden/` | Client provides text (MI-16) | **YES — by client's legal counsel** |
| Disclaimer | `/disclaimer/` | Drafted, reviewed | Recommended |

#### H2.2 Technical GDPR Measures

| # | Requirement | Implementation |
|---|---|---|
| G01 | Cookie consent | Complianz Premium. No non-functional cookies before consent. Consent logged (timestamp, anonymized IP, consent string). |
| G02 | Form consent checkboxes | All forms: "Ik ga akkoord met de privacyverklaring" — unchecked by default. Links to privacy policy. |
| G03 | Data access requests | Process documented. Gravity Forms entries exportable. WooCommerce customer data exportable. |
| G04 | Data retention | Contact entries: auto-delete after 12 months. WooCommerce orders: retain 7 years (Dutch financial data requirement). |
| G05 | Right to erasure | Process for deleting personal data from WP users, form entries, customer data, and backups. |
| G06 | Data breach notification | Process for detecting, investigating, notifying Autoriteit Persoonsgegevens within 72 hours. |
| G07 | Data Processing Agreement | Signed DPA with hosting provider, Google (analytics), and any third-party processors. |
| G08 | SSL/TLS | All data in transit encrypted via HTTPS. |
| G09 | IP anonymization | GA4 enabled by default. Confirm. |

#### H2.3 Legal Disclaimer

**This specification outlines technical implementation requirements for GDPR/AVG compliance. It does not constitute legal advice. The client must engage a qualified Dutch privacy lawyer to review all legal pages before launch.**

### H3. Performance Requirements

#### H3.1 Performance Budgets (ALL PAGES)

| Metric | Target | Tool |
|---|---|---|
| Largest Contentful Paint (LCP) | < 2.5s | PSI, Lighthouse |
| First Input Delay (FID) | < 100ms | PSI, Lighthouse |
| Cumulative Layout Shift (CLS) | < 0.1 | PSI, Lighthouse |
| Interaction to Next Paint (INP) | < 200ms | PSI, Chrome UX Report |
| Time to First Byte (TTFB) | < 600ms | WebPageTest |
| Total Page Weight (Mobile) | < 1.5 MB | WebPageTest |
| Total Page Weight (Desktop) | < 3 MB | WebPageTest |
| Speed Index | < 3.4s | Lighthouse |
| PSI Mobile Score | 90+ | PSI |
| PSI Desktop Score | 95+ | PSI |

#### H3.2 Performance Implementation

| Requirement | Implementation |
|---|---|
| Page caching | WP Rocket or FlyingPress |
| Object caching | Redis on server |
| Browser caching | 1 year for static assets with versioned filenames |
| Critical CSS | Inlined in `<head>`. Auto-generated by WP Rocket. |
| Non-critical CSS | Deferred loading |
| JavaScript | `defer` attribute. No render-blocking JS. |
| jQuery | Removed unless WooCommerce requires it. No jQuery Migrate. |
| Fonts | Self-hosted (no Google Fonts CDN). `font-display: swap`. Preloaded. Subset to Latin + Dutch diacritics. |
| Images | WebP via `<picture>`. Explicit dimensions. Lazy loading below fold. `fetchpriority="high"` on LCP. |
| Third-party scripts | Via GTM with appropriate triggers. Deferred. Audited regularly. |
| CDN | Cloudflare: full-page caching, Polish (image optimization), auto-minify. |
| Database | Clean: no old revisions, no spam, no transient garbage. |

#### H3.3 Pre-Launch Performance Validation

Every page template tested before launch:
1. PSI mobile + desktop — must score 90+/95+
2. WebPageTest (Amsterdam, Moto G4, 3G Fast)
3. GTmetrix
4. DebugBear Core Web Vitals lab test
5. Chrome DevTools Performance tab — long tasks audit
6. Chrome DevTools Coverage tab — unused CSS/JS < 30%

### H4. Accessibility Requirements (WCAG 2.2 AA)

**Compliance Target:** WCAG 2.2 Level AA — ALL success criteria.

#### H4.1 Mandatory Requirements

| # | Requirement | WCAG SC | Implementation |
|---|---|---|---|
| A01 | Color contrast | 1.4.3, 1.4.11 | Text: 4.5:1 (normal), 3:1 (large). UI: 3:1. Test via axe DevTools. |
| A02 | Keyboard navigation | 2.1.1, 2.1.2 | All interactive elements focusable and operable via keyboard. Visible focus indicator. |
| A03 | Skip to content | 2.4.1 | First focusable element. Visible on focus. Links to `<main>`. |
| A04 | Semantic HTML | 1.3.1 | H1-H2-H3 (no skipped levels). `<header>`, `<nav>`, `<main>`, `<footer>`, `<section>`. |
| A05 | ARIA landmarks | 1.3.1 | banner, navigation, main, contentinfo, search. Native HTML5 preferred over ARIA. |
| A06 | Alt text | 1.1.1 | All non-decorative images: descriptive Dutch alt text. Decorative: `alt=""`. |
| A07 | Form labels | 1.3.1, 3.3.2 | All fields have `<label>`. Required: text + `aria-required`. Errors: `aria-describedby`. |
| A08 | Link text | 2.4.4 | Descriptive destination. No "klik hier". External: `rel="noopener noreferrer"` + warning. |
| A09 | Multimedia | 1.2.2, 1.2.3 | Video: captions. Audio: transcripts. (Low priority at launch.) |
| A10 | Zoom/Resize | 1.4.4 | Usable at 200% zoom. No horizontal scroll. No cut-off content. |
| A11 | Motion | 2.3.1, 2.3.2 | No auto-play. No flashing >3/sec. Respect `prefers-reduced-motion`. |
| A12 | Touch targets | 2.5.8 (AAA) | Minimum 44x44px (AAA recommendation adopted as AA target). |
| A13 | Error identification | 3.3.1, 3.3.3 | Identify field, describe error, suggest correction. |
| A14 | Language | 3.1.1 | `lang="nl-NL"` on `<html>`. English blocks: `lang="en"`. |
| A15 | Page title | 2.4.2 | Every page: unique, descriptive `<title>`. |
| A16 | Status messages | 4.1.3 | Dynamic updates announced via `aria-live` regions. |
| A17 | Consistent navigation | 3.2.3 | Nav order and position consistent across all pages. |
| A18 | Consistent identification | 3.2.4 | Same-function components labelled consistently. |

#### H4.2 Testing Protocol

| Test | Tool | Threshold |
|---|---|---|
| Automated | axe DevTools | Zero critical issues. Zero serious issues. |
| Automated | WAVE | Zero errors. |
| Automated | Lighthouse Accessibility | Score = 100 on every template. |
| Manual keyboard | Tab through every page | All elements reachable and operable. Focus visible. |
| Manual screen reader | NVDA (Windows) or VoiceOver (Mac) | Content announced correctly. Forms usable. |
| Color contrast | WebAIM or axe | All elements pass AA thresholds. |
| 200% zoom | Browser | No content loss. No horizontal scroll. |
| Real mobile | VoiceOver iOS / TalkBack Android | Usable on real device. Minimum 3 pages tested. |

### H5. Development Constraints

#### H5.1 Technology Constraints (Hard Gates)

| # | Constraint | Rationale |
|---|---|---|
| DC01 | NO third-party page builders (Divi, Elementor, WPBakery, Beaver Builder, etc.) | Performance, lock-in, migration risk. |
| DC02 | Native Block Editor (Gutenberg) ONLY for content layout | Standardization, future-proofing, FSE compatibility. |
| DC03 | NO shortcode-based content storage | Content trapped in shortcodes is a migration nightmare. |
| DC04 | PHP 8.2+ minimum | EOL for older versions. Required by modern WP + WooCommerce. |
| DC05 | All custom code in version control (Git) | Audit trail, rollback capability, collaboration. |
| DC06 | No tracking scripts hardcoded in theme | All scripts via GTM for centralized management and consent compliance. |
| DC07 | Fonts must be self-hosted | Performance (no external CDN dependency). Privacy (no Google Fonts requests). |
| DC08 | All plugins from official sources ONLY | Security. No nulled or cracked plugins. |
| DC09 | Theme code: no eval(), no base64_decode() | Security best practice. |
| DC10 | All output escaped, all inputs sanitized | WordPress coding standards. |
| DC11 | Nonces on all custom forms | CSRF protection. |
| DC12 | Database prefix changed from `wp_` | Security hardening. |

#### H5.2 Process Constraints

| # | Constraint | Rationale |
|---|---|---|
| DC13 | All changes tested on staging before production | Prevent regressions. |
| DC14 | Full backup before every production deployment | Rollback capability. |
| DC15 | Client sign-off required before each phase transition | Governance. No surprises. |
| DC16 | Staging: noindex, password-protected | Prevent indexing and unauthorized access. |
| DC17 | Zero redirect chains in migration | SEO preservation. |
| DC18 | All content reviewed by native Dutch speaker before publication | Quality assurance. |
| DC19 | Legal pages reviewed by qualified Dutch privacy lawyer before launch | Legal compliance. |

#### H5.3 Content Constraints

| # | Constraint | Rationale |
|---|---|---|
| DC20 | Service pages: minimum 300 words Dutch content | SEO minimum. |
| DC21 | Category landings: minimum 500 words | SEO for competitive terms. |
| DC22 | All images: alt text in Dutch | Accessibility + SEO. |
| DC23 | No lorem ipsum or placeholder text at launch | Professionalism. |
| DC24 | Phone number and email consistent across all pages | Trust. Single source of truth in Customizer. |

---

## Part I: Technical Debt, QA, Risk Register, Assumptions, Open Questions

### I1. Technical Debt Resolution Plan

The current site carries significant technical debt. This plan addresses ALL known debt through the rebuild. No debt carries forward.

| # | Debt Item | Current Impact | Resolution | Phase |
|---|---|---|---|---|
| TD01 | Contact page returns HTTP 500 | Zero web leads captured | Rebuild page with Gravity Forms | Phase 2 |
| TD02 | Reguliere Schoonmaak returns HTTP 404 | Primary service invisible | Build new 300+ word page | Phase 2 |
| TD03 | Page sitemap returns HTTP 500 | Search engines cannot discover pages | Yoast/Rank Math regeneration | Phase 5 |
| TD04 | WordPress 6.2.9 (5+ versions behind) | Security vulnerabilities | Upgrade to 6.7+ (fresh install) | Phase 1 |
| TD05 | Divi 4.16.1 (10+ versions behind) | Performance overhead, security risk, lock-in | **Replace entirely** with custom block theme | Phase 1 |
| TD06 | WooCommerce 8.2.5 (2+ major versions behind) | Compatibility issues, missing features | Fresh install of 9.x+ | Phase 4 |
| TD07 | Yoast SEO 21.8.1 outdated | Sitemap broken, schema incomplete | Fresh install of latest version | Phase 5 |
| TD08 | Formidable Forms broken | Contact form inaccessible | **Replace** with Gravity Forms | Phase 2 |
| TD09 | XML-RPC enabled | Brute-force attack vector | Disable at server level | Phase 6 |
| TD10 | No privacy policy | GDPR/AVG violation | Create /privacyverklaring/ | Phase 3 |
| TD11 | No cookie consent | ePrivacy violation | Implement Complianz | Phase 6 |
| TD12 | No meta descriptions | Zero SERP CTR optimization | Write unique descriptions for all 32+ pages | Phase 5 |
| TD13 | 9 pages with <150 words | Cannot rank for competitive terms | Expand all to 300+ words | Phase 2-3 |
| TD14 | Vacatures as JPG images | Inaccessible, unindexable | Convert to HTML text + structured data | Phase 3 |
| TD15 | PDFs on legacy domain | Single point of failure | Migrate to primary domain | Phase 3 |
| TD16 | No analytics | Zero data-driven decisions | Implement GA4 + GTM | Phase 5 |
| TD17 | No backup strategy | Total data loss risk | Daily automated offsite backups | Phase 1 |
| TD18 | Default "Hello World" post live | Unprofessional appearance | Delete. 410 Gone. | Phase 0 |
| TD19 | Instagram widget broken on every page | Unprofessional appearance | Replace with static icon link | Phase 3 |
| TD20 | No responsive testing performed | Unknown mobile experience | Full mobile QA on real devices | Phase 7 |
| TD21 | ~50 attachment pages indexed and in sitemap | Crawl budget waste | Exclude from sitemap. Redirect to parent. | Phase 5 |
| TD22 | PHP version unknown (likely outdated) | Security and performance penalty | Upgrade to PHP 8.2+ | Phase 1 |
| TD23 | jQuery Migrate loaded | Performance overhead | Remove dependency | Phase 1 |

**Resolution Verification:** All TD items must be verified resolved before launch. Verification method: TD01-TD21 are testable (page loads, software versions, etc.). TD22-TD23 are server-level checks.

### I2. QA Test Plan

#### I2.1 Test Categories and Coverage

| Category | Scope | Tools | Pass Threshold |
|---|---|---|---|
| **Functional** | All 32 pages, all 3 forms, WooCommerce purchase flow, search, navigation, all links | Manual + Screaming Frog | Zero critical bugs. Zero broken links. |
| **Cross-Browser** | Chrome, Firefox, Safari, Edge (latest 2 versions each) | Manual + BrowserStack | Consistent rendering. All functions work. |
| **Mobile/Tablet** | iPhone 14+ (iOS Safari), Android Chrome, iPad | Real devices | Responsive. No horizontal scroll. Touch targets 44px+. Forms usable. |
| **Accessibility** | All page templates | axe DevTools, WAVE, Lighthouse, NVDA, keyboard-only, 200% zoom, WebAIM contrast | axe: zero critical/serious. Lighthouse: 100. WAVE: zero errors. Keyboard: complete. |
| **Performance** | All page templates | PSI, WebPageTest, GTmetrix, DebugBear | PSI mobile 90+, desktop 95+. LCP < 2.5s. CLS < 0.1. |
| **SEO** | All 32+ pages | Screaming Frog, Google Rich Results Test, GSC, manual | See I2.2 below. |
| **Security** | Server + application | Wordfence scan, manual penetration test checklist | No critical/high vulnerabilities. XML-RPC disabled. 2FA active. |
| **GDPR** | Cookie consent, form consent, data retention, legal pages | Manual + Complianz scan log | Consent banner appears. No cookies before consent. Checkboxes unchecked. Legal pages published. |

#### I2.2 SEO Test Coverage

- [ ] Every page has unique `<title>` (50-60 chars, keyword + brand + location)
- [ ] Every page has unique `<meta description>` (150-160 chars, keyword + value + CTA)
- [ ] Zero duplicate titles or descriptions (Screaming Frog)
- [ ] Zero empty titles or descriptions (Screaming Frog)
- [ ] H1 present exactly once per page
- [ ] H2/H3 hierarchy logical, no skipped levels
- [ ] All images have alt text (Screaming Frog: zero missing)
- [ ] Self-referencing canonicals on all pages
- [ ] Open Graph tags complete on all pages (og:title, og:description, og:image, og:url, og:type, og:locale)
- [ ] Twitter Card tags complete
- [ ] LocalBusiness schema: present + valid (Rich Results Test)
- [ ] Service schema on each service page: valid (Rich Results Test)
- [ ] FAQPage schema on FAQ page: valid (Rich Results Test)
- [ ] BreadcrumbList schema on all inner pages
- [ ] XML Sitemap: returns 200, valid XML, zero attachment pages, zero noindex pages
- [ ] robots.txt: returns 200, correct disallow rules
- [ ] All 301 redirects return 301 (not 302, not 307)
- [ ] Zero redirect chains (Screaming Frog or httpstatus.io)
- [ ] HTTPS enforced: HTTP -> HTTPS 301 + HSTS header
- [ ] Non-www redirects working
- [ ] Internal links: zero broken (Screaming Frog)
- [ ] Internal links: zero orphan pages (Screaming Frog)
- [ ] Mobile-friendly test passes (Google Mobile-Friendly Test)

### I3. Risk Register

| ID | Risk | Severity | Likelihood | Impact | Mitigation | Owner |
|---|---|---|---|---|---|---|
| R01 | Data loss during migration | CRITICAL | Low | Total site loss | Full backup before any step. Offsite storage. Test restore verified. | Developer |
| R02 | Client delays providing required information (MI-01 through MI-25) | HIGH | High | Timeline slip, incomplete launch | Early communication of dependencies. Phase 0 deadline. Parallel work where possible. | Client |
| R03 | Temporary traffic/ranking drop post-migration | HIGH | Medium | Lead volume decrease | URLs preserved. 301 redirects. Sitemap submitted. GSC daily monitoring. Expected normalization within 2-4 weeks. | Developer |
| R04 | Legal review delays for privacy policy | CRITICAL | Medium | Cannot launch legally | Legal pages drafted early (Phase 3). Client engages lawyer in Phase 0. | Client |
| R05 | Performance regression after launch | MEDIUM | Medium | Poor user experience | Pre-launch benchmarks. Post-launch monitoring. CDN + caching configured pre-launch. | Developer |
| R06 | Plugin conflict post-launch | MEDIUM | Low | Feature breakage | Identical staging stack. Full test suite before deployment. Staged rollout. | Developer |
| R07 | Security breach | HIGH | Low | Data loss, reputation damage | Wordfence active. 2FA on all accounts. XML-RPC disabled. Auto-updates. Daily malware scans. | Developer |
| R08 | Backup failure | HIGH | Low | No restore capability | Daily verification. Monthly test restore. Alert on failure. | Developer |
| R09 | Hosting outage | MEDIUM | Low | Site unavailable | Managed host with 99.9%+ SLA. UptimeRobot monitoring. Client has support number. | Hosting |
| R10 | Domain expiry | CRITICAL | Low | Complete site loss | Auto-renew enabled. Client reminded 90/60/30 days before expiry. | Client |
| R11 | Email delivery interruption | CRITICAL | Low | Form submissions lost | MX records documented and unchanged. SMTP via transactional email service. Post-launch form test. | Developer |
| R12 | DNS propagation delay | MEDIUM | Medium | Some users see old site | Lower TTL to 300 (5 min) 24h before launch. Monitor via whatsmydns.net. | Developer |
| R13 | Budget exceeded | HIGH | Medium | Incomplete delivery | Fixed-scope specification. Phase-based delivery. Change requests as separate scope. | Developer |
| R14 | Client rejects design direction | MEDIUM | Medium | Rework delay | Design tokens and direction approved in Phase 0. Locked before Phase 1 development. | Client |
| R15 | Airfixr product line removal requested | LOW | Medium | WooCommerce scope reduction | Decision made in Phase 0. Shop scope adjusted accordingly. | Client |
| R16 | Third-party developer (Pi-Apps) interference | LOW | Unknown | Confusion, access issues | Rebuild on new hosting. No dependency on old developer. Domain access controlled by client. | Client |

### I4. Assumptions Register

| ID | Assumption | Impact If Wrong | Validation Method |
|---|---|---|---|
| ASM01 | Client wants complete rebuild, not repair | Entire spec discarded. Switch to P0-only patching. | Client sign-off in Phase 0 |
| ASM02 | Client can obtain all required access (domain, hosting, WP admin, Google accounts) | Development blocked | Verify in Phase 0 |
| ASM03 | WordPress is the preferred CMS | CMS architecture rewrite required | Client confirmation in Phase 0 |
| ASM04 | Client accepts block-based theme (no Divi/page builder) | Spec conflicts with performance/maintainability goals | Client confirmation in Phase 0 |
| ASM05 | Client will provide MI-01 through MI-25 before respective phase deadlines | Pages incomplete, schema incomplete, trust signals absent | Track against Phase 0-3 deadlines |
| ASM06 | Client will engage legal counsel for privacy policy review | Legal non-compliance risk | Confirmation before Phase 3 completion |
| ASM07 | Client wants to keep WooCommerce webshop for Airfixr | Shop scope removed from project | Client confirmation in Phase 0 |
| ASM08 | Client serves only Dutch market (West-Brabant/Zeeland) | Multi-language, hreflang, multi-region schema needed | Client confirmation in Phase 0 |
| ASM09 | 8-9 week timeline is acceptable | Compressed phases increase risk | Client sign-off on roadmap (Section J1) |
| ASM10 | Current hosting can be replaced or upgraded | Hosting requirements may need downgrade | Verify in Phase 0 |
| ASM11 | GA4 analytics is acceptable (with consent mode) | If privacy-friendly analytics preferred, replace with Plausible/Fathom | Client confirmation in Phase 0 |
| ASM12 | Client will maintain site post-launch with developer support available | If fully managed service required, maintenance retainer needed | Discuss in Phase 0 |
| ASM13 | No international expansion planned in next 18 months | Architecture decisions may need revision | Client confirmation in Phase 0 |

### I5. Open Questions Register

Complete list of 42 open questions organized by category. See RS-08 Section 49 for full question details. Below is the priority subset that MUST be resolved in Phase 0 before any development begins.

#### Phase 0 — Must Resolve Before Development

| ID | Question | Category |
|---|---|---|
| Q01 | What is the legal entity type (BV, eenmanszaak, VOF)? | Company |
| Q02 | What is the physical business address? | Company |
| Q03 | What are the KVK and BTW numbers? | Company |
| Q04 | What are the business hours? | Operations |
| Q05 | Which municipalities/postcodes are served? | Operations |
| Q06 | Is Pi-Apps still the active developer? | Digital |
| Q07 | Do you have a Google Business Profile? Claimed/verified? | Digital |
| Q08 | What is the primary new-client source (website/phone/referral/tender)? | Digital |
| Q09 | What is the business purpose of selling Airfixr? Keep or remove? | Commercial |
| Q10 | What are the brand colors and typography preferences? | Design |
| Q11 | Is the logo available as vector file (SVG/AI/EPS)? | Design |
| Q12 | Which payment gateway is preferred? (Mollie recommended) | Commercial |
| Q13 | What are the shipping costs and delivery policy? | Commercial |
| Q14 | Who is the current hosting provider? Do you have access? | Infrastructure |
| Q15 | Do you have Google Analytics and Search Console access? | Infrastructure |
| Q16 | What is the monthly hosting budget? | Infrastructure |
| Q17 | What is the budget approval status for the rebuild? | Commercial |
| Q18 | Top 3 business goals for next 12 months? | Strategy |

**All remaining questions (Q19-Q42) must be resolved before their respective phase deadlines. See RS-08 Section 49 for the complete list.**

---

## Part J: Development Roadmap, Checklists, Launch Readiness

### J1. Development Roadmap — Sprint Breakdown

**Total Duration:** 8-9 weeks | **Sprints:** 2-week sprints | **Phases:** 8

---

#### Sprint 0: Prerequisites (Week 0)
**Goal:** Resolve all Phase 0 dependencies before development begins.

| Task | Owner | Deliverable |
|---|---|---|
| Obtain domain registrar access | Client | Login credentials |
| Select and provision managed WordPress hosting | Client/Dev | Staging + production environments provisioned |
| Resolve all 18 Phase 0 Open Questions (Q01-Q18) | Client | Answers documented |
| Approve brand colors, fonts, design direction (MI-07, MI-08) | Client | Design tokens locked |
| Provide logo vector file (MI-06) | Client | SVG/AI/EPS file |
| Decide on payment gateway (MI-15) | Client | Mollie or alternative selected |
| Engage legal counsel for privacy policy review | Client | Lawyer engaged |
| Approve project budget | Client | Budget signed off |
| Set up Git repository | Developer | Repo ready |
| Verify Google Analytics and GSC access (or create new) | Developer | GA4 + GSC verified |

---

#### Sprint 1: Foundation (Week 1-2)
**Goal:** Infrastructure, WordPress, theme foundation, design system implemented.

| Task | Depends On | Deliverable |
|---|---|---|
| Provision hosting + staging environment | Sprint 0 | Staging URL live |
| Install WordPress 6.7+ + all plugins | Hosting | WP instance configured |
| Set up Cloudflare CDN + SSL | Domain access | CDN + HTTPS active |
| Configure daily backups | Hosting | Backup system verified |
| Build custom theme foundation (header, footer, base styles) | Design tokens | Theme scaffold |
| Implement design system (CSS custom properties, typography) | Design tokens | Design tokens in code |
| Set up Git deployment pipeline | Git repo | Auto-deploy from Git to staging |
| Disable XML-RPC, configure security basics | WP instance | Security hardening started |

---

#### Sprint 2: Core Pages (Week 3-4)
**Goal:** Home page, all service pages, and conversion pages built.

| Task | Depends On | Deliverable |
|---|---|---|
| Build Home page template + populate content | Sprint 1 | Home page complete on staging |
| Build Service page template | Sprint 1 | Service template ready |
| Build all 7 service pages (P02-P08) | Service template | All service pages 300+ words |
| Build 2 category landing pages (P09, P10) | Service template | Landing pages 500+ words |
| Build Contact page template | Sprint 1 | Contact template ready |
| Build Contact page (P16) + configure Gravity Forms contact form | Contact template, MI-01 | Contact page + working form |
| Build Offerte Aanvragen page (P17) + configure quote form | Contact template | Quote page + working form with file upload |
| Build Bedankt page (P32) | Contact template | Thank-you page |
| Build 404 page (P31) | Sprint 1 | Custom 404 page |

---

#### Sprint 3: Supporting Pages (Week 5)
**Goal:** About pages, legal pages, Vacatures rebuilt, Downloads migrated, FAQ created.

| Task | Depends On | Deliverable |
|---|---|---|
| Build About page template | Sprint 1 | About template ready |
| Build Over HDS (P11) + Kwaliteit & Veiligheid (P12) | About template | 500+ words each |
| Build Referenties (P13) + client logo integration | MI-10, MI-11 | Referenties page with real content |
| Rebuild Vacatures (P14) as HTML text + application form | MI-12, CPT | Text vacancies + JobPosting schema |
| Migrate Downloads (P15) + migrate PDFs from legacy domain | Legacy domain access | PDFs on primary domain |
| Build Veelgestelde Vragen (P18) with FAQ schema | CPT `hds_faq` | 10-15 FAQ items |
| Build Legal template + all 4 legal pages (P19-P22) | MI-16, legal review | Legal pages published on staging |
| Fix Instagram: replace broken widget with static icon link | — | No error on any page |

---

#### Sprint 4: WooCommerce (Week 5-6)
**Goal:** Webshop fully configured and tested.

| Task | Depends On | Deliverable |
|---|---|---|
| Configure WooCommerce core settings | Sprint 1 | Shop configured |
| Import 14 Airfixr products | Current site product data | Products migrated |
| Configure Mollie (or chosen) payment gateway | MI-15 | Payment working in test mode |
| Configure shipping (zones, classes, rates) | MI-14 | Shipping rates active |
| Set up WooCommerce emails (branded, Dutch) | — | Order emails working |
| Build Luchtreiniging landing page (P23) | — | Airfixr intro page 300+ words |
| Add shop intro text to /winkel/ (P24) | — | Shop description written |
| Test full purchase flow: Product -> Cart -> Checkout -> Payment -> Email | All above | End-to-end test passed |

---

#### Sprint 5: SEO + Analytics (Week 6-7)
**Goal:** Complete SEO foundation, structured data, analytics, and conversion tracking.

| Task | Depends On | Deliverable |
|---|---|---|
| Configure Yoast SEO / Rank Math | All pages exist | Plugin configured |
| Write unique meta titles + descriptions for all 32+ pages | All page content | Zero empty/duplicate meta |
| Implement LocalBusiness schema (JSON-LD) | MI-01, MI-02, MI-03, MI-04 | Valid schema on Home, Contact, Over HDS |
| Implement Service schema on each service page | Service pages | Valid schema on P02-P08 |
| Implement FAQPage schema | P18 | Valid FAQ schema |
| Implement BreadcrumbList on all inner pages | All inner pages | Schema + visible breadcrumbs |
| Configure 301 redirects (all rules from Section D5) | All new URLs known | Redirect map active |
| Generate and validate XML Sitemap | All pages published | Zero errors in sitemap |
| Configure robots.txt | — | Correct rules |
| Set up GA4 via GTM | GA4 account | Tracking active |
| Set up GTM with Consent Mode v2 | Complianz | All scripts via GTM |
| Configure all conversion events (Section G5.3) | Forms + WC active | Events firing in GA4 |
| Verify Google Search Console | — | GSC verified |
| Internal linking audit: zero broken links, zero orphan pages | All pages | Screaming Frog clean |
| Image optimization: WebP conversion, compression, alt text | All media in place | Optimized images |

---

#### Sprint 6: Compliance + Security Hardening (Week 7)
**Goal:** GDPR compliance, cookie consent, security hardening, accessibility audit.

| Task | Depends On | Deliverable |
|---|---|---|
| Configure Complianz cookie consent | P19, P20 | Banner appears. No cookies before consent. Consent logged. |
| GDPR form consent checkboxes verified | All forms | All unchecked by default. Link to privacy policy. |
| Configure Wordfence: firewall, 2FA, brute force, custom login URL | — | Security active. 2FA on all admin accounts. |
| Disable XML-RPC (verify 403) | — | Confirmed blocked |
| Custom login URL configured | Wordfence | Obscured login path |
| Set up UptimeRobot monitoring | Production URL | Downtime alerts active |
| Full accessibility audit: axe DevTools, WAVE, Lighthouse, keyboard, screen reader, color contrast, zoom | All pages | Zero critical/serious issues. Lighthouse = 100. |
| Fix all accessibility issues found | Audit results | All issues resolved |
| Legal pages: final review by client's legal counsel | Legal pages drafted | Lawyer sign-off obtained |

---

#### Sprint 7: Testing + QA (Week 8)
**Goal:** Complete testing across all categories. Client review and approval on staging.

| Task | Depends On | Deliverable |
|---|---|---|
| Full functional QA (see Section I2.1) | All phases | Zero critical bugs |
| Full SEO audit (see Section I2.2) | Sprint 5 | SEO sign-off |
| Performance testing (PSI, WebPageTest, GTmetrix, DebugBear) | All phases | PSI 90+ mobile, 95+ desktop |
| Cross-browser testing (Chrome, Firefox, Safari, Edge) | All phases | Consistent rendering |
| Mobile/tablet testing on real devices | All phases | Responsive, usable |
| Client review and approval on staging | All phases | Client sign-off |
| Fix all issues found in QA | QA results | All issues resolved |
| Final backup verification (test restore to staging) | Backups | Restore works |
| Prepare production for deployment | Staging approved | Production-ready |

---

#### Sprint 8: Launch (Week 8-9)
**Goal:** Deploy to production, verify, handover.

| Task | Depends On | Deliverable |
|---|---|---|
| Complete pre-launch checklist (Section J2) | All phases | All items checked |
| Take final backup of old site | Old site access | Old site archived |
| Deploy to production | Staging approved | Site live |
| Clear all caches (WP Rocket, Cloudflare, Redis) | Production deployed | Fresh cache |
| Verify 301 redirects on production | Redirect map | All redirects working |
| Submit sitemap to GSC + Bing | Production URL | Sitemaps submitted |
| Post-launch verification: all pages, forms, WC, search, SSL, GA4 | Production live | All systems verified |
| Launch readiness report to client (Section J4) | All verified | Final report delivered |
| Handover + training: 1-hour session + written "Website Beheergids" (Dutch) | Launch complete | Client self-sufficient |

---

### J2. Pre-Launch Checklist (Condensed — see RS-07 Section 41 for full version)

#### Content
- [ ] All 32 pages published with final Dutch content
- [ ] All service pages >= 300 words
- [ ] No lorem ipsum or placeholder text
- [ ] Phone and email correct on all pages

#### Design / UX
- [ ] Responsive on mobile, tablet, desktop
- [ ] Color contrast meets WCAG AA
- [ ] Navigation works on all devices
- [ ] No broken images

#### Functionality
- [ ] All 3 forms submit and deliver emails
- [ ] WooCommerce purchase flow tested end-to-end
- [ ] Search returns relevant results
- [ ] 404 page works
- [ ] Cookie consent banner works

#### SEO
- [ ] Every page has unique title + meta description
- [ ] All Open Graph + Twitter Card tags present
- [ ] All schema validated (Rich Results Test)
- [ ] XML Sitemap working (no 500)
- [ ] All 301 redirects working
- [ ] Internal links: zero broken, zero orphans

#### Technical
- [ ] HTTPS enforced + HSTS
- [ ] XML-RPC disabled
- [ ] 2FA on all admin accounts
- [ ] Daily backups configured + verified
- [ ] Caching active + CDN active
- [ ] GA4 tracking active

#### Legal
- [ ] Privacyverklaring published + legally reviewed
- [ ] Cookiebeleid published
- [ ] Cookie consent logging active
- [ ] All form consent checkboxes unchecked by default
- [ ] KVK + BTW in footer (if provided)

#### Performance
- [ ] PSI mobile >= 90
- [ ] PSI desktop >= 95
- [ ] LCP < 2.5s, CLS < 0.1

### J3. Post-Launch Verification Checklist (Condensed — see RS-07 Section 42)

**Immediate (1 hour):**
- [ ] Homepage loads on desktop + mobile
- [ ] Contact form submission test
- [ ] Phone/email links work
- [ ] All nav links work
- [ ] SSL valid
- [ ] GA4 real-time shows users

**Day 1:**
- [ ] GSC: submit sitemap, check errors
- [ ] Check all email notifications working
- [ ] Server error logs clean
- [ ] Backup completed successfully
- [ ] Screaming Frog: zero unexpected 4xx/5xx
- [ ] Test on real mobile + tablet
- [ ] Verify GBP + social links

**Week 1:**
- [ ] Monitor GSC daily for crawl errors
- [ ] Monitor Core Web Vitals in GSC
- [ ] Check GA4 conversion events firing
- [ ] Review security logs
- [ ] Check form submissions flowing
- [ ] Check spam rate on forms

**Week 2:**
- [ ] Submit all new URLs for indexing
- [ ] Compare indexed pages to baseline
- [ ] Compare search impressions to baseline
- [ ] Check keyword rankings vs baseline
- [ ] Performance re-test

**Week 4 (30-Day Review):**
- [ ] Full SEO audit vs baseline
- [ ] Report to client: traffic, conversions, rankings, technical health
- [ ] Check all updates
- [ ] Review security logs
- [ ] Client satisfaction check

### J4. Launch Readiness Report

#### J4.1 Launch Readiness Criteria

| # | Criterion | Status |
|---|---|---|
| LR01 | All 32 pages published with final Dutch content | / |
| LR02 | All forms submit and deliver emails | / |
| LR03 | WooCommerce purchase flow tested end-to-end | / |
| LR04 | All 301 redirects configured and tested | / |
| LR05 | PSI mobile >= 90 | / |
| LR06 | Lighthouse Accessibility = 100 on all templates | / |
| LR07 | axe DevTools: zero critical/serious issues | / |
| LR08 | All SEO metadata present and unique | / |
| LR09 | All schema validated (Rich Results Test) | / |
| LR10 | XML Sitemap working (no 500) | / |
| LR11 | HTTPS enforced with HSTS | / |
| LR12 | Privacyverklaring published + legally reviewed | / |
| LR13 | Cookie consent banner + logging working | / |
| LR14 | KVK + BTW in footer (if provided) | / |
| LR15 | GA4 tracking active | / |
| LR16 | Google Search Console verified | / |
| LR17 | Daily backups configured + test restore verified | / |
| LR18 | XML-RPC disabled, 2FA active, custom login URL | / |
| LR19 | Zero broken internal links (Screaming Frog) | / |
| LR20 | Client approval on staging | / |
| LR21 | All Phase 0 dependencies resolved | / |
| LR22 | Staging: noindex + password-protected | / |
| LR23 | Cross-browser testing passed | / |
| LR24 | Mobile/tablet testing passed on real devices | / |
| LR25 | All content reviewed by native Dutch speaker | / |

**Launch decision:** ALL 25 criteria must be marked complete. Any incomplete = launch delayed.

#### J4.2 Launch Sign-Off

| Role | Name | Signature | Date |
|---|---|---|---|
| Lead Solution Architect | | | |
| Technical Lead (Development) | | | |
| Client / Business Owner | | | |
| SEO Architect | | | |
| Legal Reviewer (AVG) | | | |

#### J4.3 Rollback Plan

If critical issues discovered within 24 hours of launch:
1. Revert DNS to old site (if old site still on old hosting) OR restore old site backup
2. Communicate to client: issue identified, rollback in progress, ETA
3. Document issue and resolution
4. Fix on staging, re-test, re-launch

**Rollback Time Objective:** < 2 hours from decision to old site operational.

---

## Appendix A: Document Map

This Master Project Specification is the single source of truth. Detailed specifications are available in:

| Document | Content |
|---|---|
| `docs/rebuild-spec/01_Architecture_Sitemap.md` | Extended architecture, IA, sitemap details |
| `docs/rebuild-spec/02_Navigation_URLs_Migration.md` | Extended navigation, URLs, redirects, migration strategies |
| `docs/rebuild-spec/03_SEO_Metadata_Strategy.md` | Extended metadata, structured data, internal linking, technical SEO, local SEO |
| `docs/rebuild-spec/04_Performance_Accessibility_Security_GDPR.md` | Extended performance, accessibility, security, GDPR, cookies, analytics, forms |
| `docs/rebuild-spec/05_Components_CMS_Templates.md` | Extended components, design system, CMS, templates, blocks, WooCommerce, search, errors, logging |
| `docs/rebuild-spec/06_Backup_Deployment_GapAnalysis.md` | Extended backup, deployment, acceptance, dependency map, full gap analysis |
| `docs/rebuild-spec/07_Checklists.md` | Full expanded checklists (200+ items across all categories) |
| `docs/rebuild-spec/08_Launch_Risks_Questions_Future.md` | Extended launch readiness, risks, assumptions, all 42 open questions, future scalability |

## Appendix B: Glossary

| Term | Definition |
|---|---|
| AVG | Algemene Verordening Gegevensbescherming — Dutch GDPR |
| BTW | Belasting over de Toegevoegde Waarde — Dutch VAT (21%) |
| CLS | Cumulative Layout Shift — Core Web Vital |
| CPT | Custom Post Type — WordPress content type |
| FSE | Full Site Editing — WordPress block-based theme editing |
| GA4 | Google Analytics 4 |
| GBP | Google Business Profile |
| GSC | Google Search Console |
| GTM | Google Tag Manager |
| KVK | Kamer van Koophandel — Dutch Chamber of Commerce number |
| LCP | Largest Contentful Paint — Core Web Vital |
| MI-XX | Missing Information — see Section A4 |
| MVO | Maatschappelijk Verantwoord Ondernemen — Corporate Social Responsibility |
| NAP | Name, Address, Phone — business identity consistency |
| OSB | Ondernemersorganisatie Schoonmaak- en Bedrijfsdiensten |
| PSI | Google PageSpeed Insights |
| RI&E | Risico-Inventarisatie & -Evaluatie — Dutch risk assessment |
| VvE | Vereniging van Eigenaren — Dutch Homeowners' Association |
| WCAG | Web Content Accessibility Guidelines |

---

**END OF MASTER PROJECT SPECIFICATION — Version 1.0.0**

**This document is complete and ready for Sprint Planning. All sections are implementation-ready. Items marked "MI-XX" or "MISSING INFORMATION" require client resolution before the corresponding development phase.**

**Cross-reference:** For expanded detail on any section, refer to the 8-part rebuild specification in `docs/rebuild-spec/`.
