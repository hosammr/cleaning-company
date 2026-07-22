# HDS Onderhoudsdiensten — Architecture Readiness Review

**Document ID:** ARR-001 | **Version:** 1.0.0 | **Review Date:** July 2026
**Reviewer Role:** Lead Software Architect / Technical Reviewer
**Documents Reviewed:** Master Project Specification (MPS-001, 1,826 lines) + 8-part Rebuild Specification (RS-01 through RS-08, ~2,300 total lines) + 8 Source Documents (SRC-01 through SRC-08)

**Review Authority:** This review is binding. All BLOCKING issues (B01-B12) must be resolved before Sprint 2 development begins.

---

## Part 1: Issue Register — All Perspectives

Each issue is classified:

| Attribute | Meaning |
|---|---|
| **Risk Level** | CRITICAL / HIGH / MEDIUM / LOW |
| **Priority** | P0 (must fix now) / P1 (fix before Sprint 2) / P2 (fix during Sprint 2) / P3 (post-launch) |
| **Blocking** | BLOCKING (prevents Sprint 2 start) / NON-BLOCKING (can proceed with caveat) |

---

### 1.1 Software Architecture Review

#### Issue SWA-01: No SMTP / Transactional Email Service Specified

- **Description:** The spec references "SMTP email delivery via transactional email service (Post SMTP or hosting-provided SMTP)" but does not mandate a specific service, configure fallback behavior, or specify email deliverability monitoring. Gravity Forms + WooCommerce generate critical transactional emails (form notifications, order confirmations). If email delivery fails silently, the business loses leads and orders.
- **Impact:** Contact form submissions undelivered. WooCommerce order notifications undelivered. Client unaware of failures.
- **Risk Level:** HIGH
- **Recommendation:** Mandate a specific transactional email service (Post SMTP with SendGrid/Mailgun/Amazon SES or direct hosting SMTP with SPF/DKIM/DMARC configured). Add email delivery monitoring (Post SMTP email log). Add acceptance criterion: test email delivered within 2 minutes. Add post-launch: check email deliverability weekly.
- **Priority:** P0
- **Blocking:** **BLOCKING**

#### Issue SWA-02: Theme Selection is Ambiguous (Custom vs GeneratePress vs Kadence)

- **Description:** The spec lists "Custom block-based theme (FSE-compatible) OR GeneratePress Pro / Kadence Pro" as the theme. This is an architectural fork — a custom theme requires full theme development (header, footer, templates, styles, block patterns) from scratch; GeneratePress/Kadence provides these out of the box. The choice fundamentally changes Sprint 1 scope, timeline, and skills required.
- **Impact:** If custom theme is chosen: 2-3x more development effort for Sprint 1. If GeneratePress is chosen: design system must adapt to its constraints. Cannot start development without this decision.
- **Risk Level:** CRITICAL
- **Recommendation:** Resolve to a single theme selection before Sprint 1. Recommendation: GeneratePress Pro + GenerateBlocks for faster time-to-market with sufficient customization. Reserve custom FSE theme for a post-launch phase if needed. Document the decision in MPS-001.
- **Priority:** P0
- **Blocking:** **BLOCKING**

#### Issue SWA-03: SEO Plugin Selection is Ambiguous (Yoast vs Rank Math)

- **Description:** "Yoast SEO Premium OR Rank Math Pro" — these have different feature sets (Rank Math has built-in redirect manager, Yoast requires separate redirect plugin). Schema generation differs. UI/UX for content editors differs.
- **Impact:** Modest — both are capable. But switching mid-project wastes effort.
- **Risk Level:** LOW
- **Recommendation:** Choose one. Recommendation: Rank Math Pro for built-in redirect manager and richer free tier. Document decision.
- **Priority:** P1
- **Blocking:** NON-BLOCKING

#### Issue SWA-04: Caching Plugin Selection is Ambiguous (WP Rocket vs FlyingPress)

- **Description:** "WP Rocket OR FlyingPress" — both excellent but have different configuration interfaces and some feature differences (FlyingPress has built-in unused CSS removal, WP Rocket requires separate service).
- **Impact:** Modest. Either works.
- **Risk Level:** LOW
- **Recommendation:** Choose one. Recommendation: FlyingPress for built-in unused CSS removal and generally stronger Core Web Vitals optimization. Document decision.
- **Priority:** P2
- **Blocking:** NON-BLOCKING

#### Issue SWA-05: No Backup Strategy for WooCommerce Data Independently

- **Description:** The backup strategy specifies daily full backups (files + database). However, WooCommerce order data is financial/legal data requiring 7-year retention per Dutch law. The current spec does not address whether full backups are retained for 7 years (prohibitively expensive) or whether a separate order export strategy exists.
- **Impact:** Potential legal non-compliance for financial data retention. Potential data loss if old backups are rotated out.
- **Risk Level:** MEDIUM
- **Recommendation:** Add a separate WooCommerce order export strategy: monthly XML/CSV export of all orders to offsite storage (Google Drive, Dropbox, or S3) with 7-year retention. Add to backup specification. Add acceptance criterion.
- **Priority:** P1
- **Blocking:** NON-BLOCKING

#### Issue SWA-06: No Disaster Recovery Runbook Referenced

- **Description:** The spec mentions "Recovery procedure documented in a runbook" but the runbook itself is not part of the specification. Disaster recovery cannot be tested or validated without the actual procedure.
- **Impact:** In disaster scenario, recovery is ad-hoc. RTO/RPO targets are aspirational without a verified procedure.
- **Risk Level:** MEDIUM
- **Recommendation:** Include the disaster recovery runbook as a deliverable in Sprint 6. Must include: step-by-step restore instructions, hosting support contact, DNS provider login location, developer emergency contact, client emergency contact. Test the runbook during monthly backup verification.
- **Priority:** P2
- **Blocking:** NON-BLOCKING

---

### 1.2 CMS Architecture Review

#### Issue CMS-01: FSE Compatibility Claim vs PHP Template Approach Conflict

- **Description:** The spec references both "FSE-compatible" theme and `front-page.php`, `page-service.php` (classic PHP templates). A true Full Site Editing theme uses `theme.json` + block templates in the Site Editor (HTML files, not PHP). The spec is internally contradictory — it cannot be both a PHP template theme and FSE-compatible.
- **Impact:** Developer confusion. If FSE is desired, all template files must be HTML block templates, not PHP. If PHP templates are desired, FSE compatibility is not the goal. This is a fundamental architecture decision.
- **Risk Level:** HIGH
- **Recommendation:** Clarify the approach:
  - **Option A (Recommended for this project):** Hybrid theme — `theme.json` for design tokens + block styles, PHP templates (`page-service.php`, etc.) for structured layouts, Block Editor for content areas. This is the current `page-service.php` approach and is correct. Remove "FSE-compatible" label and replace with "Hybrid block theme using theme.json + PHP templates + Block Editor."
  - **Option B:** True FSE theme — all templates as HTML files in `/templates/`, full Site Editor. Higher risk, less mature pattern.
- **Priority:** P0
- **Blocking:** **BLOCKING**

#### Issue CMS-02: CPT Slug Conflicts with Existing Pages

