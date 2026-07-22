# Part 7: Migration Checklist, Pre-Launch, Post-Launch, QA, SEO, Content Checklists

**HDS Onderhoudsdiensten — Production Build Specification — Part 7 of 8**

---

## 40. Migration Checklist

### 40.1 Pre-Migration

- [ ] Full backup of current WordPress site (files + database) — store off-server
- [ ] Export all current site content (XML export from WordPress)
- [ ] Crawl current site with Screaming Frog. Export all URLs, status codes, titles, meta descriptions.
- [ ] Export Google Search Console data (last 16 months)
- [ ] Export Google Analytics data if any exists
- [ ] Document all current backlinks (Ahrefs / Semrush / GSC)
- [ ] Screenshot every current page (visual archive for reference)
- [ ] Record current Google rankings for key terms
- [ ] Export Google Business Profile data (if GBP exists)
- [ ] Export current WordPress media library (images + PDFs)
- [ ] Export WooCommerce products, orders, customers (if data to preserve)
- [ ] Download all PDFs from legacy domain (`hds-onderhoudsdiensten.nl`)
- [ ] Verify domain registrar login credentials work
- [ ] Verify hosting control panel login credentials work
- [ ] Verify current DNS records are documented (A, CNAME, MX, TXT)
- [ ] Verify email delivery still functions after DNS changes

### 40.2 During Migration (Staging)

- [ ] All 32 pages built and content populated
- [ ] All images optimized and uploaded
- [ ] All PDFs migrated to primary domain media library
- [ ] All forms built, tested, and delivering emails
- [ ] WooCommerce products imported, configured, tested
- [ ] All 301 redirects configured and tested
- [ ] SEO metadata written for all pages
- [ ] Structured data implemented and validated (Google Rich Results Test)
- [ ] XML Sitemap generated and validated
- [ ] robots.txt configured
- [ ] Cookie consent banner configured and tested
- [ ] GA4 and GTM configured and tested (via Tag Assistant)
- [ ] Performance optimization completed (90+ PSI mobile)
- [ ] Accessibility audit completed (zero critical issues)
- [ ] Cross-browser testing completed
- [ ] Mobile/tablet testing completed
- [ ] Client review and approval obtained

### 40.3 Launch Day

- [ ] Final backup of old live site taken
- [ ] Staging database search-replace for production domain
- [ ] Production environment SSL verified
- [ ] Files deployed to production
- [ ] Database imported to production
- [ ] All caches cleared (WP Rocket, Cloudflare, Redis, browser cache check)
- [ ] 301 redirects verified on production
- [ ] Contact form submitted and email received on production
- [ ] Quote form submitted with attachment and email received on production
- [ ] WooCommerce test purchase completed on production
- [ ] XML Sitemap submitted to Google Search Console
- [ ] robots.txt verified accessible
- [ ] Cookie consent banner verified on production (fresh browser)
- [ ] GA4 real-time reports showing traffic
- [ ] All old URLs returning correct 301 or 410 status codes
- [ ] Cloudflare caching enabled and verified

### 40.4 Post-Launch (First 48 Hours)

- [ ] Monitor GSC for crawl errors (check every 4 hours)
- [ ] Monitor email delivery (check form submissions arrive)
- [ ] Monitor site uptime (UptimeRobot)
- [ ] Monitor page speed (PageSpeed Insights)
- [ ] Check all contact phone numbers and email links work
- [ ] Respond to any user-reported issues within 2 hours
- [ ] Verify Google Business Profile link still works from website
- [ ] Verify old legacy domain PDF redirects work

---

## 41. Pre-Launch Checklist

### 41.1 Content

- [ ] All 32 pages published with final Dutch content
- [ ] All service pages >= 300 words
- [ ] No lorem ipsum or placeholder text anywhere
- [ ] Company name consistent across all pages
- [ ] Phone number correct on all pages
- [ ] Email address correct on all pages
- [ ] All legal pages published (privacyverklaring, cookiebeleid, algemene voorwaarden, disclaimer)
- [ ] Privacyverklaring legally reviewed
- [ ] Blog index page published (even if no posts yet — 404 for empty archive acceptable)
- [ ] 404 page published and styled
- [ ] Thank you page published
- [ ] All PDFs accessible from primary domain (no legacy domain dependency)

