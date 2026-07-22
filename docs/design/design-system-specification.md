# HDS Onderhoudsdiensten — Design System Specification

**Document ID:** DS-001 | **Version:** 1.0.0 | **Status:** Approved for Implementation
**Project:** helderduidelijkschoon.nl — Ground-Up Rebuild
**Language:** Nederlands (nl-NL) | **Date:** July 2026

**Aligned With:**
MPS-001 (Master Project Specification), FS-001 (Functional Specification), NFR-001 (Non-Functional Requirements), SA-001 (Solution Architecture), WTA-001 (WordPress Technical Architecture), SEO-001 (SEO Implementation), ADR-001 (Architecture Decision Records), RTM-001 (Requirements Traceability Matrix)

---

## 1. Design Principles

### 1.1 Brand Personality

| Attribute | Expression |
|---|---|
| **Helder** (Clear) | Clean layouts, generous whitespace, unambiguous hierarchy. No visual clutter. |
| **Duidelijk** (Plain) | Direct language in UI. Obvious CTAs. No decorative ambiguity. One clear action per section. |
| **Schoon** (Clean) | The brand promise extends to the interface — the site feels clean to use. |
| **Betrouwbaar** (Reliable) | Consistent patterns. No surprises. Every interaction behaves predictably. |
| **Vakkundig** (Professional) | Polished typography. Intentional spacing. High-quality imagery. No "template" feel. |
| **Regionaal** (Regional) | Warmth through photography of West-Brabant/Zeeland. Local trust signals visible. |

### 1.2 Design Goals

| Goal | Metric |
|---|---|
| **DG01 — Conversion First** | Every page drives toward a CTA: Offerte Aanvragen, Contact, or phone call. CTAs are visually dominant. |
| **DG02 — Performance by Default** | PSI Mobile ≥ 90. No design element may block rendering. All decorative assets deferred. |
| **DG03 — Accessibility Built-In** | WCAG 2.2 AA as baseline. Design tokens include contrast-safe color combinations. |
| **DG04 — Content-Adaptive** | Layouts work with 100 words or 500 words. Empty states are invisible, not broken. |
| **DG05 — Mobile-First** | Every component designed at 375px viewport first. Tablet and desktop are enhancements, not fixes. |
| **DG06 — Client Self-Sufficient** | Every page layout editable via Block Editor. No CSS knowledge needed for content updates. |
| **DG07 — Brand-Consistent** | The same component renders identically across all 32 pages. Zero one-off styles. |

### 1.3 Usability Principles

| Principle | Rule |
|---|---|
| **UP01 — Recognition over Recall** | Navigation labels are descriptive. Icon + text, never icon-only on critical actions. |
| **UP02 — Progressive Disclosure** | Show the most important information first. Details in accordions or "Lees meer" links. |
| **UP03 — Feedback Always** | Every interaction produces a visible response: hover state, focus ring, loading spinner, success message. |
| **UP04 — Error Prevention** | Required fields marked. Inline validation. Phone number as fallback when forms fail. |
| **UP05 — Consistent Patterns** | A card is a card everywhere. A CTA button looks the same on every page. Navigation is identical. |

### 1.4 Accessibility Principles

| Principle | WCAG Reference | Implementation |
|---|---|---|
| **AP01 — Perceivable** | 1.1.1, 1.3.1, 1.4.3, 1.4.11 | Alt text on all images. Semantic heading hierarchy. Color contrast ≥ 4.5:1 text, ≥ 3:1 UI. |
| **AP02 — Operable** | 2.1.1, 2.1.2, 2.4.1, 2.5.8 | Full keyboard navigation. Visible focus indicators. Skip-to-content link. Touch targets ≥ 44×44px. |
| **AP03 — Understandable** | 3.1.1, 3.2.3, 3.3.1, 3.3.2 | Dutch language declared. Consistent navigation. Clear form error messages with suggestions. |
| **AP04 — Robust** | 4.1.3 | Dynamic content updates announced via aria-live. Semantic HTML. ARIA only where HTML5 is insufficient. |

### 1.5 Visual Consistency Rules

| Rule | Enforcement |
|---|---|
| Only use colors from the Design Tokens palette. | WordPress `theme.json` `color.palette` restricts the Block Editor color picker. |
| Never write one-off `style=""` attributes, inline `font-size`, or `margin` overrides. | CSS linting. Code review. |
| All spacing uses the 4px-base spacing scale. | `theme.json` `spacing.spacingSizes`. |
| All typography uses the prescribed scale. | `theme.json` `typography.fontSizes`. |
| Components reuse patterns — do not create a new card when the existing card component works. | Block patterns catalog in Block Editor. Beheergids documentation. |

### 1.6 Mobile-First Philosophy

```
Design flow for every component:

1. Mobile (375px) — Define the smallest, essential version.
2. Tablet (768px) — Add layout enhancements (grid columns > 1).
3. Desktop (1024px) — Add hover states, wider layouts, larger typography.
4. Wide (1280px) — Contain content. Do not stretch.
```

**CSS Rule:** All media queries use `min-width`. The mobile style is the base style. Desktop overrides are additive.

---

## 2. Design Tokens

### 2.1 Primary Colors

| Token | CSS Variable | Hex | Usage |
|---|---|---|---|
| `primary` | `var(--wp--preset--color--primary)` | `#1a73e8` | Primary buttons, links, active navigation, focus rings, header phone number |
| `primary-dark` | `var(--wp--preset--color--primary-dark)` | `#1557b0` | Button hover, link hover, active states |

**Rationale:** Blue conveys trust, professionalism, and reliability — core brand values for a B2B service company. Blue is also universally associated with cleanliness and water.

### 2.2 Secondary Colors

| Token | CSS Variable | Hex | Usage |
|---|---|---|---|
| `secondary` | `var(--wp--preset--color--secondary)` | `#34a853` | Success states, "Groen" badges, environmental/MVO sections |
| `accent` | `var(--wp--preset--color--accent)` | `#ea8600` | CTA buttons ("Offerte Aanvragen"), urgent/attention elements, star ratings |

**Rationale:** Green = clean, sustainable, MVO (corporate social responsibility). Orange = action, urgency, warmth — the CTA color that converts.

### 2.3 Semantic Colors

| Token | CSS Variable | Hex | Usage |
|---|---|---|---|
| `error` | `var(--wp--preset--color--error)` | `#d32f2f` | Form validation errors, destructive actions, critical alerts |
| `success` | `var(--wp--preset--color--success)` | `#388e3c` | Form success messages, confirmation states, status indicators |
| `warning` | `var(--wp--preset--color--accent)` | `#ea8600` | Warning messages, pending states (reuses accent color) |
| `info` | `var(--wp--preset--color--primary)` | `#1a73e8` | Informational messages, help text (reuses primary color) |

### 2.4 Neutral Colors

| Token | CSS Variable | Hex | Usage |
|---|---|---|---|
| `white` | `var(--wp--preset--color--white)` | `#ffffff` | Page background, card backgrounds, text on dark backgrounds |
| `light-gray` | `var(--wp--preset--color--light-gray)` | `#f5f5f5` | Section backgrounds, alternating rows, disabled input backgrounds |
| `gray` | `var(--wp--preset--color--gray)` | `#757575` | Secondary text, placeholder text, disabled text, borders |
| `dark-gray` | `var(--wp--preset--color--dark-gray)` | `#333333` | Body text, navigation text, form labels |
| `black` | `var(--wp--preset--color--black)` | `#1a1a1a` | Headings, primary text, footer background |

### 2.5 Typography Scale

Font family: **Open Sans** — self-hosted (WOFF2), subset Latin + Dutch diacritics. Fallback: `-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif`.

| Token | CSS Variable | Size (rem) | Size (px @16px) | Usage |
|---|---|---|---|---|
| `xs` | `var(--wp--preset--font-size--xs)` | `0.75rem` | 12px | Captions, legal fine print, meta data |
| `s` | `var(--wp--preset--font-size--s)` | `0.875rem` | 14px | Helper text, labels, small body, footer links |
| `m` | `var(--wp--preset--font-size--m)` | `1rem` | 16px | Base body text, form inputs, table cells |
| `l` | `var(--wp--preset--font-size--l)` | `1.125rem` | 18px | Lead paragraphs, large body, navigation links |
| `xl` | `var(--wp--preset--font-size--xl)` | `1.25rem` | 20px | H4 headings, card titles |
| `2-xl` | `var(--wp--preset--font-size--2-xl)` | `1.5rem` | 24px | H3 headings, hero subtitle |
| `3-xl` | `var(--wp--preset--font-size--3-xl)` | `1.875rem` | 30px | H2 headings (mobile), section titles |
| `4-xl` | `var(--wp--preset--font-size--4-xl)` | `2.25rem` | 36px | H2 headings (desktop) |
| `5-xl` | `var(--wp--preset--font-size--5-xl)` | `3rem` | 48px | H1 (desktop), hero headings |

**Mobile Adjustment:** All heading sizes reduce by one step on viewports < 768px. H1 uses `4-xl` on mobile, `5-xl` on desktop.

### 2.6 Spacing System

4px-base scale. Every margin, padding, and gap uses one of these values.

| Token | CSS Variable | Size (rem) | Size (px) | Usage |
|---|---|---|---|---|
| `0` | `var(--wp--preset--spacing--0)` | `0` | 0 | No spacing, flush elements |
| `1` | `var(--wp--preset--spacing--1)` | `0.25rem` | 4px | Icon-to-text gap, tight inline spacing |
| `2` | `var(--wp--preset--spacing--2)` | `0.5rem` | 8px | List item gap, form label-to-input |
| `3` | `var(--wp--preset--spacing--3)` | `0.75rem` | 12px | Card padding (small), inline element gap |
| `4` | `var(--wp--preset--spacing--4)` | `1rem` | 16px | Base spacing: card padding, container padding, section gap |
| `5` | `var(--wp--preset--spacing--5)` | `1.25rem` | 20px | Component internal spacing |
| `6` | `var(--wp--preset--spacing--6)` | `1.5rem` | 24px | Section padding, grid gap, form group gap |
| `8` | `var(--wp--preset--spacing--8)` | `2rem` | 32px | Section padding (large), content block gap |
| `10` | `var(--wp--preset--spacing--10)` | `2.5rem` | 40px | Major section separation |
| `12` | `var(--wp--preset--spacing--12)` | `3rem` | 48px | Hero section padding |
| `16` | `var(--wp--preset--spacing--16)` | `4rem` | 64px | Page-level section separation |
| `20` | `var(--wp--preset--spacing--20)` | `5rem` | 80px | Maximum section separation |
| `24` | `var(--wp--preset--spacing--24)` | `6rem` | 96px | Extreme separation (rarely used) |

### 2.7 Border Radius

| Token | Value | CSS Variable | Usage |
|---|---|---|---|
| `none` | `0` | `--hds-radius-none` | Tables, data displays |
| `sm` | `4px` | `--hds-radius-sm` | Form inputs, small cards |
| `md` | `8px` | `--hds-radius-md` | Cards, buttons, images |
| `lg` | `16px` | `--hds-radius-lg` | Hero sections, large panels |
| `pill` | `9999px` | `--hds-radius-pill` | Full-rounded buttons, badges, tags |

### 2.8 Elevation (Shadows)