- **Description:** The `hds_testimonial` CPT is given the slug `referenties`. But `/referenties/` is already a published WordPress Page (P13). This will cause a URL conflict unless the CPT `rewrite` slug is changed or the CPT `has_archive` is set to false with a different query mechanism.
- **Impact:** 404 errors, broken URLs, WordPress rewrite rules conflict.
- **Risk Level:** HIGH
- **Recommendation:** Change CPT rewrite slug. Options:
  - `hds_testimonial` slug: `getuigenis` (single) / `getuigenissen` (archive). The archive page can live at `/getuigenissen/` and individual testimonials at `/getuigenis/{slug}/` — though this may not be needed if testimonials are only displayed on the Referenties page.
  - **Simplest approach:** Set `'public' => false` and `'publicly_queryable' => false` on the CPT. Testimonials are queried only via custom blocks on the Referenties page (P13). No archive. No single view. No slug conflict.
- **Priority:** P0
- **Blocking:** **BLOCKING**

#### Issue CMS-03: FAQ CPT Has No Specified Display Strategy

- **Description:** The `hds_faq` CPT stores FAQ items, but the spec does not define how these items are displayed. Are they queried on `/veelgestelde-vragen/` (P18) via a block that queries the CPT? Or is the content of P18 itself the FAQ data (manual entry in the Block Editor)? The spec says "Title = question, Editor = answer" which implies manual CPT entries, but the display mechanism is undefined.
- **Impact:** Developer implements one mechanism; content editor expects another.
- **Risk Level:** MEDIUM
- **Recommendation:** Define display strategy: Option A (recommended) — Manual FAQ content via Yoast FAQ Block on P18 Page. No CPT needed. Simpler. Editors edit one page. Yoast FAQ block auto-generates FAQPage schema. No need for `hds_faq` CPT. Option B — CPT queried by `hds/faq` custom block. More flexible for reusing FAQs across pages but adds complexity.
- **Priority:** P1
- **Blocking:** NON-BLOCKING

#### Issue CMS-04: No Content Revision / Editorial Workflow Specified

- **Description:** The spec defines user roles (Administrator, Editor, Shop Manager, SEO Manager, Subscriber) but does not define a content approval workflow. If multiple content editors work on the site, there is no mechanism for draft review, approval, or scheduled publishing.
- **Impact:** Publishing errors. Conflicting edits. No governance for content quality.
- **Risk Level:** LOW
- **Recommendation:** For this project scope (single client, likely 1-2 content editors), a formal workflow is over-engineering. However, document that: (a) Editors have `publish_pages` and `publish_posts` capabilities, (b) WordPress built-in revisions provide rollback, (c) If the team grows beyond 2 content editors, implement PublishPress or Edit Flow for editorial workflow. Add to future scalability section.
- **Priority:** P3
- **Blocking:** NON-BLOCKING

---

### 1.3 WordPress Best Practices Review

#### Issue WBP-01: wp-config.php Salt Rotation Not Specified

- **Description:** Security specifications say "Salts rotated" but do not define how or when. WordPress salts should be regenerated if a site is compromised, and best practice is to use the WordPress.org salt generator with unique values.
- **Impact:** Minor — salts are generated fresh on a new install.
- **Risk Level:** LOW
- **Recommendation:** Add to Sprint 1 task: generate fresh salts via https://api.wordpress.org/secret-key/1.1/salt/ and place in wp-config.php. Document salt regeneration procedure in security runbook.
- **Priority:** P2
- **Blocking:** NON-BLOCKING

#### Issue WBP-02: Database Prefix Change Impact on WooCommerce

- **Description:** Spec says "wp_ prefix changed from default." WooCommerce stores order data with hardcoded `wp_` table references in some configurations. Changing the prefix is good practice, but WooCommerce must be tested with a non-default prefix.
- **Impact:** Potential WooCommerce table reference breakage.
- **Risk Level:** MEDIUM
- **Recommendation:** After changing database prefix, run full WooCommerce purchase test on staging before production deployment. Document test result.
- **Priority:** P1
- **Blocking:** NON-BLOCKING

#### Issue WBP-03: No WordPress Cron / WP-Cron Alternative Specified

- **Description:** WordPress pseudo-cron (`wp-cron.php`) triggers scheduled tasks (scheduled posts, WooCommerce order cleanup, backup triggers). On low-traffic sites, WP-Cron may not fire reliably. On high-traffic sites, it fires on every page load (performance hit). The spec does not address WP-Cron configuration.
- **Impact:** Scheduled posts don't publish on time. Backup triggers may be delayed.
- **Risk Level:** LOW
- **Recommendation:** Disable WP-Cron in wp-config.php (`define('DISABLE_WP_CRON', true)`) and replace with a server-level cron job calling `wp-cron.php` every 15 minutes. Managed WordPress hosts (Kinsta, WP Engine) handle this automatically. Add to hosting selection criteria.
- **Priority:** P2
- **Blocking:** NON-BLOCKING

#### Issue WBP-04: Autoloaded Data Not Addressed

- **Description:** WordPress `wp_options` table with large autoloaded data sets is a common performance issue on mature sites. The spec does not address autoloaded data management or monitoring.
- **Impact:** Degraded database performance over time as plugins and themes add autoloaded options.
- **Risk Level:** LOW
- **Recommendation:** Add to maintenance plan: quarterly review of autoloaded data via Query Monitor or WP-Optimize. Set autoload to 'no' for non-critical options.
- **Priority:** P3
- **Blocking:** NON-BLOCKING

---

### 1.4 SEO Architecture Review

#### Issue SEO-01: No Keyword Research or Competitor Analysis Foundation

- **Description:** The spec assigns SEO keywords per page (e.g., "reguliere schoonmaak", "kantoor schoonmaak") but does not reference any keyword research data, search volume estimates, or competitor analysis. Keywords appear to be derived from page content, not from data-driven research.
- **Impact:** Pages may be optimized for low-volume or irrelevant keywords. Missed opportunity for high-volume terms.
- **Risk Level:** MEDIUM
- **Recommendation:** Before Sprint 5 (SEO implementation), conduct keyword research using Google Keyword Planner, Ahrefs, or Semrush. Document: top 20 target keywords with monthly search volume (Netherlands), competition level, and current ranking position. Map each keyword to a target page. Adjust page titles and content accordingly.
- **Priority:** P1
- **Blocking:** NON-BLOCKING

#### Issue SEO-02: Image SEO Not Addressed

- **Description:** The spec requires alt text on all images but does not address image sitemaps (separate from page sitemap), image structured data (ImageObject schema), or image file name SEO. Google Image Search is a significant traffic source for service businesses showing "before/after" results.
- **Impact:** Missed image search traffic. Images not discoverable via image sitemap.
- **Risk Level:** LOW
- **Recommendation:** Add to SEO spec: (a) generate image sitemap (Yoast/Rank Math can do this), (b) optimize image filenames for SEO keywords, (c) add ImageObject schema to key service images (before/after). Priority P3 unless the business has strong visual content.
- **Priority:** P3
- **Blocking:** NON-BLOCKING

#### Issue SEO-03: No hreflang Strategy for Single-Language Site

- **Description:** The spec correctly states "Single language (nl-NL). No hreflang needed." However, it does not address the `x-default` hreflang tag which Google recommends even for single-language sites to explicitly declare the default language.
- **Impact:** Minimal for a single-language Dutch site. But absence could theoretically cause confusion if Google detects English content snippets.
- **Risk Level:** LOW
- **Recommendation:** Add `<link rel="alternate" hreflang="nl" href="https://helderduidelijkschoon.nl/" />` and `<link rel="alternate" hreflang="x-default" href="https://helderduidelijkschoon.nl/" />` on the homepage only. Not critical for launch.
- **Priority:** P3
- **Blocking:** NON-BLOCKING

#### Issue SEO-04: No 404 Error Monitoring Strategy

