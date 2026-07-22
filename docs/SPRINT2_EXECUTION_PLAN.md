# HDS Onderhoudsdiensten — Sprint 2 Execution Plan

**Document ID:** SP2-001 | **Version:** 1.0.0 | **Sprint Duration:** 2 Weeks (10 Working Days)
**Predecessor:** Sprint 1 (Foundation complete) | **Successor:** Sprint 3 (Supporting Pages)
**Reference Docs:** SAD-001, BKLG-001, MPS-001 | **Date:** July 2026

---

## 1. Sprint Goal

**Build all core pages and conversion paths.** By end of Sprint 2, the website must have a complete Home page, all 7 service pages, 2 category landing pages, a fully functional Contact page with working form, a Quote Request page with file upload, a Thank You page, and a custom 404 page. A visitor must be able to discover any service, read about it, and request a quote entirely through the website.

---

## 2. Sprint Overview

| Metric | Value |
|---|---|
| **Sprint Duration** | 10 working days (Week 3 + Week 4) |
| **Total Stories** | 11 (E-CORE-01 through E-CORE-11) |
| **Total Story Points** | 72 |
| **Team Size** | 2 developers recommended |
| **Pages to Build** | 13 |
| **Templates to Build** | 3 new (Service, Category Landing, Contact/Quote) |
| **Forms to Build** | 2 (Contact GF-1, Quote GF-2) |
| **Block Patterns Used** | 8 (Hero, Service Card Grid, CTA Banner, Content+Image, Service Icon List, Cross-Sell, Contact Info+Map, 404 Content) |

---

## 3. Prerequisites — Sprint 1 Completion Verification

Before Sprint 2 Day 1, verify all Sprint 1 deliverables are complete:

| # | Prerequisite | Verified By | Status |
|---|---|---|---|
| P01 | WordPress 6.7+ installed on staging (E-INFRA-01) | Dev | [ ] |
| P02 | All plugins installed + activated (E-INFRA-02) | Dev | [ ] |
| P03 | Cloudflare CDN + SSL configured (E-INFRA-03) | Dev | [ ] |
| P04 | SMTP configured + email deliverability verified (E-INFRA-04) | Dev | [ ] |
| P05 | Daily backups configured + test restore verified (E-INFRA-05) | Dev | [ ] |
| P06 | Theme foundation built: theme.json, header.php, footer.php, functions.php, base CSS (E-INFRA-06) | Dev | [ ] |
| P07 | All 16 block patterns registered (E-INFRA-07) | Dev | [ ] |
| P08 | Design system implemented in code: CSS custom properties, typography, spacing (E-INFRA-08) | Dev | [ ] |
| P09 | CPTs registered: hds_testimonial, hds_vacancy (E-INFRA-07 CPT portion) | Dev | [ ] |
| P10 | Custom fields registered via ACF or register_post_meta | Dev | [ ] |
| P11 | Company Information Customizer section built | Dev | [ ] |
| P12 | Brand tokens resolved (MI-06, MI-07, MI-08) | Client | [ ] |
| P13 | Client address provided (MI-01 — needed for Contact page) | Client | [ ] |

**GATE CHECK:** If ANY prerequisite is incomplete, Sprint 2 does NOT start. Escalate to Solution Architect.

---

## 4. Story Inventory

| Story ID | Story Name | Points | Est. Hours | Priority | Owner |
|---|---|---|---|---|---|
| E-CORE-01 | Build Home Page Template + Content | 8 | 6 | P0 | Dev A |
| E-CORE-02 | Build Service Page Template | 8 | 6 | P0 | Dev A |
| E-CORE-03 | Build Glasbewassing Page (P02) | 5 | 3 | P0 | Dev B |
| E-CORE-04 | Build Gevelreiniging Page (P03) | 5 | 3 | P0 | Dev B |
| E-CORE-05 | Build Reguliere Schoonmaak Page (P04) — CRITICAL | 8 | 4 | P0 | Dev B |
| E-CORE-06 | Build Vloer, VVE, Oplevering Pages (P05-P07) | 8 | 6 | P0 | Dev B |
| E-CORE-07 | Build Industriele Schoonmaak Page (P08) | 5 | 3 | P0 | Dev B |
| E-CORE-08 | Build Category Landing Pages (P09, P10) | 5 | 3 | P1 | Dev B |
| E-CORE-09 | Build Contact Page + Form (P16) — CRITICAL | 13 | 8 | P0 | Dev A |
| E-CORE-10 | Build Offerte Aanvragen Page + Form (P17) | 8 | 5 | P1 | Dev A |
| E-CORE-11 | Build Bedankt Page (P32) | 2 | 1 | P0 | Dev A |

**Total:** 11 stories | 72 points | ~51 hours | 2 developers

---

## 5. Dependency Graph

```
E-CORE-01 (Home Template)
    |
    +---> E-CORE-02 (Service Template)
    |         |
    |         +---> E-CORE-03 (Glasbewassing)    [Dev B]
    |         +---> E-CORE-04 (Gevelreiniging)   [Dev B]  } ALL PARALLEL
    |         +---> E-CORE-05 (Reguliere) ★CRIT  [Dev B]  } once Service
    |         +---> E-CORE-06 (Vloer+VVE+Opl)    [Dev B]  } template is
    |         +---> E-CORE-07 (Industrieel)      [Dev B]  } built
    |         +---> E-CORE-08 (Landings P09,P10) [Dev B]
    |
    +---> E-CORE-09 (Contact Form) ★CRIT [Dev A]
              |
              +---> E-CORE-10 (Quote Form) [Dev A]
              |
              +---> E-CORE-11 (Bedankt) [Dev A]

LEGEND:
  ★CRIT = Critical path. Contact form and Reguliere Schoonmaak are the highest-impact fixes.
  Dev A = Developer 1 (templates + conversion forms)
  Dev B = Developer 2 (service pages + landing pages)
```

