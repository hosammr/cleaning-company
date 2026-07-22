# HDS Onderhoudsdiensten — UX Wireframes Specification

**Document ID:** UXW-001 | **Version:** 1.0.0 | **Status:** Approved for UI Design
**Project:** helderduidelijkschoon.nl — Ground-Up Rebuild
**Language:** Nederlands (nl-NL) | **Date:** July 2026

**Prerequisite Documents:**
FS-001 (Functional Specification), DS-001 (Design System), MPS-001 (Master Project Specification), SEO-001 (SEO Implementation), RTM-001 (Requirements Traceability Matrix), SRC-08 (User Journeys)

**Role:** This document describes the structural layout of every page. It defines WHAT goes WHERE before visual design begins. A UI designer should be able to produce high-fidelity mockups from this specification without additional clarification.

---

## 1. Wireframe Principles

### 1.1 Mobile-First

Every wireframe is described from the smallest viewport (375px). Tablet and desktop layouts are additive — they add columns, increase spacing, and reveal secondary elements. The mobile layout must be fully functional as a standalone experience.

| Viewport | Width | Layout Strategy |
|---|---|---|
| **Mobile** | 375px | Single column. Stacked sections. Full-width CTAs. Hamburger nav. |
| **Tablet** | 768px | 2-column grids where beneficial. Side-by-side form layout. |
| **Desktop** | 1024px+ | 3-4 column grids. Multi-column footer. Full dropdown nav. |

### 1.2 Progressive Disclosure

Show the most important information first. Details are revealed on demand.

| Layer | Content | Trigger |
|---|---|---|
| **Primary** | H1, hero subtitle, primary CTA, service cards | Always visible above the fold |
| **Secondary** | Service details, USPs, testimonials | Visible on scroll |
| **Tertiary** | FAQ answers, vacancy details, related services | Revealed via accordion, toggle, or "Lees meer" |

### 1.3 Accessibility (Structural)

The wireframe enforces structural accessibility — independent of visual design.

| Rule | Wireframe Implementation |
|---|---|
| Skip-to-content link | First focusable element on every page |
| Heading hierarchy | H1 → H2 → H3. Never skip a level. |
| Landmark regions | `<header>`, `<nav>`, `<main>`, `<footer>`, `<section>` with `aria-label` |
| Form labels | Every input has a visible label. Required fields marked. |
| Touch targets | Minimum 44×44px for all interactive elements |
| Focus order | Logical DOM order matches visual order |
| Screen reader context | Every section has a descriptive heading. No "click here" links. |

### 1.4 Conversion-Focused Layout

Every page serves a conversion goal. The layout guides the user toward that goal.

| Page Type | Conversion Goal | Primary CTA Position |
|---|---|---|
| Homepage | Offerte aanvragen | Hero (above fold) + bottom CTA banner |
| Service Pages | Offerte aanvragen | Hero + bottom of content + sticky mobile CTA |
| Category Landings | Navigate to specific service | Service card grid |
| About Pages | Offerte aanvragen (secondary: build trust) | Bottom CTA banner |
| Contact | Submit form | Form (left, dominant) |
| Offerte | Submit detailed request | Form (full-width) |
| Blog | Read article → Offerte | In-content CTA + sidebar CTA |

### 1.5 Content Hierarchy (Z-Pattern / F-Pattern)

Key pages follow an F-pattern reading layout:

```
[LOGO]       NAVIGATION        [PHONE]
─────────────────────────────────────
[H1 HERO HEADING — PRIMARY MESSAGE ]
[Subtitle + CTA BUTTON              ]
─────────────────────────────────────
[Service Card 1] [Service Card 2] [Card 3]
[Service Card 4] [Service Card 5] [Card 6]
─────────────────────────────────────
[CTA BANNER                          ]
─────────────────────────────────────
[FOOTER — 5 COLUMNS                 ]
```

The eye flows: Brand → Message → Options → Action → Information.

---

## 2. Global Layout

Every page shares the following structural skeleton.

### 2.1 Global Wireframe Skeleton

```
┌──────────────────────────────────────────────────────────────┐
│ SKIP-TO-CONTENT LINK (visible on focus)                      │
├──────────────────────────────────────────────────────────────┤
│ COOKIE CONSENT BANNER (Complianz — first visit only)         │
├──────────────────────────────────────────────────────────────┤
│ HEADER                                                        │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ [Logo]    DIENSTEN v  OVER HDS v  LUCHT v  CONTACT  ☏ ✉🛒│ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ BREADCRUMB (inner pages only)                                 │
│ Home > Pagina Naam                                            │
├──────────────────────────────────────────────────────────────┤
│ MAIN CONTENT AREA                                             │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │                                                          │ │
│ │  [PAGE-SPECIFIC CONTENT — see per-page wireframes]       │ │
│ │                                                          │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ CTA BANNER (most pages)                                       │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │  H2: Vrijblijvende offerte?    [PRIMARY CTA BUTTON]      │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ FOOTER                                                        │
│ ┌──────────┬──────────┬──────────────┬──────────┬──────────┐ │
│ │ DIENSTEN │ OVER HDS │ CONTACT      │ LUCHT    │JURIDISCH │ │
│ │ 7 links  │ 5 links  │ ☏ 0164-...   │ 3 links  │ 4 links  │ │
│ │          │          │ ✉ info@...   │          │          │ │
│ │          │          │ KVK / BTW    │          │          │ │
│ └──────────┴──────────┴──────────────┴──────────┴──────────┘ │
│ [Facebook] [Instagram] [GBP]     © 2026 HDS Onderhoudsdiensten│
│ [Cookie-instellingen]                                          │
└──────────────────────────────────────────────────────────────┘
```

### 2.2 Header

| Element | Position | Visibility | Mobile |
|---|---|---|---|
| **Skip-to-content link** | Top of page, outside visual flow | Visible on `:focus` | Same |
| **Cookie Banner** | Bottom or top overlay (Complianz) | First visit only | Full-width bottom bar |
| **Logo (SVG)** | Left | Always | Smaller (max-height 36px) |
| **Primary Navigation** | Right of logo | Desktop: horizontal. Mobile: hidden. | Hamburger toggle → overlay |
| **Phone** | Right of nav | Icon + number (desktop). Icon only (mobile) | Tap-to-call |
| **Email** | Right of phone | Icon (desktop). Hidden (mobile) | — |
| **Cart Icon** | Rightmost | If WooCommerce active. Badge with item count. | Same |
| **Sticky Behavior** | `position: sticky; top: 0` | On scroll, header remains visible | Same |

### 2.3 Breadcrumb

| Property | Specification |
|---|---|
| **Location** | Between header and `<main>`. On all inner pages. NOT on Homepage. |
| **Structure** | `Home > Page Name` (flat — ADR D-016). Exception: `Home > Winkel > Product Naam` (WooCommerce products). |
| **Mobile** | Truncated to last 2 items if >3 levels. |
| **Schema** | BreadcrumbList JSON-LD (Rank Math Pro auto-generates) |

### 2.4 Main Content Area

| Property | Specification |
|---|---|
| **HTML** | `<main id="content">` |
| **Min Height** | `60vh` (ensures footer pushes down on short pages) |
| **Container** | `wideSize` (1200px) for layout pages. `contentSize` (780px) for reading pages. |
| **Sidebar** | Only on Blog posts (P30) and Blog index (P29). 300px wide, right side. Sticky on desktop. |

### 2.5 CTA Banner (Global)

The CTA banner appears on every page except: Legal pages (P19-P22), 404 (P31), Bedankt (P32), Checkout (P27), Cart (P26).

| Property | Specification |
|---|---|
| **Background** | Full-width `primary` or `accent` color |
| **Content** | H2 heading (28-36px), 1 sentence body text, CTA button |
| **Heading Text** | "Wilt u een vrijblijvende offerte?" or page-specific variant |
| **CTA Button** | "Offerte aanvragen" → `/offerte-aanvragen/` (accent/orange, large) |
| **Layout** | Desktop: text left, button right (horizontal). Mobile: text centered, button full-width below (stacked). |
| **Padding** | `spacing-12` (48px) vertical |
| **Conditional** | Always visible. Not conditional. |

### 2.6 Footer

| Element | Specification |
|---|---|
| **Columns** | Desktop: 5. Tablet: 3+2. Mobile: 1-2, stacked. |
| **Column 1 — Diensten** | Glasbewassing, Gevelreiniging, Reguliere Schoonmaak, Vloeronderhoud, VVE Service, Oplevering Schoonmaak, Industriele Schoonmaak |
| **Column 2 — Over HDS** | Over HDS, Kwaliteit & Veiligheid, Referenties, Vacatures, Downloads |
| **Column 3 — Contact** | Phone (tel: link), Email (mailto: link), Address (if MI-01), KVK (if MI-02), BTW (if MI-03) |
| **Column 4 — Luchtreiniging** | Over Airfixr (→ /luchtreiniging/), Winkel (→ /winkel/), Mijn Account (→ /mijn-account/). Hidden if Airfixr removed. |
| **Column 5 — Juridisch** | Privacyverklaring, Cookiebeleid, Algemene Voorwaarden, Disclaimer |
| **Social Icons** | Below columns, centered or left: Facebook, Instagram, Google Business Profile |
| **Copyright** | "© 2026 HDS Onderhoudsdiensten" |
| **Cookie Settings** | Complianz floating button: "Cookie-instellingen" |

---

## 3. Homepage Wireframe (`/` — P01)

**Template:** `front-page.php`
**Goal:** Communicate services and USPs. Drive visitors to request a quote.
**H1:** "Helder en Duidelijk voor het Schoonste resultaat!"
**H1 (visually hidden alternative for SEO):** "HDS Onderhoudsdiensten | Schoonmaak- en Onderhoudsdiensten West-Brabant Zeeland"

### 3.1 Section Inventory (Top to Bottom)

