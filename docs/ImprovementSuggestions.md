# Improvement Suggestions — HDS Onderhoudsdiensten

## Priority Matrix

| Priority | Definition | Action Timeline |
|---|---|---|
| **P0 — CRITICAL** | Site functionality broken, legal risk, or conversion blocked | Immediate (this week) |
| **P1 — HIGH** | Significant SEO/UX deficit, competitive disadvantage | Short-term (1-2 weeks) |
| **P2 — MEDIUM** | Quality-of-life improvements, best practices | Medium-term (1 month) |
| **P3 — LOW** | Nice-to-have enhancements | Long-term (2-3 months) |

---

## P0 — CRITICAL FIXES

### 1. Fix Contact Page 500 Error
**Issue:** /contact/ returns PHP fatal error. Primary conversion path broken.
**Action:** Debug PHP error on /contact/ page. Likely causes:
- Formidable Forms plugin conflict with WordPress 6.2.9
- PHP version incompatibility (update PHP to 8.0+)
- Corrupted Divi page builder data for contact page
- Plugin update chain reaction (update WP → update plugins → test)
**Quick Fix:** If plugin debugging fails, recreate contact page with a fresh Formidable form or alternative form plugin.

### 2. Fix Regular Cleaning Page 404
**Issue:** /reguliere-schoonmaak/ and /?page_id=318 both return 404. Navigation and homepage icon both broken.
**Action:**
- Check WordPress trash for page with ID 318
- If page deleted, create new page at /reguliere-schoonmaak/ with proper content
- Ensure blog post /2015/06/29/kwaliteit-veiligheid/ doesn't conflict (if it was a page slug)
- Update ALL links (homepage icon grid, navigation, internal references)

### 3. Fix Page Sitemap 500 Error
**Issue:** /page-sitemap.xml returns 500. Yoast SEO cannot generate page sitemap.
**Action:**
- Update Yoast SEO to latest version
- Check PHP error logs for sitemap generation failures
- Verify WordPress permalink structure is set to "Post name"
- Clear Yoast SEO data and regenerate

### 4. Add Privacy Policy (AVG/GDPR)
**Issue:** No privacyverklaring on site. Legal requirement under AVG (Dutch GDPR implementation).
**Action:**
- Create /privacyverklaring/ page
- Must include: data collection purposes, cookie usage, third-party sharing, user rights, contact for data requests
- Add link to footer (required by AVG)
- Add to main navigation or footer on every page
- Add consent checkbox to contact form (if form is restored)

### 5. Add Cookie Consent
**Issue:** No cookie banner/wall. ePrivacy Directive and AVG require consent for non-essential cookies.
**Action:**
- Install cookie consent plugin (e.g., Complianz, CookieYes, or Cookiebot)
- Configure to block WooCommerce/social media cookies until consent
- Link to privacy policy page
- Log consent (AVG requirement)

### 6. Update All Software
**Issue:** WordPress 6.2.9, Divi 4.16.1, WooCommerce 8.2.5, Yoast 21.8.1 — all outdated.
**Action:**
- BACKUP entire site first (files + database)
- Update PHP to 8.0 or 8.1
- Update WordPress to latest 6.6.x
- Update Divi theme to latest 4.27.x
- Update WooCommerce to latest 9.x
- Update Yoast SEO to latest 23.x
- Update Formidable Forms
- Test all pages, forms, and shop after each update
- Disable XML-RPC after updates (use .htaccess rule or plugin)

---

## P1 — HIGH PRIORITY

### 7. Add Meta Descriptions to All Pages
**Issue:** Zero custom meta descriptions. SERP result is plain "HOME - HDS Onderhoudsdiensten".
**Action:** Write 150-160 character Dutch meta descriptions for every page using Yoast SEO, including:
- Primary keyword
- Location (regio Bergen op Zoom / West-Brabant)
- Call to action ("Vrijblijvende offerte aanvragen?")
- Unique value proposition

### 8. Implement LocalBusiness Schema
**Issue:** No structured data for local business. Missing from Google Knowledge Graph and local pack.
**Action:** Add LocalBusiness or HomeAndConstructionBusiness schema with:
- Company name: HDS Onderhoudsdiensten
- Address (needs to be added to site first)
- Phone: +31164652846
- Email: info@helderduidelijkschoon.nl
- URL: https://helderduidelijkschoon.nl/
- Opening hours (needs to be determined)
- Geo coordinates (if applicable)
- Service area: West-Brabant / Zeeland

### 9. Add Physical Address to Website
**Issue:** No address, KVK, or BTW on site. Trust signals missing. Legal requirement for Dutch businesses.
**Action:**
- Add full business address to footer
- Add KVK number to footer ("KVK: XXXXXXXX")
- Add BTW number to footer ("BTW: NLXXXXXXXXXB01")
- Add to contact page (once fixed)

### 10. Link/Embed Google Business Profile
**Action:**
- Claim/create Google Business Profile for HDS Onderhoudsdiensten
- Add GBP link to website footer
- Ensure NAP (Name, Address, Phone) is identical on GBP and website
- Add Google Maps embed to contact page

### 11. Fix Broken Internal Links
**Action:**
- Update homepage icon "VVE" link: /vve → /vve-service/
- Update homepage icon "Glas" link: /glasbewassing → /glasbewassing/
- Update homepage icon "Schoonmaak" link: /reguliere-schoonmaak/ → fix the destination page
- Standardize all trailing slash usage