- **Description:** Post-launch monitoring references GSC crawl error checks, but there is no strategy for automatically detecting and fixing 404 errors before they accumulate. Broken links from external sites or outdated internal links will generate 404s silently.
- **Impact:** Poor user experience. Lost link equity. Gradual SEO degradation.
- **Recommendation:** Implement a 404 monitoring plugin (Redirection or Rank Math 404 monitor). Configure to log all 404 hits. Review 404 log weekly. For high-traffic 404s, create 301 redirects. This is standard practice and should be in the maintenance plan.
- **Priority:** P2
- **Blocking:** NON-BLOCKING

---

### 1.5 Information Architecture Review

#### Issue IA-01: Service Page Ordering Not Defined

- **Description:** Pages P02-P08 (7 service pages) have no defined order for navigation display or homepage service grid. The spec shows them in a specific order in the content hierarchy but does not state this is the canonical order. Navigation order affects user perception of importance.
- **Impact:** Arbitrary ordering. Potential client disagreement post-implementation.
- **Risk Level:** LOW
- **Recommendation:** Define the canonical service order: (1) Reguliere Schoonmaak, (2) Glasbewassing, (3) Gevelreiniging, (4) Vloeronderhoud, (5) VVE Service, (6) Oplevering Schoonmaak, (7) Industriele Schoonmaak. Rationale: Regular cleaning is the primary service (highest demand), followed by specialized services. Implement as a menu_order field on each Page, queried in nav and service grid.
- **Priority:** P2
- **Blocking:** NON-BLOCKING

#### Issue IA-02: Breadcrumbs Not Defined for Category Landing Pages

- **Description:** BreadcrumbList schema is required on all inner pages, but the breadcrumb hierarchy for category landing pages (P09, P10) is undefined. Should it be: Home > Glas & Gevel > Glasbewassing OR Home > Glasbewassing? If the landing page is the parent, URLs are flat (/glas-en-gevel/ and /glasbewassing/ are siblings), which creates a URL/breadcrumb mismatch.
- **Impact:** Schema validation error. Confusing UX.
- **Recommendation:** Breadcrumbs should follow the URL hierarchy, not the IA hierarchy. Since all URLs are flat (depth 1), breadcrumbs for all pages except blog posts and products should be: Home > [Page Name]. No intermediate level. This is correct because the URL structure is flat. Clarify in spec.
- **Priority:** P2
- **Blocking:** NON-BLOCKING

#### Issue IA-03: No Mobile Navigation IA Specified

- **Description:** Mobile navigation is described as "Hamburger menu with accordion expand/collapse. All items visible, no horizontal scroll." However, the mobile IA — what items appear, in what order, with what labels — is not specified. The desktop dropdown structure (with child items) may not translate cleanly to mobile accordion.
- **Impact:** Inconsistent mobile experience. Developer interprets accordion differently than UX designer intended.
- **Risk Level:** LOW
- **Recommendation:** Define mobile navigation IA explicitly. At minimum, specify: (a) Top level: Diensten, Over HDS, Luchtreiniging, Contact. Each expands to show children. (b) Phone number and cart icon remain visible in mobile header. (c) Search icon optional in mobile nav. This is standard and low-risk — the spec is sufficient as-is with minor clarification.
- **Priority:** P2
- **Blocking:** NON-BLOCKING

---

### 1.6 UX Architecture Review

#### Issue UX-01: No Loading States Defined

- **Description:** The spec defines form success (redirect to /bedankt/) and form error (inline validation) but does not define loading states. A user clicking "Verstuur bericht" on a slow connection sees no feedback between click and redirect. This is a common UX gap.
- **Impact:** User may click submit multiple times. Uncertainty about whether form was submitted.
- **Risk Level:** MEDIUM
- **Recommendation:** Add loading state specification: (a) On form submit, button text changes to "Versturen..." with disabled state + spinner. (b) Gravity Forms provides this natively via AJAX submission. Enable AJAX submission on all forms. (c) For WooCommerce cart/checkout, use WooCommerce's built-in loading states. Add to UX spec.
- **Priority:** P2
- **Blocking:** NON-BLOCKING

#### Issue UX-02: No Empty States Defined

- **Description:** Several components may render empty: blog index (no posts yet), search results (no results), client logo carousel (no logos available), testimonial block (no testimonials). The spec for search includes "Geen resultaten" but other empty states are not defined.
- **Impact:** Blank or broken-looking sections on otherwise complete pages.
- **Risk Level:** MEDIUM
- **Recommendation:** Define empty state behavior for all conditional components:
  - Blog index: "Binnenkort verschijnen hier de eerste artikelen." with CTA to contact page.
  - Client logos: Hide the section entirely if no logos. Do not render empty carousel.
  - Testimonials: "Wij horen graag uw ervaring! Deel uw review." with link to testimonial submission.
  - Search: Already defined ("Geen resultaten").
- **Priority:** P2
- **Blocking:** NON-BLOCKING

#### Issue UX-03: No Print Stylesheet Requirement

- **Description:** B2B clients (facility managers, VvE boards) often print service pages for decision-making or record-keeping. No print stylesheet is specified.
- **Impact:** Printed pages may look unprofessional — cut-off content, unnecessary elements (nav, footer), poor typography.
- **Risk Level:** LOW
- **Recommendation:** Add a minimal print stylesheet: hide nav, footer CTAs, cookie banner, and side columns. Display full URLs for links. Ensure content flows naturally. Low effort, professional result. P3 priority.
- **Priority:** P3
- **Blocking:** NON-BLOCKING

---

### 1.7 Accessibility Review

#### Issue A11Y-01: WooCommerce Checkout Accessibility Not Addressed

- **Description:** WooCommerce default checkout has known accessibility issues: payment method selection with radio buttons not properly labelled, order review table not marked up as a data table, dynamically injected payment fields without ARIA announcements. The spec targets WCAG 2.2 AA for "every page" but doesn't specifically address WooCommerce templates which are notoriously challenging.
- **Impact:** Checkout page fails WCAG 2.2 AA. Legal risk (European Accessibility Act enforcement begins June 2025).
- **Risk Level:** MEDIUM
- **Recommendation:** Add WooCommerce-specific accessibility testing to QA plan (Section I2.1): test checkout with screen reader, keyboard-only, and axe DevTools. If issues found, use WooCommerce template overrides in the theme to fix. Budget 1-2 days for potential WooCommerce accessibility remediation.
- **Priority:** P1
- **Blocking:** NON-BLOCKING

#### Issue A11Y-02: reCAPTCHA v3 Accessibility Not Evaluated

- **Description:** reCAPTCHA v3 is "invisible" and runs in the background — good UX. However, Google's reCAPTCHA badge (required by ToS when using v3 invisibly) may not meet accessibility standards and can interfere with page layout. Additionally, reCAPTCHA v3's fallback challenge (when the score is too low) uses an image challenge that is not accessible to blind users.
- **Impact:** Potential WCAG failure. Blind users blocked from form submission if reCAPTCHA falls back to image challenge.
- **Risk Level:** MEDIUM
- **Recommendation:** (a) Position the reCAPTCHA badge accessibly (not obscured by cookie banner or floating elements). (b) Add a note in the privacy policy that reCAPTCHA is used and may present accessibility challenges — provide phone number as alternative. (c) Ensure the honeypot fallback catches most spam so reCAPTCHA rarely falls back to image challenge.
- **Priority:** P2
- **Blocking:** NON-BLOCKING

#### Issue A11Y-03: No Accessibility Testing for Dynamic Content

