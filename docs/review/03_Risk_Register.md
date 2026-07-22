# HDS Onderhoudsdiensten — Risk Register

**Document ID:** RR-001 | **Version:** 1.0.0 | **Date:** July 2026
**Reviewer:** Senior Solution Architect
**Input:** FAR-001, GAP-001, ARR-001, PVR-001, MPS-001 §I3, RS-08

---

## 1. Executive Summary

This Risk Register catalogues every identified risk to the HDS Onderhoudsdiensten platform rebuild. Each risk includes severity, likelihood, impact, mitigation strategy, owner, and whether it blocks development. Risks are categorized by domain and prioritized by severity × likelihood.

**Total Risks: 28**

| Priority | Count | Description |
|---|---|---|
| **P0 — CRITICAL** | 6 | Immediate resolution required. Blocks development. |
| **P1 — HIGH** | 12 | Must resolve before affected sprint. Mitigation defined. |
| **P2 — MEDIUM** | 8 | Mitigation during respective sprint. Monitor. |
| **P3 — LOW** | 2 | Accept or monitor. No immediate action required. |

---

## 2. Risk Register

### 2.1 Strategic & Business Risks

| ID | Risk | Severity | Likelihood | Impact | Mitigation | Priority | Owner | Blocking? |
|---|---|---|---|---|---|---|---|---|
| R-S01 | **Client rejects the rebuild.** The project has produced ~18,500 lines of specification without confirmed client commitment (ASM01). If the client prefers to repair the existing site instead of rebuilding, all Sprint 1-3 work is discarded. | **CRITICAL** | Medium | Complete project cancellation. All 3 sprints wasted. | Conduct client workshop IMMEDIATELY. Confirm rebuild decision. Secure budget sign-off. Do not proceed with Sprint 2 without written confirmation. | P0 | Client + Solution Architect | **BLOCKING** |
| R-S02 | **Client provides incorrect or incomplete business information.** MI-01 through MI-05 (address, KVK, BTW, hours, service area) are critical for legal compliance and SEO. If the client provides wrong information, the site is non-compliant and must be corrected post-launch. | **HIGH** | Medium | Legal non-compliance. NAP inconsistency. Trust deficit. Rework required. | Verify all client-provided information against KVK register, BTW validation service, and physical address verification. Cross-check NAP with Google Business Profile. | P1 | Client + Developer | Non-blocking |
| R-S03 | **Airfixr product line decision reversed post-launch.** If the client keeps the webshop now but removes it in 3 months, Sprint 4 (WooCommerce) effort is partially wasted. If the client removes it now but wants it back in 3 months, it must be rebuilt. | **MEDIUM** | Medium | Wasted development effort (~1 sprint). OR Additional unplanned sprint to rebuild. | Document the client's decision in writing (email or signed document). Include a note in the Beheergids: "To add WooCommerce in the future, contact developer for scoping." | P2 | Client | Non-blocking |
| R-S04 | **Budget exceeded due to scope creep.** The specification is comprehensive but new requirements may emerge during development (e.g., client requests online booking system during Sprint 2). | **HIGH** | Medium | Project overruns. Incomplete delivery. Client-developer tension. | Fixed-scope specification. All change requests handled as separate scope with separate budget estimate. Document change request process in project charter. | P1 | Project Manager | Non-blocking |

### 2.2 Technical & Architecture Risks

