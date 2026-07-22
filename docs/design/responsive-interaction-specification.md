# HDS Onderhoudsdiensten — Responsive & Interaction Specification

**Document ID:** RIS-001 | **Version:** 1.0.0 | **Status:** Approved for Implementation
**Project:** helderduidelijkschoon.nl — Ground-Up Rebuild
**Language:** Nederlands (nl-NL) | **Date:** July 2026

**Prerequisite Documents:**
DS-001 (Design System), UXW-001 (UX Wireframes), HFUI-001 (High-Fidelity UI), FS-001 (Functional Specification), NFR-001 (Non-Functional Requirements)

**Role:** This document defines HOW every interface behaves across devices and during user interaction. It does NOT define visual appearance (see HFUI-001) or structural layout (see UXW-001). It defines only behavior, interaction, animation, and device adaptation.

---

## 1. Responsive Strategy

### 1.1 Core Rules

| Rule | Implementation |
|---|---|
| **Mobile-First** | Base CSS targets the smallest supported viewport (320px). All wider breakpoints use `min-width` media queries to add or modify styles. |
| **No `max-width` queries** | Never use `max-width` for layout changes. This prevents desktop styles from leaking into mobile. |
| **Fluid between breakpoints** | Use `clamp()`, `min()`, `max()`, percentage units, and `auto-fit/auto-fill` grids between breakpoints. No fixed widths below `wideSize`. |
| **Content decides height** | No fixed-height containers except hero (min-height). Components grow with content. |
| **Touch first** | All interactive elements designed for touch (44×44px minimum) by default. Hover states are additive on devices that support `hover: hover`. |
| **Device-agnostic** | Behavior adapts to capabilities, not device names. Use `pointer: coarse` vs `pointer: fine`, `hover: hover` vs `hover: none`. |

### 1.2 Viewport Support Matrix

| Viewport Range | Min Width | Max Width | CSS Target | Primary Devices |
|---|---|---|---|---|
| **Small Mobile** | 320px | 374px | Default (no query) | iPhone SE, small Android |
| **Mobile** | 375px | 767px | `@media (min-width: 375px)` | iPhone 12-15, Galaxy S series |
| **Tablet Portrait** | 768px | 1023px | `@media (min-width: 768px)` | iPad, iPad Mini, Surface Go |
| **Tablet Landscape / Small Laptop** | 1024px | 1279px | `@media (min-width: 1024px)` | iPad Pro, 13" laptops |
| **Desktop** | 1280px | 1439px | `@media (min-width: 1280px)` | 14-15" laptops, standard monitors |
| **Large Desktop** | 1440px | 1919px | `@media (min-width: 1440px)` | 24-27" monitors |
| **Ultra-Wide** | 1920px | — | `@media (min-width: 1920px)` | 32"+ monitors, ultrawide |

### 1.3 Content Behavior Per Viewport

| Viewport | Grid Columns | Images | Navigation | CTA Behavior |
|---|---|---|---|---|
| **320–374px** | 1 column, full-width | Full-width, no side-by-side | Hamburger overlay | Full-width, stacked |
| **375–767px** | 1 column | Full-width | Hamburger overlay | Full-width, may be inline if short |
| **768–1023px** | 2 columns where specified | Side-by-side allowed | Desktop horizontal nav | Inline with text |
| **1024–1279px** | 2-3 columns | Multi-column layouts | Desktop + dropdowns | Inline |
| **1280px+** | 3-4 columns | Full multi-column | Desktop + mega menus | Inline, constrained to content width |

---

## 2. Breakpoint System

### 2.1 Primary Breakpoints (CSS Custom Properties)

```css
:root {
    --hds-bp-tablet:  768px;
    --hds-bp-desktop: 1024px;
    --hds-bp-wide:    1280px;
    --hds-bp-ultra:   1920px;
}
```

### 2.2 Media Query Usage

```css
/* Mobile — base styles, no media query */
.component { /* mobile layout */ }

/* Tablet — 768px+ */
@media (min-width: 768px) {
    .component { /* tablet enhancements */ }
}

/* Desktop — 1024px+ */
@media (min-width: 1024px) {
    .component { /* desktop layout */ }
}

/* Wide — 1280px+ */
@media (min-width: 1280px) {
    .component { /* wide container clamping */ }
}

/* Ultra-wide — 1920px+ */
@media (min-width: 1920px) {
    .component { /* maximum width enforcement */ }
}
```

### 2.3 Container Width Transitions

| Viewport | `.container` Behavior |
|---|---|
| < 768px | `width: 100%; padding-inline: 16px;` (fluid) |
| 768–1023px | `width: 100%; padding-inline: 24px; max-width: none;` (fluid with wider padding) |
| 1024–1279px | `max-width: 960px; margin-inline: auto;` (constrained) |
| 1280px+ | `max-width: 1200px; margin-inline: auto;` (clamped to `wideSize`) |
| 1920px+ | `max-width: 1200px; margin-inline: auto;` (no further expansion) |

Reading content (blog posts, legal pages) uses `contentSize` (780px) instead of `wideSize`, following the same transition pattern.

---

## 3. Navigation Behavior

### 3.1 Desktop Navigation (≥1024px)

**Top-Level Items:**
- Hover over a dropdown trigger → open dropdown after 150ms delay (prevents accidental opens when moving mouse across items)
- Click on a dropdown trigger → open dropdown immediately
- Focus on a dropdown trigger (Tab key) → open dropdown via `:focus-within`
- Click outside dropdown → close dropdown
- Press `Escape` → close dropdown, focus returns to trigger element
- Move mouse from trigger to another top-level item → close current dropdown, open new dropdown after 150ms

**Dropdown Interior:**
- `Tab` / `Shift+Tab` navigates between links within the open dropdown
- `Escape` closes the dropdown
- `ArrowDown` / `ArrowUp` move between links within the dropdown column
- Click on any link → navigate, dropdown closes naturally
- Mouse leaves the entire dropdown area (after 300ms grace period) → close dropdown

**Active Page Indicator:**
- The current page or ancestor appears with a bottom border accent
- If the current page is a child item, the parent dropdown trigger shows the accent

### 3.2 Tablet Navigation (768–1023px)

Same as desktop but with reduced spacing between items. Dropdowns may flip to left-align if near the right viewport edge.

### 3.3 Mobile Navigation (< 768px)

**Hamburger Button:**
- Always visible in header (right side)
- `aria-label="Menu openen"` when closed, `aria-label="Menu sluiten"` when open
- `aria-expanded="false"` → `true` on toggle
- `List` icon (24px) when closed, `X` icon when open
- 44×44px touch target

**Overlay Behavior:**
- Opens: slides in from the right edge (`transform: translateX(100%)` → `translateX(0)`). Duration: 250ms, easing: `cubic-bezier(0.4, 0, 0.2, 1)`.
- Closes: slides out to right. Same duration, reverse.
- Backdrop: semi-transparent black overlay (`rgba(0,0,0,0.5)`), fades in/out over 200ms
- `z-index: 1100`
- `position: fixed; inset: 0;`
- Body scroll locked (`overflow: hidden`) while overlay is open
- Focus trapped within the overlay while open
- `Escape` closes the overlay
- Click on backdrop closes the overlay
- Close button (top-right corner) closes the overlay
- Focus returns to hamburger button on close

