# Part 8: Launch Readiness, Risks, Assumptions, Open Questions, Future Scalability

**HDS Onderhoudsdiensten — Production Build Specification — Part 8 of 8**

---

## 46. Launch Readiness Report

### 46.1 Launch Readiness Criteria

| # | Criterion | Go / No-Go |
|---|---|---|
| LR01 | All 32 pages published with final Dutch content | Go / No-Go |
| LR02 | All forms submit successfully and deliver emails | Go / No-Go |
| LR03 | WooCommerce purchase flow tested end-to-end | Go / No-Go |
| LR04 | All 301 redirects configured and tested | Go / No-Go |
| LR05 | Google PageSpeed Insights mobile >= 90 | Go / No-Go |
| LR06 | Lighthouse Accessibility = 100 on all templates | Go / No-Go |
| LR07 | axe DevTools: zero critical or serious issues | Go / No-Go |
| LR08 | All SEO metadata present and unique | Go / No-Go |
| LR09 | All structured data validated (Rich Results Test) | Go / No-Go |
| LR10 | XML Sitemap working (no 500 error) | Go / No-Go |
| LR11 | HTTPS enforced with HSTS | Go / No-Go |
| LR12 | Privacyverklaring published and legally reviewed | Go / No-Go |
| LR13 | Cookie consent banner and logging working | Go / No-Go |
| LR14 | KVK and BTW in footer (if provided) | Go / No-Go |
| LR15 | GA4 tracking active | Go / No-Go |
| LR16 | Google Search Console verified | Go / No-Go |
| LR17 | Daily backups configured and verified (test restore) | Go / No-Go |
| LR18 | Security hardening complete (XML-RPC disabled, 2FA, custom login) | Go / No-Go |
| LR19 | Zero broken internal links (Screaming Frog) | Go / No-Go |
| LR20 | Client approval received on staging | Go / No-Go |
| LR21 | All Phase 0 dependencies resolved (D01-D20) | Go / No-Go |
| LR22 | Staging environment noindex and password-protected | Go / No-Go |
| LR23 | Cross-browser testing passed (Chrome, Firefox, Safari, Edge) | Go / No-Go |
| LR24 | Mobile/tablet testing passed on real devices | Go / No-Go |
| LR25 | All content reviewed by native Dutch speaker | Go / No-Go |

**Launch Decision:** All criteria marked "Go" = Launch approved. Any "No-Go" = Launch delayed until resolved.

### 46.2 Launch Sign-Off

| Role | Name | Signature | Date |
|---|---|---|---|
| Developer / Technical Lead | | | |
| Client / Business Owner | | | |
| Content Reviewer (Dutch) | | | |
| Legal Reviewer (AVG) | | | |

### 46.3 Rollback Plan

If critical issues are discovered within 24 hours of launch:

1. Revert DNS to point to old site (if old site still available on old hosting)
2. OR restore old site backup from pre-launch archive
3. Communicate to client: issue identified, rollback in progress, ETA for re-launch
4. Document the issue and resolution
5. Fix issue on staging, re-test, re-attempt launch

**Rollback Time Objective:** < 2 hours from decision to old site operational.

---

## 47. Risks

### 47.1 Technical Risks

| Risk | Severity | Likelihood | Mitigation |
|---|---|---|---|
| **Data loss during migration** | CRITICAL | Low | Full backup before any migration step. Test restore verified. Offsite backup storage. |
| **DNS propagation delay** | MEDIUM | Medium | Lower TTL to 300 (5 min) 24 hours before launch. Monitor propagation via whatsmydns.net. |
| **Email delivery interruption** | CRITICAL | Low | Verify MX records documented and unchanged. Test form emails before and after launch. |
| **Performance degradation after launch** | MEDIUM | Medium | Pre-launch performance benchmarks. Post-launch monitoring. WP Rocket + CDN already configured. |
| **Plugin conflict discovered after launch** | MEDIUM | Low | All plugins tested together on staging. Staging is identical stack to production. |
| **Security breach post-launch** | HIGH | Low | Wordfence active. 2FA on all accounts. XML-RPC disabled. Automatic updates enabled. Daily malware scans. |
| **Backup failure** | HIGH | Low | Daily backup verification. Monthly test restore. Alert on backup failure. |
| **Hosting outage** | MEDIUM | Low | Managed host with 99.9%+ SLA. UptimeRobot monitoring. Client has hosting support number. |
| **Domain expiry** | CRITICAL | Low | Auto-renew enabled. Client reminded. Developer monitors expiry date. |

### 47.2 Content Risks