```
┌──────────────────────────────────────────────────────────────┐
│ SECTION 1 — HERO                                              │
├──────────────────────────────────────────────────────────────┤
│ Background: primary gradient or hero image                     │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │                                                          │ │
│ │   H1: Helder en Duidelijk voor het Schoonste resultaat!  │ │
│ │                                                          │ │
│ │   Subtitle: Professionele schoonmaak- en onderhouds-     │ │
│ │   diensten voor bedrijven in West-Brabant en Zeeland.    │ │
│ │   Vast personeel · Veiligheid gecertificeerd · Maatwerk  │ │
│ │                                                          │ │
│ │   [OFFERTE AANVRAGEN — accent CTA button]                │ │
│ │                                                          │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ SECTION 2 — SERVICE CARD GRID                                 │
├──────────────────────────────────────────────────────────────┤
│ H2: Onze Diensten                                             │
│                                                               │
│ ┌───────────┐ ┌───────────┐ ┌───────────┐                   │
│ │ [Icon]    │ │ [Icon]    │ │ [Icon]    │                   │
│ │ Reguliere │ │ Glas-     │ │ Gevel-    │                   │
│ │ Schoonmaak│ │ bewassing │ │ reiniging │                   │
│ │ 1 zin     │ │ 1 zin     │ │ 1 zin     │                   │
│ │ [Lees >]  │ │ [Lees >]  │ │ [Lees >]  │                   │
│ └───────────┘ └───────────┘ └───────────┘                   │
│ ┌───────────┐ ┌───────────┐ ┌───────────┐ ┌───────────┐    │
│ │ Vloer-    │ │ VVE       │ │ Oplevering│ │Industriele│    │
│ │ onderhoud │ │ Service   │ │ Schoonmaak│ │Schoonmaak │    │
│ └───────────┘ └───────────┘ └───────────┘ └───────────┘    │
│                                                               │
│ Grid: 3 cols (desktop), 2 cols (tablet), 1 col (mobile)       │
│ Ordered by menu_order: Reguliere first                        │
├──────────────────────────────────────────────────────────────┤
│ SECTION 3 — USP GRID                                          │
├──────────────────────────────────────────────────────────────┤
│ H2: Waarom HDS?                                               │
│                                                               │
│ ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐         │
│ │ [Icon]   │ │ [Icon]   │ │ [Icon]   │ │ [Icon]   │         │
│ │ Vast     │ │ Veiligheid│ │ Één      │ │ Maatwerk │         │
│ │ opgeleid │ │ & Certif. │ │ aanspreek│ │ planning │         │
│ │ personeel│ │           │ │ punt     │ │          │         │
│ └──────────┘ └──────────┘ └──────────┘ └──────────┘         │
│ ┌──────────┐ ┌──────────┐                                     │
│ │ [Icon]   │ │ [Icon]   │                                     │
│ │ Milieu-  │ │ Regio    │                                     │
│ │ bewust   │ │ specialist│                                    │
│ └──────────┘ └──────────┘                                     │
│                                                               │
│ 4 cols (desktop), 2 cols (tablet), 1 col (mobile)             │
├──────────────────────────────────────────────────────────────┤
│ SECTION 4 — CLIENT LOGO CAROUSEL (Conditional)                │
├──────────────────────────────────────────────────────────────┤
│ H2: Zij gingen u voor                                         │
│                                                               │
│ [Logo 1]  [Logo 2]  [Logo 3]  [Logo 4]  [Logo 5]            │
│                                                               │
│ Horizontal scrollable carousel or static grid.                │
│ CONDITIONAL: Hide entire section if no logos (ADR D-015).    │
├──────────────────────────────────────────────────────────────┤
│ SECTION 5 — TESTIMONIALS (Conditional)                        │
├──────────────────────────────────────────────────────────────┤
│ H2: Wat onze klanten zeggen                                   │
│                                                               │
│ ┌─────────────────┐ ┌─────────────────┐ ┌─────────────────┐  │
│ │ "Quote text..."  │ │ "Quote text..."  │ │ "Quote text..."  │  │
│ │ — Author, Company│ │ — Author, Company│ │ — Author, Company│  │
│ │ ★★★★★            │ │ ★★★★★            │ │ ★★★★★            │  │
│ └─────────────────┘ └─────────────────┘ └─────────────────┘  │
│                                                               │
│ 3 cols (desktop), 1 col (tablet/mobile)                       │
│ CONDITIONAL: Hide entire section if no testimonials.         │
├──────────────────────────────────────────────────────────────┤
│ SECTION 6 — CTA BANNER                                        │
├──────────────────────────────────────────────────────────────┤
│ [H2: Wilt u een vrijblijvende offerte?]    [CTA BUTTON]      │
├──────────────────────────────────────────────────────────────┤
│ SECTION 7 — SERVICE AREA                                      │
├──────────────────────────────────────────────────────────────┤
│ H2: Ons Werkgebied                                            │
│                                                               │
│ [Text: Wij bedienen bedrijven in heel West-Brabant en        │
│  Zeeland, waaronder Bergen op Zoom, Roosendaal, Goes,        │
│  Middelburg, Terneuzen en omliggende gemeenten.]             │
│                                                               │
│ [Google Maps Embed — CONDITIONAL: only if MI-01 provided.    │
│  Wrapped in Complianz consent placeholder.]                  │
├──────────────────────────────────────────────────────────────┤
│ SECTION 8 — LATEST BLOG POSTS (Conditional)                   │
├──────────────────────────────────────────────────────────────┤
│ H2: Tips & Nieuws                                             │
│                                                               │
│ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐          │
│ │ [Image]      │ │ [Image]      │ │ [Image]      │          │
│ │ Date         │ │ Date         │ │ Date         │          │
│ │ Title        │ │ Title        │ │ Title        │          │
│ │ Excerpt      │ │ Excerpt      │ │ Excerpt      │          │
│ │ [Lees meer]  │ │ [Lees meer]  │ │ [Lees meer]  │          │
│ └──────────────┘ └──────────────┘ └──────────────┘          │
│                                                               │
│ CONDITIONAL: Hide entire section if no published posts.      │
│ [Bekijk alle artikelen →] link → /kennisbank/                 │
└──────────────────────────────────────────────────────────────┘
```

### 3.2 Section Details

#### Section 1 — Hero

| Property | Specification |
|---|---|
| **Purpose** | Communicate who HDS is, what they do, and where — in 3 seconds. |
| **H1** | "Helder en Duidelijk voor het Schoonste resultaat!" |
| **Subtitle** | 1-2 sentences: service summary + region + USP keywords |
| **CTA** | "Vrijblijvende offerte" → `/offerte-aanvragen/`. Accent button, large. |
| **Background** | Primary gradient or hero image with dark overlay |
| **Height** | Min 60vh on desktop. 50vh on mobile. |
| **Mobile** | H1 smaller (36px). Subtitle 16px. CTA full-width. |

#### Section 2 — Service Card Grid

| Property | Specification |
|---|---|
| **Purpose** | Give visitors an immediate overview of all 7 services. Each card is a path to deeper engagement. |
| **Cards** | 7 cards. Icon (32px, primary color) + Title (H3) + 1-sentence description + "Lees meer →" link. |
| **Order** | Reguliere Schoonmaak, Glasbewassing, Gevelreiniging, Vloeronderhoud, VVE Service, Oplevering Schoonmaak, Industriele Schoonmaak (ADR D-014). |
| **Grid** | 3 columns (desktop), 2 columns (tablet), 1 column (mobile). |
| **Interaction** | Entire card is clickable. Hover: subtle lift + shadow. |
| **Source** | `hds/service-card` custom block, queried by `menu_order`. |

#### Section 3 — USP Grid

| Property | Specification |
|---|---|
| **Purpose** | Differentiate HDS from competitors. Answer "Why choose HDS?" |
| **Items** | 6 cards: Vast opgeleid personeel, Veiligheid & Certificering, Één aanspreekpunt, Maatwerk planning, Milieubewust (MVO), Regio specialist. |
| **Per Card** | Icon (24px) + Heading (H3 or strong text) + 1-2 sentence explanation. |
| **Grid** | 4 columns (desktop, 4+2 wrapped), 2 columns (tablet), 1 column (mobile). |

#### Section 4 — Client Logo Carousel

| Property | Specification |
|---|---|
| **Purpose** | Social proof through recognizable client logos. |
| **Content** | Client logo images (monochrome/grayscale preferred for consistent look). 5+ logos horizontally scrollable. |
| **Conditional** | Entire section hidden if no logos in Customizer. |
| **Alt Text** | Each logo: `alt="[Client Name]"` |

#### Section 5 — Testimonials

| Property | Specification |
|---|---|
| **Purpose** | Human social proof. Real quotes from real clients. |
| **Content** | 3-5 testimonials. Each: quotation mark icon (decorative) + quote text (italic) + author name + company + star rating (1-5, accent orange filled). |
| **Grid** | 3 columns (desktop), 1 column (tablet/mobile). |
| **Conditional** | Entire section hidden if no testimonials in `hds_testimonial` CPT. |
| **Source** | `hds/testimonial` custom block. |

#### Section 6 — CTA Banner

Same as global CTA banner (see §2.5). "Wilt u een vrijblijvende offerte? Wij denken graag met u mee." → `/offerte-aanvragen/`

#### Section 7 — Service Area

| Property | Specification |
|---|---|
| **Purpose** | Confirm HDS serves the visitor's location. |
| **Content** | H2: "Ons Werkgebied". Paragraph listing primary municipalities. |
| **Map (Conditional)** | Google Maps embed showing Bergen op Zoom region. Only if MI-01 (address) provided. Wrapped in Complianz consent placeholder (GDPR). |
| **Placeholder (no address)** | Text-only version. No broken embed. |

#### Section 8 — Latest Blog Posts

| Property | Specification |
|---|---|
| **Purpose** | Demonstrate expertise. Provide fresh content signal for SEO. |
| **Content** | 3 most recent blog posts. Each: featured image (16:9, WebP) + date + title (H3, linked) + excerpt (2 lines) + "Lees meer →" |
| **Grid** | 3 columns (desktop), 1 column (tablet/mobile). |
| **Conditional** | Entire section hidden if no published posts. |
| **Footer Link** | "Bekijk alle artikelen →" → `/kennisbank/`. |

---

## 4. Service Page Wireframe (`/glasbewassing/`, etc. — P02-P08)

**Template:** `page-templates/page-service.php`
**Goal:** Provide detailed information about one service. Build credibility. Drive conversion.
**H1:** Service name (e.g., "Glasbewassing")
**Minimum Words:** 300+ Dutch words
**Schema:** Service JSON-LD

### 4.1 Section Inventory

