# Part 5: Components, Design System, CMS, Templates, Blocks, WooCommerce, Search, Errors, Logging

**HDS Onderhoudsdiensten — Production Build Specification — Part 5 of 8**

---

## 25. Component Inventory

### 25.1 Global Components (Every Page)

| Component | Notes |
|---|---|
| Header | Logo, nav, phone, email, cart icon. Optional sticky on scroll. |
| Main Navigation | Desktop dropdown, mobile hamburger/accordion. |
| Footer | Multi-column with links, contact info, legal, copyright. |
| Cookie Consent Banner | On first visit. Complianz managed. |
| Cookie Settings Button | Floating, reopens consent modal. |
| Breadcrumbs | Per-page (except Home). Yoast/Rank Math + theme. |
| Back to Top Button | Floating (mobile). Appears after 300px scroll. |
| Skip to Content Link | First focusable element. |

### 25.2 Page-Level Components

| Component | Used On |
|---|---|
| Hero Banner | Home, Service pages, Category landings |
| Service Card Grid | Home, Category landings |
| USP / Feature Cards | Home, About |
| Client Logo Carousel | Home, Referenties |
| Testimonial Block | Home, Service pages, Referenties |
| CTA Banner | Home, Service pages, About |
| Content Block | Service pages, About, Downloads, FAQ |
| Service Detail List (icon bullets) | Service pages |
| Cross-Sell Section | Service pages |
| FAQ Accordion | Veelgestelde Vragen |
| Job Vacancy Card | Vacatures |
| Download Card List | Downloads |
| Contact Info Block + Map | Contact, Footer |
| Contact Form | Contact, Offerte Aanvragen |
| Blog Post Card | Blog index, Home |
| Related Posts | Blog post |
| 404 Content Block | 404 page |
| WooCommerce Product Grid | Winkel, Product category |
| WooCommerce Single Product | Product detail |
| Thank You Message | Bedankt |

---

## 26. Design System Requirements

### 26.1 Brand Identity (Preserved from Current Site)

| Element | Value |
|---|---|
| Company Name | HDS Onderhoudsdiensten (unchanged) |
| Tagline | "Helder en Duidelijk voor het Schoonste resultaat!" (unchanged) |
| Domain Branding | HelderDuidelijkSchoon.nl (unchanged) |
| Brand Colors | Not formally defined. Turquoise/blue from existing icons. **MISSING INFORMATION — client to provide or approve brand color palette.** |

### 26.2 Design Tokens (To Be Defined)

The following must be defined before development. **All are MISSING INFORMATION unless stated otherwise.**

| Token Category | Tokens | Status |
|---|---|---|
| **Color Palette** | Primary, Secondary, Accent, Neutral (white, gray-100 through gray-900), Success (green), Warning (amber), Error (red), Info (blue) | MISSING INFORMATION |
| **Typography** | Heading font, body font (currently Open Sans). Font sizes (H1-H6, body, small). Weights. Line heights. Letter spacing. | MISSING — keep Open Sans unless client wants change |
| **Spacing Scale** | 4px-based: 4, 8, 12, 16, 20, 24, 32, 40, 48, 64, 80, 96, 128 | Standard — recommend adoption |
| **Border Radius** | None (0), small (4px), medium (8px), large (16px), pill (9999px) | Standard |
| **Shadows** | None, sm, md, lg, xl | Standard |
| **Breakpoints** | Mobile: 0-767px, Tablet: 768-1023px, Desktop: 1024-1279px, Wide: 1280px+ | Standard |
| **Container Max-Width** | 1200px or 1280px | Design decision |
| **Icon Library** | Phosphor Icons OR Font Awesome 6 OR custom SVG | Design decision |

### 26.3 Typography Specification

| Element | Size (Desktop) | Size (Mobile) | Weight | Line Height |
|---|---|---|---|---|
| H1 (page title) | 36-48px | 28-36px | 700 | 1.2 |
| H2 (section heading) | 28-36px | 22-28px | 700 | 1.3 |
| H3 (subsection) | 22-28px | 18-24px | 600 | 1.3 |
| H4 | 18-22px | 16-20px | 600 | 1.4 |
| Body | 16-18px | 16px | 400 | 1.6-1.7 |
| Small / Caption | 14px | 14px | 400 | 1.5 |
| Button Text | 16px | 16px | 600 | 1.0 |
| Nav Links | 16px | 18px (touch) | 500-600 | 1.0 |

