# Part 6: Backup, Deployment, Acceptance, Dependencies, Gap Analysis

**HDS Onderhoudsdiensten — Production Build Specification — Part 6 of 8**

---

## 35. Backup Strategy

### 35.1 Backup Schedule

| Backup Type | Frequency | Retention | Storage |
|---|---|---|---|
| **Full (files + database)** | Daily (nightly) | 30 daily, 4 weekly, 12 monthly | Offsite cloud (BlogVault / UpdraftPlus remote: Google Drive, Dropbox, or S3) |
| **Pre-update backup** | Before every plugin/theme/core update | Manual trigger or auto (BlogVault) | Same offsite |
| **Database-only** | Every 6 hours (if high-traffic) | 7 days | Same offsite |

### 35.2 Backup Verification

- Monthly test restore: restore latest backup to staging environment and verify site works
- Verification checklist: all pages load, forms submit, WooCommerce checkout works, admin login works
- Document restore procedure in a runbook (accessible to developer + client)

### 35.3 Disaster Recovery

| Scenario | RTO | RPO |
|---|---|---|
| Server failure (hosting outage) | < 4 hours | < 24 hours (last nightly) |
| Malware / defacement | < 4 hours | < 24 hours |
| Accidental content deletion | < 1 hour (revision history) or < 4 hours (backup restore) | < 24 hours |
| DNS / domain issue | < 2 hours | N/A |

**Recovery procedure:** Documented in a runbook. Client given printed copy with hosting support phone, developer phone, DNS provider login, and step-by-step restore instructions.

---

## 36. Deployment Strategy

### 36.1 Environments

| Environment | URL | Purpose |
|---|---|---|
| **Local Development** | `hds.local` or `localhost:XXXX` | All development starts here |
| **Staging** | `staging.helderduidelijkschoon.nl` (password-protected, noindex) | Pre-production testing, client review, QA. Identical to production except domain. |
| **Production** | `helderduidelijkschoon.nl` | Live site |

### 36.2 Deployment Workflow

```
Developer local -> Git push to dev/staging branch
        |
        v
Automated deployment to Staging (GitHub Actions / DeployHQ / WP Engine Git Push)
        |
        v
Staging: Client review + QA
        |
        v (Approved)
Merge staging -> main branch
        |
        v
Automated deployment to Production
        |
        v
Post-deploy: Clear caches, trigger backup, verify site
```

### 36.3 Deployment Checklist

Before every production deployment:
- [ ] All changes committed and pushed to Git
- [ ] Staging tested and approved by client
- [ ] Full backup taken (auto-triggered)
- [ ] All caches cleared post-deploy (WP Rocket, Cloudflare, Redis)
- [ ] Quick smoke test: Home, 1 service page, 1 product page, Contact, mobile view
- [ ] Rollback plan communicated

### 36.4 Handover and Training

Before handover:
- [ ] All admin credentials documented and shared securely (1Password, Bitwarden, or encrypted doc)
- [ ] 1-hour training session with client: edit pages, add blog posts, view form entries, manage WooCommerce orders
- [ ] Written "Website Beheergids" (Website Management Guide) in Dutch covering: how to log in, edit a page, add a blog post, view contact entries, manage products/orders, check analytics, who to contact for support, backup restore procedure
- [ ] Client signs off on acceptance criteria

---

## 37. Acceptance Criteria

### 37.1 Functional Acceptance