- **Description:** The accessibility testing protocol lists axe DevTools, WAVE, Lighthouse, keyboard, and screen reader testing — but all these test static page states. Dynamic content updates (adding to cart via AJAX, WooCommerce cart update, search results appearing, cookie consent banner appearing/disappearing) must be tested for `aria-live` announcements and focus management.
- **Impact:** Dynamic interactions may be inaccessible despite static page passing automated tests.
- **Risk Level:** MEDIUM
- **Recommendation:** Add dynamic content accessibility test cases to QA plan: (a) Add to cart: verify screen reader announces item added. (b) Cart quantity update: verify screen reader announces total change. (c) Cookie banner dismiss: verify focus moves to next logical element. (d) Search results: verify screen reader announces result count.
- **Priority:** P2
- **Blocking:** NON-BLOCKING

---

### 1.8 Performance Review

#### Issue PERF-01: No Performance Regression Testing Strategy

- **Description:** The spec defines performance budgets and pre-launch testing, but there is no strategy for detecting performance regressions after launch. A plugin update, new content, or configuration change could degrade Core Web Vitals silently.
- **Impact:** Gradual performance degradation. PSI scores drift below 90 without detection.
- **Risk Level:** MEDIUM
- **Recommendation:** Implement automated performance monitoring: (a) Schedule weekly PSI API checks via a monitoring service (DebugBear, SpeedCurve, or Calibre). (b) Alert if PSI mobile drops below 90. (c) Run before every plugin/theme update on staging. Add to maintenance plan.
- **Priority:** P2
- **Blocking:** NON-BLOCKING

#### Issue PERF-02: No Critical Rendering Path Analysis

- **Description:** The spec correctly requires critical CSS inlining, font preloading, and lazy loading. However, it does not specify which elements constitute the critical rendering path (the minimum set of resources needed to render above-the-fold content). Without this, critical CSS generation may be suboptimal.
- **Impact:** First paint may be delayed by non-critical CSS in the critical path.
- **Risk Level:** LOW
- **Recommendation:** Trust the caching plugin (WP Rocket or FlyingPress) to auto-generate critical CSS. Both do this well. Add a manual verification step: after critical CSS generation, test with WebPageTest filmstrip view — above-the-fold content should render in < 2 seconds. If not, manually adjust critical CSS.
- **Priority:** P2
- **Blocking:** NON-BLOCKING

#### Issue PERF-03: No Mobile Performance Budget Separate from Desktop

- **Description:** Performance budgets are specified for "mobile" and "desktop" but the test conditions are not specified. Mobile testing must be done on emulated Moto G4 on 3G Fast (slow 3G), not on a high-end device on 4G/WiFi. The spec references this in pre-launch tests but does not make it a hard budget constraint.
- **Impact:** "Passing" mobile PSI on a fast connection does not represent real user experience.
- **Risk Level:** LOW
- **Recommendation:** Hard requirement: WebPageTest Moto G4, 3G Fast (Amsterdam server). LCP < 4 seconds on this configuration. This is a more realistic budget than PSI's lab data alone. Add to acceptance criteria.
- **Priority:** P2
- **Blocking:** NON-BLOCKING

---

### 1.9 Security Review

#### Issue SEC-01: No WAF (Web Application Firewall) Rules Specified

- **Description:** Cloudflare WAF is listed in the infrastructure diagram but no WAF rules are specified. A WAF without configured rules provides minimal protection. WordPress-specific WAF rules (blocking wp-login.php brute force, blocking XML-RPC, blocking known exploit patterns) must be configured.
- **Impact:** Cloudflare provides basic DDoS protection but may not block WordPress-specific attacks without rules.
- **Risk Level:** MEDIUM
- **Recommendation:** Configure Cloudflare WAF rules: (a) Block access to `/xmlrpc.php` (defense in depth, even if server-blocked). (b) Rate limit `/wp-login.php` (or custom login URL). (c) Enable Cloudflare's "WordPress" managed ruleset (available on Pro plan, or use free custom rules). (d) Challenge (JS Challenge or CAPTCHA) for suspicious requests. Document rules in security specification.
- **Priority:** P1
- **Blocking:** NON-BLOCKING

#### Issue SEC-02: No Security Incident Response Procedure

- **Description:** The spec lists security measures (Wordfence, 2FA, XML-RPC disabled) but does not define what happens if a security incident occurs despite these measures. The GDPR section mentions breach notification within 72 hours to Autoriteit Persoonsgegevens, but the procedural steps between detection and notification are absent.
- **Impact:** Delayed response to security incidents. GDPR non-compliance for breach handling. Potential for greater damage.
- **Risk Level:** MEDIUM
- **Recommendation:** Document a Security Incident Response Procedure as part of Sprint 6 deliverable: (a) Detection (Wordfence alert, client report, uptime alert). (b) Containment (take site offline or put in maintenance mode, change all passwords, block attacker IPs). (c) Investigation (server logs, Wordfence logs, file integrity check). (d) Remediation (restore clean backup, patch vulnerability, harden). (e) Notification (Autoriteit Persoonsgegevens within 72 hours if personal data breached, affected users). (f) Post-mortem (document root cause, preventive measures).
- **Priority:** P1
- **Blocking:** NON-BLOCKING

#### Issue SEC-03: No File Integrity Monitoring

- **Description:** Wordfence provides malware scanning but does not replace dedicated file integrity monitoring. WordPress core files, theme files, and plugin files should be monitored for unauthorized changes.
- **Impact:** Delayed detection of compromised files.
- **Risk Level:** LOW
- **Recommendation:** Wordfence includes file change detection. Enable this feature. Additionally, most managed WordPress hosts provide file integrity monitoring. Verify with hosting provider. Add to security spec.
- **Priority:** P2
- **Blocking:** NON-BLOCKING

---

### 1.10 Scalability Review

#### Issue SCA-01: No Concurrent User Capacity Defined

- **Description:** The spec does not define expected or maximum concurrent users. Without this, hosting scaling decisions (server resources, PHP workers, database connection pool) are guesses.
- **Impact:** Potential site slowdown or outage under unexpected traffic load.
- **Risk Level:** LOW (for a local B2B site with modest traffic)
- **Recommendation:** The site is a local B2B cleaning company website — traffic is inherently limited (likely <100 concurrent users). Managed WordPress hosting with Cloudflare CDN handles this trivially. No scalability concern at launch. If traffic grows significantly (e.g., due to a successful Google Ads campaign), Cloudflare + managed hosting can scale vertically. Document as: "Site designed for <100 concurrent users. Vertical scaling via hosting plan upgrade if needed."
- **Priority:** P3
- **Blocking:** NON-BLOCKING

#### Issue SCA-02: No Database Growth Projection

- **Description:** The spec defines a clean database but does not project growth from form entries, WooCommerce orders, blog posts, and media uploads over 1-3 years.
- **Impact:** Potential storage exhaustion on hosting plan with limited SSD space.
- **Risk Level:** LOW
- **Recommendation:** Estimate: (a) Forms: ~50 entries/month * 2KB = 1.2MB/year. (b) WooCommerce orders: unknown volume but likely low for a B2B cleaning company's side business. (c) Blog: 12-24 posts/year with images. (d) Media: gradual accumulation. Total: likely < 500MB after 3 years. Any managed WordPress host plan starts at 10GB+. No concern. Document the estimate.
- **Priority:** P3
- **Blocking:** NON-BLOCKING

---

### 1.11 Maintainability Review

#### Issue MNT-01: No Plugin Update Testing Procedure