| ID | Risk | Severity | Likelihood | Impact | Mitigation | Priority | Owner | Blocking? |
|---|---|---|---|---|---|---|---|---|
| R-T01 | **SMTP email delivery fails silently.** Email service (SendGrid/Mailgun/SES) is configured but SPF/DKIM/DMARC DNS records are incorrect. Contact form submissions, quote requests, and WooCommerce order notifications are not delivered. The client receives no email. No one knows because there is no monitoring. | **CRITICAL** | Medium | Zero web-originated leads captured. The primary failure mode of the current site (broken contact page) is repeated in a different form. | Procure SMTP service before Sprint 2. Configure SPF/DKIM/DMARC. Test: send email from Contact form, verify inbox delivery within 2 minutes. Enable Post SMTP email logging. Monitor: weekly deliverability test. Alert: if email failure rate > 5%. | P0 | Developer | **BLOCKING** |
| R-T02 | **WooCommerce checkout fails at launch.** Mollie payment gateway is not configured correctly. Webhook URL is blocked by Cloudflare WAF. OR Mollie test mode is active on production. OR API keys are swapped. | **HIGH** | Medium | Customers cannot complete purchases. Revenue loss. Client dissatisfaction. | If Airfixr kept: configure Mollie in test mode on staging. Perform end-to-end test purchase. Verify webhook delivery (order status updates). Switch to live mode on production. Verify Cloudflare WAF allows Mollie webhook. Add to pre-launch checklist. | P1 | Developer | Non-blocking |
| R-T03 | **Cloudflare caches WooCommerce cart/checkout pages.** Page rules to bypass cache for `/winkelmand/*`, `/afrekenen/*`, `/mijn-account/*` are not configured or are misconfigured. Users see stale cart data or other users' session data. | **HIGH** | Medium | Broken shopping experience. Potential data privacy issue (cached cart data visible to other users). | Configure Cloudflare page rules before Sprint 4. Verify: `CF-Cache-Status: BYPASS` header on all WC dynamic pages. Test: add item to cart, navigate away, return — cart item still present. | P1 | Developer | Non-blocking |
| R-T04 | **Plugin conflict post-launch.** A plugin update (WooCommerce, Rank Math, Complianz, Wordfence) introduces a conflict with another plugin or with the custom theme. Functionality breaks silently. | **HIGH** | Low | Feature breakage. Form submissions fail. Checkout breaks. SEO metadata drops. | Identical staging stack. Full smoke test on staging before every plugin/theme update. Enable auto-updates for minor/patch releases only. Major releases tested on staging first. Keep a pre-update backup for rollback. | P1 | Developer | Non-blocking |
| R-T05 | **Performance degrades below PSI 90 after launch.** A plugin update, new content with unoptimized images, or configuration change causes Core Web Vitals to degrade. | **MEDIUM** | Medium | SEO ranking penalty. Poor user experience. Missed performance targets. | Weekly PSI automated checks. Alert if PSI mobile drops below 90. Performance test on staging before every major plugin update. Image optimization enforced at upload (ShortPixel/Imagify). | P2 | Developer | Non-blocking |
| R-T06 | **WordPress core major update breaks custom theme.** WordPress 6.8 or 7.0 introduces breaking changes to block editor APIs, theme.json schema, or PHP template functions. | **MEDIUM** | Low | Site partially broken. Templates not rendering correctly. Admin functionality impaired. | Test major WordPress updates on staging before production. Review WordPress Field Guide before updating. Maintain theme code to WordPress coding standards. | P2 | Developer | Non-blocking |
| R-T07 | **Relevanssi search index becomes corrupted or incomplete.** New pages/posts are not indexed. Search returns no results for existing content. | **LOW** | Low | Users cannot find content. Frustrated visitors. | Relevanssi auto-indexes on content save. Manual index rebuild available in Relevanssi settings. Monitor: test search for "glasbewassing" weekly, verify correct page appears as first result. | P3 | Developer | Non-blocking |

### 2.3 Migration & Launch Risks