---

## 27. Reusable Components

### 27.1 Component Architecture

All reusable components built as:
1. **WordPress Block Patterns** (layout composition in the editor)
2. **Custom Gutenberg Blocks** (components requiring custom fields or dynamic data)
3. **Template Parts** (header, footer)

### 27.2 Block Patterns Required

| Pattern | Fields | Used In |
|---|---|---|
| Hero Section | Heading, Subtitle, CTA (URL + text), BG Image, BG Overlay Color, Alignment | Home, Service, Category landings |
| Service Card Grid | Service selection (multi-select from pages), Columns | Home, Category landings |
| USP Grid | USPs (repeater: icon, title, text), Columns, BG color | Home, Over HDS |
| CTA Banner | Heading, Text, Button (URL + text), BG Color/Image, Full-width toggle | Home, Service, anywhere |
| Content with Image | Image (left/right/stacked), Heading, Content | Service, About |
| Service List (icon bullets) | List items (repeater: icon, text) | Service pages |
| Client Logo Grid/Carousel | Logos (repeater: image, alt text, optional link) | Home, Referenties |
| Testimonial Block | Quote, Author, Company, Optional star rating & photo | Home, Referenties, Service |
| FAQ Accordion | Questions (repeater: question, answer) | Veelgestelde Vragen, Service |
| Cross-Sell Services | Service selection (multi-select), Title override | Service pages |
| Job Listing | Job title, Hours, Location, Description, Contact | Vacatures |
| Download Card | File name, Description, File URL, File size, File type icon | Downloads |
| Contact Info + Map | Address, Phone, Email, Map iframe | Contact |
| Latest Blog Posts | Number of posts, Category filter, Columns | Home, Category landings |
| Related Posts | Number of posts, Category filter | Blog post |
| 404 Content | Heading, Text, Search form | 404 page |

### 27.3 Custom Blocks (If Patterns Alone Are Insufficient)

| Block | Purpose |
|---|---|
| `hds/service-card` | Single service card with link. Used in Service Card Grid. |
| `hds/testimonial` | Queries testimonials CPT and renders. |
| `hds/job-listing` | Queries vacancies CPT and renders. |
| `hds/contact-info` | Renders NAP + map from site settings. |

---

## 28. CMS Architecture

### 28.1 WordPress Configuration

| Setting | Value |
|---|---|
| **Permalink Structure** | `/%postname%/` (Post name) |
| **Category Base** | `kennisbank` (changed from `category`) |
| **Tag Base** | `onderwerp` |
| **Default Category** | "Nieuws" (renamed from "Geen categorie") |
| **Time Zone** | Europe/Amsterdam |
| **Date Format** | `j F Y` (20 juli 2026) |
| **Time Format** | `H:i` |
| **Language** | Nederlands (nl_NL) |
| **Media Sizes** | Thumbnail: 150x150, Medium: 600x600 (no crop), Large: 1200x1200 (no crop), Custom: 400x300 (service card), 800x600 (content), 1600x900 (hero). Disable unused default sizes. |
| **Comments** | Disabled site-wide. Remove from all post types. |
| **Pingbacks/Trackbacks** | Disabled. |
| **Post via Email** | Disabled. |
| **Search Engine Visibility** | Enabled. |

### 28.2 Custom Post Types

| CPT | Slug | Purpose | Supports |
|---|---|---|---|
| `hds_testimonial` | `referenties` | Client testimonials separate from page content | Title, Editor (quote), Featured Image (author photo), Custom Fields (author name, company, star rating) |
| `hds_vacancy` | `vacatures` | Individual job listings | Title, Editor, Custom Fields (hours, location, contact email) |
| `hds_faq` | `faq` | FAQ items | Title (question), Editor (answer) |

**Decision for simpler architecture:** Use standard WordPress Pages for all service pages (not a custom post type).

### 28.3 Custom Fields

