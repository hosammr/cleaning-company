# Part 4: Performance, Accessibility, Security, GDPR, Cookies, Analytics, Forms

**HDS Onderhoudsdiensten — Production Build Specification — Part 4 of 8**

---

## 17. Performance Requirements

### 17.1 Performance Budgets

| Metric | Target | Tool |
|---|---|---|
| **Largest Contentful Paint (LCP)** | < 2.5 seconds | PageSpeed Insights, Lighthouse |
| **First Input Delay (FID)** | < 100 ms | PageSpeed Insights, Lighthouse |
| **Cumulative Layout Shift (CLS)** | < 0.1 | PageSpeed Insights, Lighthouse |
| **Interaction to Next Paint (INP)** | < 200 ms | PageSpeed Insights, Chrome UX Report |
| **Time to First Byte (TTFB)** | < 600 ms | WebPageTest, hosting monitoring |
| **Total Page Weight** | < 1.5 MB mobile, < 3 MB desktop | WebPageTest, DevTools |
| **Speed Index** | < 3.4 seconds | Lighthouse |
| **Google PageSpeed Insights (Mobile)** | 90+ | PSI |
| **Google PageSpeed Insights (Desktop)** | 95+ | PSI |

### 17.2 Implementation Requirements

| Requirement | Implementation |
|---|---|
| **Caching** | Page caching (WP Rocket/FlyingPress). Browser caching: 1 year for static assets with versioned filenames. Redis object caching on server. |
| **CSS** | Minimal CSS. Critical CSS inlined in `<head>`. Non-critical CSS deferred. No unused CSS from frameworks. |
| **JavaScript** | Deferred loading (`defer`). No render-blocking JS. No jQuery unless WooCommerce requires it. No jQuery Migrate. |
| **Fonts** | Self-host all fonts (no Google Fonts CDN). Preload main font. `font-display: swap`. Subset to Latin + Dutch diacritics. |
| **Images** | WebP with `<picture>` fallback. Explicit width/height (prevents CLS). Lazy loading below fold. `fetchpriority="high"` on LCP image. Preload hero image. |
| **Third-party scripts** | Load via GTM with triggers. Defer all. Audit every script — remove unused tags. |
| **CDN** | Cloudflare with full-page caching, image optimization (Polish), auto-minify. |
| **Hosting** | Managed WordPress with PHP 8.2+, opcache, Redis, SSD, HTTP/2 or HTTP/3. |
| **Database** | Clean — no revisions older than 30 days, no spam, no transient garbage. |

### 17.3 Pre-Launch Performance Tests

Before every major deployment and at launch:
1. Google PageSpeed Insights (mobile + desktop) — must score 90+ mobile, 95+ desktop
2. WebPageTest (Amsterdam server, Moto G4, 3G Fast)
3. GTmetrix
4. DebugBear Core Web Vitals lab test
5. Chrome DevTools Performance tab — check long tasks (>50ms)
6. Chrome DevTools Coverage tab — unused CSS/JS (target less than 30% unused)

---

## 18. Accessibility Requirements (WCAG 2.2 AA)

### 18.1 Compliance Level

**Target: WCAG 2.2 Level AA** (all success criteria, including 2.5.8 minimum target size at AAA recommendation level)

### 18.2 Specific Requirements