---

## 6. Day-by-Day Task Plan

### WEEK 1 — FOUNDATION + SERVICE PAGES

---

#### DAY 1 (Monday) — Templates

| Time | Task | Owner | Story | Deliverable |
|---|---|---|---|---|
| 09:00 | Sprint 2 kickoff. Verify all Sprint 1 prerequisites (Section 3). | Both | — | Gate check passed |
| 10:00 | **E-CORE-01: Build front-page.php** | Dev A | E-CORE-01 | Home template with all 8 block sections |
| 10:00 | **E-CORE-01: Write Homepage Dutch content** | Dev A | E-CORE-01 | 300+ words Dutch content |
| 14:00 | **E-CORE-02: Build page-service.php** | Dev A | E-CORE-02 | Service template with Hero, Content, Cross-Sell, CTA |
| 14:00 | **E-CORE-02: Register "Service" template + custom fields** | Dev A | E-CORE-02 | Template selectable in editor; ACF fields working |
| 17:00 | Day 1 review: Are both templates rendering on staging? | Both | — | Homepage + Service template visible |

**Day 1 Deliverables:**
- [ ] front-page.php renders Home page with all 8 content blocks
- [ ] page-service.php renders with Hero + Content + Cross-Sell + CTA sections
- [ ] "Service" template selectable in Page editor
- [ ] Service custom fields (subtitle, hero_image, service_icon, cta_override) save + display
- [ ] Homepage content >= 300 words Dutch

---

#### DAY 2 (Tuesday) — Service Pages (Batch 1)

| Time | Task | Owner | Story | Deliverable |
|---|---|---|---|---|
| 09:00 | **E-CORE-03: Build P02 Glasbewassing** | Dev B | E-CORE-03 | Migrate 180 words + expand to 300+ |
| 09:00 | **E-CORE-09: Build page-contact.php** | Dev A | E-CORE-09 | Contact template (two-column) |
| 11:00 | **E-CORE-04: Build P03 Gevelreiniging** | Dev B | E-CORE-04 | Standardize naming, expand to 300+ |
| 11:00 | **E-CORE-09: Build Gravity Forms Contact Form (GF-1)** | Dev A | E-CORE-09 | 9-field form with reCAPTCHA |
| 14:00 | **E-CORE-05: Build P04 Reguliere Schoonmaak ★CRIT** | Dev B | E-CORE-05 | NEW page from scratch, 300+ words |
| 14:00 | **E-CORE-09: Configure contact form notifications** | Dev A | E-CORE-09 | Email templates (Dutch), redirect to /bedankt/ |
| 17:00 | Day 2 review | Both | — | 3 service pages + contact form working |

**Day 2 Deliverables:**
- [ ] P02 Glasbewassing: 300+ words, H2 sections, cross-links, CTA
- [ ] P03 Gevelreiniging: standardized naming (not "Gevelonderhoud"), 300+ words
- [ ] P04 Reguliere Schoonmaak: NEW page, 300+ words, cross-links (highest impact fix)
- [ ] page-contact.php renders two-column layout
- [ ] GF-1 Contact Form: all 9 fields, reCAPTCHA, privacy checkbox

---

#### DAY 3 (Wednesday) — Service Pages (Batch 2) + Contact Form Completion

| Time | Task | Owner | Story | Deliverable |
|---|---|---|---|---|
| 09:00 | **E-CORE-06: Build P05 Vloeronderhoud** | Dev B | E-CORE-06 | Migrate 140 words + expand, 7 service bullets |
| 09:00 | **E-CORE-09: Test contact form end-to-end** | Dev A | E-CORE-09 | Submit form -> redirect -> email delivered |
| 11:00 | **E-CORE-06: Build P06 VVE Service** | Dev B | E-CORE-06 | Migrate 100 words + expand, VvE Belang link |
| 11:00 | **E-CORE-09: Build Contact Info Block** | Dev A | E-CORE-09 | Phone (tel:), Email (mailto:), Address (if MI-01), KVK/BTW (if MI-02/03) |
| 14:00 | **E-CORE-06: Build P07 Oplevering Schoonmaak** | Dev B | E-CORE-06 | Migrate 90 words + expand, "0-beurt" concept |
| 14:00 | **E-CORE-09: Add contact page to navigation + homepage icon grid** | Dev A | E-CORE-09 | Page linked from all correct locations |
| 17:00 | Day 3 review | Both | — | 6 of 7 service pages complete + contact form working |

**Day 3 Deliverables:**
- [ ] P05 Vloeronderhoud: 300+ words, 7-floor-service bullet list, holiday scheduling mention
- [ ] P06 VVE Service: 300+ words, VvE Belang link functional
- [ ] P07 Oplevering Schoonmaak: 300+ words, 5-task bullet list
- [ ] Contact form: submits successfully, email delivered within 2 minutes
- [ ] Contact Info Block: phone clickable, email clickable, conditional address/KVK/BTW
- [ ] Contact page linked from main nav, homepage icon grid, footer

---

#### DAY 4 (Thursday) — Remaining Service Pages + Landings

