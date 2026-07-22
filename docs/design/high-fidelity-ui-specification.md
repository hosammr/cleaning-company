# HDS Onderhoudsdiensten — High-Fidelity UI Specification

**Document ID:** HFUI-001 | **Version:** 1.0.0 | **Status:** Approved for Frontend Implementation
**Project:** helderduidelijkschoon.nl — Ground-Up Rebuild
**Language:** Nederlands (nl-NL) | **Date:** July 2026

**Prerequisite Documents:**
DS-001 (Design System), UXW-001 (UX Wireframes), FS-001 (Functional Specification), SEO-001 (SEO Implementation), NFR-001 (Non-Functional Requirements)

**Role:** This document translates approved wireframes into a production-ready UI specification with exact visual values. Every color, font size, spacing, shadow, and interactive state is defined. A frontend developer can implement every screen directly from this specification.

---

## 1. Visual Design Principles

### 1.1 Brand Personality Expression

| Brand Attribute | Visual Expression |
|---|---|
| **Helder** (Clear) | Generous whitespace: `spacing-16` (64px) between major sections. No borders on cards unless hovered. Subtle shadows only. |
| **Duidelijk** (Plain) | Single CTA per section. Body text `dark-gray` (#333333) on `white` — maximum readability. No multi-color decorative elements. |
| **Schoon** (Clean) | `white` (#ffffff) as dominant background. `light-gray` (#f5f5f5) for alternating sections only. Blue accent sparingly — CTA buttons, links, active nav. |
| **Betrouwbaar** (Reliable) | Consistent patterns: every card has identical padding, border-radius, shadow, and hover behavior. Navigation never moves. Footer never changes. |
| **Vakkundig** (Professional) | Open Sans throughout — one typeface, 9 sizes, 2 weights. No novelty fonts. Polished line-heights. |
| **Regionaal** (Regional) | Hero images show real West-Brabant/Zeeland locations. Service area section prominent on homepage. Map embed (when address provided). |

### 1.2 Visual Hierarchy Rules

```
Priority 1 (Dominant):   H1 + Hero CTA button — 5-xl (48px) bold white, accent/orange CTA
Priority 2 (Section):    H2 headings — 4-xl (36px) bold black, section divider lines
Priority 3 (Content):    H3 headings — 2-xl (24px) semibold, body text 16px dark-gray
Priority 4 (Supporting): Captions, meta, helper text — s (14px) gray
Priority 5 (Background): Section background colors, decorative icons, dividers
```

### 1.3 White Space Strategy

| Context | Vertical Spacing | Horizontal Spacing |
|---|---|---|
| **Major sections** (Hero, CTA Banner) | `spacing-20` (80px) — desktop | Container padded `spacing-4` (16px) |
| **Standard sections** | `spacing-16` (64px) | Container padded `spacing-4` |
| **Alternating sections** | `spacing-16` (64px) + `light-gray` background | Same |
| **Content blocks within sections** | `spacing-8` (32px) between blocks | — |
| **Cards within grids** | Grid gap `spacing-6` (24px) | — |
| **Form field groups** | `spacing-6` (24px) between groups | Fields within groups: `spacing-4` (16px) gap |

### 1.4 Readability Standards

| Element | Specification |
|---|---|
| **Body text max-width** | `65ch` (~700px at 16px). Never wider. |
| **Line height** | Body: `1.65`. Headings: `1.2`–`1.3`. |
| **Paragraph spacing** | `spacing-4` (16px) margin-bottom. |
| **Link distinction** | `primary` (#1a73e8) color + underline. No color-only distinction (WCAG 1.4.1). |
| **Text on images** | Always use dark gradient overlay (`rgba(0,0,0,0.4)` → `rgba(0,0,0,0.6)`) behind white text on hero images. |

### 1.5 Consistency Enforcement

| Rule | Enforcement |
|---|---|
| Same component = Same appearance everywhere | CTA button is always accent/orange, 18px bold, 16×32px padding. Never varies. |
| Cards have identical structure | 8px border-radius, `shadow-sm` default, `shadow-md` on hover, 2px lift on hover. |
| Forms have identical field styling | 4px border-radius, `gray` border, `primary` focus ring. All labels 14px semibold `dark-gray`. |
| Buttons have identical shape language | All buttons: 8px border-radius. Unless pill variant (9999px). |

### 1.6 Trust Signal Placement

| Trust Signal | Visual Treatment | Location |
|---|---|---|
| Client logos | Grayscale, 120px max-height, consistent padding | Homepage §4, Referenties page |
| Certifications (OSB, VCA) | Color logos preferred, 80px max-height | Over HDS page, Kwaliteit & Veiligheid page |
| KVK / BTW numbers | `s` (14px), `gray` (#757575) | Footer, Contact page |
| VvE Belang partnership | Logo + text link | VVE Service page, footer |
| Star ratings | Filled stars: `accent` (#ea8600). Empty: `light-gray`. 20px size. | Testimonials |
| Google Maps embed | 100% width, 400px height, `border-radius: md` (8px) | Contact page, Service Area section |

### 1.7 Conversion Optimization

| Technique | Implementation |
|---|---|
| **CTA color contrast** | Accent orange (#ea8600) on white/dark backgrounds — stands out from blue primary palette |
| **CTA size hierarchy** | Hero CTA: large (18px, 16×32px padding). Section CTA: medium (16px, 12×24px padding). |
| **CTA repetition** | Hero CTA (above fold) + CTA Banner (bottom) + sticky mobile CTA (always visible on mobile) |
| **Directional cues** | CTA buttons include `CaretRight` arrow: "Offerte aanvragen →". Testimonial arrows point toward CTA section. |
| **White space around CTAs** | CTA buttons isolated with `spacing-6` minimum clearance. Never crowded by adjacent elements. |
| **Phone number prominence** | Header: 18px bold `primary` color. Contact page: 24px bold. Always clickable `tel:` link. |

---

## 2. Homepage UI

**URL:** `/` | **Template:** `front-page.php` | **H1:** "Helder en Duidelijk voor het Schoonste resultaat!"

### 2.1 Section 1 — Hero

**Layout:** Full viewport width (`100vw`). Content centered at `wideSize` (1200px). Left-aligned text.

**Background:** `linear-gradient(135deg, #1a73e8 0%, #1557b0 100%)` — primary gradient, no image.

**Dimensions:** Min-height `60vh` (desktop), `50vh` (mobile). Padding: `spacing-20` (80px) vertical desktop, `spacing-12` (48px) mobile.

**Typography:**
- H1: `5-xl` (48px desktop, 36px mobile), `white` (#ffffff), weight 700, line-height 1.2, max-width 700px
- Subtitle: `l` (18px desktop, 16px mobile), `white` at opacity 0.92, weight 400, line-height 1.65, max-width 550px, margin-top `spacing-4`
- USP keywords in subtitle wrapped in `<span>` with weight 600

**CTA Button:**
- Variant: CTA (accent). Background: `accent` (#ea8600). Text: `white`, `l` (18px), weight 700.
- Padding: 16px 32px. Border-radius: 8px. `CaretRight` icon after text.
- Label: "Vrijblijvende offerte" → links to `/offerte-aanvragen/`
- Hover: darken accent to `#d67900`, `shadow-md` lift
- Focus: `outline: 2px solid white`, `outline-offset: 2px` (white ring on dark bg)
- Margin-top: `spacing-8` (32px) below subtitle

### 2.2 Section 2 — Service Card Grid

**Background:** `white` (#ffffff)
**Padding:** `spacing-16` (64px) vertical

**Section Header:**
- H2: "Onze Diensten", `4-xl` (36px), weight 700, `black` (#1a1a1a), text-align: center, margin-bottom: `spacing-10` (40px)

**Grid:** `display: grid; grid-template-columns: repeat(3, 1fr); gap: spacing-6 (24px);`
- Desktop: 3 columns (3+3+1 layout: row 1 = 3, row 2 = 3, row 3 = 1 centered)
- Tablet: 2 columns
- Mobile: 1 column

**Service Card (per card):**
- Background: `white`
- Border-radius: 8px
- Box-shadow: `shadow-sm` (`0 1px 3px rgba(0,0,0,0.12)`)
- Padding: `spacing-6` (24px) all sides
- Hover: translateY(-2px), `shadow-md` (`0 4px 12px rgba(0,0,0,0.1)`), transition 150ms ease
- Entire card is a link (stretched `::after` pseudo-element)

**Card Content (top to bottom):**
- Icon: Phosphor Bold, 32px, `primary` (#1a73e8), centered, margin-bottom: `spacing-4` (16px)
- Title: H3 equivalent, `xl` (20px), weight 600, `black`, margin-bottom: `spacing-2` (8px). Clickable — link to service page.
- Description: `m` (16px), weight 400, `dark-gray` (#333333), line-height 1.5, 1-2 lines with text clamp
- "Lees meer →": `s` (14px), weight 600, `primary`, margin-top: `spacing-3` (12px). Ghost button treatment.

**Order:** menu_order (ADR D-014): Reguliere Schoonmaak, Glasbewassing, Gevelreiniging, Vloeronderhoud, VVE Service, Oplevering Schoonmaak, Industriele Schoonmaak

### 2.3 Section 3 — USP Grid

**Background:** `light-gray` (#f5f5f5) (alternating)
**Padding:** `spacing-16` (64px) vertical

**Section Header:**
- Label: "Waarom HDS?", `s` (14px), weight 600, `primary` (#1a73e8), uppercase, letter-spacing 1px, text-align: center
- H2: "Waarom kiezen voor HDS Onderhoudsdiensten?", `4-xl` (36px), weight 700, `black`, text-align: center, margin-top: `spacing-2`, margin-bottom: `spacing-10`

**Grid:** `repeat(3, 1fr)` desktop, `repeat(2, 1fr)` tablet, `1fr` mobile. Gap: `spacing-6` (24px).

**USP Card (per item):**
- Background: `white`, border-radius: 8px, `shadow-sm`
- Padding: `spacing-6` (24px)
- Hover: same as service card (lift + shadow)
- Icon: Phosphor Regular, 24px, `primary` (#1a73e8), margin-bottom: `spacing-3`
- Title: `l` (18px), weight 600, `black`, margin-bottom: `spacing-2`
- Body: `m` (16px), weight 400, `dark-gray`, line-height 1.5

**Six USPs:**
| Icon | Title | Body |
|---|---|---|
| `UserCheck` | Vast opgeleid personeel | Onze medewerkers zijn vast in dienst en vakkundig opgeleid in alle reinigingstechnieken. |
| `ShieldCheck` | Veiligheid & Certificering | Wij voldoen aan alle Arbo-wetgeving. RI&E verplicht per project. Gecertificeerd volgens de hoogste veiligheidsnormen. |
| `UserFocus` | Één aanspreekpunt | U heeft altijd één vast contactpersoon die uw wensen kent en direct schakelt. |
| `ClipboardText` | Maatwerk planning | Elke opdracht begint met analyse. Wij maken een plan op maat voor uw situatie. |
| `Leaf` | Milieubewust (MVO) | Wij gebruiken zo min mogelijk milieubelastende producten en zorgen goed voor onze medewerkers. |
| `MapPinArea` | Regio specialist | Als reinigingsspecialist van West-Brabant en Zeeland kennen wij de regio en zijn wij altijd dichtbij. |

### 2.4 Section 4 — Client Logo Carousel

**Conditional:** `display: none` on section wrapper if no logos in Customizer (ADR D-015).

**Background:** `white`
**Padding:** `spacing-16` (64px) vertical

**Section Header:**
- H2: "Zij gingen u voor", `4-xl` (36px), weight 700, `black`, text-align: center, margin-bottom: `spacing-10`

**Carousel:**
- Horizontal flex with `overflow-x: auto`, `scroll-snap-type: x mandatory`, hide scrollbar
- Logo items: `flex: 0 0 180px`, height 100px, `object-fit: contain`, grayscale filter (CSS: `filter: grayscale(100%); opacity: 0.7;`), transition to color on hover (`filter: none; opacity: 1;`)
- Each logo: `alt="[Client Name]"`, `loading="lazy"`
- Gap between logos: `spacing-8` (32px)
- No auto-scroll. Manual swipe/trackpad scroll only.

### 2.5 Section 5 — Testimonials

**Conditional:** `display: none` on section wrapper if no testimonials in `hds_testimonial` CPT (ADR D-015).

**Background:** `light-gray` (#f5f5f5)
**Padding:** `spacing-16` (64px) vertical

**Section Header:**
- H2: "Wat onze klanten zeggen", `4-xl` (36px), weight 700, `black`, text-align: center, margin-bottom: `spacing-10`

**Grid:** `repeat(3, 1fr)` desktop, `1fr` tablet/mobile. Gap: `spacing-6` (24px).

**Testimonial Card:**
- Background: `white`, border-radius: 8px, `shadow-sm`
- Padding: `spacing-8` (32px)
- Decorative quotation mark: `::before` pseudo-element, content: `"`, `5-xl` (48px), `light-gray` at opacity 0.3, position absolute top-left at `spacing-4`
- Quote text: `l` (18px), italic, weight 400, `dark-gray`, line-height 1.7, margin-bottom: `spacing-4`
- Divider: `1px solid light-gray`, margin: `spacing-4` 0
- Author: `m` (16px), weight 600, `black`
- Company: `s` (14px), weight 400, `gray`, preceded by "— "
- Star rating: 5 stars, `accent` (#ea8600) filled, `light-gray` empty, 16px size, margin-top: `spacing-2`

**Source:** `hds/testimonial` custom block. Query limit: 3-5.

### 2.6 Section 6 — CTA Banner

**Background:** `primary` (#1a73e8), full-width
**Padding:** `spacing-12` (48px) vertical

**Layout:** Desktop: `display: flex; justify-content: space-between; align-items: center;` within `wideSize` container. Mobile: stacked, centered.

**Typography:**
- H2: "Wilt u een vrijblijvende offerte?", `3-xl` (30px), weight 700, `white`, max-width 60%
- Body: "Wij denken graag met u mee over de beste oplossing.", `m` (16px), `white` at opacity 0.9

**CTA Button:** Same as Hero CTA (accent/orange, large). Label: "Offerte aanvragen →" → `/offerte-aanvragen/`

### 2.7 Section 7 — Service Area

**Background:** `white`
**Padding:** `spacing-16` (64px) vertical

**Section Header:**
- H2: "Ons Werkgebied", `4-xl` (36px), weight 700, `black`, text-align: center, margin-bottom: `spacing-6`

**Content:**
- Body text: `l` (18px), weight 400, `dark-gray`, text-align: center, max-width 600px, margin: 0 auto
- Text: "Wij bedienen bedrijven in heel West-Brabant en Zeeland, waaronder Bergen op Zoom, Roosendaal, Goes, Middelburg, Terneuzen en omliggende gemeenten."

**Google Maps Embed (Conditional):**
- Only if `hds_address` Customizer value set (MI-01)
- Wrapped in Complianz consent placeholder: gray placeholder with text "Klik om Google Maps te laden" and map icon
- On consent: loads interactive Google Maps iframe
- Dimensions: 100% width, 400px height, `border-radius: md` (8px)
- Margin-top: `spacing-8`

### 2.8 Section 8 — Latest Blog Posts

**Conditional:** `display: none` if no published posts (ADR D-015).

**Background:** `light-gray` (#f5f5f5)
**Padding:** `spacing-16` (64px) vertical

**Section Header:**
- H2: "Tips & Nieuws", `4-xl` (36px), weight 700, `black`, text-align: center, margin-bottom: `spacing-10`

**Grid:** `repeat(3, 1fr)` desktop, `1fr` tablet/mobile. Gap: `spacing-6` (24px).

**Blog Card:**
- Background: `white`, border-radius: 8px, `shadow-sm`, overflow: hidden
- Image: 16:9 aspect ratio, `object-fit: cover`, `border-radius: 8px 8px 0 0`, `loading="lazy"`
- Body padding: `spacing-4` (16px)
- Date: `xs` (12px), weight 400, `gray` (#757575), format "21 juli 2026"
- Title: H3 equivalent, `xl` (20px), weight 600, `black`, link to post, margin: `spacing-2` 0
- Excerpt: `m` (16px), `dark-gray`, 2-line clamp via `-webkit-line-clamp: 2`
- "Lees meer →": `s` (14px), weight 600, `primary`, inline link, margin-top: `spacing-2`

**Footer Link:** "Bekijk alle artikelen →", `primary`, weight 600, centered below grid, margin-top: `spacing-8`. Links to `/kennisbank/`.

---

## 3. Service Page UI (P02-P08)

**Template:** `page-templates/page-service.php`
**Schema:** Service JSON-LD (auto-generated per page)

### 3.1 Section 1 — Hero

**Background:** Condition A — if `hds_hero_image` custom field set: image with `linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.55))` overlay. Condition B — no image: `primary` gradient (same as homepage).

**Dimensions:** Min-height `50vh` desktop, `40vh` mobile. Padding: `spacing-20` vertical.

**Typography:**
- H1: [Service Name], `5-xl` (48px desktop, 36px mobile), `white`, weight 700, line-height 1.2
- Subtitle (from `hds_subtitle` custom field): `l` (18px), `white` at opacity 0.92, weight 400, max-width 600px, margin-top: `spacing-3`
- If subtitle empty: no element rendered. No blank space.

**CTA Button:** Same as homepage hero CTA (accent/orange, large). If `hds_cta_override` set, use that text. Default: "Vrijblijvende offerte".

### 3.2 Section 2 — Introduction

**Background:** `white`
**Padding:** `spacing-16` vertical
**Content Width:** `contentSize` (780px) centered

**Typography:**
- H2: Optional — not required if intro is short. If present: `4-xl` (36px), `black`, weight 700
- Body: `m` (16px), `dark-gray`, line-height 1.65, max-width 65ch
- 1-3 paragraphs

### 3.3 Section 3 — Our Approach / Benefits

**Background:** `light-gray`
**Padding:** `spacing-16` vertical
**Content Width:** `wideSize` (1200px)

**Layout:** Content with Image pattern. `display: grid; grid-template-columns: 1fr 1fr; gap: spacing-10 (40px); align-items: center;`
- Odd sections: text left, image right
- Even sections: image left, text right
- Mobile: stacked (image above text)

**Typography:**
- H2: "Onze Aanpak" or service-specific variant, `4-xl` (36px), `black`, weight 700
- Body: `m` (16px), `dark-gray`, line-height 1.65

**Image:** 16:9 aspect ratio, `border-radius: md` (8px), `shadow-sm`, `object-fit: cover`, `loading="lazy"`, explicit width/height

### 3.4 Section 4 — Service Details / Scope

**Background:** `white`
**Padding:** `spacing-16` vertical
**Content Width:** `contentSize` (780px) centered

**Typography:**
- H2: "Onze [Service Name] diensten" or "Wat doen we?", `4-xl` (36px), `black`, weight 700, margin-bottom: `spacing-6`
- Service Icon List: unordered list with `is-style-icon-list` class
- Each item: `m` (16px), `dark-gray`, line-height 1.5, padding-left: `spacing-6`, `::before` checkmark icon in `secondary` (#34a853)
- Item gap: `spacing-3` (12px)

### 3.5 Section 5 — Cross-Sell Services

**Background:** `light-gray`
**Padding:** `spacing-16` vertical

**Section Header:**
- H2: "Gerelateerde Diensten", `4-xl` (36px), weight 700, `black`, text-align: center, margin-bottom: `spacing-10`

**Grid:** 2-3 columns depending on cross-sell count per service. Gap: `spacing-6`.

**Cards:** Same `hds/service-card` block as homepage. Identical visual treatment.

### 3.6 Section 6 — Optional FAQ

**Conditional:** Entire section hidden if no FAQ content on page.

**Background:** `white`
**Padding:** `spacing-16` vertical
**Content Width:** `contentSize` (780px) centered

**Section Header:**
- H2: "Veelgestelde Vragen over [Service Name]", `4-xl` (36px), `black`, weight 700, margin-bottom: `spacing-8`
- Footer link: "Bekijk alle vragen →", `primary`, weight 600, links to `/veelgestelde-vragen/`

**Accordion Items:** Yoast/Rank Math FAQ Block rendering. Each:
- Question header: `l` (18px), weight 600, `dark-gray`, padding: `spacing-4` 0, border-bottom: `1px solid light-gray`, cursor: pointer. `CaretDown` icon (24px, `gray`) rotates 180° on open.
- Answer panel: padding: `spacing-4`, `m` (16px), `dark-gray`, line-height 1.65

### 3.7 Section 7 — CTA Banner

Identical to global CTA banner (§2.6). Heading: "Interesse in [Service Name]?" with CTA.

---

## 4. Category Landing Page UI (P09-P10)

**Template:** `page-templates/page-category-landing.php`

### 4.1 Hero

Same visual treatment as service page hero but without `hds_hero_image` custom fields. Always primary gradient background. H1: Category name. Subtitle: category description.

### 4.2 Introduction

**Background:** `white`, padding `spacing-16`, content width `contentSize` (780px). 2-3 paragraphs of introductory text about the category.

### 4.3 Service Card Grid

Same grid and card styling as homepage service grid (§2.2). Cards link to individual service pages.

**"Glas & Gevel"**: 2 cards (Glasbewassing, Gevelreiniging). 2 columns desktop, 1 column mobile.
**"Schoonmaakdiensten"**: 5 cards. 3+2 wrapped grid desktop, 1 column mobile.

### 4.4 CTA Banner

Identical to global CTA banner (§2.6).

---

## 5. About Pages UI (P11-P12)

**Template:** `page-templates/page-about.php`

### 5.1 Over HDS (`/over-hds/`)

**Hero:** Same as service page hero. H1: "Over HDS Schoonmaakdiensten". Subtitle: "Deskundigheid, ervaring en vertrouwen".

**Section 1 — Company Story:**
- Content with Image pattern (same as §3.3)
- H2: "Ons Verhaal"
- Image: team photo (MI-09), or placeholder stock photo of cleaning professionals
- Text: company history, founding year, origin story (MI-19). If not provided: focus on mission + values.

**Section 2 — Mission & Values:**
- USP Grid — same 6 USPs as homepage §2.3. Same visual treatment.

**Section 3 — Team (Conditional):**
- `display: none` if no team content
- Team member grid: `repeat(4, 1fr)` desktop, `repeat(2, 1fr)` tablet, `1fr` mobile
- Team card: photo (1:1 aspect ratio, `border-radius: pill`, 150px size) + name + role. Centered text.

**Section 4 — Certifications & Partnerships:**
- Logo grid: 4-5 columns desktop, 2 tablet, 1 mobile
- Certification logos: 80px max-height, `object-fit: contain`, color (not grayscale — these are trust signals)
- OSB logo, VCA logo (if applicable), VvE Belang logo
- Each logo linked or with caption

### 5.2 Kwaliteit & Veiligheid (`/kwaliteit-veiligheid/`)

**Hero:** H1: "Kwaliteit & Veiligheid MVO". No subtitle.

**Section 1 — Kwaliteit:** `white` bg. H2: "Kwaliteit". Body text. Content width 780px.

**Section 2 — Veiligheid:** `light-gray` bg. H2: "Veiligheid". Body text + Service Icon List (checkmarks) for safety certifications.

**Section 3 — MVO:** `white` bg. H2: "Maatschappelijk Verantwoord Ondernemen". Body text.

**CTA Banner:** Standard.

---

## 6. Referenties Page UI (`/referenties/` — P13)

**Hero:** H1: "Referenties". Subtitle: "In opdracht van o.a. onderstaande opdrachtgevers..."

**Section 1 — Client Logos (Conditional):**
- Same as homepage Client Logo Carousel (§2.4). If >8 logos, use carousel. If ≤8, use static grid.
- `display: none` if no logos (ADR D-015).

**Section 2 — Testimonials (Conditional):**
- Same as homepage Testimonials (§2.5). Show ALL testimonials from CPT (no count limit), paginated if >9.
- `display: none` if no testimonials (ADR D-015).

**Empty State (both hidden):**
- Centered text: "Wij zijn trots op onze opdrachtgevers. Binnenkort leest u hier hun ervaringen."
- CTA: "Neem contact op →" → `/contact/`. Secondary button style.

---

## 7. Vacatures Page UI (`/vacatures/` — P14)

**Hero:** H1: "Vacatures". Subtitle: "Wordt u onze collega?"

**Section 1 — Working at HDS:**
- Background: `white`
- H2: "Werken bij HDS Onderhoudsdiensten"
- 2-3 paragraphs about culture, benefits, training
- Optional USP Grid (3 items): "Vast contract", "Opleiding & ontwikkeling", "Prettige werksfeer"

**Section 2 — Open Vacancies:**
- Background: `light-gray`
- Vacancy cards rendered by `hds/job-listing` custom block

**Vacancy Card (collapsed):**
- Background: `white`, border-radius: 8px, `shadow-sm`, padding: `spacing-6`
- Border-left: `4px solid primary` (#1a73e8) as accent
- Grid: `grid-template-columns: 1fr auto`
- Left: Title (H3, `xl`, weight 600, `black`) + meta row (hours, location, start date — `s`, `gray`, icons inline)
- Right: "Bekijk ▾" toggle button (ghost style, `primary` color). `aria-expanded="false"`.
- Hover: `shadow-md`

**Vacancy Card (expanded):**
- `aria-expanded="true"`. Chevron rotates 180°.
- Expanded panel below collapsed content: padding-top `spacing-4`, border-top `1px solid light-gray`
- Description: `m` (16px), `dark-gray`, rich text from CPT
- Meta details: hours, location, start date, deadline in a definition list (`<dl>`)
- CTA: "Solliciteer direct" → mailto: link to `hds_application_email` (fallback: `info@helderduidelijkschoon.nl`), or links to application form section
- Expand animation: `max-height` transition, 250ms ease

**Empty State:**
- Centered: "Er zijn op dit moment geen openstaande vacatures."
- "Stuur een open sollicitatie naar info@helderduidelijkschoon.nl"

**Section 3 — Application Form (GF-3):**
- H2: "Open Sollicitatie"
- Gravity Forms shortcode rendering GF-3
- Fields: Naam*, E-mail*, Telefoon*, Motivatie*, CV upload* (5MB, PDF/DOCX), Privacy akkoord*
- Form styling: see §14 Form UI

---

## 8. Downloads Page UI (`/downloads/` — P15)

**Hero:** H1: "Downloads". Subtitle: "Algemene voorwaarden en documenten"

**Download Cards:**
- Background: `white`, padding: `spacing-16`
- Grid: `repeat(2, 1fr)` desktop, `1fr` mobile. Gap: `spacing-6`.
- Card: `white` bg, border-radius 8px, `shadow-sm`, padding: `spacing-6`, horizontal flex layout
- Left: `FilePdf` icon, 32px, `error` (#d32f2f — PDF red), flex-shrink: 0, margin-right: `spacing-4`
- Center: Filename (`l`, weight 600, `black`) + description (`s`, `gray`) + file size (`xs`, `gray`)
- Right: "Download ⬇" — secondary button, `s` (14px). Links to PDF in media library.

**Note section:** Info alert (blue left border, light blue background). "Heeft u vragen over onze voorwaarden? Neem contact op via 0164-652846..."

---

## 9. Contact Page UI (`/contact/` — P16)

**Template:** `page-templates/page-contact.php`

### 9.1 Hero
H1: "Contact". Subtitle: "Neem vrijblijvend contact met ons op."

### 9.2 Two-Column Layout

**Desktop:** `display: grid; grid-template-columns: 60% 40%; gap: spacing-10 (40px);`

**Mobile:** Single column. Form first, contact info below.

### 9.3 Column 1 — Contact Form (GF-1)

**Form Container:** `white` background, padding: `spacing-8` (32px), border-radius: 8px, `shadow-sm`

**Form Fields (see §14 for full form UI specification):**
1. Naam* — text input
2. Bedrijf (optioneel) — text input
3. E-mailadres* — email input
4. Telefoonnummer (optioneel) — tel input, Dutch format
5. Onderwerp* — select dropdown
6. Bericht* — textarea, min 10 chars, max 5000. Height: 150px.
7. ☐ Privacy akkoord* — checkbox, unchecked default
8. reCAPTCHA v3 — invisible
9. [VERSTUUR BERICHT] — primary button, full-width on mobile, medium on desktop

**Below Form:** Fallback text: "Lukt het niet om het formulier te verzenden? Bel ons op 0164-652846 of mail naar info@helderduidelijkschoon.nl." `s` (14px), `gray`.

### 9.4 Column 2 — Contact Info

**Container:** `white` background, padding: `spacing-8`, border-radius: 8px, `shadow-sm`

**Elements (top to bottom):**
- Phone: `Phone` icon (24px, `primary`) + `2-xl` (24px), weight 700, `black`, `tel:0164-652846` link
- Email: `Envelope` icon (24px, `primary`) + `l` (18px), weight 400, `primary`, `mailto:info@helderduidelijkschoon.nl` link
- Address (conditional — MI-01): `MapPin` icon + text. Hidden if no address.
- KVK (conditional — MI-02): Label "KVK:" + number. `s`, `gray`. Hidden if no KVK.
- BTW (conditional — MI-03): Label "BTW:" + number. `s`, `gray`. Hidden if no BTW.
- Opening hours (conditional — MI-04): `Clock` icon + text. Hidden if no hours.
- Social icons: `FacebookLogo`, `InstagramLogo`, GBP icon. 24px, `gray` → `primary` on hover. Gap: `spacing-3`.
- Divider: `1px solid light-gray` between groups, `spacing-6` vertical margin

### 9.5 Map Section (Conditional)
- Only if MI-01 (address) provided
- Google Maps embed wrapped in Complianz consent placeholder
- Placeholder: gray box (100% × 400px) with map icon + "Klik om Google Maps te laden" text
- On consent: interactive map iframe loads
- Border-radius: 8px, margin-top: `spacing-8`

---

## 10. Offerte Aanvragen Page UI (`/offerte-aanvragen/` — P17)

**Template:** `page-templates/page-quote.php`

### 10.1 Hero
H1: "Vrijblijvende Offerte Aanvragen". Subtitle: "Wij denken graag met u mee..."

### 10.2 Layout

**Desktop:** `display: grid; grid-template-columns: 65% 35%; gap: spacing-10;`

**Mobile:** Single column. Form first, process sidebar below.

### 10.3 Form (GF-2)

**Container:** `white`, padding: `spacing-8`, border-radius: 8px, `shadow-sm`

**Fields (in order):**
1. Naam* + Bedrijf* — side-by-side on desktop, stacked on mobile
2. E-mailadres* + Telefoonnummer* — side-by-side on desktop
3. Gewenste dienst* — 7 checkboxes + "Anders" with text field. 2-column grid on desktop.
4. Type gebouw (optioneel) — select dropdown
5. Postcode / Plaats* — text input, Dutch postcode regex. Helper: "bijv. 1234 AB"
6. Beschrijving (optioneel) — textarea, 150px height
7. Gewenste planning (optioneel) — select dropdown
8. Hoe gevonden? (optioneel) — select dropdown
9. Bestand uploaden (optioneel) — file input, max 5MB, PDF/JPG/PNG/DOCX. Drag-and-drop zone styling: dashed border, `light-gray` bg, "Sleep bestand hierheen of klik om te uploaden"
10. ☐ Privacy akkoord*
11. [OFFERTE AANVRAGEN →] — accent CTA, large, full-width

### 10.4 Process Sidebar

**Styling:** `white` background, padding: `spacing-8`, border-radius: 8px, `shadow-sm`, `position: sticky; top: 88px;` (header height + offset)

**Content:**
- H3: "Wat gebeurt er na uw aanvraag?", `xl`, weight 600, `black`
- 4 steps, each: `Phone/Chat/Note/Handshake` icon (20px, `primary`) + bold step title + short description
- Step gap: `spacing-4`

---

## 11. FAQ Page UI (`/veelgestelde-vragen/` — P18)

**Template:** `page-templates/page-faq.php`

**Hero:** H1: "Veelgestelde Vragen". No subtitle.

**Intro:** `contentSize` (780px) centered. "Hier vindt u antwoorden op de meest gestelde vragen... Staat uw vraag er niet bij? Neem contact met ons op."

**FAQ Accordion:** Yoast/Rank Math FAQ Block. Styling identical to service page FAQ (§3.6). 10-15 items.

**CTA Banner:** "Staat uw vraag er niet bij? [NEEM CONTACT OP]" — secondary button style on banner background.

---

## 12. Blog UI

### 12.1 Blog Index (`/kennisbank/` — P29)

**Template:** `archive.php`

**Hero:** H1: "Kennisbank". Subtitle: "Tips, nieuws en informatie over schoonmaak, onderhoud en veiligheid"

**Category Filter:** Horizontal flex, centered. Pill-shaped buttons: `white` bg, `primary` text, border: `1px solid primary`. Active: `primary` bg, `white` text. Gap: `spacing-2`. Margin-bottom: `spacing-8`.

**Blog Grid:** Same as homepage Latest Blog Posts (§2.8). `repeat(3, 1fr)` desktop, `repeat(2, 1fr)` tablet, `1fr` mobile.

**Pagination:** Centered. Numbers in pill shapes (40×40px). Current: `primary` bg, `white` text. Others: `white` bg, `dark-gray` text, `shadow-sm`. Hover: `primary` border.

**Empty State:** Centered. "Binnenkort verschijnen hier de eerste artikelen over schoonmaak, onderhoud en veiligheid." CTA: "Neem contact op →" → `/contact/`

### 12.2 Single Blog Post (`/kennisbank/{slug}/` — P30)

**Template:** `single.php`

**Layout:** `display: grid; grid-template-columns: 1fr 300px; gap: spacing-10;` (desktop). Single column (mobile — sidebar below content).

**Main Content Area:**
- Breadcrumb: Home > Kennisbank > [Article Title]
- Featured Image: full-width (`wideSize`), aspect-ratio 16:9, `border-radius: md`, `shadow-sm`, `fetchpriority="high"`, `loading="eager"`, explicit dimensions
- H1: `5-xl` (48px desktop, 36px mobile), `black`, weight 700, line-height 1.2, margin-top: `spacing-8`
- Meta row: Date (`xs`, `gray`) + Category (pill badge, `primary` bg at 10%, `primary` text, `s`, 12px) + Reading time (`xs`, `gray`). Separator: `·` between items.
- Article content: `contentSize` (780px) max-width. Standard Block Editor rendering with:
  - H2: `4-xl` (36px), `black`, margin: `spacing-8` top, `spacing-4` bottom
  - H3: `2-xl` (24px), `black`, margin: `spacing-6` top, `spacing-3` bottom
  - Paragraphs: `m` (16px), `dark-gray`, line-height 1.7, margin-bottom: `spacing-4`
  - Images: `wideSize` (1200px), `border-radius: md`, `shadow-sm`, `loading="lazy"`, alt text required
  - Blockquote: left border `4px solid primary`, padding: `spacing-4`, `light-gray` bg, italic
- In-content CTA banner: after article content, before related posts. `light-gray` bg, H3 + CTA button. "Interesse in onze schoonmaakdiensten? [OFFERTE AANVRAGEN]"
- Related Posts: H2 "Gerelateerde Artikelen", 3 blog cards, same styling as homepage

**Sidebar (300px, right):**
- Sticky: `position: sticky; top: 88px;`
- Categories widget: H3 "Categorieën", list of category links
- Recent Posts widget: H3 "Recente Artikelen", 3-5 post links with dates
- CTA widget: `primary` bg, `white` text, padding: `spacing-6`, border-radius: 8px. "Vrijblijvende offerte?" [CTA BUTTON — secondary/outline on dark bg]

---

## 13. WooCommerce UI

### 13.1 Shop Page (`/winkel/` — P24)

**Hero:** H1: "Winkel". Intro text: 100+ words about Airfixr.

**Toolbar:**
- Background: `light-gray`, padding: `spacing-3` `spacing-4`, border-radius: 4px
- Left: result count (e.g., "14 producten"), `s`, `gray`
- Right: sort dropdown, `s`, `dark-gray`

**Product Grid:** WooCommerce default. `repeat(4, 1fr)` desktop, `repeat(2, 1fr)` tablet, `1fr` mobile. Gap: `spacing-6`.

**Product Card:**
- Background: `white`, border-radius: 8px, `shadow-sm`, overflow: hidden
- Image: 1:1 aspect ratio, `object-fit: cover`, `border-radius: 8px 8px 0 0`
- Sale badge (conditional): `accent` bg, `white` text, `xs`, weight 600, padding: 2px 8px, border-radius: 4px, position: absolute top-left `spacing-2`
- Body: padding `spacing-4`
- Title: `m` (16px), weight 600, `black`, link to product
- Price: `l` (18px), weight 700, `black`. Suffix "excl. BTW" in `xs`, `gray`.
- Add to Cart: primary button, full-width, `m` (16px), margin-top: `spacing-3`
- Out of Stock: overlay on image — `black` at 60% opacity, "Niet op voorraad" text in `white`, centered. Add to Cart button disabled (50% opacity).

**Pagination:** Same as blog pagination (§12.1).

**Empty:** "Geen producten gevonden." with link back to shop.

### 13.2 Product Page (`/product/{slug}/` — P25)

**Breadcrumb:** Home > Winkel > [Category] > [Product Name]

**Two-Column Layout (desktop):** `grid-template-columns: 1fr 1fr; gap: spacing-10;`

**Left — Gallery:**
- Main image: 1:1 aspect ratio, `border-radius: md`, `shadow-sm`, `fetchpriority="high"`
- Thumbnails: horizontal row below, 4-5 thumbs at 80×80px, `border-radius: sm`, `border: 2px solid transparent`, active: `border-color: primary`. Click → swap main image.

**Right — Details:**
- H1: Product name, `5-xl` (48px), `black`, weight 700
- Price: `3-xl` (30px), weight 700, `black`. "€795,00 excl. BTW"
- Short description: `m`, `dark-gray`, margin: `spacing-4` 0
- Quantity selector: flex with `-` / `+` buttons (44×44px, `gray` border) and number input (60px wide, centered text)
- Add to Cart: primary button, large, full-width, margin-top: `spacing-4`
- Meta: SKU, Category, Tags — `s`, `gray`, margin-top: `spacing-6`

**Mobile:** Gallery above details. Single column.

**Product Description Tabs:**
- Tab navigation: horizontal flex, `border-bottom: 2px solid light-gray`. Active tab: `border-bottom: 2px solid primary`, `primary` text.
- Beschrijving / Specificaties / Beoordelingen
- Tab content: padding `spacing-6`, `contentSize` max-width

**Related Products:** Same product grid, 3-4 products. H2: "Gerelateerde Producten".

### 13.3 Cart (`/winkelmand/` — P26)

**H1:** "Winkelmand"

**Cart Table:**
- `width: 100%`, `border-collapse: collapse`
- Header: `light-gray` bg, `s` (14px), weight 600, `black`. Padding: `spacing-3`.
- Row: `border-bottom: 1px solid light-gray`, padding: `spacing-4`
- Product cell: image (80×80px, `border-radius: sm`) + product name link
- Quantity: same selector as product page, smaller (36px height)
- Remove: `X` icon, 20px, `gray` → `error` on hover
- Subtotal: `l`, weight 600, `black`

**Cart Totals (Sidebar — right on desktop, below on mobile):**
- `white` bg, padding `spacing-6`, border-radius 8px, `shadow-sm`, 300px width
- Rows: Subtotaal, BTW (21%), Verzending, Totaal
- Totaal: `xl`, weight 700, `black`, border-top `2px solid black`, padding-top `spacing-3`
- CTA: "AFREKENEN →" — accent/orange, large, full-width, margin-top `spacing-4`
- Secondary: "← Verder winkelen" — ghost button, centered, margin-top `spacing-2`

**Empty Cart:** Centered. "Uw winkelwagen is leeg." [Bekijk winkel →] primary button.

### 13.4 Checkout (`/afrekenen/` — P27)

**H1:** "Afrekenen"

**Two-Column:** `grid-template-columns: 60% 40%` (desktop). Single column (mobile — billing above order).

**Left — Billing:**
- Standard WooCommerce checkout fields with Dutch labels
- Each field: label (`s`, weight 600, `dark-gray`), input (`m`, full-width, `gray` border, 4px radius)
- Required fields: `*` suffix in `error` color
- Field groups: `spacing-4` between fields

**Right — Order Review:**
- `white` bg, padding `spacing-6`, border-radius 8px, `shadow-sm`, sticky top 88px
- Product list with thumbnails (60px), quantities, subtotals
- Totals breakdown (subtotal, BTW, shipping, total)
- Payment method radio selection: each option with radio + payment icon + label

**Terms Checkbox:** "Ik ga akkoord met de algemene voorwaarden *" — link to `/algemene-voorwaarden/`

**Place Order Button:** "PLAATS BESTELLING" — accent/orange, large, full-width

### 13.5 Order Confirmation (`/afrekenen/order-received/`)

- Centered layout, max-width 600px
- CheckCircle icon: 64px, `success` (#388e3c), centered, margin-bottom: `spacing-4`
- H1: "Bedankt voor uw bestelling!", `4-xl`, `black`, weight 700, centered
- Body: "Uw bestelling #12345 is ontvangen. U ontvangt een bevestiging per e-mail."
- Order details: bordered box, `spacing-4` padding, `light-gray` bg. Definition list: Ordernummer, Datum, Totaal, Betaalmethode.
- "Terug naar winkel ←" — ghost button, centered

### 13.6 Mijn Account (`/mijn-account/` — Conditional)

Standard WooCommerce My Account page. Login form (if not logged in) or account dashboard (orders, addresses, account details). Styled with design system form styles.

---

## 14. Global Component UI

### 14.1 Header

**Container:** `white` background, `border-bottom: 1px solid light-gray` (#f5f5f5), `position: sticky; top: 0; z-index: 1000;`

**Inner:** `display: flex; align-items: center; justify-content: space-between;` max-width `wideSize` (1200px), padding: `spacing-3` `spacing-4`

**Logo:** SVG format. Max-height 48px desktop, 36px mobile. Link to `/`. `flex-shrink: 0`.

**Desktop Navigation:**
- `display: flex; gap: spacing-4 (16px);` items
- Links: `l` (18px), weight 600, `dark-gray` (#333333), padding: `spacing-2` `spacing-1`
- Hover: `primary` (#1a73e8) color
- Active page: `primary` color + `border-bottom: 2px solid primary`, padding-bottom adjusted
- Focus: `outline: 2px solid primary`, `outline-offset: 2px`, `border-radius: 2px`
- Dropdown indicator: `CaretDown` icon, 12px, transition `transform 200ms ease`. Open: `rotate(180deg)`.

**Desktop Dropdown (Mega Menu):**
- `position: absolute; top: 100%; background: white; box-shadow: shadow-lg; border-radius: md (8px); padding: spacing-6; z-index: 100;`
- Grid: 2 columns for Diensten (Glas & Gevel | Schoonmaakdiensten). Single column for others.
- Column header: `m` (16px), weight 600, `black`, link to category landing page, margin-bottom: `spacing-2`
- Child items: `m` (16px), weight 400, `dark-gray`, padding: `spacing-1` `spacing-2`, display: block, border-radius: 4px
- Child hover: `primary` bg at opacity 0.05, `primary` text
- Open on `:focus-within` (keyboard) or `:hover` (mouse). Close on `Escape` / click outside / mouse leave.

**Header Actions (right side):**
- Phone: `Phone` icon (18px) + "0164-652846" (`l`, weight 600, `primary`). `tel:` link.
- Email: `Envelope` icon (18px, `dark-gray`). `mailto:` link. Icon only on tablet, hidden on mobile.
- Cart: `ShoppingCart` icon (20px) + count badge. Badge: `accent` bg, `white` text, `xs`, border-radius pill, min-width 18px, padding 2px 4px. Hidden if WooCommerce disabled.

**Mobile Header (<768px):**
- Logo smaller (36px height)
- Phone: icon only (no number). Tap-to-call.
- Cart: icon + badge
- Hamburger: `List` icon, 24px, `dark-gray`. `aria-label="Menu openen"`, `aria-expanded="false"`. Toggles full-screen overlay.

**Mobile Menu Overlay:**
- `position: fixed; inset: 0; background: white; z-index: 1100;`
- Close button: `X` icon, 44×44px, top-right, `spacing-4` margin
- Nav items: full-width, `l` (18px), weight 600, `dark-gray`, padding: `spacing-4`, border-bottom: `1px solid light-gray`
- Dropdown toggle: `CaretDown` icon, right-aligned, rotates on open
- Child items: indented `spacing-6`, `m` (16px), weight 400, border-left: `2px solid light-gray`
- Active: `primary` color
- Footer section: phone (full), email, social icons at bottom
- Animation: slide in from right, `transform: translateX(100%)` → `translateX(0)`, 250ms ease
- `Escape` closes. Focus returns to hamburger button.

### 14.2 Footer

**Background:** `black` (#1a1a1a)
**Text:** `white` for headers, `light-gray` (#f5f5f5) at opacity 0.8 for links
**Padding:** `spacing-16` (64px) top, `spacing-8` (32px) bottom

**Grid:** `repeat(5, 1fr)` desktop, `repeat(3, 1fr)` (row 1) + `repeat(2, 1fr)` (row 2) tablet, `repeat(2, 1fr)` mobile. Gap: `spacing-8`.

**Column Header:** `l` (18px), weight 600, `white`, margin-bottom: `spacing-4`

**Column Links:** `m` (16px), weight 400, `light-gray`, display: block, padding: `spacing-1` 0, text-decoration: none. Hover: `white`, underline.

**Contact Column:**
- Phone: icon + number, `m`, `white`, weight 600
- Email: icon + email, `m`, `primary`
- Address: conditional (MI-01), `s`, `light-gray`
- KVK/BTW: conditional, `s`, `light-gray`

**Social Icons:** 20px, `light-gray`, opacity 0.7. Hover: `white`, opacity 1. Gap: `spacing-3`. Below columns, centered or left.

**Copyright:** `xs` (12px), `gray` (#757575), centered, margin-top: `spacing-8`. "© 2026 HDS Onderhoudsdiensten"

**Cookie Settings:** Complianz floating button. `position: fixed; bottom: spacing-4; left: spacing-4; z-index: 900;`. `s`, `primary` bg, `white` text, border-radius pill, padding: 8px 16px, `shadow-md`. "Cookie-instellingen"

### 14.3 Breadcrumbs

**Position:** Below header, above `<main>`. On all inner pages. Not on Homepage.
**Background:** `white`, padding: `spacing-2` `spacing-4`, border-bottom: `1px solid light-gray` (subtle, optional)
**Typography:** `s` (14px), weight 400. Current page: weight 600, `dark-gray`, not linked. Parent items: `primary` (#1a73e8), underlined link. Separator: `>` character, `gray`, `spacing-2` margin.
**Mobile:** Truncated to last 2 items if >3 levels deep (e.g., "... > Product Name").

### 14.4 Sidebar

**Usage:** Blog posts (P30), Blog index (P29 — optional), WooCommerce product category page
**Width:** 300px (desktop). Full-width below content (mobile).
**Position:** `sticky; top: 88px;` (header height + offset) on desktop.
**Widget Styling:**
- Each widget: `white` bg, padding `spacing-6`, border-radius 8px, `shadow-sm`, margin-bottom: `spacing-6`
- Widget title: H3 equivalent, `xl` (20px), weight 600, `black`, margin-bottom: `spacing-3`
- List items: `m`, `dark-gray`, padding: `spacing-1` 0
- CTA widget: `primary` bg, `white` text, CTA button in white/outline style

### 14.5 Search

**Search Form (header/footer):**
- Input: `m`, full-width, `gray` border, 4px border-radius (right side: 0 radius). Padding: 8px 12px. Min-height 44px.
- Button: `primary` bg, `white` magnifying glass icon, 44×44px, border-radius: 0 4px 4px 0

**Search Results Page:**
- H1: "Zoekresultaten voor '[query]'"
- Results list: each result with title (H3, linked, `primary`), URL breadcrumb (`s`, `gray`), excerpt (`m`, `dark-gray`)
- Result gap: `spacing-6`, `padding-bottom: spacing-6`, `border-bottom: 1px solid light-gray`
- Pagination: same as blog pagination
- Empty: "Geen resultaten gevonden voor '[query]'." + suggestions

### 14.6 Cookie Consent Banner (Complianz)

**Position:** Bottom bar or center modal (Complianz default — center modal recommended for clarity)
**Background:** `white`, `shadow-lg`, border-radius: 8px, max-width: 600px, padding: `spacing-6`
**Title:** H3, "Deze website gebruikt cookies", `xl`, weight 600, `black`
**Body:** `m`, `dark-gray`, explanation text
**Buttons (horizontal flex, right-aligned):**
- "Weigeren" — ghost button, `dark-gray`
- "Instellingen aanpassen" — secondary button
- "Accepteren" — primary button
**Settings Modal:** Per-category toggles (functional/statistics/marketing). Functional always on. Statistics + marketing off by default.

### 14.7 Notifications / Toasts

**Position:** `fixed; top: spacing-4; right: spacing-4; z-index: 1200;`
**Success:** `white` bg, left border `4px solid success`, `CheckCircle` icon, message text
**Error:** `white` bg, left border `4px solid error`, `WarningCircle` icon, message text

### 14.8 Pagination

**Layout:** Centered horizontal flex. Gap: `spacing-2`.
**Page Numbers:** 40×40px, border-radius: 4px, `m`, weight 600. Default: `white` bg, `dark-gray` text, `shadow-sm`. Hover: `primary` border. Current: `primary` bg, `white` text. Disabled (← →): `gray` color, `opacity: 0.5`, `cursor: not-allowed`.

---

## 15. Form UI Specification (All Forms)

All Gravity Forms (GF-1, GF-2, GF-3) use identical styling. This specification overrides Gravity Forms defaults.

### 15.1 Form Container

- Max-width: `contentSize` (780px) for full-width forms. 100% for two-column layouts.
- Background: `white` (unless in a card — then card background)
- Padding: `spacing-8` (32px) when in a card

### 15.2 Labels

- `s` (14px), weight 600, `dark-gray` (#333333)
- `display: block`, margin-bottom: `spacing-1` (4px)
- Required marker: `*` or "(vereist)" in `error` (#d32f2f), `aria-hidden="true"` on asterisk
- Clicking label focuses corresponding input

### 15.3 Text Inputs (text, email, tel, url, number, password)

- Height: 44px (min-height for touch). Padding: 10px 12px.
- Font: `m` (16px), weight 400, `black` (#1a1a1a)
- Background: `white` (#ffffff)
- Border: `1.5px solid gray` (#757575)
- Border-radius: `4px` (`--hds-radius-sm`)
- Width: 100%
- Transition: border-color 150ms ease, box-shadow 150ms ease
- Hover: border-color `dark-gray` (#333333)
- Focus: border-color `primary` (#1a73e8), `box-shadow: 0 0 0 3px rgba(26,115,232,0.15)`, outline: none
- Error: border-color `error` (#d32f2f), `box-shadow: 0 0 0 3px rgba(211,47,47,0.15)`
- Success: border-color `success` (#388e3c)
- Disabled: `light-gray` (#f5f5f5) background, opacity 0.5, cursor not-allowed
- Placeholder: `gray` (#757575), italic

### 15.4 Textarea

- Same as text inputs, plus: min-height 120px, resize vertical only, line-height 1.5

### 15.5 Select Dropdown

- Same border/radius/focus/error as text inputs
- Custom chevron: `CaretDown` icon (14px, `gray`) as `background-image` or `::after` pseudo-element, right 12px
- Native `<select>` element with `appearance: none; padding-right: 36px;` for icon space

### 15.6 Checkbox

- Custom styled: `appearance: none; width: 20px; height: 20px; min-width: 20px; background: white; border: 1.5px solid gray; border-radius: 3px; cursor: pointer;`
- Checked: `background: primary; border-color: primary;` + white checkmark (`::after` pseudo-element, `content: "✓"; color: white; font-size: 14px; font-weight: 700; position: absolute; center`)
- Hover: `border-color: primary;`
- Focus: `outline: 2px solid primary; outline-offset: 2px;`
- Error: `border-color: error;`
- Disabled: `opacity: 0.5; cursor: not-allowed;`
- Label alongside: `display: inline-flex; align-items: center; gap: spacing-2;`, `cursor: pointer;`

### 15.7 Radio

- Same as checkbox but `border-radius: 50%`
- Checked indicator: filled circle (`::after`, `width: 10px; height: 10px; border-radius: 50%; background: primary;`)

### 15.8 File Upload

- Container: dashed border `1.5px solid gray`, `light-gray` background, border-radius: 8px, padding: `spacing-8` (32px), text-align: center
- Icon: `UploadSimple` (32px, `gray`), centered
- Text: "Sleep bestand hierheen of klik om te uploaden", `m`, `dark-gray`
- Meta: "Max 5 MB. PDF, JPG, PNG, DOCX.", `xs`, `gray`
- Drag-over state: border-color `primary`, background `primary` at 5% opacity

### 15.9 Error Messages

- Container: margin-top: `spacing-1`
- Icon + Text: `WarningCircle` (14px, `error`) + error text (`s`, weight 600, `error`)
- Linked to input via `aria-describedby="[field-id]-error"`
- Global error summary (top of form, if server-side errors): `error` alert box with list of errors

### 15.10 Success Message (Post-Submit)

- Form replaced with success message (or redirect — Gravity Forms configured for redirect to `/bedankt/`)
- Success: `CheckCircle` (32px, `success`), message text, auto-dismiss after 5 seconds (if inline)

### 15.11 Submit Buttons

- Primary style by default (Contact form). CTA style for Quote form.
- Full-width on mobile. Auto-width on desktop.
- Min-height: 44px.
- Loading state: text changes (e.g., "Versturen..."), `Spinner` icon with rotation animation, `pointer-events: none`, `opacity: 0.8`
- After success: button text changes to "Verzonden!" with `CheckCircle` icon (if inline success)

---

## 16. Empty State UI

### 16.1 No Testimonials / No Logos

**Section:** `display: none` on wrapper (ADR D-015). No visual element rendered. Zero space consumed.

### 16.2 No Blog Posts (Blog Index)

**Centered block** within `contentSize` container (780px), padding `spacing-16`:
- Icon: `NotePencil` (48px, `light-gray`)
- H2: "Binnenkort verschijnen hier de eerste artikelen", `3-xl`, `black`, weight 600
- Body: "over schoonmaak, onderhoud en veiligheid.", `m`, `dark-gray`
- CTA: "Neem contact op →", ghost button

### 16.3 No Vacancies

**Centered block**, padding `spacing-16`:
- H2: "Er zijn op dit moment geen openstaande vacatures.", `2-xl`, `black`
- Body: "Stuur een open sollicitatie naar info@helderduidelijkschoon.nl", `m`, `dark-gray`
- CTA: "Open sollicitatie →" — mailto link or scrolls to GF-3 form

### 16.4 Empty Search Results

- H1: "Zoekresultaten voor '[query]'"
- Alert (info style): "Geen resultaten gevonden."
- Suggestions list: "Controleer de spelling", "Gebruik een algemenere zoekterm", etc.
- CTA: "Neem contact op" → `/contact/`

### 16.5 404 Page

**Centered content**, max-width 600px, padding `spacing-20` vertical:
- H1: "Pagina niet gevonden", `5-xl`, `black`
- Body: "De pagina die u zoekt bestaat niet of is verplaatst.", `m`, `dark-gray`
- Search bar: prominent, 100% width, `shadow-sm`
- Link list: Home, Onze Diensten, Contact, Veelgestelde Vragen — each `m`, `primary`, underline
- Contact fallback: phone + email

### 16.6 500 Error

**Server-level static HTML**. Not WordPress-dependent.
- Minimal styling: system font stack, black text on white, centered
- H1: "Technische Storing"
- Body: "Onze excuses... Probeer het over een paar minuten opnieuw."
- Phone + email displayed prominently

### 16.7 Empty Cart

- H1: "Uw winkelwagen is leeg"
- Body: "Bekijk onze producten en voeg ze toe aan uw winkelwagen."
- CTA: "Bekijk winkel →" — primary button
- Below: 3-4 featured product cards (optional)

---

## 17. Interactive State Matrix

Every interactive element must implement all applicable states from this matrix.

| Element | Default | Hover | Focus | Active/Pressed | Disabled | Loading | Error |
|---|---|---|---|---|---|---|---|
| **Primary Button** | `primary` bg, `white` text, 8px radius, `shadow-sm` | `primary-dark` bg, `shadow-md`, translateY(-1px) | `outline: 2px solid primary`, `outline-offset: 2px` | `primary-dark` bg, scale(0.98) | opacity 0.5, cursor not-allowed | Spinner + "Versturen...", pointer-events none | — |
| **Secondary Button** | transparent bg, `primary` border + text | `primary` bg at 8%, `primary-dark` border | Same as primary | Same as primary | Same as primary | Same | — |
| **CTA Button** | `accent` bg (#ea8600), `white` text, 18px bold | `#d67900` bg, `shadow-md` | `outline: 2px solid accent`, `outline-offset: 2px` | Darker accent, scale(0.98) | opacity 0.5 | Spinner + larger | — |
| **Ghost Button** | transparent, `primary` text | `primary` bg at 5% | Same as primary | `primary` bg at 10% | opacity 0.5 | Spinner | — |
| **Link** | `primary` (#1a73e8), underline | `primary-dark`, underline | `outline: 2px solid primary`, `outline-offset: 2px` | `primary-dark` | — | — | — |
| **Card** | `white` bg, 8px radius, `shadow-sm` | translateY(-2px), `shadow-md`, optional border `primary` | `outline: 2px solid primary`, `outline-offset: 2px` | translateY(0), `shadow-sm` | — | — | — |
| **Text Input** | `white` bg, `gray` border 1.5px, 4px radius | `dark-gray` border | `primary` border + `box-shadow: 0 0 0 3px primary` at 15% | `primary` border | `light-gray` bg, opacity 0.5 | — | `error` border + `box-shadow: 0 0 0 3px error` at 15% |
| **Checkbox/Radio** | `white` bg, `gray` border 1.5px | `primary` border | `outline: 2px solid primary`, `outline-offset: 2px` | Checked state | opacity 0.5 | — | `error` border |
| **Nav Link** | `dark-gray` (#333333) | `primary` (#1a73e8) | `outline: 2px solid primary`, `outline-offset: 2px` | `primary-dark` | — | — | — |
| **Accordion Item** | Collapsed, `CaretDown` 0° | Header color → `primary` | `outline` on header button | Expanded, `CaretDown` 180° | — | — | — |

### 17.1 Focus Ring Standard

All interactive elements use the same focus ring:
```css
:focus-visible {
    outline: 2px solid var(--wp--preset--color--primary);
    outline-offset: 2px;
}
```
Exception: elements on dark backgrounds (hero, footer) use `outline-color: white`.

### 17.2 Reduced Motion

When `prefers-reduced-motion: reduce`:
- All transitions and animations: `duration: 0.01ms`
- Card hover lift: removed
- Accordion: instant open/close
- Loading spinner: static (no rotation)
- Carousel auto-scroll: disabled

---

## 18. Responsive Behaviour — Per Breakpoint

### 18.1 Mobile (0–767px)

| Component | Mobile Behavior |
|---|---|
| Container | `padding-inline: spacing-4` (16px) |
| Typography | H1: 36px. H2: 30px. H3: 20px. Body: 16px. |
| Header | Logo 36px. Phone icon only. Hamburger menu. |
| Navigation | Full-screen overlay (see §14.1) |
| Hero | Min-height 50vh. CTA full-width. |
| Grids | All 1 column. Cards full-width. |
| Service Card Grid | 1 column |
| USP Grid | 1 column |
| Testimonials | 1 column |
| Blog Grid / Product Grid | 1 column |
| Footer | 1-2 columns |
| Two-Column Layouts (Contact, Offerte, Blog) | Single column |
| Tables | Horizontal scroll wrapper |
| CTA Banner | Stacked (text centered, button full-width below) |
| Sticky CTA | `position: fixed; bottom: 0; width: 100%; z-index: 900;` |

### 18.2 Tablet (768–1023px)

| Component | Tablet Behavior |
|---|---|
| Typography | H1: 48px. H2: 36px. H3: 24px. |
| Header | Desktop header (logo + full nav + phone number). No hamburger. |
| Grids | 2 columns where specified |
| Service Card Grid | 2 columns |
| USP Grid | 2 columns |
| Testimonials | 2 columns (if ≥2) |
| Blog Grid | 2 columns |
| Product Grid | 2 columns |
| Footer | 3 columns + 2 columns |
| Two-Column Layouts | As desktop (side-by-side) |
| CTA Banner | Side-by-side (text left, button right) |

### 18.3 Desktop (1024–1279px)

| Component | Desktop Behavior |
|---|---|
| Grids | Full multi-column |
| Service Card Grid | 3 columns (3+3+1 centered) |
| USP Grid | 3 columns (3+3) |
| Testimonials | 3 columns (if ≥3) |
| Blog Grid | 3 columns |
| Product Grid | 4 columns |
| Footer | 5 columns |
| Hover states | All interactive hover effects active |
| Mega menu dropdowns | Hover + focus-within |
| Sidebar | Sticky, 300px |

### 18.4 Wide (1280px+)

| Component | Wide Behavior |
|---|---|
| Container | Content clamped to `wideSize` (1200px). Full-width sections span viewport. |
| Hero | Background spans full viewport. Content centered at 1200px. |
| Typography | Maximum sizes from scale. No further enlargement. |
| Margin | Auto margins center content within viewport. |

---

## 19. Accessibility — Implementation Checklist

### 19.1 Color Contrast

| Combination | Ratio | AA Pass | Notes |
|---|---|---|---|
| `black` (#1a1a1a) on `white` | 15.4:1 | ✅ AAA | Body text, headings |
| `dark-gray` (#333333) on `white` | 7.4:1 | ✅ AAA | Secondary text |
| `white` on `primary` (#1a73e8) | 4.6:1 | ✅ AA | Hero text, primary buttons |
| `white` on `accent` (#ea8600) | 3.8:1 | ✅ AA (large only) | CTA button — 18px bold minimum |
| `primary` (#1a73e8) on `white` | 4.6:1 | ✅ AA | Links |
| `gray` (#757575) on `white` | 4.1:1 | ✅ AA | Helper text — 14px minimum |
| `white` on `error` (#d32f2f) | 5.3:1 | ✅ AA | Error buttons |
| `light-gray` (#f5f5f5) on `white` | 1.1:1 | ❌ — Not used for text | Background only, never text |
| `gray` on `light-gray` | 2.8:1 | ❌ — NEVER USE | Insufficient contrast — prohibited |

### 19.2 Keyboard Navigation

**Tab order (every page):**
1. Skip-to-content link (visible on `:focus`)
2. Logo (Home link)
3. Primary navigation items (L→R)
4. Phone link
5. Cart icon
6. `<main id="content">`
7. Page content (DOM order)
8. Footer links (columns L→R)
9. Social icons
10. Cookie settings button

**Focus must always be visible.** No `outline: none` without replacement.

### 19.3 Screen Reader

- H1 on every page announces the page purpose
- All images: `alt` text in Dutch
- Form errors: `aria-describedby` links input to error message
- Dynamic updates: `aria-live="polite"` for cart, search results. `aria-live="assertive"` for critical errors.
- Navigation: `aria-label="Hoofdmenu"` on `<nav>`. `aria-expanded` on dropdown toggles.
- Skip link: "Ga naar de inhoud" → `#content`

### 19.4 Touch Targets

Minimum 44×44px for all interactive elements: buttons, nav links, form controls, icons, pagination, social links.

---

## 20. SEO-Visible Elements

### 20.1 Per-Page Metadata

| Element | Specification |
|---|---|
| `<title>` | `[Page Title] — HDS Onderhoudsdiensten`. 50-60 chars. Unique per page. |
| `<meta description>` | 150-160 chars. Keyword + location + value proposition + CTA. Unique per page. |
| `<link rel="canonical">` | Self-referencing. Includes trailing slash. |
| `og:title` | Same as `<title>` |
| `og:description` | Same as `<meta description>` |
| `og:image` | 1200×630px branded social share image |
| `og:url` | Canonical URL |
| `og:type` | `website` (pages), `article` (blog posts), `product` (products) |
| `twitter:card` | `summary_large_image` |

### 20.2 Heading Hierarchy

- H1: One per page. Primary keyword.
- H2: Section headings. Secondary keywords.
- H3: Sub-section headings. Long-tail keywords.
- Never skip a level (H1 → H3 without H2 is forbidden).

### 20.3 Images

- All content images: descriptive Dutch `alt` text
- Decorative images: `alt=""` (empty). Screen readers skip.
- Filenames: `[subject]-[context]-[location].webp`
- LCP image: `fetchpriority="high"`, no `loading="lazy"`, explicit `width`/`height`
- Below-fold images: `loading="lazy"`

### 20.4 Schema Markers (Visual Location)

| Schema Type | Page | Visual Location |
|---|---|---|
| `LocalBusiness` | Home, Contact, Over HDS | Footer + Contact info block (NAP source) |
| `Service` | P02-P08 | Hero section + service content |
| `FAQPage` | P18 | FAQ accordion items |
| `BreadcrumbList` | All inner pages | Breadcrumb navigation bar |
| `Product` | P25 (x14) | Product details section |
| `JobPosting` | P14 (per vacancy) | Each vacancy card |
| `Organization` | All pages | Footer (sameAs links to social/GBP) |

---

## 21. Component Mapping — Screen to Design System

| Screen/Component | DS-001 Reference | Implementation |
|---|---|---|
| Homepage Hero | DS §9.1 | `front-page.php` + Block Editor |
| Service Page Hero | DS §9.1 | `page-templates/page-service.php` + custom fields |
| Category Landing Hero | DS §9.1 | `page-templates/page-category-landing.php` |
| Service Cards | DS §8.2 | `hds/service-card` custom block |
| USP Grid | DS §9.5 (Grid) | Block Pattern `hds/usp-grid` |
| Client Logo Carousel | DS §8.1 (Cards, conditional) | Block Pattern |
| Testimonial Block | DS §8.3 | `hds/testimonial` custom block |
| CTA Banner | DS §8.4 | Block Pattern `hds/cta-banner` + `is-style-banner` |
| Service Area + Map | DS §9.7, §6 (Conditional) | Block Pattern `hds/contact-info-map` |
| Blog Cards | DS §8.9 | Block Pattern `hds/latest-blog-posts` |
| FAQ Accordion | DS §8.5 | Yoast/Rank Math FAQ Block on `page-templates/page-faq.php` |
| Contact Form (GF-1) | DS §6 | Gravity Forms shortcode in `page-templates/page-contact.php` |
| Quote Form (GF-2) | DS §6 | Gravity Forms shortcode in `page-templates/page-quote.php` |
| Vacancy Application (GF-3) | DS §6 | Gravity Forms shortcode on P14 |
| Vacancy Cards | DS §8.1 (conditional) | `hds/job-listing` custom block |
| Download Cards | DS §8.8 | Block Pattern `hds/download-card-list` |
| Content + Image | DS §9.2 (Section) | Block Pattern `hds/content-with-image` |
| Service Icon List | DS §3.3 (Lists) | Block Pattern `hds/service-icon-list` |
| Cross-Sell Services | DS §8.2 | Block Pattern using `hds/service-card` |
| Product Cards | DS §8.10 | WooCommerce plugin template |
| Product Grid | DS §8.10 | WooCommerce plugin template |
| 404 Content | DS §9.9 | Block Pattern + `404.php` |
| Bedankt Page | DS §9.2 | `page.php` + PHP logic |
| Header | DS §7.1 | `parts/header.php` |
| Footer | DS §7.5 | `parts/footer.php` |
| Breadcrumbs | DS §7.4 | `parts/breadcrumbs.php` + Rank Math Pro |
| Cookie Banner | DS §8.6 | Complianz Premium plugin |
| Search | DS §9.10 | `search.php` + Relevanssi |
| Pagination | DS §7.6 | `the_posts_pagination()` |
| All Buttons | DS §5 | `theme.json` button styles + CSS |
| All Forms | DS §6 | `main.css` form styles |
| All Typography | DS §3 | `theme.json` + `main.css` |

---

## 22. Developer Notes

### 22.1 CSS Architecture

**File: `assets/css/main.css`** — single production stylesheet with modular sections:
1. Reset (box-sizing, margin/padding reset)
2. Accessibility (.screen-reader-text, .skip-link, :focus-visible)
3. Custom Properties overrides (--hds-* design tokens)
4. Layout (.container, .site-main, grid utilities)
5. Typography (headings, paragraphs, lists, links — consuming theme.json tokens)
6. Header & Navigation
7. Footer
8. Components (cards, buttons, forms, accordion, alerts, tables, pagination)
9. Block Styles (is-style-* variations)
10. WooCommerce overrides (if needed)
11. Responsive (min-width media queries at 768, 1024, 1280)
12. Print stylesheet
13. Reduced motion

**CSS Variables:** All design tokens consumed via `var(--wp--preset--color--primary)` etc. No hardcoded hex values outside `theme.json`.

### 22.2 Block Editor Integration

- `theme.json` `settings.color.palette` restricts the Block Editor color picker to the 11 design tokens
- `theme.json` `settings.typography.fontSizes` controls the font size dropdown
- `theme.json` `settings.spacing.spacingSizes` controls spacing presets
- Block patterns registered in `inc/patterns.php` provide pre-built layouts
- Custom blocks in `inc/blocks.php` with JS editor scripts in `assets/js/blocks/`
- `editor.css` mirrors frontend styles in the Block Editor for WYSIWYG editing

### 22.3 Performance Implementation

- Critical CSS: FlyingPress auto-generates and inlines in `<head>`. Manual review for hero sections.
- Fonts: Self-hosted Open Sans (WOFF2), `font-display: swap`, preloaded in `<head>`. Subset: Latin + Dutch diacritics.
- Images: WebP via `<picture>` (ShortPixel/Imagify). `srcset` for responsive. `loading="lazy"` below fold. `fetchpriority="high"` on LCP.
- JavaScript: `defer` attribute. No render-blocking JS. Vanilla JS — no jQuery unless WooCommerce requires.
- No jQuery Migrate under any circumstances.

### 22.4 Gravity Forms Styling Override

Gravity Forms outputs its own CSS classes. The theme's `main.css` overrides:
- `.gform_body` — form container
- `.gform_fields` — field grid
- `.gfield_label` — labels
- `.ginput_container input`, `.ginput_container textarea`, `.ginput_container select` — inputs
- `.gfield_error` — error state
- `.validation_message` — error text
- `.gform_button` — submit button

Use Gravity Forms "No CSS" output mode for minimal conflict.

---

## 23. Acceptance Criteria

### 23.1 Visual Fidelity

| # | Criterion | Pass Condition |
|---|---|---|
| AC-HF01 | All colors match Design Token palette | Color audit: zero hardcoded hex values in production CSS outside `theme.json` |
| AC-HF02 | All typography uses Design Token scale | Font audit: only 9 font-size tokens used. No inline `font-size` overrides. |
| AC-HF03 | All spacing uses Design Token scale | Spacing audit: only spacing tokens (0–24) used for margin/padding/gap. |
| AC-HF04 | All shadows use Design Token presets | Shadow audit: only `sm`, `md`, `lg` presets used. |
| AC-HF05 | Brand consistency across all 32 pages | Visual QA: same component = identical appearance on every page. |

### 23.2 Interactive States

| # | Criterion | Pass Condition |
|---|---|---|
| AC-HF06 | All interactive elements have visible hover state | Mouse-over audit on every interactive element |
| AC-HF07 | All interactive elements have visible focus state | Keyboard tab-through: focus ring visible on every element |
| AC-HF08 | All forms display validation errors correctly | Submit invalid form: error border + error message on each invalid field |
| AC-HF09 | All forms display loading state during submission | Submit valid form: button shows spinner + text change |
| AC-HF10 | All buttons have disabled state | Disabled buttons: 50% opacity, not-allowed cursor, no hover effects |
| AC-HF11 | All cards have hover lift + shadow | Hover over any card: 2px lift + shadow-md |

### 23.3 Responsive

| # | Criterion | Pass Condition |
|---|---|---|
| AC-HF12 | Mobile (375px): single column, no horizontal scroll | Resize to 375px: overflow-x hidden, all content vertically stacked |
| AC-HF13 | Tablet (768px): 2-column grids where specified | Resize to 768px: grids switch to 2 columns |
| AC-HF14 | Desktop (1024px): full multi-column layouts | Resize to 1024px: grids at maximum column count |
| AC-HF15 | Header transforms: desktop nav, mobile hamburger | 1024px+: horizontal nav. 767px-: hamburger overlay. |
| AC-HF16 | CTA buttons full-width on mobile | 375px: all CTA buttons 100% width |

### 23.4 Accessibility

| # | Criterion | Pass Condition |
|---|---|---|
| AC-HF17 | Color contrast: all text/background combinations pass AA | WebAIM or axe check: zero contrast violations |
| AC-HF18 | All images have alt text | Screaming Frog: zero missing alt text (except decorative alt="") |
| AC-HF19 | Heading hierarchy: no skipped levels | Screaming Frog: H1→H2→H3 never skipping a level |
| AC-HF20 | Skip-to-content link present and functional | Tab on page load: skip link visible, Enter → focus moves to main |
| AC-HF21 | `lang="nl-NL"` on `<html>` element | View source: `<html lang="nl-NL">` |
| AC-HF22 | Reduced motion respected | Enable OS setting: all animations instant |

### 23.5 SEO

| # | Criterion | Pass Condition |
|---|---|---|
| AC-HF23 | Every page has unique `<title>` and `<meta description>` | Screaming Frog: zero duplicate, zero empty |
| AC-HF24 | Self-referencing canonical on every page | Screaming Frog: zero "canonical points to different URL" |
| AC-HF25 | Open Graph + Twitter Card tags present on all pages | View source: og:title, og:description, og:image, og:url, og:type, twitter:card on every page |
| AC-HF26 | All schema validates | Google Rich Results Test: zero errors on all 9 schema types |

### 23.6 Implementation Completeness

| # | Criterion | Pass Condition |
|---|---|---|
| AC-HF27 | Every page wireframe section implemented with precise visual values | Side-by-side comparison: wireframe → implementation matches |
| AC-HF28 | Every Design System component used in at least one page | Component audit: all 40+ DS components appear in at least one page template |
| AC-HF29 | Zero `style=""` attributes in production PHP templates | Code review: all styles in CSS. Inline styles only for dynamic values (background-image, etc.). |
| AC-HF30 | A frontend developer can implement any page from this specification alone | Developer test: implement a random page (e.g., VVE Service) from HFUI-001 only |

---

*End of High-Fidelity UI Specification — HFUI-001 v1.0.0*

*This specification is complete. Frontend development may proceed with HFUI-001 as the definitive visual reference. All values are exact. All states are defined. All 32 pages are covered.*