| # | Requirement | Implementation |
|---|---|---|
| A1 | **Color contrast** | Text: 4.5:1 (normal), 3:1 (large text, UI components). Test with axe DevTools or WebAIM. |
| A2 | **Keyboard navigation** | All interactive elements focusable and operable via keyboard. Visible focus indicator. Logical tab order. |
| A3 | **Skip to content** | First focusable element. Visible on focus. Links to `<main>`. |
| A4 | **Semantic HTML** | H1-H2-H3 hierarchy (no skipped levels). `<header>`, `<nav>`, `<main>`, `<article>`, `<aside>`, `<footer>`, `<section>`. |
| A5 | **ARIA landmarks** | `banner`, `navigation`, `main`, `contentinfo`, `search`. Native HTML5 takes precedence over ARIA. No redundant ARIA. |
| A6 | **Alt text** | Descriptive Dutch on every non-decorative image. Decorative: `alt=""`. SVG with text labels: `aria-hidden="true"`. |
| A7 | **Forms** | All fields have `<label>`. Required fields: text + `aria-required="true"`. Errors via `aria-describedby`. Success messages announced. |
| A8 | **Links** | Descriptive destination. No "klik hier". External: `target="_blank"` + `rel="noopener noreferrer"` + warning text. |
| A9 | **Multimedia** | Video: captions. Audio: transcripts. (Low priority — unlikely at launch.) |
| A10 | **Zoom/Resize** | Usable at 200% zoom. No horizontal scroll. No cut-off content. |
| A11 | **Motion** | No auto-playing content. No flashing (>3/sec). Respect `prefers-reduced-motion`. |
| A12 | **Touch targets** | Minimum 44x44 px (WCAG 2.5.8 AAA adopted as AA target). |
| A13 | **Error identification** | Form errors: identify field, describe error, suggest correction (WCAG 3.3.1, 3.3.3). |
| A14 | **Language** | `lang="nl-NL"` on `<html>`. Any English blocks: `lang="en"`. |
| A15 | **Page title** | Every page: unique, descriptive `<title>`. |
| A16 | **Status messages** | Dynamic updates (cart add, form feedback) announced via `aria-live` regions. |
| A17 | **Consistent navigation** | Navigation order and position consistent across all pages. |
| A18 | **Consistent identification** | Components with same function labelled consistently. |

### 18.3 Accessibility Testing

| Test | Tool | Frequency |
|---|---|---|
| Automated audit | axe DevTools | Every page, before launch + every major update |
| Automated audit | WAVE browser extension | Every page, before launch |
| Automated audit | Lighthouse Accessibility score | Must score 100. Before launch. |
| Manual keyboard test | Tab through every page, operate every element | Before launch |
| Manual screen reader | NVDA (Windows) or VoiceOver (Mac). Test contact form, nav, shop. | Before launch |
| Color contrast audit | WebAIM contrast checker or axe DevTools | All designs + all pages before launch |
| Mobile accessibility | Real device with VoiceOver iOS / TalkBack Android | Minimum 3 pages: Home, Contact, 1 Service |
| 200% zoom test | Browser 200% zoom. No content loss or horizontal scroll. | Every page template |

### 18.4 Accessibility Statement

Add footer link "Toegankelijkheid" to `/toegankelijkheid/` with WCAG 2.2 AA compliance statement and contact method for accessibility issues. Optional but recommended.

---

## 19. Security Requirements

### 19.1 Server-Level Security

| Requirement | Implementation |
|---|---|
| **HTTPS only** | HSTS: `max-age=31536000; includeSubDomains; preload` |
| **XML-RPC disabled** | Block at web server level |
| **Directory listing disabled** | `Options -Indexes` |
| **PHP version** | 8.2+ (actively supported, auto-updated for minor) |
| **File permissions** | Directories: 755, Files: 644, wp-config.php: 400 or 440 |
| **wp-config.php** | Moved one level above web root OR locked. Salts rotated. Database prefix changed from `wp_`. |
| **Database** | Strong unique password. User has minimum required privileges. |
| **SFTP only** | No FTP. Key-based authentication preferred. |

### 19.2 Application-Level Security

| Requirement | Implementation |
|---|---|
| **Security plugin** | Wordfence Premium OR Solid Security Pro |
| **2FA** | Two-factor authentication for ALL admin accounts |
| **Login URL** | Custom (not `/wp-admin/` or `/wp-login.php/`) |
| **Login attempt limiting** | Max 3 failed attempts, IP-based lockout |
| **Admin username** | Never "admin", "hds", or "helderduidelijkschoon". Unique, non-obvious. |
| **Plugins** | Official sources only. No nulled/cracked. Audit for vulnerabilities before install. |
| **Theme code** | No `eval()`, no `base64_decode()`. Output escaped (`esc_html()`, `esc_attr()`, `wp_kses()`). Inputs sanitized. Nonces on all forms. |
| **REST API** | Block `/wp-json/wp/v2/users` for user enumeration. |
| **File editor** | `define('DISALLOW_FILE_EDIT', true);` |
| **Auto-updates** | Enable for minor releases. Major releases reviewed before update. |