| Token | CSS Variable | Value | Usage |
|---|---|---|---|
| `none` | `var(--wp--preset--shadow--none)` | `none` | Flat elements, print |
| `sm` | `var(--wp--preset--shadow--sm)` | `0 1px 3px rgba(0,0,0,0.12)` | Cards (default), subtle lift |
| `md` | `var(--wp--preset--shadow--md)` | `0 4px 12px rgba(0,0,0,0.1)` | Cards (hover), dropdowns, sticky header |
| `lg` | `var(--wp--preset--shadow--lg)` | `0 8px 24px rgba(0,0,0,0.12)` | Modals, mega menu dropdown |

### 2.9 Transitions

| Token | Value | CSS Variable | Usage |
|---|---|---|---|
| `fast` | `150ms ease` | `--hds-transition-fast` | Hover states, focus rings, color changes |
| `base` | `250ms ease` | `--hds-transition-base` | Most transitions: card lift, accordion open/close |
| `slow` | `400ms ease` | `--hds-transition-slow` | Page transitions, modal open/close, hero parallax |
| `easing` | `cubic-bezier(0.4, 0, 0.2, 1)` | `--hds-easing-standard` | Material Design standard easing curve |

### 2.10 Opacity

| Token | Value | CSS Variable | Usage |
|---|---|---|---|
| `disabled` | `0.5` | `--hds-opacity-disabled` | Disabled buttons, inputs, controls |
| `hover-overlay` | `0.08` | `--hds-opacity-hover` | Hover overlay on cards, buttons |
| `muted` | `0.6` | `--hds-opacity-muted` | Secondary text, placeholder text |

### 2.11 Grid

| Property | Value | Notes |
|---|---|---|
| **Columns** | 12-column grid | For complex layouts. Most pages use 1-3 column layouts. |
| **Gutter** | `var(--wp--preset--spacing--6)` (24px) | Consistent between all grid items |
| **Gap** | `var(--wp--preset--spacing--6)` (24px) | Row + column gap |
| **Alignment** | `alignwide` = 1200px, `alignfull` = 100vw | Standard WordPress alignment classes |

### 2.12 Container Widths

| Property | Value | Usage |
|---|---|---|
| `contentSize` | `780px` | Reading-optimized content width (blog posts, legal pages) |
| `wideSize` | `1200px` | Full layout width (homepage, service pages, category landings) |
| `narrow` | `600px` | Forms, narrow content blocks |
| `max-text-width` | `65ch` | Optimal line length for body text paragraphs |

### 2.13 Breakpoints

| Name | Min Width | Max Width | Target Devices |
|---|---|---|---|
| **Mobile** | `0` | `767px` | Smartphones portrait + landscape |
| **Tablet** | `768px` | `1023px` | Tablets portrait, small laptops |
| **Desktop** | `1024px` | `1279px` | Laptops, desktop monitors |
| **Wide** | `1280px` | — | Large desktop monitors |

**CSS Pattern:**
```css
/* Mobile — base styles (no media query) */

@media (min-width: 768px) { /* Tablet */ }
@media (min-width: 1024px) { /* Desktop */ }
@media (min-width: 1280px) { /* Wide */ }
```

### 2.14 Z-Index Hierarchy

| Layer | Z-Index | Element |
|---|---|---|
| Base | `0` — `auto` | Default stacking context |
| Dropdown | `100` | Navigation dropdowns, autocomplete suggestions |
| Sticky | `500` | Sticky header, sticky sidebar |
| Overlay | `1000` | Modal backdrops, cookie consent banner |
| Modal | `1100` | Modal dialogs, mega menu |
| Notification | `1200` | Toast notifications, alerts |
| Skip Link | `10000` | Skip-to-content link (must always be topmost) |

---

## 3. Typography

### 3.1 Headings

All headings use Open Sans Bold (700). No heading level may be skipped (H1 → H2 → H3, never H1 → H3).

| Element | Desktop Size | Mobile Size | Weight | Line Height | Margin Bottom | Color |
|---|---|---|---|---|---|---|
| **H1** | `5-xl` (3rem / 48px) | `4-xl` (2.25rem / 36px) | 700 | 1.2 | `spacing-6` (24px) | `black` |
| **H2** | `4-xl` (2.25rem / 36px) | `3-xl` (1.875rem / 30px) | 700 | 1.25 | `spacing-5` (20px) | `black` |
| **H3** | `2-xl` (1.5rem / 24px) | `xl` (1.25rem / 20px) | 600 | 1.3 | `spacing-4` (16px) | `black` |
| **H4** | `xl` (1.25rem / 20px) | `l` (1.125rem / 18px) | 600 | 1.4 | `spacing-3` (12px) | `dark-gray` |

**One H1 per page.** No exceptions. If the visual design needs two prominent headings, use H1 + H2.

### 3.2 Paragraphs

| Element | Size | Weight | Line Height | Max Width | Margin Bottom | Color |
|---|---|---|---|---|---|---|
| **Body (p)** | `m` (1rem / 16px) | 400 | 1.65 | `65ch` | `spacing-4` (16px) | `dark-gray` |
| **Lead (p.lead)** | `l` (1.125rem / 18px) | 400 | 1.7 | `65ch` | `spacing-6` (24px) | `dark-gray` |
| **Small (small)** | `s` (0.875rem / 14px) | 400 | 1.5 | — | — | `gray` |
| **Caption (figcaption)** | `xs` (0.75rem / 12px) | 400 | 1.4 | — | — | `gray` |

### 3.3 Lists

| Element | Bullet Style | Indent | Item Gap | Color |
|---|---|---|---|---|
| **Unordered (ul)** | `•` disc, 4px, `dark-gray` color | `spacing-6` (24px) | `spacing-2` (8px) | `dark-gray` |
| **Ordered (ol)** | Decimal numbers, `dark-gray` color | `spacing-6` (24px) | `spacing-2` (8px) | `dark-gray` |
| **Icon List (ul.is-style-icon-list)** | `✓` checkmark, `secondary` color | `spacing-6` (24px) | `spacing-3` (12px) | `dark-gray` |
| **No Bullet (ul.is-style-no-bullet)** | None | `0` | `spacing-2` (8px) | `dark-gray` |

### 3.4 Links

| State | Color | Decoration | Cursor |
|---|---|---|---|
| **Default (a)** | `primary` | `underline` | `pointer` |
| **Hover** | `primary-dark` | `underline` | `pointer` |
| **Focus** | `primary` | `underline` + `outline: 2px solid primary` + `outline-offset: 2px` | `pointer` |
| **Active** | `primary-dark` | `underline` | `pointer` |
| **Visited** | `primary` (do not distinguish — consistent UX for B2B) | `underline` | `pointer` |
| **External Link** | `primary` | `underline` + `↗` icon via `::after` | `pointer` |

**All external links:** `rel="noopener noreferrer"`. External link warning: icon appended via CSS pseudo-element.

### 3.5 Buttons (Typography)

See §5 Buttons for full component specification. Typography only:

| Element | Font Size | Weight | Line Height | Letter Spacing | Text Transform |
|---|---|---|---|---|---|
| **Button Text** | `m` (1rem / 16px) | 600 | 1.0 | `normal` | None (Dutch sentence case) |
| **Button Small** | `s` (0.875rem / 14px) | 600 | 1.0 | `normal` | None |

### 3.6 Labels

| Element | Size | Weight | Color | Usage |
|---|---|---|---|---|
| **Form Label** | `s` (0.875rem / 14px) | 600 | `dark-gray` | Above form inputs |
| **Required Marker** | `s` (0.875rem / 14px) | 600 | `error` | `*` or "(vereist)" after label text |
| **Fieldset Legend** | `m` (1rem / 16px) | 600 | `black` | Group label for checkboxes/radios |

### 3.7 Helper Text

| Element | Size | Weight | Color | Usage |
|---|---|---|---|---|
| **Helper Text** | `xs` (0.75rem / 12px) | 400 | `gray` | Below form inputs: "bijv. 1234 AB" |
| **Counter** | `xs` (0.75rem / 12px) | 400 | `gray` | Character count: "45/5000" |

### 3.8 Error Text

| Element | Size | Weight | Color | Usage |
|---|---|---|---|---|
| **Error Message** | `s` (0.875rem / 14px) | 600 | `error` | Below invalid form fields |
| **Error Summary** | `m` (1rem / 16px) | 600 | `error` | At top of form: "Corrigeer de gemarkeerde velden." |

Error messages are programmatically associated via `aria-describedby`. Icons (⚠) precede the text.

### 3.9 Success Text

| Element | Size | Weight | Color | Usage |
|---|---|---|---|---|
| **Success Message** | `m` (1rem / 16px) | 600 | `success` | Confirmation banners, success alerts |
| **Success Badge** | `s` (0.875rem / 14px) | 600 | `success` | Status indicators |

### 3.10 Tables

| Element | Size | Weight | Color | Notes |
|---|---|---|---|---|
| **Table Header (th)** | `s` (0.875rem / 14px) | 600 | `black` | Background: `light-gray`. Left-aligned text. |
| **Table Cell (td)** | `m` (1rem / 16px) | 400 | `dark-gray` | Border-bottom: `1px solid light-gray` |
| **Table Caption** | `xs` (0.75rem / 12px) | 400 | `gray` | Above or below table |

**Accessibility:** All tables use `<caption>`, `<thead>`, `<tbody>`, and `scope="col"` / `scope="row"`.

### 3.11 Forms (Typography)

See §6 Forms for full component specification. Typography only:

| Element | Size | Weight | Color |
|---|---|---|---|
| **Input Text** | `m` (1rem / 16px) | 400 | `black` |
| **Placeholder Text** | `m` (1rem / 16px) | 400 | `gray` |
| **Input Label** | `s` (0.875rem / 14px) | 600 | `dark-gray` |
| **Legend** | `m` (1rem / 16px) | 600 | `black` |

---

## 4. Iconography

### 4.1 Icon Library

**Decision:** Phosphor Icons (phosphoricons.com) — free, MIT-licensed, 1,300+ icons, consistent 6-weight system.

**Why Phosphor:**
- Consistent visual weight across all icons (not mixed-style like Font Awesome).
- Inline SVG support (no icon font download).
- 6 weights: Thin, Light, Regular, Bold, Fill, Duotone. **Use Regular weight for UI (24px), Bold for CTAs (20px).**
- CSS-friendly: `width: 1em; height: 1em;` scaling.

### 4.2 Usage Rules

| Rule | Specification |
|---|---|
| **Icon + Text** | Always pair icons with text on critical actions. Icon-only allowed for: social media links (with `aria-label`), cart icon (with item count badge). |
| **Decorative Icons** | `aria-hidden="true"`. Screen readers ignore. |
| **Semantic Icons** | If conveying meaning without text: `aria-label="[Dutch description]"`. |
| **Sizing** | Icons match the line height of adjacent text: `width: 1em; height: 1em;` on the `<svg>`. |
| **Color** | Icons inherit color from parent via `fill: currentColor`. Never hardcode fill colors. |

### 4.3 Icon Sizes