| ID | Risk | Severity | Likelihood | Impact | Mitigation | Priority | Owner | Blocking? |
|---|---|---|---|---|---|---|---|---|
| R-M01 | **Data loss during migration.** Old site backup is corrupted. Old hosting account is suspended before backup is taken. WooCommerce orders are not exported. PDFs on legacy domain expire. | **CRITICAL** | Low | Total or partial site loss. Permanent loss of financial records (WC orders). Legal documents (PDFs) inaccessible. | Full backup of old site before any migration step. Test restore backup to verify. Offsite storage in 2 locations. Export WooCommerce data separately. Download all PDFs from legacy domain now. | P0 | Developer | **BLOCKING** |
| R-M02 | **DNS propagation causes inconsistent user experience.** DNS TTL is not lowered before launch. Users see old site (with broken pages) for 24-48 hours while DNS propagates. Form submissions go to old broken contact page. | **HIGH** | High | 24-48 hours of inconsistent experience. Lost leads. User confusion. | Lower DNS TTL to 300s 24 hours before launch. Verify propagation via whatsmydns.net. Only proceed with launch when TTL change is confirmed propagated. Add to pre-launch checklist as step 0. | P1 | Developer | **BLOCKING** (was B06 in ARR) |
| R-M03 | **Temporary search ranking drop post-migration.** Even with perfect 301 redirects and schema, Google may temporarily drop rankings while it re-indexes the new site structure. | **HIGH** | Medium | Reduced organic traffic for 2-4 weeks. Lead volume decrease during stabilization period. | URL preservation for all working pages. 301 redirects with zero chains. Sitemap submitted to GSC immediately. Daily GSC monitoring for 30 days. Client communication: expect temporary ranking fluctuation, normalization within 2-4 weeks. | P1 | Developer + Client | Non-blocking |
| R-M04 | **Email delivery interrupted by DNS changes.** MX records for `info@helderduidelijkschoon.nl` are accidentally modified during DNS cutover. Email stops working during the critical launch period. | **HIGH** | Low | Lost form submissions. Lost WooCommerce order notifications. Client unaware. | Document current MX records before any DNS changes. Verify MX records unchanged after DNS cutover. Test email delivery to info@ within 1 hour of launch. | P1 | Developer | Non-blocking |
| R-M05 | **Legacy domain `hds-onderhoudsdiensten.nl` expires.** PDFs for Terms & Conditions become inaccessible. If redirects are not configured, users clicking old links hit dead ends. | **MEDIUM** | Medium | Legal documents unavailable. Legal risk. Broken user experience for visitors with old links. | Download all PDFs now. Verify domain expiry date with client. Decision: keep and redirect, or migrate content and retire. Implement 301 redirects from legacy domain PDF URLs to new domain PDF URLs. | P2 | Client + Developer | Non-blocking |
| R-M06 | **Old hosting account becomes inaccessible before migration is complete.** Client loses access to old hosting. OR old hosting provider suspends account at an unexpected time. | **MEDIUM** | Low | Cannot extract content, media, or WooCommerce data from old site. Migration blocked. | Take full backup immediately. Export all content (XML export). Export WooCommerce data. Download all media. Store offsite. Do not rely on continuous access to old hosting. | P2 | Developer | Non-blocking |
| R-M07 | **Form spam attack post-launch.** The new site's contact and quote forms are targeted by spam bots. reCAPTCHA v3 + honeypot block most, but some spam passes through. Client receives dozens of spam emails daily. | **MEDIUM** | Low | Client overwhelmed with spam. Legitimate leads lost in spam. Time wasted filtering. | reCAPTCHA v3 score threshold tuned (increase sensitivity). Honeypot catches simple bots. Monitor spam rate weekly. If >10% spam, add additional anti-spam measures (Akismet for forms, Cloudflare Turnstile as alternative). | P2 | Developer | Non-blocking |

### 2.4 Security & Compliance Risks

| ID | Risk | Severity | Likelihood | Impact | Mitigation | Priority | Owner | Blocking? |
|---|---|---|---|---|---|---|---|---|
| R-SEC01 | **Security breach via plugin vulnerability.** An outdated or vulnerable plugin is exploited. Malware injected. Site defaced. Customer data exposed. | **CRITICAL** | Low | Data loss. Reputation damage. GDPR fine (up to 4% annual turnover or €20M). Client business disruption. | Wordfence Premium: daily malware scan + real-time threat defense. Auto-updates for minor/patch releases. Monthly plugin update cycle. 2FA on all admin accounts. Custom login URL. Security incident response runbook. | P0 | Developer | Non-blocking |
| R-SEC02 | **Launch without legal review of privacy policy.** The privacyverklaring is drafted but not reviewed by a qualified Dutch privacy lawyer (MI-17). The page is published but contains legal errors or omissions. | **CRITICAL** | Medium | GDPR/AVG non-compliance. Fine risk up to €20M. Cannot launch. | Engage Dutch privacy lawyer in Sprint 0. Draft privacyverklaring by Sprint 3. Lawyer review completed by Sprint 6. Do not launch without legal sign-off (LR12). | P0 | Client | **BLOCKING** |
| R-SEC03 | **Cookie consent is non-compliant.** Complianz is misconfigured. Non-functional cookies load before consent. Consent is not logged. Cookiebeleid page has errors. | **HIGH** | Low | GDPR/AVG non-compliance. ePrivacy Directive violation. Fine risk. | Configure Complianz with Dutch market settings. Test: DevTools → Application → Cookies → zero GA4/Facebook cookies before consent. Verify consent logging. Cookiebeleid reviewed before launch. | P1 | Developer | Non-blocking |
| R-SEC04 | **XML-RPC not disabled at server level.** The spec says XML-RPC must return 403. If it's only disabled via WordPress plugin and the plugin is deactivated, XML-RPC becomes accessible. | **MEDIUM** | Low | Brute-force attack vector reopens. | Disable XML-RPC at server level (Nginx deny or .htaccess). Verify: `curl -I https://helderduidelijkschoon.nl/xmlrpc.php` returns HTTP 403. Defense in depth: Wordfence also blocks xmlrpc.php. | P2 | Developer | Non-blocking |