```
┌──────────────────────────────────────────────────────────────┐
│ BREADCRUMB: Home > [Service Name]                              │
├──────────────────────────────────────────────────────────────┤
│ SECTION 1 — HERO                                               │
├──────────────────────────────────────────────────────────────┤
│ Background: primary gradient or hero image (hds_hero_image)    │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │                                                          │ │
│ │   H1: [Service Name]                                     │ │
│ │   Subtitle (from hds_subtitle custom field)               │ │
│ │                                                          │ │
│ │   [OFFERTE AANVRAGEN — accent CTA button]                │ │
│ │                                                          │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ SECTION 2 — INTRODUCTION                                       │
├──────────────────────────────────────────────────────────────┤
│ [Text content — managed via Block Editor]                      │
│                                                               │
│ Intro paragraph: what the service is, who it's for, why HDS.  │
│ 1-2 paragraphs. H2 heading optional.                          │
├──────────────────────────────────────────────────────────────┤
│ SECTION 3 — OUR APPROACH / BENEFITS                            │
├──────────────────────────────────────────────────────────────┤
│ H2: Onze Aanpak / Waarom [Service Name] door HDS?             │
│                                                               │
│ Content with Image pattern: text (left) + image (right).      │
│ Reversed on alternating sections. Stacked on mobile.          │
│                                                               │
│ Describes: process, safety measures, trained staff,           │
│ certifications, equipment used.                               │
├──────────────────────────────────────────────────────────────┤
│ SECTION 4 — SERVICE DETAILS / SCOPE                            │
├──────────────────────────────────────────────────────────────┤
│ H2: Wat doen we? / Onze [Service Name] diensten               │
│                                                               │
│ Service Icon List: bullet list with checkmark icons.          │
│ Lists specific tasks included in the service.                 │
│                                                               │
│ Example (Glasbewassing):                                      │
│ ✓ Traditionele glasbewassing                                  │
│ ✓ Hoogbouw glasbewassing met hoogwerker                       │
│ ✓ Veiligheidspaspoort gecertificeerd personeel                │
│ ✓ Periodiek of eenmalig                                       │
│ ✓ Aan- en afmelden bij de opdrachtgever                       │
├──────────────────────────────────────────────────────────────┤
│ SECTION 5 — CROSS-SELL SERVICES                                │
├──────────────────────────────────────────────────────────────┤
│ H2: Gerelateerde Diensten                                      │
│                                                               │
│ ┌───────────┐ ┌───────────┐ ┌───────────┐                   │
│ │ [Card]    │ │ [Card]    │ │ [Card]    │                   │
│ │ Related   │ │ Related   │ │ Related   │                   │
│ │ Service 1 │ │ Service 2 │ │ Service 3 │                   │
│ └───────────┘ └───────────┘ └───────────┘                   │
│                                                               │
│ 3-column service card grid (same component as homepage §3.2)  │
│ Cross-sell rules defined per service (FS §4.2).               │
├──────────────────────────────────────────────────────────────┤
│ SECTION 6 — OPTIONAL FAQ                                       │
├──────────────────────────────────────────────────────────────┤
│ H2: Veelgestelde Vragen over [Service Name]                    │
│                                                               │
│ FAQ Accordion: 3-5 service-specific questions.                │
│ Yoast/Rank Math FAQ Block. FAQPage schema auto-generated.     │
│                                                               │
│ CONDITIONAL: Hide section if no FAQ content.                  │
│ Link: "Bekijk alle vragen →" → /veelgestelde-vragen/          │
├──────────────────────────────────────────────────────────────┤
│ SECTION 7 — CTA BANNER                                        │
├──────────────────────────────────────────────────────────────┤
│ [H2: Interesse in [Service Name]?]          [CTA BUTTON]      │
│ [Vraag een vrijblijvende offerte aan]                         │
└──────────────────────────────────────────────────────────────┘
```

### 4.2 Section Details

#### Cross-Sell Rules

Per FS §4.2, each service page links to specific related services:

| Current Page | Cross-Sell Services |
|---|---|
| Reguliere Schoonmaak | Glasbewassing, Vloeronderhoud, Industriele Schoonmaak |
| Glasbewassing | Gevelreiniging, Reguliere Schoonmaak, Oplevering Schoonmaak |
| Gevelreiniging | Glasbewassing, Vloeronderhoud |
| Vloeronderhoud | Reguliere Schoonmaak, Oplevering Schoonmaak |
| VVE Service | Reguliere Schoonmaak, Glasbewassing, Gevelreiniging |
| Oplevering Schoonmaak | Reguliere Schoonmaak, Glasbewassing, Vloeronderhoud |
| Industriele Schoonmaak | Reguliere Schoonmaak, Vloeronderhoud |

### 4.3 Service Page — Mobile

| Element | Mobile Behavior |
|---|---|
| Hero | Full-width. H1: 36px. CTA: full-width. |
| Content + Image | Stacked: text above image. |
| Service Icon List | Full-width bullets. |
| Cross-Sell Cards | 1 column. |
| FAQ | Full-width accordion. |
| Sticky CTA | Floating CTA button at bottom of screen on mobile (optional enhancement). |

---

## 5. Category Landing Page Wireframe (`/glas-en-gevel/`, `/schoonmaakdiensten/` — P09-P10)

**Template:** `page-templates/page-category-landing.php`
**Goal:** SEO landing page for broader service terms. Guide visitors to specific service pages.
**Minimum Words:** 500+ Dutch words