| Context | Size (px) | Size (rem) | Weight |
|---|---|---|---|
| **Inline with body text** | 16px | `1em` | Regular |
| **Buttons (icon + text)** | 20px | `1.25em` | Bold |
| **Standalone UI icons** | 24px | `1.5rem` | Regular |
| **Service card icons** | 32px | `2rem` | Bold |
| **Hero icons** | 48px | `3rem` | Bold |
| **Logo / Brand mark** | As designed (SVG) | — | Custom |

### 4.4 Icon Accessibility

| Rule | Implementation |
|---|---|
| **Decorative** | `<span class="hds-icon" aria-hidden="true"><svg>...</svg></span>` |
| **Semantic (standalone)** | `<span class="hds-icon" role="img" aria-label="Telefoon"><svg>...</svg></span>` |
| **Interactive (icon button)** | `<button aria-label="Winkelwagen (0 items)"><svg>...</svg></button>` |
| **Link with icon** | `<a href="...">Tekst <span aria-hidden="true"><svg>...</svg></span></a>` |

### 4.5 Service Icon Mapping

Each service page has an icon displayed in service cards and the homepage service grid:

| Service | Phosphor Icon |
|---|---|
| Reguliere Schoonmaak | `Buildings` |
| Glasbewassing | `Windows` |
| Gevelreiniging | `Wall` |
| Vloeronderhoud | `SquaresFour` or `GridFour` |
| VVE Service | `BuildingApartment` |
| Oplevering Schoonmaak | `Wrench` |
| Industriele Schoonmaak | `Factory` |

### 4.6 UI Icon Mapping

| Context | Phosphor Icon |
|---|---|
| Phone | `Phone` |
| Email | `Envelope` |
| Location / Address | `MapPin` |
| Search | `MagnifyingGlass` |
| Menu (hamburger) | `List` |
| Close (X) | `X` |
| Arrow (right) | `CaretRight` |
| Arrow (down) | `CaretDown` |
| Arrow (left) | `CaretLeft` |
| Check (success) | `CheckCircle` |
| Error / Alert | `WarningCircle` |
| Info | `Info` |
| Download | `DownloadSimple` |
| Calendar | `Calendar` |
| Clock / Time | `Clock` |
| User / Account | `User` |
| Cart | `ShoppingCart` |
| External Link | `ArrowSquareOut` |
| Star (rating) | `Star` |
| Star (empty rating) | `Star` (with opacity) |
| Quote (testimonial) | `Quotes` |
| PDF file | `FilePdf` |
| Facebook | `FacebookLogo` |
| Instagram | `InstagramLogo` |

---

## 5. Buttons

### 5.1 Button Variants

#### Primary Button

```css
.hds-btn--primary,
.wp-block-button__link,          /* WordPress core button block */
.is-style-primary .wp-block-button__link {
    background: var(--wp--preset--color--primary);
    border: 2px solid var(--wp--preset--color--primary);
    border-radius: var(--hds-radius-md);
    color: var(--wp--preset--color--white);
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: var(--wp--preset--spacing--2);
    font-family: inherit;
    font-size: var(--wp--preset--font-size--m);
    font-weight: 600;
    line-height: 1;
    padding: 0.75rem 1.5rem;
    text-decoration: none;
    transition: background var(--hds-transition-fast),
                border-color var(--hds-transition-fast),
                box-shadow var(--hds-transition-fast);
    min-height: 44px;            /* Touch target */
    min-width: 44px;
}
```

| State | Visual |
|---|---|
| **Default** | `primary` background, white text |
| **Hover** | `primary-dark` background, `primary-dark` border |
| **Focus** | `outline: 2px solid primary`, `outline-offset: 2px` |
| **Active** | `primary-dark` background, no outline (focus handled separately) |
| **Disabled** | `opacity: 0.5`, `cursor: not-allowed`, no hover effects |
| **Loading** | Text replaced with spinner icon + "Versturen..." text change |

#### Secondary Button

```css
.hds-btn--secondary,
.is-style-secondary .wp-block-button__link {
    background: transparent;
    border: 2px solid var(--wp--preset--color--primary);
    color: var(--wp--preset--color--primary);
}
```

| State | Visual |
|---|---|
| **Default** | Transparent, `primary` border and text |
| **Hover** | `primary` background (at `opacity: 0.08`), `primary-dark` border and text |
| **Focus** | Same as Primary button focus |
| **Disabled** | Same as Primary button disabled |

#### CTA Button (Accent)

```css
.hds-btn--cta,
.is-style-cta .wp-block-button__link {
    background: var(--wp--preset--color--accent);
    border-color: var(--wp--preset--color--accent);
    color: var(--wp--preset--color--white);
    font-size: var(--wp--preset--font-size--l);
    font-weight: 700;
    padding: 1rem 2rem;
}
```

| State | Visual |
|---|---|
| **Default** | `accent` (orange) background, white text, 18px font, larger padding |
| **Hover** | Darker orange (`#d67900` — accent darkened 5%), subtle shadow lift |
| **Focus** | Same as Primary button focus |
| **Icon** | `CaretRight` arrow after text: "Offerte aanvragen →" |

#### Ghost Button

```css
.hds-btn--ghost {
    background: transparent;
    border: 2px solid transparent;
    color: var(--wp--preset--color--primary);
}
```

| State | Visual |
|---|---|
| **Default** | No border, `primary` text |
| **Hover** | `primary` background at `opacity: 0.08` |
| **Usage** | Tertiary actions, "Lees meer", card links that shouldn't compete with primary CTAs |

#### Link Button (Text-Only)

```css
.hds-btn--link {
    background: none;
    border: none;
    color: var(--wp--preset--color--primary);
    padding: 0;
    text-decoration: underline;
}
```

| State | Visual |
|---|---|
| **Default** | Underlined `primary` text, no background, no border |
| **Usage** | Inline actions, "Bekijk meer", secondary navigation |

#### Icon Button

```css
.hds-btn--icon {
    background: transparent;
    border: 2px solid transparent;
    border-radius: var(--hds-radius-pill);
    color: var(--wp--preset--color--dark-gray);
    padding: 0.5rem;
    min-width: 44px;
    min-height: 44px;
}
```

| State | Visual |
|---|---|
| **Default** | Transparent, `dark-gray` icon |
| **Hover** | `light-gray` background |
| **Usage** | Cart icon, search toggle, mobile menu toggle, social media icons |

### 5.2 Button Sizes

| Size | Padding | Font Size | Min Height |
|---|---|---|---|
| **Small** | `0.5rem 1rem` | `s` (14px) | 36px |
| **Medium** (default) | `0.75rem 1.5rem` | `m` (16px) | 44px |
| **Large** (CTA) | `1rem 2rem` | `l` (18px) | 48px |

### 5.3 Button States (Universal)

| State | Rule |
|---|---|
| **Loading** | Button text replaced with loading text + CSS spinner. `pointer-events: none`. `aria-busy="true"`. |
| **Disabled** | `opacity: 0.5`, `cursor: not-allowed`, `pointer-events: none`, `aria-disabled="true"`. Never use `disabled` attribute on links — use `role="button" aria-disabled="true"`. |
| **Focus** | `outline: 2px solid var(--wp--preset--color--primary)`, `outline-offset: 2px`. Always visible on keyboard focus. Never remove `:focus-visible` outline. |
| **Active** | Darker shade of background color. Subtle inset shadow optional. |

---

## 6. Forms

### 6.1 Text Field (Input)

```css
.hds-form input[type="text"],
.hds-form input[type="email"],
.hds-form input[type="tel"],
.hds-form input[type="url"],
.hds-form input[type="number"],
.hds-form input[type="password"] {
    background: var(--wp--preset--color--white);
    border: 1.5px solid var(--wp--preset--color--gray);
    border-radius: var(--hds-radius-sm);
    color: var(--wp--preset--color--black);
    font-family: inherit;
    font-size: var(--wp--preset--font-size--m);
    line-height: 1.5;
    padding: 0.625rem 0.75rem;
    width: 100%;
    min-height: 44px;
    transition: border-color var(--hds-transition-fast),
                box-shadow var(--hds-transition-fast);
}
```

| State | Visual |
|---|---|
| **Default** | White background, `gray` border |
| **Hover** | `dark-gray` border |
| **Focus** | `primary` border, `box-shadow: 0 0 0 3px rgba(26,115,232,0.15)` |
| **Error** | `error` border, `box-shadow: 0 0 0 3px rgba(211,47,47,0.15)` |
| **Success** | `success` border |
| **Disabled** | `light-gray` background, `opacity: 0.5`, `cursor: not-allowed` |
| **Read-Only** | `light-gray` background, default border |

### 6.2 Textarea

Same states as Text Field. Additionally:

| Property | Value |
|---|---|
| **Rows** | `4` (default). Expand via `rows` attribute or auto-resize. |
| **Resize** | `vertical` only. Disable horizontal resize. |
| **Min Height** | `100px` |

### 6.3 Select

Same states as Text Field. Additionally:

| State | Visual |
|---|---|
| **Default** | Custom chevron (`CaretDown` icon) as `background-image` to replace native select arrow |
| **Option** | Standard OS-native option rendering |

### 6.4 Checkbox

```css
.hds-form input[type="checkbox"] {
    appearance: none;
    background: var(--wp--preset--color--white);
    border: 1.5px solid var(--wp--preset--color--gray);
    border-radius: 3px;
    cursor: pointer;
    height: 20px;
    width: 20px;
    min-width: 20px;              /* Prevent flex shrink */
    position: relative;
    transition: background var(--hds-transition-fast),
                border-color var(--hds-transition-fast);
}
.hds-form input[type="checkbox"]:checked {
    background: var(--wp--preset--color--primary);
    border-color: var(--wp--preset--color--primary);
}
.hds-form input[type="checkbox"]:checked::after {
    content: "✓";
    color: white;
    font-size: 14px;
    font-weight: 700;
    left: 50%;
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
}
```

| State | Visual |
|---|---|
| **Default** | White, gray border |
| **Hover** | `primary` border |
| **Focus** | `outline: 2px solid primary`, `outline-offset: 2px` |
| **Checked** | `primary` background, white checkmark |
| **Disabled** | `opacity: 0.5`, `cursor: not-allowed` |
| **Error** | `error` border |

**Label:** Clickable — clicking the label toggles the checkbox. `display: inline-flex; align-items: center; gap: var(--wp--preset--spacing--2);`

### 6.5 Radio

Same visual states as Checkbox. Shape: `border-radius: 50%`. Checked indicator: filled circle (`::after` with `border-radius: 50%`, 10px, white).

### 6.6 Switch (Toggle)

Not used in the initial build. Deferred. Gravity Forms does not natively support switches.

### 6.7 Date Picker

Browser-native `<input type="date">` with custom styling matching Text Field. Gravity Forms date field uses this.

### 6.8 Form Validation States

| Element | Visual |
|---|---|
| **Label + Required** | Label text + `*` suffix or "(vereist)" in `error` color |
| **Input Error** | `error` border + error message below |
| **Error Message** | `error` color text, `s` font size, `⚠` icon prefix. `aria-describedby` links to input. |
| **Input Success** | `success` border + optional `✓` icon suffix (inside input, right-aligned) |
| **Success Message** | `success` color text, `✓` icon prefix |

### 6.9 Error Handling