| # | Criterion | Pass Condition |
|---|---|---|
| AC01 | All pages (P01-P32) return HTTP 200 or appropriate status | Screaming Frog: zero 4xx/5xx on expected pages |
| AC02 | Contact form submits and delivers email | Test submission received within 5 minutes |
| AC03 | Quote request form submits with file upload | Test submission with PDF received |
| AC04 | WooCommerce purchase flow end-to-end | Test order: Product -> Cart -> Checkout -> Payment -> Email received |
| AC05 | All 301 redirects work correctly | Each old URL tested manually. No redirect chains. |
| AC06 | All internal links resolve to valid pages | Screaming Frog: zero broken internal links |
| AC07 | All external links resolve to valid pages | Screaming Frog: no broken external links |
| AC08 | Search returns relevant results | Test: "glasbewassing" returns Glasbewassing page first |
| AC09 | Mobile menu works on all device sizes | Manual: iPhone SE, iPhone 14, iPad, 1920px desktop |
| AC10 | All forms have functional anti-spam | Test submission without completing hidden fields -> blocked |
| AC11 | Cookie consent banner appears first visit | Fresh browser / incognito: banner appears |
| AC12 | Non-functional cookies blocked before consent | DevTools Network: no GA/Facebook cookies before consent |
| AC13 | WooCommerce emails deliver correctly | Test order triggers admin and customer emails |

### 37.2 Non-Functional Acceptance

