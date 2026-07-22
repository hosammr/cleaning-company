# User Journey Analysis — HDS Onderhoudsdiensten

**Domain:** helderduidelijkschoon.nl
**Scope:** Documented user journeys through the live website, as observed from the current site snapshot.
**Critical Finding:** Two key touchpoints are non-functional — the Contact page returns HTTP 500 and the primary cleaning service page returns HTTP 404. All conversion journeys that depend on these touchpoints fail.

---

## Table of Contents

1. [Personas](#1-personas)
2. [Journey Maps](#2-journey-maps)
   - [Journey A: Facility Manager — Office Cleaning](#journey-a-facility-manager--office-cleaning)
   - [Journey B: VvE Board Member — Residential Complex Maintenance](#journey-b-vve-board-member--residential-complex-maintenance)
   - [Journey C: Construction Project Manager — Post-Build Cleaning](#journey-c-construction-project-manager--post-build-cleaning)
   - [Journey D: School Administrator — Floor Maintenance](#journey-d-school-administrator--floor-maintenance)
   - [Journey E: Factory Manager — Industrial Cleaning](#journey-e-factory-manager--industrial-cleaning)
   - [Journey F: Job Seeker — Employment Application](#journey-f-job-seeker--employment-application)
   - [Journey G: Air Purifier Buyer — Product Purchase](#journey-g-air-purifier-buyer--product-purchase)
   - [Journey H: Existing Client — Service Inquiry](#journey-h-existing-client--service-inquiry)
3. [Touchpoint Inventory](#3-touchpoint-inventory)
4. [Journey Failure Points](#4-journey-failure-points)
5. [Conversion Funnel](#5-conversion-funnel)
6. [UX Observations](#6-ux-observations)

---

## Step Status Key

| Symbol | Status | Meaning |
|--------|--------|---------|
| `[OK]` | Success | Touchpoint works as expected |
| `[BLOCKED]` | Failure | Touchpoint is broken (4xx/5xx); user cannot proceed |
| `[WARNING]` | Degraded | Touchpoint works but has significant UX or content issues |
| `[FRAGILE]` | Fragile | Touchpoint works but a single failure elsewhere immediately blocks the journey |
| `[UNVERIFIED]` | Unknown | Touchpoint not fully verified in the site snapshot |

---

## 1. Personas

Personas inferred from site content and service portfolio. Actual visitor segments may differ.

| ID | Persona | Primary Need | Service Page(s) | Journey Status |
|----|---------|-------------|-----------------|----------------|
| A | Facility Manager | Regular office cleaning | Reguliere Schoonmaak (`[BLOCKED]`), Glasbewassing | Broken at step 3 |
| B | VvE Board Member | Residential complex maintenance | VVE Service | Fragile |
| C | Construction Project Manager | Post-renovation delivery cleaning | Oplevering Schoonmaak | Fragile |
| D | School Administrator | Floor maintenance during holidays | Vloeronderhoud | Fragile |
| E | Factory Manager | Industrial facility cleaning | Industriële Schoonmaak | Fragile (thin content) |
| F | Job Seeker | Employment application | Vacatures | Poor (images only) |
| G | Air Purifier Buyer | Purchase air purification unit | Winkel (WooCommerce) | Unverified |
| H | Existing Client | Service question or support | Homepage (phone number) | Works (phone only) |

---

## 2. Journey Maps

Each journey describes the **intended path** (what the site is designed to support) and the **actual path** (what happens on the live site today).

### Journey A: Facility Manager — Office Cleaning

**Goal:** Find a professional cleaning company for regular office maintenance and request a quote.

**Entry Points:** Google search ("schoonmaakbedrijf regio", "kantoor schoonmaak"), direct referral, or homepage.

#### Intended Path

```
Landing Page ──► Service Page ──► Contact/Quote Form ──► Confirmation
  (Homepage)     (Reguliere       (/contact/)             (Thank-you
                 Schoonmaak)                               or email)
```

#### Actual Path

| Step | Touchpoint | URL | Status | What Happens |
|------|-----------|-----|--------|--------------|
| 1 | Search result or referral | — | `[OK]` | User arrives at homepage (most likely) |
| 2 | Homepage | `/` | `[OK]` | Sees tagline, icon grid. Identifies "SCHOONMAAK" icon. |
| 3 | Icon link "SCHOONMAAK" | `/reguliere-schoonmaak/` | `[BLOCKED]` | **HTTP 404.** Page does not exist. |
| 3a | *Alternative:* Navigate via menu | `/?page_id=318` | `[BLOCKED]` | **HTTP 404.** Same page, different URL — also broken. |
| 3b | *Alternative:* Choose different icon | e.g., `/glasbewassing/` | `[OK]` | User reads a working service page, builds interest. |
| 4 | Contact CTA (button or nav) | `/contact/` | `[BLOCKED]` | **HTTP 500.** Server error. Form cannot be displayed. |
| 4a | *Fallback:* Header phone number | `tel:0164-652846` | `[OK]` | User can call. Only remaining conversion path. |
| 4b | *Fallback:* Header email link | `mailto:info@...` | `[OK]` | User can email. Requires switching applications. |

**Result:** `[BLOCKED]` — The primary service page is missing, and the contact form is broken. The journey only succeeds if the user (a) happens to land on or navigate to a working service page, (b) notices the phone number in the header, and (c) chooses to call rather than abandoning.

#### Friction Points

- Two URL variants for the same page (`/reguliere-schoonmaak/` and `/?page_id=318`), both broken.
- The homepage icon grid presents the "SCHOONMAAK" icon as the first click target for cleaning services — it leads to a dead end.
- The telephone number is the only reliable conversion path, but it is not repeated in the page body — only in the header.
- No alternative contact method (WhatsApp, live chat, callback request) exists.

---

### Journey B: VvE Board Member — Residential Complex Maintenance

**Goal:** Find a cleaning and maintenance contractor for a residential complex, verify credibility, and request a proposal.

**Entry Points:** Referral via VvE Belang (vvebelang.nl), Google search ("VvE schoonmaak"), or homepage.

#### Intended Path

```
VvE Belang / ──► VVE Service ──► Contact/Quote ──► Confirmation
  Search         Page            Form
                (/vve-service/)  (/contact/)
```

#### Actual Path

| Step | Touchpoint | URL | Status | What Happens |
|------|-----------|-----|--------|--------------|
| 1 | Referral or search | — | `[OK]` | User arrives at `/vve-service/` or homepage. |
| 2 | Homepage icon "VVE" | `/vve` | `[WARNING]` | Resolves to `/vve-service/` but uses a **different slug** than the nav (`/vve` vs `/vve-service/`). |
| 2a | Navigation "VVE Service" | `/vve-service/` | `[OK]` | Clean URL, no issue. |
| 3 | VVE Service page | `/vve-service/` | `[OK]` | Reads scope: stairwells, halls, garages, technical maintenance, outdoor care. |
| 4 | Credibility signal | Page body | `[OK]` | VvE Belang partnership mentioned in page copy. |
| 5 | Contact CTA | `/contact/` | `[BLOCKED]` | **HTTP 500.** Cannot submit inquiry. |
| 5a | *Fallback:* Phone or email | Header | `[OK]` | Manual contact only. |

**Result:** `[FRAGILE]` — The service page works and includes a credibility signal, but the conversion step fails. The board member cannot request a proposal through the website.

#### Friction Points

- Inconsistent URL slug (`/vve` on icon vs `/vve-service/` in nav) creates confusion if the user navigates between both entry points.
- No online quote-request mechanism — board members typically require written proposals; forcing them to call adds friction.

---

### Journey C: Construction Project Manager — Post-Build Cleaning

**Goal:** Find a cleaning company for post-construction/renovation delivery cleaning ("oplevering schoonmaak") and obtain a quote.

**Entry Points:** Google search ("oplevering schoonmaak", "bouw oplevering reiniging") or referral from construction network.

#### Intended Path

```
Search / ──► Oplevering ──► Contact/Quote ──► Confirmation
  Referral   Schoonmaak     Form
            (/oplevering-   (/contact/)
             schoonmaak/)
```

#### Actual Path

| Step | Touchpoint | URL | Status | What Happens |
|------|-----------|-----|--------|--------------|
| 1 | Search or referral | — | `[OK]` | User arrives at `/oplevering-schoonmaak/` or homepage. |
| 2 | Service page | `/oplevering-schoonmaak/` | `[OK]` | Reads about "0-beurt", sees bullet list of 5 task types. Clear scope. |
| 3 | Contact CTA | `/contact/` | `[BLOCKED]` | **HTTP 500.** Cannot submit inquiry. |
| 3a | *Fallback:* Phone or email | Header | `[OK]` | Manual contact only. |

**Result:** `[FRAGILE]` — The service page conveys a clear scope, but the journey ends at the broken contact page.

#### Friction Points

- No project examples, before/after imagery, or timeline expectations on the service page — construction PMs often need these to make an initial assessment.

---

### Journey D: School Administrator — Floor Maintenance

**Goal:** Find a floor-maintenance provider who can work during school holidays and handle institutional flooring types.

**Entry Points:** Google search ("vloeronderhoud scholen", "marmoleum vloer reinigen") or direct.

#### Intended Path

```
Search ──► Vloeronderhoud ──► Contact/Quote ──► Confirmation
              Page              Form
            (/vloeronderhoud/)  (/contact/)
```

#### Actual Path

| Step | Touchpoint | URL | Status | What Happens |
|------|-----------|-----|--------|--------------|
| 1 | Search or homepage | — | `[OK]` | User arrives at `/vloeronderhoud/` or clicks "VLOER" icon from homepage. |
| 2 | Service page | `/vloeronderhoud/` | `[OK]` | Reads about floor types (linoleum, marmoleum, natural stone, carpet, wood). Sees 7-item service bullet list. |
| 3 | Scheduling signal | Page body | `[OK]` | Explicit mention: work done outside business hours, weekends, school holidays — directly relevant to schools. |
| 4 | Contact CTA | `/contact/` | `[BLOCKED]` | **HTTP 500.** Cannot submit inquiry. |
| 4a | *Fallback:* Phone or email | Header | `[OK]` | Manual contact only. |

**Result:** `[FRAGILE]` — The service page is one of the best on the site and contains a key differentiator (holiday scheduling), but the conversion step fails.

#### Friction Points

- No images of floor work, equipment, or finished floors — institutional buyers may expect visual evidence.

---

### Journey E: Factory Manager — Industrial Cleaning

**Goal:** Find a contractor for industrial facility cleaning that minimizes production downtime.

**Entry Points:** Google search ("industriële schoonmaak", "fabriek reiniging") or direct.

#### Intended Path

```
Search ──► Industriële ──► Contact/Quote ──► Confirmation
             Schoonmaak      Form
           (/industriele-     (/contact/)
            schoonmaak/)
```

#### Actual Path

| Step | Touchpoint | URL | Status | What Happens |
|------|-----------|-----|--------|--------------|
| 1 | Search or homepage icon | — | `[OK]` | User arrives at service page. |
| 2 | Service page | `/industriele-schoonmaak/` | `[WARNING]` | ~60 words. Single paragraph. No bullet points, no equipment mentions, no client examples. |
| 3 | Contact CTA | `/contact/` | `[BLOCKED]` | **HTTP 500.** Cannot submit inquiry. |
| 3a | *Fallback:* Phone or email | Header | `[OK]` | Manual contact only. |

**Result:** `[FRAGILE]` — Thin content undermines credibility even before the broken contact step. A factory manager evaluating multiple vendors has minimal substance to compare.

#### Friction Points

- Thin content: only ~60 words. No bullet list, no equipment or technique details, no mention of safety protocols for industrial environments.
- No images of industrial cleaning work.

---

### Journey F: Job Seeker — Employment Application

**Goal:** Find open positions, understand the employer, and apply for a job.

**Entry Points:** Job boards, Google search ("vacature schoonmaak Bergen op Zoom"), or direct navigation.

#### Intended Path

```
Vacatures Page ──► Read Job Descriptions ──► Apply
  (/vacatures/)                             (application
                                             form/email)
```

#### Actual Path

| Step | Touchpoint | URL | Status | What Happens |
|------|-----------|-----|--------|--------------|
| 1 | Navigation or search | `/vacatures/` | `[OK]` | User sees H1 "VACATURES" and subtitle "Wordt u onze collega?" |
| 2 | Vacancy content | `/vacatures/` | `[WARNING]` | Two vacancy posters displayed as **JPG images of scanned Word documents**. |
| 3 | Read details | — | `[BLOCKED]` | Text is embedded in images — cannot be selected, copied, or read by screen readers. |
| 4 | Apply | — | `[BLOCKED]` | No application form, no email link, no application instructions present. |

**Result:** `[BLOCKED]` — Job seekers cannot read vacancy details without decoding images manually, and there is no way to apply online.

#### Friction Points

- Vacancy text is not real text — it is locked inside JPG images. This fails accessibility standards and prevents search engines from indexing the positions.
- No information about HDS as an employer: benefits, culture, training, career paths.
- No application method of any kind (form, email address, postal address, phone extension).
- The rest of the site provides some employer context (Over HDS, Kwaliteit & Veiligheid), but these pages are not linked from the Vacatures page.

---

### Journey G: Air Purifier Buyer — Product Purchase

**Goal:** Purchase an Airfixr air purification unit.

**Entry Points:** Search, direct URL, or referral. (Unclear whether paid/organic traffic is directed to the shop.)

#### Intended Path

```
Shop Page ──► Product Page ──► Cart ──► Checkout ──► Confirmation
  (/winkel/)  (/product/...)  (/winkelmand/)
```

#### Actual Path

| Step | Touchpoint | URL | Status | What Happens |
|------|-----------|-----|--------|--------------|
| 1 | Shop page | `/winkel/` | `[WARNING]` | 14 Airfixr products listed. No shop introduction, no category description, no explanation of the product line. Prices excl. BTW. |
| 2 | Product page | `/product/airfixr-{model}/` | `[UNVERIFIED]` | Individual product pages not fully verified. Likely standard WooCommerce. |
| 3 | Add to cart | — | `[UNVERIFIED]` | Add-to-cart functionality not verified. |
| 4 | Cart | `/winkelmand/` | `[UNVERIFIED]` | Cart page not verified. |
| 5 | Checkout | — | `[UNVERIFIED]` | Checkout flow not verified. |

**Result:** `[UNVERIFIED]` — The WooCommerce shop appears functional, but the full purchase flow has not been confirmed. The shop lacks introductory context.

#### Friction Points

- No introduction explaining the Airfixr product, its benefits, or its relationship to HDS's core cleaning business.
- Shop exists in isolation — no cross-links from service pages, no "why buy from us" messaging.
- Product prices listed excl. BTW (VAT) — noted on product pages, but may surprise B2C buyers.

---

### Journey H: Existing Client — Service Inquiry

**Goal:** Contact the company for a service question, schedule change, or issue report.

**Entry Points:** Direct URL, brand search, or bookmark.

#### Intended Path

```
Homepage ──► Find contact info ──► Contact
```

#### Actual Path

| Step | Touchpoint | URL | Status | What Happens |
|------|-----------|-----|--------|--------------|
| 1 | Homepage | `/` | `[OK]` | User arrives. |
| 2 | Phone number | Header (global) | `[OK]` | Tel link `0164-652846` visible on every page. Tap-to-call works on mobile. |
| 3 | Email address | Header (global) | `[OK]` | Mailto link `info@helderduidelijkschoon.nl` visible on every page. |
| 4 | Contact page | `/contact/` | `[BLOCKED]` | HTTP 500. Not relevant if the client already knows the phone number. |
| 5 | Self-service | — | `[BLOCKED]` | No FAQ, no client portal, no service-request form exists. |

**Result:** `[OK]` (phone/email only) — The existing client journey works via phone and email but offers no self-service options.

#### Friction Points

- No FAQ section — common questions (billing, scheduling, service changes) require a phone call.
- No client login or portal for viewing schedules, invoices, or service history.
- No emergency/urgent contact number distinguished from the general line.

---

## 3. Touchpoint Inventory

### 3.1 Call-to-Action (CTA) Elements

| CTA | Location | Destination URL | HTTP Status | Notes |
|-----|----------|----------------|-------------|-------|
| Icon: OVER HDS | Homepage grid | `/over-hds/` | 200 | |
| Icon: SCHOONMAAK | Homepage grid | `/reguliere-schoonmaak/` | **404** | `[BLOCKED]` |
| Icon: GLAS | Homepage grid | `/glasbewassing` | 200 | Missing trailing slash |
| Icon: VVE | Homepage grid | `/vve` | 200 | Different slug than nav (`/vve-service/`) |
| Icon: GEVEL | Homepage grid | `/gevelreiniging/` | 200 | Label differs from nav ("Gevelonderhoud") |
| Icon: KWALITEIT | Homepage grid | `/kwaliteit-veiligheid/` | 200 | |
| Icon: VLOER | Homepage grid | `/vloeronderhoud/` | 200 | |
| Icon: CONTACT | Homepage grid | `/contact/` | **500** | `[BLOCKED]` |
| Banner text + button: "Neem contact met ons op..." | Homepage body | `/contact/` | **500** | `[BLOCKED]` — Primary conversion CTA |
| Phone number: 0164-652846 | Header (global) | `tel:0164-652846` | Working | Only reliable conversion path |
| Email: info@helderduidelijkschoon.nl | Header (global) | `mailto:...` | Working | Requires application switch |
| Facebook link | Footer (global) | Facebook URL | Working | Unverified destination |
| Instagram link | Footer (global) | Instagram URL | `[WARNING]` | Widget broken: "Instagram did not return a 200" |

### 3.2 Interactive Forms

| Form | Plugin/System | Location | Function | Status |
|------|--------------|----------|----------|--------|
| Contact form | Formidable Forms | `/contact/` | Quote and contact requests | `[BLOCKED]` — Page returns HTTP 500 |
| Testimonial submission | HMS Testimonials | `/referenties/` | Client testimonial collection | `[WARNING]` — Shortcode renders but form is empty |
| Testimonial display | HMS Testimonials | `/referenties/` | Display collected testimonials | `[WARNING]` — Shortcode renders but no testimonials shown |
| Blog comment form | WordPress default | `/2015/06/29/hallo-wereld/` | Blog post comments | `[OK]` — 1 comment received |
| Site search | WordPress default | Footer (global) | Site content search | `[OK]` |
| WooCommerce checkout | WooCommerce | `/winkel/` → cart | Product purchase flow | `[UNVERIFIED]` |

---

## 4. Journey Failure Points

Consolidated list of technical and UX failures that affect one or more journeys.

| # | Failure | Affected Touchpoint | HTTP Status | Journeys Affected | Consequence |
|---|---------|-------------------|-------------|-------------------|-------------|
| F1 | Contact page down | `/contact/` | 500 | A, B, C, D, E | Zero online form inquiries can be received |
| F2 | Primary service page missing | `/reguliere-schoonmaak/` | 404 | A | Core cleaning service has no web presence |
| F3 | Same page, alternate URL broken | `/?page_id=318` | 404 | A | Nav link to same page also dead |
| F4 | Page sitemap broken | `/page-sitemap.xml` | 500 | All | Search engines cannot discover page URLs via sitemap |
| F5 | Homepage CTA dead-end | `/contact/` from banner | 500 | A, B, C, D, E | Most prominent conversion button leads to error |
| F6 | Homepage icon URL mismatch | `/vve` vs `/vve-service/` | 200 | B | Two slugs for one page; potential redirect/confusion |
| F7 | Homepage icon trailing slash | `/glasbewassing` vs `/glasbewassing/` | 200 | A | Inconsistent URL pattern |
| F8 | Vacancy text as images | `/vacatures/` JPG posters | 200 | F | Not readable, not indexable, not accessible |
| F9 | No application method | `/vacatures/` | 200 | F | Job seekers cannot apply |
| F10 | Instagram widget broken | Footer (global) | API error | All | Unprofessional appearance on every page |
| F11 | Thin industrial cleaning content | `/industriele-schoonmaak/` | 200 | E | ~60 words; insufficient for vendor evaluation |

---

## 5. Conversion Funnel

### 5.1 Funnel Diagram

```
                      VISITOR
                         │
                  ┌──────┴──────┐
                  │   Homepage   │  ← ~30 words, H1 only
                  │   (/)
                  └──────┬──────┘
                         │
            ┌────────────┼────────────┐
            ▼            ▼            ▼
       Service Page  Service Page  Service Page
       (working)      (404!)        (working)
            │            │            │
            └────────────┼────────────┘
                         ▼
                  ┌──────────────┐
                  │  /contact/    │  ← 500 ERROR
                  │  DEAD END     │
                  └──────┬───────┘
                         │
                  ┌──────┴──────┐
                  │    PHONE     │  ← Only working conversion path
                  │  (header)    │
                  └──────────────┘
```

### 5.2 Funnel Leakage

- **Every** visitor who clicks a "Contact" link, button, or icon reaches an HTTP 500 error page.
- The website cannot receive contact-form submissions under any circumstances.
- The phone number in the header is the only functional conversion path for service inquiries.
- No analytics detected on the site — conversion rate, abandonment rate, and traffic volume are unknown.

### 5.3 Funnel Observations

- The homepage serves as the primary landing page for most journeys, but contains ~30 words — it provides minimal content for decision-making before a user must click through.
- 2 of 8 homepage icon links are broken (SCHOONMAAK → 404, CONTACT → 500).
- 1 of 8 homepage icon links uses an inconsistent URL (`/vve` vs `/vve-service/`).
- 1 of 8 homepage icon links omits the trailing slash (`/glasbewassing`).
- After a user reaches a working service page, there is no on-page CTA other than the header phone number; the main "Contact" CTA in the navigation and homepage banner both point to the broken `/contact/` page.
- The funnel has no nurture path — no email capture, no newsletter, no downloadable brochure. Users who are not ready to call leave no trace.

---

## 6. UX Observations

Objective observations about the user experience. No recommendations — facts only.

### 6.1 Navigation & Wayfinding

- The main navigation structure is logical: company info under "OVER HDS", services under two category groups, contact as the final item.
- The homepage icon grid duplicates the navigation structure but uses **inconsistent labels and URLs** (see §3.1). A user who clicks an icon and later uses the nav may encounter different URLs for the same content.
- Menu group labels ("GLAS & GEVEL", "SCHOONMAAKDIENSTEN") have no linked landing pages — they serve only as visual dividers.
- No breadcrumb trail is visible on inner pages, despite a `BreadcrumbList` schema entry (Home only) being present.
- The header phone number and email address are visible on every page, providing a constant fallback contact method.

### 6.2 Content & Credibility

- Service pages that work contain clear, honest Dutch copy with industry-appropriate terminology.
- The "vrijblijvende offerte" (no-obligation quote) message on the homepage is a positive friction-reducer — but it is undermined by the broken contact path.
- Trust signals (OSB membership, VvE Belang listing, safety certifications, trained permanent staff) are present in page copy but not visually reinforced with logos, badges, or hyperlinks.
- The footer lacks standard trust elements: no KVK number, no BTW number, no physical address, no privacy policy link.
- The Instagram widget in the footer displays an error message ("Instagram did not return a 200") on every page, damaging perceived site maintenance quality.
- The blog contains only a default "Hello World" post from 2015, indicating the site has not been actively maintained or updated with fresh content.

### 6.3 Conversion Barriers

- The contact form is the only web-based conversion mechanism — no live chat, no callback-request widget, no WhatsApp link, no online booking exists.
- The Reguliere Schoonmaak page (primary service offering) is missing entirely. Users searching for office or regular cleaning services find no content.
- The 500 error on `/contact/` is a **silent failure** — the user sees a server error page with no guidance on alternative contact methods.
- There is no email-capture or lead-nurture mechanism. Visitors who do not call immediately are lost.

### 6.4 Accessibility & Inclusivity

- The Vacatures page displays job postings exclusively as JPG images of scanned Word documents. This content is inaccessible to screen readers, not searchable via Ctrl+F, and not indexable by search engines.
- No skip-to-content link, ARIA landmarks, or keyboard-navigation enhancements were detected (unverified; standard Divi theme behavior applies).
- Phone and email links use semantic `tel:` and `mailto:` protocols, supporting assistive technology.
- Divi theme is responsive by default, but mobile usability has not been verified through testing.

### 6.5 Performance & Reliability

- Page load performance has not been benchmarked, but the technology stack (Divi + WooCommerce + Formidable Forms + unoptimized PNG icons) suggests a non-trivial payload.
- Two of 15 site pages return HTTP errors (500 and 404), and a third (`/page-sitemap.xml`) returns 500 — representing a ~20% failure rate for critical pages.
- No caching layer, CDN, or performance-optimization plugin was detected.
- The site has no visible uptime monitoring or error-tracking mechanism — the 500 error on the contact page appears to have gone unnoticed.