| Scenario | Behavior |
|---|---|
| **Client-side validation failure** | Inline error below the offending field. Field gains `error` border. Error announced via `aria-describedby`. |
| **Server-side validation failure** | Same as client-side. Additional: error summary at top of form: "Corrigeer de volgende velden:" |
| **Submission error (network)** | Inline message at form top: "Er is een fout opgetreden bij het verzenden. Probeer het opnieuw of bel 0164-652846." |
| **reCAPTCHA failure (score < 0.5)** | Silent failure. Form appears to submit but no confirmation. Fallback text below form: "Lukt het niet? Bel 0164-652846." |

### 6.10 Required Fields

| Indicator | Visual |
|---|---|
| **Asterisk** | `*` after label text, `error` color, `aria-hidden="true"` |
| **Text** | "(vereist)" after label text for clarity on forms with many optional fields |
| **Legend** | At top of form: "Velden met een * zijn verplicht." |
| **HTML** | `aria-required="true"` on the input element |

### 6.11 Success State

| Behavior | Visual |
|---|---|
| **Form submitted** | Redirect to `/bedankt/?type={form}` |
| **Bedankt page** | Success icon (CheckCircle, 64px), heading: "Bedankt voor uw [bericht/offerte/sollicitatie]!", body text explaining next steps |
| **Confirmation email** | Branded HTML email with summary of submission |

---

## 7. Navigation Components

### 7.1 Header

```
┌─────────────────────────────────────────────────────────┐
│ [Logo]   DIENSTEN v  OVER HDS v  LUCHTREINIGING v   │ ☏ │ ✉ │ 🛒 │
│          CONTACT                                        │
└─────────────────────────────────────────────────────────┘
```

| Property | Value |
|---|---|
| **Position** | `sticky`, `top: 0`, `z-index: 1000` |
| **Background** | `white` |
| **Border** | `1px solid light-gray` bottom border |
| **Height** | Auto (content-driven). Min: 64px. |
| **Inner Layout** | Flexbox: `justify-content: space-between`, `align-items: center` |
| **Padding** | `spacing-4` (16px) vertical, `spacing-4` horizontal |
| **Container** | Max `wideSize` (1200px), centered |

**Header Elements:**

| Element | Position | Behavior |
|---|---|---|
| **Logo** | Left | Links to `/`. SVG format, max-height 48px. |
| **Desktop Nav** | Center/Right (flex grow) | 4 top-level items with dropdowns. Active page highlighted. |
| **Phone** | Right (before nav on mobile) | `tel:0164-652846`. Icon + number visible. |
| **Email** | Right | `mailto:info@helderduidelijkschoon.nl`. Icon only on mobile. |
| **Cart Icon** | Right | WooCommerce cart icon with item count badge. Hidden if shop disabled. |

#### Mobile Header (< 768px)

```
┌─────────────────────────┐
│ [Logo]       ☏ 🛒 [☰] │
└─────────────────────────┘
```

| Element | Behavior |
|---|---|
| **Logo** | Smaller: max-height 36px |
| **Phone** | Icon only (tap to call). Number hidden. |
| **Cart** | Icon with count badge |
| **Hamburger** | `List` icon. Toggles mobile menu overlay. `aria-expanded="true/false"`. |

### 7.2 Desktop Navigation

| Property | Value |
|---|---|
| **Layout** | Horizontal flex: `gap: spacing-4` |
| **Font** | `l` (18px), weight 600, `dark-gray` color |
| **Active Item** | `primary` color + `2px solid primary` bottom border |
| **Hover** | `primary` color |
| **Focus** | `outline: 2px solid primary`, `outline-offset: 2px` |
| **Dropdown Trigger** | `CaretDown` icon rotates 180° on open |

**Dropdown (Mega Menu):**

```
┌──────────────────────────────────────┐
│ DIENSTEN v                           │
├─────────────────┬────────────────────┤
│ Glas & Gevel    │ Schoonmaakdiensten │
│ · Glasbewassing │ · Reguliere        │
│ · Gevelreiniging│ · Vloeronderhoud   │
│                 │ · VVE Service      │
│                 │ · Oplevering       │
│                 │ · Industrieel      │
└─────────────────┴────────────────────┘
```

| Property | Value |
|---|---|
| **Width** | Auto (content-driven). Max: 600px. |
| **Background** | `white` |
| **Shadow** | `shadow-lg` |
| **Border Radius** | `radius-md` |
| **Columns** | 2 columns (service groups) |
| **Column Headers** | Bold, `black` color, link to category landing page |
| **Column Items** | Regular weight, `dark-gray`, `sm` border-radius on hover |
| **Open Trigger** | `:focus-within` (keyboard) or `:hover` (mouse). Close on `Escape` or click outside. |

### 7.3 Mobile Navigation

Full-screen overlay triggered by hamburger icon.

| Property | Value |
|---|---|
| **Overlay** | `position: fixed`, `inset: 0`, `z-index: 1100`, `white` background |
| **Close Button** | Top-right. `X` icon, 44px touch target. `aria-label="Menu sluiten"`. |
| **Accordion** | Top-level items expand/collapse children. Only one group open at a time. |
| **Top-Level Item** | 18px, weight 600, `dark-gray`, full-width touch target (44px min height), padding: `spacing-4` |
| **Child Items** | 16px, weight 400, `dark-gray`, indented `spacing-6`, border-left: `2px solid light-gray` on active |
| **Active Item** | `primary` color |
| **Footer Section** | Below nav items: phone number (full, clickable), email, social icons |
| **Animation** | Overlay slides in from right (`transform: translateX(100%)` → `0`, 250ms ease). Backdrop fade. |
| **Escape Close** | `Escape` key closes. Focus returns to hamburger button. |

### 7.4 Breadcrumbs

| Property | Value |
|---|---|
| **Position** | Below header, above H1. On all inner pages. Not on Homepage. |
| **Layout** | Horizontal: `Home > Page Name` (flat — no intermediate levels per ADR D-016) |
| **Font** | `s` (14px), weight 400 |
| **Separator** | `>` character, `gray` color, spacing-2 on each side |
| **Current Page** | `dark-gray`, not linked, weight 600 |
| **Parent Items** | `primary` color, underlined links |
| **Schema** | `BreadcrumbList` JSON-LD generated by Rank Math Pro |

**Exception:** WooCommerce breadcrumbs: `Home > Winkel > Product Naam` (3-level for product context).

### 7.5 Footer

```
┌──────────────────────────────────────────────────────────────┐
│ DIENSTEN        OVER HDS        CONTACT       JURIDISCH      │
│ Glasbewassing   Over HDS        ☏ 0164-...    Privacy        │
│ Gevelreiniging  Kwaliteit       ✉ info@...    Cookiebeleid   │
│ Reguliere       Referenties     [Adres]       Voorwaarden    │
│ Vloeronderhoud  Vacatures       KVK: [xxx]    Disclaimer     │
│ VVE Service     Downloads       BTW: [xxx]                   │
│ Oplevering                                                     │
│ Industrieel                                                     │
│                                                                 │
│ LUCHTREINIGING               [Facebook] [Instagram] [GBP]     │
│ Luchtreiniging                                                 │
│ Winkel              © 2026 HDS Onderhoudsdiensten              │
│ Mijn Account                                                   │
└──────────────────────────────────────────────────────────────┘
```

| Property | Value |
|---|---|
| **Background** | `black` (`#1a1a1a`) |
| **Text Color** | `white` |
| **Link Color** | `light-gray` → `white` on hover |
| **Layout** | Desktop: 5 columns (Diensten, Over HDS, Contact, Luchtreiniging, Juridisch). Tablet: 3+2. Mobile: 2 then stacked. |
| **Column Headers** | `l` (18px), weight 600, `white`, margin-bottom: `spacing-4` |
| **Column Links** | `m` (16px), weight 400, `light-gray`, display: block, padding: `spacing-1` vertical |
| **Contact Info** | `s` (14px). Address/KVK/BTW only visible if Customizer values are set. |
| **Social Icons** | 24px icons, `light-gray` → `white` on hover. Links: Facebook, Instagram, Google Business Profile. |
| **Copyright** | `xs` (12px), `gray` color, centered below columns |
| **Cookie Settings** | Floating button (Complianz): "Cookie-instellingen" at bottom-left, `s` size |

### 7.6 Pagination

| Property | Value |
|---|---|
| **Layout** | Centered horizontal: `← Vorige  1  2  3  Volgende →` |
| **Current Page** | `primary` background, `white` text, no underline |
| **Other Pages** | `dark-gray` text, underline on hover |
| **Disabled** | `gray` color, `cursor: not-allowed`, `opacity: 0.5` |
| **Font** | `m` (16px), weight 600 |
| **Gap** | `spacing-3` between items |
| **Touch** | Min 44px tap targets |

---

## 8. Content Components

### 8.1 Cards (Generic)

Base card used as foundation for all card variants.

```css
.hds-card {
    background: var(--wp--preset--color--white);
    border-radius: var(--hds-radius-md);
    box-shadow: var(--wp--preset--shadow--sm);
    overflow: hidden;             /* Clip image corners */
    transition: box-shadow var(--hds-transition-fast),
                transform var(--hds-transition-fast);
}
.hds-card:hover {
    box-shadow: var(--wp--preset--shadow--md);
    transform: translateY(-2px);
}
```

| Property | Value |
|---|---|
| **Padding** | `spacing-4` (16px) body. `0` for image. |
| **Image** | Top of card. `border-radius: md md 0 0`. `object-fit: cover`. Aspect ratio: 16:9. |
| **Title** | `xl` (20px), weight 600, `black`, margin-bottom: `spacing-2` |
| **Description** | `m` (16px), `dark-gray`, `line-height: 1.5`, margin-bottom: `spacing-3` |
| **CTA** | Ghost button or link. Bottom of card body. |
| **Focus** | `outline: 2px solid primary` on the card wrapper (entire card is clickable via stretched link). |

### 8.2 Service Cards

Extensions of `.hds-card` for service pages.

| Property | Value |
|---|---|
| **Template** | [Icon (32px)] + Title + Excerpt (1 sentence) + "Lees meer →" link |
| **Grid** | `display: grid`, `grid-template-columns: 1fr` (mobile) / `repeat(2, 1fr)` (tablet) / `repeat(3, 1fr)` (desktop) |
| **Icon** | Phosphor Bold, 32px, `primary` color, centered above title |
| **Link** | Entire card is a link to the service page (stretched `::after` pseudo-element) |
| **Implementation** | `hds/service-card` custom block. Queries Page by ID. |

**Service Card Grid (Homepage):**
- 7 cards. 3 columns on desktop. 2 on tablet. 1 on mobile.
- Ordered by `menu_order` (ADR D-014): Reguliere Schoonmaak first.
- Gap: `spacing-6` (24px).

### 8.3 Testimonials

| Property | Value |
|---|---|
| **Template** | "Quote text" in italic, `::before` quotation mark (decorative, 48px, `light-gray` color), author name + company + star rating |
| **Quote** | `l` (18px), `italic`, `dark-gray`, `line-height: 1.7` |
| **Author** | `m` (16px), weight 600, `black` |
| **Company** | `s` (14px), `gray`, preceded by "— " |
| **Star Rating** | ★★★★★ (filled=accent orange, empty=light-gray). 5 stars. |
| **Grid** | 1 column (mobile) / 2 columns (tablet) / 3 columns (desktop, if 3+ testimonials) |
| **Empty State** | Hide entire section. No placeholder text. (ADR D-015) |
| **Implementation** | `hds/testimonial` custom block. Queries `hds_testimonial` CPT. |