### 2.5 Client & Stakeholder Risks

| ID | Risk | Severity | Likelihood | Impact | Mitigation | Priority | Owner | Blocking? |
|---|---|---|---|---|---|---|---|---|
| R-C01 | **Client delays providing required information.** MI-01 through MI-25 have been pending through 3 sprints. If the client does not provide information by respective phase deadlines, development is blocked. | **CRITICAL** | High | Sprint 2-3 blocked. Pages incomplete. Schema incomplete. Launch delayed. | Phase 0 client workshop with hard deadline. Parallel work where possible (content writing can proceed with placeholder company info). Empty states implemented for all conditional content. Default values used where acceptable (phone/email confirmed from old site). | P0 | Client + Project Manager | **BLOCKING** |
| R-C02 | **Client lacks technical capability to manage the site post-launch.** Despite the Beheergids and training, the client struggles with the Block Editor, Gravity Forms, or WooCommerce admin. Site content becomes stale. | **MEDIUM** | Medium | Content not updated. Blog not published. Vacancies not posted. Site becomes outdated (like the current site). | Comprehensive Beheergids (written, Dutch, with screenshots). 1-hour training session (Sprint 8). Offer post-launch support retainer (monthly maintenance + content updates). Video tutorials for common tasks. | P2 | Developer | Non-blocking |

### 2.6 Documentation & Knowledge Risks

| ID | Risk | Severity | Likelihood | Impact | Mitigation | Priority | Owner | Blocking? |
|---|---|---|---|---|---|---|---|---|
| R-D01 | **Document drift causes implementation errors.** 22 documents with overlapping content. When one document is updated, others become stale. Developer follows an outdated document and implements the wrong solution. | **HIGH** | Medium | Incorrect implementation. Rework required. Inconsistent system. | Define document authority chain (see GAP-001 G-D01). Mark outdated documents as "Superseded by [newer doc]." Use document IDs (FAR-001, GAP-001) not filenames in cross-references. | P1 | Solution Architect | Non-blocking |
| R-D02 | **Key person dependency.** The Solution Architect and Lead Developer are the only people who understand the full architecture. If either becomes unavailable, development halts. | **MEDIUM** | Low | Development blocked. Knowledge loss. | Comprehensive documentation (already exists — 22 docs, ~18,500 lines). Cross-train a second developer. Document architectural decisions with rationale (ADR) so decisions can be revisited. | P2 | Project Manager | Non-blocking |

### 2.7 External Dependency Risks