| Time | Task | Owner | Story | Deliverable |
|---|---|---|---|---|
| 09:00 | **E-CORE-07: Build P08 Industriele Schoonmaak** | Dev B | E-CORE-07 | Rewrite from 60 words to 300+ with bullet list |
| 11:00 | **E-CORE-08: Build page-category-landing.php** | Dev B | E-CORE-08 | Category landing template |
| 14:00 | **E-CORE-08: Build P09 Glas & Gevel + P10 Schoonmaakdiensten** | Dev B | E-CORE-08 | Both landings 500+ words each |
| 09:00 | **E-CORE-10: Build page-quote.php** | Dev A | E-CORE-10 | Quote template (extended Contact) |
| 11:00 | **E-CORE-10: Build Gravity Forms Quote Form (GF-2)** | Dev A | E-CORE-10 | 12-field form + file upload |
| 14:00 | **E-CORE-10: Configure quote form notifications + file upload security** | Dev A | E-CORE-10 | Download link in email, server-side validation |
| 17:00 | Day 4 review | Both | — | ALL service pages + landings built; Quote form working |

**Day 4 Deliverables:**
- [ ] P08 Industriele Schoonmaak: 300+ words (was 60), bullet list, safety section
- [ ] page-category-landing.php renders Hero + Intro + Service Card Grid + CTA
- [ ] P09 Glas & Gevel: 500+ words, 2 service cards
- [ ] P10 Schoonmaakdiensten: 500+ words, 5 service cards
- [ ] page-quote.php renders with extended form
- [ ] GF-2 Quote Form: all 12 fields, file upload (PDF/JPG/PNG/DOCX, max 5MB)
- [ ] File upload: server-side validation, file rename, download link in email

---

#### DAY 5 (Friday) — Completion + Cross-Cutting

| Time | Task | Owner | Story | Deliverable |
|---|---|---|---|---|
| 09:00 | **E-CORE-11: Build Bedankt page (P32)** | Dev A | E-CORE-11 | Thank-you page with dynamic message |
| 09:00 | **Cross-cut: Add cross-links to all service pages** | Dev B | All | Each service links to 2-3 related services |
| 11:00 | **Cross-cut: Verify all pages in navigation + footer** | Both | All | Every page reachable from nav + footer |
| 11:00 | **Cross-cut: Set SEO metadata on all Sprint 2 pages** | Both | All | Unique title + meta description per page |
| 14:00 | **Dev A: Verify 404.php from Sprint 1 still works + enhance** | Dev A | E-CORE-01 | Search bar + key links + contact info |
| 14:00 | **Dev B: Homepage service card grid — verify all 7 service cards link correctly** | Dev B | E-CORE-01 | Zero broken links from homepage |
| 16:00 | **Sprint 2 Week 1 retrospective** | Both | — | What went well, what blocked, adjustments for Week 2 |
| 17:00 | Week 1 complete: ALL code committed, ALL pages on staging | Both | — | Client preview of Week 1 progress |

**Day 5 Deliverables:**
- [ ] P32 Bedankt: dynamic message based on ?type= parameter, noindex
- [ ] All service pages have cross-links to 2-3 related services
- [ ] All pages linked from navigation, homepage, and footer
- [ ] All pages have unique title tags (50-60 chars) + meta descriptions (150-160 chars)
- [ ] 404 page: search bar + links to Home, Diensten, Contact, FAQ + phone/email
- [ ] Homepage service card grid: all 7 cards link to correct service pages

---

### WEEK 2 — POLISH, CONTENT, TESTING

---

#### DAY 6 (Monday) — Content Review + Edge Cases

| Time | Task | Owner | Deliverable |
|---|---|---|---|
| 09:00 | **Content review: all 13 Sprint 2 pages** | Both | Word count check. Every page >= 300 words (service) or 500+ (landing) |
| 11:00 | **Dutch native speaker review** | Reviewer | All content reviewed for grammar, tone, spelling |
| 13:00 | **Empty states verification** | Dev A | Client Logo Carousel hidden (no logos). Testimonial Block hidden (no testimonials). Latest Blog Posts hidden (no posts). |
| 14:00 | **Loading states: form submit buttons** | Dev A | Gravity Forms AJAX enabled. Button changes to "Versturen..." with disabled state + spinner. |
| 15:00 | **Cross-browser quick check** | Dev B | Chrome, Firefox, Safari, Edge: all 13 pages render correctly |
| 17:00 | Day 6 review | Both | Content complete, edge cases handled |

**Day 6 Deliverables:**
- [ ] All pages pass word count minimums
- [ ] Dutch content reviewed by native speaker
- [ ] Empty states: conditional sections hidden (not rendered empty)
- [ ] Form AJAX enabled: loading state on submit
- [ ] Cross-browser: no rendering issues

---

#### DAY 7 (Tuesday) — Responsive & Accessibility Baseline

| Time | Task | Owner | Deliverable |
|---|---|---|---|
| 09:00 | **Responsive testing: all 13 pages** | Dev B | Mobile (375px), Tablet (768px), Desktop (1024px), Wide (1440px) |
| 10:00 | **Mobile menu testing** | Dev B | Hamburger opens/closes. All nav items reachable. Touch targets >= 44px. |
| 11:00 | **Accessibility audit — axe DevTools** | Dev A | Run on all 13 pages. Fix any critical or serious issues. |
| 13:00 | **Accessibility audit — keyboard navigation** | Dev A | Tab through every interactive element on Home, 2 service pages, Contact form. Verify focus indicators. |
| 14:00 | **Accessibility audit — Lighthouse** | Dev A | Run Lighthouse on all page templates. Target score = 100. Fix issues. |
| 15:00 | **Accessibility — screen reader quick test** | Dev A | NVDA (Windows). Test Contact form: Are labels read? Are errors announced? Is submit confirmation announced? |
| 17:00 | Day 7 review | Both | Responsive + accessibility baseline established |

**Day 7 Deliverables:**
- [ ] All pages responsive: no horizontal scroll at any breakpoint
- [ ] Mobile menu fully functional
- [ ] axe DevTools: zero critical + zero serious on all pages
- [ ] Lighthouse Accessibility = 100 on all templates
- [ ] Keyboard: all elements focusable + operable, focus indicator visible
- [ ] Screen reader: forms announced correctly