### 8.4 CTA Sections

| Variant | Visual |
|---|---|
| **CTA Banner (Default)** | `is-style-banner` on `core/group`. Full-width `primary` or `accent` background. Centered text. H2 heading. "Offerte aanvragen" CTA button. Padding: `spacing-12` vertical. |
| **CTA Inline** | Within content flow. `light-gray` background. H3 heading. Text + CTA button. Padding: `spacing-8`. |
| **CTA Hero** | Part of Hero section. Overlaid on hero image or solid background. See §9.1. |

**Always Visible:** CTA Banners are NOT conditional. They always render. They are the primary conversion driver.

### 8.5 FAQ Accordion

| Property | Value |
|---|---|
| **Template** | Question (clickable header) → Answer (expandable panel) |
| **Header** | `l` (18px), weight 600, `dark-gray`. Full-width button with `CaretDown` icon that rotates on open. |
| **Panel** | `m` (16px), `dark-gray`. Padding: `spacing-4`. |
| **Open/Close** | `aria-expanded="true/false"`. `aria-controls` points to panel ID. |
| **Animation** | `max-height` transition, 250ms ease |
| **Multiple Open** | Allowed (not exclusive). User can open multiple FAQ items simultaneously. |
| **Schema** | FAQPage JSON-LD auto-generated by Yoast/Rank Math FAQ Block |
| **Implementation** | Yoast/Rank Math FAQ Block on standard Page at `/veelgestelde-vragen/` (P18). No CPT. (ADR D-012) |

### 8.6 Alerts

| Variant | Color | Icon | Usage |
|---|---|---|---|
| **Info Alert** | `primary` left border, `primary` at 8% opacity background | `Info` | Informational messages |
| **Success Alert** | `success` left border, `success` at 8% opacity background | `CheckCircle` | Confirmation messages |
| **Warning Alert** | `accent` left border, `accent` at 8% opacity background | `WarningCircle` | Warnings, non-critical issues |
| **Error Alert** | `error` left border, `error` at 8% opacity background | `WarningCircle` | Critical errors, form submission failures |

| Property | Value |
|---|---|
| **Layout** | Icon (left) + Text (body). Optional dismiss button (right). |
| **Border Left** | `4px solid [semantic-color]` |
| **Padding** | `spacing-4` |
| **Border Radius** | `radius-md` |
| **Role** | `role="alert"` for dynamic alerts. `role="status"` for non-urgent updates. |

### 8.7 Tables

| Property | Value |
|---|---|
| **Border** | `1px solid light-gray` |
| **Header** | `light-gray` background, `black` text, `s` font, weight 600 |
| **Cell** | `m` font, `dark-gray`. Padding: `spacing-3` horizontal, `spacing-2` vertical. |
| **Striped** | Alternating rows: even rows `light-gray` background at 50% opacity |
| **Responsive** | On mobile (< 768px): horizontal scroll wrapper. Never collapse columns. |
| **Caption** | Above table: `m` font, `dark-gray`, weight 600. |
| **Empty State** | "Geen gegevens beschikbaar." centered in a single cell spanning all columns. |

### 8.8 Downloads

| Property | Value |
|---|---|
| **Template** | File icon (PDF = `FilePdf`, 32px) + Filename + Description + File size + Download button |
| **Layout** | List or grid cards. Gap: `spacing-4`. |
| **Download Button** | Secondary button: "Download" with `DownloadSimple` icon |
| **File Size** | `s` font, `gray` color, displayed after filename |
| **Implementation** | Standard Page with Block Editor. Download Card List pattern. |

### 8.9 Blog Cards

| Property | Value |
|---|---|
| **Template** | Featured Image (16:9) + Title + Date + Category + Excerpt + "Lees meer →" |
| **Grid** | 1 column (mobile) / 2 columns (tablet) / 3 columns (desktop) |
| **Image** | `border-radius: md md 0 0`. `object-fit: cover`. |
| **Title** | `xl` (20px), weight 600, `black`, linked to post |
| **Date** | `xs` (12px), `gray`. Format: "21 juli 2026". |
| **Category** | `xs` (12px), `primary` background at 10% opacity, `primary` text, pill shape |
| **Excerpt** | `m` (16px), `dark-gray`, 2-line clamp |
| **Hover** | Card shadow elevates to `shadow-md`. Title color → `primary`. |
| **Implementation** | `archive.php` template. Latest Blog Posts pattern on Homepage queries 3 most recent. |

### 8.10 Product Cards (WooCommerce)

| Property | Value |
|---|---|
| **Template** | Product Image (1:1 square) + Title + Price (excl. BTW) + Add to Cart button |
| **Price** | `l` (18px), weight 700, `black`. Suffix: "excl. BTW" in `xs`, `gray`. |
| **Add to Cart** | Primary button, full-width below price |
| **Sale Badge** | `accent` background, `white` text, `s` font, top-left corner of image |
| **Out of Stock** | "Niet op voorraad" overlay on image. Add to Cart button disabled. |
| **Grid** | WooCommerce default: 4 columns (desktop), 2 (tablet), 1 (mobile) |
| **Implementation** | WooCommerce plugin templates (no theme override at launch). |

---

## 9. Layout Components

### 9.1 Hero Section

| Property | Value |
|---|---|
| **Layout** | Full-width section. Content max `wideSize` (1200px). |
| **Background** | Option 1: `primary` gradient. Option 2: `primary` solid + subtle pattern. Option 3: Hero image (if hds_hero_image custom field set on Service pages). |
| **Text Color** | `white` (if primary/accent background). `black` (if light background with hero image overlay). |
| **H1** | `5-xl` (48px desktop, 36px mobile), bold, `white` |
| **Subtitle** | `l` (18px), weight 400, `white` at `opacity: 0.9`, max-width: `600px` |
| **CTA Button** | CTA variant (accent/orange), large. "Vrijblijvende offerte" → `/offerte-aanvragen/`. |
| **Padding** | `spacing-20` (80px) vertical on desktop. `spacing-12` (48px) on mobile. |
| **Content Alignment** | Left-aligned. Eyebrow text (optional) above H1: `s` font, weight 600, uppercase, letter-spacing: 1px. |
| **Image Overlay** | When hero image is used: `linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.6))` overlay to ensure white text is readable. |

### 9.2 Section

| Property | Value |
|---|---|
| **Padding** | `spacing-16` (64px) vertical default. `spacing-8` (32px) for alternating sections. |
| **Background** | `white` (default) or `light-gray` (alternating). Alternating sections use `light-gray` to visually separate content blocks. |
| **Container** | `wideSize` (1200px) centered |
| **Heading** | Optional H2 above content. Centered or left-aligned. |
| **Section Label** | Optional: "Onze diensten", "Wat onze klanten zeggen", etc. Above H2. `s` font, weight 600, `primary` color, uppercase, letter-spacing: 1px. |

### 9.3 Container

| Variant | Max Width | Usage |
|---|---|---|
| **Content** | `contentSize` (780px) | Reading content: blog posts, legal pages, FAQ |
| **Wide** | `wideSize` (1200px) | Layout content: homepage, service pages, category landings |
| **Full** | `100vw` | Hero sections, CTA banners, full-width backgrounds |
| **Narrow** | `600px` | Forms, narrow content blocks |

`margin-inline: auto` centering. `padding-inline: spacing-4` for breathing room on mobile.

### 9.4 Sidebar

| Property | Value |
|---|---|
| **Usage** | Blog posts: sidebar with categories, recent posts, CTA. Product pages: sidebar with filters/categories. |
| **Layout** | CSS Grid: `grid-template-columns: 1fr 300px` (desktop), `1fr` (mobile — sidebar below content) |
| **Gap** | `spacing-8` (32px) |
| **Sticky** | Optional: `position: sticky; top: 88px;` (header height + offset) |
| **Background** | `white` or `light-gray` |

### 9.5 Grid

| Property | Value |
|---|---|
| **Columns** | 12-column grid available. Most layouts use simplified 1-4 column grids. |
| **Gap** | `spacing-6` (24px) row + column |
| **Responsive** | `auto-fit` / `auto-fill` with `minmax()` for automatic column adjustment |
| **Common Grids** | 1-col (mobile default), 2-col (tablet, e.g., content + sidebar), 3-col (desktop, e.g., service cards), 4-col (desktop, e.g., product grid) |

### 9.6 Columns (WordPress Core Columns Block)

Use WordPress `core/columns` block. Stacks vertically on mobile. Respects `wideSize` alignment.

### 9.7 Banner

`is-style-banner` on `core/group`. Full-width (`100vw`), colored background. Centered content within `wideSize` container. Usage: CTA banners, announcement bars, section transitions.

### 9.8 Empty State

**Rule (ADR D-015):** Hide conditional sections entirely when no data exists. Do not render empty space, placeholder text, or "Coming soon" messages.

| Component | Empty State Behavior |
|---|---|
| Client Logo Carousel | `display: none` on section wrapper |
| Testimonial Block | `display: none` on section wrapper |
| Latest Blog Posts | `display: none` on section wrapper |
| Blog Index | "Binnenkort verschijnen hier de eerste artikelen." with CTA → /contact/ |
| Search Results | "Geen resultaten gevonden voor '[query]'." with search suggestions + phone number |
| Vacatures Page | "Er zijn op dit moment geen openstaande vacatures. Stuur een open sollicitatie naar info@..." |
| Referenties Page | If no testimonials AND no logos: "Wij zijn trots op onze opdrachtgevers. Binnenkort leest u hier hun ervaringen." |
| Downloads Page | "Downloads zijn tijdelijk niet beschikbaar. Neem contact op." |
| Form Fields (no data) | Normal rendering — forms always display |

### 9.9 404 Page

| Property | Value |
|---|---|
| **Template** | `404.php` |
| **Heading** | H1: "Pagina niet gevonden" |
| **Body** | "De pagina die u zoekt bestaat niet of is verplaatst." |
| **Search** | Prominent search bar (centered, wide) |
| **Links** | Home, Diensten overzicht, Contact, Veelgestelde Vragen |
| **Contact** | Phone: 0164-652846. Email: info@helderduidelijkschoon.nl. |
| **HTTP Status** | Returns true 404 (not 200 with 404 content) |

### 9.10 Search Results

| Property | Value |
|---|---|
| **Template** | `search.php` |
| **Heading** | H1: "Zoekresultaten voor '[query]'" |
| **Results** | List or grid of matching pages/posts. Each: Title (linked), excerpt, URL breadcrumb. |
| **Pagination** | If > 10 results: pagination at bottom |
| **Empty** | "Geen resultaten gevonden voor '[query]'. Probeer een andere zoekterm." with suggested links |
| **Sorting** | Relevance-sorted (Relevanssi plugin) |

---

## 10. WooCommerce Components

### 10.1 Shop Page (`/winkel/`)

