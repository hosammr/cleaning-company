# HDS Onderhoudsdiensten — Gap Analysis

**Document ID:** GAP-001 | **Version:** 1.0.0 | **Date:** July 2026
**Reviewer:** Senior Solution Architect
**Input:** FAR-001 (Final Architecture Review) + all Sprint 1-3 documents

---

## 1. Executive Summary

This Gap Analysis identifies every gap between the current project state (post-Sprint 3) and the state required to begin implementation. Gaps are categorized by domain and severity. Each gap includes a concrete resolution action, owner, and blocking status.

**Total Gaps Identified: 37**
- External Dependencies: 8
- Architecture & Technology: 7
- Content & Information: 6
- Documentation & Process: 5
- Migration & Operations: 6
- Testing & QA: 3
- Security & Compliance: 2

---

## 2. Gap Inventory

### 2.1 External Dependency Gaps (Client-Dependent)

These gaps cannot be resolved by the development team. They require client action.

| ID | Gap | Severity | Description | Impact | Recommendation | Priority | Owner | Blocking? |
|---|---|---|---|---|---|---|---|---|
| G-E01 | Client workshop not conducted | **CRITICAL** | 25 Missing Information items (MI-01 through MI-25) require client input. Zero have been provided. No client engagement has occurred across 3 sprints. | Entire specification is based on assumptions. Wrong assumptions → rework. Client may not want a rebuild at all (ASM01). | Schedule mandatory 4-hour client workshop before any further development. Agenda: (1) Budget/project confirmation, (2) Business identity (address, KVK, BTW, hours, service area), (3) Brand assets (logo, colors, fonts), (4) Airfixr product line decision, (5) Hosting decision, (6) Legal counsel engagement | P0 | Client + Solution Architect | **BLOCKING** |
| G-E02 | Business address unknown | **HIGH** | MI-01 — Physical address is required for: footer, Contact page, LocalBusiness schema, Google Business Profile, KVK/BTW display context | Cannot complete Contact page, footer, or LocalBusiness schema. NAP consistency broken. | Client provides full business address (street, number, postal code, city) | P0 | Client | **BLOCKING** |
| G-E03 | KVK and BTW numbers unknown | **HIGH** | MI-02, MI-03 — Required for footer, legal compliance (Dutch B2B websites must display KVK), LocalBusiness schema | Legal non-compliance at launch. Trust signal missing. | Client provides KVK number (8 digits) and BTW number (NLXXXXXXXXXB01) | P0 | Client | **BLOCKING** |
| G-E04 | Logo vector file not provided | **HIGH** | MI-06 — Current logo is a 200×81px PNG. A vector file (SVG/AI/EPS) is needed for responsive display and the theme design system | Poor logo quality on high-DPI screens. Cannot create proper favicon/touch icons. | Client provides original vector logo file OR commissions logo recreation as SVG | P0 | Client | **BLOCKING** |
| G-E05 | Airfixr product line decision not made | **HIGH** | MI-15, Q09 — If Airfixr is removed, WooCommerce scope (Sprint 4) is eliminated. If kept, payment gateway and shipping must be configured. | Sprint 4 scope uncertain. Developer effort may be wasted. | Client confirms: (a) Keep or remove Airfixr webshop, (b) If keep: payment gateway (Mollie recommended), (c) Shipping costs and policy | P0 | Client | **BLOCKING** |
| G-E06 | Brand design tokens not provided | **MEDIUM** | MI-07, MI-08 — Color palette and typography preferences are unknown. Design system cannot be finalized | Theme development proceeds with assumed colors/fonts. Client may reject and require rework. | Client provides: (a) Brand color palette (primary, secondary, accent) OR approval of proposed palette, (b) Typography preference (Open Sans kept or change) | P1 | Client | Non-blocking |
| G-E07 | Legal counsel not engaged | **MEDIUM** | MI-17 — Privacyverklaring must be reviewed by a qualified Dutch privacy lawyer before launch. No lawyer has been engaged. | Cannot launch legally. GDPR/AVG non-compliance. | Client engages Dutch privacy lawyer. Developer drafts privacyverklaring content. Lawyer reviews before Sprint 6. | P1 | Client | Non-blocking |
| G-E08 | Hosting provider not selected | **MEDIUM** | MI-20 — Managed WordPress hosting must be provisioned before Sprint 1 (Infrastructure). | Development environment cannot be set up. Staging environment not available for client review. | Client selects hosting provider (Kinsta, WP Engine, Cloud86 recommended). Developer assists with provisioning. | P1 | Client | Non-blocking |