### 19.3 Regular Security Tasks

| Task | Frequency |
|---|---|
| Update core, plugins, themes | Weekly (auto-minor; manual-major) |
| Malware scan | Daily (Wordfence auto) |
| Review security logs | Weekly |
| Audit admin accounts | Monthly |
| Backup verification (test restore) | Monthly |
| Password rotation | Quarterly |
| Full external security audit | Annually |

---

## 20. GDPR/AVG Compliance

### 20.1 Required Documentation Pages

| Page | URL | Content |
|---|---|---|
| **Privacyverklaring** | `/privacyverklaring/` | Complete privacy policy: data controller identity, processing purposes, legal basis, retention periods, data subject rights, right to withdraw consent, right to complain to Autoriteit Persoonsgegevens, third-party data sharing, automated decision-making, international transfers, privacy contact. |
| **Cookiebeleid** | `/cookiebeleid/` | Cookie categories, specific cookies (name, purpose, duration, provider), consent mechanism, how to withdraw consent, browser settings link. |
| **Disclaimer** | `/disclaimer/` | Limitation of liability, intellectual property, external links, accuracy, applicable law. |

### 20.2 Technical GDPR Requirements

| Requirement | Implementation |
|---|---|
| **Cookie consent** | Complianz/Cookiebot. No non-functional cookies before explicit consent. Consent logged. |
| **Contact form consent** | Checkbox "Ik ga akkoord met de privacyverklaring" — unchecked by default. Link to privacy policy. NOT pre-ticked. |
| **Data access requests** | Process documented. Gravity Forms entries exportable. WooCommerce customer data export. |
| **Data retention** | Contact entries: auto-delete after 12 months. WooCommerce orders: retain 7 years (financial data — NL requirement). |
| **Right to erasure** | Process for deleting personal data on request. Delete from WP users, form entries, customer data, backups. |
| **Data breach notification** | Process for detecting, investigating, notifying Autoriteit Persoonsgegevens within 72 hours. |
| **Data Processing Agreement (DPA)** | Signed with hosting provider, analytics provider (Google), any third-party processors. |
| **SSL/TLS** | All data in transit encrypted. |
| **IP anonymization** | GA4 anonymizes by default. Confirm enabled. |

### 20.3 Legal Disclaimer

**This specification outlines technical requirements for GDPR/AVG compliance. It does not constitute legal advice. The client must have the privacyverklaring, cookiebeleid, and disclaimer reviewed by a qualified legal professional specializing in Dutch privacy law (AVG) before launch.**

---

## 21. Cookie Compliance

### 21.1 Cookie Categories and Consent

| Category | Examples | Consent Required | Blocked Before Consent? |
|---|---|---|---|
| **Functioneel (Noodzakelijk)** | WP session, WC cart, consent cookie | No (legitimate interest) | No — always loaded |
| **Analytisch (Statistieken)** | Google Analytics (_ga, _ga_*) | Yes (if not anonymized) or No (if cookieless) | Yes unless cookieless tracking |
| **Marketing (Tracking)** | Facebook Pixel, Google Ads, remarketing | Yes | Yes — blocked until consent |
| **Social Media** | Facebook/Instagram embed cookies | Yes | Yes — blocked until consent |
| **Voorkeuren** | Language/font preferences | Yes | Yes (unless strictly necessary) |

### 21.2 Cookie Consent Plugin Configuration