---

#### DAY 8 (Wednesday) — Form Testing + Error Handling

| Time | Task | Owner | Deliverable |
|---|---|---|---|
| 09:00 | **Contact Form (GF-1): validation test** | Dev A | Submit empty -> required field errors (Dutch). Invalid email -> format error. Short message -> min 10 chars error. Checkbox unchecked -> consent required error. |
| 10:00 | **Contact Form (GF-1): success + email test** | Dev A | Valid submission -> redirect /bedankt/?type=contact. Confirmation email received by user. Notification email received by info@. Both in Dutch, branded. |
| 11:00 | **Contact Form (GF-1): spam test** | Dev A | Honeypot field -> blocked. reCAPTCHA v3 -> score check in GF settings. |
| 13:00 | **Quote Form (GF-2): validation + file upload test** | Dev A | File too large (>5MB) -> clear error. Wrong file type (.exe) -> clear error. Correct file (PDF 1MB) -> upload success, download link in email. |
| 14:00 | **Quote Form (GF-2): success + email test** | Dev A | Valid submission -> redirect /bedankt/?type=offerte. Confirmation + notification emails received. |
| 15:00 | **Error state: SMTP down simulation** | Dev A | What happens if Post SMTP cannot deliver? Entry stored in GF. Admin notified. User sees success page. |
| 17:00 | Day 8 review | Both | All form scenarios tested and passing |

**Day 8 Deliverables:**
- [ ] Contact form: validation, success, email delivery, spam blocking — all pass
- [ ] Quote form: validation, file upload, email delivery — all pass
- [ ] All form errors in Dutch, inline, aria-describedby associated
- [ ] Email delivery failure: graceful degradation (entry stored)

---

#### DAY 9 (Thursday) — URL Verification + Internal Linking + Performance

| Time | Task | Owner | Deliverable |
|---|---|---|---|
| 09:00 | **URL verification: all 13 pages** | Dev B | Each page at correct URL. No 404s. Trailing slashes present. |
| 10:00 | **Screaming Frog crawl: staging** | Dev B | Crawl staging site. Zero 4xx errors. Zero broken internal links. Zero orphan pages. |
| 11:00 | **Internal linking audit** | Dev B | Each service page links to 2-3 related services + Contact/Offerte + Kwaliteit. Homepage links to all services. Navigation links to all pages. Footer links to all pages. |
| 13:00 | **Performance baseline: Lighthouse** | Both | Run Lighthouse on Home, 1 service page, Contact page. Document scores. |
| 14:00 | **Performance optimization if needed** | Both | Images: WebP format, explicit dimensions. Fonts: self-hosted, preloaded. CSS: unused CSS check. JS: deferred. |
| 16:00 | **Content snapshot for client review** | Both | Screenshots of all 13 pages. Share via staging URL (password-protected). |
| 17:00 | Day 9 review | Both | Technical readiness for Sprint 2 review |

**Day 9 Deliverables:**
- [ ] All 13 pages at correct URLs (zero 404s)
- [ ] Screaming Frog: zero broken internal links, zero orphans
- [ ] Internal linking: every page reachable from at least 2 other pages + nav + footer
- [ ] Performance: PSI baseline documented (target 90+ mobile may not be reached until Sprint 5 image optimization)

---

#### DAY 10 (Friday) — Sprint Review + Retrospective

| Time | Task | Owner | Deliverable |
|---|---|---|---|
| 09:00 | **Sprint 2 review preparation** | Both | Ensure all code committed to dev branch. All pages on staging. Demo walkthrough prepared. |
| 10:00 | **Sprint 2 Demo** | Both + Client | Walk through: Homepage -> Service pages -> Category landings -> Contact form submit -> Quote form submit -> Bedankt page -> 404 page. |
| 11:00 | **Client feedback collection** | Client | Document any change requests or issues. |
| 13:00 | **Sprint 2 retrospective** | Both | What went well? What didn't? What should we do differently in Sprint 3? |
| 14:00 | **Sprint 2 acceptance** | Both + Client | Verify all acceptance criteria (Section 10). Client sign-off. |
| 15:00 | **Sprint 3 preparation** | Both | Review Sprint 3 backlog. Identify any Sprint 2 spillover. Update estimates. |
| 17:00 | Sprint 2 closed | Both | Merge dev -> staging. Prepare for Sprint 3 start. |

**Day 10 Deliverables:**
- [ ] Sprint 2 Demo completed with client
- [ ] Client feedback documented
- [ ] Sprint retrospective notes
- [ ] Sprint 2 acceptance: all AC met, client sign-off obtained
- [ ] Sprint 3 backlog reviewed and ready

---

## 7. Parallel Execution Tracks

```
TRACK A (Dev A — Templates + Conversion Forms):
  Day 1-5: E-CORE-01 (Home) -> E-CORE-02 (Service template) -> E-CORE-09 (Contact) -> E-CORE-10 (Quote) -> E-CORE-11 (Bedankt)

TRACK B (Dev B — Service Pages + Landings):
  Day 2-4: E-CORE-03..07 (7 service pages) -> E-CORE-08 (2 landings)

Day 5: Cross-cutting (both developers)
Day 6-9: Testing + QA (both developers)
Day 10: Sprint review (both developers + client)
```

---

## 8. Specifications Per Deliverable

### 8.1 Home Page (front-page.php — P01)