| Risk | Severity | Likelihood | Mitigation |
|---|---|---|---|
| **Client delays providing content** | HIGH | High | Phase 0 dependencies clearly communicated. Parallel work possible: developer builds pages with placeholder content while client provides final text. |
| **Legal review delays privacy policy** | CRITICAL | Medium | Legal pages drafted early (Phase 3). Client engages legal counsel in Phase 0. |
| **Client logos not available (permissions)** | MEDIUM | High | Referenties page launched with testimonials only. Logos added incrementally as permissions obtained. |
| **Project photos not available** | MEDIUM | High | Use approved stock photography as fallback. Plan for real photo shoot in post-launch phase. |
| **Testimonials not collected** | MEDIUM | High | Launch testimonial section with "Write een review" invitation. Collect and add incrementally. |
| **WooCommerce product data incomplete** | LOW | Medium | Current 14 products have basic data. Migrate what exists. Missing prices/shipping added before shop launch. |

### 47.3 Business Risks

| Risk | Severity | Likelihood | Mitigation |
|---|---|---|---|
| **Temporary traffic drop after migration** | MEDIUM | Medium | All URLs preserved or 301 redirected. Sitemap submitted immediately. GSC monitored daily. Traffic expected to normalize within 2-4 weeks. |
| **Ranking drop after migration** | HIGH | Medium | Content improved (thicker, better structured). On-page SEO dramatically better. Structured data added. Rankings expected to improve medium-term; short-term fluctuations possible. |
| **Client budget exceeded** | HIGH | Medium | Fixed-scope specification. Phase-based approach with clear deliverables. Change requests handled as separate scope. |
| **Stakeholder disagreement on design** | MEDIUM | Medium | Design direction approved in Phase 0 before development. Design tokens locked early. |
| **Third-party developer (Pi-Apps) interference** | LOW | Unknown | Full rebuild on new hosting. No dependency on old developer. Ensure domain access is controlled by client. |
| **Airfixr product line irrelevance to brand** | LOW | Medium | Add Luchtreiniging landing page explaining connection. If client prefers, shop can be removed entirely. Decision needed in Phase 0. |

### 47.4 Legal and Compliance Risks

| Risk | Severity | Likelihood | Mitigation |
|---|---|---|---|
| **GDPR/AVG non-compliance** | CRITICAL | Low (if spec followed) | Privacyverklaring, cookie consent, form consent checkboxes all implemented before launch. Legal review required. |
| **KVK/BTW display non-compliance** | MEDIUM | Medium | Client provides numbers. Displayed on every page footer. If client cannot/will not provide, document the refusal and risk. |
| **Cookie consent legal challenge** | MEDIUM | Low | Dutch-specific Complianz configuration. Consent logging. Legal review of cookiebeleid. |
| **Data breach** | HIGH | Low | Security hardening per Section 19. Regular updates. Monitoring. Breach notification process documented. |

---

## 48. Assumptions

The following assumptions underpin this specification. If any assumption is false, the specification must be revised.

| # | Assumption | Impact If Wrong |
|---|---|---|
| ASM01 | **The client wants a complete rebuild**, not a repair of the existing site. | If repair is preferred, this entire specification is discarded. Only P0 items from Improvement Suggestions apply. |
| ASM02 | **The client has or can obtain access** to domain registrar, hosting (current or new), WordPress admin, and Google accounts. | Development cannot proceed without these. |
| ASM03 | **WordPress is the preferred CMS.** No requirement for a different platform (e.g., static site, Craft CMS, custom). | If a different CMS is required, the CMS Architecture (Section 28) and component strategy must be rewritten. |
| ASM04 | **A modern block theme (FSE or GeneratePress/Kadence) is acceptable.** Client does not require Divi or another page builder. | If a page builder is required, this specification conflicts with performance and maintainability goals. |
| ASM05 | **The client will provide missing business information** (address, KVK, BTW, business hours, service area) before Phase 2. | Legal pages cannot be completed. Schema cannot be completed. Trust signals absent from footer. |
| ASM06 | **The client will provide content** (photos, testimonials, client logos, vacancy text, terms text) or approve alternatives (stock photography, placeholder content). | Pages will launch with minimal content or be omitted until content is available. |
| ASM07 | **The client will engage legal counsel** to review the privacyverklaring, cookiebeleid, and algemene voorwaarden. | Legal non-compliance risk. Developer drafts are templates, not legal advice. |
| ASM08 | **The client wants to keep the WooCommerce webshop** for Airfixr product sales. | If shop is to be removed, Sections 23 (conversion tracking), 24 (forms), 31 (WooCommerce), and relevant templates are omitted. |
| ASM09 | **The client serves only the Dutch market** in the West-Brabant/Zeeland region. No international expansion is planned. | If international or multi-language is planned, hreflang, translations, and multi-region schema must be added. |
| ASM10 | **A 8-9 week timeline is acceptable.** The dependency map assumes staggered phases with client review between each. | If faster delivery is required, phases must be compressed and parallelized, increasing risk. |
| ASM11 | **The current hosting can be replaced or upgraded.** The existing hosting stack (unknown provider, possibly outdated PHP) is not fit for purpose. | If client is locked into existing hosting, hosting requirements may need to be downgraded. |
| ASM12 | **Analytics tracking consent will be obtained** via the cookie consent banner. GA4 is configured for consent mode v2. | If cookieless/privacy-friendly analytics is preferred, GA4 is replaced with Plausible, Fathom, or similar. |
| ASM13 | **The client will maintain the site post-launch** with developer support available for technical issues. | If the client expects a fully managed service, a maintenance retainer agreement is needed (not covered in this spec). |