```
┌──────────────────────────────────────────────────────────────┐
│ BREADCRUMB: Home > [Category Name]                            │
├──────────────────────────────────────────────────────────────┤
│ HERO                                                          │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H1: [Category Name — e.g., "Glas & Gevel"]               │ │
│ │ Subtitle: 1 sentence describing the category             │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ INTRODUCTION                                                  │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ 2-3 paragraphs introducing the service category.         │ │
│ │ Explaining the connection between sub-services.          │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ SERVICE CARD GRID                                             │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H2: Onze [Category] Diensten                             │ │
│ │                                                          │ │
│ │ [Service Card 1 — Full size]  [Service Card 2 — Full]    │ │
│ │                                                          │ │
│ │ Category "Glas & Gevel": 2 cards (Glasbewassing,         │ │
│ │ Gevelreiniging).                                         │ │
│ │ Category "Schoonmaakdiensten": 5 cards (Reguliere,       │ │
│ │ Vloer, VVE, Oplevering, Industrieel).                    │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ CTA BANNER                                                    │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H2: Vrijblijvende offerte?         [CTA BUTTON]          │ │
│ └──────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

**Grid:** 2 columns (desktop), 1 column (tablet/mobile) for 2-card categories. 3 columns (desktop) for 5-card categories.

---

## 6. About Page Wireframes (P11-P12)

### 6.1 Over HDS (`/over-hds/` — P11)

**Goal:** Build trust. Communicate company story, values, and credentials.
**Minimum Words:** 500+ Dutch words

```
┌──────────────────────────────────────────────────────────────┐
│ BREADCRUMB: Home > Over HDS                                   │
├──────────────────────────────────────────────────────────────┤
│ HERO                                                          │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H1: Over HDS Schoonmaakdiensten                          │ │
│ │ Subtitle: Deskundigheid, ervaring en vertrouwen          │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ SECTION 1 — COMPANY STORY                                     │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ Content with Image: text (left) + team photo (right).    │ │
│ │                                                          │ │
│ │ H2: Ons Verhaal                                          │ │
│ │ History, founding year, origin story, milestones.        │ │
│ │ Content from MI-19 (client to provide).                  │ │
│ │ If no history provided: focus on mission and values.     │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ SECTION 2 — MISSION & VALUES                                  │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H2: Waar wij voor staan                                  │ │
│ │                                                          │ │
│ │ USP Grid (6 items) — same component as Homepage §3.3:    │ │
│ │ Vast personeel, Veiligheid, Aanspreekpunt, Maatwerk,     │ │
│ │ Milieubewust, Regio specialist                           │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ SECTION 3 — TEAM / STAFF (Conditional)                        │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H2: Ons Team                                             │ │
│ │                                                          │ │
│ │ Team member cards with photos, names, roles.             │ │
│ │ CONDITIONAL: Hide if no team content.                    │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ SECTION 4 — CERTIFICATIONS / PARTNERSHIPS                     │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H2: Certificeringen & Partners                           │ │
│ │                                                          │ │
│ │ Logo grid: OSB, VCA (if applicable), VvE Belang, etc.   │ │
│ │ Each logo with alt text and optional description.       │ │
│ │ Link to /kwaliteit-veiligheid/ for details.             │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ CTA BANNER                                                    │
└──────────────────────────────────────────────────────────────┘
```

### 6.2 Kwaliteit & Veiligheid (`/kwaliteit-veiligheid/` — P12)

**Goal:** Demonstrate professional standards. Reassure B2B clients about compliance.
**Minimum Words:** 300+ Dutch words

```
┌──────────────────────────────────────────────────────────────┐
│ BREADCRUMB: Home > Kwaliteit & Veiligheid                     │
├──────────────────────────────────────────────────────────────┤
│ HERO                                                          │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H1: Kwaliteit & Veiligheid MVO                           │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ SECTION 1 — KWALITEIT                                         │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H2: Kwaliteit                                            │ │
│ │ Body text: continuous improvement, periodic checks,      │ │
│ │ complaints resolved immediately, single point of contact │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ SECTION 2 — VEILIGHEID                                        │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H2: Veiligheid                                           │ │
│ │ Body text: OSB contact, Arbeidsinspectie liaison,        │ │
│ │ Arbo compliance, mandatory RI&E per project              │ │
│ │                                                          │ │
│ │ Service Icon List: safety measures, certifications       │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ SECTION 3 — MVO (CSR)                                         │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H2: Maatschappelijk Verantwoord Ondernemen               │ │
│ │ Body text: minimal environmentally harmful products,     │ │
│ │ employee care, sustainable practices                     │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ CTA BANNER                                                    │
└──────────────────────────────────────────────────────────────┘
```

---

## 7. Referenties Page Wireframe (`/referenties/` — P13)

**Goal:** Social proof. Show real clients and testimonials.
**Minimum Words:** 300+ Dutch words

```
┌──────────────────────────────────────────────────────────────┐
│ BREADCRUMB: Home > Referenties                                │
├──────────────────────────────────────────────────────────────┤
│ HERO                                                          │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H1: Referenties                                          │ │
│ │ Subtitle: In opdracht van o.a. onderstaande              │ │
│ │ opdrachtgevers wordt door HDS dagelijkse en/of           │ │
│ │ periodieke werkzaamheden uitgevoerd.                     │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ SECTION 1 — CLIENT LOGOS                                      │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H2: Onze Opdrachtgevers                                  │ │
│ │                                                          │ │
│ │ [Logo Grid: 4-5 columns (desktop), 2 (tablet),          │ │
│ │  1 (mobile)]                                             │ │
│ │                                                          │ │
│ │ CONDITIONAL: Hide section if no logos (ADR D-015).       │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ SECTION 2 — TESTIMONIALS                                      │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H2: Wat onze klanten zeggen                              │ │
│ │                                                          │ │
│ │ [Testimonial Grid — same component as Homepage §3.5]     │ │
│ │ All testimonials from hds_testimonial CPT.               │ │
│ │                                                          │ │
│ │ CONDITIONAL: Hide section if no testimonials.            │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ EMPTY STATE (if both sections hidden)                         │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ "Wij zijn trots op onze opdrachtgevers. Binnenkort       │ │
│ │  leest u hier hun ervaringen."                           │ │
│ │                                                          │ │
│ │ [Neem contact op →] → /contact/                          │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ CTA BANNER                                                    │
└──────────────────────────────────────────────────────────────┘
```

---

## 8. Vacatures Page Wireframe (`/vacatures/` — P14)

**Goal:** Attract qualified job applicants. Present HDS as an employer.
**Minimum Words:** 300+ Dutch words
**Schema:** JobPosting JSON-LD per vacancy

```
┌──────────────────────────────────────────────────────────────┐
│ BREADCRUMB: Home > Vacatures                                  │
├──────────────────────────────────────────────────────────────┤
│ HERO                                                          │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H1: Vacatures                                            │ │
│ │ Subtitle: Wordt u onze collega?                          │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ SECTION 1 — WORKING AT HDS                                    │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H2: Werken bij HDS Onderhoudsdiensten                   │ │
│ │                                                          │ │
│ │ 2-3 paragraphs about company culture, benefits,          │ │
│ │ training, career paths. USP Grid (optional): why work    │ │
│ │ at HDS.                                                  │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ SECTION 2 — OPEN VACANCIES                                    │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H2: Openstaande Vacatures                                │ │
│ │                                                          │ │
│ │ ┌────────────────────────────────────────────────────┐   │ │
│ │ │ VACANCY CARD 1                         [Bekijk ▾]   │   │ │
│ │ │ Titel: [Job Title]                                   │   │ │
│ │ │ 🕐 [Hours/week]  📍 [Location]  📅 [Start date]     │   │ │
│ │ │                                                      │   │ │
│ │ │ Expanded (toggle):                                    │   │ │
│ │ │   Full description text                               │   │ │
│ │ │   Requirements / qualifications                      │   │ │
│ │ │   What we offer                                       │   │ │
│ │ │   Deadline: [date]                                    │   │ │
│ │ │                                                      │   │ │
│ │ │   [SOLLICITEER DIRECT — primary button]              │   │ │
│ │ │   → Opens application form or mailto: link            │   │ │
│ │ └────────────────────────────────────────────────────┘   │ │
│ │                                                          │ │
│ │ ┌────────────────────────────────────────────────────┐   │ │
│ │ │ VACANCY CARD 2 (same structure)                     │   │ │
│ │ └────────────────────────────────────────────────────┘   │ │
│ │                                                          │ │
│ │ Source: hds/job-listing custom block.                     │ │
│ │ Queries hds_vacancy CPT. Active only (hds_is_active=1).  │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ SECTION 3 — APPLICATION FORM (GF-3)                           │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H2: Open Sollicitatie                                    │ │
│ │                                                          │ │
│ │ [Gravity Forms — Vacature Application Form GF-3]         │ │
│ │ Fields: Naam, E-mailadres, Telefoonnummer, Motivatie,   │ │
│ │ CV uploaden (max 5MB, PDF/DOCX), Privacy akkoord        │ │
│ │                                                          │ │
│ │ CONDITIONAL: Show after vacancy cards.                   │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ EMPTY STATE (if no active vacancies)                          │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ "Er zijn op dit moment geen openstaande vacatures.       │ │
│ │  Stuur een open sollicitatie naar                        │ │
│ │  info@helderduidelijkschoon.nl."                         │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ CTA BANNER                                                    │
└──────────────────────────────────────────────────────────────┘
```

### 8.1 Vacancy Card Structure

| Field | Source | Fallback |
|---|---|---|
| Title | `post_title` | — |
| Hours/week | `hds_hours_per_week` | Not displayed if empty |
| Location | `hds_location` | "Regio Bergen op Zoom / West-Brabant" |
| Start date | `hds_start_date` | "In overleg" |
| Description | `post_content` (rich text from WP Editor) | — |
| Application email | `hds_application_email` | `info@helderduidelijkschoon.nl` |
| Deadline | `hds_deadline` | Not displayed if empty |
| Active toggle | `hds_is_active` | Query filter: only active vacancies |

---

## 9. Downloads Page Wireframe (`/downloads/` — P15)

**Goal:** Provide legal documents and resources.
**Minimum Words:** 150+ Dutch words

```
┌──────────────────────────────────────────────────────────────┐
│ BREADCRUMB: Home > Downloads                                  │
├──────────────────────────────────────────────────────────────┤
│ HERO                                                          │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H1: Downloads                                            │ │
│ │ Subtitle: Algemene voorwaarden en documenten             │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ DOWNLOAD CARDS                                                │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ ┌────────────────────────────────────────────────────┐   │ │
│ │ │ [PDF Icon]  Algemene voorwaarden schoonmaak-       │   │ │
│ │ │             werkzaamheden                          │   │ │
│ │ │             PDF · 245 KB                           │   │ │
│ │ │             [Download ⬇]                           │   │ │
│ │ └────────────────────────────────────────────────────┘   │ │
│ │                                                          │ │
│ │ ┌────────────────────────────────────────────────────┐   │ │
│ │ │ [PDF Icon]  Algemene voorwaarden gevelreiniging    │   │ │
│ │ │             PDF · 189 KB                           │   │ │
│ │ │             [Download ⬇]                           │   │ │
│ │ └────────────────────────────────────────────────────┘   │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ NOTE                                                          │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ "Heeft u vragen over onze voorwaarden? Neem contact      │ │
│ │  met ons op via 0164-652846 of                            │ │
│ │  info@helderduidelijkschoon.nl."                         │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ CTA BANNER                                                    │
└──────────────────────────────────────────────────────────────┘
```

---

## 10. Contact Page Wireframe (`/contact/` — P16)

**Template:** `page-templates/page-contact.php`
**Goal:** Capture leads. Provide all contact methods.
**Minimum Words:** 150+ Dutch words
**Form:** GF-1 (Contact Form)
**Schema:** LocalBusiness JSON-LD

```
┌──────────────────────────────────────────────────────────────┐
│ BREADCRUMB: Home > Contact                                    │
├──────────────────────────────────────────────────────────────┤
│ HERO                                                          │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H1: Contact                                              │ │
│ │ Subtitle: Neem vrijblijvend contact met ons op           │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ TWO-COLUMN LAYOUT                                             │
│ ┌────────────────────────────┬─────────────────────────────┐ │
│ │ COLUMN 1: FORM (60%)       │ COLUMN 2: CONTACT INFO (40%)│ │
│ │                            │                             │ │
│ │ [Gravity Forms GF-1]       │ ☏ 0164-652846               │ │
│ │                            │    (klikbaar tel: link)     │ │
│ │ Naam *                     │                             │ │
│ │ [________________]         │ ✉ info@helderduidelijkschoon│ │
│ │                            │    .nl (klikbaar mailto:)   │ │
│ │ Bedrijf (optioneel)        │                             │ │
│ │ [________________]         │ 📍 [Address — CONDITIONAL]  │ │
│ │                            │    if MI-01 provided        │ │
│ │ E-mailadres *              │                             │ │
│ │ [________________]         │ KVK: [XXXXXXXX] — CONDITIONAL│ │
│ │                            │ BTW: [NLXXXXXXXXXB01]       │ │
│ │ Telefoonnummer (optioneel) │                             │ │
│ │ [________________]         │ 🕐 Openingstijden           │ │
│ │                            │    — CONDITIONAL (MI-04)    │ │
│ │ Onderwerp * [dropdown ▾]   │                             │ │
│ │                            │ [Facebook] [Instagram]      │ │
│ │ Bericht *                  │                             │ │
│ │ [________________]         │                             │ │
│ │ [________________]         │                             │ │
│ │                            │                             │ │
│ │ ☐ Ik ga akkoord met de     │                             │ │
│ │   privacyverklaring *      │                             │ │
│ │                            │                             │ │
│ │ [VERSTUUR BERICHT]         │                             │ │
│ │                            │                             │ │
│ │ Lukt het niet? Bel         │                             │ │
│ │ 0164-652846                │                             │ │
│ └────────────────────────────┴─────────────────────────────┘ │
│                                                               │
│ MOBILE: Single column. Form first, contact info below.        │
├──────────────────────────────────────────────────────────────┤
│ MAP SECTION (Conditional)                                     │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ [Google Maps Embed — CONDITIONAL: only if MI-01          │ │
│ │  provided. Wrapped in Complianz consent placeholder.]    │ │
│ └──────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

### 10.1 Contact Form (GF-1) Fields

| # | Field | Type | Required | Notes |
|---|---|---|---|---|
| 1 | Naam | Text | Yes | Full name |
| 2 | Bedrijf | Text | No | Company name |
| 3 | E-mailadres | Email | Yes | Valid format validation |
| 4 | Telefoonnummer | Tel | No | Dutch format regex: `/^(\+31\|0)[1-9][0-9]{7,10}$/` |
| 5 | Onderwerp | Dropdown | Yes | Options: Offerte aanvragen, Vraag over diensten, Klacht of opmerking, Anders |
| 6 | Bericht | Textarea | Yes | Min 10 characters, max 5000 |
| 7 | Privacy akkoord | Checkbox | Yes | Unchecked default. Label: "Ik ga akkoord met de privacyverklaring" linked to `/privacyverklaring/` |
| 8 | Anti-spam | reCAPTCHA v3 | Yes | Invisible. Honeypot field also active. |
| 9 | Submit | Button | — | "Verstuur bericht". AJAX submission. Redirect → `/bedankt/?type=contact` |

### 10.2 Contact Info Column (Conditional Elements)