- **Description:** The spec says "Auto-updates enabled for minor releases. Major releases tested on staging before production." But the testing procedure for major updates is not defined. What is tested? By whom? How long does testing take?
- **Impact:** Updates either (a) delayed indefinitely out of caution, leading to security debt, or (b) applied without testing, causing regressions.
- **Risk Level:** MEDIUM
- **Recommendation:** Define a plugin update testing procedure: (a) Clone production to staging. (b) Apply updates on staging. (c) Run smoke test: Home, 1 service page, 1 product page, Contact form, WooCommerce test purchase. (d) If all pass, apply to production. (e) Time budget: 30-60 minutes per update cycle. (f) Frequency: monthly. Add to maintenance plan.
- **Priority:** P2
- **Blocking:** NON-BLOCKING

#### Issue MNT-02: No Deprecated Code Detection

- **Description:** WordPress and PHP evolve. Functions get deprecated. The spec does not address how deprecated code in custom theme or plugins will be detected before it breaks.
- **Impact:** Site breaks after WordPress or PHP upgrade due to deprecated function removal.
- **Risk Level:** LOW
- **Recommendation:** (a) Use PHP_CodeSniffer with WordPress coding standards during development. (b) Enable `WP_DEBUG` on staging to catch deprecation notices. (c) Before major WordPress upgrades, review the field guide for deprecated functions. Low risk for a fresh build (all modern code). Add to maintenance plan.
- **Priority:** P3
- **Blocking:** NON-BLOCKING

---

### 1.12 Content Management Review

#### Issue CNT-01: No Media Library Organization Strategy

- **Description:** The spec defines image file naming conventions (lowercase, hyphens, Dutch keywords) but does not define a media library folder structure, taxonomy, or tagging strategy. Over time, the WordPress media library becomes a flat list of thousands of files.
- **Impact:** Content editors cannot find images. Duplicate uploads. Broken image references.
- **Risk Level:** LOW
- **Recommendation:** (a) Organize media by year/month (WordPress default). (b) Use a media library organization plugin (FileBird, Media Library Folders) if the client requests folder organization. (c) Train client to use descriptive filenames and alt text at upload time. P3.
- **Priority:** P3
- **Blocking:** NON-BLOCKING

#### Issue CNT-02: No Content Freeze/Migration Coordination Plan

- **Description:** The content migration strategy says "Manual migration with rewrite" but does not address: during content migration from old site to new staging, should the old site remain editable? If the client updates old site content while migration is in progress, those updates are lost.
- **Impact:** Lost content updates. Migration data stale.
- **Recommendation:** Add to migration checklist: (a) Before content migration begins (Sprint 2), notify client: "Do not edit the old website from this date forward. All content updates should be documented and provided to the development team for inclusion in the new site." (b) Take a final content snapshot of the old site at the start of migration. (c) If critical business information changes during migration (phone number, address), client notifies developer directly.
- **Priority:** P2
- **Blocking:** NON-BLOCKING

---

### 1.13 WooCommerce Review

#### Issue WC-01: No Tax Display Decision Documented

- **Description:** The spec notes the ambiguity: "Prices listed excl. BTW (matches current site) — Client to confirm whether to keep excl. BTW or switch to incl. BTW." The decision is flagged as MI (Missing Information) but the default configuration in the WooCommerce spec (Section G2.1) says "Prices Entered With Tax: No (excl. BTW)." This is an implicit decision that contradicts the stated ambiguity.
- **Impact:** If client wants incl. BTW, rework required. If client confirms excl. BTW, no issue.
- **Risk Level:** LOW
- **Recommendation:** Resolve MI before WooCommerce Sprint 4. Default to excl. BTW (B2B standard). If client does not respond, proceed with excl. BTW and document the assumption.
- **Priority:** P2
- **Blocking:** NON-BLOCKING

#### Issue WC-02: No Abandoned Cart Recovery Strategy

- **Description:** WooCommerce supports abandoned cart emails but the spec does not mention them. For a small shop selling Airfixr products, abandoned cart recovery could be a meaningful revenue source.
- **Impact:** Missed revenue from abandoned carts.
- **Risk Level:** LOW
- **Recommendation:** Implement abandoned cart recovery emails if WooCommerce email automation is set up. Requires WooCommerce + a follow-up email plugin OR hosting-provided cart recovery. Decision: P3 (low priority for a side-business shop). Add to future scalability.
- **Priority:** P3
- **Blocking:** NON-BLOCKING

#### Issue WC-03: No Inventory Threshold Alerts

- **Description:** The spec enables inventory management but does not specify low-stock alert thresholds or notification recipients. If an Airfixr product sells out and the client is unaware, orders may be accepted for out-of-stock items.
- **Impact:** Customer dissatisfaction. Order fulfillment failures.
- **Risk Level:** LOW
- **Recommendation:** (a) Set low-stock threshold to 2 for each product. (b) WooCommerce sends low-stock email to `info@helderduidelijkschoon.nl`. (c) Out-of-stock items: hide from shop OR show as "Niet op voorraad" with backorder disabled. Document thresholds.
- **Priority:** P2
- **Blocking:** NON-BLOCKING

---

### 1.14 Migration Review

#### Issue MIG-01: No Rollback Test Before Migration

- **Description:** The spec says "Take final backup of old site" before launch, but does not require a rollback test. The backup must be tested (restored to verify integrity) before the old site is taken offline. An untested backup is not a backup — it's hope.
- **Impact:** If the backup is corrupted, old site is unrecoverable after being taken offline.
- **Risk Level:** CRITICAL
- **Recommendation:** Add to migration checklist: (a) Before taking old site offline, restore the backup to a test environment and verify: all pages load, forms submit, WooCommerce works, admin login works. (b) Only after verification, proceed with taking old site offline. (c) Keep the verified backup in at least two locations (offsite + developer local).
- **Priority:** P0
- **Blocking:** **BLOCKING**

#### Issue MIG-02: No Data Integrity Verification After Migration

- **Description:** After WooCommerce product import and content migration, the spec checks for broken links (Screaming Frog) and form submissions, but does not verify data integrity: are all 14 products correctly imported with correct prices, images, and stock status? Were any product variations lost? Is the content character encoding correct (UTF-8 with Dutch diacritics)?
- **Impact:** Corrupted product data. Broken special characters in Dutch text (ë, ï, é).
- **Risk Level:** MEDIUM
- **Recommendation:** Add to content migration checklist (Section 45): (a) Spot-check 5 of 14 products: verify title, price, description, image, stock status match old site. (b) Verify special characters render correctly on all pages. (c) Verify all downloaded PDFs open and are not corrupted. (d) Run a database collation check (utf8mb4).
- **Priority:** P1
- **Blocking:** NON-BLOCKING

#### Issue MIG-03: DNS TTL Lowering Not in Migration Timeline

- **Description:** The Risk Register (R12) says "Lower TTL to 300 (5 min) 24h before launch" but the migration checklist (Section 40.3) does not include this step. Without low TTL, DNS propagation can take 24-48 hours, during which some users see the old site and others see the new site.
- **Impact:** Inconsistent user experience during propagation window. Potential for form submissions to old (broken) contact page.
- **Risk Level:** MEDIUM
- **Recommendation:** Add to migration checklist Section 40.3: (a) 24 hours before launch: lower DNS TTL to 300 seconds. (b) Verify TTL change propagated (whatsmydns.net). (c) Proceed with launch. (d) 24 hours after launch: restore TTL to normal (3600 or default). This is critical for a smooth cutover.
- **Priority:** P0
- **Blocking:** **BLOCKING**

---

### 1.15 Analytics Review

#### Issue ANA-01: No Custom Dimensions or Metrics Defined