### 2.2 Architecture & Technology Gaps

These gaps are within the development team's control but have not been resolved.

| ID | Gap | Severity | Description | Impact | Recommendation | Priority | Owner | Blocking? |
|---|---|---|---|---|---|---|---|---|
| G-T01 | SMTP email service not procured | **HIGH** | Post SMTP plugin is selected but the actual email delivery service (SendGrid/Mailgun/SES) is not set up. Domain verification, SPF/DKIM/DMARC DNS records not configured. Flagged as B04 (BLOCKING) in ARR. | All forms submit to void. Contact requests, quote requests, WooCommerce orders generate no email notifications. Primary conversion path broken. | Procure transactional email service. Configure SPF/DKIM/DMARC. Test email delivery end-to-end. Add Post SMTP email logging and weekly deliverability test. | P0 | Developer | **BLOCKING** |
| G-T02 | Payment gateway not procured | **HIGH** | Mollie (or alternative) account not created. API keys not generated. Webhook not configured. Test mode not available. Flagged as B08 (BLOCKING) in ARR. | WooCommerce checkout cannot function. | If Airfixr kept: create Mollie account, generate API keys, configure webhook, test purchase in test mode. | P0 | Developer + Client | **BLOCKING** |
| G-T03 | Cloudflare CDN not provisioned | **MEDIUM** | Cloudflare account not created. DNS not pointed to Cloudflare. WAF rules not configured. Page rules for WooCommerce cache bypass not created. Flagged as B07 (BLOCKING) in ARR. | No CDN caching. No DDoS protection. No WAF. WooCommerce cart/checkout may be cached. | Provision Cloudflare (Free tier). Configure DNS, SSL (Full Strict), WAF rules, page rules for WC bypass. | P1 | Developer | Non-blocking |
| G-T04 | Block pattern scope unresolved | **HIGH** | MPS specifies 16 block patterns. SA-001 says 7 implemented, 9 remaining "delivered via custom blocks or block compositions." The 9 remaining patterns have no design specification, no implementation tasks, and unclear scope. | Sprint 3 estimates are based on 16 patterns. Only 7 exist. 9 are undefined. | Define which of the 9 remaining patterns are truly needed for Sprint 3. Create design specs and implementation tasks. Defer non-essential patterns to post-launch. | P0 | UX Designer + Lead Developer | **BLOCKING** |
| G-T05 | Full Site Editing (FSE) confusion persists in docs | **MEDIUM** | MPS-001 references "FSE-compatible" theme. ADR D-005 explicitly chose Hybrid (NOT FSE). WTA §3.1 says "NOT Full Site Editing." SA-001 §10.1 says "NOT Full Site Editing." The older MPS still has the FSE reference. | Developer reading MPS may implement FSE approach, conflicting with PHP template structure. | Update MPS-001: remove "FSE-compatible" label. Replace with "Hybrid block theme (theme.json + PHP templates + Block Editor)." Add cross-reference to ADR D-005. | P1 | Solution Architect | Non-blocking |
| G-T06 | `hds_faq` CPT contradiction across documents | **HIGH** | ADR-001 §3.4 lists `hds_faq` CPT. SA-001 §2 says "hds_faq CPT removed." WTA-001 §5.4 says "FAQ — NOT a CPT." RTM §5.3 still references `hds_faq`. FS §4.4 says "FAQ uses Yoast/Rank Math FAQ Block on standard Page." Five documents, two different answers. | If a developer registers hds_faq CPT based on ADR, it conflicts with RTM and FS. Unnecessary code in the theme. | **Remove `hds_faq` CPT** from all documents and all code. FAQ is implemented via Yoast/Rank Math FAQ blocks on a standard Page. Add decision record D-012 to ADR. | P0 | Solution Architect + Lead Developer | **BLOCKING** |
| G-T07 | Custom block render_callback behaviors not fully specified | **MEDIUM** | The 4 custom blocks (`hds/service-card`, `hds/testimonial`, `hds/job-listing`, `hds/contact-info`) have render_callbacks defined but edge cases are not specified: empty data, pagination, sorting, filtering options for the 2 query-based blocks. | Custom blocks render unpredictably with edge-case data. | Define for each block: query parameters (post count, order, orderby), empty state rendering, pagination behavior, and block editor attribute controls. Add to FS §8. | P1 | Lead Developer | Non-blocking |