| Element | Visibility Condition | Fallback |
|---|---|---|
| Phone | Always (hardcoded 0164-652846 from Customizer) | — |
| Email | Always (hardcoded info@helderduidelijkschoon.nl from Customizer) | — |
| Address | Only if `hds_address` and `hds_postal_city` have values (MI-01) | Element hidden. No blank space. |
| KVK | Only if `hds_kvk` has value (MI-02) | Element hidden. |
| BTW | Only if `hds_btw` has value (MI-03) | Element hidden. |
| Opening Hours | Only if `hds_opening_hours` has value (MI-04) | Element hidden. |
| Social Icons | Facebook, Instagram — always shown. GBP — only if `hds_gbp_url` has value. | Missing social: icon hidden. |

---

## 11. Offerte Aanvragen Page Wireframe (`/offerte-aanvragen/` — P17)

**Template:** `page-templates/page-quote.php`
**Goal:** Capture qualified leads with detailed requirements.
**Form:** GF-2 (Quote Request Form)

```
┌──────────────────────────────────────────────────────────────┐
│ BREADCRUMB: Home > Offerte Aanvragen                          │
├──────────────────────────────────────────────────────────────┤
│ HERO                                                          │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H1: Vrijblijvende Offerte Aanvragen                      │ │
│ │ Subtitle: Wij denken graag met u mee over de beste       │ │
│ │ oplossing. Vul het formulier in en wij nemen binnen      │ │
│ │ 2 werkdagen contact met u op.                            │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ FULL-WIDTH FORM                                               │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ [Gravity Forms GF-2]                                     │ │
│ │                                                          │ │
│ │ Naam *                         Bedrijf *                 │ │
│ │ [________________]             [________________]        │ │
│ │                                                          │ │
│ │ E-mailadres *                  Telefoonnummer *          │ │
│ │ [________________]             [________________]        │ │
│ │                                                          │ │
│ │ Gewenste dienst * (multiple checkboxes)                  │ │
│ │ ☐ Reguliere Schoonmaak  ☐ Glasbewassing                  │ │
│ │ ☐ Gevelreiniging        ☐ Vloeronderhoud                 │ │
│ │ ☐ VVE Service           ☐ Oplevering Schoonmaak          │ │
│ │ ☐ Industriele Schoonmaak ☐ Anders: [________]            │ │
│ │                                                          │ │
│ │ Type gebouw (optioneel dropdown)                         │ │
│ │ [Kies... ▾]                                              │ │
│ │ Kantoor / Wooncomplex (VvE) / School / Zorginstelling    │ │
│ │ / Fabriek of Magazijn / Bouwproject / Anders             │ │
│ │                                                          │ │
│ │ Postcode / Plaats *                                      │ │
│ │ [________________]  (bijv. 1234 AB)                      │ │
│ │                                                          │ │
│ │ Beschrijving (optioneel)                                 │ │
│ │ [_______________________________________________________]│ │
│ │                                                          │ │
│ │ Gewenste planning (optioneel dropdown)                   │ │
│ │ [Kies... ▾]                                              │ │
│ │ Zo snel mogelijk / Binnen 2 weken / Binnen 1 maand       │ │
│ │ / Binnen 3 maanden / Oriënterend                          │ │
│ │                                                          │ │
│ │ Hoe heeft u ons gevonden? (optioneel dropdown)           │ │
│ │ [Kies... ▾]                                              │ │
│ │ Google / VvE Belang / Social media / Relatie / Anders    │ │
│ │                                                          │ │
│ │ Bestand uploaden (optioneel)                             │ │
│ │ [Bestand kiezen]  Max 5 MB. PDF, JPG, PNG, DOCX.        │ │
│ │                                                          │ │
│ │ ☐ Ik ga akkoord met de privacyverklaring *              │ │
│ │                                                          │ │
│ │ [OFFERTE AANVRAGEN — CTA button, large]                  │ │
│ │                                                          │ │
│ │ Lukt het niet? Bel 0164-652846                           │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ SIDEBAR (right of form on desktop, below on mobile)           │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H3: Wat gebeurt er na uw aanvraag?                       │ │
│ │                                                          │ │
│ │ 1. 📞 Wij nemen binnen 2 werkdagen contact op            │ │
│ │ 2. 📋 We bespreken uw wensen en maken een plan           │ │
│ │ 3. 📝 U ontvangt een vrijblijvende offerte               │ │
│ │ 4. 🤝 Bij akkoord starten we met de uitvoering           │ │
│ └──────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

---

## 12. FAQ Page Wireframe (`/veelgestelde-vragen/` — P18)

**Template:** `page-templates/page-faq.php`
**Goal:** Answer common questions. Reduce phone inquiries. Capture SEO for question-based queries.
**Schema:** FAQPage JSON-LD (auto-generated by Yoast/Rank Math FAQ Block)

```
┌──────────────────────────────────────────────────────────────┐
│ BREADCRUMB: Home > Veelgestelde Vragen                        │
├──────────────────────────────────────────────────────────────┤
│ H1: Veelgestelde Vragen                                       │
│ Intro: "Hier vindt u antwoorden op de meest gestelde vragen   │
│ over onze diensten. Staat uw vraag er niet bij? Neem contact  │
│ met ons op."                                                  │
├──────────────────────────────────────────────────────────────┤
│ FAQ ACCORDION (10-15 items)                                   │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ ▾ Wat kost schoonmaak?                                   │ │
│ │   [Answer text...]                                       │ │
│ ├──────────────────────────────────────────────────────────┤ │
│ │ ▸ Werkt u ook in [plaatsnaam]?                           │ │
│ ├──────────────────────────────────────────────────────────┤ │
│ │ ▸ Hoe vaak komt u schoonmaken?                           │ │
│ ├──────────────────────────────────────────────────────────┤ │
│ │ ▸ Gebruikt u milieuvriendelijke producten?               │ │
│ ├──────────────────────────────────────────────────────────┤ │
│ │ ▸ Wat is het verschil tussen reguliere en industriële    │ │
│ │   schoonmaak?                                            │ │
│ ├──────────────────────────────────────────────────────────┤ │
│ │ ▸ Heeft u eigen schoonmaakmiddelen nodig?                │ │
│ ├──────────────────────────────────────────────────────────┤ │
│ │ ▸ Wat is oplevering schoonmaak?                          │ │
│ ├──────────────────────────────────────────────────────────┤ │
│ │ ▸ Hoe zit het met veiligheid en certificering?           │ │
│ ├──────────────────────────────────────────────────────────┤ │
│ │ ▸ Kan ik een eenmalige schoonmaak aanvragen?             │ │
│ ├──────────────────────────────────────────────────────────┤ │
│ │ ▸ Wat zijn de algemene voorwaarden?                      │ │
│ ├──────────────────────────────────────────────────────────┤ │
│ │ ▸ Werkt u ook in het weekend?                            │ │
│ ├──────────────────────────────────────────────────────────┤ │
│ │ ▸ Hoe kan ik een klacht indienen?                        │ │
│ ├──────────────────────────────────────────────────────────┤ │
│ │ ▸ Hoe vraag ik een offerte aan?                          │ │
│ ├──────────────────────────────────────────────────────────┤ │
│ │ ▸ Zijn uw medewerkers in vaste dienst?                   │ │
│ ├──────────────────────────────────────────────────────────┤ │
│ │ ▸ Welke regio bedient u?                                 │ │
│ └──────────────────────────────────────────────────────────┘ │
│                                                               │
│ Implementation: Yoast/Rank Math FAQ Block on standard Page.   │
│ NOT a CPT (ADR D-012).                                        │
├──────────────────────────────────────────────────────────────┤
│ CTA BANNER                                                    │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H2: Staat uw vraag er niet bij?     [NEEM CONTACT OP]    │ │
│ └──────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

**FAQ Accordion Behavior:**
- Multiple items may be open simultaneously.
- Open/close: `aria-expanded` toggle. 250ms `max-height` transition.
- Schema: FAQPage JSON-LD auto-generated per-item from FAQ blocks.

---

## 13. Legal Page Wireframes (P19-P22)

**Template:** `page-templates/page-legal.php`
**Goal:** Legal compliance. Not designed for conversion.
**No CTA banner.** (These pages are about compliance, not conversion.)

```
┌──────────────────────────────────────────────────────────────┐
│ BREADCRUMB: Home > [Legal Page Name]                          │
├──────────────────────────────────────────────────────────────┤
│ H1: [Legal Page Title]                                        │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ [Rich text content — block editor]                       │ │
│ │                                                          │ │
│ │ Full legal text. H2/H3 hierarchy for sections.           │ │
│ │ Content width: contentSize (780px) for readability.      │ │
│ └──────────────────────────────────────────────────────────┘ │
│                                                               │
│ Laatst bijgewerkt: [Date]                                     │
└──────────────────────────────────────────────────────────────┘
```

**Legal Pages:**

| Page | Title | URL | Content Source |
|---|---|---|---|
| P19 | Privacyverklaring | `/privacyverklaring/` | Drafted by developer, reviewed by lawyer (MI-17) |
| P20 | Cookiebeleid | `/cookiebeleid/` | Auto-generated by Complianz |
| P21 | Algemene Voorwaarden | `/algemene-voorwaarden/` | Client provides text (MI-16) |
| P22 | Disclaimer | `/disclaimer/` | Drafted |

**No sidebar. No CTA. No cross-sells.** These pages serve legal requirements only.

---

## 14. Luchtreiniging Landing Page (`/luchtreiniging/` — P23)

**Template:** `page.php` (default)
**Goal:** Introduce Airfixr product line. Bridge cleaning services and product sales.
**Conditional:** Only if Airfixr shop is kept (MI-15).