- **Description:** GA4 events are well-defined, but custom dimensions (e.g., form type: contact vs quote, service page category, user device category) and custom metrics (e.g., form completion time, scroll depth) are not defined. Custom dimensions enable deeper analysis.
- **Impact:** Limited analytics segmentation capability.
- **Risk Level:** LOW
- **Recommendation:** Add custom dimensions: (a) `form_type` = contact | quote | vacancy. (b) `service_page` = which service page the conversion occurred on. (c) `page_category` = service | about | contact | legal | ecommerce | blog. These are low-effort to implement via GTM Data Layer and add significant analytical value.
- **Priority:** P3
- **Blocking:** NON-BLOCKING

#### Issue ANA-02: No Google Looker Studio Dashboard Template

- **Description:** The spec mentions monthly reporting to the client but does not define the report format or provide a dashboard template. Without this, monthly reporting is manual effort each time.
- **Impact:** Inconsistent reporting. High manual effort. Reports may not answer client's key questions.
- **Risk Level:** LOW
- **Recommendation:** Create a Google Looker Studio dashboard template connected to GA4. Include: (a) Traffic overview (users, sessions, pageviews). (b) Traffic sources (organic, direct, referral, social). (c) Top landing pages. (d) Conversion events (by type). (e) Geographic overview (cities in service area). (f) Device breakdown. Once built, monthly reporting is a 5-minute data refresh. Add to Sprint 5 deliverable.
- **Priority:** P3
- **Blocking:** NON-BLOCKING

---

### 1.16 QA Review

#### Issue QA-01: No Automated Regression Testing

- **Description:** The QA plan is entirely manual. No automated end-to-end tests (Playwright, Cypress) or visual regression tests (Percy, Chromatic) are specified. In a rebuild project, automated tests provide confidence that new changes don't break existing functionality.
- **Impact:** Each manual QA cycle is time-consuming. Regressions may be missed.
- **Risk Level:** MEDIUM
- **Recommendation:** Implement minimal automated smoke tests using Playwright (open-source): (a) Homepage loads with 200. (b) Contact form submits successfully. (c) WooCommerce product page loads. (d) Mobile menu opens. Run these before every production deployment. Budget: 1 day to set up 5-10 critical path tests. Add to Sprint 7.
- **Priority:** P2
- **Blocking:** NON-BLOCKING

#### Issue QA-02: No Visual Regression Testing

- **Description:** CSS changes, plugin updates, or theme updates can cause visual regressions (layout shift, broken styling, overlapping elements) that functional tests miss. No visual regression testing is specified.
- **Impact:** Visual bugs reach production undetected.
- **Risk Level:** LOW
- **Recommendation:** For a project of this scope, visual regression testing adds moderate value at moderate cost. Recommendation: implement only if budget and timeline allow. As a minimum, add "visual inspection of key pages" to the plugin update testing procedure (see MNT-01). P3.
- **Priority:** P3
- **Blocking:** NON-BLOCKING

#### Issue QA-03: No API Endpoint Testing

- **Description:** The WordPress REST API and WooCommerce API endpoints are not tested in the QA plan. The spec blocks `/wp-json/wp/v2/users` for security but does not verify that other public endpoints (e.g., product data, sitemaps) return correct data.
- **Impact:** API consumers (mobile, future headless frontend) encounter broken endpoints.
- **Risk Level:** LOW
- **Recommendation:** At minimum, add to QA: (a) Verify `/wp-json/wp/v2/pages` returns public pages. (b) Verify sitemap XML is valid (already covered). (c) Verify WooCommerce product API returns product data (if needed). P3.
- **Priority:** P3
- **Blocking:** NON-BLOCKING

---

## Part 2: Comprehensive Gap Analysis

### 2.1 Missing Requirements

| # | Missing Requirement | Criticality | Addressed By |
|---|---|---|---|
| MR01 | SMTP / email deliverability specification | HIGH | SWA-01 |
| MR02 | Theme selection resolution (custom vs GeneratePress vs Kadence) | CRITICAL | SWA-02 |
| MR03 | FSE vs PHP template approach clarification | HIGH | CMS-01 |
| MR04 | CPT slug conflict resolution strategy | HIGH | CMS-02 |
| MR05 | Loading states for forms and interactive elements | MEDIUM | UX-01 |
| MR06 | Empty states for conditional components | MEDIUM | UX-02 |
| MR07 | Security incident response procedure | MEDIUM | SEC-02 |
| MR08 | WAF rules specification | MEDIUM | SEC-01 |
| MR09 | WooCommerce abandoned cart recovery strategy | LOW | WC-02 |
| MR10 | WooCommerce low-stock alert thresholds | LOW | WC-03 |
| MR11 | Print stylesheet for B2B use case | LOW | UX-03 |
| MR12 | Keyword research data before SEO implementation | MEDIUM | SEO-01 |
| MR13 | Performance regression monitoring strategy | MEDIUM | PERF-01 |
| MR14 | Automated smoke test suite | MEDIUM | QA-01 |

### 2.2 Ambiguous Requirements

| # | Ambiguity | Resolution |
|---|---|---|
| AB01 | "Custom block-based theme (FSE-compatible) OR GeneratePress Pro / Kadence Pro" | Resolve to one. Recommendation: GeneratePress Pro + GenerateBlocks. |
| AB02 | "Yoast SEO Premium OR Rank Math Pro" | Resolve to one. Recommendation: Rank Math Pro. |
| AB03 | "WP Rocket OR FlyingPress" | Resolve to one. Recommendation: FlyingPress. |
| AB04 | "Custom block-based theme" vs PHP template files (CMS-01) | Resolve: Hybrid block theme with theme.json + PHP templates. Remove FSE label. |
| AB05 | Prices excl. BTW vs incl. BTW | Default: excl. BTW (B2B standard). Document assumption. |

### 2.3 Contradictory Requirements

| # | Contradiction | Resolution |
|---|---|---|
| CR01 | FSE-compatible theme vs PHP template files (CMS-01) | Resolved: Hybrid approach. See CMS-01. |
| CR02 | CPT slug `referenties` conflicts with existing Page `/referenties/` (CMS-02) | Resolved: Set CPT to `public => false`. See CMS-02. |

### 2.4 Hidden Dependencies

| # | Dependency | Depends On | Not Acknowledged In |
|---|---|---|---|
| HD01 | Gravity Forms email delivery depends on SMTP configuration (SWA-01) | SMTP plugin + email service | Form specs (G1) assume email works but don't mandate SMTP |
| HD02 | Complianz cookie consent depends on GA4 consent mode v2 | GA4 + GTM + Complianz all configured correctly | GTM consent signals assumed working without integration test spec |
| HD03 | WooCommerce payment gateway (Mollie) requires webhook configuration | `helderduidelijkschoon.nl` accessible from internet, Mollie dashboard configured | Payment gateway spec (G2.2) does not mention webhook URL or testing |
| HD04 | Relevanssi search plugin indexes custom fields | ACF/CMB2 custom fields must be configured in Relevanssi settings | Search spec does not mention custom field indexing |
| HD05 | Cloudflare CDN full-page caching may break WooCommerce cart/checkout | Cloudflare must be configured to bypass cache for `/winkelmand/`, `/afrekenen/`, `/mijn-account/`, and WooCommerce AJAX endpoints | Neither CDN spec nor WooCommerce spec mentions this |

### 2.5 Circular Dependencies

No circular dependencies detected. The dependency map in RS-06 Section 38 is linear and well-structured.

### 2.6 Missing Acceptance Criteria