### 41.2 Design and UX

- [ ] Logo displays correctly at all screen sizes
- [ ] Color contrast meets WCAG AA on all pages
- [ ] Typography renders correctly on all browsers
- [ ] Responsive design verified: mobile, tablet, desktop, wide
- [ ] Navigation works: desktop dropdowns, mobile hamburger, footer links
- [ ] No broken images (all images load)
- [ ] Favicon and app icons present
- [ ] No horizontal scroll at any viewport width
- [ ] Touch targets >= 44px on mobile

### 41.3 Functionality

- [ ] Contact form submits and delivers email to `info@helderduidelijkschoon.nl`
- [ ] Offerte form submits with file upload and delivers email
- [ ] Vacature form submits with CV upload and delivers email (if application system active)
- [ ] All form validation errors display correctly
- [ ] All form anti-spam works
- [ ] WooCommerce product pages display correctly
- [ ] WooCommerce add-to-cart works
- [ ] WooCommerce checkout works with payment gateway
- [ ] WooCommerce emails deliver (order confirmation, admin notification)
- [ ] Site search returns results
- [ ] 404 page displays on non-existent URLs
- [ ] Cookie banner appears on first visit
- [ ] Cookie settings button allows preference changes

### 41.4 SEO

- [ ] Every page has unique `<title>` (50-60 chars)
- [ ] Every page has unique `<meta description>` (150-160 chars)
- [ ] `<title>` tags follow template: `[Page Title] - HDS Onderhoudsdiensten | [Location]`
- [ ] All Open Graph tags present on every page
- [ ] All Twitter Card tags present on every page
- [ ] Organization/LocalBusiness schema on Home, Contact, Over HDS
- [ ] Service schema on each service page
- [ ] FAQ schema on Veelgestelde Vragen
- [ ] BreadcrumbList schema on all inner pages
- [ ] Visible breadcrumbs on all inner pages
- [ ] All schema validates in Google Rich Results Test
- [ ] XML Sitemap at `/sitemap_index.xml` returns 200
- [ ] No attachment pages in sitemap
- [ ] robots.txt returns 200
- [ ] robots.txt correctly disallows sensitive paths
- [ ] Canonical tags self-referencing on all pages
- [ ] Hreflang not needed (single language) — verified absent
- [ ] Internal links audit: zero broken internal links
- [ ] Internal links audit: zero orphan pages
- [ ] All images have descriptive Dutch alt text (decorative: empty alt)

### 41.5 Technical

- [ ] HTTPS enforced (HTTP -> HTTPS 301)
- [ ] HSTS header present
- [ ] non-www -> www (or vice versa) 301 working
- [ ] All trailing slashes consistent
- [ ] All 301 redirects from old URLs tested
- [ ] Zero redirect chains
- [ ] XML-RPC disabled (returns 403)
- [ ] Custom admin login URL configured
- [ ] 2FA active on all admin accounts
- [ ] `DISALLOW_FILE_EDIT` defined as true
- [ ] Database prefix changed from `wp_`
- [ ] WordPress, theme, and all plugins on latest stable versions
- [ ] PHP 8.2+ running
- [ ] Daily backups configured and verified (test restore to staging)
- [ ] Caching enabled (WP Rocket) and configured
- [ ] CDN active and configured (Cloudflare)
- [ ] Image optimization plugin active and all images WebP
- [ ] Fonts self-hosted (no Google Fonts CDN calls)
- [ ] UptimeRobot monitoring active
- [ ] All WordPress auto-updates configured (minor only)
- [ ] Google Analytics 4 tracking active and verified
- [ ] Google Tag Manager snippet in place
- [ ] Google Search Console verified
- [ ] Staging environment noindex, nofollow, password-protected

### 41.6 Legal and Compliance