| Field Group | Attached To | Fields |
|---|---|---|
| **Service Page Settings** | Page (template: Service) | Subtitle, Hero Image, Icon (for service card), CTA override text |
| **Testimonial Details** | `hds_testimonial` | Author name, Company name, Star rating (1-5), Related service |
| **Vacancy Details** | `hds_vacancy` | Hours per week, Location, Start date, Application email, Deadline, Is active (toggle) |
| **Company Information** | Theme Settings / Customizer | Address, Postal code, City, Phone, Email, KVK, BTW, Facebook URL, Instagram URL, GBP URL, Opening hours (repeater: day, opens, closes) |
| **SEO Overrides** | All post types | Handled by Yoast/Rank Math natively — no custom fields needed. |

### 28.4 User Roles

| Role | Capabilities | Assigned To |
|---|---|---|
| **Administrator** | Full access | Developer, Site owner |
| **Editor** | Manage content, moderate comments (if enabled), form entries, analytics | Business owner, internal content manager |
| **Shop Manager** | Manage WooCommerce products, orders, coupons | Business owner |
| **SEO Manager** | Access Yoast/Rank Math, Google Site Kit | Marketing person |
| **Subscriber** | Read-only (WooCommerce customers) | Customers |

**Minimum 2 admin accounts for redundancy.**

---

## 29. Page Templates

### 29.1 Template Files

| Template File | Applied To |
|---|---|
| `front-page.php` | Home page |
| `page-service.php` | All service pages (P02-P08) |
| `page-category-landing.php` | Category landing pages (P09, P10) |
| `page-about.php` | Over HDS, Kwaliteit & Veiligheid (P11, P12) |
| `page-contact.php` | Contact (P16) |
| `page-quote.php` | Offerte Aanvragen (P17) |
| `page-faq.php` | Veelgestelde Vragen (P18) |
| `page-legal.php` | Privacyverklaring, Cookiebeleid, Algemene Voorwaarden, Disclaimer (P19-P22) |
| `page.php` | Default — Referenties, Vacatures, Downloads, others |
| `archive.php` | Blog index (P29) |
| `single.php` | Blog posts (P30) |
| `404.php` | 404 error page (P31) |
| WooCommerce templates | Handled by plugin (`woocommerce/` overrides in theme if needed) |

### 29.2 Template: Home (`front-page.php`)

```
Header
|-- Hero Section (Block Pattern)
|-- Service Card Grid (Block Pattern)
|-- USP Section (Block Pattern)
|-- Client Logo Carousel (Block Pattern — if logos available)
|-- Testimonial Block (Block Pattern — if testimonials available)
|-- CTA Banner (Block Pattern)
|-- Service Area Section (Block Pattern)
|-- Latest Blog Posts (Block Pattern — if blog active)
Footer
```

All sections editable via Block Editor. NOT hardcoded. Use `the_content()` with block patterns pre-loaded as default content.

### 29.3 Template: Service Page (`page-service.php`)

```
Header
|-- Breadcrumbs
|-- Hero Section (H1, subtitle, CTA -> /offerte-aanvragen/)
|-- Content Area (the_content())
|   |-- Intro Paragraph
|   |-- Approach / Process (H2 + text)
|   |-- Service Detail List (icon bullets)
|   |-- Safety & Quality (H2 + text)
|-- Cross-Sell Services ("Gerelateerde diensten")
|-- CTA Banner ("Vrijblijvende offerte aanvragen")
|-- Optional: FAQ Accordion
Footer
```

### 29.4 Template: Contact (`page-contact.php`)

```
Header
|-- Breadcrumbs
|-- Page Title (H1: "Contact")
|-- Two-Column Layout
|   |-- Left: Contact Form (Gravity Forms shortcode)
|   |-- Right: Contact Info Block (phone, email, address [MISSING], KVK/BTW [MISSING], hours [MISSING], social, map [if address known])
Footer
```

### 29.5 Template: Blog Post (`single.php`)

```
Header
|-- Breadcrumbs
|-- Article
|   |-- Featured Image
|   |-- Title (H1)
|   |-- Meta: Date, Category, Reading Time
|   |-- Content (the_content())
|-- Related Posts (Block Pattern — 3 articles)
|-- CTA Banner
Footer
```

---

## 30. Block Specifications

### 30.1 Core Blocks (Available)

All standard WordPress core blocks available: Paragraph, Heading, List, Image, Gallery, Quote, Button, Columns, Group, Cover, Embed, etc.

### 30.2 Custom / Third-Party Blocks