**URL:** /
**Template:** front-page.php
**Min Words:** 300+
**Blocks (top to bottom):**
1. Hero Section: H1 = Tagline "Helder en Duidelijk voor het Schoonste resultaat!" Subtitle = USP summary (1-2 sentences). CTA button = "Vrijblijvende offerte" -> /offerte-aanvragen/
2. Service Card Grid: 7 cards. Each card: service_icon (custom field), title, 1-sentence description, "Lees meer" link. Query all pages with Service template.
3. USP Grid: 4-6 cards. Icon + heading + short text. Pre-loaded with: Vast opgeleid personeel, Veiligheid & Certificering, Een aanspreekpunt, Maatwerk planning, Milieubewust (MVO), Regio specialist.
4. Client Logo Carousel: **Conditional — hide entire section if no logos uploaded.** Query from media or custom field. Placeholder for Sprint 3 (when client provides MI-10).
5. Testimonial Block: **Conditional — hide entire section if no testimonials.** Query hds_testimonial CPT. Placeholder for Sprint 3 (when client provides MI-11).
6. CTA Banner: "Wilt u een vrijblijvende offerte? Wij denken graag met u mee." Button -> /offerte-aanvragen/
7. Service Area: Text or map. Text: "Wij bedienen bedrijven in heel West-Brabant en Zeeland." Map embed only if MI-01 (address) provided.
8. Latest Blog Posts: **Conditional — hide if no posts.** 3 most recent posts. Placeholder for Sprint 5 (blog setup).

### 8.2 Service Page Template (page-service.php — P02-P08)

**Template:** page-templates/page-service.php
**Min Words:** 300+ per page
**Custom Fields:** hds_subtitle, hds_hero_image, hds_service_icon, hds_cta_override
**Sections:**
1. Breadcrumbs: Home > [Page Name]
2. Hero: H1 = page title. Subtitle = hds_subtitle (if set). CTA button = hds_cta_override or default "Vrijblijvende offerte" -> /offerte-aanvragen/. Background = hds_hero_image (if set).
3. Content Area (the_content): Blocks inserted by content editor. Expected: intro paragraph, H2 approach/process, H2 service details (icon list block pattern), H2 safety/quality, additional blocks.
4. Cross-Sell Services: Block pattern. "Gerelateerde diensten" heading. 2-3 related service cards. Configured per page (editor selects which services to show).
5. CTA Banner: "Vrijblijvende offerte aanvragen" -> /offerte-aanvragen/
6. [Optional] FAQ Accordion: Service-specific FAQs. Editor can add or omit.

**Cross-Link Rules (per service page):**

| Page | Links To |
|---|---|
| P02 Glasbewassing | Gevelreiniging (P03), Reguliere Schoonmaak (P04), Oplevering Schoonmaak (P07) |
| P03 Gevelreiniging | Glasbewassing (P02), Industriele Schoonmaak (P08) |
| P04 Reguliere Schoonmaak | Vloeronderhoud (P05), Glasbewassing (P02), VVE Service (P06) |
| P05 Vloeronderhoud | Reguliere Schoonmaak (P04), Oplevering Schoonmaak (P07) |
| P06 VVE Service | Reguliere Schoonmaak (P04), Glasbewassing (P02) |
| P07 Oplevering Schoonmaak | Reguliere Schoonmaak (P04), Glasbewassing (P02), Vloeronderhoud (P05) |
| P08 Industriele Schoonmaak | Reguliere Schoonmaak (P04), Gevelreiniging (P03) |

### 8.3 Contact Page (page-contact.php — P16) — CRITICAL

**URL:** /contact/
**Template:** page-templates/page-contact.php
**Layout:** Two-column (60% form / 40% info)
**Form:** Gravity Forms GF-1

**Left Column — Contact Form (GF-1):**
| Field | Type | Required | Notes |
|---|---|---|---|
| Naam | Text | Yes | |
| Bedrijf | Text | No | |
| E-mailadres | Email | Yes | Valid email format validation |
| Telefoonnummer | Tel | No | Dutch format accepted (+31, 06, local) |
| Onderwerp | Dropdown | Yes | Options: "Offerte aanvragen", "Vraag over diensten", "Klacht of opmerking", "Anders" |
| Bericht | Textarea | Yes | Min 10 characters |
| Privacy akkoord | Checkbox | Yes | Unchecked default. Label: "Ik ga akkoord met de [privacyverklaring](/privacyverklaring/)." |
| Anti-spam | reCAPTCHA v3 + Honeypot | Yes | Invisible to user |
| Verzenden | Submit | — | Button: "Verstuur bericht" |

**Post-Submit:** Redirect to /bedankt/?type=contact
**Confirmation Email:** Dutch, branded. "Bedankt voor uw bericht. Wij streven ernaar binnen 1 werkdag te reageren."
**Notification Email:** All submitted data to info@helderduidelijkschoon.nl

**Right Column — Contact Info Block:**
| Element | Conditional | Content |
|---|---|---|
| Phone | Always | 0164-652846 (tel: link) |
| Email | Always | info@helderduidelijkschoon.nl (mailto: link) |
| Address | If MI-01 provided | [Straat + Huisnummer], [Postcode] [Plaats] |
| KVK | If MI-02 provided | KVK: [XXXXXXXX] |
| BTW | If MI-03 provided | BTW: [NLXXXXXXXXXB01] |
| Hours | If MI-04 provided | [Openingstijden per dag] |
| Social | Always | Facebook icon link + Instagram icon link |
| Map | If MI-01 provided | Google Maps iframe embed (only load after cookie consent) |

### 8.4 Offerte Aanvragen (page-quote.php — P17)

**URL:** /offerte-aanvragen/
**Template:** page-templates/page-quote.php
**Form:** Gravity Forms GF-2