- [ ] Privacyverklaring published and linked from footer
- [ ] Cookiebeleid published and linked from footer + cookie banner
- [ ] Algemene Voorwaarden published and linked from footer
- [ ] KVK number in footer (if provided)
- [ ] BTW number in footer (if provided)
- [ ] Physical address in footer and Contact page (if provided)
- [ ] All form consent checkboxes unchecked by default
- [ ] All form consent checkboxes link to privacy policy
- [ ] Cookie consent logging active
- [ ] Cookie consent "Instellingen" modal works
- [ ] Data Processing Agreement signed with hosting provider
- [ ] Data Processing Agreement signed with analytics provider (Google)

### 41.7 Performance

- [ ] Google PageSpeed Insights mobile >= 90
- [ ] Google PageSpeed Insights desktop >= 95
- [ ] WebPageTest results within budget
- [ ] LCP < 2.5s
- [ ] CLS < 0.1
- [ ] TTFB < 600ms

---

## 42. Post-Launch Verification Checklist

### 42.1 Immediate (Within 1 Hour)

- [ ] Homepage loads on desktop and mobile
- [ ] Contact form submission test (send real inquiry, verify receipt)
- [ ] Phone number clickable and correct
- [ ] Email link clickable and correct
- [ ] All navigation links work
- [ ] SSL certificate valid (no browser warnings)
- [ ] Google Analytics real-time shows active users
- [ ] Old site URLs redirect correctly (test top 10 old URLs)
- [ ] Cloudflare caching active (check response headers for CF-Cache-Status: HIT)

### 42.2 Day 1

- [ ] Google Search Console: submit sitemap, check for errors
- [ ] Check all email notifications working (form submissions, WooCommerce orders)
- [ ] Check server error logs — zero unexpected errors
- [ ] Check backup completed successfully
- [ ] Run Screaming Frog crawl — zero unexpected 4xx/5xx
- [ ] Test on real mobile devices (not just browser resize)
- [ ] Test on real tablet
- [ ] Verify Google Business Profile link from website
- [ ] Verify Instagram and Facebook links from website
- [ ] Check all PDFs download correctly

### 42.3 Week 1

- [ ] Monitor GSC daily for crawl errors
- [ ] Monitor page speed metrics (Core Web Vitals in GSC)
- [ ] Check GA4: are conversions being tracked?
- [ ] Check GA4: are all expected events firing?
- [ ] Review security logs (Wordfence) — any attack attempts?
- [ ] Check form entry count — are submissions flowing?
- [ ] Check spam rate on forms — too high? Adjust anti-spam.
- [ ] Update any directory listings with new website URL (if changed)
- [ ] Respond to any client-reported issues

### 42.4 Week 2

- [ ] Submit all new URLs for indexing in GSC
- [ ] Compare indexed page count to old site baseline
- [ ] Compare search impressions to old site baseline
- [ ] Check keyword rankings vs pre-migration baseline
- [ ] Review GA4 traffic patterns — any unexpected drops?
- [ ] Check 404 errors in GSC — any patterns indicating redirect gaps?
- [ ] Performance re-test: PSI, WebPageTest — any degradation?
- [ ] Full backup test restore to staging

### 42.5 Week 4 (30-Day Review)

- [ ] Full SEO audit: rankings, traffic, indexed pages
- [ ] Compare to pre-migration baseline
- [ ] Report to client: traffic, conversions, rankings, technical health
- [ ] Check all plugins and themes for updates
- [ ] Review and renew SSL certificate if needed
- [ ] Review backup storage usage
- [ ] Review security logs for month — any incidents?
- [ ] Client satisfaction check: any issues or requests?

---

## 43. QA Testing Checklist

### 43.1 Functional Testing

#### Navigation
- [ ] Main nav: all links go to correct pages
- [ ] Main nav: dropdowns open/close on hover (desktop) and tap (mobile)
- [ ] Main nav: mobile hamburger opens/closes
- [ ] Main nav: mobile accordion submenus expand/collapse
- [ ] Footer: all links correct
- [ ] Homepage icon/service grid: all cards link correctly
- [ ] Breadcrumbs: visible, correct hierarchy, correct links

