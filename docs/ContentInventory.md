# Content Inventory — HDS Onderhoudsdiensten

**Domain:** helderduidelijkschoon.nl
**CMS:** WordPress (Divi theme + WooCommerce)
**Language:** Dutch (nl_NL)
**Inventory Date:** Inferred from snapshot analysis

---

## Table of Contents

1. [Navigation & Site Structure](#1-navigation--site-structure)
2. [Page Inventory](#2-page-inventory)
3. [Blog Posts](#3-blog-posts)
4. [Reusable Elements](#4-reusable-elements)
5. [Media Assets](#5-media-assets)
6. [Metadata Summary](#6-metadata-summary)

---

## Issue Severity Key

| Icon | Severity | Meaning |
|------|----------|---------|
| `[BROKEN]` | Critical | Page returns HTTP 4xx or 5xx — content inaccessible |
| `[THIN]` | High | Word count under 50; essentially no content |
| `[LIGHT]` | Medium | Word count 50–150; below recommended minimum |
| `[MISSING]` | High | Expected content or element not present |
| `[OK]` | Low/Info | Acceptable or minor issue only |

---

## 1. Navigation & Site Structure

### 1.1 Main Navigation Menu

| # | Label | Linked URL | HTTP Status | Dedicated Page? | Notes |
|---|-------|-----------|-------------|-----------------|-------|
| 1 | HOME | `/` | 200 | Yes | |
| 2 | OVER HDS | `/over-hds/` | 200 | Yes | Parent item; children are Referenties, Vacatures, Kwaliteit & Veiligheid, Downloads |
| 3 | — Referenties | `/referenties/` | 200 | Yes | |
| 4 | — Vacatures | `/vacatures/` | 200 | Yes | |
| 5 | — Kwaliteit & Veiligheid | `/kwaliteit-veiligheid/` | 200 | Yes | |
| 6 | — Downloads | `/downloads/` | 200 | Yes | |
| 7 | GLAS & GEVEL | *(menu group only)* | — | No | No dedicated parent landing page |
| 8 | — Glasbewassing | `/glasbewassing/` | 200 | Yes | |
| 9 | — Gevelonderhoud | `/gevelreiniging/` | 200 | Yes | **Label mismatch:** nav says "Gevelonderhoud" but URL slug is `gevelreiniging` |
| 10 | SCHOONMAAKDIENSTEN | *(menu group only)* | — | No | No dedicated parent landing page |
| 11 | — Vloeronderhoud | `/vloeronderhoud/` | 200 | Yes | |
| 12 | — VVE Service | `/vve-service/` | 200 | Yes | |
| 13 | — Oplevering Schoonmaak | `/oplevering-schoonmaak/` | 200 | Yes | |
| 14 | — Reguliere Schoonmaak | `/?page_id=318` | **404** | Intended: Yes | `[BROKEN]` |
| 15 | — Industriële Schoonmaak | `/industriele-schoonmaak/` | 200 | Yes | |
| 16 | CONTACT | `/contact/` | **500** | Intended: Yes | `[BROKEN]` |

### 1.2 Homepage Icon Grid (Quick Links)

A row of 8 icon+label links on the homepage, each 100×100 px PNG, pointing to service and informational pages.

| # | Icon Label | Linked URL | HTTP Status | Same as Nav? | Issue |
|---|-----------|-----------|-------------|--------------|-------|
| 1 | OVER HDS | `/over-hds/` | 200 | Yes | |
| 2 | SCHOONMAAK | `/reguliere-schoonmaak/` | **404** | Matches nav item #14 but uses a prettier slug | `[BROKEN]` — different URL than nav (`/?page_id=318`); both 404 |
| 3 | GLAS | `/glasbewassing` | 200 | Same page as nav #8 | **Trailing-slash inconsistency:** missing trailing `/` |
| 4 | VVE | `/vve` | 200 | Same page as nav #12 but different slug | **Slug inconsistency:** `/vve` vs `/vve-service/` |
| 5 | GEVEL | `/gevelreiniging/` | 200 | Matches nav #9 URL | **Label inconsistency:** icon says "GEVEL", nav says "Gevelonderhoud" |
| 6 | KWALITEIT | `/kwaliteit-veiligheid/` | 200 | Yes | |
| 7 | VLOER | `/vloeronderhoud/` | 200 | Yes | |
| 8 | CONTACT | `/contact/` | **500** | Yes | `[BROKEN]` — same 500 as nav #16 |

### 1.3 Navigation Issues Summary

| Issue | Detail | Impact |
|-------|--------|--------|
| Broken page (404) | `/reguliere-schoonmaak/` and `/?page_id=318` — primary cleaning service | `[BROKEN]` — page unreachable from both nav and homepage icon |
| Broken page (500) | `/contact/` — primary conversion page | `[BROKEN]` — page unreachable from nav, homepage icon, and homepage CTA button |
| Inconsistent URL | `/vve` (icon) vs `/vve-service/` (nav) | Two different slugs resolve to the same page; risks duplicate-content signals |
| Inconsistent trailing slash | `/glasbewassing` (icon) vs `/glasbewassing/` (nav) | Redirect or separate page unclear |
| Inconsistent label | "Gevel" (icon) vs "Gevelonderhoud" (nav) | URL is `/gevelreiniging/`; three different terms for the same content |
| No landing pages | "GLAS & GEVEL" and "SCHOONMAAKDIENSTEN" are menu-group labels with no target page | Missed internal-linking and content opportunities |

---

## 2. Page Inventory

Each page below is described with **content facts** (verified from the page) followed by **observations** (inferred issues and notable findings).

### 2.1 Home (`/`)

| Property | Value |
|----------|-------|
| HTTP Status | 200 |
| Title Tag | `HOME - HDS Onderhoudsdiensten` |
| H1 | "Helder en Duidelijk voor het Schoonste resultaat!" |
| Word Count | ~30 Dutch words |
| Images | 8 icon PNGs (100×100 px), 1 logo PNG (200×81 px) |

**Content Facts**
- Icon grid: 8 linked icons with labels (see §1.2)
- CTA banner text: "Neem contact met ons op voor een vrijblijvende offerte!"
- CTA button: "Contact" → links to `/contact/`

**Observations**
- `[BROKEN]` The single CTA button links to the broken `/contact/` page. There is no alternative conversion path.
- `[THIN]` 30 words is minimal; no introductory text, no service summary, no proof points, no visible phone number in body.
- `[MISSING]` No custom meta description (Yoast default only).
- `[MISSING]` No H2/H3 heading hierarchy.
- `[MISSING]` No og:description or og:image tags.

---

### 2.2 Over HDS (`/over-hds/`)

| Property | Value |
|----------|-------|
| HTTP Status | 200 |
| Title Tag | `OVER HDS - HDS Onderhoudsdiensten` |
| H1 | "OVER HDS SCHOONMAAKDIENSTEN" |
| Word Count | ~120 Dutch words |
| Images | None beyond site header/logo |

**Content Facts**
- Paragraph 1: Expertise, experience, trust; permanent professionally trained staff.
- Paragraph 2: Management present during new-project startup; tailored work planning; recognizable company uniforms.

**Observations**
- `[LIGHT]` 120 words. Content conveys the value proposition clearly but is brief.
- `[MISSING]` No company history, founding year, or origin story.
- `[MISSING]` No team photos or workplace imagery.
- `[MISSING]` No KVK number, BTW number, or physical address.
- `[MISSING]` No certification logos or badges (OSB, Arbo, diploma references exist on other pages but not here).
- `[MISSING]` Single H1; no H2/H3 hierarchy.

---

### 2.3 Referenties (`/referenties/`)

| Property | Value |
|----------|-------|
| HTTP Status | 200 |
| Title Tag | `REFERENTIES - HDS Onderhoudsdiensten` |
| H1 | "REFERENTIES" |
| Word Count | ~25 Dutch words |
| Images | 1 client logo (`Afbeelding6-2.png`) |

**Content Facts**
- Introductory sentence: "In opdracht van o.a. onderstaande opdrachtgevers wordt door HDS dagelijkse en/of periodieke werkzaamheden uitgevoerd."
- 1 client logo image displayed (company name not given).
- Shortcode `[hms_testimonials_form]` rendered — appears empty on the page.
- Shortcode `[hms_testimonials template="13" order="testimonial_date" direction="DESC"]` rendered — appears empty on the page.

**Observations**
- `[THIN]` 25 words. Essentially a placeholder page.
- `[MISSING]` Only 1 reference logo visible; no client names, project descriptions, or sectors.
- `[MISSING]` Testimonials plugin (HMS Testimonials) is installed but contains no testimonials — both the submission form and display shortcode render empty.
- `[MISSING]` No case studies or before/after imagery.
- `[MISSING]` Single H1; no H2/H3 hierarchy.

---

### 2.4 Vacatures (`/vacatures/`)

| Property | Value |
|----------|-------|
| HTTP Status | 200 |
| Title Tag | `VACATURES - HDS Onderhoudsdiensten` |
| H1 | "VACATURES" |
| Word Count | ~5 Dutch words (heading + subtitle only) |
| Images | 2 vacancy JPG posters (scanned from Word documents) |

**Content Facts**
- Subtitle: "Wordt u onze collega?"
- Image 1: Scanned JPG of "vacature glas-en gevelreiniging.docx"
- Image 2: Scanned JPG of "vacature oproepkracht schoonmaak.docx"

**Observations**
- `[THIN]` 5 words. No text describing the company as an employer, benefits, or application process.
- `[MISSING]` Vacancy content exists only as images of Word documents — not accessible to screen readers, not search-indexable, not copy-pasteable.
- `[MISSING]` No application instructions, email address, or form for candidates.
- `[MISSING]` No structured job data (salary, hours, location, requirements) in machine-readable form.

---

### 2.5 Kwaliteit & Veiligheid (`/kwaliteit-veiligheid/`)

| Property | Value |
|----------|-------|
| HTTP Status | 200 |
| Title Tag | `KWALITEIT & VEILIGHEID - HDS Onderhoudsdiensten` |
| H1 | "KWALITEIT & VEILIGHEID MVO" |
| Word Count | ~150 Dutch words |
| Images | `mvo-ondernemen1-2.png`, `veiligheid1.png` |

**Content Facts**
- Section 1 — Kwaliteit: Continuous improvement, periodic checks, complaints resolved immediately, single point of contact per client.
- Section 2 — Veiligheid: OSB contact, Arbeidsinspectie liaison, Arbo compliance, mandatory RI&E per project.
- Section 3 — MVO (Corporate Social Responsibility): Minimal environmentally harmful products, employee care.

**Observations**
- `[LIGHT]` 150 words. Content is substantive for the topic area.
- `[MISSING]` No hyperlinks to actual certifications, certifying bodies, or external validators.
- `[MISSING]` No certification logos or badges embedded.
- `[MISSING]` No H2/H3 hierarchy — three topic sections are visually separated but not structurally marked up.

---

### 2.6 Downloads (`/downloads/`)

| Property | Value |
|----------|-------|
| HTTP Status | 200 |
| Title Tag | `DOWNLOADS - HDS Onderhoudsdiensten` |
| H1 | "DOWNLOADS" |
| Word Count | ~10 Dutch words |
| Images | `download-enter-button-key.png`, `download2.png` |

**Content Facts**
- Subtitle: "Algemene voorwaarden:"
- Link 1: "Algemene voorwaarden schoonmaakwerkzaamheden" → PDF hosted on `www.hds-onderhoudsdiensten.nl`
- Link 2: "Algemene voorwaarden gevelreiniging" → PDF hosted on `www.hds-onderhoudsdiensten.nl`

**Observations**
- `[THIN]` 10 words. Only two PDF links with no surrounding explanation.
- `[MISSING]` PDFs are hosted on a **legacy domain** (`hds-onderhoudsdiensten.nl`), not the live domain (`helderduidelijkschoon.nl`). If the legacy domain expires or its hosting is removed, these terms become inaccessible.
- `[MISSING]` No HTML-based terms-and-conditions page on the site itself.
- `[MISSING]` No privacy policy, no cookie statement — legal requirements under GDPR/AVG are absent from the site.

---

### 2.7 Glasbewassing (`/glasbewassing/`)

| Property | Value |
|----------|-------|
| HTTP Status | 200 |
| Title Tag | `GLASBEWASSING - HDS Onderhoudsdiensten` |
| H1 | "GLASBEWASSING" |
| Word Count | ~180 Dutch words |

**Content Facts**
- Intro paragraph: Visual storytelling linking window cleanliness to building appearance and impression.
- Section — Veiligheid: Safety passports, trained staff, diplomas.
- Section — Samenwerking: Measurement, analysis, scheduling, check-in/check-out protocol with client.
- Section — Technieken: Traditional and advanced window-cleaning techniques.

**Observations**
- `[LIGHT]` 180 words. The best-written service page; persuasive copy with a clear process narrative.
- `[MISSING]` No images of glass-cleaning work, equipment, or staff.
- `[MISSING]` Section headings appear styled but are not marked up as H2/H3.

---

### 2.8 Gevelonderhoud (`/gevelreiniging/`)

| Property | Value |
|----------|-------|
| HTTP Status | 200 |
| Title Tag | `GEVELONDERHOUD - HDS Onderhoudsdiensten` |
| H1 | "GEVELONDERHOUD" |
| Word Count | ~130 Dutch words |

**Content Facts**
- Paragraph 1: Building-material expertise, safety, diploma gevelreiniging.
- Paragraph 2: Complex projects involve a construction expert and pollution inventory.
- Services listed:
  - Impregneren van uw gevel
  - Graffiti verwijderen
  - Het reinigen van daken en goten, gevel(beplating), rolluiken, zonnepanelen, reclameborden

**Observations**
- `[LIGHT]` 130 words. Service list is clear; the bullet format aids scannability.
- `[MISSING]` Page title is "GEVELONDERHOUD" but URL is `/gevelreiniging/` — inconsistent terminology.
- `[MISSING]` No images of facade work, before/after comparisons, or equipment.
- `[MISSING]` No H2/H3 structure for the service list and descriptive paragraphs.

---

### 2.9 Vloeronderhoud (`/vloeronderhoud/`)

| Property | Value |
|----------|-------|
| HTTP Status | 200 |
| Title Tag | `VLOERONDERHOUD - HDS Onderhoudsdiensten` |
| H1 | "VLOERONDERHOUD" |
| Word Count | ~140 Dutch words |
| Images | `industriele1.jpg` |

**Content Facts**
- Intro paragraph: Floor types served — linoleum, kunststof, naaldvilt — in schools, hospitals, elderly homes.
- Services listed:
  - Marmoleum vloeren reinigen en in de was zetten
  - Marmoleum vloeren strippen en nieuw wassysteem
  - Natuursteen vloeren reinigen en coaten
  - Natuursteen vloeren schuren en zoeten (kristalliseren)
  - Vloerbedekking shampoorallen
  - Houten vloeren boenen en in de was zetten
  - Grote vloeroppervlakken machinaal reinigen
- Scheduling note: Work performed outside business hours, on weekends, and during school holidays.

**Observations**
- `[LIGHT]` 140 words. Comprehensive service list; flexible scheduling is a practical differentiator.
- `[MISSING]` No H2/H3 structure for the service list.

---

### 2.10 VVE Service (`/vve-service/`)

| Property | Value |
|----------|-------|
| HTTP Status | 200 |
| Title Tag | `VVE SERVICE - HDS Onderhoudsdiensten` |
| H1 | "VVE SERVICE" |
| Word Count | ~100 Dutch words |
| Images | `crystal-stairs.jpg`, `vve-2.jpg`, `20140425_103728-1.jpg`, `view_img_hr.jpg` |

**Content Facts**
- Subtitle: "Onderhoud wooncomplexen"
- Services: Stairwells, halls, escape balconies, garages — scheduled cleaning.
- Additional services: Minor technical maintenance (doorbell, lighting), outdoor cleaning, weed removal, facade cleaning/protection.
- External validation: Listed on vvebelang.nl.

**Observations**
- `[LIGHT]` 100 words. Good cross-sell of complementary services; external VvE Belang listing adds credibility.
- `[MISSING]` No H2/H3 structure.

---

### 2.11 Oplevering Schoonmaak (`/oplevering-schoonmaak/`)

| Property | Value |
|----------|-------|
| HTTP Status | 200 |
| Title Tag | `OPLEVERING SCHOONMAAK - HDS Onderhoudsdiensten` |
| H1 | "OPLEVERING SCHOONMAAK" |
| Word Count | ~90 Dutch words |

**Content Facts**
- Context: Post-construction/renovation cleaning for newly built or renovated buildings.
- Explanation of "0-beurt": stickers, kitresten, cementresten, grofvuil.
- Services listed:
  - Volledige reiniging inclusief glasbewassing
  - Verwijderen cementresten op tegels en vloeren
  - Verwijderen verfresten op kozijnen en ramen
  - Hele gebouw stofvrij maken
  - Grofvuil verwijderen en afvoeren

**Observations**
- `[LIGHT]` 90 words. Clear bullet-format scope definition.
- `[MISSING]` No H2/H3 structure.

---

### 2.12 Industriële Schoonmaak (`/industriele-schoonmaak/`)

| Property | Value |
|----------|-------|
| HTTP Status | 200 |
| Title Tag | `INDUSTRIELE SCHOONMAAK - HDS Onderhoudsdiensten` |
| H1 | "INDUSTRIËLE SCHOONMAAK" |
| Word Count | ~60 Dutch words |

**Content Facts**
- Subtitle: "Industriële reiniging"
- Single paragraph: Leidingen, productievloeren, magazijnstellingen, minimal production downtime.

**Observations**
- `[LIGHT]` 60 words. The thinnest accessible service page.
- `[MISSING]` No service bullet list — single paragraph of prose.
- `[MISSING]` No examples of industrial clients, equipment, or project types.
- `[MISSING]` No H2/H3 structure.

---

### 2.13 Reguliere Schoonmaak — `[BROKEN]`

| Property | Value |
|----------|-------|
| HTTP Status | **404** (Nav URL: `/?page_id=318`) / **404** (Icon URL: `/reguliere-schoonmaak/`) |
| Title Tag | Unknown — page inaccessible |
| Word Count | Unknown — page inaccessible |

**Content Facts**
- Page content is inaccessible.
- This is the **primary cleaning service page** — intended to describe the core "regular cleaning" offering.
- Linked from: main navigation (as "Reguliere Schoonmaak") and homepage icon grid (as "SCHOONMAAK").

**Observations**
- `[BROKEN]` Two different URLs point to this content, both return HTTP 404.
- The homepage icon linking to `/reguliere-schoonmaak/` uses a clean slug; the nav link uses the default WordPress `?page_id=318` query-string URL. Neither resolves.
- This is the **highest-impact missing page** after Contact — it represents the company's primary service line.

---

### 2.14 Contact — `[BROKEN]`

| Property | Value |
|----------|-------|
| HTTP Status | **500** (Internal Server Error) |
| Title Tag | Unknown — page inaccessible |
| Word Count | Unknown — page inaccessible |

**Content Facts**
- Page content is inaccessible.
- This is the **primary conversion page** — the only site-wide contact destination.
- Linked from: main navigation ("CONTACT"), homepage icon grid ("CONTACT"), homepage CTA button.
- Intended content likely includes a Formidable Forms contact form.

**Observations**
- `[BROKEN]` PHP error causes HTTP 500 on every access. The contact form is inaccessible.
- Every "Contact" CTA on the site points to this broken page; there is no fallback contact method on any page (the header phone number and email are the only alternatives).
- This is the **highest-impact technical issue** on the site: zero web-originated form inquiries can be received.

---

### 2.15 Winkel — Webshop (`/winkel/`)

| Property | Value |
|----------|-------|
| HTTP Status | 200 |
| Title Tag | `Winkel - HDS Onderhoudsdiensten` |
| Word Count | Minimal — product titles only |
| Products | 14 Airfixr air purification products (WooCommerce) |

**Content Facts**
- WooCommerce shop page listing Airfixr products:
  - Airfixr 150 (€795,00 excl. BTW)
  - Airfixr 60 (€325,00 excl. BTW)
  - Airfixr 75 (€595,00 excl. BTW)
  - Airfixr Ionisator 220V (€95,00 excl. BTW)
  - Airfixr Panel (€395,00 excl. BTW)
  - Airfixr Panel RVS (€425,00 excl. BTW)
  - Airfixr Panel Silent (€395,00 excl. BTW)
  - F7 Filter 150 (€49,00 excl. BTW)
  - F7 Filter 75 (€35,00 excl. BTW)
  - Ophangsysteem Airfixr Panel RVS (price not shown)
  - Servicepakket 150 (price not shown)
  - Servicepakket 75 (price not shown)
  - UV-C Lamp 16W (price not shown)
  - UV-C Lamp 40W (price not shown)
- Pagination: `/winkel/page/2/`
- Cart: `/winkelmand/`
- 15 product images (referenced in `product-sitemap.xml`)

**Observations**
- `[THIN]` No shop introduction, no category description, no explanation of Airfixr or why a cleaning company sells air purifiers.
- `[MISSING]` Product prices listed excl. BTW (VAT); this is noted on product pages.
- `[MISSING]` No connection between the Airfixr product line and the cleaning services (cross-sell opportunity absent).
- The shop is functional and products have individual pages, but the product line is **unrelated to the core cleaning business** — a potential brand-confusion risk.

---

### 2.16 Content Summary Matrix

| # | Page | Status | Words | Images | Headings | Severity |
|---|------|--------|-------|--------|----------|----------|
| 1 | Home (`/`) | 200 | ~30 | 9 | H1 only | `[THIN]` |
| 2 | Over HDS | 200 | ~120 | 0 | H1 only | `[LIGHT]` |
| 3 | Referenties | 200 | ~25 | 1 | H1 only | `[THIN]` |
| 4 | Vacatures | 200 | ~5 | 2 | H1 only | `[THIN]` |
| 5 | Kwaliteit & Veiligheid | 200 | ~150 | 2 | H1 only | `[LIGHT]` |
| 6 | Downloads | 200 | ~10 | 2 | H1 only | `[THIN]` |
| 7 | Glasbewassing | 200 | ~180 | 0 | H1 only | `[LIGHT]` |
| 8 | Gevelonderhoud | 200 | ~130 | 0 | H1 only | `[LIGHT]` |
| 9 | Vloeronderhoud | 200 | ~140 | 1 | H1 only | `[LIGHT]` |
| 10 | VVE Service | 200 | ~100 | 4 | H1 only | `[LIGHT]` |
| 11 | Oplevering Schoonmaak | 200 | ~90 | 0 | H1 only | `[LIGHT]` |
| 12 | Industriële Schoonmaak | 200 | ~60 | 0 | H1 only | `[LIGHT]` |
| 13 | Reguliere Schoonmaak | **404** | — | — | — | `[BROKEN]` |
| 14 | Contact | **500** | — | — | — | `[BROKEN]` |
| 15 | Winkel (Webshop) | 200 | Minimal | 15 | Per product | `[THIN]` (shop intro) |

---

## 3. Blog Posts

| # | Title | URL | Date | Category | Word Count | Comment |
|---|-------|-----|------|----------|------------|---------|
| 1 | Hallo wereld! | `/2015/06/29/hallo-wereld/` | 29 Jun 2015 | Geen categorie | Default post (~20 words) | Default WordPress "Hello World" post. 1 comment present. |
| 2 | Kwaliteit & Veiligheid | `/2015/08/25/kwaliteit-veiligheid/` | 25 Aug 2015 | Unknown | Unknown | Suspected redirect to the `/kwaliteit-veiligheid/` page; content unverified. |

**Observations**
- `[MISSING]` Only two posts; the most recent is dated August 2015.
- `[MISSING]` No service-related articles, project showcases, cleaning tips, company news, or industry updates.
- The "Hello World" default post was never deleted — unprofessional appearance if indexed.

---

## 4. Reusable Elements

### 4.1 Header (Global)

| Element | Detail |
|---------|--------|
| Logo | `hds200x81.png` — "HDS Onderhoudsdiensten" (200×81 px) |
| Phone | `0164-652846` — clickable `tel:` link |
| Email | `info@helderduidelijkschoon.nl` — clickable `mailto:` link |
| Cart icon | 0 items → links to `/winkelmand/` |
| Navigation | Main menu (see §1.1) |

### 4.2 Footer (Global)

| Element | Detail |
|---------|--------|
| Search bar | WordPress default search form |
| Facebook widget/link | Present — content unverified |
| Instagram widget | `[BROKEN]` — Displays: "Instagram did not return a 200" |
| Developer credit | "Ontworpen door: @Pi-Apps" → links to `api-apps.nl` |
| Privacy policy link | `[MISSING]` — No link to a privacy statement |
| Cookie consent | `[MISSING]` — No cookie notice or consent mechanism |
| KVK / BTW | `[MISSING]` — No chamber-of-commerce registration or VAT number |
| Physical address | `[MISSING]` — No business address |

### 4.3 Forms

| Form | Plugin/System | Location | Status |
|------|--------------|----------|--------|
| Contact form | Formidable Forms | `/contact/` (inaccessible) | `[BROKEN]` — Page returns HTTP 500; form cannot be rendered |
| Testimonials submission | HMS Testimonials | `/referenties/` | `[MISSING]` — Shortcode renders but form is empty |
| Testimonials display | HMS Testimonials | `/referenties/` | `[MISSING]` — Shortcode renders but no testimonials are displayed |
| Comment form | WordPress default | Blog posts (`/2015/06/29/hallo-wereld/`) | Functional (1 comment present) |
| Search form | WordPress default | Footer (global) | Functional |

---

## 5. Media Assets

### 5.1 Logo

| File | Dimensions | Usage |
|------|-----------|-------|
| `hds200x81.png` | 200×81 px | Site-wide header logo |

### 5.2 Homepage Icons

| File | Dimensions | Label | Links To | Link Status |
|------|-----------|-------|----------|-------------|
| `overons100x100db1.png` | 100×100 px | OVER HDS | `/over-hds/` | 200 |
| `regulier.png` | 100×100 px | SCHOONMAAK | `/reguliere-schoonmaak/` | **404** |
| `glasbewassing1turq.png` | 100×100 px | GLAS | `/glasbewassing` | 200 |
| `VVE.png` | 100×100 px | VVE | `/vve` | 200 |
| `gevel2.png` | 100×100 px | GEVEL | `/gevelreiniging/` | 200 |
| `kwaliteit1.png` | 100×100 px | KWALITEIT | `/kwaliteit-veiligheid/` | 200 |
| `vloer1.png` | 100×100 px | VLOER | `/vloeronderhoud/` | 200 |
| `contact1.png` | 100×100 px | CONTACT | `/contact/` | **500** |

### 5.3 Page-Specific Images

| Page | Image File(s) | Type |
|------|--------------|------|
| Referenties | `Afbeelding6-2.png` | Client logo |
| Referenties | `20140903_090226.jpg`, `20140903_090128.jpg` | Unplaced/context unverified |
| Vacatures | 2 JPG files | Scanned Word documents (vacancy posters) |
| Kwaliteit & Veiligheid | `mvo-ondernemen1-2.png`, `veiligheid1.png` | Topic illustrations |
| VVE Service | `crystal-stairs.jpg`, `vve-2.jpg`, `20140425_103728-1.jpg`, `view_img_hr.jpg` | Property/service photos |
| Vloeronderhoud | `industriele1.jpg` | Floor-cleaning photo |
| Downloads | `download-enter-button-key.png`, `download2.png` | Download link icons |

### 5.4 Product Images (Airfixr)

- Approximately 15 product images referenced in `product-sitemap.xml`. Individual filenames not inventoried.

### 5.5 Attachment Pages

- Approximately 50 attachment pages detected in `attachment-sitemap.xml`. These are auto-generated WordPress pages for each uploaded media file.
- `[MISSING]` Attachment pages are indexed and included in the XML sitemap — they contain no unique content and consume crawl budget.

---

## 6. Metadata Summary

### 6.1 Title Tags

All accessible pages follow the pattern `[Page Name] - HDS Onderhoudsdiensten`, generated by Yoast SEO.

| Observation | Detail |
|-------------|--------|
| Homepage title | "HOME" — generic, not descriptive of the business |
| Service page titles | Adequate but not keyword-optimized |
| Consistency | Uniform template applied across all pages |

### 6.2 Meta Descriptions

| Status | Detail |
|--------|--------|
| `[MISSING]` | No custom meta descriptions set on any page. |
| Default | Yoast outputs the site title template with no description field populated. |

### 6.3 Open Graph & Social Cards

| Tag | Status |
|-----|--------|
| `og:locale` | Present — `nl_NL` |
| `og:type` | Present — `website` |
| `og:title` | Present — site title |
| `og:description` | `[MISSING]` — absent on all pages |
| `og:image` | `[MISSING]` — absent on all pages |
| `twitter:card` | Present — `summary_large_image` |
| Twitter image/description | `[MISSING]` — absent on all pages |

### 6.4 Structured Data (Schema)

| Schema Type | Status | Notes |
|-------------|--------|-------|
| WebPage | Present | Yoast auto-generated |
| BreadcrumbList | Present | Home only (single item) |
| WebSite (with SearchAction) | Present | Yoast auto-generated |
| Organization | `[MISSING]` | No company schema markup |
| LocalBusiness | `[MISSING]` | No local-business schema markup |
| Service | `[MISSING]` | No service schema on any service page |
| Product | Unknown | WooCommerce may auto-generate — not verified |
| FAQ | `[MISSING]` | No FAQ page or FAQ schema |
| Review | `[MISSING]` | No reviews or review schema |

### 6.5 Sitemaps & Indexability

| Resource | HTTP Status | Content |
|----------|-------------|---------|
| `/robots.txt` | 200 | Allow all; crawl-delay: 5 |
| `/sitemap.xml` | 200 | Sitemap index |
| `/page-sitemap.xml` | **500** | `[BROKEN]` — Page sitemap unavailable to search engines |
| `/post-sitemap.xml` | 200 | 2 blog posts |
| `/product-sitemap.xml` | 200 | 18 product entries |
| `/attachment-sitemap.xml` | 200 | ~50 attachment pages |
| `/category-sitemap.xml` | 200 | Post categories |
| `/product_cat-sitemap.xml` | 200 | Product categories |
| `/author-sitemap.xml` | 200 | Author archives |
| `/xmlrpc.php` | 200 | XML-RPC enabled |

**Observations**
- `[BROKEN]` The page sitemap (`/page-sitemap.xml`) returns HTTP 500 — search engines receive no structured listing of the site's pages.
- `[MISSING]` Attachment pages (~50) are included in sitemaps, consuming crawl budget with no unique content value.
- XML-RPC endpoint is exposed — a known vector for brute-force attacks on WordPress sites.

---

## Appendix: Cross-Reference to Related Documents

| Document | Content |
|----------|---------|
| [SiteMap.md](./SiteMap.md) | URL hierarchy, full sitemap listing, broken-link map, URL inconsistency table |
| [SEOAudit.md](./SEOAudit.md) | Technical SEO, on-page analysis, local SEO, performance indicators, ranked priority issues |
| [BusinessRequirements.md](./BusinessRequirements.md) | Service portfolio details, target audiences, operational model, certifications, legal compliance gaps, stakeholder map |