### 2.3 Content & Information Gaps

| ID | Gap | Severity | Description | Impact | Recommendation | Priority | Owner | Blocking? |
|---|---|---|---|---|---|---|---|---|
| G-C01 | No content writer identified | **HIGH** | 32 pages require Dutch-language content. Service pages need 300+ words each. Over HDS needs 500+ words. Legal pages need 500+ words. No content writer has been engaged. | Sprint 2 and Sprint 3 (Core Pages + Supporting Pages) cannot produce content. Placeholder content at launch. | Identify and engage a Dutch-language content writer. Budget 40-60 hours. Deliverables: all 32 page contents before Sprint 5 (SEO). | P0 | Project Manager | **BLOCKING** |
| G-C02 | Keyword research not conducted | **MEDIUM** | SEO titles and meta descriptions are specified but without search volume data. Pages may target keywords with zero search volume. Flagged as SEO-01 in ARR. | Suboptimal SEO targeting. Missed high-volume keyword opportunities. | Conduct keyword research before Sprint 5 (SEO implementation). Use Keyword Planner, Ahrefs, or Semrush. Map top 20 keywords to target pages. | P1 | SEO Specialist | Non-blocking |
| G-C03 | Client testimonials and references not provided | **MEDIUM** | MI-10, MI-11 — Referenties page requires client logos and testimonial text. HMS Testimonials plugin on old site is empty. No testimonials exist. | Referenties page renders empty. Testimonial block hidden (empty state). Credibility signal absent. | Client provides: (a) 5+ client names/logos with written permission, (b) 3+ testimonial texts with author names and companies. If none available, launch with empty sections and add post-launch. | P1 | Client | Non-blocking |
| G-C04 | Vacancy text not provided as HTML | **MEDIUM** | MI-12 — Current vacancies are JPG images of scanned Word documents. Client must provide vacancy text as editable text. | Vacancies page shows placeholder or empty. Unprofessional. Not accessible. | Client provides current vacancy text as Word/Google Docs/plain text. Developer converts to HTML + hds_vacancy CPT entries. | P1 | Client | Non-blocking |
| G-C05 | Terms & Conditions text not provided | **MEDIUM** | MI-16 — Algemene Voorwaarden page requires T&C text. Current site has only PDFs on legacy domain. | Legal page renders empty or with placeholder. Legal risk if terms are not published. | Client provides Algemene Voorwaarden text. If existing PDFs are complete, extract text. Review by legal counsel. | P1 | Client | Non-blocking |
| G-C06 | No Open Graph social share image | **LOW** | MI-23 — A 1200×630px branded social share image is specified but not created. Without it, social shares show a blank or the logo (poorly cropped). | Poor social media previews. Reduced click-through from social platforms. | Designer creates 1200×630px branded graphic. Upload to Media Library. Set as default in Rank Math Pro. | P2 | Designer | Non-blocking |

### 2.4 Documentation & Process Gaps