#### Forms
- [ ] Contact form: all fields accept input
- [ ] Contact form: required fields show error if empty
- [ ] Contact form: email field validates format
- [ ] Contact form: dropdown options correct
- [ ] Contact form: consent checkbox unchecked by default, checked required
- [ ] Contact form: successful submission -> redirect to /bedankt/?type=contact
- [ ] Contact form: confirmation email received by user
- [ ] Contact form: notification email received by HDS
- [ ] Offerte form: all fields accept input
- [ ] Offerte form: file upload works (PDF, JPG, PNG, DOCX)
- [ ] Offerte form: file upload rejects wrong types
- [ ] Offerte form: file upload rejects files > 5 MB
- [ ] Offerte form: successful submission -> redirect to /bedankt/?type=offerte
- [ ] Offerte form: confirmation and notification emails received
- [ ] Vacature form: all fields and CV upload work
- [ ] All forms: reCAPTCHA blocks spam submissions
- [ ] All forms: error messages in Dutch, clear, associated with fields

#### WooCommerce
- [ ] Shop page: all 14 products display
- [ ] Product page: image, price, description display
- [ ] Product page: add to cart works
- [ ] Cart: quantities updatable, remove item works
- [ ] Checkout: fields validate
- [ ] Checkout: payment gateway processes (test mode)
- [ ] Checkout: order confirmation page displays
- [ ] Checkout: emails sent to admin and customer
- [ ] My Account: registration works
- [ ] My Account: login works
- [ ] My Account: order history displays

#### Search
- [ ] Search returns results for known content
- [ ] Search returns "Geen resultaten" for nonsense queries
- [ ] Search results include relevant content types
- [ ] Search on 404 page works

#### Error Pages
- [ ] 404 page displays on non-existent URL
- [ ] 404 page includes search, links, contact info
- [ ] 404 page returns actual HTTP 404
- [ ] Custom 500 page displays on server error
- [ ] Staging environment shows errors (debug on)
- [ ] Production environment hides errors (debug off)

### 43.2 Cross-Browser Testing (Desktop)

| Feature | Chrome | Firefox | Safari | Edge |
|---|---|---|---|---|
| Homepage renders correctly | [ ] | [ ] | [ ] | [ ] |
| Navigation works | [ ] | [ ] | [ ] | [ ] |
| Forms submit | [ ] | [ ] | [ ] | [ ] |
| WooCommerce checkout | [ ] | [ ] | [ ] | [ ] |
| Cookie banner appears | [ ] | [ ] | [ ] | [ ] |
| All images load | [ ] | [ ] | [ ] | [ ] |
| Fonts render correctly | [ ] | [ ] | [ ] | [ ] |

### 43.3 Mobile Testing (Real Devices)

| Feature | iOS Safari (iPhone 14+) | Android Chrome | iPad |
|---|---|---|---|
| Homepage responsive | [ ] | [ ] | [ ] |
| Hamburger menu works | [ ] | [ ] | [ ] |
| Forms usable on mobile | [ ] | [ ] | [ ] |
| Phone link works (tel:) | [ ] | [ ] | [ ] |
| Email link works (mailto:) | [ ] | [ ] | [ ] |
| Tap targets >= 44px | [ ] | [ ] | [ ] |
| No horizontal scroll | [ ] | [ ] | [ ] |
| Cookie banner not obstructive | [ ] | [ ] | [ ] |
| WooCommerce usable on mobile | [ ] | [ ] | [ ] |
| 200% zoom usable | [ ] | [ ] | [ ] |

### 43.4 Accessibility Testing

