# Part 2: Navigation, URL Strategy, Redirects, Content Migration, Media Migration, SEO Migration

**HDS Onderhoudsdiensten — Production Build Specification — Part 2 of 8**

---

## 6. Navigation Specification

### 6.1 Main Navigation (Desktop)

```
[LOGO]    DIENSTEN v    OVER HDS v    LUCHTREINIGING v    CONTACT    [TEL] [CART]
```

### 6.2 Dropdown Menus

**DIENSTEN dropdown:**
```
Glas & Gevel               (Link to /glas-en-gevel/)
  |-- Glasbewassing           (Link to /glasbewassing/)
  |-- Gevelreiniging          (Link to /gevelreiniging/)

Schoonmaakdiensten         (Link to /schoonmaakdiensten/)
  |-- Reguliere Schoonmaak    (Link to /reguliere-schoonmaak/)
  |-- Vloeronderhoud          (Link to /vloeronderhoud/)
  |-- VVE Service             (Link to /vve-service/)
  |-- Oplevering Schoonmaak   (Link to /oplevering-schoonmaak/)
  |-- Industriele Schoonmaak  (Link to /industriele-schoonmaak/)
```

**OVER HDS dropdown:**
```
Over HDS                   (Link to /over-hds/)
Kwaliteit & Veiligheid     (Link to /kwaliteit-veiligheid/)
Referenties                (Link to /referenties/)
Vacatures                  (Link to /vacatures/)
Downloads                  (Link to /downloads/)
```

**LUCHTREINIGING dropdown:**
```
Over Airfixr               (Link to /luchtreiniging/)
Winkel                     (Link to /winkel/)
Mijn Account               (Link to /mijn-account/)
```

### 6.3 Mobile Navigation

Hamburger menu with accordion-style expandable sections matching desktop dropdown groups. All items visible; no horizontal scroll. Touch targets minimum 44x44px (WCAG 2.5.8).

### 6.4 Footer Navigation

```
DIENSTEN              OVER HDS              CONTACT
Glasbewassing         Over HDS              Telefoon: 0164-652846
Gevelreiniging        Kwaliteit & Veiligheid Email: info@helderduidelijkschoon.nl
Reguliere Schoonmaak  Referenties           Adres: [MISSING]
Vloeronderhoud        Vacatures             KVK: [MISSING] | BTW: [MISSING]
VVE Service           Downloads
Oplevering Schoonmaak                       JURIDISCH
Industriele Schoonmaak  LUCHTREINIGING      Privacyverklaring
                        Luchtreiniging      Cookiebeleid
                        Winkel              Algemene Voorwaarden
                                            Disclaimer
---------------------
(c) {YEAR} HDS Onderhoudsdiensten | [FB icon] [IG icon]
```

### 6.5 Homepage Content Blocks (Top to Bottom)

| # | Block | Content | Link |
|---|---|---|---|
| 1 | Hero Banner | Tagline + USP summary + CTA button | `/offerte-aanvragen/` |
| 2 | Service Overview Grid | 7 service cards (icon + title + 1-line desc + link) | Each links to service page |
| 3 | Why HDS (USP Section) | 4-6 USP cards: vast opgeleid personeel, veiligheid & certificering, een aanspreekpunt, maatwerk, milieubewust, regio specialist | — |
| 4 | Referenties / Klanten | Logo carousel/grid of client logos (with permission) | Link to `/referenties/` |
| 5 | Beoordelingen | 3-5 testimonials with star rating, name, company | — |
| 6 | CTA Banner | "Wilt u een vrijblijvende offerte?" + button | `/offerte-aanvragen/` |
| 7 | Service Area | Map or text listing service region | — |
| 8 | Latest Blog Posts | 3 most recent articles | `/kennisbank/` |

---

## 7. URL Strategy

### 7.1 URL Conventions

| Rule | Specification |
|---|---|
| **Protocol** | HTTPS only. HTTP — 301 redirect to HTTPS. |
| **www / non-www** | `https://helderduidelijkschoon.nl` (non-www canonical). `www.` — 301 to non-www. |
| **Trailing Slash** | **Consistently WITH trailing slash**. `/glasbewassing/` canonical. No-slash — 301. |
| **Slug Language** | Dutch, lowercase, hyphens. Example: `/oplevering-schoonmaak/`. |
| **Depth** | Maximum 1 level. Exception: WooCommerce `/product/{slug}/` and blog `/kennisbank/{slug}/`. |
| **Special Characters** | No diacritics in URLs. Use `industriele` not `industrieele`. |
| **No file extensions** | No `.html`, `.php`, `.asp`. Clean URLs only. |
| **No query parameter pages** | No `?page_id=318`. All pages use descriptive slugs. |
| **No dates in blog URLs** | `/kennisbank/{post-slug}/` not `/2025/01/title/`. |
| **WooCommerce base** | Keep `/winkel/` as shop base. Products at `/product/{slug}/`. |