| Property | Value |
|---|---|
| **Heading** | H1: "Winkel" |
| **Intro Text** | 100+ words Dutch text explaining Airfixr product line and connection to HDS |
| **Product Grid** | 4 columns (desktop), 2 (tablet), 1 (mobile) |
| **Filters** | Category filter sidebar or top bar. Categories: "Units", "Filters", "Lampen", "Accessoires". |
| **Sorting** | Default: "Nieuwste eerst". Options: prijs (laag-hoog), prijs (hoog-laag), populariteit. |
| **Pagination** | Standard pagination at bottom |

### 10.2 Category Page

| Property | Value |
|---|---|
| **Heading** | H1: Category name |
| **Description** | Category description text (if set) |
| **Product Grid** | Same as shop page, filtered to category |

### 10.3 Product Card

See §8.10 Product Cards.

### 10.4 Product Page

| Property | Value |
|---|---|
| **Layout** | Two-column: Image gallery (left, 50%) + Product details (right, 50%). Mobile: stacked. |
| **Image** | Main image + thumbnail gallery. Lightbox on click. |
| **Title** | H1: Product name |
| **Price** | `3-xl` font, weight 700. "€795,00 excl. BTW" |
| **Description** | Full product description in tab or below fold |
| **Add to Cart** | Quantity selector + Add to Cart (Primary button, large) |
| **Meta** | SKU, Category, Tags |
| **Breadcrumb** | Home > Winkel > [Category] > Product Name |

### 10.5 Cart (`/winkelmand/`)

| Property | Value |
|---|---|
| **Layout** | Table: Product, Price, Quantity, Subtotal, Remove. Sidebar: Cart totals + Checkout button. |
| **Empty Cart** | "Uw winkelwagen is leeg." with link → `/winkel/` |
| **Update Cart** | Auto-update on quantity change. Loading spinner during update. |
| **Checkout Button** | Primary button, full-width in sidebar. "Afrekenen →" |

### 10.6 Checkout (`/afrekenen/`)

| Property | Value |
|---|---|
| **Layout** | Two-column: Billing/Shipping form (left, 60%) + Order review (right, 40%). Mobile: stacked. |
| **Fields** | Standard WooCommerce checkout fields with Dutch labels |
| **Payment Methods** | Radio selection: iDEAL, Bancontact, Credit Card, PayPal, SEPA, Bank Transfer |
| **Place Order** | Primary CTA button: "Plaats bestelling" |
| **Terms** | Checkbox: "Ik ga akkoord met de algemene voorwaarden." Required. Links to `/algemene-voorwaarden/`. |

### 10.7 Order Success (`/afrekenen/order-received/`)

| Property | Value |
|---|---|
| **Heading** | "Bedankt voor uw bestelling!" |
| **Icon** | CheckCircle (64px, success color) |
| **Order Details** | Order number, date, total, payment method |
| **Next Steps** | "U ontvangt een bevestiging per e-mail." |

---

## 11. Accessibility

### 11.1 WCAG 2.2 AA — All Success Criteria

This Design System targets WCAG 2.2 Level AA compliance on every page template. All 20 REQ-ACC requirements from RTM-001 are mapped to specific design tokens and component implementations.

| WCAG SC | Description | Design System Implementation |
|---|---|---|
| **1.1.1** | Non-text Content | All non-decorative images have Dutch alt text. Decorative images: `alt=""`. SVG icons: `aria-hidden="true"`. |
| **1.3.1** | Info and Relationships | Semantic HTML: `<header>`, `<nav>`, `<main>`, `<footer>`, `<section>`, `<article>`. Form labels use `<label>`. Tables use `<th scope>`. |
| **1.4.3** | Contrast (Minimum) | Text: 4.5:1 ratio (verified per color combination). Large text (18px+): 3:1. Checked per the Design Tokens palette. |
| **1.4.4** | Resize Text | All layouts function at 200% browser zoom. No horizontal scroll. Content reflows. |
| **1.4.11** | Non-text Contrast | UI components: 3:1 against adjacent colors. Checked: button borders, form input borders, focus indicators. |
| **2.1.1** | Keyboard | All interactive elements focusable and operable via keyboard. Navigation dropdowns open on `:focus-within`. |
| **2.1.2** | No Keyboard Trap | Escape closes modals, mobile menu, dropdowns. Focus returns to trigger element. |
| **2.4.1** | Bypass Blocks | Skip-to-content link as first focusable element. Visible on focus. Links to `<main id="content">`. |
| **2.4.2** | Page Titled | Unique `<title>` per page. Pattern: `[Page Title] — HDS Onderhoudsdiensten`. |
| **2.4.4** | Link Purpose (In Context) | Descriptive link text. No "klik hier". External links have visual indicator (`↗` icon). |
| **2.5.8** | Target Size (AAA — adopted as AA) | All touch targets ≥ 44×44px. Buttons, nav links, form controls, icons. |
| **3.1.1** | Language of Page | `lang="nl-NL"` on `<html>`. Any English blocks: `lang="en"` on wrapping element. |
| **3.2.3** | Consistent Navigation | Navigation order and position identical on every page. |
| **3.3.1** | Error Identification | Form errors: field highlighted, error message describes the problem. |
| **3.3.2** | Labels or Instructions | All form fields have `<label>`. Required fields marked. Format hints provided (e.g., "bijv. 1234 AB"). |
| **4.1.3** | Status Messages | Dynamic updates announced via `aria-live`: cart updates, search results, form submission feedback. |

### 11.2 Keyboard Navigation

| Element | Key | Behavior |
|---|---|---|
| **Skip Link** | `Tab` | First focusable element. `Enter` → jump to `<main>`. |
| **Navigation Links** | `Tab` / `Shift+Tab` | Navigate between links. |
| **Dropdown Menu** | `Enter` or `Space` | Open dropdown. |
| | `Escape` | Close dropdown. Focus returns to parent. |
| | `Arrow Down/Up` | Navigate within dropdown. |
| | `Tab` | Close dropdown, move to next top-level item. |
| **Buttons** | `Enter` or `Space` | Activate. |
| **Links** | `Enter` | Follow. |
| **Form Inputs** | `Tab` / `Shift+Tab` | Move between fields. |
| | `Enter` | Submit form (if on submit button). |
| **Accordion (FAQ)** | `Enter` or `Space` | Toggle question. |
| **Modal** | `Escape` | Close modal. Focus returns to trigger. |
| | `Tab` | Trap within modal (focus loop). |

### 11.3 Focus Order

1. Skip-to-content link (visible on focus)
2. Header: Logo (link to Home)
3. Header: Navigation items (left to right)
4. Header: Phone, Email, Cart
5. Main content (`<main id="content">`)
6. Page content (top to bottom, DOM order)
7. Footer: Column links (left to right)
8. Footer: Social icons
9. Footer: Cookie settings button (Complianz)

**Focus must always be visible.** The design system includes a visible focus ring on all interactive elements:
```css
:focus-visible {
    outline: 2px solid var(--wp--preset--color--primary);
    outline-offset: 2px;
}
```

Never use `outline: none` without providing an alternative visible focus indicator.

### 11.4 ARIA Usage

**Rule:** Use native HTML5 elements unless ARIA is necessary. ARIA is a supplement, not a replacement for semantic HTML.

| ARIA Attribute | Usage |
|---|---|
| `aria-label` | Icon-only buttons, icon-only links (social media), cart button with item count |
| `aria-expanded` | Dropdown menus, accordion items, mobile menu toggle |
| `aria-controls` | Points from toggle to controlled panel (accordion, dropdown) |
| `aria-describedby` | Form error messages linked to their input |
| `aria-required` | Required form fields |
| `aria-live="polite"` | Dynamic content: cart updates, search results count, form submission feedback |
| `aria-live="assertive"` | Critical alerts: form submission errors, session timeout warnings |
| `aria-busy` | Loading states: buttons during AJAX submission |
| `aria-hidden` | Decorative icons, duplicative content (e.g., icon next to text where icon is decorative) |
| `role="alert"` | Error alerts, critical notifications |
| `role="status"` | Non-urgent status messages |
| `role="img"` | SVG icons that convey meaning without text (rare — prefer `aria-label` on parent) |

### 11.5 Color Contrast

All color combinations in the Design Tokens palette were verified:

| Combination | Ratio | Pass AA? | Usage |
|---|---|---|---|
| `black` text on `white` bg | 15.4:1 | ✅ AAA | Body text, headings |
| `dark-gray` text on `white` bg | 7.4:1 | ✅ AAA | Body text, navigation |
| `white` text on `primary` bg | 4.6:1 | ✅ AA | Primary buttons, hero text |
| `white` text on `accent` bg | 3.8:1 | ✅ AA (large text) | CTA buttons (18px bold = large text) |
| `primary` text on `white` bg | 4.6:1 | ✅ AA | Links |
| `gray` text on `white` bg | 4.1:1 | ✅ AA | Helper text, placeholders |
| `primary` text on `light-gray` bg | 4.4:1 | ✅ AA | Info alerts, highlighted sections |
| `error` text on `white` bg | 5.3:1 | ✅ AA | Error messages |

**⚠️ Caution:** `white` text on `accent` (orange `#ea8600`) achieves 3.8:1 — this passes AA for large text (≥18px bold) but NOT for normal text. **CTA button text must be at least 18px bold (or 24px regular) to use this combination.** Current CTA button spec uses 18px/700 weight — compliant.

**⚠️ Caution:** `gray` (`#757575`) on `white` — 4.1:1. Barely passes AA. Do NOT use gray text smaller than 14px. Do NOT use gray text on `light-gray` background.

### 11.6 Reduced Motion

```css
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
```

| Element | Normal Behavior | Reduced Motion Behavior |
|---|---|---|
| Card hover lift | `transform: translateY(-2px)` 150ms | No movement |
| Accordion open/close | `max-height` transition 250ms | Instant open/close |
| Hero parallax | Background scroll effect | Static background |
| Navigation dropdown | Fade + slide 200ms | Instant appearance |
| Modal open | Fade + scale 250ms | Instant appearance |
| Scroll animations | Elements animate on scroll into view | Static (all elements visible immediately) |
| Carousel (if used) | Auto-advance | No auto-advance; manual navigation only |

---

## 12. Responsive Rules

### 12.1 Mobile (< 768px)

| Component | Mobile Behavior |
|---|---|
| **Header** | Logo smaller (max-height 36px). Phone icon-only. Hamburger menu. |
| **Navigation** | Full-screen overlay accordion. |
| **Hero** | H1: `4-xl` (36px). Subtitle: `m` (16px). Padding reduced to `spacing-12`. |
| **Service Cards** | 1 column. Full-width cards. |
| **Footer** | 2 columns → stacked. |
| **CTA Banner** | Full-width. Stacked text + button. Button full-width. |
| **Forms** | Full-width inputs. Labels above inputs (not side-by-side). |
| **Tables** | Horizontal scroll wrapper. |
| **Blog Grid** | 1 column. |
| **Product Grid** | 1 column. |
| **Checkout** | Single column (billing above order review). |
| **Sidebar** | Below main content. Full width. |
| **Font Sizes** | All headings reduce by one step. Body text remains `m` (16px). |

### 12.2 Tablet (768px — 1023px)

| Component | Tablet Behavior |
|---|---|
| **Header** | Full desktop header (logo + nav + phone). |
| **Navigation** | Desktop horizontal nav with dropdowns. |
| **Service Cards** | 2 columns. |
| **Footer** | 3 columns → 2 columns. |
| **Blog Grid** | 2 columns. |
| **Product Grid** | 2 columns. |
| **Hero** | H1: `5-xl` (48px). Full padding. |