**Overlay Interior — Accordion Navigation:**
- Top-level items displayed as full-width buttons
- Items with children show a `CaretDown` icon (right side)
- Tap/click toggle button → children expand below with slide animation
- `aria-expanded="false"` → `true` on expand
- Only one accordion group open at a time (exclusive). Opening a new group closes the previously open group.
- Child items slide down: `max-height` transition, 250ms ease
- Tap on a child link → navigate, overlay closes
- Phone number and email displayed at bottom of overlay (not within accordion)

**Footer Section in Mobile Overlay:**
- Below all navigation items
- Phone: full number, `tel:` link, tap-to-call
- Email: `mailto:` link
- Social icons: Facebook, Instagram, Google Business Profile

### 3.4 Sticky Header Behavior

**Activation:**
- Header becomes sticky immediately on page load (not on scroll)
- `position: sticky; top: 0;`

**Scroll Behavior:**
- On downward scroll past 100px: header may reduce padding slightly (cosmetic — optional enhancement, not required for MVP)
- Header background: always `white` with full opacity
- Bottom border: always visible (`1px solid light-gray`)
- No shadow when at top of page. `shadow-md` appears after scrolling past 1px (provides visual separation from content).

### 3.5 Off-Canvas / Mega Menu (MOOT — Not Used)

No off-canvas menu beyond the mobile overlay. No mega menu beyond the two-column desktop dropdown. The architecture supports adding a mega menu if product volume increases, but it is not implemented at launch.

---

## 4. Header Behavior

### 4.1 Logo

- Click → navigate to `/`
- `aria-label="HDS Onderhoudsdiensten — Home"`
- No hover animation beyond link color change

### 4.2 Phone Link

- Always a `tel:0164-652846` link
- Click/tap on mobile: opens native phone dialer
- Click on desktop: may open VoIP app or show number (browser-dependent)
- Mobile (icon-only): tap area 44×44px
- No special animation beyond link hover

### 4.3 Cart Icon (WooCommerce Conditional)

- Displays cart icon with item count badge
- Badge: appears when cart has ≥1 item. Hidden when cart empty (no "0" badge).
- Item added to cart: badge number increments. Brief scale animation on the badge (transform: scale(1.3) → scale(1) over 200ms) for visual feedback.
- Click → navigate to `/winkelmand/`
- `aria-label="Winkelwagen, [N] items"` when items present. `aria-label="Winkelwagen"` when empty.

### 4.4 Search Toggle (Optional Enhancement — Deferred)

Not in the initial launch scope. Search is in the footer and on the 404 page.

---

## 5. Footer Behavior

### 5.1 Column Layout Transitions

| Viewport | Behavior |
|---|---|
| < 480px | 1 column, all columns stacked |
| 480–767px | 2 columns (Diensten + Over HDS, Contact + Luchtreiniging + Juridisch) |
| 768–1023px | 3 columns then 2 columns (3+2 wrap) |
| ≥1024px | 5 columns, equal width |

Transition is instant (CSS Grid `auto-fill` / `auto-fit` handles this without JavaScript).

### 5.2 Cookie Settings Button

- Complianz-managed floating button: `position: fixed; bottom: 16px; left: 16px; z-index: 900;`
- Always visible after consent choice is made
- Click → opens Complianz consent management modal
- Does NOT appear before consent is given (banner handles initial consent)
- `aria-label="Cookie-instellingen openen"`

### 5.3 Social Icons

- Click → open in new tab (`target="_blank" rel="noopener noreferrer"`)
- `aria-label="HDS op Facebook"`, etc.
- 44×44px touch target area (visible icon may be smaller, but the clickable region expands to meet touch target minimums)

---

## 6. Forms Behavior

### 6.1 Validation — Client-Side (Gravity Forms Built-In)

**On Field Blur:**
- Validate the field immediately when focus leaves
- If valid: field returns to default state. Success border optional (not shown by default — success is assumed unless error).
- If invalid: field border changes to `error` color. Error message appears below the field. Message is programmatically associated via `aria-describedby`.

**On Form Submit:**
- Validate all fields
- If any field is invalid: prevent submission. Scroll to the first invalid field (smooth scroll, offset by header height + 16px). Focus the first invalid field.
- Display error summary at top of form: "Corrigeer de volgende velden:" with a list of field labels and error messages. Each list item is a link that focuses the corresponding field.
- If all fields valid and reCAPTCHA passes: proceed to submission.

**Validation Rules Per Field:**

| Field | Rule | Trigger | Error Message (Dutch) |
|---|---|---|---|
| E-mailadres | Valid email format (RFC 5322) | Blur + Submit | "Vul een geldig e-mailadres in." |
| Telefoonnummer | Dutch/intl format: `/^(\+31\|0)[1-9][0-9]{7,10}$/` | Blur + Submit | "Vul een geldig telefoonnummer in." |
| Postcode | Dutch format: `/^[1-9][0-9]{3}\s?[A-Z]{2}$/i` | Blur + Submit | "Vul een geldige postcode in (bijv. 1234 AB)." |
| Bericht (GF-1) | Min 10 characters, max 5000 | Blur + Submit | "Uw bericht moet minimaal 10 tekens bevatten." |
| Privacy akkoord | Must be checked | Submit only | "U moet akkoord gaan met de privacyverklaring." |
| Bestand uploaden | Max 5 MB. Allowed types: PDF, JPG, PNG, DOCX. | On file select + Submit | "Het bestand is te groot (max 5 MB)." / "Dit bestandstype is niet toegestaan." |
| Required fields | Not empty, not whitespace-only | Blur + Submit | "Dit veld is verplicht." |

### 6.2 Validation — Server-Side (After Gravity Forms Processing)

**File Upload Validation:**
- Server checks MIME type using `finfo(FILEINFO_MIME_TYPE)` — not just file extension
- If MIME type doesn't match allowed list: delete uploaded file, return form error
- If file size exceeds `upload_max_filesize` or `post_max_size`: Gravity Forms handles this before PHP error. User sees error message.

**reCAPTCHA v3:**
- Invisible to user. No checkbox, no challenge unless score is borderline.
- Score < 0.5: submission silently blocked. Form appears to submit but entry is not stored. Anti-spam log entry created. User sees form reset (looks like a glitch — mitigated by fallback text below form).
- Score 0.5–0.7: accepted but flagged for manual review.
- Score > 0.7: accepted normally.