| # | Criterion | Pass Condition |
|---|---|---|
| AC14 | Google PSI mobile score >= 90 | Tested on Home, 1 service page, 1 product page |
| AC15 | Google PSI desktop score >= 95 | Same pages |
| AC16 | Lighthouse Accessibility = 100 | All page templates |
| AC17 | WCAG 2.2 AA verified | axe DevTools: zero critical or serious issues |
| AC18 | All images have alt text | Screaming Frog: zero missing alt text (decorative empty OK) |
| AC19 | All pages have unique titles and meta descriptions | Screaming Frog: zero duplicates, zero empty |
| AC20 | XML Sitemap accessible at `/sitemap_index.xml` | 200 with valid XML. Zero broken pages. Zero attachment pages. |
| AC21 | robots.txt accessible | 200 with valid content |
| AC22 | HTTPS enforced site-wide | HTTP -> HTTPS redirect. HSTS header present. |
| AC23 | All pages load in < 3 seconds | WebPageTest |
| AC24 | Staging is noindex, nofollow, password-protected | Verified |
| AC25 | Production is index, follow | Verified (unless deliberately noindex'd) |

### 37.3 Content Acceptance

| # | Criterion | Pass Condition |
|---|---|---|
| AC26 | All service pages >= 300 words Dutch content | Word count check |
| AC27 | No lorem ipsum or placeholder text remains | Full site text crawl |
| AC28 | Phone and email correct on all pages | Manual verification |
| AC29 | KVK and BTW in footer (if provided) | Manual verification |
| AC30 | Privacyverklaring, Cookiebeleid, Algemene Voorwaarden published | Manual verification |
| AC31 | All page titles and descriptions custom-written (not auto-generated) | Screaming Frog check |

### 37.4 Legal Acceptance

| # | Criterion | Pass Condition |
|---|---|---|
| AC32 | Privacyverklaring reviewed by legal professional | Client confirms review completed |
| AC33 | Cookie consent tested and logging verified | Complianz scan log shows consent events |
| AC34 | All form consent checkboxes unchecked by default | Manual test |
| AC35 | Data Processing Agreement signed with hosting | Client confirms DPA in place |

---

## 38. Dependency Map

### 38.1 Phase 0 — Prerequisites (Must Complete Before Phase 1)

| # | Dependency | Owner | Blocks |
|---|---|---|---|
| D01 | Domain registrar access | Client | Everything |
| D02 | Hosting account access | Client | Everything |
| D03 | WordPress admin access | Client | Content review |
| D04 | Google Search Console access | Client/Developer | SEO migration |
| D05 | Google Analytics access or new GA4 property | Developer | Analytics |
| D06 | Google Business Profile claimed/verified | Client | Local SEO |
| D07 | Brand colors, fonts, design direction approved | Client | Design |
| D08 | Physical address, KVK, BTW numbers provided | Client | Footer, schema, legal |
| D09 | Business hours provided | Client | Schema, contact page |
| D10 | Service area (specific cities/postcodes) confirmed | Client | Local SEO |
| D11 | Payment gateway decision (Mollie recommended) | Client | WooCommerce |
| D12 | Shipping costs provided | Client | WooCommerce |
| D13 | Logo vector file (SVG/AI/EPS) or budget for recreation | Client | Design |
| D14 | Client project photos or stock photography approved | Client | Service pages, hero |
| D15 | Client names/logos for Referenties (with permissions) | Client | Referenties page |
| D16 | Testimonial text provided | Client | Social proof |
| D17 | Vacancy text (full HTML, not images) | Client | Vacatures page |
| D18 | Terms & Conditions text for HTML page | Client | Algemene Voorwaarden |
| D19 | Privacyverklaring and Cookiebeleid drafted and legally reviewed | Client + legal counsel | Legal pages |
| D20 | Website rebuild budget approved | Client | All development |

### 38.2 Phase 1 — Foundation (Week 1-2)

| # | Task | Depends On |
|---|---|---|
| P1.01 | Set up hosting + staging environment | D01, D02 |
| P1.02 | Install WordPress 6.7+, configure settings | P1.01 |
| P1.03 | Install and configure all plugins | P1.02 |
| P1.04 | Set up Git repository for custom theme | P1.02 |
| P1.05 | Develop custom theme foundation (header, footer, base styles) | D07 |
| P1.06 | Implement design system (colors, typography, spacing) | D07 |
| P1.07 | Set up Cloudflare CDN | D01 |
| P1.08 | Configure SSL | P1.01 |
| P1.09 | Configure backups | P1.01 |

### 38.3 Phase 2 — Core Pages (Week 3-4)

| # | Task | Depends On |
|---|---|---|
| P2.01 | Build Home page template | P1.05, P1.06 |
| P2.02 | Build Service page template | P1.05, P1.06 |
| P2.03-P2.11 | Build all service pages + category landings (9 pages) | P2.02 |
| P2.12 | Build Contact page template | P1.05, P1.06 |
| P2.13 | Build page: Contact | P2.12, D08 |
| P2.14 | Build Quote Request page | P2.12 |
| P2.15 | Configure Gravity Forms (all 3 forms) | P2.13, P2.14 |
| P2.16 | Build Thank You page | P2.13 |

### 38.4 Phase 3 — Supporting Pages (Week 5)

| # | Task | Depends On |
|---|---|---|
| P3.01 | Build About page template | P1.05, P1.06 |
| P3.02-P3.06 | Build Over HDS, Kwaliteit, Referenties, Vacatures, Downloads | P3.01, various client inputs |
| P3.07 | Build Veelgestelde Vragen | P1.05 |
| P3.08 | Build Legal page template | P1.05 |
| P3.09-P3.12 | Build Privacyverklaring, Cookiebeleid, Algemene Voorwaarden, Disclaimer | P3.08, D18, D19 |

### 38.5 Phase 4 — WooCommerce (Week 5-6)

| # | Task | Depends On |
|---|---|---|
| P4.01 | Configure WooCommerce settings | P1.02, D11, D12 |
| P4.02 | Import/migrate 14 Airfixr products | P4.01 |
| P4.03-P4.07 | Configure payment, shipping, emails, test purchase flow | P4.01-P4.02 |

### 38.6 Phase 5 — SEO and Technical (Week 6-7)

| # | Task | Depends On |
|---|---|---|
| P5.01 | Configure Yoast SEO / Rank Math | All content pages exist |
| P5.02 | Write all meta titles and descriptions | P2, P3 |
| P5.03 | Implement structured data (all 4 schema types) | D08, D09, D10, P2, P3 |
| P5.04 | Configure 301 redirects | All new URLs known |
| P5.05 | Configure XML Sitemap and robots.txt | P5.01 |
| P5.06 | Set up GA4 (via GTM or Site Kit) | D05 |
| P5.07 | Set up Google Tag Manager | P1.08 |
| P5.08 | Configure conversion tracking | P5.06, P5.07, P2.15 |
| P5.09 | Set up Google Search Console | D04, P5.05 |
| P5.10 | On-page SEO: internal linking audit | All content |
| P5.11 | Image optimization (WebP, compression, alt text) | All media |

### 38.7 Phase 6 — Compliance and Security (Week 7)

| # | Task | Depends On |
|---|---|---|
| P6.01 | Configure Complianz cookie consent | P3.09, P3.10 |
| P6.02 | Configure Wordfence security | P1.02 |
| P6.03 | Disable XML-RPC | P1.02 |
| P6.04 | Configure custom login URL | P1.02 |
| P6.05 | GDPR form consent checkboxes | P2.15 |
| P6.06 | Set up UptimeRobot monitoring | P1.01 |
| P6.07 | Accessibility audit + remediation | All pages |

### 38.8 Phase 7 — Testing and QA (Week 8)

| # | Task | Depends On |
|---|---|---|
| P7.01 | Full functional QA (QA Testing Checklist — Section 43) | All phases |
| P7.02 | Full SEO audit (SEO Validation Checklist — Section 44) | P5 |
| P7.03 | Performance testing (PageSpeed, WebPageTest) | All phases |
| P7.04 | Cross-browser testing | All phases |
| P7.05 | Mobile/tablet testing (real devices) | All phases |
| P7.06 | Client review and approval on staging | All phases |
| P7.07 | Fix issues found in QA | P7.01-P7.06 |

### 38.9 Phase 8 — Launch (Week 8-9)

| # | Task | Depends On |
|---|---|---|
| P8.01 | Pre-launch checklist complete (Section 41) | P7 |
| P8.02 | Final backup of old site | Current site access |
| P8.03 | Deploy to production | P7, P8.01 |
| P8.04 | Clear all caches | P8.03 |
| P8.05 | Verify 301 redirects working | P8.03, P5.04 |
| P8.06 | Submit new sitemap to GSC | P8.03 |
| P8.07 | Post-launch verification (Section 42) | P8.03-P8.06 |
| P8.08 | Launch readiness report to client (Section 46) | P8.07 |
| P8.09 | Handover + training with client | P8.08 |

---

## 39. Gap Analysis: Current vs Target Website

### 39.1 Critical Gaps

| # | Gap | Current State | Target State |
|---|---|---|---|
| G01 | **Contact Page** | HTTP 500. Contact form inaccessible. | Gravity Forms. Confirmation email. GDPR-compliant. |
| G02 | **Reguliere Schoonmaak Page** | HTTP 404. | 300+ word page with service details, process, CTA. |
| G03 | **Page Sitemap** | HTTP 500. | Working XML sitemap, all pages, no attachments. |
| G04 | **Meta Descriptions** | Zero custom descriptions. | Unique 150-160 char descriptions on every page. |
| G05 | **Privacy Policy** | Does not exist. GDPR violation. | Published, legally reviewed. |
| G06 | **Cookie Consent** | Does not exist. ePrivacy violation. | Complianz banner with per-category consent, logging. |
| G07 | **KVK / BTW / Address** | Not displayed. Legal requirement. | In footer on every page. In LocalBusiness schema. |
| G08 | **Physical Address** | Not on site. | In footer and Contact page. |
| G09 | **Structured Data** | Bare minimum (WebPage, WebSite only). | Organization/LocalBusiness, Service per page, FAQ, BreadcrumbList, Product. |
| G10 | **Software Versions** | WP 6.2.9, Divi 4.16.1, WC 8.2.5 — all outdated. | Latest stable of all software. No Divi. |
| G11 | **XML-RPC** | Enabled. Attack vector. | Disabled at server. 403 Forbidden. |
| G12 | **Content Depth** | 9 of 12 pages under 150 words. 4 pages under 30 words. | Service pages 300+. Landings 500+. |

### 39.2 Significant Gaps

| # | Gap | Current State | Target State |
|---|---|---|---|
| G13 | **Homepage Content** | ~30 words. Icon grid only. | 300+ words with hero, USPs, services, testimonials, CTAs. |
| G14 | **Contact Form** | Broken (500 error). | Gravity Forms with conditional logic, file upload, consent. |
| G15 | **Referenties Page** | ~25 words. 1 logo. Empty testimonial plugin. | Client logos (with permission), project descriptions, testimonials. |
| G16 | **Vacatures Page** | ~5 words. 2 JPG images of Word docs. No application method. | HTML text, structured JobPosting data, application form. |
| G17 | **Industriele Schoonmaak** | ~60 words. Single paragraph. | 300+ words with bullets, equipment, safety protocols. |
| G18 | **Headings** | H1 only. No H2/H3 hierarchy. | Proper H1-H2-H3 on every page. |
| G19 | **Images** | Missing from service pages. No alt text. Unoptimized PNGs. | WebP images on all services. Alt text. Lazy loaded. Responsive. |
| G20 | **Blog** | 1 default "Hello World" post (2015). | 5-10 initial Dutch articles. Blog index. Categories. |
| G21 | **FAQ** | Does not exist. | 10-15 questions with FAQ schema. |
| G22 | **Analytics** | None detected. | GA4 + GSC + GTM. Conversion tracking. Monthly reporting. |
| G23 | **Performance** | Likely poor. Divi overhead. No caching. | 90+ PSI mobile. Caching + CDN + optimized images. |
| G24 | **Accessibility** | Vacatures completely inaccessible. No skip link. No ARIA. | WCAG 2.2 AA. axe DevTools zero critical issues. |
| G25 | **Mobile UX** | Not verified. Divi responsive but unoptimized. | Fully responsive. Mobile-first. 44px+ touch targets. |

### 39.3 Moderate Gaps

| # | Gap | Current State | Target State |
|---|---|---|---|
| G26 | **Social Media Widgets** | Instagram broken on every page. | Fixed Instagram link or removed widget. Working Facebook link. |
| G27 | **Search** | Default WP search in footer only. | Enhanced search (Relevanssi). Search on 404 page. |
| G28 | **404 Page** | Default server/WP 404. | Custom with search, links, contact info. |
| G29 | **Breadcrumbs** | Schema exists (Home only). Not visible on page. | Visible + schema breadcrumbs on all inner pages. |
| G30 | **Footer** | Minimal: search bar, broken Instagram, dev credit. | Comprehensive: contact info, services, legal, social. |
| G31 | **WooCommerce Shop** | Functional but no intro. No cross-links from services. | Airfixr landing. Shop intro. Cross-links. Clear BTW presentation. |
| G32 | **Downloads Page** | ~10 words. 2 PDFs on legacy domain. | HTML page with descriptions. PDFs on primary domain. |
| G33 | **Trailing Slashes** | Inconsistent. | Consistent with slash. Non-slash -> 301. |
| G34 | **URL Consistency** | `/vve` vs `/vve-service/`. Two URLs, one page. | Single canonical: `/vve-service/`. `/vve` -> 301. |
| G35 | **Category Landings** | Menu groups have no pages. | New landing pages at `/glas-en-gevel/` and `/schoonmaakdiensten/`. |

### 39.4 What Stays the Same

| Element | Reason |
|---|---|
| Domain name | Unchanged: helderduidelijkschoon.nl |
| Company name | Unchanged: HDS Onderhoudsdiensten |
| Tagline | Unchanged |
| Language | Unchanged: Dutch (nl-NL) single-language |
| Phone number | Unchanged: 0164-652846 |
| Email | Unchanged: info@helderduidelijkschoon.nl |
| Core services | Unchanged: 7 service lines |
| WooCommerce product line | Airfixr products migrated (unless client decides to remove) |
| Working URLs | Kept identical: /glasbewassing/, /gevelreiniging/, etc. |
| VvE Belang partnership | Preserved |
| OSB membership | Preserved |