**Quote Form Fields (GF-2):**
| Field | Type | Required | Notes |
|---|---|---|---|
| Naam | Text | Yes | |
| Bedrijf | Text | Yes | |
| E-mailadres | Email | Yes | |
| Telefoonnummer | Tel | Yes | |
| Gewenste dienst | Checkboxes (multi) | Yes | Glasbewassing, Gevelreiniging, Reguliere Schoonmaak, Vloeronderhoud, VVE Service, Oplevering Schoonmaak, Industriele Schoonmaak, Anders |
| Type gebouw | Dropdown | No | Kantoor, Wooncomplex/VvE, School, Zorginstelling, Fabriek/Magazijn, Bouwproject, Anders |
| Postcode / Plaats | Text | Yes | Validate Dutch postcode: regex /^[1-9][0-9]{3}\s?[A-Z]{2}$/i |
| Beschrijving aanvraag | Textarea | No | |
| Gewenste planning | Dropdown | No | Zo snel mogelijk, Binnen 2 weken, Binnen 1 maand, Binnen 3 maanden, Orienterend / geen haast |
| Hoe heeft u ons gevonden? | Dropdown | No | Google / Zoekmachine, VvE Belang, Social media, Collega / Relatie, Anders |
| Bestand uploaden | File Upload | No | Max 5MB. Types: PDF, JPG, PNG, DOCX. Server-side: MIME validation + file rename. |
| Privacy akkoord | Checkbox | Yes | Unchecked default |
| Anti-spam | reCAPTCHA v3 | Yes | Invisible |
| Offerte aanvragen | Submit | — | Button |

**Post-Submit:** Redirect to /bedankt/?type=offerte
**Confirmation Email:** Dutch, branded. Includes summary of submitted data. "Wij streven ernaar binnen 1 werkdag contact op te nemen."
**Notification Email:** All data + download link for uploaded file (NOT inline attachment) to info@helderduidelijkschoon.nl.

### 8.5 Bedankt Page (P32)

**URL:** /bedankt/
**Template:** page.php (default)
**Content:** Heading: "Bedankt voor uw bericht" (if ?type=contact) or "Bedankt voor uw offerte aanvraag" (if ?type=offerte). Subtext: "Wij streven ernaar binnen 1 werkdag te reageren." Fallback contact: phone number. Links to Home, Diensten, Contact.
**SEO:** noindex meta tag. Excluded from XML sitemap.

### 8.6 Category Landing Pages (P09, P10)

**Template:** page-templates/page-category-landing.php
**Min Words:** 500+ each