```
┌──────────────────────────────────────────────────────────────┐
│ BREADCRUMB: Home > Luchtreiniging                             │
├──────────────────────────────────────────────────────────────┤
│ HERO                                                          │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H1: Luchtreiniging met Airfixr                           │ │
│ │ Subtitle: HDS Onderhoudsdiensten is officieel dealer     │ │
│ │ van Airfixr luchtreinigingssystemen                      │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ INTRODUCTION                                                  │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ Why HDS sells Airfixr. Connection to cleaning services.  │ │
│ │ Clean air complements clean spaces.                      │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ FEATURED PRODUCTS                                             │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H2: Onze Airfixr Producten                               │ │
│ │                                                          │ │
│ │ [Product Card] [Product Card] [Product Card]             │ │
│ │ Highlight 3-4 best-selling Airfixr units.                │ │
│ │ "Bekijk alle producten →" → /winkel/                     │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ CTA BANNER                                                    │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H2: Vragen over Airfixr?  [NEEM CONTACT OP]              │ │
│ └──────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

---

## 15. Blog Wireframes

### 15.1 Blog Index (`/kennisbank/` — P29)

**Template:** `archive.php`
**Goal:** Content hub. SEO traffic acquisition. Demonstrate expertise.

```
┌──────────────────────────────────────────────────────────────┐
│ H1: Kennisbank                                                │
│ Subtitle: Tips, nieuws en informatie over schoonmaak,         │
│ onderhoud en veiligheid                                       │
├──────────────────────────────────────────────────────────────┤
│ CATEGORY FILTER (optional — if categories used)               │
│ [Alles] [Schoonmaak] [Glas & Gevel] [Veiligheid] [MVO]        │
├──────────────────────────────────────────────────────────────┤
│ BLOG GRID                                                     │
│ ┌──────────────┐ ┌──────────────┐ ┌──────────────┐          │
│ │ [Image]      │ │ [Image]      │ │ [Image]      │          │
│ │ [Category]   │ │ [Category]   │ │ [Category]   │          │
│ │ Title        │ │ Title        │ │ Title        │          │
│ │ Date         │ │ Date         │ │ Date         │          │
│ │ Excerpt      │ │ Excerpt      │ │ Excerpt      │          │
│ │ [Lees meer]  │ │ [Lees meer]  │ │ [Lees meer]  │          │
│ └──────────────┘ └──────────────┘ └──────────────┘          │
│                                                               │
│ 3 cols (desktop), 2 cols (tablet), 1 col (mobile)             │
├──────────────────────────────────────────────────────────────┤
│ PAGINATION                                                    │
│ ← Vorige  1  2  3  Volgende →                                │
├──────────────────────────────────────────────────────────────┤
│ EMPTY STATE: "Binnenkort verschijnen hier de eerste           │
│ artikelen over schoonmaak, onderhoud en veiligheid."          │
│ [Neem contact op →]                                           │
└──────────────────────────────────────────────────────────────┘
```

### 15.2 Single Blog Post (`/kennisbank/{slug}/` — P30)

**Template:** `single.php`
**Goal:** Read article. Drive to CTA.

```
┌──────────────────────────────────────────────────────────────┐
│ Two-column layout (desktop). Single column (tablet/mobile).   │
│ ┌─────────────────────────────────┬────────────────────────┐ │
│ │ MAIN CONTENT (70%)              │ SIDEBAR (30%, 300px)   │ │
│ │                                 │                        │ │
│ │ BREADCRUMB: Home > Kennisbank   │ H3: Categorieën        │ │
│ │  > [Article Title]              │ [Category list]        │ │
│ │                                 │                        │ │
│ │ [Featured Image — full width]   │ H3: Recente Artikelen  │ │
│ │                                 │ [3 recent post links]  │ │
│ │ H1: Article Title               │                        │ │
│ │                                 │ H3: Vrijblijvende      │ │
│ │ Meta: Date · Category ·         │      offerte?          │ │
│ │ [Reading time]                  │ [CTA BUTTON]           │ │
│ │                                 │                        │ │
│ │ [Article content — block editor]│                        │ │
│ │ H2, H3, paragraphs, images,     │                        │ │
│ │ lists, quotes.                  │                        │ │
│ │                                 │                        │ │
│ │ [Content CTA — inline banner]   │                        │ │
│ │ Interesse in onze diensten?     │                        │ │
│ │ [OFFERTE AANVRAGEN]             │                        │ │
│ │                                 │                        │ │
│ │ [Related Posts — 3 cards]       │                        │ │
│ │ H2: Gerelateerde Artikelen      │                        │ │
│ └─────────────────────────────────┴────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ CTA BANNER (below content, full width on mobile)              │
└──────────────────────────────────────────────────────────────┘
```

---

## 16. WooCommerce Wireframes

### 16.1 Shop Page (`/winkel/` — P24)

```
┌──────────────────────────────────────────────────────────────┐
│ BREADCRUMB: Home > Winkel                                     │
├──────────────────────────────────────────────────────────────┤
│ H1: Winkel                                                    │
│ Intro text: 100+ words explaining Airfixr and connection to HDS│
├──────────────────────────────────────────────────────────────┤
│ TOOLBAR: [Showing all 14 results]  [Sorteer: ▾ Nieuwste]      │
├──────────────────────────────────────────────────────────────┤
│ PRODUCT GRID (WooCommerce default)                             │
│ ┌────────────┐ ┌────────────┐ ┌────────────┐ ┌────────────┐ │
│ │[Image]     │ │[Image]     │ │[Image]     │ │[Image]     │ │
│ │Airfixr 150 │ │Airfixr 60  │ │Airfixr 75  │ │Airfixr Pan.│ │
│ │€795,00     │ │€325,00     │ │€595,00     │ │€395,00     │ │
│ │excl. BTW   │ │excl. BTW   │ │excl. BTW   │ │excl. BTW   │ │
│ │[In winkel- │ │[In winkel- │ │[In winkel- │ │[In winkel- │ │
│ │ wagen]     │ │ wagen]     │ │ wagen]     │ │ wagen]     │ │
│ └────────────┘ └────────────┘ └────────────┘ └────────────┘ │
│                                                               │
│ 4 cols (desktop), 2 cols (tablet), 1 col (mobile)             │
├──────────────────────────────────────────────────────────────┤
│ PAGINATION: ← Vorige  1  2  Volgende → (if >12 products)      │
└──────────────────────────────────────────────────────────────┘
```

### 16.2 Product Page (`/product/{slug}/` — P25)

```
┌──────────────────────────────────────────────────────────────┐
│ BREADCRUMB: Home > Winkel > [Category] > [Product Name]       │
├──────────────────────────────────────────────────────────────┤
│ TWO-COLUMN (desktop)                                          │
│ ┌──────────────────────────┬────────────────────────────────┐ │
│ │ PRODUCT IMAGE GALLERY    │ PRODUCT DETAILS                │ │
│ │                          │                                │ │
│ │ [Main Image — large]     │ H1: [Product Name]             │ │
│ │                          │                                │ │
│ │ [Thumb] [Thumb] [Thumb]  │ Prijs: €795,00 excl. BTW       │ │
│ │                          │                                │ │
│ │                          │ [Quantity: [-] 1 [+]]          │ │
│ │                          │                                │ │
│ │                          │ [IN WINKELWAGEN — primary btn] │ │
│ │                          │                                │ │
│ │                          │ Categorie: [Category]          │ │
│ │                          │ SKU: [SKU]                     │ │
│ └──────────────────────────┴────────────────────────────────┘ │
│                                                               │
│ MOBILE: Image gallery above details. Full-width.              │
├──────────────────────────────────────────────────────────────┤
│ PRODUCT DESCRIPTION (tabs or stacked sections)                 │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ [Beschrijving] [Specificaties] [Beoordelingen]            │ │
│ │                                                          │ │
│ │ Full product description text. Images. Specifications.   │ │
│ │ Customer reviews (if enabled).                           │ │
│ └──────────────────────────────────────────────────────────┘ │
├──────────────────────────────────────────────────────────────┤
│ RELATED PRODUCTS                                              │
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ H2: Gerelateerde Producten                               │ │
│ │ [Product Card] [Product Card] [Product Card]             │ │
│ └──────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

### 16.3 Cart (`/winkelmand/` — P26)

```
┌──────────────────────────────────────────────────────────────┐
│ H1: Winkelmand                                                │
├──────────────────────────────────────────────────────────────┤
│ ┌──────────────────────────────────────────────────────────┐ │
│ │ CART TABLE                                               │ │
│ │ ┌────────┬──────────┬────────┬──────────┬──────┬──────┐ │ │
│ │ │        │ Product  │ Prijs  │ Aantal   │Subt. │      │ │ │
│ │ ├────────┼──────────┼────────┼──────────┼──────┼──────┤ │ │
│ │ │[Image] │ Airfixr  │€795,00│[-] 1 [+] │€795, │ [✕]  │ │ │
│ │ │        │ 150      │excl.   │          │00    │      │ │ │
│ │ │        │          │BTW     │          │      │      │ │ │
│ │ └────────┴──────────┴────────┴──────────┴──────┴──────┘ │ │
│ └──────────────────────────────────────────────────────────┘ │
│                                                               │
│ ┌──────────────────────┐                                     │
│ │ CART TOTALS (sidebar) │                                     │
│ │                      │                                     │
│ │ Subtotaal    €795,00 │                                     │
│ │ BTW (21%)    €166,95 │                                     │
│ │ Verzending    €X,00  │                                     │
│ │ ─────────────────── │                                     │
│ │ Totaal      €961,95 │                                     │
│ │                      │                                     │
│ │ [AFREKENEN →]       │                                     │
│ │                      │                                     │
│ │ [Verder winkelen ←] │                                     │
│ └──────────────────────┘                                     │
│                                                               │
│ MOBILE: Cart table → scrollable. Totals below table.          │
├──────────────────────────────────────────────────────────────┤
│ EMPTY CART: "Uw winkelwagen is leeg." [Bekijk winkel →]      │
└──────────────────────────────────────────────────────────────┘
```

### 16.4 Checkout (`/afrekenen/` — P27)