### 12. Expand Homepage Content
**Issue:** ~30 words on homepage. Google treats this as thin content.
**Action:** Add 300+ words covering:
- Who HDS is and what region they serve
- Key services summary (with links)
- USP statements (permanent trained staff, safety, single point of contact)
- Social proof (logos, testimonials, certifications)
- Strong CTA

### 13. Fix Instagram Widget
**Issue:** Every page shows "Instagram did not return a 200".
**Action:**
- Update Instagram API connection (likely broken due to Meta API changes)
- Or remove widget and replace with manual Instagram link
- Or add Instagram feed plugin that works with current Meta API

---

## P2 — MEDIUM PRIORITY

### 14. Create FAQ Page
**Action:** Add /faq/ or /veelgestelde-vragen/ page with 10-15 common questions:
- "Wat kost schoonmaak?" (What does cleaning cost?)
- "Werkt u ook in [plaatsnaam]?" (Do you work in [town]?)
- "Hoe vaak komt u schoonmaken?" (How often do you clean?)
- "Gebruikt u milieuvriendelijke producten?" (Do you use eco-friendly products?)
- Add FAQ structured data (Yoast block or manual)

### 15. Add Service Schema to Service Pages
**Action:** Add Service schema to each service page with:
- Service name
- Service description
- Provider (Organization)
- Area served
- Service type

### 16. Create Blog Content
**Action:**
- Delete "Hallo wereld!" default post
- Write 5-10 blog posts on:
  - Schoonmaaktips voor kantoren
  - Waarom regelmatig glasbewassing belangrijk is
  - Vloeronderhoud: tips voor verschillende vloertypes
  - Duurzaam schoonmaken (MVO)
  - Wat doet een VvE schoonmaakbedrijf?
- Target long-tail Dutch keywords
- Post 1-2x per month minimum

### 17. Improve Vacancies Page
**Issue:** Vacancies are JPEG images of Word documents. Not accessible, not SEO-friendly, looks unprofessional.
**Action:**
- Convert vacancy content to proper HTML text
- Add structured job data (JobPosting schema)
- Add application email link or form
- Add benefits of working at HDS

### 18. Expand Thin Service Pages
**Issue:** Industriële Schoonmaak (60 words), Over HDS (120 words), Referenties (25 words).
**Action:**
- Industriële Schoonmaak: Add bullet points, equipment used, industry examples, safety certifications
- Over HDS: Add company history, team structure, mission statement, certifications, areas served
- Referenties: Add client names (with permission), project descriptions, results achieved

### 19. Add Social Proof
**Action:**
- Fix HMS Testimonials plugin to display testimonials
- Add 5+ client testimonials with names, companies, and photos
- Add certification logos (OSB, VCA, etc.)
- Add client/partner logo carousel on homepage or referenties page

### 20. Optimize Images
**Action:**
- Convert PNG icons to WebP format
- Compress all images
- Add descriptive alt text to all images (SEO + accessibility)
- Add Open Graph images for social sharing

---

## P3 — LOW PRIORITY

### 21. Add Online Booking System
**Action:** Integrate a booking/calendar system for service inquiries (e.g., Calendly, Bookly, or Amelia).

### 22. Add Live Chat / WhatsApp Button
**Action:** Add WhatsApp Business chat widget or live chat for instant customer contact.

### 23. Improve Footer
**Action:** Structured footer with:
- Company info (address, KVK, BTW)
- Service links
- Contact info
- Privacy policy link
- Social media icons
- Certification logos

### 24. Add Portfolio / Case Studies
**Action:** Create project pages with before/after photos for:
- Gevelreiniging projects
- Vloeronderhoud transformations
- Oplevering schoonmaak results

### 25. Performance Optimization
**Action:**
- Install caching plugin (W3 Total Cache or WP Rocket)
- Minify CSS/JS
- Optimize Google Fonts loading (subset to used weights only)
- Lazy load images
- Consider CDN for static assets
- Test with Google PageSpeed Insights, target 90+ mobile

### 26. Accessibility Audit
**Action:**
- Add proper ARIA labels
- Ensure color contrast meets WCAG AA
- Make vacancy content machine-readable
- Add skip-to-content link
- Ensure keyboard navigation works

### 27. Backup & Security Hardening
**Action:**
- Set up automated daily backups (files + database)
- Install security plugin (Wordfence or Sucuri)
- Disable XML-RPC
- Limit login attempts
- Enable two-factor authentication for admin accounts

### 28. Track Analytics
**Action:**
- Install Google Analytics 4 (or GA via Google Site Kit plugin)
- Set up Google Search Console
- Set up conversion tracking (phone clicks, email clicks, form submissions)
- Monitor 404 errors and fix broken links

---

## Estimated Effort Summary

| Phase | Items | Estimated Time |
|---|---|---|
| P0 — Critical Fixes | 6 items | 2-4 days |
| P1 — High Priority | 7 items | 3-5 days |
| P2 — Medium Priority | 7 items | 5-7 days |
| P3 — Low Priority | 8 items | 7-10 days |
| **TOTAL** | **28 items** | **17-26 days** |

---

## Recommended Approach

1. **Week 1:** Execute ALL P0 fixes (critical repairs + legal compliance)
2. **Week 2-3:** Execute ALL P1 fixes (SEO foundation + trust signals)
3. **Month 2:** Execute P2 items (content + social proof)
4. **Month 3+:** Execute P3 items (enhancements + optimization)

Alternatively, with the amount of technical debt and broken infrastructure, a full redesign/rebuild on a modern WordPress stack (or static site generator) could be more cost-effective than patching a 2015 Divi site.