| ID | Gap | Severity | Description | Impact | Recommendation | Priority | Owner | Blocking? |
|---|---|---|---|---|---|---|---|---|
| G-D01 | Document authority chain not defined | **HIGH** | 22 documents overlap significantly. When the same information appears in 5 places and one place is updated, the other 4 become stale. No document declares which document takes precedence in case of conflict. | Document drift. Developer uncertainty. MPS-001 says it's the "single source of truth" but many Sprint 3 docs contain more current information. | Define and publish document authority chain: FS-001 (what → functional behavior) → SA-001 (how → high-level architecture) → WTA-001 (how → WordPress implementation) → ADR-001 (why → decisions). All other documents reference these. Add conflict-resolution header to each document. | P1 | Solution Architect | Non-blocking |
| G-D02 | OR choices persist in MPS-001 | **MEDIUM** | MPS-001 was written in Sprint 2 and lists "OR" choices (Custom theme OR GeneratePress OR Kadence; Rank Math OR Yoast; FlyingPress OR WP Rocket) that were resolved by ADR in Sprint 3. | Developer reading MPS may select the wrong tool. | Update MPS-001: strike through resolved OR choices. Add "RESOLVED: See ADR D-00X" for each. | P1 | Solution Architect | Non-blocking |
| G-D03 | Two solution architecture documents exist | **MEDIUM** | `docs/architecture/SOLUTION_ARCHITECTURE.md` (SAD-001) and `docs/architecture/solution-architecture.md` (SA-001) serve similar purposes. SAD-001 is the "definitive technical reference" (SAD §1). SA-001 is the "implementation blueprint" (SA §1). The distinction is unclear to most readers. | Developer confusion about which document to consult. Effort to keep both updated. | Consolidate: merge SAD-001 and SA-001 into a single Solution Architecture Document. OR clearly differentiate: SAD-001 = Solution Architecture (high-level, decisions), SA-001 = Implementation Blueprint (low-level, code structure). If kept separate, cross-reference explicitly in each document's preamble. | P1 | Solution Architect | Non-blocking |
| G-D04 | No API Integration Specification document | **MEDIUM** | External API endpoints (Mollie, Cloudflare, Post SMTP, Gravity Forms REST, WooCommerce REST) are mentioned in architectural diagrams but no document lists endpoints, methods, auth, request/response schemas, error codes. | Developers reference external documentation ad-hoc. Integration testing is assumption-based. | Create API Integration Specification. For each external API: base URL, authentication method, key endpoints, request schema, response schema, error codes, rate limits. Minimum: Mollie payment + webhook, Cloudflare cache purge. | P2 | Lead Developer | Non-blocking |
| G-D05 | No dedicated Error Handling & Logging Strategy document | **LOW** | Error handling is described in FS §9 (5 pages) and logging is described in SA §17 (monitoring) and NFR §10. But there is no standalone strategy that covers: error taxonomy, retry logic, circuit breakers, alert thresholds, log levels, log format, log retention. | Inconsistent error handling across components. | Create Error Handling & Logging Strategy. Define: error severity levels, user-facing vs developer-facing errors, retry logic for external APIs, log levels (DEBUG/INFO/WARNING/ERROR/CRITICAL), log format, retention periods per log type. | P2 | Lead Developer | Non-blocking |

### 2.5 Migration & Operations Gaps

| ID | Gap | Severity | Description | Impact | Recommendation | Priority | Owner | Blocking? |
|---|---|---|---|---|---|---|---|---|
| G-M01 | Old site backup test restore not performed | **HIGH** | Migration spec says "take final backup of old site" but does not require testing the backup. An untested backup is not a recovery mechanism — it's hope. Flagged as B05 (CRITICAL, BLOCKING) in ARR. | If backup is corrupted, old site is unrecoverable after takedown. Total site loss scenario. | Before old site takedown: restore backup to test environment. Verify all pages, forms, WooCommerce (if kept), admin login. Only after verification, proceed with takedown. Keep verified backup in 2 locations. | P0 | Developer | **BLOCKING** |
| G-M02 | DNS TTL lowering not in launch checklist | **HIGH** | DNS TTL must be lowered to 300s 24 hours before launch to enable fast propagation. The procedure exists in SAD §34.5 but is not in the pre-launch checklist (MPS §J2, RS-07 §41). Flagged as B06 (BLOCKING) in ARR. | DNS propagation takes 24-48 hours. Users see old site (with broken pages) while others see new site. Form submissions to old broken contact page. | Add to pre-launch checklist as item 0: "24h before launch: lower DNS TTL to 300s. Verify via whatsmydns.net. 24h after launch: restore TTL to normal." | P0 | Developer | Non-blocking |
| G-M03 | Email MX record preservation not in migration plan | **MEDIUM** | DNS changes during launch may affect MX records for `info@helderduidelijkschoon.nl`. If MX records are broken, email stops working — including contact form notifications. | Lost leads during the critical launch period. Client unaware. | Add to pre-migration checklist: "Document current MX records. Verify MX records unchanged after DNS cutover. Send test email to info@ within 1 hour of launch." | P1 | Developer | Non-blocking |
| G-M04 | WooCommerce order migration not specified | **MEDIUM** | If Airfixr shop is kept, existing WooCommerce orders (financial/legal data with 7-year retention) must be migrated from old site. No migration procedure exists. | Permanent loss of financial records. Legal non-compliance for data retention. | If Airfixr kept: export WooCommerce orders, products, customers from old site. Import to new site. Verify data integrity. | P1 | Developer | Non-blocking |
| G-M05 | Content freeze notification not in timeline | **MEDIUM** | Client must stop editing old site during content migration (Sprint 2-3). No notification procedure exists in the execution plan. | Client edits old site. Migration data becomes stale. Content is lost. | Add to Sprint 2 kickoff: "Notify client: do not edit old website from [DATE]. Document all updates and send to development team." | P1 | Project Manager | Non-blocking |
| G-M06 | Legacy domain PDF migration not initiated | **LOW** | PDFs on `hds-onderhoudsdiensten.nl` have not been downloaded. Domain status is unknown. PDFs may become inaccessible if domain expires. | Terms & Conditions PDFs unavailable. Legal risk. | Download all PDFs immediately. Upload to primary domain media library. Document old URLs for redirect mapping. | P2 | Developer | Non-blocking |