| Setting | Value |
|---|---|
| **Consent banner type** | Soft opt-in insufficient — require explicit action |
| **Cookie scan** | Monthly (auto) |
| **Cookie declaration** | `/cookiebeleid/` — auto-generated by plugin |
| **Consent logging** | Enabled — timestamp, anonymized IP, consent string |
| **Consent duration** | 12 months, then re-prompt |
| **Language** | All banner text in Dutch |
| **GTM integration** | Consent signals forwarded. Tags triggered by consent state. |
| **GA4 integration** | Google Consent Mode v2 configured |

### 21.3 Cookie Banner UX

- Must appear on first visit. Not dismissible without choice.
- Three options: "Accepteren" (accept all), "Weigeren" (reject all non-essential), "Instellingen aanpassen" (customize).
- "Instellingen aanpassen" opens modal with per-category toggles. All OFF by default except Functional.
- Responsive and accessible (keyboard-navigable, screen-reader-compatible).
- "Cookiebeleid" link in banner text.
- Persistent floating/icon button to reopen preferences.

---

## 22. Analytics Requirements

### 22.1 Google Analytics 4 Setup

| Configuration | Detail |
|---|---|
| **Property** | GA4 — "HDS Onderhoudsdiensten" |
| **Data Stream** | Web stream for `helderduidelijkschoon.nl` |
| **IP Anonymization** | Enabled by default in GA4 |
| **Data Retention** | 14 months |
| **Enhanced Measurement** | Page views, scrolls, outbound clicks, site search, video engagement, file downloads — ALL enabled |
| **Unwanted referrals** | Exclude payment gateways |
| **Internal traffic** | Filter out office IP (client to provide) |
| **Bot filtering** | Exclude known bots and spiders |

### 22.2 Google Search Console

| Configuration | Detail |
|---|---|
| **Property type** | Domain (`helderduidelijkschoon.nl`) |
| **Verification** | Via GA4, DNS, or HTML file |
| **Sitemap submission** | Submit `/sitemap_index.xml` |
| **Monitoring** | Check weekly for crawl errors, indexing, search performance |
| **Core Web Vitals** | Monitor in GSC |

### 22.3 Google Tag Manager

- All tracking scripts deployed via GTM
- No hardcoded tracking in theme files
- GTM snippet in `<head>` (Consent Mode compatible)
- Data Layer: form submissions, phone clicks, email clicks, purchases

### 22.4 Reporting

| Report | Frequency | Recipient |
|---|---|---|
| Monthly traffic and conversion report | Monthly | Client |
| Landing page performance | Monthly | Client |
| Conversion source analysis | Monthly | Client |
| SEO performance (rankings, impressions, clicks) | Monthly | Client |
| Technical health (uptime, speed, errors) | Monthly | Developer |

---

## 23. Conversion Tracking

### 23.1 Conversion Events

| Event | Trigger | Measurement |
|---|---|---|
| **Phone call click** | `tel:` link clicked | GA4 event: `phone_click` |
| **Email click** | `mailto:` link clicked | GA4 event: `email_click` |
| **Contact form submission** | Successful submission -> `/bedankt/` | GA4 event: `form_submission` |
| **Quote request submission** | "Offerte Aanvragen" form -> `/bedankt/?type=offerte` | GA4 event: `quote_request` |
| **WhatsApp click** | WhatsApp link (if implemented) | GA4 event: `whatsapp_click` |
| **WooCommerce add to cart** | Product added | GA4 event: `add_to_cart` |
| **WooCommerce purchase** | Order completed | GA4 event: `purchase` |
| **Newsletter signup** | Newsletter form (if implemented) | GA4 event: `newsletter_signup` |
| **GDPR consent accepted** | Cookie banner "Accepteren" | GA4 event: `cookie_consent_accepted` |

### 23.2 Call Tracking

- **Basic:** Track phone link clicks in GA4 via GTM.
- **Advanced (Recommended):** If phone inquiries are critical, implement call tracking (CallRail, CallTrackingMetrics) with dynamic number insertion. **Decision: client to provide budget and preference.**

### 23.3 Google Ads Conversion Tracking (Future)