| ID | Risk | Severity | Likelihood | Impact | Mitigation | Priority | Owner | Blocking? |
|---|---|---|---|---|---|---|---|---|
| R-E01 | **Third-party service outage.** Mollie (payments), SendGrid/Mailgun (email), Cloudflare (CDN/WAF), UptimeRobot (monitoring) experience downtime. | **MEDIUM** | Low | Payment processing fails. Email delivery fails. CDN/WAF unavailable (site still serves from origin). Monitoring gaps. | Cloudflare: site serves from origin if CDN is down. Email: Post SMTP queues and retries. Payments: Bank Transfer (BACS) as fallback. UptimeRobot: secondary monitoring via hosting provider. | P2 | Developer | Non-blocking |
| R-E02 | **Google API changes affect reCAPTCHA, GA4, or GSC.** Google deprecates an API version or changes pricing. reCAPTCHA v3 becomes paid. GA4 changes event schema. | **LOW** | Low | Forms may require alternative CAPTCHA. Analytics may need reconfiguration. | reCAPTCHA: honeypot fallback catches basic spam even without reCAPTCHA. GA4: GTM enables switching analytics providers without code changes. | P3 | Developer | Non-blocking |

---

## 3. Risk Matrix (Severity × Likelihood)

| | Low Likelihood | Medium Likelihood | High Likelihood |
|---|---|---|---|
| **CRITICAL** | R-M01 (data loss), R-SEC01 (security breach) | R-S01 (client rejection), R-SEC02 (no legal review), R-T01 (SMTP failure), R-C01 (client delays) | — |
| **HIGH** | R-T04 (plugin conflict), R-M04 (email interrupted), R-SEC03 (cookie non-compliance) | R-S02 (wrong business info), R-T02 (WC checkout fails), R-T03 (Cloudflare WC cache), R-M03 (ranking drop), R-D01 (document drift) | R-M02 (DNS propagation) |
| **MEDIUM** | R-T06 (WP update breaks theme), R-M06 (hosting inaccessible), R-M07 (form spam), R-SEC04 (XML-RPC not disabled), R-C02 (client capability), R-D02 (key person), R-E01 (third-party outage) | R-S03 (Airfixr reversed), R-T05 (performance degradation), R-M05 (legacy domain expires) | — |
| **LOW** | R-T07 (Relevanssi corruption), R-E02 (Google API changes) | — | — |

---

## 4. Risk Mitigation Timeline

| Sprint | Risks to Mitigate |
|---|---|
| **Sprint 0 (Immediate)** | R-S01 (client confirmation), R-SEC02 (legal counsel engaged), R-C01 (client information gathered), R-M01 (old site backup taken) |
| **Sprint 1** | R-T01 (SMTP procured), R-M06 (full backup + export), R-S02 (client info verified) |
| **Sprint 2** | R-M02 (DNS TTL procedure), R-D01 (document authority chain) |
| **Sprint 4** | R-T02 (Mollie configured), R-T03 (Cloudflare WC bypass), R-S03 (Airfixr decision documented) |
| **Sprint 5** | R-SEC04 (XML-RPC verified), R-M05 (legacy domain PDFs migrated) |
| **Sprint 6** | R-SEC03 (cookie consent verified), R-T06 (WP update tested) |
| **Sprint 7** | R-T04 (plugin conflict testing), R-T05 (performance baseline), R-M07 (spam monitoring) |
| **Sprint 8** | R-M03 (ranking monitoring), R-M04 (email verification), R-C02 (client training) |
| **Post-Launch** | R-T07 (Relevanssi monitoring), R-E01 (third-party monitoring), R-E02 (Google API changes), R-D02 (cross-training) |

---

## 5. Risk Appetite Statement

The following risks are **accepted** (no additional mitigation beyond documented strategy):

| ID | Risk | Rationale for Acceptance |
|---|---|---|
| R-T07 | Relevanssi search index corruption | Low impact. Search is a secondary feature for a 32-page site. Manual rebuild is trivial. |
| R-E02 | Google API changes | Low likelihood. Google provides migration paths. |
| R-S03 | Airfixr decision reversed | Covered by documenting client decision in writing. |

All other risks require active mitigation as documented in this register.

---

## 6. Contingency Reserve

| Reserve | Amount | For |
|---|---|---|
| **Schedule Contingency** | 2 weeks (1 sprint) | Client delays, unexpected technical issues, rework from document drift |
| **Budget Contingency** | 15% of total budget | Unplanned plugin licenses, additional content writing, security remediation |
| **Technical Contingency** | 1 sprint buffer before launch (Sprint 7-8 overlap) | Launch delay without compromising quality |

---

*End of Risk Register — RR-001 v1.0.0*