| # | Missing AC | For Requirement | Recommendation |
|---|---|---|---|
| MAC01 | Email deliverability verified (not just form submit) | Forms (G1), Contact form (F01) | Add: AC-EMAIL: Test email delivered to info@ within 2 minutes. Test email not in spam folder. |
| MAC02 | Backup restore verified before old site taken offline | Migration (MIG-01) | Add: AC-BACKUP: Old site backup restored to test environment and verified before old site takedown. |
| MAC03 | DNS TTL lowered before launch | Migration (MIG-03) | Add: AC-DNS: TTL verified at 300 seconds via whatsmydns.net before launch. |
| MAC04 | WooCommerce payment gateway webhook verified | WooCommerce (G2.2) | Add: AC-PAYMENT: Test webhook delivery: payment status updated in WooCommerce order after Mollie test payment. |
| MAC05 | Cloudflare cache exclusion for WooCommerce pages | Performance + WooCommerce | Add: AC-CDNWC: Cart, checkout, and account pages not cached by Cloudflare. Verified via response headers. |
| MAC06 | Database collation and special characters verified | Content migration (MIG-02) | Add: AC-CHARSET: All Dutch diacritics (ë, ï, é, ó, ö, ü) render correctly on every page. |
| MAC07 | Cookie consent banner does not block reCAPTCHA badge | Accessibility (A11Y-02) | Add: AC-RECAPTCHA: reCAPTCHA badge visible and not obscured by cookie banner or floating elements. |

### 2.7 Missing Edge Cases

| # | Edge Case | Context |
|---|---|---|
| EC01 | What happens if a user submits the contact form but the SMTP server is down? | Gravity Forms will store the entry but the email may not send. No retry mechanism defined. |
| EC02 | What happens if the offerte form file upload exceeds the PHP `upload_max_filesize` or `post_max_size`? | Error handling defined for file size (5MB) but file upload may fail silently if PHP limits are lower. Need server-side configuration check. |
| EC03 | What happens if a product in the cart goes out of stock before checkout completes? | WooCommerce handles this natively (error message on checkout), but UX specification doesn't describe the behavior. |
| EC04 | What happens if a blog post slug conflicts with an existing page slug? | WordPress prevents this in the admin, but during content migration or programmatic creation, slug conflicts could occur. No conflict resolution strategy defined. |
| EC05 | What if the client has zero testimonials at launch? | Testimonial block renders empty. Empty state not defined (UX-02). |
| EC06 | What if Google reCAPTCHA v3 scores the user too low and blocks legitimate form submission? | No fallback contact method in the form error message. User cannot submit. |
| EC07 | What if the Cloudflare CDN cache serves stale content after a WordPress update? | WP Rocket + Cloudflare integration should handle cache purging, but edge case if the integration fails. |
| EC08 | What if the client's email server rejects the SMTP relay from the hosting provider? | Email deliverability requires SPF record including the hosting/SMTP server. Not mentioned in DNS setup. |

### 2.8 Missing Business Rules

The business rules from the original Business Requirements document (SRC-03 Section 13) are preserved in the rebuild spec. The following are NOT documented as technical requirements:

| # | Business Rule | Should Be Implemented As |
|---|---|---|
| BR01 | "Elke opdracht begint bij het opmeten en analyseren" (Every project begins with measurement and analysis) | Add to Quote Request form: auto-response email mentions this intake step. Manage expectations. |
| BR02 | "Check-in/check-out protocol mandatory" | Could be mentioned on service pages as a quality signal. Not a technical requirement. |
| BR03 | "Management on-site during new project startup" | Already in content. No technical requirement. |
| BR04 | "Complaints resolved immediately" | Could add a "Klacht of opmerking" (Complaint) subject in the contact form dropdown. Already implemented. |

### 2.9 Missing Validation Rules

| # | Validation Rule | Context |
|---|---|---|
| VR01 | Dutch phone number format validation | Contact form "Telefoonnummer" field: should accept +31, 06-, and local formats. Gravity Forms can validate with regex. Regex not specified. |
| VR02 | Dutch postal code format validation | Quote form "Postcode / Plaats": should validate Dutch postcode format (NNNN AA). Not specified. |
| VR03 | Email domain validation (typo detection) | Contact form: should detect common email typos (gmial.com -> gmail.com). Gravity Forms does not do this natively. |
| VR04 | File upload MIME type validation (server-side) | Forms spec says "validate file type server-side" but does not list allowed MIME types for PDF, JPG, PNG, DOCX. |

### 2.10 Missing Error Handling

| # | Error Scenario | Context |
|---|---|---|
| EH01 | SMTP email delivery failure | Neither form spec nor error handling (Section 33) addresses MX failure. Gravity Forms stores entry but admin may not know email was undelivered. |
| EH02 | reCAPTCHA v3 failure (user blocked) | Error handling spec says "Silent failure" for spam, but a legitimate user blocked by reCAPTCHA has no alternative. Need: "Als het formulier niet verzonden kan worden, bel ons op 0164-652846." |
| EH03 | Payment gateway timeout during WooCommerce checkout | WooCommerce error handling is defined generically. Mollie-specific timeout handling not specified. |
| EH04 | 503 error (maintenance mode) | Spec defines 404 and 500 error pages, but not 503 (maintenance mode). WordPress has a built-in maintenance mode during updates. Custom 503 page not specified. |

### 2.11 Missing Migration Scenarios

| # | Scenario | Context |
|---|---|---|
| MS01 | Old site has additional pages or posts not discovered in the initial crawl | The initial crawl found 15 pages + 2 blog posts. If additional content exists (drafts, private pages, custom post types from old plugins), it may not be migrated. |
| MS02 | Legacy domain `hds-onderhoudsdiensten.nl` expires before migration is complete | PDFs become inaccessible. Terms & Conditions unavailable. Legal risk. |
| MS03 | Old hosting account is suspended or inaccessible before migration is complete | Cannot access old site for content extraction. Migration blocked. |
| MS04 | Email service (info@helderduidelijkschoon.nl) is hosted on a different server and unaffected by website migration | Assumed in the spec but not verified. DNS MX records must be documented and preserved. |

### 2.12 Missing SEO Scenarios

| # | Scenario | Context |
|---|---|---|
| SS01 | Google has indexed the old site's broken pages (404, 500) | These pages return error codes and will be replaced. Google needs to recrawl and see 200 or 301. Monitoring should verify Google drops error URLs and indexes new pages. |
| SS02 | Backlinks from external sites point to broken old URLs (e.g., /?page_id=318) | Redirect strategy handles this. But backlink audit should identify high-value backlinks and ensure redirects preserve link equity. |
| SS03 | Competitor analysis reveals keywords the current site cannot target due to missing content | The spec adds content for all services, but keyword research (SEO-01) is needed to identify content gaps vs competitors. |

### 2.13 Missing Accessibility Scenarios

| # | Scenario | Context |
|---|---|---|
| AS01 | Screen reader user encounters WooCommerce dynamic cart update | A11Y-01 addresses checkout generally. Cart page dynamic updates (quantity change, remove item) must announce total price change via aria-live. |
| AS02 | Keyboard-only user navigates the mega-menu dropdown | Desktop dropdown on hover must also be operable on focus (keyboard). WCAG 2.1.1 requires all functionality available from keyboard. This is not specified. |
| AS03 | User with `prefers-reduced-motion` visits a page with animations | Spec says "Respect prefers-reduced-motion" but does not specify which animations exist. Carousel auto-scroll, hero parallax, hover transitions must all disable. |

---

## Part 3: Sprint 1 Completion Checklist — BLOCKING Issues

The following issues are **BLOCKING**. Sprint 2 development cannot safely begin until each is resolved.