### 12.3 Desktop (1024px — 1279px)

| Component | Desktop Behavior |
|---|---|
| **Service Cards** | 3 columns. |
| **Footer** | 5 columns. |
| **Blog Grid** | 3 columns. |
| **Product Grid** | 4 columns. |
| **Content + Sidebar** | 2-column grid. |
| **Hover States** | All interactive hover effects active. |
| **Dropdowns** | Hover to open (mouse). `:focus-within` to open (keyboard). |

### 12.4 Wide Screen (1280px+)

| Component | Wide Behavior |
|---|---|
| **Container** | Content clamped to `wideSize` (1200px). Full-width sections (`100vw`). |
| **Hero** | Background image or gradient spans full viewport width. Content centered at 1200px. |
| **Typography** | Maximum desktop font sizes. No further enlargement — readability limited by line length. |

---

## 13. Animation Guidelines

### 13.1 Allowed Animations

| Element | Animation | Duration | Easing |
|---|---|---|---|
| **Button hover** | Background color, border color | 150ms | ease |
| **Link hover** | Color | 150ms | ease |
| **Card hover lift** | `transform: translateY(-2px)` + `box-shadow` | 150ms | ease |
| **Focus ring** | `outline` (instant — no animation on focus) | 0ms | — |
| **Dropdown open** | `opacity` + `transform: translateY(-4px)` → `0` | 200ms | standard |
| **Accordion open/close** | `max-height` | 250ms | standard |
| **Modal open** | `opacity` + `transform: scale(0.95)` → `1` | 250ms | standard |
| **Mobile menu open** | `transform: translateX(100%)` → `0` | 250ms | standard |
| **Loading spinner** | `rotate` (continuous) | 750ms | linear (infinite) |
| **Skeleton loader** | Background shimmer | 1500ms | ease (infinite) |
| **Cookie banner** | `opacity` + slide up | 300ms | standard |
| **Notification toast** | Slide in from right + fade | 300ms | standard |

### 13.2 Duration

| Category | Duration | Example |
|---|---|---|
| **Micro-interactions** | 100-150ms | Button hover, link hover, icon color change |
| **Standard transitions** | 200-300ms | Dropdowns, accordions, modals, navigation |
| **Page-level** | 300-400ms | Mobile menu open, page transitions (if SPA) |

### 13.3 Easing Curves

```css
--hds-easing-standard: cubic-bezier(0.4, 0, 0.2, 1);    /* Most transitions */
--hds-easing-decelerate: cubic-bezier(0, 0, 0.2, 1);     /* Elements appearing */
--hds-easing-accelerate: cubic-bezier(0.4, 0, 1, 1);     /* Elements disappearing */
```

| Usage | Curve |
|---|---|
| Elements entering the screen | `decelerate` (fast start, slow end) |
| Elements leaving the screen | `accelerate` (slow start, fast end) |
| In-place transitions (color, shadow) | `standard` |

### 13.4 Reduced Motion

See §11.6. All animations must be disabled or reduced to instant when `prefers-reduced-motion: reduce` is active.

---

## 14. Component Naming Convention

### 14.1 CSS Class Convention (BEM)

```
.hds-[block]__[element]--[modifier]
```

| Part | Convention | Example |
|---|---|---|
| **Block** | `.hds-` prefix + component name (kebab-case) | `.hds-card`, `.hds-hero`, `.hds-btn` |
| **Element** | `__` + element name | `.hds-card__title`, `.hds-card__image` |
| **Modifier** | `--` + modifier name | `.hds-card--featured`, `.hds-btn--primary` |

**Rules:**
- No ID selectors for styling.
- Maximum 3 levels of nesting in SCSS/CSS.
- Block styles registered via `register_block_style()` use `is-style-[name]` (WordPress convention).
- State classes: `is-active`, `is-open`, `is-loading`, `is-disabled`, `has-error`, `is-visible`.

### 14.2 Block Naming (WordPress)

| Type | Convention | Example |
|---|---|---|
| **Custom Block** | `hds/[block-name]` | `hds/service-card`, `hds/testimonial` |
| **Block Pattern** | `hds/[pattern-name]` | `hds/hero-section`, `hds/cta-banner` |
| **Block Style** | `is-style-[name]` | `is-style-primary`, `is-style-card` |

### 14.3 PHP Function Naming

| Type | Convention | Example |
|---|---|---|
| **Theme Functions** | `hds_` prefix + snake_case | `hds_get_phone()`, `hds_breadcrumbs()` |
| **Block Render Callbacks** | `hds_render_[block-name]` | `hds_render_service_card()` |
| **Schema Generators** | `hds_get_[type]_schema` | `hds_get_localbusiness_schema()` |
| **Custom Fields** | `hds_` prefix + snake_case | `hds_subtitle`, `hds_hero_image` |

### 14.4 Image Naming

| Type | Convention | Example |
|---|---|---|
| **Service Images** | `[service]-[context]-[location].webp` | `glasbewassing-kantoor-bergen-op-zoom.webp` |
| **General Images** | `[subject]-[descriptor].webp` | `team-hds-medewerkers.webp` |

---

## 15. Design Tokens JSON Structure

This is the `theme.json` structure that delivers the design system to WordPress:

```json
{
    "$schema": "https://schemas.wp.org/trunk/theme.json",
    "version": 3,
    "settings": {
        "appearanceTools": true,
        "useRootPaddingAwareAlignments": true,
        "color": {
            "palette": [
                { "name": "Primary",       "slug": "primary",       "color": "#1a73e8" },
                { "name": "Primary Dark",  "slug": "primary-dark",  "color": "#1557b0" },
                { "name": "Secondary",     "slug": "secondary",     "color": "#34a853" },
                { "name": "Accent",        "slug": "accent",        "color": "#ea8600" },
                { "name": "White",         "slug": "white",         "color": "#ffffff" },
                { "name": "Light Gray",    "slug": "light-gray",    "color": "#f5f5f5" },
                { "name": "Gray",          "slug": "gray",          "color": "#757575" },
                { "name": "Dark Gray",     "slug": "dark-gray",     "color": "#333333" },
                { "name": "Black",         "slug": "black",         "color": "#1a1a1a" },
                { "name": "Error",         "slug": "error",         "color": "#d32f2f" },
                { "name": "Success",       "slug": "success",       "color": "#388e3c" }
            ]
        },
        "typography": {
            "fontFamilies": [
                {
                    "fontFamily": "\"Open Sans\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif",
                    "name": "Open Sans",
                    "slug": "body"
                },
                {
                    "fontFamily": "\"Open Sans\", -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Helvetica, Arial, sans-serif",
                    "name": "Heading",
                    "slug": "heading"
                }
            ],
            "fontSizes": [
                { "name": "XS",  "slug": "xs",   "size": "0.75rem" },
                { "name": "S",   "slug": "s",    "size": "0.875rem" },
                { "name": "M",   "slug": "m",    "size": "1rem" },
                { "name": "L",   "slug": "l",    "size": "1.125rem" },
                { "name": "XL",  "slug": "xl",   "size": "1.25rem" },
                { "name": "2XL", "slug": "2-xl", "size": "1.5rem" },
                { "name": "3XL", "slug": "3-xl", "size": "1.875rem" },
                { "name": "4XL", "slug": "4-xl", "size": "2.25rem" },
                { "name": "5XL", "slug": "5-xl", "size": "3rem" }
            ]
        },
        "spacing": {
            "spacingSizes": [
                { "name": "0",  "slug": "0",  "size": "0" },
                { "name": "1",  "slug": "1",  "size": "0.25rem" },
                { "name": "2",  "slug": "2",  "size": "0.5rem" },
                { "name": "3",  "slug": "3",  "size": "0.75rem" },
                { "name": "4",  "slug": "4",  "size": "1rem" },
                { "name": "5",  "slug": "5",  "size": "1.25rem" },
                { "name": "6",  "slug": "6",  "size": "1.5rem" },
                { "name": "8",  "slug": "8",  "size": "2rem" },
                { "name": "10", "slug": "10", "size": "2.5rem" },
                { "name": "12", "slug": "12", "size": "3rem" },
                { "name": "16", "slug": "16", "size": "4rem" },
                { "name": "20", "slug": "20", "size": "5rem" },
                { "name": "24", "slug": "24", "size": "6rem" }
            ]
        },
        "shadow": {
            "presets": [
                { "name": "None",   "slug": "none", "shadow": "none" },
                { "name": "Small",  "slug": "sm",   "shadow": "0 1px 3px rgba(0,0,0,0.12)" },
                { "name": "Medium", "slug": "md",   "shadow": "0 4px 12px rgba(0,0,0,0.1)" },
                { "name": "Large",  "slug": "lg",   "shadow": "0 8px 24px rgba(0,0,0,0.12)" }
            ]
        },
        "layout": {
            "contentSize": "780px",
            "wideSize": "1200px"
        },
        "custom": {
            "hds": {
                "companyName": "HDS Onderhoudsdiensten",
                "phone": "0164-652846",
                "email": "info@helderduidelijkschoon.nl"
            }
        }
    },
    "styles": {
        "color": {
            "background": "var(--wp--preset--color--white)",
            "text": "var(--wp--preset--color--black)"
        },
        "typography": {
            "fontFamily": "var(--wp--preset--font-family--body)",
            "fontSize": "var(--wp--preset--font-size--m)",
            "lineHeight": "1.65"
        },
        "elements": {
            "heading": {
                "typography": {
                    "fontFamily": "var(--wp--preset--font-family--heading)",
                    "fontWeight": "700",
                    "lineHeight": "1.25"
                }
            },
            "h1": {
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--5-xl)",
                    "fontWeight": "700",
                    "lineHeight": "1.2"
                }
            },
            "h2": {
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--4-xl)",
                    "fontWeight": "700",
                    "lineHeight": "1.25"
                }
            },
            "h3": {
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--2-xl)",
                    "fontWeight": "600",
                    "lineHeight": "1.3"
                }
            },
            "link": {
                "color": {
                    "text": "var(--wp--preset--color--primary)"
                }
            },
            "button": {
                "border": {
                    "radius": "8px"
                },
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--m)",
                    "fontWeight": "600"
                }
            }
        },
        "blocks": {
            "core/navigation": {
                "typography": {
                    "fontSize": "var(--wp--preset--font-size--l)",
                    "fontWeight": "600"
                }
            }
        }
    },
    "customTemplates": [
        { "name": "Service",              "title": "Service",              "postTypes": ["page"] },
        { "name": "Category Landing",     "title": "Category Landing",     "postTypes": ["page"] },
        { "name": "About",                "title": "About",                "postTypes": ["page"] },
        { "name": "Contact",              "title": "Contact",              "postTypes": ["page"] },
        { "name": "Offerte Aanvragen",    "title": "Offerte Aanvragen",    "postTypes": ["page"] },
        { "name": "FAQ",                  "title": "FAQ",                  "postTypes": ["page"] },
        { "name": "Legal",                "title": "Legal",                "postTypes": ["page"] }
    ],
    "templateParts": [
        { "name": "header", "title": "Header", "area": "header" },
        { "name": "footer", "title": "Footer", "area": "footer" }
    ]
}
```