---

## 49. Open Questions — Missing Information Required Before Development

These questions cannot be answered from existing documentation. Each must be resolved before the corresponding development phase.

### 49.1 Company and Operations

| # | Question | Required For | Phase Deadline |
|---|---|---|---|
| Q01 | What is the official legal entity (eenmanszaak, BV, VOF)? | KVK/BTW display requirements, schema | Phase 0 |
| Q02 | What is the physical business address? | Footer, Contact page, LocalBusiness schema, GBP consistency, legal compliance | Phase 0 |
| Q03 | What are the KVK and BTW numbers? | Footer legal display, schema | Phase 0 |
| Q04 | What are the business hours? | Schema markup, Contact page | Phase 0 |
| Q05 | Which specific municipalities/postcodes do you serve? | Local SEO, service area schema, location landing pages | Phase 0 |
| Q06 | How many employees currently work at HDS? | About page content | Phase 3 |
| Q07 | What is the founding year? | About page content | Phase 3 |
| Q08 | What is the company history and origin story? | About page content | Phase 3 |
| Q09 | Is there a preferred brand name (shorter than "HDS Onderhoudsdiensten")? | Consistency across site | Phase 0 |
| Q10 | What are the top 3 business goals for the next 12 months? | Digital strategy alignment | Phase 0 |
| Q11 | Are you planning to expand services, geography, or headcount? | Technical scalability requirements | Phase 0 |

### 49.2 Digital and Marketing

| # | Question | Required For | Phase Deadline |
|---|---|---|---|
| Q12 | Is Pi-Apps (api-apps.nl) still the active developer? | Footer credit, handover | Phase 0 |
| Q13 | Do you have a Google Business Profile? Is it claimed and verified? | Local SEO foundation | Phase 0 |
| Q14 | What is the primary source of new clients — website, phone, referral, tender? | Digital investment priority | Phase 0 |
| Q15 | What is the business purpose of selling Airfixr products? | Whether WooCommerce stays, improves, or is removed | Phase 0 |
| Q16 | Do you collect and manage client testimonials? | Referenties page content | Phase 0 |
| Q17 | Is the Instagram account still active? | Fix or remove broken Instagram widget | Phase 0 |
| Q18 | Do you want to publish regular content (blog, news, tips)? | Blog infrastructure decision | Phase 5 |
| Q19 | Do you have a budget for call tracking? | Advanced conversion tracking | Phase 5 |
| Q20 | What is the primary contact preference for clients — phone, email, WhatsApp? | Contact page design, conversion tracking | Phase 0 |

### 49.3 Design and Brand

| # | Question | Required For | Phase Deadline |
|---|---|---|---|
| Q21 | What are the brand colors (primary, secondary, accent)? | Design system | Phase 0 |
| Q22 | Do you have a brand style guide or logo guidelines? | Design system | Phase 0 |
| Q23 | Is the current logo available as a vector file (SVG, AI, EPS)? | Header, footer, favicon, schema image | Phase 0 |
| Q24 | Can we feature client names and logos on the Referenties page? Written permission required. | Referenties page | Phase 3 |
| Q25 | Do you have before/after project photos we can use? | Service pages, Referenties, hero images | Phase 3 |
| Q26 | Do you have team photos? | Over HDS page | Phase 3 |
| Q27 | Do you have certification logos (OSB, VCA, Arbo)? | Kwaliteit & Veiligheid page, footer | Phase 3 |

### 49.4 Commercial