- [ ] axe DevTools: zero critical issues on every page
- [ ] axe DevTools: zero serious issues on every page
- [ ] Lighthouse Accessibility score = 100 on every page
- [ ] WAVE: no errors on every page
- [ ] Keyboard: all interactive elements focusable and operable
- [ ] Keyboard: visible focus indicator on all elements
- [ ] Keyboard: logical tab order
- [ ] Keyboard: skip-to-content link works
- [ ] Screen reader (NVDA): navigation announced correctly
- [ ] Screen reader (NVDA): form labels read correctly
- [ ] Screen reader (NVDA): form errors announced
- [ ] Screen reader (NVDA): dynamic content updates announced
- [ ] Color contrast: all text meets 4.5:1 minimum
- [ ] Zoom: 200% - no content loss, no horizontal scroll
- [ ] Reduced motion: no auto-animation when `prefers-reduced-motion`

---

## 44. SEO Validation Checklist

### 44.1 Pre-Launch SEO Validation

- [ ] Every page has unique `<title>` tag (50-60 characters)
- [ ] Every page has unique `<meta name="description">` (150-160 characters)
- [ ] `<title>` tags follow pattern: `[Page Title] - HDS Onderhoudsdiensten | [Location]`
- [ ] Keywords included naturally in titles and descriptions
- [ ] No duplicate titles or descriptions (Screaming Frog check)
- [ ] No empty titles or descriptions (Screaming Frog check)
- [ ] H1 present exactly once on every page
- [ ] H2, H3 hierarchy logical (no skipped levels)
- [ ] Proper heading structure on all pages
- [ ] All images have descriptive alt text (Screaming Frog: zero missing)
- [ ] Self-referencing canonical on every page
- [ ] No canonical chains or cross-domain canonicals
- [ ] Open Graph tags complete on every page (og:title, og:description, og:image, og:url, og:type, og:locale)
- [ ] Twitter Card tags complete (twitter:card, twitter:title, twitter:description, twitter:image)
- [ ] Organization/LocalBusiness schema implemented and valid (Rich Results Test)
- [ ] Service schema on each service page, valid (Rich Results Test)
- [ ] FAQ schema on Veelgestelde Vragen, valid (Rich Results Test)
- [ ] BreadcrumbList schema on all inner pages
- [ ] Product schema on all WooCommerce product pages (auto by WC)
- [ ] XML Sitemap at `/sitemap_index.xml` returns 200 with valid XML
- [ ] Page sitemap returns 200 (this was broken on old site)
- [ ] No attachment pages in any sitemap
- [ ] No noindex pages in sitemap
- [ ] robots.txt returns 200
- [ ] robots.txt correctly configured (Disallow admin, allow everything else)
- [ ] HTTPS enforced with 301 redirect
- [ ] Non-www redirects to www (or vice versa) via 301
- [ ] All trailing slashes consistent (with slash, no-slash -> 301)
- [ ] Internal links: zero broken (Screaming Frog)
- [ ] Internal links: zero orphan pages (Screaming Frog)
- [ ] Anchor text varies and is descriptive (no "klik hier" spam)
- [ ] All service pages link to at least 2 other service pages
- [ ] Service pages link to Contact/Offerte and Kwaliteit & Veiligheid
- [ ] Mobile-friendly test passes

### 44.2 Post-Launch SEO Validation (Week 1)

- [ ] Sitemap submitted to Google Search Console — no errors
- [ ] Sitemap submitted to Bing Webmaster Tools — no errors
- [ ] GSC: no manual actions
- [ ] GSC: no security issues
- [ ] GSC: index coverage report - zero errors
- [ ] GSC: Core Web Vitals report - no poor URLs
- [ ] GSC: mobile usability report - no errors
- [ ] All 301 redirects returning 301 (not 302)
- [ ] No redirect chains (Screaming Frog or httpstatus.io)
- [ ] All old URLs resolving correctly (no dead ends)
- [ ] Google Rich Results Test: key pages show valid structured data
- [ ] Check Google index: `site:helderduidelijkschoon.nl` — pages appearing
- [ ] Check cached versions of pages — content rendering

### 44.3 Post-Launch SEO Validation (Week 4)