| Page | URL | Content |
|---|---|---|
| P09 Glas & Gevel | /glas-en-gevel/ | Hero (H1: "Glas & Gevel Reiniging"). Intro paragraph (what these services cover, who they're for). Service Card Grid: Glasbewassing + Gevelreiniging cards. CTA Banner. |
| P10 Schoonmaakdiensten | /schoonmaakdiensten/ | Hero (H1: "Schoonmaakdiensten"). Intro paragraph (overview of all 5 sub-services). Service Card Grid: Reguliere + Vloer + VVE + Oplevering + Industrieel cards. CTA Banner. |

---

## 9. Content Writing Guide (Per Service Page)

### 9.1 Content Structure Template

```
H1: [Service Name]

[Intro Paragraph — 50-80 words]
What this service is. Who it's for. One key differentiator.

## Onze aanpak [H2 — 100-150 words]
How HDS approaches this service. Process steps. Quality measures. 
Check-in/check-out protocol. Management involvement.

## Diensten [H2 — bullet list]
- [Service detail 1]
- [Service detail 2]
- [...]

## Veiligheid & Kwaliteit [H2 — 50-80 words]
Safety certifications. Training. Compliance. Single point of contact.
Periodic quality checks. Direct complaint resolution.

[CTA Button: "Vrijblijvende offerte aanvragen" -> /offerte-aanvragen/]
```

### 9.2 Per-Page Content Notes

| Page | Content Notes |
|---|---|
| P02 Glasbewassing | Existing content is the best-written service page. Migrate + expand. Sections already exist: Veiligheid, Samenwerking, Technieken. Add more process detail and CTA. |
| P03 Gevelreiniging | Standardize name to "Gevelreiniging" (was "Gevelonderhoud"). Keep existing bullet list: impregneren, graffiti, daken/goten, gevel, zonnepanelen, reclameborden. Add approach + safety sections. |
| P04 Reguliere Schoonmaak | **Entirely new page.** Write from scratch. Target: office managers. Mention: daily/weekly scheduling, trained uniformed staff, tailored work planning, no unscheduled visits. |
| P05 Vloeronderhoud | Existing content has excellent 7-item service list (marmoleum, natuursteen, vloerbedekking, hout, machinaal). Expand with approach, floor types served, holiday scheduling flexibility. |
| P06 VVE Service | Expand to highlight: stairwells/halls/garages, minor technical maintenance, outdoor cleaning, VvE Belang listing. |
| P07 Oplevering Schoonmaak | Expand to explain "0-beurt" concept clearly. Keep 5-task list. Add timeline expectations, who it's for (construction PMs). |
| P08 Industriele Schoonmaak | **Completely rewrite from 60 words.** Add bullet list: pipelines, production floors, warehouse racks, machinery, grease/oil removal. Highlight minimal downtime. Safety protocols for industrial environments. |

### 9.3 SEO Per Page (Initial Pass — Full SEO in Sprint 5)

| Page | Primary Keyword | Title Tag Pattern |
|---|---|---|
| P02 | glasbewassing | Glasbewassing — HDS Onderhoudsdiensten |
| P03 | gevelreiniging | Gevelreiniging — HDS Onderhoudsdiensten |
| P04 | reguliere schoonmaak, kantoor schoonmaak | Reguliere Schoonmaak — HDS Onderhoudsdiensten |
| P05 | vloeronderhoud | Vloeronderhoud — HDS Onderhoudsdiensten |
| P06 | vve schoonmaak | VVE Service — HDS Onderhoudsdiensten |
| P07 | oplevering schoonmaak | Oplevering Schoonmaak — HDS Onderhoudsdiensten |
| P08 | industriele schoonmaak | Industriele Schoonmaak — HDS Onderhoudsdiensten |
| P16 | contact | Contact — HDS Onderhoudsdiensten |
| P17 | offerte aanvragen | Offerte Aanvragen — HDS Onderhoudsdiensten |
| P09 | glas gevel reiniging | Glas & Gevel Reiniging — HDS Onderhoudsdiensten |
| P10 | schoonmaakdiensten | Schoonmaakdiensten — HDS Onderhoudsdiensten |

---

## 10. Sprint 2 Acceptance Criteria

### 10.1 Functional

| # | Criterion | Pass Condition |
|---|---|---|
| ACF01 | All 13 pages return HTTP 200 | Manual check on staging |
| ACF02 | Home page displays all 8 content blocks | Manual visual check |
| ACF03 | Service pages display Hero + Content + Cross-Sell + CTA | Manual check on 2 sample pages |
| ACF04 | Contact form submits and delivers email to info@ | Test submission verified |
| ACF05 | Confirmation email delivered to user | Test submission verified |
| ACF06 | Quote form submits with file upload | Test with 1MB PDF |
| ACF07 | File upload rejects wrong type (.exe) | Test with invalid file |
| ACF08 | File upload rejects oversized file (>5MB) | Test with 6MB file |
| ACF09 | Contact Info Block: phone clickable (tel:), email clickable (mailto:) | Manual click |
| ACF10 | All service pages have cross-links to 2-3 related services | Manual audit |
| ACF11 | All pages linked from main navigation, homepage icon grid, and footer | Manual audit |
| ACF12 | Bedankt page displays correct message based on ?type= parameter | Test both contact and offerte |
| ACF13 | 404 page displays search bar + key links + contact info | Navigate to non-existent URL |
| ACF14 | 404 page returns actual HTTP 404 | Check response headers |
| ACF15 | Bedankt page has noindex meta tag | Check page source |

### 10.2 Content

| # | Criterion | Pass Condition |
|---|---|---|
| ACC01 | Home page >= 300 words Dutch | Word count |
| ACC02 | All 7 service pages >= 300 words Dutch | Word count per page |
| ACC03 | Category landing pages >= 500 words Dutch | Word count per page |
| ACC04 | No lorem ipsum or placeholder text on any page | Full text crawl |
| ACC05 | All service pages have H2 sections (Onze aanpak, Diensten, Veiligheid) | Manual audit |
| ACC06 | Gevelreiniging page uses standardized name (NOT Gevelonderhoud) | Check H1 + URL |
| ACC07 | All content reviewed by native Dutch speaker | Reviewer sign-off |

### 10.3 Technical

| # | Criterion | Pass Condition |
|---|---|---|
| ACT01 | All pages use correct template | Check WP admin |
| ACT02 | Block Editor used for all content (no shortcodes) | Check post_content in database |
| ACT03 | Zero PHP errors in debug.log | Check log after full crawl |
| ACT04 | Zero JavaScript errors in browser console | Manual check on each page template |
| ACT05 | reCAPTCHA v3 badge visible on Contact + Quote pages | Manual visual |
| ACT06 | Privacy checkboxes unchecked by default on both forms | Manual check |
| ACT07 | All forms have honeypot field (anti-spam) | Check form HTML source |
| ACT08 | File upload: server-side MIME validation active | Test with renamed .exe as .pdf |
| ACT09 | File upload: uploaded files renamed (not original filename) | Check uploads directory |

---

## 11. Testing Checklist (Sprint 2)

### 11.1 Navigation

- [ ] Homepage -> each service card -> correct page
- [ ] Main nav -> DIENSTEN dropdown -> all 7 services + 2 landings -> correct URL
- [ ] Main nav -> OVER HDS -> all 4 items (links may 404 until Sprint 3 — acceptable, link URLs must be correct)
- [ ] Main nav -> CONTACT -> /contact/
- [ ] Footer -> all service links -> correct URL
- [ ] Footer -> all legal links (may 404 until Sprint 3 — acceptable)
- [ ] Mobile: hamburger menu -> all items reachable
- [ ] Mobile: phone link works (tap to call)

### 11.2 Forms

- [ ] Contact form: submit empty -> validation errors (Dutch)
- [ ] Contact form: invalid email -> format error (Dutch)
- [ ] Contact form: short message (5 chars) -> min length error (Dutch)
- [ ] Contact form: consent unchecked -> required error (Dutch)
- [ ] Contact form: valid submission -> redirect /bedankt/?type=contact
- [ ] Contact form: email delivered to info@ within 2 minutes
- [ ] Contact form: confirmation email delivered to user
- [ ] Quote form: all field types work (text, dropdown, checkboxes, file upload)
- [ ] Quote form: Dutch postcode validation (1234 AB = pass, 1234 = fail, ABCD = fail)
- [ ] Quote form: valid submission with PDF -> redirect /bedankt/?type=offerte
- [ ] Quote form: download link in admin notification email works

### 11.3 Content

- [ ] All pages: H1 present exactly once
- [ ] All service pages: H2 sections present (Onze aanpak, Diensten, Veiligheid & Kwaliteit)
- [ ] P03 Gevelreiniging: page title says "Gevelreiniging" (not "Gevelonderhoud")
- [ ] Homepage: tagline is H1
- [ ] Contact page: two-column layout on desktop
- [ ] Empty states: no empty/blank sections on homepage (conditional sections hidden)

### 11.4 Responsive

- [ ] Mobile (375px): Home page content stacks vertically, no horizontal scroll
- [ ] Mobile (375px): Contact page form is full-width (two-column collapses to single column)
- [ ] Tablet (768px): Service Card Grid displays 2 columns
- [ ] Desktop (1024px): Service Card Grid displays 3 columns
- [ ] Desktop (1024px): Contact page displays two-column layout
- [ ] Touch targets: all navigation links >= 44px height on mobile
- [ ] Touch targets: all form submit buttons >= 44px

---

## 12. Risks and Mitigations (Sprint 2)

| Risk | Probability | Impact | Mitigation |
|---|---|---|---|
| **SMTP not configured (Sprint 1 incomplete)** | Medium | CRITICAL — forms cannot deliver email | Gate check at Day 1. Do not start Sprint 2 without SMTP. |
| **Service template takes longer than estimated (1 day)** | Medium | HIGH — delays all 7 service pages | Parallel track: Dev B starts writing service page content in Google Docs while Dev A builds template. |
| **Client hasn't provided MI-01 (address)** | High | MEDIUM — Contact Info Block missing address | Conditional rendering: hide address/KVK/BTW sections if not provided. Add later. |
| **Content writing takes longer than estimated** | Medium | MEDIUM — pages launch with thin content | Focus on structural content first (H2 sections, bullet lists). Expand prose in Sprint 3. |
| **Gravity Forms file upload PHP limits too low** | Low | MEDIUM — file upload silently fails | Set php.ini: upload_max_filesize=10M, post_max_size=12M, max_execution_time=120. |
| **Block patterns from Sprint 1 have bugs** | Low | MEDIUM — pages render incorrectly | Test all 8 patterns used in Sprint 2 on Day 1. Fix before template building. |

---

## 13. Daily Standup Template

```
Date: [YYYY-MM-DD] | Sprint 2 Day [N]

Dev A:
  Yesterday: [What I completed]
  Today: [What I'm working on]
  Blockers: [Any issues]

Dev B:
  Yesterday: [What I completed]
  Today: [What I'm working on]
  Blockers: [Any issues]

Risks/Concerns: [Any new risks]
Need Client Input: [Any MI items needed]
```

---

## 14. Sprint 2 Completion Checklist

Before declaring Sprint 2 Done, verify:

- [ ] All 13 pages return HTTP 200 on staging
- [ ] All 11 stories meet their acceptance criteria
- [ ] Contact form submits successfully (tested with real email)
- [ ] Quote form submits with file upload (tested with real file)
- [ ] All pages >= minimum word count (300 service, 500 landing)
- [ ] No lorem ipsum or placeholder text
- [ ] Dutch content reviewed by native speaker
- [ ] Responsive design verified at 4 breakpoints
- [ ] axe DevTools: zero critical + zero serious on all templates
- [ ] Lighthouse Accessibility = 100 on all templates
- [ ] Screaming Frog: zero 404s, zero broken internal links
- [ ] All code committed to dev branch on Git
- [ ] Client demo completed + feedback documented
- [ ] Sprint retrospective completed
- [ ] Sprint 3 backlog reviewed and ready

---

## Appendix A: Quick Reference — URLs to Build

```
/                                    [P01 Home]
/glasbewassing/                      [P02 Glasbewassing]
/gevelreiniging/                     [P03 Gevelreiniging]
/reguliere-schoonmaak/               [P04 Reguliere Schoonmaak] ★ Critical
/vloeronderhoud/                     [P05 Vloeronderhoud]
/vve-service/                        [P06 VVE Service]
/oplevering-schoonmaak/              [P07 Oplevering Schoonmaak]
/industriele-schoonmaak/             [P08 Industriele Schoonmaak]
/glas-en-gevel/                      [P09 Glas & Gevel Landing]
/schoonmaakdiensten/                 [P10 Schoonmaakdiensten Landing]
/contact/                            [P16 Contact] ★ Critical
/offerte-aanvragen/                  [P17 Offerte Aanvragen]
/bedankt/                            [P32 Bedankt]
(any non-existent URL)               [P31 404]
```

## Appendix B: Gravity Forms Quick Setup

**GF-1 (Contact):**
- Form Title: "Contactformulier"
- Fields: Naam (text, req), Bedrijf (text), E-mailadres (email, req), Telefoonnummer (phone), Onderwerp (dropdown, req: Offerte|Vraag|Klacht|Anders), Bericht (textarea, req, min 10), Privacy (checkbox, req, unchecked), Honeypot (hidden)
- Settings: reCAPTCHA v3 enabled. Confirmations: Redirect to /bedankt/?type=contact. Notifications: Admin to info@, User confirmation to {Email:3}.

**GF-2 (Offerte):**
- Form Title: "Offerte Aanvraag"
- Fields: Same as GF-1 minus Onderwerp + add: Gewenste dienst (checkboxes, req, 8 options), Type gebouw (dropdown, 7 options), Postcode / Plaats (text, req, regex validation), Beschrijving (textarea), Gewenste planning (dropdown, 5 options), Hoe gevonden? (dropdown, 5 options), Bestand uploaden (fileupload, max 5MB, extensions: pdf,jpg,jpeg,png,doc,docx)
- Settings: reCAPTCHA v3 enabled. Confirmations: Redirect to /bedankt/?type=offerte. Notifications: Admin to info@ (with file download link), User confirmation to {Email:3}.

---

**END OF SPRINT 2 EXECUTION PLAN — Version 1.0.0**

This plan is ready for immediate execution by the development team. All tasks are sequenced, estimated, and assigned. All dependencies are mapped. All acceptance criteria are defined. The plan assumes Sprint 1 completion is verified and all prerequisites are met.