If Google Ads campaigns are planned: conversion tracking tag via GTM, conversion linker tag, phone call conversion tracking, import GA4 conversions into Google Ads. **Not required for launch.**

---

## 24. Forms Specification

### 24.1 Contact Form (`/contact/`)

| Field | Type | Required | Notes |
|---|---|---|---|
| Naam | Text | Yes | — |
| Bedrijf | Text | No | — |
| E-mailadres | Email | Yes | Valid email format |
| Telefoonnummer | Tel | No | Dutch phone format |
| Onderwerp | Dropdown | Yes | "Offerte aanvragen", "Vraag over diensten", "Klacht of opmerking", "Anders" |
| Bericht | Textarea | Yes | Min 10 characters |
| Privacy akkoord | Checkbox | Yes | "Ik ga akkoord met de privacyverklaring" — unchecked by default, links to `/privacyverklaring/` |
| Anti-spam | reCAPTCHA v3 (invisible) OR honeypot | Yes | No user interaction |
| Verzenden | Submit button | — | "Verstuur bericht" |

**Post-Submit:** Redirect to `/bedankt/?type=contact`. Confirmation email to user. Notification email to `info@helderduidelijkschoon.nl`.

### 24.2 Quote Request Form (`/offerte-aanvragen/`)

| Field | Type | Required | Notes |
|---|---|---|---|
| Naam | Text | Yes | — |
| Bedrijf | Text | Yes | — |
| E-mailadres | Email | Yes | — |
| Telefoonnummer | Tel | Yes | — |
| Gewenste dienst | Checkboxes (multi) | Yes | All 7 services + "Anders" |
| Type gebouw | Dropdown | No | Kantoor, Wooncomplex/VvE, School, Zorginstelling, Fabriek/Magazijn, Bouwproject, Anders |
| Postcode / Plaats | Text | Yes | Verify service area coverage |
| Beschrijving aanvraag | Textarea | No | — |
| Gewenste planning | Dropdown | No | "Zo snel mogelijk", "Binnen 2 weken", "Binnen 1 maand", "Binnen 3 maanden", "Orienterend / geen haast" |
| Hoe heeft u ons gevonden? | Dropdown | No | "Google / Zoekmachine", "VvE Belang", "Social media", "Collega / Relatie", "Anders" |
| Bestand uploaden | File upload | No | Max 5 MB. PDF, JPG, PNG, DOCX. |
| Privacy akkoord | Checkbox | Yes | — |
| Anti-spam | reCAPTCHA v3 | Yes | Invisible |

**Post-Submit:** Redirect to `/bedankt/?type=offerte`. Confirmation email with summary. Notification email to `info@helderduidelijkschoon.nl` with all data + file attachment.

### 24.3 Vacature Application Form (per vacancy)

| Field | Type | Required | Notes |
|---|---|---|---|
| Naam | Text | Yes | — |
| E-mailadres | Email | Yes | — |
| Telefoonnummer | Tel | Yes | — |
| Motivatie | Textarea | Yes | "Waarom wilt u bij HDS werken?" |
| CV uploaden | File upload | Yes | Max 5 MB. PDF or DOCX. |
| Privacy akkoord | Checkbox | Yes | — |

### 24.4 Form Configuration (All Forms)

| Setting | Value |
|---|---|
| **Confirmation email** | From: `info@helderduidelijkschoon.nl`. Include submitted data summary + contact phone. |
| **Notification email** | To: `info@helderduidelijkschoon.nl` (configurable). Include all submitted data. Attachments as download link, not inline. |
| **Entry storage** | Store in WordPress + forward via email. Gravity Forms auto-stores. |
| **Auto-delete entries** | Delete entries older than 12 months (configurable). |
| **Spam protection** | reCAPTCHA v3 (invisible) + honeypot fallback. |
| **Form styling** | Brand styling. Responsive. Accessible (labels, errors, focus indicators). |
| **File upload security** | Validate file type server-side. Rename uploaded files. Limit file size. Malware scan if possible. |