- [ ] Compare indexed pages count to pre-migration baseline
- [ ] Compare search impressions to pre-migration baseline
- [ ] Compare organic click-through rate to pre-migration baseline
- [ ] Check keyword rankings vs pre-migration baseline
- [ ] Full Screaming Frog crawl: zero issues
- [ ] Local SEO: GBP NAP matches website NAP exactly
- [ ] Local SEO: company appears in local pack for target keywords
- [ ] All local citations updated with consistent NAP

---

## 45. Content Migration Checklist

### 45.1 Page Content Migration

| # | Page | Status | Notes |
|---|---|---|---|
| 1 | Home | [ ] | Rewrite from ~30 words to 300+ words |
| 2 | Glasbewassing | [ ] | Migrate existing 180 words + expand to 300+ |
| 3 | Gevelreiniging | [ ] | Migrate existing 130 words + rename from "Gevelonderhoud" + expand |
| 4 | Reguliere Schoonmaak | [ ] | Write completely NEW content (currently 404) |
| 5 | Vloeronderhoud | [ ] | Migrate existing 140 words + expand |
| 6 | VVE Service | [ ] | Migrate existing 100 words + expand |
| 7 | Oplevering Schoonmaak | [ ] | Migrate existing 90 words + expand |
| 8 | Industriele Schoonmaak | [ ] | Rewrite from ~60 words to 300+ |
| 9 | Glas & Gevel landing | [ ] | NEW — aggregate overview page |
| 10 | Schoonmaakdiensten landing | [ ] | NEW — aggregate overview page |
| 11 | Over HDS | [ ] | Migrate existing 120 words + expand with history, team, values |
| 12 | Kwaliteit & Veiligheid | [ ] | Migrate existing 150 words + expand + add certification logos |
| 13 | Referenties | [ ] | Rewrite from ~25 words. Add client logos and testimonials. |
| 14 | Vacatures | [ ] | Rewrite from ~5 words. Convert images to HTML text + application form. |
| 15 | Downloads | [ ] | Rewrite from ~10 words. Add descriptions. Migrate PDFs. |
| 16 | Contact | [ ] | Write NEW content (currently 500 error). Form + contact info. |
| 17 | Offerte Aanvragen | [ ] | NEW — dedicated quote request form + content |
| 18 | Veelgestelde Vragen | [ ] | NEW — 10-15 FAQ items |
| 19 | Privacyverklaring | [ ] | NEW — full privacy policy (legal review required) |
| 20 | Cookiebeleid | [ ] | NEW — cookie declaration (Complianz auto-generates) |
| 21 | Algemene Voorwaarden | [ ] | NEW — HTML version of current PDF terms |
| 22 | Disclaimer | [ ] | NEW |
| 23 | Luchtreiniging | [ ] | NEW — Airfixr product introduction |
| 24 | Winkel | [ ] | Migrate existing. Add shop intro text. |
| 25 | Product pages (14) | [ ] | Migrate existing product data |
| 26-30 | WooCommerce system pages | [ ] | Standard WC pages |
| 31 | Blog index | [ ] | NEW |
| 32 | Blog posts (5-10) | [ ] | NEW — 5-10 initial Dutch articles |
| 33 | 404 page | [ ] | NEW |
| 34 | Bedankt page | [ ] | NEW |

### 45.2 Additional Content Creation

- [ ] Global meta description for homepage
- [ ] Unique meta descriptions for all 32+ pages
- [ ] Social share image (1200x630 px)
- [ ] Favicon (multiple sizes)
- [ ] Apple touch icon
- [ ] Custom 404 page content
- [ ] Cookie consent banner text (Dutch)
- [ ] Form confirmation emails (Dutch templates)
- [ ] Form notification emails (Dutch templates)
- [ ] WooCommerce email templates (Dutch, branded)
- [ ] Newsletter signup confirmation (if implemented)
- [ ] Website Beheergids (Dutch — for client handover)

### 45.3 Content Review Sign-Off

- [ ] All Dutch content reviewed by native Dutch speaker
- [ ] All service descriptions factually accurate (client confirmed)
- [ ] All legal content reviewed by legal professional
- [ ] All pricing and product information accurate
- [ ] All contact information verified
- [ ] All external links verified
- [ ] Client sign-off on all content