### 7.2 Reserved/Blocked URLs (Return 403 or 410)

| URL Pattern | Status | Reason |
|---|---|---|
| `/xmlrpc.php` | 403 | Brute-force attack vector |
| `/wp-admin/` | 403 (rate-limited) | IP-restricted |
| `/wp-login.php` | Custom URL via Wordfence | Obscured login path |
| `/wp-json/wp/v2/users` | Block REST API filter | Prevent user enumeration |
| `/?author={N}` | 403 | Prevent user enumeration |

---

## 8. Redirect Strategy (301 Mapping)

### 8.1 Old-to-New 301 Redirects

| Old URL | HTTP Status | New URL | Notes |
|---|---|---|---|
| `/glasbewassing` (no trailing slash) | 301 | `/glasbewassing/` | Trailing slash standardization |
| `/vve` | 301 | `/vve-service/` | Canonical URL |
| `/vve/` (if accessible) | 301 | `/vve-service/` | Canonical URL |
| `/?page_id=318` | 301 | `/reguliere-schoonmaak/` | Only if new page has same content |
| `/gevelreiniging/` | Keep as-is | `/gevelreiniging/` | No redirect; nav label updated to match |

### 8.2 410 Gone (Content Permanently Removed)

| Old URL | New Status | Notes |
|---|---|---|
| `/2015/06/29/hallo-wereld/` | 410 | Default WordPress post deleted |
| `/2015/08/25/kwaliteit-veiligheid/` | 410 | Redirect post deleted |

### 8.3 HTTPS and Domain Canonicalization

| Old URL | 301 Redirect |
|---|---|
| `http://helderduidelijkschoon.nl/*` | `https://helderduidelijkschoon.nl/*` |
| `http://www.helderduidelijkschoon.nl/*` | `https://helderduidelijkschoon.nl/*` |
| `https://www.helderduidelijkschoon.nl/*` | `https://helderduidelijkschoon.nl/*` |

### 8.4 Legacy Domain Handling

| Legacy Domain | Action |
|---|---|
| `hds-onderhoudsdiensten.nl` (PDF hosting) | Migrate all PDFs to `helderduidelijkschoon.nl`. 301 redirects from old PDF URLs to new. Once confirmed, point legacy domain to primary OR maintain redirects. **Decision needed from client.** |

---

## 9. Content Migration Strategy

### 9.1 Approach

**Manual migration with rewrite.** Existing content is too thin and inconsistent to auto-migrate. All content must be manually reviewed, rewritten, and entered into the new CMS.

### 9.2 Content Migration Priority Tiers

| Tier | Pages | Approach |
|---|---|---|
| **Tier 1 — Keep and Rewrite** | Home, Glasbewassing, Gevelreiniging, Vloeronderhoud, VVE Service, Oplevering Schoonmaak, Kwaliteit & Veiligheid, Over HDS | Migrate existing Dutch copy, expand to 300-500+ words per page, add H2/H3 hierarchy, images, CTAs |
| **Tier 2 — Rebuild from Scratch** | Reguliere Schoonmaak, Industriele Schoonmaak, Referenties, Vacatures, Downloads, Contact | Content broken or extremely thin. Write entirely new content. |
| **Tier 3 — Create New** | Offerte Aanvragen, Veelgestelde Vragen, Privacyverklaring, Cookiebeleid, Algemene Voorwaarden, Disclaimer, Luchtreiniging, Glas & Gevel landing, Schoonmaakdiensten landing, Blog posts, 404, Bedankt | No content exists. All new. |
| **Tier 4 — Preserve** | WooCommerce products (14), Winkel, Winkelmand, Afrekenen, Mijn Account | Functional eCommerce structure. Product data migrated. Shop intro and category descriptions written. |

### 9.3 Minimum Content Per Page Type

**Service Page:**
1. H1: Service name
2. Intro paragraph (50-80 words)
3. Our approach / process — H2, 100-150 words
4. Service details (bullet list) — H2 + list
5. Safety & quality — H2, 50-80 words
6. Call to action button — "Vrijblijvende offerte aanvragen"
7. Related services (2-3 cross-links)

**Minimum word count:** 300 words (service pages), 500+ words (landing pages, About), 150-300 (legal, FAQ).

### 9.4 Content Requiring Client Input

The following cannot be written without client input. Marked as "Missing Information Required Before Development":

- Company history, founding year, founder/owner name
- Client names and logos for Referenties page (with written permission)
- Testimonial text, names, and companies
- Before/after project photos
- Team member names, roles, and photos (optional)
- Service area — specific municipalities/postcodes
- Business hours
- Airfixr product descriptions and USPs
- Job vacancy details (full text, not images)
- Terms & Conditions text for HTML page

---

## 10. Media Migration Strategy

### 10.1 Image Migration Rules