### 2.6 Testing & QA Gaps

| ID | Gap | Severity | Description | Impact | Recommendation | Priority | Owner | Blocking? |
|---|---|---|---|---|---|---|---|---|
| G-Q01 | No executable test cases | **HIGH** | RTM references 210 test case IDs (T-CONTACT-01, etc.) but the actual test steps, preconditions, and expected results are not documented anywhere. Test case IDs without test content are not executable. | QA engineers cannot test. Tests exist in the RTM as placeholders only. | Create a Test Case Spreadsheet. Each row: Test ID, Category, Description, Preconditions, Test Steps, Expected Result. Link from RTM. Minimum: complete test cases for all P0 pages and forms before Sprint 2. | P1 | QA Engineer | Non-blocking |
| G-Q02 | No automated smoke tests | **MEDIUM** | All 210 test cases are manual. No Playwright/Cypress tests exist for critical paths. After launch, plugin updates require manual re-testing. Flagged as QA-01 in ARR. | QA cycles slow. Regressions missed. | Implement 5-10 Playwright smoke tests: homepage loads, contact form submits, mobile menu works, WC product page loads, search returns results. Run before every production deployment. | P2 | QA Engineer | Non-blocking |
| G-Q03 | No screen reader test script | **MEDIUM** | Accessibility testing protocol says "Manual screen reader (NVDA or VoiceOver)" but no test script exists. "Test with NVDA" is not a test plan — it's a tool reference. | Screen reader testing superficial. Accessibility failures missed. | Create screen reader test script: navigate Home → Service → Contact Form. Verify: heading hierarchy, form labels, error announcements, navigation menu operation, cart add-to-cart announcement. | P2 | QA Engineer | Non-blocking |

### 2.7 Security & Compliance Gaps

| ID | Gap | Severity | Description | Impact | Recommendation | Priority | Owner | Blocking? |
|---|---|---|---|---|---|---|---|---|
| G-S01 | No security incident response procedure | **MEDIUM** | GDPR requires breach notification within 72 hours. The spec describes the requirement (REQ-CMP-008) but no actual procedure document exists. Flagged as SEC-02 in ARR. | Delayed breach response. GDPR non-compliance. No one knows what to do. | Create Security Incident Response Runbook: detection triggers, containment steps, investigation process, remediation steps, notification template (Dutch), post-mortem template. Sprint 6 deliverable. | P1 | Developer | Non-blocking |
| G-S02 | Complianz cookie consent not procured or configured | **MEDIUM** | Complianz Premium license not purchased. Consent banner not configured. Cookiebeleid page not generated. GTM Consent Mode v2 not integrated. Without this, GA4 cannot be configured with consent. | GDPR/AVG non-compliance at launch. Cannot use GA4 legally. | Procure Complianz Premium license. Configure: consent categories (functional/statistics/marketing), banner text (Dutch), GTM consent signals. Test: zero GA4 cookies before consent. | P1 | Developer | Non-blocking |