| # | Question | Required For | Phase Deadline |
|---|---|---|---|
| Q28 | Which payment methods do you want to accept for the webshop? (Mollie recommended) | WooCommerce payment gateway | Phase 0 |
| Q29 | What are the shipping costs and delivery policy for Airfixr products? | WooCommerce shipping configuration | Phase 0 |
| Q30 | Should prices continue to display excl. BTW, or switch to incl. BTW? | WooCommerce tax display | Phase 0 |
| Q31 | Do you offer free shipping above a certain order amount? | WooCommerce shipping | Phase 0 |
| Q32 | Are there any products that should NOT be sold online (inquiry only)? | WooCommerce catalog configuration | Phase 4 |

### 49.5 Legal and Compliance

| # | Question | Required For | Phase Deadline |
|---|---|---|---|
| Q33 | Who is the data protection officer or privacy contact person? | Privacyverklaring | Phase 3 |
| Q34 | Are there any third-party data processors beyond hosting and analytics? | Privacyverklaring, DPA requirements | Phase 3 |
| Q35 | What is the data retention policy for customer and inquiry data? | Privacyverklaring, form auto-delete | Phase 3 |
| Q36 | Is there an existing terms and conditions document (beyond the PDF on the legacy domain)? | Algemene Voorwaarden page | Phase 3 |

### 49.6 Hosting and Infrastructure

| # | Question | Required For | Phase Deadline |
|---|---|---|---|
| Q37 | Who is the current hosting provider? Do you have access? | Migration planning | Phase 0 |
| Q38 | What is the current PHP version on the hosting? | Compatibility check | Phase 0 |
| Q39 | Do you have a preference for a new hosting provider? (Kinsta, WP Engine, Cloud86.nl recommended) | Hosting setup | Phase 0 |
| Q40 | What is the monthly hosting budget? | Hosting selection | Phase 0 |
| Q41 | Do you have a Google Analytics account? If so, who has access? | Analytics setup | Phase 5 |
| Q42 | Do you have a Google Search Console account? If so, who has access? | GSC setup | Phase 5 |

---

## 50. Future Scalability Considerations

### 50.1 Near-Term (0-6 Months Post-Launch)

| Enhancement | Trigger | Impact |
|---|---|---|
| **Google Ads landing pages** | Client starts paid search campaigns | 1-3 new landing pages per campaign. Dedicated template. |
| **Location-specific service pages** | Client confirms served municipalities | 3-5 city pages (e.g., schoonmaakbedrijf-bergen-op-zoom). |
| **Case study / portfolio pages** | Client provides before/after photos and project details | New CPT or blog posts for project showcases. |
| **Newsletter integration** | Client wants email marketing | Mailchimp/MailerLite integration. Signup form in footer. |
| **WhatsApp Business integration** | Client wants WhatsApp as contact channel | Floating WhatsApp button on mobile. Tracking event. |
| **Online booking system** | Client wants self-service scheduling | Calendly/Bookly integration on Offerte Aanvragen page. |
| **Live chat** | Client wants instant customer contact | Tidio or similar chat widget. |
| **Additional blog content strategy** | Client commits to content marketing | 1-2 posts per month. Topic clusters around services. |

### 50.2 Medium-Term (6-18 Months Post-Launch)

| Enhancement | Trigger | Impact |
|---|---|---|
| **Client self-service portal** | Client manages 50+ active contracts | Custom WordPress user area: view schedule, report issues, download invoices. Significant development. |
| **Automated quoting engine** | High volume of standardized quote requests | Multi-step form with pricing logic. Integrates with CRM. |
| **Multilingual site (English)** | Client expands to international/English-speaking clients | WPML or Polylang. Hreflang tags. Translated content. |
| **Review aggregation** | Client has 20+ reviews across platforms | Aggregate Google, Facebook reviews into website. Review schema. |
| **Advanced analytics dashboard** | Client wants self-service reporting | Custom GA4 Looker Studio dashboard. Monthly automated reports. |
| **Job application tracking** | Active recruitment with many applicants | Application tracking system (ATS) integration or WordPress-based pipeline. |
| **Mobile app for cleaning staff** | 20+ field staff | Job scheduling, check-in/out, photo reporting. Significant development cost. |

### 50.3 Long-Term (18+ Months)

| Enhancement | Trigger | Impact |
|---|---|---|
| **Headless WordPress / API-driven** | Need for multi-platform delivery (app, kiosk, partner portal) | WordPress as headless CMS with React/Vue frontend. Complete architecture change. |
| **CRM integration** | Sales pipeline formalized | HubSpot, Salesforce, or Dutch CRM (Simplicate, Teamleader) integration with contact forms. |
| **IoT / sensor-based cleaning** | Adoption of smart cleaning technology | Data integration from IoT sensors for predictive cleaning schedules. |
| **Franchise / multi-location model** | Geographic expansion to multiple offices | Multi-location website architecture. Location-specific microsites. |
| **E-learning platform** | Internal staff training program | LMS integration for cleaning technique and safety certifications. |