| Block | Source | Purpose |
|---|---|---|
| Yoast FAQ Block | Yoast SEO | FAQ accordion with structured data |
| Gravity Forms Block | Gravity Forms | Embed forms |
| Kadence Blocks (optional) | Kadence Blocks plugin | Row layout, info box, icon list, advanced button. Only if Kadence theme selected. Otherwise core blocks only. |

### 30.3 Block Style Variations

| Block | Style Variation | Description |
|---|---|---|
| Button | `is-style-primary` | Filled primary color |
| Button | `is-style-secondary` | Outlined secondary |
| Button | `is-style-cta` | Large CTA with arrow icon |
| Group | `is-style-card` | White bg, border-radius, shadow |
| Group | `is-style-banner` | Colored bg, full-width |
| Separator | `is-style-wide` | Wider, thicker |
| List | `is-style-icon-list` | Custom icon bullets (checkmark/arrow) |
| List | `is-style-no-bullet` | No bullet points |

### 30.4 Block Patterns (Pre-Built Layouts)

Each section in Section 27.2 is registered as a Block Pattern via PHP in the theme. Editors insert with one click.

---

## 31. WooCommerce Requirements

### 31.1 Core Configuration

| Setting | Value |
|---|---|
| **Shop Page** | Winkel (`/winkel/`) |
| **Cart Page** | Winkelmand (`/winkelmand/`) |
| **Checkout Page** | Afrekenen (`/afrekenen/`) |
| **My Account Page** | Mijn Account (`/mijn-account/`) |
| **Terms Page** | Algemene Voorwaarden (`/algemene-voorwaarden/`) |
| **Privacy Page** | Privacyverklaring (`/privacyverklaring/`) |
| **Currency** | EUR |
| **Currency Position** | Left |
| **Thousand Separator** | `.` (dot) |
| **Decimal Separator** | `,` (comma) |
| **Decimals** | 2 |
| **Prices Entered With Tax** | No (prices entered excl. BTW — matches current site) |
| **Display Prices Suffix** | "excl. BTW" (matches current site) |
| **Weight Unit** | kg |
| **Dimension Unit** | cm |
| **Enable Coupons** | Yes |
| **Enable Reviews** | Yes (moderated) |
| **Enable Guest Checkout** | Yes |
| **Allow Backorders** | No |
| **Inventory Management** | Yes |

### 31.2 Payment Gateway

| Gateway | Recommendation | Notes |
|---|---|---|
| **Mollie** (recommended) | Mollie for WooCommerce | Dutch provider. iDEAL, Bancontact, credit cards, PayPal, SEPA. No monthly fee. |
| **Stripe** (alternative) | WooCommerce Stripe Gateway | International. iDEAL, cards. |
| **Bank Transfer** | Built-in BACS | "Handmatige overboeking" for B2B invoice-based payment. |

**MISSING INFORMATION:** Client must decide payment methods and gateway. Mollie recommended for Dutch B2B/B2C.

### 31.3 Shipping Configuration

| Setting | Value |
|---|---|
| **Shipping Zones** | Nederland (default) |
| **Shipping Methods** | Flat rate OR free over EUR X,00 OR weight-based |
| **Shipping Classes** | "Klein pakket" (filters, lamps), "Groot pakket" (Airfixr units) |

**MISSING INFORMATION:** Client must provide shipping costs and policy.

### 31.4 Tax (BTW) Configuration

| Setting | Value |
|---|---|
| **Prices Entered With Tax** | No (excl. BTW) |
| **Tax Rate** | 21% (hoog tarief — standard Dutch BTW) |
| **Display in Cart/Checkout** | Show BTW as separate line item |
| **Price Display Suffix** | "excl. BTW" in shop. "incl. BTW" in cart/checkout total. |

**NOTE:** Current site displays prices excl. BTW. Common for B2B but may confuse B2C. **Client to confirm whether to keep excl. BTW or switch to incl. BTW.**

### 31.5 WooCommerce Emails

All email notifications enabled, branded with logo, in Dutch. From: "HDS Onderhoudsdiensten" `<info@helderduidelijkschoon.nl>`.

### 31.6 Airfixr Product Landing Page (`/luchtreiniging/`)