```
┌──────────────────────────────────────────────────────────────┐
│ H1: Afrekenen                                                 │
├──────────────────────────────────────────────────────────────┤
│ TWO-COLUMN (desktop). Single column (mobile).                 │
│ ┌─────────────────────────────────┬────────────────────────┐ │
│ │ FACTUURGEGEVENS (60%)           │ UW BESTELLING (40%)    │ │
│ │                                 │                        │ │
│ │ Voornaam * [______]             │ Product       Subtotaal│ │
│ │ Achternaam * [______]           │ Airfixr 150   €795,00 │ │
│ │ Bedrijf [______]                │                        │ │
│ │ Land * [Nederland ▾]           │ Subtotaal     €795,00 │ │
│ │ Adres * [______]                │ BTW (21%)     €166,95 │ │
│ │ Postcode * [______]             │ Verzending      €X,00 │ │
│ │ Plaats * [______]               │ ───────────────────── │ │
│ │ Telefoon * [______]             │ Totaal        €961,95 │ │
│ │ E-mail * [______]               │                        │ │
│ │                                 │                        │ │
│ │ ☐ Factuur naar ander adres     │                        │ │
│ │                                 │                        │ │
│ │ ☐ Account aanmaken              │                        │ │
│ │                                 │                        │ │
│ │ BESTELLING OPMERKINGEN          │                        │ │
│ │                                 │                        │ │
│ │ BETAALMETHODE                   │                        │ │
│ │ ○ iDEAL                         │                        │ │
│ │ ○ Bancontact                    │                        │ │
│ │ ○ Creditcard                    │                        │ │
│ │ ○ PayPal                        │                        │ │
│ │ ○ Overboeking                   │                        │ │
│ │                                 │                        │ │
│ │ ☐ Ik ga akkoord met de          │                        │ │
│ │   algemene voorwaarden *        │                        │ │
│ │                                 │                        │ │
│ │ [PLAATS BESTELLING — CTA btn]   │                        │ │
│ └─────────────────────────────────┴────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

### 16.5 Order Confirmation (`/afrekenen/order-received/` — P28)

```
┌──────────────────────────────────────────────────────────────┐
│ ┌──────────────────────────────────────────────────────────┐ │
│ │                     [✓ CheckCircle — 64px, green]         │ │
│ │                                                          │ │
│ │     H1: Bedankt voor uw bestelling!                      │ │
│ │                                                          │ │
│ │     Uw bestelling #12345 is ontvangen.                   │ │
│ │     U ontvangt een bevestiging per e-mail.               │ │
│ │                                                          │ │
│ │     ┌──────────────────────────────────────────────┐     │ │
│ │     │ ORDER DETAILS                                │     │ │
│ │     │ Ordernummer:    12345                        │     │ │
│ │     │ Datum:          21 juli 2026                  │     │ │
│ │     │ Totaal:         €961,95 excl. BTW             │     │ │
│ │     │ Betaalmethode:  iDEAL                         │     │ │
│ │     └──────────────────────────────────────────────┘     │ │
│ │                                                          │ │
│ │     [TERUG NAAR WINKEL ←]                                │ │
│ └──────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

---

## 17. Bedankt Page Wireframe (`/bedankt/` — P32)

**Template:** `page.php` (default)
**Goal:** Confirmation after form submission.
**Noindex:** `<meta name="robots" content="noindex, nofollow">`

```
┌──────────────────────────────────────────────────────────────┐
│ ┌──────────────────────────────────────────────────────────┐ │
│ │                     [✓ CheckCircle — 64px, green]         │ │
│ │                                                          │ │
│ │     H1: Bedankt voor uw [bericht / offerte / sollicitatie]│ │
│ │                                                          │ │
│ │     When ?type=contact:                                   │ │
│ │       "Wij nemen zo spoedig mogelijk contact met u op."   │ │
│ │                                                          │ │
│ │     When ?type=offerte:                                   │ │
│ │       "Wij nemen binnen 2 werkdagen contact met u op      │ │
│ │        om uw offerte te bespreken."                       │ │
│ │                                                          │ │
│ │     When ?type=vacature:                                  │ │
│ │       "Wij nemen uw sollicitatie in behandeling en        │ │
│ │        nemen binnen 5 werkdagen contact met u op."        │ │
│ │                                                          │ │
│ │     ┌──────────────────────────────────────────────┐     │ │
│ │     │ H3: Wat kunt u in de tussentijd doen?        │     │ │
│ │     │                                              │     │ │
│ │     │ [Bekijk onze diensten →]                     │     │ │
│ │     │ [Lees onze kennisbank →]                     │     │ │
│ │     │ [Bel ons: 0164-652846]                       │     │ │
│ │     └──────────────────────────────────────────────┘     │ │
│ └──────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

---

## 18. Error Page Wireframes

### 18.1 404 Page (P31)

**Template:** `404.php`
**HTTP Status:** 404

```
┌──────────────────────────────────────────────────────────────┐
│ ┌──────────────────────────────────────────────────────────┐ │
│ │                                                          │ │
│ │           H1: Pagina niet gevonden                       │ │
│ │                                                          │ │
│ │   De pagina die u zoekt bestaat niet of is verplaatst.   │ │
│ │                                                          │ │
│ │   ┌──────────────────────────────────────────────────┐   │ │
│ │   │ [🔍 Zoeken...                            ]       │   │ │
│ │   └──────────────────────────────────────────────────┘   │ │
│ │                                                          │ │
│ │   Of ga naar:                                            │ │
│ │   • Home                                                 │ │
│ │   • Onze Diensten                                        │ │
│ │   • Contact                                              │ │
│ │   • Veelgestelde Vragen                                  │ │
│ │                                                          │ │
│ │   Liever direct contact?                                 │ │
│ │   ☏ 0164-652846                                          │ │
│ │   ✉ info@helderduidelijkschoon.nl                        │ │
│ └──────────────────────────────────────────────────────────┘ │
└──────────────────────────────────────────────────────────────┘
```

### 18.2 500 Error (Server-Level)

**Not WordPress-dependent** (PHP/WP may be down). Static HTML file at server level.

```
┌──────────────────────────────────────────────────────────────┐
│ H1: Technische Storing                                        │
│ Onze excuses voor het ongemak. Er is tijdelijk een technische │
│ storing. Probeer het over een paar minuten opnieuw.           │
│                                                               │
│ U kunt ons bereiken op:                                       │
│ ☏ 0164-652846                                                 │
│ ✉ info@helderduidelijkschoon.nl                               │
└──────────────────────────────────────────────────────────────┘
```

### 18.3 Empty Search Results

```
┌──────────────────────────────────────────────────────────────┐
│ H1: Zoekresultaten voor "[query]"                              │
│                                                               │
│ Geen resultaten gevonden. Probeer een andere zoekterm.        │
│                                                               │
│ Suggesties:                                                   │
│ • Controleer de spelling                                      │
│ • Gebruik een algemenere zoekterm                             │
│ • Bekijk onze diensten overzicht                              │
│                                                               │
│ Of neem contact met ons op:                                   │
│ ☏ 0164-652846                                                 │
│ ✉ info@helderduidelijkschoon.nl                               │
└──────────────────────────────────────────────────────────────┘
```

---

## 19. Mobile Wireframes

### 19.1 Mobile Layout Rules

| Rule | Specification |
|---|---|
| **Single Column** | All content stacks in a single column. No side-by-side layouts (except: header logo + hamburger, form inline fields that fit). |
| **Full-Width CTAs** | All buttons are full-width (`width: 100%`) to maximize tap targets. |
| **Hamburger Menu** | Full-screen overlay. Accordion for dropdowns. See DS-001 §7.3. |
| **Typography** | Headings reduce by one step. Body text remains 16px. |
| **Padding** | Reduced from desktop: `spacing-12` → `spacing-8`. `spacing-8` → `spacing-6`. |
| **Images** | Full-width. No image beside text — always stacked. |
| **Tables** | Horizontal scroll wrapper. Never collapse columns. |
| **Footer** | 1-2 columns. Stacked. |
| **Sticky CTA** | CTA button floats at bottom of screen on service pages and homepage. `position: fixed; bottom: 0; width: 100%; z-index: 900;` |

### 19.2 Mobile-Specific Wireframe Patterns

#### Homepage Mobile

```
┌───────────────────┐
│ [Logo]     ☏ [☰] │  ← Sticky header
├───────────────────┤
│ HERO              │
│ H1 (36px)         │
│ Subtitle (16px)   │
│ [OFFERTE BTN]     │  ← Full-width
├───────────────────┤
│ ONZE DIENSTEN     │
│ [Card 1]          │  ← 1 column
│ [Card 2]          │
│ ...               │
├───────────────────┤
│ WAAROM HDS?       │
│ [USP 1]           │  ← 1 column
│ [USP 2]           │
│ ...               │
├───────────────────┤
│ [CTA BANNER]      │  ← Full-width, stacked
├───────────────────┤
│ FOOTER            │  ← 1-2 columns
│ [Diensten]        │
│ [Over HDS]        │
│ [Contact]         │
│ [Juridisch]       │
│ [Social]          │
│ [© 2026]          │
└───────────────────┘

[OFFERTE AANVRAGEN]   ← Sticky bottom CTA
```

#### Service Page Mobile

```
┌───────────────────┐
│ [Logo]     ☏ [☰] │
├───────────────────┤
│ Breadcrumb        │
│ HERO              │
│ H1 (36px)         │
│ [OFFERTE BTN]     │
├───────────────────┤
│ INTRO TEXT        │
├───────────────────┤
│ ONZE AANPAK       │
│ [Image]           │  ← Full-width
│ [Text]            │
├───────────────────┤
│ WAT DOEN WE?      │
│ ✓ Item 1          │
│ ✓ Item 2          │
├───────────────────┤
│ GERELATEERD       │
│ [Card 1]          │  ← 1 column
│ [Card 2]          │
├───────────────────┤
│ FAQ (optional)    │
├───────────────────┤
│ [CTA BANNER]      │
├───────────────────┤
│ FOOTER            │
└───────────────────┘