### 50.4 Architecture Decisions That Support Future Scalability

| Decision | Scalability Benefit |
|---|---|
| **Block-based theme (no page builder lock-in)** | Content remains portable. Migration to any future theme or headless setup is straightforward. |
| **WordPress on managed hosting** | Vertical scaling (more resources) is trivial. Horizontal scaling (multi-server) supported by most managed hosts. |
| **Cloudflare CDN** | Handles traffic spikes without server load. Global edge caching ready for any geographic expansion. |
| **Custom post types for testimonials, vacancies, FAQ** | Each data type independently queryable. Can be exposed via REST API for headless/mobile app consumption. |
| **Gravity Forms** | Conditional logic and API integrations support complex workflows. Integrates with 40+ third-party services. |
| **Google Tag Manager** | New tracking/marketing tags added without developer intervention. Scales with marketing maturity. |
| **Git-based deployment** | Rollback, code review, and multi-developer workflows built in. Scales with development team. |
| **Schema via JSON-LD (not microdata)** | Clean separation of content and structured data. Easy to update schema types as new types become available. |
| **No hardcoded content** | All layouts via block editor. Non-technical staff can create landing pages, campaigns, and new content types without developer support. |

---

## Appendix A: Document Index

| Part | File | Sections |
|---|---|---|
| Part 1 | `01_Architecture_Sitemap.md` | 1-5: Executive Summary, Architecture, IA, Sitemap |
| Part 2 | `02_Navigation_URLs_Migration.md` | 6-11: Navigation, URLs, Redirects, Migration Strategies |
| Part 3 | `03_SEO_Metadata_Strategy.md` | 12-16: Metadata, Structured Data, Internal Linking, Technical SEO, Local SEO |
| Part 4 | `04_Performance_Accessibility_Security_GDPR.md` | 17-24: Performance, Accessibility, Security, GDPR, Cookies, Analytics, Forms |
| Part 5 | `05_Components_CMS_Templates.md` | 25-34: Components, Design System, CMS, Templates, Blocks, WooCommerce, Search, Errors, Logging |
| Part 6 | `06_Backup_Deployment_GapAnalysis.md` | 35-39: Backup, Deployment, Acceptance, Dependencies, Gap Analysis |
| Part 7 | `07_Checklists.md` | 40-45: Migration, Pre-Launch, Post-Launch, QA, SEO, Content Checklists |
| Part 8 | `08_Launch_Risks_Questions_Future.md` | 46-50: Launch Readiness, Risks, Assumptions, Open Questions, Future Scalability |

## Appendix B: Glossary

| Term | Definition |
|---|---|
| **AVG** | Algemene Verordening Gegevensbescherming — Dutch implementation of GDPR |
| **BTW** | Belasting over de Toegevoegde Waarde — Dutch VAT (21% standard rate) |
| **CTA** | Call to Action — button or link prompting user action |
| **CPT** | Custom Post Type — WordPress content type beyond pages/posts |
| **FSE** | Full Site Editing — WordPress block-based theme editing |
| **GA4** | Google Analytics 4 — current version of Google Analytics |
| **GBP** | Google Business Profile — local business listing on Google |
| **GSC** | Google Search Console — search performance monitoring tool |
| **GTM** | Google Tag Manager — script/tag management platform |
| **KVK** | Kamer van Koophandel — Dutch Chamber of Commerce registration number |
| **LCP** | Largest Contentful Paint — Core Web Vital for loading performance |
| **MVO** | Maatschappelijk Verantwoord Ondernemen — Corporate Social Responsibility |
| **NAP** | Name, Address, Phone — consistent business identity across platforms |
| **OSB** | Ondernemersorganisatie Schoonmaak- en Bedrijfsdiensten — Dutch cleaning industry trade association |
| **PSI** | PageSpeed Insights — Google performance measurement tool |
| **RI&E** | Risico-Inventarisatie & -Evaluatie — Dutch occupational risk assessment |
| **VvE** | Vereniging van Eigenaren — Dutch Homeowners' Association |
| **WCAG** | Web Content Accessibility Guidelines — accessibility standards |

---

**End of Production Build Specification — Version 1.0.0**

This document set is ready for development handoff. All sections contain actionable, implementation-ready requirements. Items marked "MISSING INFORMATION" or "To Be Confirmed" require client resolution before the corresponding development phase begins.