Dedicated landing page introducing Airfixr:
- What Airfixr is and why HDS sells it
- Connection between air purification and cleaning services
- Highlight key products with shop links
- CTA: "Bekijk alle producten" -> `/winkel/`

---

## 32. Search Requirements

### 32.1 Site Search

| Requirement | Implementation |
|---|---|
| **Search location** | Footer (global) + 404 page. Optional: header search icon. |
| **Search scope** | Pages, blog posts, products. Exclude: legal pages, thank-you, cart, checkout, account. |
| **Results page** | Custom `search.php`: title (linked), excerpt with highlighted term, post type label. "Geen resultaten" message with suggested links. |
| **Search plugin** | Relevanssi (better Dutch handling, partial matching, relevance) OR native if site <50 pages. |
| **Ajax live search** | Optional (P3). If implemented: dropdown top 5 results. Not critical for launch. |

### 32.2 Search Performance

- Results load in under 1 second
- Relevanssi index updated automatically on content change
- No search queries logged in analytics by default (privacy-friendly)

---

## 33. Error Handling

### 33.1 404 Error Page (`404.php`)

Must include:
1. Heading: "Pagina niet gevonden"
2. Message: "De pagina die u zoekt bestaat niet of is verplaatst."
3. Search bar (prominent)
4. Links: Home, Diensten overview, Contact, Veelgestelde Vragen
5. Phone number and email
6. Must return actual HTTP 404 status code

### 33.2 PHP Error Handling

| Environment | Behavior |
|---|---|
| **Production** | `WP_DEBUG` = `false`. `WP_DEBUG_DISPLAY` = `false`. `WP_DEBUG_LOG` = `true`. Errors logged, never displayed. |
| **Staging** | `WP_DEBUG` = `true`. Errors displayed. |
| **Custom 500 page** | Server-level (not WP-dependent). "Er is een technische storing" + contact phone. |

### 33.3 Form Error Handling

| Scenario | Behavior |
|---|---|
| **Validation failure** | Inline field errors (Gravity Forms default). Red text, `aria-describedby` association. |
| **Server error (submission fails)** | "Er is een fout opgetreden bij het verzenden. Probeer het opnieuw of neem telefonisch contact op via 0164-652846." Logged. |
| **Spam detection** | Silent failure (reCAPTCHA blocks). Backend logs. |
| **File too large** | "Het bestand is te groot. Maximale grootte: 5 MB." |
| **File wrong type** | "Dit bestandstype is niet toegestaan. Toegestane types: PDF, JPG, PNG, DOCX." |

### 33.4 WooCommerce Error Handling

- Out of stock: "Niet op voorraad"
- Cart errors: inline notice
- Checkout errors: inline field errors with clear instructions
- Payment failure: specific gateway error message
- All errors logged for admin review

---

## 34. Logging Requirements

### 34.1 Logs to Maintain

| Log | Tool | Content |
|---|---|---|
| **PHP Error Log** | WordPress debug.log | All PHP errors, warnings, notices |
| **Security Log** | Wordfence | Login attempts, blocked IPs, file changes, malware scans |
| **Form Submission Log** | Gravity Forms entries | All form submissions with timestamps and data |
| **WooCommerce Order Log** | WooCommerce Status Logs | Payment gateway errors, fatal errors |
| **Backup Log** | Backup plugin | Success/failure per run |
| **Update Log** | Manual or MainWP | Core, theme, plugin updates with dates |
| **Uptime Log** | UptimeRobot | Downtime events with duration |

### 34.2 Log Retention

| Log Type | Retention |
|---|---|
| PHP errors | 30 days |
| Security logs | 90 days |
| Form entries | 12 months (auto-delete) |
| WooCommerce orders | 7 years (legal requirement — Dutch financial data) |
| Backup logs | 12 months |
| Uptime logs | 12 months |

### 34.3 Monitoring and Alerts

| Event | Alert | Recipient |
|---|---|---|
| Website down | Email + push (UptimeRobot) | Developer + client |
| SSL expiring | Email (UptimeRobot/hosting) | Developer |
| Malware detected | Email (Wordfence) | Developer |
| Backup failure | Email (backup plugin) | Developer |
| Disk space >80% | Email (hosting) | Developer |
| Updates available | Dashboard notification | Developer |
| Form submission | Email (Gravity Forms) | info@helderduidelijkschoon.nl |