---

## 3. Gap Summary by Severity

| Severity | Count | Blocking |
|---|---|---|
| **CRITICAL** | 1 (G-E01: Client workshop) | Yes |
| **HIGH** | 14 | 7 blocking |
| **MEDIUM** | 19 | 0 blocking |
| **LOW** | 3 | 0 blocking |
| **Total** | **37** | **8 blocking** |

---

## 4. Blocking Gap Resolution Order

The following 8 gaps must be resolved before Sprint 2 development can begin safely:

| Order | Gap ID | Gap | Estimated Time |
|---|---|---|---|
| 1 | G-E01 | Client workshop — resolve all 25 MI items | 4-hour workshop + documentation |
| 2 | G-E02 | Business address provided | Included in workshop |
| 3 | G-E03 | KVK + BTW provided | Included in workshop |
| 4 | G-E04 | Logo vector file provided | Included in workshop |
| 5 | G-E05 | Airfixr product line decision | Included in workshop |
| 6 | G-T01 | SMTP email service procured and configured | 2-4 hours |
| 7 | G-T02 | Payment gateway procured (if Airfixr kept) | 2-4 hours |
| 8 | G-T04 | Block pattern scope resolved | 2 hours |
| 9 | G-T06 | hds_faq CPT removed from all docs and code | 1 hour |
| 10 | G-C01 | Content writer identified and engaged | 1 hour |
| 11 | G-M01 | Old site backup test restore performed | 2-4 hours (at migration time) |

**Estimated Total Resolution Time:** 2-3 business days (concentrated effort), plus client workshop availability

---

## 5. Non-Blocking Gap Resolution by Sprint

| Sprint | Gaps to Resolve |
|---|---|
| **Sprint 0 (Prerequisites)** | G-E06 (brand tokens), G-E08 (hosting), G-C03 (testimonials), G-C04 (vacancy text), G-C05 (T&C text), G-D02 (MPS OR choices cleanup), G-D03 (document consolidation), G-Q01 (test cases) |
| **Sprint 1 (Foundation)** | G-T03 (Cloudflare provisioned), G-T05 (MPS FSE reference removed), G-T07 (block render_callbacks specified) |
| **Sprint 2 (Core Pages)** | G-C02 (keyword research), G-M05 (content freeze notification), G-D01 (document authority chain) |
| **Sprint 3 (Supporting Pages)** | G-S01 (security incident procedure), G-S02 (Complianz procured), G-M03 (MX record preservation) |
| **Sprint 4 (WooCommerce)** | G-M04 (WC order migration) |
| **Sprint 5 (SEO + Analytics)** | G-C06 (social share image), G-D04 (API spec) |
| **Sprint 6 (Compliance)** | None remaining — all compliance gaps resolved |
| **Sprint 7 (QA)** | G-Q02 (Playwright smoke tests), G-Q03 (screen reader script) |
| **Sprint 8 (Launch)** | G-M02 (DNS TTL in checklist), G-M06 (legacy domain PDFs) |
| **Post-Launch** | G-D05 (error handling strategy) |

---

## 6. Gap Resolution Ownership

| Owner | Gaps |
|---|---|
| **Client** | G-E01 through G-E08, G-C03 through G-C05 (11 gaps — requires client action) |
| **Solution Architect** | G-D01, G-D02, G-D03, G-T05, G-T06 (5 gaps — documentation cleanup) |
| **Lead Developer** | G-T01, G-T02, G-T07, G-D04, G-D05, G-M01 through G-M04, G-M06, G-S01, G-S02 (13 gaps — technical implementation) |
| **UX Designer** | G-T04 (1 gap — component scope) |
| **QA Engineer** | G-Q01, G-Q02, G-Q03 (3 gaps — testing) |
| **SEO Specialist** | G-C02 (1 gap — keyword research) |
| **Project Manager** | G-C01, G-M05 (2 gaps — resourcing and coordination) |
| **Designer** | G-C06 (1 gap — social image) |

---

*End of Gap Analysis — GAP-001 v1.0.0*