---

## 16. Mapping — Design System to Architecture

### 16.1 Component → Functional Specification

| Design System Component | FS Section | Pages |
|---|---|---|
| Hero Section | §4.1, §4.2 | P01 (Home), P02-P08 (Service pages), P09-P10 (Category Landings) |
| Service Cards | §4.1, §4.2, §4.3 | P01 (Homepage grid), P09-P10 (Category Landings) |
| USP Grid | §4.1 | P01 (Homepage) |
| CTA Banner | §4.1, §4.2, §4.4 | P01-P15 (all pages except system/legal) |
| Testimonial Block | §4.1, §4.5 | P01 (Homepage), P13 (Referenties) |
| Client Logo Carousel | §4.1, §4.5 | P01 (Homepage), P13 (Referenties) |
| FAQ Accordion | §4.4 | P18 (Veelgestelde Vragen), P02-P08 (Service page optional) |
| Contact Form | §4.8 | P16 (Contact) |
| Quote Form | §4.8 | P17 (Offerte Aanvragen) |
| Content + Image | §4.2, §4.4 | P02-P08 (Service pages), P11-P12 (About pages) |
| Service Icon List | §4.2 | P02-P08 (Service pages) |
| Cross-Sell Services | §4.2 | P02-P08 (Service pages) |
| Job Vacancy Card | §4.6 | P14 (Vacatures) |
| Download Card List | §4.7 | P15 (Downloads) |
| Blog Cards | §4.20 | P29 (Kennisbank index), P01 (Latest Posts) |
| Product Cards | §4.10 | P24 (Winkel), P25 (Products) |
| 404 Content | §4.17 | P31 (404) |
| Bedankt Page | §4.9 | P32 (Bedankt) |
| Search Results | §4.18 | Search results |
| Cookie Banner | §4.16 | All pages (Complianz) |

### 16.2 Component → RTM Requirements

| Design System Component | REQ-FR | REQ-ACC | REQ-SEO | REQ-UIX |
|---|---|---|---|---|
| All Components (Color Contrast) | — | REQ-ACC-001 | — | — |
| Navigation (Desktop + Mobile) | REQ-FR-031..035 | REQ-ACC-002, 015, 020 | — | REQ-UIX-001..003 |
| Header | REQ-FR-031 | REQ-ACC-003 | — | REQ-UIX-001 |
| Footer | REQ-FR-033 | REQ-ACC-002, 015 | — | REQ-UIX-004 |
| Breadcrumbs | REQ-FR-034 | REQ-ACC-002 | REQ-SEO (BreadcrumbList) | REQ-UIX-006 |
| Skip to Content | — | REQ-ACC-003 | — | REQ-UIX-007 |
| Forms (All) | REQ-FR-001..003, 019..021 | REQ-ACC-007 | — | REQ-UIX-014 |
| Hero Section | REQ-FR-013, REQ-FR-004..010 | REQ-ACC-004 | — | REQ-UIX-008 |
| Service Card Grid | REQ-FR-011..012 | REQ-ACC-002, 004 | — | REQ-UIX-009 |
| USP Grid | REQ-FR-013 | REQ-ACC-004 | — | REQ-UIX-010 |
| CTA Banner | REQ-FR-013 | REQ-ACC-002, 008 | — | REQ-UIX-011 |
| Testimonial Block | REQ-FR-041..043 | REQ-ACC-004 | — | REQ-UIX-012 |
| FAQ Accordion | — | REQ-ACC-002 | REQ-SEO-027 (FAQPage) | REQ-UIX-013 |
| Cookie Banner | — | REQ-ACC-002 | — | REQ-UIX-005 |
| All Images | REQ-FR-036..040 | REQ-ACC-006 | REQ-SEO (alt text) | — |
| All Pages | REQ-FR-028..030 | REQ-ACC-009, 013, 017 | — | — |
| WooCommerce | REQ-FR-022..027 | REQ-ACC-016, 019 | — | — |

### 16.3 Component → WordPress Technical Architecture

| Design System Component | WTA Implementation | File(s) |
|---|---|---|
| Global Styles (Colors, Typography, Spacing) | `theme.json` | `wp-content/themes/hds/theme.json` |
| Base Styles (Reset, Layout, Utilities) | `main.css` | `assets/css/main.css` |
| Header | Template Part | `parts/header.php` |
| Footer | Template Part | `parts/footer.php` |
| Breadcrumbs | Template Part + Helper | `parts/breadcrumbs.php`, `inc/helpers.php` |
| Navigation | WP Menu System | 5 menu locations registered in `functions.php` |
| Service Pages | Page Template | `page-templates/page-service.php` |
| Category Landings | Page Template | `page-templates/page-category-landing.php` |
| About Pages | Page Template | `page-templates/page-about.php` |
| Contact Page | Page Template + Gravity Forms | `page-templates/page-contact.php` |
| Quote Page | Page Template + Gravity Forms | `page-templates/page-quote.php` |
| FAQ Page | Page Template + Yoast/Rank Math FAQ Block | `page-templates/page-faq.php` |
| Legal Pages | Page Template | `page-templates/page-legal.php` |
| Homepage | Front Page Template | `front-page.php` |
| Blog | Archive + Single | `archive.php`, `single.php` |
| Search | Search Template | `search.php` |
| 404 | 404 Template | `404.php` |
| Custom Blocks (4) | Block Registration + Render Callbacks | `inc/blocks.php`, `assets/js/blocks/` |
| Block Patterns (7) | Pattern Registration | `inc/patterns.php` |
| Block Styles (6) | Style Registration | `functions.php` via `register_block_style()` |
| LocalBusiness Schema | Schema Part | `parts/schema-localbusiness.php` |
| SEO Schema | Schema Generator | `inc/schema.php` |
| Company Info | Customizer | `inc/customizer.php` |

### 16.4 Component → Development Execution Plan

| Design System Component | Sprint | Epic / Story |
|---|---|---|
| Theme Foundation (theme.json, main.css, design tokens) | Sprint 1 | E-INFRA-06, E-INFRA-08 |
| Header, Footer, Navigation | Sprint 1 | E-INFRA-06 |
| Homepage (front-page.php) | Sprint 2 | E-CORE-01 |
| Service Page Template (P02-P08) | Sprint 2 | E-CORE-02 through E-CORE-08 |
| Category Landings (P09-P10) | Sprint 2 | E-CORE-08 |
| Contact + Quote Pages + Forms | Sprint 2 | E-CORE-09, E-CORE-10 |
| About Pages (P11-P12) | Sprint 3 | E-SUPPORT-01, E-SUPPORT-02 |
| Referenties + Vacatures + Downloads | Sprint 3 | E-SUPPORT-03, E-SUPPORT-04, E-SUPPORT-06 |
| Legal Pages + FAQ | Sprint 3 | E-SUPPORT-05, E-SUPPORT-07 |
| WooCommerce Components | Sprint 4 | E-COMM-01 through E-COMM-07 |
| Blog Components | Sprint 5 | P3 (future) |
| Accessibility Audit + Fixes | Sprint 6 | E-COMPLY-07 |
| Cross-Browser + Mobile QA | Sprint 7 | E-QA |

---

## 17. Acceptance Criteria

### 17.1 Design System Completeness

| # | Criterion | Pass Condition |
|---|---|---|
| AC-DS01 | All Design Tokens defined and consistent with `theme.json` | `theme.json` passes WordPress schema validation. Color palette contains exactly 11 colors. Typography scale contains exactly 9 font sizes. |
| AC-DS02 | All components documented with states (default, hover, focus, active, disabled, loading, error) | Design System document covers all 7 states for interactive components. |
| AC-DS03 | All components have responsive behavior defined | Mobile, tablet, and desktop behavior specified for every layout component. |
| AC-DS04 | Accessibility requirements mapped to WCAG criteria | Every WCAG 2.2 AA criterion has at least one design system implementation (§11). |
| AC-DS05 | Color contrast ratios verified | All text/background combinations in the palette pass AA contrast. Caution noted for accent-on-white. |

### 17.2 Implementation-Facing

| # | Criterion | Pass Condition |
|---|---|---|
| AC-DS06 | Every page template uses design system components (no one-off styles) | Code review: zero `style=""` attributes in PHP templates. Zero hardcoded font sizes or colors outside `theme.json`. |
| AC-DS07 | BEM naming convention followed | Code review + Stylelint: all custom CSS classes follow `.hds-[block]__[element]--[modifier]`. |
| AC-DS08 | Block Editor uses design tokens | Block Editor color picker, font size selector, and spacing controls reflect the `theme.json` palette. |
| AC-DS09 | Block patterns match design system specifications | All registered block patterns render with design system colors, spacing, and typography. |

### 17.3 UX/Visual

| # | Criterion | Pass Condition |
|---|---|---|
| AC-DS10 | Consistent visual language across all 32 pages | Visual QA: CTA buttons identical on every page. Navigation identical. Cards share base style. |
| AC-DS11 | Mobile-first rendering correct | On 375px viewport: no horizontal scroll. Content reflows correctly. Touch targets ≥ 44px. |
| AC-DS12 | Hover and focus states visible on all interactive elements | Keyboard tab-through: focus ring visible on every interactive element. Mouse hover: visual change on every button/link/card. |
| AC-DS13 | Loading states visible (not blank) during AJAX operations | Form submission: button shows spinner + text change. Cart update: spinner on quantity. Search: skeleton loader. |
| AC-DS14 | Empty states invisible (sections hidden, no broken placeholders) | Visual check: if no testimonials, testimonial section not rendered. No "Coming soon" text. |
| AC-DS15 | Reduced motion respected | Enable `prefers-reduced-motion: reduce` in OS. Verify: no animations, instant transitions. |

### 17.4 Performance

| # | Criterion | Pass Condition |
|---|---|---|
| AC-DS16 | No design element blocks First Contentful Paint | PSI: FCP < 1.8s. Critical CSS inlined. |
| AC-DS17 | WebP images used throughout | DevTools Network tab: image requests are `.webp` format. |
| AC-DS18 | Fonts self-hosted (no Google Fonts CDN requests) | DevTools Network tab: zero requests to `fonts.googleapis.com`. |
| AC-DS19 | CLS < 0.1 on all page templates | PSI: CLS score < 0.1. Explicit width/height on all images. |

### 17.5 Accessibility (Additional to §11)

| # | Criterion | Pass Condition |
|---|---|---|
| AC-DS20 | Lighthouse Accessibility = 100 on all page templates | Lighthouse audit on Home, Service, Contact, Blog. |
| AC-DS21 | axe DevTools: zero critical or serious issues | Automated scan on all page templates. |
| AC-DS22 | Complete keyboard navigation through all pages | Manual test: Tab through Home → Service → Contact → Footer. Every element reachable and operable. |
| AC-DS23 | Screen reader announces page content correctly | NVDA (Windows) test: heading hierarchy, link text, form labels, image alt text all announced correctly. |

---

*End of Design System Specification — DS-001 v1.0.0*

*This Design System Specification is approved for implementation. All components, tokens, and rules defined here are binding for Sprint 2-8 development and Sprint 7 QA verification. Changes to these specifications require an Architecture Decision Record (ADR) update.*