| Rule | Specification |
|---|---|
| **Format** | All images WebP with PNG/JPEG fallback. AVIF where browser support allows. |
| **Resolution** | Logo: SVG vector (primary), 2x PNG fallback 400x162px. Icons: inline SVG. Service photos: 1200px wide minimum, optimized to less than 150KB. |
| **Compression** | All images compressed losslessly or visually-lossless (quality 85+). |
| **Lazy Loading** | `loading="lazy"` on all images below fold. `fetchpriority="high"` on hero/LCP image. |
| **Alt Text** | Descriptive Dutch alt text on every image. Service images must describe the service performed. Decorative: `alt=""`. |
| **Responsive** | `srcset` and `sizes` on all content images. Minimum: 400w, 800w, 1200w variants. |
| **Filenames** | Lowercase, hyphens, Dutch keywords. Example: `glasbewassing-kantoor-bergen-op-zoom.webp`. |

### 10.2 Current Assets to Migrate or Replace

| Asset | Action |
|---|---|
| Logo (`hds200x81.png`) | Request original vector. If unavailable, recreate as SVG. |
| Homepage icons (8 PNGs) | **Replace entirely.** New inline SVG icon library. |
| Client logo (`Afbeelding6-2.png`) | Migrate if relevant. Request higher resolution. |
| Quality & Safety images (2) | Migrate. Optimize. |
| VVE Service images (4) | Migrate. Optimize. Add alt text. |
| Vloeronderhoud image (`industriele1.jpg`) | Migrate. Optimize. |
| Vacancy JPG posters (2) | **DO NOT MIGRATE.** Replace with HTML text. |
| Airfixr product images (15) | Migrate. Optimize with consistent dimensions. |
| Download icons (2) | Replace with SVG icons. |
| Unplaced/orphan images | Audit. Place, reassign, or delete. |

### 10.3 New Media Required

| Image Type | Quantity | Notes |
|---|---|---|
| Hero image / banner | 1 | Cleaning staff in uniform at client site or abstract. **MISSING INFORMATION.** |
| Service page hero images | 7 | One per service page. **MISSING INFORMATION — client to provide real project photos.** |
| Team photo | 1-3 | For Over HDS page. **MISSING INFORMATION.** |
| Before/after project photos | 6-10 | For Referenties and case studies. **MISSING INFORMATION.** |
| Certification logos | 3-5 | OSB, VCA (if applicable), Arbo. **MISSING INFORMATION.** |
| Open Graph / social share image | 1 | 1200x630 px branded share image. |

### 10.4 PDF Migration

| PDF | Current Host | Action |
|---|---|---|
| Algemene voorwaarden schoonmaak | `hds-onderhoudsdiensten.nl` | Migrate to `helderduidelijkschoon.nl` media library. Update download link. |
| Algemene voorwaarden gevelreiniging | `hds-onderhoudsdiensten.nl` | Same as above. |
| Any additional PDFs | Unknown | Search legacy domain for additional PDFs and migrate. |

---

## 11. SEO Migration Strategy

### 11.1 Pre-Migration SEO Baseline

Before taking the old site offline:
1. Full crawl of current site (Screaming Frog or Sitebulb)
2. Export all URLs with HTTP status codes
3. Export all inlinks, outlinks, anchor text
4. Export all page titles and meta descriptions
5. Google Search Console: export all queries, pages, click data (16 months)
6. Google Business Profile: capture current NAP, categories, reviews
7. Record existing backlinks (Ahrefs, Semrush, or GSC)

### 11.2 Migration SEO Rules

| Rule | Specification |
|---|---|
| **No URL changes for working pages** | `/glasbewassing/`, `/gevelreiniging/`, `/vloeronderhoud/`, `/vve-service/`, `/oplevering-schoonmaak/`, `/industriele-schoonmaak/`, `/over-hds/`, `/kwaliteit-veiligheid/`, `/referenties/`, `/vacatures/`, `/downloads/`, `/winkel/`, `/product/*` — keep identical. |
| **Broken pages get new working URLs** | `/reguliere-schoonmaak/` and `/contact/` — build working pages at these exact URLs. No URL change. |
| **Redirections must return 301 (not 302)** | All redirects permanent. |
| **No redirect chains** | Each old URL to exactly one 301 to final destination. |
| **Canonical tags** | Self-referencing on all pages. |
| **Sitemap regeneration** | Submit new sitemap to GSC immediately after launch. Monitor for 30 days. |
| **Robots.txt** | `Allow: /`. Remove `crawl-delay: 5`. Disallow `/wp-admin/`, `/wp-json/` (except public), XML-RPC. |

### 11.3 Post-Migration SEO Monitoring (30 days)

- Daily: Check GSC for crawl errors and 404s
- Weekly: Compare indexed pages count to old baseline
- Weekly: Compare search query impressions to old baseline
- Weekly: Check all 301 redirects working
- Week 2: Submit all new URLs for indexing via GSC
- Week 4: Full audit — organic traffic, rankings, indexed pages vs pre-migration baseline