[OFFERTE AANVRAGEN]   ← Sticky bottom CTA
```

#### Contact Page Mobile

```
┌───────────────────┐
│ [Logo]     ☏ [☰] │
├───────────────────┤
│ Breadcrumb        │
│ H1: Contact       │
│                   │
│ FORM (full-width) │
│ Naam *            │
│ [________]        │
│ E-mail *          │
│ [________]        │
│ Bericht *         │
│ [________]        │
│ ☐ Privacy *       │
│ [VERSTUUR]        │
│                   │
│ CONTACT INFO      │
│ ☏ 0164-652846     │
│ ✉ info@...        │
│ KVK: ...          │
│ BTW: ...          │
├───────────────────┤
│ [CTA BANNER]      │
├───────────────────┤
│ FOOTER            │
└───────────────────┘
```

---

## 20. Component Mapping — Wireframe Sections to Design System Components

Every wireframe section maps to a reusable Design System component (DS-001).

| Wireframe Section | DS-001 Component | Type | Notes |
|---|---|---|---|
| **Header** | Header | Template Part | Logo, nav, phone, email, cart |
| **Mobile Menu** | Mobile Navigation Overlay | Template Part | Hamburger toggle → accordion overlay |
| **Breadcrumb** | Breadcrumbs | Template Part + Plugin | Visible + schema |
| **Hero (Homepage)** | Hero Section | Block Pattern + Block Editor | H1, subtitle, CTA, background |
| **Hero (Service Page)** | Hero Section + Custom Fields | Page Template | H1, `hds_subtitle`, `hds_hero_image`, CTA |
| **Service Card Grid** | Service Card Grid | Block Pattern using `hds/service-card` | 7 cards, ordered by `menu_order` |
| **USP Grid** | USP Grid | Block Pattern | 6 cards, icon + title + text |
| **Client Logo Carousel** | Client Logo Carousel | Block Pattern | Conditional — hidden if empty |
| **Testimonial Block** | Testimonial Block | Block Pattern using `hds/testimonial` | Conditional — hidden if empty |
| **CTA Banner** | CTA Banner | Block Pattern `is-style-banner` | Always visible |
| **Content with Image** | Content with Image | Block Pattern | Text + image, alternating sides |
| **Service Icon List** | Service Icon List | Block Pattern | Checkmark bullets |
| **Cross-Sell Services** | Cross-Sell Services | Block Pattern using `hds/service-card` | 2-3 related service cards |
| **FAQ Accordion** | FAQ Accordion | Yoast/Rank Math FAQ Block | FAQPage schema auto |
| **Contact Form (GF-1)** | Contact Form | Gravity Forms + Page Template | 9 fields, AJAX, reCAPTCHA |
| **Quote Form (GF-2)** | Quote Form | Gravity Forms + Page Template | 13 fields, file upload |
| **Vacancy Application (GF-3)** | Vacancy Application Form | Gravity Forms | 6 fields, CV upload |
| **Vacancy Card** | Job Vacancy Card | Block Pattern using `hds/job-listing` | Toggle expand, JobPosting schema |
| **Download Card** | Download Card List | Block Pattern | Icon + filename + size + download btn |
| **Blog Card** | Blog Card | Standard WP Block composition | Image + date + title + excerpt |
| **Blog Grid** | Latest Blog Posts | Block Pattern | 3-column grid |
| **Product Card** | Product Card | WooCommerce template | Image + title + price + add-to-cart |
| **Product Grid** | Product Grid | WooCommerce template | 4-column grid |
| **Contact Info Block** | Contact Info + Map | Block Pattern using `hds/contact-info` | Phone, email, address, KVK, BTW, social |
| **404 Content** | 404 Content | Block Pattern + `404.php` | Heading, search, links, contact |
| **Bedankt Content** | Dynamic Content | `page.php` + PHP logic | Dynamic heading based on `?type=` param |
| **Footer** | Footer | Template Part | 5-column, Customizer values |
| **Cookie Banner** | Cookie Consent | Complianz Premium plugin | Per-category consent |
| **Legal Page Content** | Legal Template | Page Template | Rich text, "Laatst bijgewerkt" |
| **Social Share / OG Image** | Open Graph Image | Rank Math Pro | 1200×630px branded image |
| **Map Embed** | Contact Info + Map | Block Pattern (conditional) | Consent placeholder wrapper |

---

## 21. Traceability

### 21.1 Wireframe Section → Functional Requirements

| Wireframe Section | FS-001 Reference | REQ-FR ID |
|---|---|---|
| Homepage Hero | §4.1 | REQ-FR-013 |
| Service Card Grid | §4.1 | REQ-FR-011, REQ-FR-012 |
| USP Grid | §4.1 | REQ-FR-013 |
| Client Logo Carousel | §4.1, §4.5 | REQ-FR-041 |
| Testimonials | §4.1, §4.5 | REQ-FR-041..043 |
| CTA Banner | §4.1 | REQ-FR-013 |
| Service Area | §4.1 | REQ-FR-013 |
| Latest Blog Posts | §4.1 | — |
| Service Page (all sections) | §4.2 | REQ-FR-004..010 |
| Category Landing | §4.3 | REQ-FR-011, REQ-FR-012 |
| About Pages | §4.4 | REQ-FR-014, REQ-FR-015 |
| Referenties | §4.5 | REQ-FR-041..043 |
| Vacatures | §4.6 | REQ-FR-044, REQ-FR-045 |
| Downloads | §4.7 | REQ-MIG-006..008 |
| Contact Page | §4.8 | REQ-FR-001..003 |
| Offerte Page | §4.8 | REQ-FR-019 |
| Bedankt Page | §4.9 | REQ-FR-017 |
| Legal Pages | §4.19 | REQ-FR-046..048 |
| Blog | §4.20 | — |
| 404 Page | §4.17 | REQ-FR-016 |
| Search | §4.18 | REQ-FR-018 |
| WooCommerce | §4.10 | REQ-FR-022..027 |

### 21.2 Wireframe Section → RTM Requirements

| Wireframe Section | RTM Coverage |
|---|---|
| All pages | REQ-SEO-001..021 (metadata per page), REQ-SEO-022 (sitemap), REQ-ACC-001..018 (accessibility) |
| Homepage | BR-001..003, 006..008, 011, 013, 014, 016 |
| Service Pages | BR-001, 002, 007, 008, 013, 014 |
| Contact + Offerte | BR-001, 008, 014 |
| Referenties | BR-006, 015 |
| Vacatures | BR-005 |
| Legal Pages | BR-009, 018 |
| Blog | BR-013 |

### 21.3 Wireframe Section → User Journeys

| Wireframe Section | User Journey (SRC-08) |
|---|---|
| Homepage Hero + Service Grid | Journey A (Facility Manager, Step 2) |
| Reguliere Schoonmaak page | Journey A, Step 3 (was BROKEN — now FIXED) |
| VVE Service page | Journey B (VvE Board Member) |
| Oplevering Schoonmaak page | Journey C (Construction PM) |
| Vloeronderhoud page | Journey D (School Administrator) |
| Industriele Schoonmaak page | Journey E (Factory Manager) |
| Vacatures page | Journey F (Job Seeker) |
| Winkel + Product pages | Journey G (Airfixr Buyer) |
| Contact page | Journey A-E, Step 4 (was BROKEN — now FIXED) |
| Header Phone Number | Journey H (Existing Client) |

### 21.4 Wireframe Section → SEO Requirements

| Wireframe Section | SEO Requirement |
|---|---|
| Every page | Unique `<title>` (50-60 chars), unique `<meta description>` (150-160 chars), self-referencing canonical, Open Graph + Twitter Card tags |
| Homepage | LocalBusiness schema, Organization schema |
| Service Pages | Service schema (per page), primary + secondary keywords in H1/H2/content |
| FAQ Page | FAQPage schema (auto-generated from FAQ blocks) |
| Vacatures Page | JobPosting schema (per vacancy) |
| Blog Posts | Article schema (Rank Math auto), category taxonomy |
| Product Pages | Product schema (WooCommerce auto) |
| 404 Page | No index. Return true 404 HTTP status. |
| Bedankt Page | `noindex, nofollow` meta tag |
| Redirects (old URLs) | 7 × 301 redirects + 2 × 410 Gone. Zero redirect chains. |

---

## 22. Acceptance Criteria

### 22.1 Wireframe Completeness

| # | Criterion | Pass Condition |
|---|---|---|
| AC-UX01 | Every page in the sitemap (32 pages) has a wireframe specification | 32/32 wireframes documented above or referenced as standard templates |
| AC-UX02 | Every page section has a stated purpose | Purpose documented for all sections |
| AC-UX03 | Every page section maps to a Design System component | Mapping table §20 covers all sections |
| AC-UX04 | Every interactive element has defined behavior | Forms: validation + submit behavior. Navigation: open/close. Accordion: toggle. Cards: click. |

### 22.2 Mobile-First

| # | Criterion | Pass Condition |
|---|---|---|
| AC-UX05 | Mobile wireframe specified for every page type | Homepage, Service, Contact, Blog, Shop mobile layouts documented |
| AC-UX06 | All CTAs full-width on mobile | Specified for every CTA component |
| AC-UX07 | Touch targets ≥ 44px | Specified for all interactive elements |
| AC-UX08 | No horizontal scroll on mobile | Single-column layout specified for all pages |

### 22.3 Conversion Architecture

| # | Criterion | Pass Condition |
|---|---|---|
| AC-UX09 | Primary CTA visible above the fold on every conversion page | Hero CTA on Homepage, Service pages, Category Landings |
| AC-UX10 | Secondary CTA at bottom of content pages | CTA Banner on all applicable pages |
| AC-UX11 | Phone number visible on every page | Header phone number (desktop: icon + number; mobile: icon, tap-to-call) |
| AC-UX12 | Contact form fallback text present | "Lukt het niet? Bel 0164-652846" below every form |

### 22.4 Accessibility (Structural)

| # | Criterion | Pass Condition |
|---|---|---|
| AC-UX13 | Skip-to-content link on every page | Specified in global layout skeleton |
| AC-UX14 | H1 → H2 → H3 hierarchy, no skips | Enforced in wireframe section specifications |
| AC-UX15 | Semantic landmarks (header, nav, main, footer) | Specified in global layout |
| AC-UX16 | All form fields have visible labels | Specified in form wireframes |
| AC-UX17 | All images have alt text placeholders | `alt="[Client Name]"`, `alt="[Product Name]"` patterns specified |

### 22.5 Content Coverage

| # | Criterion | Pass Condition |
|---|---|---|
| AC-UX18 | Minimum word counts specified per page | 300+ (service), 500+ (category landing, about, blog post), 150+ (contact, legal) |
| AC-UX19 | Empty states defined for all conditional sections | Logos, testimonials, blog posts, vacancies, search, downloads |
| AC-UX20 | H1 text specified for every page | Documented in each page's wireframe |

### 22.6 Traceability

| # | Criterion | Pass Condition |
|---|---|---|
| AC-UX21 | Every wireframe section maps to a functional requirement | Mapping table §21.1 |
| AC-UX22 | Every wireframe section maps to an RTM requirement | Mapping table §21.2 |
| AC-UX23 | Every wireframe section maps to a User Journey step | Mapping table §21.3 |
| AC-UX24 | Every wireframe section maps to an SEO requirement | Mapping table §21.4 |
| AC-UX25 | Every wireframe section maps to a Design System component | Mapping table §20 |

---

*End of UX Wireframes Specification — UXW-001 v1.0.0*

*This specification is structurally complete. A UI designer may now create high-fidelity mockups following the layouts defined above, using the Design System Specification (DS-001) for visual tokens.*