**Honeypot:**
- Hidden field, invisible to users
- If filled (bot behavior): submission silently blocked
- No feedback to user (bots don't need UX)

### 6.3 Loading Behavior

**Button State During Submission:**
- On click: button text changes (e.g., "Verstuur bericht" → "Versturen...")
- Spinner icon appears alongside text (CSS rotation animation, 750ms linear infinite)
- Button is disabled: `pointer-events: none`, `opacity: 0.8`
- `aria-busy="true"` added to button
- All form fields become non-interactive during submission (prevents double-submit via field edit)

**AJAX Submission:**
- Form submits via AJAX (Gravity Forms `ajax="true"` in shortcode)
- Page does not reload
- Loading state persists until server responds or timeout (15 seconds)
- If timeout: error behavior (see §6.4)

### 6.4 Success Behavior

**Contact Form (GF-1):**
- On successful submission: browser redirected to `/bedankt/?type=contact` (HTTP 302)
- No inline success message (redirect is cleaner, prevents form re-submission on refresh)
- GA4 event `form_submission` fired before redirect

**Offerte Form (GF-2):**
- Redirect to `/bedankt/?type=offerte`
- GA4 event `quote_request` fired

**Vacature Form (GF-3):**
- Redirect to `/bedankt/?type=vacature`
- No separate GA4 event (tracked as `form_submission`)

**Confirmation Email:**
- Sent to user's email address within 2 minutes (Post SMTP)
- Branded HTML email: HDS logo, Dutch text, summary of submission
- Admin notification sent to `info@helderduidelijkschoon.nl` simultaneously

### 6.5 Failure Behavior

**Client-Side Failure (Network / Timeout):**
- Inline error at top of form: "Er is een fout opgetreden bij het verzenden. Probeer het opnieuw of neem telefonisch contact op via 0164-652846."
- `role="alert"` for screen reader announcement
- Form remains filled (data not lost). Submit button re-enabled.
- Fallback phone number displayed prominently

**Server-Side Failure (500 Error):**
- Same inline error as network failure
- Error logged to `debug.log` with request details
- Developer alerted if Post SMTP or GF email logging detects delivery failure pattern (>5% over 24 hours)

**reCAPTCHA Blocks Legitimate User:**
- User sees form reset with no confirmation (silent failure — poor UX, acknowledged)
- Fallback text permanently visible below form: "Lukt het niet om het formulier te verzenden? Bel ons op 0164-652846 of mail naar info@helderduidelijkschoon.nl."
- This is the primary mitigation for reCAPTCHA v3 false positives

### 6.6 Inline Validation Behavior

**Visual Feedback Sequence:**
1. User types in field → field is in neutral state (gray border)
2. User leaves field (blur) → validation triggers
3. If valid: no change (stays neutral). No green checkmark (to avoid confusion with success state).
4. If invalid: border turns red, error text appears below with slide-down animation (150ms)
5. User corrects field on next focus: red border removed immediately (not waiting for blur). Error text disappears.
6. User re-blurs: re-validation triggers

**Error Message Animation:**
- Appears: `max-height` transition from 0 to auto, 150ms ease. Accompanied by slight fade-in.
- Disappears: instant (no animation — clean removal when corrected)

### 6.7 File Upload Behavior

**Drag and Drop:**
- Drop zone: dashed border, light background
- `dragover` event: border changes to `primary` color, background changes to `primary` at 5% opacity
- `dragleave` event: returns to default dashed border
- `drop` event: file selected, filename displayed in drop zone, border returns to default

**Click to Upload:**
- Click anywhere in the drop zone → opens native file picker dialog
- File selected → filename + size displayed in drop zone
- Wrong file type selected: error message displayed. File not uploaded.

**Progress Indication:**
- For files > 1 MB: progress bar displayed (Gravity Forms AJAX upload provides this)
- Progress bar: `primary` fill, `light-gray` track, 4px height, border-radius 2px
- Upload complete: checkmark icon + filename

**Max File Size Server Enforcement:**
- PHP `upload_max_filesize = 10M`, `post_max_size = 12M`
- Gravity Forms catches oversized files before PHP error
- User sees: "Het bestand is te groot (max 5 MB)."

---

## 7. Buttons Behavior

### 7.1 State Behaviors

**Hover (Devices with `hover: hover`):**
- Primary: background darkens to `primary-dark`, subtle shadow lift (`shadow-md`)
- Secondary: `primary` at 8% opacity background appears
- CTA: background darkens, shadow appears
- Ghost: `primary` at 5% opacity background appears
- Link: text color darkens to `primary-dark`
- Icon: `light-gray` background appears
- Transition: background-color 150ms ease, box-shadow 150ms ease, transform 150ms ease

**Hover (Touch Devices):**
- No hover state on touch. `:hover` styles only apply when a `hover: hover` device is detected.
- On touch, the `:active` state provides feedback instead.

**Focus (`:focus-visible`):**
- `outline: 2px solid primary`, `outline-offset: 2px`
- Appears instantly (no transition — focus must be immediate for keyboard users)
- Only visible when focused via keyboard. Not visible on mouse click (browser default `:focus-visible` behavior).
- Exception: buttons on dark backgrounds use `outline-color: white`

**Pressed / Active (`:active`):**
- Scale: `transform: scale(0.98)` (2% reduction — subtle press feedback)
- Duration: 50ms (instant feedback)
- Background color: darker shade of the default
- Important for touch devices where `:hover` doesn't apply

**Disabled:**
- `opacity: 0.5`
- `cursor: not-allowed`
- `pointer-events: none`
- `aria-disabled="true"` (never use the HTML `disabled` attribute on links styled as buttons)
- No hover, focus, or active state changes
- Tab focus still reaches disabled buttons (for screen reader announcement) but activation is prevented

**Loading:**
- `pointer-events: none` (prevent double-submission)
- `aria-busy="true"`
- Text transitions to loading text (e.g., "Versturen...") with inline CSS spinner
- Spinner: `Spinner` icon with continuous rotation animation (`animation: spin 750ms linear infinite`)
- Button width may expand slightly to accommodate longer loading text — use `min-width` equal to the default button width to prevent layout shift
- After completion: transitions to success state or page redirects

### 7.2 Button-Specific Behaviors

**CTA Button (Accent):**
- Designed to be the most visually prominent button on the page
- On pages with multiple buttons, only ONE CTA button appears (hierarchy: CTA > Primary > Secondary > Ghost)
- On mobile: becomes sticky at bottom of viewport on service pages and homepage (`position: fixed; bottom: 0; z-index: 900;`). Disappears when user scrolls past the CTA banner (the in-page CTA takes over).
- Sticky mobile CTA: slides up from bottom with 250ms ease when user scrolls up. Slides down and hides when user scrolls past the in-page CTA banner.

**Add to Cart Button:**
- On click: item added via AJAX (page does not reload)
- Button briefly shows "Toegevoegd!" with checkmark for 1.5 seconds, then returns to "In winkelwagen"
- Cart icon badge in header animates: scale burst (scale 1.3 → 1.0, 200ms)
- Quantity updates in mini-cart (if implemented)
- `aria-live="polite"` region announces: "[Product] toegevoegd aan winkelwagen"

---

## 8. Cards Behavior

### 8.1 Hover (Devices with `hover: hover`)

- `transform: translateY(-2px)` — subtle lift
- `box-shadow` transitions from `shadow-sm` to `shadow-md`
- Border color may change to `primary` at low opacity (optional — not all cards)
- Transition: 150ms ease for all properties
- Entire card area triggers hover (not just the link within)
- Cursor: `pointer` (entire card is clickable)

### 8.2 Keyboard Focus

- Tab to the link within the card (or the card itself if it's a `<button>` or `<a>`)
- `:focus-visible` outline appears on the card element
- `Enter` activates the link
- Focus order: if the card contains multiple interactive elements (e.g., share button + title link), each receives focus individually. If the card is a single link (stretched `::after`), only one tab stop.

### 8.3 Touch Behavior

- Tap on any part of the card → navigate to the link destination
- Brief `:active` scale feedback (`scale(0.98)`, 50ms) on touch
- No sticky hover state after touch (use `@media (hover: hover)` to scope hover styles)

### 8.4 Service Cards (Specific)

- When tapped/clicked → navigate to the service page
- No progressive disclosure within the card (no expand/collapse). The card is a direct link.
- `aria-label` on the link: "Lees meer over [Service Name]"

### 8.5 Vacancy Cards (Specific)

- Collapsed: shows title + meta row + "Bekijk ▾" button
- Tap/click on the title or "Bekijk" button → toggle expand
- `aria-expanded` toggles
- Expanded: full description, meta details, CTA button (Solliciteer)
- Only one vacancy card expanded at a time (exclusive). Expanding a new card collapses the previously expanded card.
- Collapse animation: `max-height` transition, 250ms ease. Expanded panel slides open.
- Focus management: when expanding, focus moves to the first focusable element in the expanded panel. When collapsing, focus returns to the toggle button.

---

## 9. Tables Behavior

### 9.1 Responsive Table Strategy

**Rule:** Never collapse table columns. Never convert rows to cards. Tables contain structured data that loses meaning when restructured. Instead, use horizontal scrolling.

**Implementation:**
- Wrap the `<table>` in a `<div class="hds-table-wrapper">`
- `overflow-x: auto` on the wrapper
- `-webkit-overflow-scrolling: touch` for smooth scrolling on iOS
- Table maintains its native column structure at all viewport widths

**Visual Indicators:**
- When the table overflows: a subtle gradient fade appears on the right edge to indicate scrollable content (`linear-gradient(to right, transparent, rgba(0,0,0,0.05))` as a pseudo-element `::after`)
- When scrolled all the way right: gradient disappears
- Implementation: detect scroll position via `IntersectionObserver` or scroll event (debounced)

### 9.2 Table-Specific Behaviors

**Cart Table (WooCommerce):**
- Quantity change: AJAX update. Row briefly highlights with `primary` at 5% background (500ms, fades out) to confirm update.
- Remove item: row fades out (200ms), then collapses (max-height transition, 200ms). Cart totals update via AJAX. `aria-live` region announces updated total.
- Empty cart after last removal: empty state appears (transition from table to empty state message, 300ms fade).

**Checkout Order Review Table:**
- Read-only. No interactive behavior beyond displaying order summary.
- Updates reflected on payment method change or coupon application (WooCommerce AJAX).

---

## 10. WooCommerce Behavior

### 10.1 Product Gallery

**Desktop:**
- Main image: large display area (1:1 aspect ratio)
- Thumbnails below: horizontal row, 4-5 visible
- Click thumbnail → main image swaps. No page reload. Smooth crossfade transition (200ms opacity).
- Click main image → lightbox (optional WooCommerce feature). Lightbox: overlay with large image. `Escape` or click outside closes. Arrow keys navigate between images. Touch: swipe left/right.

**Mobile:**
- Main image: full-width (no 1:1 constraint — adapts to product image aspect ratio)
- Thumbnails: horizontal scrollable row below. Same click-to-swap behavior.
- Swipe on main image: navigate between product images (touch gesture). No lightbox on mobile — full-screen swipe is the native pattern.

### 10.2 Cart Behavior

**Quantity Change:**
- Click `-` or `+` → quantity updates via AJAX
- Row subtotal updates immediately
- Cart totals (sidebar) update
- `aria-live="polite"` region: "Aantal bijgewerkt. Subtotaal: €X,XX"
- If quantity reaches 0: item automatically removed (with confirmation animation)
- If quantity exceeds stock: "Niet genoeg voorraad. Maximaal beschikbaar: X" error message appears inline

**Remove Item:**
- Click `✕` → item removed via AJAX
- Row fades out (200ms), then collapses (200ms)
- Cart totals update
- If this was the last item: cart transitions to empty state
- `aria-live` announces: "[Product] verwijderd uit winkelwagen"
- Undo option: optional toast notification with "Ongedaan maken" link (P3 — deferred enhancement)

**Cart Totals Update:**
- Any cart change triggers totals recalculation via WooCommerce AJAX
- Totals area briefly highlights (subtle background pulse, 300ms) to indicate update
- Shipping method change: same recalculation + highlight behavior

### 10.3 Checkout Behavior

**Field Validation:**
- Same behavior as contact form (§6.1)
- Additional WooCommerce-specific validation: postcode format by country, phone format, email format
- Billing fields marked with `*` (required)

**Payment Method Selection:**
- Radio buttons for payment methods
- Selecting a method may reveal additional fields (e.g., iDEAL bank selection dropdown)
- Radio selection triggers: field reveal (slide down, 200ms), order review total recalculation (if payment method affects fees)
- `aria-expanded` on the radio group to indicate child fields

**Coupon Application:**
- "Kortingscode?" link → reveals coupon input field (slide down, 200ms)
- Enter code + click "Toepassen" → AJAX validation
- Valid: discount applied. Cart totals update with highlight. Success message: "Kortingscode toegepast!"
- Invalid: error message: "Ongeldige kortingscode." Field cleared.

**Place Order:**
- Click "Plaats bestelling" → button enters loading state (§7.1)
- Payment processing: redirect to Mollie payment page (iDEAL, Bancontact, Cards) OR inline processing (Bank Transfer)
- If payment fails: error message displayed. Order status = "Failed" in WooCommerce. Button re-enabled.
- If payment succeeds: redirect to order confirmation page
- If payment gateway timeout (15 seconds): error message. "Er is een fout opgetreden bij het verwerken van uw betaling. Probeer het opnieuw of kies een andere betaalmethode."

### 10.4 Order Confirmation Behavior

- Page loads after successful payment
- Order details displayed (order number, date, total, payment method)
- Confirmation email sent to customer (via WooCommerce email + Post SMTP)
- GA4 `purchase` event fired with order value, currency, items
- "Terug naar winkel" link returns to `/winkel/`
- No auto-redirect. User stays on confirmation page.

---

## 11. Animations

### 11.1 Permitted Animations

| Element | Animation | Duration | Easing | Iteration |
|---|---|---|---|---|
| Button hover (bg, shadow, lift) | Background-color, box-shadow, transform | 150ms | ease | Once |
| Link hover (color) | Color | 150ms | ease | Once |
| Card hover (lift, shadow) | Transform, box-shadow | 150ms | ease | Once |
| Focus ring | Outline | 0ms (instant) | — | Once |
| Dropdown open | Opacity + translateY(-4px → 0) | 200ms | `cubic-bezier(0, 0, 0.2, 1)` | Once |
| Dropdown close | Opacity + translateY(0 → -4px) | 150ms | `cubic-bezier(0.4, 0, 1, 1)` | Once |
| Mobile menu open | transform: translateX(100% → 0) | 250ms | `cubic-bezier(0.4, 0, 0.2, 1)` | Once |
| Mobile menu close | transform: translateX(0 → 100%) | 200ms | `cubic-bezier(0.4, 0, 1, 1)` | Once |
| Accordion open | max-height | 250ms | `cubic-bezier(0.4, 0, 0.2, 1)` | Once |
| Accordion close | max-height | 200ms | `cubic-bezier(0.4, 0, 1, 1)` | Once |
| Form error appear | max-height + opacity | 150ms | ease | Once |
| Modal open | Opacity + scale(0.95 → 1) | 250ms | `cubic-bezier(0, 0, 0.2, 1)` | Once |
| Modal close | Opacity + scale(1 → 0.95) | 150ms | `cubic-bezier(0.4, 0, 1, 1)` | Once |
| Toast notification enter | translateX(100% → 0) + opacity | 300ms | `cubic-bezier(0, 0, 0.2, 1)` | Once |
| Toast notification exit | translateX(0 → 100%) + opacity | 200ms | `cubic-bezier(0.4, 0, 1, 1)` | Once |
| Loading spinner | Rotate | 750ms | linear | Infinite |
| Skeleton loader | Background shimmer | 1500ms | ease | Infinite (until content loads) |
| Cart badge burst | Scale | 200ms | `cubic-bezier(0.34, 1.56, 0.64, 1)` | Once (overshoot bounce) |
| Vacancy card expand | max-height | 250ms | ease | Once |
| Image crossfade (gallery) | Opacity | 200ms | ease | Once |
| Row fade out (cart remove) | Opacity + max-height | 200ms | ease | Once |

### 11.2 Animation Principles

| Principle | Rule |
|---|---|
| **Purposeful** | Every animation serves a function: feedback, orientation, or focus guidance. No decorative animations. |
| **Short** | No animation exceeds 300ms except loading spinners (continuous) and skeleton loaders (continuous until content). |
| **Performant** | Only animate `transform` and `opacity`. Avoid animating `width`, `height`, `top`, `left`, `margin`, `padding` — these trigger layout recalculation. Use `max-height` for expand/collapse (acceptable tradeoff — the only layout-triggering animation permitted). |
| **Easing** | Elements entering the screen decelerate (`cubic-bezier(0, 0, 0.2, 1)`). Elements leaving the screen accelerate (`cubic-bezier(0.4, 0, 1, 1)`). In-place transitions use `ease`. |

### 11.3 Reduced Motion

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

**Effect on specific elements:**
- All hover transitions: instant color/shadow change
- Dropdowns/accordions/modals: appear instantly (no slide/fade)
- Mobile menu: appears instantly
- Loading spinners: static (no rotation)
- Skeleton loaders: static placeholder
- Cart badge burst: no animation
- Card lift: no movement
- Scroll-behavior: `scroll-behavior: auto` (instant scroll, not smooth)

### 11.4 Accessibility — Animations

- No auto-playing animations (no infinite animations except user-initiated loading spinners)
- No flashing content exceeding 3 flashes per second (WCAG 2.3.1)
- `prefers-reduced-motion` respected (see §11.3)
- Animations do not convey essential information. If an animation is removed, all content and functionality remain accessible.

---

## 12. Scroll Behavior

### 12.1 Smooth Scrolling

- `scroll-behavior: smooth` on the `<html>` element for anchor link navigation
- Anchor links (e.g., skip-to-content, "Terug naar boven", form error → field) scroll smoothly
- Scroll offset: account for sticky header height (header height + 16px padding). Use `scroll-margin-top` on target elements.
- Exception: when `prefers-reduced-motion: reduce`, scroll-behavior is `auto` (instant).

### 12.2 Back-to-Top Button

- Floating button: `position: fixed; bottom: 24px; right: 24px; z-index: 900;`
- Appears after scrolling down 400px (fade in, 200ms)
- Disappears when within 100px of the top (fade out, 200ms)
- Click → smooth scroll to top of page
- `aria-label="Terug naar boven"`
- 44×44px touch target
- Hidden on mobile if sticky CTA is also shown at the bottom (avoids overlapping buttons)

### 12.3 Scroll-Linked Behavior

**Sticky Header Shadow:**
- At top of page (scrollY = 0): `box-shadow: none`
- After scrolling 1px: `box-shadow: shadow-md` appears (transition: box-shadow 150ms ease)
- Shadow indicates the header is now "floating" above content — helps with depth perception.

**Sticky Mobile CTA:**
- Visible when user scrolls down past the hero section
- Hidden when the in-page CTA Banner scrolls into view (user can see the in-page CTA)
- Re-appears when user scrolls back up (anticipating intent to convert)
- Uses `IntersectionObserver` to track the in-page CTA Banner's visibility

---

## 13. Lazy Loading

### 13.1 Images

**Default Behavior (WordPress Native):**
- All content images receive `loading="lazy"` by default (WordPress 5.5+)
- The LCP image explicitly receives `loading="eager"` and `fetchpriority="high"`
- Decorative CSS background images are loaded normally (they don't support native lazy loading)

**LCP Image Identification:**
- Homepage: Hero background (if image) or the first service card icon
- Service Pages: Hero image (if `hds_hero_image` set). Otherwise, first content image.
- Blog Posts: Featured image
- Product Pages: Main product image

**Below-Fold Images:**
- All images not in the initial viewport: `loading="lazy"`, `decoding="async"`
- Placeholder: transparent or `light-gray` background while loading. No layout shift — explicit `width` and `height` attributes prevent CLS.

### 13.2 Embeds (Google Maps, YouTube)

- Always lazy-loaded
- Complianz consent placeholder shown until user accepts marketing cookies
- On consent: iframe loaded dynamically (JavaScript injects `src` attribute)
- Placeholder: gray box with descriptive text + load button

### 13.3 JavaScript

- All scripts loaded with `defer` attribute (not `async` — execution order matters for WordPress)
- No render-blocking JavaScript in `<head>`
- GTM script: loaded via GTM snippet with `async` (per Google recommendation). Consent mode v2 controls when tags fire.

---

## 14. Images Behavior

### 14.1 Responsive Images

- All `<img>` elements render with `srcset` and `sizes` attributes (WordPress auto-generates)
- Picture element: `<source type="image/webp" srcset="...">` + `<img>` fallback (ShortPixel/Imagify handles)
- Art direction: not used (no different crops per breakpoint). Same image, different resolutions.

### 14.2 Image Loading Sequence

1. Page loads → LCP image begins loading immediately (eager)
2. Browser parses HTML → below-fold images discovered
3. Below-fold images: browser decides when to load based on viewport proximity (native lazy loading)
4. Images fade in on load (optional enhancement: CSS `animation: fadeIn 300ms ease` on `img[loading="lazy"].loaded` — requires IntersectionObserver + JS. Deferred to P3.)

### 14.3 Broken Image Fallback

- If image fails to load: display `alt` text in a styled container
- CSS: `img::before` and `img::after` pseudo-elements display the `alt` text (limited browser support for broken-image styling) + background fallback color `light-gray`
- Graceful degradation: missing image does not break layout (explicit dimensions prevent collapse)

---

## 15. Videos Behavior

**Not in scope for initial launch.** If videos are added post-launch (e.g., YouTube embed in blog post):
- Lazy-loaded via consent placeholder (Complianz)
- `loading="lazy"` on iframe
- `title` attribute for accessibility
- No autoplay
- Responsive container: 16:9 aspect ratio via `aspect-ratio` CSS or padding-bottom hack

---

## 16. Accordions Behavior (FAQ)

### 16.1 Toggle Behavior

- Click/tap on question header → toggle answer panel
- `aria-expanded="false"` → `true`
- `aria-controls="[panel-id]"` links header to panel
- Press `Enter` or `Space` on focused header → toggle (keyboard)
- Multiple items may be open simultaneously (NOT exclusive — users may want to compare answers)

### 16.2 Animation

- Panel opens: `max-height` transition from 0 to the panel's natural height, 250ms ease
- Panel closes: `max-height` transition to 0, 200ms ease (faster close than open — feels responsive)
- `CaretDown` icon: rotates 180° on open (transition: transform 200ms ease)
- Panel content fades in during the last 100ms of the open animation (`opacity 0 → 1`, overlapping with max-height transition)

### 16.3 Screen Reader

- Panel content is always in the DOM (not removed/added). `max-height: 0; overflow: hidden;` hides it visually.
- `aria-expanded` state communicates open/closed to screen readers
- When panel opens, focus does NOT move to the panel content (stays on the header button — user can Tab into the content themselves)

---

## 17. Modals Behavior

### 17.1 Modal Types in Scope

| Modal | Trigger | Implementation |
|---|---|---|
| **Cookie Consent** | First visit (auto) | Complianz Premium |
| **Cookie Settings** | Floating button click | Complianz Premium |
| **Mobile Menu** | Hamburger icon | Custom (see §3.3) |
| **Product Lightbox** | Product image click | WooCommerce optional |
| **Search Results** | N/A (dedicated page) | Not a modal — full page |

### 17.2 Modal Behavior (Generic)

**Opening:**
- Modal overlay appears: `position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000;`
- Overlay fades in: opacity 0 → 1, 200ms
- Modal content: scale(0.95) → scale(1) + opacity 0 → 1, 250ms
- Focus moves to the first focusable element inside the modal
- `aria-modal="true"` on the modal container
- `aria-labelledby` points to the modal heading
- Body scroll locked (`overflow: hidden`)

**Tab Trapping:**
- `Tab` at the last focusable element → focus wraps to the first focusable element
- `Shift+Tab` at the first focusable element → focus wraps to the last focusable element
- Focus never leaves the modal while it is open

**Closing:**
- Click the close button (`✕`)
- Click the overlay backdrop (outside the modal content)
- Press `Escape`
- All three methods: same closing animation (reverse of opening)
- After close: focus returns to the element that triggered the modal (e.g., button, link)
- Body scroll restored

**Screen Reader:**
- When modal opens: screen reader announces the modal's accessible name and role
- When modal closes: screen reader announces return to the page, focus lands on trigger element

---

## 18. Toast Notifications Behavior

### 18.1 Toast Types

| Type | Trigger | Duration | Icon |
|---|---|---|---|
| **Success** | Item added to cart, form submitted (if inline) | 4 seconds auto-dismiss | `CheckCircle` |
| **Error** | Network error, action failed | 6 seconds auto-dismiss (longer — user needs to read) | `WarningCircle` |
| **Info** | Settings saved, action confirmed | 3 seconds auto-dismiss | `Info` |

### 18.2 Toast Behavior

- Appears: slides in from the right edge + fades in. Duration: 300ms. Easing: decelerate.
- Position: `fixed; top: 16px; right: 16px; z-index: 1200;`
- Stacking: multiple toasts stack vertically. Most recent at the top. Max 3 visible toasts.
- Auto-dismiss: after the duration, slides out to the right + fades out (200ms)
- Manual dismiss: click `✕` button or swipe right on touch devices
- Hover: pause auto-dismiss timer while mouse is hovering over the toast
- `role="status"` for info/success. `role="alert"` for errors.
- Screen reader: announces toast content immediately (alert) or at next graceful opportunity (status)

### 18.3 Toast Use Cases (Launch Scope)

| Use Case | Toast? |
|---|---|
| Product added to cart | ✅ Toast: "Airfixr 150 toegevoegd aan winkelwagen" [Bekijk winkel] |
| Product removed from cart | ✅ Toast: "Verwijderd" [Ongedaan maken] (undo deferred to P3) |
| Coupon applied | ✅ Toast: "Kortingscode toegepast!" |
| Form inline success (no redirect) | ❌ No redirect used for forms. Redirect to /bedankt/. |
| Network error | ✅ Toast: "Verbindingsfout. Controleer uw internetverbinding." |
| Cookie consent saved | ❌ Banner simply closes. No toast needed. |

---

## 19. Search Behavior

### 19.1 Search Form (Footer / 404 / Header)

- User types query → presses `Enter` or clicks search icon
- Browser navigates to `/?s=[query]`
- Full page reload (not AJAX — Relevanssi replaces the standard WP search with fulltext Dutch-language search on reload)
- Input field sanitized: leading/trailing whitespace trimmed

### 19.2 Search Results Page

- Page loads with Relevanssi-enhanced query results
- Results sorted by relevance (Relevanssi score)
- H1: "Zoekresultaten voor '[query]'" — query displayed as entered (escaped for XSS)
- Each result: title (H3, linked), excerpt with search term highlighting (`<mark>` element for matched terms via Relevanssi), URL breadcrumb (`s`, gray)
- Pagination: if >10 results, paginated. Same pagination component as blog.
- Empty: "Geen resultaten gevonden voor '[query]'." with suggestions (see HFUI §16.4)

### 19.3 Search Analytics

- GA4 Enhanced Measurement `search` event auto-fires on `/` URL with `?s=` parameter
- Custom dimension `search_term` captured
- Monthly review of search terms → identify content gaps

---

## 20. Empty States Behavior

### 20.1 Conditional Section Hiding (ADR D-015)

**Sections that HIDE when empty:**
- Client Logo Carousel → `display: none` on section wrapper
- Testimonial Block → `display: none` on section wrapper
- Latest Blog Posts → `display: none` on section wrapper
- Team Section (Over HDS) → `display: none` on section wrapper

**Behavior:** No animation. No placeholder. Section simply does not exist in the DOM. Pages above and below join seamlessly (the `spacing-16` padding of the adjacent sections provides adequate breathing room without the missing section creating an awkward gap).

### 20.2 Sections that SHOW an Empty State when empty:

| Section | Empty State Behavior | Transition |
|---|---|---|
| Blog Index | Static message + CTA. Always present in DOM. | No transition (always present if no posts) |
| Vacancies Page | Static message + email link. Always present in DOM. | No transition |
| Search Results | Dynamic message based on query. | Page load — no transition |
| Downloads Page | Static message. Always present in DOM. | No transition |
| Cart | Static message + shop link. Replaces cart table. | 300ms fade transition when last item removed |
| Referenties Page (both sections hidden) | Compound empty state — shows only if both logos and testimonials are empty. | No transition (conditional PHP logic) |

---

## 21. Error States Behavior

### 21.1 404 Page

- Triggered by any URL that doesn't match a WordPress route
- Returns true HTTP 404 status code (not a 200 with 404 content)
- No redirect. URL remains as entered.
- Search bar focused by default for quick re-query
- GA4 event: `404_error` fired (if configured)

### 21.2 500 Error (Server-Level)

- Triggered by PHP fatal error or server misconfiguration
- NOT WordPress-dependent (may be static HTML at server level)
- User sees minimal page with contact information
- Error logged to `debug.log`
- Developer alerted via WordPress fatal error handler or hosting monitoring

### 21.3 503 Maintenance Mode

- Triggered by WordPress core/plugin/theme updates
- WordPress built-in `maintenance.php`
- Custom `maintenance.php` overrides the default with branded message
- Returns HTTP 503 status
- Auto-dismissed when update completes

### 21.4 Form Error Recovery

- User corrects invalid field → error state clears immediately on focus (not waiting for blur)
- User submits form after correction → re-validates all fields
- Server-side error: form re-renders with field values preserved (Gravity Forms default). Error messages displayed. User does not lose entered data.

### 21.5 Network Error (AJAX Operations)

- Cart update / add-to-cart / checkout fails due to network
- Toast notification appears: "Verbindingsfout. Controleer uw internetverbinding en probeer opnieuw."
- Operation can be retried by user (click button again)
- Button re-enabled after error

---

## 22. Accessibility Behavior

### 22.1 Keyboard — Page-Level Navigation

**Tab Order (every page):**
1. Skip-to-content link: "Ga naar de inhoud" → `#content`
2. Logo: "HDS Onderhoudsdiensten — Home"
3-6. Primary navigation items (L→R, including dropdown child items when expanded)
7. Phone link: `tel:0164-652846`
8. Cart icon (if WooCommerce active)
9. Main content (`<main id="content">`) — receives focus from skip link
10. All interactive elements in page content (DOM order: top → bottom, left → right)
11. Footer links (columns L→R)
12. Social icons (Facebook, Instagram, GBP)
13. Cookie settings button (Complianz)

### 22.2 Keyboard — Component-Specific

| Component | Key | Behavior |
|---|---|---|
| **Skip Link** | `Enter` | Focus moves to `<main id="content">` |
| **Dropdown Trigger** | `Enter` / `Space` | Open/close dropdown |
| | `Escape` | Close dropdown. Focus returns to trigger. |
| | `ArrowDown` / `ArrowUp` | Navigate between links within open dropdown |
| **Accordion Header** | `Enter` / `Space` | Toggle accordion panel |
| **Modal** | `Escape` | Close modal. Focus returns to trigger. |
| | `Tab` / `Shift+Tab` | Cycle through focusable elements within modal (loops) |
| **Hamburger Menu** | `Enter` / `Space` | Open overlay |
| **Mobile Overlay** | `Escape` | Close overlay. Focus returns to hamburger. |
| | `Tab` / `Shift+Tab` | Cycle through overlay elements (loops) |
| **Product Thumbnail** | `Enter` / `Space` | Swap main image |
| **Quantity Selector** | `ArrowUp` / `ArrowDown` | Increment/decrement quantity |
| **Sort Dropdown** | `Enter` / `Space` | Open dropdown |
| | `ArrowDown` / `ArrowUp` | Navigate options |
| | `Enter` | Select option. Page reloads with new sort order. |

### 22.3 Focus Management

**Focus Traps:**
- Modals: focus loops within modal content
- Mobile overlay: focus loops within overlay
- No other elements trap focus

**Focus Restoration:**
- Closing a modal/overlay/dropdown: focus returns to the element that triggered it
- Form error → field: focus moves to first invalid field (via `.focus()` after scroll)
- After AJAX cart update: focus stays on the quantity selector (user can continue adjusting)

**Focus Visibility:**
- `outline: 2px solid primary; outline-offset: 2px;` on all `:focus-visible` elements
- Never `outline: none` without a replacement visual indicator of equal or greater visibility
- Dark backgrounds: `outline-color: white`

### 22.4 Screen Reader Announcements

| Event | ARIA Mechanism | Announcement |
|---|---|---|
| Page load | `<title>` + H1 | Page title + main heading |
| Dropdown opened | `aria-expanded="true"` | "Uitgeklapt" (implicit via state change) |
| Accordion toggled | `aria-expanded="true/false"` | State change announced |
| Form error | `aria-describedby` + `role="alert"` | Error message read when field focused |
| Cart update | `aria-live="polite"` region | "Aantal bijgewerkt. Subtotaal: €X,XX" |
| Item added to cart | `aria-live="polite"` region | "[Product] toegevoegd aan winkelwagen" |
| Search results loaded | H1 content update | "X zoekresultaten voor [query]" |
| Modal opened | `aria-modal="true"` | Modal's `aria-labelledby` heading announced |
| Toast notification | `role="status"` / `role="alert"` | Toast text content announced |
| Loading state | `aria-busy="true"` | "Bezig met laden" (implicit) |

### 22.5 Touch Targets

All interactive elements: minimum **44×44px** touch area. This includes:
- Navigation links
- Buttons (all variants, all sizes → min-height and min-width 44px)
- Form controls (checkbox 20px visible + 24px padding = 44px total)
- Hamburger icon
- Cart icon
- Social media icons
- Pagination numbers
- Accordion headers
- Dropdown toggles
- Cookie settings floating button
- Back-to-top button

**Smaller visual elements:** Where the visible icon is < 44px (e.g., social icon 24px), the clickable area is expanded via padding or a transparent `::after` pseudo-element to meet the 44px minimum.

### 22.6 Reduced Motion

See §11.3. All animations disabled when `prefers-reduced-motion: reduce`.

---

## 23. Performance Considerations

### 23.1 Render-Blocking Avoidance

| Resource | Strategy |
|---|---|
| CSS | Critical CSS inlined in `<head>` (FlyingPress auto). Non-critical CSS deferred. |
| JavaScript | `defer` attribute. No scripts in `<head>`. GTM loads async. |
| Fonts | Self-hosted WOFF2. `font-display: swap`. Preloaded in `<head>`. |
| Images | LCP: eager + fetchpriority=high. Below-fold: lazy. Explicit dimensions. |
| Third-party | GTM manages all third-party scripts. Consent mode v2 defers marketing tags. |

### 23.2 Interaction Responsiveness

| Interaction | Target Response Time | Measurement |
|---|---|---|
| Button click → visual feedback | < 100ms | CSS `:active` pseudo-class (instant — no JavaScript needed) |
| Form validation on blur | < 50ms (client-side validation is instant) | Gravity Forms built-in |
| AJAX cart update | < 500ms | WooCommerce AJAX. User sees button state change immediately. |
| Page navigation (link click → page start loading) | < 200ms | Standard browser navigation |
| Form submission → redirect | < 2 seconds | Gravity Forms + Post SMTP |
| Dropdown/accordion open | < 16ms (single frame) | CSS transition starts immediately. JavaScript only toggles class. |

### 23.3 Scroll Performance

- No scroll event listeners that modify the DOM without `requestAnimationFrame` or `IntersectionObserver`
- Sticky elements: use CSS `position: sticky` (GPU-accelerated, no JavaScript)
- Scroll-linked animations: use `IntersectionObserver` (not scroll events) for sticky CTA visibility, header shadow, back-to-top button
- `passive: true` on any touch/wheel event listeners

### 23.4 Animation Performance

- Only animate `transform` and `opacity` (compositor-only properties — no layout or paint triggered)
- The sole exception: `max-height` for accordion/collapse (acceptable — the only layout-triggering animation and it's user-initiated, not continuous)
- Use `will-change` sparingly and only on elements that actually animate (remove after animation ends to free GPU memory)
- Use `contain: layout style paint` on cards and other self-contained components to isolate layout calculations

---

## 24. Acceptance Criteria

### 24.1 Responsive Behavior

| # | Criterion | Pass Condition |
|---|---|---|
| AC-RIS01 | Mobile menu opens/closes with correct animation and focus management | Test: iPhone 14 viewport. Tap hamburger → overlay slides in. Tab → focus trapped within. Escape → closes, focus returns to hamburger. |
| AC-RIS02 | Desktop dropdowns open on hover, focus, and close correctly | Test: 1280px viewport. Hover parent → dropdown opens after 150ms. Tab to parent → opens via :focus-within. Escape closes. |
| AC-RIS03 | All grids collapse to 1 column on mobile | Test: 375px viewport. Service card grid, USP grid, blog grid, product grid all 1 column. No horizontal scroll. |
| AC-RIS04 | Tables scroll horizontally, do not collapse | Test: 375px viewport. Cart table, checkout order review: horizontal scroll with gradient indicator. |
| AC-RIS05 | Sticky header shadow appears on scroll | Test: scroll down 1px on desktop. Header gains shadow. Scroll to top. Shadow removed. |
| AC-RIS06 | Sticky mobile CTA appears/disappears correctly | Test: 375px viewport. Service page. Scroll past hero → CTA appears. Scroll past in-page CTA banner → CTA hides. Scroll up → CTA re-appears. |

### 24.2 Form Behavior

| # | Criterion | Pass Condition |
|---|---|---|
| AC-RIS07 | Inline validation triggers on blur and submit | Test: leave email field with invalid value → red border + error message appears. Correct value → error clears on focus. |
| AC-RIS08 | Form error summary appears on invalid submit | Test: submit empty contact form. Error summary at top: "Corrigeer de volgende velden". Each item links to field. Focus moves to first error. |
| AC-RIS09 | Loading state appears during form submission | Test: submit valid contact form. Button text changes to "Versturen..." with spinner. Button disabled. |
| AC-RIS10 | Success redirect works | Test: submit valid form. Redirected to /bedankt/?type=contact. Page shows correct dynamic heading. |
| AC-RIS11 | Network error shows fallback text | Test: simulate network failure during form submit. Inline error appears. Phone fallback visible. |
| AC-RIS12 | File upload validates type and size | Test: upload .exe file → error. Upload 6MB file → error. Upload valid PDF → accepted. |

### 24.3 Interaction States

| # | Criterion | Pass Condition |
|---|---|---|
| AC-RIS13 | All buttons show correct hover/focus/active/disabled/loading states | Test: interact with each button variant in each state. Visual change matches spec. |
| AC-RIS14 | Cards show lift + shadow on hover | Test: hover service card. 2px lift + shadow-md. 150ms transition. Touch: no sticky hover. |
| AC-RIS15 | Focus ring visible on all interactive elements | Test: Tab through entire page. Every interactive element shows outline. |
| AC-RIS16 | Disabled elements not interactive | Test: disabled button. Cannot click. Opacity 0.5. Cursor not-allowed. Tab still reaches for announcement. |

### 24.4 Accessibility Behavior

| # | Criterion | Pass Condition |
|---|---|---|
| AC-RIS17 | Skip-to-content link works | Test: load page. Tab → skip link visible. Enter → focus moves to main. |
| AC-RIS18 | Keyboard navigation through entire page | Test: Tab through every page type. No keyboard traps. All elements reachable. |
| AC-RIS19 | Screen reader announces dynamic content | Test: NVDA. Add item to cart → announcement heard. Form error → error announced on field focus. |
| AC-RIS20 | Reduced motion disables all animations | Test: enable OS setting. Re-test all animations. All instant. |

### 24.5 WooCommerce Behavior

| # | Criterion | Pass Condition |
|---|---|---|
| AC-RIS21 | Add to cart works via AJAX | Test: click "In winkelwagen". Item added. Page does not reload. Cart badge animates. Toast appears. |
| AC-RIS22 | Cart quantity update works via AJAX | Test: click +/- on cart page. Quantity updates. Subtotal updates. Totals update. |
| AC-RIS23 | Cart item removal animates | Test: click ✕ on cart item. Row fades out + collapses. Totals update. |
| AC-RIS24 | Checkout payment method selection works | Test: select different payment methods. Fields appear/disappear. Order review updates. |
| AC-RIS25 | Checkout place order → loading → Mollie redirect | Test: place order. Button shows loading. Redirected to Mollie payment page. |

### 24.6 Performance

| # | Criterion | Pass Condition |
|---|---|---|
| AC-RIS26 | Button click → visual feedback < 100ms | Test: click button. `:active` state visible instantly (CSS pseudo-class). |
| AC-RIS27 | AJAX cart update < 500ms | Test: adjust cart quantity. Network tab: response time < 500ms. |
| AC-RIS28 | First Input Delay (FID) < 100ms | PSI / Chrome UX Report: FID < 100ms. |
| AC-RIS29 | No layout shift during interaction | Test: all interactions. CLS does not increase. Explicit dimensions on images. |
| AC-RIS30 | Scroll performance smooth (60fps) | Test: scroll through page. No jank. DevTools Performance: no long tasks > 50ms during scroll. |

---

*End of Responsive & Interaction Specification — RIS-001 v1.0.0*

*This specification defines all interactive behaviors, responsive adaptations, animations, and accessibility interactions. Frontend development may implement behavior directly from this document.*