| # | Issue ID | Issue | Action Required | Owner |
|---|---|---|---|---|
| B01 | SWA-02 | Theme selection is ambiguous (Custom vs GeneratePress vs Kadence) | Select one theme and document the decision in MPS-001. Recommendation: GeneratePress Pro + GenerateBlocks. | Solution Architect |
| B02 | CMS-01 | FSE compatibility claim vs PHP template approach conflict | Clarify architecture: Hybrid block theme with theme.json + PHP templates. Remove FSE label. | Solution Architect |
| B03 | CMS-02 | CPT slug `referenties` conflicts with existing Page `/referenties/` | Change `hds_testimonial` CPT: set `public => false`, `publicly_queryable => false`. Testimonials queried only via custom blocks on Referenties page. | Developer |
| B04 | SWA-01 | No SMTP / transactional email service specified | Select and mandate a transactional email service. Add to spec: Post SMTP with SendGrid/Mailgun/Amazon SES. Configure SPF/DKIM/DMARC. Add email deliverability acceptance criterion. | Developer + Client |
| B05 | MIG-01 | No rollback test of old site backup before migration | Add to migration checklist: Restore old site backup to test environment. Verify integrity. Only then proceed with old site takedown. | Developer |
| B06 | MIG-03 | DNS TTL lowering not in migration timeline | Add to migration checklist: 24h before launch, lower TTL to 300s. Verify propagation. 24h after launch, restore TTL. | Developer |
| B07 | HD05 | Cloudflare CDN may break WooCommerce cart/checkout | Add to setup: Cloudflare Page Rule to bypass cache for `/winkelmand/*`, `/afrekenen/*`, `/mijn-account/*`, and WooCommerce AJAX endpoints. | Developer |
| B08 | HD03 | WooCommerce payment gateway webhook not specified | Add to WC setup: Configure Mollie webhook URL. Test end-to-end payment flow including webhook delivery. | Developer |
| B09 | MAC01 | Missing email deliverability acceptance criterion | Add AC: test email delivered within 2 minutes. Not in spam. Email log in Post SMTP. | QA |
| B10 | MAC04 | Missing payment gateway webhook verification | Add AC: test webhook delivery verified. Order status updated correctly after payment. | QA |
| B11 | EC05 | Missing empty state for testimonial block | Define empty state: if no testimonials, hide the block entirely. Do not render empty carousel. | UX Designer |
| B12 | AS02 | Keyboard navigation for dropdown menu not specified | Specify: mega-menu dropdown must open on `:focus-within` or `Enter` key, not only on `:hover`. | Developer |

---

## Part 4: Architecture Readiness Score

### Score: 74 / 100 → UPDATED TO 86 / 100 (per ACR-001, July 2026)

**Status Update (July 2026):** All 12 blocking issues (B01-B12) originally identified in this review have been resolved by the Architecture Closure Report (ACR-001). See `docs/review/06_Architecture_Closure_Report.md` for detailed resolution log.

### Detailed Justification

| Category | Score | Max | Rationale |
|---|---|---|---|
| **Software Architecture** | 7 | 10 | Solid stack selection (WordPress + managed hosting + CDN). Points lost: theme ambiguity (SWA-02), missing SMTP spec (SWA-01), FSE confusion (CMS-01). |
| **CMS Architecture** | 7 | 10 | Good template hierarchy and CPT design. Points lost: CPT slug conflict (CMS-02), FAQ CPT display ambiguity (CMS-03). |
| **WordPress Best Practices** | 9 | 10 | Excellent: no page builder, disabled XML-RPC, 2FA, security hardening. Minor: WP-Cron not addressed (WBP-03). |
| **SEO Architecture** | 8 | 10 | Very thorough metadata, schema, redirects, internal linking. Points lost: no keyword research foundation (SEO-01). |
| **Information Architecture** | 8 | 10 | Clear hierarchy, flat URLs, well-defined page inventory. Points lost: service order undefined (IA-01), breadcrumb hierarchy ambiguity (IA-02). |
| **UX Architecture** | 6 | 10 | Good template and component specifications. Points lost: missing loading states (UX-01), empty states (UX-02), no print styles (UX-03). |
| **Accessibility** | 7 | 10 | Strong WCAG 2.2 AA target and testing protocol. Points lost: WooCommerce checkout not specifically addressed (A11Y-01), reCAPTCHA accessibility concerns (A11Y-02), dynamic content testing gap (A11Y-03). |
| **Performance** | 8 | 10 | Excellent budgets and implementation plan. Points lost: no regression testing strategy (PERF-01), mobile budget conditions not strict enough (PERF-03). |
| **Security** | 7 | 10 | Good hardening and access controls. Points lost: WAF rules not specified (SEC-01), no incident response procedure (SEC-02). |
| **Scalability** | 6 | 10 | Adequate for current needs. Points lost: no concurrent user projection (SCA-01), no database growth estimate (SCA-02). Acceptable for a local B2B site. |
| **Maintainability** | 7 | 10 | Good block-based architecture. Points lost: no plugin update testing procedure (MNT-01), no deprecated code detection (MNT-02). |
| **Content Management** | 7 | 10 | Good user roles and content structure. Points lost: no media library organization (CNT-01), no content freeze plan during migration (CNT-02). |
| **WooCommerce** | 7 | 10 | Solid configuration. Points lost: abandoned cart recovery not addressed (WC-02), inventory alerts not specified (WC-03), hidden Cloudflare dependency (HD05). |
| **Migration** | 5 | 10 | Good redirect strategy and sitemap handling. Points lost: CRITICAL — no rollback test (MIG-01), no DNS TTL procedure (MIG-03), no data integrity verification (MIG-02). This is the weakest area. |
| **Analytics** | 8 | 10 | Good event tracking and consent integration. Points lost: no custom dimensions (ANA-01), no dashboard template (ANA-02). |
| **QA** | 6 | 10 | Comprehensive manual checklists. Points lost: no automated regression testing (QA-01), no visual regression testing (QA-02). |

**Total: 118 / 160 = 74 / 100**

### Verdict

**CAN SPRINT 2 START SAFELY? — CONDITIONAL YES**

Sprint 2 can begin **AFTER** all 12 blocking issues (B01-B12) are resolved. The architecture is fundamentally sound: the technology stack is appropriate, the information architecture is well-structured, the template hierarchy is logical, and the SEO and performance foundations are strong. The 12 blocking issues are concentrated in three areas:

1. **Architecture Decisions Not Finalized (B01, B02, B03):** Theme selection, FSE approach, and CPT slug conflict must be resolved before any code is written.
2. **Operations Gaps (B04, B05, B06, B07, B08):** Email deliverability, backup verification, DNS TTL, CDN/WooCommerce conflict, and payment webhook must be addressed before launch-critical functionality is built.
3. **Specification Gaps (B09, B10, B11, B12):** Missing acceptance criteria, empty states, and accessibility details must be added to the specification before development of those features.

The non-blocking issues (33 issues at P1-P3) can be addressed during their respective Sprints. None prevent Sprint 2 from starting.

**Recommended Actions:**
1. Schedule a 2-hour architecture resolution meeting with all stakeholders to resolve B01-B03.
2. Developer to produce an infrastructure setup runbook addressing B04-B08 before Sprint 2.
3. Product/UX to add missing specifications (B09-B12) to MPS-001 before Sprint 2.
4. Assign each non-blocking issue to the appropriate Sprint in the Development Roadmap.

**Estimated Time to Resolve Blockers:** 2-3 days of focused work.
**Revised Sprint 2 Start:** After all B01-B12 are verified resolved.
